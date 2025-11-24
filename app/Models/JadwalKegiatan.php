<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKegiatan extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $primaryKey = 'event_id';
    public $timestamps = false;

    protected $fillable = [
        'event_name',
        'theme',
        'poster',
        'start_time',
        'end_time',
        'location',
        'is_recurring',
        'requires_registration',
        'created_by',
        'created_at',
        'is_have_tamu_undangan',
    ];

    // Relasi ke user yang membuat kegiatan (admin pembuat)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke tamu undangan
    public function tamuUndangan()
    {
        return $this->hasMany(\App\Models\EventTamu::class, 'event_id', 'event_id');
    }
}
