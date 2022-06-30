<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;



Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
});

class ZBillNumbersByPrinterExport implements FromCollection, WithEvents, WithTitle, WithHeadings
{
    protected $data;


    public function __construct(array $data)
    {
        $this->data = $data['z_numbers_by_printer'];
        $this->total_rows = $this->data->count();
    }

    public function headings(): array
    {
        return [
            "Fecha",
            "Tipo Fac.",
            "Serial impresora",
            "Nro. Z",
            "Nro. Factura \r\nInf.",
            "Nro. Factura \r\nSup."
        ];
    }

    public function collection()
    {
        return $this->data;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->styleCells(
                    'A1:F1',
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
                    'A:F',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                    ]
                );
                              
                $event->sheet->styleCells(
                    'A1:F' . ($this->total_rows + 1),
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
                $event->sheet->getColumnDimension('E')->setWidth(110, 'px');
                $event->sheet->getColumnDimension('F')->setWidth(110, 'px');              
            },
        ];
    }

    public function title(): string
    {
        return 'Numeros Facturas';
    }
}
