<?php

namespace Modules\ExpedientesElectronicos\Http\Controllers;

use App\Exports\expedientes_electronicos\RequestExport;
use App\Exports\GenericExport;
use Modules\ExpedientesElectronicos\Http\Requests\CreateExpedienteRequest;
use Modules\ExpedientesElectronicos\Http\Requests\UpdateExpedienteRequest;
use Modules\ExpedientesElectronicos\Repositories\ExpedienteRepository;
use Modules\ExpedientesElectronicos\Models\DocExpedienteHistorial;
use Modules\ExpedientesElectronicos\Models\Expediente;
use Modules\Intranet\Models\Dependency;
use Modules\DocumentosElectronicos\Http\Controllers\UtilController;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\SendNotificationController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\User;
use Modules\ExpedientesElectronicos\Models\ExpedienteHistorial;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DateTime;
use DB;
use Illuminate\Mail\Markdown;
use IntlDateFormatter;
use Modules\Configuracion\Models\ConfigurationGeneral;
use Modules\DocumentaryClassification\Models\dependencias;
use Modules\ExpedientesElectronicos\Models\DocumentosExpediente;
use Modules\ExpedientesElectronicos\Models\ExpedienteHasMetadato;
use Modules\ExpedientesElectronicos\Models\ExpedienteLeido;

