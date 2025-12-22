<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'uuid', 'user_id', 'common_name', 'organization', 'locality',
        'state', 'country', 'san', 'key_bits', 'serial_number',
        'cert_content', 'key_content', 'csr_content',
        'valid_from', 'valid_to'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
