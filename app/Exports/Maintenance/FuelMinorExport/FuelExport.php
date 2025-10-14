<?php

namespace App\Exports\Maintenance\FuelMinorExport;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class FuelExport implements FromView, WithEvents {

    private $view;
    private $data;
    private $column;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña - Sep .16  - 2021
     * @version 1.0.0
     * 
     * @param $view vista que se desea exportar
     * @param $data datos que se le envian a la vista
     * @param $column columna limite con "estilos" en la hoja de calculo
     */
    public function __construct($view ,$data, $column="k") {
        $this->view = $view;
        $this->data = $data;
        $this->column = $column;
    }

    /**
     * Regstro de eventos
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 14 - 2021
     * @version 1.0.0
     *
     * @return array
     */
    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total=1;
                $cellRange = 'A'.$total.':'.$this->column.$total; // All headers
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                        // Set border Style
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        // Set font style
                        'font' => [
                            // 'name'      =>  'Arial',
                            'size'      =>  18,
                            'bold'      =>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'FFFFFF'
                            ]
                        ]
                    ]);
                
                foreach ($this->data as $dataMinor) {
                    
                    $col=$total+1;
                    $cellRange = 'A'.$col.':'.$this->column.$col; // All headers
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                        // Set border Style
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        // Set font style
                        'font' => [
                            // 'name'      =>  'Arial',
                            'size'=>  14,
                            'bold'=>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '0FFF00'
                            ]
                        ]
                    ]);



                    $col=$total+2;
                    $cellRange = 'A'.$col.':'.$this->column.$col; // All headers
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                        // Set border Style
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        // Set font style
                        'font' => [
                            // 'name'      =>  'Arial',
                            'size'=>  11,
                            'bold'=>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'C0BEBE'
                            ]
                        ]
                    ]);


                    $col=$total+5;
                    $cellRange = 'A'.$col.':'.$this->column.$col; // All headers
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                        // Set border Style
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        // Set font style
                        'font' => [
                            // 'name'      =>  'Arial',
                            'size'=>  14,
                            'bold'=>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '787676'
                            ]
                        ]
                    ]);

                    $col=$total+6;
                    $cellRange = 'A'.$col.':'.$this->column.$col; // All headers
                    $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                        // Set border Style
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        // Set font style
                        'font' => [
                            // 'name'      =>  'Arial',
                            'size'=>  11,
                            'bold'=>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => 'C0BEBE'
                            ]
                        ]
                    ]);




                    $total+=7+count($dataMinor['mant_equipment_fuel_consumptions']);
                }

                $total=0;
                $total++;
                foreach ($this->data as $dataMinor) {

                    $total++;
                    $total++;                    
                    $total++;
                    $total++;
                    $total++;
                    $total++;

                    foreach ($dataMinor['mant_equipment_fuel_consumptions'] as  $value) {
                        $total++;
                    }                   
                    $total++;
                }              
                
                $event->sheet->getStyle('A2:'.strtoupper($this->column).($total))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
        
                foreach(range('A',strtoupper($this->column)) as $column){
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true) ;
                }
            }
        ];
    }

    // public function drawings() {
    //     $drawing = new Drawing();
    //     $drawing->setName('Logo');
    //     $drawing->setDescription('Logo');
    //     $drawing->setPath(public_path('/assets/img/logo_epa.png'));
    //     $drawing->setHeight(90);
    //     $drawing->setCoordinates('A1');

    //     return $drawing;
    // }

    /**
     * Obtiene la letra del abecedario dependiendo de la posicion
     *
     * @author Jhoan Sebastian Chilito S. - May. 29 - 2020
     * @version 1.0.0
     */
    function getByPosition($index): string {
        // Crea un array con las letras de la A a la Z
        $alphabet = range('A', 'Z');
        // Seteamos la posición restando 1 porque los índices comienzan en 0
        $pos = $index - 1;
        // Retornamos la letra, o NULL, si $index desborda el array
        // Para evitar que true sea tratado como índice 1, controlamos con is_bool también
        return ( !empty($alphabet[$pos]) && !is_bool($index) ) ? $alphabet[$pos] : NULL ;
    }


//     public function registerEvents() : array
// {
//     return [
//         AfterSheet::class    => function(AfterSheet $event) {
//             $cellRange = 'A1:L1'; // All headers
//             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
//             $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->getColor()
//                          ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
//             $event->sheet->getDelegate()->getStyle($cellRange)->getFill()
//                          ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
//                          ->getStartColor()->setARGB('FF17a2b8');
//             $event->sheet->setAutoFilter($cellRange);

//             $event->sheet->styleCells(
//                 'A2:'.$to['column'].$to['row'],
//                 [
//                     'borders' => [
//                         'allBorders' => [
//                             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                         ],
//                     ]
//                 ]
//             );
//         },
//     ];
// }

    /**
     * Exporta los datos desde una vista
     *
     * @author Erika Johana Gonzalez - Mayo . 02 - 2021
     * @version 1.0.0
     * 
     */
    public function view(): View {
        // Retorna la vista enviando los datos
        return view($this->view)->with('data', $this->data);
    }
}
