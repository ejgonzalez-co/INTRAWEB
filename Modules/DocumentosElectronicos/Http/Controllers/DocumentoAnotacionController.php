<?php

namespace Modules\DocumentosElectronicos\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentosElectronicos\Http\Requests\CreateDocumentoAnotacionRequest;
use Modules\DocumentosElectronicos\Http\Requests\UpdateDocumentoAnotacionRequest;
use Modules\DocumentosElectronicos\Repositories\DocumentoAnotacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\DocumentosElectronicos\Models\Documento;
use Modules\DocumentosElectronicos\Models\DocumentoAnotacion;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentoAnotacionController extends AppBaseController {

    /** @var  DocumentoAnotacionRepository */
    private $documentoAnotacionRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentoAnotacionRepository $documentoAnotacionRepo) {
        $this->documentoAnotacionRepository = $documentoAnotacionRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentoAnotacion.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $documentoId = base64_decode($request->de);
        $documento = Documento::where('id', $documentoId)->first();
        $documentoConsecutive = $documento["consecutivo"];
        return view('documentoselectronicos::documento_anotacions.index',compact(['documentoId','documentoConsecutive']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {
        $documento_anotacions = DocumentoAnotacion::with("users")->where("de_documento_id", $id)->latest()->get();
        return $this->sendResponse($documento_anotacions->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentoAnotacionRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoAnotacionRequest $request, $id) {

        $input = $request->all();

        $user = Auth::user();

        $input["de_documento_id"] = $id;
        $input["correspondence_internal_id"] = $id;
        $input["nombre_usuario"] = $user->fullname;
        $input["users_id"] = $user->id;
        $input["leido_por"] = $user->id;

        // Valida si no seleccionó ningún adjunto
        if($input["attached"] ?? false) {
            $input['attached'] = $input["attached"];
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $documentoAnotacion = $this->documentoAnotacionRepository->create($input);
            $documentoAnotacion->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoAnotacion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine());
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
     * @param UpdateDocumentoAnotacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoAnotacionRequest $request) {

        $input = $request->all();

        /** @var DocumentoAnotacion $documentoAnotacion */
        $documentoAnotacion = $this->documentoAnotacionRepository->find($id);

        if (empty($documentoAnotacion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $documentoAnotacion = $this->documentoAnotacionRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoAnotacion->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine(). ' Id: '.($documentoAnotacion['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DocumentoAnotacion del almacenamiento
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

        /** @var DocumentoAnotacion $documentoAnotacion */
        $documentoAnotacion = $this->documentoAnotacionRepository->find($id);

        if (empty($documentoAnotacion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $documentoAnotacion->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine(). ' Id: '.($documentoAnotacion['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('documento_anotacions').'.'.$fileType;

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
}
