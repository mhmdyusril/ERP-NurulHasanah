<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@ranurulhasanah.sch.id'],
            [
                'name' => 'Prof. Dion O\'Connell',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Seed Teacher
        $teacher = \App\Models\User::firstOrCreate(
            ['email' => 'guru@ranurulhasanah.sch.id'],
            [
                'name' => 'Guru Pengajar',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // Seed Academic Term
        $term1 = \App\Models\AcademicTerm::firstOrCreate(
            ['academic_year' => '2026/2027', 'semester' => 'Ganjil'],
            ['is_active' => true]
        );
        $term2 = \App\Models\AcademicTerm::firstOrCreate(
            ['academic_year' => '2026/2027', 'semester' => 'Genap'],
            ['is_active' => false]
        );

        // Seed Billing Categories
        $spp = \App\Models\BillingCategory::firstOrCreate(
            ['name' => 'SPP Bulanan'],
            ['default_amount' => 150000]
        );
        $seragam = \App\Models\BillingCategory::firstOrCreate(
            ['name' => 'Seragam Sekolah'],
            ['default_amount' => 850000]
        );
        $pangkal = \App\Models\BillingCategory::firstOrCreate(
            ['name' => 'Uang Pangkal'],
            ['default_amount' => 2500000]
        );

        // Seed Classes
        $classA = \App\Models\Classes::firstOrCreate(
            ['nama_kelas' => 'Kelas A (Nusa)'],
            ['wali_kelas_id' => $teacher->id]
        );
        $classB = \App\Models\Classes::firstOrCreate(
            ['nama_kelas' => 'Kelas B (Bangsa)'],
            ['wali_kelas_id' => $teacher->id]
        );

        // Seed Students
        \App\Models\Student::firstOrCreate(
            ['nis' => '20261001'],
            [
                'nama' => 'Ahmad Firdaus',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '2020-05-12',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'nama_wali' => 'Bpk. Budi Santoso',
                'class_id' => $classA->id,
            ]
        );

        \App\Models\Student::firstOrCreate(
            ['nis' => '20261002'],
            [
                'nama' => 'Siti Aminah',
                'tempat_lahir' => 'Bandung',
                'tgl_lahir' => '2020-08-22',
                'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                'nama_wali' => 'Ibu Rahmawati',
                'class_id' => $classB->id,
            ]
        );

        // Seed Alumni (Lulus)
        \App\Models\Student::firstOrCreate(
            ['nis' => '20250001'],
            [
                'nama' => 'Alumni Hebat',
                'tempat_lahir' => 'Jakarta',
                'tgl_lahir' => '2019-01-01',
                'alamat' => 'Jl. Kenangan No. 99',
                'nama_wali' => 'Bpk. Bangga',
                'class_id' => $classB->id,
                'status' => 'Lulus',
            ]
        );
    }
}
