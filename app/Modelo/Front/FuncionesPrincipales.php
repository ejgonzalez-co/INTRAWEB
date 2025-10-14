<?php

namespace App\Modelo\Front;

use Illuminate\Database\Eloquent\Model;


//Instancia para la conezión a la base de datos
use DB;

class FuncionesPrincipales extends Model
{

    //Función que que consulta el menu
    public static function menus_principal() {
        //Consulta para retornar el listado líneas
        $menus_principal = DB::table('ventanilla_menu')
        ->select('title', 'link')
        ->where([
            ['published', '=', 1],
            ['menutype', '=', 'mainmenu'],
        ])
        ->get();
        
        return $menus_principal;
    }
}
