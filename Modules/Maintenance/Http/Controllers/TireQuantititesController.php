<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateTireQuantititesRequest;
use Modules\Maintenance\Http\Requests\UpdateTireQuantititesRequest;
use Modules\Maintenance\Repositories\TireQuantititesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\Intranet\Models\Dependency;
use Modules\Maintenance\Models\ResumeMachineryVehiclesYellow;
use Modules\Maintenance\Models\TireQuantitites;
use Modules\Maintenance\Models\TireInformations;
use Modules\Maintenance\Models\TireGestionHistory;
use Modules\Maintenance\Models\TireHistory;
use Modules\Maintenance\Models\TireWears;
use App\Exports\Maintenance\ResquestExportTires;
use Flash;
use App\User;
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
class TireQuantititesController extends AppBaseController
{

    /** @var  TireQuantititesRepository */
    private $tireQuantititesRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TireQuantititesRepository $tireQuantititesRepo)
    {
        $this->tireQuantititesRepository = $tireQuantititesRepo;
    }

    /**
     * Muestra la vista para el CRUD de TireQuantitites.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('maintenance::tire_quantitites.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Johan David Velasco Rios. - sep. 23 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {
        $user=Auth::user();

        if(Auth::user()->hasRole('mant Consulta proceso')){

        $tire_quantitites = TireQuantitites::with(['ResumeMachineryVehiclesYellow', 'dependencies'])->where('dependencias_id', $user->id_dependencia)->latest()->get();
        
        
        }else{
            
        // $user->id_dependencia    ----   dependencias_id ----dependencies_id
        $tire_quantitites = TireQuantitites::with(['ResumeMachineryVehiclesYellow', 'dependencies'])->latest()->get();

            
        }
        return $this->sendResponse($tire_quantitites->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todas las dependencias o precesos de la EPA
     *
     * @author Johan David Velasco R. - Sep. 09 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function allDependencies()
    {
        $dependecies = Dependency::all();
        return $this->sendResponse($dependecies->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todas los vehiculos en base a la dependencia seleccionada
     *
     * @author Johan David Velasco R. - Sep. 09 - 2021
     * @version 1.0.0
     *
     * @param id 
     * @return Response
     */
    public function allVehicles()
    {
        $vehicles = ResumeMachineryVehiclesYellow::all();
        return $this->sendResponse($vehicles->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todas los vehiculos en base a la dependencia seleccionada
     *
     * @author Johan David Velasco R. - Sep. 09 - 2021
     * @version 1.0.0
     *
     * @param id 
     * @return ResponseTire
     */
    public function allVehiclesPlaques($id)
    {
        $vehiclesPlaque = ResumeMachineryVehiclesYellow::where('id', '=', $id)->get();
        return $this->sendResponse($vehiclesPlaque->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Consulta la cantidad de llantas que tine el activo
     *
     * @author Johan David Velasco Rios. - octubre. 21 - 2021
     * @version 1.0.0
     *
     * @param $request
     *
     * @return Response
     */
    public function TiresActives($plaque)
    {
        $tires = ResumeMachineryVehiclesYellow::where('id','=',$plaque)->first()->toArray();
        $number_tires = $tires['number_tires'];
        return $this->sendResponse($number_tires, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los datos en base a la placa ingresada
     *
     * @author Johan David Velasco Rios. - Diciembre. 2 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getPlaque(Request $request)
    {
        //Consulta la plca en la base de datos
        $resumeMachineryVehiclesYellow = ResumeMachineryVehiclesYellow::where('plaque','like',"%".$request['query']."%")->get();
        //Recorre el dato obtenido
        foreach ($resumeMachineryVehiclesYellow as $value) {
            $value['tire_quantity'] = $value['number_tires'];
            $value['mant_resume_machinery_vehicles_yellow_id'] = $value['id'];

            
        }      

        return $this->sendResponse($resumeMachineryVehiclesYellow, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Johan David Velasco Rios. - sep. 23 - 2021
     * @version 1.0.0
     *
     * @param CreateTireQuantititesRequest $request
     *
     * @return Response
     */
    public function store(CreateTireQuantititesRequest $request)
    {
        $user=Auth::user();
        $tire = $request->all();
        $input = ['dependencias_id'=>$tire['dependencias_id'],'mant_resume_machinery_vehicles_yellow_id'=>$tire['mant_resume_machinery_vehicles_yellow_id'],'tire_quantity'=>$tire['tire_quantity'],'plaque'=>$tire['plaque']];
        $numberTires = $input['tire_quantity'];
        $idPlaque = $input['plaque'];
        
        //Hace una consulta y trae la cantidad de llantas que tiene la hoja de vida
        $tires = ResumeMachineryVehiclesYellow::where('plaque','=',$idPlaque)->first()->toArray();
        $number_tires = $tires['number_tires'];
        
        //Busca en la base de datos si ya la placa esta registrada 
        $plaque = TireQuantitites::all()->where('plaque', '=', $idPlaque);
        // Inicia la transaccion

        
        if ($numberTires == $number_tires) {
                # code...
            DB::beginTransaction();
            try {
                

                if (count($plaque) >= 1) {
                    return $this->sendSuccess('Ya tiene un registro con esta placa, si desea agregar mas llantas por favor edítelo', 'error');
                } else {
                    // Inserta el registro en la base de datos
                    $tireQuantitites = $this->tireQuantititesRepository->create($input);
                }


                $tireQuantitites->dependencies;
                $tireQuantitites->ResumeMachineryVehiclesYellow;

                //segun la cantidad de llantas que es ingresado, se define el numero de registros que se hara en la tabla de tireInformations
                for ($i = 1; $i <= $numberTires; $i++) {
                    $quantitieTire = TireInformations::create([
                        'mant_tire_quantities_id' => $tireQuantitites['id'],
                    ]);
                    
                }
                
                $consultInformation = TireInformations::where('mant_tire_quantities_id', $tireQuantitites['id'])->get()->toArray();

                //Recorre el array que se obtuvo en la consulta e itera la cantidad de registros que tendra el historial
                foreach ($consultInformation as $value) {
                    $history = TireHistory::create([
                        'users_id' => Auth::user()->id,
                        'user_name' => Auth::user()->name,
                        'mant_tire_informations_id' => $value['id'],
                        'active_name' => $tireQuantitites->ResumeMachineryVehiclesYellow['name_vehicle_machinery'],
                        'plaque' => $tireQuantitites->ResumeMachineryVehiclesYellow['plaque'],
                        'observation' => 'Llanta del vehiculo'
                    ]);
                }
                
                $tireQuantitites->TireHistories;
                //Guarda en el historial de llantas
                $historyTire= new TireGestionHistory();
                $historyTire->users_id=$user->id;
                $historyTire->action="Crear";
                $historyTire->description="Se crea el registro.";
                $historyTire->name_user=$user->name;
                $historyTire->plaque=$tireQuantitites->plaque;
                $historyTire->dependencia=$tireQuantitites->dependencies->nombre;
                $historyTire->equipment= $tireQuantitites->ResumeMachineryVehiclesYellow->name_vehicle_machinery;
                $historyTire->save();

                //Efectua los cambios realizados
                DB::commit();
                return $this->sendResponse($tireQuantitites->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
            }
        }else{
            return $this->sendSuccess('Por favor actualice el registro en la hoja de vida del activo en el campo cantidad de llantas.', 'error');
        }
    }

    /**
     * Elimina un TireQuantitites del almacenamiento
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
    public function destroy(Request $request)
    {
        
        $user=Auth::user();
        /** @var TireQuantitites $tireQuantitites */
        $tireQuantitites = $this->tireQuantititesRepository->find($request['id']);

        //Realiza una consulta en la tabla de informacion, lo cual trae un array
        $tireInformations = TireInformations::where('mant_tire_quantities_id',$tireQuantitites['id'])->get();
        $countInformation = $tireInformations->count();
        
        //Valida si la consulta llega con datos o vacia
        if($countInformation > 1){
            //Recorre el array obtenido en la consulta
            foreach ($tireInformations as $value) {
                //Valida por medio del estado, si tiene datos o no
                if($value['state'] != null){
                    return $this->sendSuccess('Tiene llantas con información adicional, si desea eliminarlas, por favor vaya a la vista.', 'error');
                }
                //Si no tiene datos, procedera a eliminar
                else{

                    if (empty($tireQuantitites)) {
                        return $this->sendError(trans('not_found_element'), 200);
                    }
            
                    // Inicia la transaccion
                    DB::beginTransaction();
                    try {
                        // Elimina el registro
                        $tireQuantitites->delete();
                        //Guarda en el historial de llantas
                        $historyTire= new TireGestionHistory();
                        $historyTire->users_id=$user->id;
                        $historyTire->action="Eliminar";
                        $historyTire->description=$request['observationDelete'];
                        $historyTire->name_user=$user->name;
                        $historyTire->plaque=$tireQuantitites->plaque;
                        $historyTire->dependencia=$tireQuantitites->dependencies->nombre;
                        $historyTire->equipment= $tireQuantitites->ResumeMachineryVehiclesYellow->name_vehicle_machinery;
                        $historyTire->save();

                        // Efectua los cambios realizados
                        DB::commit();
            
                        return $this->sendSuccess(trans('msg_success_drop'));
                    } catch (\Illuminate\Database\QueryException $error) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                        // Retorna mensaje de error de base de datos
                        return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
                    } catch (\Exception $e) {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Inserta el error en el registro de log
                        $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                        // Retorna error de tipo logico
                        return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
                    }
                }
            }
        //Dato el caso que la consulta venga vacia, procedera a eliminar directamente
        }else{

            if (empty($tireQuantitites)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Elimina el registro
                $tireQuantitites->delete();
    
                // Efectua los cambios realizados
                DB::commit();
    
                return $this->sendSuccess(trans('msg_success_drop'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('maintenance', 'Modules\Maintenance\Http\Controllers\TireQuantititesController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
            }
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
    //     $fileName = time().'-'.trans('tire_quantitites').'.'.$fileType;

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

        return Excel::download(new ResquestExportTires('maintenance::tire_quantitites.report_excel', $input['data'], 'd'), $fileName);
    }
}
