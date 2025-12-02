<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\FoundItem;
use App\Models\User;

class FoundItemPhoto extends Model
{
    use HasFactory;

    protected $table = 'found_item_photos';
    protected $primaryKey = 'photo_id';

    const UPDATED_AT = null;

    protected $fillable = [
        'found_item_id',
        'image_url',
        'caption',
        'uploaded_by_admin_id',
    ];

    public function foundItem()
    {
        return $this->belongsTo(FoundItem::class, 'found_item_id', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by_admin_id');
    }
}
