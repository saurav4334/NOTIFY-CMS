<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = ['section', 'content', 'sort_order', 'is_active'];

    protected $casts = [
        'content' => 'array',
        'is_active' => 'boolean',
    ];

    public static function content(string $section, mixed $default = null): mixed
    {
        return static::where('section', $section)->value('content') ?? $default;
    }
}
