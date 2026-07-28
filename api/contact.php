<?php
/**
 * Notify — contact / lead endpoint with SMTP email (PHPMailer).
 * =============================================================================
 * Receives the contact form (POST, JSON or form-data), validates and stores the
 * lead in a private file OUTSIDE the web root, then emails sales@notifybd.com
 * over authenticated SMTP. If the email fails the lead is still saved and the
 * response is truthful.
 *
 * All configuration (SMTP + recipients) is read from api/.env — nothing is
 * hardcoded and no credential is ever sent to or from the browser.
 *
 * PHPMailer is vendored under api/lib/PHPMailer/ (no Composer required on the
 * shared host).
 * =============================================================================
 */

declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as MailException;

/* ── Never leak warnings, paths or stack traces to the client ─────────────── */
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

const MAX_BODY_BYTES = 20000;
const MIN_FILL_SECONDS = 3;        // time-trap: humans are not this fast
const MAX_FORM_AGE_SECONDS = 7200; // stale form → make them reload
const MAX = [                      // per-field maximum lengths
    'name' => 100, 'company' => 120, 'phone' => 20, 'email' => 254,
    'service' => 60, 'message' => 2000,
    'utm_source' => 120, 'utm_medium' => 120, 'utm_campaign' => 150,
    'referrer' => 400, 'source_url' => 400,
];

/* =============================================================================
   Bootstrap
   ============================================================================= */
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

/* Storage lives OUTSIDE the document root when configured, so leads can never
   be fetched over HTTP. */
$storageDir = cfg($env, 'LEAD_STORAGE_PATH', __DIR__ . '/storage');
$logFile    = rtrim($storageDir, '/\\') . '/error.log';

function log_error(string $logFile, string $message): void
{
    $dir = dirname($logFile);
    if (!is_dir($dir)) {
        @mkdir($dir, 0750, true);
    }
    @file_put_contents($logFile, sprintf("[%s] %s\n", gmdate('c'), $message), FILE_APPEND | LOCK_EX);
}

function respond(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

set_exception_handler(function ($e) use ($logFile) {
    log_error($logFile, 'Uncaught: ' . $e->getMessage());
    respond(500, ['success' => false, 'message' => 'Something went wrong on our end. Please try again shortly.']);
});

/* =============================================================================
   CORS — restricted to the configured site origin(s).
   ============================================================================= */
$allowedOrigins = array_filter(array_map(
    'trim',
    explode(',', cfg($env, 'ALLOWED_ORIGINS', 'https://notifybd.com,https://www.notifybd.com'))
));

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin !== '') {
    if (!in_array($origin, $allowedOrigins, true)) {
        respond(403, ['success' => false, 'message' => 'Origin not allowed.']);
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
    respond(405, ['success' => false, 'message' => 'Method not allowed.']);
}

/* =============================================================================
   Rate limiting — per IP, file-based.
   ============================================================================= */
function client_ip(): string
{
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
    $hits = array_values(array_filter($hits, static fn($t) => is_int($t) && ($now - $t) < $windowSeconds));
    if (count($hits) >= $max) {
        return true;
    }
    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);
    if (random_int(1, 50) === 1) {
        foreach (glob($dir . '/*.json') ?: [] as $old) {
            if (@filemtime($old) < $now - ($windowSeconds * 4)) {
                @unlink($old);
            }
        }
    }
    return false;
}

$ip = client_ip();
if (rate_limit_exceeded($storageDir, $ip, (int) cfg($env, 'RATE_LIMIT_MAX', '5'), (int) cfg($env, 'RATE_LIMIT_WINDOW', '3600'))) {
    respond(429, ['success' => false, 'message' => 'You have sent several messages recently. Please try again later, or contact us directly.']);
}

/* =============================================================================
   Input — accepts application/json and form-data.
   ============================================================================= */
$contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
$input = [];
if (strpos($contentType, 'application/json') !== false) {
    $raw = file_get_contents('php://input', false, null, 0, MAX_BODY_BYTES + 1);
    if ($raw === false || strlen($raw) > MAX_BODY_BYTES) {
        respond(413, ['success' => false, 'message' => 'Your message is too large.']);
    }
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        respond(400, ['success' => false, 'message' => 'Malformed request.']);
    }
    $input = $decoded;
} else {
    $input = $_POST;
}

