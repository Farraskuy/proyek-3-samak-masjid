<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_accounts';
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
        'logo_url',
        'category',
        'type',
        'is_deletable',
        'is_active'
        // Note: 'balance' is NOT fillable - must use dedicated methods
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    /**
     * Scope for zakat banks only
     */
    public function scopeZakat($query)
    {
        return $query->where('category', 'zakat');
    }

    /**
     * Scope for infaq banks only
     */
    public function scopeInfaq($query)
    {
        return $query->where('category', 'infaq');
    }

    /**
     * Scope for active banks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if bank can be deleted
     */
    public function canBeDeleted(): bool
    {
        return $this->is_deletable && $this->balance <= 0;
    }

    /**
     * Add balance (only via donation approval)
     */
    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    /**
     * Subtract balance (only via expense recording)
     */
    public function subtractBalance(float $amount): void
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
        }
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }
}