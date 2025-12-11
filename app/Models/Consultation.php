<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConsultationMessage;

class Consultation extends Model
{
    use HasFactory;

    protected $table = 'consultations';
    protected $primaryKey = 'id';
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
        'user_id',
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
        'is_anonymous' => 'boolean',
    ];

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ustadz()
    {
        return $this->belongsTo(User::class, 'answered_by_ustadz_id');
    }

    public function messages()
    {
        return $this->hasMany(ConsultationMessage::class);
    }

    // Deprecated relationships (kept for backward compatibility if needed, but better to remove)
    public function inputter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answerer()
    {
        return $this->belongsTo(User::class, 'answered_by_ustadz_id');
    }
}
