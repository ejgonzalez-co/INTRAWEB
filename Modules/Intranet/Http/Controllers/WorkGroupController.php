<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateWorkGroupRequest;
use Modules\Intranet\Http\Requests\UpdateWorkGroupRequest;
use Modules\Intranet\Repositories\WorkGroupRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Carbon\Carbon;
use DB;
use Modules\Intranet\Models\WorkGroup;
use Illuminate\Support\Facades\Auth;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class WorkGroupController extends AppBaseController {

    /** @var  WorkGroupRepository */
    private $workGroupRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(WorkGroupRepository $workGroupRepo) {
        $this->workGroupRepository = $workGroupRepo;
    }

    /**
     * Muestra la vista para el CRUD de WorkGroup.
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
            return view('intranet::work_groups.index');
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
        // $work_groups = $this->workGroupRepository->all();
        // return $this->sendResponse($work_groups->toArray(), trans('data_obtained_successfully'));

        //$work_groups = WorkGroup::with(['users'])->latest()->get();
        $work_groups = WorkGroup::with(['users' => function($query) {
            $query->where('user_type', 'Funcionario')->where('block', '0')->paginate(15);
        }])->latest()->get();

        return $this->sendResponse($work_groups->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateWorkGroupRequest $request
     *
     * @return Response
     */
    public function store(CreateWorkGroupRequest $request) {

        $input = $request->all();

        /**
         * Valida si se ingresa la imagen de perfil
         */
        if ($request->hasFile('url_img_profile')) {
            $input['url_img_profile'] = substr($input['url_img_profile']->store('public/work_group/avatar'), 7);
        }else{
            $input['url_img_profile'] = null;
        }

        // Organiza campos booleanos
        $input['state'] = $this->toBoolean($input['state']);

        // Crea una nuevo registro de grupo de trabajo
        $workGroup = $this->workGroupRepository->create([
            'name'            => $input['name'],
            'description'     => $input['description'],
            'state'           => $input['state'],
            'url_img_profile' => $input['url_img_profile'],
            'end_date'        => $input['end_date'] ?? null
        ]);

        // Valida si viene usuarios para asignar
        if (!empty($input['users'])) {
            // Inserta relacion con usuarios
            $workGroup->users()->sync($input['users']);
        }
        $workGroup->users;

        return $this->sendResponse($workGroup->toArray(), trans('msg_success_save'));        
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateWorkGroupRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateWorkGroupRequest $request) {

        $input = $request->all();

        /** @var WorkGroup $workGroup */
        $workGroup = $this->workGroupRepository->find($id);

        if (empty($workGroup)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        /**
         * Valida si se ingresa la imagen de perfil
         */
        if ($request->hasFile('url_img_profile')) {
            $input['url_img_profile'] = substr($input['url_img_profile']->store('public/work_group/avatar'), 7);
        }

        // Organiza campos booleanos
        $input['state'] = $this->toBoolean($input['state']);

        $workGroup = $this->workGroupRepository->update($input, $id);

        // Valida si viene grupos de trabajo para actualizar
        if (empty($input['users'])) {
            $workGroup->users()->detach();
        } else {
            // Inserta relacion con grupos de trabajo
            $workGroup->users()->sync($input['users']);
        }

        $workGroup->users;
        // dd($workGroup->toArray());
     


        return $this->sendResponse($workGroup->toArray(), trans('msg_success_update'));

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
        $workGroups = $this->workGroupRepository->find($id);

        if (empty($workGroups)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $workGroups->users;   
        return $this->sendResponse($workGroups->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Elimina un WorkGroup del almacenamiento
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

        /** @var WorkGroup $workGroup */
        $workGroup = $this->workGroupRepository->find($id);

        if (empty($workGroup)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $workGroup->delete();

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
        $fileName = time().'-'.trans('work_groups').'.'.$fileType;

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
