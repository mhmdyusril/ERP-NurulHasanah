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

    /**
     * Tampilan antarmuka scan presensi barcode & QR Code
     */
    public function scan(Request $request)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $totalStudents = Student::active()->count();
        $attendancesToday = Attendance::with(['student.classroom'])
            ->whereDate('date', $today)
            ->get();
            
        $hadir = $attendancesToday->where('status', 'Hadir')->count();
        $sakit = $attendancesToday->where('status', 'Sakit')->count();
        $izin = $attendancesToday->where('status', 'Izin')->count();
        $alpa = $attendancesToday->where('status', 'Alpa')->count();
        $belumAbsen = max(0, $totalStudents - ($hadir + $sakit + $izin + $alpa));

        $recentLogs = Attendance::with(['student.classroom'])
            ->whereDate('date', $today)
            ->latest('updated_at')
            ->take(20)
            ->get();

        return view('attendances.scan', compact(
            'today',
            'totalStudents',
            'hadir',
            'sakit',
            'izin',
            'alpa',
            'belumAbsen',
            'recentLogs'
        ));
    }

    /**
     * Endpoint perekaman scan barcode / QR Code santri realtime
     */
    public function scanProcess(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'status' => 'nullable|in:Hadir,Sakit,Izin,Alpa',
            'notes' => 'nullable|string'
        ]);

        $code = trim($request->code);
        $status = $request->input('status', 'Hadir');

        // Cari siswa berdasarkan NIS atau ID
        $student = Student::with('classroom')
            ->where('nis', $code)
            ->orWhere('id', is_numeric($code) ? (int)$code : 0)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kartu siswa tidak terdaftar (Kode: ' . $code . ')'
            ], 404);
        }

        $today = Carbon::today()->format('Y-m-d');
        $timeNow = Carbon::now()->format('H:i:s');

        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'date' => $today,
            ],
            [
                'status' => $status,
                'notes' => $request->notes ?? 'Presensi QR Barcode',
            ]
        );

        // Rekapitulasi hari ini
        $totalStudents = Student::active()->count();
        $attendancesToday = Attendance::whereDate('date', $today)->get();
        $hadir = $attendancesToday->where('status', 'Hadir')->count();
        $sakit = $attendancesToday->where('status', 'Sakit')->count();
        $izin = $attendancesToday->where('status', 'Izin')->count();
        $alpa = $attendancesToday->where('status', 'Alpa')->count();
        $belumAbsen = max(0, $totalStudents - ($hadir + $sakit + $izin + $alpa));

        return response()->json([
            'status' => 'success',
            'message' => "Kehadiran ananda {$student->nama} berhasil dicatat ({$status})",
            'data' => [
                'attendance' => $attendance,
                'student' => [
                    'id' => $student->id,
                    'nama' => $student->nama,
                    'nis' => $student->nis,
                    'kelas' => $student->classroom?->nama_kelas ?? 'Belum ada kelas'
                ],
                'time' => $timeNow,
                'stats' => [
                    'total' => $totalStudents,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alpa' => $alpa,
                    'belum_absen' => $belumAbsen
                ]
            ]
        ]);
    }
}
