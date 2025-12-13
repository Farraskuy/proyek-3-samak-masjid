<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enabled',
        'frequency',
        'tables',
        'output_format',
        'last_run_at',
        'next_run_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'tables' => 'array',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    /**
     * Calculate next run timestamp based on frequency
     */
    public function calculateNextRun(): \Carbon\Carbon
    {
        $now = now();
        
        return match($this->frequency) {
            'daily' => $now->addDay(),
            'weekly' => $now->addWeek(),
            'monthly' => $now->addMonth(),
            'yearly' => $now->addYear(),
            default => $now->addWeek(),
        };
    }
}
