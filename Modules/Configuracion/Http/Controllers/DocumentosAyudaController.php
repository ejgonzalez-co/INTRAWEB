<?php

namespace Modules\Configuracion\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Configuracion\Http\Requests\CreateDocumentosAyudaRequest;
use Modules\Configuracion\Http\Requests\UpdateDocumentosAyudaRequest;
use Modules\Configuracion\Repositories\DocumentosAyudaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Barryvdh\Snappy\Facades\SnappyImage;
use DB;
use Modules\Configuracion\Models\DocumentosAyuda;
use PhpOffice\PhpWord\IOFactory;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentosAyudaController extends AppBaseController {

    /** @var  DocumentosAyudaRepository */
    private $documentosAyudaRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentosAyudaRepository $documentosAyudaRepo) {
        $this->documentosAyudaRepository = $documentosAyudaRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentosAyuda.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole("Administrador intranet")){
            return view('configuracion::documentos_ayudas.index');
        }
        return view("auth.forbidden");
    }

    /**
     * Muestra la vista para el CRUD de DocumentosAyuda.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexPublico(Request $request) {
        return view('configuracion::documentos_ayudas.index_publico');
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
        $documentos_ayudas = DocumentosAyuda::with("users")->latest()->get();
        return $this->sendResponse($documentos_ayudas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentosAyudaRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentosAyudaRequest $request) {

        $input = $request->all();
        $input['users_id'] = Auth::user()->id;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona un documento de ayuda, de lo contrario se genera un error al usuario
            if ($request->hasFile('documento')) {
                $input['documento'] = substr($input['documento']->store('public/container/cg_documentos_ayuda_'.date("Y")), 7);
            } else {
                return $this->sendSuccess('Debe adjuntar un documento de ayuda', 'error');
            }
            // Valida si se selecciona la imagen previa del documento de ayuda
            if ($request->hasFile('imagen_previa')) {
                $input['imagen_previa'] = substr($input['imagen_previa']->store('public/container/cg_documentos_ayuda_'.date("Y")), 7);
            }

            // Condición para validar si existe algún registro de urls de videos
            if (!empty($input["videos_url_value"])) {
                $videos_url = [];

                foreach($input["videos_url_value"] as $video) {
                    // Decodificar el JSON en un array asociativo
                    $video = json_decode($video, true);
                    $videos_url[] = trim($video["video_url"]);
                }
                $input["videos_url"] = implode(",", $videos_url);
            }
            // Inserta el registro en la base de datos
            $documentosAyuda = $this->documentosAyudaRepository->create($input);
            // Si el usuario no adjunto imagen previa del documento, se genera una a partir del documento
            if(!isset($input['imagen_previa'])) {
                // Valida si es el documento principal es un word para poner la imagen previa del archivo adjuntado
                if($request->file('documento')->getMimeType() == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $request->file('documento')->getMimeType() == "application/.docx" || $request->file('documento')->getMimeType() == "application/.doc"){
                    // Ruta del archivo de Word
                    $imagen_previa = $this->wordToImage2('storage/'.$input['documento'], $documentosAyuda->id);
                    // Actualiza la imagen previa tomada de la plantilla
                    $documentosAyuda->update(["imagen_previa" => $imagen_previa]);
                }
            }
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentosAyuda->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateDocumentosAyudaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentosAyudaRequest $request) {

        $input = $request->all();

        /** @var DocumentosAyuda $documentosAyuda */
        $documentosAyuda = $this->documentosAyudaRepository->find($id);

        if (empty($documentosAyuda)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona un documento de ayuda
            if ($request->hasFile('documento')) {
                $input['documento'] = substr($input['documento']->store('public/container/cg_documentos_ayuda_'.date("Y")), 7);
            }
            // Valida si se selecciona la imagen previa del documento de ayuda
            if ($request->hasFile('imagen_previa')) {
                $input['imagen_previa'] = substr($input['imagen_previa']->store('public/container/cg_documentos_ayuda_'.date("Y")), 7);
            }

            // Condición para validar si existe algún registro de urls de videos
            if (!empty($input["videos_url_value"])) {
                $videos_url = [];

                foreach($input["videos_url_value"] as $video) {
                    // Decodificar el JSON en un array asociativo
                    $video = json_decode($video, true);
                    $videos_url[] = trim($video["video_url"]);
                }
                $input["videos_url"] = implode(",", $videos_url);
            }
            // Actualiza el registro
            $documentosAyuda = $this->documentosAyudaRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentosAyuda->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DocumentosAyuda del almacenamiento
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

        /** @var DocumentosAyuda $documentosAyuda */
        $documentosAyuda = $this->documentosAyudaRepository->find($id);

        if (empty($documentosAyuda)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $documentosAyuda->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\DocumentosAyudaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('documentos_ayudas').'.'.$fileType;

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

    public function wordToImage2($plantilla, $id) {
        // Ruta al archivo DOCX
        $docxFilePath = $plantilla;

        // Convertir el archivo DOCX a HTML
        $phpWord = IOFactory::load($docxFilePath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Generar la salida HTML en un buffer
        ob_start();
        $htmlWriter->save("php://output");
        $htmlContent = ob_get_clean();

        $image = SnappyImage::loadHTML($htmlContent)->output();

        // Guardar la imagen o hacer algo con ella
        // Por ejemplo, puedes guardarla en el sistema de archivos
        if(!file_exists(storage_path('app/public/container/cg_documentos_ayuda_'.date('Y')))) {
            mkdir(storage_path('app/public/container/cg_documentos_ayuda_'.date('Y')), 755, true);
        }

        $ruta_documento = 'container/cg_documentos_ayuda_'.date('Y').'/imagen_previa_'.$id.'.jpg';
        file_put_contents(storage_path('app/public/'.$ruta_documento), $image);

        return $ruta_documento;
    }
}
