<?php

namespace App\Exports\expedientes_electronicos;

use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use App\Http\Controllers\JwtController;


/**
 * Exporta datos de manera generica a excel o pdf
 *
 * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
 * @version 1.0.0
 */
class GenericExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithEvents {

    use Exportable;

    /**
     * Datos de exportacion
     */
    private $data;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     */
    public function __construct($data) {
        $this->data = JwtController::decodeToken($data);
        // dd($this->data);
        array_walk($this->data, fn(&$object) => $object = (array) $object);
    }

    /**
     * Regstro de eventos
     *
     * @author Jhoan Sebastian Chilito S. - May. 29 - 2020
     * @version 1.0.0
     *
     * @return array
     */
    public function registerEvents(): array {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:'.$this->getByPosition(count(array_keys(current($this->data))) - 2).'1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                    // Set border Style
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000']
                        ]
                    ],
                    // Set font style
                    'font' => [
                        // 'name'      =>  'Arial',
                        'size'      =>  14,
                        'bold'      =>  true
                    ],

                    // Set background style
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'e0e0e0'
                        ]
                    ]
                ]);
            }
        ];
    }

    /**
     * Lista de datos a exportar typo collection
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     */
    public function collection() {
        return collect($this->data);
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
        // dd(count($alphabet));
        return ( !empty($alphabet[$pos]) && !is_bool($index) ) ? $alphabet[$pos] : NULL ;
    }

    /**
     * Organiza los datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 29 - 2020
     * @version 1.0.0
     */
    public function map($data): array {
        unset($data['deleted_at']);
        unset($data['id']);
        unset($data['cf_user_id']);
        unset($data['id_dependencia_padre']);
        unset($data['dependencias_list']);
        unset($data['headquarters']);
        $fechaFormateada = date('d/m/Y H:i:s', strtotime($data['created_at']));
        $data['created_at'] = $fechaFormateada;
        $data['updated_at'] = $fechaFormateada;
        return $data;
    }

    /**
     * Headers de la tabla
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     */
    public function headings(): array {
        $listHeaders = [];
        // Loop through colors array
        foreach(array_keys(current($this->data)) as $key) {
            if ($key !== 'deleted_at' && $key !== 'id' && $key !== 'cf_user_id' && $key !== 'id_dependencia_padre' && $key !== 'dependencias_list' && $key !== 'headquarters') {
                $listHeaders[] = trans(ucfirst($key));
            }
        }

        return $listHeaders;
    }
}
