<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateSetTireRequest;
use Modules\Maintenance\Http\Requests\UpdateSetTireRequest;
use Modules\Maintenance\Repositories\SetTireRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\SetTire;
use Modules\Maintenance\Models\TireReferences;
use App\Exports\Maintenance\RequestExport;
use Modules\Maintenance\Models\TireBrand;
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
class SetTireController extends AppBaseController {

    /** @var  SetTireRepository */
    private $setTireRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SetTireRepository $setTireRepo) {
        $this->setTireRepository = $setTireRepo;
    }

    /**
     * Muestra la vista para el CRUD de SetTire.
     *
     * @author José Manuel Marín Londoño. - Abr. 30 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::set_tires.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        //Obtioene todos los registro con su relacion
        $set_tires = SetTire::with(['tireBrand', 'tireAll'])->latest()->get();
        return $this->sendResponse($set_tires->toArray(), trans('data_obtained_successfully'));
    }

        /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getBrands() {
        // Obtione todas las marcas de las llantas
        $tire_brands = TireBrand::all();
        return $this->sendResponse($tire_brands->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @param CreateSetTireRequest $request
     *
     * @return Response
     */
    public function store(CreateSetTireRequest $request) {
        // Obtiene todo lo que viene por el request
        $input = $request->all();
        // Obtiene lo que viene por el campo de observacion
        $observation = $request['observation'];

        $reference=TireReferences::where('id', $input['mant_tire_all_id'])->get()->first();
        $reference->toArray();
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Valida que si el campo de observacion viene vacio entonces Guarda los demas campos y le asigna un mensaje por defecto a la observacion
            if($observation == null){
                $setTire = new SetTire();
                $setTire->mant_tire_brand_id = $input['mant_tire_brand_id'];
                $setTire->registration_date = $input['registration_date'];
                $setTire->tire_reference = $reference->name;
                $setTire->maximum_wear= $input['maximum_wear'];
                $setTire->observation = "Sin observaciones";
                // Guarda en la base de datos
                $setTire->save();
                // Manda la relacion que tiene
                $setTire->tireBrand;
            }else{

                $input['tire_reference']=$reference->name;
                // Inserta el registro en la base de datos
                $setTire = $this->setTireRepository->create($input);
                //Manda la relacion que tiene
                $setTire->tireBrand;
            }
            $setTire->tireAll;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($setTire->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author José Manuel Marín Londoñp. - Abr. 31 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSetTireRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSetTireRequest $request) {

        $input = $request->all();

        /** @var SetTire $setTire */
        $setTire = $this->setTireRepository->find($id);

        if (empty($setTire)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $setTire = $this->setTireRepository->update($input, $id);
            //Manda la relacion
            $setTire->tireBrand;
            $setTire->tireAll;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($setTire->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un SetTire del almacenamiento
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

        /** @var SetTire $setTire */
        $setTire = $this->setTireRepository->find($id);

        if (empty($setTire)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $setTire->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\SetTireController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
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
        
        return Excel::download(new RequestExport('maintenance::set_tires.report_excel',$input['data'],'e'), $fileName);
    }
}