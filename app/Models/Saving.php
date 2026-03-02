<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'type',
        'kategori',
        'amount',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
