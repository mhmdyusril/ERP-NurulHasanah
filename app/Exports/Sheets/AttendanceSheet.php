<?php

namespace App\Exports\Sheets;

use App\Models\Student;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $startDate;
    private $endDate;
    private $classId;
    private $iteration = 0;

    public function __construct($startDate, $endDate, $classId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->classId = $classId;
    }

    public function collection()
    {
        $query = Student::active()
            ->with(['classroom', 'attendances' => function($q) {
                $q->whereBetween('date', [$this->startDate, $this->endDate]);
            }]);

        if ($this->classId) {
            $query->where('class_id', $this->classId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ['Laporan Rekapitulasi Kehadiran Siswa'],
            ['Periode: ' . date('d M Y', strtotime($this->startDate)) . ' s/d ' . date('d M Y', strtotime($this->endDate))],
            [],
            [
                'No',
                'Nama Lengkap',
                'NIS',
                'Kelas',
                'Hadir',
                'Sakit',
                'Izin',
                'Alpa',
            ]
        ];
    }

    public function map($student): array
    {
        $this->iteration++;
        
        $hadir = $student->attendances->where('status', 'Hadir')->count();
        $sakit = $student->attendances->where('status', 'Sakit')->count();
        $izin = $student->attendances->where('status', 'Izin')->count();
        $alpa = $student->attendances->where('status', 'Alpa')->count();

        return [
            $this->iteration,
            $student->nama,
            $student->nis,
            $student->classroom->nama_kelas ?? 'Tanpa Kelas',
            $hadir,
            $sakit,
            $izin,
            $alpa,
        ];
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        
        return [
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            4    => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFE2EFDA']]],
        ];
    }
}
