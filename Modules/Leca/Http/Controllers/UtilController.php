<?php

namespace Modules\Leca\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $roles = Role::all()->filter(function($item, $key){
            if($item->name == 'Toma de Muestra' || $item->name == 'Recepcionista' || $item->name == 'Personal de Apoyo' || $item->name == 'Analista fisicoquímico' || $item->name == 'Analista microbiológico' || $item->name == 'Administrador Leca' || $item->name == 'Operario Externo'){
                return true;
            }
        });
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

}
