<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


/**
 * Exporta datos a excel o pdf
 *
 * @author Carlos Moises Garcia T. - Nov. 11 - 2020
 * @version 1.0.0
 */
class ExportViewExcel implements FromView
{   
    private $view;
    private $data;

    /**
     * Constructor de la clase
     *
     * @author Carlos Moises Garcia T. - Nov. 11 - 2020
     * @version 1.0.0
     * 
     * @param $view vista que se desea exportar
     * @param $data datos que se le envian a la vista
     */
    public function __construct($view ,$data){   
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Exporta los datos desde una vista
     *
     * @author Carlos Moises Garcia T. - Nov. 11 - 2020
     * @version 1.0.0
     * 
     */
    public function view(): View {
        // Retorna la vista enviando los datos
        return view($this->view)->with('data', $this->data);
    }
}
