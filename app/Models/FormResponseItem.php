<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormResponseItem extends Model
{
    use HasFactory;

    protected $fillable = ['response_id','field_name','field_label','value'];

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'response_id');
    }
}
