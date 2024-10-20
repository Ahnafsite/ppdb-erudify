<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'title'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function admissionSetting()
    {
        return $this->belongsTo(AdmissionSetting::class);
    }
}
