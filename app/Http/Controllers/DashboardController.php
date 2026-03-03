<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Student::count();
        $guruAktif = User::where('role', 'guru')->count();
        $totalDanaMasuk = Payment::where('status', 'lunas')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Logic for Chart (Last 6 Months SPP)
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $chartData['labels'][] = $month->translatedFormat('M');
            $chartData['data'][] = Payment::where('status', 'lunas')
                ->whereMonth('payment_date', $month->month)
                ->whereYear('payment_date', $month->year)
                ->sum('amount');
        }

        // Logic for Recent Activities
        $recentStudents = Student::latest()->take(5)->get();
        $recentPayments = Payment::where('status', 'lunas')->latest()->take(5)->get();
        
        $activities = collect();
        foreach($recentStudents as $student) {
            $activities->push([
                'title' => 'Pendaftaran Siswa Baru',
                'description' => $student->nama . ' berhasil ditambahkan.',
                'timestamp' => $student->created_at,
                'icon' => 'user-plus',
                'color' => 'text-sage',
                'bg' => 'bg-sage/10',
            ]);
        }
        foreach($recentPayments as $payment) {
            $activities->push([
                'title' => 'Pembayaran SPP Lunas',
                'description' => ($payment->student->nama ?? 'Siswa') . ' melunasi tagihan.',
                'timestamp' => $payment->created_at,
                'icon' => 'check-circle',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
            ]);
        }

        $recentActivities = $activities->sortByDesc('timestamp')->take(5)->map(function($item) {
            $item['time'] = $item['timestamp'] ? $item['timestamp']->diffForHumans() : 'Baru saja';
            return $item;
        })->values()->all();

        return view('dashboard', compact('totalSiswa', 'totalDanaMasuk', 'guruAktif', 'chartData', 'recentActivities'));
    }
}
