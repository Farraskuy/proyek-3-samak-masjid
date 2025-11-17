<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LostItemPhoto extends Model
{
    use HasFactory;

    protected $primaryKey = 'photo_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null;

    protected $fillable = [
        'item_id',
        'image_url',
        'caption',
        'uploaded_by_admin_id',
    ];

    public function item()
    {
        return $this->belongsTo(LostAndFoundItem::class, 'item_id', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by_admin_id');
    }
}
