<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'name', 'company_name', 'phone', 'address',
        'visit_type', 'expected_cost', 'notes',
        'status', 'ticket_number', 'scheduled_at', 'assigned_to',
    ];

    protected $casts = [
        'scheduled_at'  => 'datetime',
        'expected_cost' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public static function generateTicketNumber(): string
    {
        $prefix = 'TKT-' . date('Ymd') . '-';
        $last   = static::where('ticket_number', 'like', $prefix . '%')
                        ->orderByDesc('id')
                        ->value('ticket_number');

        $seq = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'قيد الانتظار',
            'confirmed'   => 'تم التأكيد',
            'in_progress' => 'جاري التنفيذ',
            'completed'   => 'مكتمل',
            'cancelled'   => 'ملغي',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'     => '#f59e0b',
            'confirmed'   => '#22d3ee',
            'in_progress' => '#8b5cf6',
            'completed'   => '#10b981',
            'cancelled'   => '#ef4444',
            default       => '#7ba4c4',
        };
    }

    public function getVisitTypeLabelAttribute(): string
    {
        return match ($this->visit_type) {
            'technical_support' => 'دعم تقني',
            'consultation'      => 'استشارة',
            'installation'      => 'تركيب',
            'maintenance'       => 'صيانة',
            'training'          => 'تدريب',
            'other'             => 'أخرى',
            default             => $this->visit_type,
        };
    }
}