/** Trim, cap length, strip control characters (incl. CR/LF → header-injection defence). */
function clean(array $input, string $key, int $maxLen): string
{
    $value = $input[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value) ?? '';
    return mb_substr(trim($value), 0, $maxLen);
}

$name     = clean($input, 'name', MAX['name']);
$company  = clean($input, 'company', MAX['company']);
$phone    = clean($input, 'phone', MAX['phone']);
$email    = clean($input, 'email', MAX['email']);
// "service" is the interested service; fall back to the form's sms_type/volume.
$service  = clean($input, 'service', MAX['service']);
if ($service === '') {
    $service = clean($input, 'sms_type', MAX['service']);
}
$message  = clean($input, 'message', MAX['message']);
$utmSource   = clean($input, 'utm_source', MAX['utm_source']);
$utmMedium   = clean($input, 'utm_medium', MAX['utm_medium']);
$utmCampaign = clean($input, 'utm_campaign', MAX['utm_campaign']);
$referrer    = clean($input, 'referrer', MAX['referrer']);
$sourceUrl   = clean($input, 'source_url', MAX['source_url']);
$honeypot    = clean($input, 'website', 100);
$startedAt   = (int) ($input['started_at'] ?? 0);

/* ── Bot traps — respond like success so a bot learns nothing ────────────── */
if ($honeypot !== '') {
    log_error($logFile, "Honeypot triggered from {$ip}");
    respond(200, ['success' => true, 'message' => 'Thank you. Our sales team will contact you shortly.']);
}
$elapsed = $startedAt > 0 ? (time() - (int) round($startedAt / 1000)) : PHP_INT_MAX;
if ($startedAt > 0 && $elapsed < MIN_FILL_SECONDS) {
    log_error($logFile, "Time-trap triggered from {$ip} ({$elapsed}s)");
    respond(200, ['success' => true, 'message' => 'Thank you. Our sales team will contact you shortly.']);
}
if ($startedAt > 0 && $elapsed > MAX_FORM_AGE_SECONDS) {
    respond(422, ['success' => false, 'message' => 'This form has been open for a while. Please reload the page and try again.']);
}

/* =============================================================================
   Validation — the server is authoritative.
   ============================================================================= */
$errors = [];
if (mb_strlen($name) < 2) {
    $errors['name'] = 'Enter your full name (at least 2 characters).';
}
$normalisedPhone = preg_replace('/[\s\-()]/', '', $phone) ?? '';
if (!preg_match('/^(?:\+?880|0)1[3-9]\d{8}$/', $normalisedPhone)) {
    $errors['phone'] = 'Enter a valid Bangladeshi mobile number, e.g. 01712345678.';
}
$emailValid = ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
if ($email !== '' && !$emailValid) {
    $errors['email'] = 'Enter a valid email address, or leave the field empty.';
}
if (mb_strlen($message) < 10) {
    $errors['message'] = 'Tell us a little more (at least 10 characters).';
}
if ($errors) {
    respond(422, ['success' => false, 'message' => 'Please check the required fields.', 'errors' => $errors]);
}

/* =============================================================================
   Save the lead FIRST (before email). Storage is not optional; email is best-effort.
   ============================================================================= */
$dhaka = new DateTime('now', new DateTimeZone('Asia/Dhaka'));
$lead = [
    'received_at'  => $dhaka->format('Y-m-d H:i:s'),
    'received_utc' => gmdate('c'),
    'name'         => $name,
    'company'      => $company,
    'phone'        => $normalisedPhone,
    'email'        => $email,
    'service'      => $service,
    'message'      => $message,
    'source_url'   => $sourceUrl,
    'referrer'     => $referrer,
    'utm_source'   => $utmSource,
    'utm_medium'   => $utmMedium,
    'utm_campaign' => $utmCampaign,
    'ip'           => $ip,
    'user_agent'   => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200),
];

