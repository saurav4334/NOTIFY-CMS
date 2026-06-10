<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page', 'title', 'description', 'keywords',
        'og_title', 'og_description', 'og_image', 'schema',
    ];

    protected $casts = ['schema' => 'array'];

    public static function forPage(string $page): ?self
    {
        return static::where('page', $page)->first();
    }
}
