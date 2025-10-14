<?php

namespace Modules\leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\leca\Http\Requests\CreateObservacionesDuplicadoFluoruroRequest;
use Modules\leca\Http\Requests\UpdateObservacionesDuplicadoFluoruroRequest;
use Modules\leca\Repositories\ObservacionesDuplicadoFluoruroRepository;
use Modules\Leca\Models\ObservacionesDuplicadoFluoruro;
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
class ObservacionesDuplicadoFluoruroController extends AppBaseController {

    /** @var  ObservacionesDuplicadoFluoruroRepository */
    private $observacionesDuplicadoFluoruroRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ObservacionesDuplicadoFluoruroRepository $observacionesDuplicadoFluoruroRepo) {
        $this->observacionesDuplicadoFluoruroRepository = $observacionesDuplicadoFluoruroRepo;
    }

    /**
     * Muestra la vista para el CRUD de ObservacionesDuplicadoFluoruro.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $lc_ensayo_fluoruros_id=$request['lc_ensayo_fluoruros_id'];
        return view('leca::observaciones_duplicado_fluoruros.index', compact('lc_ensayo_fluoruros_id'));
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
        $observaciones_duplicado_fluoruros = ObservacionesDuplicadoFluoruro::where('lc_ensayo_fluoruros_id',$request['lc_ensayo_fluoruros_id'])->get();
        return $this->sendResponse($observaciones_duplicado_fluoruros->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateObservacionesDuplicadoFluoruroRequest $request
     *
     * @return Response
     */
    public function store(CreateObservacionesDuplicadoFluoruroRequest $request) {

        $input = $request->all();
        $user = Auth::user();


        $input['users_id'] = $user->id;
        $input['name_user'] = $user->name;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $observacionesDuplicadoFluoruro = $this->observacionesDuplicadoFluoruroRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($observacionesDuplicadoFluoruro->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateObservacionesDuplicadoFluoruroRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateObservacionesDuplicadoFluoruroRequest $request) {

        $input = $request->all();

        /** @var ObservacionesDuplicadoFluoruro $observacionesDuplicadoFluoruro */
        $observacionesDuplicadoFluoruro = $this->observacionesDuplicadoFluoruroRepository->find($id);

        if (empty($observacionesDuplicadoFluoruro)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $observacionesDuplicadoFluoruro = $this->observacionesDuplicadoFluoruroRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($observacionesDuplicadoFluoruro->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ObservacionesDuplicadoFluoruro del almacenamiento
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

        /** @var ObservacionesDuplicadoFluoruro $observacionesDuplicadoFluoruro */
        $observacionesDuplicadoFluoruro = $this->observacionesDuplicadoFluoruroRepository->find($id);

        if (empty($observacionesDuplicadoFluoruro)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $observacionesDuplicadoFluoruro->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ObservacionesDuplicadoFluoruroController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('observaciones_duplicado_fluoruros').'.'.$fileType;

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
