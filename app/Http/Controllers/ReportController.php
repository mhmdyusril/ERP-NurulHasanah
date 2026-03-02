<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Saving;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;

class ReportController extends Controller
{
    public function finance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $class_id = $request->input('class_id');
        
        // Prepare classes for filter dropdown
        $classesQuery = Classes::query();
        if (auth()->user()->role === 'guru') {
            $classesQuery->where('wali_kelas_id', auth()->id());
        }
        $classes = $classesQuery->get();

        if (!$class_id && $classes->count() > 0) {
            $class_id = $classes->first()->id;
        }

        // ==========================================
        // 1. DATA ABSENSI
        // ==========================================
        $students = collect();
        if ($class_id) {
            $students = Student::active()->where('class_id', $class_id)
                ->with(['attendances' => function($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                }])
                ->get();
        }

        // ==========================================
        // 2. DATA SPP & PEMBAYARAN
        // ==========================================
        $paymentsQuery = Payment::with('student', 'billingCategory')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->where('status', 'lunas');
            
        if ($class_id) {
            $paymentsQuery->whereHas('student', function ($q) use ($class_id) {
                $q->where('class_id', $class_id);
            });
        }
        $payments = $paymentsQuery->get();

        // ==========================================
        // 3. DATA TABUNGAN
        // ==========================================
        $savingsQuery = Saving::with('student')
            ->whereBetween('date', [$startDate, $endDate]);
            
        if ($class_id) {
            $savingsQuery->whereHas('student', function ($q) use ($class_id) {
                $q->where('class_id', $class_id);
            });
        }
        $savings = $savingsQuery->get();

        // Rekapitulasi Keuangan
        $totalSPP = $payments->sum('amount');
        $totalSetoran = $savings->where('type', 'Setor')->sum('amount');
        $totalSetoranWajib = $savings->where('type', 'Setor')->where('kategori', 'Wajib')->sum('amount');
        $totalSetoranBebas = $savings->where('type', 'Setor')->where('kategori', 'Bebas')->sum('amount');
        $totalTarikan = $savings->where('type', 'Tarik')->sum('amount');

        return view('reports.finance', compact(
            'startDate', 'endDate', 
            'classes', 'class_id', 'students',
            'payments', 'savings', 
            'totalSPP', 'totalSetoran', 'totalSetoranWajib', 'totalSetoranBebas', 'totalTarikan'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $class_id = $request->input('class_id');

        $fileName = 'Rekap_Laporan_' . \Str::slug($startDate . '_sd_' . $endDate) . '.xlsx';
        
        return Excel::download(new RekapExport($startDate, $endDate, $class_id), $fileName);
    }
}
