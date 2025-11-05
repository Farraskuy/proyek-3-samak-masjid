<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'user_id',//nanti diperbaiki
        'title',
        'slug',
        'content', //nanti 
        'keterangan',
        'featured_image_url',//untuk nama image + path
        'created_at',
        'published_at',
        'status'
    ];
}
