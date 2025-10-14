<?php

namespace Modules\Configuracion\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Configuracion\Http\Requests\CreateversionsUpdateRequest;
use Modules\Configuracion\Http\Requests\UpdateversionsUpdateRequest;
use Modules\Configuracion\Models\versionsUpdate;
use Modules\Configuracion\Repositories\versionsUpdateRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class versionsUpdateController extends AppBaseController {

    /** @var  versionsUpdateRepository */
    private $versionsUpdateRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(versionsUpdateRepository $versionsUpdateRepo) {
        $this->versionsUpdateRepository = $versionsUpdateRepo;
    }

    /**
     * Muestra la vista para el CRUD de versionsUpdate.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        if(Auth::user()->hasRole("Administrador intranet")){
            return view('configuracion::versions_updates.index');
        }
        return view("auth.forbidden");

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $versions_updates = $this->versionsUpdateRepository->all();
        return $this->sendResponse($versions_updates->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateversionsUpdateRequest $request
     *
     * @return Response
     */
    public function store(CreateversionsUpdateRequest $request) {

        $input = $request->all();
        //Obtiene la version actual
        $version_actual = versionsUpdate::select("numero_version")->orderBy("id", "desc")->first();
        //Separa la version por los puntos
        $version_actual = explode(".", $version_actual->numero_version);
        //En el ciclo aumenta dependiendo la seleccion del campo, asi mismo aumenta el campo que se necesita
        switch ($request->tipo_de_cambio) {
            case 'Mejora Estructural':
                $version_actual[0]++;
                break;
            case 'Mejora Mediana':
                $version_actual[1]++;
                break;
            case 'Mejora Pequeña':
                $version_actual[2]++;
                break;
        }
        $nuevaVersion = implode(".", $version_actual);
        //iguala la varaible para almacenar la ultima version del lanzamiento
        $input['numero_version'] = $nuevaVersion;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $versionsUpdate = $this->versionsUpdateRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($versionsUpdate->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateversionsUpdateRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateversionsUpdateRequest $request) {

        $input = $request->all();

        /** @var versionsUpdate $versionsUpdate */
        $versionsUpdate = $this->versionsUpdateRepository->find($id);

        if (empty($versionsUpdate)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $versionsUpdate = $this->versionsUpdateRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($versionsUpdate->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un versionsUpdate del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var versionsUpdate $versionsUpdate */
        $versionsUpdate = $this->versionsUpdateRepository->find($id);

        if (empty($versionsUpdate)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $versionsUpdate->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Configuracion\Http\Controllers\versionsUpdateController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('versions_updates').'.'.$fileType;

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
