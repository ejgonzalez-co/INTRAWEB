<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use App\Http\Controllers\AppBaseController;
use Modules\Intranet\Http\Requests\CreateDependencyRequest;
use Modules\Intranet\Http\Requests\UpdateDependencyRequest;
use Modules\Intranet\Models\Dependency;
use Modules\Intranet\Repositories\DependencyRepository;
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
class DependencyController extends AppBaseController {

    /** @var  DependencyRepository */
    private $dependencyRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(DependencyRepository $dependencyRepo) {
        $this->dependencyRepository = $dependencyRepo;
    }

    /**
     * Muestra la vista para el CRUD de Dependency.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('intranet::dependencies.index');
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
        //Trae solo la dependencia de Laboratorio de Ensayo de Calidad Agua
        $dependencies = Dependency::with(['headquarters'])->latest()->get()->filter(function($item, $key){
            if($item->nombre == 'Laboratorio de Ensayo de Calidad Agua'){
                return true;
            }
        });
        // $dependencies = Dependency::with(['headquarters'])->latest()->get();
        return $this->sendResponse($dependencies->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateDependencyRequest $request
     *
     * @return Response
     */
    public function store(CreateDependencyRequest $request) {

        $input = $request->all();

        $dependency = $this->dependencyRepository->create($input);
        // Obtiene sede asignada
        $dependency->headquarters;

        return $this->sendResponse($dependency->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDependencyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDependencyRequest $request) {

        $input = $request->all();

        /** @var Dependency $dependency */
        $dependency = $this->dependencyRepository->find($id);

        if (empty($dependency)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $dependency = $this->dependencyRepository->update($input, $id);
        // Obtiene sede asignada
        $dependency->headquarters;

        return $this->sendResponse($dependency->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un Dependency del almacenamiento
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

        /** @var Dependency $dependency */
        $dependency = $this->dependencyRepository->find($id);

        if (empty($dependency)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $dependency->delete();

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
        $fileName = time().'-'.trans('dependencies').'.'.$fileType;

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
