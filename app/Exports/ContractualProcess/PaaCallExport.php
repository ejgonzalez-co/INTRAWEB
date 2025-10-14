<?php

namespace App\Exports\ContractualProcess;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class PaaCallExport implements FromView, WithEvents, WithDrawings {

   private $view;
   private $data;

   /**
    * Constructor de la clase
    *
    * @author Carlos Moises Garcia T. - Abri. 18 - 2021
    * @version 1.0.0
    * 
    * @param $view vista que se desea exportar
    * @param $data datos que se le envian a la vista
    */
   public function __construct($view ,$data) {
      $this->view = $view;
      $this->data = $data;
   }

   /**
    * Regstro de eventos
    *
    * @author Carlos Moises Garcia T. - May. 19 - 2021
    * @version 1.0.0
    *
    * @return array
    */
   public function registerEvents(): array {
      return [
         AfterSheet::class => function(AfterSheet $event) {

            $event->sheet->getDelegate()->getStyle('A16:O17')->applyFromArray([
               // Establece estilos de fuente
               'font' => [
                  // 'name' => 'Arial',
                  'size' => 14,
                  'bold' => true
               ],

              // Establece el color del fondo
               'fill' => [
                  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                  'startColor' => [
                     'rgb' => 'e0e0e0'
                  ]
               ],
            ]);

            $event->sheet->getDelegate()->getStyle('A7:A14')->applyFromArray([
               // Establece estilos de fuente
               'font' => [
                  'size' => 14,
                  'bold' => true
               ],
               // Establece el color del fondo
               'fill' => [
                  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                  'startColor' => [
                     'rgb' => 'e0e0e0'
                  ]
               ],
            ]);

            $event->sheet->getDelegate()->getStyle('D13:D14')->applyFromArray([
               // Establece estilos de fuente
               'font' => [
                  'size' => 14,
                  'bold' => true
               ],
              // Establece el color del fondo
               'fill' => [
                  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                  'startColor' => [
                     'rgb' => 'e0e0e0'
                  ]
               ],
            ]);

            $event->sheet->getStyle('A2:O'.(count($this->data)+17))->applyFromArray([
               // Establece la alineacion de las celdas
               'alignment' => [
                  'wrapText' => true,
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
               ],
               // Establece los bordes de las celdas
               'borders' => [
                  'allBorders' => [
                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                     'color' => ['argb' => '000000'],
                  ],
               ],
               // Establece estilos de fuente
               'font' => [
                  'size' => 12,
               ],
            ]);

            // $event->sheet->setColumnFormat(array(
            //       'A2:K2' => '0000'
            // ));
            
            // Recorre las columnas
            foreach(range('A','O') as $column){
               // Envia la dimencion del ancho de la columna
               $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(25);
            }
         }
      ];
   }

   /**
    * Inserta imagenes en la tabla
    *
    * @author Carlos Moises Garcia T. - Jul. 15 - 2021
    * @version 1.0.0
    * 
    */
   public function drawings() {
      
      $drawing = new Drawing();
      $drawing->setName('Logo');
      $drawing->setDescription('Logo EPA');
      $drawing->setPath(public_path('assets/img/default/icon_epa.png'));
      $drawing->setHeight(60); // Dimension de la imagen
      $drawing->setCoordinates('A2'); // Celda donde va a contener la imagen
      $drawing->setOffsetX(25); // Alineacion horizontal
      $drawing->setOffsetY(15); // Alineacion vertial

      return $drawing;
   }

   /**
    * Exporta los datos desde una vista
    *
    * @author Carlos Moises Garcia T. - May. 19 - 2021
    * @version 1.0.0
    * 
    */
   public function view(): View {
      // Retorna la vista enviando los datos
      return view($this->view)->with('data', $this->data);
   }
}