$stored = false;
$leadsDir = rtrim($storageDir, '/\\') . '/leads';
if (is_dir($leadsDir) || @mkdir($leadsDir, 0750, true)) {
    $leadFile = $leadsDir . '/leads-' . $dhaka->format('Y-m') . '.jsonl';
    $line = json_encode($lead, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
    $stored = @file_put_contents($leadFile, $line, FILE_APPEND | LOCK_EX) !== false;
}
if (!$stored) {
    log_error($logFile, 'Failed to write lead to storage: ' . $leadsDir);
}

/* =============================================================================
   Send via authenticated SMTP (PHPMailer).
   ============================================================================= */
require __DIR__ . '/lib/PHPMailer/Exception.php';
require __DIR__ . '/lib/PHPMailer/PHPMailer.php';
require __DIR__ . '/lib/PHPMailer/SMTP.php';

$emailed = false;

$smtpHost = cfg($env, 'SMTP_HOST');
$smtpUser = cfg($env, 'SMTP_USERNAME');
$smtpPass = cfg($env, 'SMTP_PASSWORD');
$toEmail  = cfg($env, 'LEAD_TO_EMAIL', 'sales@notifybd.com');
$fromAddr = cfg($env, 'LEAD_FROM_EMAIL', $smtpUser);
$fromName = cfg($env, 'LEAD_FROM_NAME', 'Notify Website');

if ($smtpHost !== '' && $smtpUser !== '' && $smtpPass !== '' && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $enc = strtolower(cfg($env, 'SMTP_ENCRYPTION', 'ssl'));
        $mail->SMTPSecure = $enc === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int) cfg($env, 'SMTP_PORT', '465');
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 15;

        $mail->setFrom($fromAddr, $fromName);
        $mail->addAddress($toEmail);
        if ($emailValid) {
            $mail->addReplyTo($email, $name);
        }

        $serviceLabel = $service !== '' ? $service : 'General enquiry';
        $mail->Subject = sprintf('New Notify Website Lead — %s — %s', $name, $serviceLabel);

        $lines = [
            'New lead from the Notify website',
            str_repeat('=', 48),
            'Name:              ' . $name,
            'Company:           ' . ($company !== '' ? $company : '—'),
            'Phone:             ' . $normalisedPhone,
            'Email:             ' . ($email !== '' ? $email : '—'),
            'Interested service:' . ' ' . $serviceLabel,
            '',
            'Message:',
            $message,
            '',
            str_repeat('-', 48),
            'Source page:       ' . ($sourceUrl !== '' ? $sourceUrl : '—'),
            'Submitted (Dhaka): ' . $lead['received_at'] . ' (Asia/Dhaka)',
            'UTM source:        ' . ($utmSource !== '' ? $utmSource : '—'),
            'UTM medium:        ' . ($utmMedium !== '' ? $utmMedium : '—'),
            'UTM campaign:      ' . ($utmCampaign !== '' ? $utmCampaign : '—'),
            'Referrer:          ' . ($referrer !== '' ? $referrer : '—'),
            'IP address:        ' . $ip,
        ];
        $mail->Body = implode("\n", $lines);

        $mail->send();
        $emailed = true;
    } catch (MailException $e) {
        // ErrorInfo is safe (PHPMailer-composed); never echo it to the client.
        log_error($logFile, 'SMTP send failed: ' . $mail->ErrorInfo);
    } catch (\Throwable $e) {
        log_error($logFile, 'SMTP exception: ' . $e->getMessage());
    }
} else {
    log_error($logFile, 'SMTP not configured (missing host/user/pass) — lead stored only.');
}

/* =============================================================================
   Truthful response.
   ============================================================================= */
if ($stored || $emailed) {
    respond(200, ['success' => true, 'message' => 'Thank you. Our sales team will contact you shortly.']);
}

log_error($logFile, 'Lead LOST (no storage, no mail) from ' . $normalisedPhone);
respond(500, ['success' => false, 'message' => 'We could not record your message. Please email sales@notifybd.com directly so your enquiry is not lost.']);
