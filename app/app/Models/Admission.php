<?php

namespace App\Models;

use App\Models\Enums\AdmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    const Types = AdmissionStatus::class;

    protected $casts = [
        'type' => AdmissionStatus::class,
        'admission_date' => 'date',
    ];

    protected $fillable = [
        'student_id',
        'admission_date',
        'type'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}