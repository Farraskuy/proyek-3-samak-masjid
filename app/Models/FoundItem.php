<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FoundItem extends Model
{
    use HasFactory;

    protected $table = 'lost_and_found_items';
    protected $primaryKey = 'item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'inputted_by_user_id',
        'item_name',
        'description',
        'location_found',
        'status',
        'retrieved_by_name',
        'retrieved_at',
        'category',
    ];

    protected $casts = [
        'retrieved_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'inputted_by_user_id');
    }

    public function photos()
    {
        return $this->hasMany(FoundItemPhoto::class, 'found_item_id', 'item_id');
    }
}
