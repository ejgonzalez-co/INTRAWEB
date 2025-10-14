<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateOilElementWearConfigurationRequest;
use Modules\Maintenance\Http\Requests\UpdateOilElementWearConfigurationRequest;
use Modules\Maintenance\Repositories\OilElementWearConfigurationRepository;
use Modules\Maintenance\Models\OilElementWearConfiguration;
use App\Http\Controllers\AppBaseController;
use App\Exports\Maintenance\RequestExport;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Maintenance\Models\OilElementWear;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class OilElementWearConfigurationController extends AppBaseController {

    /** @var  OilElementWearConfigurationRepository */
    private $oilElementWearConfigurationRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(OilElementWearConfigurationRepository $oilElementWearConfigurationRepo) {
        $this->oilElementWearConfigurationRepository = $oilElementWearConfigurationRepo;
    }

    /**
     * Muestra la vista para el CRUD de OilElementWearConfiguration.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::oil_element_wear_configurations.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $oil_element_wear_configurations = $this->oilElementWearConfigurationRepository->all();
        return $this->sendResponse($oil_element_wear_configurations->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateOilElementWearConfigurationRequest $request
     *
     * @return Response
     */
    public function store(CreateOilElementWearConfigurationRequest $request) {

        $input = $request->all();
        
        // $elementName = OilElementWearConfiguration::where("element_name","=",$input['element_name'])->get()->toArray();
        
        // if(count($elementName)){
        //     return $this->sendResponse([], "El nombre de elemento de desgaste ya se encuentra registrado,por favor ingrese uno nuevo","error");
        // }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $oilElementWearConfiguration = $this->oilElementWearConfigurationRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($oilElementWearConfiguration->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateOilElementWearConfigurationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOilElementWearConfigurationRequest $request) {

        $input = $request->all();

        /** @var OilElementWearConfiguration $oilElementWearConfiguration */
        $oilElementWearConfiguration = $this->oilElementWearConfigurationRepository->find($id);

        if (empty($oilElementWearConfiguration)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $oilElementWearConfiguration = $this->oilElementWearConfigurationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($oilElementWearConfiguration->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un OilElementWearConfiguration del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        $oilElementWearConfigurationCount = OilElementWear::where('mant_oil_element_wear_configurations_id', $id)
        ->whereHas('oils', function($query) {
            $query->whereNull('deleted_at');
        })
        ->count();

        if ($oilElementWearConfigurationCount == 0) {
            /** @var OilElementWearConfiguration $oilElementWearConfiguration */
            $oilElementWearConfiguration = $this->oilElementWearConfigurationRepository->find($id);
    
    
            if (empty($oilElementWearConfiguration)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Elimina el registro
                $oilElementWearConfiguration->delete();
    
                // Efectua los cambios realizados
                DB::commit();
    
                return $this->sendSuccess(trans('msg_success_drop'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilElementWearConfigurationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
            # code...
        }else{
            return $this->sendSuccess('No es posible eliminar este registro, ya que el elemento de desgaste de aceite está relacionado con registros en Gestión de Aceites.', 'error');
        }
    }


    /**
     * Genera el reporte de gestion de combustibles en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();
        // dd($input['data']);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];

        $fileName = date('Y-m-d H:i:s').'-'.trans('vehicle_fuel').'.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::oil_element_wear_configurations.report_excel', $input['data'],'f'), $fileName);
    }
}
