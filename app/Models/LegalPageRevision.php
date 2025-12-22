<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalPageRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'legal_page_id',
        'content',
        'version',
        'change_log',
        'is_active',
        'created_by'
    ];

    public function legalPage()
    {
        return $this->belongsTo(LegalPage::class);
    }
}
