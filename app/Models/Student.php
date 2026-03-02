<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'jenis_kelamin',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'nama_wali',
        'class_id',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function classroom()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function savings()
    {
        return $this->hasMany(Saving::class);
    }
}
