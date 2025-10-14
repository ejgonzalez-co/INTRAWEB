<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Instancia de la clase del modelo de Docente
use App\Modelo\Front\FuncionesPrincipales;

//Instancia de la clase del modelo de Departamento
use App\Modelo\Departamento;

use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{
    public function index(){

        //Consulta para retornar las ciudades
        $menus_principal = FuncionesPrincipales::menus_principal();

        //return view('welcome', compact('menus_principal'));
        //return view('tramite.Ivc.index');
        return redirect()->to('https://www.ventanillaunicavirtualquindio.gov.co/index.php');
    }


    public function eliminarAdjunto($ruta, $archivo){

       if (Storage::exists($ruta."/".$archivo)){
            Storage::delete($ruta."/".$archivo);
            return "exito";
        }else{
           return "error";
       }
    } 

    public function getCiudades(Departamento $departamento)
    {
        return $departamento->ciudades()->select('id', 'nombreCiudad')->get();
    }

    public function getDepartamentos(){
        return  $departamentos = Departamento::departamentos();
    }
}
