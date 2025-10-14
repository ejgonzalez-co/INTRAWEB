<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateTireBrandRequest;
use Modules\Maintenance\Http\Requests\UpdateTireBrandRequest;
use Modules\Maintenance\Repositories\TireBrandRepository;
use Modules\Maintenance\Models\SetTire;
use Modules\Maintenance\Models\TireBrand;
use Modules\Maintenance\Models\TireReference;
use App\Http\Controllers\AppBaseController;
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
class TireBrandController extends AppBaseController {

    /** @var  TireBrandRepository */
    private $tireBrandRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TireBrandRepository $tireBrandRepo) {
        $this->tireBrandRepository = $tireBrandRepo;
    }

    /**
     * Muestra la vista para el CRUD de TireBrand.
     *
     * @author José Manuel Marín Londoño. - Agosto. 27 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::tire_brands.index');
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
        $tire_brands = TireBrand::with("TireReferences")->get();
        // $tire_brands = TireBrand::all();
        return $this->sendResponse($tire_brands->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTireBrands() {
        $tire_brands = TireBrand::select("id","brand_name")->orderBy("brand_name")->get();
        return $this->sendResponse($tire_brands->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Abr. 30 - 2021
     * @version 1.0.0
     *
     * @param CreateTireBrandRequest $request
     *
     * @return Response
     */
    public function store(CreateTireBrandRequest $request) {
        //Obtiene todo lo que trae el request
        $input = $request->all();
        //Obtiene lo que viene por el campo de descripcion
        $descripction = $request['descripction'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Valida que si el campo de descripcion viene vacio entonces Guarda los demas campos y le asigna un mensaje por defecto a la observacion
            if($descripction == null){
                $tireBrand = new TireBrand();
                $tireBrand->brand_name = $input['brand_name'];
                $tireBrand->descripction = "Sin observaciones";
                //Guarda en la base de datos
                $tireBrand->save();
            }else{
                // Inserta el registro en la base de datos
                $tireBrand = $this->tireBrandRepository->create($input);
            }

            $tireReferences = $input["tire_references"];
            $totalReferences = count($tireReferences);
            
            // Ciclo para asignar las referencias de las llantas
            for ($index = 0; $index < $totalReferences; $index++) {
                if (isset($tireReferences[$index])) {
                    $tireReference = json_decode($tireReferences[$index]);
            
                    if ($tireReference && isset($tireReference->reference_name)) {
                        $this->_createTireReference($tireBrand['id'], $tireReference->reference_name);
                    }
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $tireBrand->TireReferences;

            return $this->sendResponse($tireBrand->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateTireBrandRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTireBrandRequest $request) {

        $input = $request->all();

        /** @var TireBrand $tireBrand */
        $tireBrand = $this->tireBrandRepository->find($id);

        if (empty($tireBrand)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $tireBrand = $this->tireBrandRepository->update($input, $id);

            TireReference::where("mant_tire_brand_id",$tireBrand["id"])->delete(); // Eliminar los tipos de referencias para asignar los nuevos
            
            $tireReferences = $input["tire_references"];
            $totalReferences = count($tireReferences);
            
            // Ciclo para asignar las referencias de las llantas
            for ($index = 0; $index < $totalReferences; $index++) {
                if (isset($tireReferences[$index])) {
                    $tireReference = json_decode($tireReferences[$index]);
            
                    if ($tireReference && isset($tireReference->reference_name)) {
                        $this->_createTireReference($tireBrand['id'], $tireReference->reference_name);
                    }
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $tireBrand->TireReferences;
        
            return $this->sendResponse($tireBrand->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TireBrand del almacenamiento
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

        /** @var TireBrand $tireBrand */
        $tireBrand = $this->tireBrandRepository->find($id);

        if (empty($tireBrand)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $tireBrand->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\TireBrandController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        
        return Excel::download(new RequestExport('maintenance::tire_brands.report_excel',$input['data'],'b'), $fileName);
    }

    private function _createTireReference(int $tireBrandId, string $referenceName) : void{
        TireReference::create(["mant_tire_brand_id" => $tireBrandId,"reference_name" => $referenceName]);
    }
}