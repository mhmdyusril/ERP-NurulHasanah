<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->string('academic_year', 20); // e.g. "2026/2027"
            
            // Kehadiran tidak lagi disimpan disini, dipindah ke modul Attendances.
            
            // Kolom Perkembangan Anak Spesifik PAUD/RA (Skala: BB, MB, BSH, BSB)
            $table->enum('nilai_agama_moral', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            $table->enum('fisik_motorik', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            $table->enum('kognitif', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            $table->enum('bahasa', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            $table->enum('sosial_emosional', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            $table->enum('seni', ['BB', 'MB', 'BSH', 'BSB'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
};
