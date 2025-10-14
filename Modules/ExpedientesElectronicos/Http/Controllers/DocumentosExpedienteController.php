<?php

namespace Modules\ExpedientesElectronicos\Http\Controllers;

use App\Exports\expedientes_electronicos\GenericExport;
use Modules\ExpedientesElectronicos\Http\Requests\CreateDocumentosExpedienteRequest;
use Modules\ExpedientesElectronicos\Http\Requests\UpdateDocumentosExpedienteRequest;
use Modules\ExpedientesElectronicos\Http\Controllers\ExpedienteController;
use Modules\ExpedientesElectronicos\Repositories\DocumentosExpedienteRepository;
use Modules\ExpedientesElectronicos\Models\DocumentosExpediente;
use Modules\ExpedientesElectronicos\Models\DocExpedienteHistorial;
use Modules\ExpedientesElectronicos\Models\ExpedienteHistorial;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use Modules\PQRS\Models\PQR;
use Modules\ExpedientesElectronicos\Http\Requests\CreateExpedienteRequest;
use Modules\DocumentosElectronicos\Models\Documento;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\External;
use App\Http\Controllers\JwtController;
use Modules\ExpedientesElectronicos\Models\TiposDocumental;
use Modules\ExpedientesElectronicos\Models\Expediente;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\SendNotificationController;
use App\User;
use \stdClass;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Modules\ExpedientesElectronicos\Models\DocumentoExpedienteAnotacion;
use Modules\ExpedientesElectronicos\Models\ExpedienteHasMetadato;
use ZipArchive;
use File;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentosExpedienteController extends AppBaseController {

    /** @var  DocumentosExpedienteRepository */
    private $documentosExpedienteRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentosExpedienteRepository $documentosExpedienteRepo) {
        $this->documentosExpedienteRepository = $documentosExpedienteRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocumentosExpediente.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        // Valida si tiene permisos de administrador
        // if(Auth::user()->hasRole('Admin Expedientes Electrónicos') || Auth::user()->hasRole('Operador Expedientes Electrónicos') || Auth::user()->hasRole('Consulta Expedientes Electrónicos')) {
            $infoExpediente = Expediente::with(["eeExpedienteHistorials", "serieClasificacionDocumental", "subserieClasificacionDocumental", "eePermisoUsuariosExpedientes" => function($query) {
                $query->where(function($n) {
                    // Datos del usuario en sesión
                    $user = Auth::user();
                    // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                    $n->where(function($subQuery) use ($user) {
                        // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                        $subQuery->where('dependencia_usuario_id', $user->id)->where('tipo', 'Usuario');
                    });
                    $n->orWhere(function($subQuery) use ($user) {
                        // Aplica la segunda condición: el 'dependencia_usuario_id' debe ser igual a la dependencia del usuario y el 'tipo' debe ser 'Dependencia'.
                        $subQuery->where('dependencia_usuario_id', $user->id_dependencia)->where('tipo', 'Dependencia');
                    });
                });
            }])->where('id', base64_decode($request['c']) )->get()->first()->toArray();
            // Id del usuario en sesión
            $user_id = Auth::user()->id;
            // $consecutivoExpediente = Expediente::select('consecutivo')->where('id', base64_decode($request['c']) )->first()->consecutivo;
            return view('expedienteselectronicos::documentos_expedientes.index', compact(['infoExpediente', "user_id"]))->with('c', $request['c']);
        // }

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
    public function all(Request $request) {
        // Decodifica los campos filtrados
        $filtros = str_replace(["'{","}'"], ["{","}"], base64_decode($request["f"]));

        $filtros_metadatos = "";

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

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros}

        // Valida si existen las variables del paginado y si esta filtrando
        if ((isset($request["f"]) && $request["f"] != "") || isset($request["pi"])) {
            $documentos_expedientes = DocumentosExpediente::where('ee_expediente_id', base64_decode($request['c']))->where('estado_doc', 'Asociado')->with(["eeTiposDocumentales", "eeExpediente", "documentoHasMetadatos", "documentoExpedienteAnotaciones", "anotacionesPendientes"])
            ->when($filtros_metadatos, function($query) use($filtros_metadatos) {
                $query->whereHas("documentoHasMetadatos", function($m) use($filtros_metadatos) {
                    $m->where(function ($n) use ($filtros_metadatos) {
                        // Recorre los filtros
                        foreach ($filtros_metadatos as $key => $valor_metatado) {
                            $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                // Separa el nombre del filtro(llave del array) para obtener el id del metadato
                                $de_metadatos_id = explode("_", $key);
                                $de_metadatos_id = end($de_metadatos_id);
                                $q->where("ee_metadatos_id", $de_metadatos_id); // Id del metadato en su nombre
                                $q->whereColumn("ee_documentos_expediente_id", "ee_documentos_expediente.id"); // Id del documento
                                $q->where("valor", "LIKE", "%".$valor_metatado."%"); // Valor del metadato ingresado en el filtro
                            });
                        }
                    })
                    ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                });
            })
            ->when(!Auth::user()->hasRole('Consulta Expedientes Electrónicos'), function ($query) {
                $query->where(function ($q) {
                    $q->where(function ($n) {
                        $n->whereHas('eeExpediente', function ($expedienteQuery) {
                            $expedienteQuery->where('id_responsable', '!=', Auth::user()->id)
                                            ->whereHas('eePermisoUsarExpedientesAux', function ($permisoQuery) {
                                                $permisoQuery->where('permiso', 'Incluir información y editar documentos (solo del usuario)');
                                                $permisoQuery->where('ee_documentos_expediente.users_id', Auth::user()->id);
                                            });
                        });
                    })
                    ->orWhereDoesntHave('eeExpediente', function ($expedienteQuery) {
                        $expedienteQuery->where('id_responsable', '!=', Auth::user()->id)
                                        ->whereHas('eePermisoUsarExpedientesAux', function ($permisoQuery) {
                                            $permisoQuery->where('permiso', 'Incluir información y editar documentos (solo del usuario)');
                                        });
                    });
                });
            })
            ->latest('orden_documento') // Ordenar por fecha de actualización
            ->paginate(base64_decode($request["pi"])); // Paginación
        } else {
            $documentos_expedientes = DocumentosExpediente::where('ee_expediente_id', base64_decode($request['c']))->where('estado_doc', 'Asociado')->with(["eeTiposDocumentales", "eeExpediente", "documentoHasMetadatos", "documentoExpedienteAnotaciones", "anotacionesPendientes"])
            ->when('eeExpediente.id_responsable' != Auth::user()->id && !Auth::user()->hasRole('Consulta Expedientes Electrónicos'), function($m) {
                $m->where(function($q) {
                    $q->where("DocumentosExpediente.info_documento.dependencia", Auth::user()->id_dependencia);
                });
            })
            ->latest('orden_documento') // Ordenar por fecha de actualización
            ->get()->toArray();
        }

        $count_documentos_expedientes = $documentos_expedientes->total(); // Total de documentos encontrados
        $documentos_expedientes = $documentos_expedientes->toArray()["data"]; // Consulta los documentos según los filtros
        return $this->sendResponseAvanzado($documentos_expedientes, trans('data_obtained_successfully'), null, ["total_registros" => $count_documentos_expedientes]);

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentosExpedienteRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentosExpedienteRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Origen del documento a incluir en el expediente
            $input["origen_creacion"] = "Nuevo documento";
            // Modulo origen de creación del documento que se va asociar al expediente
            $input["modulo_intraweb"] = "Expediente electrónico";
            // Vigencia del registro = Año actual
            $input["vigencia"] = date("Y");
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;
            // nombre del usuario que crea el registro
            $input["user_name"] = Auth::user()->fullname;
            // pone el estado del documento en asociado para que se pueda visualizar dentro del expediente
            $input["estado_doc"] = "Asociado";
            // Id del expdiente
            $input["ee_expediente_id"] = base64_decode($input["c"]);
            if($input["origen_creacion"] != "Nuevo documento" && empty($input["modulo_consecutivo"])){
                return $this->sendSuccess('<strong>Consecutivo inexistente.</strong>'. '<br>' . "Por favor ingrese un consecutivo que exista.", 'warning');
            }
            // Calcula el consecutivo del documento del expediente
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->count();
            if($input["origen_creacion"] != "Nuevo documento"){
                $documento_existente = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->where("modulo_consecutivo", $input["modulo_consecutivo"])->count();
                if($documento_existente > 0){
                    return $this->sendSuccess('<strong>Documento ya existe.</strong>'. '<br>' . "Este documento ya se encuentra asociado a este expediente.", 'warning');
                }
            }
            $input["orden_documento"] = $total_documentos+1;
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
            $ee_expediente_id = (int)$input["ee_expediente_id"];
            $expedienteLength = strlen($ee_expediente_id);

            if($expedienteNumberDigits > $expedienteLength) {
                $expedienteDigs = $expedienteNumberDigits - $expedienteLength;
                $formattedExpedienteId = str_repeat("0", $expedienteDigs) . $ee_expediente_id;
            } else {
                $formattedExpedienteId = $ee_expediente_id;
            }

            $input["consecutivo"] = $input["vigencia"] . $formattedExpedienteId . $norden;
            // Valida si se adjunto
            if($input["origen_creacion"] == "Nuevo documento"){
                if ($request->hasFile('adjunto')) {
                    $input['adjunto'] = substr($input['adjunto']->store('public/container/expedientes_electronicos_' . date("Y")), 7);
                }
            }

            if($input["origen_creacion"] != "Nuevo documento"){
                if (!empty($input["document_pdf"])) {
                    $input['adjunto'] = $input["document_pdf"];
                } else {
                    return $this->sendSuccess('<strong>El documento es requerido.</strong>'. '<br>' . "Por favor seleccione un consecutivo con adjunto.", 'warning');
                }
            }

            // Valida si el registro tiene mas de un adjunto relacionado
            if(is_array($input["adjunto"])) {
                // Convierte el array de adjuntos relacionados a una cadena separada por coma
                $input["adjunto"] = implode(",", $input["adjunto"]);
            }

            // Inserta el registro en la base de datos
            $documentosExpediente = $this->documentosExpedienteRepository->create($input);
            //Guarda el historial del registro
            $input["ee_documentos_expediente_id"] = $documentosExpediente->id;
            $historial = $input;
            $historial["accion"] = "Crear";
            $historial["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($historial);

            // Valida si tiene metadatos registrados
            if (!empty($input['metadatos'])) {
                // Recorre los metadatos
                foreach ($input['metadatos'] as $llave_metadato => $valor_metadato) {
                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["ee_metadatos_id"] = $llave_metadato;
                    $registroMetadatoArray["ee_expediente_id"] = $input["ee_expediente_id"];
                    $registroMetadatoArray["ee_documentos_expediente_id"] = $documentosExpediente->id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    ExpedienteHasMetadato::create($registroMetadatoArray);
                }
            }
            // Información del expediente relacionado al documento
            $expediente_info = $documentosExpediente->eeExpediente->toArray();
            // Se construye el asunto del correo en formato JSON, incluyendo el consecutivo del expediente
            $asunto = json_decode('{"subject": "Asociación de documento al expediente ' . $expediente_info["consecutivo"] . '"}');
            // Se obtiene el correo electrónico del responsable del expediente
            $email_responsable = User::where('id', $expediente_info["id_responsable"])->first()->email;
            // Se arma el arreglo de notificación con los datos necesarios
            $notificacion["id"] = $documentosExpediente->id;
            $notificacion["consecutivo"] = $documentosExpediente->consecutivo;
            $notificacion["state"] = $expediente_info['estado'];
            $notificacion["nombre_funcionario"] = $expediente_info["nombre_responsable"];
            $notificacion['id_encrypted'] = base64_encode($expediente_info["id"]);
            $notificacion["mail"] = $email_responsable;
            // Mensaje HTML que se mostrará en la notificación
            $notificacion['mensaje'] = "<p>Le informamos que se ha asociado el documento <strong>{$documentosExpediente->consecutivo}</strong> al expediente con consecutivo <strong>{$expediente_info['consecutivo']}</strong>, desde el módulo {$documentosExpediente->modulo_intraweb}";
            // Envío de la notificación usando el controlador correspondiente y plantilla de correo
            SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email_responsable, 'Expedientes electrónicos');
            // Recarga las relaciones
            $documentosExpediente->eeTiposDocumentales;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentosExpediente->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentosExpedienteRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentosExpedienteRequest $request) {

        $input = $request->all();

        /** @var DocumentosExpediente $documentosExpediente */
        $documentosExpediente = $this->documentosExpedienteRepository->find($id);

        if (empty($documentosExpediente)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $documentosExpediente = $this->documentosExpedienteRepository->update($input, $id);
            $input["ee_documentos_expediente_id"] = $documentosExpediente->id;
            $historial = $input;
            $historial["accion"] = "actualiza";
            $historial["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($historial);
            $documentosExpediente->eeTiposDocumentales;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentosExpediente->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Elimina un DocumentosExpediente del almacenamiento
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

        /** @var DocumentosExpediente $documentosExpediente */
        $documentosExpediente = $this->documentosExpedienteRepository->find($id);

        if (empty($documentosExpediente)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $documentosExpediente->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
        $data_decode = JwtController::decodeToken($input["data"]);
        if(array_key_exists("filtros", $input)) {
            if($input["filtros"] != "") {
                $data = DocumentosExpediente::with('eeTiposDocumentales')->where("ee_expediente_id", $data_decode[0]->ee_expediente_id)->whereRaw($input["filtros"])->latest()->get()->toArray();
            } else {
                $data = DocumentosExpediente::with('eeTiposDocumentales')->where("ee_expediente_id", $data_decode[0]->ee_expediente_id)->latest()->get()->toArray();
            }
        }
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('documentos_expedientes').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // $data = DocumentosExpediente::latest()->get()->toArray();

            //nueva clave para lista de correspondencia
            $input['expedientes_documentos'] = [];

            // $infor_expediente = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials"])->where("id", $data[0]["ee_expediente_id"])->first()->get()->toArray();
            $infor_expediente = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials"])->where("id", $data[0]["ee_expediente_id"])->get()->toArray();

            //ciclo para agregar las correspondencia a los usuarios
            foreach($data as $item){
                //objecto vacio para incluir elementos necesarios
                $object_document_expediente = new stdClass;
                $object_document_expediente->consecutivo = $item['consecutivo'] ?? '';
                $object_document_expediente->nombre_documento = $item['nombre_documento'] ?? '';
                $object_document_expediente->ee_tipos_documentales_id = $item["ee_tipos_documentales"]["name"] ?? 'N/A';
                $object_document_expediente->created_at = $item['created_at'] ?? '';
                $object_document_expediente->fecha_documento = $item['fecha_documento'] ?? '';
                $object_document_expediente->orden_documento = $item['orden_documento'] ?? '';
                $object_document_expediente->pagina_inicio = $item['pagina_inicio'] ?? '';
                $object_document_expediente->pagina_fin = $item['pagina_fin'] ?? '';
                $object_document_expediente->hash_value = $item['hash_value'] ?? '';
                $object_document_expediente->hash_algoritmo = $item['hash_algoritmo'] ?? '';
                $object_document_expediente->descripcion = $item['descripcion'] ?? '';
                $object_document_expediente->adjunto = $item['adjunto'] ?? '';
                $object_document_expediente->modulo_intraweb = $item['modulo_intraweb'] ?? '';
                $object_document_expediente->formato_corr_recibida_email = $item['modulo_intraweb'] == "Correspondencia recibida" && empty($item['adjunto']) && !empty($item['info_documento']) && $item['info_documento'][0]['channel_name'] == 'Correo electrónico' ? 'Email' : null;

                array_push($input['expedientes_documentos'],$object_document_expediente);
            }

            //elimina el atributo data
            unset($input['data']);

            try {
                $filePDF = PDF::loadView(
                    'expedienteselectronicos::doc_expediente_historials.report_pdf',
                    [
                        'data' => $input['expedientes_documentos'],
                        'info_expediente' => $infor_expediente
                    ]
                )->setPaper('a3', 'landscape');
                return $filePDF->download("documentos_expedientes.pdf");
            } catch (\Exception $e) {
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
            }

        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function tiposDocumentales($encripteId) {
        $infoExpediente = Expediente::where('id', base64_decode($encripteId))->get()->first()->toArray();
        $tipoDocumentales = documentarySerieSubseries::select('cd_type_documentaries.*','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries')
        ->join('cd_type_documentaries', 'cd_type_documentaries_has_cl_series_subseries.id_type_documentaries', '=', 'cd_type_documentaries.id')
        ->where('id_series_subseries', isset($infoExpediente["classification_subserie"]) && !empty($infoExpediente["classification_subserie"])
            ? $infoExpediente["classification_subserie"]
            : $infoExpediente["classification_serie"]
        )
        ->get();
        //  Agregar los criterios de búsqueda a cada tipo documental
        foreach ($tipoDocumentales as $documento) {
            $documento->criterios_busqueda = DB::table('cd_criterios_busqueda_has_cd_series_subseries')
                ->join('cd_criterios_busqueda', 'cd_criterios_busqueda_has_cd_series_subseries.cd_criterios_busqueda_id', '=', 'cd_criterios_busqueda.id')
                ->where('cd_criterios_busqueda_has_cd_series_subseries.cd_type_documentaries', $documento->id)
                ->select('cd_criterios_busqueda.*') // Traer todos los datos de la tabla
                ->get();
        }

        // dd($tipoDocumentales->toArray());
        return $this->sendResponse($tipoDocumentales->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los tipos documentales según la serie y subserie recibida por parámetro
     *
     * @author Seven Soluciones Informáticas S.A.S - Mar. 12 - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function tiposDocumentalesExpedienteCreado($serieId, $subSerieId = null) {
        // dd("sass");

        if($subSerieId == null){
            $subSerieId = $serieId;
        }
        $tipoDocumentales = documentarySerieSubseries::select('cd_type_documentaries.*','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries')
        ->join('cd_type_documentaries', 'cd_type_documentaries_has_cl_series_subseries.id_type_documentaries', '=', 'cd_type_documentaries.id')
        ->where('id_series_subseries', !empty($subSerieId)
            ? $subSerieId
            : $serieId
        )
        ->get();

        //  Agregar los criterios de búsqueda a cada tipo documental
        foreach ($tipoDocumentales as $documento) {
            $documento->criterios_busqueda = DB::table('cd_criterios_busqueda_has_cd_series_subseries')
                ->join('cd_criterios_busqueda', 'cd_criterios_busqueda_has_cd_series_subseries.cd_criterios_busqueda_id', '=', 'cd_criterios_busqueda.id')
                ->where('cd_criterios_busqueda_has_cd_series_subseries.cd_type_documentaries', $documento->id)
                ->select('cd_criterios_busqueda.*') // Traer todos los datos de la tabla
                ->get();
        }

        // dd($tipoDocumentales->toArray());
        return $this->sendResponse($tipoDocumentales->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Actualiza un registro especifico
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 4 - 2024
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentoRequest $request
     *
     * @return Response
     */
    public function updateFile($id, UpdateDocumentoRequest $request)
    {
        $input = $request->all();
        dd($input);

        /** @var Documento $documento */
        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['document_pdf'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $documento = $this->documentoRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
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
    public function moduloPqrs(Request $request) {
        $query = $request->input('query');
        // Obtiene los pqrs en donde el estado sea igual a finalizado y tenga un documento
        $pqr = PQR::where('pqr_id','like','%'.$query.'%')->where("estado", "Finalizado")->where("document_pdf", "!=", "")->get()->toArray();
        return $this->sendResponse($pqr, trans('data_obtained_successfully'));
    }


    /**
     * Obtiene los registros
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function moduloInterna(Request $request) {
        $query = $request->input('query');
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $interna = Internal::where('consecutive','like','%'.$query.'%')->where("state", "Público")->where("document_pdf", "!=", "")->get()->toArray();
        return $this->sendResponse($interna, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los registros
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function moduloRecibida(Request $request) {
        $query = $request->input('query');
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $recibida = ExternalReceived::where('consecutive','like','%'.$query.'%')->where("state", "3")->where("document_pdf", "!=", "")->get()->toArray();
        return $this->sendResponse($recibida, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los registros
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function moduloEnviada(Request $request) {
        $query = $request->input('query');
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $recibida = External::where('consecutive','like','%'.$query.'%')->where("state", "Público")->where("document_pdf", "!=", "")->get()->toArray();
        return $this->sendResponse($recibida, trans('data_obtained_successfully'));
    }


    /**
     * Obtiene los registros
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function moduloDocumentoselectronicos(Request $request) {
        $query = $request->input('query');
        // Obtiene las internas las cuales esten en estado publicas y tengas documento
        $recibida = Documento::where('consecutivo','like','%'.$query.'%')->where("estado", "Público")->where("document_pdf", "!=", "")->get()->toArray();
        return $this->sendResponse($recibida, trans('data_obtained_successfully'));
    }

    /**
     * Guarda los documento al expediente
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function asociarDocumento(Request $request, $id_expediente) {

        $input = $request->all();

        try{
            $input["ee_expediente_id"] = base64_decode($id_expediente);
            // Vigencia del registro = Año actual
            $input["vigencia"] = date("Y");
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;
            // nombre del usuario que crea el registro
            $input["user_name"] = Auth::user()->fullname;
            // pone el estado del documento en asociado para que se pueda visualizar dentro del expediente
            $input["estado_doc"] = "Asociado";
            // Origen del documento
            $input["origen_creacion"] = "Módulo de intraweb";
            // consecutivo del modulo que entra
            $input["modulo_consecutivo"] = $input["modulo_consecutivo"];
            // Nombre del documento es igual al consecutivo
            $input["nombre_documento"] = $input["modulo_consecutivo"];
            // Modulo de la intraweb
            $input["modulo_intraweb"] = $input["modulo_intraweb"];
            // Fecha del documento
            $input["fecha_documento"] = date("Y-m-d H:i:s");
            // Calcula el consecutivo del documento del expediente
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->count();
            // Guardar el orden del consecutivo
            $input["orden_documento"] = $total_documentos+1;

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
            $ee_expediente_id = (int)$input["ee_expediente_id"];
            $expedienteLength = strlen($ee_expediente_id);

            if($expedienteNumberDigits > $expedienteLength) {
                $expedienteDigs = $expedienteNumberDigits - $expedienteLength;
                $formattedExpedienteId = str_repeat("0", $expedienteDigs) . $ee_expediente_id;
            } else {
                $formattedExpedienteId = $ee_expediente_id;
            }

            $input["consecutivo"] = $input["vigencia"] . $formattedExpedienteId . $norden;

            $documento_existente = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->where("modulo_consecutivo", $input["modulo_consecutivo"])->count();
            if($documento_existente > 0){
                return "existe";
            }
            // Si el registro tiene un adjunto relacionado, entra a la condición
            if(!empty($input["adjunto"])) {
                // Convierte en array la cadena de adjuntos en caso tal que sean mas de uno
                $adjuntos = explode(",", $input["adjunto"]);
                // dd(reset($adjuntos));
                // Obtiene la ruta completa del primer documento (sea local o S3)
                $archivo = $this->getDocumentPath(reset($adjuntos));
                // Si la ruta contiene la palabra "storage" quiere decir que el archivo está local
                if(strpos($archivo, "/storage/") !== false) {
                    // Se arma la ruta absoluta local al archivo
                    $archivo = storage_path("app/public/".reset($adjuntos));
                }
                // Se obtiene el contenido del documento
                $fileContents = file_get_contents($archivo);
                // Calcula el hash en md5 del contenido del documento
                $hash = md5($fileContents);
                // Se asigna el hassh y el método de codificación del documento, para su posterior inserción
                $input["hash_value"] = $hash;
                $input["hash_algoritmo"] = 'MD5';
            } else {
                // Si no tiene un adjunto relacionado, no aplica el hash
                $input["hash_value"] = 'N/A';
                $input["hash_algoritmo"] = 'N/A';
            }
            
            $guardarDoc = DocumentosExpediente::create($input);
            $input["ee_documentos_expediente_id"] = $guardarDoc->id;
            $historial = $input;
            $historial["accion"] = "Crear";
            $historial["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($historial);
            $expediente_info = Expediente::where("id", $input["ee_expediente_id"])->first()->toArray();
            $expediente_info["ee_expediente_id"] = $expediente_info["id"];
            $expediente_info["users_id"] = $input["users_id"];
            $expediente_info["user_name"] = $input["user_name"];
            $expediente_info["detalle_modificacion"] = "Se asoció la correspondencia ".$input["modulo_consecutivo"]." del módulo ".$input["modulo_intraweb"];
            ExpedienteHistorial::create($expediente_info);

            // Valida si tiene metadatos registrados
            if (!empty($input['metadatos'])) {
                // Recorre los metadatos
                foreach ($input['metadatos'] as $llave_metadato => $valor_metadato) {
                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["ee_metadatos_id"] = $llave_metadato;
                    $registroMetadatoArray["ee_expediente_id"] = $expediente_info["id"];
                    $registroMetadatoArray["ee_documentos_expediente_id"] = $guardarDoc->id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    ExpedienteHasMetadato::create($registroMetadatoArray);
                }
            }
            // Se construye el asunto del correo en formato JSON, incluyendo el consecutivo del expediente
            $asunto = json_decode('{"subject": "Asociación de documento al expediente ' . $expediente_info["consecutivo"] . '"}');
            // Se obtiene el correo electrónico del responsable del expediente
            $email_responsable = User::where('id', $expediente_info["id_responsable"])->first()->email;
            // Se arma el arreglo de notificación con los datos necesarios
            $notificacion["id"] = $guardarDoc->id;
            $notificacion["consecutivo"] = $guardarDoc->consecutivo;
            $notificacion["state"] = $expediente_info['estado'];
            $notificacion["nombre_funcionario"] = $expediente_info["nombre_responsable"];
            $notificacion['id_encrypted'] = base64_encode($expediente_info["id"]);
            $notificacion["mail"] = $email_responsable;
            // Mensaje HTML que se mostrará en la notificación
            $notificacion['mensaje'] = "<p>Le informamos que se ha asociado el documento <strong>{$guardarDoc->consecutivo}</strong> al expediente con consecutivo <strong>{$expediente_info['consecutivo']}</strong>, desde el módulo {$guardarDoc->modulo_intraweb}";
            // Envío de la notificación usando el controlador correspondiente y plantilla de correo
            SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email_responsable, 'Expedientes electrónicos');
            return $this->sendResponse($input, trans('data_obtained_successfully'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Cambia el estado al momento de darle eliminar documento
     *
     * @author Manuel marin. - Julio. 19 - 2024
     * @version 1.0.0
     *
     *
     * @return Response
     */
    public function cambiarEstadoDocumento(int $documentoId, string $observacion = ""){
        try{

            $documentosExpediente = $this->documentosExpedienteRepository->update(["adjunto" => "Eliminado"], $documentoId);
            $historial = $documentosExpediente->toArray();

            // Crear un registro de un historial
            $historial["ee_documentos_expediente_id"] = $documentosExpediente->id;
            $historial["accion"] = "Eliminar";
            $historial["justificacion"] = $observacion;
            DocExpedienteHistorial::create($historial);

            $documentosExpediente->eeTiposDocumentales;
            $documentosExpediente->eeExpediente;

            return $this->sendResponse($documentosExpediente->toArray(), trans('msg_success_save'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Obtiene los detalles para la vista del blade
     *
     * @author Manuel marin. - Julio. 19 - 2024
     * @version 1.0.0
     *
     *
     * @return Response
     */
    public function show($id)
    {
        $documento = $this->documentosExpedienteRepository->find($id);
        $documento->eeExpediente;
        $documento->documentoHasMetadatos;
        $documento->documentoExpedienteAnotaciones;
        //Inicia varray
        $informacion_correspondencia = [];
        //Valida con empty que no venga vacio ese append
        if (!empty($documento->info_documento)) {
            $informacion_correspondencia = $documento->info_documento->toArray();
        }
        //Valida al informacion de la correspondeia
        if (empty($informacion_correspondencia)) {
            $informacion_correspondencia[0] = [];
        }
        // Agregar documento_expediente al primer elemento del array
        $informacion_correspondencia[0]["documento_expediente"] = $documento;
        return $this->sendResponse($informacion_correspondencia[0], trans('data_obtained_successfully'));
    }

    /**
     * Crea el expediente y asocia el documento el cual se le realizo al accion
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function crearExpedienteAsociar(Request $request) {

        $input = $request->all();

        try {
            if($input["id_responsable"] == Auth::user()->id) {
                $input["estado"] = "Abierto";
                $input["estado_doc"] = "Asociado";
            } else {
                $input["estado"] = "Pendiente de firma";
                $input["estado_doc"] = "En cola";
            }

            $createExpedienteRequest = new CreateExpedienteRequest();
            // Condiciona que no se guarden los metadatos en el store de la clase ExpedienteController
            $input["guardar_metadatos"] = false;
            $createExpedienteRequest->merge($input);
            $expediente = app(ExpedienteController::class)->store($createExpedienteRequest);
            $expediente= JwtController::decodeToken($expediente["data"]);

            $expediente = array('data' => (array) $expediente);
            // Crea una instancia de tipo Request para enviar a la función 'aprobarFirmarExpedientes' de la clase 'ExpedienteController'
            $request_documento = new Request($expediente["data"]);
            $request_documento["type_send"] = "Aprobar Firma";
            // Validad si el responsable es el mismo usuario que está en sesión (creando el expediente)
            if($input["id_responsable"] == Auth::user()->id) {
                // Aprueba el expediente firmándolo, ya que el responsable es el mismo usuario que está creando el expediente
                $expediente_firmado = (array) app(ExpedienteController::class)->aprobarFirmarExpedientes($request_documento);
                // Valida el tipo de retorno, si es 'info' hubo un problema con la firma del expediente
                if($expediente_firmado["type_message"] == "info")
                        return $expediente_firmado;
            }

            $input["ee_expediente_id"] = $expediente["data"]["id"];
            // Vigencia del registro = Año actual
            $input["vigencia"] = date("Y");
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;
            // nombre del usuario que crea el registro
            $input["user_name"] = Auth::user()->fullname;
            // Origen
            $input["origen_creacion"] = "Módulo de intraweb";
            // consecutivo del modulo que entra
            $input["modulo_consecutivo"] = $input["modulo_consecutivo"];
            // Nombre del documento es igual al consecutivo
            $input["nombre_documento"] = $input["modulo_consecutivo"];
            // Modulo de la intraweb
            $input["modulo_intraweb"] = $input["modulo_intraweb"];
            // Fecha del documento
            $input["fecha_documento"] = date("Y-m-d H:i:s");
            // Calcula el consecutivo del documento del expediente
            $total_documentos = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->count();
            // Guardar el orden del consecutivo
            $input["orden_documento"] = $total_documentos+1;

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
            $ee_expediente_id = (int)$input["ee_expediente_id"];
            $expedienteLength = strlen($ee_expediente_id);

            if($expedienteNumberDigits > $expedienteLength) {
                $expedienteDigs = $expedienteNumberDigits - $expedienteLength;
                $formattedExpedienteId = str_repeat("0", $expedienteDigs) . $ee_expediente_id;
            } else {
                $formattedExpedienteId = $ee_expediente_id;
            }

            $input["consecutivo"] = $input["vigencia"] . $formattedExpedienteId . $norden;

            $documento_existente = DocumentosExpediente::where("ee_expediente_id", $input["ee_expediente_id"])->where("modulo_consecutivo", $input["modulo_consecutivo"])->count();
            if($documento_existente > 0){
                return "existe";
            }

            // Si el registro tiene un adjunto relacionado, entra a la condición
            if(!empty($input["adjunto"])) {
                // Convierte en array la cadena de adjuntos en caso tal que sean mas de uno
                $adjuntos = explode(",", $input["adjunto"]);
                // Obtiene la ruta completa del primer documento (sea local o S3)
                $archivo = $this->getDocumentPath(reset($adjuntos));
                // Si la ruta contiene la palabra "storage" quiere decir que el archivo está local
                if(strpos($archivo, "/storage/") !== false) {
                    // Se arma la ruta absoluta local al archivo
                    $archivo = storage_path("app/public/".reset($adjuntos));
                }
                // Se obtiene el contenido del documento
                $fileContents = file_get_contents($archivo);
                // Calcula el hash en md5 del contenido del documento
                $hash = md5($fileContents);
                // Se asigna el hassh y el método de codificación del documento, para su posterior inserción
                $input["hash_value"] = $hash;
                $input["hash_algoritmo"] = 'MD5';
            } else {
                // Si no tiene un adjunto relacionado, no aplica el hash
                $input["hash_value"] = 'N/A';
                $input["hash_algoritmo"] = 'N/A';
            }

            $guardarDoc = DocumentosExpediente::create($input);
            $input["ee_documentos_expediente_id"] = $guardarDoc->id;
            $historial = $input;
            $historial["accion"] = "Crear";
            $historial["justificacion"] = "No aplica";
            DocExpedienteHistorial::create($historial);
            // Valida si tiene metadatos registrados
            if (!empty($input['metadatos'])) {
                // Recorre los metadatos
                foreach ($input['metadatos'] as $llave_metadato => $valor_metadato) {
                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["ee_metadatos_id"] = $llave_metadato;
                    $registroMetadatoArray["ee_expediente_id"] = $ee_expediente_id;
                    $registroMetadatoArray["ee_documentos_expediente_id"] = $guardarDoc->id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    ExpedienteHasMetadato::create($registroMetadatoArray);
                }
            }

            // Información del expediente relacionado al documento
            $expediente_info = $expediente["data"];
            // Se construye el asunto del correo en formato JSON, incluyendo el consecutivo del expediente
            $asunto = json_decode('{"subject": "Asociación de documento al expediente ' . $expediente_info["consecutivo"] . '"}');
            // Se obtiene el correo electrónico del responsable del expediente
            $email_responsable = User::where('id', $expediente_info["id_responsable"])->first()->email;
            // Se arma el arreglo de notificación con los datos necesarios
            $notificacion["id"] = $guardarDoc->id;
            $notificacion["consecutivo"] = $guardarDoc->consecutivo;
            $notificacion["state"] = $expediente_info['estado'];
            $notificacion["nombre_funcionario"] = $expediente_info["nombre_responsable"];
            $notificacion['id_encrypted'] = base64_encode($expediente_info["id"]);
            $notificacion["mail"] = $email_responsable;
            // Mensaje HTML que se mostrará en la notificación
            $notificacion['mensaje'] = "<p>Le informamos que se ha asociado el documento <strong>{$guardarDoc->consecutivo}</strong> al expediente con consecutivo <strong>{$expediente_info['consecutivo']}</strong>, desde el módulo {$guardarDoc->modulo_intraweb}";
            // Envío de la notificación usando el controlador correspondiente y plantilla de correo
            SendNotificationController::SendNotification('expedienteselectronicos::expedientes.emails.plantilla_notificaciones', $asunto, $notificacion, $email_responsable, 'Expedientes electrónicos');
            return $this->sendResponse($input, trans('data_obtained_successfully'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Obtiene todos los elementos existentes de tipos documentales
     *
     * @author Seven Soluciones Informáticas S.A.S - Feb. 28 - 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerTiposDocumentalesFiltro() {
        $tipoDocumentales = documentarySerieSubseries::select('cd_type_documentaries.*','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries')
        ->join('cd_type_documentaries', 'cd_type_documentaries_has_cl_series_subseries.id_type_documentaries', '=', 'cd_type_documentaries.id')
        ->get();
        // Retorna los tipos documentales obtenidos
        return $this->sendResponse($tipoDocumentales->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los usuarios que han asociado o creado un documento a un expediente
     *
     * @author Seven Soluciones Informáticas. - Mar. 13. 2025
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsersAsocioDocumentoFiltro($id_expediente)
    {
        // Decodifica el id del expediente recibido por parámetro
        $id_expediente = base64_decode($id_expediente);
        // Filtra a todos los usuarios según las codiciones del id_expediente y que tengan relación con algún documento asociado
        $users = User::select("users.id", "users.name", "users.id_dependencia", "users.id_cargo")->join('ee_documentos_expediente', function($join) use($id_expediente) {
            $join->on('ee_documentos_expediente.users_id', '=', 'users.id')
            ->where('ee_documentos_expediente.ee_expediente_id', $id_expediente);
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
     * Muestra la vista para el CRUD de DocumentosExpediente a los usuarios externos
     *
     * @author Desarrollador Seven - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexDocumentosUsuarioExterno(Request $request) {
        // Si la sesión no tiene un pin de acceso, retorna el usuario a la vista login
        if(!session('pin_acceso')) {
            return view('auth.login_exteno_expedientes');
        }
        // Obtiene la información del expediente según el código encriptado recibido por parámetro
        $infoExpediente = Expediente::with(["eeExpedienteHistorials", "serieClasificacionDocumental", "subserieClasificacionDocumental", "eePermisoUsuariosExpedientes" => function($query) {
            $query->where(function($n) {
                // Filtra los expedientes que tienen permisos de uso asociados al ID de la dependencia o usuario.
                $n->where(function($subQuery) {
                    // Aplica la primera condición: el 'dependencia_usuario_id' debe ser igual al ID del usuario y el 'tipo' debe ser 'Usuario'.
                    $subQuery->where('id', session('id'))->where('tipo', 'Usuario');
                });
            });
        }])->where('id', base64_decode($request['c']) )->get()->first()->toArray();

        return view('expedienteselectronicos::documentos_expedientes.index_usuario_externo', compact(['infoExpediente']))->with('c', $request['c']);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allDocumentosUsuarioExterno(Request $request) {
        // Si la sesión no tiene un pin de acceso, retorna el usuario a una vista de forbidden
        if(!session('pin_acceso')) {
            return view("auth.forbidden");
        }
        // Decodifica los campos filtrados
        $filtros = str_replace(["'{","}'"], ["{","}"], base64_decode($request["f"]));

        $filtros_metadatos = "";

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

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        // Valida si existen las variables del paginado y si esta filtrando
        if ((isset($request["f"]) && $request["f"] != "") || isset($request["pi"])) {
            $documentos_expedientes = DocumentosExpediente::where('ee_expediente_id', base64_decode($request['c']))->where('estado_doc', 'Asociado')->with(["eeTiposDocumentales", "eeExpediente", "documentoHasMetadatos", "documentoExpedienteAnotaciones"])
            ->when($filtros_metadatos, function($query) use($filtros_metadatos) {
                $query->whereHas("documentoHasMetadatos", function($m) use($filtros_metadatos) {
                    $m->where(function ($n) use ($filtros_metadatos) {
                        // Recorre los filtros
                        foreach ($filtros_metadatos as $key => $valor_metatado) {
                            $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                // Separa el nombre del filtro(llave del array) para obtener el id del metadato
                                $de_metadatos_id = explode("_", $key);
                                $de_metadatos_id = end($de_metadatos_id);
                                $q->where("ee_metadatos_id", $de_metadatos_id); // Id del metadato en su nombre
                                $q->whereColumn("ee_documentos_expediente_id", "ee_documentos_expediente.id"); // Id del documento
                                $q->where("valor", "LIKE", "%".$valor_metatado."%"); // Valor del metadato ingresado en el filtro
                            });
                        }
                    })
                    ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                });
            })
            ->when(session()->get('permiso') == 'Incluir información y editar documentos (solo del usuario)', function ($query) {
                $query->where('tipo_usuario', 'Externo')->where('users_id', session()->get('id'));
            })
            ->latest('orden_documento') // Ordenar por fecha de actualización
            ->paginate(base64_decode($request["pi"])); // Paginación
        } else {
            $documentos_expedientes = DocumentosExpediente::where('ee_expediente_id', base64_decode($request['c']))->where('estado_doc', 'Asociado')->with(["eeTiposDocumentales", "eeExpediente", "documentoHasMetadatos", "documentoExpedienteAnotaciones"])
            ->when('eeExpediente.id_responsable' != Auth::user()->id && !Auth::user()->hasRole('Consulta Expedientes Electrónicos'), function($m) {
                $m->where(function($q) {
                    $q->where("DocumentosExpediente.info_documento.dependencia", Auth::user()->id_dependencia);
                });
            })
            ->when(session()->get('permiso') == 'Incluir información y editar documentos (solo del usuario)', function ($query) {
                $query->where('tipo_usuario', 'Externo')->where('users_id', session()->get('id'));
            })
            ->latest('orden_documento') // Ordenar por fecha de actualización
            ->get()->toArray();
        }

        $count_documentos_expedientes = $documentos_expedientes->total(); // Total de documentos encontrados
        $documentos_expedientes = $documentos_expedientes->toArray()["data"]; // Consulta los documentos según los filtros
        return $this->sendResponseAvanzado($documentos_expedientes, trans('data_obtained_successfully'), null, ["total_registros" => $count_documentos_expedientes]);

    }

    /**
     * Registra el leído de un usuario a las anotaciones de un documento_expediente_id
     *
     * @author Seven Soluciones Informáticas S.A.S. Ago. 04 - 2025
     * @version 1.0.0
     *
     * @param int $ee_documentos_expediente_id
     *
     * @return Response
     */
    public function leidoAnotacionDocumentoExpediente($ee_documentos_expediente_id) {
        // Obtener el ID del usuario actual
        $userId = Auth::check() ? Auth::id() : 0;
        // Actualizar los registros directamente en la base de datos
        $result_documento_expediente_anotacion_leido = DocumentoExpedienteAnotacion::where('ee_documentos_expediente_id', $ee_documentos_expediente_id)
            ->where(function ($query) use ($userId) { 
                $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                    ->orWhere(function ($n) use ($userId) { 
                        $n->whereNot('leido_por', $userId)->where('leido_por', 'not like', '%,' . $userId . ',%')->where('leido_por', 'not like', $userId . ',%')->where('leido_por', 'not like', '%,' . $userId);
                    });
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        // Se obtiene el documento asociado al expediente con sus relaciones actualizadas
        $documento = $this->documentosExpedienteRepository->find($ee_documentos_expediente_id);
        $documento->eeExpediente;
        $documento->documentoHasMetadatos;
        $documento->documentoExpedienteAnotaciones;
        $documento->anotacionesPendientes;
        
        return $this->sendResponse($documento->toArray(), "Anotación del documento leída con éxito");
    }

    /**
     * Guarda una nueva anotación asociada a un documento de un expediente
     *
     * @author Seven Soluciones Informáticas S.A.S. Ago. 04 - 2025
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function guardarAnotacionDocumentoExpediente(Request $request, $documentoExpedienteId) {
        // Obtiene los valores de los campos
        $input = $request->all();
        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["anotacion"],"<p") !== false){
            $input["anotacion"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["anotacion"]);
            $input["anotacion"] = preg_replace('/<\/p>/i', '</span>', $input["anotacion"]);
        }

        // Asigna el ID del expediente para la relación con la anotación
        $input["ee_documentos_expediente_id"] = $documentoExpedienteId;
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
            $documentoExpedienteAnotacion = DocumentoExpedienteAnotacion::create($input);
            
            $documentoExpedienteAnotacion->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documentoExpedienteAnotacion->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param int $ee_expediente_id id del expediente a descargar
     * @param Request $request datos recibidos
     */
    public function descargarDocumentosExpediente($ee_expediente_id, Request $request)
    {
        try {
            $input = $request->all();
            $ee_expediente_id = base64_decode($ee_expediente_id);
            // Obtiene los documentos del expediente con relación
            $data = DocumentosExpediente::with('eeTiposDocumentales')
                ->where("ee_expediente_id", $ee_expediente_id)
                ->when(!empty($input["filtros"]), function ($query) use ($input) {
                    $query->whereRaw($input["filtros"]);
                })
                ->latest()->get();
            // Generar índice electrónico (PDF)
            $input['expedientes_documentos'] = [];
            foreach ($data as $item) {
                $object = new \stdClass;
                $object->consecutivo = $item->consecutivo;
                $object->nombre_documento = $item->nombre_documento;
                $modulo = $this->normalizarTexto($item->modulo_intraweb ?? 'Otros');
                $object->ubicacion_documento = $this->transformarRutaAdjuntos($item->adjunto, $modulo);
                $object->ee_tipos_documentales_id = $item->eeTiposDocumentales->name ?? 'N/A';
                $object->created_at = $item->created_at;
                $object->fecha_documento = $item->fecha_documento;
                $object->orden_documento = $item->orden_documento;
                $object->pagina_inicio = $item->pagina_inicio;
                $object->pagina_fin = $item->pagina_fin;
                $object->hash_value = $item->hash_value;
                $object->hash_algoritmo = $item->hash_algoritmo;
                $object->descripcion = $item->descripcion;
                $object->adjunto = $item->adjunto;
                $object->modulo_intraweb = $item->modulo_intraweb;
                $object->formato_corr_recibida_email = ($item->modulo_intraweb == "Correspondencia recibida" && empty($item->adjunto) && !empty($item->info_documento) && $item->info_documento[0]['channel_name'] == 'Correo electrónico') ? 'Email' : null;

                $input['expedientes_documentos'][] = $object;
            }

            // Info expediente
            $infor_expediente = Expediente::with(["users","dependencias","oficinaProductoraClasificacionDocumental","subserieClasificacionDocumental","serieClasificacionDocumental","eeExpedienteHistorials"])->where("id", $ee_expediente_id)->get()->toArray();
            
            // Nombre base
            $timestamp = time();
            $folderName = "expediente_{$timestamp}";
            $storagePath = storage_path("app/temp_zip/{$folderName}");
            File::makeDirectory($storagePath, 0755, true, true);
            
            // Guardar PDF índice
            $pdfPath = "{$storagePath}/indice_electronico.pdf";
            PDF::loadView('expedienteselectronicos::doc_expediente_historials.indice_electronico_docs_descargar', [
                'data' => $input['expedientes_documentos'],
                'info_expediente' => $infor_expediente
            ])
                ->setPaper('a3', 'landscape')
                ->save($pdfPath);
            // Copiar documentos según módulo_intraweb
            foreach ($data as $doc) {
                if (empty($doc->adjunto)) continue;

                $modulo = $doc->modulo_intraweb ?? 'Otros';
                $modulo = $this->normalizarTexto($modulo); // quita tildes
                $moduloFolder = "{$storagePath}/{$modulo}";
                File::makeDirectory($moduloFolder, 0755, true, true);

                // Soporta múltiples adjuntos separados por coma
                $adjuntos = explode(',', $doc->adjunto);
                foreach ($adjuntos as $adjunto) {
                    $adjunto = trim($adjunto);
                    if (empty($adjunto)) continue;

                    $adjuntoPath = storage_path("app/public/{$adjunto}");
                    if (File::exists($adjuntoPath)) {
                        // Extrae el nombre base del archivo (incluye extensión)
                        $baseFileName = basename($adjunto);
                        // Previene conflictos de nombres duplicados
                        $finalName = $doc->consecutivo ? $doc->consecutivo . '_' . $baseFileName : $baseFileName;
                        $finalName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $finalName);

                        // Copiar el archivo al directorio del módulo
                        File::copy($adjuntoPath, "{$moduloFolder}/{$finalName}");
                    }
                }
            }

            // Crear ZIP
            $zipPath = storage_path("app/temp_zip/expediente_{$timestamp}.zip");
            $zip = new ZipArchive;

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($files as $file) {
                    /** @var SplFileInfo $file */
                    if (!$file->isFile()) continue;

                    // Ruta relativa dentro del zip (evita rutas del sistema)
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($storagePath) + 1);

                    $zip->addFile($filePath, $relativePath);
                }

                $zip->close();
            } else {
                return response()->json(['message' => 'No se pudo crear el archivo ZIP.'], 500);
            }

            // Descargar ZIP y limpiar
            return response()->file($zipPath, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="expediente_'.$timestamp.'.zip"',
            ])->deleteFileAfterSend(true);
        } catch(\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocumentosExpedienteController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
            // Retorna error de tipo lógico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    function transformarRutaAdjuntos($cadena, $modulo_intraweb) {
        $rutas = explode(',', $cadena);
        $resultado = [];

        foreach ($rutas as $ruta) {
            $ruta = trim($ruta);
            $nombreArchivo = basename($ruta); // obtiene solo el nombre del archivo
            $resultado[] = "* {$modulo_intraweb}/{$nombreArchivo}";
        }

        return implode('<br>', $resultado);
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $ee_expediente_id id del expediente a descargar
     * @param Request $request datos recibidos
     */
    function normalizarTexto($texto) {
        // Reemplaza tildes por vocales sin tilde
        $acentos = ['á','é','í','ó','ú','Á','É','Í','Ó','Ú','ñ','Ñ'];
        $sinAcentos = ['a','e','i','o','u','A','E','I','O','U','n','N'];
        $ruta_documento = str_replace($acentos, $sinAcentos, $texto);
        return preg_replace('/[^A-Za-z0-9 _\-]/', '_', $ruta_documento); // luego quita caracteres especiales, pero deja las letras limpias
    }
}
