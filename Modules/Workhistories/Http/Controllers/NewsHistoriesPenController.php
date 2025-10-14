<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateNewsHistoriesPenRequest;
use Modules\Workhistories\Http\Requests\UpdateNewsHistoriesPenRequest;
use Modules\Workhistories\Repositories\NewsHistoriesPenRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\WorkHistPensioner;
use Modules\Workhistories\Models\NewsHistoriesPen;
use Illuminate\Support\Facades\Auth;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class NewsHistoriesPenController extends AppBaseController {

    /** @var  NewsHistoriesPenRepository */
    private $newsHistoriesPenRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(NewsHistoriesPenRepository $newsHistoriesPenRepo) {
        $this->newsHistoriesPenRepository = $newsHistoriesPenRepo;
    }

    /**
     * Muestra la vista para el CRUD de NewsHistoriesPen.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $work_historie = WorkHistPensioner::where('id',$request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie =$work_historie["name"]." ".$work_historie["surname"];
        return view('workhistories::news_histories_pens.index',compact(['work_historie_id','name_work_historie']));
        }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all($id) {

        $news_histories = NewsHistoriesPen::where('work_histories_p_id',$id)->latest()->get()->toArray();
        return $this->sendResponse($news_histories, trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateNewsHistoriesPenRequest $request
     *
     * @return Response
     */
    public function store($id,CreateNewsHistoriesPenRequest $request) {

        $input = $request->all();
        $input["work_histories_p_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["users_name"]=Auth::user()->name;


        $newsHistories = $this->newsHistoriesPenRepository->create($input);

        return $this->sendResponse($newsHistories->toArray(), trans('msg_success_save'));
    }



    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateNewsHistoriesPenRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNewsHistoriesPenRequest $request) {

        $input = $request->all();

        /** @var NewsHistoriesPen $newsHistoriesPen */
        $newsHistoriesPen = $this->newsHistoriesPenRepository->find($id);

        if (empty($newsHistoriesPen)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        $newsHistoriesPen = $this->newsHistoriesPenRepository->update($input, $id);

        return $this->sendResponse($newsHistoriesPen->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un NewsHistoriesPen del almacenamiento
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

        /** @var NewsHistoriesPen $newsHistoriesPen */
        $newsHistoriesPen = $this->newsHistoriesPenRepository->find($id);

        if (empty($newsHistoriesPen)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $newsHistoriesPen->delete();

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
        $fileName = time().'-'.trans('news_histories_pens').'.'.$fileType;

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
