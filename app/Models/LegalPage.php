<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'is_active'];

    public function revisions()
    {
        return $this->hasMany(LegalPageRevision::class);
    }

    public function currentRevision()
    {
        return $this->hasOne(LegalPageRevision::class)->where('is_active', true)->latestOfMany();
    }
}
