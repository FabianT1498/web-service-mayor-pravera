<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\ZBillRecordExport;
use App\Exports\ZBillSummaryExport;

class ZBillDataExport implements WithMultipleSheets
{
    use Exportable;
   
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

        $sheets[] = new ZBillRecordExport($this->data);
        $sheets[] = new ZBillSummaryExport($this->data);

        return $sheets;
    }
}