use Illuminate\Support\Str;
use Modules\ExpedientesElectronicos\Models\ExpedienteAnotacion;
use Modules\ExpedientesElectronicos\Models\PermisoUsuariosExpediente;
use stdClass;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ExpedienteController extends AppBaseController {

    /** @var  ExpedienteRepository */
    private $expedienteRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ExpedienteRepository $expedienteRepo) {
        $this->expedienteRepository = $expedienteRepo;
    }

    /**
     * Muestra la vista para el CRUD de Expediente.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('expedienteselectronicos::expedientes.index');
    }

    /**
     * Muestra la vista principal para validar los documentos del expediente electronicos
     *
     * @param Request $request
     * @return void
     */
    public function indexValidarDocumento(Request $request)
    {
        return view('expedienteselectronicos::expedientes.index_validar_documento');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */

    public function all(Request $request)
    {
        // Decodifica los campos filtrados
        $filtros = str_replace(["'{","}'"], ["{","}"], base64_decode($request["f"]));

        $filtros_metadatos = "";
        $rol_filtro_consulta = "";

        // Usuario actual en sesión
        $user = Auth::user();
        $user_id = $user->id;
        $user_id_dependencia = $user->id_dependencia;

        // Valida si en los filtros realizados viene el filtro de _obj_llave_valor_
        if(stripos($filtros, "_obj_llave_valor_") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de _obj_llave_valor_ en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, '_obj_llave_valor_') !== false;
            }))[0];

            // Se extrae el valor del filtro _obj_llave_valor_
            $filtros_metadatos = strtolower(explode("= ", $filtro[$posicion])[1]);
            // Decodifica los filtros de metadatos, ya que vienen en json
            $filtros_metadatos = json_decode($filtros_metadatos, true);
            // Se elimina el filtro de _obj_llave_valor_ del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }

        // Valida si en los filtros realizados viene el filtro de rol_consulta_expedientes
        if(stripos($filtros, "rol_consulta_expedientes") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de rol_consulta_expedientes en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, 'rol_consulta_expedientes') !== false;
            }))[0];
            // Se extrae el valor del filtro rol_consulta_expedientes
            $rol_filtro_consulta = strtolower(explode("%", $filtro[$posicion])[1]);
            // Guardar el filtro de rol_consulta_expedientes seleccionado por el usuario
            session(['rol_consulta_expedientes' => $rol_filtro_consulta]);
            // Se elimina el filtro de rol_consulta_expedientes del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }

        if(!empty($request["tablero"])){
            return $this->obtenerDatostablero($request->all(), $user);
        }

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros}

        // Valida si existen las variables del paginado y si esta filtrando
        if ((isset($request["f"]) && $request["f"] != "") || isset($request["pi"])) {
            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
            $expedientes = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials", "eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes", "expedienteLeido", "expedienteAnotaciones", "anotacionesPendientes"])
            ->when($rol_filtro_consulta == "operador" && Auth::user()->hasRole('Operador Expedientes Electrónicos'), function($query) use($user_id_dependencia) {
                $query->where("dependencias_id", $user_id_dependencia);
            })
            ->when($rol_filtro_consulta == "consulta_expedientes" && Auth::user()->hasRole('Consulta Expedientes Electrónicos'), function($query) use($user_id_dependencia) {
                $query->whereNot("estado", "Pendiente de firma");
            })
            ->when($rol_filtro_consulta == "responsable_expedientes", function($query) use($user_id) {
                $query->where("id_responsable", $user_id);
            })
            ->when($rol_filtro_consulta == "funcionario", function($query) use($user) {
                $query->where(function($q) use ($user) {
                    $q->whereHas('eePermisoUsuariosExpedientes', function($query) use ($user) {
                        // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                        $query->where(function($subQuery) use ($user) {
                            // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                            $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                        });
                        $query->orWhere(function($subQuery) use ($user) {
                            // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                            $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                        });
                    })
                    ->orWhere("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente')
                    ->orWhere("permiso_general_expediente", 'Todas las dependencias están autorizadas para ver información y documentos del expediente');
                });
                $query->where(function($q) use ($user) {
                    $q->where("estado", "Abierto");
                    $q->orWhere("estado", "Cerrado");
                });
            })
            ->when($filtros, function($query) use($filtros) {
                $query->whereRaw($filtros);
            })
            ->when($filtros_metadatos, function($query) use($filtros_metadatos) {
                $query->whereHas("expedienteHasMetadatos", function($m) use($filtros_metadatos) {
                    $m->where(function ($n) use ($filtros_metadatos) {
                        // Recorre los filtros
                        foreach ($filtros_metadatos as $key => $valor_metatado) {
                            $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                // Separa el nombre del filtro(llave del array) para obtener el id del metadato
                                $de_metadatos_id = explode("_", $key);
                                $de_metadatos_id = end($de_metadatos_id);
                                $q->where("ee_metadatos_id", $de_metadatos_id); // Id del metadato en su nombre
                                $q->where("valor", "LIKE", "%".$valor_metatado."%"); // Valor del metadato ingresado en el filtro
                            });
                        }
                    });
                });
            })
            ->latest("updated_at")
            ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página

            $count_expedientes = $expedientes->total(); // Total de expedientes encontrados
            $expedientes = $expedientes->toArray()["data"]; // Consulta los expedientes según los filtros
        } else {
            $expedientes = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials", "eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes"])
            ->where(function($q) use ($user) {
                $q->whereHas('eePermisoUsuariosExpedientes', function($query) use ($user) {
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $query->where(function($subQuery) use ($user) {
                        // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                        $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                    });
                    $query->orWhere(function($subQuery) use ($user) {
                        // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                        $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                    });
                })
                ->orWhere("permiso_general_expediente", 'Todas las dependencias están autorizadas para ver información y documentos del expediente')
                ->orWhere("id_responsable", $user->id) // Se agrega la condición por responsable
                ->orWhere("users_id", $user->id); // Se agrega la condición por creador

            })->latest()->get();
            // Contar el número total de registros de la consulta realizada según el paginado seleccionado
            $count_expedientes = Expediente::count();
        }

        return $this->sendResponseAvanzado($expedientes, trans('data_obtained_successfully'), null, ["total_registros" => $count_expedientes]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateExpedienteRequest $request
     *
     * @return Response
     */
    public function store(CreateExpedienteRequest $request) {
        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            if(Auth::user()->hasRole('Operador Expedientes Electrónicos')){
                $input["estado"] = "Pendiente de firma";
                $observacion_historial_expediente = "Expediente creado y enviado a firma del responsable por parte del operador";
                $input["id_usuario_enviador"] = Auth::user()->id;
                $input["nombre_usuario_enviador"] = Auth::user()->fullname;
                $roles_usuario = Auth::user()->roles->pluck('name')->toArray();
                $roles_expedientes = collect($roles_usuario)
                    ->filter(function ($role) {
                        return str_contains(strtolower($role), 'expediente');
                    })
                    ->implode(' - ');

                $input["tipo_usuario_enviador"] = $roles_expedientes;
            } else if(empty($input["estado"])){
                $input["estado"] = "Abierto";
                $observacion_historial_expediente = "Expediente creado";
            }
            // Vigencia del registro = Año actual
            $input["vigencia"] = date("Y");
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;
            // nombre del usuario que crea el registro
            $input["user_name"] = Auth::user()->fullname;
            // Valida de que se seleccionara el responsable del expediente
            if(!empty($input["id_responsable"])) {
                $nombre_responsable = User::find($input["id_responsable"]);
                $input["nombre_responsable"] = $nombre_responsable["fullname"];

                if($nombre_responsable->hasRole('Operadores')){
                    $input["operador"] = $input["id_responsable"];
                    $input["operador_name"] = $input["nombre_responsable"];
                }
            } else {
                return $this->sendSuccess('<strong>Responsable no seleccionado.</strong>'. '<br>' . "Por favor seleccione un responsable.", 'warning');
            }
            if(empty($input["dependencias_id"])){
                $input["dependencias_id"] = $input["classification_production_office"];
            }
            $totalExpedientes = Expediente::count();
            $input["id_expediente"] = $totalExpedientes+1;

            $dependenciaInfo = Dependency::where("id", $input["dependencias_id"])->first();
            $input["nombre_dependencia"] = $dependenciaInfo->nombre;

            $codigo_dependencia = $dependenciaInfo->codigo;
            $datos_consecutivo = $this->generarConsecutivo($input, $codigo_dependencia);
            $input["consecutivo"] = $datos_consecutivo["consecutivo"];
            $input["consecutivo_order"] = $datos_consecutivo["consecutivo_order"];

            if(empty($input["consecutivo"])) {
                return $this->sendError('Error al generar el consecutivo. Por favor, intente nuevamente.');
            }

            // Si el estado del expediente es 'Abierto', genera el hash de la firma del usuario en el documento de la carátula de apertura
            if($input["estado"] == "Abierto"){
                $publicIp = $this->detectIP();
                $input["ip_apertura"] = $publicIp;
                $input["id_firma_caratula_apertura"] = hash('sha256', date("Y-m-d H:i:s") . ($input["consecutivo"] ?? $input["users_id"]));
            }

            // Inserta el registro en la base de datos
            $expediente = $this->expedienteRepository->create($input);
            // Valida si tiene metadatos registrados
            // guardar_metadatos: Si existe este atributo, es porque la petición de guardar expediente viene de un módulo de Intraweb, por lo tanto no debe guardar metadatos
            if (!empty($input['metadatos']) && empty($input['guardar_metadatos'])) {
                // Borra todos los registros de metadatos para volver a insertarlos
                ExpedienteHasMetadato::where('ee_expediente_id', $expediente->id)->delete();
                // Recorre los metadatos
                foreach ($input['metadatos'] as $llave_metadato => $valor_metadato) {

                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["ee_metadatos_id"] = $llave_metadato;
                    $registroMetadatoArray["ee_expediente_id"] = $expediente->id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    ExpedienteHasMetadato::create($registroMetadatoArray);
                }
            } else {
                // Elimina todos los registros de metadatos relacionados al documento
                ExpedienteHasMetadato::where('ee_expediente_id', $expediente->id)->delete();
            }

            $permiso_usuarios_expedientes = $input['ee_permiso_usuarios_expedientes'] ?? null;

            // Condición para validar si existe algún registro de permiso de usuarios externos
            if (!empty($permiso_usuarios_expedientes)) {
                // Almacena la información de los usuarios externos para enviarle notificación
                $notificacion_usuarios = [];
                // Ciclo para recorrer todos los registros de usuarios externos
                foreach($permiso_usuarios_expedientes as $option) {
                    // Decodifica la cadena JSON de usuarios externos
                    $informacion_usuario = json_decode($option, true);
                    $notificacion_usuarios[] = $informacion_usuario;
                    if($informacion_usuario["tipo_usuario"] == "Interno") {
                        // Se crean el permiso para el usuario interno
                        PermisoUsuariosExpediente::create([
                            'nombre' => $informacion_usuario["nombre"] ?? null,
                            'correo' => $informacion_usuario["tipo"] == "Usuario" ? $informacion_usuario["correo"] : null,
                            'dependencia_usuario_id' => $informacion_usuario["dependencia_usuario_id"],
                            'tipo' => $informacion_usuario["tipo"] ?? null,
                            'tipo_usuario' => "Interno",
                            'permiso' => $informacion_usuario["permiso"],
                            'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                            'ee_expedientes_id' => $expediente->id
                        ]);
                    } else {
                        $informacion_usuario["pin_acceso"] = $this->generateAlphaNumericPin();;
                        // Se crea el permiso para el usuario externo
                        PermisoUsuariosExpediente::create([
                            'nombre' => $informacion_usuario["nombre"],
                            'correo' => $informacion_usuario["correo"],
                            'pin_acceso' => $informacion_usuario["pin_acceso"],
                            'tipo' => "Usuario",
                            'tipo_usuario' => "Externo",
                            'permiso' => $informacion_usuario["permiso"],
                            'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                            'ee_expedientes_id' => $expediente->id
                        ]);
                    }
                }

                // Si hay registros nuevos, envía la notificación correspondiente
                self::enviar_notificacion_permisos_usuarios($notificacion_usuarios, $expediente, "nuevos");
            }

            //Guarda el historial del registro
            $input["ee_expediente_id"] = $expediente->id;
            $input["detalle_modificacion"] = $observacion_historial_expediente;
            $historial = $input;

            ExpedienteHistorial::create($historial);
            //Manda las relaciones

            $expediente->users;
            $expediente->serieClasificacionDocumental;
            $expediente->subserieClasificacionDocumental;
            $expediente->dependencias;
            $expediente->oficinaProductoraClasificacionDocumental;
            $expediente->eeExpedienteHistorials;
            $expediente->eePermisoUsuariosExpedientes;
            $expediente->expedienteHasMetadatos;

            // Si el estado del expediente es 'Abierto', invoca la función 'crearCaratulaAbierto' para crear la carátula de apertura
            if($input["estado"] == "Abierto" || $input["estado"] == "Pendiente de firma") {
                // Invoca la función para construir el documento de carátula de apertura del expediente
                $resultadoCaratula = $this->crearCaratulaAbierto($expediente);

                if($resultadoCaratula == 'Fallo'){
                    return $this->sendSuccess(config('constants.support_message') . "<br>Ocurrió un error al crear la carátula.", 'info');
                }
            }

            // Valida de que se seleccionara el responsable del expediente para enviar la notificación de correo
            if(!empty($input["id_responsable"])) {
                $asunto = json_decode('{"subject": "Solicitud de firma del expediente  ' . $expediente->consecutivo . '"}');
                $email = User::where('id', $expediente->id_responsable)->first()->email;
                $notificacion["id"] = $expediente->id;
                $notificacion["consecutivo"] = $expediente->consecutivo;
                $notificacion["state"] = $expediente->estado;
                $notificacion["nombre_funcionario"] = $expediente->nombre_responsable;
                $notificacion['id_encrypted'] = base64_encode($expediente->id);
                $notificacion['mensaje'] = "<p>Le informamos que el expediente con consecutivo <strong>{$expediente->consecutivo}</strong> ha sido enviado por el operador <strong>{$expediente->nombre_usuario_enviador}</strong> para su firma y aprobación, con la siguiente observación:
                <br><br>
                " . ($expediente->observacion ?? 'Sin observación') . ".
                <br><br>
                Por favor, ingrese al sistema de Intraweb para firmar y aprobar el expediente.</p";
                try {
                    SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email, 'Expedientes electrónicos');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($external['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($expediente->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function guardarCaratulaExpediente($expediente) {

    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateExpedienteRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        /** @var Expediente $expediente */
        $expediente = $this->expedienteRepository->find($id);

        if (empty($expediente)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $input = $request->all();
        $observacion_detallada = $input["detalle_modificacion"];


        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if(Auth::user()->hasRole('Operador Expedientes Electrónicos') && $input["estado"] == "Devuelto para modificaciones"){
                $input["estado"] = "Pendiente de firma";
                $observacion_historial_expediente = "Expediente editado y enviado a firma del responsable por parte del operador";
            } else {
                $observacion_historial_expediente = "Expediente editado";
            }

            if(empty($input["dependencias_id"])){
                $input["dependencias_id"] = $input["classification_production_office"];
            }
            // Valida de que se seleccionara el responsable del expediente
            if(!empty($input["id_responsable"])) {
                $nombre_responsable = User::find($input["id_responsable"]);
                $input["nombre_responsable"] = $nombre_responsable["fullname"];

                if($nombre_responsable->hasRole('Operadores')){
                    $input["operador"] = $input["id_responsable"];
                    $input["operador_name"] = $input["nombre_responsable"];
                }
            } else {
                return $this->sendSuccess('<strong>Responsable no seleccionado.</strong>'. '<br>' . "Por favor seleccione un responsable.", 'warning');
            }

            $dependenciaInfo = Dependency::where("id", $input["dependencias_id"])->first();
            $input["nombre_dependencia"] = $dependenciaInfo->nombre;

            // Si el estado del expediente es 'Cerrado', genera el hash de la firma del usuario en el documento de la carátula de cierre
            if($input["estado"] == "Cerrado"){
                $publicIp = $this->detectIP();
                $input["ip_cierre"] = $publicIp;
                $input["id_firma_caratula_cierre"] = hash('sha256', date("Y-m-d H:i:s") . ($input["consecutivo"] ?? $input["users_id"]));
            }

            // Valida si tiene metadatos registrados
            if (!empty($input['metadatos'])) {
                // Borra todos los registros de metadatos para volver a insertarlos
                ExpedienteHasMetadato::where('ee_expediente_id', $id)->delete();
                // Recorre los metadatos
                foreach ($input['metadatos'] as $llave_metadato => $valor_metadato) {

                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["ee_metadatos_id"] = $llave_metadato;
                    $registroMetadatoArray["ee_expediente_id"] = $id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    ExpedienteHasMetadato::create($registroMetadatoArray);
                }
            } else {
                // Elimina todos los registros de metadatos relacionados al documento
                ExpedienteHasMetadato::where('ee_expediente_id', $id)->delete();
            }

            // Guarda parcialmente el id del usuario responsable
            $id_usuario_responsable_anterior = $expediente->id_responsable;
            $nombre_usuario_responsable_anterior = $expediente->nombre_responsable;

            // Actualiza el registro
            $expediente = $this->expedienteRepository->update($input, $id);

            $permiso_usuarios_expedientes = $input['ee_permiso_usuarios_expedientes'] ?? null;

            // Condición para validar si existe algún registro de permiso de usuarios externos
            if (!empty($permiso_usuarios_expedientes)) {
                // Obtiene la lista de permisos actuales antes de la actualización del expediente
                $registros_actuales_antes_update = PermisoUsuariosExpediente::where("ee_expedientes_id", $expediente->id)->get()->toArray();
                $registros_a_insertar = array_map(fn($item) => json_decode($item, true), $permiso_usuarios_expedientes);
                // Ciclo para recorrer todos los registros de usuarios externos
                foreach($registros_a_insertar as $i => $option) {
                    // Decodifica la cadena JSON de usuarios externos
                    $informacion_usuario = $option;
                    
                    if($informacion_usuario["tipo_usuario"] == "Interno") {
                        $existe = collect($registros_actuales_antes_update)->contains(function ($item) use ($informacion_usuario) {
                            return $item['dependencia_usuario_id'] === $informacion_usuario['dependencia_usuario_id']
                                && $item['tipo'] === $informacion_usuario['tipo'];
                        });
                        if($existe) {
                            // Se crean la cantidad de registros ingresados por el usuario
                            PermisoUsuariosExpediente::where('dependencia_usuario_id', $informacion_usuario["dependencia_usuario_id"])->where('tipo', $informacion_usuario["tipo"])->where('ee_expedientes_id', $expediente->id)
                            ->update([
                                'nombre' => $informacion_usuario["nombre"],
                                'permiso' => $informacion_usuario["permiso"],
                                'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                            ]);
                        } else {
                            // Se crean el permiso para el usuario interno
                            PermisoUsuariosExpediente::create([
                                'nombre' => $informacion_usuario["nombre"] ?? null,
                                'dependencia_usuario_id' => $informacion_usuario["dependencia_usuario_id"],
                                'tipo' => $informacion_usuario["tipo"] ?? null,
                                'tipo_usuario' => "Interno",
                                'correo' => $informacion_usuario["tipo"] == "Usuario" ? $informacion_usuario["correo"] : null,
                                'permiso' => $informacion_usuario["permiso"],
                                'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                                'ee_expedientes_id' => $expediente->id
                            ]);
                        }
                    } else {
                        // Valida si el registro que está iterando, ya existe en los registros actuales relacionados al expediente
                        $existe = collect($registros_actuales_antes_update)->contains('correo', $informacion_usuario["correo"]);
                        if($existe) {
                            // Se crean la cantidad de registros ingresados por el usuario
                            PermisoUsuariosExpediente::where('correo', $informacion_usuario["correo"])->where('ee_expedientes_id', $expediente->id)
                            ->update([
                                'nombre' => $informacion_usuario["nombre"],
                                'permiso' => $informacion_usuario["permiso"],
                                'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                            ]);
                        } else {
                            $informacion_usuario["pin_acceso"] = $this->generateAlphaNumericPin();
                            $registros_a_insertar[$i]['pin_acceso'] = $informacion_usuario["pin_acceso"];
                            // Se crean la cantidad de registros ingresados por el usuario
                            PermisoUsuariosExpediente::create([
                                'nombre' => $informacion_usuario["nombre"],
                                'correo' => $informacion_usuario["correo"],
                                'pin_acceso' => $informacion_usuario["pin_acceso"],
                                'tipo' => "Usuario",
                                'tipo_usuario' => "Externo",
                                'permiso' => $informacion_usuario["permiso"],
                                'limitar_descarga_documentos' => isset($informacion_usuario["limitar_descarga_documentos"]) && ($this->toBoolean($informacion_usuario["limitar_descarga_documentos"]) || $informacion_usuario["limitar_descarga_documentos"] == 1) ? 1 : 0,
                                'ee_expedientes_id' => $expediente->id
                            ]);
                        }
                    }
                }

                $registros_eliminados = array_filter($registros_actuales_antes_update, function ($registroActual) use ($registros_a_insertar) {
                    foreach ($registros_a_insertar as $nuevoRegistro) {
                        // Comparación de externos por correo
                        if (
                            isset($registroActual['correo'], $nuevoRegistro['correo']) &&
                            $registroActual['correo'] === $nuevoRegistro['correo']
                        ) {
                            return false; // ya existe, no se elimina
                        }

                        // Comparación de internos por dependencia_usuario_id
                        if (
                            isset($registroActual['dependencia_usuario_id'], $nuevoRegistro['dependencia_usuario_id']) &&
                            $registroActual['dependencia_usuario_id'] === $nuevoRegistro['dependencia_usuario_id']
                        ) {
                            return false; // ya existe, no se elimina
                        }
                    }

                    return true; // no se encontró ningún match, se elimina
                });
                
                $idsEliminados = array_column($registros_eliminados, 'id');
                // Elimina los registros previos de permisos de usuarios externos
                PermisoUsuariosExpediente::whereIn("id", $idsEliminados)->delete();
                // Si hay registros eliminados, envía la notificación correspondiente
                count($registros_eliminados) > 0 && self::enviar_notificacion_permisos_usuarios($registros_eliminados, $expediente, "eliminados");

                // Determina los registros nuevos comparando los permisos después de la actualización con los anteriores
                $registros_nuevos = array_udiff(
                    $registros_a_insertar,
                    $registros_actuales_antes_update,
                    function ($a, $b) {
                        // Si ambos son externos (tienen correo)
                        if (isset($a['correo']) && isset($b['correo'])) {
                            return $a['correo'] <=> $b['correo'];
                        }

                        // Si ambos son internos (tienen dependencia_usuario_id)
                        if (isset($a['dependencia_usuario_id']) && isset($b['dependencia_usuario_id'])) {
                            return $a['dependencia_usuario_id'] <=> $b['dependencia_usuario_id'];
                        }

                        // Si son de tipos distintos, los consideramos diferentes
                        return 1;
                    }
                );

                // Si hay registros nuevos, envía la notificación correspondiente
                count($registros_nuevos) > 0 && self::enviar_notificacion_permisos_usuarios($registros_nuevos, $expediente, "nuevos");
            } else {
                // Obtiene la lista de permisos actuales antes de la actualización del expediente
                $registros_eliminados = PermisoUsuariosExpediente::where("ee_expedientes_id", $expediente->id)->get()->toArray();
                // Si hay registros eliminados, envía la notificación correspondiente
                count($registros_eliminados) > 0 && self::enviar_notificacion_permisos_usuarios($registros_eliminados, $expediente, "eliminados");
                // Elimina los registros previos de permisos de usuarios externos
                PermisoUsuariosExpediente::where("ee_expedientes_id", $expediente->id)->delete();
            }
            //Manda las relaciones
            $expediente->users;
            $expediente->serieClasificacionDocumental;
            $expediente->subserieClasificacionDocumental;
            $expediente->dependencias;
            $expediente->oficinaProductoraClasificacionDocumental;
            $expediente->eeExpedienteHistorials;
            $expediente->eePermisoUsuariosExpedientes;
            $expediente->expedienteHasMetadatos;

            $input["ee_expediente_id"] = $expediente->id;
            $input["observacion"] = $observacion_detallada;
            $input["detalle_modificacion"] = $observacion_historial_expediente;
            $historial = $input;
            // Datos del usuario en sesión
            $usuario = Auth::user();
            $historial["users_id"] = $usuario->id;
            $historial["user_name"] = $usuario->fullname;
            ExpedienteHistorial::create($historial);


            // Si el estado del expediente es 'Cerrado', invoca la función 'crearCaratulaCerrado' para crear la carátula de cierre
            if($input["estado"] == "Cerrado") {
                $expediente_caratula = $expediente;
                $expediente_caratula->eeDocumentosExpedientes;
                // Invoca la función para construir el documento de carátula de cierre del expediente
                $this->crearCaratulaCerrado($expediente_caratula);
            }

            // Valida de que se seleccionara el responsable del expediente para enviar la notificación de correo
            if(!empty($input["id_responsable"]) && session('rol_consulta_expedientes') == "operador") {
                $asunto = json_decode('{"subject": "Solicitud de firma del expediente ' . $expediente->consecutivo . '"}');
                $email = User::where('id', $expediente->id_responsable)->first()->email;
                $notificacion["id"] = $expediente->id;
                $notificacion["consecutivo"] = $expediente->consecutivo;
                $notificacion["state"] = $expediente->estado;
                $notificacion["nombre_funcionario"] = $expediente->nombre_responsable;
                $notificacion['id_encrypted'] = base64_encode($expediente->id);
                $notificacion['mensaje'] = "<p>Le informamos que el expediente con consecutivo <strong>{$expediente->consecutivo}</strong> ha sido enviado por el operador <strong>{$expediente->nombre_usuario_enviador}</strong> para su firma y aprobación, con la siguiente observación:
                <br><br>
                " . ($expediente->observacion ?? 'Sin observación') . ".
                <br><br>
                Por favor, ingrese al sistema de Intraweb para firmar y aprobar el expediente.</p";
                try {
                    SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email, 'Expedientes electrónicos');
                    // Valida si el responsable del expediente fue modificado, de ser verdadero, se le envía notificación al responsable anterior, de su revocación de rol
                    if($id_usuario_responsable_anterior != $expediente->id_responsable) {
                        $asunto = json_decode('{"subject": "[Intraweb] Se ha revocado su responsabilidad en el expediente ' . $expediente->consecutivo . '"}');
                        $email = User::where('id', $id_usuario_responsable_anterior)->first()->email;
                        $notificacion["nombre_funcionario"] = $nombre_usuario_responsable_anterior;
                        $notificacion['mensaje'] = "<p>Le informamos que ya no es responsable del expediente con consecutivo <strong>{$expediente->consecutivo}</strong>. A partir de ahora, no será necesario que realice la firma ni la aprobación del mismo
                            <br><br>
                            <span style='font-size: 15x;'>Si considera que esto es un error o requiere más información, por favor comuníquese con el equipo de soporte.</span></p";
                        SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email, 'Expedientes electrónicos');
                    }
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($external['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($expediente->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Expediente del almacenamiento
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

        /** @var Expediente $expediente */
        $expediente = $this->expedienteRepository->find($id);

        if (empty($expediente)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $expediente->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
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
        $fileName = time().'-'.trans('expedientes').'.'.$fileType;

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
     * Crea un documento pdf Cuando el estado del expediente sea abierto
     *
     * @author Manuel Marin - Jul. 07 - 2024
     * @version 1.0.0
     *
     * @param input $request
     *
     * @return Response
     */
    private function crearCaratulaAbierto($input)
    {
        try {
            // Convertir el input a un array
            $expediente = $input->toArray();
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $expediente["id"])->count();
            $numberDigits = 2;
            $expedienteNumberDigits = 3;  // Número de dígitos para ee_expediente_id
            // valida el numero de order obtenido de la consulta
            if(!$total_documentos){
                $orden = 0;
            }
            $orden = (int)$total_documentos + 1;
            $length = strlen($orden);
            // agrega ceros al consecutivo si aplica
            if($numberDigits > $length) {
                $digs = $numberDigits - $length;
                $norden = str_repeat("0", $digs) . $orden;
            } else {
                $norden = $orden;
            }
            // Formatear ee_expediente_id
            $ee_expediente_id = (int)$expediente["id_expediente"];
            $expedienteLength = strlen($ee_expediente_id);
            if($expedienteNumberDigits > $expedienteLength) {
                $expedienteDigs = $expedienteNumberDigits - $expedienteLength;
                $formattedExpedienteId = str_repeat("0", $expedienteDigs) . $ee_expediente_id;
            } else {
                $formattedExpedienteId = $ee_expediente_id;
            }
            $expediente["consecutivo_doc"] = $expediente["vigencia"] . $formattedExpedienteId . $norden;
            // Obtener la información general del sitio
            $info_sitio = ConfigurationGeneral::get()->first();
            // Agregar la información del sitio al expediente
            $expediente["logo"] = $info_sitio["logo"];
            $expediente["nombre_entidad"] = $info_sitio["nombre_entidad"];
            $expediente["origen_creacion"] = "Nuevo documento";
            // Modulo origen de creación del documento que se va asociar al expediente
            $expediente["modulo_intraweb"] = "Expediente electrónico";
            $expediente["nombre_documento"] = "Carátula de apertura";

            $info_responsable = User::find($expediente["id_responsable"]);
            $expediente["firma_responsable"] = $info_responsable->url_digital_signature;

            // Obtener la fecha actual
            $current_date = new DateTime();
            // Crear el formateador de fechas
            $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'GMT');
            // Formatear la fecha
            $formatted_date = $formatter->format($current_date);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_documento_extensa"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);
            // Asignar la fecha actual en formato Y-m-d H:i:s al expediente
            $expediente["fecha_documento"] = date("Y-m-d H:i:s");

            // Fecha 'fecha_inicio_expediente'
            $fecha_inicio_expediente = new DateTime($expediente["fecha_inicio_expediente"]);
            // Formatear la fecha
            $formatted_date = $formatter->format($fecha_inicio_expediente);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_inicio_expediente"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);

            // Asignar el ID del expediente electrónico si no está presente
            $expediente["ee_expediente_id"] = $expediente["ee_expediente_id"] ?? $expediente["id"];
            // Contar el total de documentos en el expediente y asignar el orden del documento actual
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $expediente["ee_expediente_id"])->count();
            $expediente["orden_documento"] = $total_documentos+1;
            // Especifica la ruta donde deseas guardar el archivo PDF
            $carpeta_expedientes = 'app/public/container/expedientes_electronicos_' . date("Y");

            // Crear la carpeta si no existe
            if(!file_exists($carpeta_expedientes))
                @mkdir(storage_path($carpeta_expedientes), 0755, true);

            // Definir la ruta del archivo adjunto
            $ruta_adjunto = $carpeta_expedientes.'/CaratulaApertura'.$expediente["ee_expediente_id"].'.pdf';
            $path = storage_path($ruta_adjunto);
            // Generar el PDF con la vista correspondiente y los datos del expediente
            $filePDF = PDF::loadView('expedienteselectronicos::expedientes.exports.caratula_abierto', ['data' => $expediente]);
            // Guarda el archivo PDF en la ubicación especificada
            $filePDF->save($path);
            // nombre del expediente que es de tipo caratula
            $expediente["nombre_expediente"] = "Caratula";
            // Asignar la ruta del adjunto al expediente
            $expediente['adjunto'] = substr($ruta_adjunto, 11);
            $expediente['estado_doc'] = "Asociado";
            $expediente['consecutivo'] = $expediente["consecutivo_doc"];
            // Crear el registro del documento en el expediente
            $documentoCaratula = DocumentosExpediente::create($expediente);
            $expediente["ee_documentos_expediente_id"] = $documentoCaratula->id;
            $expediente["accion"] = "Crear";
            $expediente["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($expediente);

            return "Exito";

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return "Fallo";

            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return "Fallo";

            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Crea la carátula de cierre cuando el estado del expediente sea cerrado
     *
     * @author Seven Soluciones Informáticas S.A.S - Jul. 16 - 2024
     * @version 1.0.0
     *
     * @param input $request
     *
     * @return Response
     */
    private function crearCaratulaCerrado($input)
    {
        try {
            // Convertir el input a un array
            $expediente = $input->toArray();
            // Obtener la información general del sitio
            $info_sitio = ConfigurationGeneral::get()->first();
            // Agregar la información del sitio al expediente
            $expediente["logo"] = $info_sitio["logo"];
            $expediente["nombre_entidad"] = $info_sitio["nombre_entidad"];
            $expediente["origen_creacion"] = "Nuevo documento";
            // Modulo origen de creación del documento que se va asociar al expediente
            $expediente["modulo_intraweb"] = "Expediente electrónico";
            $expediente["nombre_documento"] = "Carátula de cierre";

            $info_responsable = User::find($expediente["id_responsable"]);
            $expediente["firma_responsable"] = $info_responsable->url_digital_signature;

            // Obtener la fecha actual
            $current_date = new DateTime();
            // Crear el formateador de fechas
            $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'GMT');
            // Formatear la fecha
            $formatted_date = $formatter->format($current_date);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_documento_extensa"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);
            // Asignar la fecha actual en formato Y-m-d H:i:s al expediente
            $expediente["fecha_documento"] = date("Y-m-d H:i:s");


            // Fecha 'fecha_inicio_expediente'
            $fecha_inicio_expediente = new DateTime($expediente["fecha_inicio_expediente"]);
            // Formatear la fecha
            $formatted_date = $formatter->format($fecha_inicio_expediente);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_inicio_expediente"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);

            // Asignar el ID del expediente electrónico si no está presente
            $expediente["ee_expediente_id"] = $expediente["ee_expediente_id"] ?? $expediente["id"];

            // Contar el total de documentos en el expediente y asignar el orden del documento actual
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $expediente["id"])->count();
            $expediente["orden_documento"] = $total_documentos+1;
            $numberDigits = 2;
            $expedienteNumberDigits = 3;  // Número de dígitos para ee_expediente_id
            // valida el numero de order obtenido de la consulta
            if(!$total_documentos){
                $orden = 0;
            }
            $orden = (int)$total_documentos + 1;
            $length = strlen($orden);
            // agrega ceros al consecutivo si aplica
            if($numberDigits > $length) {
                $digs = $numberDigits - $length;
                $norden = str_repeat("0", $digs) . $orden;
            } else {
                $norden = $orden;
            }
            // Formatear ee_expediente_id
            $ee_expediente_id = (int)$expediente["id_expediente"];
            $expedienteLength = strlen($ee_expediente_id);
            if($expedienteNumberDigits > $expedienteLength) {
                $expedienteDigs = $expedienteNumberDigits - $expedienteLength;
                $formattedExpedienteId = str_repeat("0", $expedienteDigs) . $ee_expediente_id;
            } else {
                $formattedExpedienteId = $ee_expediente_id;
            }
            $expediente["consecutivo_doc"] = $expediente["vigencia"] . $formattedExpedienteId . $norden;

            // Especifica la ruta donde deseas guardar el archivo PDF
            $carpeta_expedientes = 'app/public/container/expedientes_electronicos_' . date("Y");
            // Crear la carpeta si no existe
            if(!file_exists($carpeta_expedientes))
                @mkdir(storage_path($carpeta_expedientes), 0755, true);
            // Definir la ruta del archivo adjunto
            $ruta_adjunto = $carpeta_expedientes.'/CaratulaCierre'.$expediente["ee_expediente_id"].'.pdf';
            // nombre del expediente que es de tipo caratula
            $expediente["nombre_expediente"] = "Caratula";
            // Asignar la ruta del adjunto al expediente
            $expediente['adjunto'] = substr($ruta_adjunto, 11);
            $expediente['estado_doc'] = "Asociado";
            $expediente['consecutivo'] = $expediente["consecutivo_doc"];
            $expediente['codigo_acceso_caratula_cierre'] = Str::random(10);
            $codigo_acceso_caratula = $expediente['codigo_acceso_caratula_cierre'];

            // Crear el registro del documento en el expediente
            $caratula_cierre = DocumentosExpediente::create($expediente);
            $expediente["ee_documentos_expediente_id"] = $caratula_cierre->id;
            $expediente["accion"] = "Cerrar expediente";
            $expediente["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($expediente);

            // Agrega el registro de la carátula de cierre para mostrar en el listado de documentos del expediente del reporte
            array_push($expediente["ee_documentos_expedientes"], $caratula_cierre->toArray());

            $path = storage_path($ruta_adjunto);
            // Generar el PDF con la vista correspondiente y los datos del expediente
            $filePDF = PDF::loadView('expedienteselectronicos::expedientes.exports.caratula_cerrado', ['data' => $expediente]);
            // Guarda el archivo PDF en la ubicación especificada
            $filePDF->save($path);
            // Ruta relativa del documento
            $ruta_adjunto = substr($ruta_adjunto, 11);
            // Hash de integridad de la carátula de apertura
            $hash_caratula_cierre = hash_file('sha256', 'storage/' . $ruta_adjunto);

            $expediente = $this->expedienteRepository->update(['hash_caratula_cierre' =>  $hash_caratula_cierre, 'codigo_acceso_caratula_cierre' =>  $codigo_acceso_caratula], $expediente["id"]);
            $historial = $expediente->toArray();

            $historial["ee_expediente_id"] = $historial["id"];
            ExpedienteHistorial::create($historial);

            return "Exito";
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Obtiene los registros
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerExpediente(Request $request) {
        $query = $request->input('query');
        // Usuario actual en sesión
        $usuario = Auth::user();
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $expediente = Expediente::with(['eeDocumentosExpedientes' => function($query) {
            $query->where('orden_documento', 1);
        }])
        ->where('consecutivo', 'like', '%' . $query . '%')
        ->where(function($q) use ($usuario) {
            $q->whereHas('eePermisoUsuariosExpedientes', function($query) use ($usuario) {
                // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                $query->where(function($subQuery) use ($usuario) {
                    // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                    $subQuery->where('dependencia_usuario_id', $usuario->id)->where('tipo', 'Usuario');
                });
                $query->orWhere(function($subQuery) use ($usuario) {
                    // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                    $subQuery->where('dependencia_usuario_id', $usuario->id_dependencia)->where('tipo', 'Dependencia');
                });
            })
            ->orWhere("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente');
        })
        ->get()->toArray();

        return $this->sendResponse($expediente, trans('data_obtained_successfully'));
    }

    /**
     * Calcula el consecutivo del expediente
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    private function generarConsecutivo($documento, $codigo_dependencia) {
        try{
            $formatoConsecutivoValores = [];
            $formatoConsecutivoValores["prefijo_dependencia"] = $codigo_dependencia;
            $formatoConsecutivoValores["serie_documental"] = $documento["serie_clasificacion_documental"]["no_serieosubserie"] ?? '';
            $formatoConsecutivoValores["subserie_documental"] = $documento["subserie_clasificacion_documental"]["no_subserie"] ?? '';
            $formatoConsecutivoValores["separador_consecutivo"] = '';
            $formatoConsecutivoValores["vigencia_actual"] = date("Y");

            $formatoConsecutivo = $formatoConsecutivoValores["subserie_documental"] ? "vigencia_actual, prefijo_dependencia, serie_documental, subserie_documental, consecutivo_documento" : "vigencia_actual, prefijo_dependencia, serie_documental, consecutivo_documento";
            // Calcular consecutivo
            $numberDigits = 3;
            // Reemplaza valores en el formato del consecutivo
            $formatoConsecutivo = str_replace("prefijo_dependencia", $formatoConsecutivoValores["prefijo_dependencia"], $formatoConsecutivo);
            $formatoConsecutivo = str_replace("subserie_documental", $formatoConsecutivoValores["subserie_documental"] ? $formatoConsecutivoValores["subserie_documental"] : '', $formatoConsecutivo);
            $formatoConsecutivo = str_replace("serie_documental", $formatoConsecutivoValores["serie_documental"] ? $formatoConsecutivoValores["serie_documental"] : '', $formatoConsecutivo);
            $formatoConsecutivo = str_replace("vigencia_actual", $formatoConsecutivoValores["vigencia_actual"], $formatoConsecutivo);

            //consulta segun el numero de correspondencia
            $order = Expediente::where('consecutivo', 'like', '%' . str_replace(", ", $formatoConsecutivoValores["separador_consecutivo"], str_replace("consecutivo_documento", '', $formatoConsecutivo)) . '%')->max('consecutivo_order');


            //valida el numero de order obtenido de la consulta
            if(!$order){
                $orden = 0;
            }

            $orden = (int)$order+1;

            $length = strlen($orden);

            //agrega cceros al consecutivo si aplica
            if($numberDigits > $length)
            {
                $digs = $numberDigits - $length;
                $norden = str_repeat("0",$digs).$orden;
            }
            else
            {
                $norden = $orden;
            }

            //asigna datos para retornar
            $nextConsecutivo = str_replace("consecutivo_documento", $norden, $formatoConsecutivo);
            $datosConsecutivo['consecutivo'] = str_replace(", ", $formatoConsecutivoValores["separador_consecutivo"], $nextConsecutivo);
            $datosConsecutivo['consecutivo_order'] = $orden;

            return $datosConsecutivo;

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Obtiene todos los tipos de expedientes
     *
     * @author Manuel Marin
     * @version 1.0.0
     *
     * @return Response
     */
    public function getExpedienteFiltros(Request $request, $queryFiltros = "") {
        $query = $request->input('query');
        // Usuario actual en sesión
        $usuario = Auth::user();
        $filtros = base64_decode($queryFiltros);
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $expedientes_filtrados = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials", "eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes" => function($query) {
            $query->where('orden_documento', 1);
        }])
        ->where("estado", "Abierto")
        ->where('consecutivo', 'like', '%' . $query . '%')
        ->when($filtros, function($q){
            $q->whereRaw($filtros)->get();
        })
        ->where(function($q) use ($usuario) {
            $q->whereHas('eePermisoUsuariosExpedientes', function($query) use ($usuario) {
                // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                $query->where(function($subQuery) use ($usuario) {
                    // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                    $subQuery->where('dependencia_usuario_id', $usuario->id)->where('tipo', 'Usuario');
                });
                $query->orWhere(function($subQuery) use ($usuario) {
                    // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                    $subQuery->where('dependencia_usuario_id', $usuario->id_dependencia)->where('tipo', 'Dependencia');
                });
            })
            ->orWhere("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente')
            ->orWhere("id_responsable", $usuario->id);
        })
        ->get();

        return $this->sendResponse($expedientes_filtrados->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Seven Soluciones Informáticas S.A.S. - Agos. 28. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsuariosAutorizados(Request $request)
    {
        $query =$request->input('query');
        $searchQuery = $query;

        // Usuarios
        $users = User::select('users.*')  // Seleccionamos todos los campos del usuario
        ->where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                ->orWhereHas('dependencies', function ($subQuery) use ($searchQuery) {
                    $subQuery->where('nombre', 'like', '%' . $searchQuery . '%');
                });
        })
        ->whereNotNull('id_cargo')
        ->where('block', '!=', 1)
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->with(['positions', 'dependencies']) // Cargamos las relaciones necesarias
        ->get()
        ->map(function($user) {
            return [
                'nombre' => $user->fullname, // Usamos el accessor fullname
                'id' => $user->id,
                'correo' => $user->email,
                'tipo' => 'Usuario'
            ];
        });

        // Dependencias
        $query =$request->input('query');
        $dependencias = DB::table('dependencias')
            ->select(DB::raw('CONCAT("Dependencia ", nombre) AS nombre, id, "Dependencia" AS tipo'))
            ->where('dependencias.nombre', 'like', '%' . $query . '%')
            ->get();

        $usuarios_dependencias = array_merge($users->toArray(), $dependencias->toArray());

        return $this->sendResponse($usuarios_dependencias, trans('data_obtained_successfully'));
    }
    /**
     * Cambia el estado al momento de darle eliminar documento
     *
     * @author Manuel marin. - Julio. 19 - 2024
     * @version 1.0.0
     *
     *
     * @return Response
     */
    public function cerrarExpediente(int $documentoId){
        try{
            $publicIp = $this->detectIP();
            $expediente = $this->expedienteRepository->update([
                "estado" => "Cerrado",
                "ip_cierre" => $publicIp,
                "id_firma_caratula_cierre" => hash('sha256', date("Y-m-d H:i:s") . ($documentoId))
            ], $documentoId);
            $historial = $expediente->toArray();

            //Guarda el historial del registro
            $historial["ee_expediente_id"] = $expediente->id;
            $historial["detalle_modificacion"] = "Expediente cerrado exitosamente";

            ExpedienteHistorial::create($historial);
            //Manda las relaciones

            $expediente->users;
            $expediente->serieClasificacionDocumental;
            $expediente->subserieClasificacionDocumental;
            $expediente->dependencias;
            $expediente->oficinaProductoraClasificacionDocumental;
            $expediente->eeExpedienteHistorials;

            $expediente_caratula = $expediente;
            $expediente_caratula->eeDocumentosExpedientes;
            // Invoca la función para construir el documento de carátula de cierre del expediente
            $this->crearCaratulaCerrado($expediente_caratula);

            return $this->sendResponse($expediente->toArray(), trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage() . '. Linea: ' . $error->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Obtiene los usuario responsables que esten autorizados para firmar
     *
     * @author Manuel Marin. - Sep. 20. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerResponsable(Request $request)
    {
        // Usuarios
        $query = $request->input('query');
        // Se inicializa la variable con el $query para que no tenga problemas al momento de usarla en la funcion
        $searchQuery = $query;
        // Filtra a todos los usuarios en donde el nombre coincida
        $users = User::where('autorizado_firmar', 1)
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda la aprobacion de la firma o la devolucion del expediente
     *
     * @author Manuel Marin. - Sep. 25 - 2024
     * @version 1.0.0
     *
     */
    public function aprobarFirmarExpedientes(Request $request)
    {
        $input = $request->all();
        $id = $input["id"];
        /** @var Expediente $expediente */
        $expediente = $this->expedienteRepository->find($id);

        if (empty($expediente)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Datos del usuario en sesión
        $usuario = Auth::user();
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si el estao del documento es 'Pendiente de firma'
            if($input["estado"] == "Pendiente de firma") {
                if($input["type_send"] == "Aprobar Firma") {
                    $verificar_firma = self::verificarFirmaUsuario($usuario)->getData();
                    if($verificar_firma->type_message == "info")
                        return $verificar_firma;
                    $input["estado"] = "Abierto";
                    $publicIp = $this->detectIP();
                    $input["ip_apertura"] = $publicIp;
                    $observacion_historial_expediente = "Expediente aprobado y firmado";
                    $input["id_firma_caratula_apertura"] = hash('sha256', date("Y-m-d H:i:s") . ($input["consecutivo"] ?? $input["users_id"]));
                    DocumentosExpediente::where('ee_expediente_id', $input["id"])->update(['estado_doc' =>  'Asociado']);
                } else {
                    $observacion_historial_expediente = "Expediente devuelto para modificaciones";
                    $input["estado"] = "Devuelto para modificaciones";
                }
                $expediente = $this->expedienteRepository->update($input, $id);

                $input["ee_expediente_id"] = $expediente->id;
                $input["observacion"] = $input["observacion_accion"];

                $expediente->users;
                $expediente->serieClasificacionDocumental;
                $expediente->subserieClasificacionDocumental;
                $expediente->dependencias;
                $expediente->oficinaProductoraClasificacionDocumental;
                $expediente->eeExpedienteHistorials;
                $expediente->eePermisoUsuariosExpedientes;
                $expediente->expedienteHasMetadatos;

                if($input["type_send"] == "Aprobar Firma") {
                    $resultadoCaratula = $this->crearCaratulaAbiertoFirmado($expediente, $observacion_historial_expediente, $usuario->toArray());
                } else {
                    $historial = $input;
                    $historial["detalle_modificacion"] = $observacion_historial_expediente;
                    $historial["dependencias_id"] = $usuario->id_dependencia;
                    $historial["nombre_dependencia"] = dependencias::select('nombre')->where('id', $usuario->id_dependencia)->pluck('nombre')->first();
                    $historial["users_id"] = $usuario["id"];
                    $historial["user_name"] = $usuario["fullname"];
                    // Se crea el historial del expediente
                    ExpedienteHistorial::create($historial);

                    $asunto = json_decode('{"subject": "Devolución de firma del expediente  ' . $expediente->consecutivo . '"}');
                    $email = User::where('id', $expediente->id_usuario_enviador)->first()->email;
                    $notificacion["id"] = $expediente->id;
                    $notificacion["consecutivo"] = $expediente->consecutivo;
                    $notificacion["state"] = $expediente->estado;
                    $notificacion["nombre_funcionario"] = $expediente->nombre_usuario_enviador;
                    $notificacion['id_encrypted'] = base64_encode($expediente->id);
                    $notificacion['mensaje'] = "<p>Le informamos que el expediente con consecutivo <strong>{$expediente->consecutivo}</strong> ha sido <strong>devuelto para modificaciones</strong> por el funcionario <strong>{$expediente->nombre_responsable}</strong>, con la siguiente observación:
                    <br><br>
                    {$expediente->observacion_accion}.
                    <br><br>
                    Por favor, ingrese al sistema de Intraweb para realizar las correcciones necesarias y gestionar el expediente.</p";
                    try {
                        SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email, 'Expedientes electrónicos');
                    } catch (\Swift_TransportException $e) {
                        // Manejar la excepción de autenticación SMTP aquí
                        $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                    } catch (\Exception $e) {
                        // Por ejemplo, registrar el error
                        $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($external['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                    }
                }
            } else {
                $input["users_id"] = $usuario->id;
                $input["user_name"] = $usuario->fullname;
                // Valida si la acción es
                if($input["type_send"] == "Aprobar Firma"){
                    $input["estado"] = "Cerrado";
                    $expediente = $this->expedienteRepository->update($input, $id);
                    $input["ee_expediente_id"] = $expediente->id;
                    $input["detalle_modificacion"] = "Se aprobo el cierre del expediente";
                    ExpedienteHistorial::create($input);
                    $resultadoCaratulaCerrado = $this->cerrarExpediente($expediente->id);
                } else {
                    $input["estado"] = "Abierto";
                    $expediente = $this->expedienteRepository->update($input, $id);
                    // Expediente::where('id', $expediente->id)->update(['estado' =>  'Abierto']);
                    $input["ee_expediente_id"] = $expediente->id;
                    $input["detalle_modificacion"] = "Se nego el cierre del expediente y se paso a estado abierto nuevamente";
                    ExpedienteHistorial::create($input);
                }

                $expediente->users;
                $expediente->serieClasificacionDocumental;
                $expediente->subserieClasificacionDocumental;
                $expediente->dependencias;
                $expediente->oficinaProductoraClasificacionDocumental;
                $expediente->eeExpedienteHistorials;
                $expediente->eePermisoUsuariosExpedientes;
                $expediente->expedienteHasMetadatos;
            }

            DB::commit();
            return $this->sendResponse($expediente->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Crea un documento pdf Cuando el estado del expediente sea abiertoy le pone la firma
     *
     * @author Manuel Marin - Sep. 25 - 2024
     * @version 1.0.0
     *
     * @param input $request
     *
     * @return Response
     */
    private function crearCaratulaAbiertoFirmado($input, $observacion_historial_expediente, $usuario)
    {
        try {
            // Convertir el input a un array
            $expediente = $input->toArray();
            $info_sitio = ConfigurationGeneral::get()->first();
            // Agregar la información del sitio al expediente
            $expediente["logo"] = $info_sitio["logo"];
            $expediente["nombre_entidad"] = $info_sitio["nombre_entidad"];
            $expediente["origen_creacion"] = "Nuevo documento";
            $expediente["nombre_documento"] = "Carátula de apertura";
            $caratula_expediente = DocumentosExpediente::where('ee_expediente_id', $expediente["id"])->where('nombre_expediente', 'Caratula')->first();
            $expediente["consecutivo_doc"] = $caratula_expediente->consecutivo;

            $info_responsable = User::find($input["id_responsable"]);
            $expediente["firma_responsable"] = $info_responsable->url_digital_signature;

            // Obtener la fecha actual
            $current_date = new DateTime();
            // Crear el formateador de fechas
            $formatter = new IntlDateFormatter('es_ES', IntlDateFormatter::FULL, IntlDateFormatter::NONE, 'GMT');
            // Formatear la fecha
            $formatted_date = $formatter->format($current_date);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_documento_extensa"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);

            // Fecha 'fecha_inicio_expediente'
            $fecha_inicio_expediente = new DateTime($expediente["fecha_inicio_expediente"]);
            // Formatear la fecha
            $formatted_date = $formatter->format($fecha_inicio_expediente);
            // Reemplazar la segunda ocurrencia de "de" por "del"
            $parts = explode(' de ', $formatted_date);
            // Formatear la fecha y capitalizar la primera letra
            $expediente["fecha_inicio_expediente"] = ucfirst($parts[0] . ' de ' . $parts[1] . ' del ' . $parts[2]);
            $expediente["codigo_acceso_caratula_apertura"] = Str::random(10);
            $codigo_acceso_caratula = $expediente["codigo_acceso_caratula_apertura"];

            // Especifica la ruta donde deseas guardar el archivo PDF
            $carpeta_expedientes = 'app/public/container/expedientes_electronicos_' . date("Y");

            // Crear la carpeta si no existe
            if(!file_exists($carpeta_expedientes))
                @mkdir(storage_path($carpeta_expedientes), 0755, true);

            // Definir la ruta del archivo adjunto
            $ruta_adjunto = $carpeta_expedientes.'/CaratulaAperturaFirmada'.$expediente["id"].'.pdf';
            $path = storage_path($ruta_adjunto);
            // Generar el PDF con la vista correspondiente y los datos del expediente
            $filePDF = PDF::loadView('expedienteselectronicos::expedientes.exports.caratula_abierto', ['data' => $expediente]);
            // Guarda el archivo PDF en la ubicación especificada
            $filePDF->save($path);
            // Ruta relativa del documento
            $ruta_adjunto = substr($ruta_adjunto, 11);
            // Hash de integridad de la carátula de apertura
            $hash_caratula_apertura = hash_file('sha256', 'storage/' . $ruta_adjunto);

            DocumentosExpediente::where('ee_expediente_id', $expediente["id"])->where('nombre_expediente', 'Caratula')->update(['adjunto' =>  $ruta_adjunto]);
            $expediente["adjunto"] = $ruta_adjunto;
            $expediente["ee_documentos_expediente_id"] = $caratula_expediente->id;
            $expediente["accion"] = "Caratula firmada";
            $expediente["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($expediente);

            $expediente = $this->expedienteRepository->update(['hash_caratula_apertura' =>  $hash_caratula_apertura, 'codigo_acceso_caratula_apertura' =>  $codigo_acceso_caratula], $expediente["id"]);
            $historial = $expediente->toArray();

            $historial["ee_expediente_id"] = $historial["id"];
            $historial["detalle_modificacion"] = $observacion_historial_expediente;
            $historial["dependencias_id"] = $usuario["id_dependencia"];
            $historial["nombre_dependencia"] = $usuario["dependencies"]["nombre"];
            $historial["users_id"] = $usuario["id"];
            $historial["user_name"] = $usuario["fullname"];
            ExpedienteHistorial::create($historial);

            return "Exito";
            DB::commit();

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return "Fallo";

            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return "Fallo";

            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }


        /**
     * Cambia el estado al momento de darle eliminar documento
     *
     * @author Manuel marin. - Julio. 19 - 2024
     * @version 1.0.0
     *
     *
     * @return Response
     */
    public function enviarFirmaCerrado(int $documentoId){
        try {
            $roles_usuario = Auth::user()->roles->pluck('name')->toArray();
            $roles_expedientes = collect($roles_usuario)
                ->filter(function ($role) {
                    return str_contains(strtolower($role), 'expediente');
                })
                ->implode(' - ');

            $expediente = $this->expedienteRepository->update([
                "estado" => "Pendiente de aprobación de cierre",
                "id_usuario_enviador" => Auth::user()->id,
                "nombre_usuario_enviador" => Auth::user()->fullname,
                "tipo_usuario_enviador" => $roles_expedientes
            ], $documentoId);
            $historial = $expediente->toArray();

            //Guarda el historial del registro
            $historial["ee_expediente_id"] = $expediente->id;

            ExpedienteHistorial::create($historial);
            //Manda las relaciones

            $expediente->users;
            $expediente->serieClasificacionDocumental;
            $expediente->subserieClasificacionDocumental;
            $expediente->dependencias;
            $expediente->oficinaProductoraClasificacionDocumental;
            $expediente->eeExpedienteHistorials;

            return $this->sendResponse($expediente->toArray(), trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Obtiene la informacion del expediente desde el componente de expediente
     *
     * @author Manuel Marín - Sep. 30 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getInformacionExpediente($idExpediente) {
        $expedienteId = base64_decode($idExpediente);
        //Obtiene los documentos del expediente
        $expediente_info = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials", "eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes"])->where('id', $expedienteId)->first();
        return $this->sendResponse($expediente_info->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene la informacion para que se muestre al tablero del administrador
     *
     * @author Seven Soluciones Informáticas - Mar. 10 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    private function obtenerDatostablero($filtros, $user) {
        // Rol con el que el usuario está consultado el tablero
        $rol_filtro_consulta = $filtros["rol_consulta_expedientes"] ?? session('rol_consulta_expedientes') ?? "funcionario";
        // Obtiene todos los expedientes según el rol y permisos del usuario
        $expedientes = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials","eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes", "expedienteLeido", "expedienteAnotaciones", "anotacionesPendientes"])
            ->when($rol_filtro_consulta == "operador" && Auth::user()->hasRole('Operador Expedientes Electrónicos'), function($query) use($user) {
                $query->where("dependencias_id", $user->id_dependencia);
            })
            ->when($rol_filtro_consulta == "consulta_expedientes" && Auth::user()->hasRole('Consulta Expedientes Electrónicos'), function($query) {
                $query->whereNot("estado", "Pendiente de firma");
            })
            ->when($rol_filtro_consulta == "responsable_expedientes", function($query) use($user) {
                $query->where("id_responsable", $user->id);
            })
            ->when($rol_filtro_consulta == "funcionario", function($query) use($user) {
                $query->where(function($q) use ($user) {
                    $q->whereHas('eePermisoUsuariosExpedientes', function($query) use ($user) {
                        // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                        $query->where(function($subQuery) use ($user) {
                            // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                            $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                        });
                        $query->orWhere(function($subQuery) use ($user) {
                            // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                            $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                        });
                    })
                    ->orwhere("permiso_general_expediente", 'Todas las dependencias pueden incluir y editar documentos en el expediente')
                    ->orWhere("permiso_general_expediente", 'Todas las dependencias están autorizadas para ver información y documentos del expediente');
                });
                $query->where(function($q) use ($user) {
                    $q->where("estado", "Abierto");
                    $q->orWhere("estado", "Cerrado");
                });
            })
            ->latest("updated_at")
            ->get()->toArray();
        // Definimos los estados originales y sus equivalentes para reemplazar
        $statuses = ["Abierto", "Cerrado", "Pendiente de aprobación de cierre", "Pendiente de firma", "Devuelto para modificaciones"];
        $statuses_to_reemplace = ["abierto", "cerrado", "pendiente_aprobacion_cierre", "pendiente_de_firma", "devuelto_para_modificaciones"];

        $modified_status_data = [];

        foreach ($expedientes as $expediente) {
            // Asumiendo que el estado está en algún campo como 'estado' o 'status' del expediente
            $estado = $expediente['estado']; // Ajusta este campo según tu estructura de datos

            // Convertimos el estado al formato deseado
            $estado_modificado = str_replace($statuses, $statuses_to_reemplace, $estado);

            // Si no existe el contador para este estado, lo inicializamos
            if (!isset($modified_status_data[$estado_modificado])) {
                $modified_status_data[$estado_modificado] = 0;
            }

            // Incrementamos el contador para este estado
            $modified_status_data[$estado_modificado]++;
        }

        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'estados' => $modified_status_data,
        ]);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtener_dependencias(Request $request) {
        // Obtiene las dependencias con sus sedes
        $query =$request->input('query');
        // Valida de que el operador del expediente solo obtenga la oficina productora a la que el pertenece
        if(Auth::user()->hasRole('Operador Expedientes Electrónicos')){
            $dependencies = Dependency::with(['headquarters','dependenciasList'])->where('id', Auth::user()->id_dependencia)->latest()->get();
        } else {
            $dependencies = Dependency::with(['headquarters','dependenciasList'])->latest()->get();
        }

        return $this->sendResponse($dependencies->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Asigna el leído del expediente electrónico según el usuario
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 3 - 2024
     * @version 1.0.0
     *
     * @param int $id del expediente
     */
    public function registrarLeido($expedienteId, $rolAplicado)
    {
        // Valida si el usuario tiene una sesión activa
        if(Auth::check()) {
            // Información del usuario logueado
            $userLogin = Auth::user();
        } else { // Si no tiene una sesión activa, es un usuario externo
            $userLogin = new stdClass;
            $userLogin->id = session('id');
            $userLogin->fullname = session('nombre');
        }
        
        // Valida el rol del usuario que esta leyendo el expediente
        if ($rolAplicado == "operador") {
            $rol = "Operador";
        } else if ($rolAplicado == "consulta_expedientes") {
            $rol = "Consulta";
        } else if ($rolAplicado == "responsable_expedientes") {
            $rol = "Responsable";
        } else if ($rolAplicado == "usuario_externo") {
            $rol = "Usuario externo";
        } else {
            $rol = "Funcionario";
        }

        $leidoExpediente = ExpedienteLeido::select("id", "accesos", "nombre_usuario")
        ->where("ee_expediente_id", $expedienteId)
        ->where("users_id", $userLogin->id)
        ->where("tipo_usuario", $rol)
        ->first();
        if ($leidoExpediente) {
            // Valida si ya tiene accesos
            if ($leidoExpediente["accesos"]) {
                $accesos = $leidoExpediente["accesos"] . "<br/>" . date("Y-m-d H:i:s");
            } else {
                $accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido
            $resultleidoExpediente = ExpedienteLeido::where("id", $leidoExpediente["id"])->update(["accesos" => $accesos], $leidoExpediente["id"]);
        } else {
            $leidoExpediente = date("Y-m-d H:i:s");

            // Crea un registro de leido del registro
            $resultleidoExpediente = ExpedienteLeido::create([
                'tipo_usuario' => $rol,
                'nombre_usuario' => $userLogin->fullname,
                'accesos' => $leidoExpediente,
                'vigencia' => date("Y"),
                'ee_expediente_id' => $expedienteId,
                'users_id' => $userLogin->id
            ]);
        }

        // Buscar y obtener la instancia de Documento correspondiente
        $expediente = $this->expedienteRepository->find($expedienteId);

        //Manda las relaciones
        $expediente->users;
        $expediente->serieClasificacionDocumental;
        $expediente->subserieClasificacionDocumental;
        $expediente->dependencias;
        $expediente->oficinaProductoraClasificacionDocumental;
        $expediente->eeExpedienteHistorials;
        $expediente->eePermisoUsuariosExpedientes;
        $expediente->expedienteHasMetadatos;

        // Devolver una respuesta con los datos de la instancia del documento actualizado
        return $this->sendResponse($expediente->toArray(), "Leído de expediente registrado con éxito");
    }

    /**
     * Exporta el historial del expediente
     *
     * @author Seven Soluciones Informáticas. - Mar. 04. 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportHistorial($id)
    {
        $historial = ExpedienteHistorial::where('ee_expediente_id', $id)->get();

        return Excel::download(new RequestExport('expedienteselectronicos::expedientes.exports.report_historial', JwtController::generateToken($historial->toArray()), 'K'), 'Prueba.xlsx');
    }

    public function verificarFirmaUsuario($usuario) {
        // Valida si el usuario posee una firma para la publicación del documento
        if(!$usuario->url_digital_signature || !file_exists(storage_path("app/public/".$usuario->url_digital_signature))){
            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
        } else {
            return $this->sendSuccess('Firma verificada', 'ok');
        }
    }

    /**
     * Obtiene todos los usuarios que son responsables de al menos un expediente
     *
     * @author Seven Soluciones Informáticas. - Mar. 13. 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsersResponsableExpedienteFiltro()
    {
        // Filtra a todos los usuarios que son responsable
        $users = User::select("users.id", "users.name", "users.id_dependencia", "users.id_cargo")->join('ee_expediente', function($join) {
            $join->on('ee_expediente.id_responsable', '=', 'users.id');
        })
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->where("is_assignable_pqr_correspondence",0)
        ->groupBy("users.id")
        ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Valida la existencia del documento electronico según el código ingresado por el usuario
     *
     * @param Request $request
     * @return void
     */
    public function validarAdjuntoDocumento(Request $request)
    {
        $input = $request->all();
        $hash = hash_file('sha256', $request['documento_adjunto']);
        // Retorna la veracidad del adjunto del documento electronico, en caso tal de que coincida algún registro
        return $this->sendResponse(["documentos_identicos" => $input["hash"] == $hash], "Documento verificado");
    }

    /**
     * Valida la existencia del documento electronico según el código ingresado por el usuario
     *
     * @param [type] $codigo
     * @param Request $request
     * @return void
     */
    public function validarDocumento(string $codigo, Request $request)
    {
        // Consulta
        $documentos = DocumentosExpediente::with("eeExpediente")
            ->whereHas("eeExpediente", function($q) use ($codigo) {
                $q->whereRaw("codigo_acceso_caratula_apertura = BINARY ?", [$codigo])
                ->orWhereRaw("codigo_acceso_caratula_cierre = BINARY ?", [$codigo]);
            })->get();

        // Filtrar los documentos según el campo que coincidió
        $documentosFiltrados = $documentos->filter(function ($documento) use ($codigo) {
            $expediente = $documento->eeExpediente;

            if ($expediente->codigo_acceso_caratula_apertura === $codigo) {
                return $documento->nombre_documento === "Carátula de apertura";
            } elseif ($expediente->codigo_acceso_caratula_cierre === $codigo) {
                return $documento->nombre_documento === "Carátula de cierre";
            }

            return false;
        })->values(); // Reindexa la colección desde 0

        // Retorna la información del documento, en caso tal de que coincida algún registro
        return $this->sendResponse($documentosFiltrados->toArray(), trans('msg_success_update'));
    }

    /**
     * Envía una notificación por correo electrónico cuando se otorgan o revocan permisos sobre un expediente.
     *
     * @param array $datos_destinatario Lista de destinatarios con su información.
     * @param object $expediente Objeto del expediente relacionado con la notificación.
     * @param string $tipo_usuario Tipo de acción realizada ("nuevos" para otorgar permisos, otro valor para revocarlos).
     *
     * @return void
     */
    public function enviar_notificacion_permisos_uso($datos_destinatario, $expediente, $tipo_usuario) {
        // Define el asunto del correo según si los permisos fueron otorgados o revocados
        if ($tipo_usuario == "nuevos") {
            $asunto = json_decode('{"subject": "Permisos habilitados para el expediente ' . $expediente->consecutivo . '"}');
        } else {
            $asunto = json_decode('{"subject": "Revocación de permisos en el expediente ' . $expediente->consecutivo . '"}');
        }

        // Prepara los datos de la notificación
        $notificacion['consecutivo'] = $expediente->consecutivo;
        $notificacion['nombre_expediente'] = $expediente->nombre_expediente;
        $notificacion['created_at'] = $expediente->created_at->format('d/m/Y');

        // Itera sobre cada destinatario para enviar la notificación
        foreach ($datos_destinatario as $destinatario) {
            // Obtiene el correo electrónico del usuario por su ID
            $email = User::where('id', $destinatario["dependencia_usuario_id"])->first()->email;
            $notificacion["nombre_funcionario"] = $destinatario["nombre"];

            try {
                // Envía la notificación según el tipo de permiso (nuevo o revocado)
                SendNotificationController::SendNotification(
                    $tipo_usuario == "nuevos" ?
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_permiso_uso' :
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_revocar_permiso_uso',
                    $asunto,
                    $notificacion,
                    $email,
                    'Expedientes electrónicos'
                );
            } catch (\Swift_TransportException $e) {
                // Maneja errores de autenticación SMTP y los registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine()
                );
            } catch (\Exception $e) {
                // Captura cualquier otro error y lo registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine() .
                    ' Consecutivo: ' . ($external['consecutive'] ?? 'Desconocido') .
                    '- ID: ' . ($id ?? 'Desconocido')
                );
            }
        }
    }


    /**
     * Envía una notificación por correo electrónico cuando se otorgan o revocan permisos de consulta sobre un expediente.
     *
     * @param array $datos_destinatario Lista de destinatarios con su información.
     * @param object $expediente Objeto del expediente relacionado con la notificación.
     * @param string $tipo_usuario Tipo de acción realizada ("nuevos" para otorgar permisos, otro valor para revocarlos).
     *
     * @return void
     */
    public function enviar_notificacion_permisos_consulta($datos_destinatario, $expediente, $tipo_usuario) {
        // Define el asunto del correo según si los permisos fueron otorgados o revocados
        if ($tipo_usuario == "nuevos") {
            $asunto = json_decode('{"subject": "Acceso de consulta asignado al expediente ' . $expediente->consecutivo . '"}');
        } else {
            $asunto = json_decode('{"subject": "Revocación de permisos en el expediente ' . $expediente->consecutivo . '"}');
        }

        // Prepara los datos de la notificación
        $notificacion['consecutivo'] = $expediente->consecutivo;
        $notificacion['nombre_expediente'] = $expediente->nombre_expediente;
        $notificacion['created_at'] = $expediente->created_at->format('d/m/Y');
        $notificacion['id_encrypted'] = base64_encode($expediente->id);

        // Itera sobre cada destinatario para enviar la notificación
        foreach ($datos_destinatario as $destinatario) {
            // Obtiene el correo electrónico del usuario por su ID
            $email = User::where('id', $destinatario["dependencia_usuario_id"])->first()->email;
            $notificacion["nombre_funcionario"] = $destinatario["nombre"];

            try {
                // Envía la notificación según el tipo de permiso (nuevo o revocado)
                SendNotificationController::SendNotification(
                    $tipo_usuario == "nuevos" ?
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_permiso_consulta' :
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_revocar_permiso_consulta',
                    $asunto,
                    $notificacion,
                    $email,
                    'Expedientes electrónicos'
                );
            } catch (\Swift_TransportException $e) {
                // Maneja errores de autenticación SMTP y los registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine()
                );
            } catch (\Exception $e) {
                // Captura cualquier otro error y lo registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine() .
                    ' Consecutivo: ' . ($external['consecutive'] ?? 'Desconocido') .
                    '- ID: ' . ($id ?? 'Desconocido')
                );
            }
        }
    }

    /**
     * Envía una notificación a un usuario externo por correo electrónico cuando se otorgan o revocan permisos sobre un expediente.
     *
     * @param array $datos_destinatario Lista de destinatarios con su información.
     * @param object $expediente Objeto del expediente relacionado con la notificación.
     * @param string $accion Tipo de acción realizada ("nuevos" para otorgar permisos, otro valor para revocarlos).
     *
     * @return void
     */
    public function enviar_notificacion_permisos_usuarios($datos_destinatario, $expediente, $accion) {
        // Define el asunto del correo según si los permisos fueron otorgados o revocados
        if ($accion == "nuevos") {
            $asunto = json_decode('{"subject": "Permisos habilitados para el expediente ' . $expediente->consecutivo . '"}');
        } else {
            $asunto = json_decode('{"subject": "Revocación de permisos en el expediente ' . $expediente->consecutivo . '"}');
        }

        // Prepara los datos de la notificación
        $notificacion['consecutivo'] = $expediente->consecutivo;
        $notificacion['nombre_expediente'] = $expediente->nombre_expediente;
        $notificacion['created_at'] = $expediente->created_at->format('d/m/Y');
        // Itera sobre cada destinatario para enviar la notificación
        foreach ($datos_destinatario as $destinatario) {
            if(!empty($destinatario["tipo"]) && $destinatario["tipo"] == "Dependencia") {
                $email = User::whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
                ->where('id_cargo', '!=', 0)
                ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
                ->where('id_dependencia', $destinatario["dependencia_usuario_id"])
                ->pluck('email') // Realiza la consulta y obtiene una colección de usuarios
                ->toArray();
            } else {
                // Obtiene el correo electrónico del usuario por su ID
                $email = $destinatario["correo"];
                $notificacion["nombre_funcionario"] = $destinatario["nombre"];
            }

            if($destinatario["tipo_usuario"] == "Externo") {
                $notificacion["correo"] = $destinatario["correo"];
                $notificacion["pin_acceso"] = $destinatario["pin_acceso"];
            }
            
            $notificacion["permiso"] = $destinatario["permiso"];
            $notificacion["tipo_usuario"] = $destinatario["tipo_usuario"];

            try {
                // Envía la notificación según el tipo de permiso (nuevo o revocado)
                SendNotificationController::SendNotification(
                    $accion == "nuevos" ?
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_permiso_usuarios' :
                    'expedienteselectronicos::expedientes.emails.plantilla_notificacion_revocar_permiso_usuarios',
                    $asunto,
                    $notificacion,
                    $email,
                    'Expedientes electrónicos'
                );
            } catch (\Swift_TransportException $e) {
                // Maneja errores de autenticación SMTP y los registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine()
                );
            } catch (\Exception $e) {
                // Captura cualquier otro error y lo registra en el log
                $this->generateSevenLog(
                    'ExpedientesElectronicos',
                    'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '.
                    (Auth::user()->fullname ?? 'Usuario Desconocido') .
                    ' - Error: ' . $e->getMessage() . '. Línea: ' . $e->getLine() .
                    ' Consecutivo: ' . ($external['consecutive'] ?? 'Desconocido') .
                    '- ID: ' . ($id ?? 'Desconocido')
                );
            }
        }
    }

    /**
     * Genera un PIN alfanumérico aleatorio y seguro.
     *
     * @param int $length Longitud del PIN que se desea generar (por defecto 8).
     * @return string El PIN generado, compuesto por letras y números.
     */
    function generateAlphaNumericPin($length = 8): string {
        // Conjunto de caracteres permitidos: mayúsculas, minúsculas y dígitos
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        // Variable para almacenar el PIN resultante
        $pin = '';

        // Último índice posible del conjunto de caracteres (para evitar desbordes)
        $maxIndex = strlen($characters) - 1;

        // Repite tantas veces como la longitud especificada
        for ($i = 0; $i < $length; $i++) {
            // Elige un índice aleatorio dentro del rango permitido
            $randomIndex = random_int(0, $maxIndex);

            // Añade el carácter correspondiente al índice aleatorio al PIN
            $pin .= $characters[$randomIndex];
        }

        // Devuelve el PIN generado
        return $pin;
    }

    /**
     * Muestra la vista para el CRUD de Expediente.
     *
     * @author Desarrollador Seven - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexUsuarioExterno(Request $request) {
        return view('expedienteselectronicos::expedientes.index_usuario_externo');
    }

    /**
     * Obtiene todos los elementos existentes de expedientes electrónicos, según los permisos del usuario externo
     *
     * @author Desarrollador Seven - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function allUsuarioExterno(Request $request)
    {
        // Decodifica los campos filtrados
        $filtros = str_replace(["'{","}'"], ["{","}"], base64_decode($request["f"]));

        $filtros_metadatos = "";
        $rol_filtro_consulta = "";

        // Valida si en los filtros realizados viene el filtro de _obj_llave_valor_
        if(stripos($filtros, "_obj_llave_valor_") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de _obj_llave_valor_ en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, '_obj_llave_valor_') !== false;
            }))[0];

            // Se extrae el valor del filtro _obj_llave_valor_
            $filtros_metadatos = strtolower(explode("= ", $filtro[$posicion])[1]);
            // Decodifica los filtros de metadatos, ya que vienen en json
            $filtros_metadatos = json_decode($filtros_metadatos, true);
            // Se elimina el filtro de _obj_llave_valor_ del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }

        // Valida si en los filtros realizados viene el filtro de rol_consulta_expedientes
        if(stripos($filtros, "rol_consulta_expedientes") !== false) {
            // Se separan los filtros por el operador AND, obteniendo un array
            $filtro = explode(" AND ", $filtros);
            // Se obtiene la posición del filtro de rol_consulta_expedientes en el array de filtros
            $posicion = array_keys(array_filter($filtro, function($value) {
                return stripos($value, 'rol_consulta_expedientes') !== false;
            }))[0];
            // Se extrae el valor del filtro rol_consulta_expedientes
            $rol_filtro_consulta = strtolower(explode("%", $filtro[$posicion])[1]);
            // Guardar el filtro de rol_consulta_expedientes seleccionado por el usuario
            session(['rol_consulta_expedientes' => $rol_filtro_consulta]);
            // Se elimina el filtro de rol_consulta_expedientes del array de filtro
            unset($filtro[$posicion]);
            // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
            $filtros = implode(" AND ", $filtro);
        }

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros}

        // Valida si existen las variables del paginado y si esta filtrando
        if ((isset($request["f"]) && $request["f"] != "") || isset($request["pi"])) {
            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
            $expedientes = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials","eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes", "expedienteLeido", "expedienteAnotaciones", "anotacionesPendientes"])
            ->when($rol_filtro_consulta == "usuario_externo", function($query)  {
                $query->whereHas('eePermisoUsuariosExpedientes', function($q) {
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $q->where("correo", session()->get('correo'))->where("pin_acceso", session()->get('pin_acceso'));
                });
                $query->where(function($q) {
                    $q->where("estado", "Abierto");
                    $q->orWhere("estado", "Cerrado");
                });
            })
            ->when($filtros, function($query) use($filtros) {
                $query->whereRaw($filtros);
            })
            ->when($filtros_metadatos, function($query) use($filtros_metadatos) {
                $query->whereHas("expedienteHasMetadatos", function($m) use($filtros_metadatos) {
                    $m->where(function ($n) use ($filtros_metadatos) {
                        // Recorre los filtros
                        foreach ($filtros_metadatos as $key => $valor_metatado) {
                            $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                // Separa el nombre del filtro(llave del array) para obtener el id del metadato
                                $de_metadatos_id = explode("_", $key);
                                $de_metadatos_id = end($de_metadatos_id);
                                $q->where("ee_metadatos_id", $de_metadatos_id); // Id del metadato en su nombre
                                $q->where("valor", "LIKE", "%".$valor_metatado."%"); // Valor del metadato ingresado en el filtro
                            });
                        }
                    });
                });
            })
            ->latest("updated_at")
            ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página

            $count_expedientes = $expedientes->total(); // Total de expedientes encontrados
            $expedientes = $expedientes->toArray()["data"]; // Consulta los expedientes según los filtros
        } else {
            $expedientes = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials", "eePermisoUsuariosExpedientes","expedienteHasMetadatos", "eeDocumentosExpedientes", "expedienteAnotaciones", "anotacionesPendientes"])
            ->where(function($query) {
                $query->whereHas('eePermisoUsuariosExpedientes', function($q) {
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $q->where("correo", session()->get('correo'))->where("pin_acceso", session()->get('pin_acceso'));
                });
                $query->where(function($q) {
                    $q->where("estado", "Abierto");
                    $q->orWhere("estado", "Cerrado");
                });

            })->latest()->get();
            // Contar el número total de registros de la consulta realizada según el paginado seleccionado
            $count_expedientes = Expediente::where(function($query) {
                $query->whereHas('eePermisoUsuariosExpedientes', function($q) {
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $q->where("correo", session()->get('correo'))->where("pin_acceso", session()->get('pin_acceso'));
                });
                $query->where(function($q) {
                    $q->where("estado", "Abierto");
                    $q->orWhere("estado", "Cerrado");
                });

            })->count();
        }

        return $this->sendResponseAvanzado($expedientes, trans('data_obtained_successfully'), null, ["total_registros" => $count_expedientes]);
    }

    /**
     * Registra el leído de un usuario a las anotaciones de un expediente_id
     *
     * @author Seven Soluciones Informáticas S.A.S. Ago. 04 - 2025
     * @version 1.0.0
     *
     * @param int $ee_expediente_id
     *
     * @return Response
     */
    public function leidoAnotacionExpediente($ee_expediente_id) {
        // Obtener el ID del usuario actual
        $userId = Auth::check() ? Auth::id() : 0;
        // Actualizar los registros directamente en la base de datos
        $result_expediente_anotacion_leido = ExpedienteAnotacion::where('ee_expediente_id', $ee_expediente_id)
            ->where(function ($query) use ($userId) { 
                $query->where('leido_por', null)
                    ->orWhere(function ($n) use ($userId) { 
                        $n->whereNot('leido_por', $userId)->where('leido_por', 'not like', '%,' . $userId . ',%')->where('leido_por', 'not like', $userId . ',%')->where('leido_por', 'not like', '%,' . $userId);
                    });
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        // Se obtiene el expediente con sus relaciones actualizadas
        $expediente = $this->expedienteRepository->find($ee_expediente_id);
        $expediente->users;
        $expediente->serieClasificacionDocumental;
        $expediente->subserieClasificacionDocumental;
        $expediente->dependencias;
        $expediente->oficinaProductoraClasificacionDocumental;
        $expediente->eeExpedienteHistorials;
        $expediente->eePermisoUsuariosExpedientes;
        $expediente->expedienteHasMetadatos;
        $expediente->anotacionesPendientes;
        
        return $this->sendResponse($expediente->toArray(), "Anotación leida con éxito");
    }

    /**
     * Guarda una nueva anotación asociada a un expediente
     *
     * @author Seven Soluciones Informáticas S.A.S. Ago. 04 - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function guardarAnotacionExpediente(Request $request, $expedienteId) {
        // Obtiene los valores de los campos
        $input = $request->all();

        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["anotacion"],"<p") !== false){
            $input["anotacion"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["anotacion"]);
            $input["anotacion"] = preg_replace('/<\/p>/i', '</span>', $input["anotacion"]);
        }

        // Asigna el ID del expediente para la relación con la anotación
        $input["ee_expediente_id"] = $expedienteId;
        // Obtiene el ID del usuario en sesión
        $input["users_id"] = Auth::check() ? Auth::user()->id : 0;
        // Obtiene el nombre de usuario en sesión
        $input["nombre_usuario"] = Auth::check() ? Auth::user()->fullname : "Usuario externo";
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
            $expedienteAnotacion = ExpedienteAnotacion::create($input);
            
            $expedienteAnotacion->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($expedienteAnotacion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }
}
