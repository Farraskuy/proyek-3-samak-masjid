<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostAndFoundItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'inputted_by_admin_id',
        'item_name',
        'description',
        'location_found',
        'featured_image_url',
        'status',
        'retrieved_by_name',
        'retrieved_at',
    ];

    protected $casts = [
        'retrieved_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}