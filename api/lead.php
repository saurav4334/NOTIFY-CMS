<?php
/**
 * NotifyBD — lead capture endpoint.
 * =============================================================================
 * The ONLY server-side code on the site. Plain PHP, no framework, no database,
 * no Composer. Drop it on any cPanel host with PHP 7.4+.
 *
 * Accepts a POST from the contact form, validates it, stores it, and tries to
 * email it. If the email fails, the lead is still stored and the response says
 * so honestly — a lead is never silently dropped (which is exactly what the
 * mockup's fake form did to every submission).
 *
 * Configuration lives in api/.env (see api/.env.example). Nothing is hardcoded.
 * =============================================================================
 */

declare(strict_types=1);

/* ── Never leak warnings, paths or stack traces to the client ─────────────── */
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

const MAX_BODY_BYTES = 20000;   // reject oversized payloads outright
const MIN_FILL_SECONDS = 3;     // time-trap: humans are not this fast
const MAX_FORM_AGE_SECONDS = 7200; // stale form → make them reload

/* =============================================================================
   Bootstrap
   ============================================================================= */

/** Minimal .env reader. Returns [] when the file is absent. */
function load_env(string $path): array
{
    if (!is_readable($path)) {
        return [];
    }
    $env = [];
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $value = trim($value);
        // Strip optional surrounding quotes.
        if (strlen($value) >= 2
            && (($value[0] === '"' && substr($value, -1) === '"')
             || ($value[0] === "'" && substr($value, -1) === "'"))) {
            $value = substr($value, 1, -1);
        }
        $env[trim($key)] = $value;
    }
    return $env;
}

$env = load_env(__DIR__ . '/.env');

function cfg(array $env, string $key, string $default = ''): string
{
    return isset($env[$key]) && $env[$key] !== '' ? $env[$key] : $default;
}

/**
 * Storage lives OUTSIDE the document root when possible, so leads can never be
 * fetched over HTTP. LEAD_STORAGE_PATH should point somewhere like
 * /home/<cpaneluser>/notifybd-storage. The in-tree fallback is additionally
 * protected by api/.htaccess and a deny rule in the root .htaccess.
 */
$storageDir = cfg($env, 'LEAD_STORAGE_PATH', __DIR__ . '/storage');
$logFile    = rtrim($storageDir, '/\\') . '/error.log';

/** Appends to the private error log. Never echoed to the client. */
function log_error(string $logFile, string $message): void
{
    $dir = dirname($logFile);
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    @file_put_contents(
        $logFile,
        sprintf("[%s] %s\n", gmdate('c'), $message),
        FILE_APPEND | LOCK_EX
    );
}

/** Sends a JSON response and stops. */
function respond(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/* Turn any fatal/unexpected error into a clean 500 with no path disclosure. */
set_exception_handler(function ($e) use ($logFile) {
    log_error($logFile, 'Uncaught: ' . $e->getMessage());
    respond(500, [
        'ok' => false,
        'message' => 'Something went wrong on our end. Please try again shortly.',
    ]);
});

/* =============================================================================
   CORS — same-origin only, restricted to the configured site origin.
   ============================================================================= */
$allowedOrigins = array_filter(array_map(
    'trim',
    explode(',', cfg($env, 'ALLOWED_ORIGINS', 'https://notifybd.com,https://www.notifybd.com'))
));

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    if (!in_array($origin, $allowedOrigins, true)) {
        respond(403, ['ok' => false, 'message' => 'Origin not allowed.']);
    }
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Accept');
    header('Access-Control-Max-Age: 600');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/* ── POST only ────────────────────────────────────────────────────────────── */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Method not allowed.']);
}

/* =============================================================================
   Rate limiting — per IP, file-based (no database available).
   ============================================================================= */
