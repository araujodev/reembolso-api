<?php

namespace App\Exports;

use App\Models\Refund;
use Maatwebsite\Excel\Concerns\FromArray;

class RefundsReportExport implements FromArray
{

    private $month;
    private $year;
    private $employee_id;

    public function __construct($month, $year, $employee_id)
    {
        $this->month = $month;
        $this->year = $year;
        $this->employee_id = $employee_id;
    }

    public function array(): array
    {
        $dataset = Refund::where('employee_id', $this->employee_id)
            ->whereMonth('date', '=', $this->month)
            ->whereYear('date', '=', $this->year)
            ->get();

        $totalRefunds = $dataset->sum('value');
        $countRefunds = $dataset->count();

        $result = [
            ['totalRefunds' => $totalRefunds],
            ['refunds' => $countRefunds],
            ['month' => $this->month],
            ['year' => $this->year],
            ['ID Employee' => $this->employee_id]
        ];

        return $result;
    }
}
