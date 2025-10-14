<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\correspondence\RequestExportPlanilla;
use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreatePlanillaRequest;
use Modules\Correspondence\Http\Requests\UpdatePlanillaRequest;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Modules\Correspondence\Repositories\PlanillaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\External;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\Planilla;
use App\Http\Controllers\JwtController;
use Modules\Correspondence\Models\PlanillaRutaDependencia;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PlanillaController extends AppBaseController {

    /** @var  PlanillaRepository */
    private $planillaRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PlanillaRepository $planillaRepo) {
        $this->planillaRepository = $planillaRepo;
    }

    /**
     * Muestra la vista para el CRUD de Planilla.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('correspondence::planillas.index');

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
        $planillas = Planilla::with("planillaRuta")->latest()->get();
        return $this->sendResponse($planillas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePlanillaRequest $request
     *
     * @return Response
     */
    public function store(CreatePlanillaRequest $request) {

        $input = $request->all();
        // Se obtiene información del usuario que esta generando la planilla
        $usuario = Auth::user();
        // Por defecto, el estado de la planilla es elaboración
        $input["estado"] = "Elaboración";
        // Se formatean las fechas reemplazando la letra T por un espacio " "
        $rango_desde = str_replace("T", " ", $input["rango_planilla_desde"]);
        $rango_hasta = str_replace("T", " ", $input["rango_planilla_hasta"]);
        // Se guarda la consulta del rango de fechas de las correspondencias
        $input["consulta_sql"] = "created_at >= '".$rango_desde."' AND created_at <= '".$rango_hasta."'";
        $input["rango_planilla"] = "Desde : ".$rango_desde."<br/> Hasta : ".$rango_hasta;
        // ID del usuario que creó la planilla
        $input["users_id"] = $usuario->id;
        // Nombre de la usuario que creó la planilla
        $input["nombre_usuario"] = $usuario->name;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $planilla = $this->planillaRepository->create($input);
            $planilla->planillaRuta;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($planilla->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdatePlanillaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlanillaRequest $request) {

        $input = $request->all();
        // Se actualiza el estado de la planilla a público
        $input["estado"] = "Público";
        $currentYear = date('Y');
    
        $ultimaPlanilla = Planilla::whereYear('created_at', $currentYear)->orderBy('consecutivo', 'desc')->first();
            
        // Se cacula el consecutivo incremental de la planilla
        $input["consecutivo"] = $ultimaPlanilla ? intval($ultimaPlanilla->consecutivo) + 1 : 1;
        // Selecciona los ids de las dependencias asociadas según la ruta de la planilla
        $ids_dependencias_ruta = PlanillaRutaDependencia::where("correspondence_planilla_ruta_id", $input["correspondence_planilla_ruta_id"])->pluck("dependencias_id")->toArray();
        // Valida el tipo de correspondencia para la consulta de las correspondencias
        switch ($input['tipo_correspondencia']) {
            case "Externa Recibida":
                // Consulta todas las correspondencias recibidas que estén en el rango de la fecha consultada y según las dependencias de la ruta
                $correspondencias = ExternalReceived::whereRaw($input['consulta_sql'])->whereIn("dependency_id", $ids_dependencias_ruta)->where('channel', '!=', 2)->latest()->get()->toArray();
                $input["contenido"] = json_encode($correspondencias);
                break;
            case "Externa Enviada":
                // Consulta todas las correspondencias enviadas en estado público que estén en el rango de la fecha consultada y según las dependencias de la ruta
                $correspondencias = External::where('state', 'Público')->whereRaw($input['consulta_sql'])->whereIn("dependencias_id", $ids_dependencias_ruta)->where('origen', 'FISICO')->latest()->get()->toArray();
                $input["contenido"] = json_encode($correspondencias);
                break;
            case "Interna":
                // Consulta todas las correspondencias internas en estado público que estén en el rango de la fecha consultada y según las dependencias de la ruta
                $correspondencias = Internal::with('internalRecipients')->where('state', 'Público')->whereRaw($input['consulta_sql'])->whereIn("dependencias_id", $ids_dependencias_ruta)->where('origen', 'FISICO')->latest()->get()->toArray();
                $input["contenido"] = json_encode($correspondencias);
                break;
        }

        /** @var Planilla $planilla */
        $planilla = $this->planillaRepository->find($id);

        if (empty($planilla)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $planilla = $this->planillaRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($planilla->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Consecutivo: '.($input['consecutivo'] ?? 'Desconocido') . ' ID: ' .($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Planilla del almacenamiento
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

        /** @var Planilla $planilla */
        $planilla = $this->planillaRepository->find($id);

        if (empty($planilla)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $planilla->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Consecutivo : '. ($planilla['consecutivo'] ?? 'Desconocido' . '- ID: '. ($id ?? 'Desconocido')));
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

        $input["data"] = JwtController::decodeToken($input['data']);
        array_walk($input["data"], fn(&$object) => $object = (array)$object);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('planillas').'.'.$fileType;
        $correspondencias["consecutivo"] = $input["data"][0]["consecutivo"] ?? '';

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Selecciona los ids de las dependencias asociadas según la ruta de la planilla
            $ids_dependencias_ruta = PlanillaRutaDependencia::where("correspondence_planilla_ruta_id", $input["data"][0]['planilla_ruta']->id)->pluck("dependencias_id")->toArray();
            // Valida el tipo de correspondencia para la consulta de las correspondencias
            switch ($input["data"][0]['tipo_correspondencia']) {
                case "Externa Recibida":
                    // Consulta todas las correspondencias recibidas que estén en el rango de la fecha consultada y según las dependencias de la ruta, adicional, si el estado es público, se exportan las correspondencias ya guardadas previamente
                    $correspondencias["correspondencias"] = $input["data"][0]['estado'] == "Elaboración" ? ExternalReceived::whereRaw($input["data"][0]['consulta_sql'])->whereIn("dependency_id", $ids_dependencias_ruta)->latest()->get()->toArray() : json_decode($input["data"][0]['contenido'], true);
                    $correspondencias["tipo"] = "EXTERNA RECIBIDA";
                    break;
                case "Externa Enviada":
                    // Consulta todas las correspondencias enviadas en estado público que estén en el rango de la fecha consultada y según las dependencias de la ruta, adicional, si el estado es público, se exportan las correspondencias ya guardadas previamente
                    $correspondencias["correspondencias"] = $input["data"][0]['estado'] == "Elaboración" ? External::where('state', 'Público')->whereRaw($input["data"][0]['consulta_sql'])->where('origen', 'FISICO')->whereIn("dependencias_id", $ids_dependencias_ruta)->latest()->get()->toArray() : json_decode($input["data"][0]['contenido'], true);
                    $correspondencias["tipo"] = "EXTERNA ENVIADA";
                    break;
                case "Interna":
                    // Consulta todas las correspondencias internas en estado público que estén en el rango de la fecha consultada y según las dependencias de la ruta, adicional, si el estado es público, se exportan las correspondencias ya guardadas previamente
                    $correspondence_data = $input["data"][0]['estado'] == "Elaboración" ? Internal::with('internalRecipients')->where('state', 'Público')->where('origen', 'FISICO')->whereRaw($input["data"][0]['consulta_sql'])->whereIn("dependencias_id", $ids_dependencias_ruta)->latest()->get()->toArray() : json_decode($input["data"][0]['contenido'], true);
                    $correspondencias["tipo"] = "INTERNA";
                    
                    // Bucle para obtener las dependencias de los destinatarios en una cadena
                    $correspondencias["correspondencias"] = array_map(function($correspondence) {
                        $internal_recipients = $correspondence["internal_recipients"];
                        $dependencia_ids = array_column($internal_recipients, "users_dependencia_id");
                        
                        $dependencias = DB::table('dependencias as d')
                            ->whereIn('d.id', $dependencia_ids)
                            ->pluck('d.nombre')
                            ->toArray();
                    
                        $correspondence["list_dependencias_recipients"] = implode(", ", $dependencias);
                        return $correspondence;
                    }, $correspondence_data);
                    break;
            }
            // Descarga el archivo generado
            $filePDF = PDF::loadView("correspondence::planillas.report_planilla", ['data' => $correspondencias])
            ->setPaper("A3", "landscape");
        return $filePDF->download("Planilla.pdf");
        
    
            // return Excel::download(new RequestExportPlanilla('correspondence::planillas.report_planilla', $correspondencias, 'I'), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);

        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }
}
