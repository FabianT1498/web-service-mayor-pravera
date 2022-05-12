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
        $this->row_count_by_user = $this->getTotalRowsWorkSheet($data['totals_from_safact']);
        $this->worksheet_row_count += (1 + (count($this->worksheet_row_count) * 2));
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

                $event->sheet->styleCells(
                    'A1:F' . $this->data->count() + 1,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );

                $event->sheet->styleCells(
                    'A' . $this->data->count() + 1 . ':F' . $this->data->count() + 1,
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

                $event->sheet->styleCells(
                    'E1:F' . $this->data->count() + 1,
                    [
                        'numberFormat' => [
                            'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                          
                        ]
                    ]
                );

                
            },
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->styleCells(
                    'A1:I1',
                    [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                                'color' => ['argb' => '000'],
                            ],
                        ]
                    ]
                );
            },
        ];
    }
}
