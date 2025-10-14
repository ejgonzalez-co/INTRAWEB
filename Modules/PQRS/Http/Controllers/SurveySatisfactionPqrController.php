<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\GenericExport;
use Modules\PQRS\Http\Requests\CreateSurveySatisfactionPqrRequest;
use Modules\PQRS\Http\Requests\UpdateSurveySatisfactionPqrRequest;
use Modules\PQRS\Repositories\SurveySatisfactionPqrRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\JwtController;
use Modules\PQRS\Models\SurveySatisfactionPqr;
use Modules\PQRS\Models\PQR;
use Modules\Configuracion\Models\ConfigurationGeneral;



/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class SurveySatisfactionPqrController extends AppBaseController {

    /** @var  SurveySatisfactionPqrRepository */
    private $surveySatisfactionPqrRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(SurveySatisfactionPqrRepository $surveySatisfactionPqrRepo) {
        $this->surveySatisfactionPqrRepository = $surveySatisfactionPqrRepo;
    }

    /**
     * Muestra la vista para el CRUD de SurveySatisfactionPqr.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $nombreEntidad = ConfigurationGeneral:: select('nombre_entidad')->first()->nombre_entidad;
        $pqr_id = $request->cHFyX2lk;
        $pqr = PQR:: where('id', JwtController::decodeToken($request->cHFyX2lk) )->first();


        return view('pqrs::survey_satisfaction_pqrs.index', compact(['pqr', 'nombreEntidad']))->with('pqr_id', $pqr_id);
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

        $pqr_id = JwtController::decodeToken($request->cHFyX2lk);

      
        $survey_satisfaction_pqrs = $this->surveySatisfactionPqrRepository->all();
        return $this->sendResponse($survey_satisfaction_pqrs->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateSurveySatisfactionPqrRequest $request
     *
     * @return Response
     */
    public function store(CreateSurveySatisfactionPqrRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $pqr = PQR:: where('id', JwtController::decodeToken($input['pqr_id']) )->first();
            
            $survey = SurveySatisfactionPqr::where('pqr_id', $pqr->id)->first();

            if (!empty( $survey)) {
                return $this->sendSuccess('<strong>Atención.</strong>'. '<br><br>' . "Le informamos que la  PQRS <strong> $pqr->pqr_id </strong> ya cuenta con una encuesta de satisfacción diligenciada.", 'warning');
                
            }

            $input['users_id'] = $pqr->ciudadano_users_id ? $pqr->ciudadano_users_id  : null;
            $input['pqr_id'] = $pqr->id ;
            // Inserta el registro en la base de datos

            $surveySatisfactionPqr = $this->surveySatisfactionPqrRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($surveySatisfactionPqr->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController  -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController  -  Error: '.$e->getMessage());
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
     * @param UpdateSurveySatisfactionPqrRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSurveySatisfactionPqrRequest $request) {

        $input = $request->all();

        /** @var SurveySatisfactionPqr $surveySatisfactionPqr */
        $surveySatisfactionPqr = $this->surveySatisfactionPqrRepository->find($id);

        if (empty($surveySatisfactionPqr)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $surveySatisfactionPqr = $this->surveySatisfactionPqrRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($surveySatisfactionPqr->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Consecutivo: '.($surveySatisfactionPqr['pqr_id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un SurveySatisfactionPqr del almacenamiento
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

        /** @var SurveySatisfactionPqr $surveySatisfactionPqr */
        $surveySatisfactionPqr = $this->surveySatisfactionPqrRepository->find($id);

        if (empty($surveySatisfactionPqr)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $surveySatisfactionPqr->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Encuesta PQR', 'Modules\PQRS\Http\Controllers\SurveySatisfactionPqrController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Consecutivo: '.($surveySatisfactionPqr['pqr_id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('survey_satisfaction_pqrs').'.'.$fileType;

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
