<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'name', 'path', 'disk', 'mime_type', 'extension', 'size', 'folder', 'uploaded_by',
    ];

    protected $casts = ['size' => 'integer'];

    protected $appends = ['url'];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): string
    {
        // Root-relative so it resolves correctly on any host (localhost, 127.0.0.1:8000, prod).
        if ($this->disk === 'public') {
            return '/storage/'.ltrim($this->path, '/');
        }
        return Storage::disk($this->disk)->url($this->path);
    }
}
