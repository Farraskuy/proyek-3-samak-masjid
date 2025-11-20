<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationConfirmation extends Model
{
    use HasFactory;

    protected $table = 'donation_confirmations';
    protected $primaryKey = 'confirmation_id';

    protected $fillable = [
        'user_id',
        'guest_name',
        'amount',
        'transfer_date',
        'destination_account_id',
        'source_bank',
        'proof_image_url',
        'notes',
        'status',
        'verified_by',
        'verified_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(BankAccount::class, 'destination_account_id', 'account_id');
    }
   
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}