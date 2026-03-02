<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\AttendanceSheet;
use App\Exports\Sheets\SppSheet;
use App\Exports\Sheets\TabunganSheet;

class RekapExport implements WithMultipleSheets
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $classId;

    public function __construct($startDate, $endDate, $classId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->classId = $classId;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new AttendanceSheet($this->startDate, $this->endDate, $this->classId);
        $sheets[] = new SppSheet($this->startDate, $this->endDate, $this->classId);
        $sheets[] = new TabunganSheet($this->startDate, $this->endDate, $this->classId);

        return $sheets;
    }
}
