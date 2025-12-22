<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class CaCertificate extends Model
{
    protected $fillable = [
        'uuid', 
        'ca_type', 
        'cert_content', 
        'key_content',
        'serial_number',
        'common_name',
        'organization',
        'valid_from',
        'valid_to'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
