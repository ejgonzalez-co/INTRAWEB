<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreateProcessLeadersRequest;
use Modules\ContractualProcess\Http\Requests\UpdateProcessLeadersRequest;
use Modules\ContractualProcess\Repositories\ProcessLeadersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use App\User;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ProcessLeadersController extends AppBaseController {

    /** @var  ProcessLeadersRepository */
    private $processLeadersRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ProcessLeadersRepository $processLeadersRepo) {
        $this->processLeadersRepository = $processLeadersRepo;
    }

    /**
     * Muestra la vista para el CRUD de ProcessLeaders.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::process_leaders.index');
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
        $processLeaders = $this->processLeadersRepository->all();
        return $this->sendResponse($processLeaders->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateProcessLeadersRequest $request
     *
     * @return Response
     */
    public function store(CreateProcessLeadersRequest $request) {

        $input = $request->all();

        $user = User::find($input['users_id']);

        $input['leader_name'] = $user->name;

        $processLeaders = $this->processLeadersRepository->create($input);

        return $this->sendResponse($processLeaders->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateProcessLeadersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProcessLeadersRequest $request) {

        $input = $request->all();

        /** @var ProcessLeaders $processLeaders */
        $processLeaders = $this->processLeadersRepository->find($id);

        if (empty($processLeaders)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $user = User::find($input['users_id']);

        $input['leader_name'] = $user->name;

        $processLeaders = $this->processLeadersRepository->update($input, $id);

        return $this->sendResponse($processLeaders->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un ProcessLeaders del almacenamiento
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

        /** @var ProcessLeaders $processLeaders */
        $processLeaders = $this->processLeadersRepository->find($id);

        if (empty($processLeaders)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $processLeaders->delete();

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
        $fileName = time().'-'.trans('process_leaders').'.'.$fileType;

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
