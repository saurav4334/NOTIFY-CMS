<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SmsRate extends Model
{
    protected $fillable = [
        'type', 'tier', 'min_qty', 'max_qty', 'price', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'min_qty' => 'integer',
        'max_qty' => 'integer',
        'price' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /** Resolve the per-SMS price for a given quantity and masking type. */
    public static function priceFor(string $type, int $quantity): ?float
    {
        $rate = static::active()->ofType($type)
            ->where('min_qty', '<=', $quantity)
            ->where(fn ($q) => $q->whereNull('max_qty')->orWhere('max_qty', '>=', $quantity))
            ->orderByDesc('min_qty')
            ->first();

        return $rate ? (float) $rate->price : null;
    }
}
