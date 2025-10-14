<?php

namespace Modules\Maintenance\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Exports\ExportExcel;
use App\Exports\GenericExport;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Modules\Maintenance\Http\Requests\CreateRequestNeedOrdersRequest;
use Modules\Maintenance\Http\Requests\UpdateRequestNeedOrdersRequest;
use Modules\Maintenance\Repositories\RequestNeedOrdersRepository;
use Modules\Maintenance\Repositories\RequestNeedOrdersItemRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Maintenance\Models\RequestNeed;
use Modules\Maintenance\Models\RequestNeedOrders;
use Modules\Maintenance\Models\RequestNeedOrdersItem;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\ButgetExecution;
use App\Mail\SendMail;
use App\Http\Controllers\SendNotificationController;
use Illuminate\Support\Facades\Mail;
use Modules\Maintenance\Models\BudgetAssignation;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\HistoryOrder;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\VehicleFuel;
use Modules\Maintenance\Models\Stock;
use Modules\Maintenance\Models\StockHistory;
use Modules\Maintenance\Models\AssetManagement;
use Modules\Maintenance\Models\RequestNeedItem;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\User;
use Illuminate\Http\JsonResponse;
use Modules\Maintenance\Models\RequestNeedHistory;
use Modules\Maintenance\Repositories\RequestNeedRepository;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Support\Facades\Storage;
use Modules\Maintenance\Http\Controllers\RequestNeedController;
use Modules\Maintenance\Models\AdditionNeed;
use Modules\Maintenance\Models\AdditionSparePartActivity;
/***
 * Flujo principal del controller

    -sendEntrada

    Punto de entrada cuando se confirma una entrada al almacén.

    Recorre $input["ordenes_entradas"] y actualiza:

    RequestNeedItem

    RequestNeedOrdersItem

    Genera o actualiza Stock (aquí entra _updateProductToStock o lógica duplicada).

    Maneja validaciones de contrato disponible, factura y si es repuestos o no.

    -_updateProductToStock

    Crea o actualiza Stock y StockHistory.

    Hoy en tu versión original todavía tenía algo de lógica de conversión, pero eso ya lo movimos a sendEntrada.

    -_decreaseStockQuantity

    Disminuye stock cuando hay salidas.

    También termina llamando a _updateProductToStock en ciertos escenarios para recalcular.

    -_averageProductsIfDifferent

    Se encarga de calcular costo promedio cuando entran productos con diferente costo unitario.

    Otras funciones de apoyo

    getContractAvailableValue, getTotalValueToBeSpent, isOnlyForSparePart, etc.

    No tienen relación directa con conversión de unidades ni stock.
    * **/
class RequestNeedOrdersController extends AppBaseController {

    /** @var  RequestNeedRepository */
    private $requestNeedRepository;

    /** @var  RequestNeedOrdersRepository */
    private $requestNeedOrdersRepository;

    /** @var  RequestNeedOrdersRepository */
    private $requestNeedOrderItemRepository;

    // Id de subgerencia de aseo
    const ASSISTANT_MANAGER_SANITATION_ID = 23;

    // Id de Gestion de aseo
    const CLEANING_MANAGEMENT_ID = 19;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(RequestNeedRepository $requestNeedRepo,RequestNeedOrdersRepository $requestNeedOrdersRepo,RequestNeedOrdersItemRepository $requestNeedOrderItemsRepo) {
        $this->requestNeedRepository = $requestNeedRepo;
        $this->requestNeedOrdersRepository = $requestNeedOrdersRepo;
        $this->requestNeedOrderItemRepository = $requestNeedOrderItemsRepo;
    }

