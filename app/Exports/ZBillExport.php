<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class ZBillExport implements FromView, WithEvents
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->row_count_by_user = $this->getTotalRowsWorkSheetByUser($data['totals_from_safact']);
    }

    private function getTotalRowsWorkSheetByUser($totals_from_safact){
        $row_count = [];

        foreach($totals_from_safact as $key_codusua => $dates){
            $row_count[$key_codusua] = 0;
            foreach ($dates as $key_date => $printers){
                foreach ($printers as $key_printer => $z_numbers){
                    $row_count[$key_codusua] += $z_numbers->count();   
                }
            }

            $row_count[$key_codusua] += 2;
        }

        return $row_count;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.z_bill.z_bill_records', $this->data);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,     
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->styleCells(
                    'A1:M1',
                    [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'argb' => 'FFA0A0A0',
                            ],
                            'endColor' => [
                                'argb' => 'FFFFFFFF',
                            ],
                        ],
                        'font' => [
                            'bold' => true,
                        ],
                    ]
                );

                /** Usuarios de caja */
                $prev = null;
                $start = 2;
                foreach($this->row_count_by_user as $user_row_count){
                    if ($prev){
                        $start += $prev;
                    }

                    $event->sheet->styleCells(
                        'A'. $start . ':M' . $start,
                        [
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'argb' => 'FFA0A0A0',
                                ],
                                'endColor' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                            'font' => [
                                'bold' => true,
                            ],
                            'mergeCells'
                        ]
                    );

                    $prev = $user_row_count;
                } 
            },
        ];
    }
}
