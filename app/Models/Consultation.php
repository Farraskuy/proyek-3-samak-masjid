<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';
    protected $primaryKey = 'consultation_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'question_subject',
        'question_text',
        'question_from',
        'answer_text',
        'rejection_reason',
        'conclusion',
        'status',
        'is_anonymous',
        'inputted_by_admin_id',
        'answered_by_ustadz_id',
        'created_at',
        'answered_at',
        'closed_at',
        'published_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'answered_at' => 'datetime',
        'closed_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function inputter()
    {
        return $this->belongsTo(User::class, 'inputted_by_admin_id', 'id');
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by_ustadz_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class, 'consultation_id', 'id');
    }
}

