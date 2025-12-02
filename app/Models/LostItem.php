<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemCategory;

class LostItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_by_admin_id',
        'category_id',
        'item_name',
        'description',
        'location_lost',
        'lost_at',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'lost_at' => 'datetime',
        'expiry_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function photos()
    {
        return $this->hasMany(LostItemPhoto::class, 'lost_item_id');
    }
}
