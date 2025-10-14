<?php

namespace Modules\Workhistories\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Workhistories\Http\Requests\CreateFamilyPensionerRequest;
use Modules\Workhistories\Http\Requests\UpdateFamilyPensionerRequest;
use Modules\Workhistories\Repositories\FamilyPensionerRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\Workhistories\Models\WorkHistPensioner;

use Modules\Workhistories\Models\FamilyPensioner;
use Illuminate\Support\Facades\Auth;


/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class FamilyPensionerController extends AppBaseController {

    /** @var  FamilyPensionerRepository */
    private $familyPensionerRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(FamilyPensionerRepository $familyPensionerRepo) {
        $this->familyPensionerRepository = $familyPensionerRepo;
    }

    /**
     * Muestra la vista para el CRUD de FamilyPensioner.
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
        return view('workhistories::family_pensioners.index',compact(['work_historie_id','name_work_historie']));
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
   
        $family_pensioners = FamilyPensioner::where('work_histories_p_id',$id)->latest()->get()->toArray();
        return $this->sendResponse($family_pensioners, trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateFamilyPensionerRequest $request
     *
     * @return Response
     */
    public function store(CreateFamilyPensionerRequest $request,$id) {

        $input = $request->all();

        $input["work_histories_p_id"]=$id;
        $input["users_id"]=Auth::user()->id;
        $input["state"]="Activo";


        $familyPensioner = $this->familyPensionerRepository->create($input);

        return $this->sendResponse($familyPensioner->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateFamilyPensionerRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFamilyPensionerRequest $request) {

        $input = $request->all();

        /** @var FamilyPensioner $familyPensioner */
        $familyPensioner = $this->familyPensionerRepository->find($id);

        if (empty($familyPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $familyPensioner = $this->familyPensionerRepository->update($input, $id);

        return $this->sendResponse($familyPensioner->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un FamilyPensioner del almacenamiento
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

        /** @var FamilyPensioner $familyPensioner */
        $familyPensioner = $this->familyPensionerRepository->find($id);

        if (empty($familyPensioner)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $familyPensioner->delete();

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
        $fileName = time().'-'.trans('family_pensioners').'.'.$fileType;

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
