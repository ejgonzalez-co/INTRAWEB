<?php

namespace App\Exports\Intranet\Poll;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Http\Controllers\JwtController;

class RequestExport implements FromView, WithEvents {

    private $view;
    private $data;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez - Mayo . 02 - 2021
     * @version 1.0.0
     * 
     * @param $view vista que se desea exportar
     * @param $data datos que se le envian a la vista
     */
    public function __construct($view ,$data) {
        $this->view = $view;
        $this->data = JwtController::decodeToken($data);

        array_walk($this->data, fn(&$object) => $object = (array) $object);
    }

    /**
     * Regstro de eventos
     *
     * @author Erika Johana Gonzalez - Mayo . 02 - 2021
     * @version 1.0.0
     *
     * @return array
     */
    public function registerEvents(): array {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // All headers
                $cellRange = 'A1:D1'; 
                
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
                        'size'      =>  14,
                        'bold'      =>  true
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],

                    // Set background style
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'e0e0e0'
                        ]
                    ]
                ]);
                //fin cabecera
                $contador = 1;
                foreach ($this->data as $data) {
                    $contador += count($data['intranet_polls_answers']);
                    $contador++;
                }

                $event->sheet->getStyle('A1:D'.($contador+2))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
                    ],

                    
                ]);
        
                foreach(range('A','D') as $column){
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
