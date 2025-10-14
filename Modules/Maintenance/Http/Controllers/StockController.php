<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateStockRequest;
use Modules\Maintenance\Http\Requests\UpdateStockRequest;
use Modules\Maintenance\Repositories\StockRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Exports\Maintenance\RequestExport;
use App\Http\Controllers\AntiXSSController;
use Modules\Maintenance\Models\Stock;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class StockController extends AppBaseController {

    /** @var  StockRepository */
    private $stockRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(StockRepository $stockRepo) {
        $this->stockRepository = $stockRepo;
    }

    /**
     * Muestra la vista para el CRUD de Stock.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $typeWinery = $request["t"];
        $userAuth = Auth::user();

        // Validacion para retornar un forbidden si intentan acceder sin los permisos necesarios
        if(!$userAuth->hasRole(["Administrador de mantenimientos","mant Almacén CAM","mant Almacén Aseo"])){
            return view("auth.forbidden");
        }

        // Validacion para retornar un 404 si intentan acceder a una ruta no establecida
        if(($typeWinery !== "Aseo" && $typeWinery !== "CAM") || is_null($typeWinery)){
            return abort(404,"Esta intentado acceder a una vista no existente");
        }

        // Retorna forbidden si el almacen cam esta intentando acceder a el stock de aseo
        if($userAuth->hasRole("mant Almacén CAM") && $typeWinery === "Aseo"){
            return view("auth.forbidden");
        }

        // Retorna forbidden si el almacen aseo esta intentando acceder a el stock del cam
        if($userAuth->hasRole("mant Almacén Aseo") && $typeWinery === "CAM"){
            return view("auth.forbidden");
        }

        return view('maintenance::stocks.index',compact("typeWinery"));
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
        if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])){
            $stocks = Stock::with("StockHistories")->where("bodega",AntiXSSController::xssClean($request["t"]))->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get();

            $quantityStocks = Stock::where("bodega",AntiXSSController::xssClean($request["t"]))->whereRaw(base64_decode($request["f"]))->count();
        }
        else{
            $stocks = Stock::with("StockHistories")->where("bodega",AntiXSSController::xssClean($request["t"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get();
            $quantityStocks = Stock::where("bodega",AntiXSSController::xssClean($request["t"]))->count();
        }

        return $this->sendResponseAvanzado($stocks->toArray(), trans('data_obtained_successfully'),null,["total_registros" => $quantityStocks]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateStockRequest $request
     *
     * @return Response
     */
    public function store(CreateStockRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $stock = $this->stockRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($stock->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateStockRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStockRequest $request) {

        $input = $request->all();

        /** @var Stock $stock */
        $stock = $this->stockRepository->find($id);

        if (empty($stock)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $stock = $this->stockRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($stock->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Stock del almacenamiento
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

        /** @var Stock $stock */
        $stock = $this->stockRepository->find($id);

        if (empty($stock)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $stock->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\StockController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
                $input["data"] = Stock::where("bodega",AntiXSSController::xssClean($request["t"]))->whereRaw(base64_decode($request["f"]))->get();
            }
            else{
                $input["data"] = Stock::where("bodega",AntiXSSController::xssClean($request["t"]))->get();
            }
        }


        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('stocks').'.'.$fileType;

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
            return Excel::download(new RequestExport('maintenance::stocks.exports.xlsx', $input['data'],"F"), $fileName);
        }
    }
}
