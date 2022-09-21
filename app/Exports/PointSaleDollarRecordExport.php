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

class PointSaleDollarRecordExport implements FromView, WithEvents, WithTitle
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->row_count_by_user = $this->getTotalRowsWorkSheetByUser($data['point_sale_dollar_records']);
        $this->total_rows = $this->getTotalRows($this->row_count_by_user);
    }

    private function getTotalRowsWorkSheetByUser($records){
        $row_count = [];

        foreach($records as $key_codusua => $dates){
            $row_count[$key_codusua] = 0; 
            foreach ($dates as $key_date => $records){
                $row_count[$key_codusua] += $records->count();   
            }
            $row_count[$key_codusua] += ($dates->count() + 4);
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
        return view('exports.zelle_record.zelle_report', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getColumnDimension('A')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('B')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('C')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('D')->setWidth(120, 'px');

                $event->sheet->setCellValue('G2', 'Fecha: ' . $this->data['start_date'] . ($this->data['start_date'] !== $this->data['end_date'] 
                    ? (' hasta ' . $this->data['end_date']) 
                    : ''));
                $event->sheet->mergeCells('G2:J2');
                $event->sheet->styleCells(
                    'G2:J2',
                    [
                        
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000'],
                            ],
                        ],
                        
                    ]
                );

                if ($this->data['point_sale_dollar_records']->count() > 0){
                    $event->sheet->styleCells(
                        'A:C', 
                        [
                            'numberFormat' => [
                                'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                            
                            ]
                        ]
                    );

                    /** Resumen total */
                    $event->sheet->setCellValue('G4', 'Resumen');
                    $event->sheet->mergeCells('G4:J4');

                    $event->sheet->setCellValue('G5', 'Monto ($)');
                    $event->sheet->mergeCells('G5:H5');
                    $event->sheet->setCellValue('I5', 'bolivares');
                    $event->sheet->mergeCells('I5:J5');
                    $event->sheet->setCellValue('G6', $this->data['total_point_sale_dollar_amount_by_user']['dollar']);
                    $event->sheet->mergeCells('G6:H6');
                    $event->sheet->setCellValue('I6', $this->data['total_point_sale_dollar_amount_by_user']['bs']);
                    $event->sheet->mergeCells('I6:J6');

                    $event->sheet->styleCells(
                        'G4:J4',
                        [
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => 'c5c5c5',
                                ],
                                'endColor' => [
                                    'rgb' => 'c5c5c5',
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
                    $event->sheet->mergeCells('G4:J4');

                    $event->sheet->styleCells(
                        'G4:J6',
                        [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000'],
                                ],
                            ],
                            'numberFormat' => [
                                'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                            
                            ]
                        ]
                    );

                    $prev = null;
                    $start = 1;

                    foreach($this->row_count_by_user as $key_user => $user_row_count){
                        if (!is_null($prev)){
                            $start += $prev;
                        }

                        $event->sheet->styleCells(
                            'A' . $start . ':D' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)),
                            [
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => '000'],
                                    ],
                                ],
                            ]
                        );

                        $event->sheet->styleCells(
                            'A' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)) . ':D' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)),
                            [
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => 'c5c5c5',
                                    ],
                                    'endColor' => [
                                        'rgb' => 'c5c5c5',
                                    ],
                                ],
                                'font' => [
                                    'bold' => true,
                                ],
                                'alignment' => [
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                                
                            ]
                        );

                        $event->sheet->styleCells(
                            'A'. $start . ':D'. $start,
                            [
                                'fill' => [
                                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                    'startColor' => [
                                        'rgb' => '858585',
                                    ],
                                    'endColor' => [
                                        'rgb' => '858585',
                                    ],
                                ],
                                'font' => [
                                    'bold' => true,
                                ],
                                'alignment' => [
                                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                                
                            ]
                        );

                        $event->sheet->styleCells(
                            'A'. ($start + 1) . ':D'. ($start + 1),
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
                                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                ],
                                
                            ]
                        );
                        
                        $event->sheet->mergeCells('A'. ($start + 1) . ':D'. ($start + 1));

                        $start_dates = $start + 2;
                        $prev_dates = null;
                        
                        foreach($this->data['point_sale_dollar_records'][$key_user] as $dates){
                            

                            if (!is_null($prev_dates)){
                                $start_dates += ($prev_dates + 1);
                            }

                            $event->sheet->styleCells(
                                'A'. ($start_dates) . ':D'. ($start_dates),
                                [
                                    'fill' => [
                                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'c5c5c5',
                                        ],
                                        'endColor' => [
                                            'rgb' => 'c5c5c5',
                                        ],
                                    ],
                                    'font' => [
                                        'bold' => true,
                                    ],
                                    'alignment' => [
                                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                    ],
                                    
                                ]
                            );

                            $event->sheet->mergeCells('A'. ($start_dates) . ':D'. ($start_dates));

                            $prev_dates = $dates->count();
                    
                        }

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
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Entradas del punto de venta internacional';
    }
}