function client_ip(): string
{
    // REMOTE_ADDR only. X-Forwarded-For is client-controlled and trusting it
    // would let anyone bypass the rate limit by spoofing a header.
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

function rate_limit_exceeded(string $storageDir, string $ip, int $max, int $windowSeconds): bool
{
    $dir = rtrim($storageDir, '/\\') . '/ratelimit';
    if (!is_dir($dir) && !@mkdir($dir, 0750, true) && !is_dir($dir)) {
        return false; // storage unavailable → fail open rather than block real leads
    }

    $file = $dir . '/' . hash('sha256', $ip) . '.json';
    $now = time();

    $hits = [];
    if (is_readable($file)) {
        $decoded = json_decode((string) file_get_contents($file), true);
        if (is_array($decoded)) {
            $hits = $decoded;
        }
    }

    // Drop timestamps outside the window.
    $hits = array_values(array_filter($hits, static fn($t) => is_int($t) && ($now - $t) < $windowSeconds));

    if (count($hits) >= $max) {
        return true;
    }

    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);

    // Opportunistic cleanup so the directory cannot grow without bound.
    if (random_int(1, 50) === 1) {
        foreach (glob($dir . '/*.json') ?: [] as $old) {
            if (@filemtime($old) < $now - ($windowSeconds * 4)) {
                @unlink($old);
            }
        }
    }

    return false;
}

$maxPerWindow = (int) cfg($env, 'RATE_LIMIT_MAX', '5');
$window       = (int) cfg($env, 'RATE_LIMIT_WINDOW', '3600');
$ip           = client_ip();

if (rate_limit_exceeded($storageDir, $ip, $maxPerWindow, $window)) {
    respond(429, [
        'ok' => false,
        'message' => 'You have sent several messages recently. Please try again later, or contact us directly.',
    ]);
}

/* =============================================================================
   Input — accepts both application/json and form-data.
   ============================================================================= */
$contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
$input = [];

if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input', false, null, 0, MAX_BODY_BYTES + 1);
    if ($raw === false || strlen($raw) > MAX_BODY_BYTES) {
        respond(413, ['ok' => false, 'message' => 'Your message is too large.']);
    }
    $decoded = json_decode($raw, true);
    $input = is_array($decoded) ? $decoded : [];
} else {
    $input = $_POST;
}

/** Trim, cap length, and strip control characters (incl. CR/LF). */
function clean(array $input, string $key, int $maxLen = 500): string
{
    $value = $input[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    // Removing CR and LF here is what neutralises header-injection attempts
    // such as "name\r\nBcc: victim@example.com" before the value ever reaches
    // a mail header.
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? '';
    return mb_substr(trim($value), 0, $maxLen);
}

$name    = clean($input, 'name', 100);
$phone   = clean($input, 'phone', 20);
$email   = clean($input, 'email', 254);
$company = clean($input, 'company', 120);
$volume  = clean($input, 'volume', 40);
$smsType = clean($input, 'sms_type', 40);
$message = clean($input, 'message', 2000);
$honeypot = clean($input, 'website', 100);
$startedAt = (int) ($input['started_at'] ?? 0);

/* ── Bot traps ────────────────────────────────────────────────────────────────
   Both respond with a normal-looking success so a bot cannot learn what tripped
   it. The submission is discarded and logged. */
if ($honeypot !== '') {
    log_error($logFile, "Honeypot triggered from {$ip}");
    respond(200, ['ok' => true, 'message' => 'Thank you. We have received your message.']);
}

$elapsed = $startedAt > 0 ? (time() - (int) round($startedAt / 1000)) : PHP_INT_MAX;
if ($startedAt > 0 && $elapsed < MIN_FILL_SECONDS) {
    log_error($logFile, "Time-trap triggered from {$ip} ({$elapsed}s)");
    respond(200, ['ok' => true, 'message' => 'Thank you. We have received your message.']);
}
if ($startedAt > 0 && $elapsed > MAX_FORM_AGE_SECONDS) {
    respond(422, [
        'ok' => false,
        'message' => 'This form has been open for a while. Please reload the page and try again.',
    ]);
}

/* =============================================================================
   Validation — the server is authoritative; the client checks are a courtesy.
   ============================================================================= */
$errors = [];

if (mb_strlen($name) < 2) {
    $errors['name'] = 'Enter your full name (at least 2 characters).';
}

/** Bangladeshi mobile: optional +880/880/0 prefix, then 1[3-9] + 8 digits. */
$normalisedPhone = preg_replace('/[\s\-()]/', '', $phone) ?? '';
if (!preg_match('/^(?:\+?880|0)1[3-9]\d{8}$/', $normalisedPhone)) {
    $errors['phone'] = 'Enter a valid Bangladeshi mobile number, e.g. 01712345678.';
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address, or leave the field empty.';
}

if (mb_strlen($message) < 10) {
    $errors['message'] = 'Tell us a little more (at least 10 characters).';
}

if ($errors) {
    respond(422, [
        'ok' => false,
        'message' => 'Please correct the highlighted fields.',
        'errors' => $errors,
    ]);
}

/* =============================================================================
   Store the lead FIRST. Email is best-effort; storage is not.
   ============================================================================= */
$lead = [
    'received_at' => gmdate('c'),
    'name'        => $name,
    'phone'       => $normalisedPhone,
    'email'       => $email,
    'company'     => $company,
    'volume'      => $volume,
    'sms_type'    => $smsType,
    'message'     => $message,
    'ip'          => $ip,
    'user_agent'  => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200),
];

