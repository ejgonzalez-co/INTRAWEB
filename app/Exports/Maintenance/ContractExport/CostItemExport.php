<?php

namespace App\Exports\Maintenance\ContractExport;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Http\Controllers\JwtController;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class CostItemExport implements FromView, WithEvents {

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
        $this->data = JwtController::decodeToken($data);
        $this->column = $column;
    }


    /**
     * Regstro de eventos
     *
     * @author Nicolas Dario Ortiz Peña - Sep .16  - 2021
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

                $total=2;
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
                            'size'=>  14,
                            'bold'=>  true
                        ],
    
                        // Set background style
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => '059417'
                            ]
                        ]
                    ]);


                    $total=5;
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
                                'size'=>  14,
                                'bold'=>  true
                            ],
        
                            // Set background style
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => [
                                    'rgb' => '059417'
                                ]
                            ]
                        ]);

                        
                    $total=5;
                    $total=$total+count($this->data);
                    
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
