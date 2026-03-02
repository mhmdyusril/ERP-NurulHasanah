<?php

namespace App\Exports\Sheets;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class SppSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
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
        $query = Payment::with('student.classroom', 'billingCategory')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->where('status', 'lunas');

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
            ['Laporan Pemasukan Tagihan/SPP'],
            ['Periode: ' . date('d M Y', strtotime($this->startDate)) . ' s/d ' . date('d M Y', strtotime($this->endDate))],
            [],
            [
                'No',
                'Tanggal Lunas',
                'Nama Siswa',
                'Kelas',
                'Kategori/Jenis Tagihan',
                'Nominal',
            ]
        ];
    }

    public function map($payment): array
    {
        $this->iteration++;

        return [
            $this->iteration,
            \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y'),
            $payment->student->nama,
            $payment->student->classroom->nama_kelas ?? 'Tanpa Kelas',
            $payment->billingCategory->name ?? 'SPP',
            $payment->amount,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function title(): string
    {
        return 'Laporan SPP';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        
        return [
            1    => ['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => 'center']],
            2    => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center']],
            4    => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'color' => ['argb' => 'FFFFF2CC']]],
        ];
    }
}
