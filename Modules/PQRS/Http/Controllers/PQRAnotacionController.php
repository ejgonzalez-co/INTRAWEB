<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\GenericExport;
use Modules\PQRS\Http\Requests\CreatePQRAnotacionRequest;
use Modules\PQRS\Http\Requests\UpdatePQRAnotacionRequest;
use Modules\PQRS\Repositories\PQRAnotacionRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\PQRAnotacion;
use Modules\PQRS\Models\PQRLeidoAnotacion;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PQRAnotacionController extends AppBaseController {

    /** @var  PQRAnotacionRepository */
    private $pQRAnotacionRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PQRAnotacionRepository $pQRAnotacionRepo) {
        $this->pQRAnotacionRepository = $pQRAnotacionRepo;
    }

    /**
     * Muestra la vista para el CRUD de PQRAnotacion.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador de requerimientos","Operadores","Consulta de requerimientos"]) || !Auth::user()->hasRole(["Ciudadano"])){
            // Busca el PQR según el id recibido por URL
            $pQR = PQR::find($request->ci);
            // Si no coincide ningún registro, lo redirecciona a la vista principal de PQRS
            if (empty($pQR)) {
                return redirect("/pqrs/p-q-r-s");
            }
            // Consulta la información del PQR según el id recibido por URL
            $pqr = PQR::where('id', $request->ci)->first();
            $pqrId = $request->ci;
            $pqrConsecutive = $pqr["pqr_id"];
            // Envía a la vista el id del PQR y el consecutivo
            return view('pqrs::p_q_r_anotacions.index', compact(['pqrId', 'pqrConsecutive']));
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
    public function all($pqr_id) {
        $p_q_r_anotacions = PQRAnotacion::with(["users"])->where("pqr_id", $pqr_id)->latest()->get()->toArray();
        return $this->sendResponse($p_q_r_anotacions, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePQRAnotacionRequest $request
     *
     * @return Response
     */
    public function store(CreatePQRAnotacionRequest $request, $pqrId) {
        // Obtiene los valores de los campos
        $input = $request->all();

        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["anotacion"],"<p") !== false){
            $input["anotacion"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["anotacion"]);
            $input["anotacion"] = preg_replace('/<\/p>/i', '</span>', $input["anotacion"]);
        }

        // Asigna el ID del PQR para la relación con la anotación
        $input["pqr_id"] = $pqrId;
        // Obtiene el ID del usuario en sesión
        $input["users_id"]=Auth::user()->id;
        // Obtiene el nombre de usuario en sesión
        $input["nombre_usuario"]=Auth::user()->fullname;
        // Obtiene el año actual y lo asigna a la vigencia
        $input["vigencia"] = date("Y");
        $input["leido_por"] = Auth::user()->id;

        // Valida si no seleccionó ningún adjunto
        if($input["attached"] ?? false) {
            $input['attached'] = $input["attached"];
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $pQRAnotacion = $this->pQRAnotacionRepository->create($input);
            
            $pQRAnotacion->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQRAnotacion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdatePQRAnotacionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePQRAnotacionRequest $request) {

        $input = $request->all();

        /** @var PQRAnotacion $pQRAnotacion */
        $pQRAnotacion = $this->pQRAnotacionRepository->find($id);

        if (empty($pQRAnotacion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pQRAnotacion = $this->pQRAnotacionRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($pQRAnotacion->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine(). ' Id PQR: '.($pQRAnotacion['pqr_id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PQRAnotacion del almacenamiento
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

        /** @var PQRAnotacion $pQRAnotacion */
        $pQRAnotacion = $this->pQRAnotacionRepository->find($id);

        if (empty($pQRAnotacion)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $pQRAnotacion->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRAnotacionController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine(). ' Id PQR: '.($pQRAnotacion['pqr_id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('p_q_r_anotacions').'.'.$fileType;

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

    public function leidoAnotacionPQR($pqr_anotacion_id) {
        // Consulta si la anotación ya ha sido leida por el usuario
        $pqr_anotacion_leido = PQRLeidoAnotacion::select("id", "accesos")->where("pqr_anotacion_id", $pqr_anotacion_id)->where("users_id", Auth::user()->id)->first();
        // Entra a la condición si el usuario ya había leido previamente la anotación
        if($pqr_anotacion_leido) {
            // Valida si ya tiene accesos
            if($pqr_anotacion_leido["accesos"]) {
                $pqr_accesos = $pqr_anotacion_leido["accesos"]."<br/>".date("Y-m-d H:i:s");
            } else {
                $pqr_accesos = date("Y-m-d H:i:s");
            }
            // Actualiza el campo accesos del PQR leído
            $result_pqr_anotacion_leido = PQRLeidoAnotacion::where("id", $pqr_anotacion_leido["id"])->update(["accesos" => $pqr_accesos], $pqr_anotacion_leido["id"]);
        } else {
            $fecha_leido = date("Y-m-d H:i:s");
            // Valida si es el usuario que esta leyendo la anotación del PQR, tiene el rol de administrador de requerimientos
            if(Auth::user()->hasRole('Administrador de requerimientos')) {
                $rol = "Administrador";
            } else if(Auth::user()->hasRole('Consulta de requerimientos')) { // Valida si es el usuario que esta leyendo la anotación el PQR, tiene el rol de Consulta de requerimientos
                $rol = "Consultor";
            } else if(Auth::user()->hasRole('Operadores')) { // Valida si es el usuario que esta leyendo la anotación del PQR, tiene el rol de operadores
                $rol = "Operador";
            } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta leyendo la anotación del PQR, tiene el rol de ciudadano
                $rol = "Ciudadano";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido de la anotación PQR
            $result_pqr_anotacion_leido = PQRLeidoAnotacion::create([
                'nombre_usuario' => Auth::user()->name,
                'tipo_usuario' => $rol,
                'accesos' => $fecha_leido,
                'vigencia' => date("Y"),
                'pqr_anotacion_id' => $pqr_anotacion_id,
                'users_id' => Auth::user()->id
            ]);
        }

        return $this->sendResponse($result_pqr_anotacion_leido, "Anotación leida con éxito");
    }
}
