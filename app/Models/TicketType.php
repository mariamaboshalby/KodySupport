<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TicketType extends Model
{
    protected $fillable = ['name', 'slug', 'expected_cost', 'is_active', 'sort_order'];

    protected $casts = [
        'expected_cost' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    // ── Boot: توليد slug تلقائيًا ────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (self $type) {
            if (empty($type->slug)) {
                $type->slug = static::generateSlug($type->name);
            }
        });
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name, '_') ?: Str::lower(preg_replace('/\s+/', '_', trim($name)));
        $slug = $base;
        $i    = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = "{$base}_{$i}";
            $i++;
        }
        return $slug;
    }

    // ── Relationships ─────────────────────────────────────────────────────────
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('name');
    }
}
