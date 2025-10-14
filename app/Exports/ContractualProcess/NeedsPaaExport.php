<?php

namespace App\Exports\ContractualProcess;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
class NeedsPaaExport implements FromView, WithEvents {

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
            $cellRange = 'A1:D2'; // All headers
            
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

               // Set background style
               'fill' => [
                  'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                  'startColor' => [
                     'rgb' => 'e0e0e0'
                  ]
               ],
            ]);

            $event->sheet->getStyle('A2:D'.(count($this->data)+1+1))->applyFromArray([
               'borders' => [
                  'allBorders' => [
                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                     'color' => ['argb' => '000000'],
                  ],
               ],
            ]);
   
            foreach(range('A','D') as $column){
               $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true) ;
            }
         }
      ];
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
