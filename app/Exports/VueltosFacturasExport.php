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

class VueltosFacturasExport implements FromView, WithEvents, WithTitle
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->row_count_by_user = $this->getTotalRowsWorkSheetByUser($data['bill_vueltos']);
        $this->total_rows = $this->getTotalRows($this->row_count_by_user);
    }

    private function getTotalRowsWorkSheetByUser($bill_vueltos){
        $row_count = [];

        foreach($bill_vueltos as $key_codusua => $dates){
            $row_count[$key_codusua] = 0; 
            foreach ($dates as $key_date => $numeros_d){
                foreach ($numeros_d as $numero_d => $metodos_vuelto){
                    foreach($metodos_vuelto as $metodo_vuelto){
                        $row_count[$key_codusua] += $metodo_vuelto->count();
                    }
                }
            }
            $row_count[$key_codusua] += (($dates->count() * 2) + 4);
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
        return view('exports.vueltos_facturas.vueltos_report', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getColumnDimension('A')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('B')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('C')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('D')->setWidth(120, 'px');
                $event->sheet->getColumnDimension('E')->setWidth(100, 'px');
                $event->sheet->getColumnDimension('F')->setWidth(100, 'px');
                
                /** Fecha */
                $event->sheet->setCellValue('H2', 'Fecha: ' . $this->data['start_date'] . ($this->data['start_date'] !== $this->data['end_date'] 
                    ? (' hasta ' . $this->data['end_date']) 
                    : ''));
                $event->sheet->mergeCells('H2:J2');
                $event->sheet->styleCells(
                    'H2:J2',
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

                if ($this->data['bill_vueltos']->count() > 0){

                    /** Resumen total */
                    $event->sheet->setCellValue('H4', 'Resumen');
                    $event->sheet->mergeCells('H4:P4');

                    $event->sheet->setCellValue('H5', 'Caja');
                    $event->sheet->setCellValue('I5', 'Vuelto Efec.(Bs)');
                    $event->sheet->mergeCells('I5:J5');
                    $event->sheet->setCellValue('K5', 'Vuelto Efec.($)');
                    $event->sheet->mergeCells('K5:L5');
                    $event->sheet->setCellValue('M5', 'Vuelto PM.(Bs)');
                    $event->sheet->mergeCells('M5:N5');
                    $event->sheet->setCellValue('O5', 'Vuelto PM.($)');
                    $event->sheet->mergeCells('O5:P5');

                    $i = 6;
                    foreach($this->data['total_money_back_by_users'] as $key_codusua => $metodos_vuelto){
                        $event->sheet->setCellValue('H' . $i, $key_codusua);
                        $event->sheet->mergeCells('H' . $i . ':H' . $i);
                        $event->sheet->setCellValue('I' . $i, array_key_exists('Efectivo', $metodos_vuelto) ? $metodos_vuelto['Efectivo']['MontoBs'] : 0);
                        $event->sheet->mergeCells('I' . $i . ':J' . $i);
                        $event->sheet->setCellValue('K' . $i, array_key_exists('Efectivo', $metodos_vuelto) ? $metodos_vuelto['Efectivo']['MontoDiv']: 0);
                        $event->sheet->mergeCells('K' . $i . ':L' . $i);
                        $event->sheet->setCellValue('M' . $i, array_key_exists('PM', $metodos_vuelto) ? $metodos_vuelto['PM']['MontoBs'] : 0);
                        $event->sheet->mergeCells('M' . $i . ':N' . $i);
                        $event->sheet->setCellValue('O' . $i, array_key_exists('PM', $metodos_vuelto) ? $metodos_vuelto['PM']['MontoDiv'] : 0);
                        $event->sheet->mergeCells('O' . $i . ':P' . $i);

                        $i++;
                    }

                    $event->sheet->styleCells(
                        'H4:P4',
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

                    $event->sheet->styleCells(
                        'H5:P5',
                        [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                        ]
                    );
                    
                    $start_summary_row = 4;
                    $event->sheet->styleCells(
                        'H4:P' . ($start_summary_row + 2 + count($this->data['total_money_back_by_users'])),
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

                    $event->sheet->setCellValue('H' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
                            'Total:');
                    $event->sheet->mergeCells('H' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                            ':H' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']));
                    
                    $event->sheet->setCellValue('I' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
                            array_key_exists('Efectivo', $this->data['total_money_back']) ? $this->data['total_money_back']['Efectivo']['MontoBs'] : 0);
                    $event->sheet->mergeCells('I' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                            ':J' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']));
                    
                    $event->sheet->setCellValue('K' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
                            array_key_exists('Efectivo', $this->data['total_money_back']) ? $this->data['total_money_back']['Efectivo']['MontoDiv'] : 0);
                    $event->sheet->mergeCells('K' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                            ':L' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']));
                    
                    $event->sheet->setCellValue('M' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
                            array_key_exists('PM', $this->data['total_money_back']) ? $this->data['total_money_back']['PM']['MontoBs'] : 0);
                    $event->sheet->mergeCells('M' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                            ':N' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']));
                    
                    $event->sheet->setCellValue('O' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
                            array_key_exists('PM', $this->data['total_money_back']) ? $this->data['total_money_back']['PM']['MontoDiv'] : 0);
                    $event->sheet->mergeCells('O' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                        ':P' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']));

                    $event->sheet->styleCells('H' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']) .
                        ':P' . $start_summary_row + 2 + count($this->data['total_money_back_by_users']),
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
                        
                    $event->sheet->styleCells(
                        'B:F', 
                        [
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
                            'A' . $start . ':F' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)),
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
                            'A' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)) . ':F' . (is_null($prev) ? ($user_row_count - 1) : ($start + $user_row_count - 2)),
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
                            'A'. $start . ':F'. $start,
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
                            'A'. ($start + 1) . ':F'. ($start + 1),
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
                        
                        $event->sheet->mergeCells('A'. ($start + 1) . ':F'. ($start + 1));

                        $start_dates = $start + 2;
                        $prev_dates = null;
                        
                        foreach($this->data['bill_vueltos'][$key_user] as $dates){
                            

                            if (!is_null($prev_dates)){
                                $start_dates += ($prev_dates + 2);
                            }

                            $event->sheet->styleCells(
                                'A'. ($start_dates) . ':F'. ($start_dates),
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

                            $event->sheet->mergeCells('A'. ($start_dates) . ':F'. ($start_dates));

                            $prev_dates = $dates->count();
                    
                        }

                        $prev = $user_row_count;                    
                    }
                        
                    $event->sheet->styleCells(
                        'A:P',
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
        return 'Vueltos por factura';
    }
}
