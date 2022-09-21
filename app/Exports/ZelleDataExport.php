<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\ZelleRecordExport;
use App\Exports\ZelleRecordSaintExport;
use App\Exports\PointSaleDollarRecordExport;
use App\Exports\PointSaleDollarRecordSaintExport;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class ZelleDataExport implements WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new ZelleRecordExport($this->data);
        $sheets[] = new ZelleRecordSaintExport($this->data);
        $sheets[] = new PointSaleDollarRecordExport($this->data);
        $sheets[] = new PointSaleDollarRecordSaintExport($this->data);
      
        return $sheets;
    }
}
