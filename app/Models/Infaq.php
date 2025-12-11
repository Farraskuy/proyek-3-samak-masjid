<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Infaq extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'infaqs';

    protected $fillable = [
        'name',
        'poster_url',
        'description',
        'bank_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Related bank account (infaq type only)
     */
    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'account_id');
    }

    /**
     * Scope for active infaq programs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
