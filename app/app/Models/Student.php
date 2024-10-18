<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $casts = [
        'birth_date' => 'date',
        'gender' => 'string',
        'details' => 'array'
    ];

    protected $fillable = [
        'user_id',
        'admission_setting_id',
        'name',
        'birth_place',
        'birth_date',
        'address',
        'phone_number',
        'gender',
        'program_id',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admissionSetting()
    {
        return $this->belongsTo(AdmissionSetting::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function parent()
    {
        return $this->hasOne(StudentParent::class, 'student_id');
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function admission()
    {
        return $this->hasOne(Admission::class);
    }
}
