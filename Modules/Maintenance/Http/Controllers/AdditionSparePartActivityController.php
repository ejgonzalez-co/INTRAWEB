<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateAdditionSparePartActivityRequest;
use Modules\Maintenance\Http\Requests\UpdateAdditionSparePartActivityRequest;
use Modules\Maintenance\Repositories\AdditionSparePartActivityRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\SendNotificationController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Matrix\Operators\Addition;
use Modules\Maintenance\Models\AdditionNeed;
use Modules\Maintenance\Models\AdditionSparePartActivity;
use Modules\Maintenance\Models\ImportActivitiesProviderContract;
use Modules\Maintenance\Models\ImportSparePartsProviderContract;
use Modules\Maintenance\Models\Providers;
use Modules\Maintenance\Models\RequestNeed;
use Modules\Maintenance\Models\RequestNeedHistory;
use Modules\Maintenance\Models\RequestNeedItem;
use Modules\Maintenance\Models\RequestNeedOrders;
use Modules\Maintenance\Models\RequestNeedOrdersItem;
use Modules\Maintenance\Repositories\AdditionNeedRepository;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar - 2025
 * @version 1.0.0
 */
class AdditionSparePartActivityController extends AppBaseController {

    /** @var  AdditionSparePartActivityRepository */
    private $additionSparePartActivityRepository;

    /** @var  AdditionNeedRepository */
    private $additionNeedRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     */
    public function __construct(AdditionSparePartActivityRepository $additionSparePartActivityRepo,AdditionNeedRepository $additionNeedRepo) {
        $this->additionSparePartActivityRepository = $additionSparePartActivityRepo;
        $this->additionNeedRepository = $additionNeedRepo;
    }

