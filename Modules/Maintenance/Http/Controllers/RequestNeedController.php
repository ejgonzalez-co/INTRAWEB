<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateRequestNeedRequest;
use Modules\Maintenance\Http\Requests\UpdateRequestNeedRequest;
use Modules\Maintenance\Repositories\RequestNeedRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Maintenance\Models\AdministrationCostItem;
use Modules\Maintenance\Models\BudgetAssignation;
use Modules\Maintenance\Models\ButgetExecution;
use Modules\Maintenance\Models\ProviderContract;
use Modules\Maintenance\Models\ImportActivitiesProviderContract;
use Modules\Maintenance\Models\ImportSparePartsProviderContract;
use Modules\Maintenance\Models\RequestNeedItem;
use Modules\Maintenance\Models\AssetType;
use Modules\Maintenance\Models\RequestNeed;
use Modules\Maintenance\Models\RequestNeedOrders;
use Modules\Maintenance\Models\RequestNeedOrdersItem;
use Modules\Maintenance\Models\HistoryOrder;
use App\Exports\Maintenance\RequestExport;
use App\Http\Controllers\JwtController;
use Modules\Maintenance\Models\Stock;
use Modules\Maintenance\Models\StockHistory;
use Modules\Maintenance\Models\AssetManagement;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Http\Controllers\SendNotificationController;
use Carbon\Carbon;
use Modules\Maintenance\Models\AdditionSparePartActivity;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;


