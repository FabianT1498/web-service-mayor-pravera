<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;


Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class ZBillSummaryExport implements FromView, WithEvents, WithTitle
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->total_rows = count($this->data['totals_by_user']) + 2;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.z_bill.z_bill_summary', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->styleCells(
                    'A1:K1',
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
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        
                    ]
                );

                $event->sheet->styleCells(
                    'B:K', 
                    [
                        'numberFormat' => [
                            'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                          
                        ]
                    ]
                );

                $event->sheet->styleCells(
                    'A'. $this->total_rows . ':K' . $this->total_rows,
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
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        
                    ]
                );

                $event->sheet->styleCells(
                    'A:K',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                              
                $event->sheet->styleCells(
                    'A1:K' . $this->total_rows,
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000'],
                            ],
                        ],
                    ]
                );

                $event->sheet->getColumnDimension('A')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('B')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('C')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('D')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('E')->setWidth(85, 'px');
                $event->sheet->getColumnDimension('F')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('G')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('H')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('I')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('J')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('K')->setWidth(90, 'px');
              
            },
        ];
    }

    public function title(): string
    {
        return 'Resumen';
    }
}
