<?php

namespace Modules\Leca\Http\Controllers;

use DB;
use Auth;
use Flash;
use Response;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\ObservacionesDuplicadoNitritos;
use Modules\Leca\Repositories\ObservacionesDuplicadoNitritosRepository;
use Modules\Leca\Http\Requests\CreateObservacionesDuplicadoNitritosRequest;
use Modules\Leca\Http\Requests\UpdateObservacionesDuplicadoNitritosRequest;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ObservacionesDuplicadoNitritosController extends AppBaseController {

    /** @var  ObservacionesDuplicadoNitritosRepository */
    private $observacionesDuplicadoNitritosRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ObservacionesDuplicadoNitritosRepository $observacionesDuplicadoNitritosRepo) {
        $this->observacionesDuplicadoNitritosRepository = $observacionesDuplicadoNitritosRepo;
    }

    /**
     * Muestra la vista para el CRUD de ObservacionesDuplicadoNitritos.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $lc_ensayo_nitritos_id=$request['lc_ensayo_aluminio_id'];

        return view('leca::observaciones_duplicado_nitritos.index', compact('lc_ensayo_nitritos_id'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
                
        $observaciones_duplicado_nitrito = ObservacionesDuplicadoNitritos::where('lc_ensayo_nitritos_id',$request['lc_ensayo_nitritos_id'])->get();

        return $this->sendResponse($observaciones_duplicado_nitrito->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateObservacionesDuplicadoNitritosRequest $request
     *
     * @return Response
     */
    public function store(CreateObservacionesDuplicadoNitritosRequest $request) {

        $input = $request->all();

        $user = Auth::user();


        $input['users_id'] = $user->id;
        $input['name_user'] = $user->name;



        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $observacionesDuplicadoNitritos = $this->observacionesDuplicadoNitritosRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($observacionesDuplicadoNitritos->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateObservacionesDuplicadoNitritosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateObservacionesDuplicadoNitritosRequest $request) {

        $input = $request->all();

        /** @var ObservacionesDuplicadoNitritos $observacionesDuplicadoNitritos */
        $observacionesDuplicadoNitritos = $this->observacionesDuplicadoNitritosRepository->find($id);

        if (empty($observacionesDuplicadoNitritos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $observacionesDuplicadoNitritos = $this->observacionesDuplicadoNitritosRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($observacionesDuplicadoNitritos->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ObservacionesDuplicadoNitritos del almacenamiento
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

        /** @var ObservacionesDuplicadoNitritos $observacionesDuplicadoNitritos */
        $observacionesDuplicadoNitritos = $this->observacionesDuplicadoNitritosRepository->find($id);

        if (empty($observacionesDuplicadoNitritos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $observacionesDuplicadoNitritos->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoNitritosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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
        $fileName = time().'-'.trans('observaciones_duplicado_nitritos').'.'.$fileType;

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
