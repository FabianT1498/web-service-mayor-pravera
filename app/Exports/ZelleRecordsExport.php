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

class ZelleRecordsExport implements FromView, WithEvents, WithTitle
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data;
        $this->row_count_by_user = $this->getTotalRowsWorkSheetByUser($data['zelle_records']);
        $this->total_rows = $this->getTotalRows($this->row_count_by_user);
    }

    private function getTotalRowsWorkSheetByUser($zelle_records){
        $row_count = [];

        foreach($zelle_records as $key_codusua => $dates){
            $row_count[$key_codusua] = 0; 
            foreach ($dates as $key_date => $records){
                $row_count[$key_codusua] += $records->count();   
            }
            $row_count[$key_codusua] += ($dates->count() + 3);
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

                // $event->sheet->styleCells(
                //     'F:M', 
                //     [
                //         'numberFormat' => [
                //             'formatCode' => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                          
                //         ]
                //     ]
                // );

                $prev = null;
                $start = 1;

                foreach($this->row_count_by_user as $key_user => $user_row_count){
                    if (!is_null($prev)){
                        $start += $prev;
                    }

                    $event->sheet->styleCells(
                        'A'. $start . ':D'. $start,
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

                    foreach($this->data['zelle_records']->get($key_user) as $dates){
                        foreach($dates as $records){
                            $event->sheet->styleCells(
                                'A'. ($start_dates) . ':D'. ($start_dates),
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
                        }
                        
                    }


                    $prev = $user_row_count;

                    // $event->sheet->styleCells(
                    //     'A'. $start . ':M' . $start,
                    //     [
                    //         'fill' => [
                    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    //             'startColor' => [
                    //                 'argb' => 'FFA0A0AF',
                    //             ],
                    //             'endColor' => [
                    //                 'argb' => 'FFFFFFFF',
                    //             ],
                    //         ],
                    //         'font' => [
                    //             'bold' => true,
                    //         ],
                    //         'alignment' => [
                    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    //         ],
                    //     ]
                    // );
                    
                    
                    
                    
                }
                
                // $event->sheet->styleCells(
                //     'A:M',
                //     [
                //         'alignment' => [
                //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                //         ],
                //     ]
                // );

                // $event->sheet->styleCells(
                //     'A1:M' . $this->total_rows + 1,
                //     [
                //         'borders' => [
                //             'allBorders' => [
                //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                 'color' => ['argb' => '000'],
                //             ],
                //         ],
                //     ]
                // );

                $event->sheet->getColumnDimension('A')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('B')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('C')->setWidth(90, 'px');
                $event->sheet->getColumnDimension('D')->setWidth(120, 'px');
            },
        ];
    }

    public function title(): string
    {
        return 'Detalles';
    }
}
