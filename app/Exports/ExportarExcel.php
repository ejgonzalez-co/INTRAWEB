<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportarExcel implements FromView
{
    private $vista;
    private $data;

    public function __construct($vista, $data)
    {   
        $this->vista = $vista;
        $this->data = $data;
    }

    

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
       
        return view($this->vista, $this->data);
    }
}
