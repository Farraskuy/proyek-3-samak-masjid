<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $table = 'financial_transactions';

    protected $fillable = [
        'type',
        'amount',
        'bank_name',
        'category',
        'description',
        'transaction_date',
        'proof_image_url',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}