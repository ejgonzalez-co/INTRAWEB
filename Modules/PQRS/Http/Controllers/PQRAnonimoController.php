<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\PQRS\RequestExportPQRS;
use Modules\PQRS\Http\Requests\CreatePQRRequest;
use Modules\PQRS\Http\Requests\UpdatePQRRequest;
use Modules\PQRS\Repositories\PQRRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;
use App\Mail\SendMail;
use App\Rules\ReCaptcha;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\PQRHistorial;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PQRAnonimoController extends AppBaseController
{

    /** @var  PQRRepository */
    private $pQRRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PQRRepository $pQRRepo)
    {
        $this->pQRRepository = $pQRRepo;
    }

    /**
     * Muestra la vista para el CRUD de PQR del ciudadano anónimo.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {

        return view('pqrs::p_q_r_s_ciudadano_anonimo.index');
    }

    /**
     * Muestra la vista de PQRS repositorio al ciudadano anónimo del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorioCiudadanoAnonimo(Request $request)
    {
        return view('pqrs::p_q_r_s_ciudadano_anonimo.index_repositorio');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        // Guardar la URL anterior en la sesión
        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            // Decodifica los campos filtrados
            $filtros = base64_decode($request["f"]);
            if ($filtros === "vigencia LIKE '%%' AND pqr_id LIKE '%%'" || $filtros === "vigencia LIKE '%%'") {
                return $this->sendResponseAvanzado("", trans('data_obtained_successfully'), null, ["no_consulto" => "No", "mensaje_enviado" => "Por favor ingrese un número de radicado en el filtro", "icono" => "info"]);
            }
            // Se remplaza el campo de la vigencia por el de documento que siempre tiene que ser anonimo
            $filtros = str_replace("vigencia LIKE '%%'", "documento_ciudadano = 'Anónimo'", $filtros);
            $filtros = str_replace("pqr_id LIKE", "pqr_id =", $filtros);
            $filtros = str_replace("%", "", $filtros);

            // dd($filtros);
            // Consulta el PQR que coincida con el filtro y según el paginado y los filtros de búsqueda realizados
            $p_q_r_s = PQR::with(["pqrCopia", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "pqrHistorial", "pqrCorrespondence"])->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
            // Contar el número total de registros de la consulta realizada según los filtros
            $count_p_q_r_s = PQR::whereRaw($filtros)->count();
            if ($count_p_q_r_s === 0) {
                return $this->sendResponseAvanzado($p_q_r_s, trans('data_obtained_successfully'), null, ["total_registros" => $count_p_q_r_s, "no_consulto" => "No", "mensaje_enviado" => "No se encontró ningún PQR con ese número de radicado. Por favor, verifique e intente nuevamente.", "icono" => "info"]);
            } else {
                return $this->sendResponseAvanzado($p_q_r_s, trans('data_obtained_successfully'), null, ["total_registros" => $count_p_q_r_s]);
            }
        } else {

            // Si no esta buscando por ID del PQR, retorna vacío
            $p_q_r_s = [];
            $count_p_q_r_s = 0;
            return $this->sendResponseAvanzado($p_q_r_s, trans('data_obtained_successfully'), null, [
                "no_consulto" => "No",
                "mensaje_enviado" => "<h2>¡Hola!</h2> para generar un nuevo PQR, haz clic en el botón \"<strong>Crear PQR</strong>\" . Si deseas consultar un PQR existente, introduce el número de radicado y oprima el botón \"<strong>Buscar</strong>\".",
                "icono" => "info",
                "total_registros" => $count_p_q_r_s
            ]);
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'g_recaptcha_response' => ['required', new ReCaptcha]
        ], ['g_recaptcha_response.required' => 'Se requiere el recaptcha de Google']);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePQRRequest $request
     *
     * @return Response
     */
    public function store(CreatePQRRequest $request)
    {
        // Se invoca el metodo de validación para verificar el token del reCaptcha
        $this->validator($request->all())->validate();

        $input = $request->all();
        $input = AntiXSSController::xssClean($input, ["contenido"]);

        // Vigencia = año actual
        $input["vigencia"] = date("Y");
        $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // Genera un código de verificación único para cada documento
        $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
        // Se asignan a las variables del ciudadano, Ciudadano anónimo
        $input["nombre_ciudadano"] = "Ciudadano anónimo";
        $input["documento_ciudadano"] = "Anónimo";
        // Valida si seleccionó o no el documento principal del PQR
        if (isset($input["document_pdf"])) {
            $input['document_pdf'] = implode(",", (array) $input["document_pdf"]);
        }
        // Valida si seleccionó o no un adjunto el ciudadano
        if ($input["adjunto_ciudadano"] ?? false) {
            $input['adjunto_ciudadano'] = implode(",", $input["adjunto_ciudadano"]);
        }
        //Valida si desea recibir la respuesta por correo electronico
        $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;

        // Consulta el máximo consecutivo de los PQRS
        $pqr_id = PQR::select(DB::raw("MAX(CAST(SUBSTR(pqr_id, 8) AS SIGNED)) AS consecutivo"))->where("vigencia", date("Y"))->pluck("consecutivo")->first();
        // Si ya existe un registro de PQRS, al consecutivo se incrementa en 1
        if ($pqr_id) {
            $input["pqr_id"] = date("Y") . "PQR" . ($pqr_id + 1);
        } else { // De lo contrario es el primer PQR
            $input["pqr_id"] = date("Y") . "PQR1";
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Organiza campos booleanos
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;            // Inserta el registro en la base de datos
            $pQR = $this->pQRRepository->create($input);

            $notificacion = $pQR->toArray();
            // Asigna a la foranea en el historial de pqr, el id del registro principal de PQR
            $input["pqr_pqr_id"] = $pQR->id;

            $historial = $input;
            $historial['action'] = "Creación de registro";

            // Guarda en la tabla historial de PQR
            PQRHistorial::create($historial);

            // Sincroniza las relaciones del registro
            $pQR->pqrCopia;
            $pQR->pqrLeidos;
            $pQR->pqrEjeTematico;
            $pQR->pqrTipoSolicitud;
            $pQR->funcionarioUsers;
            $pQR->ciudadanoUsers;
            $pQR->pqrAnotacions;
            $pQR->pqrHistorial;
            // Efectua los cambios realizados
            DB::commit();
            // Valida si existe un correo de ciudadano para enviar la notificación
            if (isset($input["email_ciudadano"])) {
                // Asunto del email
                $asunto = json_decode('{"subject": "Notificación del PQR ' . $pQR->pqr_id . '"}');
                // Nombre el ciudadano anónimo
                $notificacion["name"] = $input["nombre_ciudadano"];
                // Mensaje que va en el cuerpo del correo dirigido al ciudadano anónimo
                $notificacion["mensaje"] = "El radicado de su PQR es " . $pQR->pqr_id . ", el cual se encuentra en estado " . $input["estado"];
                // Envia notificacion al ciudadano anónimo


                try {
                    SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_ciudadano', $asunto, $notificacion, $input["email_ciudadano"], 'PQRSD');
                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRController 818 - ' . (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRController 821 - ' . (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $exception->getMessage());
                }
            }

            return $this->sendResponse($pQR->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $e->getMessage());
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
     * @param UpdatePQRRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePQRRequest $request)
    {

        $input = $request->all();

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Organiza campos booleanos
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;
            // Actualiza el registro
            $pQR = $this->pQRRepository->update($input, $id);
            // Asigna a la foranea en el historial de pqr, el id del registro principal de PQR
            $input["pqr_pqr_id"] = $pQR->id;
            // Guarda en la tabla historial de PQR
            PQRHistorial::create($input);
            // Sincroniza las relaciones del registro
            $pQR->pqrCopia;
            $pQR->pqrLeidos;
            $pQR->pqrEjeTematico;
            $pQR->pqrTipoSolicitud;
            $pQR->funcionarioUsers;
            $pQR->ciudadanoUsers;
            $pQR->pqrAnotacions;
            $pQR->pqrHistorial;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $e->getMessage() . ' Linea: ' . $e->getLine() . ' Id PQR: ' . $pQR['pqr_id']);
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PQR del almacenamiento
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
    public function destroy($id)
    {

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $pQR->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\PQRS\Http\Controllers\PQRAnonimoController -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();
        // dd($input);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('PQRS') . '.' . $fileType;

        // Guarda el archivo excel en ubicacion temporal
        // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

        // Descarga el archivo generado
        return Excel::download(new RequestExportPQRS('pqrs::p_q_r_s.report_excel', $input['data']), $fileName);
    }

    /**
     * Actualiza un registro especifico
     *
     * @author German Gonzalez V. - Abr. 18 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePQRRequest $request
     *
     * @return Response
     */
    public function updateFile($id, UpdatePQRRequest $request)
    {
        $input = $request->all();

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['adjunto'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pQR = $this->pQRRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\Correspondence\Http\Controllers\PQRAnonimoController -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRAnonimo', 'Modules\Correspondence\Http\Controllers\PQRAnonimoController -  Error: ' . $e->getMessage() . ' Linea: ' . $e->getLine() . ' Id: ' . $pQR['pqr_id']);
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }
}
