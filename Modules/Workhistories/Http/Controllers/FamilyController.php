<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateFamilyRequest;
use Modules\Workhistories\Http\Requests\UpdateFamilyRequest;
use Modules\Workhistories\Repositories\FamilyRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\Family;
use Modules\Workhistories\Models\WorkHistoriesActive;
use Illuminate\Support\Facades\Auth;



/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class FamilyController extends AppBaseController {

    /** @var  FamilyRepository */
    private $familyRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(FamilyRepository $familyRepo) {
        $this->familyRepository = $familyRepo;
    }

    /**
     * Muestra la vista para el CRUD de Family.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $work_historie = WorkHistoriesActive::where('id',$request->wh)->first();
        $work_historie_id = $request->wh;
        $name_work_historie =$work_historie["name"]." ".$work_historie["surname"];
        return view('workhistories::families.index',compact(['work_historie_id','name_work_historie']));
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

        $families = Family::where('work_histories_id',$id)->latest()->get()->toArray();
        return $this->sendResponse($families, trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateFamilyRequest $request
     *
     * @return Response
     */
    public function store(CreateFamilyRequest $request,$id) {

        $input = $request->all();
        $input["work_histories_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["state"]="Activo";

        $family = $this->familyRepository->create($input);

        return $this->sendResponse($family->toArray(), trans('msg_success_save'));
    }



    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateFamilyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFamilyRequest $request) {

        $input = $request->all();

        /** @var Family $family */
        $family = $this->familyRepository->find($id);

        if (empty($family)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $family = $this->familyRepository->update($input, $id);

        return $this->sendResponse($family->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Family del almacenamiento
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

        /** @var Family $family */
        $family = $this->familyRepository->find($id);

        if (empty($family)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $family->delete();

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
        $fileName = time().'-'.trans('families').'.'.$fileType;

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
