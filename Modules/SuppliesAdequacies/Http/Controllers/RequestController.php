<?php

namespace Modules\SuppliesAdequacies\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\Maintenance\RequestExport;
use Modules\SuppliesAdequacies\Http\Requests\CreateRequestRequest;
use Modules\SuppliesAdequacies\Models\RequestSuppliesAdequacies;
use Modules\SuppliesAdequacies\Models\RequestNeed;
use Modules\SuppliesAdequacies\Models\HolidayCalendar;
use Modules\SuppliesAdequacies\Models\WorkingHours;
use Modules\SuppliesAdequacies\Http\Requests\UpdateRequestRequest;
use Modules\SuppliesAdequacies\Repositories\RequestRepository;
use App\Http\Controllers\AppBaseController;
use App\User;
use Modules\SuppliesAdequacies\Models\RequestHistory;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Arr;
use Modules\SuppliesAdequacies\Models\RequestAnnotation;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class RequestController extends AppBaseController {

    /** @var  RequestRepository */
    private $requestSuppliesAdequaciesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(RequestRepository $requestRepo) {
        $this->requestSuppliesAdequaciesRepository = $requestRepo;
    }

    /**
     * Muestra la vista para el CRUD de Request.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
    
        if (Auth::user()->hasRole(["Administrador requerimiento gestión recursos","Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación","Funcionario requerimiento gestión recursos"])) {
            return view('suppliesadequacies::requests.index');
        }
        return view("auth.forbidden");

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
        $userAuth = Auth::user();
        if($userAuth->hasRole("Administrador requerimiento gestión recursos")){
            return $this->getRequestSuppliesAdequaciesToAdministrator($request);
        }
        elseif($userAuth->hasRole(["Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"])){
            return $this->getRequestSuppliesAdequaciesToOperator($request);
        }
        else{
            return $this->getRequestSuppliesAdequaciesToFuncionary($request);
        }
    }

    public function getRequestSuppliesAdequaciesToAdministrator($request){


        $result = RequestSuppliesAdequacies::select('status')
        ->selectRaw('COUNT(*) as cantidad')
        ->groupBy('status')
        ->orderBy('status', 'asc')  // Orden alfabético ascendente
        ->get()
        ->keyBy('status')  // Agrupa por el campo 'status'
        ->mapWithKeys(function($item) {
            // Transforma el status a minúsculas y elimina los espacios
            $status = strtolower(str_replace(' ', '', $item->status));
            
            // Devuelve el estado transformado como clave y la cantidad como valor
            return [$status => $item->cantidad];
        });
    


        // Obtén la cantidad total de registros que cumplen con los filtros (sin aplicar skip y take)
        $countRequests = RequestSuppliesAdequacies::with(["requestsSuppliesAdjustementsNeeds","histories","userCreator","assignedOfficer","annotations","pendingAnnotations"])
            ->when($request['f'] != null, function($q) use ($request) {
                $q->whereRaw(base64_decode($request['f']));
            })
            ->count(); 
    
        // Luego obtienes los registros específicos con el paginado
        $requests = RequestSuppliesAdequacies::with(["requestsSuppliesAdjustementsNeeds","histories","userCreator","assignedOfficer","annotations","pendingAnnotations"])
            ->when($request['f'] != null, function($q) use ($request) {
                $q->whereRaw(base64_decode($request['f']));
            })
            ->latest()
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))
            ->get()
            ->map(function($request){
                $request["encrypted_id"] = encrypt($request["id"]);
                $request["creator_name"] = $request->userCreator->name;
                return $request;
            });
    
        // Retorna la respuesta incluyendo el total de registros
        return $this->sendResponseAvanzado($requests->toArray(), trans('data_obtained_successfully'), null, ["total_registros" => $countRequests,"status"=>$result]);
    }
    
    public function getRequestSuppliesAdequaciesToOperator($request){
        $result = RequestSuppliesAdequacies::select('status')
        ->where("assigned_officer_id", Auth::user()->id)
        ->selectRaw('COUNT(*) as cantidad')
        ->whereIn('status',['Asignada','En proceso','Cerrada','Finalizada'])
        ->groupBy('status')
        ->orderBy('status', 'asc')  // Orden alfabético ascendente
        ->get()
        ->keyBy('status')  // Agrupa por el campo 'status'
        ->mapWithKeys(function($item) {
            // Transforma el status a minúsculas y elimina los espacios
            $status = strtolower(str_replace(' ', '', $item->status));
            
            // Devuelve el estado transformado como clave y la cantidad como valor
            return [$status => $item->cantidad];
        });
        // Contamos la cantidad total de registros que cumplen con los filtros (sin aplicar skip y take)
        $countRequests = RequestSuppliesAdequacies::where("assigned_officer_id", Auth::user()->id)
            ->orWhere("user_creator_id", Auth::user()->id)
            ->when($request['f'] != null, function($q) use ($request) {
                $q->whereRaw(base64_decode($request['f']));
            })
            ->count();  // Esto te da el total de registros sin aplicar skip/take
        
        // Ahora obtenemos los registros con paginación
        $requests = RequestSuppliesAdequacies::where(function($query) use ($request) {
            $query->where("assigned_officer_id", Auth::user()->id)
                ->orWhere("user_creator_id", Auth::user()->id);
        })
        ->with([
            "requestsSuppliesAdjustementsNeeds", "histories", "userCreator", 
            "assignedOfficer", "annotations", "pendingAnnotations"
        ])
        ->when($request['f'] != null, function($q) use ($request) {
            $q->whereRaw(base64_decode($request['f']));
        })
        ->latest()
        ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
        ->take(base64_decode($request["pi"]))
        ->get()
        ->map(function($request){
            $request["encrypted_id"] = encrypt($request["id"]);
            $request["creator_name"] = $request->userCreator->name;
            return $request;
        });

        // Retornamos la respuesta con el contador total de registros
        return $this->sendResponseAvanzado($requests->toArray(), trans('data_obtained_successfully'), null, ["total_registros" => $countRequests,"status"=>$result]);
    }
    

    public function getRequestSuppliesAdequaciesToFuncionary($request){

        $result = RequestSuppliesAdequacies::select('status')
        ->selectRaw('COUNT(*) as cantidad')
        ->where("user_creator_id", Auth::user()->id)
        ->whereIn('status',['Abierta','Asignada','En proceso','Próxima vigencia','Finalizada','Cancelada'])
        ->groupBy('status')
        ->orderBy('status', 'asc')  // Orden alfabético ascendente
        ->get()
        ->keyBy('status')  // Agrupa por el campo 'status'
        ->mapWithKeys(function($item) {
            // Transforma el status a minúsculas y elimina los espacios
            $status = strtolower(str_replace(' ', '', $item->status));
            
            // Devuelve el estado transformado como clave y la cantidad como valor
            return [$status => $item->cantidad];
        });
        // Contamos la cantidad total de registros que cumplen con los filtros (sin aplicar skip y take)
        $countRequests = RequestSuppliesAdequacies::where("user_creator_id", Auth::user()->id)
            ->when($request['f'] != null, function($q) use ($request) {
                $q->whereRaw(base64_decode($request['f']));
            })
            ->count(); // Contamos los registros sin aplicar la paginación
        
        // Ahora obtenemos los registros con paginación
        $requests = RequestSuppliesAdequacies::where("user_creator_id", Auth::user()->id)
            ->with(["requestsSuppliesAdjustementsNeeds", "histories", "userCreator", "assignedOfficer"])
            ->when($request['f'] != null, function($q) use ($request) {
                $q->whereRaw(base64_decode($request['f']));
            })
            ->latest()
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))
            ->get()
            ->map(function($request){
                $request["encrypted_id"] = encrypt($request["id"]);
                return $request;
            });
        
        // Retornamos la respuesta con el contador total de registros
        return $this->sendResponseAvanzado($requests->toArray(), trans('data_obtained_successfully'), null, ["total_registros" => $countRequests,"status"=>$result]);
    }
    

    public function getOperatorsByRequirements(string $requirementType){
        // Validacion para cambiar el valor ya que no permitia la ruta directamente con /
        if($requirementType == "Suministros devolutivo - Asignación"){
            $requirementType = "Suministros devolutivo / Asignación";
        }

        $role = "Operador {$requirementType}";

        $operators = User::role([$role])->get()->toArray();
        return $this->sendResponse($operators, trans('data_obtained_successfully'));
    }

    public function getOperators(){
        $allowedRoles = ["Administrador requerimiento gestión recursos","Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"];
        $operators = User::role($allowedRoles)->orderBy("name")->get()->toArray();
        return $this->sendResponse($operators, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateRequestRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $userAuth = Auth::user();


        // Valida si no hay necesidades registradas a la solicitud
        if(empty($input["requests_supplies_adjustements_needs"])){
            return $this->sendSuccess("Por favor ingrese una necesidad a la lista dando click en el botón <strong>Agregar necesidad a la lista.</strong>", 'info');
        }

        // Validaciones para cuando se envia una solicitud
        $isSendingRequestToAdmin = empty($input["type_storage"]);
        if($isSendingRequestToAdmin){
            if($this->_haveNoPermiseToSign($userAuth->id)){
                return $this->sendSuccess("No se encuentra con autorización para firmar.", 'info');
            }
            if($this->_haveNoSignature($userAuth->id)){
                return $this->sendSuccess("No tiene registrada una firma, por favor registrar una firma en su perfil <strong><a href='". env("APP_URL") ."/profile' target='_blank'>Ver mi perfil</a></strong>", 'info');
            }
        }

        if(!empty($input["url_documents"])){
            if(is_array($input["url_documents"])){
                $input["url_documents"] = implode(",",$input["url_documents"]);
            }
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $requestSuppliesAdequacies = RequestSuppliesAdequacies::create(["user_creator_id" => $userAuth->id,"consecutive" => $this->calculateNextConsecutive(),"subject" => $input["subject"],"need_type" => $input["need_type"],"justification" => $input["justification"],"url_documents" => $input["url_documents"] ?? null,"status" => !empty($input["type_storage"]) ? "En elaboración" : "Abierta"]);

            $requestSuppliesAdequacies["tracking"] = "Se crea la solicitud";

            $this->_assignNeedsToRequest($input["requests_supplies_adjustements_needs"],$requestSuppliesAdequacies["id"]);
            $this->_createRequestHistory($requestSuppliesAdequacies);

            // Efectua los cambios realizados
            DB::commit();

            // $this->exportFormatVIG(encrypt($requestSuppliesAdequacies["id"]));

            // Relaciones
            $requestSuppliesAdequacies->requestsSuppliesAdjustementsNeeds;
            $requestSuppliesAdequacies->histories;

            $requestSuppliesAdequacies["encrypted_id"] = encrypt($requestSuppliesAdequacies["id"]);

            return $this->sendResponse($requestSuppliesAdequacies->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). ' lINEA '.$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    private function _haveNoPermiseToSign(int $userId) : bool{
        $havePermiseToSign = User::select("autorizado_firmar")->where("id",$userId)->first()->autorizado_firmar;
        return $havePermiseToSign == 0 || is_null($havePermiseToSign);
    }

    private function _haveNoSignature(int $userId) : bool{
        $havePermiseToSign = User::select("url_digital_signature")->where("id",$userId)->first()->url_digital_signature;
        return is_null($havePermiseToSign);
    }

    public function readAnnotationByRequestId(int $requestId){
        // Obtener el ID del usuario actual
        $userId = Auth::id();

        // Actualizar los registros directamente en la base de datos
        RequestAnnotation::where('requests_supplies_adjustements_id', $requestId)
            ->where(function ($query) use ($userId) {
                $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                    ->orWhere('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                    ->orWhere('leido_por', 'not like', $userId . ',%'); // También para el caso donde el ID del usuario esté al principio seguido de una coma
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        // Buscar y obtener la instancia de Internal correspondiente
        $requestSuppliesAdequacies = $this->requestSuppliesAdequaciesRepository->find($requestId);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Internal
        $requestSuppliesAdequacies->pendingAnnotations;

        // Devolver una respuesta con los datos de la instancia de Internal actualizados
        return $this->sendResponse($requestSuppliesAdequacies->toArray(), trans('msg_success_update'));
    }

    private function _assignNeedsToRequest($needs,$requestId) : void{
        foreach ($needs as $key => $need) {
            $need = json_decode($need,true);

            RequestNeed::create(["requests_supplies_adjustements_id" => $requestId, "need_type" => $need["need_type"],"code" => $need["code"] ?? null,"unit_measure" => $need["unit_measure"],"request_quantity" => $need["request_quantity"]]);
        }
    }

    private function _createRequestHistory($request){
        RequestHistory::create(["requests_supplies_adjustements_id" => $request["id"], "user_creator_id" => $request["user_creator_id"],"expiration_date" => $request["expiration_date"] ?? null,"date_attention" => $request["date_attention"] ?? null,"tracking" => $request["tracking"] ?? null,"status" => $request["status"]]);
    }

    /**
     * Funcion encargada de generar el consecutivo de la orden
    */
    public function calculateNextConsecutive() {
        $cantidad = RequestSuppliesAdequacies::whereNotNull("consecutive")->count();

        $numero = $cantidad + 1;
        $numero_rellenado = str_pad($numero, 3, '0', STR_PAD_LEFT);
        $codigo = date("Y") . "-IN" . $numero_rellenado;

        return $codigo;
    }

    public function exportFormatVIG(string $requestId){
        $requestId = decrypt($requestId);
        $inputFileType = 'Xlsx';
        $inputFileName = base_path('Modules/SuppliesAdequacies/Resources/views/requests/templates/VIG-GG-R-001.xlsx');

        $requestSuppliesAdequacies =  RequestSuppliesAdequacies::with(["requestsSuppliesAdjustementsNeeds","userCreator"])->where("id",$requestId)->first()->toArray();

        $reader = IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        // Valores de celdas estaticas
        $spreadsheet->getActiveSheet()->setCellValue('B6', $requestSuppliesAdequacies["consecutive"]);
        $spreadsheet->getActiveSheet()->setCellValue('B7', $requestSuppliesAdequacies["created_at"]);
        $spreadsheet->getActiveSheet()->setCellValue('F6', "Gestión de recursos");
        $spreadsheet->getActiveSheet()->setCellValue('F8', $requestSuppliesAdequacies["user_creator"]["positions"]["nombre"]);

        $spreadsheet->getActiveSheet()->setCellValue('I8', $requestSuppliesAdequacies["need_type"]);

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
        $cellValue = 11;
        // $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue, (count($requestSuppliesAdequacies["requests_supplies_adjustements_needs"]) - 1));

        $no_item = 1;
        for ($i = 0; $i < count($requestSuppliesAdequacies["requests_supplies_adjustements_needs"]) ; $i++) {
            $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            $spreadsheet->getActiveSheet()->setCellValue('A'.($cellValue), $no_item);

            // Añade un fondo blanco para eliminar la decoracion por defecto de la plantilla
            $spreadsheet->getActiveSheet()->getStyle("A{$cellValue}")->applyFromArray(['fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ]]);
            $spreadsheet->getActiveSheet()->getStyle("B{$cellValue}")->applyFromArray(['fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ]]);
            $spreadsheet->getActiveSheet()->getStyle("H{$cellValue}")->applyFromArray(['fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ]]);
            $spreadsheet->getActiveSheet()->getStyle("I{$cellValue}")->applyFromArray(['fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ]]);
            $spreadsheet->getActiveSheet()->getStyle("J{$cellValue}")->applyFromArray(['fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFFF']
            ]]);

            $spreadsheet->getActiveSheet()->setCellValue('B'.($cellValue), $requestSuppliesAdequacies["requests_supplies_adjustements_needs"][$i]["need_type"]);
            $spreadsheet->getActiveSheet()->mergeCells('B'.$cellValue.':G'.$cellValue);

            $spreadsheet->getActiveSheet()->setCellValue('H'.($cellValue), $requestSuppliesAdequacies["requests_supplies_adjustements_needs"][$i]["code"])->getStyle('H'.($cellValue+$i))->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('I'.($cellValue), $requestSuppliesAdequacies["requests_supplies_adjustements_needs"][$i]["unit_measure"])->getStyle('I'.($cellValue+$i))->getAlignment()->setHorizontal('center');
            $spreadsheet->getActiveSheet()->setCellValue('J'.($cellValue), $requestSuppliesAdequacies["requests_supplies_adjustements_needs"][$i]["request_quantity"])->getStyle('J'.($cellValue+$i))->getAlignment()->setHorizontal('center');
            $no_item++;
            $cellValue++;
        }

        // Suma el valor de la celda para obtener la celda donde va la justificacion de la solicitud
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('A'.($cellValue), "Justificación de la Solicitud: " . $requestSuppliesAdequacies['justification']);
        $spreadsheet->getActiveSheet()->mergeCells('A'.$cellValue.':J'.$cellValue);

        // Suma el valor de la celda para obtener la celda donde va la firma autorizada de la solicitud
        $cellValue++;
        // Valida que el usuario creador si tenga una firma relacionada
        if(!empty($requestSuppliesAdequacies["user_creator"]["url_digital_signature"])) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath(storage_path('app/public' . '/' . $requestSuppliesAdequacies["user_creator"]["url_digital_signature"])); /* put your path and image here */
            $drawing->setCoordinates("C{$cellValue}");
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
            $drawing->setHeight(40);
            $drawing->setResizeProportional(true);
            $drawing->setOffsetX(2);
            $drawing->setOffsetY(2);
        }
        // Suma el valor de la celda para obtener la celda donde va el nombre de quien realizo la solicitud
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('C'.($cellValue), $requestSuppliesAdequacies["user_creator"]["name"]);
        $spreadsheet->getActiveSheet()->mergeCells('C'.$cellValue.':J'.$cellValue);

        // Suma el valor de la celda para obtener la celda donde va la el cargo del usuario quien creo la solicitud
        $cellValue++;
        $spreadsheet->getActiveSheet()->setCellValue('C'.($cellValue), $requestSuppliesAdequacies["user_creator"]["positions"]["nombre"]);
        $spreadsheet->getActiveSheet()->mergeCells('C'.$cellValue.':J'.$cellValue);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="VIG-GG-R-001.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit();
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateRequestRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        $userAuth = Auth::user();

        if(!empty($input["url_documents"])){
            if(is_array($input["url_documents"])){
                $input["url_documents"] = implode(",",$input["url_documents"]);
            }
        }

        if($userAuth->hasRole("Administrador requerimiento gestión recursos")){
            if($input["status"] == "Asignada"){
                $currentDate = date("Y-m-d");

                $holidayCalendars = HolidayCalendar::get()->toArray();
                $workingHours = WorkingHours::latest()->first();

                // Calcula la fecha de vencimiento de la solicitud
                $input["expiration_date"] = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    "Días",
                    $request["term_type"],
                    $request["quantity_term"],
                    $workingHours
                )[0];
            }

            if($input["status"] == "Finalizada"){
                $input["date_attention"] = date("Y-m-d H:i:s");
            }

        }

        if($userAuth->hasRole("Funcionario requerimiento gestión recursos")){
            $input["status"] = !empty($input["type_storage"]) ? $input["status"] : "Abierta";
        }


        $requestSuppliesAdequacies = ["id" => $id, "user_creator_id" => $userAuth->id,"expiration_date" => $input["expiration_date"] ?? null,$input["date_attention"] ?? null,"tracking" => !empty($input["tracking"]) ? $input["tracking"] : null,"status" => $input["status"]];

        $this->_createRequestHistory($requestSuppliesAdequacies);

        /** @var Request $request */
        $request = $this->requestSuppliesAdequaciesRepository->find($id);

        if (empty($request)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $request = $this->requestSuppliesAdequaciesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $request->histories;
            $request->assignedOfficer;

            return $this->sendResponse($request->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Request del almacenamiento
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

        /** @var Request $request */
        $request = $this->requestSuppliesAdequaciesRepository->find($id);

        if (empty($request)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $request->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'AppModules\SuppliesAdequacies\Http\Controllers\RequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('requests').'.'.$fileType;

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
            return Excel::download(new RequestExport('suppliesadequacies::requests.exports.xlsx', $input['data'], 'K'), $fileName);
        }
    }
}
