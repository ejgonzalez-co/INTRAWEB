<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreatePollRequest;
use Modules\Intranet\Http\Requests\UpdatePollRequest;
use Modules\Intranet\Repositories\PollRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use App\User;
use Modules\Intranet\Models\PollUser;
use Modules\Intranet\Models\PollWorkGroup;
use Modules\Intranet\Models\Poll;
use DB;
use App\Exports\Intranet\Poll\RequestExportPolls;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Intranet\Models\WorkGroup;
use Illuminate\Support\Arr;

/**
 * Clase que controla todo lo relacionado con encuestas de satisfaccion del modulo intranet
 *
 * @author Erika Johana Gonzalez C. - Jun. 15. 2021
 * @version 1.0.0
 */
class PollController extends AppBaseController
{

    /** @var  PollRepository */
    private $pollRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     */
    public function __construct(PollRepository $pollRepo)
    {
        $this->pollRepository = $pollRepo;
    }

    /**
     * Muestra la vista para el CRUD de Poll.
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::polls.index');
        }
        return view("auth.forbidden");
    }

    public function homePanel(Request $request) {
        return view('intranet::polls.index_public');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all()
    {
        //valida si es un administrador de intranet
        if (Auth::user()->hasRole('Administrador intranet de encuestas')) {
            //obtiene las encuestas con relacion a usuarios y grupos, el admin puede ver todo
            $polls = Poll::with(['users','workGroups'])->latest()->get()->toArray();

            // Recorre recorre las encuestas por grupos y asigna el valor de poll_work_groups con los ids
            for ($i=0; $i < count($polls) ; $i++) {
                foreach ($polls[$i]['work_groups'] as  $value) {
                    //contiene solo los ids de los grupos
                    $polls[$i]['poll_work_groups'][] = $value['id'];
                }
            }

            // Recorre recorre las encuestas y asigna el valor de poll_users con los ids
            for ($i=0; $i < count($polls) ; $i++) {
                foreach ($polls[$i]['users'] as  $value) {
                    //contiene solo los ids de los usuarios
                    $polls[$i]['poll_users'][] = $value['id'];
                }
            }
        } else {
            //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
            $groups =  DB::table('users_work_groups')
                ->where('users_id', Auth::user()->id)
                ->pluck("work_groups_id")->toArray();

            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
            $polls = Poll::select('intranet_polls.*')->with(['workGroups:id','users'])
            ->leftjoin('intranet_polls_work_groups', 'intranet_polls.id', '=', 'intranet_polls_work_groups.intranet_polls_id')
            ->whereNull("intranet_polls_work_groups.deleted_at")
            ->where(function($q) use ($groups){ 
                $q->whereIn("intranet_polls_work_groups.work_groups_id", $groups);
                $q->orWhereRelation('users', 'users_id', Auth::user()->id);
            })->where("intranet_polls.state", 2)->get()->toArray();
        }
        // Retorna las encuestas del usuario
        return $this->sendResponse($polls, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los usuarios que tienen cargo
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsers(Request $request)
    {
        $query =$request->input('query');
        $users = DB::table('users')
        ->select(DB::raw('users.name as users_name,users.id as users_id'))
        ->where('users.name', 'like', '%'.$query.'%')
        ->whereNotNull('users.id_cargo')
        ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
        ->get();

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los grupos por nombre
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getWorkGroup(Request $request)
    {
        $query =$request->input('query');
        $users = DB::table('work_groups')
        ->select(DB::raw('work_groups.name as name, work_groups.id as id'))
        ->where('work_groups.name', 'like', '%'.$query.'%')
        ->get();

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @param CreatePollRequest $request
     *
     * @return Response
     */
    public function store(CreatePollRequest $request)
    {
        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            //Datos iniciales de la encuesta
            $input["creator"]=Auth::user()->name;
            $input["users_name"]=Auth::user()->name;
            $input["users_id"]=Auth::user()->id;

            //estado En elaboración
            $input["state"]=1;

            // Inserta el registro en la base de datos
            $poll = $this->pollRepository->create($input);

            // Valida si viene grupos de trabajo para asignar
            if (!empty($input['poll_work_groups'])) {
                // Inserta relacion con grupos de trabajo
                $poll->workGroups()->sync($input['poll_work_groups']);

                $poll->workGroups;
                // Recorre workGroups y lo converte a array para asignar poll_work_groups que solo contiene los ids
                $arrayGroups = [];
                foreach ($poll->workGroups as  $value) {
                    $arrayGroups[] = $value['id'];
                }
                $poll->poll_work_groups = $arrayGroups;
            }

            // Valida si viene usuarios para asignar
            if (!empty($input['users'])) {
                //recorre los usuarios
                foreach ($input['users'] as $user) {
                    //crea el usuario con la información que llega
                    $userInformation = json_decode($user);
                    $userInformation->intranet_polls_id= $poll->id;
                    //se tuvo que hacer asi y no con sync porque tenia que almacenar el nombre del usuario
                    $pollUser = (array) $userInformation;

                    $pollUserSave = new PollUser($pollUser);
                    //guarda
                    $poll->users()->save($pollUserSave);
                }
                $poll->users;
            }
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($poll->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet_poll', 'Modules\Intranet\Http\Controllers\PollController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet_poll', 'Modules\Intranet\Http\Controllers\PollController - '. Auth::user()->name.' -  Error: '.$e->getMessage().$e->getMessage(). 'Linea: '.$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePollRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePollRequest $request)
    {
        $input = $request->all();

        /** @var Poll $poll */
        $poll = $this->pollRepository->find($id);

        if (empty($poll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Actualiza el registro de encuesta
            $poll = $this->pollRepository->update($input, $id);

            // Valida si viene grupos de trabajo para actualizar
            if (empty($input['poll_work_groups'])) {
                //borra todo
                $poll->workGroups()->sync([]);
                $poll->poll_work_groups = [];
            } else {
                // Inserta relacion con grupos de trabajo
                $poll->workGroups()->sync($input['poll_work_groups']);
                // Recorre workGroups y lo converte a array para asignar poll_work_groups que solo contiene los ids
                $arrayGroups = [];
                foreach ($poll->workGroups as  $value) {
                    $arrayGroups[] = $value['id'];
                }
                $poll->poll_work_groups = $arrayGroups;
            }
            $poll->workGroups;
           
   
            // Valida si tiene usuarios para actualizar
            if (empty($input['users'])) {
                //borra todo cuando esta vacio
                PollUser::where('intranet_polls_id', $id)->delete();
            } else {

                //borra todo para volver a insertarlo
                PollUser::where('intranet_polls_id', $id)->delete();
                //recorre los usuarios
                foreach ($input['users'] as $user) {
                    //crea el usuario con la información que llega
                    $userInformation = json_decode($user);
                    $userInformation->intranet_polls_id= $poll->id;
                    //se tuvo que hacer asi y no con sync porque tenia que almacenar el nombre del usuario
                    $pollUser = (array) $userInformation;

                    $pollUserSave = new PollUser($pollUser);

                    $poll->users()->save($pollUserSave);
                }
            }
            $poll->users;
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($poll->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\PollController - '. Auth::user()->name.' -  Error: '.$e->getMessage().$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($poll['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }


    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id)
    {
        $polls = $this->pollRepository->find($id);

        if (empty($polls)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $polls->users;
        $polls->workGroups;
        //valida si es un admin
        if (Auth::user()->hasRole('Administrador intranet de encuestas')) {
            $polls->intranetPollsQuestions;
        // $polls->intranetPollsAnswers;
        } else {
            //si es un funcionario normal
            $polls->intranetPollsQuestions;
            //consulta solos las respuestas del funcionario
            $polls->intranetPollsAnswers->where("users_id", Auth::user()->id);
        }

        return $this->sendResponse($polls->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Elimina un Poll del almacenamiento
     *
     * @author Erika Johana Gonzalez C. - Jun. 15. 2021
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

        /** @var Poll $poll */
        $poll = $this->pollRepository->find($id);

        if (empty($poll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $poll->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
       
        // dd($request);
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('polls').'.'.$fileType;
        
        return Excel::download(new RequestExportPolls('intranet::polls.report_excel', $input['data']), $fileName);
    }


    /**
     * Actualiza el estado de la solicitud y envia correo a los destinatarios
     *
     * @author Erika Johana Gonzalez. Ene. 20 - 2021
     * @version 1.0.0
     *
     * @param UpdatePollRequest $request
     *
     * @return Response
     */
    public function publishPolls(UpdatePollRequest $request)
    {
        $input = $request->all();
        //asigna el valor del id que luego es utilizado para encontrar y actualizar la encuesta
        $id = $input["id"];
        $observations = $input["observations"];

        $poll = $this->pollRepository->find($id);

        if (empty($poll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //actualiza el estado a publicada
        $input["state"] =2;
        $poll = $this->pollRepository->update($input, $id);

        if ($poll->state == 2) {
            //recorre los ususarios y envia notificacion a cada uno
            if (!empty($input['users'])) {
                // Asunto del email
                $custom = json_decode('{"subject": "Invitación a contestar la encuesta '.$poll->title.'"}');
                // Asigna la encuesta al usuario
                $user= $poll;
                //envia la observacion
                $user->observations = $observations;
                // Envia el email al usuario
                Mail::to($poll->usersBelongs)->send(new SendMail('intranet::polls.email_poll_publish', $user, $custom));
            }
            //recorre los grupos y consulta los usuarios y envia notificacion a cada uno
            if (!empty($input['poll_work_groups'])) {
                // Recorre los grupos asignados a la encuesta
                $userMail = array();
                foreach ($input['poll_work_groups'] as $groupId) {
                    // Obtiene los datos del grupo
                    $group = WorkGroup::find($groupId);
                    
                    // Recorre los usuarios del grupo
                    foreach ($group->users as $user) {
                        $userMail[] = $user;
                    }
                }
                $custom = json_decode('{"subject": "Invitación a contestar la encuesta '.$poll->title.'"}');
                // Asigna la encuesta al usuario
                $user = $poll;
                $user->observations = $observations;

                // Envia el email al usuario
                Mail::to($userMail)->send(new SendMail('intranet::polls.email_poll_publish', $user, $custom));
            }
        }
        return $this->sendResponse($poll->toArray(), 'Encuesta publicada satisfactoriamente!', "success");
    }
}
