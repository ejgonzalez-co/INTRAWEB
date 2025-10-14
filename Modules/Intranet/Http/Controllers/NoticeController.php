<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AntiXSSController;
use Modules\Intranet\Http\Requests\CreateNoticeRequest;
use Modules\Intranet\Http\Requests\UpdateNoticeRequest;
use Modules\Intranet\Repositories\NoticeRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Intranet\Models\Notice;
use Modules\Intranet\Models\Event;
use Modules\Intranet\Models\Download;
use Modules\Intranet\Models\Poll;
use Modules\Intranet\Models\NoticeMonitoringControl;

use Illuminate\Support\Arr;
use Carbon\Carbon;


/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class NoticeController extends AppBaseController {

    /** @var  NoticeRepository */
    private $noticeRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(NoticeRepository $noticeRepo) {
        $this->noticeRepository = $noticeRepo;
    }

    /**
     * Muestra la vista para el CRUD de Notice.
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
            return view('intranet::notices.index');
        }
        return view("auth.forbidden");
    }
    public function indexAll(Request $request) {
        return view('intranet::notices.index_notices');
    }

    public function homePanel(Request $request) {
        return view('intranet::notices.index_public');
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

        $notices = Notice::with(['category','noticeMonitoringControl'])->latest()->get();

        // $notices = $this->noticeRepository->all();
        return $this->sendResponse($notices->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function allPublic() {
        $dateNow = date("Y-m-d");

        $notices = Notice::with(['category'])->where("state",1)
        ->whereDate('date_end', '>=', $dateNow)
        ->whereDate('date_start', '<=', $dateNow)->latest()->get();

        // $notices = $this->noticeRepository->all();
        return $this->sendResponse($notices->toArray(), trans('data_obtained_successfully'));
    }

    public function buscador(Request $request) {
        // Obtiene los criterio de búsqueda del usuario
        $parametros = $request->all();
        // Almacena los resultados de la búsqueda por cada uno de los componentes
        $resultadoLista = [];

        // ***** Sección del componente de NOTICIAS *****
        $admNoticias = Auth::user()->hasRole("Administrador intranet");
        // Condición para validar que el usuario que esta en sesión sea un administrador de intranet
        if($admNoticias) {
            $notices = Notice::with(['category'])
                ->where('title','like','%'.$parametros["query"].'%')
                // ->orwhere('content','like','%'.$parametros["query"].'%')
                ->orwhere('keywords','like','%'.$parametros["query"].'%')->latest()->get();
        } else {
            // Fecha actual del sistema
            $dateNow = date("Y-m-d H:i:s");
            // Si no es un admin, obtiene solo las noticias que esten dentro del rango de fechas estipuladas por la noticia
            $notices = Notice::with(['category'])
                ->where("state",1)
                ->whereDate('date_end', '>=', $dateNow)
                ->whereDate('date_start', '<=', $dateNow)
                ->where('title','like','%'.$parametros["query"].'%')
                // ->orwhere('content','like','%'.$parametros["query"].'%')
                ->orwhere('keywords','like','%'.$parametros["query"].'%')->latest()->get();
        }

        // Almacena el resultado de la búsqueda
        $resultadoLista["Noticias"] = ["enlace" => "notices-public", "contenido" => $notices->toArray()];
        // ***** Fin de sección del componente de noticias *****



        // ***** Sección del componente de EVENTOS *****
        $admEventos = Auth::user()->hasRole("Administrador intranet de eventos");
        // Condición para validar que el usuario que esta en sesión sea un administrador de intranet
        if($admEventos) {
            $events = Event::with(['typeEvents', 'workGroups', 'users', 'eventsHistories'])
            ->where('title','like','%'.$parametros["query"].'%')
            ->orwhere('description','like','%'.$parametros["query"].'%')
            ->latest()->get()->toArray();

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
            $events = Event::with(['workGroups:id','users'])
                ->where("state", "2")
                ->where('title','like','%'.$parametros["query"].'%')
                ->orwhere('description','like','%'.$parametros["query"].'%')
                ->latest()->get()->filter(function ($event) use ($groups) {

                $eventGroups = $event->workGroups->toArray();
                //El método Arr::flatten convierte un arreglo multidimensional en uno simple, ejemplo: https://styde.net/manejo-de-arreglos-con-laravel/
                $eventGroups = Arr::flatten($eventGroups);

                $eventUsers = $event->users->toArray();

                $eventUsers = Arr::flatten($eventUsers);

                return !!array_intersect($eventGroups, $groups) ? true : ($eventUsers ? $eventUsers[5] == Auth::user()->id : false);
            })->values()->toArray();
        }
        // Almacena el resultado de la búsqueda
        $resultadoLista["Eventos"] = ["enlace" => "events-public", "contenido" => $events];
        // ***** Fin de sección del componente de eventos *****



        // ***** Sección del componente de DESCARGAS *****
        $admDescargas = Auth::user()->hasRole("Administrador intranet de descargas");

        // Condición para validar que el usuario que esta en sesión sea un administrador de descargas
        if($admDescargas) {
            // Obtiene todas las descargas dependiendo del criterio de búsqueda hecho por el usuario
            $downloads = Download::with(["downloadTagsSelected", "users", "owner", "categorie", "fileVote", "downloadLicense"])
                ->where('title','like','%'.$parametros["query"].'%')
                ->where('description','like','%'.$parametros["query"].'%')
                ->orwhere('metakey','like','%'.$parametros["query"].'%')->latest()->get();
        } else {
            // Valida si el usuario en sesion pertenece a los grupos de trabajo de la descarga
            $groups =  DB::table('users_work_groups')
                ->where('users_id', Auth::user()->id)
                ->pluck("work_groups_id")->toArray();
            // De no ser un administrador, le muestra solo las descargas que esten publicados, como también en el rago de fechas activas; consulta los grupos y usuarios de los archivos
            $downloads = Download::with(["downloadTagsSelected", "users", "owner", "categorie", "fileVote", "downloadLicense"])
                ->where("published", "Publicado")
                ->where('publish_up', "<=", Carbon::now())
                ->where('publish_down', ">=", Carbon::now())
                ->where('title','like','%'.$parametros["query"].'%')
                ->orwhere('description','like','%'.$parametros["query"].'%')
                ->orwhere('metakey','like','%'.$parametros["query"].'%')->latest()->get()
                ->filter(function ($download) use ($groups) {
                    $download_grupos = $download->categorie->downloadAccessGroups->toArray();
                    //El método Arr::flatten convierte un arreglo multidimensional en uno simple, ejemplo: https://styde.net/manejo-de-arreglos-con-laravel/
                    $download_grupos_acceso = Arr::flatten($download_grupos);
                    // Obtiene todos los usuarios que tienen acceso a la descarga
                    $download_users_acceso = $download->categorie->downloadAccessUsers->toArray();
                    // Convierte y minimiza la estructura del arreglo asosiativo, a un arreglo mas simple
                    Arr::flatten($download_users_acceso);

                    return !!array_intersect($download_grupos_acceso, $groups) ? true : ($download_users_acceso ? $download_users_acceso[0] == Auth::user()->id : false);
                })->values();
        }
        // Almacena el resultado de los arhivos de descarga de la búsqueda
        $resultadoLista["Descargas"] = ["enlace" => "downloads-public", "contenido" => $downloads->toArray()];
        // ***** Fin de sección del componente de descargas *****

        // ***** Sección del componente de ENCUESTAS DE SATISFACCIÓN *****
        $admEncuestas = Auth::user()->hasRole("Administrador intranet de encuestas");
        // Condición para validar que el usuario que esta en sesión sea un administrador de intranet
        if($admEncuestas) {
            // Obtiene las encuestas con relacion a usuarios y grupos, el admin puede ver todo
            $encuestas = Poll::with(['users','workGroups'])
                ->where('title','like','%'.$parametros["query"].'%')
                ->orwhere('description','like','%'.$parametros["query"].'%')->latest()->get()->toArray();

            // Recorre recorre las encuestas por grupos y asigna el valor de poll_work_groups con los ids
            for ($i=0; $i < count($encuestas) ; $i++) {
                foreach ($encuestas[$i]['work_groups'] as  $value) {
                    //contiene solo los ids de los grupos
                    $encuestas[$i]['poll_work_groups'][] = $value['id'];
                }
            }

            // Recorre recorre las encuestas y asigna el valor de poll_users con los ids
            for ($i=0; $i < count($encuestas) ; $i++) {
                foreach ($encuestas[$i]['users'] as  $value) {
                    //contiene solo los ids de los usuarios
                    $encuestas[$i]['poll_users'][] = $value['id'];
                }
            }
        } else {
            // Valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
            $groups =  DB::table('users_work_groups')
            ->where('users_id', Auth::user()->id)
            ->pluck("work_groups_id")->toArray();
            // Consula los grupos y usuarios de la encuesta, dependiendo del estado y el criterio de búsqueda del ususario
            $encuestas = Poll::with(['workGroups:id','users'])
                ->where("state", "2")
                ->where('title','like','%'.$parametros["query"].'%')
                ->orwhere('description','like','%'.$parametros["query"].'%')->latest()->get()->filter(function ($poll) use ($groups) {
                $poll_grupos = $poll->workGroups->toArray();
                // El método Arr::flatten convierte un arreglo multidimensional en uno simple, ejemplo: https://styde.net/manejo-de-arreglos-con-laravel/
                $poll_grupos = Arr::flatten($poll_grupos);
                // Obtiene los usuarios validados en la cuesta
                $poll_users = $poll->users->toArray();
                // Convierte el arreglo de encuestas, en uno básico
                $poll_users = Arr::flatten($poll_users);
                // Retorna las encuestas que el usuario tiene permitido ver
                return !!array_intersect($poll_grupos, $groups) ? true : ($poll_users ? $poll_users[5] == Auth::user()->id : false);
            })->values()->toArray();
        }
        // Almacena el resultado de las encuestas de la búsqueda
        $resultadoLista["Encuestas"] = ["enlace" => "polls-public", "contenido" => $encuestas];
        // ***** Fin de sección del componente de encuestas de satisfacción *****

        return $this->sendResponse($resultadoLista, trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Sep. 23 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function registroConsultaNotices(Request $request) {

        $notice = Notice::find($request["query"]);

        if (empty($notice)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        return $this->sendResponse($notice->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Sep. 23 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function registroConsultaDownloads(Request $request) {

        $download = Download::find($request["query"]);

        if (empty($download)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        return $this->sendResponse($download->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Sep. 23 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function registroConsultaPolls(Request $request) {

        $poll = Poll::find($request["query"]);

        if (empty($poll)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        return $this->sendResponse($poll->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Sep. 23 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function registroConsultaEvents(Request $request) {

        $event = Event::find($request["query"]);

        if (empty($event)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        return $this->sendResponse($event->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateNoticeRequest $request
     *
     * @return Response
     */
    public function store(CreateNoticeRequest $request) {

        $input = $request->all();

        if(!empty($input["annexes"])){
            $input["annexes"] = implode(",",$input["annexes"]);
        }

        // Sanatiza los datos del textarea
        $input["content"] = AntiXSSController::xssClean($input["content"]);

        // Reemplaza los hipervinculos para al momento de renderizar los links tengan mejor usabilidad
        $input["content"] = preg_replace("/<a\s+href=/i", "<a target='_blank' href=",$input["content"]);
        $input["content"] = preg_replace("/<\/a>/i", " <i class='fas fa-external-link-alt'></i></a>",$input["content"]);

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input["users_name"]=Auth::user()->name;
            $input["users_id"]=Auth::user()->id;
            $input["views"]=0;

            // Valida si se ingresa la imagen
            if ($request->hasFile('image_presentation')) {
                $input['image_presentation'] = substr($input['image_presentation']->store('public/intranet/notice/presentation'), 7);
            } else {
                // Establece imagen  por defecto
                $input['image_presentation'] = 'imagen_default_noticias.png';
            }

             // Valida si se ingresa la imagen
            if ($request->hasFile('image_banner')) {
                $input['image_banner'] = substr($input['image_banner']->store('public/intranet/notice/banners'), 7);
            } else {
                // Establece imagen  por defecto
                $input['image_banner'] = 'imagen_default_noticias.png';
            }

            // Inserta el registro en la base de datos
            $notice = $this->noticeRepository->create($input);
            $notice->category;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($notice->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author German Gonzalez V. - Sep. 23 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) {

        $notice = $this->noticeRepository->find($id);

        if (empty($notice)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        //Consulta si hay ya un registro de de este usuarios
        $monitoreo = NoticeMonitoringControl::where('intranet_news_id',$id)->where('user_id',Auth::user()->id)->get()->first();

        //Si no encuentra nada y es igual a null el crea el registro
        if ($monitoreo == null) {
            NoticeMonitoringControl::create([
                'intranet_news_id' => $id,
                'name' => Auth::user()->name,
                'user_id'=> Auth::user()->id,
                'fullname' =>Auth::user()->fullname,
                'fechas_visualizaciones' => date('Y-m-d H:i:s')
            ]);
        //De lo contrario actualiza y agrega la fecha actual al campo fechas_visualizaciones
        }else{
            NoticeMonitoringControl::where('id',$monitoreo->id)->update([
                'fechas_visualizaciones' => $monitoreo->fechas_visualizaciones.",".date('Y-m-d H:i:s')
            ]);
        }




        $totalViews = $notice->views+1;
        $input["views"] = $totalViews;

        $notice = $this->noticeRepository->update($input, $id);
        $notice->category;

        return $this->sendResponse($notice->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateNoticeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNoticeRequest $request) {

        $input = $request->all();

        /** @var Notice $notice */
        $notice = $this->noticeRepository->find($id);

        if (empty($notice)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if(!empty($input["annexes"])){
                $input["annexes"] = implode(",",$input["annexes"]);
            }

            // Sanatiza los datos del textarea
            $input["content"] = AntiXSSController::xssClean($input["content"]);

            // Reemplaza los hipervinculos para al momento de renderizar los links tengan mejor usabilidad
            $input["content"] = preg_replace("/<a\s+href=/i", "<a target='_blank' href=",$input["content"]);
            $input["content"] = preg_replace("/<i class='fas fa-external-link-alt'><\/i>/", "", $input["content"]);
            $input["content"] = preg_replace("/<\/a>/i", "<i class='fas fa-external-link-alt'></i></a>",$input["content"]);

            // Valida si se ingresa la imagen
            if ($request->hasFile('image_presentation')) {
                $input['image_presentation'] = substr($input['image_presentation']->store('public/intranet/notice/presentation'), 7);
            }

             // Valida si se ingresa la imagen
             if ($request->hasFile('image_banner')) {
                $input['image_banner'] = substr($input['image_banner']->store('public/intranet/notice/banners'), 7);
            }

            // Actualiza el registro
            $notice = $this->noticeRepository->update($input, $id);
            $notice->category;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($notice->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($notice['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Notice del almacenamiento
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

        /** @var Notice $notice */
        $notice = $this->noticeRepository->find($id);

        if (empty($notice)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $notice->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\NoticeController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($notice['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('notices').'.'.$fileType;

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
     * Obtiene las primeras 6 noticias del módulo de noticias
     *
     * @author Seven Soluciones Informáticas S.A.S. - Nov. 21 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function allPublicDashboard() {
        $dateNow = date("Y-m-d");

        $notices = Notice::with(['category'])->where("state",1)
        ->whereDate('date_end', '>=', $dateNow)
        ->whereDate('date_start', '<=', $dateNow)->latest()->take(6)->get();

        return $this->sendResponse($notices->toArray(), trans('data_obtained_successfully'));
    }
}
