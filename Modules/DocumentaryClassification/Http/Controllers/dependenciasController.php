<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\documentary\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatedependenciasRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatedependenciasRequest;
use Modules\DocumentaryClassification\Repositories\dependenciasRepository;
use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\documentary\RequestExport;
use Modules\DocumentaryClassification\Models\dependencias;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\JwtController;
use Modules\Configuracion\Models\ConfigurationGeneral;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class dependenciasController extends AppBaseController {

    /** @var  dependenciasRepository */
    private $dependenciasRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(dependenciasRepository $dependenciasRepo) {
        $this->dependenciasRepository = $dependenciasRepo;
    }

    /**
     * Muestra la vista para el CRUD de dependencias.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole("Gestión Documental Admin")){
            return view('documentaryclassification::dependencias.index');
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

        $dependencias = dependencias::with('trdList')->orderBy('nombre')->get();

        return $this->sendResponse($dependencias->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatedependenciasRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Inserta el registro en la base de datos
            $dependencias = $this->dependenciasRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();


            return $this->sendResponse($dependencias->toArray(),trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function all_dependencias_inventory(){

        $dependenciasConTRD = dependencias::has('trdList')->get();
        return $this->sendResponse($dependenciasConTRD->toArray(), trans('data_obtained_successfully'));
    }



    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatedependenciasRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatedependenciasRequest $request) {

        $input = $request->all();
 
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $dependencia = $this->dependenciasRepository->find($input["id"]);
                // Valida si viene usuarios para asignar en las copias
            if (!empty($input['trd_list'])) {
                $delete = dependenciasSerieSubseries::where("id_dependencia", $input["id"])->delete();

                //recorre los destinatarios
                foreach ($input['trd_list'] as $trd) {


                    //array de destinatarios
                    $trdArray = json_decode($trd,true);
                    // $delete = dependenciasSerieSubseries::where("id_dependencia", $input["id"])->where("id_series_subseries", $trdArray["id"])->delete();

                    // dd($trdArray);
                    $trdArray["id_dependencia"] = $input["id"];
                    $trdArray["id_series_subseries"] = $trdArray["id_series_subseries"];
                    $trdArray["type"] = $trdArray["type"];
                    $trdArray["name"] = $trdArray["name"];
                    $trdArray["no_serieosubserie"] = $trdArray["no_serieosubserie"];

                    dependenciasSerieSubseries::create($trdArray);
                }
                $dependencia->trdList;

            }else{
                $delete = dependenciasSerieSubseries::where("id_dependencia", $input["id"])->delete();

            }
            

            DB::commit();

            return $this->sendResponse($dependencia->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un dependencias del almacenamiento
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

        /** @var dependencias $dependencias */
        // $dependencias = $this->dependenciasRepository->find($id);

        $dependencias = dependenciasSerieSubseries::select('id')->where('id_dependencia', (int) $id)->latest()->get();

        // if (empty($dependencias)) {
        //     return $this->sendError(trans('not_found_element'), 200);
        // }

        // Inicia la transaccion
        // DB::beginTransaction();
        try {
            // Elimina el registro
            // $dependencias->delete();

            // // Efectua los cambios realizados
            // DB::commit();

            //ciclo donde se elimina el registo que tiene TRD de la dependencia
            foreach($dependencias as $item){
                dependenciasSerieSubseries::where('id',$item['id'])->delete();
            }

            //modifica atributos de dependecias del_confirm
            dependencias::where('id', (int) $id)->update(['del_confirm' => 0]);


            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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


        $dependencias = dependenciasSerieSubseries::with(['idDependecias','seriesOsubseries'])->orderBy('name')->get()->toArray();


        $fecha = date("Y-m-d");

        $fileType = 'Xlsx';
        $storagePath = public_path('assets/formatos/cuadro_clasificacion_v4.xlsx');

        $cellValue = 11;


        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        $configuracion = ConfigurationGeneral::first();
        if ($configuracion->logo) {
            //tratado para agregar el logo de la entidad.              
           $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
           $drawing->setPath(storage_path( 'app/public/'.$configuracion->logo)); /* put your path and image here */
           $drawing->setCoordinates('A2');
           $drawing->setWorksheet($spreadsheet->getActiveSheet());
           $drawing->setHeight(70);
           $drawing->setResizeProportional(true);
           $drawing->setOffsetX(90); // this is how
           $drawing->setOffsetY(10);
       }

        if(count($dependencias) != 1){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(12, count($dependencias)-1);
        }

        $spreadsheet->getActiveSheet()->setCellValue('G8', date("Y"));
        $spreadsheet->getActiveSheet()->setCellValue('H8', date("m"));
        $spreadsheet->getActiveSheet()->setCellValue('I8', date("d"));



        foreach ($dependencias as $value) {

            $spreadsheet->getActiveSheet()->mergeCells('C'. $cellValue . ':E' . $cellValue);
            $spreadsheet->getActiveSheet()->mergeCells('G'. $cellValue . ':I' . $cellValue);


            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, $value['id'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $value['id_dependecias'] ? $value['id_dependecias']['codigo'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, $value['id_dependecias'] ? $value['id_dependecias']['codigo_oficina_productora'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue, $value['series_osubseries'] ? $value['series_osubseries']['no_serie'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue, $value['series_osubseries'] ? $value['series_osubseries']['name_serie'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue, $value['series_osubseries'] ? $value['series_osubseries']['no_subserie'] : null);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue, $value['series_osubseries'] ? $value['series_osubseries']['name_subserie'] : null);

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







    //funcion export de dependenica especifica
    public function export_dependencia_specific(Request $request) {

        $input = $request->all();

        $dependencia = dependencias::where('id',$input['id_dependencia'])->get()->first()->toArray();

        $dependenciasSeries = dependenciasSerieSubseries::with(['idDependecias','seriesOsubseries'])->where('id_dependencia',$input['id_dependencia'])->orderBy('name')->get()->toArray();

        $fileType = 'Xlsx';
        $storagePath = public_path('assets/formatos/formato_trd.xlsx');
        $cellValue = 11;


        // Lee el archivo del storage enviado 
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($storagePath);
        $spreadsheet->setActiveSheetIndex(0);

        $spreadsheet->getActiveSheet()->setCellValue('K7', $dependencia['codigo_oficina_productora']."-".$dependencia['nombre']);

        if(count($dependenciasSeries) != 1){
            $spreadsheet->getActiveSheet()->insertNewRowBefore(12, count($dependenciasSeries)-1);
        }

        $cantidad = count($dependenciasSeries) + $cellValue;

        $spreadsheet->getActiveSheet()->setCellValue('M'.$cantidad, date("Y"));
        $spreadsheet->getActiveSheet()->setCellValue('Q'.$cantidad, date("m"));
        $spreadsheet->getActiveSheet()->setCellValue('U'.$cantidad, date("d"));


        $configuracion = ConfigurationGeneral::first();
        if ($configuracion->logo) {
            //tratado para agregar el logo de la entidad.              
           $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
           $drawing->setPath(storage_path( 'app/public/'.$configuracion->logo)); /* put your path and image here */
           $drawing->setCoordinates('A2');
           $drawing->setWorksheet($spreadsheet->getActiveSheet());
           $drawing->setHeight(70);
           $drawing->setResizeProportional(true);
           $drawing->setOffsetX(70); // this is how
           $drawing->setOffsetY(10);
       }


        foreach($dependenciasSeries as $item){

            $tipos = '';

            if($item['series_osubseries']['soport']=="Físico" || $item['series_osubseries']['soport']=="Físico y Electrónico"){
                    $soporte_fisico = 'X';
            
            }else{
                    $soporte_fisico = ' ';
            }
            
            if($item['series_osubseries']['soport']=="Electrónico" || $item['series_osubseries']['soport']=="Físico y Electrónico"){
                
                $soporte_electronico = 'X';
            
            }else{
                $soporte_electronico =  ' ';
            }
                
            if($item['series_osubseries']['confidentiality']=="Pública"){
                $confidencialidad_publica =  'X';
            
            }else{
                $confidencialidad_publica =  ' ';
            }
                
                
            if($item['series_osubseries']['confidentiality']=="Clasificada"){
                $confidencialidad_clasificada =  'X';
            
            }else{
                $confidencialidad_clasificada =  ' ';
            }
            
            if($item['series_osubseries']['confidentiality']=="Reservada"){
                $confidencialidad_reservada =  'X';
            
            }else{
                $confidencialidad_reservada =  ' ';
            }
                
            if($item['series_osubseries']['full_conversation']=="1"){
                $conservacion = 'X';
            
            }else{
                $conservacion = '';
            }
            
                
            if($item['series_osubseries']['delete']=="1"){
                $eliminacion = 'X';
            
            }else{
                $eliminacion = ' ';
            }
                
            if($item['series_osubseries']['select']=="1"){
                $seleccion = 'X';
            
            }else{
                $seleccion = ' ';
            }
                
            if($item['series_osubseries']['medium_tecnology']=="1"){
                $digitalizacion = 'X';
            
            }else{
                $digitalizacion = ' ';
            }
                
            if($item['series_osubseries']['not_transferable_central']=="1"){
                $no_trasnferible = 'X';
            
            }else{
                $no_trasnferible = ' ';
            }

            foreach ($item['series_osubseries']['types_list'] as $key => $data) {
                $tipos = $data['name']."\n\n".$tipos;

            }

            $spreadsheet->getActiveSheet()->getStyle('D'.$cellValue)->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->mergeCells('S'. $cellValue . ':U' . $cellValue);


            $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue, $item['id_dependecias']['codigo'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('B'.$cellValue, $item['series_osubseries']['no_serie'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('C'.$cellValue, $item['series_osubseries']['no_subserie'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('D'.$cellValue, $item['series_osubseries']['name_serie'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('E'.$cellValue, $item['series_osubseries']['name_subserie'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('F'.$cellValue, $tipos ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('G'.$cellValue, $item['series_osubseries']['time_gestion_archives'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('H'.$cellValue, $item['series_osubseries']['time_central_file'] ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('I'.$cellValue, $soporte_fisico ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('J'.$cellValue, $soporte_electronico ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('K'.$cellValue, $confidencialidad_publica ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('L'.$cellValue, $confidencialidad_clasificada ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('M'.$cellValue, $confidencialidad_reservada ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('N'.$cellValue, $conservacion ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('O'.$cellValue, $eliminacion ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('P'.$cellValue, $seleccion ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('Q'.$cellValue, $digitalizacion ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('R'.$cellValue, $no_trasnferible ?? null);
            $spreadsheet->getActiveSheet()->setCellValue('S'.$cellValue, $item['series_osubseries']['description_final'] ?? null);


            $cellValue++;
            $text = null;
        }
        

         // Configuraciones de los encabezados del archivo
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="FORMATO MONITOREO INFORME generar TRD excel .xlsx"');
        header('Cache-Control: max-age=0');
 
         // Exportacion del archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $fileType);
        $writer->save('php://output');
        exit;
 
        return $this->sendResponse($writer, trans('msg_success_update'));
    }
}
