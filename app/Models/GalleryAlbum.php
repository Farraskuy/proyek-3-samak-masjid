<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasFactory;

    protected $table = 'gallery_albums';
    protected $primaryKey = 'album_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'album_name',
        'description',
        'created_by',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function photos()
    {
        return $this->hasMany(GalleryPhoto::class, 'album_id', 'album_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function cover()
    {
        return $this->hasOne(GalleryPhoto::class, 'album_id', 'album_id')
                    ->where('caption', 'Cover Album');
    }
    
}
