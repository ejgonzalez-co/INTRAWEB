<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateInternalRequest;
use Modules\Correspondence\Http\Requests\UpdateInternalRequest;
use Modules\Correspondence\Repositories\InternalRepository;
use Modules\Correspondence\Repositories\InternalHistoryRepository;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\InternalTypes;
use Modules\Correspondence\Models\InternalRecipient;
use Modules\Configuracion\Models\Variables;
use Modules\Intranet\Models\Dependency;
use App\User;
use Modules\Correspondence\Http\Controllers\UtilController;


/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class InternalController extends AppBaseController
{

    /** @var  InternalRepository */
    private $internalRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(InternalRepository $internalRepo,InternalHistoryRepository $internalRepoHistory)
    {
        $this->internalRepository = $internalRepo;
        $this->InternalHistoryRepository = $internalRepoHistory;

    }

    /**
     * Muestra la vista para el CRUD de Internal.
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
        return view('correspondence::internals.index');
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

        //valida si es un administrador de intranet
        if (Auth::user()->hasRole('Correspondencia Interna Admin')) {
            $internals = Internal::with(['type'])->where("year", 2022)->latest()->get()->toArray();
        } else {

            //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
            $groups =  DB::table('users_work_groups')
            ->where('users_id', Auth::user()->id)
            ->pluck("work_groups_id")->toArray();

            $internals= Internal::select('correspondence_internal.*', 'correspondence_internal_recipient.name')
            ->join('correspondence_internal_recipient', 'correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
            ->where("correspondence_internal.year", 2022)
            ->where("correspondence_internal.internal_all", 1)
            ->orWhere("correspondence_internal_recipient.users_id", Auth::user()->id)
            ->orWhere("correspondence_internal_recipient.dependencias_id", Auth::user()->id_dependencia)
            ->orWhere("correspondence_internal_recipient.cargos_id", Auth::user()->id_cargo)
            ->orWhereIn("correspondence_internal_recipient.work_groups_id",$groups)
            ->get()->toArray();

        }
        return $this->sendResponse($internals, trans('data_obtained_successfully'));

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

        $internals = $this->internalRepository->find($id);
        if (empty($internals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $internals->internalRecipients;

        $internal_recipients = $internals->toArray()["internal_recipients"];
        // dd($internal_recipients);
           // Valida si viene usuarios para asignar
           if (!empty($internal_recipients)) {
            $internals["recipientsList"] = $internal_recipients;

            }
        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Obtiene datos completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function getDataEdit($id)
    {

        $internals = $this->internalRepository->find($id);
        if (empty($internals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $internals->internalRecipients;

        $internal_recipients = $internals->toArray()["internal_recipients"];
        // dd($internal_recipients);
           // Valida si viene usuarios para asignar
           if (!empty($internal_recipients)) {
            $internals["recipientsList"] = $internal_recipients;

            }
        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
    }
    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateInternalRequest $request
     *
     * @return Response
     */
    public function store(CreateInternalRequest $request)
    {
        $input = $request->all();
        // Valida si no seleccionó ningún adjunto
        if($input["document_pdf"] ?? false) {
            $input['document_pdf'] = implode(",", $input["document_pdf"]);
        }
    
        $input["users_id"]=Auth::user()->id;

        $input["dependencias_id"]=5;

        $input["state"]="Público";
        $input["year"] = date("Y");
        
        $informationUser = User::select("name","id_dependencia")->where('id', $input["from_id"])->first();

        $input["from"]=$informationUser["name"];


        $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();

        $input["dependencias_id"] = $infoDependencia["id"];
        $input["dependency_from"] = $infoDependencia["name"];

        $formatConsecutive = Variables::where('name' , 'var_internal_consecutive')->pluck('value')->first();
        $formatConsecutivePrefix = Variables::where('name' , 'var_internal_consecutive_prefix')->pluck('value')->first();

        //DP
        $DP = $infoDependencia["codigo"];
        $siglas = $infoDependencia["codigo_oficina_productora"];

        //PL
        $PL = InternalTypes::where('id', $input["type"])->pluck('prefix')->first();

        //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
        $dataConsecutive = UtilController::getNextConsecutive('Internal',$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas);

        $input["consecutive"] = $dataConsecutive['consecutive'];
        $input["consecutive_order"] = $dataConsecutive['consecutive_order'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {
        // Inserta el registro en la base de datos
        $internal = $this->internalRepository->create($input);

        // Valida si viene usuarios para asignar
        if (!empty($input['recipientsList'])) {
            //texto para almacenar en la tabla interna 
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($input['recipientsList'] as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent,true);
                $textRecipients[] = $recipentArray["name"];
                $recipentArray["correspondence_internal_id"] = $internal->id;
                InternalRecipient::create($recipentArray);
            }
            $internal->internalRecipients;
            $updateInternal["recipients"] = implode("<br>", $textRecipients);
            $internal = $this->internalRepository->update($updateInternal,$internal->id);
        }


        $input['correspondence_internal_id'] = $internal->id;
        // Crea un nuevo registro de historial
        $this->InternalHistoryRepository->create($input);
        //Obtiene el historial
        $internal->internalHistory;

        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($internal->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . ' Linea: '. $e->getLine());
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
     * @param UpdateInternalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInternalRequest $request)
    {
        $input = $request->all();

        /** @var Internal $internal */
        $internal = $this->internalRepository->find($id);

        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
    
        if($input["document_pdf"] ?? false) {
            $input['document_pdf'] = implode(",", $input["document_pdf"]);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $internal = $this->internalRepository->update($input, $id);

                    // Valida si viene usuarios para asignar
            if (!empty($input['recipientsList'])) {

                //borra todo para volver a insertarlo
                InternalRecipient::where('correspondence_internal_id', $id)->delete();

                //texto para almacenar en la tabla interna 
                $textRecipients = array();
                //recorre los destinatarios
                foreach ($input['recipientsList'] as $recipent) {
                    //array de destinatarios
                    $recipentArray = json_decode($recipent,true);
                    $textRecipients[] = $recipentArray["name"];
                    $recipentArray["correspondence_internal_id"] = $id;
                    InternalRecipient::create($recipentArray);
                }
                $internal->internalRecipients;
                $updateInternal["recipients"] = implode("<br>", $textRecipients);
                $internal = $this->internalRepository->update($updateInternal,$id);
            }else{

                //borra todo para volver a insertarlo
                InternalRecipient::where('correspondence_internal_id', $id)->delete();
                $updateInternal["recipients"] = "";
                $internal = $this->internalRepository->update($updateInternal,$id);
            }

            $input['correspondence_internal_id'] = $internal->id;
            // Crea un nuevo registro de historial
            $this->InternalHistoryRepository->create($input);
            //Obtiene el historial
            $internal->internalHistory;

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine().' Consecutivo: '.($internal['consecutive'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Internal del almacenamiento
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

        /** @var Internal $internal */
        $internal = $this->internalRepository->find($id);

        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $internal->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine().' Consecutivo: '.($internal['consecutive'] ?? 'Desconocido' ));
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
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('internals').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } elseif (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getRecipients(Request $request)
    {
        // Usuarios
        $query =$request->input('query');
        $users = DB::table('users')
        ->select(DB::raw('CONCAT("Usuario ", users.name) AS name, users.id as users_id, "Usuario" AS type'))
        ->where('users.name', 'like', '%'.$query.'%')
        ->whereNotNull('users.id_cargo')
        ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
        ->get();
        
        // Grupos
        $query =$request->input('query');
        $grupos = DB::table('work_groups')
        ->select(DB::raw('CONCAT("Grupo ", work_groups.name) AS name, work_groups.id as work_groups_id, "Grupo" AS type'))
        ->where('work_groups.name', 'like', '%'.$query.'%')
        ->get();

        // Dependencias
        $query =$request->input('query');
        $dependencias = DB::table('dependencias')
        ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS name, dependencias.id as dependencias_id, "Dependencia" AS type'))
        ->where('dependencias.nombre', 'like', '%'.$query.'%')
        ->get();

        // Cargos
        $query =$request->input('query');
        $position = DB::table('cargos')
        ->select(DB::raw('CONCAT("Cargo ", cargos.nombre) AS name, cargos.id as cargos_id, "Cargo" AS type'))
        ->where('cargos.nombre', 'like', '%'.$query.'%')
        ->get();

        $recipients = array_merge($users->toArray(), $grupos->toArray(), $dependencias->toArray(), $position->toArray());

        return $this->sendResponse($recipients, trans('data_obtained_successfully'));
    }


      /**
     * Obtiene los tipos de documentos
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTypes(Request $request)
    {

        $types = InternalTypes::orderBy("name")->get()->toArray();

        return $this->sendResponse($types, trans('data_obtained_successfully'));
    }


    public function guardarAdjuntoRotulo($id, Request $request) {
        
        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            // Selecciona los adjuntos de la correspondencia
            $adjuntos_interna = Internal::where("id", $id)->pluck("document_pdf")->first();
            // Valida si existe una variable con un file
            if ($request->hasFile('document_pdf')) {
                $input['document_pdf'] = substr($input['document_pdf']->store('public/container/internal_2022'), 7);
            }
            // Valida si la correspondencia ya tenía adjuntos previamente
            if($adjuntos_interna)
            {
                $adjuntos = explode(",", $adjuntos_interna);
                // Añade el nuevo adjunto del rótulo al array de adjuntos
                $adjuntos[] = $input['document_pdf'];
                
                $input['document_pdf'] = implode(",", $adjuntos);
            }

            // Actualiza el campo adjunto (document_pdf) de la correspondencia
            $internal_rotule = $this->internalRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($internal_rotule->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine() . ' ID: ' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticProvider->toArray(), trans('msg_success_save'));
    }
}
