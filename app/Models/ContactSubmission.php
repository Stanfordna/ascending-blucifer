<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'honeypot_field',
        'submission_time_seconds',
        'status',
        'read_at',
        'responded_at',
        'admin_notes',
        'ip_address',
        'user_agent',
        'referrer',
    ];

    protected function casts(): array
    {
        return [
            'submission_time_seconds' => 'integer',
            'read_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    public function markAsRead(): void
    {
        $this->update([
            'status' => 'read',
            'read_at' => now(),
        ]);
    }

    public function markAsResponded(): void
    {
        $this->update([
            'status' => 'responded',
            'responded_at' => now(),
        ]);
    }

    public function archive(): void
    {
        $this->update(['status' => 'archived']);
    }

    public function isSpam(): bool
    {
        if (!empty($this->honeypot_field)) {
            return true;
        }

        if ($this->submission_time_seconds !== null && $this->submission_time_seconds < 3) {
            return true;
        }

        return false;
    }
}
