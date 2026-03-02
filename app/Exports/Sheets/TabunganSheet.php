<?php

namespace App\Exports\Sheets;

use App\Models\Saving;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class TabunganSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
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
        $query = Saving::with('student.classroom')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc');

        if ($this->classId) {
            $query->whereHas('student', function ($q) {
                $q->where('class_id', $this->classId);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            ['Laporan Riwayat Tabungan Siswa'],
            ['Periode: ' . date('d M Y', strtotime($this->startDate)) . ' s/d ' . date('d M Y', strtotime($this->endDate))],
            [],
            [
                'No',
                'Tanggal',
                'Nama Siswa',
                'Kelas',
                'Tipe Transaksi',
                'Kategori',
                'Nominal',
            ]
        ];
    }

    public function map($saving): array
    {
        $this->iteration++;

        return [
            $this->iteration,
            \Carbon\Carbon::parse($saving->date)->format('d/m/Y'),
            $saving->student->nama,
            $saving->student->classroom->nama_kelas ?? 'Tanpa Kelas',
            $saving->type,
            $saving->type === 'Tarik' ? '-' : $saving->kategori,
            $saving->amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Mutasi Tabungan';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        
        return [
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            4    => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFE2EFDA']]],
        ];
    }
}
