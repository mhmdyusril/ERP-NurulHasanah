<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SavingController extends Controller
{
    public function index(Request $request)
    {
        $class_id = $request->input('class_id');
        $classesQuery = Classes::query();
        if (auth()->user()->role === 'guru') {
            $classesQuery->where('wali_kelas_id', auth()->id());
        }
        $classes = $classesQuery->get();

        $studentsQuery = Student::active(); // Filter for active students
        if ($class_id) {
            $studentsQuery->where('class_id', $class_id);
        } elseif (auth()->user()->role === 'guru') {
            $guruClassesIds = $classes->pluck('id');
            $studentsQuery->whereIn('class_id', $guruClassesIds);
        }

        $students = $studentsQuery->with('classroom')->withSum(['savings as total_setoran' => function($q) {
            $q->where('type', 'Setor');
        }], 'amount')
        ->withSum(['savings as total_tarikan' => function($q) {
            $q->where('type', 'Tarik');
        }], 'amount')
        ->paginate(15);

        return view('savings.index', compact('classes', 'students', 'class_id'));
    }

    public function show(Student $saving) // Note: routing uses resource 'savings', so parameter is $saving which actually is Student ID logically or we make it custom. Wait, let's just use Student $saving and bind it if we use resource.
    {
        $student = $saving;
        $student->load('classroom');
        $transactions = Saving::where('student_id', $student->id)->orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
        
        $saldo = $transactions->where('type', 'Setor')->sum('amount') - $transactions->where('type', 'Tarik')->sum('amount');
        
        return view('savings.show', compact('student', 'transactions', 'saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'type' => 'required|in:Setor,Tarik',
            'kategori' => 'nullable|in:Wajib,Bebas',
            'amount' => 'required|numeric|min:1000',
            'notes' => 'nullable|string|max:255',
        ]);

        $data = $request->except('_token');
        if ($request->type === 'Tarik') {
            $data['kategori'] = 'Bebas'; // Or null if strictly designed, but default is 'Bebas'
        } elseif (!isset($data['kategori'])) {
            $data['kategori'] = 'Bebas';
        }

        if ($request->type === 'Tarik') {
            $totalSetor = Saving::where('student_id', $request->student_id)->where('type', 'Setor')->sum('amount');
            $totalTarik = Saving::where('student_id', $request->student_id)->where('type', 'Tarik')->sum('amount');
            $saldo = $totalSetor - $totalTarik;
            if ($request->amount > $saldo) {
                return back()->withErrors(['amount' => 'Saldo tidak mencukupi untuk penarikan ini. Saldo saat ini: Rp ' . number_format($saldo, 0, ',', '.')]);
            }
        }

        Saving::create($data);

        return back()->with('success', 'Transaksi tabungan berhasil dicatat.');
    }
}
