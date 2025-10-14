<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateDownloadFileVoteRequest;
use Modules\Intranet\Http\Requests\UpdateDownloadFileVoteRequest;
use Modules\Intranet\Repositories\DownloadFileVoteRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

use Modules\Intranet\Models\DownloadFileVote;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class DownloadFileVoteController extends AppBaseController {

    /** @var  DownloadFileVoteRepository */
    private $downloadFileVoteRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DownloadFileVoteRepository $downloadFileVoteRepo) {
        $this->downloadFileVoteRepository = $downloadFileVoteRepo;
    }

    /**
     * Muestra la vista para el CRUD de DownloadFileVote.
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
            return view('intranet::download_file_votes.index');
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
        // $download_file_votes = $this->downloadFileVoteRepository->all();
        $download_file_votes = DownloadFileVote::with(["download", "users"])->latest()->get();
        return $this->sendResponse($download_file_votes->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDownloadFileVoteRequest $request
     *
     * @return Response
     */
    public function store(CreateDownloadFileVoteRequest $request) {

        $input = $request->all();

        try {

            $order = DownloadFileVote::max("ordering");

            $order = $order + 1;

            $input["ordering"] = $order;
            $input["users_id"] = Auth::user()->id;

            // Inserta el registro en la base de datos
            $downloadFileVote = $this->downloadFileVoteRepository->create($input);

            return $this->sendResponse($downloadFileVote->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadFileVoteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadFileVoteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdateDownloadFileVoteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDownloadFileVoteRequest $request) {

        $input = $request->all();

        /** @var DownloadFileVote $downloadFileVote */
        $downloadFileVote = $this->downloadFileVoteRepository->find($id);

        if (empty($downloadFileVote)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $downloadFileVote = $this->downloadFileVoteRepository->update($input, $id);
        
            return $this->sendResponse($downloadFileVote->toArray(), trans('msg_success_update'));
        } 
        catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadFileVoteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Intranet\Http\Controllers\DownloadFileVoteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($downloadFileVote['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DownloadFileVote del almacenamiento
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

        /** @var DownloadFileVote $downloadFileVote */
        $downloadFileVote = $this->downloadFileVoteRepository->find($id);

        if (empty($downloadFileVote)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $downloadFileVote->delete();

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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('download_file_votes').'.'.$fileType;

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
}
