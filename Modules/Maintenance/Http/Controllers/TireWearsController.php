<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateTireWearsRequest;
use Modules\Maintenance\Http\Requests\UpdateTireWearsRequest;
use Modules\Maintenance\Models\TireQuantitites;
use Modules\Maintenance\Models\TireInformations;
use Modules\Maintenance\Models\TireWears;
use Modules\Maintenance\Models\InflationPressure;
use Modules\Maintenance\Models\TireHistory;
use Modules\Maintenance\Models\TireRecordDepth;
use Modules\Maintenance\Models\SetTire;
use Modules\Maintenance\Models\TireWearHistory;
use Modules\Maintenance\Repositories\TireWearsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\Maintenance\ResquestExportTires;
use Modules\Maintenance\Models\VehicleFuel;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Maintenance\Models\TireHistoryMileage;
use Auth;
use DB;
use App\User;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use DateTime;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TireWearsController extends AppBaseController
{

    /** @var  TireWearsRepository */
    private $tireWearsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TireWearsRepository $tireWearsRepo)
    {
        $this->tireWearsRepository = $tireWearsRepo;
    }

    /**
     * Muestra la vista para el CRUD de TireWears.
     *
     * @author Johan David Velasco Rios 22. sep. 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $machinery = explode('/',$request['individual_tire_id']);
        $tire_informations = TireInformations::with('ResumeMachineryVehiclesYellow')->where('id', $machinery[0])->get()->first()->toArray();
        $tire_wears = TireWears::where('mant_tire_informations_id', $machinery[0])
        ->orderBy('revision_date', 'desc')
        ->first();
        $depth_tire = $tire_informations['depth_tire'];
        $mileage_initial = $tire_informations['mileage_initial'];
        $general_cost_mm = $tire_informations['general_cost_mm'];
        $date = date("Y-m-d");
        return view('maintenance::tire_wears.index')->with("tire_id_plaque",  $request['individual_tire_id'] ?? null)->with('plaque', $tire_informations['plaque'])
        ->with('depth_tire', $depth_tire)->with('mileage_initial', $mileage_initial)->with('general_cost_mm', $general_cost_mm)->with('max_wear', $tire_informations["max_wear_for_retorquing"])->with('date',$date)
        ->with('machinery', $machinery[1] ?? null)->with("mant_resume_machinery_vehicles_yellow_id", $tire_informations['mant_resume_machinery_vehicles_yellow_id'] ?? null)->with('last_revision_date', $tire_wears['revision_date'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Johan David Velasco Rios 22. sep. 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        $tire_wears = TireWears::with(['RecordDepth','tireInformations','ResumeMachineryVehiclesYellow'])->where('mant_tire_informations_id', $request['tire_id_plaque'])->where('mant_resume_machinery_vehicles_yellow_id',$request['id_vehicle'])->latest()->get();
        return $this->sendResponse($tire_wears->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Consulta el kilometraje del combustible
     *
     * @param int $idMachinery
     * @param string $fecha
     * @return Response
     */
    public function checkFuelMileageWears($idMachinery, $fecha)
    {

        $formattedDate = $fecha;

        // Obtener el primer registro con una fecha mayor a la ingresada
        $post = VehicleFuel::where('mant_resume_machinery_vehicles_yellow_id', $idMachinery)
            ->where('invoice_date', '>=', $formattedDate)
            ->orderBy('invoice_date', 'asc')->latest()
            ->first();

        // Si no existe un registro con una fecha mayor, buscar el primer registro con fecha menor
        if (empty($post)) {
            $post = VehicleFuel::where('mant_resume_machinery_vehicles_yellow_id', $idMachinery)
                ->where('invoice_date', '<', $formattedDate)
                ->orderBy('invoice_date', 'desc')
                ->first();
        }

        // Retornar la respuesta
        return $this->sendResponse(
            !empty($post) ? $post->toArray() : [],
            trans('data_obtained_successfully')
        );
    }

    public function checkDateTireWears($fecha, $idTire, $idMachinery){

        $tire_wears = TireWears::where('mant_tire_informations_id', $idTire)->where('mant_resume_machinery_vehicles_yellow_id',$idMachinery)->where('revision_date', '>=', $fecha)->first();

        return $this->sendResponse(
            !empty($tire_wears) ? $tire_wears['id'] : 0,
            trans('data_obtained_successfully')
        );

    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Johan David Velasco Rios 22. sep. 2021
     * @version 1.0.0
     *
     * @param CreateTireWearsRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {

        //Recupera el usuario en sesion
        $user=Auth::user();
        $input = $request->all();


        //Limita la cantidad de decimales a 2 
        if(!isset($input['cost_km']) || !isset($input['registration_depth']) || !isset($input['wear_total'])){
            return $this->sendSuccess("Por favor verificar la información diligenciada ya que no es correcta", 'error');
        }

        $input['registration_depth'] = number_format($input['registration_depth'], 2);
        //Limita la cantidad de decimales a 2 
        $input['wear_total'] = number_format($input['wear_total'], 2);
        //Limita la cantidad de decimales a 2 
        $input['cost_km'] = number_format($input['cost_km'], 2);
        $record_depth = $input['record_depth'];
        $revision_pressure = $input['revision_pressure'];
        $input['mant_tire_informations_id'] = $input['tire_id_plaque'];
        //Busca en la tabla tireInformation un dato que tenga el id igual a la forenea 
        $tireInformations =  TireInformations::where('id', $input['mant_tire_informations_id'])->get()->first();

        $input['mant_resume_machinery_vehicles_yellow_id'] = $tireInformations['mant_resume_machinery_vehicles_yellow_id'];


        //Valida si la relacion viene vacia
        if (!empty($tireInformations)) {
            //saca el 2 porciento de la presion ingresada por el administrador
            $percentage = $tireInformations['inflation_pressure'] * 0.02;
            //suma ese 2 porciento a la presion ingresada por el usuario
            $pressureHigher = $tireInformations['inflation_pressure'] + $percentage;
            //resta ese 2 porciento a la presion ingresada por el usuario
            $pressureLess = $tireInformations['inflation_pressure'] - $percentage;

            //Valida si la presión en la revision esta por arriba del 2 porciento 
            if ($revision_pressure > $pressureHigher) {
                $result_pressure = 'Presión Alta';

                //Valida si la presión en la revision esta por arriba del 2 porciento n
            } elseif ($revision_pressure < $pressureLess) {
                $result_pressure = 'Presión Baja';

                //Valida si la presión no es ni mayor ni menor a la dada por el administrador 
            } elseif ($revision_pressure <= $pressureHigher && $revision_pressure >= $pressureLess) {
                $result_pressure = 'Presión Normal';
            }

            $tireInformations['tire_pressure'] = $result_pressure;
       

            $available_depth = $tireInformations['available_depth'];
            $wear_total = number_format($input['wear_total'],2);
    
            //Saca el porcentaje del desgaste de la llanta
            $tire_wear = ($wear_total / $available_depth) * 100;


            $differentRelationsCount = TireWears::where('mant_tire_informations_id', $input['mant_tire_informations_id'])
                ->distinct()
                ->count('mant_resume_machinery_vehicles_yellow_id');

            $tireWearOld = TireWears::where('mant_tire_informations_id', $input['mant_tire_informations_id'])->latest()->first();

            if($differentRelationsCount <= 1){
                // Verificar que $tireWearOld no sea null y usar acceso consistente (objeto)
                if(!$tireWearOld || empty($tireWearOld->mant_resume_machinery_vehicles_yellow_id) || 
                $tireWearOld->mant_resume_machinery_vehicles_yellow_id == $input["mant_resume_machinery_vehicles_yellow_id"]){
                    $tireInformations['kilometraje_rodamiento'] = $input['route_total'];
                } else {
                    $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) + $input['route_total'];
                }
            } else {
                if($tireWearOld && 
                (empty($tireWearOld->mant_resume_machinery_vehicles_yellow_id) || 
                $tireWearOld->mant_resume_machinery_vehicles_yellow_id == $input["mant_resume_machinery_vehicles_yellow_id"])) {
                    
                    $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) - $tireWearOld->route_total + $input['route_total'];
                } else {
                    $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) + $input['route_total'];
                }
            }
    
            $tireInformations['tire_wear'] = number_format($tire_wear,2);
            
            $tireInformations->save();

            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Inserta el registro en la base de datos
                $tireWears = $this->tireWearsRepository->create($input);
                $arrayTireRecordDepth = [];

                //Recorre el array del dynamicList y lo convierte un objeto
                foreach ($record_depth as $value) {
                    $arrayTireRecordDepth[] = $value['name'];
                }

                //Recorre el objeto y los almacena la tabla TireRecordDepth
                foreach ($arrayTireRecordDepth as $data) {
                    $tireRecordDepth = TireRecordDepth::create([
                        'mant_tire_wears_id' => $tireWears['id'],
                        'name' => floatval($data)
                    ]);
                }

                //Almacena un nuevo dato en el historial
                $history=new TireWearHistory();
                $history->mant_tire_wears_id=$tireWears['id'];
                $history->mant_tire_informations_id = $input['mant_tire_informations_id'];
                $history->users_id=$user->id;
                $history->user_name=$user->name;
                $history->action="Crear";
                $history->plaque=$tireInformations['plaque'];
                $history->position=$tireInformations['position_tire'];
                $history->revision_mileage=$input['revision_mileage'];
                $history->revision_pressure = $input['revision_pressure'];
                $history->wear_total = $input['wear_total'];
                $history->status = 'Activo';
                $history->observation= $input['observation'];
                $history->descripcion= 'Se creo un desgaste a la llanta';

                $history->save();

                $newDate = new DateTime($tireInformations['date_assignment']);
                $historyMilage = new TireHistoryMileage();
                $historyMilage->mant_tire_informations_id = $input['mant_tire_informations_id'] ?? null;
                $historyMilage->mant_vehicle_fuels_id = $tireInformations['mant_vehicle_fuels_id'] ?? null;
                $historyMilage->mant_tire_wears_id = $tireWears['id'];
                $historyMilage->date_assignment = $newDate->format('Y-m-d')  ?? null;
                $historyMilage->plaque = $tireInformations['plaque'] ?? null;
                $historyMilage->mileage_initial = $tireInformations['mileage_initial'] ?? null;
                $historyMilage->revision_date = $tireWears['revision_date'] ?? null;
                $historyMilage->revision_mileage = $tireWears['revision_mileage'] ?? null;
                $historyMilage->route_total = $tireWears['route_total'] ?? null;
                $historyMilage->kilometraje_rodamiento = $tireInformations['kilometraje_rodamiento'] ?? 0;
                $historyMilage->save();


                $tireWears->tireInformations['depth_tire'];
                //Se creo un nuevo registro en el historial
                

                $tireWears->RecordDepth;

                // $this->enviaCorreo($tireWears);
            
                // Efectua los cambios realizados
                DB::commit();
                return $this->sendResponse($tireWears->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
            }

        }else{
            return $this->sendSuccess('La referencia de llanta '. $setTire['tire_reference'] . ' no cuenta con una presión de inflado establecida, por favor diríjase a presión de inflado y establezca una', 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Johan David Velasco Rios 22. sep. 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTireWearsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTireWearsRequest $request)
    {
        //Recupera el usuario en sesion
        $user=Auth::user();

        $input = $request->all();

        if(!isset($input['cost_km']) || !isset($input['registration_depth']) || !isset($input['wear_total'])){
            return $this->sendSuccess("Por favor diligenciar todo el formulario", 'error');
        }
        
        //Inicializamolas variables
        $data = [];
        $revision_pressure = $input['revision_pressure'];


        /** @var TireWears $tireWears */
        $tireWears = $this->tireWearsRepository->find($id);


        if (empty($tireWears)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $tireInformations =  TireInformations::where('id', $input['mant_tire_informations_id'])->get()->first();

        $input['mant_resume_machinery_vehicles_yellow_id'] = $tireInformations['mant_resume_machinery_vehicles_yellow_id'];


        //Valida si la relacion viene vacia
        if (!empty($InflationPressure)) {
            //saca el 2 porciento de la presion ingresada por el administrador
            $percentage = $tireInformations['inflation_pressure'] * 0.02;
            //suma ese 2 porciento a la presion ingresada por el usuario
            $pressureHigher = $tireInformations['inflation_pressure'] + $percentage;
            //resta ese 2 porciento a la presion ingresada por el usuario
            $pressureLess = $tireInformations['inflation_pressure'] - $percentage;

            //Valida si la presión en la revision esta por arriba del 2 porciento 
            if ($revision_pressure > $pressureHigher) {
                $result_pressure = 'Presión Alta';

                //Valida si la presión en la revision esta por arriba del 2 porciento n
            } elseif ($revision_pressure < $pressureLess) {
                $result_pressure = 'Presión Baja';

                //Valida si la presión no es ni mayor ni menor a la dada por el administrador 
            } elseif ($revision_pressure <= $pressureHigher && $revision_pressure >= $pressureLess) {
                $result_pressure = 'Presión Normal';
            }
            $tireInformations['tire_pressure'] = $result_pressure;
        }

        $available_depth = $tireInformations['available_depth'];
        $wear_total = number_format($input['wear_total'],2);

        //Saca el porcentaje del desgaste de la llanta
        $tire_wear = ($wear_total / $available_depth) * 100;

        $tireInformations['tire_wear'] = number_format($tire_wear,2);
        
        $differentRelationsCount = TireWears::where('mant_tire_informations_id', $input['mant_tire_informations_id'])
            ->distinct()
            ->count('mant_resume_machinery_vehicles_yellow_id');

        $tireWearOld = TireWears::where('mant_tire_informations_id', $input['mant_tire_informations_id'])->latest()->first();

        if($differentRelationsCount <= 1){
            // Verificar que $tireWearOld no sea null y usar acceso consistente (objeto)
            if(!$tireWearOld || empty($tireWearOld->mant_resume_machinery_vehicles_yellow_id) || 
            $tireWearOld->mant_resume_machinery_vehicles_yellow_id == $input["mant_resume_machinery_vehicles_yellow_id"]){
                $tireInformations['kilometraje_rodamiento'] = $input['route_total'];
            } else {
                $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) + $input['route_total'];
            }
        } else {
            if($tireWearOld && 
            (empty($tireWearOld->mant_resume_machinery_vehicles_yellow_id) || 
            $tireWearOld->mant_resume_machinery_vehicles_yellow_id == $input["mant_resume_machinery_vehicles_yellow_id"])) {
                
                $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) - $tireWearOld->route_total + $input['route_total'];
            } else {
                $tireInformations['kilometraje_rodamiento'] = ($tireInformations['kilometraje_rodamiento'] ?? 0) + $input['route_total'];
            }
        }

        //Asigna el total de desgaste y la presion de la llanta a la tabla tireInformations
        $tireInformations->save();


        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $tireWears = $this->tireWearsRepository->update($input, $id);

            //Busca los datos que fueron ingresados en el dinamyc list y los elimina 
            TireRecordDepth::where('mant_tire_wears_id', $input['id'])->delete();
            //Recorre el array con los nuevos datos ingresados
            foreach ($input['record_depth'] as $value) {
                //Lo convierte en un objeto
                $data[] = $value['name'];
            }
            //Recorre ese objeto y almacena los nuevo datos 
            foreach ($data as $key) {
                $tireRecordDepth = TireRecordDepth::create([
                    'mant_tire_wears_id' => $tireWears['id'],
                    'name' => floatval($key)
                ]);
            }

                //Almacena un nuevo dato en el historial
                $history=new TireWearHistory();
                $history->mant_tire_wears_id=$tireWears['id'];
                $history->mant_tire_informations_id = $input['mant_tire_informations_id'];
                $history->users_id=$user->id;
                $history->user_name=$user->name;
                $history->action="Editar";
                $history->plaque=$tireInformations['plaque'];
                $history->position=$tireInformations['position_tire'];
                $history->revision_mileage=$input['revision_mileage'];
                $history->revision_pressure = $input['revision_pressure'];
                $history->wear_total = $input['wear_total'];
                $history->status = 'Activo';
                $history->observation= $input['observation'];
                $history->descripcion= 'Se actualizo un desgaste de la llanta';

                $history->save();

                $newDate = new DateTime($tireInformations['date_assignment']);
                $historyMilage = new TireHistoryMileage();
                $historyMilage->mant_tire_informations_id = $input['mant_tire_informations_id'] ?? null;
                $historyMilage->mant_vehicle_fuels_id = $tireInformations['mant_vehicle_fuels_id'] ?? null;
                $historyMilage->mant_tire_wears_id = $tireWears['id'];
                $historyMilage->date_assignment = $newDate->format('Y-m-d')  ?? null;
                $historyMilage->plaque = $tireInformations['plaque'] ?? null;
                $historyMilage->mileage_initial = $tireInformations['mileage_initial'] ?? null;
                $historyMilage->revision_date = $tireWears['revision_date'] ?? null;
                $historyMilage->revision_mileage = $tireWears['revision_mileage'] ?? null;
                $historyMilage->route_total = $tireWears['route_total'] ?? null;
                $historyMilage->kilometraje_rodamiento = $tireInformations['kilometraje_rodamiento'] ?? 0;
                $historyMilage->save();

            // Efectua los cambios realizados
            DB::commit();
            $tireWears->RecordDepth;
            return $this->sendResponse($tireWears->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TireWears del almacenamiento
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
    public function destroy($id)
    {
            //Recupera el usuario en sesion
            $user=Auth::user();
        /** @var TireWears $tireWears */
        $tireWears = $this->tireWearsRepository->find($id);
    

        $tireInformations = TireInformations::where('id', $tireWears['mant_tire_informations_id'])->get()->first();

        //Trae el desgaste anterior ingresado
        $dataOld = TireWears::where([
            ['created_at', '<', $tireWears['created_at']],
            ["mant_tire_informations_id", "=", $tireInformations['id']]
        ])->limit(1)->latest()->get()->first();

        //Valida que la consulta si traiga el dato anterior
        if (!empty($dataOld)) {
            //Consulta el desgaste maximo de la tabla setTire
            //Valida si la relacion viene vacia
            if (!empty($tireInformations)) {
                //suma 5 a la presión de inflado
                $mayorCinco = $tireInformations['inflation_pressure'] * 0.5;
                $pressure = $dataOld['revision_pressure'] * 0.5;

                //Valida si la presión en la revision es 5% mayor a la presión dada por el admin
                if ($pressure > $mayorCinco) {
                    $result_pressure = 'Presión Alta';

                    //Valida si la presión en la revision es 5% menor a la presión dada por el admin
                } elseif ($pressure < $mayorCinco) {
                    $result_pressure = 'Presión Baja';

                    //Valida si la presión no es ni mayor ni menor a la dada por el administrador 
                } elseif ($pressure == $mayorCinco) {
                    $result_pressure = 'Presión Normal';
                }
                $tireInformations['tire_pressure'] = $result_pressure;
            }
            $available_depth = $tireInformations['available_depth'];
            $registration_depth = $dataOld['registration_depth'];

            //Saca el porcentaje del desgaste de la llanta
            $tire_wear = ((($dataOld['registration_depth'] / $available_depth) * 100) - 100) * (-1);

            //Asigna el total de desgaste y la presion de la llanta a la tabla tireInformations
            $tireInformations['tire_wear'] = $tire_wear;
            $tireInformations->save();

        //Dado el caso que la consulta venga vacia, va a establecer los campos en null
        } else {
            $tireInformations['tire_pressure'] = null;
            $tireInformations['tire_wear'] = null;

            $tireInformations->save();
        }

        if (empty($tireWears)) {
            return $this->sendError(trans('not_found_element'), 200);
        }



        // Inicia la transaccion
        DB::beginTransaction();
        try {

            //Almacena un nuevo dato en el historial
            $history=new TireWearHistory();
            $history->mant_tire_wears_id=$tireWears['id'];
            $history->mant_tire_informations_id = $tireWears['mant_tire_informations_id'];
            $history->users_id=$user->id;
            $history->user_name=$user->name;
            $history->action="Eliminar";
            $history->plaque=$tireInformations['plaque'];
            $history->position=$tireInformations['position_tire'];
            $history->revision_mileage=$tireWears['revision_mileage'];
            $history->revision_pressure = $tireWears['revision_pressure'];
            $history->wear_total = $tireWears['wear_total'];
            $history->status = 'Activo';
            $history->descripcion= 'Se elimino un desgaste de la llanta';

            $history->save();

            $deleteTireRecordDepth = TireRecordDepth::where('mant_tire_wears_id',$id)->delete();

            // Elimina el registro
            $tireWears->delete();
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireWearsController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    // /**
    //  * Organiza la exportacion de datos
    //  *
    //  * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
    //  * @version 1.0.0
    //  *
    //  * @param Request $request datos recibidos
    //  */
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('tire_wears').'.'.$fileType;

    //     // Valida si el tipo de archivo es pdf
    //     if (strcmp($fileType, 'pdf') == 0) {
    //         // Guarda el archivo pdf en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
    //     } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
    //         // Guarda el archivo excel en ubicacion temporal
    //         // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

    //         // Descarga el archivo generado
    //         return Excel::download(new GenericExport($input['data']), $fileName);
    //     }
    // }

    /**
     * Organiza la exportacion de datos
     *
     * @author Johan David Velasco Rios. - septiembre. 14 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {

        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('tireBrand').'.'.$fileType;
        $fileName = 'excel.' . $fileType;

        return Excel::download(new ResquestExportTires('maintenance::tire_wears.report_excel', $input['data'], 'l'), $fileName);
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
        // //Valida que el desgaste de la llanta sea mayor que 30
        // if($butgetExecution->tireInformations->tire_wear>30){
        // //obtiene usuarios con el rol especificado
        // $users = User::role('Administrador de mantenimientos')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_admin_tire_information";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");



        // //obtiene usuarios con el rol especificado
        // $users = User::role('mant Operador apoyo administrativo')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_tire_information";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");



        // //obtiene usuarios con el rol especificado
        // $users = User::role('mant Operador Llantas')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_oll_tire_information";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");

        // }
        // //Valida que la presion sea presion alta o presion baja
        // if($butgetExecution->tireInformations->tire_pressure=="Presión Baja" || $butgetExecution->tireInformations->tire_pressure=="Presión Alta"){

        // //obtiene usuarios con el rol especificado
        // $users = User::role('Administrador de mantenimientos')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_admin_tire_pressure";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        // $butgetExecution["presion"]=$butgetExecution->tireInformations['tire_pressure'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");


        // //obtiene usuarios con el rol especificado
        // $users = User::role('mant Operador apoyo administrativo')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_tire_pressure";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        // $butgetExecution["presion"]=$butgetExecution->tireInformations['tire_pressure'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");


        // //obtiene usuarios con el rol especificado
        // $users = User::role('mant Operador Llantas')->get();
        // $emails = array();
        // foreach ($users as $user) {
        //     $emails[] = $user->email;
        // }
        // //dd($emails);
        // //Asigna las variables que van en el correo
        // $butgetExecution["view"] = "maintenance::emails_maintenance.email_ope_oll_tire_pressure";
        // $butgetExecution["title"] = "Llantas";
        // $butgetExecution["placa"]=$butgetExecution->tireInformations['plaque'];
        // $butgetExecution["dependencia"]=$butgetExecution->tireInformations['name_dependencias'];
        // $butgetExecution["position"]=$butgetExecution->tireInformations['position_tire'];
        // $butgetExecution["porcentaje"]=number_format($butgetExecution->tireInformations['tire_wear'], 2);
        // $butgetExecution["marca"]=$butgetExecution->tireInformations['tire_brand'];
        // $butgetExecution["referencia"]=$butgetExecution->tireInformations['tire_reference'];
        // $butgetExecution["presion"]=$butgetExecution->tireInformations['tire_pressure'];
        
        // // Envia el correo 
        // $this->sendEmail('maintenance::emails_maintenance.template', compact('butgetExecution'), $emails, "Llantas");

        // }
    }

    /**
    * Envia correo electronico segun los parametros
    *
    * @author Erika Johana Gonzalez - Mazzo. 04 - 2021
    * @version 1.0.0
    */
    public static function sendEmail($view, $data, $emails, $title){

    if(isset($emails)) {
        Mail::send($view, $data, function ($message) use ( $emails, $title){
            $message->from(config('mail.username'), $title);
            $message->subject($title);
            $message->to($emails);
        });
    }
    }
}
