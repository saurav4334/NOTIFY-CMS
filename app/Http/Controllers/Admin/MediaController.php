<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private const DISK = 'public';
    private const ALLOWED = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'pdf'];
    private const MAX_KB = 5120; // 5 MB
    private const OPTIMIZABLE = ['jpg', 'jpeg', 'png', 'webp'];
    private const MAX_WIDTH = 1920; // px — downscale oversized uploads

    /** JSON list of media, optionally filtered by folder, plus the folder list. */
    public function index(Request $request): JsonResponse
    {
        $query = Media::query()->latest();

        if ($folder = $request->query('folder')) {
            $query->where('folder', $folder);
        }

        return response()->json([
            'media' => $query->limit(500)->get(),
            'folders' => Media::query()->distinct()->orderBy('folder')->pluck('folder'),
            'total' => Media::count(),
        ]);
    }

    /** Upload one or more files. */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'max:20'],
            'files.*' => ['file', 'mimes:'.implode(',', self::ALLOWED), 'max:'.self::MAX_KB],
            'folder' => ['nullable', 'string', 'max:64'],
        ]);

        $folder = Str::slug($request->input('folder', 'uploads')) ?: 'uploads';
        $saved = [];

        foreach ($request->file('files') as $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $stored = Str::slug($name).'-'.Str::random(6).'.'.$ext;
            $path = $file->storeAs($folder, $stored, self::DISK);

            $this->optimize($path, $ext);

            $saved[] = Media::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'disk' => self::DISK,
                'mime_type' => $file->getClientMimeType(),
                'extension' => $ext,
                'size' => Storage::disk(self::DISK)->size($path), // post-optimization size
                'folder' => $folder,
                'uploaded_by' => Auth::id(),
            ]);
        }

        ActivityLog::record('created', null, count($saved).' file(s) uploaded to media');

        return response()->json(['message' => count($saved).' file(s) uploaded.', 'media' => $saved], 201);
    }

    /**
     * Downscale oversized raster images and re-encode to save bandwidth, using
     * native GD. SVG/PDF are skipped. Best-effort: the original is kept on failure.
     */
    private function optimize(string $path, string $ext): void
    {
        if (! in_array($ext, self::OPTIMIZABLE, true) || ! function_exists('imagecreatetruecolor')) {
            return;
        }

        try {
            $absolute = Storage::disk(self::DISK)->path($path);
            $info = @getimagesize($absolute);
            if (! $info) {
                return;
            }
            [$width, $height] = $info;

            $src = match ($ext) {
                'png' => @imagecreatefrompng($absolute),
                'webp' => @imagecreatefromwebp($absolute),
                default => @imagecreatefromjpeg($absolute),
            };
            if (! $src) {
                return;
            }

            // Downscale only if wider than the cap; otherwise just re-encode to compress.
            if ($width > self::MAX_WIDTH) {
                $newW = self::MAX_WIDTH;
                $newH = (int) round($height * $newW / $width);
                $dst = imagecreatetruecolor($newW, $newH);
                if (in_array($ext, ['png', 'webp'], true)) {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);
                imagedestroy($src);
                $src = $dst;
            }

            match ($ext) {
                'png' => imagepng($src, $absolute, 6),
                'webp' => imagewebp($src, $absolute, 82),
                default => imagejpeg($src, $absolute, 82),
            };
            imagedestroy($src);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function destroy(Media $medium): JsonResponse
    {
        Storage::disk($medium->disk)->delete($medium->path);
        $medium->delete();
        ActivityLog::record('deleted', null, "Media #{$medium->id} deleted");

        return response()->json(['message' => 'File deleted.', 'total' => Media::count()]);
    }
}
