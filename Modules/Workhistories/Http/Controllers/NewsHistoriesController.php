<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateNewsHistoriesRequest;
use Modules\Workhistories\Http\Requests\UpdateNewsHistoriesRequest;
use Modules\Workhistories\Repositories\NewsHistoriesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

use Modules\Workhistories\Models\NewsHistories;
use Modules\Workhistories\Models\WorkHistoriesActive;
use Illuminate\Support\Facades\Storage;
use Modules\Workhistories\Repositories\WorkHistoriesActiveRepository;
use Modules\Workhistories\Repositories\DocumentsNewsRepository;
use Illuminate\Support\Facades\Auth;

/**
 * DEscripcion de la clase
 *
 * @author Erika Johana Gonzalez C. Nov.  15 - 2020
 * @version 1.0.0
 */
class NewsHistoriesController extends AppBaseController
{

    /** @var  NewsHistoriesRepository */
    private $newsHistoriesRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
     * @version 1.0.0
     */
    public function __construct(NewsHistoriesRepository $newsHistoriesRepo)
    {
        $this->newsHistoriesRepository = $newsHistoriesRepo;
    }

    /**
     * Muestra la vista para el CRUD de NewsHistories.
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $work_historie = WorkHistoriesActive::where('id', $request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie = $work_historie["name"] . " " . $work_historie["surname"];
        return view('workhistories::news_histories.index', compact(['work_historie_id', 'name_work_historie']));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id)
    {

        $news_histories = NewsHistories::where('work_histories_id', $id)->latest()->get()->toArray();
        return $this->sendResponse($news_histories, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
     * @version 1.0.0
     *
     * @param CreateNewsHistoriesRequest $request
     *
     * @return Response
     */
    public function store($id, CreateNewsHistoriesRequest $request)
    {

        $input = $request->all();
        $input["work_histories_id"] = $id;
        $input["users_id"] = Auth::user()->id;
        $input["users_name"] = Auth::user()->name;


        $newsHistories = $this->newsHistoriesRepository->create($input);

        return $this->sendResponse($newsHistories->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateNewsHistoriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNewsHistoriesRequest $request)
    {

        $input = $request->all();

        /** @var NewsHistories $newsHistories */
        $newsHistories = $this->newsHistoriesRepository->find($id);

        if (empty($newsHistories)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $newsHistories = $this->newsHistoriesRepository->update($input, $id);

        return $this->sendResponse($newsHistories->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un NewsHistories del almacenamiento
     *
     * @author Erika Johana Gonzalez C. Nov.  15 - 2020
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

        /** @var NewsHistories $newsHistories */
        $newsHistories = $this->newsHistoriesRepository->find($id);

        if (empty($newsHistories)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $newsHistories->delete();

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
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('news_histories') . '.' . $fileType;

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
