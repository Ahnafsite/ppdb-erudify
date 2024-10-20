<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_parent_id',
        'document_requirement_id',
        'file_path',
        'verified'
    ];

    public function studentParent()
    {
        return $this->belongsTo(StudentParent::class);
    }

    public function documentRequirement()
    {
        return $this->belongsTo(DocumentRequirement::class);
    }
}
