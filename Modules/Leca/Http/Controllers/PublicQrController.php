<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateSampleTakingRequest;
use Modules\Leca\Http\Requests\UpdateSampleTakingRequest;
use Modules\Leca\Repositories\SampleTakingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\SamplePoints;
use Modules\Leca\Models\DynamicPhOneList;
use SimpleSoftwareIO\QrCode\Generator;
use Modules\Leca\Models\DynamicPhTwoList;
use Modules\Leca\Models\SampleTakingHasListTrials;
use Modules\Leca\Models\DynamicPhList;
use Modules\Leca\Models\ResidualChlorineList;
use App\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Modules\Leca\Models\ListTrials;
use Illuminate\Support\Arr;
use Flash;
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
class PublicQrController extends AppBaseController {

    /** @var  SampleTakingRepository */
    private $sampleTakingRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SampleTakingRepository $sampleTakingRepo) {
        $this->sampleTakingRepository = $sampleTakingRepo;
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
    public function index(Request $request) {     
        //Recupera los datos de la muestra   
        $sample = SampleTaking::where("id", $request['lc_qr'] ?? null)->first();
        //Consulta los ensayos que solo sean fisicos
        $physicalTests = ListTrials::select('id')->where('type', 'Fisico')->get()->toArray();
        //Consulta los ensayos que solo sean quimicos
        $chemicalTesting = ListTrials::select('id')->where('type', 'Químicos')->get()->toArray();
        //Consulta los ensayos que solo sean microbiologicos
        $microbiologicalTesting = ListTrials::select('id')->where('type', 'Microbiológicos')->get()->toArray();
        //Consulta los ensayos que tiene relaiconados la muestra
        $sampleTests = SampleTakingHasListTrials::select('lc_list_trials_id')->where('lc_sample_taking_id', $sample->id)->get()->toArray();
        //Consulta los datos del usuario que realizo la muestra
        $users = User::where("id", $sample->users_id)->first()->toArray();
        //Obtiene la firma del usuario que realizo la muestra
        $signatureUsers = $users['url_digital_signature'];
        //Inicializa el array de los ensayos fisicos
        $arrayPhysical = [];
        //Inicializa el array de los ensayos quimicos
        $arrayChemical = [];
        //Inicializa el array para los ensayos fisicos que tiene la muestra
        $arraySamplePhysical = [];
        //Inicializa el array para los ensayos microbiologicos
        $arrayMicrobiological = [];
        //Inicializa el array para los ensayos fisicos finales
        $physicals = [];
        //Inicialoza el array para los ensayos quimicos finales
        $chemicals = [];
        //Inicializa el array para los ensayos microbilogicos finales
        $microbiological = [];
        //Recorre los ensayos que tiene la muestra
        foreach($sampleTests as $data){
            //Convierte el array en una cadena
            $onePhysicalSample = json_encode($data);
            //Separa la cadena por los dos puntos
            $twoPhysicalSample = explode(":", $onePhysicalSample);
            //Separa la cadena por el corchete en la posicion 1
            $threePhysicalSample = explode("}", $twoPhysicalSample[1]);
            //Solo toma el resultado de la posicion 0
            $fourPhysicalSample = $threePhysicalSample[0];
            //Le asgina el valor a una variable que lo va a convertir en unn array
            $fivePhysicalSample = json_decode($fourPhysicalSample);
            //Agrega un elemento a una posicion del array
            array_push($arraySamplePhysical, $fivePhysicalSample);
        }
        
        //Recorre todos los ensayos fisicos que existen
        foreach($physicalTests as $option){
            //Convierte el array en una cadena
            $onePhysical = json_encode($option);
            //Separa la cedena por los dos puntos
            $twoPhysical = explode(":", $onePhysical);
            //Separa la cadena por el corchete en la posicion 1
            $threePhysical = explode("}", $twoPhysical[1]);
            //Solo toma el resultado de la posicion 0
            $fourPhysical = $threePhysical[0];
            //Le asigna el valor a una variable que lo va a convertir en un array
            $fivePhysical = json_decode($fourPhysical);
            //Agrega un elemento a una posicion del array
            array_push($arrayPhysical, $fivePhysical);
        }

        //Recorre solo los ensayosque tiene relacionados la muestra
        foreach($arraySamplePhysical as $validate){
            //Valida si el id de los estados fisicos estan dentro del array de los estados fisicos que existen
            if (in_array($validate, Arr::pluck($physicalTests, 'id')) == true) {
                //Agrega un nuevo elemento a una posicion del array
                array_push($physicals, $validate);
            }
        }
        if($physicals == []){
            $finalPhysicals = "N/A";
        } else {
            //Convierte el resultado en una cadena
            $finalPhysicals = json_encode($physicals);
        }

        //Recorre todos los ensayos fisicos que existen
        foreach($chemicalTesting as $option){
            //Convierte el array en una cadena
            $oneChemical = json_encode($option);
            //Separa la cedena por los dos puntos
            $twoChemical = explode(":", $oneChemical);
            //Separa la cadena por el corchete en la posicion 1
            $threeChemical = explode("}", $twoChemical[1]);
            //Solo toma el resultado de la posicion 0
            $fourChemical = $threeChemical[0];
            //Le asigna el valor a una variable que lo va a convertir en un array
            $fiveChemical = json_decode($fourChemical);
            //Agrega un elemento a una posicion del array
            array_push($arrayChemical, $fiveChemical);
        }
        //Recorre solo los ensayosque tiene relacionados la muestra
        foreach($arraySamplePhysical as $validate){
            //Valida si el id de los estados quimicos estan dentro del array de los estados quimicos que existen
            if (in_array($validate, Arr::pluck($chemicalTesting, 'id')) == true) {
                //Agrega un nuevo elemento a una posicion del array
                array_push($chemicals, $validate);
            }
        }
        if($chemicals == []){
            $finalChemicals = "N/A";
        } else {
            //Convierte el resultado en una cadena
            $finalChemicals = json_encode($chemicals);
        }

        //Recorre todos los ensayos fisicos que existen
        foreach($microbiologicalTesting as $option){
            //Convierte el array en una cadena
            $oneMicrobiological = json_encode($option);
            //Separa la cedena por los dos puntos
            $twoMicrobiological = explode(":", $oneMicrobiological);
            //Separa la cadena por el corchete en la posicion 1
            $threeMicrobiological = explode("}", $twoMicrobiological[1]);
            //Solo toma el resultado de la posicion 0
            $fourMicrobiological = $threeMicrobiological[0];
            //Le asigna el valor a una variable que lo va a convertir en un array
            $fiveMicrobiological = json_decode($fourMicrobiological);
            //Agrega un elemento a una posicion del array
            array_push($arrayMicrobiological, $fiveMicrobiological);
        }
        //Recorre solo los ensayosque tiene relacionados la muestra
        foreach($arraySamplePhysical as $validate){
            //Valida si el id de los estados quimicos estan dentro del array de los estados quimicos que existen
            if (in_array($validate, Arr::pluck($microbiologicalTesting, 'id')) == true) {
                //Agrega un nuevo elemento a una posicion del array
                array_push($microbiological, $validate);
            }
        }
        if($microbiological == []){
            $finalMicrobiological = "N/A";
        } else {
            //Convierte el resultado en una cadena
            $finalMicrobiological = json_encode($microbiological);
        }
        //Envia el nombre del usuario que realizo la muestra
        $userName = $sample->user_name;
        //Envia el proceso del usuario que realizo la muestra
        $process = $sample->process;
        //Envia el codigo de la muestra 
        $codeSAmple = $sample->sample_reception_code;
        //Envia el cargo del usuario que realizo la muestra
        $charge = $sample->charge;
        //Valida si se checkeo el acido ascorbico
        if ($sample->ascorbic_acid == "true"){
            $ascorbic_acid = "X";
        } else {
            $ascorbic_acid = "⠀";
        }
        //Valida si se checkeo el acetato
        if ($sample->acetate == "true"){
            $acetate = "X";
        } else {
            $acetate = "⠀";
        }
        //Valida si se chekeo el naoh
        if ($sample->naoh == "true"){
            $naoh = "X";
        } else {
            $naoh = "⠀";
        }
        //Valida si se chekeo el hci
        if ($sample->hci == "true"){
            $hci = "X";
        } else {
            $hci = "⠀";
        }
        //Valida si se checkeo el h2so4
        if ($sample->h2so4 == "true"){
            $h2so4 = "X";
        } else {
            $h2so4 = "⠀";
        }
        //Valida si se checkeo el HNO3
        if ($sample->hno3 == "true"){
            $hno3 = "X";
        } else {
            $hno3 = "⠀";
        }
        //Valida si se Checkeo la muestra filtrada
        if ($sample->filtered_sample == "true"){
            $filteredSample = "X";
        } else {
            $filteredSample = "⠀";
        }
        //Valida si se chekeo la refrigeracion
        if ($sample->refrigeration == "true"){
            $refrigeration = "X";
        } else {
            $refrigeration = "⠀";
        }
        //Valida si el tipo de agua es cruda
        if($sample->type_water == "Cruda"){
            $rawWaterNew = "X";
        } else {
            $rawWaterNew = "⠀";
        }
        //Valida si el tipo de agua es tratada
        if ($sample->type_water == "Tratada"){
            $treatedWaterNew = "X";
        } else {
            $treatedWaterNew = "⠀";
        }
        //Valida si el tipo de agua es de proceso
        if ($sample->type_water == "De proceso"){
            $processWaterNew = "X";
        } else {
            $processWaterNew = "⠀";
        }
        //Obtiene el dato de la fecha y hora en la que el registro se creo
        $date = $sample->hour_from_to;
        //Se usa la funcion strtotime para convertir en un formato de fecha Unix para poder obtener los datos que se necesitan
        $entireDate = strtotime($date);
        //Obtiene la hora de la fecha entera
        $hour = date("H", $entireDate);
        //Obtiene los minutos de la fecha entera
        $minute = date("i", $entireDate);
        //Concatena la hora y los minutos en una variable de  hora de la muestra
        $hourSample = $hour.':'.$minute;
        //Obtiene el año de la fecha entera
        $year = date("y", $entireDate);
        //Obtiene el mes de la fecha entera
        $month = date("m", $entireDate);
        //Obtiene el dia de la fecha entera
        $day = date("d", $entireDate);
        //Retorna todas las variables a la vista index del qr para asi poder recibirlas en el archivo blade
        return view('leca::sample_takings.index_public',compact(['year','month','day','hourSample','treatedWaterNew','rawWaterNew','processWaterNew','refrigeration','filteredSample','hno3','h2so4','hci','naoh','acetate','ascorbic_acid','charge','codeSAmple','process','userName','finalPhysicals','finalChemicals','finalMicrobiological','signatureUsers']))->with("lc_qr", $request['lc_qr'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author José Manuel Marín Londoño. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        //Obtiene las relaciones que se necesitan para obtener datos en la vista del qr
        $sample_takings = SampleTaking::with(['lcStartSampling','lcSamplePoints','users','lcDynamicPhOneLists','lcDynamicPhTwoLists','lcDynamicPhLists','lcResidualChlorineLists','lcListTrials','lcListTrialsTwo'])->where('lc_start_sampling_id', $request['lc_start_sampling_id'])->latest()->get();
        return $this->sendResponse($sample_takings->toArray(), trans('data_obtained_successfully'));
    }


}