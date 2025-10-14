<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateCorreoIntegradoRequest;
use Modules\Correspondence\Http\Requests\UpdateCorreoIntegradoRequest;
use Modules\Correspondence\Repositories\CorreoIntegradoRepository;
use App\Http\Controllers\AppBaseController;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use GuzzleHttp\Cookie\CookieJarInterface;
use Illuminate\Support\Facades\Mail;
use Modules\Correspondence\Models\CorreoIntegrado;
use Modules\Correspondence\Models\CorreoIntegradoHistorial;
use Modules\Correspondence\Models\CorreoIntegradoAdjunto;
use Modules\Configuracion\Models\Variables;
use Modules\Correspondence\Http\Controllers\ExternalReceivedController;
use Modules\Correspondence\Http\Requests\CreateExternalReceivedRequest;
use Modules\Correspondence\Models\CorreoIntegradoConfiguracion;
use Webklex\IMAP\Client;
use Zend\Mail\Storage\Imap;
use App\Http\Controllers\JwtController;
use DateTime;

// declare(strict_types=1);

require_once __DIR__.'/../../../../vendor/autoload.php';

use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;
/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class CorreoIntegradoControllerList extends AppBaseController {

    /** @var  CorreoIntegradoRepository */
    private $correoIntegradoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(CorreoIntegradoRepository $correoIntegradoRepo) {
        $this->correoIntegradoRepository = $correoIntegradoRepo;
    }

    /**
     * Muestra la vista para el CRUD de CorreoIntegrado.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index() {

        if(Auth::user()->hasRole(["Administrador Correspondencia","Correspondencia Recibida Admin"])){
            return view('correspondence::correo_integrados_admin.index');
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

        if(Auth::user()->hasRole(["Administrador Correspondencia","Correspondencia Recibida Admin"])){
           
            $configuracion_correo = CorreoIntegradoConfiguracion::latest()->get();
            return $this->sendResponse($configuracion_correo->toArray(), trans('data_obtained_successfully'));

        }else{

            return $this->sendResponse([], trans('data_obtained_successfully'));
        }
    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateCorreoIntegradoRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();
        // Asigna el id y nombre del usuario que esta clasificando la comunicación
        $input["users_id"] = Auth::user()->id;
    
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::create($input);
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($correoIntegradoConfiguracion->toArray(), trans('Configuración actualizada correctamente'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage());
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
     * @param CreateExternalReceivedRequest $request El tipo de esta variable fue modificado, ya que se requiere para guardar la correspondencia recibida
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();
        // ID de la configuración en caso de que ya haya una
        $id = $input["id"] ?? null;
        // Asigna el id y nombre del usuario que esta clasificando la comunicación
        $input["users_id"] = Auth::user()->id;
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::find($id);
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si la clasificación de la comunicación fue una correspondencia

            if (empty($correoIntegradoConfiguracion)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
            else {
                    // Actualiza el registro
                $correoIntegradoConfiguracion2 = $correoIntegradoConfiguracion->update($input);
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($correoIntegradoConfiguracion->toArray(), trans('Configuración actualizada correctamente'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine(). ' Configuracion Id: ' .($correoIntegradoConfiguracion['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un CorreoIntegrado del almacenamiento
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
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::find($id);

        if (empty($correoIntegradoConfiguracion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $correoIntegradoConfiguracion->delete();

            // Efectua los cambios realizados
            DB::commit();
            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine(). ' Configuracion Id: ' .($correoIntegradoConfiguracion['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

   
    public function guardarConfiguracionCorreo(Request $request) {
        $input = $request->all();
        // ID de la configuración en caso de que ya haya una
        $id = $input["id"] ?? null;
        // Asigna el id y nombre del usuario que esta clasificando la comunicación
        $input["users_id"] = Auth::user()->id;
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::find($id);

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si la clasificación de la comunicación fue una correspondencia
            if(empty($correoIntegradoConfiguracion)) {
                // Actualiza el registro
                $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::create($input);
            } else {
                // Actualiza el registro
                $correoIntegradoConfiguracion = $correoIntegradoConfiguracion->update($input);
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($correoIntegradoConfiguracion->toArray(), trans('Configuración actualizada correctamente'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }


}
