<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image_url',
        'updated_by_admin',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        
    ];

    public function updatedByAdmin()
    {
        return $this->belongsTo(User::class, 'updated_by_admin');
    }
}
