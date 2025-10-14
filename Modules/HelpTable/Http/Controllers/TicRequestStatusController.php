<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicRequestStatusRequest;
use Modules\HelpTable\Http\Requests\UpdateTicRequestStatusRequest;
use Modules\HelpTable\Repositories\TicRequestStatusRepository;
use Modules\HelpTable\Models\TicRequestStatus;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
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
class TicRequestStatusController extends AppBaseController {

    /** @var  TicRequestStatusRepository */
    private $ticRequestStatusRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicRequestStatusRepository $ticRequestStatusRepo) {
        $this->ticRequestStatusRepository = $ticRequestStatusRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicRequestStatus.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC"])){
            return view('help_table::tic_request_statuses.index');
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
        // Valida si el usuario logueado es un administrador
         if (Auth::user()->hasRole('Administrador TIC')) {
            $tic_request_statuses = $this->ticRequestStatusRepository->all();
        } else if(Auth::user()->hasRole('Soporte TIC')){
            $tic_request_statuses = TicRequestStatus::
            whereIn('id', [2, 3, 4, 6])
            ->latest()
            ->get();
        } else {
            $tic_request_statuses = TicRequestStatus::
            where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->where('id', '!=', 6)
            ->where('id', '!=', 7)
            ->where('id', '!=', 4)
            ->latest()
            ->get();
        }
        return $this->sendResponse($tic_request_statuses->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicRequestStatusRequest $request
     *
     * @return Response
     */
    public function store(CreateTicRequestStatusRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $ticRequestStatus = $this->ticRequestStatusRepository->create($input);

            return $this->sendResponse($ticRequestStatus->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestStatusController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestStatusController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateTicRequestStatusRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicRequestStatusRequest $request) {
        
        $input = $request->all();

        /** @var TicRequestStatus $ticRequestStatus */
        $ticRequestStatus = $this->ticRequestStatusRepository->find($id);

        if (empty($ticRequestStatus)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $ticRequestStatus = $this->ticRequestStatusRepository->update($input, $id);
        
            return $this->sendResponse($ticRequestStatus->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestStatusController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestStatusController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicRequestStatus del almacenamiento
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

        /** @var TicRequestStatus $ticRequestStatus */
        $ticRequestStatus = $this->ticRequestStatusRepository->find($id);

        if (empty($ticRequestStatus)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticRequestStatus->delete();

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
        $fileName = time().'-'.trans('tic_request_statuses').'.'.$fileType;

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
