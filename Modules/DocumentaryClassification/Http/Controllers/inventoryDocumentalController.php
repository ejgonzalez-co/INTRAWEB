<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\documentary\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreateinventoryDocumentalRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdateinventoryDocumentalRequest;
use Modules\DocumentaryClassification\Repositories\inventoryDocumentalRepository;
use App\Http\Controllers\AppBaseController;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Modules\DocumentaryClassification\Models\inventoryDocumental;
use Modules\DocumentaryClassification\Models\inventoryDocuments;
use Modules\DocumentaryClassification\Models\CriteriosBusquedaValue;
use Illuminate\Http\Request;
use App\Exports\documentary\RequestExport;
use Maatwebsite\Excel\Facades\Excel;
use Flash;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;
use Symfony\Component\Console\Input\Input;
use App\Http\Controllers\JwtController;
use Modules\Intranet\Models\User;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class inventoryDocumentalController extends AppBaseController {

    /** @var  inventoryDocumentalRepository */
    private $inventoryDocumentalRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(inventoryDocumentalRepository $inventoryDocumentalRepo) {
        $this->inventoryDocumentalRepository = $inventoryDocumentalRepo;
    }


    /**
     * Muestra la vista para el CRUD de inventoryDocumental.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Gestión Documental Admin","Gestión Documental Consulta"])){
            return view('documentaryclassification::inventory_documentals.index');
        }
        return view("auth.forbidden");
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
        $request_all = inventoryDocumental::with('seriesOsubseries','dependencia','CriteriosBusquedaValue')->latest()->get();
        $item["metadatos"] = [];
        foreach ($request_all as &$item) {
            $jsonData = json_decode($item["metadatos"], true);
        
            if (is_array($jsonData)) {
                $metadata = is_array($item["metadatos"]) ? $item["metadatos"] : [];
        
                // Verificar si $jsonData tiene al menos un elemento antes de acceder a [0]
                $firstElement = reset($jsonData); // Obtiene el primer elemento sin depender del índice
        
                if (is_string($firstElement)) {
                    $decodedAttachments = json_decode($firstElement, true);
        
                    if (is_array($decodedAttachments)) {
                        foreach ($decodedAttachments as $selectedAttachment => $attachmentData) {
                            $metadata[$selectedAttachment] = $attachmentData;
                        }
                    }
                }
        
                $item["metadatos"] = $metadata;
            }
        }

        // $inventory_documentals = $this->inventoryDocumentalRepository->all();

        return $this->sendResponse($request_all->toArray(), trans('data_obtained_successfully'));
    }

    //obtiene todos los documentos digitales indicado por el id de inventario

    public function get_documents_metadata($id){

        //guarda id
        $request_data = $id;

        //consulta para hayar los documentos asignar al select
        $request_consult_documents = inventoryDocuments::select('cd_inventory_documents.id','cd_inventory_documents.url_document_digital as name_url')
        ->where('cd_inventory_documents.id_inventory_documental', $request_data)
        ->get();


        return $this->sendResponse($request_consult_documents->toArray(), trans('data_obtained_successfully'));

    }


    public function getDocuments($id){

        //guarda id
        $request_data = $id;

     

        return $this->sendResponse($request_consult_documents->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Redirecciona a la vista de inventario documental
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateinventoryDocumentalRequest $request
     *
     * @return Response
     */
    public function indexDocumentos(Request $request)
    {
        $input = $request->toArray();
        
        return view('documentaryclassification::documentos_serie_subseries.index')->with('type',$input['type'] ?? null);
    }


    public function getAllDocumentosInventario(Request $request)
    {

        $input = $request->toArray();

        if (array_key_exists('type',$input)) {
            
            if ($input['type'] == 'Correspondencia externa enviada') {
                $data = DB::table('externa_enviada_inventario')->get()->map(function ($data) {
                    $data->tipo_correspondencia = 'Correspondencia externa enviada'; 
                    return $data;
                });

            }elseif($input['type'] == 'Correspondencia externa recibida'){
                $data = DB::table('externa_recibida_inventario')->get()->map(function ($data){
                    $data -> tipo_correspondencia = 'Correspondencia externa recibida';
                    return $data;
                });

            }elseif ($input['type'] == 'Correspondencia interna') {
                $data = DB::table('interna_inventario')->get()->map(function ($data){
                    $data -> tipo_correspondencia = 'Correspondencia interna';
                    return $data;
                });
                
            }elseif ($input['type'] == 'PQRSD') {
                $data = DB::table('pqr_inventario')->get()->map(function($data){
                    $data->tipo_correspondencia = 'PQRSD';
                    return $data;
                });

            }elseif($input['type'] == 'Documentos electrónicos'){
                $data = DB::table('documento_electronico_inventario')->get()->map(function ($data) {
                    $data->tipo_correspondencia = 'Documentos electrónicos'; 
                    return $data;
                });

            }elseif($input['type'] == 'Todos'){
                $data = DB::table('todos_inventario')->get()->map(function ($data) {
                    $data->tipo_correspondencia = 'Todos'; 
                    return $data;
                });
                
            }else{
                $data = DB::table('externa_enviada_inventario')->get()->map(function ($data) {
                    $data->tipo_correspondencia = 'Correspondencia externa enviada'; 
                    return $data;
                });
            }
        }else{
            $data = DB::table('externa_enviada_inventario')->get()->get()->map(function ($data) {
                $data->tipo_correspondencia = 'Correspondencia externa enviada'; 
                return $data;
            });

        }



       return $this->sendResponse($data->toArray(), trans('data_obtained_successfully'));

    }



    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateinventoryDocumentalRequest $request
     *
     * @return Response
     */
    public function store(CreateinventoryDocumentalRequest $request) {

        $input = $request->all();

    
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $input['attachment'] = isset($input["attachment"]) ? implode(",", $input["attachment"]) : null;

            // Inserta el registro en la base de datos
            $inventoryDocumental = $this->inventoryDocumentalRepository->create($input);
            $inventoryDocumental->seriesOsubseries;

            if (array_key_exists('criterios_value',$input)) {
                $criterios = json_decode($input['criterios_value']);

                CriteriosBusquedaValue::create([
                    'cd_inventory_documental_id' => $inventoryDocumental->id,
                    'texto_criterio' => isset($criterios->texto_criterio) ? $criterios->texto_criterio : null,
                    'lista_criterio' => isset($criterios->lista_criterio) ? $criterios->lista_criterio : null,
                    'contenido_criterio' => isset($criterios->contenido_criterio) ? $criterios->contenido_criterio : null,
                    'numero_criterio' => isset($criterios->numero_criterio) ? $criterios->numero_criterio : null,
                    'fecha_criterio' => isset($criterios->fecha_criterio) ? date("Y-m-d",strtotime($criterios->fecha_criterio)) : null,
                ]);
            }

            // dd($inventoryDocumental->toArray());

            if(!isset($inventoryDocumental['date_initial'])){
                $inventoryDocumental['date_initial'] = null;
            }
            if(!isset($inventoryDocumental['date_finish'])){
                $inventoryDocumental['date_finish'] = null;
            }
            
            // dd($inventoryDocumental->toArray());
            if(is_string($inventoryDocumental->metadatos)){

                $jsonData = json_decode($inventoryDocumental->metadatos, true);

                if ($jsonData) {
                    $metadata = is_array($inventoryDocumental->metadatos) ? $inventoryDocumental->metadatos: [];

                    foreach (json_decode($jsonData[0]) as $selectedAttachment => $attachmentData) {
                        $metadata[$selectedAttachment] = $attachmentData;
                    }

                    $inventoryDocumental->metadatos = $metadata;
                }
            }
            // Efectua los cambios realizados
            DB::commit();
            $inventoryDocumental->metadatos;
            $inventoryDocumental->dependencia;
            $inventoryDocumental->CriteriosBusquedaValue;

            return $this->sendResponse($inventoryDocumental->toArray(), trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {

            dd($e);
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateinventoryDocumentalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateinventoryDocumentalRequest $request) {

        $input = $request->all();

        $input['attachment'] = isset($input["attachment"]) ? implode(",", $input["attachment"]) : null;

        /** @var inventoryDocumental $inventoryDocumental */
        $inventoryDocumental = $this->inventoryDocumentalRepository->find($id);

        if (empty($inventoryDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $inventoryDocumental = $this->inventoryDocumentalRepository->update($input, $id);

            if (array_key_exists('criterios_value',$input)) {
                $criterios = json_decode($input['criterios_value']);

                CriteriosBusquedaValue::where('cd_inventory_documental_id',$id)->update([
                    'cd_inventory_documental_id' => $inventoryDocumental->id,
                    'texto_criterio' => isset($criterios->texto_criterio) ? $criterios->texto_criterio : null,
                    'lista_criterio' => isset($criterios->lista_criterio) ? $criterios->lista_criterio : null,
                    'contenido_criterio' => isset($criterios->contenido_criterio) ? $criterios->contenido_criterio : null,
                    'numero_criterio' => isset($criterios->numero_criterio) ? $criterios->numero_criterio : null,
                    'fecha_criterio' => isset($criterios->fecha_criterio) ? date("Y-m-d",strtotime($criterios->fecha_criterio)) : null,
    
                ]);
            }
            // dd($inventoryDocumental->metadatos);
            $inventoryDocumental->seriesOsubseries;
            // dd($inventoryDocumental->toArray());
            if(is_string($inventoryDocumental->metadatos)){

                $jsonData = json_decode($inventoryDocumental->metadatos, true);

                if ($jsonData) {
                    $metadata = is_array($inventoryDocumental->metadatos) ? $inventoryDocumental->metadatos: [];

                    foreach (json_decode($jsonData[0]) as $selectedAttachment => $attachmentData) {
                        $metadata[$selectedAttachment] = $attachmentData;
                    }

                    $inventoryDocumental->metadatos = $metadata;
                }
            }
            // Efectua los cambios realizados
            DB::commit();
            // dd($inventoryDocumental->toArray());
            $inventoryDocumental->dependencia;
            $inventoryDocumental->CriteriosBusquedaValue;
            $inventoryDocumental->metadatos;


            return $this->sendResponse($inventoryDocumental->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un inventoryDocumental del almacenamiento
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

        /** @var inventoryDocumental $inventoryDocumental */
        $inventoryDocumental = $this->inventoryDocumentalRepository->find($id);

        if (empty($inventoryDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            CriteriosBusquedaValue::where('cd_inventory_documental_id',$id)->delete();
            // Elimina el registro
            $inventoryDocumental->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\inventoryDocumentalController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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

        $data = JwtController::decodeToken($input['data']);

        $array_data = [];
        $array_data['data'] = [];
        
        function validation_topografica ($array,$item_name){

            if(isset($array->$item_name)){
                return $array->$item_name;
            }else{
                return null;
            }

        }

        foreach($data as $item){
            
            $array = [];
            
            $array['created_at'] = $item->created_at;

            $array['shelving'] = validation_topografica($item,'shelving');

            $array['tray'] = validation_topografica($item,'tray');

            $array['box'] = validation_topografica($item,'box');

            $array['file'] = validation_topografica($item,'file');

            $array['book'] = validation_topografica($item,'book');

            $consult_serie_subserie = seriesSubSeries::select('no_serie','name_serie','no_subserie','name_subserie')->where('id',$item->id_series_subseries)->first();

            if($consult_serie_subserie['no_subserie'] == null && $consult_serie_subserie['name_subserie'] == null ){
                $array['name_serie_subserie'] = $consult_serie_subserie['no_serie'].'-'.$consult_serie_subserie['name_serie'];
            }else{
                $array['name_serie_subserie'] = $consult_serie_subserie['no_serie'].'-'.$consult_serie_subserie['no_subserie'].'-'.$consult_serie_subserie['name_subserie'];
            }


            $array['description_expedient'] = $item->description_expedient;
            $array['date_initial'] = $item->date_initial;
            $array['date_finish'] = $item->date_finish;
            $array['folios'] = $item->folios;
            $array['soport'] = $item->soport;
            $array['count_documents'] = '';
            
            array_push($array_data['data'],$array);
            $array=[];

        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('Reporte_excel').'.'.$fileType;

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
            // return Excel::download(new GenericExport($input['data']), $fileName);
        
        return Excel::download(new RequestExport('documentaryclassification::inventory_documentals.report_excel', JwtController::generateToken($array_data) ,'i'),$fileName);
        }
    }

      /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
 public function exportSecondView ( Request $request)
    {

        $input = $request->all();
        $fecha = date("Y-m-d");
        $fileType = 'Xlsx';
        $storagePath = public_path('assets/formatos/inventario-documental-v4.xlsx');
        $cellValue = 19;
        $ultima_configuracion = DB::table('configuration_general')->latest()->first();
        $user = User::find(Auth::user()->id);
        $dependencia = $user->dependencies->nombre;
        $documentary_inventories = JwtController::decodeToken($input['data']);
      
    
        // Ordena el array $documentary_inventories ascendentemente por el campo 'nombre'
        usort($documentary_inventories, function ($a, $b) {
            $nombreA = isset($a->dependencia)  ? $a->dependencia->nombre : $a->nombre;
            $nombreB = isset($b->dependencia)  ? $b->dependencia->nombre : $b->nombre;
            return strcmp($nombreA, $nombreB);
        });

        // Ordena el array $documentary_inventories ascendentemente por el campo 'nombre'
        usort($documentary_inventories, function ($a, $b) {
            return strcmp($a->nombre, $b->nombre);
        });
        array_walk($documentary_inventories, fn(&$object) => $object = (array)$object);

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        if(count($documentary_inventories) != 1){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(20, count($documentary_inventories) - 1);
        }

        $spreadsheet->getActiveSheet()->setCellValue('Q12', explode('-',$fecha)[0]);
        $spreadsheet->getActiveSheet()->setCellValue('R12', explode('-',$fecha)[1]);
        $spreadsheet->getActiveSheet()->setCellValue('S12', explode('-',$fecha)[2]);

        $spreadsheet->getActiveSheet()->setCellValue('B9', 'ENTIDAD PRODUCTORA: '.  $ultima_configuracion->nombre_entidad);
        $spreadsheet->getActiveSheet()->setCellValue('B12', 'OFICINA PRODUCTORA: '.  $dependencia); 

        $logo = isset($ultima_configuracion->logo) ? storage_path( 'app/public/'.$ultima_configuracion->logo) : dirname(app_path()).'/public/assets/img/favicon.ico';
        //tratado para agregar el logo de la entidad.
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($logo); /* put your path and image here */
        $drawing->setCoordinates('B2');
        $drawing->setWorksheet($spreadsheet->getActiveSheet());
        $drawing->setHeight(70);
        $drawing->setResizeProportional(true);
        $drawing->setOffsetX(90); // this is how
        $drawing->setOffsetY(10);


        foreach ($documentary_inventories as $value) {
            if (!empty($value["tipo_correspondencia"]) && $value["tipo_correspondencia"] == "Correspondencia externa recibida") {
            $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue, 'Comunicaciones por correo' . ' - ' . $value["tipo_correspondencia"]);
            }elseif( !empty($value["tipo_correspondencia"]) && $value["tipo_correspondencia"] == "Correspondencia externa enviada" && array_key_exists('guia',$input)){
                $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue, $value['guia'] . ' - ' . $value["tipo_correspondencia"]);
            }else{
                $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue, !empty($value['dependencia']) ? $value['observation'] : ($value["tipo_correspondencia"] ?? null));
            }

            if (empty($value['dependencia']) && isset($value['documentos']) && !empty($value['documentos'])) {
            $documentos = explode(',', $value['documentos']);
            } else {
                $documentos = [];
            }

            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $value['consecutivo'] ?? 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, !empty($value['dependencia']) ? $value['series_osubseries']->no_serie : ( $value['no_serie'] ?? null ));
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, !empty($value['dependencia']) ? $value['series_osubseries']->name_serie : ( $value['name_serie'] ?? null ));
            $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue, !empty($value['dependencia']) ? $value['dependencia']->nombre : ($value['nombre'] ?? null));
            $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue, !empty($value['dependencia']) ? (!empty($value['date_initial']) ? $value['date_initial'] : 'S.F' ) : (date('Y-m-d', strtotime($value['created_at'])) ?? null));
            $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue, !empty($value['dependencia']) ? (!empty($value['date_finish']) ? $value['date_finish'] : 'S.F' ) : 'S.F');
            $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue, !empty($value['dependencia']) ? $value['box'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue, !empty($value['dependencia']) ? $value['file'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue, !empty($value['dependencia']) ? $value['book'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue, !empty($value['dependencia']) ? $value['folios'] : null);
            // $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue, !empty($value['dependencia']) ? $value['series_osubseries']->type : null);
            $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue, !empty($value['dependencia']) ? $value['total_documents'] : null);
            $spreadsheet->getActiveSheet()
            ->setCellValue('P' . $cellValue, empty($value['dependencia']) ? implode("\n", $documentos) : null);

            // Activar el ajuste de texto en esa celda
            $spreadsheet->getActiveSheet()
                ->getStyle('P' . $cellValue)
                ->getAlignment()
                ->setWrapText(true);
            $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue, empty($value['dependencia']) ? count($documentos) : null);
            
            $spreadsheet->getActiveSheet()->mergeCells('S'. $cellValue . ':T' . $cellValue);
            
            if ( !empty($value['origen']) && $value['origen'] == 'DIGITAL') {
            $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue, 'X');
                
            }elseif ( !empty($value['origen']) && $value['origen'] == 'FISICO') {
                $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue, 'X');
            }else{
                $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue, '');
                $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue, 'X');
            }

            $cellValue++;
        }

        // Configuraciones de los encabezados del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Excel inventario documental digital'.'.xlsx"');
        header('Cache-Control: max-age=0');

        // Exportacion del archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('msg_success_update'));

    }





    public function export_report(){

        $inputFileType = 'Xlsx';
        $inputFileName = public_path('assets/formatos/inventario-documental-reporte.xlsx');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $contCeldas = 7;

        $request_all = inventoryDocumental::with('seriesOsubseries','dependencia')->latest()->get()->toArray();    

        if(count($request_all) != 1){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(8, count($request_all) - 1);
        }

        foreach ($request_all as $key => $value) {


            $spreadsheet->getActiveSheet()->setCellValue('A'.$contCeldas, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('B'.$contCeldas, $value['shelving'] ? $value['shelving'] : 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('C'.$contCeldas, $value['tray'] ? $value['tray'] : 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('D'.$contCeldas, $value['box'] ? $value['box'] : 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('E'.$contCeldas, $value['file'] ? $value['file'] : 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('F'.$contCeldas, $value['book'] ? $value['book'] : 'N/A');


            
            if ($value['series_osubseries']['no_subserie'] == '') {
                $spreadsheet->getActiveSheet()->setCellValue('G'.$contCeldas, $value['series_osubseries'] ? $value['series_osubseries']['no_serie'] .' - '. $value['series_osubseries']['name_serie']: 'N/A');
            }else{
                $spreadsheet->getActiveSheet()->setCellValue('G'.$contCeldas, $value['series_osubseries'] ? $value['series_osubseries']['no_serie'] .' - '. $value['series_osubseries']['name_serie'] . '/' . $value['series_osubseries']['no_subserie'] .' - '. $value['series_osubseries']['name_subserie']: 'N/A');
            }


                if($value['date_initial']){
                    $fechaInicial = strtotime($value['date_initial']);
                    $spreadsheet->getActiveSheet()->setCellValue('H'.$contCeldas,  date("Y", $fechaInicial));
                    $spreadsheet->getActiveSheet()->setCellValue('I'.$contCeldas,  date("m", $fechaInicial));
                    $spreadsheet->getActiveSheet()->setCellValue('J'.$contCeldas,  date("d", $fechaInicial));
                }


                if($value['date_finish']){
                    $fechaFinal = strtotime($value['date_finish']);
                    $spreadsheet->getActiveSheet()->setCellValue('K'.$contCeldas,  date("Y", $fechaFinal));
                    $spreadsheet->getActiveSheet()->setCellValue('L'.$contCeldas,  date("m", $fechaFinal));
                    $spreadsheet->getActiveSheet()->setCellValue('M'.$contCeldas,  date("d", $fechaFinal));
                }



                $spreadsheet->getActiveSheet()->setCellValue('N'.$contCeldas, $value['folios'] ? $value['folios'] : 'N/A');

                if ($value['soport'] == 'Físico') {
                    $spreadsheet->getActiveSheet()->setCellValue('O'.$contCeldas, 'X');

                } elseif ($value['soport'] == 'Electrónico') {
                    $spreadsheet->getActiveSheet()->setCellValue('P'.$contCeldas, 'X');

                } else{
                    $spreadsheet->getActiveSheet()->setCellValue('Q'.$contCeldas, 'X');

                }
                

                $spreadsheet->getActiveSheet()->setCellValue('R'.$contCeldas, $value['description_expedient'] ? $value['description_expedient'] : 'N/A');



            $contCeldas++;


        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte plan mejoramiento.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('data_obtained_successfully'));
    }

}
