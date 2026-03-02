<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $class_id = $request->input('class_id');
        
        // For guru, ideally we only fetch their class, but for simplicity we fetch all classes
        // or we filter it by wali_kelas_id if user is guru
        $classesQuery = Classes::query();
        if (auth()->user()->role === 'guru') {
            $classesQuery->where('wali_kelas_id', auth()->id());
        }
        $classes = $classesQuery->get();

        if (!$class_id && $classes->count() > 0) {
            $class_id = $classes->first()->id;
        }

        $students = collect();
        if ($class_id) {
            $students = Student::active()->where('class_id', $class_id)
                ->with(['attendances' => function($q) use ($date) {
                    $q->whereDate('date', $date);
                }])
                ->get();
        }

        return view('attendances.index', compact('classes', 'students', 'date', 'class_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'attendances.*.notes' => 'nullable|string',
        ]);

        $date = $request->date;

        foreach ($request->attendances as $student_id => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Data absensi berhasil disimpan untuk tanggal ' . Carbon::parse($date)->format('d M Y'));
    }
}
