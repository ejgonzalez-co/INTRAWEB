<?php

namespace Modules\ExpedientesElectronicos\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ExpedientesElectronicos\Http\Requests\CreateExpedienteHistorialRequest;
use Modules\ExpedientesElectronicos\Http\Requests\UpdateExpedienteHistorialRequest;
use Modules\ExpedientesElectronicos\Repositories\ExpedienteHistorialRepository;
use Modules\ExpedientesElectronicos\Models\ExpedienteHistorial;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\ExpedientesElectronicos\Models\DocumentosExpediente;
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
class ExpedienteHistorialController extends AppBaseController {

    /** @var  ExpedienteHistorialRepository */
    private $expedienteHistorialRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ExpedienteHistorialRepository $expedienteHistorialRepo) {
        $this->expedienteHistorialRepository = $expedienteHistorialRepo;
    }

    /**
     * Muestra la vista para el CRUD de ExpedienteHistorial.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('expedienteselectronicos::expediente_historials.index');

        // return view("auth.forbidden");

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
        if(Auth::user()->hasRole('Operador Expedientes Electrónicos')){
            $expediente_historials = $this->expedienteHistorialRepository->all();
        } else {
            $expediente_historials = ExpedienteHistorial::where(function($query) {
                $query->whereIn('ee_expediente_id', function($subQuery) {
                    $subQuery->select('ee_expedientes_id')
                             ->from('ee_permiso_consultar_expediente')
                             ->where('dependencia_usuario_id', Auth::id());
                })
                ->whereIn('ee_expediente_id', function($subQuery) {
                    $subQuery->select('ee_expedientes_id')
                             ->from('ee_permiso_usar_expediente')
                             ->where('dependencia_usuario_id', Auth::id());
                });
            })
            ->orWhere('permiso_consultar_expedientes_todas', 1)
            ->get();
        }
        return $this->sendResponse($expediente_historials->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateExpedienteHistorialRequest $request
     *
     * @return Response
     */
    public function store(CreateExpedienteHistorialRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $expedienteHistorial = $this->expedienteHistorialRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($expedienteHistorial->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateExpedienteHistorialRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExpedienteHistorialRequest $request) {

        $input = $request->all();

        /** @var ExpedienteHistorial $expedienteHistorial */
        $expedienteHistorial = $this->expedienteHistorialRepository->find($id);

        if (empty($expedienteHistorial)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $expedienteHistorial = $this->expedienteHistorialRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($expedienteHistorial->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un ExpedienteHistorial del almacenamiento
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

        /** @var ExpedienteHistorial $expedienteHistorial */
        $expedienteHistorial = $this->expedienteHistorialRepository->find($id);

        if (empty($expedienteHistorial)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $expedienteHistorial->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteHistorialController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('expediente_historials').'.'.$fileType;

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


    /**
     * Obtiene si la correspondencia esta relacionada o no
     *
     * @author Manuel Marín - Sep. 30 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function correspondenciaRelacionada($campo, $id_expiente) {
        $campoBuscar = base64_decode($campo);
        $id_expediente = base64_decode($id_expiente);
        // Obtiene y verifica si el documento existe en el expediente
        $correspondencia_relacionada = DocumentosExpediente::with(["eeExpediente"])->where('modulo_consecutivo', $campoBuscar)->where('ee_expediente_id', $id_expediente)->latest()->get();
        return $this->sendResponse($correspondencia_relacionada->toArray(), trans('data_obtained_successfully'));
    }
}
