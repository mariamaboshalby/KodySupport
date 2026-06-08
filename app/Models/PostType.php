<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostType extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'is_default', 'sort_order'];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (PostType $type) {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
