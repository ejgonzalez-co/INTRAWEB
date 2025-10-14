<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Http\Requests\CreateStartSamplingRequest;
use Modules\Leca\Http\Requests\UpdateStartSamplingRequest;
use Modules\Leca\Models\ChlorineResidualStandard;
use Modules\Leca\Models\HistoryStartSampling;
use Modules\Leca\Models\PatronNtu;
use Modules\Leca\Models\SampleTaking;
use Modules\Leca\Models\SamplingSchedule;
use Modules\Leca\Models\StartSampling;
use Modules\Leca\Repositories\StartSamplingRepository;
use App\User;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class StartSamplingController extends AppBaseController
{

    /** @var  StartSamplingRepository */
    private $startSamplingRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(StartSamplingRepository $startSamplingRepo)
    {
        $this->startSamplingRepository = $startSamplingRepo;
    }

    /**
     * Muestra la vista para el CRUD de StartSampling.
     *
     * @author José Manuel Marín Londoño. - Enero. 03 - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('leca::start_samplings.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {
        $start_samplings = StartSampling::with(['lcChlorineResidualStandards', 'historyStartSamplings', 'lcPatronNtu', 'usersA'])->latest()->get();
        return $this->sendResponse($start_samplings->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateStartSamplingRequest $request
     *
     * @return Response
     */
    public function store(CreateStartSamplingRequest $request)
    {
        //Obtiene los datos de usuarios que esta en sesion
        $user = Auth::user();
        $input = $request->toArray();

        // Inicia la transaccion
        DB::beginTransaction();
        //Valida si se ingresa una referencia diferente a la que tiene el equipo
        if (empty($input['reference_thermohygrometer'])) {
            $input['reference_thermohygrometer'] = "138";
        } else {
            $input['reference_thermohygrometer'] = "" . $input['reference_thermohygrometer'];
        }
        //Valida si se ingresa una referencia diferente a la que tiene el equipo
        if (empty($input['reference_multiparameter'])) {
            $input['reference_multiparameter'] = "145";
        } else {
            $input['reference_multiparameter'] = "" . $input['reference_multiparameter'];
        }
        //Valida si se ingresa una referencia diferente a la que tiene el equipo
        if (empty($input['reference_photometer'])) {
            $input['reference_photometer'] = "049";
        } else {
            $input['reference_photometer'] = "" . $input['reference_photometer'];
        }
        //Valida si se ingresa una referencia diferente a la que tiene el equipo
        if (empty($input['reference_turbidimeter'])) {
            $input['reference_turbidimeter'] = "112";
        } else {
            $input['reference_turbidimeter'] = "" . $input['reference_turbidimeter'];
        }
        try {
            if (array_key_exists('environmental_conditions', $input)) {
                if ($input['environmental_conditions'] == 'false') {
                    $input['environmental_conditions'] = null;
                    $input['reference_thermohygrometer'] = null;
                }
            }

            if (array_key_exists('potentiometer_multiparameter', $input)) {
                if ($input['potentiometer_multiparameter'] == 'false') {
                    $input['potentiometer_multiparameter'] = null;
                    $input['reference_multiparameter'] = null;
                }
            }

            if (array_key_exists('chlorine_residual', $input)) {
                if ($input['chlorine_residual'] == 'false') {
                    $input['chlorine_residual'] = null;
                    $input['reference_photometer'] = null;
                }
            }

            if (array_key_exists('turbidimeter', $input)) {
                if ($input['turbidimeter'] == 'false') {
                    $input['turbidimeter'] = null;
                    $input['reference_turbidimeter'] = null;
                }
            }
            $input['users_id'] = Auth::user()->id;
            $input['user_name'] = Auth::user()->name;
            // Inserta el registro en la base de datos
            $startSampling = $this->startSamplingRepository->create($input);
            $startSampling->users;

            //crea un objeto de tipo HistoryStartSampling y lo guarda en la base de datos
            $history = new HistoryStartSampling();
            $history->lc_start_sampling_id = $startSampling->id;
            $history->users_id = $startSampling->users_id;
            $history->observation = "Se creo un inicio de toma";
            $history->user_name = $startSampling->users->name;
            $history->action = "Creacion";

            $history->save();
            $startSampling->historyStartSamplings;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($startSampling->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateStartSamplingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStartSamplingRequest $request)
    {

        $input = $request->all();

        /** @var StartSampling $startSampling */
        $startSampling = $this->startSamplingRepository->find($id);

        if (empty($startSampling)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Valida si se ingresa una referencia diferente a la que tiene el equipo
            if (empty($input['reference_thermohygrometer'])) {
                $input['reference_thermohygrometer'] = "138";
            } else {
                $input['reference_thermohygrometer'] = $input['reference_thermohygrometer'];
            }
            //Valida si se ingresa una referencia diferente a la que tiene el equipo
            if (empty($input['reference_multiparameter'])) {
                $input['reference_multiparameter'] = "138";
            } else {
                $input['reference_multiparameter'] = $input['reference_multiparameter'];
            }
            //Valida si se ingresa una referencia diferente a la que tiene el equipo
            if (empty($input['reference_photometer'])) {
                $input['reference_photometer'] = "138";
            } else {
                $input['reference_photometer'] = $input['reference_photometer'];
            }

            //Valida si se ingresa una referencia diferente a la que tiene el equipo
            if (empty($input['reference_turbidimeter'])) {
                $input['reference_turbidimeter'] = "138";
            } else {
                $input['reference_turbidimeter'] = $input['reference_turbidimeter'];
            }

            if (array_key_exists('environmental_conditions', $input)) {
                if ($input['environmental_conditions'] == 'false') {
                    $input['environmental_conditions'] = null;
                    $input['reference_thermohygrometer'] = null;
                }
            } else {
                $input['reference_thermohygrometer'] = null;
            }
            if (array_key_exists('potentiometer_multiparameter', $input)) {
                if ($input['potentiometer_multiparameter'] == 'false') {
                    $input['potentiometer_multiparameter'] = null;
                    $input['reference_multiparameter'] = null;
                }
            } else {
                $input['reference_multiparameter'] = null;
            }

            if (array_key_exists('chlorine_residual', $input)) {
                if ($input['chlorine_residual'] == 'false') {
                    $input['chlorine_residual'] = null;
                    $input['reference_photometer'] = null;
                }
            } else {
                $input['reference_photometer'] = null;
            }

            if (array_key_exists('turbidimeter', $input)) {
                if ($input['turbidimeter'] == 'false') {
                    $input['turbidimeter'] = null;
                    $input['reference_turbidimeter'] = null;
                }
            } else {
                $input['reference_turbidimeter'] = null;
            }
            // Actualiza el registro

            $startSampling = $this->startSamplingRepository->update($input, $id);

            //crea un objeto de tipo HistoryStartSampling y lo guarda en la base de datos
            $history = new HistoryStartSampling();
            $history->lc_start_sampling_id = $startSampling->id;
            $history->users_id = $startSampling->users_id;
            $history->observation = "Se Actualizo datos del inicio de la toma";
            $history->user_name = $startSampling->users->name;
            $history->action = "Actualización";

            $history->save();
            $startSampling->historyStartSamplings;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($startSampling->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un StartSampling del almacenamiento
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

        /** @var StartSampling $startSampling */
        $startSampling = $this->startSamplingRepository->find($id);

        if (empty($startSampling)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $startSampling->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\StartSamplingController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('start_samplings') . '.' . $fileType;

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
     *Guarda los datos de la informacion final de la muestra
     *
     * @author José Manuel Marin Londoño. - Enero. 14 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function informationFinish(Request $request)
    {
        $input = $request->except(['created_at', 'updated_at', '_method']);

        //Recupera el id del registro de titulo para buscarlo y modificarlo
        $idInformation = $input['id'];
        $startSampling = StartSampling::find($idInformation);
        //Valida si el campo esta deshabilitado o no
        if (array_key_exists('digital_thermometer', $input)) {
            if ($input['digital_thermometer'] == 'false') {
                $input['digital_thermometer'] = null;
            }
        }
        //Valida si se ingresa una referencia diferente a la que tiene el equipo
        if (empty($input['which'])) {
            $input['which'] = "158";
        } else {
            $input['which'] = "" . $input['which'];
        }

        $arrayCloro = ChlorineResidualStandard::where('lc_start_sampling_id', $startSampling->id)->get();
        foreach ($arrayCloro as $key => $value) {
            $value->delete();
        }

        $arrayPatron = PatronNtu::where('lc_start_sampling_id', $startSampling->id)->get();
        foreach ($arrayPatron as $key => $value) {
            $value->delete();
        }

        if (!empty($input['lc_chlorine_residual_standards'])) {
            //Ciclo que guarda los ph one
            foreach ($input['lc_chlorine_residual_standards'] as $option) {
                $arrayChlorineResidual = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                ChlorineResidualStandard::create([
                    'chlorine_residual' => $arrayChlorineResidual->chlorine_residual,
                    'lc_start_sampling_id' => $startSampling->id,
                ]);
            }
        }

        if (!empty($input['lc_patron_ntu'])) {
            //Ciclo que guarda los ph one
            foreach ($input['lc_patron_ntu'] as $option) {
                $arrayPatron = json_decode($option);
                // Se crean la cantidad de registros ingresados por el usuario
                PatronNtu::create([
                    'patron' => $arrayPatron->patron,
                    'lc_start_sampling_id' => $startSampling->id,
                ]);
            }
        }
        $ntuPatron = [];
        if (!empty($input['lc_patron_ntu'])) {
            foreach ($input['lc_patron_ntu'] as $option) {
                $arrayPatron = json_decode($option);
                array_push($ntuPatron, $arrayPatron->patron);

            }
        }
        if (!empty($input['initial_pattern'])) {
            $input['initial_pattern'] = $ntuPatron[0];
        }

        //Actualiza el registro
        $startSampling = $this->startSamplingRepository->update($input, $idInformation);

        //crea un objeto de tipo HistoryStartSampling y lo guarda en la base de datos
        $history = new HistoryStartSampling();
        $history->lc_start_sampling_id = $startSampling->id;
        $history->users_id = $startSampling->users_id;
        $history->observation = "Se actualizo la información final de la toma";
        $history->user_name = $startSampling->users->name;
        $history->action = "Actualización";

        $history->save();
        $startSampling->historyStartSamplings;
        $startSampling->lcChlorineResidualStandards;
        $startSampling->lcPatronNtu;

        return $this->sendResponse($startSampling->toArray(), trans('msg_success_update'));

    }

    /**
     * Visualiza el pdf
     *
     * @author José Manuel Marín Londoño. - Diciembre. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function showPdf($id)
    {
        return $this->pdfExport($id)->stream('archivo.pdf');
    }

    /**
     * Abre la vista con el pdf
     *
     * @author José Manuel Marín Londoño. - Diciembre. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function pdfExport($id)
    {
        try {
            //Busca la consantancia por medio del id
            $start_samplings = StartSampling::find($id);
            if (!empty($start_samplings->name_delivery_conformity)) {
                $usersConformi = User::where("id", $start_samplings->name_delivery_conformity)->first()->toArray();
            } else {
                $usersConformi = '';
            }
            $users = User::where("id", $start_samplings->users_id)->first()->toArray();
            $signatureUsers = isset($users['url_digital_signature']) ? $users['url_digital_signature'] : 'Firma no encontrada';
            $datePH = $start_samplings->date_last_ph_adjustment;
            //Separa la cadena por el -
            if (!empty($usersConformi)) {
                // $datePHFinish = 'N/A';
                $datePHFinish = explode("-", $datePH);
            } else {
                $datePHFinish = 'N/A';
                // $datePHFinish = explode("-", $datePH);
            }

            if (!empty($start_samplings->date_last_ph_adjustment)) {
                $dateDay = explode(" ", $datePHFinish[2]);
            } else {
                $dateDay = '';
            }
            //Separa el dia
            $muestra = $start_samplings->which;
            //Valida si si hay un termometro digital
            if (!empty($start_samplings->digital_thermometer)) {
                $digitalThermometer = $start_samplings->which;
            } else {
                $digitalThermometer = "NO";
            }
            $date = $start_samplings->created_at->toArray();
            //Obtiene el nombre de la persona responsable
            $usersResposible = User::where("id", $start_samplings->name_person_responsible)->first()->toArray();
            //Consulta el patron de cloro residual
            $clorineResidual = ChlorineResidualStandard::select('chlorine_residual')->where('lc_start_sampling_id', $id)->get()->toArray();
            //Inicializa el array
            $arrayResidualClorines = [];
            //Inicializa el array
            $residualClorine = [];
            //Recorre el cloro residual que tiene la muestra
            foreach ($clorineResidual as $data) {
                //Convierte el array en una cadena
                $oneClorineResidual = json_encode($data);
                //Separa la cadena por los dos puntos
                $twoClorineResidual = explode(":", $oneClorineResidual);
                //Separa la cadena por el corchete en la posicion 1
                $threeClorineResidual = explode("}", $twoClorineResidual[1]);
                //Solo toma el resultado de la posicion 0
                $fourClorineResidual = $threeClorineResidual[0];
                //Le asgina el valor a una variable que lo va a convertir en unn array
                $fiveClorineResidual = json_decode($fourClorineResidual);
                //Agrega un elemento a una posicion del array
                array_push($arrayResidualClorines, $fiveClorineResidual);
            }
            //Recorre solo los ensayosque tiene relacionados la muestra
            foreach ($arrayResidualClorines as $validate) {
                //Agrega un nuevo elemento a una posicion del array
                array_push($residualClorine, $validate);
            }
            if ($residualClorine == []) {
                $finalClorineResidual = "N/A";
            } else {
                //Convierte el resultado en una cadena
                $finalClorineResidual = json_encode($residualClorine);
            }
            //Consulta que se trae todas las muestras relacionadas a esta toma
            $samplesTaking = SampleTaking::where("lc_start_sampling_id", $id)->get()->toArray();
            //abre el pdf que se genera por medio del archivo html
            $pdf = PDF::loadView('leca::exports.' . 'start_sample_pdf', [
                'start_samplings' => $start_samplings,
                'date' => $date,
                'signatureUsers' => $signatureUsers,
                'datePHFinish' => $datePHFinish,
                'dateDay' => $dateDay,
                'digitalThermometer' => $digitalThermometer,
                'usersResposible' => $usersResposible,
                'usersConformi' => $usersConformi,
                'finalClorineResidual' => $finalClorineResidual,
                'samplesTaking' => $samplesTaking,
            ])->setPaper('a4', 'landscape');

            return $pdf;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Migracion de inicio de toma
     *
     * @author José Manuel Marín Londoño. - Diciembre. 17 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function MigrateExcel(Request $request)
    {

        try {
            $user = Auth::user();
            $input = $request->toArray();
            $successfulRegistrations = 0;
            $failedRegistrations = 0;
            $storedRecords = [];
            if ($request->hasFile('file_import')) {
                $data = Excel::toArray(new StartSampling, $input["file_import"]);
                $contArray = count($data[0][0]);
                unset($data[0][0]);

                if ($contArray == 34) {
                    foreach ($data[0] as $row) {
                        try {
                            if ($row[6] == 'No') {
                                $row[6] = null;
                            }
                            if ($row[7] == 'No') {
                                $row[7] = null;
                            }
                            if ($row[8] == 'No') {
                                $row[8] = null;
                            }
                            if ($row[9] == 'No') {
                                $row[9] = null;
                            }
                            if ($row[21] == 'No') {
                                $row[21] = null;
                            }
                            if (!empty($row[32])) {
                                //Obtiene el id del analista que ingresaron
                                $usersConformiMigrate = User::where("name", $row[32])->first();
                                $row[32] = $usersConformiMigrate['id'];
                            }

                            if ($row[0] != null || $row[1] != null || $row[2] != null || $row[3] != null || $row[4] != null || $row[5] != null || $row[6] != null || $row[7] != null || $row[8] != null || $row[9] != null || $row[10] != null || $row[11] != null || $row[12] != null || $row[13] != null || $row[14] != null || $row[15] != null || $row[16] != null || $row[17] != null || $row[18] != null || $row[19] != null || $row[20] != null || $row[21] != null || $row[22] != null || $row[23] != null || $row[24] != null || $row[25] != null || $row[26] != null || $row[27] != null || $row[28] != null || $row[29] != null || $row[30] != null || $row[31] != null || $row[32] != null) {

                                $register = StartSampling::create([
                                    'users_id' => Auth::user()->id,
                                    'user_name' => Auth::user()->name,
                                    'name' => Auth::user()->id,
                                    'name_person_responsible' => Auth::user()->id,
                                    'vehicle_arrival_time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0])->format('H:i:s'),
                                    'leca_outlet' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1])->format('H:i:s'),
                                    'time_sample_completion' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2])->format('H:i:s'),
                                    'service_agreement' => $row[3],
                                    'sample_request' => $row[4],
                                    'time' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5])->format('H:i:s'),
                                    'environmental_conditions' => $row[6],
                                    'potentiometer_multiparameter' => $row[7],
                                    'chlorine_residual' => $row[8],
                                    'turbidimeter' => $row[9],
                                    'another_test' => $row[10],
                                    'other_equipment' => $row[11],
                                    'chlorine_test' => $row[12],
                                    'factor' => $row[13],
                                    'residual_color' => $row[14],
                                    'media_and_DPR' => $row[15],
                                    'mean_chlorine_value' => $row[16],
                                    'DPR_chlorine_residual' => $row[17],
                                    'date_last_ph_adjustment' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[18])->format(''),
                                    'pending' => $row[19],
                                    'asymmetry' => $row[20],
                                    'digital_thermometer' => $row[21],
                                    'which' => $row[22],
                                    'arrival_LECA' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[23])->format('H:i:s'),
                                    'observations' => $row[24],
                                    'witness' => $row[25],
                                    'initial' => $row[26],
                                    'intermediate' => $row[27],
                                    'end' => $row[28],
                                    'standard_ph' => $row[29],
                                    'chlorine_residual_target' => $row[30],
                                    'initial_pattern' => $row[31],
                                    'name_delivery_conformity' => $row[32],
                                ]);
                                //Ejecuta el modelo de usuarios
                                $register->users;
                                if (!empty($row[33])) {
                                    $clorine = explode(";", $row[33]);
                                    //Ciclo que guarda el cloro residual
                                    foreach ($clorine as $option) {
                                        $arrayChlorineResidual = $option;
                                        // // Se crean la cantidad de registros ingresados por el usuario
                                        ChlorineResidualStandard::create([
                                            'chlorine_residual' => $arrayChlorineResidual,
                                            'lc_start_sampling_id' => $register->id,
                                        ]);
                                    }
                                }
                                //Ejecuta el modelo con el estandar de cloro residual
                                $register->lcChlorineResidualStandards;
                                //crea un objeto de tipo HistoryStartSampling y lo guarda en la base de datos
                                $history = new HistoryStartSampling();
                                $history->lc_start_sampling_id = $register->id;
                                $history->users_id = $register->users_id;
                                $history->observation = "Se creo un inicio de toma";
                                $history->user_name = $register->users->name;
                                $history->action = "Creacion";

                                $history->save();

                                $register->historyStartSamplings;

                                $storedRecords[] = $register->toArray();

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
     * Migracion de inicio de toma
     *
     * @author Nicolas Dario Ortiz Peña. - Julio. 06 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformationMuestra($id)
    {
        $consecutivo = '';
        $startSampling = SampleTaking::with('lcResidualChlorineLists')->where('lc_start_sampling_id', $id)->get();

        foreach ($startSampling as $key => $value) {
            if (count($value->lcResidualChlorineLists) >= 2) {
                $consecutivo = $value->sample_reception_code;

            }

        }
        return $this->sendResponse($consecutivo, trans('data_obtained_successfully'));
    }

    /**
     * Migracion de inicio de toma
     *
     * @author Nicolas Dario Ortiz Peña. - Julio. 06 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformationMuestraPromedio($id)
    {
        $consecutivo = '';
        $startSampling = SampleTaking::with('lcResidualChlorineLists')->where('lc_start_sampling_id', $id)->get();

        foreach ($startSampling as $key => $value) {
            if (count($value->lcResidualChlorineLists) >= 2) {
                $consecutivo = $value->cloro_promedio;

            }

        }
        return $this->sendResponse(floatval($consecutivo), trans('data_obtained_successfully'));
    }

    /**
     * Migracion de inicio de toma
     *
     * @author Nicolas Dario Ortiz Peña. - Julio. 06 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCloroDpr($id)
    {
        $total = 0;
        $consecutivo = '';
        $startSampling = SampleTaking::with('lcResidualChlorineLists')->where('lc_start_sampling_id', $id)->get();
        $array = [];

        foreach ($startSampling as $key => $value) {
            if (count($value->lcResidualChlorineLists) >= 2) {
                foreach ($value->lcResidualChlorineLists as $key => $item) {
                    if ($item->mg_cl2 > 0) {
                        $array[] = floatval($item->mg_cl2);
                    }
                }
            }
        }

        if (count($array) >= 2) {
            $total = ((($array[0] - $array[1])) * 1 / (($array[0] + $array[1]) / 2)) * 100;

            if ($total < 0) {
                $total = $total * -1;

            }

        }

        return $this->sendResponse(floatval($total), trans('data_obtained_successfully'));
    }

    /**
     * Exporta excel
     *
     * @author Nicolas Dario Ortiz Peña. - Enero. 04 - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function exporta($id)
    {

        try {
            //Busca la consantancia por medio del id
            $start_samplings = StartSampling::find($id);
            if (!empty($start_samplings->name_delivery_conformity)) {
                $usersConformi = User::where("id", $start_samplings->name_delivery_conformity)->first()->toArray();
            } else {
                $usersConformi = '';
            }
            $users = User::where("id", $start_samplings->users_id)->first()->toArray();
            $signatureUsers = $users['url_digital_signature'];
            $datePH = $start_samplings->date_last_ph_adjustment;
            //Separa la cadena por el -
            if (!empty($usersConformi)) {
                // $datePHFinish = 'N/A';
                $datePHFinish = explode("-", $datePH);
            } else {
                $datePHFinish = 'N/A';
                // $datePHFinish = explode("-", $datePH);
            }

            if (!empty($start_samplings->date_last_ph_adjustment)) {
                $dateDay = explode(" ", $datePHFinish[2]);
            } else {
                $dateDay = '';
            }
            //Separa el dia
            $muestra = $start_samplings->which;
            //Valida si si hay un termometro digital
            if (!empty($start_samplings->digital_thermometer)) {
                $digitalThermometer = $start_samplings->which;
            } else {
                $digitalThermometer = "NO";
            }
            $date = $start_samplings->created_at->toArray();
            //Obtiene el nombre de la persona responsable
            $usersResposible = User::where("id", $start_samplings->name_person_responsible)->first()->toArray();
            //Consulta el patron de cloro residual
            $clorineResidual = ChlorineResidualStandard::select('chlorine_residual')->where('lc_start_sampling_id', $id)->get()->toArray();
            //Inicializa el array
            $arrayResidualClorines = [];
            //Inicializa el array
            $residualClorine = [];
            //Recorre el cloro residual que tiene la muestra
            foreach ($clorineResidual as $data) {
                //Convierte el array en una cadena
                $oneClorineResidual = json_encode($data);
                //Separa la cadena por los dos puntos
                $twoClorineResidual = explode(":", $oneClorineResidual);
                //Separa la cadena por el corchete en la posicion 1
                $threeClorineResidual = explode("}", $twoClorineResidual[1]);
                //Solo toma el resultado de la posicion 0
                $fourClorineResidual = $threeClorineResidual[0];
                //Le asgina el valor a una variable que lo va a convertir en unn array
                $fiveClorineResidual = json_decode($fourClorineResidual);
                //Agrega un elemento a una posicion del array
                array_push($arrayResidualClorines, $fiveClorineResidual);
            }
            //Recorre solo los ensayosque tiene relacionados la muestra
            foreach ($arrayResidualClorines as $validate) {
                //Agrega un nuevo elemento a una posicion del array
                array_push($residualClorine, $validate);
            }
            if ($residualClorine == []) {
                $finalClorineResidual = "N/A";
            } else {
                //Convierte el resultado en una cadena
                $finalClorineResidual = json_encode($residualClorine);
            }
            //Consulta que se trae todas las muestras relacionadas a esta toma
            $samplesTaking = SampleTaking::where("lc_start_sampling_id", $id)->get()->toArray();
            //abre el pdf que se genera por medio del archivo html
            // $pdf = PDF::loadView('leca::exports.' . 'start_sample_pdf', [
            //     'start_samplings' => $start_samplings,
            //     'date' => $date,
            //     'signatureUsers' => $signatureUsers,
            //     'datePHFinish' => $datePHFinish,
            //     'dateDay' => $dateDay,
            //     'digitalThermometer' => $digitalThermometer,
            //     'usersResposible' => $usersResposible,
            //     'usersConformi' => $usersConformi,
            //     'finalClorineResidual' => $finalClorineResidual,
            //     'samplesTaking' => $samplesTaking,
            // ])->setPaper('a4', 'landscape');

            // return $pdf;
        } catch (\Exception $e) {
            dd($e);
        }
        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/leca/excel/Reporte de toma de muestra.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $user = Auth::user();
        $usersResponsablefinal = User::where("id", $start_samplings['name_person_responsible'])->first();
        //adquiere la firma del administrador
        if ($usersResponsablefinal->url_digital_signature) {
            //tratado para agregar la firma del usuario en sesión.
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath(storage_path('app/public/' . $usersResponsablefinal->url_digital_signature)); /* put your path and image here */
            $drawing->setCoordinates('T30');
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
            $drawing->setHeight(46);
            $drawing->setResizeProportional(true);
            $drawing->setOffsetX(150); // this is how
            $drawing->setOffsetY(2);
        }

        //Aqui va el nuevo
        //Aqui empieza lo de arriba
        if ($date['month'] <= 9) {
            $date['month'] = '0' . $date['month'];
        }

        $spreadsheet->getActiveSheet()->setCellValue('B10', substr($date['year'], -2));
        $spreadsheet->getActiveSheet()->setCellValue('C10', $date['month']);
        $spreadsheet->getActiveSheet()->setCellValue('D10', $date['day']);

        if ($start_samplings['type_customer'] == 'Captacion' || empty($start_samplings['vehicle_arrival_time']) || $start_samplings['vehicle_arrival_time'] == '00:00:00') {
            $start_samplings['vehicle_arrival_time'] = 'NA';
        }
        $spreadsheet->getActiveSheet()->setCellValue('I9', $start_samplings['vehicle_arrival_time']);
        if ($start_samplings['type_customer'] == 'Captacion' || empty($start_samplings['time_sample_completion']) || $start_samplings['time_sample_completion'] == '00:00:00' || $start_samplings['time_sample_completion'] == '000:00:00') {
            $start_samplings['time_sample_completion'] = 'NA';
        }
        $spreadsheet->getActiveSheet()->setCellValue('I10', $start_samplings['time_sample_completion']);

        $start_samplings['service_agreement'] = $start_samplings['service_agreement'] ? $start_samplings['service_agreement'] : 'NA';
        $spreadsheet->getActiveSheet()->setCellValue('P9', $start_samplings['service_agreement']);
        // DD( $start_samplings['service_agreement']);

        $spreadsheet->getActiveSheet()->setCellValue('U9', $start_samplings['sample_request'] ?: 'NA');
        $spreadsheet->getActiveSheet()->setCellValue('Y9', $start_samplings['time'] ?: 'NA');
        $spreadsheet->getActiveSheet()->setCellValue('AB9', 'NA');

        $spreadsheet->getActiveSheet()->setCellValue('AB11', 'Otro ensayo: ' . $start_samplings['another_test']);
        $spreadsheet->getActiveSheet()->setCellValue('AB12', 'Otro equipo: ' . $start_samplings['other_equipment']);
        if ($start_samplings['type_customer'] == 'Captacion') {
            $spreadsheet->getActiveSheet()->setCellValue('C13', 'EPA-LAB-EQ-N/A');
            $spreadsheet->getActiveSheet()->setCellValue('F13', 'EPA-LAB-EQ-N/A');
            $spreadsheet->getActiveSheet()->setCellValue('R13', 'EPA-LAB-EQ-N/A');
            $spreadsheet->getActiveSheet()->setCellValue('V13', 'EPA-LAB-EQ-N/A');
            $spreadsheet->getActiveSheet()->setCellValue('AB13', 'EPA-LAB-EQ-N/A');
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('C13', 'EPA-LAB-EQ-' . $start_samplings['reference_thermohygrometer']);
            $spreadsheet->getActiveSheet()->setCellValue('F13', 'EPA-LAB-EQ-' . $start_samplings['reference_multiparameter']);
            $spreadsheet->getActiveSheet()->setCellValue('R13', 'EPA-LAB-EQ-' . $start_samplings['reference_photometer']);
            $spreadsheet->getActiveSheet()->setCellValue('V13', 'EPA-LAB-EQ-' . $start_samplings['reference_turbidimeter']);
            $spreadsheet->getActiveSheet()->setCellValue('AB13', 'EPA-LAB-EQ-' . $start_samplings['other_equipment']);
        }

        $spreadsheet->getActiveSheet()->setCellValue('V14', $start_samplings['chlorine_test']);
        $spreadsheet->getActiveSheet()->setCellValue('AE14', $start_samplings['factor']);
        $spreadsheet->getActiveSheet()->setCellValue('F16', 'Edición' . $start_samplings['edition']);

        $contCeldas = 21;
        $totalSample = count($samplesTaking);
        if ($totalSample == 1) {
            // $spreadsheet->getActiveSheet()->insertNewRowBefore(22, $totalSample+1);
        } else {
            $spreadsheet->getActiveSheet()->insertNewRowBefore(22, $totalSample - 1);
        }
        $contadorTotal = 1;
        foreach ($samplesTaking as $key => $value) {
            $ensayoRealizar = SamplingSchedule::where('id', $value['lc_sampling_schedule_id'])->first()->toArray();
            $texto = $ensayoRealizar['mensaje'];
            $BuscarF = 'Físico';
            $ensayoF = strpos($texto, $BuscarF);
            if ($ensayoF === false) {
                $ensayoFMostrar = '';
            } else {
                $ensayoFMostrar = 'X';
            }
            $buscarQ = 'Químico';
            $ensayoQ = strpos($texto, $buscarQ);
            if ($ensayoQ === false) {
                $ensayoQMostrar = '';
            } else {
                $ensayoQMostrar = 'X';
            }
            $buscarB = 'Microbiológico';
            $ensayoB = strpos($texto, $buscarB);
            if ($ensayoB === false) {
                $ensayoBMostrar = '';
            } else {
                $ensayoBMostrar = 'X';
            }

            $spreadsheet->getActiveSheet()->mergeCells('AF' . $contCeldas . ':AG' . $contCeldas);
            $spreadsheet->getActiveSheet()->mergeCells('D' . $contCeldas . ':F' . $contCeldas);

            $spreadsheet->getActiveSheet()->setCellValue('A' . $contCeldas, $contadorTotal++);

            $sammplePoints = DB::table('lc_sample_points')->where("id", $value['lc_sample_points_id'])->first();

            $spreadsheet->getActiveSheet()->setCellValue('B' . $contCeldas, $sammplePoints->code ?? '');

            $spreadsheet->getActiveSheet()->setCellValue('C' . $contCeldas, $value['sample_reception_code']);

            // $direccion = DB::table('lc_sampling_schedule')->where("lc_sample_points_id", $value['lc_sample_points_id'])->first();

            $spreadsheet->getActiveSheet()->setCellValue('D' . $contCeldas, $sammplePoints->point_location ?? '');

            $pHOneList = DB::table('lc_dynamic_ph_list')->where("lc_sample_taking_id", $value['id'])->where("deleted_at", null)->take(2)->get();

            if (count($pHOneList) > 0) {

                $letra = 'H';

                foreach ($pHOneList as $key => $phVariable) {

                    # code...
                    $spreadsheet->getActiveSheet()->setCellValue($letra . $contCeldas, $phVariable->ph_unit);
                    $letra++;
                    $spreadsheet->getActiveSheet()->setCellValue($letra . $contCeldas, $phVariable->temperature_range);
                    $letra++;

                }
            }

            $spreadsheet->getActiveSheet()->setCellValue('L' . $contCeldas, $value['ph_promedio']);

            $spreadsheet->getActiveSheet()->setCellValue('M' . $contCeldas, $value['temperatura_promedio']);

            $duplicadoSi = 'D';
            $pos = strpos($value['sample_reception_code'], $duplicadoSi);
            if ($pos !== false) {
                $prueba = DB::table('lc_residual_chlorine_list')->where("lc_sample_taking_id", $value['id'])->get()->toArray();
                $spreadsheet->getActiveSheet()->setCellValue('O' . $contCeldas, $prueba[1]->chlorine_residual_test ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('N' . $contCeldas, $prueba[1]->v_sample ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('P' . $contCeldas, $prueba[1]->mg_cl2 ?? '');
            } else {
                $prueba = DB::table('lc_residual_chlorine_list')->where("lc_sample_taking_id", $value['id'])->latest()->first();
                $spreadsheet->getActiveSheet()->setCellValue('N' . $contCeldas, $prueba->v_sample ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('O' . $contCeldas, $prueba->chlorine_residual_test ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('P' . $contCeldas, $prueba->mg_cl2 ?? '');

                $spreadsheet->getActiveSheet()->setCellValue('G' . $contCeldas, $value['type_water']);
                $spreadsheet->getActiveSheet()->setCellValue('Q' . $contCeldas, $value['turbidez'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('R' . $contCeldas, $value['humidity'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('S' . $contCeldas, $value['temperature'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('T' . $contCeldas, $value['hour_from_to'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('U' . $contCeldas, $value['prevailing_climatic_characteristics'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('V' . $contCeldas, $ensayoFMostrar);
                $spreadsheet->getActiveSheet()->setCellValue('W' . $contCeldas, $ensayoQMostrar);
                $spreadsheet->getActiveSheet()->setCellValue('X' . $contCeldas, $ensayoBMostrar);
                $spreadsheet->getActiveSheet()->setCellValue('Y' . $contCeldas, $value['container_number'] ?? '');
                $spreadsheet->getActiveSheet()->setCellValue('Z' . $contCeldas, $value['reception_hour'] ?? '');

                if ($value['according'] == 'Si' || $value['according_receipt'] == 'Si') {
                    $spreadsheet->getActiveSheet()->setCellValue('AA' . $contCeldas, 'X');
                }
                if ($value['according'] == 'No' || $value['according_receipt'] == 'No') {
                    $spreadsheet->getActiveSheet()->setCellValue('AB' . $contCeldas, 'X');
                }
                if ($value['sample_characteristics'] == 'MR') {
                    $spreadsheet->getActiveSheet()->setCellValue('AC' . $contCeldas, 'X');
                }
                if ($value['sample_characteristics'] == 'S') {
                    $spreadsheet->getActiveSheet()->setCellValue('AD' . $contCeldas, 'X');
                }
                if ($value['sample_characteristics'] == 'ME') {
                    $spreadsheet->getActiveSheet()->setCellValue('AE' . $contCeldas, 'X');
                }
                $spreadsheet->getActiveSheet()->setCellValue('AF' . $contCeldas, $value['observations'] ?? '');
            }

            // if (($value['quimico'] == true && $value['fisico'] == true && $value['microbiologico'] == true)||$value['todos'] == true) {

            //     $spreadsheet->getActiveSheet()->setCellValue('V' . $contCeldas, 'X');
            //     $spreadsheet->getActiveSheet()->setCellValue('W' . $contCeldas, 'X');
            //     $spreadsheet->getActiveSheet()->setCellValue('X' . $contCeldas, 'X');

            // } else {
            //     if ($value['fisico'] == true ) {
            //         $spreadsheet->getActiveSheet()->setCellValue('V' . $contCeldas, 'X');

            //     }
            //     if ($value['quimico'] == true) {
            //         $spreadsheet->getActiveSheet()->setCellValue('W' . $contCeldas, 'X');

            //     }
            //     if ($value['microbiologico'] == true) {
            //         $spreadsheet->getActiveSheet()->setCellValue('X' . $contCeldas, 'X');

            //     }
            // }

            $contCeldas++;
        }
        //celda inicial es el valor inicial
        $celdaAbajo = $totalSample + 22;
        $muestraDuplicado = SampleTaking::where("lc_start_sampling_id", $start_samplings['id'])->get()->toArray();
        $arrayDuplicado = [];
        foreach ($muestraDuplicado as $value) {
            $buscar = 'D';
            $dupliMuestra = strpos($value['sample_reception_code'], $buscar);
            if ($dupliMuestra === false) {

            } else {
                array_push($arrayDuplicado, $value['sample_reception_code']);
            }
        }

        //ACa empieza lo de abajo
        $spreadsheet->getActiveSheet()->setCellValue('J' . $celdaAbajo, $arrayDuplicado[0] ?? 'N/A');
        $cloroPromedio = SampleTaking::where("lc_start_sampling_id", $start_samplings['id'])->get()->first();

        $valorCloro = isset($cloroPromedio['cloro_promedio']) ? number_format($cloroPromedio['cloro_promedio'], 2) : 'NA';
        $spreadsheet->getActiveSheet()->setCellValue('P' . $celdaAbajo, $valorCloro);

        // $spreadsheet->getActiveSheet()->setCellValue('P'.$celdaAbajo, $cloroPromedio['cloro_promedio']: number_format($cloroPromedio['cloro_promedio'], 2) ? 'NA');

        $celdaAbajo = $celdaAbajo + 1;
        if ($start_samplings['type_customer'] == 'Captacion') {
            $spreadsheet->getActiveSheet()->setCellValue('P' . $celdaAbajo, 'N/A');
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('P' . $celdaAbajo, number_format($start_samplings['DPR_chlorine_residual'], 2));
        }

        $fecha = strtotime($start_samplings['date_last_ph_adjustment']);

        $newDate = date('Y-m-d', $fecha);

        $anio = date("y", $fecha);
        $mes = date("m", $fecha);
        $dia = date("d", $fecha);

        $celdaAbajo = $celdaAbajo + 1;
        if ($start_samplings['type_customer'] == 'Captacion') {
            $spreadsheet->getActiveSheet()->setCellValue('R' . $celdaAbajo, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('S' . $celdaAbajo, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('T' . $celdaAbajo, 'N/A');
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('R' . $celdaAbajo, $anio);
            $spreadsheet->getActiveSheet()->setCellValue('S' . $celdaAbajo, $mes);
            $spreadsheet->getActiveSheet()->setCellValue('T' . $celdaAbajo, $dia);
        }

        $celdaAbajo = $celdaAbajo - 1;
        if ($start_samplings['type_customer'] == 'Captacion') {
            $spreadsheet->getActiveSheet()->setCellValue('U' . $celdaAbajo, 'N/A');
            $spreadsheet->getActiveSheet()->setCellValue('Z' . $celdaAbajo, 'N/A');
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('U' . $celdaAbajo, $start_samplings['pending']);
            $spreadsheet->getActiveSheet()->setCellValue('Z' . $celdaAbajo, $start_samplings['asymmetry']);

        }

        $celdaAbajo = $celdaAbajo + 2;
        // $spreadsheet->getActiveSheet()->setCellValue('H' . $celdaAbajo, $start_samplings['salida_temperatura'] . '°');
        $value = isset($start_samplings['salida_temperatura']) ? $start_samplings['salida_temperatura'] . '°' : 'NA';
        $spreadsheet->getActiveSheet()->setCellValue('H' . $celdaAbajo, $value);

        $celdaAbajo = $celdaAbajo + 2;
        $spreadsheet->getActiveSheet()->setCellValue('H' . $celdaAbajo, $start_samplings['llegada_temperatura'] . '°');

        $celdaAbajo = $celdaAbajo;
        $spreadsheet->getActiveSheet()->setCellValue('A' . $celdaAbajo, 'EPA-LAB-EQ-' . $start_samplings['reference_thermohygrometer']);

        $usersResposible = User::where("id", $start_samplings->users_id)->first();
        $usersConformidad = User::where("name", $start_samplings['name'])->first();
        if($usersConformidad == null){

            $usersConformidad = User::where("id", $start_samplings['name'])->first();

        }
        if ($usersConformidad->url_digital_signature) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath(storage_path('app/public' . '/' . $usersConformidad->url_digital_signature)); /* put your path and image here */
            $drawing->setCoordinates('Y37');
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
            $drawing->setHeight(150);
            $drawing->setResizeProportional(true);
            $drawing->setOffsetX(2); // this is how
            $drawing->setOffsetY(2);
        }
        $celdaAbajo = $celdaAbajo + 2;
        $spreadsheet->getActiveSheet()->setCellValue('D' . $celdaAbajo, $start_samplings['observations']);

        $celdaAbajo = $celdaAbajo + 1;
        $spreadsheet->getActiveSheet()->setCellValue('Z' . $celdaAbajo, $start_samplings['witness']);
        $spreadsheet->getActiveSheet()->setCellValue('T' . $celdaAbajo, $usersResponsablefinal->name);

        $celdaAbajo = $celdaAbajo + 3;
        $spreadsheet->getActiveSheet()->setCellValue('M' . $celdaAbajo, $start_samplings['standard_ph']);

        if ($start_samplings['type_customer'] == 'Captacion') {
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, 'N/A');
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, 'N/A');
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, 'N/A');
            $celdaAbajo = $celdaAbajo - 2;
            $spreadsheet->getActiveSheet()->setCellValue('N' . $celdaAbajo, 'N/A');
        } else {
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, $start_samplings['initial']);
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, $start_samplings['intermediate']);
            $celdaAbajo = $celdaAbajo + 1;
            $spreadsheet->getActiveSheet()->setCellValue('K' . $celdaAbajo, $start_samplings['end']);
            $celdaAbajo = $celdaAbajo - 2;
            $spreadsheet->getActiveSheet()->setCellValue('N' . $celdaAbajo, $start_samplings['chlorine_residual_target']);
        }

        $clorineResidual = ChlorineResidualStandard::where('lc_start_sampling_id', $start_samplings['id'])->take(3)->get()->toArray();

        $clorineResidual = ChlorineResidualStandard::where('lc_start_sampling_id', $start_samplings['id'])->take(3)->get()->toArray();

        if ($start_samplings['type_customer'] == 'Captacion') {
            $cont = $celdaAbajo;
            if (count($clorineResidual) > 0) {
                foreach ($clorineResidual as $key => $value) {
                    $spreadsheet->getActiveSheet()->setCellValue('Q' . $cont, 'N/A');
                    $cont++;
                }
            }
        } else {
            $cont = $celdaAbajo;
            if (count($clorineResidual) > 0) {
                foreach ($clorineResidual as $key => $value) {
                    $spreadsheet->getActiveSheet()->setCellValue('Q' . $cont, $value['chlorine_residual']);
                    $cont++;
                }
            }
        }

        if ($start_samplings['type_customer'] == 'Captacion') {
            $spreadsheet->getActiveSheet()->setCellValue('T' . $celdaAbajo, 'N/A');
        } else {
            $spreadsheet->getActiveSheet()->setCellValue('T' . $celdaAbajo, $start_samplings['initial_pattern']);
        }
        $celdaAbajo = $celdaAbajo + 1;
        $spreadsheet->getActiveSheet()->setCellValue('Y' . $celdaAbajo, $start_samplings['name']);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte Excel.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('data_obtained_successfully'));

    }

}
