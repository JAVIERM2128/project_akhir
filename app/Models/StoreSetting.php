<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'logo_path',
        'contact_phone',
        'address',
        'description',
        'social_media',
    ];

    protected $casts = [
        'social_media' => 'array',
    ];
}
