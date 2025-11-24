<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTamu extends Model
{
    protected $table = 'event_tamu';
    protected $fillable = ['event_id', 'nama_tamu'];

    public function event()
    {
        return $this->belongsTo(JadwalKegiatan::class, 'event_id', 'event_id');
    }
}
