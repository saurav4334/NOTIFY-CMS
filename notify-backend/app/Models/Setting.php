<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    protected $casts = ['value' => 'array'];

    /** Fetch a single setting value by group + key. */
    public static function value(string $group, string $key, mixed $default = null): mixed
    {
        return static::where('group', $group)->where('key', $key)->value('value') ?? $default;
    }

    /** Return a whole group as a flat key => value array. */
    public static function group(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    /** Create or update a setting. */
    public static function put(string $group, string $key, mixed $value): self
    {
        return static::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value],
        );
    }
}
