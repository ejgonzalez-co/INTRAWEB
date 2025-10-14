<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateEquipmentMinorFuelConsumptionRequest;
use Modules\Maintenance\Http\Requests\UpdateEquipmentMinorFuelConsumptionRequest;
use Modules\Maintenance\Repositories\EquipmentMinorFuelConsumptionRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\EquipmentMinorFuelConsumption;
use Modules\Maintenance\Models\ResumeEquipmentMachinery;
use Modules\Maintenance\Models\FuelConsumptionHistoryMinors;
use Modules\Maintenance\Models\MinorEquipmentFuel;
use App\Exports\HelpTable\RequestExport;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\SendNotificationController;

/**
 * Esta es la clase de consumo por equipos menores
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class EquipmentMinorFuelConsumptionController extends AppBaseController {

    /** @var  EquipmentMinorFuelConsumptionRepository */
    private $equipmentMinorFuelConsumptionRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     */
    public function __construct(EquipmentMinorFuelConsumptionRepository $equipmentMinorFuelConsumptionRepo) {
        $this->equipmentMinorFuelConsumptionRepository = $equipmentMinorFuelConsumptionRepo;
    }

    /**
     * Muestra la vista para el CRUD de EquipmentMinorFuelConsumption.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $sentinel=false;
         //Obtiene el objeto que lo va contener
        $minorEquipment=MinorEquipmentFuel::find($request['equipment']);

        //Obtiene el objeto que lo va contener
        $minorEquipmentSecond=MinorEquipmentFuel::where('dependencias_id',$minorEquipment->dependencias_id)->where('fuel_type', $minorEquipment->fuel_type)->where('created_at','>',$minorEquipment->created_at)->get();

        //Si existe un objeto de combustible por equipo menor despues del actual esto da diferente de 0 por lo tanto ingresa
        if(count($minorEquipmentSecond)!=0){
            $sentinel=true;
        }
        else{
            $sentinel=false;
        }
        return view('maintenance::equipment_minor_fuel_consumptions.index', compact('sentinel'))->with("equipment", $request['equipment'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
    // Variable para contar el número total de registros de la consulta realizada
        $count_notifications = 0;


        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {

            $equipment_minor_fuel_consumptions = EquipmentMinorFuelConsumption::with(['dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->where('mant_minor_equipment_fuel_id',$request['equipment'])->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

            $count_notifications = EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id',$request['equipment'])->whereRaw(base64_decode($request["f"]))->count();



        } else if(isset($request["?cp"]) && isset($request["pi"])) {

            $equipment_minor_fuel_consumptions = EquipmentMinorFuelConsumption::with(['dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->where('mant_minor_equipment_fuel_id',$request['equipment'])->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

            $count_notifications = EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id',$request['equipment'])->count();

        } else {

            $equipment_minor_fuel_consumptions = EquipmentMinorFuelConsumption::with(['dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->where('mant_minor_equipment_fuel_id',$request['equipment'])->latest()->get()->toArray();

            $count_notifications = EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id',$request['equipment'])->count();

        }


        // return $this->sendResponse($notifications->toArray(), trans('data_obtained_successfully'));

        return $this->sendResponseAvanzado($equipment_minor_fuel_consumptions, trans('data_obtained_successfully'), null, ["total_registros" => $count_notifications]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateEquipmentMinorFuelConsumptionRequest $request
     *
     * @return Response
     */
    public function store(CreateEquipmentMinorFuelConsumptionRequest $request) {

        //Recupera el usuario en sesion
        $user=Auth::user();

        $input = $request->all();
        
        $total=0;
        $totalSupply=0;
        //Obtiene el objeto que lo va contener
        $minorEquipment=MinorEquipmentFuel::find($request['mant_minor_equipment_fuel_id']);

    //    DD($request['mant_minor_equipment_fuel_id'],  $minorEquipment->toArray());

        $fuel_type = MinorEquipmentFuel ::select('fuel_type')->where('id', $request['mant_minor_equipment_fuel_id'])->get()->first();

        $input['fuel_type']= $fuel_type->fuel_type;
        

        if (property_exists( 'liter_supplied',$request)) {
            $input['liter_supplied']=   $request['liter_supplied'];
        }
        //Obtiene el objeto que lo va contener
        $minorEquipmentSecond=MinorEquipmentFuel::where('dependencias_id',$minorEquipment->dependencias_id)->where('fuel_type', $minorEquipment->fuel_type)->where('created_at','>',$minorEquipment->created_at)->get();

        //Verifica y que la fehca no sea mayor
        if($minorEquipment->supply_date > $request['supply_date']){
            return $this->sendResponse("error", 'La fecha del suministro tiene que ser mayor a la fecha de la creación de la asignación de combustible.', 'warning');
        }
        //Verifica que si existan consumo por equipos
        if(count($minorEquipmentSecond)!=0){
            
            return $this->sendResponse("error", 'Ya hay un nuevo registro no se pueden agregar mas consumos por combustibles.', 'warning');
        }else{        
            
            //Obtiene todas las relaciones de consumo de combustible por equipo
            $total=EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id', $request['mant_minor_equipment_fuel_id'])->sum("gallons_supplied");

            //Se le suma la cantidad de galones
            $total+=$request['gallons_supplied'];
            //Valida que el numero de galones registrados sea menor al numero de galones del saldo final
            if($total<=ceil($minorEquipment->final_fuel_balance)){
                // Inicia la transaccion
            DB::beginTransaction();
                try {
                    // Inserta el registro en la base de datos
                    $equipmentMinorFuelConsumption = $this->equipmentMinorFuelConsumptionRepository->create($input);

                    $historyConsumption= new FuelConsumptionHistoryMinors();
                    $historyConsumption->users_id=$user->id;
                    $historyConsumption->action="Crear";
                    $historyConsumption->description="Se crea el registro";
                    $historyConsumption->name_user=$user->name;
                    $historyConsumption->dependencia=$equipmentMinorFuelConsumption->dependencias->nombre;
                    $historyConsumption->id_equipment_minor=$equipmentMinorFuelConsumption->mant_minor_equipment_fuel_id;
                    $historyConsumption->fuel_equipment_consumption=$equipmentMinorFuelConsumption->mantResumeEquipmentMachinery->name_equipment;
                    $historyConsumption->save();

                    $this->ActualizaTabla( $minorEquipment->dependencias_id, $minorEquipment->fuel_type);
                    $equipmentMinorFuelConsumption->mantMinorEquipmentFuel;
                    $equipmentMinorFuelConsumption->dependencias;
                    $equipmentMinorFuelConsumption->mantResumeEquipmentMachinery;
                    // Efectua los cambios realizados
                    DB::commit();

                    $this->enviaCorreo($equipmentMinorFuelConsumption);

                    return $this->sendResponse($equipmentMinorFuelConsumption->toArray(), trans('msg_success_save'));
                } catch (\Illuminate\Database\QueryException $error) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                    // Retorna mensaje de error de base de datos
                    return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
                } catch (\Exception $e) {
                    // Devuelve los cambios realizados
                    DB::rollback();
                    // Inserta el error en el registro de log
                    $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                    // Retorna error de tipo logico
                    return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
                }
            }else{
                return $this->sendResponse("error", 'El número de galones suministrados supera la cantidad de galones del saldo final', 'warning');
            }
        }
        
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEquipmentMinorFuelConsumptionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEquipmentMinorFuelConsumptionRequest $request) {

        //Recupera el usuario en sesion
        $user=Auth::user();

        $input = $request->all();
        //busca el objeto que va ser editado con la relacion
        $equipmentMinorFuelConsumption = EquipmentMinorFuelConsumption::find($id);

        //Consumo por equipo
        $idMinor=$equipmentMinorFuelConsumption->mant_minor_equipment_fuel_id;        
        $total=0;

        //Busca el equipo menor
        $minorEquipment=MinorEquipmentFuel::find($idMinor);
        //Consumo por equipo
        $var2=EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id', $idMinor)->get();
        //Se recorre los registro de consumo por equipo
        foreach ($var2 as  $value) {
            $total+=$value->gallons_supplied;
        }
         //Llama el metodo para verificar los valores
        $sentinel=$this->verifyValues( $minorEquipment, $request['gallons_supplied'],$equipmentMinorFuelConsumption->gallons_supplied);
         //verifica que retorna el metodo
        if($sentinel[0]==false){
            return $this->sendResponse("error", "No se puede editar existe una incongruencia en el registro creado en la fecha:  $sentinel[1]", 'warning');
         }
         //Se suma el galon nuevo suministrado
         $total+=$request['gallons_supplied'];
         $total=$total-$equipmentMinorFuelConsumption->gallons_supplied;
         
         //Verifica que no este vacio
        if (empty($equipmentMinorFuelConsumption)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //Verifica que el total de consumo no sea mayor que el de la bolsa
        if($total<=$minorEquipment->final_fuel_balance){
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Actualiza el registro
                $equipmentMinorFuelConsumption = $this->equipmentMinorFuelConsumptionRepository->update($input, $id);

                $equipmentMinorFuelConsumption->mantMinorEquipmentFuel;
                $equipmentMinorFuelConsumption->dependencias;
                $equipmentMinorFuelConsumption->mantResumeEquipmentMachinery;

                $historyConsumption= new FuelConsumptionHistoryMinors();
                $historyConsumption->users_id=$user->id;
                $historyConsumption->action="Editar";
                $historyConsumption->description=$input['descriptionDelete'];
                $historyConsumption->name_user=$user->name;
                $historyConsumption->dependencia=$equipmentMinorFuelConsumption->dependencias->nombre;
                $historyConsumption->id_equipment_minor=$equipmentMinorFuelConsumption->mant_minor_equipment_fuel_id;
                $historyConsumption->fuel_equipment_consumption=$equipmentMinorFuelConsumption->mantResumeEquipmentMachinery->name_equipment;
                $historyConsumption->save();

                //Actualiza toda la tabla de esa dependencia
                $this->ActualizaTabla( $minorEquipment->dependencias_id, $minorEquipment->fuel_type);

                // Efectua los cambios realizados
                DB::commit();
                
                return $this->sendResponse($equipmentMinorFuelConsumption->toArray(), trans('msg_success_update'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }else{
            return $this->sendResponse("error", 'El número de galones suministrados supera la cantidad de galones del saldo final', 'warning');
        }
    }

    /**
     * Elimina un EquipmentMinorFuelConsumption del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request) {
        // dd("ELIMINACION", $request["observationDelete"]);
        //Recupera el usuario en sesion
        $user=Auth::user();

        /** @var EquipmentMinorFuelConsumption $equipmentMinorFuelConsumption */
        $equipmentMinorFuelConsumption = $this->equipmentMinorFuelConsumptionRepository->find($request['id']);
        //Elimina todos los registros 
        $idMinor=$equipmentMinorFuelConsumption->mant_minor_equipment_fuel_id;        
        
        //equipos menores
        $minorEquipment=MinorEquipmentFuel::find($idMinor);

        if (empty($equipmentMinorFuelConsumption)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            
            //LLama la relacion consumo de equipos menores
            $documentsMinorEquipment=$equipmentMinorFuelConsumption->mantDocumentsMinorEquipments()->get();
            //Recorre la relacion de equipos menores
            if ( empty($documentsMinorEquipment) ){
                foreach ($documentsMinorEquipment as $value) {
                    //Separa las url por comas
                    $urls=explode(",", $value->url);
                    //Elimina los registros de documentos del servidor
                    foreach ($urls as $variable) {
                        Storage::disk('public')->delete($variable);
                    }

                }
            }
           //Elimina la relacion
            $equipmentMinorFuelConsumption->delete();

            $historyConsumption= new FuelConsumptionHistoryMinors();
            $historyConsumption->users_id=$user->id;
            $historyConsumption->action="Eliminar";
            $historyConsumption->description=$request['observationDelete'];
            $historyConsumption->name_user=$user->name;
            $historyConsumption->dependencia=$equipmentMinorFuelConsumption->dependencias->nombre;
            $historyConsumption->id_equipment_minor=$equipmentMinorFuelConsumption->mant_minor_equipment_fuel_id;
            $historyConsumption->fuel_equipment_consumption=$equipmentMinorFuelConsumption->mantResumeEquipmentMachinery->name_equipment;
            $historyConsumption->save();

            //Elimina todas las relaciones
            $equipmentMinorFuelConsumption->mantDocumentsMinorEquipments()->delete();

            $this->ActualizaTabla( $minorEquipment->dependencias_id, $minorEquipment->fuel_type);
            // Efectua los cambios realizados
            DB::commit();

            $equipmentMinorFuelConsumptionConsult = EquipmentMinorFuelConsumption::with(['dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->where('id',$request['id'] )->get();
            $equipmentMinorFuelConsumption->mantMinorEquipmentFuel;
            $equipmentMinorFuelConsumption->dependencias;
            $equipmentMinorFuelConsumption->mantResumeEquipmentMachinery;
            
            return $this->sendResponse($equipmentMinorFuelConsumptionConsult->toArray(), trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\EquipmentMinorFuelConsumptionController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    public function MigrateComsuption(Request $request) {
        $input = $request->toArray();

        try {
            $user = Auth::user();
            $input = $request->toArray();
            $successfulRegistrations = 0;
            $failedRegistrations = 0;
            $storedRecords = [];
            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';


            if ($request->hasFile('file_import')) {
                // Obtener el archivo del request
                $file = $request->file('file_import');
                // Obtener la extensión original del archivo
                $extension = $file->getClientOriginalExtension();
                // Extensiones permitidas
                $allowedExtensions = ['xls', 'xlsx'];
                // Validar la extensión
                if (!in_array($extension, $allowedExtensions)) {
                    return $this->sendError('El archivo debe ser un documento Excel con extensión .xls o .xlsx.', []);

                }
                $data = Excel::toArray(new EquipmentMinorFuelConsumption, $input["file_import"]);
                
                $contArray = count($data[0]);

                unset($data[0][0]);
 
                    foreach ($data[0] as $row) {

                        // Convertir la fecha al formato deseado
                        $fechaFormateada = \DateTime::createFromFormat('d/m/Y', $row[1])->format('Y-m-d');
                        
                            try {
                            // $user_asigned = User :: where('name' , $row[3])->with('dependencies')->first();

                            // $dataFuel = MinorEquipmentFuel::where('id',$input['mant_minor_equipment_fuel_id'])->get()->first();

                            $resumeEquipment = ResumeEquipmentMachinery::where('no_inventory', $row[5])->get()->first()->toArray();
                            


                                $EquipmentMinorFuelConsumption = EquipmentMinorFuelConsumption::create([
                                    'mant_minor_equipment_fuel_id' => '275',
                                    'mant_resume_equipment_machinery_id' => $resumeEquipment['id'] ?? null,
                                    'dependencias_id' => '19',
                                    'fuel_input' => 'Galón',
                                    'fuel_type' => 'Gasolina',
                                    'supply_date' => $fechaFormateada ?? null,
                                    'gallons_supplied' => $row[6] ?? null,
                                    'name_receives_equipment' => $row[3] ?? null,
                                    'migrado' => 1,
                                    'fecha_migracion' => date('Y-m-d')
                                ]);
                                    $successfulRegistrations++;

                            } catch (\Illuminate\Database\QueryException $error) {
                                $failedRegistrations++;
                                 $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. Auth::user()->fullname . ' -  Error: ' . $error->getMessage() . '. Linea: ' . $error->getLine());

                            }
                        
                    }
                
            }
            return $this->sendResponse($externa, trans('msg_success_save') . "<br /><br />Registros exitosos: $successfulRegistrations<br />Registros fallidos: $failedRegistrations");
        } catch (\Exception $e) {
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        if(array_key_exists("filtros", $input)) {

            if($input["filtros"] != "") {

                $input["data"] = EquipmentMinorFuelConsumption::with(['mantMinorEquipmentFuel','dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->whereRaw($input["filtros"])->latest()->get()->toArray();

            } else {
                $input["data"] = EquipmentMinorFuelConsumption::with(['mantMinorEquipmentFuel','dependencias', 'mantResumeEquipmentMachinery', 'mantDocumentsMinorEquipments'])->latest()->get()->toArray();
            }
            
        }


        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-Consumo de combustible por equipo.'.$fileType;

        // Valida si el tipo de archivo es excel
        return Excel::download(new RequestExport('maintenance::equipment_minor_fuel_consumptions.report_excel', $input['data'],'H'), $fileName);

    }

    /**
     * Obtiene todos la maquinaria de equipos menores dependiendo de la dependencia
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getMachinary($datos)
    {       
       
    $array =  explode(',',$datos);
    $id = $array[0];
    $fuel_type_id = $array[1];


    $fuel_type = MinorEquipmentFuel ::select('fuel_type')->where('id', $fuel_type_id)->get()->first();

        //Obtiene todas las dependencias
        $machinery = ResumeEquipmentMachinery::where('dependencias_id', $id)
            ->where('fuel_type', $fuel_type->fuel_type)
            ->orderBy('name_equipment', 'asc')
            ->orderByRaw('CAST(no_inventory AS UNSIGNED) ASC')
            ->get()
            ->map(function($item) {
                $item["name_equipment_inventory"] = $item->name_equipment . " - " . $item->no_inventory;
                return $item;
            });


        return $this->sendResponse($machinery->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos la maquinaria de equipos menores 
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 30 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getMachinaryAll()
    {       
        //Obtiene todas las dependencias
        $machinery = ResumeEquipmentMachinery::select(
            '*',
            DB::raw("CONCAT(name_equipment, ' - ', no_inventory) as nombre_inventario")
        )
        ->whereNotNull('name_equipment')
        ->whereNotNull('no_inventory')
        ->whereNull('deleted_at')  // Filtra los que no tengan deleted_at
        ->orderBy('name_equipment', 'asc')  // Orden alfabético ascendente
        ->distinct()  // Evita registros repetidos
        ->get();
        return $this->sendResponse($machinery->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene la cantidad de combustible disponible
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 31 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getFuelAvaible($id)
    {
        $total=0;
        
         //Obtiene el objeto que lo va contener
        $minorEquipment=MinorEquipmentFuel::find($id);
         //Obtiene todas las relaciones de consumo de combustible por equipo
        $var2=EquipmentMinorFuelConsumption::where('mant_minor_equipment_fuel_id', $id)->get();
         //Va recorrer todos los registros para sumar el valor de galones que tiene
        if($var2){
            foreach ($var2 as  $value) {
                $total+=$value->gallons_supplied;
            }
        }
        
        
        $result=$minorEquipment->final_fuel_balance-$total;
        
        
        return $this->sendResponse($result, trans('data_obtained_successfully'));
    }



    /**
     * Actualiza la tabla cuando hay cambios
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function ActualizaTabla($id, $type_fuel)
    {
                //saldo inicial de combustible --  initial_fuel_balance -- es el saldo final del registro anterior
                //mas compras en el periodo   --  more_buy_fortnight -- 
                //menos entregas de combustible  --  less_fuel_deliveries -- es la suma del registro de consumo de combustible por equipos menores
                //saldo final de combustible  --  final_fuel_balance --- es la resta entre saldo inicial de combustible mas compras del periodo menos entregas de combustible
                //Obtiene todo los registros de esa dependencia
                $minorEquipmentTwo = MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('dependencias_id', $id)->where('fuel_type', $type_fuel)->get();
                //Recorre todo el arreglo de equipos menores
                for($i=1; $i< count($minorEquipmentTwo);$i++){               
                    //A cada de esos registros se le asigna los valos correspondientes del registro anterior    
                    $minorEquipmentTwo[$i]->initial_fuel_balance=$minorEquipmentTwo[$i-1]->final_fuel_balance;
                    $minorEquipmentTwo[$i]->less_fuel_deliveries=$minorEquipmentTwo[$i-1]->total_consume_fuel;
                    $minorEquipmentTwo[$i]->final_fuel_balance=($minorEquipmentTwo[$i]->initial_fuel_balance+$minorEquipmentTwo[$i]->more_buy_fortnight)-$minorEquipmentTwo[$i]->less_fuel_deliveries;
                    //Se guardan los cambios
                    $minorEquipmentTwo[$i]->save();
                }
    }

    /**
     * Verifica que el total de saldos finales de la dependencia sea mayor que el total de todas las suma de consumo de combustible
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function verifyValues($minorEquipment, $gallonsSuplied, $restTotal)
    {
        //El objeto padre del consumo que se va editar- Galon nuevo que se va guardar- Galones que se van a editar
        
        $totalValueFinal=0;
        $totalConsumption=0;
        $array=[];
        //Se inicializa el arreglo
        $array[0]=true;
        $array[1]=null;
        //Se buscan todos los registros posteriores al que se desea editar
        $minorEquipmentTwo = MinorEquipmentFuel::with('mantEquipmentFuelConsumptions')->where('dependencias_id', $minorEquipment->dependencias_id)->where('created_at','>',$minorEquipment->created_at)->get();
        //Verifica que si existan registros posteriores
        if(count($minorEquipmentTwo)!=0){
        //Verifica que el registro nuevo sus galones sean mayor al que habia
        if($gallonsSuplied > $restTotal){
            //Se resta el valor del registro que habia con el nuevo
            $surplus=$gallonsSuplied-$restTotal;
            //Se recorre el arreglo de los registros 
            foreach ($minorEquipmentTwo as $value) {
                //Se le asigna el valor de consumo final menos la diferencia del registro nuevo con el viejo
                $valueInitial= $value->final_fuel_balance-$surplus;
                //Se recorre el consumo de cada registro y se guarda en una variabl
                foreach ($value->mantEquipmentFuelConsumptions as $valueTwo) {
                    $totalConsumption+=$valueTwo->gallons_supplied;
                }
                //Se compruba que si es mayor ingresa y retorna false y la fecha para mostrar el mensaje de la incongruencia
                if($valueInitial<$totalConsumption){
                    $array[0]=false;
                    $array[1]=$value->created_at;
                    return $array;
                }
            }
        }else{
            //Si no retorna true y puede continuar con la actualizacion
            $array[0]=true;
            $array[1]=null;
            return $array;
        }

        }
        return $array;
    }



            /**
     * Envia correo
     *
     * @author Nicolas Dario Ortiz Peña. - febrero. 17 - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function enviaCorreo($butgetExecution) {
                
                //obtiene usuarios con el rol especificado
                $users = User::role('Administrador de mantenimientos')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //dd($emails);
                ///Asigna valores que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_admin_equipt_minor";
                $butgetExecution["title"] = "Combustible equipos menores";
                //Aqui lo convierte en formato numero
                $butgetExecution["valor"]= number_format($butgetExecution->mantMinorEquipmentFuel->total_fuel_avaible, 2);
                
                // Envia el correo 
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Combustible equipos menores");


                //obtiene usuarios con el rol especificado
                $users = User::role('mant Operador apoyo administrativo')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //Asigna los valores que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_equipt_minor";
                $butgetExecution["title"] = "Combustible equipos menores";
                $butgetExecution["valor"]= number_format($butgetExecution->mantMinorEquipmentFuel->total_fuel_avaible, 2);

                //Envia el correo
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Combustible equipos menores");

                //obtiene usuarios con el rol especificado
                $users = User::role('mant Operador Combustible equipos menores')->get();
                $emails = array();
                foreach ($users as $user) {
                    $emails[] = $user->email;
                }
                //Asigna los datos que van en el correo
                $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_em_equipt_minor";
                $butgetExecution["title"] = "Combustible equipos menores";
                $butgetExecution["valor"]= number_format($butgetExecution->mantMinorEquipmentFuel->total_fuel_avaible, 2);
                //Envia el correo
                $this->sendEmail('maintenance::emails_maintenance.template', $butgetExecution->toArray(), $emails, "Combustible equipos menores");
        
        

    }

        /**
     * Envia correo electronico segun los parametros
     *
     * @author Erika Johana Gonzalez - Mazzo. 04 - 2021
     * @version 1.0.0
     */
    public static function sendEmail($view, $data, $emails, $title){
        if(isset($emails)) {
            $asunto = json_decode('{"subject": "' . $title . '"}');

            SendNotificationController::SendNotification($view,$asunto,$data,$emails,'Mantenimientos de activos - Equipos menores');
        }
    }
}
