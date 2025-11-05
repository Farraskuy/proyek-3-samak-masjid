<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationConfirmation extends Model
{
    use HasFactory;

    protected $table = 'donation_confirmations';
    protected $primaryKey = 'confirmation_id';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'amount',
        'transfer_date',
        'destination_account_id',
        'source_bank',
        'proof_image_url',
        'notes',
        'status',
        'verified_by',
        'verified_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'destionation_account_id', 'account_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

}