    /**
     * Muestra la vista para el CRUD de RequestNeedOrders.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $need = RequestNeed::where('id',base64_decode($request->rn))->first();
        $needId = $request->rn;
        $needConsecutivo = $need["consecutivo"] ?? null;

        return view('maintenance::request_need_orders.index',compact(['needId','needConsecutivo']));
    }

/**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request,$needId) {
        $request_need_orders = [];
        $quantity_request_need_orders = 0;

        //valida si el usuario en sesión es provedor externo
         // valida si el usuario en sesión es provedor externo
        if (session('outside')) {

            // Valida si tiene filtros realizados
            if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                $filters = base64_decode($request["f"]);

                // Caso especial: buscar por numero_contrato
                if (strpos($filters, "numero_contrato") !== false) {
                    preg_match("/numero_contrato\s+LIKE\s+'%(\d+)%'/", $filters, $contract_number);

                    $request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                        ->whereHas("solicitudPrincipal", function ($query) use ($contract_number) {
                            $query->where('rubro_objeto_contrato_id', $contract_number[1]);
                        })
                        ->where('id_proveedor_externo', session('id'))
                        ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                        ->take(base64_decode($request["pi"]))
                        ->latest()
                        ->get()
                        ->toArray();

                    $quantity_request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                        ->whereHas("solicitudPrincipal", function ($query) use ($contract_number) {
                            $query->where('rubro_objeto_contrato_id', $contract_number[1]);
                        })
                        ->where('id_proveedor_externo', session('id'))
                        ->count();
                } else {
                    // filtros normales
                    $request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                        ->where('id_proveedor_externo', session('id'))
                        ->whereRaw($filters)
                        ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                        ->take(base64_decode($request["pi"]))
                        ->latest()
                        ->get()
                        ->toArray();

                    $quantity_request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                        ->where('id_proveedor_externo', session('id'))
                        ->whereRaw($filters)
                        ->count();
                }
            } else {
                // sin filtros
                $request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                    ->where('id_proveedor_externo', session('id'))
                    ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                    ->take(base64_decode($request["pi"]))
                    ->latest()
                    ->get()
                    ->toArray();

                $quantity_request_need_orders = RequestNeedOrders::with(["solicitudPrincipal","ordenesItem"])
                    ->where('id_proveedor_externo', session('id'))
                    ->count();
            }
        } 
        
        else{



            //Si NO es mant Almacén Aseo o mant Almacén CAM
            if($needId !== "MsQs=="){


                    $request_need_orders = RequestNeedOrders::where('mant_sn_request_id',base64_decode($needId))->with('ordenesItem', 'user','histori','solicitudPrincipal')->latest()->get()->map(function ($request_need_orders){
                        $request_need_orders["encrypted_id"] = encrypt($request_need_orders["id"]);

                        // Obtiene un valor boleano si la orden contiene actividades
                        $request_need_orders["have_activities"] = array_key_exists("Actividades",array_flip(array_column($request_need_orders["ordenesItem"]->toArray(),"tipo_necesidad")));

                        // Obtiene un valor boleano si la orden contiene repuestos
                        $request_need_orders["have_spart"] = array_key_exists("Repuestos",array_flip(array_column($request_need_orders["ordenesItem"]->toArray(),"tipo_necesidad")));

                        $request_need_orders["encrypted_id"] = encrypt($request_need_orders["id"]);
                        return $request_need_orders;
                    })->toArray();

                    $quantity_request_need_orders = count($request_need_orders);
            }else{


                if (Auth::user()->hasRole('mant Almacén Aseo')) {
                    $hasFilters = isset($request["f"], $request["cp"], $request["pi"]);
                    $decodedFilter = $hasFilters ? trim(base64_decode($request["f"])) : null;


                    $baseQuery = RequestNeedOrders::where(function ($query) {
                        $query->whereIn("estado", ["Orden en trámite", "Orden Finalizada"])
                            ->where(function ($sub) {
                                $sub->where("rol_asignado_nombre", "Almacén Aseo")
                                    ->orWhere("bodega", "Almacén de Aseo");
                            });
                    })
                    ->with(['ordenesItem', 'ordenesEntradas', 'histori', 'solicitudPrincipal', 'user']);

                    if ($decodedFilter) {
                        $baseQuery->whereRaw($decodedFilter);
                    }

                    $all = $baseQuery->latest()->get()->toArray();

                    $request_need_orders = [];
                    foreach ($all as $request_need_order) {
                        $solicitud = $request_need_order["solicitud_principal"] ?? null;

                        if ($solicitud && ($solicitud["tipo_solicitud"] ?? null) === "Activo") {
                            $request_need_order["ordenes_entradas"] = array_values(array_filter($request_need_order["ordenes_entradas"], fn($e) => $e["tipo_necesidad"] == "Repuestos"));
                            $request_need_order["ordenes_item"] = array_values(array_filter($request_need_order["ordenes_item"], fn($e) => $e["tipo_necesidad"] == "Repuestos"));
                            if (count($request_need_order["ordenes_entradas"])) {
                                $request_need_orders[] = $request_need_order;
                            }
                        } else {
                            $request_need_orders[] = $request_need_order;
                        }
                    }

                    $quantity_request_need_orders = count($request_need_orders);

                    $page = intval(base64_decode($request["cp"] ?? '1'));
                    $perPage = intval(base64_decode($request["pi"] ?? '10'));
                    $offset = ($page - 1) * $perPage;
                    $request_need_orders_paginated = array_slice($request_need_orders, $offset, $perPage);

                    return $this->sendResponseAvanzado(
                        $request_need_orders_paginated,
                        trans('data_obtained_successfully'),
                        null,
                        ["total_registros" => $quantity_request_need_orders]
                    );
                }

                if (Auth::user()->hasRole('mant Almacén CAM')) {
                    $hasFilters = isset($request["f"], $request["cp"], $request["pi"]);
                    $decodedFilter = $hasFilters ? trim(base64_decode($request["f"])) : null;
                        
                    $baseQuery = RequestNeedOrders::where(function ($query) {
                            $query->where(function ($q) {
                                $q->whereIn("estado", ["Orden en trámite", "Orden Finalizada"])
                                ->where("bodega", "Almacén CAM");
                            })
                            ->orWhere(function ($q) {
                                $q->where("rol_asignado_nombre", "Almacén CAM");
                            });
                        })
                        ->with(['ordenesItem', 'ordenesEntradas', 'histori', 'solicitudPrincipal', 'user']);

                    if ($decodedFilter) {
                        $baseQuery->whereRaw($decodedFilter);
                    }

                    $all = $baseQuery->orderBy("consecutivo", "desc")->get()->toArray();

                    $request_need_orders = [];
                    foreach ($all as $request_need_order) {
                        $solicitud = $request_need_order["solicitud_principal"] ?? null;

                        if ($solicitud && ($solicitud["tipo_solicitud"] ?? null) === "Activo") {
                            $request_need_order["ordenes_entradas"] = array_values(array_filter($request_need_order["ordenes_entradas"], fn($e) => $e["tipo_necesidad"] == "Repuestos"));
                            $request_need_order["ordenes_item"] = array_values(array_filter($request_need_order["ordenes_item"], fn($e) => $e["tipo_necesidad"] == "Repuestos"));
                            if (count($request_need_order["ordenes_entradas"])) {
                                $request_need_orders[] = $request_need_order;
                            }
                        } else {
                            $request_need_orders[] = $request_need_order;
                        }
                    }

                    $quantity_request_need_orders = count($request_need_orders);

                    // paginación
                    $page = intval(base64_decode($request["cp"] ?? '1'));
                    $perPage = intval(base64_decode($request["pi"] ?? '10'));
                    $offset = ($page - 1) * $perPage;
                    $request_need_orders_paginated = array_slice($request_need_orders, $offset, $perPage);

                    return $this->sendResponseAvanzado(
                        $request_need_orders_paginated,
                        trans('data_obtained_successfully'),
                        null,
                        ["total_registros" => $quantity_request_need_orders]
                    );
                }

                 
                else if(Auth::user()->hasRole('mant Proveedor interno')){
                    $request_need_orders = RequestNeedOrders::whereIn("estado",["Orden en trámite","Orden Finalizada"])->where("rol_asignado", Auth::user()->id)->with('ordenesItem','histori','solicitudPrincipal')->latest()->get()->map(function ($request_need_orders){
                        $request_need_orders["encrypted_id"] = encrypt($request_need_orders["id"]);
                        return $request_need_orders;
                    });
                }
            }    


        }

        return $this->sendResponseAvanzado($request_need_orders, trans('data_obtained_successfully'),null,["total_registros" => $quantity_request_need_orders]);
    }

    public function getActiveMileageOrCurrentHourmeterByActive(int $activeId){
        $mileageCurrentOrHourmeter = VehicleFuel::select(["current_mileage","current_hourmeter"])->where("mant_resume_machinery_vehicles_yellow_id",$activeId)->latest()->first();

        if(empty($mileageCurrentOrHourmeter)){
            $mileageCurrentOrHourmeter = "No aplica";
        }
        else{
            if(empty($mileageCurrentOrHourmeter["current_mileage"])){
                $mileageCurrentOrHourmeter = $mileageCurrentOrHourmeter["current_hourmeter"];
            }
            else{
                $mileageCurrentOrHourmeter = $mileageCurrentOrHourmeter["current_mileage"];
            }
        }
        return $this->sendResponse($mileageCurrentOrHourmeter, trans('msg_success_save'));
    }

    public function getRequestInformationExternalProvider(int $requestId){
        $request_need_orders = RequestNeedOrders::where('id', $requestId)->first();
        return $this->sendResponse($request_need_orders->toArray(), trans('data_obtained_successfully'));
    }

    public function getCurrentMileageHourmeter(int $requestId){
        $request_need_orders = RequestNeedOrders::select("mant_sn_request_id")->where('id', $requestId)->first()->mant_sn_request_id;
        $request_need = RequestNeed::select("activo_id")->where('id', $request_need_orders)->first()->activo_id;

        $mileageCurrentOrHourmeter = VehicleFuel::select(["current_mileage","current_hourmeter"])->where("mant_resume_machinery_vehicles_yellow_id",$request_need)->latest()->first();

        if(empty($mileageCurrentOrHourmeter)){
            $mileageCurrentOrHourmeter = "No aplica";
        }
        else{
            if(empty($mileageCurrentOrHourmeter["current_mileage"])){
                $mileageCurrentOrHourmeter = $mileageCurrentOrHourmeter["current_hourmeter"];
            }
            else{
                $mileageCurrentOrHourmeter = $mileageCurrentOrHourmeter["current_mileage"];
            }
        }
        return $this->sendResponse($mileageCurrentOrHourmeter, trans('data_obtained_successfully'));
    }


    /**
     * Obtiene el kilometraje u horometro mas reciente a partir de una fecha
     *
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    public function getLatestFualManagementByDate(int $requestId,string $date){
        $request_need_orders = RequestNeedOrders::select("mant_sn_request_id")->where('id', $requestId)->first()->mant_sn_request_id;
        $request_need = RequestNeed::select("activo_id")->where('id', $request_need_orders)->first()->activo_id;
        
        $mileageCurrentOrHourmeter = VehicleFuel::select(["current_mileage","current_hourmeter"])->where("mant_resume_machinery_vehicles_yellow_id",$request_need)->whereDate("invoice_date",">=",$date)->first();

        // Valida si hay registros de la fecha seleccionada o mayor
        if(!is_null($mileageCurrentOrHourmeter)){
            if(empty($mileageCurrentOrHourmeter["current_mileage"])){
                $mileageCurrentOrHourmeter["current_hourmeter_or_mileage"] = $mileageCurrentOrHourmeter["current_hourmeter"];
            }
            else{
                $mileageCurrentOrHourmeter["current_hourmeter_or_mileage"] = $mileageCurrentOrHourmeter["current_mileage"];
            }

            return $this->sendResponse($mileageCurrentOrHourmeter->toArray(), trans('data_obtained_successfully'));
        }

        // Si no hay registros entonces busca el ultimo registro de la fecha seleccionada o mayor
        $mileageCurrentOrHourmeter = VehicleFuel::select(["current_mileage","current_hourmeter"])->where("mant_resume_machinery_vehicles_yellow_id",$request_need)->latest()->first();

        if(empty($mileageCurrentOrHourmeter["current_mileage"])){
            $mileageCurrentOrHourmeter["current_hourmeter_or_mileage"] = $mileageCurrentOrHourmeter["current_hourmeter"];
        }
        else{
            $mileageCurrentOrHourmeter["current_hourmeter_or_mileage"] = $mileageCurrentOrHourmeter["current_mileage"];
        }

        return $this->sendResponse($mileageCurrentOrHourmeter->toArray(), trans('data_obtained_successfully'));
    }

    public function finishRequest(Request $request){
        $input = $request->all();
        $input["estado"] = "Orden Finalizada";
        $requestNeedOrders = $this->requestNeedOrdersRepository->update($input, $input["id"]);
 
        // Crea el historial y modifica el estado de la solicitud principal cuando todas las ordenes estan finalizadas
        if(!$this->_havePendingOrdersToFinish($requestNeedOrders["mant_sn_request_id"])){
            $this->requestNeedRepository->update(["estado" => "Finalizada"], $requestNeedOrders["mant_sn_request_id"]);         

            $this->createRequestNeedHistory("Solicitud finalizada automaticamente desde gestión de ordenes","Finalizada",$requestNeedOrders["mant_sn_request_id"],"Se finaliza la orden");
        }
        
        $totalValueToBeDiscounted = $this->_getTotalValueToBeDiscounted($input["ordenes_item"]);

        // Si hay valor a descontar y la orden no es de salida
        if($totalValueToBeDiscounted > 0 && $input["tramite_almacen"] != "Salida Confirmada"){
            // $requestNeed = RequestNeed::select(["mant_administration_cost_items_id","rubro_objeto_contrato_id","rubro_id","consecutivo","tipo_solicitud"])->with("necesidades")->where("id",$input["mant_sn_request_id"])->first()->toArray();


            $requestNeed = RequestNeed::select([
                "id", // ¡No puede faltar!
                "mant_administration_cost_items_id",
                "rubro_objeto_contrato_id",
                "rubro_id",
                "consecutivo",
                "tipo_solicitud"
            ])
            ->with("necesidades")
            ->where("id", $input["mant_sn_request_id"])
            ->first()
            ?->toArray();

            // dd($requestNeed["necesidades"]);
            $administrationCostItems = $requestNeed["tipo_solicitud"] == "Activo" ? $this->_getAdministrationCostItem($requestNeed["rubro_objeto_contrato_id"],$requestNeed["rubro_id"],$requestNeed["tipo_solicitud"]) : $this->_getAdministrationCostItem($requestNeed["rubro_objeto_contrato_id"],$requestNeed["mant_administration_cost_items_id"],$requestNeed["tipo_solicitud"]);
            if($this->_exceedsAvailableValue($administrationCostItems["value_avaible"],$totalValueToBeDiscounted)){
                return $this->sendSuccess("La orden no puede ser guardada, ya que el valor de la orden <strong>($".number_format($totalValueToBeDiscounted) .")</strong> excede el valor disponible del rubro<strong>($". number_format($administrationCostItems["value_avaible"]) .")</strong> Si desea continuar, le recomendamos que intente modificando el valor de la orden.", 'info');
            }
            
            if($requestNeed["tipo_solicitud"] == "Activo" || $requestNeed["tipo_solicitud"] == "Inventario"){

                $this->_generateExecutionToTheItem(["rubro_objeto_contrato_id" => $requestNeed["rubro_objeto_contrato_id"],"rubro_id" => $requestNeed["rubro_id"],"consecutivo" => $requestNeed["consecutivo"], "orden_consecutivo" => $input["consecutivo"]],$totalValueToBeDiscounted,$administrationCostItems);
              
                if($requestNeed["tipo_solicitud"] == "Activo"){

                    // Genera la entrada y salida del producto en el stock
                    $spareParts = $this->_getOnlySparePart($input["ordenes_item"]);

                    $winery = $input["bodega"] === "Almacén de Aseo" ? "Aseo" : "CAM";
                    $this->_decreaseStockQuantity($spareParts,"Activo",$winery);

                    $controller = app(RequestNeedController::class); // Usamos el contenedor de Laravel
                    $result = $controller->_createNewOrderOutputFromTheWarehouse($requestNeed["id"],$spareParts,($input["bodega"] === "Almacén de Aseo" ? "Almacén de Aseo" : "Almacén CAM"));
                }
            }
        }

        $requestNeedOrders["ordenes_item"] = $input["ordenes_item"];

        HistoryOrder::create(["mant_sn_orders_id" => $requestNeedOrders["id"], "users_id" => Auth::user()->id, "nombre_usuario" => Auth::user()->name, "estado" => $input["estado"], "accion" => "Se finaliza la orden", "observacion" => $input["observacion_finalizacion"] ?? ""]);

        // Crea el registro en la vista de gestion de mantenimientos
        $this->_createRegisterToAssetManagement($requestNeedOrders);

        // Relaciones
        $requestNeedOrders->histori;
        $requestNeedOrders->ordenesItem;

        // Valida si todas las ordenes estan en tramite para cambiar el estado de la solicitud principal
        if($this->_allOrdersAreInProcess($requestNeedOrders["mant_sn_request_id"])){
            RequestNeed::where("id",$requestNeedOrders["mant_sn_request_id"])->update(["estado" => "En trámite"]);

            $this->createRequestNeedHistory("Orden en trámite automaticamente desde gestión de ordenes","En trámite",$requestNeedOrders["mant_sn_request_id"],"Se encuentra tramitando la orden");
        }

        return $this->sendResponse($requestNeedOrders, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene solo los repuestos de una orden
     *
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    public function _getOnlySparePart(array $items) : array{
        $spare_parts = [];
        foreach ($items as $key => $item) {
            $item = json_decode($item,true);

            if($item["tipo_necesidad"] === "Repuestos"){
                $spare_parts[] = $item;
            }
        }

        return $spare_parts;
    }

    /**
     * Genera un decremento en la cantidad de los productos que se encuentran en el stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _decreaseStockQuantity(array $spare_parts, string $requestType = "Activo",string $winery) : void{

        

        foreach ($spare_parts as $key => $spare_part) {
            $item = RequestNeedItem::select(["unidad_medida","valor_unitario","IVA","valor_total"])->where("descripcion_nombre",$spare_part["descripcion_nombre"])->where("mant_sn_request_id",$spare_part["mant_sn_request_needs_id"])->first()->toArray();
            $product = Stock::where("codigo",$spare_part["codigo_entrada"])->where("bodega",$winery)->first();

            // 
            $item = ["unidad_medida" => $item["unidad_medida"],"valor_unitario" => $item["valor_unitario"],"IVA" => $item["IVA"], "valor_total" => $item["valor_total"], "descripcion_nombre" => $spare_part["descripcion_nombre"],"codigo_entrada" => $spare_part["codigo_entrada"], "cantidad_entrada" => $spare_part["cantidad_entrada"]];

            // Si no existe entonces lo crea
            if(is_null($product)){
                $newProduct = $this->_createProductToStock($item,$winery);
                $this->_createProductToStockHistories($newProduct,true,$requestType);
            }else{
                // Obtiene el promedio de los precios del producto
                $currentUnitCost = $product["costo_unitario"];
                $newUnitCost = $item["valor_unitario"];
                $unitCost = $this->_averageProductsIfDifferent($product,$newUnitCost)["costo_unitario"];

                $product["cantidad_actual"] = $product["cantidad"];
                $product["costo_unitario"] = $unitCost;
                $product["cantidad"] = $product["cantidad"] - $spare_part["cantidad_entrada"];
                $productUpdated = $this->_updateProductToStock($product);
                $productUpdated["cantidad_actual"] = $product["cantidad_actual"];

                $productUpdated["cantidad"] = $spare_part["cantidad_entrada"];
                $this->_createProductToStockHistories($productUpdated,false,$requestType);
            }
        }
    }

        /**
     * Calcula el nuevo valor unitario que se guardara en el producto
     * teniendo en cuenta la cantidad disponible y el costo unitario
     * 
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    private function _averageProductsIfDifferent(Stock $product, float|int $incomingUnitCost) : Stock {
        // Valida si no hay productos disponibles entonces el nuevo valor del costo unitario es del producto entrante
        if($product["cantidad"] == 0){
            $product["costo_unitario"] = $incomingUnitCost;
        }

        // Valida Si el producto entrante y el actual son diferentes para realizar el promedio
        if($product["costo_unitario"] != $incomingUnitCost){
            $product["costo_unitario"] = $this->_getAverage($product["costo_unitario"],$incomingUnitCost);
        }

        return $product;
    }

            /**
     * Obtiene el promedio de los 2 costos unitarios para guardar en el stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _getAverage(int $currentUnitCost, int $newUnitCost) : float{
        return ($currentUnitCost + $newUnitCost) / 2;
    }

    /**
     * Genera un registro de stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _createProductToStock(array $spare_part, string $winery) : Stock{
        $newProductToStock = Stock::create(["codigo" => $spare_part["codigo_entrada"],"articulo" => $spare_part["descripcion_nombre"],"grupo" => "N/A","cantidad" => $spare_part["cantidad_entrada"],"unidad_medida" => $spare_part["unidad_medida"],"costo_unitario" => $spare_part["valor_unitario"],"iva" => $spare_part["IVA"],"total" => $spare_part["valor_total"],"bodega" => $winery]);

        return $newProductToStock;
    }

    /**
     * Actualiza un registro del stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _updateProductToStock(Stock $stock) : Stock{
        $totalAvailableValue = $stock["cantidad"] > 0 ? $stock["total"] - ($stock["costo_unitario"] * $stock["cantidad"]) : 0;
        Stock::where("id",$stock["id"])->update(["cantidad" => $stock["cantidad"], "total" => $totalAvailableValue, "costo_unitario" => $stock["costo_unitario"]]);
        $product = Stock::where("id",$stock["id"])->first();

        return $product;
    }

    /**
     * Genera un registro de historial a un producto stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _createProductToStockHistories(Stock $stock, bool $isNewProduct = false, string $requestType) : void {
        $userAuth = Auth::user();

        StockHistory::create(["stock_id" => $stock["id"],"usuario_id" => $userAuth->id,"usuario_nombre" => $userAuth->name,"accion" => "Entrada","cantidad" => $stock["cantidad"]]);

        // Agrega el retraso para evitar que el registro de salida quede primero que el registro de entrada
        sleep(.3);

        if($requestType == "Activo"){
            StockHistory::create(["stock_id" => $stock["id"],"usuario_id" => $userAuth->id,"usuario_nombre" => $userAuth->name,"accion" => "Salida","cantidad" => $stock["cantidad"]]);
        }

        $stock["cantidad"] = $isNewProduct ? 0 : $stock["cantidad_actual"];
        $stock = $this->_updateProductToStock($stock);
    }

    private function _getTotalValueToBeDiscounted(array $items) : int|float{
        $totalValueToBeDiscounted = 0;
        $iva = .19;

        // Bucle para obtener el valor total a descontar
        foreach ($items as $item) {
            $item = json_decode($item);

            if($item->tipo_necesidad == "Actividades"){
                $item->cantidad_final = empty($item->cantidad_final) || is_null($item->cantidad_final) || $item->cantidad_final == "" ? $item->cantidad : $item->cantidad_final;

                // Si el valor es 0 entonces no debe descontar del rubro
                if($item->cantidad_final != 0){
                    $requestNeedItem = RequestNeedItem::select(["valor_unitario","IVA","total_value"])->where("descripcion",$item->descripcion)->where("mant_sn_request_id",$item->mant_sn_request_needs_id)->first();

                    $totalValueToBeDiscounted += ($requestNeedItem["total_value"]) * $item->cantidad_final;
                }
            }
            else{
                $item->cantidad_entrada = is_null($item->cantidad_entrada) || $item->cantidad_entrada == "" ? $item->cantidad : $item->cantidad_entrada;
                
                // Si el valor es 0 entonces no debe descontar del rubro
                if($item->cantidad_entrada != 0){
                    $requestNeedItem = RequestNeedItem::select(["valor_unitario","IVA","total_value"])->where("descripcion",$item->descripcion)->where("mant_sn_request_id",$item->mant_sn_request_needs_id)->first();

                    $totalValueToBeDiscounted += ($requestNeedItem["total_value"]) * $item->cantidad_entrada;
                }
            }
        }

        return $totalValueToBeDiscounted ?? 0;
    }

    private function _havePendingOrdersToFinish(int $requestNeedId){
        // Obtieniendo las descripciones de las ordenes ya creadas
        $requestNeedOrdersId = RequestNeedOrders::select("id")->where("mant_sn_request_id",$requestNeedId)->pluck("id");
        $orders_item_descriptions = RequestNeedOrdersItem::select("descripcion")->whereIn("mant_sn_orders_id",$requestNeedOrdersId)->pluck("descripcion");

        $quantityPendingDescriptions = RequestNeedItem::whereNotIn("descripcion",$orders_item_descriptions)->where("mant_sn_request_id",$requestNeedId)
        ->count();

        $ordersInProcess = RequestNeedOrders::where("mant_sn_request_id",$requestNeedId)->whereIn("estado",["Orden en elaboración","Orden en trámite"])->count();

        return $ordersInProcess > 0 || $quantityPendingDescriptions > 0;
    }

        /**
     * Obtiene el nombre del rubro 
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $valueAvailable valor disponible del rubro
     * @param int $totalValueToBeDiscounted cantidad de plata a descontar
     */
    private function _getAdministrationCostItem(int $contractId, int|string $itemId, string $requestType) : object{
        // Se obtiene las asignaciones con relación al contrato
        $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $contractId)->pluck('id')->toArray();

