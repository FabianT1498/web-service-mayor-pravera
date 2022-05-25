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

class ZBillRecordExport implements FromView, WithEvents, WithTitle
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->row_count_by_user = $this->getTotalRowsWorkSheetByUser($data['totals_from_safact']);
        $this->total_rows = $this->getTotalRows($this->row_count_by_user);
    }

    private function getTotalRowsWorkSheetByUser($totals_from_safact){
        $row_count = [];

        foreach($totals_from_safact as $key_codusua => $dates){
            $row_count[$key_codusua] = 0;
            foreach ($dates as $key_date => $printers){
                foreach ($printers as $key_printer => $z_numbers){
                    foreach ($z_numbers as $records){
                        $row_count[$key_codusua] += $records->count();   
                    }
                }
            }

            $row_count[$key_codusua] += 2;
        }

        return $row_count;
    }

    private function getTotalRows($row_count_by_user){
        return array_reduce($row_count_by_user, function($acc, $el){
            return $acc + $el;
        }, 0);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('exports.z_bill.z_bill_records', $this->data);
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
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        
                    ]
                );

                $event->sheet->styleCells(
                    'F:M', 
                    [
                        'numberFormat' => [
                            'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                          
                        ]
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
                                    'argb' => 'FFA0A0AF',
                                ],
                                'endColor' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]
                    );
                    
                    $event->sheet->mergeCells('A'. $start . ':M' . $start);
                    
                    $prev = $user_row_count;
                }
                
                $event->sheet->styleCells(
                    'A:M',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );

                $event->sheet->styleCells(
                    'A1:M' . $this->total_rows + 1,
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
                $event->sheet->getColumnDimension('D')->setWidth(60, 'px');
                $event->sheet->getColumnDimension('E')->setWidth(85, 'px');
                $event->sheet->getColumnDimension('F')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('G')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('H')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('I')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('J')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('K')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('L')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('M')->setWidth(90, 'px');
            },
        ];
    }

    public function title(): string
    {
        return 'Detalles';
    }
}