use Modules\Maintenance\Models\RequestNeedHistory;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class RequestNeedController extends AppBaseController {

    /** @var  RequestNeedRepository */
    private $requestNeedRepository;

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
    public function __construct(RequestNeedRepository $requestNeedRepo) {
        $this->requestNeedRepository = $requestNeedRepo;
    }

    /**
     * Muestra la vista para el CRUD de RequestNeed.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if (session('outside')) {
            return view('maintenance::request_needs.index');
        }else {
            $user = User::join('dependencias as d', 'users.id_dependencia', '=', 'd.id')
            ->where('users.id',Auth::user()->id)
            ->select('d.nombre')
            ->first();
            $dependencia =   $user->nombre; 


            $is_leader='';
            if(auth()->user()->hasRole('mant Líder de proceso')){
                $is_leader =  true;
            }else {
                $is_leader =  false;
            }


             return view('maintenance::request_needs.index', compact('is_leader'))->with('dependencia', $dependencia);
        }
     

      
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
        if (session('outside')) {
            $request_needs = RequestNeed::latest()->get();
        }else{
            $userAuth = Auth::user();

            if($userAuth->hasRole('Administrador de mantenimientos')){

                $tablero = $request->input('tablero');  // o $request->query('tablero')

                $cantidadPaginado = base64_decode($request["pi"]);

                // Preparar filtros
                $filtros = $this->prepararFiltros($request);

                return $this->consultasAdmin($tablero, $filtros, $cantidadPaginado);
            }
            else if($userAuth->hasRole('mant Almacén Aseo')){

                $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos')->latest()->get();
            }
            else if($userAuth->hasRole('mant Almacén CAM')){
                $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos')->latest()->get();
            }
            else if($userAuth->hasRole('mant Proveedor interno')){
                $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos')->latest()->get();
            }
            else if($userAuth->hasRole('mant Líder de proceso')){

                $cantidadPaginado = base64_decode($request["pi"]);

                // Preparar filtros
                $filtros = $this->prepararFiltros($request);

                return $this->consultasLider($filtros, $cantidadPaginado);

                // $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos', 'historial')->where("users_id",$userAuth->id)->latest()->get()->map(function ($request_need){
                //     $request_need["encrypted_id"] = encrypt($request_need["id"]);
                //     return $request_need;
                // });

                // $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos')->where("users_id",Auth::user()->id)->latest()->get();
            }
        }
        // $request_needs = $this->requestNeedRepository->all();
        // $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos')->latest()->get();


        return $this->sendResponse($request_needs->toArray(), trans('data_obtained_successfully'));
    }

        // Prepara los filtros, decodificando y reemplazando los valores necesarios
    private function prepararFiltros(Request $request)
    {
        // Reemplazar los espacios en blanco por + en la cadena de filtros codificada
        $request["f"] = str_replace(" ", "+", $request["f"]);
        // Decodificar filtros si existen
        if (isset($request["f"]) && !empty($request["f"])) {
            $filtros = base64_decode($request["f"]);
            // Escapar la palabra reservada 'from' con comillas invertidas
            $filtros = str_replace("from", "`from`", $filtros);
            return $filtros;
        }
        return "";
    }

    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Enviada Admin'
    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Enviada Admin'
    private function consultasAdmin($tablero, $filtros, $cantidadPaginado)
    {
        if ($tablero) {
            return $this->tableroAdmin();
        }

        // Caso especial: solicitudes en estado "SOLICITUD DE ADICIÓN"
        if ($filtros == "estado LIKE '%SOLICITUD DE ADICIÓN%'") {
            // Obtiene las solicitudes con adiciones pendientes por tramitar
            $pending_addition_requests = AdditionSparePartActivity::select("request_id")
                ->where('status', 'En trámite')
                ->whereNull('deleted_at')
                ->pluck("request_id")
                ->toArray();

            $request_needs = RequestNeed::with('contratoDatos','necesidades','rubroDatos','dependencia','historial','ordenes','users')
                ->where('en_administracion', 1)
                ->whereIn("id", $pending_addition_requests)
                ->latest("mant_sn_request.updated_at")
                ->paginate($cantidadPaginado);

            $count_request_needs = $request_needs->total();

            $request_needs_register = array_map(function ($request_need) {
                $request_need["encrypted_id"] = encrypt($request_need["id"]);

                $value_total_needs = $this->getTotalValueNeeds($request_need["necesidades"]);

                $request_need["valor_total_necesidades_actividades"] = !empty($request_need["necesidades"]) ? $value_total_needs["total_value_activities"] : 0;
                $request_need["valor_total_necesidades_repuestos"] = !empty($request_need["necesidades"]) ? $value_total_needs["total_value_spare_parts"] : 0;

                // Cuando proviene una solicitud de un lider y es de aseo para salida de stock el registro no se puede editar
                $request_need["is_editable"] = $request_need["tipo_solicitud"] === 'Stock'
                    ? ($request_need["dependencias_id"] != 19 && $request_need["dependencias_id"] != 23) && $request_need["users_id"] === Auth::user()->id
                    : true;

                return $request_need;
            }, $request_needs->toArray()["data"]);

            return $this->sendResponseAvanzado($request_needs_register, trans('data_obtained_successfully'), null, ["total_registros" => $count_request_needs]);
        }

        // 🔹 Nueva lógica: filtrar por provider_id si está en $filtros
        $providerId = null;
        if (preg_match("/provider_id\s+LIKE\s+'%(\d+)%'/", $filtros, $matches)) {
            $providerId = $matches[1];
            // Eliminar la condición de provider_id del string de filtros
            $filtros = trim(str_replace($matches[0], '', $filtros));
            // Limpiar operadores sueltos
            $filtros = preg_replace('/^(AND|OR)\s*/i', '', $filtros);
            $filtros = preg_replace('/\s*(AND|OR)$/i', '', $filtros);
            $filtros = preg_replace('/\s*(AND|OR)\s*(AND|OR)\s*/i', ' $1 ', $filtros);
        }

        $request_needs_query = RequestNeed::with([
                'contratoDatos',
                'necesidades',
                'rubroDatos',
                'dependencia',
                'historial',
                'ordenes',
                'users'
            ])
            ->where('en_administracion', 1)
            ->when($providerId, function ($query) use ($providerId) {
                $query->whereHas('contratoDatos', function ($subQuery) use ($providerId) {
                    $subQuery->where('mant_providers_id', $providerId);
                });
            })
            ->when($filtros, function ($query) use ($filtros) {
                $query->whereRaw($filtros)->whereNull("deleted_at");
            });

        $request_needs = $request_needs_query
            ->latest("mant_sn_request.updated_at")
            ->paginate($cantidadPaginado);

        $count_request_needs = $request_needs->total();

        $request_needs_register = array_map(function ($request_need) {
            $request_need["encrypted_id"] = encrypt($request_need["id"]);

            $value_total_needs = $this->getTotalValueNeeds($request_need["necesidades"]);

            $request_need["valor_total_necesidades_actividades"] = !empty($request_need["necesidades"]) ? $value_total_needs["total_value_activities"] : 0;
            $request_need["valor_total_necesidades_repuestos"] = !empty($request_need["necesidades"]) ? $value_total_needs["total_value_spare_parts"] : 0;

            // Cuando proviene una solicitud de un lider y es de aseo para salida de stock el registro no se puede editar
            $request_need["is_editable"] = $request_need["tipo_solicitud"] === 'Stock'
                ? ($request_need["dependencias_id"] != 19 && $request_need["dependencias_id"] != 23) && $request_need["users_id"] === Auth::user()->id
                : true;

            return $request_need;
        }, $request_needs->toArray()["data"]);

        return $this->sendResponseAvanzado($request_needs_register, trans('data_obtained_successfully'), null, ["total_registros" => $count_request_needs]);
    }


    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Enviada Admin'
    private function consultasLider($filtros, $cantidadPaginado)
    {
        $request_needs_query = RequestNeed::with('contratoDatos','necesidades','rubroDatos','dependencia','historial','ordenes')
        ->where('users_id',Auth::user()->id)
        ->when($filtros, function ($queryFiltros) use ($filtros) {
            $queryFiltros
                ->whereRaw($filtros)
                ->whereNull("deleted_at");
        });

        $request_needs = $request_needs_query
                            ->latest("mant_sn_request.updated_at")
                            ->paginate($cantidadPaginado);

        $count_request_needs = $request_needs->total();

        $request_needs_register = array_map(function($request_need){
           $request_need["encrypted_id"] = encrypt($request_need["id"]);
            return $request_need;
        },$request_needs->toArray()["data"]);

        return $this->sendResponseAvanzado($request_needs_register, trans('data_obtained_successfully'), null, ["total_registros" => $count_request_needs]);
    }

        // Maneja la lógica cuando se pasa el parámetro 'tablero' para usuarios administradores
    private function tableroAdmin(){
        $estados_originales = ["Finalizada", "En elaboración", "En revisión", "En trámite", "Cancelada"];
        $estados_reemplazar = ["finalizada", "en_elaboracion", "en_revision", "en_tramite", "cancelada"];

        // Base de la consulta con relaciones necesarias
        $requestNeed = RequestNeed::select(
                'estado',
                DB::raw('COUNT(*) as total')
            )
            ->whereNull("deleted_at")
            ->groupBy('estado')
            ->get();

        // Mapeo de estados
        $status_totales = $requestNeed->pluck('total', 'estado')
            ->mapWithKeys(function ($total, $status) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $status) => $total];
            });

        // Obtiene las solicitudes con adiciones pendientes por tramitar 
        $pending_addition_requests = AdditionSparePartActivity::select("request_id")
                                                                ->where('status', 'En trámite')
                                                                ->whereNull('deleted_at')
                                                                ->pluck("request_id")
                                                                ->toArray();
                                                            
        $request_pending_to_addition = RequestNeed::whereIn("id",$pending_addition_requests)->count();                                                    
        $status_totales["solicitud_adicion"] = $request_pending_to_addition;

        // Calcular el total de registros
        $totalSuma = $requestNeed->sum('total');

        // Devolvemos la respuesta
        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'estados' => $status_totales,
            'total_solicitudes' => $totalSuma
        ]);
    }

    public function getTypesRequest(string|int $dependence){
        $types_request = [];

        if(Auth::user()->hasRole("Administrador de mantenimientos")){
            $types_request[] = ["name" => "Activo","value" => "Activo"];
            $types_request[] = ["name" => "Compra/Almacén","value" => "Inventario"];
            $isNotForCleaning = $dependence != 'Subgerencia de Aseo' && $dependence !== "Gestión Aseo" && $dependence != self::CLEANING_MANAGEMENT_ID && $dependence !== self::ASSISTANT_MANAGER_SANITATION_ID;

            // Si la dependencia no es de aseo entonces puede crear solicitudes de stock
            if($isNotForCleaning){
                $types_request[] = ["name" => "Stock/Salida", "value" => "Stock"];
            }

            return $this->sendResponse($types_request, trans('data_obtained_successfully'));
        }

        $isForCleaning = $dependence == 'Gestión Aseo' || $dependence == 'Subgerencia de Aseo';
        $types_request[] = ["name" => "Activo","value" => "Activo"];

        if($isForCleaning){
            $types_request[] = ["name" => "Compra/Almacén","value" => "Inventario"];
            $types_request[] = ["name" => "Stock/Salida", "value" => "Stock"];
        }

        return $this->sendResponse($types_request, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene el valor total de las necesidades separadas por repuestos y actividades
     *
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    public function getTotalValueNeeds(array $needs) : array{
        $totalValueActivites = 0;
        $totalValueSpareParts = 0;

        // Bucle para obtener la cantidad total de cada tipo de necesidad
        foreach ($needs as $key => $need) {
            if($need["necesidad"] === "Actividades"){
                // Obtiene la cantidad
                $need["cantidad_final_actividades"] = is_null($need["cantidad_final"]) ? $need["cantidad_solicitada"] : $need["cantidad_final"];
                $totalValueActivites += ($need["total_value"]) * $need["cantidad_final_actividades"];
            }
            else{
                $totalValueSpareParts += ($need["total_value"]) * $need["cantidad_entrada"];
            }
        }

        return ["total_value_activities" => $totalValueActivites, "total_value_spare_parts" => $totalValueSpareParts];
    }

    public function getNeedActivities($needs){
        foreach ($needs as $need) {
            if($need["tipo_necesidad"] === "Actividades"){
                $need["valor_total"] = ($need["total_value"]) * $need["cantidad_final"];
            }
        }

        return $needs->toArray();
    }
    
    public function allActivesByType($tipo, $d) {
        
        $depend = '';
        if (is_numeric($d)) {
            $depend = $d;
        } else {
            $depend = DB:: table('dependencias')->where('nombre', $d)->first();
            $depend = $depend->id;
        }

        $actives = [];

        // Valida que obtenga los datos de los activos que tiene numero de inventario
        if($tipo == 10 || $tipo == 9 || $tipo == 18 || $tipo == 15 || $tipo == 12){
            $actives = $this->getActives("mant_resume_equipment_machinery",$tipo,$depend,["name_equipment","no_inventory"]);
        }
        else{
            $actives = $this->getActives("mant_resume_machinery_vehicles_yellow",$tipo,$depend,["name_vehicle_machinery","plaque"]);
        }
        
        return $this->sendResponse($actives, trans('data_obtained_successfully'));
    }

    public function getActives(string $table_name,int $type_id,int $dependence_id, array $fields_to_concat){
        $active = DB::table($table_name)
        ->select('*',DB::raw('
            CASE
                WHEN '. $fields_to_concat[1] .' IS NOT NULL THEN CONCAT('. $fields_to_concat[0] .', " ", '. $fields_to_concat[1] .')
                ELSE CONCAT('. $fields_to_concat[0] .', " ", " ")
            END AS name
        '))
        ->where('mant_asset_type_id', $type_id)
        ->where('status', 'Activo')
        ->where('dependencias_id', $dependence_id)
        ->whereNull('deleted_at')
        ->get()->toArray();

        return $active;
    }

    public function allDescriptions($tipo_necesidad,$contrato) {
        // mant_import_activities_provider_contract Actividades ImportActivitiesProviderContract
        // mant_import_spare_parts_provider_contract Repuestos ImportSparePartsProviderContract
        if($tipo_necesidad && $contrato){
            if($tipo_necesidad == "Repuestos"){
                $descripciones = ImportSparePartsProviderContract::where("mant_provider_contract_id",$contrato)
                ->latest()
                ->get();    
            }else{

                $descripciones = ImportActivitiesProviderContract::where("mant_provider_contract_id",$contrato)
                ->latest()
                ->get();    
            }
        
        return $this->sendResponse($descripciones->toArray(), trans('data_obtained_successfully'));
        }
    }

    /**
     * Obtiene todos los productos en stock de una bodega específica con cantidad mayor a cero.
     * Recalcula el costo unitario sin IVA y el valor del IVA para cada producto.
     *
     * @param string $winery Nombre o identificador de la bodega a consultar.
     * @return \Illuminate\Http\JsonResponse Respuesta con los datos ajustados de stock.
     */
    public function getAllDescriptionsToStock(string $winery)
    {
        $descriptions = Stock::where("bodega", $winery)
            ->where("cantidad", ">", 0)
            ->orderBy("articulo")
            ->get()
            ->map(function ($item) {
                // Se recalcula el costo base (sin IVA) y se calcula el valor del IVA (19%)
                $nuevo_costo = $item->costo_unitario / 1.19;
                $nuevo_iva = $item->costo_unitario - $nuevo_costo;
                $item->iva = $nuevo_iva;
                $item->costo_unitario = $nuevo_costo;
                return $item;
            })
            ->toArray();

        return $this->sendResponse($descriptions, trans('data_obtained_successfully'));
    }


    public function getPhysicalQuantityStockByDescription(int $descriptionId){
        $physical_quantity = Stock::select("cantidad")->where("id",$descriptionId)->orderBy("articulo")->first()->cantidad;
        return $this->sendResponse($physical_quantity, trans('data_obtained_successfully'));
    }

    public function getAvailableQuantityStockByDescription(int $descriptionId){
        $stock = Stock::select(["cantidad","id_solicitud_necesidad","codigo"])->where("id",$descriptionId)->orderBy("articulo")->first();

        // Obtiene las cantidades solicitadas de una descripcion
        // $amounts_requested_in_requests = DB::table('mant_sn_request_needs as request_items')
        // ->join('mant_sn_request as request', 'request_items.mant_sn_request_id', '=', 'request.id')
        // ->whereIn("request.estado",["En elaboración","En trámite"])
        // ->where("request_items.descripcion",$descriptionId)
        // ->where("request_items.estado_stock_almacen","Salida Pendiente")
        // ->whereNull("request.deleted_at")
        // ->whereNull("request_items.deleted_at")
        // ->where("request_items.codigo",$stock->codigo)
        // ->sum("request_items.cantidad_solicitada");
        $amounts_requested_in_requests = RequestNeedItem::with("solicitud")->whereHas('solicitud', function ($query) {
            $query->whereIn('estado', ['En elaboración', 'En trámite'])
                  ->whereNull('deleted_at');
        })
        ->where('descripcion', $descriptionId)
        ->whereNull('deleted_at')
        ->where('codigo', $stock->codigo)
        ->get();

        $filteredNeeds = $amounts_requested_in_requests->filter(function ($need) {
            return $need["solicitud"]["estado_stock_almacen"] === 'Salida Pendiente' || $need["solicitud"]["estado_stock_almacen"] == null;
        });
        
        $sumCantidadSolicitada = $filteredNeeds->sum('cantidad_solicitada');

        $available_quantity_stock = $stock->cantidad - $sumCantidadSolicitada;

        return $this->sendResponse($available_quantity_stock, trans('data_obtained_successfully'));
    }

    public function allDescriptionsByrequest($SolicitudId) {

        // Obtieniendo las descripciones de las ordenes ya creadas
        $requestNeedOrdersId = RequestNeedOrders::select("id")->where("mant_sn_request_id",base64_decode($SolicitudId))->pluck("id");

        $orders_item_descriptions = RequestNeedOrdersItem::select("mant_sn_request_needs_id_real")->whereIn("mant_sn_orders_id",$requestNeedOrdersId)->whereNotNull("mant_sn_request_needs_id_real")->pluck("mant_sn_request_needs_id_real");

        $descripciones = RequestNeedItem::whereNotIn("id",$orders_item_descriptions)->where("mant_sn_request_id",base64_decode($SolicitudId))
        ->latest()
        ->get();  

        return $this->sendResponse($descripciones->toArray(), trans('data_obtained_successfully'));
        
    }

    
   

    public function allDescriptionsByrequestId($SolicitudId) {
        $descripciones = RequestNeedItem::where("mant_sn_request_id",base64_decode($SolicitudId))
        ->latest()
        ->get();    
        return $this->sendResponse($descripciones->toArray(), trans('data_obtained_successfully'));
        
    }

    public function allProviderInternals() {

        $usuarios = User::role('mant Proveedor interno')->get();
        
        return $this->sendResponse($usuarios->toArray(), trans('data_obtained_successfully'));
        
    }
    
    public function aseoContracts($rubro)
    {
        // Normaliza entrada
        $rubro = str_contains($rubro, "-") ? explode("-", $rubro) : $rubro;

        $contratos = ProviderContract::with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
            ->where("condition", "Activo")
            ->whereHas('mantBudgetAssignation', function ($query) use ($rubro) {
                $query->whereHas('mantAdministrationCostItems', function ($query) use ($rubro) {
                    if (Auth::user()->hasRole("Administrador de mantenimientos")) {
                        $query->where('mant_heading_id', $rubro[0])
                            ->whereIn('cost_center_name', [$rubro[1], $rubro[1] . " \r\n"]);
                    } else {
                        $query->where('mant_heading_id', $rubro[0])
                            ->where('cost_center', 3);
                    }
                });
            })
            ->latest()
            ->get();

        $contratosNuevos = [];

        foreach ($contratos as $contrato) {
            foreach ($contrato->mantBudgetAssignation as $assignation) {
                foreach ($assignation->mantAdministrationCostItems as $item) {
                    $match = false;

                    if (Auth::user()->hasRole("Administrador de mantenimientos")) {
                        $match = $item->mant_heading_id == $rubro[0] &&
                                (trim($item->cost_center_name) == trim($rubro[1]));
                    } else {
                        $match = $item->mant_heading_id == $rubro[0] &&
                                $item->cost_center == 3;
                    }

                    if (!$match) continue;

                    $valueToExecute = RequestNeed::where("mant_administration_cost_items_id", $item->id)
                        ->where("rubro_objeto_contrato_id", $contrato->id)
                        ->whereNotIn("estado", ["Cancelada", "Finalizada"])
                        ->sum("total_solicitud");

                    $valueAvailable = $item->value_avaible - $valueToExecute;

                    if ($valueAvailable > 0) {
                        $obj = new \stdClass();
                        $obj->id = $contrato->id;
                        $obj->object = $contrato->object;
                        $obj->contract_number = $contrato->contract_number;
                        $obj->mant_administration_cost_items_id = $item->id;
                        $obj->value_avaible = $valueAvailable;
                        $obj->provider = $contrato->providers; // opcional

                        $contratosNuevos[] = $obj;
                    }
                }
            }
        }

        return $this->sendResponse($contratosNuevos, trans('data_obtained_successfully'));
    }

    public function aseoContracts1($rubro) {

        
        $rubro = str_contains($rubro,"-") !== false ? explode("-",$rubro) : $rubro;
        $contratos = ProviderContract::with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
        ->where("condition","Activo")
        ->whereHas('mantBudgetAssignation', function ($query) use ($rubro) {
            $query->whereHas('mantAdministrationCostItems', function ($query) use ($rubro) {
                if(Auth::user()->hasRole("Administrador de mantenimientos")){
                    $query->where('mant_heading_id', $rubro[0]);
                    $query->whereIn('cost_center_name', [$rubro[1], $rubro[1]." \r\n"]);
                }
                else{
                    $rubro = explode("-",$rubro);
                    $query->where('mant_heading_id', $rubro[0])->where('cost_center', 3);
                }

            });
        })
        ->latest()
        ->get()->toArray();  
        
        $contratosNuevos = array();
        foreach ($contratos as $key => $contrato) {

            $objetotemporal = new \stdClass();

            $objetotemporal->id=$contrato['id'];

            $objetotemporal->object=$contrato['object'];
            $objetotemporal->contract_number=$contrato['contract_number'];
            //Recorre los rubros que tiene este proyeto
            foreach ($contrato['mant_budget_assignation'][0]['mant_administration_cost_items'] as $rubro_contrato){
                // Se valida que la variable $rubro_contrato['mant_heading_id'] sea igual a el rubro que se mando por el select del fileds, el cual se recibe al inicio de la funcion
                if($rubro_contrato['mant_heading_id'] == $rubro[0] && ($rubro_contrato["cost_center_name"] == $rubro[1] || $rubro_contrato["cost_center_name"] == $rubro[1]." \r\n")){
                    //Asigna el id a la variable que se necesita
                    $objetotemporal->mant_administration_cost_items_id = $rubro_contrato['id'];
                    //Asigna valor disponible a la variable que se necesita
                    $objetotemporal->value_avaible = $rubro_contrato['value_avaible'];
                }

            }

            if($objetotemporal->value_avaible > 0){
                $contratosNuevos[] = $objetotemporal;
            }
            //id de la asignacion del rubro en la asignacion presupuestal
            // $objetotemporal->mant_administration_cost_items_id = $contrato['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['id'];
            // $objetotemporal->value_avaible = $contrato['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['value_avaible'];
        }

        // dd($contratosNuevos);
        return $this->sendResponse($contratosNuevos, trans('data_obtained_successfully'));
    }

    public function getAvailableValueByItem(int $itemId) {
        // Obtiene el valor disponible de un rubro específico
        $item = AdministrationCostItem::find($itemId);

        return $this->sendResponse($item, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los contratos que los rubros tengan el centro costo de aseo
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCleaningManagementAllContracts() : array{
        $contratos = ProviderContract::with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
        ->whereHas('mantBudgetAssignation', function ($query) {
            $query->whereHas('mantAdministrationCostItems', function ($query) {
                    $query->where('cost_center', 3);
            });
        })->where('condition','Activo')
        ->latest()
        ->get()->toArray(); 

        return $this->sendResponse($contratos, trans('data_obtained_successfully'));
    }


      /**
     * Obtiene todos los contratos que los rubros tengan el centro costo segun su dependencia
     *
     * @author  Leonardo - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getContractsByDependence(int|string $id_proceso) : array{
        $costCenter = $id_proceso == self::ASSISTANT_MANAGER_SANITATION_ID  || $id_proceso == self::CLEANING_MANAGEMENT_ID  ?  ['03']  : ['01', '02', '08', '09'];
        $contratos = ProviderContract::with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
        ->whereHas('mantBudgetAssignation', function ($query) use ($costCenter) {
            $query->whereHas('mantAdministrationCostItems', function ($query) use ($costCenter) {
                    $query->whereIn('cost_center', $costCenter );
            });
        })->where('condition','Activo')
        ->orderBy('object')
        ->get()->toArray(); 
        
        return $this->sendResponse($contratos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los contratos que los rubros tengan el centro costo segun su dependencia
     *
     * @author  Leonardo - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getContractsByActive(int|string $id_activo) : array{


        $rubros = DB::table('mant_sn_actives_heading')
        ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
        ->where("activo_id",$id_activo)
        ->whereNull("deleted_at")
        ->get()->toArray();

        $contratos = ProviderContract::with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
        ->whereHas('mantBudgetAssignation', function ($query) use ($rubros) {
            $query->whereHas('mantAdministrationCostItems', function ($query) use ($rubros) {
                    $query->whereIn('cost_center', $rubros );
            });
        })->where('condition','Activo')
        ->orderBy('object')
        ->get()->toArray(); 
        
        return $this->sendResponse($contratos, trans('data_obtained_successfully'));
    }

    public function getContractsByDependency(int|string $dependencyId) {
        if($dependencyId == self::CLEANING_MANAGEMENT_ID || $dependencyId == self::ASSISTANT_MANAGER_SANITATION_ID){
            $contratos = ProviderContract::where("condition","Activo")->with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
            ->whereHas('mantBudgetAssignation', function ($query) {
                $query->whereHas('mantAdministrationCostItems', function ($query) {
                    $query->where("code_cost","2.3.2.02.02.008")
                    ->where("cost_center",03);
                });
            })
            ->latest()
            ->get()->toArray();
        }
        else{
            $contratos = ProviderContract::
            where("dependencias_id",$dependencyId)
            ->where("condition","Activo")
            ->latest()
            ->get()->toArray();  
        }

        return $this->sendResponse($contratos, trans('data_obtained_successfully'));
    }


    public function allContracts(int|string $rubro, int|string $secondRubro = null) 
    { 

        // dd($rubro, $secondRubro);
        // 🔹 Heading principal
        $headings = DB::table('mant_sn_actives_heading')
            ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo','centro_costo_id'])
            ->where("id", $rubro)
            ->whereNull("deleted_at")
            ->first();

        if (!$headings) {
            return $this->sendError("El rubro principal no existe", [], 404);
        }

        // 🔹 Heading secundario (si aplica)
        $secondHeadings = null;
        if ($secondRubro) {
            $secondHeadings = DB::table('mant_sn_actives_heading')
                ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo','centro_costo_id'])
                ->where("id", $secondRubro)
                ->whereNull("deleted_at")
                ->first();

            if (!$secondHeadings) {
                return $this->sendError("El segundo rubro no existe", [], 404);
            }
        }

        // dd($headings, $secondHeadings);

        // 🔹 Consultar contratos
        $contratos = ProviderContract::where("condition", "Activo")
            ->with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
            ->whereHas('mantBudgetAssignation', function ($query) use ($headings, $secondHeadings) {
                // Condición para el primer rubro
                $query->whereHas('mantAdministrationCostItems', function ($query) use ($headings) {
                    // $query->where('mant_heading_id', $headings->rubro_id)
                        $query->where("code_cost", $headings->code_heading)
                        ->where("cost_center", $headings->centro_costo_codigo);
                });

                // Condición para el segundo rubro (si existe)
                if ($secondHeadings) {
                    $query->whereHas('mantAdministrationCostItems', function ($query) use ($secondHeadings) {
                        // $query->where('mant_heading_id', $secondHeadings->rubro_id)
                            $query->where("code_cost", $secondHeadings->code_heading)
                            ->where("cost_center", $secondHeadings->centro_costo_codigo);
                    });
                }
            })
            ->latest()
            ->get()
            ->toArray();


            // dd($contratos->toSql(), $contratos->getBindings());


        // 🔹 Construcción de respuesta
        $contratosNuevos = [];
        foreach ($contratos as $contrato) {
            $objetotemporal = new \stdClass();
            $objetotemporal->id = $contrato['id'];
            $objetotemporal->object = $contrato['object'];
            $objetotemporal->contract_number = $contrato['contract_number'];
            $objetotemporal->value_avaible = 0;

            $foundFirst = false;
            $foundSecond = !$secondHeadings; // Si no hay segundo, lo damos como cumplido

            // dd($contrato['mant_budget_assignation'][0]['mant_administration_cost_items']);
            foreach ($contrato['mant_budget_assignation'][0]['mant_administration_cost_items'] as $rubro_contrato) {
                // Validar contra primer rubro
                if ($rubro_contrato['mant_heading_id'] == $headings->rubro_id 
                    && $rubro_contrato["mant_center_cost_id"] == $headings->centro_costo_id) {

                    $foundFirst = true;
                    $objetotemporal->mant_administration_cost_items_id = $rubro_contrato['id'];

                    $valueToExecute = RequestNeed::where("mant_administration_cost_items_id", $rubro_contrato['id'])
                        ->where("rubro_objeto_contrato_id", $objetotemporal->id)
                        ->whereNotIn("estado", ["Cancelada", "Finalizada"])
                        ->sum("total_solicitud");

                    $objetotemporal->value_avaible += $rubro_contrato['value_avaible'] - $valueToExecute;
                }

                // Validar contra segundo rubro (si existe)
                if ($secondHeadings && $rubro_contrato['mant_heading_id'] == $secondHeadings->rubro_id 
                    && $rubro_contrato["mant_center_cost_id"] == $secondHeadings->centro_costo_id) {

                    $foundSecond = true;
                    $objetotemporal->mant_administration_cost_items_id_second = $rubro_contrato['id'];

                    $valueToExecuteSecond = RequestNeed::where("mant_administration_cost_items_id", $rubro_contrato['id'])
                        ->where("rubro_objeto_contrato_id", $objetotemporal->id)
                        ->whereNotIn("estado", ["Cancelada", "Finalizada"])
                        ->sum("total_solicitud");

                    $objetotemporal->value_avaible += $rubro_contrato['value_avaible'] - $valueToExecuteSecond;
                }
            }


            // Solo incluir si cumple rubro 1 y (si aplica) rubro 2
            if ($foundFirst && $foundSecond && $objetotemporal->value_avaible > 0) {
                $contratosNuevos[] = $objetotemporal;
            }
        }

        return $this->sendResponse($contratosNuevos, trans('data_obtained_successfully'));
    }

    
    public function allContractsInicial(int|string $rubro) {
        $headings = DB::table('mant_sn_actives_heading')
        ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo','centro_costo_id'])
        ->where("id",$rubro)
        ->whereNull("deleted_at")
        ->first();

        $contratos = ProviderContract::where("condition","Activo")->with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
        ->whereHas('mantBudgetAssignation', function ($query) use ($headings) {
            $query->whereHas('mantAdministrationCostItems', function ($query) use ($headings) {
                $query->where('mant_heading_id', $headings->rubro_id)
                ->where("code_cost",$headings->code_heading)
                ->where("cost_center",$headings->centro_costo_codigo);
            });
        })
        ->latest()
        ->get()->toArray();   

        $contratosNuevos = array();
        foreach ($contratos as $key => $contrato) {
            $objetotemporal = new \stdClass();

            $objetotemporal->id=$contrato['id'];

            $objetotemporal->object=$contrato['object'];
            $objetotemporal->contract_number=$contrato['contract_number'];
            $objetotemporal->value_avaible = 0;

            //Recorre los rubros que tiene este proyeto
            foreach ($contrato['mant_budget_assignation'][0]['mant_administration_cost_items'] as $rubro_contrato){
                // Se valida que la variable $rubro_contrato['mant_heading_id'] sea igual a el rubro que se mando por el select del fileds, el cual se recibe al inicio de la funcion
                if($rubro_contrato['mant_heading_id'] == $headings->rubro_id && $rubro_contrato["mant_center_cost_id"] == $headings->centro_costo_id){
                    //Asigna el id a la variable que se necesita
                    $objetotemporal->mant_administration_cost_items_id = $rubro_contrato['id'];

                    // Valor total de las necesidades pendientes
                    $valueToExecute = RequestNeed::where("mant_administration_cost_items_id",$rubro_contrato['id'])->where("rubro_objeto_contrato_id",$objetotemporal->id)->whereNotIn("estado",["Cancelada","Finalizada"])->sum("total_solicitud"); 

                    //Asigna valor disponible a la variable que se necesita
                    $objetotemporal->value_avaible = $rubro_contrato['value_avaible'] - $valueToExecute;
                    // $objetotemporal->value_avaible = $rubro_contrato['value_avaible'];

                }

            }
            //id de la asignacion del rubro en la asignacion presupuestal
            // $objetotemporal->mant_administration_cost_items_id = $contrato['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['id'];
            // $objetotemporal->value_avaible = $contrato['mant_budget_assignation'][0]['mant_administration_cost_items'][0]['value_avaible'];

            // Valida que tenga valor disponible para mostrar en el select de objeto de contrato

            if($objetotemporal->value_avaible > 0){
                $contratosNuevos[] = $objetotemporal;
            }
        }


        return $this->sendResponse($contratosNuevos, trans('data_obtained_successfully'));
    }

    public function allContractsInventario(int|string $rubro)
    {
        // Asegura que $rubro sea string antes de hacer explode
        $rubro = (string) $rubro;
        $datos_rubro = explode("-", $rubro);
        $rubro_id = $datos_rubro[0] ?? null;
        $centro_costo = $datos_rubro[1] ?? null;
    
        if (!$rubro_id || !$centro_costo) {
            return $this->sendError("Formato de rubro inválido. Debe ser 'ID-NOMBRE'.");
        }
    
        // Busca el centro de costo
        $datosCentroCosto = DB::table('mant_center_cost')
            ->select('*')
            ->where('name', $centro_costo)
            ->whereNull('deleted_at')
            ->first();
    
        if (!$datosCentroCosto) {
            return $this->sendError("Centro de costo '{$centro_costo}' no encontrado.");
        }
    
        // Simulamos objeto heading para usarlo luego en el foreach
        $headings = (object)[
            'rubro_id' => (int)$rubro_id,
            'centro_costo_id' => $datosCentroCosto->id
        ];
    
        // Consulta de contratos
        $contratos = ProviderContract::where("condition", "Activo")
            ->with(["providers", "mantBudgetAssignation.mantAdministrationCostItems"])
            ->whereHas('mantBudgetAssignation', function ($query) use ($headings) {
                $query->whereHas('mantAdministrationCostItems', function ($query) use ($headings) {
                    $query->where('mant_heading_id', $headings->rubro_id)
                          ->where('mant_center_cost_id', $headings->centro_costo_id);
                });
            })
            ->latest()
            ->get()
            ->toArray();
    
        $contratosNuevos = [];
    
        foreach ($contratos as $contrato) {
            $objetotemporal = new \stdClass();
    
            $objetotemporal->id = $contrato['id'];
            $objetotemporal->object = $contrato['object'];
            $objetotemporal->contract_number = $contrato['contract_number'];
            $objetotemporal->mant_administration_cost_items_id = null;
            $objetotemporal->value_avaible = 0;
    
            // Recorre rubros asociados a la asignación presupuestal
            foreach ($contrato['mant_budget_assignation'][0]['mant_administration_cost_items'] as $rubro_contrato) {
                if (
                    $rubro_contrato['mant_heading_id'] == $headings->rubro_id &&
                    $rubro_contrato["mant_center_cost_id"] == $headings->centro_costo_id
                ) {
                    $objetotemporal->mant_administration_cost_items_id = $rubro_contrato['id'];
    
                    // Total solicitado que aún no ha sido ejecutado
                    $valueToExecute = RequestNeed::where("mant_administration_cost_items_id", $rubro_contrato['id'])
                        ->where("rubro_objeto_contrato_id", $objetotemporal->id)
                        ->whereNotIn("estado", ["Cancelada", "Finalizada"])
                        ->sum("total_solicitud");
    
                    // Calcula valor disponible
                    $objetotemporal->value_avaible = $rubro_contrato['value_avaible'] - $valueToExecute;
                    //  $objetotemporal->value_avaible = $rubro_contrato['value_avaible'] ;

                }
            }
    
            if ($objetotemporal->value_avaible > 0) {
                $contratosNuevos[] = $objetotemporal;
            }
        }

    
        return $this->sendResponse($contratosNuevos, trans('data_obtained_successfully'));
    }
    
    
    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateRequestNeedRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestNeedRequest $request) {

        $input = $request->all();

        $userAuth = Auth::user();

        // Validacion para guardar el id del rubro que se va a ejecutar al finalizar una solicitud
        if (!empty($input["contrato_datos"])) {
            $input["mant_administration_cost_items_id"] = array_key_exists("mant_administration_cost_items_id", $input["contrato_datos"])
                ? $input["contrato_datos"]["mant_administration_cost_items_id"]
                : (array_key_exists("rubro_aseo_datos", $input) && array_key_exists("mant_administration_cost_items_id", $input["rubro_aseo_datos"])
                    ? $input["rubro_aseo_datos"]["mant_administration_cost_items_id"]
                    : null);
        }

        if (array_key_exists("rubro_aseo_datos", $input) && !empty($input["rubro_aseo_datos"])) {
            $input["mant_administration_cost_items_id"] = array_key_exists("mant_administration_cost_items_id", $input["rubro_aseo_datos"])
                ? $input["rubro_aseo_datos"]["mant_administration_cost_items_id"]
                : null;
        }

        
        // Valida el valor disponible
        if($input["tipo_solicitud"] == "Activo"){
            // Validacion por si quien hace la solicitud es un lider de proceso de gestion de aseo
            if(!empty($input["valor_disponible"])){
                if($input["valor_disponible"] <= 0){
                    return $this->sendSuccess("La solicitud no puede ser guardada, ya que el valor del rubro excede el límite establecido. Si desea continuar, le recomendamos que intente modificando el rubro y seleccionando un nuevo contrato. En caso contrario, cierre la solicitud para finalizar.", 'info');
                }
            }
        }

        if(empty($input["necesidades"])){
            return $this->sendSuccess("En este momento, la lista no cuenta con ninguna necesidad registrada. Para continuar con la creación de la solicitud, por favor, proceda a listar las necesidades correspondientes.", 'info');
        }

        if(!empty($input["url_documents"])){
            $input["url_documents"] = implode(",",$input["url_documents"]);
        }
        
        // Se valida si la dependencia ingresa como un texto o un id, si ingresa como texto, la solucitud la estacreando un administrador, 
        // si es un id, la solicitud la esta creando un lider de proceso.
        if (isset($input['dependencia']) && !is_numeric($input['dependencia'])) {
        $dependencia  = DB :: table('dependencias')->where('nombre',$input['dependencia'])->first();
        $input['dependencias_id'] = $dependencia->id;
        }  else {
        $input['dependencias_id'] = $input['dependencia'];
        }

        //si es un usuario administrador quien crea la solicitud se mostrara 1, esto con el fin de mostrar solo las solicitudes en estado pendiente del administrador.
        if (auth()->user()->hasRole('Administrador de mantenimientos')) {
            $input['en_administracion'] = 1;
        }

        // Realiza las validaciones
        $validacionResult = $this->_validacionesNecesidad($input);

        // Verifica si hubo problemas en las validaciones
        if ($validacionResult !== null) {
            // Hubo problemas, por lo tanto, retorna el mensaje de validación
            return $this->sendSuccess($validacionResult, 'info');
        }

        $input['users_id'] = Auth::user()->id;
        if(auth()->user()->hasRole('Administrador de mantenimientos')){
            $input['estado'] = 'En elaboración';
            // $input['consecutivo'] =  $this->_getConsecutivo();
        } else {
            $input['estado'] = 'En elaboración';
        }

        if($input['tipo_solicitud'] === "Stock"){
            $input['estado'] = "En elaboración";
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

        $input["observacion"] = $input["observacion"] ?? "";
        // if($input['tipo_solicitud'] != "Stock"){
        //     if(!empty($input["rubro_id"])){
        //         $input["rubro_id"] = strpos($input["rubro_id"],"-") !== false ? explode("-",$input["rubro_id"])[0] : $input["rubro_id"];
        //     }
        // }
       
        // Inserta el registro en la base de datos
        $requestNeed = $this->requestNeedRepository->create($input);
        // se guarda el id encriptado para dirigir a la vista de observación
        $requestNeed["encrypted_id"] = encrypt($requestNeed["id"]);
        RequestNeedItem::where("mant_sn_request_id",$requestNeed['id'])->delete();

          

            //crea las necesidades
            foreach ($input["necesidades"] as $necesidadEntrante) {
                $necesidad = json_decode($necesidadEntrante);


                $item = isset($necesidad->descripcion_datos) 
                    ? ($necesidad->descripcion_datos->articulo ?? $necesidad->descripcion_datos->description ?? null) 
                    : null;


                $this->_addNecesidad(
                    $requestNeed["id"],
                    $necesidad->necesidad ?? null,
                    $necesidad->descripcion ?? 0,
                    $item,
                    $necesidad->unidad_medida,
                    $necesidad->valor_unitario ?? 0, 
                    $necesidad->IVA ?? 0,
                    $necesidad->cantidad_solicitada ?? 0,
                    $necesidad->valor_total ?? 0,
                    'Pendiente',
                    $input["tipo_solicitud"],
                    $necesidad->necesidad ?? null,
                    $necesidad->tipo_mantenimiento ?? "",
                    $necesidad->descripcion_datos->codigo ?? ($necesidad->codigo ?? null),
                    $necesidad->total_value ?? 0,

                ); 
            }



            $this->createHistory($input["observacion"],$requestNeed["estado"],$requestNeed["id"],'Se crea el registro');
            // Efectua los cambios realizados
            DB::commit();

            //Relaciones

            $requestNeed->necesidades;
            $requestNeed->rubroDatos;
            $requestNeed->dependencia;
            $requestNeed->dependencias;
            $requestNeed->historial;
            $requestNeed->contratoDatos;
            $requestNeed->users;

            // Cuando proviene una solicitud de un lider y es de aseo para salida de stock el registro no se puede editar
            $requestNeed["is_editable"] = $requestNeed["tipo_solicitud"] === 'Stock' ? ($requestNeed["dependencias_id"] != 19 && $requestNeed["dependencias_id"] != 23) && $requestNeed["users_id"] === $userAuth->id : true;
            

            return $this->sendResponse($requestNeed->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Envia la solicitud a tramite a almacen CAM o almacen Aseo.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $requestNeedId
     *
     * @return Response
     */
    public function sendStockRequestToProcessing(int|string $requestNeedId){
        $requestNeed = RequestNeed::with("necesidades")->where("id",$requestNeedId)->first();

        // Asigna el codigo de la descripcion del stock
        foreach ($requestNeed["necesidades"] as $necesity) {
            $necesity["codigo"] = Stock::select("codigo")->where("id",$necesity["descripcion"])->first()->codigo;
        }

        $userLogged = Auth::user();

        $requestNeedOrderData = ["mant_sn_request_id" => $requestNeedId, "rol_asignado" => $userLogged->hasRole("Administrador de mantenimientos") ? "Almacén CAM" : "Almacén de Aseo","orders" => $requestNeed["necesidades"]->toArray(),"user_id" => $userLogged->id,"user_name" => $userLogged->name, "bodega" => "Almacén CAM", "request_type" => $requestNeed["tipo_solicitud"],"tipo_solicitud" => $requestNeed["tipo_solicitud"]];

        $this->_createRequestNeedOrder($requestNeedOrderData);

        $consecutivo =  is_null($requestNeed["consecutivo"]) ? $this->_getConsecutivo() : $requestNeed["consecutivo"];

        $requestNeed = $this->requestNeedRepository->update(["estado" => "En trámite","consecutivo" => $consecutivo, "en_administracion" => 1 ], $requestNeedId);
        $this->createHistory($requestNeed["observacion"],$requestNeed["estado"],$requestNeed["id"], 'Se envió a revisión' );
        $requestNeed->historial;
        return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));
    }

    /**
     * Envia la solicitud a tramite a almacen CAM o almacen Aseo.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $requestNeedId
     *
     * @return Response
     */
    public function sendWareHouseRequestToProcessing(int|string $requestNeedId){
        $requestNeed = RequestNeed::with("necesidades")->where("id",$requestNeedId)->first()->toArray();

        $availabilityWineryAndSupplier = $this->_getAvailabilityWineryAndSupplier($requestNeed);

        $providerContract = ProviderContract::with("providers")->where("id",$requestNeed["rubro_objeto_contrato_id"])->first()->toArray();

        $requestNeed["rol_asignado_nombre"] = $providerContract["providers"]["document_type"] ." - ". $providerContract["providers"]["identification"] . " - " . $providerContract["providers"]["name"] . " - " . $providerContract["providers"]["mail"];
        $requestNeed["proveedor_nombre"] =  $requestNeed["rol_asignado_nombre"];

        $userLogged = Auth::user();
        
        
        $requestNeedOrderData = ["mant_sn_request_id" => $requestNeedId,"tipo_solicitud" => $requestNeed["tipo_solicitud"], "rol_asignado" => $availabilityWineryAndSupplier["to_winery"] ? ($userLogged->hasRole("Administrador de mantenimientos") ? "Almacén CAM" : "Almacén de Aseo") : null,"orders" => $requestNeed["necesidades"],"user_id" => $userLogged->id,"user_name" => $userLogged->name, "id_proveedor_externo" => $providerContract["providers"]["id"], "rol_asignado_nombre" => $requestNeed["rol_asignado_nombre"],"has_only_activities" => $this->_hasOnlyActivityItems($requestNeed["necesidades"]), "bodega" => $availabilityWineryAndSupplier["to_winery"] ?
        (in_array($requestNeed["dependencias_id"], [19, 23]) ? "Almacén de Aseo" : "Almacén CAM") : null];
        
        $order = $this->_createRequestNeedOrder($requestNeedOrderData);
        
        $consecutivo =  is_null($requestNeed["consecutivo"]) ? $this->_getConsecutivo() : $requestNeed["consecutivo"];
        
        $requestNeed = $this->requestNeedRepository->update(["estado" => "En trámite","consecutivo" => $consecutivo, "en_administracion" => 1, "approving_user_id" => $userLogged->id, "approval_date" => date("Y-m-d H:i:s"), "approval_justification" => "" ], $requestNeedId);
        $requestNeed['id_order'] = $order->id;
        // Envía la notificación al proveedor externo
        $this->_sendEmail('1',$requestNeed);

        $this->createHistory($requestNeed["observacion"],$requestNeed["estado"],$requestNeed["id"], 'Se envió a revisión');
        $requestNeed->historial;
        


        return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));
    }

    private function _hasOnlyActivityItems(array $items) : bool{
        $quantitySpareParts = 0;

        // Itera las necesidades para cuantificar la cantidad de repuestos que tiene la solicitud
        foreach ($items as $key => $item) {
            $quantitySpareParts += $item["tipo_necesidad"] === "Repuestos" ? 1 : 0;
        }

        return $quantitySpareParts === 0;
    }    
    
    public function sendRequestToWarehouseCleanliness(int|string $requestNeedId){
        $requestNeed = RequestNeed::with("necesidades")->where("id",$requestNeedId)->first()->toArray();
        
        $nextConsecutiveOrder = $this->nextConsecutiveOrder();
        
        $requestNeedOrder = RequestNeedOrders::create(["mant_sn_request_id" => $requestNeed["id"],'users_id' => $requestNeed["users_id"],"tipo_solicitud" => $requestNeed["tipo_solicitud"],"estado" => "Orden en trámite","consecutivo" => $nextConsecutiveOrder,"rol_asignado_nombre" => $requestNeed["rol_asignado_nombre"] ?? null,"tramite_almacen" => "Salida Pendiente","estado_proveedor" => "Pendiente", "id_proveedor_externo" => $requestNeed["id_proveedor_externo"] ?? null, "bodega" => "Almacén de Aseo"]);
        
        HistoryOrder::create(['mant_sn_orders_id'=>$requestNeedOrder["id"],'users_id' => $requestNeed["users_id"], 'nombre_usuario' => Auth::user()->name, 'estado' => 'Orden en trámite', 'accion' => 'Se crea registro' ]);
        
        
        // Inicializa la informacion que necesitara los items de la orden
        $requestOrderItem = ["mant_sn_orders_id" => $requestNeedOrder["id"], "mant_sn_request_needs_id" => $requestNeedOrder["mant_sn_request_id"], "orders" => $requestNeed["necesidades"] ];

        $this->_assignOrderItem($requestOrderItem,true);

        $consecutivo =  is_null($requestNeed["consecutivo"]) ? $this->_getConsecutivo() : $requestNeed["consecutivo"];

        $requestNeed = $this->requestNeedRepository->update(["estado" => "En trámite","consecutivo" => $consecutivo, "en_administracion" => 1 ], $requestNeedId);
        $this->createHistory($requestNeed["observacion"],$requestNeed["estado"],$requestNeed["id"], 'Se envió a revisión');

        return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene la disponibilidad del almacen o provedoor para recibir la orden.
     *
     * @author Kleverman Salazar Florez - 24 Octubre 2024
     * @version 1.0.0
     *
     */
    private function _getAvailabilityWineryAndSupplier(array $requestNeed) : array{
        $quantityNecesities = ["Actividades" => 0, "Repuestos" => 0];
        $availabilityWineryAndSupplier = ["to_winery" => false, "to_external_supplier" => false];
        
        // Iteracion para validar si debe enviar solo al almacen, proveedor o ambos
        foreach (array_column($requestNeed["necesidades"],"necesidad") as $necesity) {
            $quantityNecesities[$necesity]++;
        }

        // Determinar usuarios a enviar
        $availabilityWineryAndSupplier["to_external_supplier"] = $quantityNecesities["Actividades"] > 0;
        $availabilityWineryAndSupplier["to_winery"] = $quantityNecesities["Repuestos"] > 0;

        return $availabilityWineryAndSupplier;
    }

    private function _createRequestNeedOrder($data){
        $nextConsecutive = $this->nextConsecutiveOrder();
        $requestNeedOrder = RequestNeedOrders::create(["mant_sn_request_id" => $data["mant_sn_request_id"],'users_id' => $data["user_id"],"tipo_solicitud" => $data["tipo_solicitud"],"estado" => "Orden en trámite","consecutivo" => $nextConsecutive,"rol_asignado_nombre" => $data["rol_asignado_nombre"] ?? null,"tramite_almacen" => $data["has_only_activities"] ? null : (!empty($data["request_type"]) ? "Salida Pendiente" : "Entrada Pendiente"),"estado_proveedor" => "Pendiente", "id_proveedor_externo" => $data["id_proveedor_externo"] ?? null, "bodega" => $data["bodega"] ?? null]);

        HistoryOrder::create(['mant_sn_orders_id'=>$requestNeedOrder["id"],'users_id' => $data["user_id"], 'nombre_usuario' => $data["user_name"], 'estado' => 'Orden en trámite', 'accion' => 'Se crea registro' ]);

        // Inicializa la informacion que necesitara los items de la orden
        $requestOrderItem = ["mant_sn_orders_id" => $requestNeedOrder["id"], "mant_sn_request_needs_id" => $requestNeedOrder["mant_sn_request_id"], "orders" => $data["orders"] ];

        $this->_assignOrderItem($requestOrderItem);
         return $requestNeedOrder;
    }

    public function _assignOrderItem($data,bool $assignToStock = false){
        foreach ($data["orders"] as $orderItem) {

            RequestNeedOrdersItem::create(["mant_sn_orders_id" => $data["mant_sn_orders_id"],"mant_sn_request_needs_id" => $data["mant_sn_request_needs_id"], "mant_sn_request_needs_id_real" => $orderItem["id"] ?? $orderItem["mant_sn_request_needs_id_real"] ?? null, "descripcion" => $orderItem["descripcion"],"descripcion_nombre" => $orderItem["descripcion_nombre"], "unidad" => $orderItem["unidad_medida"] ?? $orderItem["unidad"], "cantidad" => $orderItem["cantidad_solicitada"] ?? $orderItem["cantidad"], "tipo_mantenimiento" => $orderItem["tipo_mantenimiento"],"tipo_solicitud" => $orderItem["tipo_solicitud"],"tipo_necesidad" => $orderItem["necesidad"] ?? $orderItem["tipo_necesidad"], "estado" => "Pendiente", "codigo_entrada" => $orderItem["codigo"] ?? null, "codigo_salida" => $assignToStock ? $orderItem["codigo"] :  null  ]);
        }
    }

    /**
     * Funcion encargada de generar el consecutivo de la orden
    */ 
    public function nextConsecutiveOrderini() {
        $cantidad = RequestNeedOrders::whereNotNull("consecutivo")->count();

        $numero = $cantidad + 1;
        $numero_rellenado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        $codigo = date("Y") . "-" . $numero_rellenado;
       
        return $codigo;
    }

    public function nextConsecutiveOrder()
    {
        // Obtiene el valor máximo numérico del consecutivo (solo parte después del guion)
        $maxNumero = RequestNeedOrders::whereNotNull('consecutivo')
            ->whereNull('deleted_at')
            ->selectRaw('MAX(CAST(SUBSTRING_INDEX(consecutivo, "-", -1) AS UNSIGNED)) as max_numero')
            ->value('max_numero');

        // Si no hay registros, comienza desde 1
        $numero = ($maxNumero ?? 0) + 1;

        // Rellena con ceros (por ejemplo: 001, 002...)
        $numero_rellenado = str_pad($numero, 3, '0', STR_PAD_LEFT);

        // Genera el código con el año actual
        $codigo = date('Y') . '-' . $numero_rellenado;

        return $codigo;
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateRequestNeedRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        // Validacion para guardar el id del rubro que se va a ejecutar al finalizar una solicitud
        if (!empty($input["contrato_datos"])) {
            $input["mant_administration_cost_items_id"] = array_key_exists("mant_administration_cost_items_id", $input["contrato_datos"])
                ? $input["contrato_datos"]["mant_administration_cost_items_id"]
                : (array_key_exists("rubro_aseo_datos", $input) && array_key_exists("mant_administration_cost_items_id", $input["rubro_aseo_datos"])
                    ? $input["rubro_aseo_datos"]["mant_administration_cost_items_id"]
                    : null);
        }

        if (array_key_exists("rubro_aseo_datos", $input) && !empty($input["rubro_aseo_datos"])) {
            $input["mant_administration_cost_items_id"] = array_key_exists("mant_administration_cost_items_id", $input["rubro_aseo_datos"])
                ? $input["rubro_aseo_datos"]["mant_administration_cost_items_id"]
                : null;
        }

        if(empty($input["necesidades"])){
            return $this->sendSuccess("En este momento, la lista no cuenta con ninguna necesidad registrada. Para continuar con la creación de la solicitud, por favor, proceda a listar las necesidades correspondientes.", 'info');
        }        


    
        // $dependencia  = DB :: table('dependencias')->where('nombre',$input['dependencia'])->first();
        // $input['dependencias_id'] = $dependencia->id;

        // Se valida si la dependencia ingresa como un texto o un id, si ingresa como texto, la solucitud la estacreando un administrador, 
        // si es un id, la solicitud la esta creando un lider de proceso.

        if (isset($input['dependencia']) && !is_numeric($input['dependencia'])) {
            // Verificamos si es un array (por si viene de Vue con un v-model por objeto)
            if (is_array($input['dependencia']) && isset($input['dependencia']['nombre'])) {
                $dependencia = DB::table('dependencias')->where('nombre', $input['dependencia']['nombre'])->first();
            } else {
                // Aquí asumimos que es simplemente un string con el nombre directamente
                $dependencia = DB::table('dependencias')->where('nombre', $input['dependencia'])->first();
            }
        
            // Validamos que se encontró la dependencia
            if (!$dependencia) {
                return $this->sendSuccess('No se encontró la dependencia especificada.', 'error');
            }
        
            $input['dependencias_id'] = $dependencia->id;
        } else {
            // Si ya viene como id, simplemente lo usamos
            $input['dependencias_id'] = $input['dependencias_id'] ?? null;
        }

         // Realiza las validaciones
         $validacionResult = $this->_validacionesNecesidad($input);

         // Verifica si hubo problemas en las validaciones
         if ($validacionResult !== null) {
             // Hubo problemas, por lo tanto, retorna el mensaje de validación
             return $this->sendSuccess($validacionResult, 'info');
         }
 
        //  $input['estado'] = 'En elaboración';
 

        /** @var RequestNeed $requestNeed */
        $requestNeed = $this->requestNeedRepository->find($id);

        if (empty($requestNeed)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

             if (!empty($input['observacion'])) {
                 $observacion = $input["observacion"];
                }
            else{
                 $observacion = "";

             }

            // Actualiza el registro
            $requestNeed = $this->requestNeedRepository->update($input, $id);

            RequestNeedItem::where("mant_sn_request_id",$requestNeed['id'])->delete();

            //crea las necesidades
            foreach ($input["necesidades"] as $necesidadEntrante) {
                $necesidad = json_decode($necesidadEntrante);

                    $item = isset($necesidad->descripcion_datos) 
                    ? ($necesidad->descripcion_datos->articulo ?? $necesidad->descripcion_datos->description ?? null) 
                    : null;
    

               $this->_addNecesidad(
                   $requestNeed["id"],
                   $necesidad->necesidad ?? null,
                   $necesidad->descripcion_datos->id ?? $necesidad->descripcion,
                   $item,
                   $necesidad->unidad_medida,
                   $necesidad->valor_unitario ?? 0, 
                   $necesidad->IVA ?? 0,
                   $necesidad->cantidad_solicitada ?? 0,
                   $necesidad->valor_total ?? 0,
                   'Pendiente',
                   $input["tipo_solicitud"],
                   $necesidad->necesidad ?? null,
                   $necesidad->tipo_mantenimiento ?? "Preventivo",
                   $necesidad->descripcion_datos->codigo ?? ($necesidad->codigo ?? null),
                                       $necesidad->total_value ?? 0,

               ); 
           }

           
           $this->createHistory($observacion,$requestNeed["estado"],$requestNeed["id"], 'Se modificó el registro');

            // Efectua los cambios realizados
            DB::commit();

            //Relaciones

            $requestNeed->necesidades;
            $requestNeed->contratoDatos;
            $requestNeed->rubroDatos;
            $requestNeed->rubroDatos;
            $requestNeed->historial;

            $userAuth = Auth::user();

            // Cuando proviene una solicitud de un lider y es de aseo para salida de stock el registro no se puede editar
            $requestNeed["is_editable"] = $requestNeed["tipo_solicitud"] === 'Stock' ? ($requestNeed["dependencias_id"] != 19 && $requestNeed["dependencias_id"] != 23) && $requestNeed["users_id"] === $userAuth->id : true;


            return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un RequestNeed del almacenamiento
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
    public function destroy($id)
{
    /** @var RequestNeed $requestNeed */
    $requestNeed = $this->requestNeedRepository->find($id);

    if (empty($requestNeed)) {
        return $this->sendError(trans('not_found_element'), 200);
    }

    DB::beginTransaction();
    try {
        // 🔹 Si tiene consecutivo, no eliminar: cambiar estado a "Cancelada"
        if (!empty($requestNeed->consecutivo)) {
            $requestNeed->estado = 'Cancelada';
            $requestNeed->save();
        } else {
            // 🔹 Si no tiene consecutivo, eliminar normalmente
            $requestNeed->delete();
        }

        DB::commit();
        return $this->sendSuccess(trans('msg_success_drop'));

    } catch (\Illuminate\Database\QueryException $error) {
        DB::rollback();
        $this->generateSevenLog(
            'module_name',
            'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' - Error: '.$error->getMessage()
        );
        return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');

    } catch (\Exception $e) {
        DB::rollback();
        $this->generateSevenLog(
            'module_name',
            'Modules\Maintenance\Http\Controllers\RequestNeedController - '. Auth::user()->name.' - Error: '.$e->getMessage()
        );
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

        $providerId = null;
    
        // Buscar y extraer el valor de provider_id
        if (preg_match("/provider_id\s+LIKE\s+'%(\d+)%'/", $input["filtros"], $matches)) {
            $providerId = $matches[1];

            // Eliminar la condición de provider_id del string de filtros
            $input["filtros"] = trim(str_replace($matches[0], '', $input["filtros"]));
            // Si quedan operadores AND/OR colgando, limpiarlos
            $input["filtros"] = preg_replace('/^(AND|OR)\s*/i', '', $input["filtros"]);
            $input["filtros"] = preg_replace('/\s*(AND|OR)$/i', '', $input["filtros"]);
            $input["filtros"] = preg_replace('/\s*(AND|OR)\s*(AND|OR)\s*/i', ' $1 ', $input["filtros"]);
        }

        if(array_key_exists("filtros", $input)) {
            $userAuth = Auth::user();
            $request_needs_query = RequestNeed::with([
                    'contratoDatos',
                ])
                ->when($providerId, function ($query) use ($providerId) {
                    $query->whereHas('contratoDatos', function ($subQuery) use ($providerId) {
                        $subQuery->where('mant_providers_id', $providerId);
                    });
                })
                ->when($input["filtros"], function ($query) use ($input) {
                    $query->whereRaw($input["filtros"])->whereNull("deleted_at");
                });                    
            if($input["filtros"] != "") {
                if($userAuth->hasRole("Administrador de mantenimientos")){
                    $input["data"] = $request_needs_query->where('en_administracion', 1)->latest()->get()->toArray();
                }
                else{
                    $input["data"] = $request_needs_query->where("users_id",$userAuth->id)->latest()->get()->toArray();
                }
            }
            else{
                if($userAuth->hasRole("Administrador de mantenimientos")){
                    $input["data"] = $request_needs_query->where('en_administracion', 1)->latest()->get()->toArray();
                }
                else{
                    $input["data"] = $request_needs_query->where("users_id",$userAuth->id)->latest()->get()->toArray();
                }
            }
        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('tireBrand').'.'.$fileType;
        $fileName = 'excel.' . $fileType;

        return Excel::download(new RequestExport('maintenance::request_needs.report_excel', JwtController::generateToken($input['data']), 'g'), $fileName);
    }

    private function _validacionesNecesidad($input){
        /** Inicia Validaciones */

       
        return null;
        /** Fin de las validaciones */
    }

    /**
     * Crea necesidades
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function _addNecesidad($id, $necesidad, $descripcion, $descripcion_nombre, $unidad_medida, $valor_unitario, $IVA, $cantidad_solicitada, $valor_total,$estado,$tipo_solicitud,$tipo_necesidad, $tipo_mantenimiento = "Preventivo", $codigo, $total_value = null) {
        // 
        RequestNeedItem::create([
            'mant_sn_request_id' => $id,
            'necesidad' => $necesidad,
            'descripcion' => $descripcion ?? 0,
            'descripcion_nombre' => $descripcion_nombre ?? '',
            'unidad_medida' => $unidad_medida,
            'valor_unitario' => $valor_unitario ?? 0,
            'IVA' => $IVA ?? 0,
            'cantidad_solicitada' => $cantidad_solicitada ?? 0,
            'valor_total' => $valor_total ?? 0,
            'estado' => $estado,
            'tipo_solicitud' => $tipo_solicitud,
            'tipo_necesidad' => $tipo_necesidad,
            'tipo_mantenimiento' => $tipo_mantenimiento,
            'codigo' => $codigo ?? null,
            'total_value' => $total_value ?? 0,

        ]);
    }

    /**
     * Crea los consecutivos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function _getConsecutivo() {
        // [2023 -INP-001] producto
        // [2023 -INS-001]

        $cantidad = RequestNeed::whereNotNull("consecutivo")
        ->count();

        $numero = $cantidad + 1;
        $numero_rellenado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        
        $codigo = date("Y") . "-IN-" . $numero_rellenado;
        return $codigo;
    }

    public function sendRequest($id) {

        // $datos = RequestNeed::where("id", $id)->with(["dependencia","rubroDatos","necesidades"])->get()->toArray()[0];

        // $inputFileType = 'Xlsx';
        // $inputFileName = app_path('Modules/Maintenance/Resources/Views/request_needs/FormatosReportes/Formato_identificacion_necesidades.xlsx');

        // $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        // $spreadsheet = $reader->load($inputFileName);
        // $spreadsheet->setActiveSheetIndex(0);

        // $spreadsheet->getActiveSheet()->setCellValue('B6', "22652");
        // $spreadsheet->getActiveSheet()->setCellValue('B7', $datos["updated_at"]);
        // $spreadsheet->getActiveSheet()->setCellValue('G6', ($datos["tipo_solicitud"] == 'Activo') ? $datos["tipo_solicitud"] : 'NA');
        // $spreadsheet->getActiveSheet()->setCellValue('G7', ($datos["tipo_solicitud"] == 'Activo') ? $datos["activo_nombre"] : 'NA');
        // $spreadsheet->getActiveSheet()->setCellValue('G8', $datos["rubro_datos"]["name_heading"]);
        // if (Auth::user()->hasRole('mant Líder de proceso')) {
        //     $spreadsheet->getActiveSheet()->setCellValue('G9', $datos["dependencia"]['nombre']);
        // }else{
        //     $spreadsheet->getActiveSheet()->setCellValue('G9', $datos["dependencia"]['nombre']);
        // }
        // $spreadsheet->getActiveSheet()->setCellValue('J9', $datos["tipo_necesidad"]);

        // $user = DB::table('users')->where('id',$datos["users_id"])->first();
        // $spreadsheet->getActiveSheet()->setCellValue('D17', $user->name);

        // $cargo =  DB::table('cargos')->where('id',$user->id_cargo)->first();

        // $spreadsheet->getActiveSheet()->setCellValue('D18', $cargo->nombre);

        // if ($user->url_digital_signature) {
        //     $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        //     $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
        //     $drawing->setCoordinates('D16');
        //     $drawing->setWorksheet($spreadsheet->getActiveSheet());
        //     $drawing->setHeight(100);
        //     $drawing->setResizeProportional(true);
        //     $drawing->setOffsetX(2); // this is how
        //     $drawing->setOffsetY(2);
        // }
        // $fila = 12;
        // if(count($datos["necesidades"]) > 1) {
        //     $spreadsheet->getActiveSheet()->insertNewRowBefore($fila, (count($datos["necesidades"]) - 1));
        //     // Combinar la celda
        //     $spreadsheet->getActiveSheet()->mergeCells("C12:E12");
        // }
        // $suma_valor_total = 0;
        // $cantidad_necesidades = count($datos["necesidades"]);

        // for ($i = 0; $i < count($datos["necesidades"]) ; $i++) { 
        //     $spreadsheet->getActiveSheet()->setCellValue('A'.($fila+$i), $i+1);
        //     $spreadsheet->getActiveSheet()->setCellValue('B'.($fila+$i), $datos["necesidades"][$i]["necesidad"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('C'.($fila+$i), $datos["necesidades"][$i]["descripcion"]);
        //     // $spreadsheet->getActiveSheet()->setCellValue('F'.($fila+$i), $datos["necesidades"][$i]["consecutivo"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('G'.($fila+$i), $datos["necesidades"][$i]["unidad_medida"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('H'.($fila+$i), "$ ".$datos["necesidades"][$i]["valor_unitario"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('I'.($fila+$i), "$ ".$datos["necesidades"][$i]["IVA"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('J'.($fila+$i), $datos["necesidades"][$i]["cantidad_solicitada"]);
        //     $spreadsheet->getActiveSheet()->setCellValue('K'.($fila+$i), "$ ".$datos["necesidades"][$i]["valor_total"]);
        //     $suma_valor_total += $datos["necesidades"][$i]["valor_total"];
        // }

        // $spreadsheet->getActiveSheet()->setCellValue('K'.($fila + $cantidad_necesidades),"$ ". $suma_valor_total);
        // $spreadsheet->getActiveSheet()->setCellValue('A'.($fila + $cantidad_necesidades + 1), "Justificación de la Solicitud: ".$datos["observacion"]);
        // // $spreadsheet->getActiveSheet()->setCellValue('A'.($fila + $cantidad_necesidades + 1), "Justificación de la Solicitud: ".$datos["observacion"]);
        // // $spreadsheet->getActiveSheet()->setCellValue('J9', 'geral la tiene rica');
        
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment;filename="Identificacion de Necesidades.xlsx"');
        // header('Cache-Control: max-age=0');


        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->save('php://output');

        // Guarda el archivo en la ruta especificada:
        // $ruta = Storage::put('/public/storage/leca/excel/Identificacion_de_Necesidades.xlsx', $writer->output());


        $requestNeed = $this->requestNeedRepository->find($id);
        
        $consecutivo = "";
        if($requestNeed->estado=="En elaboración"){
            $estado = "En revisión";
            $consecutivo =  $this->_getConsecutivo();

    


            $requestNeed = $this->requestNeedRepository->update(["estado" => $estado,"consecutivo" => $consecutivo, "en_administracion" => 1,"date_supervisor_submission" => date("Y-m-d H:i:s") ], $id);
            $this->createHistory($requestNeed["observacion"],$requestNeed["estado"],$requestNeed["id"], 'Se envió a revisión' );

        }else{
            if($requestNeed->estado=="En revisión"){
                $estado = "En revisión";
           
                $requestNeed = $this->requestNeedRepository->update(["estado" => $estado], $id);
            }
        }

        $requestNeed->historial;
        return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));
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
            SendNotificationController::SendNotification('maintenance::request_need_orders.emails.notification_supplier_externo',$custom,$data,$provider_mail,' Identificación de necesidades');
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

    public function changeStateRequest(UpdateRequestNeedRequest $request) {

        $input = $request->all();

        // Obtiene las ordenes pendientes por finalizar
        $pendingOrders = $this->getPendingOrdersByRequestId($input["id"]);

        // Muestra un mensaje si hay ordenes pendientes por finalizar
        if($pendingOrders){
            $message = "La solicitud no puede ser guardada porque el proveedor y/o almacén no han finalizado las órdenes.";
            return $this->sendSuccess($message, 'info');
        }

        $request = RequestNeed::select("id")->with("necesidades")->where("id",$input["id"])->first()->toArray();
        
        $separateNeeds = $this->_getSeparateNeeds($request["necesidades"]);
        
        if(($input["tipo_solicitud"] === "Stock" || $input["tipo_solicitud"] === "Activo") && $input["estado"] === "Finalizada"){
            // Resta las cantidades al producto en el stock
            // foreach ($input["necesidades"] as $orden_salida_encode) {
            //     $orden_salida = json_decode($orden_salida_encode);
    
            //     $stock = Stock::where("id_solicitud_necesidad",$orden_salida->descripcion)->first();
                
            //     // Si es null es porque es de stock la solicitud
            //     if(is_null($stock)){
            //         $stock = Stock::where("id",$orden_salida->descripcion)->first();
            //     }

            //     $quantity = $stock["cantidad"] - $orden_salida->cantidad_solicitada;
            //     Stock::where("id",$stock["id"])->update(["cantidad" => $quantity]);
            //     StockHistory::create(["stock_id" => $stock["id"],"usuario_nombre" => $userLogged->name, "usuario_id" => $userLogged->id, "accion" => "Salida", "cantidad" => $orden_salida->cantidad_solicitada]);
            // }

            if($input["tipo_solicitud"] == "Activo"){
                $totalValueToBeDiscounted = $this->_getTotalValueToBeDiscounted($input["necesidades"]);

                $administrationCostItems = $this->_getAdministrationCostItem($input["rubro_objeto_contrato_id"],$input["rubro_id"],"Activo");
                if($this->_exceedsAvailableValue($administrationCostItems["value_avaible"],$totalValueToBeDiscounted)){
                    return $this->sendSuccess("La solicitud no puede ser guardada, ya que el valor de la solicitud <strong>($".number_format($totalValueToBeDiscounted) .")</strong> excede el valor disponible del rubro<strong>($". number_format($administrationCostItems["value_avaible"]) .")</strong> Si desea continuar, le recomendamos que intente modificando el valor de la solicitud.", 'info');
                }
                else{
                    $needWithSpareParts = [];

                    // Ciclo para obtener las necesidades de repuesto
                    foreach ($input["necesidades"] as $necesity) {
                        $necesity = json_decode($necesity,true);

                        // Si tiene actividades va a guardar el campo de actividad por el proveedor
                        if($necesity["necesidad"] === "Actividades"){
                            $final_quantity = is_null($necesity["cantidad_final"]) || $necesity["cantidad_final"] == "" ? $necesity["cantidad_solicitada"] : $necesity["cantidad_final"];
                            RequestNeedItem::where("id",$necesity["id"])->update(["cantidad_final" => $final_quantity]);
                            // $needWithSpareParts[] = $necesity;
                        }
                        if($necesity["necesidad"] === "Repuestos"){
                            $needWithSpareParts[] = $necesity;
                        }
                    }

                    // Si tiene necesidades con repuesto entonces creara la salida del almacen automaticamente
                    if($this->_haveNeedWithSpareParts($needWithSpareParts)){
                        $this->_createNewOrderOutputFromTheWarehouse($input["id"],$needWithSpareParts);
                    }



                    // Validacion para disminuir las cantidades de stock si la solicitud es combinada (contiene actividades y repuestos) 
                    // if(count($separateNeeds["spare_parts"]) > 0 && count($separateNeeds["activities"]) > 0){

                if(count($separateNeeds["spare_parts"]) > 0){

                        $winery = $input["dependencias_id"] == self::ASSISTANT_MANAGER_SANITATION_ID || $input["dependencias_id"] == self::CLEANING_MANAGEMENT_ID ? "Aseo" : "CAM";

                        $this->_decreaseStockQuantity($separateNeeds["spare_parts"],"Activo",$winery);
                        // $this->_generateExecutionToTheItem(["rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],"rubro_id" => $input["rubro_id"],"consecutivo" => $input["consecutivo"]],$totalValueToBeDiscounted,"Activo");

                        $this->_generateExecutionToTheItemConSegundoRubro([
                            "rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],
                            "rubro_id" => $input["rubro_id"],
                            "second_rubro_id" => $input["second_rubro_id"] ?? null, // 👈 clave para el 50/50
                            "consecutivo" => $input["consecutivo"]
                        ], $totalValueToBeDiscounted, "Activo");
                    }
                    elseif(count($separateNeeds["spare_parts"]) == 0 && count($separateNeeds["activities"]) > 0){
                        // $this->_decreaseStockQuantity($separateNeeds["spare_parts"],"Activo");
                        // $this->_generateExecutionToTheItem(["rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],"rubro_id" => $input["rubro_id"],"consecutivo" => $input["consecutivo"]],$totalValueToBeDiscounted,"Activo");


                         $this->_generateExecutionToTheItemConSegundoRubro([
                            "rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],
                            "rubro_id" => $input["rubro_id"],
                            "second_rubro_id" => $input["second_rubro_id"] ?? null, // 👈 clave para el 50/50
                            "consecutivo" => $input["consecutivo"]
                        ], $totalValueToBeDiscounted, "Activo");
                    }
                    elseif(count($separateNeeds["spare_parts"]) > 0 && count($separateNeeds["activities"]) == 0){
                        $winery = $input["dependencias_id"] == self::ASSISTANT_MANAGER_SANITATION_ID || $input["dependencias_id"] == self::CLEANING_MANAGEMENT_ID ? "Aseo" : "CAM";

                        $this->_decreaseStockQuantity($separateNeeds["spare_parts"],"Activo",$winery);
                    }

                }
            }

            // Crea el registro de la vista de gestion de mantenimientos
            $requestNeedOrder = RequestNeedOrders::with("ordenesItem")->where("mant_sn_request_id",$input["id"])->first()->toArray();
            $requestNeedOrder["invoice_no"] = $input["invoice_no"];
            $this->_createRegisterToAssetManagement($requestNeedOrder);
        }

        if($input["tipo_solicitud"] == "Inventario" && $input["estado"] === "Finalizada"){
            $totalValueToBeDiscounted = $this->_getTotalValueToBeDiscounted($input["necesidades"]);
            // $this->_generateExecutionToTheItem(["rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],"rubro_id" => $input["mant_administration_cost_items_id"],"consecutivo" => $input["consecutivo"]],$totalValueToBeDiscounted,"Inventario");

            $this->_generateExecutionToTheItemConSegundoRubro([
                            "rubro_objeto_contrato_id" => $input["rubro_objeto_contrato_id"],
                            "rubro_id" => $input["mant_administration_cost_items_id"],
                            "second_rubro_id" => $input["second_rubro_id"] ?? null, // 👈 clave para el 50/50
                            "consecutivo" => $input["consecutivo"]
                        ], $totalValueToBeDiscounted, "Inventario");
            // $this->_decreaseStockQuantity($separateNeeds["spare_parts"],"Inventario");
        }

        if($input["estado"] === "Finalizada"){
            RequestNeedOrders::where('mant_sn_request_id', $input['id'])
            ->update(['estado' => 'Orden Finalizada']);
        }

        $requestNeed = $this->requestNeedRepository->update($input, $input['id']);
        // Crea el seguimiento y control
        $this->createHistory($input["observacion_fin"],$requestNeed["estado"],$requestNeed["id"],'Se realiza cambio de estado');
        $requestNeed ->historial;
        $requestNeed ->necesidades;

        return $this->sendResponse($requestNeed->toArray(), trans('msg_success_update'));

    }

    /**
     * Obtiene los consecutivos de las ordenes pendientes por el id de la solicitud de necesidad
     *
     * @author Kleverman Salazar Florez - 2025
     * @version 1.0.0
     *
     */
    public function getPendingOrdersByRequestId(int $requestId){
        return RequestNeedOrders::select("consecutivo")
            ->where('mant_sn_request_id', $requestId)
            ->where(function($query){
                $query->where('tramite_almacen', 'Entrada Pendiente')
                      ->orWhere('estado_proveedor', 'Pendiente');
            })
            ->pluck("consecutivo")
            ->toArray();
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
     * Obtiene un array con las necesidades de actividades y repuestos separadas
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    public function _getSeparateNeeds(array $needs) : array{
        $activites = array_values(array_filter($needs,function($need){
            return $need["tipo_necesidad"] == "Actividades";
        }));

        $spare_parts = array_values(array_filter($needs,function($need){
            return $need["tipo_necesidad"] == "Repuestos";
        }));

        return ["activities" => $activites,"spare_parts" => $spare_parts];
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
            $product = Stock::where("codigo",$spare_part["codigo"])->where("bodega",$winery)->first();

            // Si no existe entonces lo crea
            if(is_null($product)){
                $newProduct = $this->_createProductToStock($spare_part,$winery);
                $this->_createProductToStockHistories($newProduct,true,$requestType);
            }else{
                // Obtiene el promedio de los precios del producto
                $currentUnitCost = $product["costo_unitario"];
                $newUnitCost = $spare_part["descripcion_datos"]["unit_value"] ?? $spare_part["descripcion_datos"]["valor_unitario"];
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
     * Genera un registro de stock
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     */
    private function _createProductToStock(array $spare_part, string $winery) : Stock{
        $newProductToStock = Stock::create(["codigo" => $spare_part["codigo"],"articulo" => $spare_part["descripcion_nombre"],"grupo" => "N/A","cantidad" => $spare_part["cantidad_entrada"],"unidad_medida" => $spare_part["unidad_medida"],"costo_unitario" => $spare_part["valor_unitario"],"iva" => $spare_part["IVA"],"total" => $spare_part["valor_total"],"bodega" => $winery]);

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
        sleep(.1);

        StockHistory::create(["stock_id" => $stock["id"],"usuario_id" => $userAuth->id,"usuario_nombre" => $userAuth->name,"accion" => "Salida","cantidad" => $stock["cantidad"]]);

        $stock["cantidad"] = $isNewProduct ? 0 : $stock["cantidad_actual"];
        $stock = $this->_updateProductToStock($stock);
    }

    /**
     * Valida si tiene necesidades con repuestos
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param int $valueAvailable valor disponible del rubro
     * @param int $totalValueToBeDiscounted cantidad de plata a descontar
     */
    private function _haveNeedWithSpareParts(array $needWithSpareParts) : bool{
        return count($needWithSpareParts) > 0;
    }

    /**
     * Crea el registro de la salida de la almacen
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param array $needs datos de las necesidades de repuestos
     * @param int $requestId id de la solicitud de la necesidad
     */
    public function _createNewOrderOutputFromTheWarehouse(int $requestId ,array $needs, $almacen = 'Almacén CAM') : void{
        $nextConsecutive = $this->nextConsecutiveOrder();

        // Usuario en sesion
        $userAuth = Auth::user();

        $rolAsignadoNombre = $almacen === 'Almacén de Aseo' ? 'Almacén Aseo' : 'Almacén CAM';

        $requestNeedOrder = RequestNeedOrders::create(["mant_sn_request_id" => $requestId,"estado" => "Orden Finalizada","consecutivo" => $nextConsecutive,"rol_asignado_nombre" => $rolAsignadoNombre,"tipo_solicitud" => "Activo","tramite_almacen" => "Salida Confirmada", "bodega" => $almacen,"fecha_salida_almacen" => date("Y-m-d H:i:s")]);

        HistoryOrder::create(['mant_sn_orders_id'=> $requestNeedOrder["id"],'users_id' => $userAuth->id, 'nombre_usuario' => $userAuth->name, 'estado' => 'Orden Finalizada', 'accion' => 'Se crea registro' ]);

        $requestOrderItem = ["mant_sn_orders_id" => $requestNeedOrder["id"], "mant_sn_request_needs_id" => $requestNeedOrder["mant_sn_request_id"], "orders" => $needs];

        // Inicializa la informacion que necesitara los items de la orden
        $this->_assignOrderItem($requestOrderItem);
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
    private function _generateExecutionToTheItem(array $requestNeed,int $amountToBeDeducted,string $needType) : void{
        $userAuth = Auth::user();

        $administrationCostItems = $this->_getAdministrationCostItem($requestNeed["rubro_objeto_contrato_id"],$requestNeed["rubro_id"],$needType);

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
            'observation' => "Solicitud de identificación de necesidad ". $requestNeed["consecutivo"],
            'name_user' => $userAuth->name,
            'users_id' => $userAuth->id,
        ]);
    }


    private function _generateExecutionToTheItemConSegundoRubro(array $requestNeed, int $amountToBeDeducted, string $needType) : void
    {
        // Si NO hay segundo rubro, ejecuta normal
        if (empty($requestNeed['second_rubro_id'])) {
            $this->_generateExecutionToTheItem($requestNeed, $amountToBeDeducted, $needType);
            return;
        }

        // Divide 50/50. Maneja impares para no perder 1 peso.
        $half = intdiv($amountToBeDeducted, 2);
        $rest = $amountToBeDeducted - $half; // asegura suma exacta

        // Ejecución para rubro principal
        $this->_generateExecutionToTheItem([
            "rubro_objeto_contrato_id" => $requestNeed["rubro_objeto_contrato_id"],
            "rubro_id" => $requestNeed["rubro_id"],
            "consecutivo" => $requestNeed["consecutivo"],
        ], $half, $needType);

        // Ejecución para segundo rubro
        $this->_generateExecutionToTheItem([
            "rubro_objeto_contrato_id" => $requestNeed["rubro_objeto_contrato_id"],
            "rubro_id" => $requestNeed["second_rubro_id"],
            "consecutivo" => $requestNeed["consecutivo"],
        ], $rest, $needType);
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
    private function _getAdministrationCostItem(int $contractId, int $itemId,string $needType) : object{
        // Se obtiene las asignaciones con relación al contrato
        $budgetAssignationIds = BudgetAssignation::where('mant_provider_contract_id', $contractId)->pluck('id')->toArray();

        if($needType === "Activo"){
            $heading = DB::table('mant_sn_actives_heading')
            ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
            ->where("id",$itemId)
            ->whereNull("deleted_at")
            ->first();

            $administrationCostItem = AdministrationCostItem::whereIn('mant_budget_assignation_id', $budgetAssignationIds)
            ->where('mant_heading_id', $heading->rubro_id)
            ->where('code_cost', $heading->code_heading)
            ->where('cost_center', $heading->centro_costo_codigo)
            ->first();
        }
        else{
            $administrationCostItem = AdministrationCostItem::where("id",$itemId)->first();
        }
        
        // $heading = DB::table('mant_sn_actives_heading')
        // ->select(['id','rubro_id','rubro_codigo as code_heading','centro_costo_codigo'])
        // ->where("id",$itemId)
        // ->whereNull("deleted_at")
        // ->first();
        
        return $administrationCostItem;
    }

    /**
     * Obtiene el valor a descontar del rubro
     *
     * @author Kleverman Salazar Florez - 2024
     * @version 1.0.0
     *
     * @param array $necesities registro de las necesidades
     */
    private function _getTotalValueToBeDiscounted(array $necesities) : int{
        $totalValueToBeDiscounted = 0;

        // Recorre las necesidades para aumentar el valor total a descontar del rubro
        foreach ($necesities as $key => $necesity) {
            $necesity = json_decode($necesity,true);
            if($necesity["tipo_necesidad"] == "Actividades" && ($necesity["cantidad_final"] > 0 || is_null($necesity["cantidad_final"]) || $necesity["cantidad_final"] == "")){
                $quantity = empty($necesity["cantidad_final"]) ? $necesity["cantidad_solicitada"] : ($necesity["cantidad_final"] != "" ? $necesity["cantidad_final"] : $necesity["cantidad_solicitada"]);
                $totalValueToBeDiscounted += ($necesity['total_value']) * $quantity;
            }
            if($necesity["tipo_necesidad"] == "Repuestos"){
                $quantity = empty($necesity["cantidad_final"]) ? $necesity["cantidad_solicitada"] : ($necesity["cantidad_final"] != "" ? $necesity["cantidad_final"] : $necesity["cantidad_solicitada"]);
                $totalValueToBeDiscounted += ($necesity['total_value']) * $quantity;
            }
        }

        return $totalValueToBeDiscounted;
    }

    private function _createRegisterToAssetManagement($serviceOrder): void{
        $requestNeed = RequestNeed::with("necesidades")->where("id",$serviceOrder["mant_sn_request_id"])->first()->toArray();

        $requestNeedOrder = RequestNeedOrders::where("mant_sn_request_id",$requestNeed["id"])->first()->toArray();

        $requestNeedItems = RequestNeedItem::select(["id","necesidad","valor_total","descripcion_nombre","cantidad_solicitada","mant_sn_request_id","unidad_medida"])->where("mant_sn_request_id",$requestNeed["id"])->get()->map(function($requestNeedItem){
            $requestNeedItem["tipo_mantenimiento"] =  RequestNeedOrdersItem::select(["tipo_mantenimiento","id"])->where("mant_sn_request_needs_id",$requestNeedItem["mant_sn_request_id"])->first()->tipo_mantenimiento;
            return $requestNeedItem;
        });
        
        // Bucle para crear los registros en gestion de mantenimientos
        foreach ($requestNeedItems as $requestNeedItem) {
            AssetManagement::create([
                "tipo_mantenimiento" => $requestNeedItem["tipo_mantenimiento"] ?? "No aplica", 
                'nombre_activo' => $requestNeed["activo_nombre"] ?? "No aplica",
                'kilometraje_actual' => $requestNeed["kilometraje_horometro"] ?? "No aplica",
                'kilometraje_recibido_proveedor' => $requestNeedOrder["mileage_or_hourmeter_received"] ?? "No aplica",
                'nombre_proveedor' => $requestNeedOrder["proveedor_nombre"] ?? "No aplica",
                'no_salida_almacen' => $requestNeed["numero_salida_almacen"] ?? "No aplica",
                'no_factura' => $serviceOrder["invoice_no"] ?? "No aplica",
                'no_solicitud' => $requestNeed["consecutivo"] ?? "No aplica",
                'no_orden' => $requestNeedOrder["consecutivo"] ?? "No aplica",
                'actividad' => $requestNeedItem["necesidad"] == "Actividades" ? json_encode($requestNeedItem) : null,
                'repuesto' => $requestNeedItem["necesidad"] == "Repuestos" || $requestNeed["tipo_solicitud"] == "Stock" ? json_encode($requestNeedItem) : null,
                'unidad_medida' => $requestNeedItem["unidad_medida"],
                'request_id' => $requestNeed["id"],
                'order_id' => $requestNeedOrder["id"],
            ]);
        }
    }

    public function createHistory($observacion,$estado,$mant_sn_request_id,$accion) {


        RequestNeedHistory::create([
            'users_nombre' =>  Auth::user()->name,
            'users_id' => Auth::user()->id,
            'observacion' => $observacion,
            'estado' => $estado,
            'mant_sn_request_id' => $mant_sn_request_id,
            'accion' => $accion
        ]);
    

    }
    /**
     * Exporta las necesidades de una solicitud
     *
     * @author Seven Soluciones Informáticas. - Diciembre. 25 - 2023
     * @version 1.0.0
     *
     */

     public function getFormatoIdentificacionNecesidades(Request $request)
    {
        $isOutside = session('outside');

        // Si viene de fuera, obtenemos desde la orden
        if ($isOutside && $request->input("datos.order_id")) {
            $orden = RequestNeedOrders::with("ordenesItem", "solicitudPrincipal", "solicitudPrincipal.providers")
                ->findOrFail($request->input("datos.order_id"));

            $datos = $orden->solicitudPrincipal->toArray();
            $needs = $orden->ordenesItem->toArray();
        } else {
            $datos = !empty($request->input("datos.mant_sn_request_id"))
                ? RequestNeed::with("necesidades")->find($request->input("datos.mant_sn_request_id"))->toArray()
                : $request->input("datos");

            $needs = $datos["necesidades"];
        }

        $created_at = Carbon::parse($datos["created_at"]);

        $contract = ProviderContract::select(["id", "mant_providers_id", "users_id", "type_contract", "contract_number"])
            ->with(["users", "providers"])
            ->find($datos["rubro_objeto_contrato_id"])
            ->toArray();

        $providerInformation = implode(" - ", [
            $contract["providers"]["document_type"] ?? "N/A",
            $contract["providers"]["identification"] ?? "N/A",
            $contract["providers"]["name"] ?? "N/A",
            $contract["providers"]["mail"] ?? "N/A",
        ]);

    

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(base_path('Modules/Maintenance/Resources/views/request_needs/FormatosReportes/VIG-GR-R-026.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        // --- Datos generales ---
        $sheet->setCellValue('C7', $datos["consecutivo"] ?? "Aún no tiene consecutivo");
        $sheet->setCellValue('C8', $providerInformation);
        $sheet->setCellValue('C9', $datos["tipo_solicitud"] === 'Activo' ? $datos["activo_nombre"] : 'NA');

        if ($isOutside) {
            $dependencia = $datos["dependencia"]["nombre"] ?? "Sin nombre";
        } elseif (Auth::user()->hasRole('mant Líder de proceso')) {
            $dependencia = is_array($datos["dependencia"]) ? ($datos["dependencia"]["nombre"] ?? "Sin nombre") : $datos["dependencia"];
        } else {
            $dependencia = $datos["dependencia"]["nombre"] ?? "Sin nombre";
        }

        $sheet->setCellValue('C10', (string) $dependencia);
        $sheet->setCellValue('M7', "{$contract["type_contract"]}, {$contract["contract_number"]}");
        $sheet->mergeCells("M9:O9");
        $sheet->setCellValue('M9', '$' . number_format($contract["total_value_contract"]));
        $sheet->setCellValue('M10', $contract["users"]["name"]);

        $sheet->setCellValue('Q9', $created_at->format("y"));
        $sheet->setCellValue('R9', $created_at->format("m"));
        $sheet->setCellValue('S9', $created_at->format("d"));

        if (!empty($datos["approval_date"])) {
            $approval_date = Carbon::parse($datos["approval_date"]);
            $sheet->setCellValue('Q10', $approval_date->format("y"));
            $sheet->setCellValue('R10', $approval_date->format("m"));
            $sheet->setCellValue('S10', $approval_date->format("d"));
        }

        $sheet->setCellValue(
            in_array($datos["dependencias_id"], [self::ASSISTANT_MANAGER_SANITATION_ID, self::CLEANING_MANAGEMENT_ID]) ? "O14" : "O15",
            "X"
        );

        // --- Listado de necesidades ---
        $cell = 18;
        $countNeeds = count($needs);

        if ($countNeeds > 1) {
            $sheet->insertNewRowBefore($cell, $countNeeds - 1);
        }

        $products_quantity = ["Actividades" => 0, "Repuestos" => 0];
        $value_total_needs = 0;

        foreach ($needs as $need) {
            $products_quantity[$need["necesidad"]]++;

            $sheet->setCellValue("A$cell", $need["descripcion_nombre"]);
            $sheet->mergeCells("A$cell:F$cell");
            $sheet->getStyle("C$cell")->getAlignment()->setHorizontal('center');

            $sheet->setCellValue("G$cell", $need["necesidad"]);
            $sheet->setCellValue("H$cell", $need["cantidad_solicitada"] ?? $need["cantidad"]);
            $sheet->setCellValue("I$cell", $need["unidad_medida"] ?? $need["unidad"]);
            $sheet->setCellValue("J$cell", $need["valor_unitario"]);
            $sheet->setCellValue("K$cell", $need["IVA"] ?? 0);
            $sheet->setCellValue("L$cell", $need["valor_total"] ?? 0);
            foreach (["J", "K", "L"] as $col) {
                $sheet->getStyle("$col$cell")->getNumberFormat()->setFormatCode('"$"#,##0.00');
            }

            $value_total_needs += (($need["valor_total"] ?? 0)) * ($need["cantidad_solicitada"] ?? $need["cantidad"]);
            $cell++;
        }

        $sheet->setCellValue('M18', $datos["supervisor_observation"] ?? "No aplica");
        $sheet->mergeCells("M18:S" . (17 + $countNeeds));

        $sheet->setCellValue('S13', $products_quantity["Repuestos"] > 0 ? "X" : "");
        $sheet->setCellValue('S15', $products_quantity["Actividades"] > 0 ? "X" : "");

        $sheet->setCellValue("L" . ($cell + 1), $datos["total_solicitud"] ?? 0);
        $sheet->setCellValue("M" . ($cell + 1), $datos["observacion"] ?? "No aplica");

        $this->insertUserSignature($sheet, $datos["users_id"], 'B', $cell + 3);

        if (!empty($datos["approving_user_id"])) {
            $this->insertUserSignature($sheet, $datos["approving_user_id"], 'L', $cell + 3);
        }

        $fileName = "Identificacion de Necesidades " . ($datos["consecutivo"] ?? $datos["created_at"]);

        if ($request->filled("datos.mant_sn_request_id")) {
            $styleArray = [
                'borders' => [
                    'top' => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left' => ['borderStyle' => Border::BORDER_THIN],
                    'right' => ['borderStyle' => Border::BORDER_THIN],
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
        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header("Content-Disposition: attachment;filename=\"$fileName.xlsx\"");
            header('Cache-Control: max-age=0');

            \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        }
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
     
 
     public function getFormatoIdentificacionNecesidadesMail(int $id)
     {
         $order = RequestNeedOrders::select(['mant_sn_request_id', 'consecutivo'])->find($id);
         $datos = RequestNeed::with("necesidades")->find($order['mant_sn_request_id']);
     
         $created_at = Carbon::parse($datos["created_at"]);
     
         $contract = ProviderContract::select([
                 "id", "mant_providers_id", "users_id", "type_contract", "contract_number"
             ])
             ->with(["users", "providers"])
             ->find($datos["rubro_objeto_contrato_id"])
             ->toArray();
     
         $providerInformation = implode(" - ", [
             $contract["providers"]["document_type"],
             $contract["providers"]["identification"],
             $contract["providers"]["name"],
             $contract["providers"]["mail"]
         ]);
     

         $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
         $spreadsheet = $reader->load(base_path('Modules/Maintenance/Resources/views/request_needs/FormatosReportes/VIG-GR-R-026.xlsx'));
         $spreadsheet->setActiveSheetIndex(0);
         $sheet = $spreadsheet->getActiveSheet();
     
         $sheet->setCellValue('C7', $datos["consecutivo"] ?? "Aún no tiene consecutivo");
         $sheet->setCellValue('C8', $providerInformation);
         $sheet->setCellValue('C9', $datos["tipo_solicitud"] === 'Activo' ? $datos["activo_nombre"] : 'NA');
     
        if (session('outside')) {
            $dependencia = $datos["dependencia"]["nombre"];
        } elseif (Auth::user()->hasRole('mant Líder de proceso')) {
            $dependencia = is_array($datos["dependencia"]) ? ($datos["dependencia"]["nombre"] ?? "Sin nombre") : $datos["dependencia"];
        } else {
            $dependencia = $datos["dependencia"]["nombre"] ?? "Sin nombre";
        }

        // Garantiza que sea string
        $sheet->setCellValue('C10', (string) $dependencia);

     
         $sheet->setCellValue('M7', "{$contract["type_contract"]}, {$contract["contract_number"]}");
         $sheet->mergeCells("M9:O9");
         $sheet->setCellValue('M9', '$' . number_format($contract["total_value_contract"]));
         $sheet->setCellValue('M10', $contract["users"]["name"]);
     
         $sheet->setCellValue('Q9', $created_at->format("y"));
         $sheet->setCellValue('R9', $created_at->format("m"));
         $sheet->setCellValue('S9', $created_at->format("d"));
     
         if (!empty($datos["approval_date"])) {
             $approval_date = Carbon::parse($datos["approval_date"]);
             $sheet->setCellValue('Q10', $approval_date->format("y"));
             $sheet->setCellValue('R10', $approval_date->format("m"));
             $sheet->setCellValue('S10', $approval_date->format("d"));
         }
     
         $sheet->setCellValue(
             in_array($datos["dependencias_id"], [self::ASSISTANT_MANAGER_SANITATION_ID, self::CLEANING_MANAGEMENT_ID]) ? "O14" : "O15",
             "X"
         );
     
         // --- Necesidades ---
         $cell = 18;
         $needs = $datos["necesidades"];
         $countNeeds = count($needs);
     
         if ($countNeeds > 1) {
             $sheet->insertNewRowBefore($cell + 1, $countNeeds - 1);
         }
     
         $products_quantity = ["Actividades" => 0, "Repuestos" => 0];
         $value_total_needs = 0;
     
         foreach ($needs as $need) {
             $products_quantity[$need["necesidad"]]++;
             $sheet->setCellValue("A$cell", $need["descripcion_nombre"]);
             $sheet->mergeCells("A$cell:F$cell");
             $sheet->getStyle("C$cell")->getAlignment()->setHorizontal('center');
     
             $sheet->setCellValue("G$cell", $need["necesidad"]);
             $sheet->setCellValue("H$cell", $need["cantidad_solicitada"]);
             $sheet->setCellValue("I$cell", $need["unidad_medida"]);
             $sheet->setCellValue("J$cell", $need["valor_unitario"]);
             $sheet->setCellValue("K$cell", $need["IVA"]);
             $sheet->setCellValue("L$cell", $need["valor_total"] ?? 0);

             foreach (["J", "K", "L"] as $col) {
                 $sheet->getStyle("$col$cell")->getNumberFormat()->setFormatCode('"$"#,##0.00');
             }
     
             $value_total_needs += ($need["total_value"]) * $need["cantidad_solicitada"];
             $cell++;
         }
     
         $sheet->setCellValue('M18', $datos["approval_justification"] ?? "No aplica");
         $sheet->mergeCells("M18:S" . (17 + $countNeeds));
     
         $sheet->setCellValue('S13', $products_quantity["Repuestos"] > 0 ? "X" : "");
         $sheet->setCellValue('S15', $products_quantity["Actividades"] > 0 ? "X" : "");

         $sheet->setCellValue("L" . ($cell + 1), $datos["total_solicitud"] ?? 0);
         $sheet->setCellValue("M" . ($cell + 1), $datos["observacion"] ?? "No aplica");
     
         // --- Firmas ---
         $this->insertUserSignature($sheet, $datos["users_id"], 'B', $cell + 3);
         if (!empty($datos["approving_user_id"])) {
             $this->insertUserSignature($sheet, $datos["approving_user_id"], 'L', $cell + 3);
         }
     
        //  $sheet->removeRow(18);
     
         $styleArray = [
             'borders' => [
                 'top' => ['borderStyle' => Border::BORDER_THIN],
                 'bottom' => ['borderStyle' => Border::BORDER_THIN],
                 'left' => ['borderStyle' => Border::BORDER_THIN],
                 'right' => ['borderStyle' => Border::BORDER_THIN],
             ],
         ];
     
         $sheet->getStyle('I7:S7')->applyFromArray($styleArray);
         $sheet->getStyle('A7:A20')->applyFromArray($styleArray);
     
         $pdf = UtilController::convertXlsxToPdf($spreadsheet);
         $tmp = UtilController::generateTemporaryFile($pdf);
         $fileName = "Identificacion de Necesidades " . $order["consecutivo"] . ".pdf";
     
         return Response::make(Storage::get($tmp), 200, [
             'Content-Type' => 'application/pdf',
             'Content-Disposition' => "attachment; filename=\"$fileName\"",
             'Content-Length' => Storage::size($tmp)
         ]);
     }


    
    public function getRubrosByContrato(int $contratoId)
{
    if (Auth::user()->hasRole("Administrador de mantenimientos")) {
        $headings = AdministrationCostItem::join("mant_budget_assignation as a", "mant_budget_assignation_id", "=", "a.id")
            ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
            ->join('mant_heading as k', 'mant_heading_id', '=', 'k.id')
            ->select(
                'k.name_heading',
                'k.code_heading',
                DB::raw("REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '') as cost_center_name"),
                DB::raw("CONCAT(k.id, '-', REPLACE(REPLACE(cost_center_name, '\r', ''), '\n', '')) AS id_combinado")
            )
            ->distinct()
            ->where("s.id", $contratoId)
            ->get();
    } else {
        // Contratos por dependencia (aunque no debería llegar por aquí si usas contrato directamente)
        $headings = DB::table('mant_administration_cost_items as c')
            ->join('mant_budget_assignation as a', 'c.mant_budget_assignation_id', '=', 'a.id')
            ->join('mant_provider_contract as s', 'a.mant_provider_contract_id', '=', 's.id')
            ->join('mant_heading as k', 'c.mant_heading_id', '=', 'k.id')
            ->where('s.id', $contratoId)
            ->where('c.cost_center', 3)
            ->select(
                'k.name_heading',
                'k.code_heading',
                'c.cost_center_name',
                DB::raw("CONCAT(k.id, '-', REPLACE(REPLACE(c.cost_center_name, '\r', ''), '\n', '')) AS id_combinado")
            )
            ->get();
    }
    // dd($headings->toArray());

    return $this->sendResponse($headings->toArray(), trans('data_obtained_successfully'));
}


    
}
