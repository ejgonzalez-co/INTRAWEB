<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\Leca\RequestExport;
use App\Http\Controllers\AppBaseController;
use App\Mail\SendMail;
use Modules\Leca\Http\Requests\CreateSamplingScheduleRequest;
use Modules\Leca\Http\Requests\UpdateSamplingScheduleRequest;
use Modules\Leca\Models\SamplePoints;
use Modules\Leca\Models\SamplingSchedule;
use Modules\Leca\Repositories\SamplingScheduleRepository;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\Http\Controllers\SendNotificationController;
use App\Http\Controllers\JwtController;


/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class SamplingScheduleController extends AppBaseController
{

    /** @var  SamplingScheduleRepository */
    private $samplingScheduleRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(SamplingScheduleRepository $samplingScheduleRepo)
    {
        $this->samplingScheduleRepository = $samplingScheduleRepo;
    }

    /**
     * Muestra la vista para el CRUD de SamplingSchedule.
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
        return view('leca::sampling_schedules.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        $count = 0;
        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

            
            if (Auth::user()->hasRole('Administrador Leca')) {
                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->whereRaw( base64_decode($request["f"]) )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();


                $count = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->whereRaw( base64_decode($request["f"]) )->count();

            }else{

                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->whereRaw( base64_decode($request["f"]) )->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->whereRaw( base64_decode($request["f"]) )->count();
            }

        } else if(isset($request["cp"]) && isset($request["pi"])) {

            if (Auth::user()->hasRole('Administrador Leca')) {
                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();


                $count = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->count();

            }else{

                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->count();
            }

        } else {

            if (Auth::user()->hasRole('Administrador Leca')) {
                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();


                $count = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->count();

            }else{

                $sampling_schedule = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->latest()->get()->toArray();

                $count = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->count();
            }

        }

        return $this->sendResponseAvanzado($sampling_schedule, trans('data_obtained_successfully'), null, ["total_registros" => $count]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author José Manuel Marín Londoño. - Dic. 02 - 2021
     * @version 1.0.0
     *
     * @param CreateSamplingScheduleRequest $request
     *
     * @return Response
     */
    public function store(CreateSamplingScheduleRequest $request)
    {

        $user = Auth::user();
        $input = $request->all();

        $input['user_creador'] = $user->name;
        $input['vigencia']=date("Y");
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
        if (isset($input['microbiologico']) == true) {
            if ($input['microbiologico'] == true) {
                $mensaje = $mensaje . "\n - Microbiológico";

            }
        }
        if (isset($input['todos']) == true) {
            if ($input['todos'] == true) {
                $mensaje = "";
                $mensaje = "\n - Microbiológico \n   - Físico \n  - Químico";

            }
        }

        $input['mensaje'] = $mensaje;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['users_name'] = $input['objet_oficial_sampling']['name'];
            if (Auth::user()->hasRole('Administrador Leca')) {
                $arrayPoints = $input['lc_sample_points_id'];
                $cont = count($arrayPoints);
                if ($cont > 0) {
                    foreach ($arrayPoints as $key => $value) {
                        $input['lc_sample_points_id'] = $value;

                        if (!array_key_exists("observation", $input)) {
                            $input['observation'] = null;
                        }

                        if (!array_key_exists("direction", $input)) {
                            $input['direction'] = null;
                        }
                        // Inserta el registro en la base de datos
                        $samplingSchedule = $this->samplingScheduleRepository->create($input);

                        //Envia las relaciones
                        $samplingSchedule->lcSamplePoints;
                        $samplingSchedule->users;
                        // Efectua los cambios realizados
                        DB::commit();

                        //Obtiene los datos del funcionario, que el administrador le asigno la toma de muestra
                        $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $input['users_name'])->get();
                        $usersOfficialsNew = $usersOfficials[0];

                        //Agrega una nueva posision a user la cual se puede concatenar informacion de la base de datos
                        $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                        $custom = json_decode('{"subject": "LECA (EPA)"}');
                        //Envia notificacion al usuario asignado
                        // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.creation_shows_administrator', $usersOfficialsNew, $custom));
                        SendNotificationController::SendNotification('leca::sampling_schedules.email.creation_shows_administrator',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                        if ($input['duplicado'] == 'Si') {

                            $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $input['users_name'])->get();
                            $usersOfficialsNew = $usersOfficials[0];

                            $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                            $custom = json_decode('{"subject": "LECA (EPA)"}');

                            // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                            SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                            $usersOfficials = User::role(['Administrador Leca'])->get();
                            $usersOfficialsNew = $usersOfficials[0];

                            $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                            $custom = json_decode('{"subject": "LECA (EPA)"}');

                            // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                            SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                        }

                    }

                    return $this->sendResponse($samplingSchedule->toArray(), trans('msg_success_save'));
                } else {
                    return $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                }
            } else if (Auth::user()->hasRole('Analista fisicoquímico') || Auth::user()->hasRole('Analista microbiológico') || Auth::user()->hasRole('Recepcionista') || Auth::user()->hasRole('Personal de Apoyo') || Auth::user()->hasRole('Toma de Muestra')) {

                $arrayPoints = $input['lc_sample_points_id'];
                $cont = count($arrayPoints);
                if ($cont > 0) {
                    foreach ($arrayPoints as $key => $value) {
                        $input['lc_sample_points_id'] = $value;

                        if (!array_key_exists("observation", $input)) {
                            $input['observation'] = null;
                        }

                        if (!array_key_exists("direction", $input)) {
                            $input['direction'] = null;
                        }
                        // Inserta el registro en la base de datos
                        $samplingSchedule = $this->samplingScheduleRepository->create($input);
                        //Envia las relaciones
                        $samplingSchedule->lcSamplePoints;
                        $samplingSchedule->users;

                        // Efectua los cambios realizados
                        DB::commit();
                        //Obtiene todos los usuarios que tengan el rol de administrador leca
                        $userSchedule = User::role('Administrador Leca')->get();
                        $usersSheduleNew = $userSchedule[0];
                        //Agrega una nueva posision a user la cual se puede concatenar informacion de la base de datos
                        $usersSheduleNew->offiialsSchedule = $samplingSchedule;
                        // $user_appointmetn->offiialsSchedule=$appointment;
                        $custom = json_decode('{"subject": "LECA (EPA)"}');
                        //Envia notificacion al usuario asignado
                        // Mail::to($usersSheduleNew)->send(new SendMail('leca::sampling_schedules.email.creation_shows_official', $usersSheduleNew, $custom));
                        SendNotificationController::SendNotification('leca::sampling_schedules.email.creation_shows_official',$custom,$usersSheduleNew,$usersSheduleNew->email,'Leca');


                        if ($input['duplicado'] == 'Si') {

                            $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $input['name'])->get();
                            $usersOfficialsNew = $usersOfficials[0];

                            $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                            $custom = json_decode('{"subject": "LECA (EPA)"}');

                            // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                            SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                            $usersOfficials = User::role(['Administrador Leca'])->get();
                            $usersOfficialsNew = $usersOfficials[0];

                            $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                            $custom = json_decode('{"subject": "LECA (EPA)"}');

                            // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                            SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                        }
                    }

                    return $this->sendResponse($samplingSchedule->toArray(), trans('msg_success_save'));

                } else {
                    return $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
                }

            }
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($samplingSchedule->toArray(), trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
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
     * @param UpdateSamplingScheduleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSamplingScheduleRequest $request)
    {

        $user = Auth::user();
        $input = $request->all();

        $mensaje = "";
        if (isset($input['quimico']) == true) {
            if ($input['quimico'] == true) {
                $mensaje = " \n - Químico";
            }
        }
        if (isset($input['fisico']) == true) {
            if ($input['fisico'] == true) {
                $mensaje = $mensaje . " \n - Físico";
            }
        }
        if (isset($input['microbiologico']) == true) {
            if ($input['microbiologico'] == true) {
                $mensaje = $mensaje . " \n - Microbiológico";

            }
        }
        if (isset($input['todos']) == true) {
            if ($input['todos'] == true) {
                $mensaje = "";
                $mensaje = " \n - Microbiológico \n   - Físico \n  - Químico";

            }
        }

        $input['mensaje'] = $mensaje;

        /** @var SamplingSchedule $samplingSchedule */
        $samplingSchedule = $this->samplingScheduleRepository->find($id);
        //Obtiene la observacion que tenia antes
        $obervationBefore = $samplingSchedule->observation;

        if (empty($samplingSchedule)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if ($samplingSchedule->duplicado != $input['duplicado']) {
                if ($input['duplicado'] == 'Si') {

                    $usersOfficials = User::role(['Analista microbiológico', 'Analista fisicoquímico', 'Recepcionista', 'Personal de Apoyo', 'Toma de Muestra'])->where('name', $input['users_name'])->get();
                    $usersOfficialsNew = $usersOfficials[0];

                    $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                    $custom = json_decode('{"subject": "LECA (EPA)"}');

                    // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                    SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');


                    $usersOfficials = User::role(['Administrador Leca'])->get();
                    $usersOfficialsNew = $usersOfficials[0];

                    $usersOfficialsNew->offiialsSchedule = $samplingSchedule;
                    $custom = json_decode('{"subject": "LECA (EPA)"}');

                    // Mail::to($usersOfficialsNew)->send(new SendMail('leca::sampling_schedules.email.email_duplicado', $usersOfficialsNew, $custom));
                    SendNotificationController::SendNotification('leca::sampling_schedules.email.email_duplicado',$custom,$usersOfficialsNew,$usersOfficialsNew->email,'Leca');

                }
            }

            $input['users_name'] = $input['objet_oficial_sampling']['name'];
            if ($input['observation'] != $obervationBefore) {
                // Actualiza el registro
                $samplingSchedule = $this->samplingScheduleRepository->update($input, $id);
                $samplingSchedule->lcSamplePoints;
                $samplingSchedule->users;

                // Efectua los cambios realizados
                DB::commit();
                //Obtiene todos los usuarios que tengan el rol de administrador leca
                $userSchedule = User::role('Administrador Leca')->get();
                $usersSheduleNew = $userSchedule[0];
                //Agrega una nueva posision a user la cual se puede concatenar informacion de la base de datos
                $usersSheduleNew->offiialsSchedule = $samplingSchedule;
                // $user_appointmetn->offiialsSchedule=$appointment;
                $custom = json_decode('{"subject": "LECA (EPA)"}');
                //Envia notificacion al usuario asignado
                // Mail::to($usersSheduleNew)->send(new SendMail('leca::sampling_schedules.email.assignment_of_observation', $usersSheduleNew, $custom));
                SendNotificationController::SendNotification('leca::sampling_schedules.email.assignment_of_observation',$custom,$usersSheduleNew,$usersSheduleNew->email,'Leca');


                return $this->sendResponse($samplingSchedule->toArray(), trans('msg_success_update'));
            }
            // Actualiza el registro
            $samplingSchedule = $this->samplingScheduleRepository->update($input, $id);
            $samplingSchedule->lcSamplePoints;
            $samplingSchedule->users;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($samplingSchedule->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un SamplingSchedule del almacenamiento
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

        /** @var SamplingSchedule $samplingSchedule */
        $samplingSchedule = $this->samplingScheduleRepository->find($id);

        if (empty($samplingSchedule)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $samplingSchedule->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\SamplingScheduleController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author José Manuel Marín Londoño. - Act. 07 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->toArray();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = 'reporte.' . $fileType;

        if(array_key_exists("filtros", $input)) {

            if (Auth::user()->hasRole('Administrador Leca')) {
                $input['data'] = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->whereRaw( $input['filtros'] )->latest()->get()->toArray();

            }else{

                $input['data'] = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->whereRaw( $input['filtros'] )->latest()->get()->toArray();

            }
            
        }else{
            
            if (Auth::user()->hasRole('Administrador Leca')) {
                $input['data'] = SamplingSchedule::with(['users', 'lcSamplePoints', 'tomasMuestra'])->latest()->get()->toArray();

            }else{

                $input['data'] = SamplingSchedule::with(['users', 'lcSamplePoints'])->where('users_id', $user->id)->latest()->get()->toArray();

            }
        }


        // Retorna la descarga del excel y se le esta ingresando como parametro hasta que columna va a llenar ese excel en este caso es la (k)
        return Excel::download(new RequestExport('leca::sampling_schedules.report_excel', JwtController::generateToken($input['data']), 'g'), $fileName);
    }

    /**
     * Obtiene todos los puntos de muestra para la programacion de toma de muestra
     *
     * @author José Manuel Marín Londoño. - Dic. 01 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPointSampling()
    {
        $points = SamplePoints::all();

        return $this->sendResponse($points->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los funcionarios para el automcomplete
     *
     * @author Josè Manuel Marìn Londoño. - Nov. 22 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getOficcialsSampling(Request $request)
    {
        $usersOfficials = User::role(['Toma de Muestra', 'Recepcionista'])->where('name', "like", "%" . $request['query'] . "%")->get();
        // $usersOfficials = User::all();
        return $this->sendResponse($usersOfficials, trans('data_obtained_successfully'));
    }
}
