<?php

namespace Modules\NotificacionesIntraweb\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\correspondence\RequestExportCorrespondence;
use Modules\NotificacionesIntraweb\Http\Requests\CreateNotificacionesMailIntrawebRequest;
use Modules\NotificacionesIntraweb\Http\Requests\UpdateNotificacionesMailIntrawebRequest;
use Modules\NotificacionesIntraweb\Repositories\NotificacionesMailIntrawebRepository;
use App\Http\Controllers\AppBaseController;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Modules\Correspondence\Http\Controllers\UtilController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\SendNotificationController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Exception;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class NotificacionesMailIntrawebController extends AppBaseController {

    /** @var  NotificacionesMailIntrawebRepository */
    private $notificacionesMailIntrawebRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(NotificacionesMailIntrawebRepository $notificacionesMailIntrawebRepo) {
        $this->notificacionesMailIntrawebRepository = $notificacionesMailIntrawebRepo;
    }

    /**
     * Muestra la vista para el CRUD de NotificacionesMailIntraweb.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('notificacionesintraweb::notificaciones_mail_intrawebs.index');

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        // Variable para contar el número total de registros de la consulta realizada
        $count_notifications = 0;

        $request["f"] = str_replace(" ", "+", $request["f"]);

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {


            if (Auth::user()->hasRole('Administrador intranet')) {
                $notifications = NotificacionesMailIntraweb::whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy("estado_notificacion", "DESC")->latest()->get()->toArray();

                $count_notifications = NotificacionesMailIntraweb::whereRaw(base64_decode($request["f"]))->count();

            }
            // Valida si el usuario logueado usuario normal tic
            else{
                $notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();

                $count_notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->whereRaw(base64_decode($request["f"]))->count();
            }
            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados

            // Contar el número total de registros de la consulta realizada según los filtros

        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if (Auth::user()->hasRole('Administrador intranet')) {
                // Consulta los tipo de solicitudes según el paginado seleccionado
                $notifications = NotificacionesMailIntraweb::latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();

                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_notifications = NotificacionesMailIntraweb::count();
            }else{
                // Consulta los tipo de solicitudes según el paginado seleccionado
                $notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();

                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->count();
            }





        } else {

            if (Auth::user()->hasRole('Administrador intranet')) {
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $notifications = NotificacionesMailIntraweb::latest()->get()->toArray();

                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_notifications = NotificacionesMailIntraweb::count();
            }else{
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->latest()->get()->toArray();

                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_notifications = NotificacionesMailIntraweb::where('user_id',Auth::user()->id)->count();
            }


        }


        // return $this->sendResponse($notifications->toArray(), trans('data_obtained_successfully'));

        return $this->sendResponseAvanzado($notifications, trans('data_obtained_successfully'), null, ["total_registros" => $count_notifications]);
    }

    /**
     * Función para reenviar los correos de intraweb
     *
     * @author Johan David Velasco Rios - 31/05/2024
     * @version 1.0.0
     *
     * @param $request = Obtiene toda la data desde el registro que se esta reenviando el correo - object
     *
     */
    public function reenviarNotificacion (Request $request)
    {

        //Convierte $request en array
        $input = $request->toArray();

        //Busca la notificacion enviada en la base de datos
        $notificacion = NotificacionesMailIntraweb::where('id',$input['id'])->get()->first()->toArray();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            //Asigna el asunto
            $asunto = json_decode('{"subject": "'.$input['asunto_notificacion'].'"}');

            //Llama la funcion general para reenviar el correo nuevamente
            SendNotificationController::SendNotification($input['plantilla_notificacion'],$asunto,json_decode($input['datos_mail'],true),$input['correo_destinatario'],$input['modulo'],'update',$input['id_mail'] ?? null, $input['primer_mensaje_novalido'] ?? null);

            // Efectua los cambios realizados
            DB::commit();

            //consulta la informacion actualizada
            $notificacionNew = NotificacionesMailIntraweb::where('id',$input['id'])->get()->first()->toArray();

            //Valida si la informacion consultada es diferente de vacia
            if (!empty($notificacionNew)) {
                if ($notificacionNew['estado_notificacion'] == 'Enviado') {
                    //Retorna la info
                    return $this->sendResponse($notificacionNew, 'La notificación fue enviada con exito.','');
                }else {
                    return $this->sendResponse($notificacionNew, 'No se pudo entregar la notificación al correo ingresado.','error');
                }
            }else{
                throw new Exception("La variable notificacionNew esta vacia, por favor verificar", 1);
            }






        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('NotificacionesMailIntrawebController', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$error->getMessage());

            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("Hemos detectado un problema con el servidor de correos. ". config('constants.support_message'), 'info');

        } catch (\Exception $e) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('NotificacionesMailIntrawebController', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$e->getMessage().$e->getLine());
            return $this->sendErrorData("Hemos detectado un problema con el servidor de correos. ". config('constants.support_message'), 'info');

        }

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateNotificacionesMailIntrawebRequest $request
     *
     * @return Response
     */
    public function store(CreateNotificacionesMailIntrawebRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $notificacionesMailIntraweb = $this->notificacionesMailIntrawebRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($notificacionesMailIntraweb->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateNotificacionesMailIntrawebRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNotificacionesMailIntrawebRequest $request) {

        $input = $request->all();

        /** @var NotificacionesMailIntraweb $notificacionesMailIntraweb */
        $notificacionesMailIntraweb = $this->notificacionesMailIntrawebRepository->find($id);

        if (empty($notificacionesMailIntraweb)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $notificacionesMailIntraweb = $this->notificacionesMailIntrawebRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($notificacionesMailIntraweb->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un NotificacionesMailIntraweb del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var NotificacionesMailIntraweb $notificacionesMailIntraweb */
        $notificacionesMailIntraweb = $this->notificacionesMailIntrawebRepository->find($id);

        if (empty($notificacionesMailIntraweb)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $notificacionesMailIntraweb->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\NotificacionesMailIntrawebController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Leonardo FH - 2024
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();
        if (array_key_exists("filtros", $input)) {
            // Determina el query base
            $query = NotificacionesMailIntraweb::query();

            // Aplica filtros si son distintos de "estado_notificacion LIKE '%%'"
            if ($input["filtros"] != "estado_notificacion LIKE '%%'") {
                $query->whereRaw($input["filtros"]);
            }

            // Aplica el filtro por usuario si no es un Administrador intranet
            if (!Auth::user()->hasRole('Administrador intranet')) {
                $query->where('user_id', Auth::user()->id);
            }

            // Ejecuta la consulta y almacena los resultados
            $input["data"] = $query->latest()->get()->toArray();
        }
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('notificacionesintraweb').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Descarga el archivo generado
            return Excel::download(new RequestExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Descarga el archivo generado
            return Excel::download(new RequestExportCorrespondence('notificacionesintraweb::notificaciones_mail_intrawebs.report_excel',$input['data'],count($input['data']),'i'), $fileName);
        }
    }
}
