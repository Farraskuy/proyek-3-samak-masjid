<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KotakAmal extends Model
{
    use HasFactory;

    protected $table = 'kotak_amal_collections';

    protected $fillable = [
        'box_name',
        'collection_date',
        'total_amount',
        'status',
        'officers',
        'details',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'total_amount' => 'decimal:2',
        'officers' => 'array',
        'details' => 'array',
    ];
}
