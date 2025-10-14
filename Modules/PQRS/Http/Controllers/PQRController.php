<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\PQRS\RequestExportPQRS;
use App\Exports\correspondence\RequestExport;
use Modules\PQRS\Http\Requests\CreatePQRRequest;
use Modules\PQRS\Http\Requests\UpdatePQRRequest;
use Modules\PQRS\Repositories\PQRRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;
use App\Http\Controllers\JwtController;
use App\Mail\SendMail;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Modules\Intranet\Models\Citizen;
use Modules\PQRS\Models\HolidayCalendar;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\PQRCopia;
use Modules\PQRS\Models\PQREjeTematico;
use Modules\PQRS\Models\PQRHistorial;
use Modules\PQRS\Models\PQRLeido;
use Modules\PQRS\Models\WorkingHours;
use Modules\PQRS\Models\PQRTipoSolicitud;
use Modules\Configuracion\Models\Variables;
use Modules\Intranet\Models\Dependency;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\Correspondence\Models\pQRReceived;
use Modules\Correspondence\Models\ReceivedHistory;
use Modules\Configuracion\Models\ConfigurationGeneral;
use Modules\PQRS\Models\PQRAnotacion;
use Modules\PQRS\Http\Controllers\UtilController;
use App\Http\Controllers\SendNotificationController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Illuminate\Support\Facades\Crypt;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PQRController extends AppBaseController {

    /** @var  PQRRepository */
    private $pQRRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PQRRepository $pQRRepo) {
        $this->pQRRepository = $pQRRepo;
    }

    /**
     * Muestra la vista para el CRUD de PQR.
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
            $clasificacion = Variables::where('name' , 'clasificacion_documental_pqrs')->pluck('value')->first();

            return view('pqrs::p_q_r_s.index',compact(['clasificacion']));
        }
        return view("auth.forbidden");
    }

    /**
     * Muestra la vista de PQRS repositorio del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorio(Request $request) {
            return view('pqrs::p_q_r_s.index_repositorio')->with("vigencia",  $request['vigencia']);;

    }

    /**
     * Muestra la vista de PQRS repositorio al ciudadano del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorioCiudadano(Request $request) {
        return view('pqrs::p_q_r_s_ciudadano.index_repositorio')->with("vigencia",  $request['vigencia']);
    }

    /**
     * Muestra la vista para el CRUD de PQR del ciudadano.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexCiudadano(Request $request) {
        return view('pqrs::p_q_r_s_ciudadano.index');
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

        $query = $request->input('query');

        if($query){
            if(Auth::user()->hasRole(['Administrador de requerimientos', 'Correspondencia Enviada Admin'])){
                $pqrs = DB::table('pqr')
                ->where('pqr_id', 'like', '%'.$query.'%')
                ->whereNotNull('fecha_vence')
                ->whereNotIn('estado', ['Finalizado', 'Cancelado', 'Abierto'])
                ->get();
                return $this->sendResponse($pqrs->toArray(), trans('data_obtained_successfully'));
            } else {
                $pqrs = DB::table('pqr')
                ->where('pqr_id', 'like', "%{$query}%")
                ->where('funcionario_users_id', Auth::user()->id)
                ->whereNotNull('fecha_vence')
                ->whereNotIn('estado', ['Finalizado', 'Cancelado', 'Abierto'])
                ->get();
                return $this->sendResponse($pqrs->toArray(), trans('data_obtained_successfully'));
            }
        }

        // Reemplaza los espacios en blanco por + en la cadena de filtros codificada
        $request["f"] = str_replace(" ", "+", $request["f"]);
        // Decodifica los campos filtrados
        $filtros = base64_decode($request["f"]);
        if (strpos($filtros, "linea_tiempo LIKE '%VENCIDO%' AND estado LIKE '%FINALIZADO%'") !== false) {
            $filtros = str_replace("linea_tiempo LIKE '%VENCIDO%' AND estado LIKE '%FINALIZADO%'", "linea_tiempo LIKE '%VENCIDO%' AND estado = 'Finalizado'", $filtros);
        } else if(strpos($filtros, "estado LIKE '%SOLO VENCIDO%'") !== false){
            $filtros = str_replace("estado LIKE '%SOLO VENCIDO%'", "estado NOT LIKE '%Finalizado%' AND estado NOT LIKE '%Cancelado%'", $filtros);
        }

        // Valida que en los filtros venga seleccionada una dependencia
        if(strpos($filtros, "dependency_id") !== false){
            // Expresión regular para buscar "dependency_id = 'número'"
            $expresion_regular = "/dependency_id = '(\d+)'/";
            // es por el cual se va a reemplazar en la consulta, la variable $1 logra encontrar la primera expresion regular que este en (), asi al momento de hacer el str replace puede encontrar justo los numeros que se estan ingresando en las dependencias
            $reemplazar = "funcionario_users_id IN (SELECT id FROM users WHERE id_dependencia = $1)";
            $filtros = preg_replace($expresion_regular, $reemplazar, $filtros);
        }

        // Valida si en los filtros realizados viene el filtro de vigencia, si no viene, le asigna la vigencia actual
        if(!$filtros) {
            // Se comenta para que liste todos los registros
            // $filtros = "vigencia = ".date("Y");
        }
        $pqrs_personales = "";

        //Valida que el filtro se halla realizado por destacado
        if(stripos($filtros, "destacado ") !== false) {
            //Consulta los pqrs que el usuario en sesion tenga destacados
            $pqr_leido = PQRLeido::select("pqr_id")->where("users_id", Auth::user()->id)->where("destacado", 1)->get()->toArray();
            //Inicializa variable
            $condicion = "";
            //Recorre el resultado de la consulta
            foreach ($pqr_leido as $pqr) {
                //Concatena los id en una variable para poder tenerlos todos juntos
              $condicion .= "id = " . $pqr["pqr_id"] . " OR ";
            }
            // Eliminar el "OR " final, en caso de que $condition este vacío, se le asigna '1!=1' para que no obtenga registros, o sea no hay destacados
            $condicion = $condicion ? substr($condicion, 0, -4) : "1!=1";
            //Reemplaza el primer parametro por el segunto que es la variable condicion
            $filtros = str_replace("destacado LIKE '%1%'", "(".$condicion.")", $filtros);

        }

        // Valida si en los filtros realizados viene el filtro de pqrs_propios
        if(stripos($filtros, "pqrs_propios") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de pqrs_propios en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, 'pqrs_propios') !== false;
            }))[0];
            // Se extrae el valor del filtro pqrs_propios
            $pqrs_personales = strtolower(explode("%", $filtro[$posicion])[1]);
            // Se elimina el filtro de pqrs_propios del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }
        // Por defecto el tipo de usuario es funcionario
        $tipo_usuario = "Funcionario";
        // Valida si es el usuario que esta en sesión es un administrador o un consultor de requerimientos y que no esté consultan sus PQRS personales
        if((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos')) && $pqrs_personales != "pqrs_personales") {
            $tipo_usuario = "Administrador";
        } else if(Auth::user()->hasRole('Operadores') && $pqrs_personales != "pqrs_personales") { // Valida si es el usuario que esta en sesión es un operador y que no esté consultan sus PQRS personales
            $tipo_usuario = "Operador";
        } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta en sesión es un ciudadano
            $tipo_usuario = "Ciudadano";
        }
        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && isset($request["cp"]) && isset($request["pi"])) {
            $linea_tiempo = "";
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            if(strpos($filtros, "linea_tiempo") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de linea de tiempo en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return strpos($value, 'linea_tiempo') !== false;
                }))[0];
                // Se extrae la linea de tiempo a filtrar por el usuario
                $linea_tiempo = explode("%", $filtro[$posicion])[1];
                // Se elimina el filtro de linea de tiempo del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = implode(" AND ", $filtro);
            }
            $filtrar_pqrs = "";
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            if(strpos($filtros, "tipos_pqrs") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de linea de tiempo en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return strpos($value, 'tipos_pqrs') !== false;
                }))[0];
                // Se extrae la linea de tiempo a filtrar por el usuario
                $filtrar_pqrs = explode("%", $filtro[$posicion])[1];
                // Se elimina el filtro de linea de tiempo del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = implode(" AND ", $filtro);
                $consulta = "1=1";
                // Valida si el filtro de PQRS esta definido en 'pendientes_ejecucion'
                if($filtrar_pqrs == "PENDIENTES_EJECUCION" && $tipo_usuario == "Funcionario" && stripos($filtros, "FINALIZADO") === false) {
                    // Consulta para seleccionar los PQRS que no esten finalizados ni cancelados y que sean asignados al usuario en sesión
                    $consulta = "estado != 'Finalizado' AND estado != 'Cancelado' AND funcionario_users_id = ".Auth::user()->id;
                }
                // Se concatena la consulta generada anteriormente en caso de que hayan otros filtros, de lo contrario se asigna 1=1 para que no genere error
                $filtros = $filtros ? $filtros." AND ".$consulta : $consulta;
            }
            // Se valida si no quedaron filtros diferentes a linea de tiempo, siendo asi, se asigna por defecto el filtro 1=1
            if(empty($filtros)) $filtros = "1=1";

            // dd($filtros, $tipo_usuario, $filtrar_pqrs, $linea_tiempo);
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            if((strpos($filtros, "estado") === false && $linea_tiempo) && $filtrar_pqrs == 'PENDIENTES_EJECUCION') {
                switch ($tipo_usuario) {

                    case "Administrador":
                        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                        $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                        // Contar el número total de registros de la consulta realizada según los filtros
                        $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                    break;

                    case "Operador" :

                        if($filtrar_pqrs == "COPIAS_COMPARTIDOS") {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        } else {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        }
                    break;

                    case "Ciudadano" :
                        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                        $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                            $q->where("users_id", Auth::user()->id);
                            $q->orWhere("ciudadano_users_id", Auth::user()->id);
                        })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                        // Contar el número total de registros de la consulta realizada según los filtros
                        $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                            $q->where("users_id", Auth::user()->id);
                            $q->orWhere("ciudadano_users_id", Auth::user()->id);
                        })->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                    break;

                    default :

                        // Valida si el filtro de PQRS esta definido en 'COPIAS_COMPARTIDOS'
                        if($filtrar_pqrs == "COPIAS_COMPARTIDOS") {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        } else {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                                $q->where("funcionario_users_id", Auth::user()->id);
                                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                            })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                                $q->where("funcionario_users_id", Auth::user()->id);
                                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                            })->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        }

                    break;
                }
            } else {
                switch ($tipo_usuario) {
                    case "Administrador":

                        if($filtrar_pqrs == "COPIAS_COMPARTIDOS") {
                            // Consulta los tipo de solicitudes solo en los que el usuario se lo hayan compartido o asignado como copia
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereHas('pqrCopiaCopmpartida')->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereHas('pqrCopiaCopmpartida')->count();
                        }
                        else if($filtrar_pqrs == 'PENDIENTES_EJECUCION'){
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        }
                        else {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->count();
                        }
                    break;

                    case "Operador" :


                        if($filtrar_pqrs == "COPIAS_COMPARTIDOS") {
                            // Consulta los tipo de solicitudes solo en los que el usuario se lo hayan compartido o asignado como copia
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->count();
                        }
                        else if($filtrar_pqrs == 'PENDIENTES_EJECUCION'){
                                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                                $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                                // Contar el número total de registros de la consulta realizada según los filtros
                                $count_p_q_r_s = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->count();
                        }
                        else {
                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->count();
                        }
                    break;

                    case "Ciudadano" :
                        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                        $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                            $q->where("users_id", Auth::user()->id);
                            $q->orWhere("ciudadano_users_id", Auth::user()->id);
                        })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                        // Contar el número total de registros de la consulta realizada según los filtros
                        $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                            $q->where("users_id", Auth::user()->id);
                            $q->orWhere("ciudadano_users_id", Auth::user()->id);
                        })->count();
                    break;

                    default :

                        // Valida si el filtro de PQRS esta definido en 'COPIAS_COMPARTIDOS'
                        if($filtrar_pqrs == "COPIAS_COMPARTIDOS") {
                            // Consulta los tipo de solicitudes solo en los que el usuario se lo hayan compartido o asignado como copia
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->whereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id)->count();
                        } else {

                            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                            $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                                $q->where("funcionario_users_id", Auth::user()->id);
                                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                            })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                            // Contar el número total de registros de la consulta realizada según los filtros
                            $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                                $q->where("funcionario_users_id", Auth::user()->id);
                                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                            })->count();
                            // $filtros = $filtros." AND funcionario_users_id = ".Auth::user()->id;

                        }

                    break;
                }
            }
        } else {
            switch ($tipo_usuario) {

                case "Administrador":
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                    // Contar el número total de registros de la consulta realizada según los filtros
                    $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->count();
                break;

                case "Operador" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                    // Contar el número total de registros de la consulta realizada según los filtros
                    $count_p_q_r_s = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->count();
                break;

                case "Ciudadano" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                        $q->where("users_id", Auth::user()->id);
                        $q->orWhere("ciudadano_users_id", Auth::user()->id);
                    })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                    // Contar el número total de registros de la consulta realizada según los filtros
                    $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                        $q->where("users_id", Auth::user()->id);
                        $q->orWhere("ciudadano_users_id", Auth::user()->id);
                    })->count();
                break;

                default :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrCopia", "pqrCompartida", "pqrCopiaCopmpartida", "pqrLeidos", "pqrEjeTematico", "pqrTipoSolicitud", "funcionarioUsers", "ciudadanoUsers", "pqrAnotacions", "anotacionesPendientes", "pqrHistorial", 'pqrCorrespondence','pqrCorrespondenceExternal', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'pqrDocumentoElectronico'])->where(function($q) {
                        $q->where("funcionario_users_id", Auth::user()->id);
                        $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                    })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                    // Contar el número total de registros de la consulta realizada según los filtros
                    $count_p_q_r_s = PQR::whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->where(function($q) {
                        $q->where("funcionario_users_id", Auth::user()->id);
                        $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                    })->count();
                break;
            }
        }

        return $this->sendResponseAvanzado($p_q_r_s, trans('data_obtained_successfully'), null, ["total_registros" => $count_p_q_r_s]);

    }

    /**
     * Obtiene todos los elementos existentes del repositorio
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRepositoryPQR(Request $request)
    {
        $input = $request->toArray();

        try{

            $userid = Auth::user()->user_joomla_id;

            $likedes1 = '%"id":"'.$userid.'"%';

            $likedes2 = '%"id":'.$userid.',%';

            $likedes3 = '%"id":'.$userid.'}%';

            $table = "";

            $date = date("Y");

            //valida a que tabla realizar la consulta
            if ($input['vigencia'] != '' && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
                $table = "pqr_".$input['vigencia'];
            }else{
                $table = "pqr";
            }

            $vigencyPQRCount = 0;


                // Valida si existen las variables del paginado y si esta filtrando
                if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {

                    if ((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {

                        $querys = DB::connection('joomla')->table($table);

                        $vigencyPQRCount = $querys->whereRaw(base64_decode($request["f"]))->count();
                        $vigencyPQR = $querys->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                    } else {

                        $querys = DB::connection('joomla')->table($table)->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                            $query->where('funcionario', $userid)
                                ->orWhere('operador', $userid)
                                ->orWhere('copia', 'LIKE', $userid)
                                ->orWhere('copia', 'LIKE', $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid);
                        });

                        $vigencyPQRCount = $querys->whereRaw(base64_decode($request["f"]))->count();
                        $vigencyPQR = $querys->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                    }

                } else if(isset($request["?cp"]) && isset($request["pi"])) {

                    if ((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {

                        $querys = DB::connection('joomla')->table($table);

                        $vigencyPQRCount = $querys->count();
                        $vigencyPQR = $querys->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                    } else {

                        $querys = DB::connection('joomla')->table($table)->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                            $query->where('funcionario', $userid)
                                ->orWhere('operador', $userid)
                                ->orWhere('copia', 'LIKE', $userid)
                                ->orWhere('copia', 'LIKE', $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid);
                        });

                        $vigencyPQRCount = $querys->count();
                        $vigencyPQR = $querys->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                    }

                } else {
                        if ((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {
                            $vigencyPQR = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." order by cf_created DESC");

                        }else{

                            $vigencyPQR = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." where (funcionario = '".$userid."' or operador = '".$userid."' or copia like '".$userid."' or copia like '".$userid.",%' or copia like '%,".$userid.",%' or copia like '%,".$userid."') order by cf_created DESC");
                        }


                        $vigencyPQRCount = count($vigencyPQR);

                }

        return $this->sendResponseAvanzado($vigencyPQR, trans('data_obtained_successfully'), null, ["total_registros" => $vigencyPQRCount]);


        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRSD\Http\Controllers\PQRController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRSD\Http\Controllers\PQRController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        }


    }



    public function getAnnotation($request){

        $data = explode('-',$request);

        $annotations = DB::connection('joomla')->table($data[1])->where($data[2],$data[0])->get();

        return $this->sendResponse($annotations, trans('data_obtained_successfully'));


    }


    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePQRRequest $request
     *
     * @return Response
     */
    public function store(CreatePQRRequest $request) {
        // Obtiene los valores de los campos recibidos del formulario de PQRS
        $input = $request->all();
        $input = AntiXSSController::xssClean($input,["contenido"]);

        try {
            // Organiza campos booleanos
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;
            // Obtiene el ID del usuario en sesión
            $input["users_id"] = Auth::user()->id;
            $input["users_name"] = Auth::user()->fullname;

            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // Genera un código de verificación único para cada documento
            $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);

            // Obtiene el año actual para asignarlo a la vigencia
            $input["vigencia"] = date("Y");

            if(Auth::user()->hasRole('Administrador de requerimientos')){
                // Valida que se halla ingresado un ciudadno, sea existente o personalizado
                if(!(isset($input["nombre_ciudadano"]) && (isset($input["email_ciudadano"]) || isset($input["nombre_ciudadano"]) == "Ciudadano anónimo"))) {
                    return $this->sendSuccess('<strong>El ciudadano es requerido</strong>'. '<br>' . "autocomplete un ciudadano o asigne uno personalizado", 'warning');
                }
                // Valida que se halla ingresado un eje temático, sea existente o personalizado
                if(isset($input["estado"]) && $input["estado"] != 'Abierto' && $input["estado"] != 'Asignado' && $input["estado"] != 'Esperando respuesta del ciudadano' && $input["estado"] != 'Cancelado' && !(isset($input["nombre_ejetematico"]) && isset($input["plazo"]) && isset($input["tipo_plazo"]))) {
                    return $this->sendSuccess('<strong>El eje temático es requerido</strong>'. '<br>' . "autocomplete el eje temático o asigne uno personalizado", 'warning');
                }
            } else {
                // Obtiene los datos del ciudadano que esta registrando el PQR
                $userCitizen = Citizen::where("user_id", $input['users_id'])->first();
                // Se asignan los siguientes datos del ciudadano a las siguientes variables por defecto
                $input["nombre_ciudadano"] = $userCitizen["name"] ?? (Auth::user()->name ?? 'Sin Nombre');
                $input["documento_ciudadano"] = $userCitizen["document_number"] ?? '';
                $input["email_ciudadano"] = Auth::user()->email ?? 'Sin Email';
            }
            // Valida si seleccionó o no un adjunto
            if($input["adjunto"] ?? false) {
                $input['adjunto'] = implode(",", $input["adjunto"]);
            }

            // Valida si seleccionó o no el documento principal del PQR
            if (isset($input["document_pdf"])) {
                $input['document_pdf'] = implode(",", (array) $input["document_pdf"]);
            }
            //Valida si desea recibir la respuesta por correo electronico
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;

            // Valida si seleccionó o no el documento de la respuesta parcial
            if (isset($input["adjunto_r_parcial"])) {
                $input['adjunto_r_parcial'] = implode(",", (array) $input["adjunto_r_parcial"]);
            }

            // Valida si seleccionó o no un adjunto el ciudadano
            if($input["adjunto_ciudadano"] ?? false) {
                $input['adjunto_ciudadano'] = implode(",", $input["adjunto_ciudadano"]);
            }

            // Valida y asigna el nombre de la tipo de la solicitud
            $input['tipo_solicitud_nombre'] = isset($input["pqr_tipo_solicitud_id"])
            ? DB::table('pqr_tipo_solicitud')->select('nombre')->where('id', $input['pqr_tipo_solicitud_id'])->first()->nombre
            : null;

            // Consulta el máximo consecutivo de los PQRS
            $pqr_id = PQR::select(DB::raw("MAX(CAST(SUBSTR(pqr_id, 8) AS SIGNED)) AS consecutivo"))->where("vigencia", date("Y"))->pluck("consecutivo")->first();
            // Si ya existe un registro de PQRS, al consecutivo se incrementa en 1
            if($pqr_id) {
                // Calcula el consecutivo tentativo del PQR
                $pqr_id_validar = date("Y")."PQR".($pqr_id+1);
                // Consulta si ya existe un pqr_id de la vigencia actual que sea de migración
                $existe_pqr = PQR::where("pqr_id", $pqr_id_validar)->where("vigencia", date("Y")."1")->count();
                // Si existe el pqr_id de un PQR de migración, al nuevo pqr se le concatena al final "-2"
                if($existe_pqr) {
                    $input["pqr_id"] = date("Y")."PQR".($pqr_id+1)."-2";
                } else {
                    $input["pqr_id"] = date("Y")."PQR".($pqr_id+1);
                }
            } else { // De lo contrario es el primer PQR
                $input["pqr_id"] = date("Y")."PQR1";
            }
            // Valida si el campo de funcionario_users_id esta lleno
            if(isset($input["funcionario_users_id"])) {
                $funcionario_destinatario = User::find($input["funcionario_users_id"]);
                $input["funcionario_destinatario"] = $funcionario_destinatario["fullname"];
                $input["dependency_id"] = $funcionario_destinatario->dependencies->id;

                if($funcionario_destinatario->hasRole('Operadores')){
                    $input["operador"] = $input["funcionario_users_id"];
                    $input["operador_name"] = $input["funcionario_destinatario"];
                }

            }
            if($input["estado"] != "Cancelado" && $input["estado"] != "Abierto") {
                // Obtiene todos los dias no laborales disponibles
                $holidayCalendars = HolidayCalendar::get()->toArray();
                // Obtiene el horario laboral
                $workingHours = WorkingHours::latest()->first();
                //Obiene la fecha actual
                $currentDate = date("Y-m-d H:i:s");
                // Transforma la fecha actual en un string
                $dateObtained = strval($currentDate);
                // Divide la fecha actual en fecha y hora
                $arrayDate = explode(" ",$dateObtained);
                // Separa la hora en horas,minutos y segundos
                $hour = explode(":",$arrayDate[1]);
                // Se valida que los minutos de la hora de la fecha actual esten en cero
                if($hour[1]=="00"){
                    // Se le resta un minuto
                    $currentDate = strtotime ( '-1 minute' , strtotime($currentDate) ) ;
                    // Se asigna el nuevo valor de la hora a la fecha actual
                    $currentDate = date ("Y-m-d H:i:s",  $currentDate);
                }

                // Si el ciudadano esta creando el PQR, no debe de asignarse fecha de vencimiento
                if(!Auth::user()->hasRole('Ciudadano')) {

                    if(isset($input["tipo_plazo"]) && isset($input["plazo"])) {
                        // Calcula la fecha de vencimiento de la solicitud
                        $expiration_date = $this->calculateFutureDate(
                            Arr::pluck($holidayCalendars, 'date'),
                            // $ticRequest->created_at,
                            $currentDate,
                            "Días",
                            $input["tipo_plazo"],
                            $input["plazo"],
                            $workingHours
                        );

                        // Calcula la fecha temprana de la solicitud
                        $expiration_date_temprana = $this->calculateFutureDate(
                            Arr::pluck($holidayCalendars, 'date'),
                            // $ticRequest->created_at,
                            $currentDate,
                            "Días",
                            $input["tipo_plazo"],
                            $input["temprana"],
                            $workingHours
                        );
                    } else if(!empty($input["pqr_eje_tematico_id"])) {
                        $eje_tematico = PQREjeTematico::where("id", $input["pqr_eje_tematico_id"])->first();
                        // Calcula la fecha de vencimiento de la solicitud
                        $expiration_date = $this->calculateFutureDate(
                            Arr::pluck($holidayCalendars, 'date'),
                            // $ticRequest->created_at,
                            $currentDate,
                            $eje_tematico->plazo_unidad,
                            $eje_tematico->tipo_plazo,
                            $eje_tematico->plazo,
                            $workingHours
                        );

                        // Calcula la fecha temprana de la solicitud
                        $expiration_date_temprana = $this->calculateFutureDate(
                            Arr::pluck($holidayCalendars, 'date'),
                            // $ticRequest->created_at,
                            $currentDate,
                            $eje_tematico->plazo_unidad,
                            $eje_tematico->tipo_plazo,
                            $eje_tematico->temprana,
                            $workingHours
                        );
                    }

                    // Asigna los datos para actualizar la solicitud
                    $input['fecha_vence'] = $expiration_date[0];
                    $input['fecha_temprana'] = $expiration_date_temprana[0];
                }
            }
            // Si el estado es finalizado, se asigna la fecha de finalización
            if($input["estado"] == "Finalizado") {
                $input['fecha_fin'] = date('Y-m-d H:i:s');

            }



            // Inicia la transaccion
            DB::beginTransaction();

            // Inserta el registro en la base de datos
            $pQR = $this->pQRRepository->create($input);

            $notificacion_funcionario = $pQR->toArray();
            $notificacion = $pQR->toArray();

            // Condición para validar si existe algún registro de copia
            if (!empty($input['pqr_copia'])) {
                // Eliminar los registros de las copias según el id del registro principal del PQR
                PQRCopia::where('pqr_id', $pQR->id)->where("tipo","=","Copia")->delete();
                //texto para almacenar en la tabla interna
                $textRecipients = array();
                //recorre los destinatarios
                foreach($input['pqr_copia'] as $recipent){
                    //array de destinatarios
                    $recipentArray = json_decode($recipent, true);
                    $recipentArray["vigencia"] = date("Y");
                    $recipentArray["pqr_id"] = $pQR->id;
                    $recipentArray["tipo"] = "Copia";
                    $recipentArray["name"] = $recipentArray["fullname"];
                    $textRecipients[] = $recipentArray["name"];

                    // Definir mensaje por defecto (para casos no especificados)
                    // Asunto del email
                    $asunto = json_decode('{"subject": "Notificación de copia de   PQR  ' . $pQR->pqr_id . '"}');
                    // Se agrega este link en la notificaciones de pqr, para que el boton de la notificación redireccione directamente a PQR
                    $notificacion_funcionario['link'] = '/pqrs/p-q-r-s?qder=' . base64_encode($pQR->id);
                     // Nombre del funcionario asignado al PQR
                     $notificacion_funcionario["name"] = $recipentArray["name"];
                     // Mensaje para el correo dirigido al funcionario destinatario
                     $notificacion_funcionario["mensaje"] = "Le informamos que ha sido añadido/a como destinatario/a de copia en el registro de la <strong>{$pQR->pqr_id}</strong> <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes.";

                    // Enviar notificación por correo al funcionario asignado
                    SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_funcionario',$asunto,$notificacion_funcionario,$recipentArray["email"],'PQRSD');

                    PQRCopia::create($recipentArray);
                }
            }
            // Asigna a la foranea en el historial de pqr, el id del registro principal de PQR
            $input["pqr_pqr_id"] = $pQR->id;
            $input["linea_tiempo"] = $pQR->getLineaTiempoAttribute();

            $historial = $input;
            $historial['action'] = "Creación de registro";

            // Guarda en la tabla historial de PQR
            PQRHistorial::create($historial);
            // Valida si es el usuario que esta creando el PQR, tiene el rol de administrador de requerimientos
            if(Auth::user()->hasRole('Administrador de requerimientos')) {
                $rol = "Administrador";
            } else if(Auth::user()->hasRole('Consulta de requerimientos')) { // Valida si es el usuario que esta creando el PQR, tiene el rol de Consulta de requerimientos
                $rol = "Consultor";
            } else if(Auth::user()->hasRole('Operadores')) { // Valida si es el usuario que esta creando el PQR, tiene el rol de operadores
                $rol = "Operador";
            } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta creando el PQR, tiene el rol de ciudadano
                $rol = "Ciudadano";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del PQR recién creado
            PQRLeido::create([
                'nombre_usuario' => Auth::user()->name,
                'tipo_usuario' => $rol,
                'accesos' => date("Y-m-d H:i:s"),
                'vigencia' => date("Y"),
                'pqr_id' => $pQR->id,
                'users_id' => Auth::user()->id
            ]);


            // Sincroniza las relaciones del registro
            $pQR->pqrCopia;
            $pQR->pqrCompartida;
            $pQR->pqrCopiaCopmpartida;
            $pQR->pqrLeidos;
            $pQR->pqrEjeTematico;
            $pQR->pqrTipoSolicitud;
            $pQR->funcionarioUsers;
            $pQR->ciudadanoUsers;
            $pQR->pqrAnotacions;
            $pQR->pqrHistorial;
            $pQR->serieClasificacionDocumental;
            $pQR->subserieClasificacionDocumental;
            $pQR->oficinaProductoraClasificacionDocumental;
            // Efectua los cambios realizados
            DB::commit();


            if (isset($input["email_ciudadano"])) {

                $asunto = json_decode('{"subject": "Actualización sobre el PQR ' . $pQR->pqr_id . '"}');

                // Se agrega este link en la notificaciones de pqr, para que el boton de la notificación redireccione directamente a PQR
                $notificacion['link'] = '/pqrs/p-q-r-s';
                $notificacion["mensaje"] = "Le informamos que se ha creado un <b>PQR con número ".$pQR->pqr_id."</b> el cual actualmente se encuentra en estado <b>'".$input["estado"]."'</b>.";
                $notificacion["consecutive"] = $pQR->pqr_id ?? '';

                try {
                    SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_ciudadano',$asunto,$notificacion,$input["email_ciudadano"],'PQRSD');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController  - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController  - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage());
                }
            }

            return $this->sendResponse($pQR->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
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
     * @param UpdatePQRRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePQRRequest $request) {
        $input = $request->all();

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        // Obtiene el ID del usuario en sesión
        $input["users_id"] = Auth::user()->id;
        $input["users_name"] = Auth::user()->fullname;


        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Valida que se halla ingresado un ciudadno, sea existente o personalizado
        if(!(isset($input["nombre_ciudadano"]) && (isset($input["email_ciudadano"]) || isset($input["nombre_ciudadano"]) == "Ciudadano anónimo"))) {
            return $this->sendSuccess('<strong>El ciudadano es requerido</strong>'. '<br>' . "autocomplete un ciudadano o asigne uno personalizado", 'warning');
        }
        // Valida que se halla ingresado un eje temático, sea existente o personalizado
        if(isset($input["estado"]) && $input["estado"] != 'Abierto' && $input["estado"] != 'Asignado' && $input["estado"] != 'Esperando respuesta del ciudadano' && $input["estado"] != 'Cancelado' && !(isset($input["nombre_ejetematico"]) && isset($input["plazo"]) && isset($input["tipo_plazo"]))) {
            return $this->sendSuccess('<strong>El eje temático es requerido</strong>'. '<br>' . "autocomplete el eje temático o asigne uno personalizado", 'warning');
        }
        // Valida que se halla ingresado un eje temático, sea existente o personalizado
        // if( $input["estado"] == 'Esperando respuesta del ciudadano' && !isset($input["adjunto_espera_ciudadano"]) ) {
        //     return $this->sendSuccess('<strong>Adjunto requerido</strong>'. '<br>' . "Ingrese por lo menos un  documento, con peso maximo de 5MB.", 'warning');
        // }
        // Valida si seleccionó o no un adjunto
        if(isset($input["adjunto"])) {
            $input['adjunto'] = implode(",",(array) $input["adjunto"]);
        }
        // Valida si seleccionó o no el documento principal del PQR
        if (isset($input["document_pdf"])) {
            $input['document_pdf'] = implode(",", (array) $input["document_pdf"]);
        }
         // Valida si seleccionó o no el documento para la espera del ciudadano
         if (isset($input["adjunto_espera_ciudadano"])) {
            $input['adjunto_espera_ciudadano'] = implode(",", (array) $input["adjunto_espera_ciudadano"]);
        }
        // Valida si seleccionó o no el adjunto de la respuesta parcial
        if (isset($input["adjunto_r_parcial"])) {
            $input['adjunto_r_parcial'] = implode(",", (array) $input["adjunto_r_parcial"]);
        }
        // Valida si seleccionó o no el documento adj_oficio_respuesta
        if (isset($input["adj_oficio_respuesta"])) {
            $input['adj_oficio_respuesta'] = implode(",", (array) $input["adj_oficio_respuesta"]);
        }

        // Valida si seleccionó o no el documento principal del PQR
        if (isset($input["adj_oficio_solicitud"])) {
            $input['adj_oficio_solicitud'] = implode(",", (array) $input["adj_oficio_solicitud"]);
        }

        // Valida si seleccionó o no un adjunto el ciudadano
        if($input["adjunto_ciudadano"] ?? false) {
            $input['adjunto_ciudadano'] = implode(",", (array) $input["adjunto_ciudadano"]);
        }

        //Valida si desea recibir la respuesta por correo electronico
        $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;


        // Valida si seleccionó o no un adjunto con rol de admin de pqr finalizadas
        if($input["adjunto_finalizado"] ?? false) {
            $input['adjunto_finalizado'] = implode(",", (array) $input["adjunto_finalizado"]);
        }

        if(!empty($input["funcionario_users_id"])) {
            $funcionario_destinatario = User::find($input["funcionario_users_id"]);
            $input["funcionario_destinatario"] = $funcionario_destinatario["fullname"];
            $input["dependency_id"] = $funcionario_destinatario->dependencies->id;

            if($funcionario_destinatario->hasRole('Operadores')){
                $input["operador"] = $input["funcionario_users_id"];
                $input["operador_name"] = $input["funcionario_destinatario"];
            }
        }
        // Ingresa a calcular la fecha de vencimiento si el estado es asignado, finalizado o en tramite y que no tenga una fecha de vencimiento calculcada previa
        if (($input["estado"] == "Asignado" || $input["estado"] == "Finalizado" || $input["estado"] == "En trámite")) {
            // Obtiene todos los dias no laborales disponibles
            $holidayCalendars = HolidayCalendar::get()->toArray();
            // Obtiene el horario laboral
            $workingHours = WorkingHours::latest()->first();
            //Obiene la fecha actual
            $currentDate = $pQR["created_at"];
            // Transforma la fecha actual en un string
            $dateObtained = strval($currentDate);
            // Divide la fecha actual en fecha y hora
            $arrayDate = explode(" ",$dateObtained);
            // Separa la hora en horas,minutos y segundos
            $hour = explode(":",$arrayDate[1]);
            // Se valida que los minutos de la hora de la fecha actual esten en cero
            if($hour[1]=="00"){
                // Se le resta un minuto
                $currentDate = strtotime ( '-1 minute' , strtotime($currentDate) ) ;
                // Se asigna el nuevo valor de la hora a la fecha actual
                $currentDate = date ("Y-m-d H:i:s",  $currentDate);
            }

            if(isset($input["tipo_plazo"]) && isset($input["plazo"])) {
                // Calcula la fecha de vencimiento de la solicitud
                $expiration_date = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    "Días",
                    $input["tipo_plazo"],
                    $input["plazo"],
                    $workingHours
                );

                // Calcula la fecha temprana de la solicitud
                $expiration_date_temprana = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    "Días",
                    $input["tipo_plazo"],
                    $input["temprana"],
                    $workingHours
                );
            } else if(!empty($input["pqr_eje_tematico_id"])) {
                $eje_tematico = PQREjeTematico::where("id", $input["pqr_eje_tematico_id"])->first();
                // Calcula la fecha de vencimiento de la solicitud
                $expiration_date = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    $eje_tematico->plazo_unidad,
                    $eje_tematico->tipo_plazo,
                    $eje_tematico->plazo,
                    $workingHours
                );

                // Calcula la fecha temprana de la solicitud
                $expiration_date_temprana = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    $eje_tematico->plazo_unidad,
                    $eje_tematico->tipo_plazo,
                    $eje_tematico->temprana,
                    $workingHours
                );
            }

            if(isset($input["tipo_plazo"]) && isset($input["plazo"])) {
                // Asigna los datos para actualizar la solicitud
                if(empty($input["fecha_fin_parcial"])) {
                    $input['fecha_vence'] = $expiration_date[0];
                }
                $input['fecha_temprana'] = $expiration_date_temprana[0];
            }
        }
        // Si el estado es finalizado, se asigna la fecha de finalización
        if($input["estado"] == "Finalizado") {

            $input['fecha_fin'] = $input['fecha_fin'] ?? date('Y-m-d H:i:s');
            $dias_restantes = UtilController::diasRestantes($input['tipo_plazo'],$input['fecha_vence'],$input["estado"],$input["fecha_fin"]);

            $input['dias_restantes'] = $dias_restantes;
        } else if($input["estado"] == "Respuesta parcial") {// Si el estado es Respuesta parcial, se recalcula la fecha de vencimiento
            // Se asigna la fecha actual al campo de fecha_fin_parcial
            $input['fecha_fin_parcial'] = date('Y-m-d H:i:s');
            // Obtiene todos los dias no laborales disponibles
            $holidayCalendars = HolidayCalendar::get()->toArray();
            // Obtiene el horario laboral
            $workingHours = WorkingHours::latest()->first();
            //Obiene la fecha actual
            $currentDate = $pQR["created_at"];
            // Transforma la fecha actual en un string
            $dateObtained = strval($currentDate);
            // Divide la fecha actual en fecha y hora
            $arrayDate = explode(" ",$dateObtained);
            // Separa la hora en horas,minutos y segundos
            $hour = explode(":",$arrayDate[1]);
            // Se valida que los minutos de la hora de la fecha actual esten en cero
            if($hour[1]=="00"){
                // Se le resta un minuto
                $currentDate = strtotime ( '-1 minute' , strtotime($currentDate) ) ;
                // Se asigna el nuevo valor de la hora a la fecha actual
                $currentDate = date ("Y-m-d H:i:s",  $currentDate);
            }


            if(isset($input["tipo_plazo"]) && isset($input["plazo"])) {
                // Calcula la fecha de vencimiento de la solicitud
                $expiration_date = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    "Días",
                    $input["tipo_plazo"],
                    $input["plazo"]*2,// Se duplica el plazdo desde la fecha de creación del PQR
                    $workingHours
                );

                // Calcula la fecha temprana de la solicitud
                $expiration_date_temprana = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    "Días",
                    $input["tipo_plazo"],
                    $input["temprana"]*2,// Se duplica el plazdo desde la fecha de creación del PQR
                    $workingHours
                );
            } else if(!empty($input["pqr_eje_tematico_id"])) {
                $eje_tematico = PQREjeTematico::where("id", $input["pqr_eje_tematico_id"])->first();
                // Calcula la fecha de vencimiento de la solicitud
                $expiration_date = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    $eje_tematico->plazo_unidad,
                    $eje_tematico->tipo_plazo,
                    $eje_tematico->plazo*2,// Se duplica el plazdo desde la fecha de creación del PQR
                    $workingHours
                );

                // Calcula la fecha temprana de la solicitud
                $expiration_date_temprana = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    // $ticRequest->created_at,
                    $currentDate,
                    $eje_tematico->plazo_unidad,
                    $eje_tematico->tipo_plazo,
                    $eje_tematico->temprana*2,// Se duplica el plazdo desde la fecha de creación del PQR
                    $workingHours
                );
            }

            // Asigna los datos para actualizar la solicitud
            $input['fecha_vence'] = $expiration_date[0];
            $input['fecha_vence_temprana'] = $expiration_date_temprana[0];
        }


        // Valida que hallan devuelto la PQRS
        if($input["estado"] == "Devuelto"){
            if(empty($input["operador"])){
                // Consulta el primer usuario que alla puesto el pqr en asignado si no fue el operador
                $devolerAsignador = PQRHistorial::where('estado', 'Asignado')->where('pqr_pqr_id', $input["id"])->firstOrFail()->toArray();
                $input["funcionario_destinatario"] = $devolerAsignador["users_name"];
                $input["funcionario_users_id"] = $devolerAsignador["users_id"];
            } else {
                // Valida de que el operador lo devuelva
                if($input["operador"] == $input["users_id"]){
                    $input["funcionario_destinatario"] = $input["funcionario_destinatario"];
                    $input["funcionario_users_id"] = $input["funcionario_users_id"];
                } else {
                    // Entra cuando el que devolvio fue el funcionario
                    $input["funcionario_destinatario"] = $input["operador_name"];
                    $input["funcionario_users_id"] = $input["operador"];
                }
            }
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Organiza campos booleanos
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;
            // Actualiza el registro
            $pQR = $this->pQRRepository->update($input, $id);

            $notificacion_funcionario = $pQR->toArray();
            $notificacion = $pQR->toArray();

            // Condición para validar si existe algún registro de copia
            if (!empty($input['pqr_copia'])) {
                // Eliminar los registros de las copias según el id del registro principal del PQR
                PQRCopia::where('pqr_id', $pQR->id)->where("tipo","=","Copia")->delete();
                //texto para almacenar en la tabla interna
                $textRecipients = array();
                //recorre los destinatarios
                foreach($input['pqr_copia'] as $recipent){
                    //array de destinatarios
                    $recipentArray = json_decode($recipent, true);
                    $recipentArray["vigencia"] = date("Y");
                    $recipentArray["pqr_id"] = $id;
                    $recipentArray["tipo"] = "Copia";
                    $recipentArray["name"] = $recipentArray["fullname"];
                    $textRecipients[] = $recipentArray["name"];
                    PQRCopia::create($recipentArray);
                    $email = User :: where('id', $recipentArray['users_id'])->first()->email;
                    // Definir mensaje por defecto (para casos no especificados)
                    // Asunto del email
                    $asunto = json_decode('{"subject": "Notificación de copia de   PQR  ' . $pQR->pqr_id . '"}');
                    // Se agrega este link en la notificaciones de pqr, para que el boton de la notificación redireccione directamente a PQR
                    $notificacion_funcionario['link'] = '/pqrs/p-q-r-s?qder=' . base64_encode($pQR->id);
                     // Nombre del funcionario asignado al PQR
                     $notificacion_funcionario["name"] = $recipentArray["name"];
                     // Mensaje para el correo dirigido al funcionario destinatario
                     $notificacion_funcionario["mensaje"] = "Le informamos que ha sido añadido/a como destinatario/a de copia en el registro de la <strong>{$pQR->pqr_id}</strong> <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes.";
                     $notificacion_funcionario["consecutive"] = $pQR->pqr_id ?? '';

                    // Enviar notificación por correo al funcionario asignado
                    SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_funcionario',$asunto,$notificacion_funcionario,$email,'PQRSD');
                }
            }
            // Asigna a la foranea en el historial de pqr, el id del registro principal de PQR
            $input["pqr_pqr_id"] = $pQR->id;

            $input["linea_tiempo"] = $pQR->getLineaTiempoAttribute();

            $historial = $input;
            $historial['action'] = "Actualización de registro";

            // Guarda en la tabla historial de PQR
            PQRHistorial::create($historial);
            // Sincroniza las relaciones del registro
            $pQR->pqrCopia;
            $pQR->pqrCompartida;
            $pQR->pqrCopiaCopmpartida;
            $pQR->pqrLeidos;
            $pQR->pqrEjeTematico;
            $pQR->pqrTipoSolicitud;
            $pQR->funcionarioUsers;
            $pQR->ciudadanoUsers;
            $pQR->pqrAnotacions;
            $pQR->pqrHistorial;
            $pQR->serieClasificacionDocumental;
            $pQR->subserieClasificacionDocumental;
            $pQR->oficinaProductoraClasificacionDocumental;

            // Efectua los cambios realizados
            DB::commit();


            // Obtener el estado del PQR desde el input
            $estado = $input['estado'];

            // Definir mensaje por defecto (para casos no especificados)
             // Asunto del email
             $asunto = json_decode('{"subject": "Notificación sobre el PQR ' . $pQR->pqr_id . '"}');

             // Nombre el ciudadano remitente
             $notificacion["name"] = $input["nombre_ciudadano"];
               // Se agrega este link en la notificaciones de pqr, para que el boton de la notificación redireccione directamente a PQR
              $notificacion_funcionario['link'] = '/pqrs/p-q-r-s?qder=' . base64_encode($pQR->id);
             // Valida si el estado del PQR es asignado
            // $notificacion["mensaje"] = "Se ha realizado una modificación en el Sistema de Intraweb - Ventanilla única virtual para el PQR con número ".$pQR->pqr_id.". Su respuesta es necesaria para continuar con la gestión de la solicitud.";

            // Seleccionar mensaje según el estado del PQR
            switch ($estado) {
                case "Finalizado":
                    if (!empty($input["empresa_traslado"])) {
                        $notificacion["mensaje"] = "Le informamos que su solicitud con número de radicado <strong>{$input["pqr_id"]}</strong> ha sido trasladada a la entidad competente <strong>{$input["empresa_traslado"]}</strong> para su gestión. <br><br> Por esta razón, su petición, queja o reclamo (PQR) ha sido cerrada en nuestra entidad La entidad, y a partir de ahora será <strong>{$input["empresa_traslado"]}</strong> quien se encargará de su seguimiento y resolución.<br><br>Agradecemos su comprensión y quedamos atentos a cualquier inquietud.";

                    } else {
                        //Consulta las variables para implementar encuesta de satisfacción
                        $permiteEncuesta = Variables::where('name', 'enviar_encuesta_satisfaccion_pqr')->pluck('value')->first();
                        $encuesta = (!empty($permiteEncuesta) && $permiteEncuesta == 'Si')
                        ?  "<br><p>Su opinión es muy importante para nosotros. Con el objetivo de mejorar continuamente nuestro servicio y garantizar su satisfacción, lo invitamos a completar la encuesta de satisfacción correspondiente a su PQRSDF.<br>📋<a href='" . url('pqrs/survey-satisfaction-pqrs') . "?cHFyX2lk=" . JwtController::generateToken($input["id"])  . "'> Responder encuesta de satisfacción</a></p>"
                        : "";

                        $notificacion["mensaje"] = "Le informamos que su PQR con número <strong>{$pQR->pqr_id}</strong> ha sido finalizado exitosamente. <br><b>Respuesta: </b><em>{$pQR->respuesta}</em>." . $encuesta;
                    }
                    break;
                case "Cancelado":
                    $notificacion["mensaje"] = "El PQR con número <strong>{$pQR->pqr_id}</strong> ha sido cancelado. <br><br> Observación: <em>{$pQR->respuesta}</em>.";

                    break;
                case "Esperando respuesta del ciudadano":
                    $notificacion["mensaje"] = "Se ha realizado una pregunta relacionada con el PQR número <strong>{$pQR->pqr_id}</strong>. <br><br> <strong>Pregunta:</strong> {$pQR->pregunta_ciudadano}. <br><br> Su respuesta es necesaria para continuar con la gestión de la solicitud.";
                    break;
                case "Respuesta parcial":
                    $notificacion["mensaje"] = "El PQR con número <strong>{$pQR->pqr_id}</strong> se encuentra en estado <strong>{$estado}</strong>. <br><br> <strong>Respuesta parcial:</strong> {$pQR->respuesta_parcial} <br> <strong>Nueva fecha de vencimiento:</strong> {$pQR->fecha_vence}.";
                    break;

                case "Asignado":
                    // Consulta para obtener el nombre de la dependencia del usuario que le están asignando el PQR
                    $dependencia_funcionario = User::select('dependencias.nombre')
                    ->join('dependencias', 'users.id_dependencia', '=', 'dependencias.id')
                    ->where('users.id', $input["funcionario_users_id"])
                    ->pluck("nombre")->first();
                    $input['dependencia_funcionario'] = $dependencia_funcionario;
                    // Mensaje dirigido al ciudadano para informar sobre la asignación del PQR
                    $notificacion["mensaje"] = "El PQR con número ".$pQR->pqr_id." ha sido asignado a la dependencia '".$input['dependencia_funcionario']."' con un plazo de ".$input["plazo"]." ".($eje_tematico["plazo_unidad"] ?? "días").".";

                    try {
                        // Nombre del funcionario asignado al PQR
                        $notificacion_funcionario["name"] = $funcionario_destinatario["name"];
                        // Mensaje para el correo dirigido al funcionario destinatario
                        $notificacion_funcionario["mensaje"] = "Le informamos que el PQR con número <strong>{$pQR->pqr_id}</strong> ha sido asignado por el administrador de PQRS. El plazo estimado para su resolución es de <strong>{$input["plazo"]} " . ($eje_tematico["plazo_unidad"] ?? "días") . "</strong>.";

                        $notificacion_funcionario["consecutive"] = $pQR->pqr_id ?? '';

                        // Enviar notificación por correo al funcionario asignado
                        SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_funcionario',$asunto,$notificacion_funcionario,$funcionario_destinatario["email"],'PQRSD');

                    } catch (\Swift_TransportException $exception) {
                        // Manejar la excepción de autenticación SMTP aquí
                        $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController 818 - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage());
                    } catch (\Exception $exception) {
                        // Por ejemplo, registrar el error
                        $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController 821 - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage(). ' Linea: '.$e->getLine(). ' Consecutivo: '.$pQR->pqr_id);
                    }
                break;

                case "Devuelto":
                    $notificacion["mensaje"] = "El PQR con número <strong>{$pQR->pqr_id}</strong> se encuentra en estado <strong>{$estado}</strong>. <br><br> <strong>Razón de la devolución:</strong> {$pQR->devolucion}.";
                    break;


                default:
                    $notificacion["mensaje"] = "El PQR con número <strong>{$pQR->pqr_id}</strong> se encuentra en estado <strong>{$estado}</strong>.";

                    break;
            }

            // Valida si existe un correo de ciudadano para enviar la notificación
            if (isset($input["email_ciudadano"])) {

                try {
                    $notificacion["consecutive"] = $pQR->pqr_id ?? '';

                    SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_ciudadano',$asunto,$notificacion,$input["email_ciudadano"],'PQRSD');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController 818 - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController 821 - '. Auth::user()->fullname.' -  Error: '.$exception->getMessage(). ' Linea: '.$e->getLine(). ' Consecutivo: '.$pQR->pqr_id);
                }
            }

            return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', $e->getFile().' - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: ' . $e->getMessage(). ' Linea: '.$e->getLine(). ' Consecutivo: '.($pQR->pqr_id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }



    /**
     * Elimina un PQR del almacenamiento
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

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $pQR->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine(). ' Consecutivo: '.($pQR->pqr_id ?? 'Desconocido'));
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

        $data = JwtController::decodeToken($input['data']);

        $query =$request->input('query');

        if($query){
            $pqrs = DB::table('pqr')
            ->where('pqr_id', 'like', '%'.$query.'%')
            ->get();
            return $this->sendResponse($pqrs->toArray(), trans('data_obtained_successfully'));
        }

        // Decodifica los campos filtrados
        $filtros = $request["filtros"];
        // Valida si en los filtros realizados viene el filtro de vigencia, si no viene, le asigna la vigencia actual
        if(!$filtros) {
            // Se comenta para que liste todos los registros
            // $filtros = "vigencia = ".date("Y");
        }
        $pqrs_personales = "";
        // Valida si en los filtros realizados viene el filtro de pqrs_propios
        if(stripos($filtros, "pqrs_propios") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de pqrs_propios en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, 'pqrs_propios') !== false;
            }))[0];
            // Se extrae el valor del filtro pqrs_propios
            $pqrs_personales = strtolower(explode("%", $filtro[$posicion])[1]);
            // Se elimina el filtro de pqrs_propios del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }
        // Por defecto el tipo de usuario es funcionario
        $tipo_usuario = "Funcionario";
        // Valida si es el usuario que esta en sesión es un administrador o un consultor de requerimientos y que no esté consultan sus PQRS personales
        if((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos')) && $pqrs_personales != "pqrs_personales") {
            $tipo_usuario = "Administrador";
        } else if(Auth::user()->hasRole('Operadores') && $pqrs_personales != "pqrs_personales") { // Valida si es el usuario que esta en sesión es un operador y que no esté consultan sus PQRS personales
            $tipo_usuario = "Operador";
        } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta en sesión es un ciudadano
            $tipo_usuario = "Ciudadano";
        }

        $filtrar_pqrs = "";
        // Valida si en los filtros realizados viene el filtro de linea de tiempo
        if(strpos($filtros, "tipos_pqrs") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de linea de tiempo en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return strpos($value, 'tipos_pqrs') !== false;
            }))[0];
            // Se extrae la linea de tiempo a filtrar por el usuario
            $filtrar_pqrs = explode("%", $filtro[$posicion])[1];
            // Se elimina el filtro de linea de tiempo del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
            $consulta = "1=1";

            //Verifica si es de solo los vencidos
            if (strpos($filtros, "linea_tiempo LIKE '%VENCIDO%' AND estado LIKE '%FINALIZADO%'") !== false) {
                $filtros = str_replace("linea_tiempo LIKE '%VENCIDO%' AND estado LIKE '%FINALIZADO%'", "linea_tiempo LIKE '%VENCIDO%' AND estado = 'Finalizado'", $filtros);
            } else if(strpos($filtros, "estado LIKE '%SOLO VENCIDO%'") !== false){
                $filtros = str_replace("estado LIKE '%SOLO VENCIDO%'", "estado NOT LIKE '%Finalizado%' AND estado NOT LIKE '%Cancelado%'", $filtros);
            }
            // Valida si el filtro de PQRS esta definido en 'pendientes_ejecucion'
            if($filtrar_pqrs == "PENDIENTES_EJECUCION" && $tipo_usuario == "Funcionario") {
                // Consulta para seleccionar los PQRS que no esten finalizados ni cancelados y que sean asignados al usuario en sesión
                $consulta = "estado != 'Finalizado' AND estado != 'Cancelado' AND funcionario_users_id = ".Auth::user()->id;
            }
            // Se concatena la consulta generada anteriormente en caso de que hayan otros filtros, de lo contrario se asigna 1=1 para que no genere error
            $filtros = $filtros ? $filtros." AND ".$consulta : $consulta;
        }
        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["filtros"])) {
            $linea_tiempo = "";
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            if(strpos($filtros, "linea_tiempo") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de linea de tiempo en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return strpos($value, 'linea_tiempo') !== false;
                }))[0];
                // Se extrae la linea de tiempo a filtrar por el usuario
                $linea_tiempo = explode("%", $filtro[$posicion])[1];
                // Se elimina el filtro de linea de tiempo del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = implode(" AND ", $filtro);
            }
            // Se valida si no quedaron filtros diferentes a linea de tiempo, siendo asi, se asigna por defecto el filtro 1=1
            if(empty($filtros)) $filtros = "1=1";
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            switch ($tipo_usuario) {

                case "Administrador":
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", 'pqrCorrespondence', "encuesta"])->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->get()->toArray();
                break;

                case "Operador" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence", "encuesta"])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->get()->toArray();
                break;

                case "Ciudadano" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence"])->where(function($q) {
                        $q->where("users_id", Auth::user()->id);
                        $q->orWhere("ciudadano_users_id", Auth::user()->id);
                    })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->get()->toArray();
                break;

                default :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence"])->where(function($q) {
                        $q->where("funcionario_users_id", Auth::user()->id);
                        $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                    })->whereLineaTiempo($linea_tiempo)->whereRaw($filtros)->latest()->get()->toArray();
                break;
            }
        }
        else{
            // Valida si en los filtros realizados viene el filtro de linea de tiempo
            switch ($tipo_usuario) {

                case "Administrador":
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence", "encuesta"])->latest()->get()->toArray();
                break;

                case "Operador" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence", "encuesta"])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->latest()->get()->toArray();
                break;

                case "Ciudadano" :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence"])->where(function($q) {
                        $q->where("users_id", Auth::user()->id);
                        $q->orWhere("ciudadano_users_id", Auth::user()->id);
                    })->latest()->get()->toArray();
                break;

                default :
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $p_q_r_s = PQR::with(["pqrEjeTematico","pqrTipoSolicitud", "funcionarioUsers", "pqrCorrespondence"])->where(function($q) {
                        $q->where("funcionario_users_id", Auth::user()->id);
                        $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
                    })->latest()->get()->toArray();
                break;
            }
        }
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('PQRS').'.'.$fileType;

        // Guarda el archivo excel en ubicacion temporal
        // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Descarga el archivo generado
            $filePDF = PDF::loadView("pqrs::p_q_r_s.report_pdf", ['data' => $p_q_r_s])->setPaper("a4", "landscape");
            return $filePDF->download("Reporte pqrs.pdf");
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel

            //Consulta las variables para implementar encuesta de satisfacción
            $variable = Variables::where('name', 'enviar_encuesta_satisfaccion_pqr')->pluck('value')->first();

            // Longitud de columnas en el excel. si tiene activadas las encuesta es mas largo.
             $longitd = (!empty($variable) && $variable == 'Si') ? "U" : "R";
             $p_q_r_s[0]['encuesta_sactisfaccion'] = (!empty($variable) && $variable == 'Si') ? 'Si': "No";
            // Descarga el archivo generado
            return Excel::download(new RequestExportPQRS('pqrs::p_q_r_s.report_excel', JwtController::generateToken($p_q_r_s), $longitd), $fileName);
        }
    }

    /**
     * Organiza la exportacion de datos avanzados
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportReportAvanzado(Request $request) {

        $input = $request->all();
        if(!empty($input["extraParam"]) && $input["extraParam"] === "vencidos_pendientes"){
            $inputFileType = 'Xlsx';
            $inputFileName =  dirname(app_path()).'/Modules/PQRS/Resources/views/p_q_r_s/reports/reporte_vencidad_pediente_repta.xlsx';

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);
            $spreadsheet->setActiveSheetIndex(0);

            $configuracion = ConfigurationGeneral::first();
                if ($configuracion->logo) {
                    //tratado para agregar el logo de la entidad.
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath(storage_path( 'app/public/'.$configuracion->logo)); /* put your path and image here */
                    $drawing->setCoordinates('a1');
                    $drawing->setWorksheet($spreadsheet->getActiveSheet());
                    $drawing->setHeight(90);
                    $drawing->setResizeProportional(true);
                    $drawing->setOffsetX(20); // this is how
                    $drawing->setOffsetY(10);
                }

            $dependencias = Dependency::select('nombre', 'id')->get()->toArray();

            $cellValue=3;

            $xlsxColumns = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA','AB','AC','AD','AE','AF','AG','AH','AI'];

            $xlsxColumnsValue = 0;

            $dependencias[] = ['nombre' => 'Sin Dependencia', 'id' => null];


            foreach ($dependencias as  $value) {
                $spreadsheet->getActiveSheet()->mergeCells('A'.$cellValue.':B'.$cellValue);
                $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue , $value['nombre']);

                if (strpos($input["filtros"], 'fecha_vence') !== false) {
                    // expresion regular que trae de los filtros la cha de inicio
                    preg_match('/fecha_vence\) >= (.{12})/', $input["filtros"], $rangeStart);
                    // expresion regular que trae la fecha final
                    preg_match('/fecha_vence\) <= (.{12})/', $input["filtros"],$rangeEnd);


                    // extrae la fecha de inicio para ponerla en el encabezado.
                    preg_match('/\d{4}-\d{2}-\d{2}/', $rangeStart[0], $matches);
                    // Convertimos la fecha al formato 'dd mes aaaa'
                    $initDate = $matches[0];

                    // extrae la fecha de fin para ponerla en el encabezado.
                    preg_match('/\d{4}-\d{2}-\d{2}/', $rangeEnd[0], $matches);
                    // Convertimos la fecha al formato 'dd mes aaaa'
                    $endDate = $matches[0];

                    $initDateSplit = explode('-', $initDate);
                    $endDateSplit = explode('-', $endDate);

                    $mesInit = strtoupper (\Carbon\Carbon::create()->month($initDateSplit[1])->locale('es')->translatedFormat('F'));
                    $mesEnd = strtoupper (\Carbon\Carbon::create()->month($endDateSplit[1])->locale('es')->translatedFormat('F'));

                    $query = 'DATE(pqr.'.$rangeStart[0].'AND DATE(pqr.'. $rangeEnd[0]." AND ";
                } else {
                    $query = '';
                }

                if ($value['id'] === null) {
                    $condicion = $query."dependency_id IS NULL AND (estado = 'Asignado' or estado = 'En trámite' or estado = 'Esperando respuesta del ciudadano' or estado = 'Respuesta parcial' or estado = 'Devuelto')";
                } else {
                    $condicion = $query."dependency_id = ".$value['id']." AND (estado = 'Asignado' or estado = 'En trámite' or estado = 'Esperando respuesta del ciudadano' or estado = 'Respuesta parcial' or estado = 'Devuelto') AND 1=1";
                }

                $pqrs = PQR::whereLineaTiempo('VENCIDO')->whereRaw($condicion)->get()->toArray();


                // Establece por defecto las columnas en 0 o en -
                for ($i=0; $i < $xlsxColumnsValue + 1; $i++) {
                    $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$i].$cellValue, '0');
                }
                $totalCantidadpqr = count($pqrs);
                $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue].$cellValue, $totalCantidadpqr);
                $cellValue++;
                $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_indicadores_cumplimiento.xlsx"');
            header('Cache-Control: max-age=0');

            // Exportacion del archivo
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $inputFileType);
            $writer->save('php://output');
            exit;

            return $this->sendResponse($writer, trans('msg_success_update'));
        } else {
            $query = "";
            $cadenaFechas ='';

            if (strpos($input["filtros"], 'created_at') !== false) {
                // expresion regular que trae de los filtros la cha de inicio
                preg_match('/created_at\) >= (.{12})/', $input["filtros"], $rangeStart);
                // expresion regular que trae la fecha final
                preg_match('/created_at\) <= (.{12})/', $input["filtros"],$rangeEnd);


                // extrae la fecha de inicio para ponerla en el encabezado.
                preg_match('/\d{4}-\d{2}-\d{2}/', $rangeStart[0], $matches);
                // Convertimos la fecha al formato 'dd mes aaaa'
                $initDate = $matches[0];

                // extrae la fecha de fin para ponerla en el encabezado.
                preg_match('/\d{4}-\d{2}-\d{2}/', $rangeEnd[0], $matches);
                // Convertimos la fecha al formato 'dd mes aaaa'
                $endDate= $matches[0];


                $cadenaFechas =  $initDate .', A LA FECHA DE CORTE. '.$endDate ;
                $query = 'DATE(pqr.'.$rangeStart[0].'AND DATE(pqr.'. $rangeEnd[0];

            }else{
                $query = 'pqr.created_at is not NULL';
            }


            $inputFileType = 'Xlsx';
            $inputFileName =  dirname(app_path()).'/Modules/PQRS/Resources/views/p_q_r_s/reports/report_avanzado_para_mejorar.xlsx';

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);
            // $spreadsheet ='Reporte de indicadores de cumplimiento';
            $spreadsheet->setActiveSheetIndex(0);


            $configuracion = ConfigurationGeneral::first();


                $logo = isset($configuracion->logo) ? storage_path( 'app/public/'.$configuracion->logo) : dirname(app_path()).'/public/assets/img/favicon.ico';
                //tratado para agregar el logo de la entidad.
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setPath($logo); /* put your path and image here */
                $drawing->setCoordinates('a1');
                $drawing->setWorksheet($spreadsheet->getActiveSheet());
                $drawing->setHeight(70);
                $drawing->setResizeProportional(true);
                $drawing->setOffsetX(90); // this is how
                $drawing->setOffsetY(10);

            $dependencias = Dependency::select('nombre', 'id')->get()->toArray();

            $spreadsheet->getActiveSheet()->setCellValue('B1', 'CONSOLIDADO PQRS (Éste reporte se genera relacionando los tipos de PQRS bajo una fecha establecida según su radicación)'.$cadenaFechas .'
            (Peticiones, Quejas, Reclamos, Sugerencias, Denuncias)');

            $cellValue=4;
            $cellValueIni=4;

            // Obtiene los tipos de solicitudes para su posterior iteracion en el xlsx
            $requestTypes = PQRTipoSolicitud::select(["id","nombre"])->where('estado', 'Activo')->get()->toArray();

            $xlsxColumns = ['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z','AA','AB','AC','AD','AE','AF','AG','AH','AI'];

            $xlsxColumnsValue = 0;

            // Agrega los tipos de solicitudes al archivo
            foreach ($requestTypes as $key => $requestType) {
                $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$key]. 3,$requestType["nombre"] );
                $spreadsheet->getActiveSheet()->insertNewColumnBefore($xlsxColumns[$key+1]);
                $xlsxColumnsValue++;
            }

            // Elimina la columna que se agrega de mas
            $spreadsheet->getActiveSheet()->removeColumnByIndex($xlsxColumnsValue + 2);

            // Combinar celda del titulo de tipos de pqr
            $spreadsheet->getActiveSheet()->mergeCells('B' . 2 . ':'. $xlsxColumns[$xlsxColumnsValue - 1] . 2);

            // recorre las dependencias.
            foreach ($dependencias as  $value) {

                $spreadsheet->getActiveSheet()->setCellValue('A'.$cellValue ,$value['nombre']); //genera las columnas las dependencias.

                $pqrs= PQR::select('dependencias.nombre as nombre_dependencia', 'pqr.estado', 'pqr_tipo_solicitud.nombre as nombre_tipo', DB::raw('COUNT(pqr_tipo_solicitud.nombre) as cantidad_tipo'))
                ->join('pqr_tipo_solicitud', 'pqr.pqr_tipo_solicitud_id', '=', 'pqr_tipo_solicitud.id')
                ->join('users', 'pqr.funcionario_users_id', '=', 'users.id')
                ->join('dependencias', 'users.id_dependencia', '=', 'dependencias.id')
                ->where('dependencias.id', $value['id'])
                -> whereRaw($query)
                ->groupBy('dependencias.nombre', 'pqr_tipo_solicitud.nombre')
                ->get()->toArray();

                // Establece por defecto las columnas en 0 o en -
                for ($i=0; $i < $xlsxColumnsValue + 6; $i++) {
                    $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$i].$cellValue, '0');
                }

                $totalCantidadTipo = 0; // Inicializamos una variable para almacenar la suma

                foreach ($pqrs as $pqr) {
                    $index = 0;
                    foreach ($requestTypes as $key => $requestType) {
                        if ($pqr["nombre_tipo"] === $requestType["nombre"]) {
                            $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$key].$cellValue, $pqr['cantidad_tipo']);
                            break;
                        }
                    }
                    // Sumamos el valor de cantidad_tipo en cada iteración
                    $totalCantidadTipo += $pqr['cantidad_tipo'];
                }

                // Después del bucle, puedes establecer el total en la columna
                $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue].$cellValue, $totalCantidadTipo);

                // query que obtiene los totales de los vencidos.
                $TV_query = PQR::select('dependencias.nombre', 'pqr.estado', DB::raw("IF(pqr.fecha_fin > pqr.fecha_vence OR (CURDATE() > DATE(pqr.fecha_vence) AND pqr.fecha_fin IS NULL), 'Vencido', '') AS fron"), DB::raw('COUNT(*) as cantidad'))
                    ->join('users', 'pqr.funcionario_users_id', '=', 'users.id')
                    ->join('dependencias', 'users.id_dependencia', '=', 'dependencias.id')
                    ->where('pqr.estado', '!=', 'Cancelado')
                    ->where('dependencias.id', $value['id'])
                    ->groupBy('dependencias.nombre', 'fron')
                    -> whereRaw($query)
                    ->having('fron', 'Vencido')
                ->get()->toArray();

                // Inicializa las variables para calcular indicadores de cumplimiento
                $TV=0;
                $TFA=0;
                $TF=0;

                $TV=isset($TV_query[0]['cantidad']) ? $TV_query[0]['cantidad'] : 0;

                // estable el total de pqr vencidos
                $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue+1].$cellValue, isset($TV_query[0]['cantidad']) ? $TV_query[0]['cantidad'] : '0');

                $pqrs2= DB::table('pqr')
                ->select('pqr.pqr_id', 'd.nombre', 'pqr.estado',
                    DB::raw("
                        CASE
                            WHEN pqr.estado = 'Finalizado vencido justificado' THEN 'Finalizado vencido justificado'
                            WHEN pqr.fecha_fin > pqr.fecha_vence
                                OR (CURDATE() > DATE(pqr.fecha_vence)
                                AND pqr.estado != 'Finalizado vencido justificado'
                                AND pqr.fecha_fin IS NULL) THEN 'Vencido'
                            WHEN CURDATE() >= DATE(pqr.fecha_temprana)
                                AND pqr.estado NOT IN ('Finalizado', 'Finalizado vencido justificado', 'Abierto', 'Cancelado') THEN 'Próximo a vencer'
                            WHEN pqr.estado NOT IN ('Abierto', 'Cancelado', 'Finalizado vencido justificado') THEN 'A tiempo'
                            ELSE ''
                        END AS fron
                    "),
                    DB::raw('COUNT(*) as Cantidad')
                )
                ->join('users as u', 'pqr.funcionario_users_id', '=', 'u.id')
                ->join('dependencias as d', 'u.id_dependencia', '=', 'd.id')
                ->whereIn('pqr.estado', ['finalizado', 'Finalizado vencido justificado'])
                ->where('d.id', $value['id'])
                -> whereRaw($query)
                ->whereNotNull('pqr.created_at')
                ->groupBy('d.nombre', 'fron')
                ->get()->toArray();


                foreach ($pqrs2 as $pqrs) {
                    if ($pqrs->fron == 'A tiempo' && $pqrs->estado == 'Finalizado') {
                        $TFA = $pqrs->Cantidad;
                        $TF += $pqrs->Cantidad;

                        $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue+2].$cellValue, $pqrs->Cantidad);
                    }  elseif ($pqrs->estado == 'Finalizado vencido justificado') {

                        $TF += $pqrs->Cantidad ?? 0;
                        $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue+3].$cellValue, $pqrs->Cantidad);
                    }
                    elseif ($pqrs->fron == 'Vencido' && $pqrs->estado == 'Finalizado') {

                        $TF += $pqrs->Cantidad ?? 0;
                        $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue+4].$cellValue, $pqrs->Cantidad);
                    }

                    // Indicador de cumplimiento (TFA / (TV+TF)) * 100 =
                    $indicador = $TFA / ($TV+$TF);
                    $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$xlsxColumnsValue+5].$cellValue, $indicador);

                }

                $cellValue++;
                $spreadsheet->getActiveSheet()->insertNewRowBefore($cellValue);
            }

            //Se calcula la cantidad de dependencias, este es el valor de las cilas  + 3 de las filas de encabezado
            $toalRow = Dependency::get()->count();

            // Combinar celda de Indicador de cumplimiento
            $spreadsheet->getActiveSheet()->mergeCells('c' . $toalRow+9 . ':'. $xlsxColumns[$xlsxColumnsValue + 5] . $toalRow+9);
            $spreadsheet->getActiveSheet()->setCellValue('c' . $toalRow+9, 'TFA = Cantidad de PQRSD resueltos en los términos legales respecto al tiempo (Finalizados A Tiempo)');

            $spreadsheet->getActiveSheet()->mergeCells('c' . $toalRow+10 . ':'. $xlsxColumns[$xlsxColumnsValue + 5] . $toalRow+10);
            $spreadsheet->getActiveSheet()->setCellValue('c' . $toalRow+10, 'TV+TF = Cantidad de PQRSD que cumplieron los términos legales respecto a su tiempo (TOTAL PQRSD VENCIDOS + TOTAL PQRSD FINALIZADOS)');

            // Establece por defecto las columnas en 0 o en -
            for ($i=0; $i < $xlsxColumnsValue; $i++) {
                    $spreadsheet->getActiveSheet()->setCellValue($xlsxColumns[$i].$toalRow+6 , '=SUM('.$xlsxColumns[$i].$cellValueIni.':'.$xlsxColumns[$i].$toalRow+3 .')');
            }

            //Configuraciones de los encabezados del archivo
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Reporte_indicadores_cumplimiento.xlsx"');
            header('Cache-Control: max-age=0');

            // Exportacion del archivo
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $inputFileType);
            $writer->save('php://output');
            exit;

            return $this->sendResponse($writer, trans('msg_success_update'));
        }

    }


    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportRepositoryPqr( Request $request)
    {

        $input = $request->all();

        $userid = Auth::user()->user_joomla_id;


        $likedes1 = '%"id":"'.$userid.'"%';

        $likedes2 = '%"id":'.$userid.',%';

        $likedes3 = '%"id":'.$userid.'}%';

        $table = "";

        $date = date("Y");

        //valida a que tabla realizar la consulta
        if (isset($input['vigencia']) && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
            $table = "pqr_".$input['vigencia'];
        }else{
            $table = "pqr";
        }

        if ((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {

            $querys = DB::connection('joomla')->table($table);

        }else{

            $querys = DB::connection('joomla')->table($table)->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                $query->where('funcionario', $userid)
                    ->orWhere('operador', $userid)
                    ->orWhere('copia', 'LIKE', $userid)
                    ->orWhere('copia', 'LIKE', $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid);
            });

        }

        //Valida si la consulta trae filtros
        if (isset($input['filtros'])) {
            $pqr = $querys->whereRaw($input['filtros'])->orderBy('cf_created', 'DESC')->get()->toArray();
        } else {
            $pqr = $querys->orderBy('cf_created', 'DESC')->get()->toArray();
        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = 'setting.' . $fileType;

        return Excel::download(new RequestExport('pqrs::p_q_r_s.reports.report_excel', JwtController::generateToken($pqr), 's'), $fileName);

    }

    /**
     * Actualiza un registro especifico
     *
     * @author German Gonzalez V. - Abr. 18 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePQRRequest $request
     *
     * @return Response
     */
    public function updateFile($id, UpdatePQRRequest $request) {
        $input = $request->all();

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['adjunto'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pQR = $this->pQRRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\Correspondence\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\Correspondence\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine(). ' Consecutivo: '.($pQR['pqr_id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function destacado($pqr_id, $opcion_seleccionada) {
        // Selecciona los accesos según el ID del PQR y el ID del usuario
        $pqr_leido = PQRLeido::select("id", "accesos")->where("pqr_id", $pqr_id)->where("users_id", Auth::user()->id)->first();
        // Valida si existe el acceso del usuario al PQR según la solicitud
        if($pqr_leido) {
            // Actualiza el campo destacado del usuario al PQR
            $result_pqr_destacado = PQRLeido::where("id", $pqr_leido["id"])->update(["destacado" => $opcion_seleccionada], $pqr_leido["id"]);
        } else {
            $pqr_leido = date("Y-m-d H:i:s");
            // Valida si es el usuario que esta marcando como destacado, tiene el rol de administrador de requerimientos
            if(Auth::user()->hasRole('Administrador de requerimientos')) {
                $rol = "Administrador";
            } else if(Auth::user()->hasRole('Consulta de requerimientos')) { // Valida si es el usuario que esta destacando el PQR, tiene el rol de Consulta de requerimientos
                $rol = "Consultor";
            } else if(Auth::user()->hasRole('Operadores')) { // Valida si es el usuario que esta marcando como destacado, tiene el rol de operadores
                $rol = "Operador";
            } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta marcando como destacado, tiene el rol de ciudadano
                $rol = "Ciudadano";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido-destando el PQR
            $result_pqr_destacado = PQRLeido::create([
                'nombre_usuario' => Auth::user()->name,
                'tipo_usuario' => $rol,
                'destacado' => 1,
                'vigencia' => date("Y"),
                'pqr_id' => $pqr_id,
                'users_id' => Auth::user()->id
            ]);
        }

        return $this->sendResponse($result_pqr_destacado, "PQR destacado con éxito");
    }

    public function leidoPQR($pqr_id) {
        // Selecciona los accesos según el ID del PQR y el ID del usuario
        $pqr_leido = PQRLeido::select("id", "accesos")->where("pqr_id", $pqr_id)->where("users_id", Auth::user()->id)->first();
        if($pqr_leido) {
            // Valida si ya tiene accesos
            if($pqr_leido["accesos"]) {
                $pqr_accesos = $pqr_leido["accesos"]."<br/>".date("Y-m-d H:i:s");
            } else {
                // Si no, es el primer acceso
                $pqr_accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido de PQR
            $result_pqr_leido = PQRLeido::where("id", $pqr_leido["id"])->update(["accesos" => $pqr_accesos], $pqr_leido["id"]);
        } else {
            $pqr_leido = date("Y-m-d H:i:s");
            // Valida si es el usuario que esta leyendo el PQR, tiene el rol de administrador de requerimientos
            if(Auth::user()->hasRole('Administrador de requerimientos')) {
                $rol = "Administrador";
            } else if(Auth::user()->hasRole('Consulta de requerimientos')) { // Valida si es el usuario que esta leyendo el PQR, tiene el rol de Consulta de requerimientos
                $rol = "Consultor";
            } else if(Auth::user()->hasRole('Operadores')) { // Valida si es el usuario que esta leyendo el PQR, tiene el rol de operadores
                $rol = "Operador";
            } else if(Auth::user()->hasRole('Ciudadano')) { // Valida si es el usuario que esta leyendo el PQR, tiene el rol de ciudadano
                $rol = "Ciudadano";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del PQR
            $result_pqr_leido = PQRLeido::create([
                'nombre_usuario' => Auth::user()->name,
                'tipo_usuario' => $rol,
                'accesos' => $pqr_leido,
                'vigencia' => date("Y"),
                'pqr_id' => $pqr_id,
                'users_id' => Auth::user()->id
            ]);
        }

        // Obtener el ID del usuario actual
        $userId = Auth::id();

        // Actualizar los registros directamente en la base de datos
        PQRAnotacion::where('pqr_id', $pqr_id)
            ->where(function ($query) use ($userId) {
                $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                    ->orWhere('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                    ->orWhere('leido_por', 'not like', $userId . ',%'); // También para el caso donde el ID del usuario esté al principio seguido de una coma
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        $pqr = $this->pQRRepository->find($pqr_id);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Internal
        $pqr->anotacionesPendientes;


        return $this->sendResponse($pqr, "PQR leido con éxito");
    }

    public function obtenerTableroConsolidado(Request $request) {
        // Obtiene los parámetros o filtros de consulta
        $parametros = $request->all();
        // Vigencia de consulta por defecto para el tablero
        $vigencia = date("Y");
        // Arreglo para almacenar el consolidado de los estados y lineas de tiempo
        $consolidado = [];
        // Arreglos para formatear los estados originales a estados sin caracteres especiales ni espacios, esto para la asigación de los consolidados en el tablero
        $estados_originales = ["Asignado", "En trámite", "Esperando respuesta del ciudadano", "Devuelto", "Respuesta parcial", "Finalizado"];
        $estados_reemplazar = ["asignado", "en_tramite", "esperando_respuesta_ciudadano", "devuelto", "respuesta_parcial", "finalizado"];
        // Valida si el usuario que esta en sesión es un administrador o consultor de requerimientos y que no este filtrando por sus PQRS propios
        if((Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos')) && $parametros["pqrs_propios"] != "pqrs_personales") {
            // Obtiene la cantidad total de PQRS según la vigencia
            $total = PQR::count();
            // Obtiene la cantidad de todos los PQRS en estado abierto según la vigencia
            $abiertos = PQR::where("estado", "Abierto")->count();
            // Obtiene la cantidad de todos los PQRS en estado abierto, que no sean de canal Web y según la vigencia
            $abiertos_fisicos = PQR::where("estado", "Abierto")->whereNot("canal", "Web")->count();
            // Obtiene la cantidad de los PQRS en estado abierto, de canal Web y según la vigencia
            $abiertos_web = PQR::where("estado", "Abierto")->where("canal", "Web")->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique y según la vigencia
            $linea_tiempo_consolidado = PQR::select(DB::raw("COUNT(id) as cantidad, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'proximo_vencer', IF(estado != 'Abierto' && estado != 'Cancelado', 'a_tiempo', ''))) AS linea_tiempo_consolidado"))->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->groupBy('linea_tiempo_consolidado')->get()->toArray();
            // Obtiene la cantidad de los PQRS en estado finalizado o finalizado vencido justificado y según la vigencia
            $finalizados = PQR::where(function($q) {
                $q->where("estado", "Finalizado");
                $q->orWhere("estado", "Finalizado vencido justificado");
            })->count();
            // Obtiene la cantidad de PQRS en estado finalizado vencido justificado y según la vigencia
            $finalizados_vencidos_justificados = PQR::where("estado", "Finalizado vencido justificado")->count();
            // Obtiene la cantidad de PQRS en estado cancelado y según la vigencia
            $cancelados = PQR::where("estado", "Cancelado")->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique según la dependencia del usuario destinatario y la vigencia, agrupando por la línea de tiempo y el estado
            $linea_tiempo_estados = PQR::select(DB::raw("COUNT(id) AS cantidad, estado, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado', 'proximo_vencer', 'a_tiempo')) AS linea_tiempo_consolidado"))->whereNot("estado", "Abierto")->whereNot("estado", "Cancelado")->groupBy('linea_tiempo_consolidado', 'estado')->get()->toArray();
        } else if(Auth::user()->hasRole('Operadores') && $parametros["pqrs_propios"] != "pqrs_personales") { // Valida si es el usuario que esta en sesión es un operador y que no este filtrando por sus PQRS
            // Obtiene la cantidad total de PQRS según la dependencia del usuario destinatario y la vigencia
            $total = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de todos los PQRS en estado abierto según la dependencia del usuario destinatario y la vigencia
            $abiertos = PQR::where("estado", "Abierto")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de todos los PQRS en estado abierto, que no sean de canal Web, según la dependencia del usuario destinatario y la vigencia
            $abiertos_fisicos = PQR::where("estado", "Abierto")->whereNot("canal", "Web")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de los PQRS en estado abierto, de canal Web, según la dependencia del usuario destinatario y la vigencia
            $abiertos_web = PQR::where("estado", "Abierto")->where("canal", "Web")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique, según la dependencia del usuario destinatario y la vigencia
            $linea_tiempo_consolidado = PQR::select(DB::raw("COUNT(id) as cantidad, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'proximo_vencer', IF(estado != 'Abierto' && estado != 'Cancelado', 'a_tiempo', ''))) AS linea_tiempo_consolidado"))->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->groupBy('linea_tiempo_consolidado')->get()->toArray();
            // Obtiene la cantidad de los PQRS en estado finalizado o finalizado vencido justificado, según la dependencia del usuario destinatario y la vigencia
            $finalizados = PQR::whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->where(function($q) {
                $q->where("estado", "Finalizado");
                $q->orWhere("estado", "Finalizado vencido justificado");
            })->count();
            // Obtiene la cantidad de PQRS en estado finalizado vencido justificado, según la dependencia del usuario destinatario y la vigencia
            $finalizados_vencidos_justificados = PQR::where("estado", "Finalizado vencido justificado")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de PQRS en estado cancelado, según la dependencia del usuario destinatario y la vigencia
            $cancelados = PQR::where("estado", "Cancelado")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique según la dependencia del usuario destinatario y la vigencia, agrupando por la línea de tiempo y el estado
            $linea_tiempo_estados = PQR::select(DB::raw("COUNT(id) AS cantidad, estado, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado', 'proximo_vencer', 'a_tiempo')) AS linea_tiempo_consolidado"))->whereNot("estado", "Abierto")->whereNot("estado", "Cancelado")->whereRelation("funcionarioUsers", "id_dependencia", Auth::user()->id_dependencia)->groupBy('linea_tiempo_consolidado', 'estado')->get()->toArray();
        } else { // Si no es ninguno de los anteriores, es un funcionario corriente
            // Obtiene la cantidad total de PQRS validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $total = PQR::where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de todos los PQRS en estado abierto validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $abiertos = PQR::where("estado", "Abierto")->where(function($q){
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de todos los PQRS en estado abierto, que no sean de canal Web, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $abiertos_fisicos = PQR::where("estado", "Abierto")->whereNot("canal", "Web")->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de los PQRS en estado abierto, de canal Web, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $abiertos_web = PQR::where("estado", "Abierto")->where("canal", "Web")->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $linea_tiempo_consolidado = PQR::select(DB::raw("COUNT(id) as cantidad, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'proximo_vencer', IF(estado != 'Abierto' && estado != 'Cancelado', 'a_tiempo', ''))) AS linea_tiempo_consolidado"))->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->groupBy('linea_tiempo_consolidado')->get()->toArray();

            // Obtiene la cantidad de los PQRS en estado finalizado o finalizado vencido justificado, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $finalizados = PQR::where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->where(function($q) {
                $q->where("estado", "Finalizado");
                $q->orWhere("estado", "Finalizado vencido justificado");
            })->count();
            // Obtiene la cantidad de PQRS en estado finalizado vencido justificado, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $finalizados_vencidos_justificados = PQR::where("estado", "Finalizado vencido justificado")->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de PQRS en estado cancelado, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
            $cancelados = PQR::where("estado", "Cancelado")->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->count();
            // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique validando si es un usuario destinatario o es un usuario con copia y según vigencia, agrupando por la línea de tiempo y el estado
            $linea_tiempo_estados = PQR::select(DB::raw("COUNT(id) AS cantidad, estado, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado', 'proximo_vencer', 'a_tiempo')) AS linea_tiempo_consolidado"))->whereNot("estado", "Abierto")->whereNot("estado", "Cancelado")->where(function($q) {
                $q->where("funcionario_users_id", Auth::user()->id);
                // $q->orWhereRelation('pqrCopiaCopmpartida', 'users_id', Auth::user()->id);
            })->groupBy('linea_tiempo_consolidado', 'estado')->get()->toArray();
        }
        // Arreglo para almacenar la cantidad de PQRS según la linea de tiempo (A tiempo, Próximos a vencerse, Vencidos)
        $linea_tiempo_totales = [];
        // Recorre el resultado de los PQRS según la línea de tiempo, organizándolos por su línea de tiempo como llave y la cantidad como el valor
        foreach($linea_tiempo_consolidado as $v) {
            $linea_tiempo_totales[$v["linea_tiempo_consolidado"]] = $v["cantidad"];
        }
        // Arreglo para almacenar la cantidad de PQRS según la linea de tiempo (A tiempo, Próximos a vencerse, Vencidos) y estado
        $linea_tiempo_totales_estados = [];
        // Recorre el resultado de los PQRS según la línea de tiempo, organizándolos por su línea de tiempo + estado como llave y la cantidad como el valor
        foreach($linea_tiempo_estados as $v) {
            $linea_tiempo_totales_estados[$v["linea_tiempo_consolidado"].'_'.str_replace($estados_originales, $estados_reemplazar, $v["estado"])] = $v["cantidad"];
        }
        // Organiza los diferentes consolidados en un arreglo
        $consolidado = ["total" => $total, "abiertos" => $abiertos, "abiertos_fisicos" => $abiertos_fisicos, "abiertos_web" => $abiertos_web, "finalizados" => $finalizados, "finalizados_vencidos_justificados" => $finalizados_vencidos_justificados, "cancelados" => $cancelados];
        // Une todos los arreglos creados anteriormente
        $consolidado_total = array_merge($consolidado, $linea_tiempo_totales, $linea_tiempo_totales_estados);
        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de PQRS
        return $this->sendResponse($consolidado_total, "Consolidado PQR obtenido con éxito");
    }

    /**
     * Modifica la fecha de vencimiento de los PQRS a los que aplique a partir de la fecha, según los días no hábiles
     *
     * @return void
     */
    public function modificarFechaVencimiento() {
        // Consulta todos los PQRS que tengan fecha de vencimiento y sea mayor a la fecha actual
        $pqrs = PQR::where("fecha_vence", ">=", date("Y-m-d H:i:s"))->whereNotNull("fecha_vence")->get()->toArray();
        // Obtiene todos los dias no laborales disponibles
        $holidayCalendars = HolidayCalendar::get()->toArray();
        // Obtiene el horario laboral
        $workingHours = WorkingHours::latest()->first();
        // Matríz para guardar los PQRS a los que se le actualizó la fecha de vencimiento
        $pqr_fecha_vence_actualizada = [];
        foreach($pqrs as $pqr) {
            //Obiene la fecha de creación del PQR
            $currentDate = $pqr["created_at"];
            // Transforma la fecha actual en un string
            $dateObtained = strval($currentDate);
            // Divide la fecha actual en fecha y hora
            $arrayDate = explode(" ", $dateObtained);
            // Separa la hora en horas,minutos y segundos
            $hour = explode(":", $arrayDate[1]);
            // Se valida que los minutos de la hora de la fecha actual esten en cero
            if($hour[1]=="00"){
                // Se le resta un minuto
                $currentDate = strtotime ( '-1 minute' , strtotime($currentDate) ) ;
                // Se asigna el nuevo valor de la hora a la fecha actual
                $currentDate = date ("Y-m-d H:i:s",  $currentDate);
            }

            $eje_tematico = PQREjeTematico::where("id", $pqr["pqr_eje_tematico_id"])->first();
            // Calcula la fecha de vencimiento de la solicitud
            $expiration_date = $this->calculateFutureDate(
                Arr::pluck($holidayCalendars, 'date'),
                $currentDate,
                $pqr["pqr_eje_tematico_id"] ? $eje_tematico->plazo_unidad : "Días",
                $pqr["tipo_plazo"],
                ($pqr["estado"] == "Respuesta parcial" || !empty($pqr["fecha_fin_parcial"])) ? $pqr["plazo"]*2 : $pqr["plazo"],
                $workingHours
            );
            // Si la fecha de vencimiento actual del PQR es diferente a la calculada, se cumple la condición
            if ($pqr["fecha_vence"] != $expiration_date[0]) {
                // Calcula la fecha temprana de la solicitud
                $expiration_date_temprana = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    $currentDate,
                    $pqr["pqr_eje_tematico_id"] ? $eje_tematico->plazo_unidad : "Días",
                    $pqr["tipo_plazo"],
                    ($pqr["estado"] == "Respuesta parcial" || !empty($pqr["fecha_fin_parcial"])) ? $pqr["temprana"]*2 : $pqr["temprana"],
                    $workingHours
                );
                // Guarda la información del PQR con la fecha de vencimiento anterior y la actualizada
                $pqr_fecha_vence_actualizada[] = ["pqr_id" => $pqr["pqr_id"], "contenido" => $pqr["contenido"], "fecha_vence_anterior" => $pqr["fecha_vence"], "fecha_vence_actualizada" => $expiration_date[0], "fecha_temprana_anterior" => $pqr["fecha_temprana"], "fecha_temprana_actualizada" => $expiration_date_temprana[0]];
                // Actualiza la fecha de vencimiento según el calendario de días no hábiles
                $pQR = $this->pQRRepository->update(["fecha_vence" => $expiration_date[0], "fecha_temprana" => $expiration_date_temprana[0]], $pqr["id"]);
            }
        }
        // Descarga el archivo generado en formato pdf
        return Excel::download(new RequestExportPQRS('pqrs::p_q_r_s.report_cambio_fecha_vence', JwtController::generateToken($pqr_fecha_vence_actualizada), 'c'), "actualizacion_fecha_vence_PQRS.pdf", \Maatwebsite\Excel\Excel::DOMPDF);
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ene. 17 - 2024
     * @version 1.0.0
     *
     * @param int $id del registro procediente de las entradas recientes del dashboard
     */
    public function showFromDashboard($id)
    {
        $pqr = $this->pQRRepository->find($id);
        if (empty($pqr)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Relaciones
        $pqr->pqrCopia;
        $pqr->pqrCompartida;
        $pqr->pqrCopiaCopmpartida;
        $pqr->pqrLeidos;
        $pqr->pqrEjeTematico;
        $pqr->pqrTipoSolicitud;
        $pqr->funcionarioUsers;
        $pqr->ciudadanoUsers;
        $pqr->pqrAnotacions;
        $pqr->pqrHistorial;
        $pqr->pqrCorrespondence;
        $pqr->serieClasificacionDocumental;
        $pqr->subserieClasificacionDocumental;
        $pqr->oficinaProductoraClasificacionDocumental;

        return $this->sendResponse($pqr->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Recibe la respuesta del ciudadano
     *
     * @author Manuel Marin. - Feb. 23 - 2024
     * @version 1.0.0
     *
     */
    public function answerPqrs(Request $request)
    {
        try{

            $input = $request->all();

            // Busca el pqr con el id que llega en el request
            $pqrs = PQR::find($input["id"]);

            // Instancia los datos a guardar
            $pqrs->respuesta_ciudadano = $input["respuesta_ciudadano"];
            // Valida si seleccionó o no el documento de la respuesta del ciudadano
            if (isset($input["adjunto_r_ciudadano"])) {
                $pqrs->adjunto_r_ciudadano = implode(",", (array) $input["adjunto_r_ciudadano"]);
            }
            // Actualiza el pqr
            $pqrs->save();
            $notificacion = $pqrs->toArray();
            // Guarda el historial
            $input["respuesta_ciudadano"] = $pqrs->respuesta_ciudadano;
            $input["adjunto_r_ciudadano"] = $pqrs->adjunto_r_ciudadano;
            $input["estado"] = $pqrs->estado;
            $input["canal"] = $pqrs->canal;
            $input["pqr_pqr_id"] = $pqrs->id;
            if($input["document_pdf"] ?? false) {
                $input['document_pdf'] = implode(",", $input["document_pdf"]);
            }
            unset($input["pqr_leidos"]);
            unset($input["pqr_historial"]);
            $historial = $input;

            $historial['action'] = "Respuesta del ciudadano";
            $historial['linea_tiempo'] = "5";

            //Crea el historial
            PQRHistorial::create($historial);
            // Envia la relacion con el historial
            $pqrs->pqrHistorial;

            $funcionario_destinatario = User::find($input["funcionario_users_id"]);

            $asunto = json_decode('{"subject": "Notificación del PQR '.$input["pqr_id"].'"}');

            $notificacion["mensaje"] = "Se registro una respuesta por parte del ciudadano en el PQRS ".$input["pqr_id"]." y se encuentra en estado ".$input["estado"].",";
            $notificacion['link'] = '/pqrs/p-q-r-s?qder=' . base64_encode($pqrs->id);

            $notificacion["consecutive"] = $pqrs->pqr_id ?? '';

            SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_funcionario',$asunto,$notificacion,$funcionario_destinatario["email"],'PQRSD');

            return $this->sendResponse($pqrs, trans('msg_success_update'));


        } catch (\Illuminate\Database\QueryException $error) {
            // Revertir la transacción en caso de error de la base de datos
            DB::rollback();

            // Generar un registro de error en el sistema de logs
            $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());

            // Enviar una respuesta informativa con un mensaje de soporte genérico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error general
            DB::rollback();

            // Generar un registro de error en el sistema de logs
            $this->generateSevenLog('PQRS', $e->getFile() . ' -  -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '.$pqrs->pqr_id);

            // Enviar una respuesta informativa con un mensaje de soporte genérico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * guarda las compartidas del pqrs
     *
     * @author Manuel Marin. - Marzo. 13 - 2024
     * @version 1.0.0
     *
     */
    public function pqrShare(Request $request)
    {

        $input = $request->all();
        $id = $input["id"];

        $pQR = $this->pQRRepository->find($id);

        $notificacion_funcionario = $pQR->toArray();

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if (!empty($input['pqr_compartida'])) {
            // Eliminar los registros de las copias según el id del registro principal del PQR
            PQRCopia::where('pqr_id', $pQR->id)->where("tipo","=","Compartida")->delete();

            //texto para almacenar en la tabla interna
            $textRecipients = array();
            //recorre los destinatarios
            foreach($input['pqr_compartida'] as $recipent){
                //array de destinatarios
                $recipentArray = json_decode($recipent, true);
                $recipentArray["vigencia"] = date("Y");
                $recipentArray["pqr_id"] = $id;
                $recipentArray["tipo"] = "Compartida";
                $recipentArray["name"] = $recipentArray["fullname"];
                $textRecipients[] = $recipentArray["name"];

                PQRCopia::create($recipentArray);

                $email = User :: where('id', $recipentArray['users_id'])->first()->email;
                // Definir mensaje por defecto (para casos no especificados)

                $email = User :: where('id', $recipentArray['users_id'])->first()->email;
                // Asunto del email
                $asunto = json_decode('{"subject": "Notificación de PQR ' . $pQR->pqr_id . ' compartida"}');
                // Se agrega este link en la notificaciones de pqr, para que el boton de la notificación redireccione directamente a PQR
                $notificacion_funcionario['link'] = '/pqrs/p-q-r-s?qder=' . base64_encode($pQR->id);
                // Nombre del funcionario asignado al PQR
                $notificacion_funcionario["name"] = $recipentArray["name"];
                // Mensaje para el correo dirigido al funcionario destinatario
                $notificacion_funcionario["mensaje"] = "Le informamos que se le ha compartido la PQR con radicado <strong>{$pQR->pqr_id}</strong>";
                $notificacion_funcionario["consecutive"] = $pQR->pqr_id ?? '';

                // Enviar notificación por correo al funcionario asignado
                SendNotificationController::SendNotification('pqrs::p_q_r_s.email.notificacion_funcionario',$asunto,$notificacion_funcionario,$email,'PQRSD');
            }
        }

        if (!empty($input['annotation'])) {
            PQRAnotacion :: create([
                'pqr_id' => $id,
                'users_id'  => Auth::user()->id,
                'nombre_usuario' => Auth::user()->fullname,
                'anotacion' => $input['annotation']
            ]);
        }
        $pQR->pqrCompartida;
        $pQR->pqrAnotacions;

        return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
    }

    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Manuel Marin. - Abril. 01. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getOnlyPqrs(Request $request)
    {
        $query =$request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where(function($q) {
            $q->where('id_dependencia', '=', Auth::user()->id_dependencia);
            $q->orWhereHas('roles', function ($query) {
                    $query->where('name', 'Operadores'); // Filtra por rol 'Operadores'
                });
        })
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los administradores de PQRS para hacer devolucion
     *
     * @author Manuel Marin. - Abril. 01. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUserAdminPqrs(Request $request)
    {
        $query =$request->input('query');
        $users = User::role('Administrador de requerimientos')->get();
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Exporta el historial del PQRS
     *
     * @author Manuel Marin. - Abril. 09. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportHistorial($id)
    {
        $historial = PQRHistorial::where('pqr_pqr_id', $id)->get();

        return Excel::download(new RequestExport('pqrs::p_q_r_s.reports.export_historial', JwtController::generateToken($historial->toArray()), 'J'), 'Prueba.xlsx');
    }


    /**
     * Migracion de las muestras de la toma
     *
     * @author Leonardo F.H 11 de junio 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function MigratePqr(Request $request)
    {

        try {
            $user = Auth::user();
            $input = $request->toArray();
            $successfulRegistrations = 0;
            $failedRegistrations = 0;
            $storedRecords = [];
            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            if ($request->hasFile('file_import')) {
                // Obtener el archivo del request
                $file = $request->file('file_import');
                // Obtener la extensión original del archivo
                $extension = $file->getClientOriginalExtension();
                // Extensiones permitidas
                $allowedExtensions = ['xls', 'xlsx'];
                // Validar la extensión
                if (!in_array($extension, $allowedExtensions)) {
                    return $this->sendError('El archivo debe ser un documento Excel con extensión .xls o .xlsx.', []);

                }
                $data = Excel::toArray(new PQR, $input["file_import"]);
                $contArray = count($data[0][0]);
                unset($data[0][0]);

                if ($contArray == 5) {
                    foreach ($data[0] as $row) {
                        try {


                            if ($row[0] != null || $row[1] != null || $row[2] != null || $row[3]) {

                                // Consulta el máximo consecutivo de los PQRS
                                $pqr_id = PQR::select(DB::raw("MAX(CAST(SUBSTR(pqr_id, 8) AS SIGNED)) AS consecutivo"))->where("vigencia", date("Y"))->pluck("consecutivo")->first();
                                // Si ya existe un registro de PQRS, al consecutivo se incrementa en 1
                                if($pqr_id){
                                    $consecutivo = date("Y")."PQR".($pqr_id+1);
                                } else { // De lo contrario es el primer PQR
                                    $consecutivo = date("Y")."PQR1";
                                }

                                $register = PQR::create([
                                    'pqr_id'=> $consecutivo,
                                    'nombre_ciudadano' => $row[0],
                                    'documento_ciudadano' => $row[1],
                                    'email_ciudadano' => $row[2],
                                    'contenido' => $row[3],
                                    'canal'=> $row[4],
                                    'estado' => 'Abierto',
                                    'vigencia' => date("Y"),
                                    'users_id'=> $user->id,
                                    'users_name'=> $user->name,
                                    'validation_code' => substr(str_shuffle($caracteres_permitidos), 0, 8),

                                ]);

                                $successfulRegistrations++;
                            }
                        } catch (\Illuminate\Database\QueryException $error) {
                            $failedRegistrations++;
                        }
                    }
                } else {
                    return $this->sendError('Error,por favor verifique que el número de columnas con datos en el excel coincida con el número de columnas del formulario de importación de actividades', []);
                }
            }
            return $this->sendResponse($register, trans('msg_success_save') . "<br /><br />Registros exitosos: $successfulRegistrations<br />Registros fallidos: $failedRegistrations");
        } catch (\Exception $e) {
               // Inserta el error en el registro de log
               $this->generateSevenLog('PQRS', 'Modules\PQRS\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
        }
    }

    /**
     * Organiza la exportacion de datos avanzados
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportReporteTipoAdjunto(Request $request)
    {


        if (strpos($request["filtros"], 'created_at') !== false ||
        strpos($request["filtros"], 'fecha_fin') !== false ||
        strpos($request["filtros"], 'fecha_vence') !== false) {

        $dateFields = ['created_at', 'fecha_fin', 'fecha_vence'];
        $conditions = [];
        $cadenaFechas = '';

        foreach ($dateFields as $field) {
            if (strpos($request["filtros"], $field) !== false) {
                // Expresión regular para fecha de inicio
                preg_match('/' . $field . '\) >= (.{12})/', $request["filtros"], $rangeStart);
                // Expresión regular para fecha final
                preg_match('/' . $field . '\) <= (.{12})/', $request["filtros"], $rangeEnd);

                if (!empty($rangeStart) && !empty($rangeEnd)) {
                    // Extrae la fecha de inicio
                    preg_match('/\d{4}-\d{2}-\d{2}/', $rangeStart[0], $matches);
                    $initDate = $matches[0];

                    // Extrae la fecha final
                    preg_match('/\d{4}-\d{2}-\d{2}/', $rangeEnd[0], $matches);
                    $endDate = $matches[0];

                    // Construye la condición para este campo
                    $conditions[] = 'DATE(pqr.' . $rangeStart[0] . ' AND DATE(pqr.' . $rangeEnd[0];

                    // Agrega las fechas a la cadena descriptiva
                    $cadenaFechas .= ($cadenaFechas ? ' Y ' : '') .
                                    "PARA $field: $initDate A LA FECHA DE CORTE $endDate";
                }
            }
        }

        // Si se encontraron condiciones, las une con AND
        $query = !empty($conditions) ? implode(' AND ', $conditions) : 'TRUE';

        } else {
            // Construir condición por defecto para todos los campos
            $defaultConditions = [
                'pqr.created_at is not NULL',
                'pqr.fecha_fin is not NULL',
                'pqr.fecha_vence is not NULL'
            ];
            $query = implode(' AND ', $defaultConditions);
        }


        $dependencias_tipos_respuestas = PQR::select(
                    'd.nombre AS dependencia',
                    DB::raw('SUM(IF(tipo_adjunto = "Respuesta con adjunto" OR tipo_adjunto = "" OR tipo_adjunto IS NULL, 1, 0)) AS respuesta_con_adjunto'),
                    DB::raw('SUM(IF(tipo_adjunto = "Respuesta sin adjunto", 1, 0)) AS respuesta_sin_adjunto'),
                    DB::raw('SUM(IF(tipo_adjunto = "Adjunto pendiente", 1, 0)) AS sin_adjunto')
                )
                ->join('dependencias AS d', 'pqr.dependency_id', '=', 'd.id')
                ->whereRaw($query)
                ->whereIn('estado', ['Finalizado', 'Finalizado vencido justificado'])
                ->groupBy('d.nombre')
                ->orderBy('d.nombre', 'ASC')
                ->get();

        return Excel::download(new RequestExport('pqrs::p_q_r_s.reports.report_excel_tipos_adjuntos', JwtController::generateToken($dependencias_tipos_respuestas->toArray()), 'E'), 'dependencias_tipos_respuestas.xlsx');
    }

    /**
     * Relaciona el adjunto de respuesta a un PQRSD finalizado
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function relacionarAdjuntoRespuesta(Request $request)
    {
        $pqr_info = $request->all();
        $id = $pqr_info["id"];

        /** @var PQR $pQR */
        $pQR = $this->pQRRepository->find($id);

        if (empty($pQR)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Arreglo para actualizar el adjunto de respuesta del PQR
            $input = [];
            // Valida si seleccionó o no un adjunto
            if($pqr_info["adjunto"] ?? false) {
                // Concatena los adjuntos de respuesta, separados por (,)
                $input['adjunto'] = implode(",", $pqr_info["adjunto"]);
                // Actualiza el valor del tipo de adjunto
                $input['tipo_adjunto'] = "Respuesta con adjunto";
                // Actualiza la fecha de actualización del PQRSD
                $input['updated_at'] = date("Y-m-d H:i:s");
                // Actualiza el registro
                $pQR = $this->pQRRepository->update($input, $id);
                $historial = $pQR->toArray();
                // Asigna a la foranea en el historial de pqr, el id del registro principal de PQR
                $historial["pqr_pqr_id"] = $pQR->id;
                $historial['action'] = "Actualización del registro con la respuesta adjunta";
                // Guarda en la tabla historial de PQR
                PQRHistorial::create($historial);
                // Sincroniza las relaciones del registro
                $pQR->pqrCopia;
                $pQR->pqrCompartida;
                $pQR->pqrCopiaCopmpartida;
                $pQR->pqrLeidos;
                $pQR->pqrEjeTematico;
                $pQR->pqrTipoSolicitud;
                $pQR->funcionarioUsers;
                $pQR->ciudadanoUsers;
                $pQR->pqrAnotacions;
                $pQR->pqrHistorial;
                $pQR->serieClasificacionDocumental;
                $pQR->subserieClasificacionDocumental;
                $pQR->oficinaProductoraClasificacionDocumental;
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQR->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\Correspondence\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\Correspondence\Http\Controllers\PQRController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine().' Consecutivo: '.($pQR['pqr_id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Obtiene todos los elementos existentes del repositorio de un ciudadano
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRepositoryPQRCiudadano(Request $request)
    {
        try {
            $username = Auth::user()->username;

            $table = "";
            $date = date("Y");
            $vigencia = "";
            $vigencyPQRCount = 0;
            // Reemplaza los espacios en blanco por + en la cadena de filtros codificada
            $request["f"] = str_replace(" ", "+", $request["f"]);
            // Decodifica los campos filtrados
            $filtros = base64_decode($request["f"]);
            // Valida si en los filtros realizados viene el filtro de vigencia
            if(stripos($filtros, "vigencia") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de vigencia en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return stripos($value, 'vigencia') !== false;
                }))[0];
                // Se extrae el valor del filtro vigencia
                $vigencia = strtolower(explode("%", $filtro[$posicion])[1]);
                // Se elimina el filtro de vigencia del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = $filtro ? implode(" AND ", $filtro) : $filtro;
            }
            //valida a que tabla realizar la consulta
            if ($vigencia != '' && $vigencia != $date && $vigencia != '2024') {
                $table = "pqr_".$vigencia;
            }else{
                $table = "pqr";
            }
            // Valida si existen las variables del paginado y si esta filtrando
            if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                $querys = DB::connection('joomla')->table($table)
                    ->where('documento_ciudadano', $username)
                    ->when($filtros, function($q) use($filtros) {
                        $q->whereRaw($filtros);
                    })
                    ->paginate(base64_decode($request["pi"]));

                $vigencyPQRCount = $querys->total();
                $vigencyPQR = $querys->toArray()["data"];
            } else if(isset($request["cp"]) && isset($request["pi"])) {
                $querys = DB::connection('joomla')->table($table)
                    ->where('documento_ciudadano', $username)
                    ->paginate(base64_decode($request["pi"]));

                $vigencyPQRCount = $querys->total();
                $vigencyPQR = $querys->toArray()["data"];
            }

            return $this->sendResponseAvanzado($vigencyPQR, trans('data_obtained_successfully'), null, ["total_registros" => $vigencyPQRCount]);


        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRSD\Http\Controllers\PQRController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('PQRS', 'Modules\PQRSD\Http\Controllers\PQRController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage(). '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        }
    }
    /**
     * Generar una URL para visualizar un documento en una nueva pestaña.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function watchDocument(Request $request)
     {
         try {
             // Obtener parámetros de la solicitud
             $documentoEncriptado = $request->query('document');
             $idCorrespondence = $request->query('id');
             $idMail = $request->query('id_mail');

             // Valida si llega un documento
             if (!empty($documentoEncriptado)) {
                 $documento = Crypt::decryptString($documentoEncriptado);
                 $this->readDocument($idCorrespondence,$idMail);
                 return view('correspondence::view_document_public.index', compact(['documento']));
             }
             $read = $this->readDocument($idCorrespondence,$idMail);

             $notificacion = NotificacionesMailIntraweb::where('id_mail', $idMail)->get()->first()->toArray();

              // Retonara la vista publica, para informar de que se leyó exitosamente el correo
             return view('correspondence::view_document_public.correo_leido',compact(['read','notificacion']));

             // dd($documentoEncriptado,$idCorrespondence,$idMail);
         } catch (\Exception $e) {
             $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

         }
     }

    private function readDocument($id, $idMail = null)
    {
        // Obtenemos la dirección IP pública del usuario
        $publicIp = $this->detectIP();
        // $codigoDecode = JwtController::decodeToken($input['codigoAccesoDocumento']);

        // Buscamos la correspondencia externa asociada al código de validación
        $pqr = PQR::where('id', $id)->get()->first();

        // Verificamos si se encontró la correspondencia externa
        if ($pqr) {
            // Registramos la apertura de la correspondencia externa desde el correo electrónic

            PQRLeido::create([
                'nombre_usuario' => "Apertura desde el correo electrónico",
                'tipo_usuario' => 'Ciudadano',
                'accesos' => date("Y-m-d H:i:s"),
                'vigencia' => date("Y"),
                'pqr_id' => $id,
            ]);

            if ($idMail != null) {
                // Una vez se envía se actualiza el campo nuevamente
                $notificacion = NotificacionesMailIntraweb::where('id_mail', $idMail)
                ->update([
                    'leido' => 'Si',
                    'fecha_hora_leido' => date('Y-m-d H:i:s'),
                    'ip_leido' => $publicIp,
                    'agente_navegador' => 'Google'
                ]);
            }

            return $this->sendResponse([
                'success' => true,
            ], trans('msg_success_save'));
        } else {
            // Si no se encontró la correspondencia externa, retornamos un mensaje de error
            return $this->sendResponse([
                'success' => false,
            ], 'Documento no encontrado');
        }
    }
}
