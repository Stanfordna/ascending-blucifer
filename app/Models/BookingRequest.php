<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'service_id',
        'service_other',
        'preferred_date_1',
        'preferred_time_1',
        'preferred_date_2',
        'preferred_time_2',
        'message',
        'is_new_client',
        'status',
        'confirmed_at',
        'scheduled_datetime',
        'admin_notes',
        'honeypot_field',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date_1' => 'date',
            'preferred_date_2' => 'date',
            'is_new_client' => 'boolean',
            'confirmed_at' => 'datetime',
            'scheduled_datetime' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('status', 'confirmed');
    }

    public function confirm(?string $scheduledDatetime = null): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
            'scheduled_datetime' => $scheduledDatetime,
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function isSpam(): bool
    {
        return !empty($this->honeypot_field);
    }
}