        // Intenta convertir el rubro en un entero si da error es porque es un string ejemplo 52-ASEO y hay que separar
        $itemId = (int) $itemId;

        if($requestType == "Activo"){            
            $heading = DB::table('mant_sn_actives_heading')
            ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
            ->where("id",$itemId)
            ->whereNull("deleted_at")
            ->first();
            
            // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
            $administrationCostItem = AdministrationCostItem::whereIn('mant_budget_assignation_id', $budgetAssignationIds)
            ->where('mant_heading_id', $heading->rubro_id)
            ->where('code_cost', $heading->code_heading)
            ->where('cost_center', $heading->centro_costo_codigo)
            ->first();
        }
        else{
            // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
            $administrationCostItem = AdministrationCostItem::where('id', $itemId)
            ->first();
        }

        return $administrationCostItem;
    }

    /**
     * Validacion si el total a descontar es mayor al valor disponible
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $valueAvailable valor disponible del rubro
     * @param int $totalValueToBeDiscounted cantidad de plata a descontar
     */
    private function _exceedsAvailableValue(int $valueAvailable, int $totalValueToBeDiscounted) : bool{
        return $valueAvailable < $totalValueToBeDiscounted;
    }

    /**
     * Genera la ejecucion del rubro
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $itemId id del rubro
     * @param int $amountToBeDeducted cantidad de plata a descontar
     */
    private function _generateExecutionToTheItem(array $requestNeed,int $amountToBeDeducted,$administrationCostItems) : void{
        $userAuth = Auth::user();

        // Genera el nuevo valor disponible
        $newValueAvailable = $administrationCostItems->last_executed_value -  intval($amountToBeDeducted);

        // Genera el porcentaje de ejecución.
        $percentageExecutionItem = (intval($amountToBeDeducted) / $administrationCostItems->value_item  ) * 100;

        ButgetExecution::create([
            'mant_administration_cost_items_id' => $administrationCostItems->id,
            'minutes' => $requestNeed["consecutivo"],
            'date' => date("Y-m-d : H:i:s"),
            'executed_value' => $amountToBeDeducted,
            'new_value_available' => $newValueAvailable,
            'percentage_execution_item' => $percentageExecutionItem,
            'observation' => "Solicitud de identificación de necesidad ". $requestNeed["consecutivo"]. " con el consecutivo de la orden ". $requestNeed["orden_consecutivo"],
            'name_user' => $userAuth->name,
            'users_id' => $userAuth->id,
        ]);
    }

    public function createRequestNeedHistory(string $observation,string $status,int $mant_sn_request_id,string $action) : void {
        $userAuth = Auth::user();
        
        RequestNeedHistory::create([
            'users_nombre' =>  $userAuth->name,
            'users_id' => $userAuth->id,
            'observacion' => $observation,
            'estado' => $status,
            'mant_sn_request_id' => $mant_sn_request_id,
            'accion' => $action
        ]);
    }

    /**
     * Returna si ya las ordenes estan todas en proceso para cambiar el estado de la solicitud
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     * 
     * @param requestNeedId id de la solicitud de necesidad
     *
     * @return bool
     */
    private function _allOrdersAreInProcess($requestNeedId){
        $requestNeedOrdersId = RequestNeedOrders::select("id")->where("mant_sn_request_id",$requestNeedId)->pluck("id");
        $orders_item_descriptions = RequestNeedOrdersItem::select("descripcion")->whereIn("mant_sn_orders_id",$requestNeedOrdersId)->pluck("descripcion");

        $descripciones = RequestNeedItem::whereNotIn("descripcion",$orders_item_descriptions)->where("mant_sn_request_id",$requestNeedId)
        ->count();

        $ordersInProcess = RequestNeedOrders::where("mant_sn_request_id",$requestNeedId)->whereIn("estado",["Orden en elaboración","Orden Finalizada"])->count();
        return $ordersInProcess == 0 && $descripciones == 0;
    }

    private function _createRegisterToAssetManagement($serviceOrder): void{
        $requestNeed = RequestNeed::where("id",$serviceOrder["mant_sn_request_id"])->first();

        $ordersId = [];
        
        // Bucle para obtener los id de las ordenes que se necesitan crear en gestion de mantenimientos
        foreach ($serviceOrder["ordenes_item"] as $order) {
            $ordersId[] = json_decode($order)->mant_sn_request_needs_id;
        }
        
        $requestNeedItems = RequestNeedItem::select(["id","necesidad","valor_total","descripcion_nombre","cantidad_solicitada","tipo_mantenimiento","unidad_medida"])->whereIn("mant_sn_request_id",$ordersId)->get();


        
        // Bucle para crear los registros en gestion de mantenimientos
        foreach ($requestNeedItems as $requestNeedItem) {
            AssetManagement::create([
                "tipo_mantenimiento" => $requestNeedItem["tipo_mantenimiento"] ?? "No aplica", 
                'nombre_activo' => $requestNeed["activo_nombre"] ?? "No aplica",
                'kilometraje_actual' => $requestNeed["kilometraje_horometro"] ?? "No aplica",
                'kilometraje_recibido_proveedor' => $serviceOrder["mileage_or_hourmeter_received"] ?? "No aplica",
                'nombre_proveedor' => $serviceOrder["proveedor_nombre"] ?? "No aplica",
                'no_salida_almacen' => $serviceOrder["numero_salida_almacen"] ?? "No aplica",
                'no_factura' => $serviceOrder["numero_factura"] ?? "No aplica",
                'no_solicitud' => $requestNeed["consecutivo"] ?? "No aplica",
                'no_orden' => $serviceOrder["consecutivo"] ?? "No aplica",
                'actividad' => $requestNeedItem["necesidad"] == "Actividades" ? json_encode($requestNeedItem) : null,
                'repuesto' =>  json_encode($requestNeedItem),
                'unidad_medida' => $requestNeedItem["unidad_medida"] ?? "No aplica",
                'request_id' => $requestNeed["id"],
                'order_id' => $serviceOrder["id"],

            ]);
        }
    }

    public function saveExternalProviderRequest(Request $request){
        $input = $request->all();

        $action = $input["estado_proveedor"] == "Pendiente por finalizar" ? "Se notifica al administrador" : "Se finaliza la solicitud";
        $input["supplier_end_date"] = $input["estado_proveedor"] == "Pendiente por finalizar" ? null : date("Y-m-d");

        if(!empty($input["url_evidences"])){
            if(is_array($input["url_evidences"])){
                $input["url_evidences"] = implode(",",$input["url_evidences"]);
            }
        }

        $requestNeedOrders = $this->requestNeedOrdersRepository->update($input, $input["id"]);

        HistoryOrder::create(['mant_sn_orders_id'=> $requestNeedOrders->id,'users_id' => session("id"), 'nombre_usuario' => session("name"), 'estado' => $input["estado_proveedor"], 'accion' => $action, "observacion" => $input["provider_observation"]]);

        // Envia correo al administrador
        $admin_email = User::role("Administrador de mantenimientos")->first()->email;

        // Obtiene la informacion de la solicitud en general
        $request_need = RequestNeed::select(["consecutivo","id"])->where("id",$requestNeedOrders["mant_sn_request_id"])->first()->toArray();

        // Asigna valores extras
        $request_need["external_provider_name"] = session("name");
        $request_need["request_order_consecutive"] = $requestNeedOrders["consecutivo"];
        $request_need["external_provider_observation"] = $input["provider_observation"];

        $custom = json_decode('{"subject": "Notificación de identificación de necesidades"}');

        $input["estado_proveedor"] == "Pendiente por finalizar" ? SendNotificationController::SendNotification('maintenance::request_need_orders.emails.external_providers.notification_pending_finalization_request',$custom,$request_need,$admin_email,'Necesidades') : SendNotificationController::SendNotification('maintenance::request_need_orders.emails.external_providers.notification_completed_request',$custom,$request_need,$admin_email,'Identificación de necesidades');

        // Crea el historial a la solicitud de la orden del proveedor
        RequestNeedHistory::create([
            'users_nombre' =>  session("name"),
            'users_id' => session("id"),
            'observacion' => $input["provider_observation"],
            'estado' => "En trámite",
            'mant_sn_request_id' => $request_need["id"],
            'accion' => $input["estado_proveedor"] == "Pendiente por finalizar" ? "Orden #".$requestNeedOrders["consecutivo"]." pendiente por finalizar por el proveedor externo" : "Orden #".$requestNeedOrders["consecutivo"]." finalizada por el proveedor externo"
        ]);
        
        // Relaciones
        $requestNeedOrders->histori;

        return $this->sendResponse($requestNeedOrders->toArray(), trans('msg_success_update'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateRequestNeedOrdersRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestNeedOrdersRequest $request) {

        $input = $request->all();
        // Muestra mensaje de alerta para cuando no agregan a la lista
        if(empty($input["ordenes_item"])){
            return $this->sendSuccess("Por favor diligenciar el formulario y dar click en el boton de <strong>Agregar necesidad a la lista.</strong>", 'info');
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['mant_sn_request_id'] = base64_decode($input['mant_sn_request_id']);

            // Obtiene el id del rubro para validar si la cantidad de solicitudes supera la cantidad disponible del rubro
            $itemAvailableValue = RequestNeed::where("id",$input['mant_sn_request_id'])->first()->valor_disponible;

            $totalValueToBeSpent = 0;

            // Hace un loop para obtener el valor total del gasto
            foreach ($input["ordenes_item"] as $itemEncode) {
                $item = json_decode($itemEncode);
                $totalValueToBeSpent += ($item->descripcion_datos->total_value) * $item->descripcion_datos->cantidad_solicitada;
            }

            if($totalValueToBeSpent > $itemAvailableValue){
                return $this->sendSuccess("La orden no puede ser guardada, ya que el valor de la orden<strong>($". number_format($totalValueToBeSpent) .")</strong> excede el valor disponible del rubro<strong>($".number_format($itemAvailableValue).")</strong>. Si desea continuar, le recomendamos que intente modificando la orden.", 'info');
            }

            $input['users_id'] = Auth::user()->id;
            $input['usuario'] = Auth::user()->name;
            $input['estado'] = 'Orden en elaboración';
            // Inserta el registro en la base de datos
            $requestNeedOrders = $this->requestNeedOrdersRepository->create($input);
            // se guarda el id encriptado para dirigir a la vista de observación
            $requestNeedOrders["encrypted_id"] = encrypt($requestNeedOrders["id"]);

            // Borra datos de la base de datos
            RequestNeedOrdersItem::where("mant_sn_orders_id",$requestNeedOrders['id'])->delete();

            //crea las ordenes
            foreach ($input["ordenes_item"] as $datoEntrante) {
                $orden = json_decode($datoEntrante);

                // dd($orden);
                $this->_addOrden(
                    $requestNeedOrders["id"], //mant_sn_orders_id 
                    $requestNeedOrders["mant_sn_request_id"] ?? 0, //mant_sn_request_needs_id  
                    $orden->descripcion_datos->descripcion_nombre ?? '', 
                    $orden->descripcion_datos->descripcion ?? 0, //descripcion 
                    $orden->unidad ?? '',
                    $orden->cantidad ?? 0,
                    $orden->tipo_mantenimiento ?? '', 
                    $orden->observacion ?? '', 
                    'Pendiente', //estado,
                    $orden->descripcion_datos->tipo_solicitud  ?? '',
                    $orden->descripcion_datos->tipo_necesidad  ?? '',
                    !empty($orden->codigo_salida) ? $orden->codigo_salida : null,
                    $orden->descripcion_datos->id ?? null, 

                );
            }

            HistoryOrder :: create(['mant_sn_orders_id'=>$requestNeedOrders["id"],'users_id' => Auth::user()->id, 'nombre_usuario' => Auth::user()->name, 'estado' => 'Orden en elaboración', 'accion' => 'Se crea registro' ]);
            $requestNeedOrders->histori;
            $requestNeedOrders->ordenesItem;
            $requestNeedOrders->solicitudPrincipal;
           
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($requestNeedOrders->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'));
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'));
        }
    }

    /**
     * Valida si el valor del gasto excede el valor disponible del rubro.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $itemId
     * @param int $expense
     *
     * @return bool
     */
    private function _exceedsItemAvailability(int $itemId, int $expense) : bool{
        $itemAvailability = $this->_getItemAvailability($itemId);
        return $itemAvailability < $expense;
    }

    /**
     * Obtiene el valor disponible del rubro.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $id
     * @param int $itemId
     *
     * @return
     */
    private function _getItemAvailability(int $itemId){
        $itemAvailability = AdministrationCostItem::where("mant_heading_id",$itemId)->latest()->first();
        return $itemAvailability["last_executed_value"];
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateRequestNeedOrdersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequestNeedOrdersRequest $request) {
        $input = $request->all();
        /** @var RequestNeedOrders $requestNeedOrders */
        $requestNeedOrders = $this->requestNeedOrdersRepository->find($id);

        if (empty($requestNeedOrders)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $input['usuario'] = Auth::user()->name;
            $input['estado'] = 'Orden en elaboración';
            // Inserta el registro en la base de datos
            $requestNeedOrders = $this->requestNeedOrdersRepository->update($input, $id);

            // Borra datos de la base de datos
            RequestNeedOrdersItem::where("mant_sn_orders_id",$requestNeedOrders['id'])->delete();


          
            //crea las ordenes
            foreach ($input["ordenes_item"] as $datoEntrante) {
                $orden = json_decode($datoEntrante);

                $this->_addOrden(
                    $requestNeedOrders["id"], //mant_sn_orders_id 
                    $requestNeedOrders["mant_sn_request_id"] ?? 0, //mant_sn_request_needs_id  
                    $orden->descripcion_datos->descripcion_nombre ?? $orden->descripcion_nombre ?? '', 
                    $orden->descripcion_datos->descripcion ?? $orden->descripcion ?? 0, //descripcion 
                    $orden->unidad ?? '',
                    $orden->cantidad ?? 0,
                    $orden->tipo_mantenimiento ?? '', 
                    $orden->observacion ?? '', 
                    'Pendiente', //estado,
                    $orden->descripcion_datos->tipo_solicitud  ?? $orden->tipo_solicitud ?? '',
                    $orden->descripcion_datos->tipo_necesidad  ?? $orden->tipo_necesidad  ?? '',
                    $orden->codigo_salida ?? null,
                    $orden->descripcion_datos->id ?? $orden->mant_sn_request_needs_id_real ?? null, 

                ); 
            }
            HistoryOrder :: create(['mant_sn_orders_id'=>$requestNeedOrders->id,'users_id' => Auth::user()->id, 'nombre_usuario' => Auth::user()->name, 'estado' => $requestNeedOrders->estado, 'accion' => 'Se edita el registro' ]);
            $requestNeedOrders->histori;
            $requestNeedOrders->ordenesItem;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($requestNeedOrders->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'));
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'));
        }
    }


    /**
     * Elimina un RequestNeedOrders del almacenamiento
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

        /** @var RequestNeedOrders $requestNeedOrders */
        $requestNeedOrders = $this->requestNeedOrdersRepository->find($id);
        if (empty($requestNeedOrders)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
    
     if($requestNeedOrders->estado == "Orden en trámite"){

       
            //datos de la solicitud
            $datosRequest = RequestNeed::where("id", $requestNeedOrders->mant_sn_request_id)->first();

            // Se obtiene las asignaciones con relación al contrato
            $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $datosRequest->rubro_objeto_contrato_id)
                ->pluck('id');
                // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
            $administrationCostItems = AdministrationCostItem::where('mant_budget_assignation_id', $budgetAssignationIds)
                ->where('mant_heading_id', $datosRequest->rubro_id)
                ->first();



           $ButgetExecution = ButgetExecution:: where('mant_administration_cost_items_id',$administrationCostItems->id  )->first();
            if ($ButgetExecution) {
                $ButgetExecution->delete();
                }

        }


        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Elimina el registro
            $requestNeedOrders->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'));
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'));
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
        $fileName = time().'-'.trans('request_need_orders').'.'.$fileType;

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

    /**
     * Función encargada de agregar las lista de la orden de servicio
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function _addOrden($mantSnOrdersId,
        $mantSnRequestNeedsId, 
        $descripcionNombre, 
        $descripcion,
        $unidad,
        $cantidad,
        $tipoMantenimiento,
        $observacion,
        $estado,
        $tipo_solicitud,
        $tipo_necesidad,
        $codigo_salida = null,
        $mantSnRequestNeedsIdReal = null) {

        // Crea un nuevo elemento RequestNeedOrdersItem utilizando el método create()
        RequestNeedOrdersItem::create([
            'mant_sn_orders_id' => $mantSnOrdersId,
            'mant_sn_request_needs_id' => $mantSnRequestNeedsId,
            'descripcion_nombre' => $descripcionNombre,
            'descripcion' => $descripcion,
            'unidad' => $unidad,
            'cantidad' => $cantidad,
            'tipo_mantenimiento' => $tipoMantenimiento,
            'observacion' => $observacion,
            'estado' => $estado,
            'tipo_solicitud' => $tipo_solicitud,
            'tipo_necesidad' => $tipo_necesidad,
            'codigo_salida' => $codigo_salida,
            'mant_sn_request_needs_id_real' => $mantSnRequestNeedsIdReal,
        ]);
    }


    public function sendRequestOrder(Request $request) {

        $input = $request->all();
        $consecutivo = "";
        $rol_asignado_nombre = "";
        $rol_asignado = "";
        $bodega = "";
        $estado_proveedor = "";
        $requestNeedOrder = '';
     
        if($input['estado_orden'] == "Aprobar y enviar al proveedor externo"){
            $datosRequest = RequestNeed::where("id", $input['mant_sn_request_id'])->first()->toArray();

            if(is_null($datosRequest["approving_user_id"])){
                $requestNeed = $this->requestNeedRepository->update(["approving_user_id" => Auth::user()->id, "approval_date" => date("Y-m-d H:i:s"), "approval_justification" => $input["observacion"] ?? "No aplica" ], $input["mant_sn_request_id"]);
            }

            $ordersId = [];
            foreach ($input["ordenes_item"] as $orderItem) {
                $ordersId[] = json_decode($orderItem)->mant_sn_request_needs_id;
            }

            $requestNeedItems = RequestNeedItem::whereIn("id",$ordersId)
            ->get()->toArray();

            $totalValueToBeSpent = 0;
            foreach ($requestNeedItems as $requestNeedItem) {
                $totalValueToBeSpent += ($requestNeedItem["total_value"]) * $requestNeedItem["cantidad_solicitada"];
            }

            // Se asigna el proveedor externo 
            $requestNeedOrder = $this->requestNeedOrdersRepository->update(['id_proveedor_externo' => $input['proveedor_id']], $input['id']);
            //datos de la solicitud

            // Se obtiene las asignaciones con relación al contrato
            $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $datosRequest["rubro_objeto_contrato_id"])
                ->pluck('id');
                // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
            $administrationCostItems = AdministrationCostItem::where('mant_budget_assignation_id', $budgetAssignationIds)
                ->where('mant_heading_id', $datosRequest["rubro_id"])
                ->first();

            
            $rol_asignado_nombre = $input['proveedor_nombre'];
            $rol_asignado = 0;
            $tramite_almacen = "Entrada Pendiente";
            $estado_proveedor = "Pendiente";
            $bodega = $input['bodega'];


            

        }else if($input['estado_orden'] == "Aprobar y enviar al proveedor interno"){

            $user = User::find(intval($input['funcionario_id']));

            if ($user) {
                $rol_asignado_nombre = $user->name;
            }
            $rol_asignado = $input['funcionario_id'];
            $tramite_almacen = "Entrada Pendiente";
            $estado_proveedor = "Pendiente";

            
        }else{

            //Stock
            $rol_asignado = 0;
            $rol_asignado_nombre = $input["rol_asignado_nombre"];
            $tramite_almacen = "Salida Pendiente";
            $estado_proveedor = "Salida Pendiente";

        }

        if($input['estado']=="Orden en elaboración"){

            $estado = "Orden en trámite";
            $consecutivo =  $this->_getConsecutivo();
            $requestNeedOrder = $this->requestNeedOrdersRepository->update(["estado" => $estado,"consecutivo" => $consecutivo,"rol_asignado_nombre" => $rol_asignado_nombre,"rol_asignado" => $rol_asignado,"tramite_almacen" => $tramite_almacen,"bodega" => $bodega,"estado_proveedor" => $estado_proveedor], $input['id']);
    
        }

        if($input['estado_orden'] == "Aprobar y enviar al proveedor externo"){
           $requestNeedOrder['id_order'] = $requestNeedOrder->id;
            // Notifica al usuario externo, el primer parametro de esta función es 1, para identificar que se le enviará el mensaje al proveedor externo, el segundo paramero es la data.
            $this->_sendEmail('1',$requestNeedOrder);
        }else if($input['estado_orden'] == "Aprobar y enviar al proveedor interno"){
            // Notifica al usuario interno, el primer parametro de esta función es 1, para identificar que se le enviará el mensaje al proveedor interno, el segundo paramero es la data.
            $this->_sendEmail('2',$requestNeedOrder);
        }

        // Si ya la ultima orden esta en tramite debe de cambiar el estado de la solicitud
        if($this->_hasNotOrdersAssigned($input['mant_sn_request_id'])){
            RequestNeed::where("id", $input['mant_sn_request_id'])->update(["estado" => "En trámite"]);
        }

        HistoryOrder :: create(['mant_sn_orders_id'=>$requestNeedOrder->id,'users_id' => Auth::user()->id, 'nombre_usuario' => Auth::user()->name, 'estado' => $requestNeedOrder->estado, 'accion' => 'Se envía orden' ]);
        $requestNeedOrder->histori;

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }

    /**
     * Funcion encargada de generar el consecutivo de la orden
     */ 
    public function _getConsecutivo() {
     
        $cantidad = RequestNeedOrders::whereNotNull("consecutivo")->count();

        $numero = $cantidad + 1;
        $numero_rellenado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        $codigo = date("Y") . "-" . $numero_rellenado;
       
        return $codigo;
    }


    

    /**
     * Función encargada de realizar la ejecución del rubro
     *
     */ 
    public function _generarEjecucion(
        $administrationCostItemsId,
        $minutes,
        $date,
        $executedValue,
        $observation,
        $nameUser,
        $userId
    ) 
    {

        // Consulta el rubro
        $administrationCostItems = AdministrationCostItem::find($administrationCostItemsId);
        // Genera el nuevo valor disponible
        $newValueAvailable = $administrationCostItems->last_executed_value -  intval($executedValue);
        // Genera el porcentaje de ejecución.
        $percentageExecutionItem = (intval($executedValue) / $administrationCostItems->value_item  ) * 100;

        ButgetExecution::create([
            'mant_administration_cost_items_id' => $administrationCostItemsId,
            'minutes' => $minutes,
            'date' => $date,
            'executed_value' => $executedValue,
            'new_value_available' => $newValueAvailable,
            'percentage_execution_item' => $percentageExecutionItem,
            'observation' => $observation,
            'name_user' => $nameUser,
            'users_id' => $userId,
        ]);
    }

    public function finishStateProvider(Request $request) {
        $input = $request->all();
       
        $estado_proveedor = $input["estado_proveedor"];
    
        $requestNeedOrder = $this->requestNeedOrdersRepository->update(["estado_proveedor" => $estado_proveedor], $input['id']);

        HistoryOrder::create(["mant_sn_orders_id" => $requestNeedOrder["id"],"users_id" => Auth::user()->id,"nombre_usuario" => Auth::user()->name, "estado" => $input["estado_proveedor"], "observacion" => $input["observacion"] ?? "", "accion" => "Se notifica al administrador" ]);

        // Relaciones
        $requestNeedOrder->histori;

        // Obtiene el correo del administrador para enviar posteriormente un correo
        $admin_email = User::role("Administrador de mantenimientos")->first()->email;

        // Envia el correo a la jefatura
        $custom = json_decode('{"subject": "Notificación de identificación de necesidades"}');

        $requestNeedOrder["internal_provider_observation"] = $input["observacion"];
        $requestNeedOrder["request_consecutive"] = $requestNeedOrder->solicitudPrincipal->consecutivo;

        // $input["estado_proveedor"] == "Pendiente" ? Mail::to($admin_email)->send(new SendMail('maintenance::request_need_orders.emails.internal_providers.notification_pending_finalization_request', $requestNeedOrder, $custom)) : Mail::to($admin_email)->send(new SendMail('maintenance::request_need_orders.emails.internal_providers.notification_completed_request', $requestNeedOrder, $custom));

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }



    public function sendNumeroFactura(Request $request)
    {
        $input = $request->all();

        // por si acaso normalizamos igual
        $inputFactura['no_factura'] = 0;
        $inputFactura['numero_factura'] = $input['numero_factura'];
        $inputFactura['numero_entrada_almacen'] = $input['numero_entrada_almacen'];


        $requestNeedOrder = $this->requestNeedOrdersRepository->update($inputFactura, $input['id']);

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }


    public function sendEntrada(Request $request)
    {
        $input = $request->all();

        // dd($input);
        // por si acaso normalizamos igual
        $input['no_factura'] = !empty($input['no_factura']) ? 1 : 0;
        // dd($input);
        $input['tramite_almacen'] = "Entrada Confirmada";
        $input['estado_proveedor'] = "Entrada Confirmada";

        
        // Busca si ya existe una adición "En trámite" para esta orden.

        $hasProcessingAddition = AdditionSparePartActivity::where('order_id', $input['id'])
                                                        ->where('status', 'En trámite')
                                                        ->exists();
        // Si existe una adición en trámite, no se permite confirmar la entrada.    
        if ($hasProcessingAddition) {
            return $this->sendSuccess(
                "No se puede procesar la entrada. Esta orden tiene adiciones en estado 'En trámite' que deben ser gestionadas por un administrador.",
                'info'
            );
        }


        $requestNeed = RequestNeed::select([
                'id','consecutivo','rubro_objeto_contrato_id','rubro_id',
                'tipo_solicitud','mant_administration_cost_items_id','dependencias_id'
            ])
            ->with(["contratoDatos","necesidades"])
            ->where('id',$input['mant_sn_request_id'])
            ->first()
            ->toArray();



     
        $isOnlyForSparePart = $this->isOnlyForSparePart($requestNeed["necesidades"]); 
        $availableContract = $this->getContractAvailableValue(
            $requestNeed["mant_administration_cost_items_id"],
            $requestNeed["rubro_objeto_contrato_id"], $requestNeed["id"]
        );
        $totalValueToBeSpent = $this->getTotalValueToBeSpent($input["ordenes_entradas"]);

        if($isOnlyForSparePart && ($requestNeed["tipo_solicitud"] === "Activo" || $requestNeed["tipo_solicitud"] === "Inventario")){
            if($totalValueToBeSpent > $availableContract){
                return $this->sendSuccess(
                    "El valor a descontar($".number_format($totalValueToBeSpent).") es superior al valor disponible del rubro($".number_format($availableContract).")",
                    'info'
                );
            }
        }

        if(!$isOnlyForSparePart){
            RequestNeed::where("id",$input['mant_sn_request_id'])
                ->update(["invoice_no" => $input["numero_factura"] ?? null]);
        }

        // dd($input["ordenes_entradas"]);
        foreach ($input["ordenes_entradas"] as $orden_entrada) {
            $orden_entrada = json_decode($orden_entrada);

            if(is_null($orden_entrada->cantidad_entrada) || $orden_entrada->cantidad_entrada == ""){
                $orden_entrada->cantidad_entrada = $orden_entrada->cantidad;
            }

            // 🔹 Costo unitario original
            // $requestNeedItem = RequestNeedItem::where("descripcion",$orden_entrada->descripcion)
            //     ->where("mant_sn_request_id",$orden_entrada->mant_sn_request_needs_id)
            //     ->first()
            //     ->toArray();


            $requestNeedItem = RequestNeedItem::where(function ($query) use ($orden_entrada) {
                $query->where('descripcion', $orden_entrada->descripcion)
                    ->orWhere('descripcion_nombre', $orden_entrada->descripcion_nombre);
            })
            ->where('mant_sn_request_id', $orden_entrada->mant_sn_request_needs_id)
            ->first();


            if (!$requestNeedItem) {
                $this->generateSevenLog('NECESIDADES_SIN_ENCONTRAR', 'Modules\Maintenance\Http\Controllers\RequestNeedOrdersController - '. Auth::user()->name ?? 'Desconocido' .' -  Error: No se encontró RequestNeedItem para la entrada (se omite): '. json_encode($orden_entrada));
                continue;
            }

            $requestNeedItemArray = $requestNeedItem->toArray();


            $costo_unitarioOriginal = ($requestNeedItem["descripcion_datos"]["unit_value"] ?? 
                                    $requestNeedItem["descripcion_datos"]["valor_unitario"]);

            /**
             * 🔹 PUNTO 4 y 5 – Conversión automática de cantidades y costo unitario
             */
            $factor = 1;
            $unidadFinal = $orden_entrada->unidad;

            switch ($orden_entrada->unidad_medida_conversion ?? 'No aplica') {
                case 'Cuñete / Libras':
                    $factor = 35;
                    $unidadFinal = "Libras";
                    break;
                case 'Cuñete / Galones':
                    $factor = 5;
                    $unidadFinal = "Galones";
                    break;
                case 'Tambor / Cuartos':
                    $factor = 220;
                    $unidadFinal = "Cuartos";
                    break;
                case 'Tambor / Galones':
                    $factor = 55;
                    $unidadFinal = "Galones";
                    break;
                case 'Caja / Cuartos':
                    $factor = 24;
                    $unidadFinal = "Cuartos";
                    break;
            }

            $cantidadConvertida = ($orden_entrada->unidad_medida_conversion !== "No aplica")
                ? $orden_entrada->cantidad_entrada * $factor
                : $orden_entrada->cantidad_entrada;

            $costo_unitarioConvertido = ($orden_entrada->unidad_medida_conversion !== "No aplica")
                ? $costo_unitarioOriginal / $factor
                : $costo_unitarioOriginal;

            /**
             * 🔹 Actualización de cantidades en tablas relacionadas
             */
            RequestNeedItem::where("mant_sn_request_id",$orden_entrada->mant_sn_request_needs_id)
                ->where("descripcion_nombre",$orden_entrada->descripcion_nombre)
                ->where("unidad_medida",$orden_entrada->unidad)
                ->update([
                    "codigo" => $orden_entrada->codigo_entrada,
                    "cantidad_entrada" => $cantidadConvertida,
                    "unidad_medida_conversion" => $orden_entrada->unidad_medida_conversion ?? "No aplica", // 
                    "cantidad_solicitada_conversion" => $cantidadConvertida // guarda la cantidad final ya convertida
                ]);

            RequestNeedOrdersItem::where("descripcion",$orden_entrada->descripcion)
                ->where("mant_sn_request_needs_id",$orden_entrada->mant_sn_request_needs_id)
                ->update([
                    "codigo_entrada" => $orden_entrada->codigo_entrada,
                    "cantidad_entrada" => $cantidadConvertida,
                    "unidad_medida_conversion" => $orden_entrada->unidad_medida_conversion ?? "No aplica", // 
                    "cantidad_solicitada_conversion" => $cantidadConvertida // guarda la cantidad final ya convertida
                ]);

            /**
             * 🔹 Manejo de inventario
             */
            if($requestNeed["tipo_solicitud"] === "Inventario"){
                $winery = $requestNeed["dependencias_id"] == self::ASSISTANT_MANAGER_SANITATION_ID 
                    || $requestNeed["dependencias_id"] == self::CLEANING_MANAGEMENT_ID ? "Aseo" : "CAM";

                $stock = Stock::where("codigo",$orden_entrada->codigo_entrada)
                            ->where("bodega",$winery)
                            ->where("unidad_medida", $unidadFinal) // 🔹 validación extra
                            ->first();

                if(is_null($stock)){
                    $newStock = Stock::create([
                        "id_solicitud_necesidad" => $orden_entrada->descripcion,
                        "codigo" => $orden_entrada->codigo_entrada, 
                        "articulo" => $requestNeedItem["descripcion_datos"]["articulo"] ?? 
                                    $requestNeedItem["descripcion_datos"]["description"],
                        "grupo" => "N/A", 
                        "cantidad" => $cantidadConvertida, 
                        "costo_unitario" => $costo_unitarioConvertido,   // ✅ costo ajustado
                        "total" => $cantidadConvertida * $costo_unitarioConvertido, // ✅
                        "unidad_medida" => $unidadFinal, // ✅ unidad final
                        "iva" => $requestNeedItem["descripcion_datos"]["IVA"] ?? $requestNeedItem["descripcion_datos"]["iva"],
                        "bodega" => $winery
                    ]);

                    StockHistory::create([
                        "stock_id" => $newStock["id"],
                        "usuario_nombre" => Auth::user()->name, 
                        "usuario_id" => Auth::user()->id, 
                        "accion" => "Entrada", 
                        "cantidad" => $cantidadConvertida
                    ]);
                } else {
                    $quantity = $stock["cantidad"] + $cantidadConvertida;
                    $unitCost = $this->_averageProductsIfDifferent($stock,$costo_unitarioConvertido)["costo_unitario"];

                    Stock::where("id",$stock["id"])
                        ->update([
                            "cantidad" => $quantity, 
                            "costo_unitario" => $unitCost, 
                            "iva" => $requestNeedItem["descripcion_datos"]["IVA"] ?? $requestNeedItem["descripcion_datos"]["iva"],
                            "unidad_medida" => $unidadFinal
                        ]);

                    StockHistory::create([
                        "stock_id" => $stock["id"],
                        "usuario_nombre" => Auth::user()->name, 
                        "usuario_id" => Auth::user()->id, 
                        "accion" => "Entrada", 
                        "cantidad" => $cantidadConvertida
                    ]);
                }
            }

            /**
            * 🔹 PUNTO 5 – Salida automática si aplica
            */
            if(isset($orden_entrada->requiere_salida) && $orden_entrada->requiere_salida && isset($stock)){
                StockHistory::create([
                    "stock_id" => $stock["id"],
                    "usuario_nombre" => Auth::user()->name, 
                    "usuario_id" => Auth::user()->id, 
                    "accion" => "Salida automática", 
                    "cantidad" => $cantidadConvertida
                ]);

                $stock->cantidad -= $cantidadConvertida;
                $stock->save();
            }
        }


        $requestNeedOrder = $this->requestNeedOrdersRepository->update($input, $input['id']);
        $this->_sendEmail('3', $requestNeedOrder);

        HistoryOrder::create([
            "mant_sn_orders_id" => $requestNeedOrder["id"],
            "users_id" => Auth::user()->id,
            "nombre_usuario" => Auth::user()->name, 
            "estado" => "Entrada Confirmada", 
            "observacion" => "N/A", 
            "accion" => "Se confirma la entrada al almacen"
        ]);

        $userLogged = Auth::user();

        RequestNeedHistory::create([
            'users_nombre' =>  $userLogged->name,
            'users_id' => $userLogged->id,
            'observacion' => null,
            'estado' => "En trámite",
            'mant_sn_request_id' => $requestNeed["id"],
            'accion' => "Entrada confirmada de la orden #".$requestNeedOrder["consecutivo"]
        ]);

        $requestNeedOrder->histori;

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }


    private function _convertEntrada($unidad, $cantidad, $unidadConversion, $costoUnitario)
    {
        $cantidadFinal = $cantidad;
        $unidadFinal = $unidad;
        $costoFinal = $costoUnitario;

        if($unidadConversion && $unidadConversion !== "No aplica"){
            switch ($unidad) {
                 case "Cuñete":
                if($unidadConversion === "Libras"){
                    $cantidadFinal = $cantidad * 35;
                    $unidadFinal = "Libras";
                    $costoFinal = $costoUnitario / 35;
                }
                if($unidadConversion === "Galones"){
                    $cantidadFinal = $cantidad * 5;
                    $unidadFinal = "Galones";
                    $costoFinal = $costoUnitario / 5;
                }
                break;

                case "Tambor":
                    if($unidadConversion === "Cuartos"){
                        $cantidadFinal = $cantidad * 220;
                        $unidadFinal = "Cuartos";
                        $costoFinal = $costoUnitario / 220;
                    }
                    if($unidadConversion === "Galones"){
                        $cantidadFinal = $cantidad * 55;
                        $unidadFinal = "Galones";
                        $costoFinal = $costoUnitario / 55;
                    }
                    break;

                case "Caja":
                    if($unidadConversion === "Cuartos"){
                        $cantidadFinal = $cantidad * 24;
                        $unidadFinal = "Cuartos";
                        $costoFinal = $costoUnitario / 24;
                    }
                break;
            }
        }

        return [
            "cantidad" => $cantidadFinal,
            "unidad" => $unidadFinal,
            "costo_unitario" => $costoFinal
        ];
    }

    public function sendEntradaInicial(Request $request) {
        $input = $request->all();

        $input['tramite_almacen'] = "Entrada Confirmada";
        $input['estado_proveedor'] = "Entrada Confirmada";

        $totalValueToBeSpent = 0;

        $requestNeed = RequestNeed::select(['id','consecutivo','rubro_objeto_contrato_id','rubro_id','tipo_solicitud','mant_administration_cost_items_id','dependencias_id'])->with(["contratoDatos","necesidades"])->where('id',$input['mant_sn_request_id'])->first()->toArray();

        $isOnlyForSparePart = $this->isOnlyForSparePart($requestNeed["necesidades"]); // Valida si la solicitud solo tiene necesidades de repuestos

        $availableContract = $this->getContractAvailableValue($requestNeed["mant_administration_cost_items_id"],$requestNeed["rubro_objeto_contrato_id"], $requestNeed["id"]);
        $totalValueToBeSpent = $this->getTotalValueToBeSpent($input["ordenes_entradas"]);

        if($isOnlyForSparePart && ($requestNeed["tipo_solicitud"] === "Activo" || $requestNeed["tipo_solicitud"] === "Inventario")){
            if($totalValueToBeSpent > $availableContract){
                return $this->sendSuccess("El valor a descontar($".number_format($totalValueToBeSpent).") es superior al valor disponible del rubro($".number_format($availableContract).")", 'info');
            }
        }

        // Actualiza el numero de la factura de la solicitud principal si hay actividades
        if(!$isOnlyForSparePart){
            RequestNeed::where("id",$input['mant_sn_request_id'])->update(["invoice_no" => $input["numero_factura"] ?? null]);
        }

        foreach ($input["ordenes_entradas"] as $orden_entrada) {
            $orden_entrada = json_decode($orden_entrada);

            // Modifica el item de la orden
            // $this->requestNeedOrderItemRepository->update(["cantidad_entrada" => $orden_entrada->cantidad_entrada], $orden_entrada->id);
            
            // Si llega nulo entonces la cantidad de entrada va tener el valor de cantidad solicitada
            if(is_null($orden_entrada->cantidad_entrada) || $orden_entrada->cantidad_entrada == ""){
                $orden_entrada->cantidad_entrada = $orden_entrada->cantidad;
            }

            
            // Modifica el item de la solicitu de necesidad
            RequestNeedItem::where("mant_sn_request_id",$orden_entrada->mant_sn_request_needs_id)->where("descripcion_nombre",$orden_entrada->descripcion_nombre)->where("unidad_medida",$orden_entrada->unidad)->update(["codigo" => $orden_entrada->codigo_entrada, "cantidad_entrada" => $orden_entrada->cantidad_entrada]);
            
            
            $requestNeedItem = RequestNeedItem::where("descripcion",$orden_entrada->descripcion)->where("mant_sn_request_id",$orden_entrada->mant_sn_request_needs_id)->first()->toArray();
            
            // Actualiza el item de la orden para actualizar su codigo y cantidad
            RequestNeedOrdersItem::where("descripcion",$orden_entrada->descripcion)->where("mant_sn_request_needs_id",$orden_entrada->mant_sn_request_needs_id)->update(["codigo_entrada" => $orden_entrada->codigo_entrada, "cantidad_entrada" => $orden_entrada->cantidad_entrada]);
            
            $costo_unitario = ($requestNeedItem["descripcion_datos"]["unit_value"] ?? $requestNeedItem["descripcion_datos"]["valor_unitario"] );

            // Validacion para crear o editar un stock
            if($requestNeed["tipo_solicitud"] === "Inventario"){
                $winery = $requestNeed["dependencias_id"] == self::ASSISTANT_MANAGER_SANITATION_ID || $requestNeed["dependencias_id"] == self::CLEANING_MANAGEMENT_ID ? "Aseo" : "CAM";
                $stock = Stock::where("codigo",$orden_entrada->codigo_entrada)->where("bodega",$winery)->first();
                if(is_null($stock)){

                    $newStock = Stock::create(["id_solicitud_necesidad" => $orden_entrada->descripcion,"codigo" => $orden_entrada->codigo_entrada, "articulo" => $requestNeedItem["descripcion_datos"]["articulo"] ?? $requestNeedItem["descripcion_datos"]["description"],"grupo" => "N/A", "cantidad" => $orden_entrada->cantidad_entrada, "costo_unitario" => $costo_unitario, "total" => $orden_entrada->cantidad_entrada  * $costo_unitario, "unidad_medida" => $requestNeedItem["descripcion_datos"]["unidad_medida"] ?? ($requestNeedItem["descripcion_datos"]["unit_measure"] ?? $requestNeedItem["unidad_medida"]), "iva" => $requestNeedItem["descripcion_datos"]["IVA"] ?? $requestNeedItem["descripcion_datos"]["iva"],"bodega" => $winery]);
                    StockHistory::create(["stock_id" => $newStock["id"],"usuario_nombre" => Auth::user()->name, "usuario_id" => Auth::user()->id, "accion" => "Entrada", "cantidad" => $orden_entrada->cantidad_entrada ?? $orden_entrada->cantidad]);
                }
                else{
                    $quantity = $stock["cantidad"] + $orden_entrada->cantidad_entrada;
                    $unitCost = $this->_averageProductsIfDifferent($stock,$costo_unitario)["costo_unitario"];
                    Stock::where("id",$stock["id"])->update(["cantidad" => $quantity, "costo_unitario" => $unitCost, "iva" => $requestNeedItem["descripcion_datos"]["IVA"] ?? $requestNeedItem["descripcion_datos"]["iva"]]);
                    StockHistory::create(["stock_id" => $stock["id"],"usuario_nombre" => Auth::user()->name, "usuario_id" => Auth::user()->id, "accion" => "Entrada", "cantidad" => $orden_entrada->cantidad_entrada ?? $orden_entrada->cantidad]);
                }
            }

            // $orden_entrada->descripcion_datos->descripcion_entrada = $orden_entrada->descripcion_datos->descripcion_nombre;
        }

        // Validacion para ejecutar el rubro si es de una solicitud de activo y solo tiene repuestos
        // if($isOnlyForSparePart && ($requestNeed["tipo_solicitud"] === "Activo" || $requestNeed["tipo_solicitud"] === "Inventario")){
        //     $this->executeBudgetExecution(["rubro_id" => $requestNeed["rubro_id"], "rubro_objeto_contrato_id" => $requestNeed["rubro_objeto_contrato_id"],"consecutivo" => $requestNeed["consecutivo"]], $totalValueToBeSpent,$requestNeed["tipo_solicitud"]);
        // }

        // Ejecuta la ejecucion presupuestal del rubro
        // $this->executeBudgetExecution($requestNeed,$totalValueToBeSpent);
      
        $requestNeedOrder = $this->requestNeedOrdersRepository->update($input, $input['id']);

        // TODO: Hacer que el envio de correos funcione correctamente $this->_sendEmail('3', $requestNeedOrder);
        $this->_sendEmail('3', $requestNeedOrder);

        HistoryOrder::create(["mant_sn_orders_id" => $requestNeedOrder["id"],"users_id" => Auth::user()->id,"nombre_usuario" => Auth::user()->name, "estado" => "Entrada Confirmada", "observacion" => "N/A", "accion" => "Se confirma la entrada al almacen" ]);

        $userLogged = Auth::user();

        // Crea el historial a la solicitud de la entrada confirmada
        RequestNeedHistory::create([
            'users_nombre' =>  $userLogged->name,
            'users_id' => $userLogged->id,
            'observacion' => null,
            'estado' => "En trámite",
            'mant_sn_request_id' => $requestNeed["id"],
            'accion' => "Entrada confirmada de la orden #".$requestNeedOrder["consecutivo"]
        ]);

        // Relaciones
        $requestNeedOrder->histori;

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }

    public function getTotalValueToBeSpentInicial($items) : int|float{
        $totalValueToBeSpent = 0;

        // Iteracion para obtener el valor total a descontar
        foreach ($items as $orden_entrada) {
            $orden_entrada = json_decode($orden_entrada);

            // Validacion para no tener en cuenta el item en el descuento del rubro
            if(!is_null($orden_entrada->cantidad_entrada) && $orden_entrada->cantidad_entrada != ""){
                if($orden_entrada->cantidad_entrada == 0){
                    continue;
                }
            }
            
            $cantidad_a_descontar = is_null($orden_entrada->cantidad_entrada) || $orden_entrada->cantidad_entrada == "" ? $orden_entrada->cantidad : $orden_entrada->cantidad_entrada;
            
            $requestNeedItem = RequestNeedItem::where("descripcion",$orden_entrada->descripcion)->where("mant_sn_request_id",$orden_entrada->mant_sn_request_needs_id)->first()->toArray();


            $totalValueToBeSpent += (($requestNeedItem["descripcion_datos"]["total_value"]) * $cantidad_a_descontar);
        }

        return $totalValueToBeSpent;
    }

    public function getTotalValueToBeSpent($items): int|float
    {
        $totalValueToBeSpent = 0;

        // Iteracion para obtener el valor total a descontar
        foreach ($items as $orden_entrada) {
            $orden_entrada = json_decode($orden_entrada);

            // Validacion para no tener en cuenta el item en el descuento del rubro
            if (!is_null($orden_entrada->cantidad_entrada) && $orden_entrada->cantidad_entrada != "") {
                if ($orden_entrada->cantidad_entrada == 0) {
                    continue;
                }
            }
            
            $cantidad_a_descontar = is_null($orden_entrada->cantidad_entrada) || $orden_entrada->cantidad_entrada == "" ? $orden_entrada->cantidad : $orden_entrada->cantidad_entrada;
            
            // ✅ INICIA LA CORRECCIÓN
            // 1. Ejecuta la consulta y guarda el resultado (puede ser un objeto o null).
            $requestNeedItem = RequestNeedItem::where("descripcion", $orden_entrada->descripcion)
                                            ->where("mant_sn_request_id", $orden_entrada->mant_sn_request_needs_id)
                                            ->first();

            // 2. Valida si el resultado es nulo. Si es así, sáltate esta iteración.
            if (!$requestNeedItem) {
                continue; // No se encontró el item, pasamos al siguiente.
            }

            // 3. Si llegamos aquí, es seguro usar el objeto.
            $requestNeedItemArray = $requestNeedItem->toArray();
            $totalValueToBeSpent += (($requestNeedItemArray["descripcion_datos"]["total_value"]) * $cantidad_a_descontar);
            // ✅ TERMINA LA CORRECCIÓN
        }

        return $totalValueToBeSpent;
    }


    /**
     * Obtiene el valor disponible de un rubro, excluyendo la solicitud actual.
     *
     * @param int $itemId ID del rubro (cost item).
     * @param int $contractId ID del contrato.
     * @param int $currentRequestId ID de la solicitud de necesidad actual para excluirla del cálculo.
     * @return int|float
     */
    public function getContractAvailableValue(int $itemId, int $contractId, int $currentRequestId) : int|float
    {
        // Informacion del rubro a obtener el valor disponible
        $item = AdministrationCostItem::find($itemId);

        // Obtiene el valor total del encapsulamiento de las necesidades, excluyendo la actual
        $encapsulatedValueOfNeeds = $this->_getEncapsulatedValueOfNeeds($itemId, $contractId, $currentRequestId);

        return $item["value_avaible"] - $encapsulatedValueOfNeeds;
    }
  

    /**
     * Obtiene el valor encapsulado el cual es el valor total de todas las solicitudes de necesidades
     * que se encuentren en un estado diferente a Cancelada o Finalizada de un mismo rubro y contrato
     * 
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    private function _getEncapsulatedValueOfNeeds(int $itemId, int $contractId, int $currentRequestId) : int|float{

        $encapsulatedValueOfNeeds = RequestNeed::where("mant_administration_cost_items_id", $itemId)
            ->where("rubro_objeto_contrato_id", $contractId)
            ->whereNotIn("estado", ["Cancelada", "Finalizada"])
            ->where('id', '<>', $currentRequestId) // <-- LÍNEA CLAVE: Excluye la solicitud actual
            ->sum("total_solicitud");

        return $encapsulatedValueOfNeeds;


        // $encapsulatedValueOfNeeds = RequestNeed::where("mant_administration_cost_items_id",$itemId)->where("rubro_objeto_contrato_id",$contractId)->whereNotIn("estado",["Cancelada","Finalizada"])->sum("total_solicitud");

        // return $encapsulatedValueOfNeeds;
    }

    /**
     * Valida si la solicitud contiene necesidades de tipo actividad
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     */
    private function isOnlyForSparePart(array $needs) : bool{
        $needsQuantityWithActivities = count(array_filter($needs,function($need){
            return $need["tipo_necesidad"] == "Actividades";
        }));

        return $needsQuantityWithActivities == 0;
    }

    public function executeBudgetExecution($itemData,$totalValueToBeSpent,string $needType){
        // Usuario en sesion
        $userLogged = Auth::user();

        $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $itemData["rubro_objeto_contrato_id"])->pluck('id');

        if($needType === "Activo"){
            $heading = DB::table('mant_sn_actives_heading')
            ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
            ->where("id",$itemData["rubro_id"])
            ->whereNull("deleted_at")
            ->first();
        }
        else{
            $heading = DB::table('mant_administration_cost_items as c')
            ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
            ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
            ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
            ->select('k.id as rubro_id','k.code_heading','c.cost_center as centro_costo_codigo')
            ->where('k.id',$itemData["rubro_id"])
            ->where('s.id',$itemData["rubro_objeto_contrato_id"])
            ->distinct('name_heading')
            ->first();
        }
        
        // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
        $administrationCostItem = AdministrationCostItem::whereIn('mant_budget_assignation_id', $budgetAssignationIds)
        ->where('mant_heading_id', $heading->rubro_id)
        ->where('code_cost', $heading->code_heading)
        ->where('cost_center', $heading->centro_costo_codigo)
        ->first();

        // Se obtiene las asignaciones con relación al contrato
        // $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $itemData["rubro_objeto_contrato_id"])
        //     ->pluck('id');

        // // se obtiene el rubro con el contrato que se consulto anteriormente y el nombre del centro de costo
        // $administrationCostItems = AdministrationCostItem::whereIn('mant_budget_assignation_id', $budgetAssignationIds)
        //     ->where('id', $itemData["rubro_id"])
        //     ->first();


        $this->_generarEjecucion(
            $administrationCostItem->id, //mant_administration_cost_items_id 
            $itemData["consecutivo"] ?? '', //minutes 
            date("Y-m-d H:i:s") ?? '',  //date
            $totalValueToBeSpent, //executed_value 
            "Solicitud de identificación de necesidad ".$itemData["consecutivo"] ?? '', //observation
            $userLogged->name ?? 0, //name_user
            $userLogged->id ?? ''  //users_id
        );
    }

    public function sendSalida(Request $request) {
        $input = $request->all();

        $userLogged = Auth::user();
        
        $input['tramite_almacen'] = "Salida Confirmada";
        $input['estado_proveedor'] = "Salida Confirmada";
        $input['numero_salida_almacen'] = $request['numero_salida_almacen'];
        
        
        // Bucle para restar la cantidad fisica actual co
        foreach ($input["ordenes_item"] as $order_item) {
            // codigo_entrada
            // cantidad_entrada
            $order_item = json_decode($order_item,true);

            // Inicializa el codigo de salida para verificar el producto en el stock ya sea por entrada o salida
            $order_item["codigo_salida"] = is_null($order_item["codigo_salida"]) ? $order_item["codigo_entrada"] : $order_item["codigo_salida"];

            $dependenciId = RequestNeed::select('dependencias_id')->where('id',$order_item['mant_sn_request_needs_id'])->first()->dependencias_id;
            $bodega = ($dependenciId == 19 || $dependenciId == 23)  ? 'Aseo' : 'CAM';

            
             // Realiza la resta para actualizar el valor de la cantidad fisica que hay en el stock
            $stock_quantity = Stock::where("codigo",$order_item["codigo_salida"])->where('bodega',  $bodega )->first()->cantidad - $order_item["cantidad"];

            Stock::where("codigo",$order_item["codigo_salida"])->where('bodega',  $bodega )->update(["cantidad" => $stock_quantity]);
            $stock_id = Stock::select("id")->where("codigo",$order_item["codigo_salida"])->where('bodega',  $bodega )->first()->id;

            StockHistory::create(["stock_id" => $stock_id,"usuario_nombre" => $userLogged->name, "usuario_id" => $userLogged->id, "accion" => "Salida", "cantidad" => $order_item["cantidad"]]);
        }

      
        $requestNeedOrder = $this->requestNeedOrdersRepository->update($input, $input['id']);

        HistoryOrder::create(["mant_sn_orders_id" => $requestNeedOrder["id"],"users_id" => $userLogged->id,"nombre_usuario" => $userLogged->name, "estado" => "Salida Confirmada", "observacion" => "N/A", "accion" => "Se confirma la salida al almacen" ]);

        $requestNeed = RequestNeed::select(["id","dependencias_id","users_id","tipo_solicitud"])->find($input["mant_sn_request_id"]);

        // Valida si la solicitud es de aseo y de un lider para finalizarla de manera automatica
        if($this->_requestIsForCleaning($requestNeed["dependencias_id"],$requestNeed["users_id"]) && $requestNeed["tipo_solicitud"] === "Stock") {
            $requestNeedOrder = $this->requestNeedOrdersRepository->update(["estado" => "Orden Finalizada"], $input['id']);

            $requestNeed = $this->requestNeedRepository->update(["estado" => "Finalizada"],$requestNeed["id"]);

            RequestNeedHistory::create([
                'users_nombre' =>  $userLogged->name,
                'users_id' => $userLogged->id,
                'observacion' => null,
                'estado' => "Finalizada",
                'mant_sn_request_id' => $requestNeed["id"],
                'accion' => "Solicitud finalizada automaticamente por la salida ".$requestNeedOrder["consecutivo"]
            ]);

            $requestNeedOrders["ordenes_item"] = $input["ordenes_item"];
            $requestNeedOrders["mant_sn_request_id"] = $requestNeed["id"];
            $requestNeedOrders["numero_salida_almacen"] = $requestNeed["numero_salida_almacen"];

            // Crea el registro en la vista de gestion de mantenimientos
            $this->_createRegisterToAssetManagement($requestNeedOrders);
        }

        // Crea el historial a la solicitud de la salida confirmada
        RequestNeedHistory::create([
            'users_nombre' =>  $userLogged->name,
            'users_id' => $userLogged->id,
            'observacion' => null,
            'estado' => "En trámite",
            'mant_sn_request_id' => $requestNeed["id"],
            'accion' => "Salida confirmada de la orden #".$requestNeedOrder["consecutivo"]
        ]);

        // Relaciones
        $requestNeedOrder->histori;
        $requestNeedOrder->ordenesItem;
        $requestNeedOrder->ordenesEntradas;

        return $this->sendResponse($requestNeedOrder->toArray(), trans('msg_success_update'));
    }

    /**
     * Valida si la solicitud principal es de aseo y su usuario creador es un lider
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     */
    private function _requestIsForCleaning(int $requestProcess, int $creatorId) : bool{
        $isCreatorLeader = User::find($creatorId)->hasRole("mant Líder de proceso");

        return $isCreatorLeader && ($requestProcess === self::CLEANING_MANAGEMENT_ID || $requestProcess === self::ASSISTANT_MANAGER_SANITATION_ID);
    }
    

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function fromatGr($id)
    {
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(base_path('Modules/Maintenance/Resources/views/request_needs/FormatosReportes/VIG-GR-R-026.xlsx'));
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();

        $orden = RequestNeedOrders::where('id', $id)
            ->with('user', 'solicitudPrincipal', 'ordenesItem')
            ->first();

        if (!$orden) {
            abort(404, "Order not found.");
        }

        $contrato = BudgetAssignation::with("users")
            ->where('mant_provider_contract_id', $orden->solicitudPrincipal->rubro_objeto_contrato_id)
            ->first() ?? (object)['users' => (object)['name' => 'N/A'], 'value_contract' => 0];

        $providerContract = ProviderContract::with('providers')
            ->find($orden->solicitudPrincipal->rubro_objeto_contrato_id);

        $provider = $providerContract->providers ?? null;

        $sheet->setCellValue('C7', $orden->solicitudPrincipal->consecutivo ?? 'Sin consecutivo');

        $providerInformation = implode(" - ", [
            $provider->document_type ?? "N/A",
            $provider->identification ?? "N/A",
            $provider->name ?? "N/A",
            $provider->mail ?? "N/A",
        ]);
        $sheet->setCellValue('C8', $providerInformation);

        $sheet->setCellValue('C9', $orden->solicitudPrincipal->tipo_solicitud === 'Activo'
            ? $orden->solicitudPrincipal->activo_nombre
            : 'NA');

        if (session('outside')) {
            $dependencia = $orden->solicitudPrincipal->dependencia['nombre'] ?? "Sin nombre";
        } elseif (Auth::check() && Auth::user()->hasRole('mant Líder de proceso')) {
            $dependencia = is_array($orden->solicitudPrincipal->dependencia)
                ? ($orden->solicitudPrincipal->dependencia['nombre'] ?? "Sin nombre")
                : ($orden->solicitudPrincipal->dependencia->nombre ?? "Sin nombre");
        } else {
            $dependencia = $orden->solicitudPrincipal->dependencia['nombre'] ?? "Sin nombre";
        }
        $sheet->setCellValue('C10', (string)$dependencia);

        $sheet->setCellValue('M7', "{$providerContract->type_contract}, {$providerContract->contract_number}");
        $sheet->mergeCells("M9:O9");
        $sheet->setCellValue('M9', '$' . number_format($contrato->value_contract ?? 0));
        $sheet->setCellValue('M10', $contrato->users->name ?? 'N/A');

        $created_at = Carbon::parse($orden->solicitudPrincipal->created_at);
        $sheet->setCellValue('Q9', $created_at->format("y"));
        $sheet->setCellValue('R9', $created_at->format("m"));
        $sheet->setCellValue('S9', $created_at->format("d"));

        if (!empty($orden->solicitudPrincipal->approval_date)) {
            $approval_date = Carbon::parse($orden->solicitudPrincipal->approval_date);
            $sheet->setCellValue('Q10', $approval_date->format("y"));
            $sheet->setCellValue('R10', $approval_date->format("m"));
            $sheet->setCellValue('S10', $approval_date->format("d"));
        }

        $sheet->setCellValue(
            in_array($orden->bodega, [self::ASSISTANT_MANAGER_SANITATION_ID, self::CLEANING_MANAGEMENT_ID]) ? "O14" : "O15",
            "X"
        );

        $cell = 18;
        $products_quantity = ["Actividades" => 0, "Repuestos" => 0];
        $value_total_needs = 0;
        $quantityOrders = count($orden->ordenesItem);

        if ($quantityOrders > 1) {
            $sheet->insertNewRowBefore($cell, ($quantityOrders - 1));
        }

        foreach ($orden->ordenesItem as $item) {

            $products_quantity[$item->tipo_necesidad]++;

            $sheet->setCellValue("A$cell", $item->descripcion_nombre);
            $sheet->mergeCells("A$cell:F$cell");
            $sheet->getStyle("C$cell")->getAlignment()->setHorizontal('center');

            $sheet->setCellValue("G$cell", $item->tipo_necesidad);
            $sheet->setCellValue("H$cell", $item->cantidad);
            $sheet->setCellValue("I$cell", $item->unidad);

            $unit_value_data = RequestNeedItem::select("valor_unitario", "IVA","valor_total","total_value")
                ->where("id", $item->mant_sn_request_needs_id_real)
                ->first();

                // dd($unit_value_data->valor_total);
            $unit_value = $unit_value_data->valor_unitario ?? 0;
            $item_iva = $unit_value_data->IVA ?? 0;
            $valor_total_item = $unit_value_data->valor_total ?? 0;
            $total_value = $unit_value_data->total_value ?? 0;
            $sheet->setCellValue("J$cell", $unit_value);
            $sheet->setCellValue("K$cell", $item_iva);
            $sheet->setCellValue("L$cell", $valor_total_item);

            foreach (["J", "K", "L"] as $col) {
                $sheet->getStyle("$col$cell")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            }

            $value_total_needs += ($total_value) * $item->cantidad;
            $cell++;
        }

        $sheet->setCellValue("M18", $orden->solicitudPrincipal->approval_justification ?? "No aplica");
        $sheet->mergeCells("M18:S" . (17 + $quantityOrders));

        $sheet->setCellValue('S13', $products_quantity["Repuestos"] > 0 ? "X" : "");
        $sheet->setCellValue('S15', $products_quantity["Actividades"] > 0 ? "X" : "");

        $sheet->setCellValue("L" . ($cell + 1), $value_total_needs ?? 0);
        $sheet->setCellValue("M" . ($cell + 1), $orden->solicitudPrincipal->observacion ?? "No aplica");

        $this->insertUserSignature($sheet, $orden->solicitudPrincipal->users_id, 'B', $cell + 3);
        if (!is_null($orden->solicitudPrincipal->approving_user_id)) {
            $this->insertUserSignature($sheet, $orden->solicitudPrincipal->approving_user_id, 'L', $cell + 3);
        }

        $fileName = "Identificacion de Necesidades " . $orden->solicitudPrincipal->consecutivo;

        // 🔁 Exportar en PDF si la sesión es externa
        if (session('outside')) {
            $styleArray = [
                'borders' => [
                    'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ]
            ];
            $sheet->getStyle('I7:S7')->applyFromArray($styleArray);
            $sheet->getStyle('A7:A20')->applyFromArray($styleArray);

            $pdf = UtilController::convertXlsxToPdf($spreadsheet);
            $tmp = UtilController::generateTemporaryFile($pdf);

            return Response::make(Storage::get($tmp), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"$fileName.pdf\"",
                'Content-Length' => Storage::size($tmp)
            ]); 
        }

        // 🧾 Exportar como Excel por defecto
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$fileName.xlsx\"");
        header('Cache-Control: max-age=0');

        IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        exit;
    }

    private function insertUserSignature($sheet, $userId, $col, $row)
    {
        $user = User::with("positions")->find($userId)?->toArray();
        $signaturePath = $user["url_digital_signature"] ?? null;

        $path = $signaturePath && file_exists(storage_path('app/public/' . $signaturePath))
            ? storage_path('app/public/' . $signaturePath)
            : $this->getDefaultSignaturePath();
     
         $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
         $drawing->setPath($path);
         $drawing->setCoordinates($col . $row);
         $drawing->setWorksheet($sheet);
         $drawing->setHeight(100);
         $drawing->setResizeProportional(true);
         $drawing->setOffsetX(200);
         $drawing->setOffsetY(2);
     
         $sheet->setCellValue($col . ($row + 1), $user["name"]);
         $sheet->setCellValue($col . ($row + 2), $user["positions"]["nombre"]);
    }
     
    private function getDefaultSignaturePath()
    {
         $defaultImagePath = storage_path('app/temp/default_sign.png');
     
         if (!file_exists($defaultImagePath)) {
             @mkdir(dirname($defaultImagePath), 0755, true);
             file_put_contents($defaultImagePath, file_get_contents("https://intraweb.seven.com.co/assets/img/default/default_sign.png"));
         }
     
         return $defaultImagePath;
    }
    /**
     * Exportar el certificado VIG-GR-R-026
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $requestNeedOrderId
     */
    public function exportCertificateVigGrR026(int $requestNeedOrderId){
        $requestNeedOrder = RequestNeedOrders:: where('id',$requestNeedOrderId)->with('user', 'solicitudPrincipal','ordenesItem')->first();

        $requestNeedOrder["provider_contract"] = ProviderContract::with("users")->where("id",$requestNeedOrder->solicitudPrincipal->rubro_objeto_contrato_id)->first();

        $requestNeedOrder["contract"] = BudgetAssignation::where('mant_provider_contract_id',$requestNeedOrder->solicitudPrincipal->rubro_objeto_contrato_id)->first();

        $activities_quantity =  0;
        $spare_parts_quantity =  0;

        // Bucle para obtener el tipo de solicitud producto, servicio o combinada
        foreach ($requestNeedOrder["ordenesItem"] as $key => $item) {
            if($item["tipo_necesidad"] == "Repuestos"){
                $spare_parts_quantity++;
            }
            else{
                $activities_quantity++;
            }
        }

        $requestNeedOrder["need_types"] = ["activites" => $activities_quantity, "spare_parts" => $spare_parts_quantity];
        
        $filePDF = PDF::loadView("maintenance::request_need_orders.exports.VIG-GR-R-026", ['data' => $requestNeedOrder])->setPaper("a4", "landscape");
        return $filePDF->download("VIG-GR-R-026.pdf");
    }

   /**
     * Función encargada de realizar las notificaciones al correo electronico,
     * @param type , Este parametro sirve para identificar a quien va dirigido el mensaje, si es 
     * 1, va dirijido alproveedor  externo
     *
     * @param data, este parametro trae la data que se recolectara en el mensaje.
     */ 
    public function _sendEmail($type, $data){
        //Este es el asunto del correo
        $custom = json_decode('{"subject": "Notificación de identificación de necesidades"}');

        if ($type == '1') {
            $provider_mail = explode(" - ",$data["proveedor_nombre"])[3];
            SendNotificationController::SendNotification('maintenance::request_need_orders.emails.notification_supplier_externo',$custom,$data,$provider_mail,'Identificación de necesidades');
        }
        elseif ($type == '2') {
         //aca se envia el email a el correo delproveedor interno
         Mail::to('ecardenas@seven.com.co')->send(new SendMail('maintenance::request_need_orders.emails.notification_supplier_interno', $data, $custom));

        }
        elseif ($type == '3') {
            $requestNeed= RequestNeedOrders:: where('id', $data->id)->with('solicitudPrincipal')->first();
            $admin_email = User::role("Administrador de mantenimientos")->first()->email;

            SendNotificationController::SendNotification('maintenance::request_need_orders.emails.entrada',$custom,$requestNeed,$admin_email,'Identificación de necesidades');
           }

    
    }

    /**
     * Valida si las ordenes de una solicitud ya estan todas asigndas
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $requestId id de la solicitud en general
     */
    private function _hasNotOrdersAssigned(int $requestId) : bool{
        $quantityNotOrdersAssigned = RequestNeedOrders::where('mant_sn_request_id',$requestId)->where("estado","Orden en elaboración")->count();
        return $quantityNotOrdersAssigned == 0;
    }
    
}
