<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Nwidart\Modules\Facades\Module;
use Auth;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\Correspondence\Models\External;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\Internal;
use Modules\DocumentosElectronicos\Models\Documento;
use Modules\Intranet\Models\User;
use Modules\Intranet\Models\UserHistory;
use Modules\PQRS\Models\PQR;
use Mpdf\Mpdf;
use setasign\Fpdi\Tcpdf\Fpdi;
use TCPDF;

class NavigationController extends AppBaseController {

    /**
     * Constructor de la clase
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ago. 03 - 2020
     * @version 1.0.0
     */
    public function __construct() {

    }

    /**
     * Obtiene la informacion de los componentes del modulo
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 30 - 2020
     * @version 1.0.0
     */
    public function components(Request $request) {
        // Inicializa lista de componentes
        $dataModule = [];

        // Valida si se selecciona un modulo
        if ($request->has('module')) {
            // Obtiene el parametro del tipo de modulo
            $moduleSelected = $request->query('module');
            // Obtiene la lista de componentes del modulo seleccionado
            $dataModule = config('constants.modules.'.$moduleSelected);
            // Recorre los componentes que tiene el modulo
            foreach($dataModule->components as $key => $component) {
                /**
                 * Valida el rol del usuario con los roles permitidos en el componentes
                 * Si el rol del usuario no existe dentro del los componentes entra en la condicion
                 */
                if (!array_intersect(Auth::user()->getRoleNames()->toArray(), $component->roles)) {
                    // Elimina el componente del arreglo
                    unset($dataModule->components[$key]);
                }
            }
        }
        return view('layouts.components')
            ->with('dataModule', $dataModule)
            ->with('sidebarHide', true);
    }

    /**
     * Obtiene la información de los módulos activos
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ago. 03 - 2020
     * @version 1.0.0
     */
    public function modules() {
        // Lista de modulos habilitados
        $dataModule= collect(Module::getByStatus(1))->map(function ($module) {
            $dataModuleComponents = config('constants.modules.'.$module->getName());
            // Valida que el rol del usuario exista dentro del modulo
            if (isset($dataModuleComponents->components[0])) {
                if (array_intersect(Auth::user()->getRoleNames()->toArray(), $dataModuleComponents->components[0]->roles)) {

                    $arrayModule = array();

                    $arrayModule['slug'] = $module->getName();
                    $arrayModule['name'] = $module->getName();
                    $arrayModule['description'] = $dataModuleComponents->description;
                    $arrayModule['img'] = $dataModuleComponents->img;
                    $arrayModule['title'] = $dataModuleComponents->title;

                    return $arrayModule;
                }
            }


        });

        if(!Auth::user()->hasRole('Ciudadano')){

            // Arreglo para almacenar de manera aparente los datos del módulo Herramientas Colaborativas
            $arrayModuleHC = [];
            $arrayModuleHC['slug'] = "HerramientasColaborativas";
            $arrayModuleHC['name'] = "HerramientasColaborativas";
            $arrayModuleHC['description'] = "Utilice herramientas colaborativas para mantenerse informado sobre encuestas, noticias, documentos y eventos en su entidad.";
            $arrayModuleHC['img'] = "assets/img/modules/Intranet.png";
            $arrayModuleHC['title'] = "Herramientas colaborativas";
            $dataModule["HerramientasColaborativas"] = $arrayModuleHC;
        }
        return view('layouts.modules')
        ->with('dataModule', $dataModule)
        ->with('sidebarHide', true);
    }

    /**
     * Obtiene la información del usuario logueado para mostrar en el tablero de control
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.0.0
     */
    public function dashboard() {

        $mostrarPopup = false;

        $ultima_version = DB::table('intraweb_version_update')->select('numero_version')->latest()->first();

        if (Auth::check()) {
            $user = Auth::user();
            if ($ultima_version && ($user->version_actual === null || $ultima_version->numero_version != $user->version_actual)) {
                $mostrarPopup = true;
                $user->version_actual = $ultima_version->numero_version;
                $user->save();
            }
        }

        if(Auth::user()->hasRole("Proveedor TIC")){
           return redirect("/help-table/tic-requests");
        }
        return view('layouts.dashboard', compact('mostrarPopup')) ;
    }

    /**
     * Obtiene las cantidades totales de los módulos de corresponndencia interna, enviada y recibida, además de PQR
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.1.0
     */
    public function obtenerTotalesDashboard(Request $request) {
        $totalesCorrInternas = $this->obtenerTotalesCorrInterna($request);
        $totalesCorrEnviadas = $this->obtenerTotalesCorrEnviada($request);
        $totalesCorrRecibidas = $this->obtenerTotalesCorrRecibida($request);
        $totalesPqrs = $this->obtenerTotalesPQRS($request);
        $totalesDocumentosElectronicos = $this->obtenerTotalesDocumentosElectronicos($request);
        // Organiza los diferentes consolidados en un arreglo
        $consolidado = ["PQRS" => $totalesPqrs, "CorrRecibida" => $totalesCorrRecibidas, "CorrEnviada" => $totalesCorrEnviadas, "CorrInterna" => $totalesCorrInternas, "DocumentosElectronicos" => $totalesDocumentosElectronicos];
        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades totales
        return $this->sendResponse($consolidado, "Consolidado dashboard");
    }

