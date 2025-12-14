<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteInformation extends Model
{
    use HasFactory;

    protected $table = 'website_information';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image_url',
        'updated_by_admin',
        'updated_at',
        'footer_address',
        'footer_phone',
        'footer_email',
        'footer_social_links',
        'zakat_settings',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'footer_social_links' => 'array',
        'zakat_settings' => 'array',
    ];

    public function updatedByAdmin()
    {
        return $this->belongsTo(User::class, 'updated_by_admin');
    }
}
