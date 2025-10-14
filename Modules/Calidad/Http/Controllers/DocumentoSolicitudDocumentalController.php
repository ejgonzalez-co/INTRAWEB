<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\calidad\RequestExport;
use Modules\Calidad\Http\Requests\CreateDocumentoSolicitudDocumentalRequest;
use Modules\Calidad\Http\Requests\UpdateDocumentoSolicitudDocumentalRequest;
use Modules\Calidad\Repositories\DocumentoSolicitudDocumentalRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Calidad\Models\DocumentoSolicitudDocumental;
use Modules\Calidad\Models\DocumentoSolicitudDocumentalHistorial;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentoSolicitudDocumentalController extends AppBaseController {

    /** @var  DocumentoSolicitudDocumentalRepository */
    private $documentoSolicitudDocumentalRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentoSolicitudDocumentalRepository $documentoSolicitudDocumentalRepo) {
        $this->documentoSolicitudDocumentalRepository = $documentoSolicitudDocumentalRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentoSolicitudDocumental.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('calidad::documento_solicitud_documentals.index');

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $documento_solicitud_documentals = DocumentoSolicitudDocumental::with(["documentoTipoDocumento", "macroProceso", "proceso", "documentoSolicitudDocumentalHistorials"])->latest()->get();
        return $this->sendResponse($documento_solicitud_documentals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentoSolicitudDocumentalRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoSolicitudDocumentalRequest $request) {

        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $input["estado"] = "Solicitud en revisión";

            $usuario_solicitante = User::with("positions")->where("id", $input["users_id_solicitante"])->first();
            $input["nombre_solicitante"] = $usuario_solicitante["name"];
            $input["cargo"] = $usuario_solicitante["positions"]["nombre"];

            $usuario_responsable = User::with("positions")->where("id", $input["users_id_responsable"])->first();
            $input["funcionario_responsable"] = $usuario_responsable["name"];
            $input["cargo_responsable"] = $usuario_responsable["positions"]["nombre"];

            $input["nombre_documento"] = !empty($input["calidad_documento_id_object"]) ? $input["calidad_documento_id_object"]["titulo"] : $input["nombre_documento"];

            // Valida si se adjunto un documento
            if ($request->hasFile('adjunto')) {
                $input['adjunto'] = substr($input['adjunto']->store('public/container/calidad_documentos_' . date("Y")), 7);
            }
            // Inserta el registro en la base de datos
            $documentoSolicitudDocumental = $this->documentoSolicitudDocumentalRepository->create($input);
            $documentoSolicitudDocumental["calidad_documento_solicitud_documental_id"] = $documentoSolicitudDocumental->id;

            DocumentoSolicitudDocumentalHistorial::create($documentoSolicitudDocumental->toArray());

            // Efectua los cambios realizados
            DB::commit();

            $documentoSolicitudDocumental->proceso;
            $documentoSolicitudDocumental->macroproceso;
            $documentoSolicitudDocumental->documentoSolicitudDocumentalHistorials;
            $documentoSolicitudDocumental->documentoTipoDocumento;

            return $this->sendResponse($documentoSolicitudDocumental->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
     * @param UpdateDocumentoSolicitudDocumentalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoSolicitudDocumentalRequest $request) {

        $input = $request->all();

        /** @var DocumentoSolicitudDocumental $documentoSolicitudDocumental */
        $documentoSolicitudDocumental = $this->documentoSolicitudDocumentalRepository->find($id);

        if (empty($documentoSolicitudDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $usuario_solicitante = User::with("positions")->where("id", $input["users_id_solicitante"])->first();
            $input["nombre_solicitante"] = $usuario_solicitante["name"];
            $input["cargo"] = $usuario_solicitante["positions"]["nombre"];

            $usuario_responsable = User::with("positions")->where("id", $input["users_id_responsable"])->first();
            $input["funcionario_responsable"] = $usuario_responsable["name"];
            $input["cargo_responsable"] = $usuario_responsable["positions"]["nombre"];

            $input["nombre_documento"] = !empty($input["calidad_documento_id_object"]) ? $input["calidad_documento_id_object"]["titulo"] : $input["nombre_documento"];

            // Valida si se adjunto un documento
            if ($request->hasFile('adjunto')) {
                $input['adjunto'] = substr($input['adjunto']->store('public/container/calidad_documentos_' . date("Y")), 7);
            }
            // Actualiza el registro
            $documentoSolicitudDocumental = $this->documentoSolicitudDocumentalRepository->update($input, $id);
            $documentoSolicitudDocumental["calidad_documento_solicitud_documental_id"] = $documentoSolicitudDocumental->id;

            DocumentoSolicitudDocumentalHistorial::create($documentoSolicitudDocumental->toArray());

            // Efectua los cambios realizados
            DB::commit();

            $documentoSolicitudDocumental->proceso;
            $documentoSolicitudDocumental->macroproceso;
            $documentoSolicitudDocumental->documentoSolicitudDocumentalHistorials;
            $documentoSolicitudDocumental->documentoTipoDocumento;

            return $this->sendResponse($documentoSolicitudDocumental->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DocumentoSolicitudDocumental del almacenamiento
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var DocumentoSolicitudDocumental $documentoSolicitudDocumental */
        $documentoSolicitudDocumental = $this->documentoSolicitudDocumentalRepository->find($id);
        // Consulta todos los registros de historial de la solicitud documental
        $documentoSolicitudDocumentalHistorial = DocumentoSolicitudDocumentalHistorial::where("calidad_documento_solicitud_documental_id", $id);

        if (empty($documentoSolicitudDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina los registros de historial, antes que el registro principal de solicitud
            $documentoSolicitudDocumentalHistorial->delete();
            // Elimina el registro de la solicitud documental
            $documentoSolicitudDocumental->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
    public function export(Request $request) {
        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('documento_solicitud_documentals').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new RequestExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Descarga el archivo generado
            return Excel::download(new RequestExport('calidad::documento_solicitud_documentals.reportes.reporte_excel_solicitudes', $input['data'], 'm'), $fileName);
        }
    }

    /**
     * Exporta el historial de una solicitud documental
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 24. 2024
     * @version 1.0.0
     *
     * @param Integer $id - Id de la solicitud documental.
     * @return Response
     */
    public function exportarHistorial($id)
    {
        $historial = DocumentoSolicitudDocumentalHistorial::with(["documentoTipoDocumento", "macroProceso", "proceso"])->where('calidad_documento_solicitud_documental_id', $id)->get();

        return Excel::download(new RequestExport('calidad::documento_solicitud_documentals.reportes.reporte_historial', JwtController::generateToken($historial->toArray()), 'M'), 'Prueba.xlsx');
    }

    /**
     * Gestiona la solicitud documental actualizando su estado y observaciones, y registra la actualización en el historial.
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 24. 2024
     * @version 1.0.0
     *
     * @param Request $request Datos recibidos de la solicitud.
     * @return \Illuminate\Http\Response Respuesta con el mensaje de éxito o error.
     */
    public function gestionarSolicitudDocumental(Request $request) {
        // Obtener todos los datos del request
        $input = $request->all();
        // ID de la solicitud del documento
        $id = $input["id"];
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Iniciar un array para almacenar los datos de la solicitud
            $solicitud = [];
            // Asignar el estado de la solicitud desde el input
            $solicitud["estado"] = $input["estado_solicitud"];
            // Asignar las observaciones desde el input
            $solicitud["observaciones"] = $input["observaciones"];
            // Asignar la fecha y hora actual para la actualización
            $solicitud["updated_at"] = date("Y-m-d H:i:s");
            // Actualiza el registro
            $documentoSolicitudDocumental = $this->documentoSolicitudDocumentalRepository->update($solicitud, $id);
            // Asignar el ID de la solicitud actualizado a la variable correspondiente
            $documentoSolicitudDocumental["calidad_documento_solicitud_documental_id"] = $documentoSolicitudDocumental->id;
            // Crear un nuevo registro en el historial de solicitudes documentales usando el array convertido a partir del objeto
            DocumentoSolicitudDocumentalHistorial::create($documentoSolicitudDocumental->toArray());

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoSolicitudDocumental->toArray(), trans('Solicitud gestionada correctamente'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage().' -  Línea: '.$error->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\DocumentoSolicitudDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$error->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }
}
