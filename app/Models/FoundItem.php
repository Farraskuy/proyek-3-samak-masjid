<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD

class FoundItem extends Model
{
    /** @use HasFactory<\Database\Factories\FoundItemFactory> */
    use HasFactory;
=======
use App\Models\User;
use App\Models\FoundItemPhoto;

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
<<<<<<<< HEAD:app/Models/LostAndFoundItem.php
        return $this->belongsTo(\App\Models\User::class, 'inputted_by_user_id');
========
        return $this->belongsTo(User::class, 'inputted_by_admin_id');
>>>>>>>> refs/remotes/origin/master:app/Models/FoundItem.php
    }

    public function photos()
    {
        return $this->hasMany(FoundItemPhoto::class, 'found_item_id', 'item_id');
    }
>>>>>>> refs/remotes/origin/master
}
