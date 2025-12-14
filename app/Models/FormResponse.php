<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'ip_address',
        'user_agent',
        'event_id',
        'is_verified',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function items()
    {
        return $this->hasMany(FormResponseItem::class, 'response_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
