<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateQuotaPartsNewsUsersRequest;
use Modules\Workhistories\Http\Requests\UpdateQuotaPartsNewsUsersRequest;
use Modules\Workhistories\Repositories\QuotaPartsNewsUsersRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class QuotaPartsNewsUsersController extends AppBaseController {

    /** @var  QuotaPartsNewsUsersRepository */
    private $quotaPartsNewsUsersRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(QuotaPartsNewsUsersRepository $quotaPartsNewsUsersRepo) {
        $this->quotaPartsNewsUsersRepository = $quotaPartsNewsUsersRepo;
    }

    /**
     * Muestra la vista para el CRUD de QuotaPartsNewsUsers.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('workhistories::quota_parts_news_users.index');
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
        $quota_parts_news_users = $this->quotaPartsNewsUsersRepository->all();
        return $this->sendResponse($quota_parts_news_users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateQuotaPartsNewsUsersRequest $request
     *
     * @return Response
     */
    public function store(CreateQuotaPartsNewsUsersRequest $request) {

        $input = $request->all();

        $quotaPartsNewsUsers = $this->quotaPartsNewsUsersRepository->create($input);

        return $this->sendResponse($quotaPartsNewsUsers->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateQuotaPartsNewsUsersRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateQuotaPartsNewsUsersRequest $request) {

        $input = $request->all();

        /** @var QuotaPartsNewsUsers $quotaPartsNewsUsers */
        $quotaPartsNewsUsers = $this->quotaPartsNewsUsersRepository->find($id);

        if (empty($quotaPartsNewsUsers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $quotaPartsNewsUsers = $this->quotaPartsNewsUsersRepository->update($input, $id);

        return $this->sendResponse($quotaPartsNewsUsers->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un QuotaPartsNewsUsers del almacenamiento
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

        /** @var QuotaPartsNewsUsers $quotaPartsNewsUsers */
        $quotaPartsNewsUsers = $this->quotaPartsNewsUsersRepository->find($id);

        if (empty($quotaPartsNewsUsers)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $quotaPartsNewsUsers->delete();

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
        $fileName = time().'-'.trans('quota_parts_news_users').'.'.$fileType;

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