    /**
     * Muestra la vista para el CRUD de AdditionSparePartActivity.
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        if($request->has("o")){
            $endpoint = "get-addition-spare-part-activities?o=".$request->o;
            $title = "Gestionar adiciones de la orden: ".$this->getConsecutiveOrder($request->o);
            return view('maintenance::addition_spare_part_activities.index',compact("endpoint","title"))->with("order",$request->o);
        };

        if($request->has("s")){
            if(Auth::check() && Auth::user()->hasRole("Administrador de mantenimientos")) {
                // Obtiene el consecutivo y id del contrato de la solicitud para la utilizacion en el formulario
                $request_need = RequestNeed::select(["id","consecutivo","mant_administration_cost_items_id","rubro_objeto_contrato_id"])->find(base64_decode($request->s));

                $endpoint = "get-addition-spare-part-activities?s=".$request->s;
                $title = "Gestionar adciones de la solicitud de identificación: ".$request_need->consecutivo;
                return view('maintenance::addition_spare_part_activities.index',compact("endpoint","title"))->with("mant_administration_cost_items_id",$request_need["mant_administration_cost_items_id"])->with("rubro_objeto_contrato_id",$request_need["rubro_objeto_contrato_id"])->with("request_id",base64_encode($request_need["id"]));
            }
        };

        return view("auth.forbidden");
    }

    private function getConsecutiveOrder(string $order_id) : string {
        // Obtiene el consecutivo de la orden
        $consecutive = RequestNeedOrders::select("consecutivo")->find(base64_decode($order_id))->consecutivo;

        return $consecutive;
    }
    
    private function getConsecutiveRequest(string $request_id) : string {
        // Obtiene el consecutivo de la orden
        $consecutive = RequestNeed::select("consecutivo")->find(base64_decode($request_id))->consecutivo;

        return $consecutive;
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        if(session("id")){
            $order_id = base64_decode(explode("?",$request["o"])[0]);

            $addition_spare_part_activities = AdditionSparePartActivity::where("order_id",$order_id)
                                                ->with(["Needs","providerCreator","adminCreator"])
                                                ->latest()
                                                ->get();

            return $this->sendResponse($addition_spare_part_activities->toArray(), trans('data_obtained_successfully'));
        }

        if(Auth::check() && Auth::user()->hasRole("Administrador de mantenimientos")) {
            $request_id = base64_decode(explode("?",$request["s"])[0]);

            $orders_id = RequestNeedOrders::select("id")
                                                ->where("mant_sn_request_id",$request_id)
                                                ->pluck("id")
                                                ->toArray();

            $addition_spare_part_activities = AdditionSparePartActivity::whereIn("order_id",$orders_id)
                                                ->with(["Needs","providerCreator","adminCreator","RequestNeed"])
                                                ->latest()
                                                ->get();

            return $this->sendResponse($addition_spare_part_activities->toArray(), trans('data_obtained_successfully'));
        }

        return $this->sendResponse([], trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param CreateAdditionSparePartActivityRequest $request
     *
     * @return Response
     */
    public function store(CreateAdditionSparePartActivityRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Desencripta el id de la orden
            $input["order_id"] = (int) base64_decode($input["order"]);

            // Obtiene el id de la orden para obtener el id de la solicitud
            $input["request_id"] = RequestNeedOrders::select("mant_sn_request_id")->find($input["order_id"])->mant_sn_request_id;

            $input["provider_creator_id"] = session("id");
            $input["status"] = "En trámite";

            // Inserta el registro en la base de datos
            $additionSparePartActivity = $this->additionSparePartActivityRepository->create($input);
            
            // Efectua los cambios realizados
            DB::commit();

            // Crea las necesidades asociadas a la adición
            $this->_createAdditionNeeds($additionSparePartActivity->id,$input["needs"]);

            // Obtiene el correo del administrador de mantenimientos
            $provider = Providers::select(["name","mail"])->find($additionSparePartActivity->provider_creator_id);

            // Obtiene el consecutivo de la solicitud
            $request_consecutive = $this->getConsecutiveRequest(base64_encode($additionSparePartActivity->request_id));

            // Crea el log de la solicitud de necesidad
            RequestNeedHistory::create([
                "users_nombre" => $provider["name"],
                "observacion" => $input["provider_observation"] ?? "",
                "estado" => $additionSparePartActivity->status,
                "users_id" => session("id"),
                "mant_sn_request_id" => $additionSparePartActivity->request_id,
                "accion" => "Creación de solicitud de adición"
            ]);

            // Obtiene el correo del administrador de mantenimientos
            // $admin_mail = User::select(["email"])->role("Administrador de mantenimientos")->first()->email;
            $admin_mails = User::role("Administrador de mantenimientos")->pluck('email')->toArray();

            $mail_data = [
                "admin_name" => implode(", ", $admin_mails),
                "provider_name" => $provider["name"],
                "consecutive" => $request_consecutive,
                "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?s=" . base64_encode($additionSparePartActivity->request_id)
            ];

            $this->_sendEmail($admin_mails,"maintenance::addition_spare_part_activities.mails.adition_created_by_provider",$mail_data,"Nueva solicitud de adición para su aprobación  - $request_consecutive");

            // Carga las relaciones
            $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);

            return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            
            // Devuelve los cambios realizados
            DB::rollback();

            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();


            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param CreateAdditionSparePartActivityRequest $request
     *
     * @return Response
     */
    public function createProcess(CreateAdditionSparePartActivityRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Desencripta el id de la orden
            $input["order_id"] = (int) base64_decode($input["order"]);

            // Obtiene el id de la orden para obtener el id de la solicitud
            $input["request_id"] = RequestNeedOrders::select("mant_sn_request_id")->find($input["order_id"])->mant_sn_request_id;

            $input["provider_creator_id"] = session("id");
            $input["status"] = "En trámite";

            // Inserta el registro en la base de datos
            $additionSparePartActivity = $this->additionSparePartActivityRepository->create($input);
            
            // Efectua los cambios realizados
            DB::commit();

            // Crea las necesidades asociadas a la adición
            $this->_createAdditionNeeds($additionSparePartActivity->id,$input["needs"]);

            // Obtiene el correo del administrador de mantenimientos
            $provider = Providers::select(["name","mail"])->find($additionSparePartActivity->provider_creator_id);

            // Obtiene el consecutivo de la solicitud
            $request_consecutive = $this->getConsecutiveRequest(base64_encode($additionSparePartActivity->request_id));

            // Obtiene el correo del administrador de mantenimientos
            $admin_mails = User::role("Administrador de mantenimientos")->pluck('email')->toArray();

            
            if($input["type_request"] == "Adición"){
                $mail_data = [
                    "admin_name" => implode(", ", $admin_mails),
                    "provider_name" => $provider["name"],
                    "consecutive" => $request_consecutive,
                    "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?o=" . base64_encode($additionSparePartActivity->order_id)
                ];
    
                $this->_sendEmail($provider->mail,"maintenance::addition_spare_part_activities.mails.adition_created_by_provider",$mail_data,"Nueva solicitud de adición para su aprobación  - $request_consecutive");
            }
            else{
                $mail_data = [
                    "admin_name" => implode(", ", $admin_mails),
                    "provider_name" => $provider["name"],
                    "consecutive" => $request_consecutive,
                    "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?o=" . base64_encode($additionSparePartActivity->order_id)
                ];
    
                $this->_sendEmail($provider->mail,"maintenance::addition_spare_part_activities.mails.adition_cost_request_created_by_admin",$mail_data,"Asignación de costos requerida  - $request_consecutive");
            }

            // Carga las relaciones
            $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);

