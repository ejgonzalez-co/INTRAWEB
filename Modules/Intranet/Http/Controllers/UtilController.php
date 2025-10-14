<?php

namespace Modules\Intranet\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UtilController extends AppBaseController {

    /**
     * Obtiene todos los roles existentes
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 07 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function roles() {
        // Selección de los roles con estado activo agrupados por el módulo asignado, concatenando los roles para represantarlos como un solo arreglo por módulo
        $roles = Role::select(DB::raw('CONCAT("[", GROUP_CONCAT(CONCAT("{\"id\": ", id,", \"name\": \"", name,"\"}")), "]") as opciones, module AS agrupador'))->where("state", "Activo")->whereNot("name", "Ciudadano")->groupBy("agrupador")->get();

        $excluirAgrupadores = [];

        // Módulos que se ocultaran los roles
        config('app.mod_mesa_ayuda') === false ? $excluirAgrupadores[] = "Mesa de ayuda" : null;
        config('app.mod_gestion_documental') === false ? $excluirAgrupadores[] = "Gestión Documental" : null;
        config('app.mod_documentos') === false ? $excluirAgrupadores[] = "Documentos Electrónicos" : null;
        config('app.mod_calidad') === false ? $excluirAgrupadores[] = "Calidad" : null;
        config('app.mod_expedientes') === false ? $excluirAgrupadores[] = "Expedientes" : null;

        if (!empty($excluirAgrupadores)) {
            $roles = $roles->reject(fn($item) => in_array($item['agrupador'], $excluirAgrupadores));
        }
        
        return $this->sendResponse($roles->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Elimina las listas vacias de un objeto o las vuelve nulas
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 07 - 2020
     * @version 1.0.0
     *
     * @param Object $object objeto contenedor de propiedades
     * @param Boolean $nulleable valida si el areglo debe hacerce nulo
     * @param Array $properties propiedades de objeto
     */
    public static function dropNullEmptyList($object, $nulleable, ...$properties) {
        // Recorre las propiedades a verificar
        foreach ($properties as $prop) {
            // Valida si la lista no tiene elementos
            if (count($object[$prop]) === 0) {
                // Valida si se debe asignar el valor nulo o se debe eliminar el arreglo vacio
                if ($nulleable === true) {
                    // Asigna valor nulo
                    $object[$prop] = null;
                } else {
                    // Elimina la propiedad vacia
                    unset($object[$prop]);
                }
            }
        }
        return $object;
    }

    /**
     * Convierte un arreglo con la notacion punto a arreglo normal
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 29 - 2020
     * @version 1.0.0
     *
     * @param Array $arrayDot arreglo con notacion punto
     */
    public static function arrayUndot($arrayDot) {
        $arrUndot = array();
        foreach ($arrayDot as $key => $value) {
            Arr::set($arrUndot, $key, $value);
        }
        return $arrUndot;
    }

    /**
     * Obtiene los datos de una constante dependiendo del nombre
     *
     * @author Carlos Moises Garcia T. - Oct. 27 - 2020
     * @version 1.0.0
     *
	 * @param nameConstant nombre de la constante a obtener
	 * 
     * @return Response
     */
	public function getConstants($nameConstant) {

		$dateConstant = config('intranet.'.$nameConstant);
		return $this->sendResponse($dateConstant, trans('data_obtained_successfully'));
	}

}
