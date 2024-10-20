<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'admission_period_start' => 'date',
        'admission_period_end' => 'date',
        'requirements' => 'array',
        'contact_person' => 'array',
    ];

    protected $fillable = [
        'admission_period_start',
        'admission_period_end',
        'requirements',
        'image',
        'year',
        'contact_person'
    ];

    public function attachments()
    {
        return $this->hasMany(DocumentRequirement::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
