<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $fillable = [
        'key',
        'section',
        'label',
        'type',
        'content',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public static function get(string $key, mixed $default = null): ?string
    {
        return static::where('key', $key)->value('content') ?? $default;
    }

    public static function getBySection(string $section): array
    {
        return static::where('section', $section)
            ->pluck('content', 'key')
            ->toArray();
    }
}
