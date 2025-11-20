<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'related_user_id',
        'type',
        'title',
        'message',
        'action_url',
        'related_id',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relatedUser()
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public static function createNotification($userId, $type, $title, $message, $actionUrl = null, $relatedId = null, $relatedUserId = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'related_id' => $relatedId,
            'related_user_id' => $relatedUserId,
        ]);
    }
}