$stored = false;
$leadsDir = rtrim($storageDir, '/\\') . '/leads';

if (is_dir($leadsDir) || @mkdir($leadsDir, 0750, true)) {
    // One JSONL file per month — easy to read, cheap to append, no DB needed.
    $leadFile = $leadsDir . '/leads-' . gmdate('Y-m') . '.jsonl';
    $line = json_encode($lead, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    $stored = @file_put_contents($leadFile, $line, FILE_APPEND | LOCK_EX) !== false;
}

if (!$stored) {
    log_error($logFile, 'Failed to write lead to storage: ' . $leadsDir);
}

/* =============================================================================
   Email notification (best effort).
   ============================================================================= */
$to      = cfg($env, 'LEAD_TO_EMAIL');
$from    = cfg($env, 'LEAD_FROM_EMAIL');
$subject = cfg($env, 'LEAD_SUBJECT', 'New enquiry from notifybd.com');

$emailed = false;

if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL) && function_exists('mail')) {
    // Every header value here is either from .env (trusted) or has already had
    // CR/LF stripped by clean(), so header injection is not possible.
    $fromAddress = ($from !== '' && filter_var($from, FILTER_VALIDATE_EMAIL))
        ? $from
        : 'no-reply@' . preg_replace('/[^a-z0-9.\-]/i', '', $_SERVER['HTTP_HOST'] ?? 'notifybd.com');

    $headers = [
        'From: NotifyBD Website <' . $fromAddress . '>',
        'Content-Type: text/plain; charset=utf-8',
        'MIME-Version: 1.0',
        'X-Mailer: NotifyBD-Lead',
    ];
    // Only set Reply-To when the visitor gave a valid address.
    if ($email !== '') {
        $headers[] = 'Reply-To: ' . $email;
    }

    $body = "New enquiry from the NotifyBD website\n"
        . str_repeat('-', 44) . "\n"
        . "Name:       {$name}\n"
        . "Mobile:     {$normalisedPhone}\n"
        . 'Email:      ' . ($email !== '' ? $email : '—') . "\n"
        . 'Company:    ' . ($company !== '' ? $company : '—') . "\n"
        . 'Volume:     ' . ($volume !== '' ? $volume : '—') . "\n"
        . 'SMS type:   ' . ($smsType !== '' ? $smsType : '—') . "\n"
        . "Received:   {$lead['received_at']} UTC\n\n"
        . "Message:\n{$message}\n";

    $emailed = @mail($to, $subject, $body, implode("\r\n", $headers));

    if (!$emailed) {
        log_error($logFile, 'mail() failed for lead from ' . $normalisedPhone);
    }
}

/* =============================================================================
   Truthful response.
   ============================================================================= */
if ($stored || $emailed) {
    respond(200, [
        'ok' => true,
        'message' => 'Thank you. We have received your message and will get back to you.',
    ]);
}

// Nothing worked: we could neither store nor send. Say so — do not pretend.
log_error($logFile, 'Lead LOST (no storage, no mail) from ' . $normalisedPhone);
respond(500, [
    'ok' => false,
    'message' => 'We could not record your message. Please contact us directly so your enquiry is not lost.',
]);