    /**
     * Obtiene las cantidades totales del módulo de PQR
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.0.0
     */
    public function obtenerTotalesPQRS(Request $request) {
        // Obtiene los parámetros o filtros de consulta
        $parametros = $request->all();
        // Vigencia de consulta por defecto para el tablero
        $vigencia = date("Y");
        // Arreglo para almacenar el consolidado de los estados y lineas de tiempo
        $consolidado = [];
        // Obtiene la cantidad total de PQRS validando si es un usuario destinatario o es un usuario con copia y según la vigencia
        $totalPQRS = PQR::where(function($q) {
            // Valida si el usuario es un administrador
            if(!(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {
                $q->where("funcionario_users_id", Auth::user()->id);
            }
        })
        ->where(function($q) {
            // Agrega la condición para que el estado sea diferente de 'finalizado' y 'cancelado'
            $q->where('estado', '!=', 'Finalizado')
              ->where('estado', '!=', 'Cancelado');
        })
        ->count();

        // dd($totalPQRS->toSql(), $totalPQRS->getBindings());


        // Obtiene la cantidad de los PQRS, calculando la línea de tiempo (A tiempo, Próximo a vencer, Vencido) de los PQRS a los que aplique, validando si es un usuario destinatario o es un usuario con copia y según la vigencia
        $linea_tiempo_consolidado = PQR::select(DB::raw("COUNT(id) as cantidad, IF(fecha_fin > fecha_vence || (CURRENT_TIMESTAMP() > fecha_vence && fecha_fin IS NULL), 'vencidos', IF(CURRENT_TIMESTAMP() >= fecha_temprana && estado != 'Finalizado' && estado != 'Finalizado vencido justificado' && estado != 'Abierto' && estado != 'Cancelado', 'proximo_vencer', IF(estado != 'Abierto' && estado != 'Cancelado', 'a_tiempo', ''))) AS linea_tiempo_consolidado"))
        ->whereNotIn('estado', ['Abierto', 'Finalizado', 'Finalizado vencido justificado', 'Cancelado'])
        ->where(function($q) {
            // Valida si el usuario es un administrador
            if(!(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos'))) {
                $q->where("funcionario_users_id", Auth::user()->id);
                $q->orWhereRelation('pqrCopia', 'users_id', Auth::user()->id);
            }
        })
        ->where(function($q) {
            // Agrega la condición para que el estado sea diferente de 'finalizado' y 'cancelado'
            $q->where('estado', '!=', 'finalizado')
              ->where('estado', '!=', 'cancelado');
        })
        ->groupBy('linea_tiempo_consolidado')->get()->toArray();
        // Arreglo para almacenar la cantidad de PQRS según la linea de tiempo (A tiempo, Próximos a vencerse, Vencidos)
        $linea_tiempo_totales = [];
        // Recorre el resultado de los PQRS según la línea de tiempo, organizándolos por su línea de tiempo como llave y la cantidad como el valor
        foreach($linea_tiempo_consolidado as $v) {
            $linea_tiempo_totales[$v["linea_tiempo_consolidado"]] = $v["cantidad"] ? $v["cantidad"] : 0;
        }
        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de PQRS
        return ["totalPQRS" => $totalPQRS, "linea_tiempo" => $linea_tiempo_totales];
    }

    /**
     * Obtiene las cantidades totales del módulo de Correspondencia recibida
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.0.0
     */
    public function obtenerTotalesCorrRecibida(Request $request) {
        // Contar el número total de registros de la consulta realizada sin los filtros
        $count_externals = ExternalReceived::select('external_received.id')
        ->leftJoin('external_received_copy_share', function($join) {
            $join->on('external_received.id', '=', 'external_received_copy_share.external_received_id')
            ->whereNull('external_received_copy_share.deleted_at');
        })
        ->where(function($q) {
            // Valida si el usuario es un administrador
            if(!Auth::user()->hasRole('Correspondencia Recibida Admin')) {
                $q->where("external_received.functionary_id", Auth::user()->id);
                $q->orWhere("external_received_copy_share.users_id", Auth::user()->id);
            }
        })
        ->distinct("external_received.id")
        ->count();
        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de Correspondencias Recibidas
        return ["totalCorrRecibidas" => $count_externals];
    }

    /**
     * Obtiene las cantidades totales del módulo de Correspondencia enviada
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.1.0
     */
    public function obtenerTotalesCorrEnviada(Request $request) {
        // Variable para contar el número total de registros de la consulta realizada
        $count_externals = 0;
        $externals_state = [];
        // Arreglos para formatear los estados originales a estados sin caracteres especiales ni espacios, esto para la asigación de los consolidados en el tablero
        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];

        //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
        $groups =  DB::table('users_work_groups')
        ->where('users_id', Auth::user()->id)
        ->pluck("work_groups_id")->toArray();

        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
        $externals_state = External::select('state', DB::raw('COUNT(DISTINCT correspondence_external.id) as total'))
        ->leftjoin('correspondence_external_recipient', 'correspondence_external.id', '=', 'correspondence_external_recipient.correspondence_external_id')
        ->leftjoin('correspondence_external_copy_share', 'correspondence_external.id', '=', 'correspondence_external_copy_share.correspondence_external_id')
        ->whereNull("correspondence_external_recipient.deleted_at")
        ->whereNull("correspondence_external_copy_share.deleted_at")
        ->where(function($q) use ($groups) {
            // Valida si el usuario es un administrador
            if(!Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                $q->where(
                    function($d) use ($groups) {

                        $d->where(
                            function($a) use ($groups){
                                $a->where("correspondence_external_recipient.users_id", Auth::user()->id);
                                $a->orWhere("correspondence_external_recipient.dependencias_id", Auth::user()->id_dependencia);
                                $a->orWhere("correspondence_external_recipient.cargos_id", Auth::user()->id_cargo);
                                $a->orWhereIn("correspondence_external_recipient.work_groups_id",$groups);
                                $a->orWhere("correspondence_external.external_all", 1);

                            }
                        )->where("correspondence_external.state","Público");
                    }
                );

                $q->orWhere("correspondence_external.from_id", Auth::user()->id);
                $q->orWhere("correspondence_external.elaborated_now", Auth::user()->id);
                $q->orWhere("correspondence_external.reviewd_now", Auth::user()->id);
                $q->orWhere("correspondence_external.approved_now", Auth::user()->id);
                $q->orWhere("correspondence_external_copy_share.users_id", Auth::user()->id);
                $q->orWhereRelation('externalVersions.externalSigns', 'users_id', Auth::user()->id);
            }
        })
        ->groupBy("correspondence_external.state")->get()->toArray();

        // Arreglo para almacenar la cantidad de correspondencias externas según el estado (Público, Elaboración, Revisión, Aprobación, Pendiente de firma, Devuelto para modificaciones)
        $state_totales = [];
        // Recorre el resultado de correspondencias externas según el estado, organizándolos por su estado como llave y el total como el valor
        foreach($externals_state as $v) {
            $state_totales[str_replace($estados_originales, $estados_reemplazar, $v["state"])] = $v["total"];
        }

        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
        $count_externals = External::select(
            'correspondence_external.origen',
            DB::raw('COUNT(DISTINCT correspondence_external.id) as total')
            )
            ->leftJoin('correspondence_external_recipient', 'correspondence_external.id', '=', 'correspondence_external_recipient.correspondence_external_id')
            ->leftJoin('correspondence_external_copy_share', 'correspondence_external.id', '=', 'correspondence_external_copy_share.correspondence_external_id')
            ->whereNull("correspondence_external_recipient.deleted_at")
            ->whereNull("correspondence_external_copy_share.deleted_at")
            ->where(function ($q) use ($groups) {
                // Valida si el usuario es un administrador
                if (!Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                    $q->where(function ($d) use ($groups) {
                        $d->where(function ($a) use ($groups) {
                            $a->where("correspondence_external_recipient.users_id", Auth::user()->id)
                                ->orWhere("correspondence_external_recipient.dependencias_id", Auth::user()->id_dependencia)
                                ->orWhere("correspondence_external_recipient.cargos_id", Auth::user()->id_cargo)
                                ->orWhereIn("correspondence_external_recipient.work_groups_id", $groups)
                                ->orWhere("correspondence_external.external_all", 1);
                        })->where("correspondence_external.state", "Público");
                    });

                    $q->orWhere("correspondence_external.from_id", Auth::user()->id)
                        ->orWhere("correspondence_external.elaborated_now", Auth::user()->id)
                        ->orWhere("correspondence_external.reviewd_now", Auth::user()->id)
                        ->orWhere("correspondence_external.approved_now", Auth::user()->id)
                        ->orWhere("correspondence_external_copy_share.users_id", Auth::user()->id)
                        ->orWhereRelation('externalVersions.externalSigns', 'users_id', Auth::user()->id);
                }
            })
            ->distinct("correspondence_external.id")
            ->groupBy("correspondence_external.origen")
            ->pluck('total', 'correspondence_external.origen')
            ->toArray();

        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de Correspondencias Enviadas
        return ["totalCorrEnviadas" => $count_externals["FISICO"] ?? 0, "totalCorrEnviadasCP" => $count_externals["DIGITAL"] ?? 0, "totalEstados" => $state_totales];
    }

    /**
     * Obtiene las cantidades totales del módulo de Correspondencia interna
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 14 - 2024
     * @version 1.1.0
     */
    public function obtenerTotalesCorrInterna(Request $request) {
        $internals_state = [];
        // Arreglos para formatear los estados originales a estados sin caracteres especiales ni espacios, esto para la asigación de los consolidados en el tablero
        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];

        //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
        $groups =  DB::table('users_work_groups')
        ->where('users_id', Auth::user()->id)
        ->pluck("work_groups_id")->toArray();

        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
        $internals_state = Internal::select('state', DB::raw('COUNT(DISTINCT correspondence_internal.id) as total'))
        ->leftjoin('correspondence_internal_recipient', 'correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
        ->leftJoin('correspondence_internal_copy_share', function($join) {
           $join->on('correspondence_internal.id', '=', 'correspondence_internal_copy_share.correspondence_internal_id')
             ->whereNull('correspondence_internal_copy_share.deleted_at');
        })
        ->whereNull("correspondence_internal_recipient.deleted_at")

        ->where(function($q) use ($groups){
            // Valida si el usuario es un administrador
            if(!Auth::user()->hasRole('Correspondencia Interna Admin')) {
                $q->where(
                    function($d) use ($groups) {

                $d->where(
                        function($a) use ($groups){
                            $a->where("correspondence_internal_recipient.users_id", Auth::user()->id);
                            $a->orWhere("correspondence_internal_recipient.dependencias_id", Auth::user()->id_dependencia);
                            $a->orWhere("correspondence_internal_recipient.cargos_id", Auth::user()->id_cargo);
                            $a->orWhereIn("correspondence_internal_recipient.work_groups_id",$groups);
                            $a->orWhere("correspondence_internal.internal_all", 1);

                        }
                    )->where("correspondence_internal.state","Público");
                }
                );

                $q->orWhere("correspondence_internal.from_id", Auth::user()->id);
                $q->orWhere("correspondence_internal.elaborated_now", Auth::user()->id);
                $q->orWhere("correspondence_internal.reviewd_now", Auth::user()->id);
                $q->orWhere("correspondence_internal.approved_now", Auth::user()->id);
                $q->orWhere("correspondence_internal_copy_share.users_id", Auth::user()->id);
                $q->orWhereRelation('internalVersions.internalSigns','users_id',Auth::user()->id);
            }

         })
         ->groupBy("correspondence_internal.state")->get()->toArray();

        // Arreglo para almacenar la cantidad de correspondencias internas según el estado (Público, Elaboración, Revisión, Aprobación, Pendiente de firma, Devuelto para modificaciones)
        $state_totales = [];
        // Recorre el resultado de correspondencias internas según el estado, organizándolos por su estado como llave y el total como el valor
        foreach($internals_state as $v) {
            $state_totales[str_replace($estados_originales, $estados_reemplazar, $v["state"])] = $v["total"];
        }

        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
        $count_internals_cero_papeles = Internal::select('correspondence_internal.*', 'correspondence_internal_recipient.name')
        ->leftjoin('correspondence_internal_recipient', 'correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
        ->leftJoin('correspondence_internal_copy_share', function($join) {
           $join->on('correspondence_internal.id', '=', 'correspondence_internal_copy_share.correspondence_internal_id')
             ->whereNull('correspondence_internal_copy_share.deleted_at');
        })
        ->whereNull("correspondence_internal_recipient.deleted_at")
        ->where("correspondence_internal.origen", "DIGITAL")
        ->where(function($q) use ($groups) {
            // Valida si el usuario es un administrador
            if(!Auth::user()->hasRole('Correspondencia Interna Admin')) {
                $q->where(
                    function($d) use ($groups) {

                $d->where(
                        function($a) use ($groups){
                            $a->where("correspondence_internal_recipient.users_id", Auth::user()->id);
                            $a->orWhere("correspondence_internal_recipient.dependencias_id", Auth::user()->id_dependencia);
                            $a->orWhere("correspondence_internal_recipient.cargos_id", Auth::user()->id_cargo);
                            $a->orWhereIn("correspondence_internal_recipient.work_groups_id",$groups);
                            $a->orWhere("correspondence_internal.internal_all", 1);

                        }
                    )->where("correspondence_internal.state","Público");
                }
                );

                $q->orWhere("correspondence_internal.from_id", Auth::user()->id);
                $q->orWhere("correspondence_internal.elaborated_now", Auth::user()->id);
                $q->orWhere("correspondence_internal.reviewd_now", Auth::user()->id);
                $q->orWhere("correspondence_internal.approved_now", Auth::user()->id);
                $q->orWhere("correspondence_internal_copy_share.users_id", Auth::user()->id);
                $authUserId = Auth::user()->id;
                $q->orWhereRaw("FIND_IN_SET(?, correspondence_internal.elaborated)", [$authUserId]);
                $q->orWhereRaw("FIND_IN_SET(?, correspondence_internal.reviewd)", [$authUserId]);
                $q->orWhereRaw("FIND_IN_SET(?, correspondence_internal.approved)", [$authUserId]);
                $q->orWhereRelation('internalVersions.internalSigns','users_id',Auth::user()->id);
            }
        })
        ->distinct("correspondence_internal.id")
        ->count();
        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de Correspondencias Internas
        return ["totalCorrInternasCP" => $count_internals_cero_papeles, "totalEstados" => $state_totales];
    }

    /**
     * Obtiene las cantidades totales del módulo de Documentos electrónicos
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 09 - 2024
     * @version 1.0.0
     */
    public function obtenerTotalesDocumentosELectronicos(Request $request) {
        // Variable para contar el número total de registros de la consulta realizada
        $count_documentos = 0;
        //valida si es un administrador de documentos electrónicos
        if (Auth::user()->hasRole('Admin Documentos Electrónicos')) {
            // Contar el número total de registros de documentos
            $count_documentos = Documento::count();
        } else {
            // Consulta el total de documentos electrónicos según los que el usuario pueda acceder
            $count_documentos = Documento::select(DB::raw('users_id_actual AS approved_now, users_id_actual AS elaborated_now, users_id_actual AS reviewd_now'), 'de_documento.updated_at', 'consecutivo',
            DB::raw('CASE
                        WHEN LENGTH(titulo_asunto) - LENGTH(REPLACE(titulo_asunto, " ", "")) + 1 > 30
                        THEN CONCAT(SUBSTRING_INDEX(titulo_asunto, " ", 30), "...")
                        ELSE titulo_asunto
                    END AS titulo_asunto'),
            'estado', DB::raw('"Documentos electrónicos" AS module'), DB::raw('CONCAT("documentos-electronicos/documentos?qder=", TO_BASE64(de_documento.id)) AS link'))
            ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
            ->whereNull("de_documento_compartir.deleted_at")
            ->where(function($q) {
                $q->where(function ($d) {
                    $d->where("de_documento_compartir.users_id", Auth::user()->id);
                    $d->where("de_documento.estado", "Público");
                });
                $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                $q->orWhereRelation('deDocumentoVersions.deDocumentoFirmars', 'users_id', Auth::user()->id);
                $q->orWhereHas("deTiposDocumentos.dePermisoConsultarDocumentos", function ($q) {
                    $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                    $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                    $q->where("dependencia_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                    $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                    $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                    $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                });
            })
            ->distinct("de_documento.id")
            ->count();
        }

        // Retorna un arreglo multidimensional con todos los arreglos de las cantidades de los documentos electrónicos
        return ["totalDocumentosElectronicos" => $count_documentos];
    }

    /**
     * Obtiene las entradas mas recientes de los módulos de corresponndencia interna, enviada y recibida,
     * además de PQR ordenadas por fecha de modificación (updated_at)
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 17 - 2024
     * @version 1.0.0
     */
    public function obtenerEntradasRecientesDashboard() {
        //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
        $groups =  DB::table('users_work_groups')
        ->where('users_id', Auth::user()->id)
        ->pluck("work_groups_id")->toArray();
        // Crea una unión de las corresponndencias interna, enviada y recibida para mostar en el listado del dashboard
        $entradas_recientes = Internal::select('elaborated_now', 'reviewd_now', 'approved_now', 'correspondence_internal.updated_at', 'consecutive',
            DB::raw('CASE
                        WHEN LENGTH(title) - LENGTH(REPLACE(title, " ", "")) + 1 > 30
                        THEN CONCAT(SUBSTRING_INDEX(title, " ", 30), "...")
                        ELSE title
                    END AS title'),
            'state', DB::raw('"Interna" AS module'), DB::raw('CONCAT("correspondence/internals?qder=", TO_BASE64(correspondence_internal.id)) AS link'))
            ->leftjoin('correspondence_internal_recipient', 'correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
            ->leftJoin('correspondence_internal_copy_share', function($join) {
           $join->on('correspondence_internal.id', '=', 'correspondence_internal_copy_share.correspondence_internal_id')
             ->whereNull('correspondence_internal_copy_share.deleted_at');
        })
            ->whereNull("correspondence_internal_recipient.deleted_at")

            ->where(function($q) use ($groups){
                // Valida si el usuario es un administrador
                if(!Auth::user()->hasRole('Correspondencia Interna Admin') || true) {
                    $q->where(
                        function($d) use ($groups) {
                            $d->where(
                                function($a) use ($groups){
                                    $a->where("correspondence_internal_recipient.users_id", Auth::user()->id);
                                    $a->orWhere("correspondence_internal_recipient.dependencias_id", Auth::user()->id_dependencia);
                                    $a->orWhere("correspondence_internal_recipient.cargos_id", Auth::user()->id_cargo);
                                    $a->orWhereIn("correspondence_internal_recipient.work_groups_id",$groups);
                                    $a->orWhere("correspondence_internal.internal_all", 1);

                                }
                            )->where("correspondence_internal.state","Público");
                        }
                    );

                    $q->orWhere("correspondence_internal.from_id", Auth::user()->id);
                    $q->orWhere("correspondence_internal.elaborated_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal.reviewd_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal.approved_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal_copy_share.users_id", Auth::user()->id);
                    $q->orWhereRelation('internalVersions.internalSigns','users_id',Auth::user()->id);
                }

            })
            ->union(External::select(DB::raw('"" AS elaborated_now, "" AS elaborated_now, "" AS elaborated_now'), 'correspondence_external.updated_at', 'consecutive',
                    DB::raw('CASE
                                WHEN LENGTH(title) - LENGTH(REPLACE(title, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(title, " ", 30), "...")
                                ELSE title
                            END AS title'),
                    'state', DB::raw('"Externa" AS module'), DB::raw('CONCAT("correspondence/externals?qder=", TO_BASE64(correspondence_external.id)) AS link'))
                    ->leftjoin('correspondence_external_recipient', 'correspondence_external.id', '=', 'correspondence_external_recipient.correspondence_external_id')
                    ->leftjoin('correspondence_external_copy_share', 'correspondence_external.id', '=', 'correspondence_external_copy_share.correspondence_external_id')
                    ->whereNull("correspondence_external_recipient.deleted_at")
                    ->whereNull("correspondence_external_copy_share.deleted_at")
                    ->where(function($q) use ($groups){
                        // Valida si el usuario es un administrador
                        if(!Auth::user()->hasRole('Correspondencia Enviada Admin') || true) {

                            $q->where(
                                function($d) use ($groups) {
                                    $d->where(
                                        function($a) use ($groups){
                                            $a->where("correspondence_external_recipient.users_id", Auth::user()->id);
                                            $a->orWhere("correspondence_external_recipient.dependencias_id", Auth::user()->id_dependencia);
                                            $a->orWhere("correspondence_external_recipient.cargos_id", Auth::user()->id_cargo);
                                            $a->orWhereIn("correspondence_external_recipient.work_groups_id",$groups);
                                            $a->orWhere("correspondence_external.external_all", 1);

                                        }
                                    )->where("correspondence_external.state","Público");
                                }
                            );

                            $q->orWhere("correspondence_external.from_id", Auth::user()->id);
                            $q->orWhere("correspondence_external.elaborated_now", Auth::user()->id);
                            $q->orWhere("correspondence_external.reviewd_now", Auth::user()->id);
                            $q->orWhere("correspondence_external.approved_now", Auth::user()->id);
                            $q->orWhere("correspondence_external_copy_share.users_id", Auth::user()->id);
                            $q->orWhereRelation('externalVersions.externalSigns','users_id',Auth::user()->id);
                        }
                    })
                )
            ->union(ExternalReceived::select(DB::raw('"" AS elaborated_now, "" AS elaborated_now, "" AS elaborated_now'), 'external_received.updated_at', DB::raw('consecutive collate utf8mb4_unicode_ci as consecutive'),
                    DB::raw('CASE
                                WHEN LENGTH(issue) - LENGTH(REPLACE(issue, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(issue, " ", 30), "...")
                                ELSE issue
                            END collate utf8mb4_unicode_ci AS title'))
                    ->selectRaw('CASE
                                    WHEN state = 1 THEN "Devuelto"
                                    WHEN state = 2 THEN "Pendiente"
                                    WHEN state = 3 THEN "Público"
                                    WHEN state = 4 THEN "Rechazado"
                                    ELSE "Sin estado"
                                END as state')
                    ->selectRaw('"Externa recibida" AS module')
                    ->selectRaw('CONCAT("correspondence/external-receiveds?qder=", TO_BASE64(external_received.id)) AS link')
                    ->leftJoin('external_received_copy_share', function($join) {
                        $join->on('external_received.id', '=', 'external_received_copy_share.external_received_id')
                        ->whereNull('external_received_copy_share.deleted_at');
                    })
                    ->where(function($q) {
                        // Valida si el usuario es un administrador
                        if(!Auth::user()->hasRole('Correspondencia Recibida Admin') || true) {
                            $q->where("external_received.functionary_id", Auth::user()->id);
                            $q->orWhere("external_received_copy_share.users_id", Auth::user()->id);
                        }
                    })
                )
            ->union(PQR::select(DB::raw('funcionario_users_id AS approved_now, funcionario_users_id AS elaborated_now, funcionario_users_id AS reviewd_now'), 'updated_at', DB::raw('pqr_id collate utf8mb4_unicode_ci AS consecutive'),
                    DB::raw('CASE
                                WHEN LENGTH(contenido) - LENGTH(REPLACE(contenido, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(contenido, " ", 30), "...")
                                ELSE contenido
                            END collate utf8mb4_unicode_ci AS title'),
                    DB::raw('estado collate utf8mb4_unicode_ci AS state'), DB::raw('"PQRS" AS module'), DB::raw('CONCAT("pqrs/p-q-r-s?qder=", TO_BASE64(id)) AS link'))
                ->where(function($q) {
                    // Valida si el usuario es un administrador
                    if(!(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos')) || true) {
                        $q->where("funcionario_users_id", Auth::user()->id);
                        $q->orWhereRelation('pqrCopia', 'users_id', Auth::user()->id);
                    }
                })
            )
            ->union(Documento::select(DB::raw('users_id_actual AS approved_now, users_id_actual AS elaborated_now, users_id_actual AS reviewd_now'), 'de_documento.updated_at', 'consecutivo',
                    DB::raw('CASE
                                WHEN LENGTH(titulo_asunto) - LENGTH(REPLACE(titulo_asunto, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(titulo_asunto, " ", 30), "...")
                                ELSE titulo_asunto
                            END AS titulo_asunto'),
                    'estado', DB::raw('"Documentos electrónicos" AS module'), DB::raw('CONCAT("documentos-electronicos/documentos?qder=", TO_BASE64(de_documento.id)) AS link'))
                ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                ->whereNull("de_documento_compartir.deleted_at")
                ->where(function($q) {
                    $q->where(function ($d) {
                        $d->where("de_documento_compartir.users_id", Auth::user()->id);
                        $d->where("de_documento.estado", "Público");
                    });
                    $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                    $q->orWhereRelation('deDocumentoVersions.deDocumentoFirmars', 'users_id', Auth::user()->id);
                    $q->orWhereHas("deTiposDocumentos.dePermisoConsultarDocumentos", function ($q) {
                        $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                        $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                        $q->where("dependencia_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                        $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                        $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                        $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                    });
                })
            )
            ->orderBy('updated_at', 'DESC')
            ->take(7)
            ->get()->toArray();
        // Retorna un arreglo multidimensional con las entrdas mas recientes de las diferentes correspondencias, PQRS y documentos electrónicos
        return $this->sendResponse($entradas_recientes, "Entradas recientes del dashboard");
    }

    /**
     * Obtiene las entradas mas recientes de los módulos de corresponndencia interna, enviada y recibida,
     * además de PQR ordenadas por fecha de modificación (updated_at)
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 17 - 2024
     * @version 1.0.0
     */
    public function buscadorDashboard(Request $request) {
        // Obtiene los criterio de búsqueda del usuario
        $parametros = $request->all();
        //valida si el usuario en sesion pertenece a los grupos de trabajo de la encuesta
        $groups =  DB::table('users_work_groups')
        ->where('users_id', Auth::user()->id)
        ->pluck("work_groups_id")->toArray();
        // Crea una unión de las corresponndencias interna, enviada y recibida para mostar en el listado del dashboard
        $entradas_recientes = Internal::select('elaborated_now', 'reviewd_now', 'approved_now', 'correspondence_internal.updated_at', 'consecutive',
            DB::raw('CASE
                        WHEN LENGTH(title) - LENGTH(REPLACE(title, " ", "")) + 1 > 30
                        THEN CONCAT(SUBSTRING_INDEX(title, " ", 30), "...")
                        ELSE title
                    END AS title'),
            'state', DB::raw('"Interna" AS module'), DB::raw('CONCAT("correspondence/internals?qder=", TO_BASE64(correspondence_internal.id)) AS link'))
            ->leftjoin('correspondence_internal_recipient', 'correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
            ->leftJoin('correspondence_internal_copy_share', function($join) {
           $join->on('correspondence_internal.id', '=', 'correspondence_internal_copy_share.correspondence_internal_id')
             ->whereNull('correspondence_internal_copy_share.deleted_at');
        })
            ->whereNull("correspondence_internal_recipient.deleted_at")

            ->where('correspondence_internal.title','like','%'.$parametros["query"].'%')
            ->where(function($q) use ($groups){
                // Valida si el usuario es un administrador
                if(!Auth::user()->hasRole('Correspondencia Interna Admin') || true) {
                    $q->where(
                        function($d) use ($groups) {
                            $d->where(
                                function($a) use ($groups){
                                    $a->where("correspondence_internal_recipient.users_id", Auth::user()->id);
                                    $a->orWhere("correspondence_internal_recipient.dependencias_id", Auth::user()->id_dependencia);
                                    $a->orWhere("correspondence_internal_recipient.cargos_id", Auth::user()->id_cargo);
                                    $a->orWhereIn("correspondence_internal_recipient.work_groups_id",$groups);
                                    $a->orWhere("correspondence_internal.internal_all", 1);

                                }
                            )->where("correspondence_internal.state","Público");
                        }
                    );

                    $q->orWhere("correspondence_internal.from_id", Auth::user()->id);
                    $q->orWhere("correspondence_internal.elaborated_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal.reviewd_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal.approved_now", Auth::user()->id);
                    $q->orWhere("correspondence_internal_copy_share.users_id", Auth::user()->id);
                    $q->orWhereRelation('internalVersions.internalSigns','users_id',Auth::user()->id);
                }

            })
            ->union(External::select(DB::raw('"" AS elaborated_now, "" AS elaborated_now, "" AS elaborated_now'), 'correspondence_external.updated_at', 'consecutive',
                    DB::raw('CASE
                                WHEN LENGTH(title) - LENGTH(REPLACE(title, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(title, " ", 30), "...")
                                ELSE title
                            END AS title'),
                    'state', DB::raw('"Externa" AS module'), DB::raw('CONCAT("correspondence/externals?qder=", TO_BASE64(correspondence_external.id)) AS link'))
                    ->leftjoin('correspondence_external_recipient', 'correspondence_external.id', '=', 'correspondence_external_recipient.correspondence_external_id')
                    ->leftjoin('correspondence_external_copy_share', 'correspondence_external.id', '=', 'correspondence_external_copy_share.correspondence_external_id')
                    ->whereNull("correspondence_external_recipient.deleted_at")
                    ->whereNull("correspondence_external_copy_share.deleted_at")
                    ->where('correspondence_external.title','like','%'.$parametros["query"].'%')
                    ->where(function($q) use ($groups){
                        // Valida si el usuario es un administrador
                        if(!Auth::user()->hasRole('Correspondencia Enviada Admin') || true) {

                            $q->where(
                                function($d) use ($groups) {
                                    $d->where(
                                        function($a) use ($groups){
                                            $a->where("correspondence_external_recipient.users_id", Auth::user()->id);
                                            $a->orWhere("correspondence_external_recipient.dependencias_id", Auth::user()->id_dependencia);
                                            $a->orWhere("correspondence_external_recipient.cargos_id", Auth::user()->id_cargo);
                                            $a->orWhereIn("correspondence_external_recipient.work_groups_id",$groups);
                                            $a->orWhere("correspondence_external.external_all", 1);

                                        }
                                    )->where("correspondence_external.state","Público");
                                }
                            );

                            $q->orWhere("correspondence_external.from_id", Auth::user()->id);
                            $q->orWhere("correspondence_external.elaborated_now", Auth::user()->id);
                            $q->orWhere("correspondence_external.reviewd_now", Auth::user()->id);
                            $q->orWhere("correspondence_external.approved_now", Auth::user()->id);
                            $q->orWhere("correspondence_external_copy_share.users_id", Auth::user()->id);
                            $q->orWhereRelation('externalVersions.externalSigns','users_id',Auth::user()->id);
                        }
                    })
                )
            ->union(ExternalReceived::select(DB::raw('"" AS elaborated_now, "" AS elaborated_now, "" AS elaborated_now'), 'external_received.updated_at', DB::raw('consecutive collate utf8mb4_unicode_ci as consecutive'),
                    DB::raw('CASE
                        WHEN LENGTH(issue) - LENGTH(REPLACE(issue, " ", "")) + 1 > 30
                        THEN CONCAT(SUBSTRING_INDEX(issue, " ", 30), "...")
                        ELSE issue
                    END collate utf8mb4_unicode_ci AS title'))
                    ->selectRaw('CASE
                                    WHEN state = 1 THEN "Devuelto"
                                    WHEN state = 2 THEN "Pendiente"
                                    WHEN state = 3 THEN "Público"
                                    WHEN state = 4 THEN "Rechazado"
                                    ELSE "Sin estado"
                                END as state')
                    ->selectRaw('"Externa recibida" AS module')
                    ->selectRaw('CONCAT("correspondence/external-receiveds?qder=", TO_BASE64(external_received.id)) AS link')
                    ->leftJoin('external_received_copy_share', function($join) {
                        $join->on('external_received.id', '=', 'external_received_copy_share.external_received_id')
                        ->whereNull('external_received_copy_share.deleted_at');
                    })
                    ->where('external_received.issue','like','%'.$parametros["query"].'%')
                    ->where(function($q) {
                        // Valida si el usuario es un administrador
                        if(!Auth::user()->hasRole('Correspondencia Recibida Admin') || true) {
                            $q->where("external_received.functionary_id", Auth::user()->id);
                            $q->orWhere("external_received_copy_share.users_id", Auth::user()->id);
                        }
                    })
                )
            ->union(PQR::select(DB::raw('funcionario_users_id AS approved_now, funcionario_users_id AS elaborated_now, funcionario_users_id AS reviewd_now'), 'updated_at', DB::raw('pqr_id collate utf8mb4_unicode_ci AS consecutive'),
                    DB::raw('CASE
                                WHEN LENGTH(contenido) - LENGTH(REPLACE(contenido, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(contenido, " ", 30), "...")
                                ELSE contenido
                            END collate utf8mb4_unicode_ci AS title'),
                    DB::raw('estado collate utf8mb4_unicode_ci AS state'), DB::raw('"PQRS" AS module'), DB::raw('CONCAT("pqrs/p-q-r-s?qder=", TO_BASE64(id)) AS link'))
                    ->where('contenido','like','%'.$parametros["query"].'%')
                    ->where(function($q) {
                        // Valida si el usuario es un administrador
                        if(!(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Consulta de requerimientos')) || true) {
                            $q->where("funcionario_users_id", Auth::user()->id);
                            $q->orWhereRelation('pqrCopia', 'users_id', Auth::user()->id);
                        }
                    })
                )
            ->union(Documento::select(DB::raw('users_id_actual AS approved_now, users_id_actual AS elaborated_now, users_id_actual AS reviewd_now'), 'de_documento.updated_at', 'consecutivo',
                    DB::raw('CASE
                                WHEN LENGTH(titulo_asunto) - LENGTH(REPLACE(titulo_asunto, " ", "")) + 1 > 30
                                THEN CONCAT(SUBSTRING_INDEX(titulo_asunto, " ", 30), "...")
                                ELSE titulo_asunto
                            END AS titulo_asunto'),
                    'estado', DB::raw('"Documentos electrónicos" AS module'), DB::raw('CONCAT("documentos-electronicos/documentos?qder=", TO_BASE64(de_documento.id)) AS link'))
                    ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                    ->whereNull("de_documento_compartir.deleted_at")
                    ->where('titulo_asunto','like','%'.$parametros["query"].'%')
                    ->where(function($q) {
                        $q->where(function ($d) {
                            $d->where("de_documento_compartir.users_id", Auth::user()->id);
                            $d->where("de_documento.estado", "Público");
                        });
                        $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                        $q->orWhereRelation('deDocumentoVersions.deDocumentoFirmars', 'users_id', Auth::user()->id);
                        $q->orWhereHas("deTiposDocumentos.dePermisoConsultarDocumentos", function ($q) {
                            $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                            $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                            $q->where("dependencia_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                            $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                            $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                            $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                        });
                    }))
                ->orderBy('updated_at', 'DESC')
                ->get()->toArray();
        // Retorna un arreglo multidimensional con las entradas mas recientes de las diferentes correspondencias, PQRS y documentos electrónicos
        return $this->sendResponse($entradas_recientes, "Entradas recientes del dashboard");
    }

    /**
     * Aceptar los términos y condiciones cuando un usuario entra al sistema por primer vez y
     * cambio de la contraseña por una nueva.
     *
     * @param Request $request
     * @return void
     */
    public function serviceTermsChangePassword(Request $request) {
        $input = $request->all();

        $request->validate([
            'password' => ['required', 'string',  Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            'confirmed']
        ]);
        // Encripta la contraseña ingresada por el usuario
        $input['password'] = Hash::make($input['password']);
        // Asigna el valor de 1 aceptando los términos y condiciones por el usuario
        $input['accept_service_terms'] = 1;
        // Obtiene el ID del usuario en sesión
        $user_id = Auth::user()->id;
        // Actualiza el password y términos y condiciones por el usuario
        User::find($user_id)->update($input);
        // Obtiene la información del usuario según el ID
        $user_updated = User::find($user_id)->toArray();
        $user_updated["users_id"] = $user_id;
        // Crea un registro de historial del usuario
        UserHistory::create($user_updated);
        // Retorna una excepción o si todo sale correcto se le muestran los módulos al usuario
        return $request->wantsJson() ? new JsonResponse([], 201) : redirect("/modules");
    }

    public function firmarPDF() {
        /// Ruta al PDF original que deseas firmar
        $pdfOriginalPath = public_path('storage/documento_expediente_sin_firmar.pdf');

        // Ruta donde se guardará el PDF firmado
        $pdfFirmadoPath = public_path('storage/documento_expediente_sin_firmar_firmado.pdf');

        // Ruta al archivo .p12 (certificado digital)
        // $p12Path = 'file://'.public_path('storage/certificate_seven.crt');
        $p12Path = 'file://'.public_path('storage/selfcert.pem');
        $p12KeyPath = 'file://'.public_path('storage/enc_key.pem');
        $p12Password = 'cne7';

        // set additional information
        $info = array(
            'Name' => '3ECS',
            'Location' => 'xx',
            'Reason' => 'Testing TCPDF',
            'ContactInfo' => 'xx',
        );

        // Crear una instancia de TCPDF_Fpdi
        $pdf = new Fpdi();
        $pdf->setSignature($p12Path, $p12KeyPath, $p12Password, '', 2, $info);

        // Importar el PDF original
        $pageCount = $pdf->setSourceFile($pdfOriginalPath);
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $templateId = $pdf->importPage($pageNumber);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
        }

        // Configurar la posición y texto de la firma (ajusta según tus necesidades)
        $x = 50;
        $y = 220;
        $textoFirma = 'Firmado digitalmente por: Germán';

        // Agregar la firma al PDF
        // $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY($x, $y);
        $pdf->Write(0, $textoFirma);

        // Firmar el PDF
        $pdf->Output($pdfFirmadoPath, 'F');

        return response()->download($pdfFirmadoPath);

    }

    public function firmarPDFV2() {
        // $uploadedPdf = $request->file('pdfDoc');
        $pdf = new Fpdi();
        // $pdfFilePath = $uploadedPdf->getPathname();
        $pdfFilePath = public_path('storage/ComprobanteDePago.7909312811.pdf');
        $pageCount = $pdf->setSourceFile($pdfFilePath);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
        }
        $info = array(
            'Name' => '',
            'Location' => 'Office',
            'Reason' => 'Aproval',
            'ContactInfo' => 'Info de contacto GGV'
        );
        $certificate = 'file://' . public_path('storage/certificado.pem');
        $key='file://' . public_path('storage/clave_privada.pem');
        $pdf->setSignature($certificate, $key, 'Pass1257141563', '', 2, $info);
        $pdfContent = $pdf->Output('', 'S');

        return response($pdfContent)
           ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="new_pdf.pdf"');

    }

    public function firmarPDF_TCPDF()
    {
        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 052');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 052', PDF_HEADER_STRING);
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        //set some language-dependent strings
        // $pdf->setLanguageArray($l);
        // ---------------------------------------------------------
        /*
        NOTES:
        - To create self-signed signature: openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout tcpdf.crt -out tcpdf.crt
        - To export crt to p12: openssl pkcs12 -export -in tcpdf.crt -out tcpdf.p12
        - To convert pfx certificate to pem: openssl pkcs12 -in tcpdf.pfx -out tcpdf.crt -nodes
        */
        // set certificate file
        $certificate = 'file://'.public_path('storage/certificado.pem');
        $key='file://' . public_path('storage/clave_privada.pem');

        // set additional information
        $info = array(
            'Name' => 'TCPDF',
            'Location' => 'Office',
            'Reason' => 'Testing TCPDF',
            'ContactInfo' => 'http://www.tcpdf.org',
            );
        // set document signature
        $pdf->setSignature($certificate, $key, 'Ggv12345*', '', 2, $info);
        // set font
        $pdf->SetFont('helvetica', '', 12);
        // add a page
        $pdf->AddPage();
        // print a line of text
        $text = 'This is a digitally signed document using the default (example) tcpdf.crt certificate.
        To validate this signature you have to load the tcpdf.fdf on the Arobat Reader to add the certificate to List of Trusted Identities.

        For more information check the source code of this example and the source code documentation for the setSignature() method.

        www.tcpdf.org';
        $pdf->writeHTML($text, true, 0, true, 0);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // *** set signature appearance ***
        // create content for signature (image and/or text)
        $pdf->Image('../images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');
        // define active area for signature appearance
        $pdf->setSignatureAppearance(180, 60, 15, 15);
        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(180, 80, 15, 15);
        // ---------------------------------------------------------
        //Close and output PDF document
        $pdf->Output('example_052.pdf', 'I');
    }

    public function firmarPDF_TCPDFV2()
    {
        //============================================================+
        // File name   : example_052.php
        // Begin       : 2009-05-07
        // Last Update : 2013-05-14
        //
        // Description : Example 052 for TCPDF class
        //               Certification Signature (experimental)
        //
        // Author: Nicola Asuni
        //
        // (c) Copyright:
        //               Nicola Asuni
        //               Tecnick.com LTD
        //               www.tecnick.com
        //               info@tecnick.com
        //============================================================+

        /**
         * Creates an example PDF TEST document using TCPDF
         * @package com.tecnick.tcpdf
         * @abstract TCPDF - Example: Certification Signature (experimental)
         * @author Nicola Asuni
         * @since 2009-05-07
         */

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nicola Asuni');
        $pdf->SetTitle('TCPDF Example 052');
        $pdf->SetSubject('TCPDF Tutorial');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 052', PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------

        /*
        NOTES:
        - To create self-signed signature: openssl req -x509 -nodes -days 365000 -newkey rsa:1024 -keyout tcpdf.crt -out tcpdf.crt
        - To export crt to p12: openssl pkcs12 -export -in tcpdf.crt -out tcpdf.p12
        - To convert pfx certificate to pem: openssl pkcs12 -in tcpdf.pfx -out tcpdf.crt -nodes
        */

        // set certificate file
        $certificate = 'file://'.public_path('storage/certificado.pem');
        $certificate2 = 'file://'.public_path('storage/clave_privada.pem');

        // set additional information
        $info = array(
            'Name' => 'TCPDF',
            'Location' => 'Office',
            'Reason' => 'Testing TCPDF',
            'ContactInfo' => 'http://www.tcpdf.org',
            );

        // set document signature
        $pdf->setSignature($certificate, $certificate2, 'tcpdfdemo', '', 2, $info);

        // set font
        $pdf->SetFont('helvetica', '', 12);

        // add a page
        $pdf->AddPage();

        // print a line of text
        $text = 'This is a <b color="#FF0000">digitally signed document</b> using the default (example) <b>tcpdf.crt</b> certificate.<br />To validate this signature you have to load the <b color="#006600">tcpdf.fdf</b> on the Arobat Reader to add the certificate to <i>List of Trusted Identities</i>.<br /><br />For more information check the source code of this example and the source code documentation for the <i>setSignature()</i> method.<br /><br /><a href="http://www.tcpdf.org">www.tcpdf.org</a>';
        $pdf->writeHTML($text, true, 0, true, 0);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
        // *** set signature appearance ***

        // create content for signature (image and/or text)
        $pdf->Image('images/tcpdf_signature.png', 180, 60, 15, 15, 'PNG');

        // define active area for signature appearance
        $pdf->setSignatureAppearance(180, 60, 15, 15);

        // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

        // *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(180, 80, 15, 15);

        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output('example_052.pdf', 'D');

        //============================================================+
        // END OF FILE
        //============================================================+
    }

    function signPDF() {
        $pdfPath = public_path('storage/documentos/documento.pdf');
        $signedPdfPath = public_path('storage/documentos/documento_signed.pdf');

        // Verificar que el PDF original exista
        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'El documento PDF no existe'], 404);
        }

        // Cargar la clave privada y el certificado
        // $privateKeyPath = storage_path('certificados/private_german.pem');
        // $certificatePath = storage_path('certificados/certificate_german.pem');
        $privateKeyPath = storage_path('certificados/private_german_mejorado.pem');
        $certificatePath = storage_path('certificados/certificate_german_mejorado.pem');

        if (!file_exists($privateKeyPath) || !file_exists($certificatePath)) {
            return response()->json(['error' => 'No se encontró el certificado o la clave privada'], 500);
        }

        $privateKey = file_get_contents($privateKeyPath);
        $certificate = file_get_contents($certificatePath);

        // Crear el objeto TCPDF para firmar el PDF
        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Importar el PDF original
        $pdf->setSourceFile($pdfPath);
        $tplId = $pdf->importPage(1);
        $pdf->useTemplate($tplId);

        // Agregar una firma visible en el PDF
        $pdf->SetFont('Helvetica', '', 12);
        $pdf->SetXY(10, 250);
        $pdf->Cell(0, 10, 'Firmado digitalmente por German', 0, 1, 'C');

        // Definir la ubicación de la firma
        $pdf->setSignatureAppearance(10, 240, 50, 20);

        // Agregar la firma digital al PDF (PAdES)
        $pdf->setSignature($certificate, $privateKey, '', '', 2);
        $pdf->Output($signedPdfPath, 'F');

        // Descargar el PDF firmado
        return response()->download($signedPdfPath)->deleteFileAfterSend(true);
    }

    function signDocx() {
        $docxPath = public_path('storage/documentos/documento.docx');
        $signedDocxPath = public_path('storage/documentos/documento_signed.sig');

        // $privateKey = file_get_contents(storage_path('certificados/private_german.pem'));
        // $cert = file_get_contents(storage_path('certificados/certificate_german.pem'));
        $privateKey = file_get_contents(storage_path('certificados/private_german_mejorado.pem'));
        $cert = file_get_contents(storage_path('certificados/certificate_german_mejorado.pem'));

        // Leer el contenido del archivo a firmar
        $docxContent = file_get_contents($docxPath);

        // Crear la firma digital en formato DER (CAdES)
        $tempSigned = tempnam(sys_get_temp_dir(), 'signed_');

        // Firmar usando OpenSSL CLI en lugar de la función PHP para evitar el problema
        $cmd = "openssl cms -sign -binary -nodetach -in {$docxPath} -signer " . storage_path('certificados/certificate_german.pem') .
            " -inkey " . storage_path('certificados/private_german.pem') . " -outform DER -out {$tempSigned}";

        exec($cmd, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error al firmar el documento: " . implode("\n", $output));
        }

        // Mover el archivo firmado a la ruta final
        rename($tempSigned, $signedDocxPath);

        return response()->download($signedDocxPath);
    }

    function signXML() {
        $xmlPath = public_path('storage/documentos/factura.xml');
        $signedXmlPath = public_path('storage/documentos/factura_signed.xml');

        $privateKey = file_get_contents(storage_path('certificados/privkey.pem'));
        $cert = file_get_contents(storage_path('certificados/cert.pem'));

        $doc = new DOMDocument();
        $doc->load($xmlPath);

        // Crear la firma
        $objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $objDSig->addReference(
            $doc,
            XMLSecurityDSig::SHA256,
            ['http://www.w3.org/2000/09/xmldsig#enveloped-signature']
        );

        // Firmar con la clave privada
        $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type'=>'private']);
        $objKey->loadKey($privateKey, true);
        $objDSig->sign($objKey);
        $objDSig->add509Cert($cert);

        // Adjuntar firma al XML
        $objDSig->appendSignature($doc->documentElement);

        // Guardar el XML firmado
        $doc->save($signedXmlPath);

        return response()->download($signedXmlPath);
    }
}
