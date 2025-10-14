<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Intranet\Http\Requests\CreateEventRequest;
use Modules\Intranet\Http\Requests\UpdateEventRequest;
use Modules\Intranet\Repositories\EventRepository;
use Modules\Intranet\Models\Event;
use Modules\Intranet\Models\EventUser;
use Modules\Intranet\Models\EventHistory;
use Modules\Intranet\Models\WorkGroup;
use App\Http\Controllers\AppBaseController;
use Modules\Intranet\Http\Controllers\UtilController;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Notifications\EventCalendarMail;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\User;
use Auth;
use Redirect;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class EventController extends AppBaseController {

    /** @var  EventRepository */
    private $eventRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(EventRepository $eventRepo) {
        $this->eventRepository = $eventRepo;
    }

    /**
     * Muestra la vista para el CRUD de Event.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::events.index');
        }
        return view("auth.forbidden");
    }

    public function homePanel(Request $request) {
        return view('intranet::events.index_public');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {

        //valida si es un administrador de intranet
        if (Auth::user()->hasRole('Administrador intranet de eventos')) {

            $events = Event::with(['typeEvents', 'workGroups', 'users', 'eventsHistories'])
            ->latest()
            ->get()
            ->toArray();

            // Recorre recorre los eventos por grupos y asigna el valor de calendar_groups con los ids
            for ($i=0; $i < count($events) ; $i++) {
                foreach ($events[$i]['work_groups'] as  $value) {
                    //contiene solo los ids de los grupos
                    $events[$i]['calendar_groups'][] = $value['id'];
                }
            }

            // Recorre recorre los eventos y asigna el valor de calendar_users con los ids
            for ($i=0; $i < count($events) ; $i++) {
                foreach ($events[$i]['users'] as  $value) {
                    //contiene solo los ids de los usuarios
                    $events[$i]['calendar_users'][] = $value['id'];
                }
            }
        } else {
            // Valida si el usuario en sesion pertenece a los grupos de trabajo del evento
            $groups =  DB::table('users_work_groups')
                ->where('users_id', Auth::user()->id)
                ->pluck("work_groups_id")->toArray();
            // Consula los grupos y usuarios del evento
            $events = Event::with(['workGroups:id','users'])->where("state", "2")->latest()->get()->filter(function ($event) use ($groups) {

                $eventGroups = $event->workGroups->toArray();
                //El método Arr::flatten convierte un arreglo multidimensional en uno simple, ejemplo: https://styde.net/manejo-de-arreglos-con-laravel/
                $eventGroups = Arr::flatten($eventGroups);

                $eventUsers = $event->users->toArray();

                $eventUsers = Arr::flatten($eventUsers);

                return !!array_intersect($eventGroups, $groups) ? true : ($eventUsers ? $eventUsers[5] == Auth::user()->id : false);
            })->values()->toArray();
        }
        // Retorna los eventos del usuario
        return $this->sendResponse($events, trans('data_obtained_successfully'));


        // $events = Event::with(['typeEvents', 'workGroups', 'users', 'eventsHistories'])
        // ->whereHas('users', function($query) {
        //     // Valida si el rol del usuario logueado no sea un administrador del modulo
        //     if (!Auth::user()->hasRole('Administrador intranet de eventos')) {
        //         $query->where('users_id', Auth::user()->id);
        //     }
        // })
        // ->latest()
        // ->get()
        // ->toArray();

        // Recorre recorre los eventos
        // for ($i=0; $i < count($events) ; $i++) { 
        //     foreach ($events[$i]['work_groups'] as  $value) {
        //         $events[$i]['calendar_groups'][] = $value['id'];
        //     }

        //     foreach ($events[$i]['users'] as  $value) {
        //         $events[$i]['calendar_users'][] = $value['id'];
        //     }
        // }

        // Recorre recorre los eventos
        // for ($i=0; $i < count($events) ; $i++) {
        //     foreach ($events[$i]['work_groups'] as  $value) {
        //         $events[$i]['calendar_groups'][] = $value['id'];
        //     }

        //     foreach ($events[$i]['users'] as  $value) {
        //         //contiene solo los ids de los usuarios
        //         $events[$i]['calendar_users'][] = $value['id'];
        //     }
        // }

        // return $this->sendResponse($events, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateEventRequest $request
     *
     * @return Response
     */
    public function store(CreateEventRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Obtiene el nombre de la duracion del evento
            $duration = $this->getObjectOfList(config('intranet.duration_event'), 'id', $input['duration'])->name;

            // Valida si la duracion es en minutos
            if (strpos($duration, 'minuto') !== false) {
                // Convierte a entero la duracion
                $minute = (int)$duration;
                // Le suma los minutos a la hora inicial
                $end_hour = strtotime ( '+'.$minute.' minute', strtotime ($input["initial_hour"]));
                // Convierte a fecha la fecha final
                $end_hour = date ('H:i:s', $end_hour);
        
                $input["end_hour"] = $end_hour;
            } else {
                // Convierte array la duracion
                $duration = explode(",", $duration); 
                // Convierte a entero la hora de la duracion
                $hour = (int)$duration[0];
                // Le suma las horas a la hora inicial
                $end_hour = strtotime ( '+'.$hour.' hour', strtotime ($input["initial_hour"]));

                // Valida si la duracion tiene minutos
                if(count($duration) > 1) {
                    $end_hour = strtotime ( '+30 minute', $end_hour);
                }

                // Convierte a fecha la fecha final
                $end_hour = date ('H:i:s', $end_hour);
                $input["end_hour"] = $end_hour;
            }
            
            // Crea el registro
            $event = $this->eventRepository->create($input);

            // Valida si viene grupos de trabajo para asignar
            if (!empty($input['calendar_groups'])) {
                // Inserta relacion con grupos de trabajo
                $event->workGroups()->sync($input['calendar_groups']);

                $event->workGroups;
                // Recorre workGroups y lo converte a array para asignar calendar_groups que solo contiene los ids
                $arrayGroups = [];
                foreach ($event->workGroups as  $value) {
                    $arrayGroups[] = $value['id'];

                    // Obtiene los datos del grupo
                    $group = WorkGroup::find($value['id']);
                    // Recorre los usuarios del grupo
                    foreach ($group->users as $user) {
                        // Asunto del email
                        $custom = json_decode('{"subject": "Invitación al evento '.$event->title.'"}');
                        // Asigna el evento al usuario
                        $user->event = $event;
                        // Envia el email al usuario
                        Mail::to($user)->send(new SendMail('intranet::events.email_new_event', $user, $custom));
                    }
                }
                $event->calendar_groups = $arrayGroups;
            }

            // Valida si viene grupos de trabajo para asignar
            // if (!empty($input['calendar_groups'])) {
            //     // Recorre los grupos asignados al evento
            //     foreach ($input['calendar_groups'] as $groupId) {
            //         // Obtiene los datos del grupo
            //         $group = WorkGroup::find($groupId);
            //         // Recorre los usuarios del grupo
            //         foreach ($group->users as $user) {
            //             // Envia la notificacion del evento
            //             // $user->notify(new EventCalendarMail($user->name));

            //             // Asunto del email
            //             $custom = json_decode('{"subject": "Invitación al evento '.$event->title.'"}');
            //             // Asigna el evento al usuario
            //             $user->event = $event;
            //             // Envia el email al usuario
            //             Mail::to($user)->send(new SendMail('intranet::events.email_new_event', $user, $custom));
            //         }
            //     }
            //     // Inserta relacion con grupos de trabajo
            //     $event->workGroups()->sync($input['calendar_groups']);
            // }

            // Valida si viene usuarios para asignar
            if (!empty($input['users'])) {
                //recorre los usuarios
                foreach ($input['users'] as $user) {
                    //crea el usuario con la información que llega
                    $userInformation = json_decode($user);
                    $userInformation->intranet_events_id = $event->id;
                    //se tuvo que hacer asi y no con sync porque tenia que almacenar el nombre del usuario
                    $eventUser = (array) $userInformation;
                    
                    $eventUserSave = new EventUser($eventUser);

                    //guarda
                    $event->users()->save($eventUserSave);
                    
                    // Obtiene los datos del usuario
                    $user = User::find($userInformation->users_id);
                    // Envia la notificacion del evento
                    // $user->notify(new EventCalendarMail($user->name));

                    // Asunto del email
                    $custom = json_decode('{"subject": "Invitación al evento '.$event->title.'"}');
                    // Asigna el evento al usuario
                    $user->event = $event;
                    // Envia el email al usuario
                    Mail::to($user)->send(new SendMail('intranet::events.email_new_event', $user, $custom));
                }
                $event->users;
            }

            // Valida si viene usuarios para asignar
            // if (!empty($input['calendar_users'])) {
            //     // Recorre los usuarios asignados al evento
            //     foreach ($input['calendar_users'] as $userId) {
            //         // Obtiene los datos del usuario
            //         $user = User::find($userId);
            //         // Envia la notificacion del evento
            //         // $user->notify(new EventCalendarMail($user->name));

            //         // Asunto del email
            //         $custom = json_decode('{"subject": "Invitación al evento '.$event->title.'"}');
            //         // Asigna el evento al usuario
            //         $user->event = $event;
            //         // Envia el email al usuario
            //         Mail::to($user)->send(new SendMail('intranet::events.email_new_event', $user, $custom));
            //     }
            //     // Inserta relacion con usuarios
            //     $event->users()->sync($input['calendar_users']);
            // }

            // Obtiene los grupos de trabajo
            // $event->workGroups;

            // // Recorre workGroups y lo converte a array para asignar calendar_groups que solo contiene los ids
            // $array_groups = [];

            // foreach ($event->workGroups as  $value) {
            //     $array_groups[] = $value['id'];
            // }
        
            // $event->calendar_groups = $array_groups;

            // // Obtiene usuarios
            // $event->users;
            // // Recorre users y lo converte a array para asignar calendar_users que solo contiene los ids
            // $array_users = [];
    
            // foreach ($event->users as  $value) {
            //     $array_users[] = $value['id'];
            // }
        
            // $event->calendar_users = $array_users;

            // Asigna el id del evento para el registro del historial
            $input['intranet_events_id'] = $event->id;
            // Crea un nuevo registro de historial
            EventHistory::create($input);
            // Obtiene el historial de cambios del evento
            $event->eventsHistories;
            // Obtiene los tipos de eventos
            $event->typeEvents;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($event->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRequest $request) {

        $input = $request->all();

        /** @var Event $event */
        $oldEvent = $this->eventRepository->find($id);

        if (empty($oldEvent)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();

        try {
            $oldEvent->workGroups;
            $oldEvent->users;
    
            // Obtiene el nombre de la duracion del evento
            $duration = $this->getObjectOfList(config('intranet.duration_event'), 'id', $input['duration'])->name;
    
            // Valida si la duracion es en minutos
            if (strpos($duration, 'minuto') !== false) {
                // Convierte a entero la duracion
                $minute = (int)$duration;
                // Le suma los minutos a la hora inicial
                $end_hour = strtotime ( '+'.$minute.' minute', strtotime ($input["initial_hour"]));
                // Convierte a fecha la fecha final
                $end_hour = date ('H:i:s', $end_hour);
                $input["end_hour"] = $end_hour;
            } else {
                // Convierte array la duracion
                $duration = explode(",", $duration); 
                // Convierte a entero la hora de la duracion
                $hour = (int)$duration[0];
                // Le suma las horas a la hora inicial
                $end_hour = strtotime ( '+'.$hour.' hour', strtotime ($input["initial_hour"]));
    
                // Valida si la duracion tiene minutos
                if(count($duration) > 1) {
                    $end_hour = strtotime ( '+30 minute', $end_hour);
                }
    
                // Convierte a fecha la fecha final
                $end_hour = date ('H:i:s', $end_hour);
                $input["end_hour"] = $end_hour;
            }
            
            // Actualiza el registro
            $event = $this->eventRepository->update($input, $id);

            // Valida si viene grupos de trabajo para actualizar
            if (empty($input['calendar_groups'])) {
                //borra todo
                $event->workGroups()->sync([]);
                $event->calendar_groups = [];
            } else {
                // Inserta relacion con grupos de trabajo
                $event->workGroups()->sync($input['calendar_groups']);
                // Recorre workGroups y lo converte a array para asignar calendar_groups que solo contiene los ids
                $arrayGroups = [];
                foreach ($event->workGroups as  $value) {
                    $arrayGroups[] = $value['id'];
                }
                $event->calendar_groups = $arrayGroups;
            }
            // $event->workGroups;

            
            // Valida si viene grupos para actualizar
            if (empty($input['calendar_groups'])) {
                $event->workGroups()->sync([]);
            } else {
                // Inserta relacion con los grupos de trabajo
                $event->workGroups()->sync($input['calendar_groups']);

                // Valida si el evento es cancelado
                if ($event->state == 2) {
                     // Recorre los grupos asignados al evento
                    foreach ($input['calendar_groups'] as $groupId) {
                        // Obtiene los datos del grupo
                        $group = WorkGroup::find($groupId);
                        // Recorre los usuarios del grupo
                        foreach ($group->users as $user) {
                            // Envia la notificacion del evento
                            // $user->notify(new EventCalendarMail($user->name));

                            // Asunto del email
                            $custom = json_decode('{"subject": "Cancelación del evento '.$event->title.'"}');
                            // Asigna el evento al usuario
                            $user->event = $event;
                            // Envia el email al usuario
                            Mail::to($user)->send(new SendMail('intranet::events.email_cancel_event', $user, $custom));
                        }
                    }
                }
            }
    
            // Obtiene los grupos de trabajo
            $event->workGroups;
    
            // Recorre workGroups y lo converte a array para asignar calendar_groups que solo contiene los ids
            // $array_groups = [];
    
            // foreach ($event->workGroups as  $value) {
            //     $array_groups[] = $value['id'];
            // }
        
            // $event->calendar_groups = $array_groups;

            // Valida si tiene usuarios para actualizar
            if (empty($input['users'])) {
                //borra todo cuando esta vacio
                EventUser::where('intranet_events_id', $id)->delete();
            } else {

                //borra todo para volver a insertarlo
                EventUser::where('intranet_events_id', $id)->delete();
                //recorre los usuarios
                foreach ($input['users'] as $user) {
                    //crea el usuario con la información que llega
                    $userInformation = json_decode($user);
                    $userInformation->intranet_events_id = $event->id;
                    //se tuvo que hacer asi y no con sync porque tenia que almacenar el nombre del usuario
                    $eventUser = (array) $userInformation;

                    $eventUserSave = new EventUser($eventUser);

                    //guarda
                    $event->users()->save($eventUserSave);
                    
                    // Obtiene los datos del usuario
                    $user = User::find($userInformation->users_id);
                    // Envia la notificacion del evento
                    // $user->notify(new EventCalendarMail($user->name));

                    // Asunto del email
                    $custom = json_decode('{"subject": "Invitación al evento '.$event->title.'"}');
                    // Asigna el evento al usuario
                    $user->event = $event;
                    // Envia el email al usuario
                    Mail::to($user)->send(new SendMail('intranet::events.email_new_event', $user, $custom));
                }
            }

            $event->users;
    
    
            // Valida si viene usuarios para actualizar
            // if (empty($input['calendar_users'])) {
            //     $event->users()->sync([]);
            // } else {
            //     // Inserta relacion con usuarios
            //     $event->users()->sync($input['calendar_users']);

            //     // Valida si el evento es cancelado
            //     if ($event->state == 2) {
            //         // Recorre los usuarios asignados al evento
            //         foreach ($input['calendar_users'] as $userId) {
            //             // Obtiene los datos del usuario
            //             $user = User::find($userId);
            //             // Envia la notificacion del evento
            //             // $user->notify(new EventCalendarMail($user->name));

            //             // Asunto del email
            //             $custom = json_decode('{"subject": "Cancelación del evento '.$event->title.'"}');
            //             // Asigna el evento al usuario
            //             $user->event = $event;
            //             // Envia el email al usuario
            //             Mail::to($user)->send(new SendMail('intranet::events.email_cancel_event', $user, $custom));
            //         }
            //     }
            // }
    
            // // Obtiene usuarios
            // $event->users;
            // // Recorre users y lo converte a array para asignar calendar_users que solo contiene los ids
            // $array_users = [];
    
            // foreach ($event->users as  $value) {
            //     $array_users[] = $users['id'];
            // }
        
            // $event->calendar_users = $array_users;
    
            // Prepara los datos del evento para comparar
            $arrDataEvent = Arr::dot(UtilController::dropNullEmptyList($event->replicate()->toArray(), true, 'work_groups', 'users')); // Datos actuales del evento
            $arrDataOldEvent = Arr::dot(UtilController::dropNullEmptyList($oldEvent->replicate()->toArray(), true, 'work_groups', 'users')); // Datos antiguos del evento
    
            // Datos diferenciados
            $arrDiff = array_diff_assoc($arrDataEvent, $arrDataOldEvent);
    
            // Valida si los datos antiguos son diferentes a los actuales
            if ( count($arrDiff) > 0) {
                // Asigna el id del evento para el registro del historial
                $input['intranet_events_id'] = $event->id;
                // Crea un nuevo registro de historial
                EventHistory::create($input);
            }

            
    
            // Obtiene el historial de cambios del evento
            $event->eventsHistories;
            // Obtiene los tipos de eventos
            $event->typeEvents;
        
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($event->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$e->getMessage().$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($oldEvent['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Event del almacenamiento
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
    public function destroy($id) {

        /** @var Event $event */
        $event = $this->eventRepository->find($id);

        if (empty($event)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        try {
            // Elimina el registro
            $event->delete();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\EventController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($event['id']) ?? 'Desconocido');
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('events').'.'.$fileType;

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
     * Muestra la vista para el CRUD de Event.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function calendarEvents(Request $request) {
        return view('intranet::events.calendar');
    }

    
    /**
     * Confirma asistencia al evento.
     *
     * @author Carlos Moises Garcia T. - Jul. 05 - 2020
     * @version 1.0.0
     *
     * @param $id identificador del evento
     *
     * @return Response
     */
    public function confirmAttendanceEvent($id, $confirmation) {

        // Obtiene los datos del evento
        $event = Event::find($id);

        // Valida si existe el evento
        if (!empty($event) && !empty($confirmation)) {
            $eventUser = $event->users()->where('users_id', Auth::user()->id)->get()->toArray();
            // Valida que el usuario este asignado en el evento y evento no este confirmado

            // dd($eventUser);
            if ($event->users()->where('users_id', Auth::user()->id) > 0) {
                $event->users()->where('users_id', Auth::user()->id)->update([
                    'confirmation' => $confirmation,
                ]);

                return  Redirect::to(config('app.url').'/intranet/events');
            }

            // $doctors = $event->users('users', function($query) {
            //     $query->where('users_id', Auth::user()->id);
            // })->get();
            // dd( $event->users()->where('users_id', Auth::user()->id)->count());

            // $doctors = Auth::user()->calendarEvents()->whereHas('calendarEvents', function($q) {
            //     $q->where('intranet_events_id',14);
            // });

            // dd($doctors->calendarEvents);
            // dd(Auth::user()->calendarEvents()->where('intranet_events_id', 134));
        }
    }
}
