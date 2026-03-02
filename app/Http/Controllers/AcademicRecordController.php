<?php

namespace App\Http\Controllers;

use App\Models\AcademicRecord;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $semester = $request->input('semester', 'Ganjil');
        $year = $request->input('academic_year', '2026/2027');

        $students = Student::active()->with(['classroom', 'academicRecords' => function ($q) use ($semester, $year) {
            $q->where('semester', $semester)->where('academic_year', $year);
        }])->get();

        return view('academics.index', compact('students', 'semester', 'year'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student, Request $request)
    {
        // This is tricky if we use implicit binding, let's just pass student instead of academic
        // Wait, standard resource edit takes the resource ID.
        // Let's override it or create a specific method.
        // Since we are replacing the closure, let's just use store to update/create.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'semester' => 'required|in:Ganjil,Genap',
            'academic_year' => 'required|string',
            'nilai_agama_moral' => 'nullable|in:BB,MB,BSH,BSB',
            'fisik_motorik' => 'nullable|in:BB,MB,BSH,BSB',
            'kognitif' => 'nullable|in:BB,MB,BSH,BSB',
            'bahasa' => 'nullable|in:BB,MB,BSH,BSB',
            'sosial_emosional' => 'nullable|in:BB,MB,BSH,BSB',
            'seni' => 'nullable|in:BB,MB,BSH,BSB',
        ]);

        AcademicRecord::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'semester' => $request->semester,
                'academic_year' => $request->academic_year,
            ],
            [
                'nilai_agama_moral' => $request->nilai_agama_moral,
                'fisik_motorik' => $request->fisik_motorik,
                'kognitif' => $request->kognitif,
                'bahasa' => $request->bahasa,
                'sosial_emosional' => $request->sosial_emosional,
                'seni' => $request->seni,
            ]
        );

        return back()->with('success', 'Rapor akademik berhasil disimpan.');
    }
}