            return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // TODO: Eliminar Authuser porqueel proveedor no necesariamente es un usuario del sistema
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Permite aprobar,devolver o rechazar una solicitud de adición por el administrador de mantenimientos
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
 
    public function processAddition(Request $request){
        $input = $request->all();

        // La lógica de CREACIÓN de la adición principal queda igual...
        if(empty($input["id"])){


            // Valida que venga el tipo de solicitud por si proviene de un admin
            if(!empty($input["type_request"])){


                if($input["type_request"] == "Adición"){
                    // Valida si el valor de la adición es menor que el valor disponible de la solicitud
                    if($input["total_solicitud"] > $input["valor_disponible"]){
                        return $this->sendSuccess("El valor total de la adición($" . number_format($input["total_solicitud"]) . ") no puede ser mayor al valor disponible de la solicitud($" . number_format($input["valor_disponible"]) .")", 'info');
                    }
                    
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try{
                        $input["status"] = "Aprobada";
                        $input["admin_creator_id"] = Auth::id();
                        $input["request_id"] = base64_decode($input["request_id"]);

                        // Consulta el id de la orden por la solicitud
                        $input["order_id"] = RequestNeedOrders::select("id")->where("mant_sn_request_id",$input["request_id"])->first()->id;
                        $additionSparePartActivity = $this->additionSparePartActivityRepository->create($input);
        
                        // Efectua los cambios realizados
                        DB::commit();
                        // dd($input["needs"]);
        
                        // Crea las necesidades asociadas a la adición
                        $this->_createAdditionNeeds($additionSparePartActivity->id,$input["needs"]);
        
                        $this->_createRequestNeedByAdmin($input["needs"],$additionSparePartActivity);

                        // Obtiene el usuario en sesion
                        $userAuth = Auth::user();

                        // Crea el log de la solicitud de necesidad
                        RequestNeedHistory::create([
                            "users_nombre" => $userAuth->name,
                            "observacion" => $input["admin_observation"] ?? "",
                            "estado" => $additionSparePartActivity->status,
                            "users_id" => $userAuth->id,
                            "mant_sn_request_id" => $additionSparePartActivity->request_id,
                            "accion" => "Creación de solicitud de adición"
                        ]);                 
        
                        // Carga las relaciones
                        $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);

                        return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_save'));
                    }
                    catch (\Exception $e) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$e->getMessage());
                        // Retorna error de tipo logico
                        return $this->sendSuccess(config('constants.support_message'), 'info');
                    }
                }
                if($input["type_request"] == "Solicitud validación costo"){
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try{
                        // Cambia el valor por defecto si viene NaN
                        $input["total_solicitud"] = 0;                        
                        $input["status"] = "Solicitud en asignación de costo";
                        $input["admin_creator_id"] = Auth::id();
                        $input["request_id"] = base64_decode($input["request_id"]);

                        // Consulta el id de la orden por la solicitud
                        $input["order_id"] = RequestNeedOrders::select("id")->where("mant_sn_request_id",$input["request_id"])->first()->id;
                        $additionSparePartActivity = $this->additionSparePartActivityRepository->create($input);
        
                        // Efectua los cambios realizados
                        DB::commit();
        
                        // Crea las necesidades asociadas a la adición
                        $this->_createAdditionNeeds($additionSparePartActivity->id,$input["needs"]);

                        // Obtiene el usuario en sesion
                        $userAuth = Auth::user();

                        // Crea el log de la solicitud de necesidad
                        RequestNeedHistory::create([
                            "users_nombre" => $userAuth->name,
                            "observacion" => $input["admin_observation"] ?? "",
                            "estado" => $additionSparePartActivity->status,
                            "users_id" => $userAuth->id,
                            "mant_sn_request_id" => $additionSparePartActivity->request_id,
                            "accion" => "Creación de solicitud de adición validación costo"
                        ]);                 
                        
                        // Carga las relaciones
                        $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);
        
                        return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_save'));
                    }
                    catch (\Exception $e) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$e->getMessage());
                        // Retorna error de tipo logico
                        return $this->sendSuccess(config('constants.support_message'), 'info');
                    }
                }
            }    
        }

        // --- INICIA LÓGICA DE ACTUALIZACIÓN ---
        DB::beginTransaction();
        try {
            /** @var AdditionSparePartActivity $additionSparePartActivity */
            $additionSparePartActivity = $this->additionSparePartActivityRepository->find($input["id"]);

            if (empty($additionSparePartActivity)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            // 1. Actualiza el registro principal
            $additionSparePartActivity = $this->additionSparePartActivityRepository->update($input, $input["id"]);

            $approvedNeedsIds = [];

            // 2. Procesa todas las 'needs' (actualiza existentes y crea nuevas)
            foreach ($input["needs"] as $needData) {
                if (is_string($needData)) {
                    $needData = json_decode($needData, true);
                }

                if (!empty($needData["id"])) { // Actualizar una 'need' existente
                    $need = AdditionNeed::find($needData["id"]);
                    if ($need) {
                        $need->update(["is_approved" => $needData["is_approved"] ?? false]);
                    }
                } else { // Crear una 'need' nueva
                    $need = AdditionNeed::create([
                        "addition_id"      => $additionSparePartActivity->id, // Asignación correcta
                        "need"             => $needData["need"] ?? null,
                        "description"      => $needData["description"] ?? null,
                        "valor_total"      => $needData["valor_total"] ?? null,
                        "unit_measurement" => $needData["unit_measurement"] ?? null,
                        "unit_value"       => $needData["unit_value"] ?? null,
                        "iva"              => $needData["iva"] ?? null,
                        "amount_requested" => $needData["amount_requested"] ?? null,
                        "maintenance_type" => $needData["maintenance_type"] ?? null,
                        "is_approved"      => $needData["is_approved"] ?? false,
                    ]);
                }

                // Si esta 'need' fue aprobada, guardamos su ID
                if ($need && $need->is_approved) {
                    $approvedNeedsIds[] = $need->id;
                }
            }


            // 3. Si la adición fue APROBADA y hay 'needs' aprobadas, se crean los registros finales
            // 3. Si la adición fue APROBADA, valida que se hayan seleccionado 'needs'
            if ($input["status"] == "Aprobada") {

                // Verifica si la lista de 'needs' aprobadas NO está vacía
                if (empty($approvedNeedsIds)) {

                    // ✅ NUEVA VALIDACIÓN: Si no se seleccionó ninguna 'need', muestra este mensaje.
                    return $this->sendSuccess(
                        'Para aprobar la adición, debe seleccionar al menos una necesidad. Para esto chequee la casilla "Aprobar" en las necesidades correspondientes.',
                        'info'
                    );
                    
                } else {

                      // Buscamos los modelos completos y frescos desde la BD
                    $approvedNeedsCollection = AdditionNeed::whereIn('id', $approvedNeedsIds)->get();
                    
                    // LLAMADA COHERENTE: Pasamos la información limpia y el padre
                    $this->_createRequestNeed($approvedNeedsCollection, $additionSparePartActivity);
                    
                }
            }

            
            // Valida si es canceLada para crear las necesidades a la solicitud y orden
            if($input["status"] == "Cancelada"){
                // Obtiene el correo del administrador de mantenimientos
                $provider = Providers::select(["name","mail"])->find($additionSparePartActivity->provider_creator_id);

                // Obtiene el consecutivo de la solicitud
                $request_consecutive = $this->getConsecutiveRequest(base64_encode($additionSparePartActivity->request_id));
                
                // Obtiene el usuario en sesion
                $userAuth = Auth::user();

                // Crea el log de la solicitud de necesidad
                RequestNeedHistory::create([
                    "users_nombre" => $userAuth->name,
                    "observacion" => $input["admin_observation"] ?? "",
                    "estado" => $additionSparePartActivity->status,
                    "users_id" => $userAuth->id,
                    "mant_sn_request_id" => $additionSparePartActivity->request_id,
                    "accion" => "Solicitud de adición cancelada"
                ]);                 

                $mail_data = [
                    "consecutive" => $request_consecutive,
                    "provider_name" => $provider["name"],
                    "observation" => $input["admin_observation"],
                    "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?o=" . base64_encode($additionSparePartActivity->order_id),
                    "url_pdf" => env('APP_URL')."/maintenance/generate-gr-r/" . $additionSparePartActivity->order_id
                ];

                $this->_sendEmail($provider["mail"],"maintenance::addition_spare_part_activities.mails.adition_cancelled_by_admin",$mail_data,"Solicitud de adición cancelada - $request_consecutive");
            }

            // Valida si es canceada para crear las necesidades a la solicitud y orden
            if($input["status"] == "En trámite devuelto"){
                // Obtiene el correo del administrador de mantenimientos
                $provider = Providers::select(["name","mail"])->find($additionSparePartActivity->provider_creator_id);

                // Obtiene el consecutivo de la solicitud
                $request_consecutive = $this->getConsecutiveRequest(base64_encode($additionSparePartActivity->request_id));
                
                // Obtiene el usuario en sesion
                $userAuth = Auth::user();

                // Crea el log de la solicitud de necesidad
                RequestNeedHistory::create([
                    "users_nombre" => $userAuth->name,
                    "observacion" => $input["admin_observation"] ?? "",
                    "estado" => $additionSparePartActivity->status,
                    "users_id" => $userAuth->id,
                    "mant_sn_request_id" => $additionSparePartActivity->request_id,
                    "accion" => "Solicitud de adición devuelta para ajustes"
                ]);                 

                $mail_data = [
                    "consecutive" => $request_consecutive,
                    "provider_name" => $provider["name"],
                    "observation" => $input["admin_observation"],
                    "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?o=" . base64_encode($additionSparePartActivity->order_id),
                    "url_pdf" => env('APP_URL')."/maintenance/generate-gr-r/" . $additionSparePartActivity->order_id
                ];

                $this->_sendEmail($provider["mail"],"maintenance::addition_spare_part_activities.mails.adition_cancelled_by_admin",$mail_data,"Solicitud de adición devuelta para ajustes - $request_consecutive");
            }
        

            // Efectua los cambios realizados       

            DB::commit();

            $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);

            return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_update'));

        } catch (\Exception $e) {
            DB::rollback();
            // Manejo de errores...
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController processAddition - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error, '.$e->getMessage());
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *  
     * @param int $id
     * @param UpdateAdditionSparePartActivityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAdditionSparePartActivityRequest $request) {

        $input = $request->all();

        /** @var AdditionSparePartActivity $additionSparePartActivity */
        $additionSparePartActivity = $this->additionSparePartActivityRepository->find($id);

        if (empty($additionSparePartActivity)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input["status"] = "En trámite";
            $input["provider_creator_id"] = session("id");

            // Actualiza el registro
            $additionSparePartActivity = $this->additionSparePartActivityRepository->update($input, $id);

            AdditionNeed::where("addition_id",$id)->delete();

            // Crea las necesidades asociadas a la adición
            $this->_createAdditionNeeds($additionSparePartActivity->id,$input["needs"]);

            // Efectua los cambios realizados
            DB::commit();

            // Crea el log de la solicitud de necesidad
            RequestNeedHistory::create([
                "users_nombre" => session("name"),
                "observacion" => $input["admin_observation"] ?? "",
                "estado" => $additionSparePartActivity->status,
                "users_id" => 1,
                "mant_sn_request_id" => $additionSparePartActivity->request_id,
                "accion" => "Solicitud de adición en trámite"
            ]);
            
            // Obtiene el correo del administrador de mantenimientos
            $admin = User::select(["email","name"])->role("Administrador de mantenimientos")->first();
            

            // Obtiene el consecutivo de la solicitud
            $request_consecutive = $this->getConsecutiveRequest(base64_encode($additionSparePartActivity->request_id));

            $mail_data = [
                "admin_name" => $admin["name"],
                "consecutive" => $request_consecutive,
                "url_register" => env('APP_URL')."/maintenance/addition-spare-part-activities?s=" . base64_encode($additionSparePartActivity->order_id)
            ];

            $this->_sendEmail($admin["email"],"maintenance::addition_spare_part_activities.mails.adition_cost_request_approved_by_provider",$mail_data,"Solicitud de adición en trámite - $request_consecutive");            

            $additionSparePartActivity->load(["Needs","providerCreator","adminCreator"]);
        
            return $this->sendResponse($additionSparePartActivity->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '. session("name").' -  Error: '.$error->getMessage(). "Linea: ".$error->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '. session("name").' -  Error: '.$e->getMessage(). "Linea: ".$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    private function _createAdditionNeeds(int $additionId,array $needs) : void {
        foreach ($needs as $need) {
            $need = json_decode($need, true);
            $need["addition_id"] = $additionId;
            $need["description"] = array_key_exists("descripcion_datos",$need) ? ( array_key_exists("description",$need["descripcion_datos"]) ? $need["descripcion_datos"]["description"] : $need["description"]) : $need["description"];

            AdditionNeed::create($need);
        }
    }
    /**
     * Crea los registros de necesidad en las tablas de solicitudes y órdenes.
     *
     * @param \Illuminate\Database\Eloquent\Collection $approvedNeeds La colección de objetos AdditionNeed aprobados.
     * @param AdditionSparePartActivity $addition El modelo padre.
     * @return void
     */
    private function _createRequestNeed($approvedNeeds, AdditionSparePartActivity $addition): void
    {
        if ($approvedNeeds->isEmpty()) {
            return;
        }

        $needs_total_value = 0;
        
        // Cargamos la relación una sola vez para evitar consultas en el bucle
        $addition->load('RequestNeed');
        $request_total_value = $addition->RequestNeed->total_solicitud;

        // Recorremos la colección de modelos (ya no es un array de JSON)
        foreach ($approvedNeeds as $need) {
            $needs_total_value += $need->valor_total;

            $spare_part_or_activity_id = $this->_importSparePartOrActivity($addition->RequestNeed->rubro_objeto_contrato_id, $need->toArray());

            // --- Crea la necesidad en la solicitud (RequestNeedItem) ---
            $requestNeedItem = RequestNeedItem::create([
                "mant_sn_request_id" => $addition->request_id,
                "tipo_solicitud" => "Activo", // CAMPO AGREGADO
                "tipo_necesidad" => $need->need,
                "necesidad" => $need->need, // CAMPO AGREGADO
                "descripcion" => $spare_part_or_activity_id,
                "descripcion_nombre" => $need->description,
                "unidad_medida" => $need->unit_measurement,
                "valor_unitario" => $need->unit_value,
                "cantidad_solicitada" => $need->amount_requested,
                "IVA" => $need->iva,
                "valor_total" => $need->valor_total,
                "estado" => "Pendiente", // CAMPO AGREGADO
                "total_value" => $need->valor_total, // CAMPO AGREGADO
                "tipo_mantenimiento" => $need->maintenance_type,
            ]);

            // --- Crea la necesidad en la orden (RequestNeedOrdersItem) ---
            RequestNeedOrdersItem::create([
                "mant_sn_orders_id" => $addition->order_id,
                "mant_sn_request_needs_id" => $addition->request_id,
                "descripcion" => $spare_part_or_activity_id, // CAMPO AGREGADO
                "descripcion_nombre" => $need->description,
                "unidad" => $need->unit_measurement, // CAMPO AGREGADO
                "cantidad" => $need->amount_requested, // CAMPO AGREGADO
                "tipo_mantenimiento" => $need->maintenance_type, // CAMPO AGREGADO
                "tipo_solicitud" => "Activo", // CAMPO AGREGADO
                "tipo_necesidad" => $need->need, // CAMPO AGREGADO
                "mant_sn_request_needs_id_real" => $requestNeedItem->id,
            ]);
        }

        // Actualiza el valor total de la solicitud principal
        RequestNeed::where("id", $addition->request_id)->update([
            "total_solicitud" => $request_total_value + $needs_total_value
        ]);
    }

    private function _createRequestNeedInicial(array $needs) : void{
        $request_total_value = 0;
        $needs_total_value = 0;
        foreach ($needs as $need) {
            $need = json_decode($need, true);

            // Valida si la necesidad fue aprobada
            if($need["is_approved"] == null || $need["is_approved"] == false){
                continue;
            }

            $needs_total_value += $need["valor_total"];

            // Obtiene la adición para obtener la solicitud
            $additionSparePartActivity = AdditionSparePartActivity::select(["id","request_id","order_id"])->with("RequestNeed")->find($need["addition_id"]);

            // Crea el repuesto o actividad al contrato
            $spare_part_or_activity_id = $this->_importSparePartOrActivity($additionSparePartActivity->RequestNeed->rubro_objeto_contrato_id,$need);

            $request_total_value = $additionSparePartActivity->RequestNeed->total_solicitud;

            // Crea la necesidad en la solicitud
            $requestNeedItem = RequestNeedItem::create([
                "mant_sn_request_id" => $additionSparePartActivity->request_id,
                "tipo_solicitud" => "Activo",
                "tipo_necesidad" => $need["need"],
                "necesidad" => $need["need"],
                "descripcion" => $spare_part_or_activity_id,
                "descripcion_nombre" => $need["description"],
                "unidad_medida" => $need["unit_measurement"],
                "valor_unitario" => $need["unit_value"],
                "cantidad_solicitada" => $need["amount_requested"],
                "IVA" => $need["iva"],
                "valor_total" => $need["valor_total"],
                "estado" => "Pendiente",
                "total_value" => $need["valor_total"],
                "tipo_mantenimiento" => $need["maintenance_type"]
            ]);

            // Crea la necesidad en la orden
            RequestNeedOrdersItem::create([
                "mant_sn_orders_id" => $additionSparePartActivity->order_id,
                "mant_sn_request_needs_id" => $additionSparePartActivity->request_id,
                "descripcion" => $spare_part_or_activity_id,
                "descripcion_nombre" => $need["description"],
                "unidad" => $need["unit_measurement"],
                "cantidad" => $need["amount_requested"],
                "tipo_mantenimiento" => $need["maintenance_type"],
                "tipo_solicitud" => "Activo",
                "tipo_necesidad" => $need["need"],
                "mant_sn_request_needs_id_real" => $requestNeedItem["id"]
            ]);           
        }
        
        // Actualizar el valor total de la solicitud
        RequestNeed::where("id",$additionSparePartActivity->request_id)->update([
            "total_solicitud" => $request_total_value + $needs_total_value
        ]);        
    }

    private function _createRequestNeedByAdmin(array $needs, AdditionSparePartActivity $additionSparePartActivity) : void{
        $request_total_value = 0;
        $needs_total_value = 0;

        // dd($needs);
        foreach ($needs as $need) {
            $need = json_decode($need, true);

            $need["description"] = array_key_exists("descripcion_datos",$need) ? ( array_key_exists("description",$need["descripcion_datos"]) ? $need["descripcion_datos"]["description"] : $need["description"]) : $need["description"];

            $needs_total_value += $need["valor_total"];

            $request_total_value = $additionSparePartActivity->RequestNeed->total_solicitud;

            // Crea el repuesto o actividad al contrato
            $spare_part_or_activity_id = $this->_importSparePartOrActivity($additionSparePartActivity->RequestNeed->rubro_objeto_contrato_id,$need);

            // Crea la necesidad en la solicitud
            $requestNeedItem = RequestNeedItem::create([
                "mant_sn_request_id" => $additionSparePartActivity->request_id,
                "tipo_solicitud" => "Activo",
                "tipo_necesidad" => $need["need"],
                "necesidad" => $need["need"],
                "descripcion" => $need["descripcion_datos"]["id"],
                "descripcion_nombre" => $need["description"],
                "unidad_medida" => $need["unit_measurement"],
                "valor_unitario" => $need["unit_value"],
                "cantidad_solicitada" => $need["amount_requested"],
                "IVA" => $need["iva"],
                "valor_total" => $need["valor_total"],
                "estado" => "Pendiente",
                "total_value" => $need["valor_total"],
                "tipo_mantenimiento" => $need["maintenance_type"],
            ]);   
                     
            // Crea la necesidad en la orden
            RequestNeedOrdersItem::create([
                "mant_sn_orders_id" => $additionSparePartActivity->order_id,
                "mant_sn_request_needs_id" => $additionSparePartActivity->request_id,
                "descripcion" => $spare_part_or_activity_id,
                "descripcion_nombre" => $need["description"],
                "unidad" => $need["unit_measurement"],
                "cantidad" => $need["amount_requested"],
                "tipo_mantenimiento" => $need["maintenance_type"],
                "tipo_solicitud" => "Activo",
                "tipo_necesidad" => $need["need"],
                "mant_sn_request_needs_id_real" => $requestNeedItem["id"]
            ]);               
        }
        
        // Actualizar el valor total de la solicitud
        RequestNeed::where("id",$additionSparePartActivity->request_id)->update([
            "total_solicitud" => $request_total_value + $needs_total_value
        ]);        
    }

    private function _importSparePartOrActivity(int $contractId, array $need) : int {
        $spare_part_or_activity = [];
        if($need["need"] == "Repuestos"){
            // Importa el repuesto al contrato
            $spare_part_or_activity = ImportSparePartsProviderContract::create([
                'description' => $need["description"],
                'unit_measure' => $need["unit_measurement"],
                'unit_value' => $need["unit_value"],
                'iva' => $need["iva"],
                'total_value' => $need["unit_value"] + $need["iva"],
                'mant_provider_contract_id' => $contractId
            ]);
        }
        else{
            $spare_part_or_activity = ImportActivitiesProviderContract::create([
                'description' => $need["description"],
                'type' => "No aplica",
                'system' => "No aplica",
                'unit_measurement' => $need["unit_measurement"],
                'quantity' => $need["amount_requested"],
                'unit_value' => $need["unit_value"],
                'iva' => $need["iva"],
                'total_value' => ($need["unit_value"] + $need["iva"]) * $need["amount_requested"],
                'mant_provider_contract_id' => $contractId
            ]);
        }

        return $spare_part_or_activity["id"];
    }

    /**
     * Elimina un AdditionSparePartActivity del almacenamiento
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var AdditionSparePartActivity $additionSparePartActivity */
        $additionSparePartActivity = $this->additionSparePartActivityRepository->find($id);

        if (empty($additionSparePartActivity)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $additionSparePartActivity->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\AdditionSparePartActivityController - '.  (Auth::user()->name ?? 'sin sesión') .' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar - 2025
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('addition_spare_part_activities').'.'.$fileType;

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
     * Envia correo
     *
     * @author Kleverman Salazar Florez. - Abr . 17 - 2023
     * @version 1.0.0
     *
     * @param string $emails correos de los destinatarios
     * @param string $storageTemplate ubicacion de la plantilla del correo
     * @param object $data datos que se mostraran en el correo
     * @param string $subject texto del asunto
     */
    private function _sendEmail(string $emails, string $storageTemplate, object|array $data, string $subject): void
    {
        $custom = json_decode('{"subject": "' . $subject . '"}');

        // Envia notificacion al usuario asignado
        SendNotificationController::SendNotification($storageTemplate,$custom,$data,$emails,'Identificación de necesidades');
    }
}
