<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = [
        'name', 'role', 'text', 'rating', 'initials', 'bg', 'color',
        'avatar', 'is_featured', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
