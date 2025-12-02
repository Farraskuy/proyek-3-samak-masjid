<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Postingan extends Model
{
    use HasFactory;



    protected $casts = [
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // include approval fields
    // protected $attributes = [
    //     'approval_status' => 'pending',
    // ];

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'slug',
        'content',
        'featured_image_url',
        'created_at',
        'published_at',
        'status',
        'keterangan',
        'kategori',
        'approval_note',
        'approved_by',
        'approved_at'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
