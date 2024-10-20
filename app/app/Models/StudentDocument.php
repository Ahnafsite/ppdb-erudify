<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'document_requirement_id',
        'file_path',
        'verified'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function documentRequirement()
    {
        return $this->belongsTo(DocumentRequirement::class);
    }
}
