<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\Leca\RequestExport;
use App\Http\Controllers\AppBaseController;
use App\Mail\SendMail;
use Modules\Leca\Http\Requests\CreateSampleTakingRequest;
use Modules\Leca\Http\Requests\UpdateSampleTakingRequest;
use Modules\Leca\Models\DynamicPhList;
use Modules\Leca\Models\DynamicPhOneList;
use Modules\Leca\Models\DynamicPhTwoList;
use Modules\Leca\Models\Ensayo;
use Modules\Leca\Models\HistorySampleTaking;
use Modules\Leca\Models\ListTrials;
use Modules\Leca\Models\ResidualChlorineList;
use Modules\Leca\Models\SamplePoints;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\SampleTakingHasListTrials;
use Modules\Leca\Models\SamplingSchedule;
use Modules\Leca\Models\StartSampling;
use Modules\Leca\Repositories\SampleTakingRepository;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Nicolas Dario Ortiz Peña. - Abr. 06 - 2020
 * @version 1.0.0
 */
class SampleTakingController extends AppBaseController
{

    /** @var  SampleTakingRepository */
    private $sampleTakingRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SampleTakingRepository $sampleTakingRepo)
    {
        $this->sampleTakingRepository = $sampleTakingRepo;
    }

    /**
     * Muestra la vista de recepcion de muestra
     *
     * @author Johan David Velasco R. - Feb. 15 - 2022
     * @version 1.0.0
     */
    public function reception(Request $request)
    {
        return view('leca::sample_reception.index');
    }

    /**
     * Obtiene todas las relaciones del modelo
     *
     * @author Johan David Velasco R. - Feb. 15 - 2022
     * @version 1.0.0
     */
    public function showReception($id)
    {
        $sample_reception = SampleTaking::with(['lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('id', $id)->latest()->get();
        return $this->sendResponse($sample_reception[0]->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Optiene los datos a mostrar en el edit
     *
     * @author Johan David Velasco R. - Feb. 15 - 2022
     * @version 1.0.0
     */
    public function editReception($id)
    {
        //Consulta los datos de la toma muestra
        $consult = SampleTaking::where('id', $id)->with(['lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->get()->toArray();
        //Valida si la consulta ya trae los datos almacenados del formularios
        if (!empty($consult[0]['reception_date'])) {

            $dataNew = ['editHistory' => '1'];

            //De lo contrario asignara estos nuevos
        } else {
            //Consulta el dia actual
            $hoy = date("Y-m-d H:i");
            //Inicializa la variable
            $requestedParameters = '';
            //Inicializa variable
            $finalAdictionPersev = '';
            //Inicializa la variable
            $perseveringAddiction = [];
            // dd($consult[0]);
            //Recorre los ensayos
            foreach ($consult[0]['lc_list_trials'] as $value) {
                $requestedParameters = $requestedParameters . $value['name'] . ',';
            }
            if ($consult[0]['refrigeration'] == "true") {
                $Refrigeration = "Refrigeración";
                array_push($perseveringAddiction, $Refrigeration);
            }
            if ($consult[0]['filtered_sample'] == "true") {
                $filteredSample = "Muestra filtrada";
                array_push($perseveringAddiction, $filteredSample);
            }
            if ($consult[0]['hno3'] == "true") {
                $hno = "HNO3";
                array_push($perseveringAddiction, $hno);
            }
            if ($consult[0]['h2so4'] == "true") {
                $hso = "H2SO4";
                array_push($perseveringAddiction, $hso);
            }
            if ($consult[0]['hci'] == "true") {
                $hci = "HCI";
                array_push($perseveringAddiction, $hci);
            }
            if ($consult[0]['naoh'] == "true") {
                $naoh = "NaOH";
                array_push($perseveringAddiction, $naoh);
            }
            if ($consult[0]['acetate'] == "true") {
                $acetate = "Acetato";
                array_push($perseveringAddiction, $acetate);
            }
            if ($consult[0]['ascorbic_acid'] == "true") {
                $accorbicAcide = "Ácido ascórbico";
                array_push($perseveringAddiction, $accorbicAcide);
            }
            foreach ($perseveringAddiction as $value) {
                $finalAdictionPersev = $finalAdictionPersev . $value . ',';
            }
            //Consulta el nombre del usuario que recepciona
            $nameReceipt = Auth::user()->name;
            //Consulta la firma del usuario que recepciona
            $urReceipt = Auth::user()->url_digital_signature;

            //asigna los datos a un nuevo array
            $dataNew = ['state_receipt' => '1', 'validation' => '1', 'url_receipt' => $urReceipt, 'requested_parameters' => $requestedParameters, 'reception_date' => $hoy, 'name_receipt' => $nameReceipt, 'persevering_addiction' => $finalAdictionPersev];
        }
        //Junta el array de la consulta con el nuevo que se creo
        $edit_reception = array_merge($consult[0], $dataNew);

        //Retorna los datos
        return $this->sendResponse($edit_reception, trans('data_obtained_successfully'));
    }

    /**
     * Lista los datos de la tabla
     *
     * @author Johan David Velasco R. - Feb. 15 - 2022
     * @version 1.0.0
     */
    public function allReception(Request $request)
    {
        $count_sample_takings = 0;

        $result = str_replace(' ', '+', $request['f']);

        $filtros = base64_decode($result);

        $newFilter = $this->removeExistsAfterCondition($filtros);

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            $pattern = "/type_customer_name\s+LIKE\s+'%([^%]+)%'/i";

            if (preg_match($pattern, $filtros, $matches)) {

                $typeCostomer = $matches[1];

                $sample_takings = SampleTaking::with(['users'])
                    ->whereHas('lcStartSampling', function ($query) use ($typeCostomer) {
                        $query->where('type_customer', $typeCostomer);
                    })
                    ->where('duplicado_cloro', 'No')
                    ->whereRaw($newFilter)
                    ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                    ->take(base64_decode($request["pi"]))
                    ->latest()
                    ->get()
                    ->toArray();

                $count_sample_takings = SampleTaking::with(['users'])
                    ->whereHas('lcStartSampling', function ($query) use ($typeCostomer) {
                        $query->where('type_customer', $typeCostomer);
                    })
                    ->where('duplicado_cloro', 'No')
                    ->whereRaw($newFilter)->count();


            }else{
                $sample_takings = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->whereRaw($newFilter)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();
    
                $count_sample_takings = SampleTaking::where('duplicado_cloro', 'No')->whereRaw($newFilter)->count();
                
            }
            


        } else if(isset($request["cp"]) && isset($request["pi"])) {

            $sample_takings = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

            $count_sample_takings = SampleTaking::where('duplicado_cloro', 'No')->count();

        } else {

            $sample_takings = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->latest()->get()->toArray();

            $count_sample_takings = SampleTaking::where('duplicado_cloro', 'No')->count();
            
        }

        // $tire_informations = TireInformations::with(['TireWears'])->where('mant_tire_informations_id',$request['data_id'])->latest()->get();
        return $this->sendResponseAvanzado($sample_takings, trans('data_obtained_successfully'), null, ["total_registros" => $count_sample_takings]);
        // return $this->sendResponse($sample_takings->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Limpia el query de los filtros no deseados en la variable
     *
     * @param string $query La consulta a limpiar.
     * @return string La consulta limpia.
     */
    public function removeExistsAfterCondition($query) {
        // Expresión regular para encontrar la condición `type_customer_name LIKE '%CAPTACION%'` o `type_customer_name LIKE '%DISTRIBUCION%'`
        $pattern1 = "/\b(type_customer_name\s+LIKE\s+'%CAPTACION%'|type_customer_name\s+LIKE\s+'%DISTRIBUCION%')\s*(AND\s*)?/i";

        // Reemplaza las condiciones encontradas por una cadena vacía
        $cleanedQuery = preg_replace($pattern1, '', $query);

        // Elimina cualquier "AND" colgante al final de la consulta
        $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);

        // Si la consulta está vacía después de limpiar, devuelve "1=1"
        if (empty(trim($cleanedQuery))) {
            $cleanedQuery = "1=1";
        }

        return $cleanedQuery;
    }



    /**
     * Muestra la vista publica a la que redirecciona el codigo qr
     *
     * @author José Manuel Marín Londoño. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexPublic(Request $request)
    {
        return view('leca::sample_takings.index_public')->with("lc_start_sampling_id", $request['lc_start_sampling_id'] ?? null);
    }

    /**
     * Muestra la vista para el CRUD de SampleTaking.
     *
     * @author José Manuel Marín Londoño. - Enero. 07 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $infoTaking = StartSampling::where('id', $request['lc_start_sampling_id'])->first();
        return view('leca::sample_takings.index', compact('infoTaking'))->with("lc_start_sampling_id", $request['lc_start_sampling_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        $sample_takings = SampleTaking::with(['programacion', 'lcStartSampling', 'lcSamplePoints', 'users', 'lcDynamicPhOneLists', 'lcDynamicPhTwoLists', 'lcDynamicPhLists', 'lcResidualChlorineLists', 'lcListTrials', 'lcListTrialsTwo', 'lcHistorySampleTakings'])->where('lc_start_sampling_id', $request['lc_start_sampling_id'])->latest()->get();
        return $this->sendResponse($sample_takings->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Enero. 05 - 2022
     * @version 1.0.0
     *
     * @param CreateSampleTakingRequest $request
     *
     * @return Response
     */
    public function store(CreateSampleTakingRequest $request)
    {
        //Obtiene los datos de usuarios que esta en sesion
        $user = Auth::user();
        $input = $request->all();
        $input['ph_promedio'] = number_format($input['ph_promedio'], 2);

        $Year = date("Y");
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $año = date('Y');
            $array = str_split($año);
            $muestraConsecutivo = SampleTaking::whereYear('created_at', $año)->get()->last();

            if ($muestraConsecutivo) {

                $consecutivoArray = str_split($muestraConsecutivo->sample_reception_code);
                $arrayTwo = [];
                $numero = 0;
                $numeroUno = 0;
                $numeros = '';
                for ($i = 0; $i < count($consecutivoArray); $i++) {
                    if (intval($consecutivoArray[$i]) > $numeroUno) {
                        $numero = $i;

                        $i = count($consecutivoArray);

                    }
                }
                for ($i = $numero; $i < count($consecutivoArray); $i++) {
                    array_push($arrayTwo, $consecutivoArray[$i]);
                }

                $numeros = implode($arrayTwo);

                $num = intVal($numeros);

                $num = $num + 1;

                $numeros = strval($num);

                $consecutivo = str_pad($numeros, 4, "0", STR_PAD_LEFT) . '-' . $array[count($array) - 2] . $array[count($array) - 1];

            } else {
                $consecutivo = '0001' . '-' . $array[count($array) - 2] . $array[count($array) - 1];
            }

            $input['sample_reception_code'] = $consecutivo;

            if ($input['lc_sampling_schedule_id'] == 0 && $input['emergencia'] == 'Si') {

                $point=new SamplePoints();
                $point->users_id=$user->id;
                $point->point_location=$input['direction'];
                $point->no_samples_taken="Pendiente";
                $point->sector="Pendiente";
                $point->tank_feeding="Pendiente";
                $point->code="Pendiente";
                $point->save();

                $otro = new SamplingSchedule();
                //Se recupera la fecha del dia
                $fecha = date('Y-m-d 12:00:00');

                $mensaje = "";
                if (isset($input['quimico']) == true) {
                    if ($input['quimico'] == true) {
                        $otro->quimico = $input['quimico'];
                        $mensaje = "\n - Químico";
                    }
                }
                if (isset($input['fisico']) == true) {
                    if ($input['fisico'] == true) {
                        $otro->fisico = $input['fisico'];
                        $mensaje = $mensaje . "\n - Físico";
                    }
                }
                if (isset($input['biologico']) == true) {
                    if ($input['biologico'] == true) {
                        $otro->biologico = $input['biologico'];
                        $mensaje = $mensaje . "\n - Biológico";
                    }
                }
                if (isset($input['todos']) == true) {
                    if ($input['todos'] == true) {
                        $mensaje = "";
                        $otro->todos = $input['todos'];
                        $mensaje = "\n - Biológico \n   - Físico \n  - Químico";
                    }
                }
                
                $otro->lc_sample_points_id= $point->id;
                $otro->users_id = $user->id;
                $otro->sampling_date = $fecha;
                $otro->duplicado = $input['duplicado'];
                $otro->mensaje = $mensaje;
                $otro->observation = $input['observationsPunto'];
                $otro->user_creador = $user->name;
                $otro->users_name = $user->name;
                $otro->save();

                $input['lc_sampling_schedule_id']=$otro->id;

                if ($input['duplicado'] == 'Si') {

                    $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra', 'Leca Admin'])->where('name', $user->name)->get();
                    $usersOfficialsNew = $usersOfficials[0];

                    $usersOfficialsNew->offiialsSchedule = $otro;
                    $custom = json_decode('{"subject": "LECA (EPA)"}');

                    // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));

                    $usersOfficials = User::role(['Administrador Leca'])->get();
                    $usersOfficialsNew = $usersOfficials[0];

                    $usersOfficialsNew->offiialsSchedule = $otro;
                    $custom = json_decode('{"subject": "LECA (EPA)"}');

                    // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));

                }
                $input['lc_sample_points_id'] = $otro->lc_sample_points_id;
            } else {
                if ($input['lc_sampling_schedule_id'] == 0 && $input['emergencia'] == 'No') {

                    $otro = SamplingSchedule::find($input['puntoReemplazar']);
                    //Se recupera la fecha del dia
                    $fecha = date('Y-m-d 12:00:00');

                    $mensaje = "";
                    if (isset($input['quimico']) == true) {
                        if ($input['quimico'] == true) {
                            $mensaje = "\n - Químico";
                        }
                    }
                    if (isset($input['fisico']) == true) {
                        if ($input['fisico'] == true) {
                            $mensaje = $mensaje . "\n - Físico";
                        }
                    }
                    if (isset($input['biologico']) == true) {
                        if ($input['biologico'] == true) {
                            $mensaje = $mensaje . "\n - Biológico";
                        }
                    }
                    if (isset($input['todos']) == true) {
                        if ($input['todos'] == true) {
                            $mensaje = "";
                            $mensaje = "\n - Biológico \n   - Físico \n  - Químico";
                        }
                    }

                    $arrayPoints = $input['lc_sample_points_id'];

                    if (count($arrayPoints) > 0) {
                        $otro->lc_sample_points_id = $arrayPoints[0];
                    }

                    $otro->users_id = $user->id;
                    $otro->sampling_date = $fecha;
                    $otro->duplicado = $input['duplicado'];
                    $otro->mensaje = $mensaje;
                    $otro->observation = $input['observationsPunto'];
                    $otro->user_creador = $user->name;
                    $otro->users_name = $user->name;
                    $otro->quimico = $input['quimico'];
                    $otro->fisico = $input['fisico'];
                    $otro->biologico = $input['biologico'];
                    $otro->todos = $input['todos'];
                    $otro->save();

                    $input['lc_sampling_schedule_id']=$otro->id;

                    if ($input['duplicado'] == 'Si') {

                        $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra', 'Leca Admin'])->where('name', $user->name)->get();
                        $usersOfficialsNew = $usersOfficials[0];
        
                        $usersOfficialsNew->offiialsSchedule = $otro;
                        $custom = json_decode('{"subject": "LECA (EPA)"}');
        
                        // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
        
                        $usersOfficials = User::role(['Administrador Leca'])->get();
                        $usersOfficialsNew = $usersOfficials[0];
        
                        $usersOfficialsNew->offiialsSchedule = $otro;
                        $custom = json_decode('{"subject": "LECA (EPA)"}');
        
                        // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
        
                    }


                    $input['lc_sample_points_id'] = $otro->lc_sample_points_id;
                }else{
                    $otro = SamplingSchedule::find($input['lc_sampling_schedule_id']);
                    $input['lc_sample_points_id']=$otro->lc_sample_points_id;

                }
            }

            $numberSampleTaking = SampleTaking::where('lc_start_sampling_id', $input['lc_start_sampling_id'])->count();
            if ($numberSampleTaking == 15) {
                return $this->sendResponse("error", 'El limite de muestras por toma son menor o igual a 15', 'warning');
            }

            //Carácteres para texto aleatorio
            $chain = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
            $chain .= '0123456789';
            //Se obtiene la longitud de la cadena de caracteres
            $chainLength = strlen($chain);
            //Se define la varibale que va a tener el texto aleatorio
            $randomText = "";
            //Se define la longitud de el texto aleatorio, en este caso son 10, pero se pueden poner lo que se necesite
            $lengthRandomText = 60;

            //Se empieza a crear el texto aleatorio
            for ($i = 1; $i <= $lengthRandomText; $i++) {
                //Definimos un numero aleatorio entre 0 y la longitud de la cadena de caracteres -1
                $position = rand(0, $chainLength - 1);
                //obtenemos un caracter aleatorio escogido de la cadena de caracteres
                $randomText .= substr($chain, $position, 1);
            }

            // $IdQr = $input['lc_start_sampling_id'];
            // $input['url_qr'] = 'leca/codes_ qr/url_qr'.$randomText.$IdQr.'.svg';

            // QrCode::merger(public_path('logo_epa.png'),0.3, true)->generate('http://192.168.137.216:8000/leca/public-sample?lc_qr='.$IdQr, '../public/storage/leca/codes_qr/url_qr'.$IdQr.'.svg');
            // QrCode::generate('http://192.168.137.216:8000/leca/public-sample?lc_qr='.$IdQr, '../public/storage/leca/codes_qr/url_qr'.$randomText.$IdQr.'.svg');
            $input['charge'] = "Laboratorio de Ensayo de Calidad Agua";
            $input['process'] = "Toma de Muestra";
            if ($input['lc_sampling_schedule_id'] != 0) {
                $duplicadoConsulta = SamplingSchedule::where('id', $input['lc_sampling_schedule_id'])->first();
                $infoDuplicado = $duplicadoConsulta->duplicado;
            } else {
                $infoDuplicado = $input['duplicado'];
            }
            $input['users_id'] = Auth::user()->id;
            $input['user_name'] = Auth::user()->name;
            $input['duplicado'] = $infoDuplicado;
            $input['vigencia'] = $Year;
            $input['duplicado_cloro'] = 'No';
            // Inserta el registro en la base de datos
            $sampleTaking = $this->sampleTakingRepository->create($input);

            if (empty($input['url_qr'])) {
                $IdQr = $sampleTaking->id;
                $input['url_qr'] = 'leca/codes_qr/url_qr' . $randomText . $IdQr . '.png';

                QrCode::format('png')->size(1500)->merge(public_path('epaimagen1.png'), .2, true)->generate(config('app.url') . '/leca/public-sample?lc_qr=' . $IdQr, '../public/storage/leca/codes_qr/url_qr' . $randomText . $IdQr . '.png');

                $sampleTaking = $this->sampleTakingRepository->update($input, $IdQr);
            }

            if (!empty($input['lc_dynamic_ph_one_lists'])) {
                //Ciclo que guarda los ph one
                foreach ($input['lc_dynamic_ph_one_lists'] as $option) {
                    $arrayPhOne = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhOneList::create([
                        'ph_unit' => $arrayPhOne->ph_unit,
                        't1' => $arrayPhOne->t1,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }

            if (!empty($input['lc_dynamic_ph_one_lists'])) {
                //Ciclo que guarda los ph two
                foreach ($input['lc_dynamic_ph_two_lists'] as $option) {
                    $arrayPhTwo = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhTwoList::create([
                        'ph_unit' => $arrayPhTwo->ph_unit,
                        't2' => $arrayPhTwo->t2,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }

            if (!empty($input['lc_dynamic_ph_lists'])) {
                //Ciclo que guarda los ph
                foreach ($input['lc_dynamic_ph_lists'] as $option) {
                    $arrayPh = json_decode($option);
                    //Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhList::create([
                        'ph_unit' => $arrayPh->ph_unit,
                        'temperature_range' => $arrayPh->temperature_range,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }
            $contador = 0;
            if (!empty($input['lc_residual_chlorine_lists'])) {
                //Ciclo que guarda el cloro residual
                foreach ($input['lc_residual_chlorine_lists'] as $option) {
                    $arrayResidualClorine = json_decode($option);

                    $contador++;

                    //Se crea la cantidad de registros ingresados por el usuario
                    ResidualChlorineList::create([
                        'v_sample' => $arrayResidualClorine->v_sample,
                        'chlorine_residual_test' => $arrayResidualClorine->chlorine_residual_test,
                        'mg_cl2' => $arrayResidualClorine->mg_cl2,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }

            if ($contador > 1) {
                $sample = $this->sampleTakingRepository->create($input);

                $sample->sample_reception_code = $sample->sample_reception_code . '-D';
                $sample->duplicado_cloro = 'Si';

                if (!empty($input['lc_residual_chlorine_lists'])) {
                    //Ciclo que guarda el cloro residual
                    foreach ($input['lc_residual_chlorine_lists'] as $option) {
                        $arrayResidualClorine = json_decode($option);
    
                        $contador++;
    
                        //Se crea la cantidad de registros ingresados por el usuario
                        ResidualChlorineList::create([
                            'v_sample' => $arrayResidualClorine->v_sample,
                            'chlorine_residual_test' => $arrayResidualClorine->chlorine_residual_test,
                            'mg_cl2' => $arrayResidualClorine->mg_cl2,
                            'lc_sample_taking_id' => $sample->id,
                        ]);
                    }
                }


                // Inserta el registro en la base de datos
                $sample->save();
            }


            //Ejecuta el modelo de puntos de muestra
            $sampleTaking->lcSamplePoints;
            //Ejecuta el modelo de users
            $sampleTaking->users;
            //Ejecuta el modelo de ph one
            $sampleTaking->lcDynamicPhOneLists;
            //Ejecuta el modelo de ph two
            $sampleTaking->lcDynamicPhTwoLists;
            //Ejecuta el modelo de ph
            $sampleTaking->lcDynamicPhLists;
            //Ejecuta el modelo de cloro residual
            $sampleTaking->lcResidualChlorineLists;

            $sampleTaking->programacion;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($sampleTaking->toArray(), trans('msg_success_save'));
        } catch (\Exception $e) {
            dd($e);
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSampleTakingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSampleTakingRequest $request)
    {
        $input = $request->all();
        $input['ph_promedio'] = number_format($input['ph_promedio'], 2);
        $user = Auth::user();

        if ($input['lc_sampling_schedule_id'] == 0) {
            //Se recupera la fecha del dia
            $fecha = date('Y-m-d 12:00:00');
            $otro = new SamplingSchedule();
            $otro->users_id = $user->id;
            $otro->sampling_date = $fecha;
            $otro->direction = $input['direction'];
            $otro->duplicado = $input['duplicado'];
            $otro->observation = $input['observationsPunto'];
            $otro->user_creador = $user->name;
            $otro->lc_sample_points_id = 64;
            $otro->users_name = $user->name;
            $otro->save();

            $input['lc_sampling_schedule_id'] = $otro->id;

            if ($input['duplicado'] == 'Si') {

                $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $user->name)->get();
                $usersOfficialsNew = $usersOfficials[0];

                $usersOfficialsNew->offiialsSchedule = $otro;
                $custom = json_decode('{"subject": "LECA (EPA)"}');

                // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');

                $usersOfficials = User::role(['Administrador Leca'])->get();
                $usersOfficialsNew = $usersOfficials[0];

                $usersOfficialsNew->offiialsSchedule = $otro;
                $custom = json_decode('{"subject": "LECA (EPA)"}');

                // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


            }

        }

        if (!empty($input['is_accepted'])) {
            if ($input['is_accepted'] == 'Si') {
                $input['estado_analisis'] = 'Análisis pendiente';
            } else {
                $input['estado_analisis'] = null;
            }

        }

        //Inicializa esta variables para luego valugar los mensajes del historial
        $validation = 0;
        $validationEdit = 0;

        //Valida si en el array de input exite una key con el nombre de validation
        if (array_key_exists('validation', $input)) {
            //Elimina del array del input los siguientes campos
            unset($input['lc_dynamic_ph_one_lists'], $input['validation'], $input['lc_dynamic_ph_two_lists'], $input['lc_dynamic_ph_lists'], $input['lc_residual_chlorine_lists'], $input['lc_list_trials'], $input['lc_list_trials_two'], $input['lc_history_sample_takings'], $input['_method']);
            //suma 1 a la variable
            $validation++;

            //Valida si en el array existe una key llamada editHistory
        } elseif (array_key_exists('editHistory', $input)) {
            //Elimina del array input los siguiente campos
            unset($input['editHistory']);
            //suma 1 a la variable
            $validationEdit++;
        }

        /** @var SampleTaking $sampleTaking */
        $sampleTaking = $this->sampleTakingRepository->find($id);

        if (empty($sampleTaking)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $sampleTaking = $this->sampleTakingRepository->update($input, $id);
            //Elimina los registros de ph one
            DynamicPhOneList::where('lc_sample_taking_id', $sampleTaking->id)->delete();
            if (!empty($input['lc_dynamic_ph_one_lists'])) {
                //Ciclo que guarda los ph one
                foreach ($input['lc_dynamic_ph_one_lists'] as $option) {
                    $arrayPhOne = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhOneList::create([
                        'ph_unit' => $arrayPhOne->ph_unit,
                        't1' => $arrayPhOne->t1,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }
            //Elimina los registros de ph two
            DynamicPhTwoList::where('lc_sample_taking_id', $sampleTaking->id)->delete();
            if (!empty($input['lc_dynamic_ph_two_lists'])) {
                //Ciclo que guarda los ph two
                foreach ($input['lc_dynamic_ph_two_lists'] as $option) {
                    $arrayPhTwo = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhTwoList::create([
                        'ph_unit' => $arrayPhTwo->ph_unit,
                        't2' => $arrayPhTwo->t2,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }
            //Elimina los registros de ph
            DynamicPhList::where('lc_sample_taking_id', $sampleTaking->id)->delete();
            if (!empty($input['lc_dynamic_ph_lists'])) {
                //Cilo que guarda los ph
                foreach ($input['lc_dynamic_ph_lists'] as $option) {
                    $arrayPh = json_decode($option);
                    //Se crean la cantidad de registros ingresados por el usuario
                    DynamicPhList::create([
                        'ph_unit' => $arrayPh->ph_unit,
                        'temperature_range' => $arrayPh->temperature_range,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }
            //Elimina los registros de cloro residual
            ResidualChlorineList::where('lc_sample_taking_id', $sampleTaking->id)->delete();
            if (!empty($input['lc_residual_chlorine_lists'])) {
                //Ciclo que guarda el cloro residual
                foreach ($input['lc_residual_chlorine_lists'] as $option) {
                    $arrayResidualClorine = json_decode($option);
                    //Se crea la cantidad de registros ingresados por el usuario
                    ResidualChlorineList::create([
                        'v_sample' => $arrayResidualClorine->v_sample,
                        'chlorine_residual_test' => $arrayResidualClorine->chlorine_residual_test,
                        'mg_cl2' => $arrayResidualClorine->mg_cl2,
                        'lc_sample_taking_id' => $sampleTaking->id,
                    ]);
                }
            }

            //crea un objeto de tipo HistorySampleTaking y lo guarda en la base de datos
            $history = new HistorySampleTaking();
            if ($validation > 0) {
                if ($input['is_accepted'] == 'Si') {
                    $history->action = "Se recepciono la muestra";
                } else {
                    $history->action = "La muestra esta pendiente de recepción";
                }
            } else {
                if ($validationEdit > 0) {
                    $history->action = "Actualización en la recepción de la muestra";
                } else {
                    $history->action = "Actualización";
                }

            }

            $history->lc_sample_taking_id = $sampleTaking->id;
            $history->users_id = $sampleTaking->users_id;
            $history->observation = $input['observations_edit'] ?? null;
            $history->user_name = $sampleTaking->users->name;
            $history->save();
            $sampleTaking->lcHistorySampleTakings;
            //Ejecuta el modelo de puntos de muestra
            $sampleTaking->lcSamplePoints;
            //Ejecuta el modelo de users
            $sampleTaking->users;
            //Ejecuta el modelo de ph one
            $sampleTaking->lcDynamicPhOneLists;
            //Ejecuta el modelo de ph two
            $sampleTaking->lcDynamicPhTwoLists;
            //Ejecuta el modelo de ph
            $sampleTaking->lcDynamicPhLists;
            //Ejecuta el modelo de cloro residual
            $sampleTaking->lcResidualChlorineLists;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($sampleTaking->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateSampleTakingRequest $request
     *
     * @return Response
     */
    public function updateReception($id, UpdateSampleTakingRequest $request)
    {
        $input = $request->all();
        $user = Auth::user();

        if ($input['lc_sampling_schedule_id'] == 0) {
            //Se recupera la fecha del dia
            $fecha = date('Y-m-d 12:00:00');
            $otro = new SamplingSchedule();
            $otro->users_id = $user->id;
            $otro->sampling_date = $fecha;
            $otro->direction = $input['direction'];
            $otro->duplicado = $input['duplicado'];
            $otro->observation = $input['observationsPunto'];
            $otro->user_creador = $user->name;
            $otro->lc_sample_points_id = 64;
            $otro->users_name = $user->name;
            $otro->save();

            $input['lc_sampling_schedule_id'] = $otro->id;

            if ($input['duplicado'] == 'Si') {

                $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $user->name)->get();
                $usersOfficialsNew = $usersOfficials[0];

                $usersOfficialsNew->offiialsSchedule = $otro;
                $custom = json_decode('{"subject": "LECA (EPA)"}');

                // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');

                $usersOfficials = User::role(['Administrador Leca'])->get();
                $usersOfficialsNew = $usersOfficials[0];

                $usersOfficialsNew->offiialsSchedule = $otro;
                $custom = json_decode('{"subject": "LECA (EPA)"}');

                // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


            }
        }

        if (!empty($input['is_accepted'])) {
            if ($input['is_accepted'] == 'Si') {
                $input['estado_analisis'] = 'Análisis pendiente';
            } else {
                $input['estado_analisis'] = null;
            }

        }

        //Inicializa esta variables para luego valugar los mensajes del historial
        $validation = 0;
        $validationEdit = 0;

        /** @var SampleTaking $sampleTaking */
        $sampleTaking = $this->sampleTakingRepository->find($id);

        if (empty($sampleTaking)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $sampleTaking = $this->sampleTakingRepository->update($input, $id);

            //crea un objeto de tipo HistorySampleTaking y lo guarda en la base de datos
            $history = new HistorySampleTaking();
            if ($validation > 0) {
                if ($input['is_accepted'] == 'Si') {
                    $history->action = "Se recepciono la muestra";
                } else {
                    $history->action = "La muestra esta pendiente de recepción";
                }
            } else {
                if ($validationEdit > 0) {
                    $history->action = "Actualización en la recepción de la muestra";
                } else {
                    $history->action = "Actualización";
                }

            }

            $history->lc_sample_taking_id = $sampleTaking->id;
            $history->users_id = $sampleTaking->users_id;
            $history->observation = $input['observations_edit'] ?? null;
            $history->user_name = $sampleTaking->users->name;
            $history->save();
            $sampleTaking->lcHistorySampleTakings;
            //Ejecuta el modelo de puntos de muestra
            $sampleTaking->lcSamplePoints;
            //Ejecuta el modelo de users
            $sampleTaking->users;
            //Ejecuta el modelo de ph one
            $sampleTaking->lcDynamicPhOneLists;
            //Ejecuta el modelo de ph two
            $sampleTaking->lcDynamicPhTwoLists;
            //Ejecuta el modelo de ph
            $sampleTaking->lcDynamicPhLists;
            //Ejecuta el modelo de cloro residual
            $sampleTaking->lcResidualChlorineLists;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($sampleTaking->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un SampleTaking del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Abr. 06 - 2020
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

        /** @var SampleTaking $sampleTaking */
        $sampleTaking = $this->sampleTakingRepository->find($id);

        if (empty($sampleTaking)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $sampleTaking->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SampleTakingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author José Manuel Marín Londoño. - May. 03 - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportReception(Request $request)
    {
        $input = $request->all();

        $result = str_replace(' ', '+', $request['f']);

        $filtros = base64_decode($result);

        $newFilter = $this->removeExistsAfterCondition($filtros);

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            $pattern = "/type_customer_name\s+LIKE\s+'%([^%]+)%'/i";

            if (preg_match($pattern, $filtros, $matches)) {

                $typeCostomer = $matches[1];

                $input['data'] = SampleTaking::with(['users'])
                    ->whereHas('lcStartSampling', function ($query) use ($typeCostomer) {
                        $query->where('type_customer', $typeCostomer);
                    })
                    ->where('duplicado_cloro', 'No')
                    ->whereRaw($newFilter)
                    ->latest()
                    ->get()
                    ->toArray();


            }else{
                $input['data'] = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->whereRaw($newFilter)->latest()->get()->toArray();
    
                
            }
            

        } else if(isset($request["cp"]) && isset($request["pi"])) {

            $input['data'] = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->latest()->get()->toArray();


        } else {

            $input['data'] = SampleTaking::with(['users'])->where('duplicado_cloro', 'No')->latest()->get()->toArray();

            
        }
        
        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/leca/excel/leca-047.xlsx');
       // $inputFileName = storage_path('public/leca/report_management/leca-047.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $totalSample = count($input['data']);
        if ($totalSample == 1) {
            // $spreadsheet->getActiveSheet()->insertNewRowBefore(22, $totalSample+1);
        } else {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(13, $totalSample - 1);
        }

        $cont = 12;

        $hoy = date("y-m-d"); 
        $spreadsheet->getActiveSheet()->setCellValue('AB7' , $hoy);

        foreach ($input['data'] as $key => $value) {

            //Aqui va el nuevo
            //Aqui empieza lo de arriba

            if ($value['reception_date']) {
                $array = explode(' ', $value['reception_date']);
                $hour = $array[1];
                $array = explode('-', $array[0]);
                $year = $array[0];
                $month = $array[1];
                $day = $array[2];

                $spreadsheet->getActiveSheet()->setCellValue('A' . $cont, substr($year, -2));
                $spreadsheet->getActiveSheet()->setCellValue('B' . $cont, $month);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $cont, $day);
                $spreadsheet->getActiveSheet()->setCellValue('D' . $cont, $value["reception_hour"]);
            }

            if ($value['created_at']) {
                $arrayc = explode(' ', $value['created_at']);
                $hourc = explode(':', $arrayc[1]);
                $hourc = $hourc[0] . ':' . $hourc[1];
                $arrayc = explode('-', $arrayc[0]);
                $yearc = $arrayc[0];
                $monthc = $arrayc[1];
                $dayc = $arrayc[2];

                $spreadsheet->getActiveSheet()->setCellValue('E' . $cont, substr($yearc, -2));
                $spreadsheet->getActiveSheet()->setCellValue('F' . $cont, $monthc);
                $spreadsheet->getActiveSheet()->setCellValue('G' . $cont, $dayc);
                $spreadsheet->getActiveSheet()->setCellValue('H' . $cont, $value['hour_from_to']);
            }
            $user = DB::table('users')->where("id", $value['users_id'])->first();

            if ($user->url_digital_signature) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $user->url_digital_signature)); /* put your path and image here */
                $drawing->setCoordinates('I' . $cont);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            }

            if($value['type_water'] == 'Cruda'){
                $tipoAgua = 'C';
            } else if($value['type_water'] == 'Tratada'){
                $tipoAgua = 'T';
            } else {
                $tipoAgua = 'P';
            }


            if($value['type_receipt'] == 'Vidrio'){
                $tipoRecipiente = 'V';
            } else if($value['type_receipt'] == 'Plástico'){
                $tipoRecipiente = 'P';
            } else {
                $tipoRecipiente = 'V-P';
            }
            $spreadsheet->getActiveSheet()->setCellValue('J' . $cont, $tipoAgua);
            $spreadsheet->getActiveSheet()->setCellValue('K' . $cont, $value['cloro_promedio']);
            $spreadsheet->getActiveSheet()->setCellValue('L' . $cont, $value['ph_promedio']);
            $spreadsheet->getActiveSheet()->setCellValue('M' . $cont, $value['turbidez']);
            $spreadsheet->getActiveSheet()->setCellValue('N' . $cont, $value['conductivity_reception']);
            $spreadsheet->getActiveSheet()->setCellValue('O' . $cont, $value['other_reception']);

            $spreadsheet->getActiveSheet()->setCellValue('P' . $cont, $tipoRecipiente);
            $spreadsheet->getActiveSheet()->setCellValue('Q' . $cont, $value['volume_liters']);



            $muestras= DB::table('lc_sample_taking_has_lc_list_trials as listaTomas')->select('listaTomas.lc_list_trials_id as codigoMuestra')->where('listaTomas.lc_sample_taking_id',$value['id'])->get();
            $codigos=[];
    
             for ($j=0; $j < count($muestras) ; $j++) { 
                $codigos[$j]=$muestras[$j]->codigoMuestra;
             }
             asort($codigos);
             $codigosDeMuestras = implode(', ', $codigos);



            $spreadsheet->getActiveSheet()->setCellValue('R' . $cont, $codigosDeMuestras);
            $spreadsheet->getActiveSheet()->setCellValue('S' . $cont, $value['persevering_addiction']);
            $spreadsheet->getActiveSheet()->setCellValue('T' . $cont, $value['t_initial_receipt']);
            $spreadsheet->getActiveSheet()->setCellValue('U' . $cont, $value['t_final_receipt']);

            $spreadsheet->getActiveSheet()->setCellValue('V' . $cont, '');
            $spreadsheet->getActiveSheet()->setCellValue('W' . $cont, '');

            $spreadsheet->getActiveSheet()->setCellValue('X' . $cont, $value['observation_receipt']);

            $MR = '';
            $S = '';
            if ($value['is_accepted'] == 'Si') {
                $MR = 'X';
            } else {
                $MR = '';
            }
            if ($value['is_accepted'] == 'No') {
                $S = 'X';
            } else {
                $S = '';
            }
            //aqui
            $spreadsheet->getActiveSheet()->setCellValue('Y' . $cont, $MR);
            $spreadsheet->getActiveSheet()->setCellValue('Z' . $cont, $S);
            
            $MR = '';
            $S = '';
            if ($value['according_receipt'] == 'Si') {
                $MR = 'X';
            } else {
                $MR = '';
            }
            if ($value['according_receipt'] == 'No') {
                $S = 'X';
            } else {
                $S = '';
            }
            $spreadsheet->getActiveSheet()->setCellValue('V' . $cont, $MR);
            $spreadsheet->getActiveSheet()->setCellValue('W' . $cont, $S);
            
            $spreadsheet->getActiveSheet()->setCellValue('AA' . $cont, $value['sample_reception_code']);
            $spreadsheet->getActiveSheet()->setCellValue('AB' . $cont, $value['name_receipt']);



            if ($value['url_receipt']) {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath(storage_path('app/public' . '/' . $value['url_receipt'])); /* put your path and image here */
                $drawing->setCoordinates('AC' . $cont);
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(36);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(2); // this is how
                $drawing->setOffsetY(2);
            }

            $cont++;

        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte Excel.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('data_obtained_successfully'));

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author José Manuel Marín Londoño. - May. 03 - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = 'reporte.' . $fileType;

        // Retorna la descarga del excel y se le esta ingresando como parametro hasta que columna va a llenar ese excel en este caso es la (e)
        return Excel::download(new RequestExport('leca::sample_takings.report_excel', $input['data'], 'e'), $fileName);
    }

    /**
     * Obtiene todos los puntos que esten guardados
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPointsLocation($id)
    {

        $starSampling = StartSampling::select('id')->where('id', $id)->first();
        $sampleTalking = SampleTaking::where('lc_start_sampling_id',$starSampling->id)->first();
        //Se recupera la fecha del dia
        $fecha = date('Y-m-d 12:00:00');
        if (Auth::user()->hasRole('Administrador Leca') && empty($sampleTalking)) {

            //Se recupera el usuario en sesion
            $user = Auth::user();
            $programacion = [];
            

            //se recupera la programacion del dia y del usuario asignado
            $programacion = SamplingSchedule::with('lcSamplePoints')->where('sampling_date', $fecha)->get();
            $programacion->toArray();
            $programacion->prepend(array('id' => 0, 'nombre_punto' => "Punto de reemplazo"));
            // array_push($programacion, array('id' => 0, 'nombre' => "Otro"));
        } else {
            $user = Auth::user();
            //Se recupera el usuario en sesion
            $programacion = [];

            //se recupera la programacion del dia y del usuario asignado
            $programacion = SamplingSchedule::with('lcSamplePoints')->where('sampling_date', $fecha)->where('users_id', $user->id)->get();
            $programacion->toArray();
            
            $programacion->prepend(array('id' => 0, 'nombre_punto' => "Punto de reemplazo"));
            // array_push($programacion, array('id' => 0, 'nombre' => "Otro"));

        }
    
        return $this->sendResponse($programacion, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los puntos que esten guardados
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPointsLocationReem()
    {

        //Se recupera la fecha del dia
        $fecha = date('Y-m-d 12:00:00');
        if (Auth::user()->hasRole('Administrador Leca')) {

            //Se recupera el usuario en sesion
            $user = Auth::user();
            $programacion = [];
            //se recupera la programacion del dia y del usuario asignado
            $programacion = SamplingSchedule::with('lcSamplePoints')->where('sampling_date', $fecha)->get();
            $programacion->toArray();

            // $programacion->prepend(array('id' => 0, 'nombre_punto' => "Punto de reemplazo"));
            // array_push($programacion, array('id' => 0, 'nombre' => "Otro"));
        } else {
            $user = Auth::user();
            //Se recupera el usuario en sesion
            $programacion = [];
            //se recupera la programacion del dia y del usuario asignado
            $programacion = SamplingSchedule::with('lcSamplePoints')->where('sampling_date', $fecha)->where('users_id', $user->id)->get();
            $programacion->toArray();

            // $programacion->prepend(array('id' => 0, 'nombre_punto' => "Punto de reemplazo"));
            // array_push($programacion, array('id' => 0, 'nombre' => "Otro"));

        }

        return $this->sendResponse($programacion, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los puntos que esten guardados
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPointsLocationReemplazo()
    {

        //Se recupera el usuario en sesion
        $programacion = [];
        //se recupera la programacion del dia y del usuario asignado
        $programacion = SamplingSchedule::with('lcSamplePoints')->where('id', '!=', 1)->get();
        $programacion->toArray();

        return $this->sendResponse($programacion, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los puntos que esten guardados
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPointAll()
    {

        $puntos = SamplePoints::all();

        return $this->sendResponse($puntos, trans('data_obtained_successfully'));
    }
    /**
     *Guarda los datos extras del formulario de la informacion de qr
     *
     * @author José Manuel Marin Londoño. - Enero. 05 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function informationQr(Request $request)
    {

        $input = $request->except(['created_at', 'updated_at', '_method']);

        //Recupera el id del registro de titulo para buscarlo y modificarlo
        $idInformation = $input['id'];
        $oldSampleTaking = SampleTaking::find($idInformation);

        if (array_key_exists('refrigeration', $input)) {
            if ($input['refrigeration'] == 'false') {
                $input['refrigeration'] = null;
            }
        }
        if (array_key_exists('hci', $input)) {
            if ($input['hci'] == 'false') {
                $input['hci'] = null;
            }
        }
        if (array_key_exists('filtered_sample', $input)) {
            if ($input['filtered_sample'] == 'false') {
                $input['filtered_sample'] = null;
            }
        }
        if (array_key_exists('naoh', $input)) {
            if ($input['naoh'] == 'false') {
                $input['naoh'] = null;
            }
        }
        if (array_key_exists('hno3', $input)) {
            if ($input['hno3'] == 'false') {
                $input['hno3'] = null;
            }
        }
        if (array_key_exists('acetate', $input)) {
            if ($input['acetate'] == 'false') {
                $input['acetate'] = null;
            }
        }
        if (array_key_exists('h2so4', $input)) {
            if ($input['h2so4'] == 'false') {
                $input['h2so4'] = null;
            }
        }
        if (array_key_exists('ascorbic_acid', $input)) {
            if ($input['ascorbic_acid'] == 'false') {
                $input['ascorbic_acid'] = null;
            }
        }

        // // Valida si viene grupos de trabajo para asignar
        // if (!empty($input['lc_list_trials'])) {
        //     // Inserta relacion con grupos de trabajo
        //     $sampleTaking->lcListTrials()->sync($input['lc_list_trials']);
        // }

        //Ejecuta la relacion con ensayos fisicos
        $oldSampleTaking->lcListTrials;

        //Actualiza el registro
        $sampleTaking = $this->sampleTakingRepository->update($input, $idInformation);

        //Valida si viene ensayos fisicos para actualizar
        if (empty($input['lc_list_trials'])) {
            $sampleTaking->lcListTrials()->detach();
        } else {

            //Inserta relacion con los ensayos fisicos
            $sampleTaking->lcListTrials()->sync($input['lc_list_trials']);

            //consulta los ensayos de la muestra
            $ensayo = Ensayo::where('lc_sample_taking_id', $sampleTaking->id)->get();
            //recupera el usuario
            $user = Auth::user();
            //En este for se asignan los valores a cada ensayo
            foreach ($ensayo as $value) {
                $value->nombre_usuario = $user->name;
                $value->user_id = $user->id;
                $value->estado = "Pendiente";
                $value->save();
            }
        }

        //Ejecuta la relacion con los ensayos fisicos
        $sampleTaking->lcListTrials;

        //Prepara los datos que se ingresan para comparar
        $arrDataSampleTaking = Arr::dot(UtilController::dropNullEmptyList($sampleTaking->replicate()->toArray(), true, 'lc_list_trials')); // Datos actuales de usuario
        $arrDataOldSampleTaking = Arr::dot(UtilController::dropNullEmptyList($oldSampleTaking->replicate()->toArray(), true, 'lc_list_trials')); // Datos antiguos de usuario

        // Datos diferenciados
        $arrDiff = array_diff_assoc($arrDataSampleTaking, $arrDataOldSampleTaking);
        // Valida si los datos antiguos son diferentes a los actuales
        if (count($arrDiff) > 0) {
            // Lista diferencial sin la notacion punto
            $arrDiffUndot = UtilController::arrayUndot($arrDiff);
            // Agrega ensayos de trabajo asignados
            $input['lc_list_trials'] = array_key_exists('lc_list_trials', $arrDiffUndot) ?
            json_encode($sampleTaking->lcListTrials->toArray()) : json_encode($oldSampleTaking->lcListTrials->toArray());
        }

        // $input['url_qr'] = substr($input['url_image']->store('public/citizen_poll/config/url_image'), 7);

        // //Valida si vinen ensayos quimicos para actualizar
        // if (empty($input['lc_list_trials_two'])) {
        //     dd("entro a eliminar 2");
        //     $sampleTaking->lcListTrialsTwo()->detach();
        // } else {
        //     // dd("entro a guardar 2");
        //     //Inserta relacion con los ensayos quimicos
        //     $sampleTaking->lcListTrialsTwo()->sync($input['lc_list_trials_two']);
        // }

        // //Ejecuta la relacion con los ensayos quimicos
        // $sampleTaking->lcListTrialsTwo;

        // //Prepara los datos que se ingresan para comparar
        // $arrDataSampleTaking = Arr::dot(UtilController::dropNullEmptyList($sampleTaking->replicate()->toArray(), true,'lc_list_trials_two')); // Datos actuales de usuario
        // $arrDataOldSampleTaking = Arr::dot(UtilController::dropNullEmptyList($oldSampleTaking->replicate()->toArray(), true,'lc_list_trials_two')); // Datos antiguos de usuario

        // $arrDiff = array_diff_assoc($arrDataSampleTaking, $arrDataOldSampleTaking);

        // // Valida si los datos antiguos son diferentes a los actuales
        // if ( count($arrDiff) > 0) {
        //     // Lista diferencial sin la notacion punto
        //     $arrDiffUndot = UtilController::arrayUndot($arrDiff);
        //     // Agrega ensayos de trabajo asignados
        //     $input['lc_list_trials_two'] = array_key_exists('lc_list_trials_two', $arrDiffUndot) ?
        //         json_encode($sampleTaking->lcListTrialsTwo->toArray()) : json_encode($oldSampleTaking->lcListTrialsTwo->toArray());
        // }

        return $this->sendResponse($sampleTaking->toArray(), trans('msg_success_update'));

    }

    /**
     * Obtiene los ensayos fisicos
     *
     * @author Josè Manuel Marìn Londoño. - Enero. 05 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getListTrialsPhysicists(Request $request)
    {
        $listTrials = ListTrials::all();
        return $this->sendResponse($listTrials, trans('data_obtained_successfully'));
    }

    /**
     * Migracion de las muestras de la toma
     *
     * @author José Manuel Marín Londoño. - Diciembre. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function MigrateSampleExcel(Request $request)
    {

        try {
            $user = Auth::user();
            $input = $request->toArray();
            $successfulRegistrations = 0;
            $failedRegistrations = 0;
            $storedRecords = [];
            if ($request->hasFile('file_import_sample')) {
                $data = Excel::toArray(new SampleTaking, $input["file_import_sample"]);
                $contArray = count($data[0][0]);
                unset($data[0][0]);
                // dd($contArray);

                if ($contArray == 32) {
                    foreach ($data[0] as $row) {
                        try {
                            if ($row[13] == 'No') {
                                $row[13] = null;
                            } else {
                                $row[13] = 'true';
                            }
                            if ($row[14] == 'No') {
                                $row[14] = null;
                            } else {
                                $row[14] = 'true';
                            }
                            if ($row[15] == 'No') {
                                $row[15] = null;
                            } else {
                                $row[15] = 'true';
                            }
                            if ($row[16] == 'No') {
                                $row[16] = null;
                            } else {
                                $row[16] = 'true';
                            }
                            if ($row[17] == 'No') {
                                $row[17] = null;
                            } else {
                                $row[17] = 'true';
                            }
                            if ($row[18] == 'No') {
                                $row[18] = null;
                            } else {
                                $row[18] = 'true';
                            }
                            if ($row[19] == 'No') {
                                $row[19] = null;
                            } else {
                                $row[19] = 'true';
                            }
                            if ($row[20] == 'No') {
                                $row[20] = null;
                            } else {
                                $row[20] = 'true';
                            }
                            if (!empty($row[0])) {
                                $samplePoints = SamplePoints::where("point_location", $row[0])->first();
                                $row[0] = $samplePoints['id'];
                            }
                            if ($row[0] != null || $row[1] != null || $row[2] != null || $row[3] != null || $row[4] != null || $row[5] != null || $row[6] != null || $row[7] != null || $row[8] != null || $row[9] != null || $row[10] != null || $row[11] != null || $row[12] != null) {

                                $register = SampleTaking::create([
                                    'charge' => "Laboratorio de Ensayo de Calidad Agua",
                                    'process' => "Toma de Muestra",
                                    'users_id' => Auth::user()->id,
                                    'user_name' => Auth::user()->name,
                                    'lc_start_sampling_id' => $input['lc_start_sampling_id'],
                                    'lc_sample_points_id' => $row[0],
                                    'sample_reception_code' => $row[1],
                                    'type_water' => $row[2],
                                    'humidity' => $row[3],
                                    'temperature' => $row[4],
                                    'hour_from_to' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])->format('H:i:s'),
                                    'prevailing_climatic_characteristics' => $row[6],
                                    'test_perform' => $row[7],
                                    'container_number' => $row[8],
                                    'hour' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9])->format('H:i:s'),
                                    'according' => $row[10],
                                    'sample_characteristics' => $row[11],
                                    'observations' => $row[12],
                                    'refrigeration' => $row[13],
                                    'hci' => $row[14],
                                    'filtered_sample' => $row[15],
                                    'naoh' => $row[16],
                                    'hno3' => $row[17],
                                    'acetate' => $row[18],
                                    'h2so4' => $row[19],
                                    'ascorbic_acid' => $row[20],
                                ]);
                                //Ejecuta el modelo de puntos de muestra
                                $register->lcSamplePoints;
                                //Ejecuta el modelo de users
                                $register->users;
                                if (!empty($row[21])) {
                                    $arrayToString = json_encode($row[21]);
                                    $stringSearch = '.';
                                    $position = strrpos($arrayToString, $stringSearch);
                                    //se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
                                    if ($position === false) {
                                        $trials = explode(",", $row[21]);
                                    } else {
                                        $trials = explode(".", $row[21]);
                                    }
                                    //Ciclo que guarda los ensayos
                                    foreach ($trials as $option) {
                                        SampleTakingHasListTrials::create([
                                            'lc_sample_taking_id' => $register->id,
                                            'lc_list_trials_id' => $option,
                                        ]);
                                    }
                                    $register->lcListTrials;
                                }
                                if (!empty($row[22] && $row[23])) {
                                    $phOne = explode(";", $row[22]);
                                    $t1One = explode(";", $row[23]);
                                    $increase = count($phOne);
                                    for ($i = 0; $i < $increase; $i++) {
                                        $phOneList = new DynamicPhOneList();
                                        $phOneList->lc_sample_taking_id = $register->id;
                                        $phOneList->ph_unit = $phOne[$i];
                                        $phOneList->t1 = $t1One[$i];
                                        $phOneList->save();
                                    }
                                    //Ejecuta el modelo de ph one
                                    $register->lcDynamicPhOneLists;
                                }
                                if (!empty($row[24] && $row[25])) {
                                    $phTwo = explode(";", $row[24]);
                                    $t2Two = explode(";", $row[25]);
                                    $increaseTwo = count($phTwo);
                                    for ($i = 0; $i < $increaseTwo; $i++) {
                                        $phTwoList = new DynamicPhTwoList();
                                        $phTwoList->lc_sample_taking_id = $register->id;
                                        $phTwoList->ph_unit = $phTwo[$i];
                                        $phTwoList->t2 = $t2Two[$i];
                                        $phTwoList->save();
                                    }
                                    //Ejecuta el modelo de ph two
                                    $register->lcDynamicPhTwoLists;
                                }
                                if (!empty($row[26] && $row[27])) {
                                    $phList = explode(";", $row[26]);
                                    $temperatureRange = explode(";", $row[27]);
                                    $increaseTwo = count($phList);
                                    for ($i = 0; $i < $increaseTwo; $i++) {
                                        $listPh = new DynamicPhList();
                                        $listPh->lc_sample_taking_id = $register->id;
                                        $listPh->ph_unit = $phList[$i];
                                        $listPh->temperature_range = $temperatureRange[$i];
                                        $listPh->save();
                                    }
                                    //Ejecuta el modelo de ph
                                    $register->lcDynamicPhLists;
                                }
                                if (!empty($row[28] && $row[29] && $row[30] && $row[31])) {
                                    $vSample = explode(";", $row[28]);
                                    $clorineResidual = explode(";", $row[29]);
                                    $mgCl2 = explode(";", $row[30]);
                                    $increaseResidualClorine = count($vSample);
                                    for ($i = 0; $i < $increaseResidualClorine; $i++) {
                                        $clorineList = new ResidualChlorineList();
                                        $clorineList->lc_sample_taking_id = $register->id;
                                        $clorineList->v_sample = $vSample[$i];
                                        $clorineList->chlorine_residual_test = $clorineResidual[$i];
                                        $clorineList->mg_cl2 = $mgCl2[$i];
                                        $clorineList->save();
                                    }
                                    //Ejecuta el modelo de cloro residual
                                    $register->lcResidualChlorineLists;
                                }
                                //Carácteres para texto aleatorio
                                $chain = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
                                $chain .= '0123456789';
                                //Se obtiene la longitud de la cadena de caracteres
                                $chainLength = strlen($chain);
                                //Se define la varibale que va a tener el texto aleatorio
                                $randomText = "";
                                //Se define la longitud de el texto aleatorio, en este caso son 10, pero se pueden poner lo que se necesite
                                $lengthRandomText = 60;

                                //Se empieza a crear el texto aleatorio
                                for ($i = 1; $i <= $lengthRandomText; $i++) {
                                    //Definimos un numero aleatorio entre 0 y la longitud de la cadena de caracteres -1
                                    $position = rand(0, $chainLength - 1);
                                    //obtenemos un caracter aleatorio escogido de la cadena de caracteres
                                    $randomText .= substr($chain, $position, 1);
                                }

                                $IdQr = $register->id;
                                $input['url_qr'] = 'leca/codes_qr/url_qr' . $randomText . $IdQr . '.png';

                                QrCode::format('png')->size(1500)->merge(public_path('leca_qr.png'), .2, true)->generate(config('app.url') . '/leca/public-sample?lc_qr=' . $IdQr, '../public/storage/leca/codes_qr/url_qr' . $randomText . $IdQr . '.png');

                                $register = $this->sampleTakingRepository->update($input, $IdQr);
                                $storedRecords[] = $register->toArray();
                                //Ejecuta el modelo de puntos de muestra
                                $register->lcSamplePoints;
                                //Ejecuta el modelo de users
                                $register->users;
                                //Ejecuta el modelo de ph one
                                $register->lcDynamicPhOneLists;
                                //Ejecuta el modelo de ph two
                                $register->lcDynamicPhTwoLists;
                                //Ejecuta el modelo de ph
                                $register->lcDynamicPhLists;
                                //Ejecuta el modelo de cloro residual
                                $register->lcResidualChlorineLists;
                                //Ejecuta el modelo con los ensayos
                                $register->lcListTrials;
                                //Ejecuta el modelo con los ensayos dos
                                $register->lcListTrialsTwo;

                                $successfulRegistrations++;
                            }
                        } catch (\Illuminate\Database\QueryException $error) {
                            $failedRegistrations++;
                        }
                    }
                } else {
                    return $this->sendError('Error,por favor verifique que el número de columnas con datos en el excel coincida con el número de columnas del formulario de importación de actividades', []);
                }
            }
            return $this->sendResponse($register, trans('msg_success_save') . "<br /><br />Registros exitosos: $successfulRegistrations<br />Registros fallidos: $failedRegistrations");
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Obtiene toda la informacion de la programacion
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformationProgramming($id)
    {

        $programacion = SamplingSchedule::find($id);

        return $this->sendResponse($programacion->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene toda la informacion del cloro residual
     *
     * @author Nicolas Dario Ortiz Peña. - Mayo. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformationCloruro($id)
    {
        $cloro = StartSampling::find($id);

        return $this->sendResponse($cloro->toArray(), trans('data_obtained_successfully'));
    }
}
