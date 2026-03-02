<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'semester',
        'academic_year',
        'nilai_agama_moral',
        'fisik_motorik',
        'kognitif',
        'bahasa',
        'sosial_emosional',
        'seni',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
