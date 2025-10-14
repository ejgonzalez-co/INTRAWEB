<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateInflationPressureRequest;
use Modules\Maintenance\Http\Requests\UpdateInflationPressureRequest;
use Modules\Maintenance\Repositories\InflationPressureRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\InflationPressure;
use Modules\Maintenance\Models\TireReferences;
use App\Exports\Maintenance\RequestExport;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class InflationPressureController extends AppBaseController {

    /** @var  InflationPressureRepository */
    private $inflationPressureRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(InflationPressureRepository $inflationPressureRepo) {
        $this->inflationPressureRepository = $inflationPressureRepo;
    }

    /**
     * Muestra la vista para el CRUD de InflationPressure.
     *
     * @author José Manuel Marín Londoño. - Abr. 30 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::inflation_pressures.index');
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
        $inflation_pressures = InflationPressure::with(['tireAll'])->latest()->get();
        return $this->sendResponse($inflation_pressures->toArray(), trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @param CreateInflationPressureRequest $request
     *
     * @return Response
     */
    public function store(CreateInflationPressureRequest $request) {
        //Obtiene todo lo que viene por el request
        $input = $request->all();

        $reference=TireReferences::where('id', $input['mant_tire_all_id'])->get()->first();

        //Obtiene lo que viene por el campo de observacion
        $observation = $request['observation'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Valida que si el campo de observacion viene vacio entonces Guarda los demas campos y le asigna un mensaje por defecto a la observacion
            if($observation == null){
                $inflationPressure = new InflationPressure();
                $inflationPressure->registration_date = $input['registration_date'];
                $inflationPressure->tire_reference = $reference->name;
                $inflationPressure->inflation_pressure = $input['inflation_pressure'];
                $inflationPressure->observation = "Sin observaciones";
                //Guarda en la base de datos
                $inflationPressure->save();
            }else{

                $input['tire_reference']=$reference->name;
                // Inserta el registro en la base de datos
                $inflationPressure = $this->inflationPressureRepository->create($input);
            }
            $inflationPressure->tireAll;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($inflationPressure->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateInflationPressureRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInflationPressureRequest $request) {

        $input = $request->all();

        /** @var InflationPressure $inflationPressure */
        $inflationPressure = $this->inflationPressureRepository->find($id);

        if (empty($inflationPressure)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $inflationPressure = $this->inflationPressureRepository->update($input, $id);
            $inflationPressure->tireAll;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($inflationPressure->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un InflationPressure del almacenamiento
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

        /** @var InflationPressure $inflationPressure */
        $inflationPressure = $this->inflationPressureRepository->find($id);

        if (empty($inflationPressure)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $inflationPressure->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\InflationPressureController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author José Manuel Marín Londoño. - May. 30 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('tireBrand').'.'.$fileType;
        $fileName = 'excel.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::inflation_pressures.report_excel',$input['data'],'d'), $fileName);
    }
}
