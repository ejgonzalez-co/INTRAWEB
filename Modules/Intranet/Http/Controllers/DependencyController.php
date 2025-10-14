<?php

namespace Modules\Intranet\Http\Controllers;

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
use Auth;
use Modules\Intranet\Models\DependenciasRelacionadas;

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
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::dependencies.index');
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
    public function all(Request $request) {

        $query =$request->input('query');

        if($query){
            
            $dependencies = Dependency::with(['headquarters'])->where('nombre', 'like', '%' . $query . '%')->latest()->get();

        }else{
            $dependencies = Dependency::with(['headquarters','dependenciasList'])->latest()->get();
        }
        
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

        if ($request->hasFile('logo')) {
            $input['logo'] = substr($input['logo']->store('public/dependencias/logo'), 7);
        }

        // Valida si se han proporcionado dependencias
        if (!empty($input['dependencias_list'])) {
            // Elimina las dependencias existentes para esta dependencia
            DependenciasRelacionadas::where("dependencias_id", $dependency->id)->delete();

            // Itera sobre las dependencias proporcionadas
            foreach ($input['dependencias_list'] as $dependenciaRelacionada) {
                // Decodifica la entrada JSON a un array asociativo
                $typesArray = json_decode($dependenciaRelacionada, true);

                // Elimina cualquier relación existente con esta dependencia
                DependenciasRelacionadas::where("dependencias_id", $dependency->id)
                    ->where("id_dependencia_relacionada", $typesArray["id"])
                    ->delete();

                // Crea una nueva relación de dependencia
                $nuevaDependencia = new DependenciasRelacionadas([
                    "dependencias_id" => $dependency->id,
                    "id_dependencia_relacionada" => $typesArray["id"],
                    "nombre" => $typesArray["nombre"]
                ]);

                // Guarda la nueva relación de dependencia
                $nuevaDependencia->save();
            }

            // Carga la lista actualizada de dependencias
            $dependency->dependenciasList;

        } else {
            // Si no se proporcionaron dependencias, elimina todas las dependencias asociadas a esta dependencia
            DependenciasRelacionadas::where("dependencias_id", $dependency->id)->delete();
        }


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

        if ($request->hasFile('logo')) {
            $input['logo'] = substr($input['logo']->store('public/dependencias/logo'), 7);
        }

        $dependency = $this->dependencyRepository->update($input, $id);
        // Obtiene sede asignada
        $dependency->headquarters;
        

            // Valida si viene usuarios para asignar en las copias
            if (!empty($input['dependencias_list'])) {
                $delete = DependenciasRelacionadas::where("dependencias_id", $dependency->id)->delete();
                //recorre los destinatarios
                foreach ($input['dependencias_list'] as $dependenciaRelacionada) {
    
                    //array de destinatarios
                    $typesArray = json_decode($dependenciaRelacionada,true);
                    // $delete = DependenciasRelacionadas::where("dependencias_id", $dependency->id)->where("id_dependencia_relacionada", $typesArray["id"])->delete();
    
                    // dd($typesArray);
                    $typesArray["dependencias_id"] = $dependency->id;
                    $typesArray["id_dependencia_relacionada"] = $typesArray["id"];
                    $typesArray["nombre"] = $typesArray["nombre"];
    
                    DependenciasRelacionadas::create($typesArray);
                }
                $dependency->dependenciasList;
    
            }else{
                $delete = DependenciasRelacionadas::where("dependencias_id", $dependency->id)->delete();
    
            }

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
