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
use Modules\Leca\Models\ObservacionesDuplicadoDureza;
use Modules\Leca\Repositories\ObservacionesDuplicadoDurezaRepository;
use Modules\Leca\Http\Requests\CreateObservacionesDuplicadoDurezaRequest;
use Modules\Leca\Http\Requests\UpdateObservacionesDuplicadoDurezaRequest;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ObservacionesDuplicadoDurezaController extends AppBaseController {

        /** @var  ObservacionesDuplicadoDurezaRepository */
        private $ObservacionesDuplicadoDurezaRepository;

        /**
         * Constructor de la clase
         *
         * @author Desarrollador Seven - 2022
         * @version 1.0.0
         */
        public function __construct(ObservacionesDuplicadoDurezaRepository $ObservacionesDuplicadoDurezaRepo) {
            $this->ObservacionesDuplicadoDurezaRepository = $ObservacionesDuplicadoDurezaRepo;
        }
    
        /**
         * Muestra la vista para el CRUD de ObservacionesDuplicadoDureza.
         *
         * @author Desarrollador Seven - 2022
         * @version 1.0.0
         *
         * @param Request $request
         *
         * @return Response
         */
        public function index(Request $request) {
    
            $lc_ensayo_dureza_id=$request['lc_ensayo_dureza_id'];
    
            return view('leca::observaciones_duplicado_dureza.index', compact('lc_ensayo_dureza_id'));
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
            
            $observaciones_duplicado_durezas = ObservacionesDuplicadoDureza::where('lc_ensayo_dureza_id',$request['lc_ensayo_dureza_id'])->get();
    
            return $this->sendResponse($observaciones_duplicado_durezas->toArray(), trans('data_obtained_successfully'));
        }
    
        /**
         * Guarda un nuevo registro al almacenamiento
         *
         * @author Desarrollador Seven - 2022
         * @version 1.0.0
         *
         * @param CreateObservacionesDuplicadoDurezaRequest $request
         *
         * @return Response
         */
        public function store(CreateObservacionesDuplicadoDurezaRequest $request) {
            
            $input = $request->all();
            $user = Auth::user();
    
            
            $input['users_id'] = $user->id;
            $input['name_user'] = $user->name;
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Inserta el registro en la base de datos
                $ObservacionesDuplicadoDureza = $this->ObservacionesDuplicadoDurezaRepository->create($input);
    
                // Efectua los cambios realizados
                DB::commit();
    
                return $this->sendResponse($ObservacionesDuplicadoDureza->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
         * @param UpdateObservacionesDuplicadoDurezaRequest $request
         *
         * @return Response
         */
        public function update($id, UpdateObservacionesDuplicadoDurezaRequest $request) {
    
            $input = $request->all();
    
            /** @var ObservacionesDuplicadoDureza $ObservacionesDuplicadoDureza */
            $ObservacionesDuplicadoDureza = $this->ObservacionesDuplicadoDurezaRepository->find($id);
    
            if (empty($ObservacionesDuplicadoDureza)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
            
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Actualiza el registro
                $ObservacionesDuplicadoDureza = $this->ObservacionesDuplicadoDurezaRepository->update($input, $id);
    
                // Efectua los cambios realizados
                DB::commit();
            
                return $this->sendResponse($ObservacionesDuplicadoDureza->toArray(), trans('msg_success_update'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }
    
        /**
         * Elimina un ObservacionesDuplicadoDureza del almacenamiento
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
    
            /** @var ObservacionesDuplicadoDureza $ObservacionesDuplicadoDureza */
            $ObservacionesDuplicadoDureza = $this->ObservacionesDuplicadoDurezaRepository->find($id);
    
            if (empty($ObservacionesDuplicadoDureza)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
    
            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Elimina el registro
                $ObservacionesDuplicadoDureza->delete();
    
                // Efectua los cambios realizados
                DB::commit();
    
                return $this->sendSuccess(trans('msg_success_drop'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObservacionesDuplicadoDurezaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
            $fileName = time().'-'.trans('observaciones_duplicado_durezas').'.'.$fileType;
    
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
