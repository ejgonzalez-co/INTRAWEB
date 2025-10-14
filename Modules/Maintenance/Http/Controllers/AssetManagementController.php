<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\Maintenance\RequestExport;
use Modules\Maintenance\Http\Requests\CreateAssetManagementRequest;
use Modules\Maintenance\Http\Requests\UpdateAssetManagementRequest;
use Modules\Maintenance\Repositories\AssetManagementRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Maintenance\Models\AssetManagement;
use Modules\Maintenance\Models\RequestNeed;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Illuminate\Support\Carbon;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class AssetManagementController extends AppBaseController {

    /** @var  AssetManagementRepository */
    private $assetManagementRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(AssetManagementRepository $assetManagementRepo) {
        $this->assetManagementRepository = $assetManagementRepo;
    }

    /**
     * Muestra la vista para el CRUD de AssetManagement.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::asset_managements.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])){
            $asset_managements = AssetManagement::with(["RequestNeed","Order"])->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get();

            $quantity_asset_managements = AssetManagement::whereRaw(base64_decode($request["f"]))->count();
        }
        else{
            $asset_managements = AssetManagement::with(["RequestNeed","Order"])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get();
            $quantity_asset_managements = AssetManagement::count();
        }

        return $this->sendResponseAvanzado($asset_managements->toArray(), trans('data_obtained_successfully'),null,["total_registros" => $quantity_asset_managements]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateAssetManagementRequest $request
     *
     * @return Response
     */
    public function store(CreateAssetManagementRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $assetManagement = $this->assetManagementRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($assetManagement->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateAssetManagementRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAssetManagementRequest $request) {

        $input = $request->all();

        /** @var AssetManagement $assetManagement */
        $assetManagement = $this->assetManagementRepository->find($id);

        if (empty($assetManagement)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $assetManagement = $this->assetManagementRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($assetManagement->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un AssetManagement del almacenamiento
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

        /** @var AssetManagement $assetManagement */
        $assetManagement = $this->assetManagementRepository->find($id);

        if (empty($assetManagement)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $assetManagement->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AssetManagementController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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

        if(array_key_exists("filtros", $input)) {
            if($input["filtros"] != "") {
                $input["data"] = AssetManagement::with(["Order","RequestNeed"])->whereRaw($input["filtros"])->latest()->get()->map(function($asset_managements){
                    $request_need = RequestNeed::select("activo_id")->where("consecutivo",$asset_managements["no_solicitud"])->first();
                    if(!is_null($request_need)){
                        $active = ResumeMachineryVehiclesYellow::select(["mant_asset_type_id","no_inventory"])->find($request_need["activo_id"]);
                        if(!is_null($active)){
                            $asset_managements["inventory_number"] = $active["mant_asset_type_id"] == 10 ? $active["no_inventory"] : "" ;
                        }
                        else{
                            $asset_managements["inventory_number"] = "";
                        }
                    }
                    else{
                        $asset_managements["inventory_number"] = "";
                    }

                    return $asset_managements;
                });
            }
            else{
                $input["data"] = AssetManagement::with(["Order","RequestNeed"])->latest()->get()->map(function($asset_managements){
                    $request_need = RequestNeed::select("activo_id")->where("consecutivo",$asset_managements["no_solicitud"])->first();
                    if(!is_null($request_need)){
                        $active = ResumeMachineryVehiclesYellow::select(["mant_asset_type_id","no_inventory"])->find($request_need["activo_id"]);
                        if(!is_null($active)){
                            $asset_managements["inventory_number"] = $active["mant_asset_type_id"] == 10 ? $active["no_inventory"] : "" ;
                        }
                        else{
                            $asset_managements["inventory_number"] = "";
                        }
                    }
                    else{
                        $asset_managements["inventory_number"] = "";
                    }
                    
                    return $asset_managements;
                });
            }
        }


        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('asset_managements').'.'.$fileType;

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
            return Excel::download(new RequestExport('maintenance::asset_managements.exports.xlsx', JwtController::generateToken($input['data']), "O"), 'Listado de gestión de mantenimientos.xlsx');
        }
    }

        /**
     * Organiza la exportacion de datos
     *
     * @param Request $request datos recibidos
     */
 public function exportMantenances(Request $request, $plaque)
{
    $currentYear = Carbon::now()->year;

    $mantenances = AssetManagement::where('nombre_activo', 'like', '%' . $plaque . '%')
        ->whereYear('created_at', $currentYear)
        ->get();

    return Excel::download(
        new RequestExport('maintenance::asset_managements.exports.xlsx', JwtController::generateToken($mantenances), "M"),
        'Listado de gestión de mantenimientos.xlsx'
    );
}

    public function show(int $id) {
        
            $asset_managements = AssetManagement::where('id', $id)->first();
            $quantity_asset_managements = $asset_managements->count();
       
        return $this->sendResponseAvanzado($asset_managements->toArray(), trans('data_obtained_successfully'),null,["total_registros" => $quantity_asset_managements]);
    }

}

