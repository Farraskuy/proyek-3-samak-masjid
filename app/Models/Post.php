<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'post_id',
        'user_id',//nanti diperbaiki
        'title',
        'slug',
        'content', //nanti
        'featured_image_url',//untuk nama image + path
        'created_at',
        'published_at',
        'status',
        'keterangan',
        'kategori'
    ];
}
