<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRequirement extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'for' => 'string',
        'types' => 'array',
        'required' => 'boolean',
    ];

    protected $fillable = ['name', 'for','types', 'required', 'admission_setting_id'];

    public function studentDocuments()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function parentDocuments()
    {
        return $this->hasMany(ParentDocument::class);
    }
}
