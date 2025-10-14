<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Calidad\Http\Requests\CreateDocumentoTipoDocumentoRequest;
use Modules\Calidad\Http\Requests\UpdateDocumentoTipoDocumentoRequest;
use Modules\Calidad\Repositories\DocumentoTipoDocumentoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Calidad\Models\DocumentoTipoDocumento;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentoTipoDocumentoController extends AppBaseController {

    /** @var  DocumentoTipoDocumentoRepository */
    private $documentoTipoDocumentoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentoTipoDocumentoRepository $documentoTipoDocumentoRepo) {
        $this->documentoTipoDocumentoRepository = $documentoTipoDocumentoRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentoTipoDocumento.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('calidad::documento_tipo_documentos.index');

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
        $documento_tipo_documentos = DocumentoTipoDocumento::with("tipoSistema")->latest()->get();
        return $this->sendResponse($documento_tipo_documentos->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentoTipoDocumentoRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoTipoDocumentoRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Usuario en sesión
            $user = Auth::user();
            $input["users_id"] = $user->id;
            $input["usuario_creador"] = $user->name;
            $input["orden"] = DocumentoTipoDocumento::Max("orden")->pluck("orden")->first() + 1;
            // Inserta el registro en la base de datos
            $documentoTipoDocumento = $this->documentoTipoDocumentoRepository->create($input);
            // Carga las relaciones del tipo de documento recién agregado
            $documentoTipoDocumento->tipoSistema;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoTipoDocumento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateDocumentoTipoDocumentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoTipoDocumentoRequest $request) {

        $input = $request->all();

        /** @var DocumentoTipoDocumento $documentoTipoDocumento */
        $documentoTipoDocumento = $this->documentoTipoDocumentoRepository->find($id);

        if (empty($documentoTipoDocumento)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $documentoTipoDocumento = $this->documentoTipoDocumentoRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoTipoDocumento->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DocumentoTipoDocumento del almacenamiento
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

        /** @var DocumentoTipoDocumento $documentoTipoDocumento */
        $documentoTipoDocumento = $this->documentoTipoDocumentoRepository->find($id);

        if (empty($documentoTipoDocumento)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Verifica si el tipo de documento tiene registros asociados
            $relacionDocumentos = $documentoTipoDocumento->documentos()->exists();
            // Verifica si el tipo de sistema a eliminar tiene relación con alguno de los demás componentes (valida si ya esta siendo usado)
            if($relacionDocumentos) {
                // Retorna mensaje al usuario indicándole el porqué no se puede eliminar dicho registro
                return $this->sendSuccess("No se puede eliminar este tipo de sistema porque está en uso", "warning");
            }
            // Elimina el registro
            $documentoTipoDocumento->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\DocumentoTipoDocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('documento_tipo_documentos').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Obtiene todos los elementos existentes en estado activo
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerTiposDocumentosActivos() {
        $documento_tipo_documentos = DocumentoTipoDocumento::with("tipoSistema")->where("estado", "Activo")->get();
        return $this->sendResponse($documento_tipo_documentos->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes en estado activo, esto para usar el formulario de documentos.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param Int $tipo_sistema - Id del tipo de sistema
     *
     * @return Response
     */
    public function obtenerTiposDocumentosActivosDoc($tipo_sistema) {
        $documento_tipo_documentos = DocumentoTipoDocumento::with("tipoSistema")->where("estado", "Activo")->where("calidad_tipo_sistema_id", $tipo_sistema)->get();
        return $this->sendResponse($documento_tipo_documentos->toArray(), trans('data_obtained_successfully'));
    }
}
