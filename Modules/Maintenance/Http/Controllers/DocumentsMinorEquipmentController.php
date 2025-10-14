<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateDocumentsMinorEquipmentRequest;
use Modules\Maintenance\Http\Requests\UpdateDocumentsMinorEquipmentRequest;
use Modules\Maintenance\Repositories\DocumentsMinorEquipmentRepository;
use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\DocumentsMinorEquipment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Documentos de equipos menores
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class DocumentsMinorEquipmentController extends AppBaseController {

    /** @var  DocumentsMinorEquipmentRepository */
    private $documentsMinorEquipmentRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     */
    public function __construct(DocumentsMinorEquipmentRepository $documentsMinorEquipmentRepo) {
        $this->documentsMinorEquipmentRepository = $documentsMinorEquipmentRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentsMinorEquipment.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        //Verifica que si traiga un valor
        if($request['equipmentConsume']){
            //Envia el consumo por equipos menores
            $equipmentConsumption=EquipmentMinorFuelConsumption::find($request['equipmentConsume']);
        }

        return view('maintenance::documents_minor_equipments.index')->with("equipment", $request['equipment'] ?? null)->with("equipmentConsume", $request['equipmentConsume'] ?? null)->with("id_consumption", $equipmentConsumption->mant_minor_equipment_fuel_id ?? null);
    }
    
    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        //Verifica que si exista y lo envia con documentos
        if($request['equipment']){
            $documents_minor_equipments = DocumentsMinorEquipment::where('mant_minor_equipment_fuels_id',$request['equipment'])->latest()->get();
            return $this->sendResponse($documents_minor_equipments->toArray(), trans('data_obtained_successfully'));
        }else{
            $documents_minor_equipments = DocumentsMinorEquipment::where('mant_equipment_fuel_consumption_id',$request['equipmentConsume'])->latest()->get();
            return $this->sendResponse($documents_minor_equipments->toArray(), trans('data_obtained_successfully'));
        }
        
       
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateDocumentsMinorEquipmentRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentsMinorEquipmentRequest $request) {
        if ($request['url']) {
            $input = $request->all();
            
            $input['url'] = implode(",", $input["url"]);
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Inserta el registro en la base de datos
                $documentsMinorEquipment = $this->documentsMinorEquipmentRepository->create($input);

                // Efectua los cambios realizados
                DB::commit();

                return $this->sendResponse($documentsMinorEquipment->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }else{
            return $this->sendResponse("error", 'Debe adjuntar un documento.', 'warning');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentsMinorEquipmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentsMinorEquipmentRequest $request) {

        $input = $request->all();
        
        /** @var DocumentsMinorEquipment $documentsMinorEquipment */
        $documentsMinorEquipment = $this->documentsMinorEquipmentRepository->find($id);

        if (empty($documentsMinorEquipment)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['url'] = implode(",", $input["url"]);

            // Actualiza el registro
            $documentsMinorEquipment = $this->documentsMinorEquipmentRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($documentsMinorEquipment->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un DocumentsMinorEquipment del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var DocumentsMinorEquipment $documentsMinorEquipment */
        $documentsMinorEquipment = $this->documentsMinorEquipmentRepository->find($id);

        if (empty($documentsMinorEquipment)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
        $urls=explode(",", $documentsMinorEquipment->url);
       
        foreach ($urls as $variable) {
            Storage::disk('public')->delete($variable);
        }
        
        

            // Elimina el registro
            $documentsMinorEquipment->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\DocumentsMinorEquipmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('documents_minor_equipments').'.'.$fileType;

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
