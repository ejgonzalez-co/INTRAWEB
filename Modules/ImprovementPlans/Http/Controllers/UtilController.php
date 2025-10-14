<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Exports\ImprovementPlans\RequestExport;
use App\Http\Controllers\SendNotificationController;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class UtilController extends AppBaseController {

    /**
     * Obtiene todos los roles existentes
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function roles() {
        $roles = Role::orderBy("name")->get();
        return $this->sendResponse($roles->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Envia correos a los destinatarios correspondientes
     *
     * @author Kleverman Salazar Florez. - Ago. 14 - 2023
     * @version 1.0.0
     *
     * @param array $mails
     * @param string $locationTemplate
     * @param $data
     * @param string $subject
     * 
     */
    public static function sendMail(array $mails,string $locationTemplate,$data,string $subject) : void{
        // Asunto del email
        $asunto = json_decode('{"subject": "'.$subject.'"}');
        SendNotificationController::SendNotification($locationTemplate, $asunto, $data, $mails, 'Planes de mejoramiento');
        // Mail::to($mails)->send(new SendMail($locationTemplate, $data, $custom));
    }


    /**
     * Exporta un certificado en formato xlsx.
     *
     * @author Kleverman Salazar Florez - Ago. 07 - 2023
     * @version 1.0.0
     *
     * @param int $locationOfTheTemplate
     * @param array $data
     * @param string $finalColum
     * @param string $archiveName
     * 
     * @return object
     *
     */
    public static function exportReportToXlsxFile(string $locationOfTheTemplate, $data,string $finalColum, string $archiveName) : object{
        return Excel::download(new RequestExport($locationOfTheTemplate, $data, $finalColum), $archiveName);
    }

    /**
     * Valida los mimes de un archivo
     *
     * @author Kleverman Salazar Florez. - Oct. 25 - 2023
     * @version 1.0.0
     * 
     * @param object $file
     * @param string $storageLocation
     *
     * @return bool
     */
    public static function validateArchive(array $data,string $fieldName ,string $validations) : bool {
        $validator = Validator::make($data, [
            $fieldName => $validations,
        ]);

        return $validator->fails();
    }


    /**
     * Elimina las listas vacias de un objeto o las vuelve nulas
     *
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
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
                if ($nulleable) {
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
     * @author Kleverman Salazar Florez. - Ago. 24 - 2023
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
