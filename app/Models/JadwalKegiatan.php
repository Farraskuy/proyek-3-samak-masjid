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
        'has_registration_form',
        'registration_form_id',
        'has_closing_form',
        'closing_form_id',
        'questionnaire_enabled',
        'registration_enabled',
        'event_started',
        'has_pj',
        'pj_user_id',
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

    // Relasi ke form pendaftaran
    public function registrationForm()
    {
        return $this->belongsTo(Form::class, 'registration_form_id');
    }

    // Relasi ke form penutupan/kuisioner
    public function closingForm()
    {
        return $this->belongsTo(Form::class, 'closing_form_id');
    }

    // Relasi ke penanggung jawab
    public function pjUser()
    {
        return $this->belongsTo(User::class, 'pj_user_id');
    }
}
