<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Student::count();
        $guruAktif = User::where('role', 'guru')->count();
        $totalDanaMasuk = 0; // Menunggu Modul Pembayaran

        // Dummy Data for Chart (Last 6 Months SPP)
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            'data' => [2500000, 3200000, 2800000, 4100000, 3900000, 4500000] // In Rupiah
        ];

        // Dummy Data for Recent Activities
        $recentActivities = [
            [
                'title' => 'Pendaftaran Siswa Baru',
                'description' => 'Ahmad Firdaus (Kelas A Nusa) berhasil ditambahkan.',
                'time' => '1 jam yang lalu',
                'icon' => 'user-plus',
                'color' => 'text-sage',
                'bg' => 'bg-sage/10',
            ],
            [
                'title' => 'Pembayaran SPP Lunas',
                'description' => 'Siti Aminah telah melunasi SPP Bulan Juni.',
                'time' => '3 jam yang lalu',
                'icon' => 'check-circle',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
            ],
            [
                'title' => 'Pembuatan Rombel',
                'description' => 'Kelas Baru (Kelas B Bangsa) dibuka.',
                'time' => 'Kemarin, 09:00',
                'icon' => 'building',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
            ]
        ];

        return view('dashboard', compact('totalSiswa', 'totalDanaMasuk', 'guruAktif', 'chartData', 'recentActivities'));
    }
}
