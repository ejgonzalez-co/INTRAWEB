<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicKnowledgeBaseRequest;
use Modules\HelpTable\Http\Requests\UpdateTicKnowledgeBaseRequest;
use Modules\HelpTable\Repositories\TicKnowledgeBaseRepository;
use Modules\HelpTable\Models\TicKnowledgeBase;
use Modules\HelpTable\Models\TicRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Exports\RequestExport;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicKnowledgeBaseController extends AppBaseController {

    /** @var  TicKnowledgeBaseRepository */
    private $ticKnowledgeBaseRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicKnowledgeBaseRepository $ticKnowledgeBaseRepo) {
        $this->ticKnowledgeBaseRepository = $ticKnowledgeBaseRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicKnowledgeBase.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC","Soporte TIC"])){
            return view('help_table::tic_knowledge_bases.index');
        }
        return view("auth.forbidden");
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
        $tic_knowledge_bases = TicKnowledgeBase::
        with(['ticTypeRequest', 'users'])
        ->latest()->get();
        return $this->sendResponse($tic_knowledge_bases->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicKnowledgeBaseRequest $request
     *
     * @return Response
     */
    public function store(CreateTicKnowledgeBaseRequest $request) {

        $input = $request->all();

        try {
            $input['users_id'] = Auth::user()->id;

            if(!empty($input['attached'])){
                $input['attached'] = implode(",", $input["attached"]);
            }
            // Inserta el registro en la base de datos
            $ticKnowledgeBase = $this->ticKnowledgeBaseRepository->create($input);

            if (!empty($input['component'])) {
                $ticKnowledgeBase = TicRequest::find($input['ht_tic_requests_id']);
                $ticKnowledgeBase->ticKnowledgeBases;
            } else {
                $ticKnowledgeBase->ticTypeRequest;
                $ticKnowledgeBase->users;
            }

            return $this->sendResponse($ticKnowledgeBase->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicKnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicKnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicKnowledgeBaseRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicKnowledgeBaseRequest $request) {

        $input = $request->all();

        /** @var TicKnowledgeBase $ticKnowledgeBase */
        $ticKnowledgeBase = $this->ticKnowledgeBaseRepository->find($id);

        if (empty($ticKnowledgeBase)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            if(!empty($input['attached'])){
                $input['attached'] = implode(",", $input["attached"]);
            }
            // Actualiza el registro
            $ticKnowledgeBase = $this->ticKnowledgeBaseRepository->update($input, $id);

            $ticKnowledgeBase->ticTypeRequest;
            $ticKnowledgeBase->users;
        
            return $this->sendResponse($ticKnowledgeBase->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicKnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicKnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicKnowledgeBase del almacenamiento
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

        /** @var TicKnowledgeBase $ticKnowledgeBase */
        $ticKnowledgeBase = $this->ticKnowledgeBaseRepository->find($id);

        if (empty($ticKnowledgeBase)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticKnowledgeBase->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    // public function export(Request $request) {
    //     $input = $request->all();

    //     // Tipo de archivo (extencion)
    //     $fileType = $input['fileType'];
    //     // Nombre de archivo con tiempo de creacion
    //     $fileName = time().'-'.trans('tic_knowledge_bases').'.'.$fileType;

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
     * Genera el reporte de encuestas en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - May. 26 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('tic_knowledge_bases').'.'.$fileType;
        
        return Excel::download(new RequestExport('help_table::tic_knowledge_bases.report_excel', $input['data'], 'f'), $fileName);
    }
}
