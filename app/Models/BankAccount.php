<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $table = 'bank_accounts';
    protected $primaryKey =  'account_id';
    public $timestamps = false;
    
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
        'logo_url',
        'is_active'
    ];
}