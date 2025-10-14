<?php

namespace Modules\DocumentosElectronicos\Http\Controllers;

use App\Exports\documentos_electronicos\RequestExport;
use App\Exports\GenericExport;
use Modules\DocumentosElectronicos\Http\Requests\CreateDocumentoRequest;
use Modules\DocumentosElectronicos\Http\Requests\UpdateDocumentoRequest;
use Modules\DocumentosElectronicos\Repositories\DocumentoRepository;
use Modules\DocumentosElectronicos\Repositories\DocumentoHistorialRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JwtController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Configuracion\Models\Variables;
use Modules\DocumentaryClassification\Models\dependencias;
use Modules\DocumentosElectronicos\Http\Controllers\UtilController;
use Modules\DocumentosElectronicos\Models\Documento;
use Modules\DocumentosElectronicos\Models\DocumentoAnotacion;
use Modules\DocumentosElectronicos\Models\DocumentoCompartir;
use Modules\DocumentosElectronicos\Models\DocumentoFirmar;
use Modules\DocumentosElectronicos\Models\DocumentoHasMetadato;
use Modules\DocumentosElectronicos\Models\DocumentoLeido;
use Modules\DocumentosElectronicos\Models\DocumentoVersion;
use Modules\DocumentosElectronicos\Models\TipoDocumento;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Google\Service\BinaryAuthorization\Jwt;
use Modules\DocumentosElectronicos\Models\DocumentoHistorial;

use function Ramsey\Uuid\v1;
use App\Http\Controllers\SendNotificationController;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\PQRHistorial;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2024
 * @version 1.0.0
 */
class DocumentoController extends AppBaseController
{

    /** @var  DocumentoRepository */
    private $documentoRepository, $documentoHistorialRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     */
    public function __construct(DocumentoRepository $documentoRepo, DocumentoHistorialRepository $documentoRepoHistorial)
    {
        $this->documentoRepository = $documentoRepo;
        $this->documentoHistorialRepository = $documentoRepoHistorial;
    }

    /**
     * Muestra la vista para el CRUD de Documento.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Variable de entorno para controlar la visualización de la sección de clasificación documental
        $clasificacion = Variables::where('name', 'clasificacion_documental_documentos_electronicos')->pluck('value')->first();
        $habilita_finaliza_pqrs = Variables::where('name', 'habilitar_finalizar_pqrs_documentos_electronicos')->pluck('value')->first();

        return view('documentoselectronicos::documentos.index', compact(["clasificacion", "habilita_finaliza_pqrs"]));
    }


    public function indexSign($id, Request $request)
    {
        //id es id de la tabla de_documento_firmar
        return view('documentoselectronicos::documentos.index_sign',compact(["id"]));
    }

    /**
     * Muestra la vista principal para validar los documentos electronicos
     *
     * @param Request $request
     * @return void
     */
    public function indexValidarDocumento(Request $request)
    {
        return view('documentoselectronicos::documentos.index_validar_documento');
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
        // Consulta del  según el código enviado por el usuario
        $documento = Documento::whereRaw("validation_code = BINARY '".$codigo."'")->get();
        // Retorna la información del documento, en caso tal de que coincida algún registro
        return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
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
     * Valida el código de acceso ingresado y devuelve información relevante según el estado del documento.
     *
     * @param Illuminate\Http\Request $request La solicitud HTTP recibida.
     * @return Illuminate\Http\Response La respuesta HTTP con la información sobre el documento según su estado.
     */
    public function validarCodigoIngresado(Request $request)
    {
        // Obtener todos los datos de la solicitud
        $input = $request->all();
        $input = AntiXSSController::xssClean($input,["codigoAccesoDocumento"]);

        // Buscar un documento en la tabla 'Documento' por el código de acceso y el ID, excluyendo documentos en estado 'Pendiente de firma'
        $documento = Documento::select("id", "estado", "plantilla","document_pdf")
            ->where("codigo_acceso_documento", $input['codigoAccesoDocumento'])
            ->where("id", JwtController::decodeToken($input['idTablaFirmar']))
            ->whereNotIn('estado', ['Pendiente de firma'])
            ->first();

        if ($documento) {
            // Si se encuentra un documento válido y no está pendiente de firma
            if ($documento->estado == 'Revisión' || $documento->estado == 'Elaboración') {
                // Devolver la ruta del PDF para revisión o elaboración
                return $this->sendResponse([
                    'success' => true,
                    'rutapdf' => $documento->plantilla . "?rm=embedded&embedded=true",
                    'id_documento' => $documento->id
                ], trans('msg_success_save'));
            }
            else if($documento->estado == 'Público' ){
                $rutapdf = $this->getDocumentPath($documento->document_pdf);
                // Devolver un mensaje indicando que el documento no está disponible para edición
                return $this->sendResponse([
                    'success' => true,
                    'rutapdf' => 'publico',
                    'id_documento' => $rutapdf
                ], trans('msg_success_save'));
            }
            else {
                // Devolver un mensaje indicando que el documento no está disponible para edición
                return $this->sendResponse([
                    'success' => true,
                    'rutapdf' => 'estado_diferente',
                    'id_documento' => "El documento seleccionado no está disponible para su edición en este momento."
                ], trans('msg_success_save'));
            }
        } else {
            // Si no se encuentra un documento válido en la tabla 'Documento', buscar en la tabla 'DocumentoFirmar'
            $documento = DocumentoFirmar::where('id', JwtController::decodeToken($input['idTablaFirmar']))
                ->where('codigo_acceso_documento', $input['codigoAccesoDocumento'])
                ->with('deDocumentoVersion')
                ->first();

            if ($documento) {
                // Si se encuentra un documento válido en 'DocumentoFirmar'
                if ($documento->estado == 'Firma aprobada') {
                    // Devolver un mensaje indicando que el documento está firmado y detalles adicionales
                    return $this->sendResponse([
                        'success' => true,
                        'rutapdf' => 'firmado',
                        'id_documento' => "Desde la IP: " . $documento->ip . " - Fecha firma: " . $documento->fecha_firma . ""
                    ], trans('msg_success_save'));
                }
                else if($documento->estado == 'Devuelto para modificaciones'){
                        // Devolver un mensaje indicando que el documento está firmado y detalles adicionales
                        return $this->sendResponse([
                            'success' => true,
                            'rutapdf' => 'devuelto',
                            'id_documento' => ""
                        ], trans('msg_success_save'));
                }

                else {
                    $anexos = Documento::select("adjuntos")->where("id",$documento->de_documentos_id)->first()->adjuntos;
                    $anexos = is_null($anexos) ? [] : explode(",",$anexos);
                    $rutapdf = $this->getDocumentPath($documento->deDocumentoVersion->document_pdf_temp);

                    // Devolver la ruta del PDF para documentos que no están firmados pero disponibles para revisión
                    return $this->sendResponse([
                        'success' => true,
                        'rutapdf' => $rutapdf,
                        'id_documento' => $documento->de_documentos_id,
                        'anexos' => $anexos
                    ], trans('msg_success_save'));
                }
            } else {
                // Si no se encuentra ningún documento válido en ninguna tabla, devolver una respuesta con éxito pero sin datos
                return $this->sendResponse([
                    'success' => false,
                    'rutapdf' => '',
                ], trans('msg_success_save'));
            }
        }

        // Si no se cumple ninguna de las condiciones anteriores, devolver una respuesta de éxito vacía
        return $this->sendResponse([
            'success' => false,
            'rutapdf' => '',
        ], trans('msg_success_save'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
    {
        //valida si es un administrador de documentos electrónicos
        if (Auth::user()->hasRole('Admin Documentos Electrónicos')) {
            $tipo_documentos = TipoDocumento::where("estado", "Público")->orderBy('nombre', 'ASC')->get()->toArray();
        } else {
            // Si no es un admin, solo obtiene los tipos de documentos que tenga permiso de usarlos según la dependencia
            $tipo_documentos = TipoDocumento::with("dePermisoCrearDocumentos:de_tipos_documentos_id,dependencias_id")
                ->where("estado", "Público")
                ->where(function($e) {
                    $e->whereHas("dePermisoCrearDocumentos", function ($q) {
                        $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                        $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                        $q->where("dependencias_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                        $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                        $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                        $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                    });
                    $e->orWhere("permiso_crear_documentos_todas", 1);
                })
                ->orderBy('nombre', 'ASC')->get()->toArray();
        }
        // Variable para contar el número total de registros de la consulta realizada
        $count_documentos = 0;

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
        //valida si es un administrador de documentos electrónicos
        if (Auth::user()->hasRole('Admin Documentos Electrónicos')) {
            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                if(!empty($request["fa"])){
                    $request["fa"] = base64_decode($request["fa"]);
                    // Si existe un filto general entonces debe filtrar por todos los campos
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::with([
                        'deTiposDocumentos',
                        'deDocumentoHistorials',
                        'documentoAnotacions',
                        'anotacionesPendientes',
                        'deCompartidos',
                        'deDocumentoVersions',
                        'serieClasificacionDocumental',
                        'subserieClasificacionDocumental',
                        'oficinaProductoraClasificacionDocumental',
                        'users',
                        'deDocumentoFirmars',
                        'documentoLeido',
                        'documentoAsociado',
                        'deDocumentoHasDeMetadatos'
                    ])
                    ->whereRaw("consecutivo LIKE ? OR titulo_asunto LIKE ? OR elaboro_nombres LIKE ?", 
                         ['%' . $request["fa"] . '%', '%' . $request["fa"] . '%', '%' . $request["fa"] . '%'])
                    ->orWhereRaw($filtros) // Aplica tus filtros
                    ->when($filtros_metadatos, function($md) use ($filtros_metadatos) {
                        $md->whereHas('deDocumentoHasDeMetadatos', function($m) use ($filtros_metadatos) {
                            $m->where(function ($n) use ($filtros_metadatos) {
                                foreach ($filtros_metadatos as $key => $valor_metatado) {
                                    $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                        $de_metadatos_id = explode("_", $key);
                                        $de_metadatos_id = end($de_metadatos_id);
                                        $q->where('de_metadatos_id', $de_metadatos_id)
                                        ->where('valor', 'LIKE', '%' . $valor_metatado . '%');
                                    });
                                }
                            })
                            ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                        });
                    })
                    ->latest('updated_at') // Ordenar por fecha de actualización
                    ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
                else{
                    // Si existe un filto general entonces debe filtrar por todos los campos
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::with([
                        'deTiposDocumentos',
                        'deDocumentoHistorials',
                        'documentoAnotacions',
                        'anotacionesPendientes',
                        'deCompartidos',
                        'deDocumentoVersions',
                        'serieClasificacionDocumental',
                        'subserieClasificacionDocumental',
                        'oficinaProductoraClasificacionDocumental',
                        'users',
                        'deDocumentoFirmars',
                        'documentoLeido',
                        'documentoAsociado',
                        'deDocumentoHasDeMetadatos'
                    ])
                    ->whereRaw($filtros) // Aplica tus filtros
                    ->when($filtros_metadatos, function($md) use ($filtros_metadatos) {
                        $md->whereHas('deDocumentoHasDeMetadatos', function($m) use ($filtros_metadatos) {
                            $m->where(function ($n) use ($filtros_metadatos) {
                                foreach ($filtros_metadatos as $key => $valor_metatado) {
                                    $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                        $de_metadatos_id = explode("_", $key);
                                        $de_metadatos_id = end($de_metadatos_id);
                                        $q->where('de_metadatos_id', $de_metadatos_id)
                                        ->where('valor', 'LIKE', '%' . $valor_metatado . '%');
                                    });
                                }
                            })
                            ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                        });
                    })
                    ->latest('updated_at') // Ordenar por fecha de actualización
                    ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
            } else if (isset($request["cp"]) && isset($request["pi"])) {
                // Valida si existe la busqueda general
                if(!empty($request["fa"])){
                    $request["fa"] = base64_decode($request["fa"]);

                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::with([
                        'deTiposDocumentos',
                        'deDocumentoHistorials',
                        'documentoAnotacions',
                        'anotacionesPendientes',
                        'deCompartidos',
                        'deDocumentoVersions',
                        'serieClasificacionDocumental',
                        'subserieClasificacionDocumental',
                        'oficinaProductoraClasificacionDocumental',
                        'users',
                        'deDocumentoFirmars',
                        'documentoLeido',
                        'documentoAsociado',
                        'deDocumentoHasDeMetadatos'
                    ])
                    ->whereRaw("consecutivo LIKE ? OR titulo_asunto LIKE ? OR elaboro_nombres LIKE ?", 
                         ['%' . $request["fa"] . '%', '%' . $request["fa"] . '%', '%' . $request["fa"] . '%'])
                    ->latest('updated_at') // Ordenar por fecha de actualización
                    ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página

                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
                else{
                    // Consulta los tipo de solicitudes según el paginado seleccionado
                    $documentos = Documento::with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])
                    ->latest('updated_at') // Ordenar por fecha de actualización
                    ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
            } else {
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $documentos = Documento::with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])->latest("updated_at")->get()->toArray();
                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_documentos = Documento::count();
            }
        } else {
            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                if(!empty($request["fa"])){
                    $request["fa"] = base64_decode($request["fa"]);

                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::select('de_documento.*', 'de_documento_compartir.nombre')->with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])
                        ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                        ->whereNull("de_documento_compartir.deleted_at")
                        ->where(function ($q) {
                            $q->where(function ($d) {
                                $d->where("de_documento_compartir.users_id", Auth::user()->id);
                                $d->where("de_documento.estado", "Público");
                            });
                            $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                            $q->orWhere("de_documento.users_id", Auth::user()->id);
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
                        ->whereRaw("consecutivo LIKE ? OR titulo_asunto LIKE ? OR elaboro_nombres LIKE ?", 
                            ['%' . $request["fa"] . '%', '%' . $request["fa"] . '%', '%' . $request["fa"] . '%'])
                        ->orWhereRaw($filtros) // Aplica tus filtros
                        ->when($filtros_metadatos, function($md) use ($filtros_metadatos) {
                            $md->whereHas('deDocumentoHasDeMetadatos', function($m) use ($filtros_metadatos) {
                                $m->where(function ($n) use ($filtros_metadatos) {
                                    foreach ($filtros_metadatos as $key => $valor_metatado) {
                                        $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                            $de_metadatos_id = explode("_", $key);
                                            $de_metadatos_id = end($de_metadatos_id);
                                            $q->where('de_metadatos_id', $de_metadatos_id)
                                            ->where('valor', 'LIKE', '%' . $valor_metatado . '%');
                                        });
                                    }
                                })
                                ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                            });
                        })
                        ->groupBy("de_documento.id")
                        ->distinct("de_documento.id")
                        ->latest('updated_at') // Ordenar por fecha de actualización
                        ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                        $count_documentos = $documentos->total(); // Total de documentos encontrados
                        $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
                else{
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::select('de_documento.*', 'de_documento_compartir.nombre')->with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])
                        ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                        ->whereNull("de_documento_compartir.deleted_at")
                        ->where(function ($q) {
                            $q->where(function ($d) {
                                $d->where("de_documento_compartir.users_id", Auth::user()->id);
                                $d->where("de_documento.estado", "Público");
                            });
                            $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                            $q->orWhere("de_documento.users_id", Auth::user()->id);
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
                        ->whereRaw($filtros) // Aplica tus filtros
                        ->when($filtros_metadatos, function($md) use ($filtros_metadatos) {
                            $md->whereHas('deDocumentoHasDeMetadatos', function($m) use ($filtros_metadatos) {
                                $m->where(function ($n) use ($filtros_metadatos) {
                                    foreach ($filtros_metadatos as $key => $valor_metatado) {
                                        $n->orWhere(function ($q) use ($key, $valor_metatado) {
                                            $de_metadatos_id = explode("_", $key);
                                            $de_metadatos_id = end($de_metadatos_id);
                                            $q->where('de_metadatos_id', $de_metadatos_id)
                                            ->where('valor', 'LIKE', '%' . $valor_metatado . '%');
                                        });
                                    }
                                })
                                ->havingRaw('COUNT(*) >= ' . count($filtros_metadatos));
                            });
                        })
                        ->groupBy("de_documento.id")
                        ->distinct("de_documento.id")
                        ->latest('updated_at') // Ordenar por fecha de actualización
                        ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                        $count_documentos = $documentos->total(); // Total de documentos encontrados
                        $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
            } else if (isset($request["cp"]) && isset($request["pi"])) {
                // Valida si existe la busqueda general
                if(!empty($request["fa"])){
                    $request["fa"] = base64_decode($request["fa"]);

                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::select('de_documento.*', 'de_documento_compartir.nombre')->with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])
                        ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                        ->whereNull("de_documento_compartir.deleted_at")
                        ->whereRaw("consecutivo LIKE ? OR titulo_asunto LIKE ? OR elaboro_nombres LIKE ?", 
                         ['%' . $request["fa"] . '%', '%' . $request["fa"] . '%', '%' . $request["fa"] . '%'])
                        ->where(function ($q) {
                            $q->where(function ($d) {
                                $d->where("de_documento_compartir.users_id", Auth::user()->id);
                                $d->where("de_documento.estado", "Público");
                            });
                            $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                            $q->orWhere("de_documento.users_id", Auth::user()->id);
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
                        ->groupBy("de_documento.id")
                        ->latest('updated_at') // Ordenar por fecha de actualización
                        ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
                else{
                    // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                    $documentos = Documento::select('de_documento.*', 'de_documento_compartir.nombre')->with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])
                        ->leftjoin('de_documento_compartir', 'de_documento.id', '=', 'de_documento_compartir.de_documentos_id')
                        ->whereNull("de_documento_compartir.deleted_at")
                        ->where(function ($q) {
                            $q->where(function ($d) {
                                $d->where("de_documento_compartir.users_id", Auth::user()->id);
                                $d->where("de_documento.estado", "Público");
                            });
                            $q->orWhere("de_documento.users_id_actual", Auth::user()->id);
                            $q->orWhere("de_documento.users_id", Auth::user()->id);
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
                        ->groupBy("de_documento.id")
                        ->latest('updated_at') // Ordenar por fecha de actualización
                        ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página
    
                    $count_documentos = $documentos->total(); // Total de documentos encontrados
                    $documentos = $documentos->toArray()["data"]; // Consulta los documentos según los filtros
                }
            } else {

                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $documentos = Documento::with(['deTiposDocumentos', 'deDocumentoHistorials', 'documentoAnotacions', 'anotacionesPendientes', 'deCompartidos', 'deDocumentoVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'deDocumentoFirmars', 'documentoLeido', 'documentoAsociado', 'deDocumentoHasDeMetadatos'])->latest()->get()->toArray();
                    // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                    $count_documentos = Documento::count();
            }
        }
        return $this->sendResponseAvanzado($documentos, trans('data_obtained_successfully'), null, ["total_registros" => $count_documentos, "tipo_documentos" => $tipo_documentos]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param CreateDocumentoRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoRequest $request)
    {
        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $documento = $this->documentoRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDocumentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoRequest $request)
    {
        $input = $request->all();

        $userLogin = Auth::user();

        // Validar el tipo de guardado en el documento ingrese a la condicion si es de tipo guardar_avance
        if(empty($input["type_storage"]) || (!empty($input["type_storage"]) && $input["type_storage"] != 'guardar_avance')){
            // Valida si el origen del documento es para adjuntar y no una intraweb
            if($input["origen_documento"] == "Producir documento en línea a través de Intraweb"){
                // Valida si el usuario quien publica esta autorizado para firmar
                if($input["tipo"] == "publicacion"){
                    $isAuthorized = User::select("autorizado_firmar")->where("id",$userLogin->id)->first()->autorizado_firmar;
                    // Si no esta autorizado para firmar muestra una alerta
                    if($isAuthorized == 0 || is_null($isAuthorized)){
                        return $this->sendSuccess('<strong>¡Atención! No se encuentra autorizado para firmar</strong><br /><br />La autorización para firmar es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma.', 'info');
                    }
                }
            }
        }

        /** @var Documento $documento */
        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }


        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Valida si viene usuarios para asignar
            if (!empty($input['de_compartidos'])) {

                //borra todo para volver a insertarlo
                DocumentoCompartir::where('de_documentos_id', $id)->delete();

                // Arreglo para almacenar el nombre de los usuarios y/o dependencias a los que se le compartió el documento
                $usuariosCompartidos = array();
                // Recorre los compartidos
                foreach ($input['de_compartidos'] as $recipent) {
                    // Array de compartidos
                    $destinatarioArray = json_decode($recipent, true);
                    $destinatarioArray["de_documentos_id"] = $id;
                    $usuariosCompartidos[] = $destinatarioArray["nombre"];

                    DocumentoCompartir::create($destinatarioArray);
                }
                $input["compartidos"] = implode("<br>", $usuariosCompartidos);
            } else {
                // Elimina todos lo usuarios/dependencias que estaban como compartidos
                DocumentoCompartir::where('de_documentos_id', $id)->delete();
                $input["compartidos"] = "";
            }

            // Valida si tiene metadatos registrados
            if (!empty($input['metadatos'])) {
                // Borra todos los registros de metadatos para volver a insertarlos
                DocumentoHasMetadato::where('de_documentos_id', $id)->delete();
                // Recorre los metadatos
                foreach (json_decode($input['metadatos']) as $llave_metadato => $valor_metadato) {

                    $llave_metadato = explode("_", $llave_metadato);
                    $id_metadato = end($llave_metadato);
                    // Array de metadatos
                    $registroMetadatoArray = [];
                    $registroMetadatoArray["valor"] = $valor_metadato;
                    $registroMetadatoArray["de_metadatos_id"] = $id_metadato;
                    $registroMetadatoArray["de_documentos_id"] = $id;
                    // Inserta los valores de los metadatos relacionado al metadato y al documento
                    DocumentoHasMetadato::create($registroMetadatoArray);
                }
            } else {
                // Elimina todos los registros de metadatos relacionados al documento
                DocumentoHasMetadato::where('de_documentos_id', $id)->delete();
            }

            // Formatea separando por coma los enlaces de los adjuntos del documento
            $input['adjuntos'] = isset($input["adjuntos"]) && $input["adjuntos"] ? implode(",", $input["adjuntos"]) : null;
            $users = json_decode($input["users"], true);
            $de_tipos_documentos = json_decode($input["de_tipos_documentos"], true);

            // Validar el tipo de guardado en el documento ingrese a la condicion si es de tipo guardar_avance
            if(empty($input["type_storage"]) || (!empty($input["type_storage"]) && $input["type_storage"] != 'guardar_avance')){
                $formatoConsecutivoValores = [];
                $formatoConsecutivoValores["prefijo_dependencia"] = $users["dependencies"]["codigo"];
                $formatoConsecutivoValores["serie_documental"] = $input["classification_serie"] ?? '';
                $formatoConsecutivoValores["subserie_documental"] = $input["classification_subserie"] ?? '';
                $formatoConsecutivoValores["prefijo_documento"] = $de_tipos_documentos["prefijo"];
                $formatoConsecutivoValores["separador_consecutivo"] = $de_tipos_documentos["separador_consecutivo"];
                $formatoConsecutivoValores["vigencia_actual"] = date("Y");
            }

            // Validar el tipo de guardado en el documento ingrese a la condicion si es de tipo guardar_avance
            if(empty($input["type_storage"]) || (!empty($input["type_storage"]) && $input["type_storage"] != 'guardar_avance')){
                // tipo = Acción a realizar con el documento actual
                switch ($input["tipo"]) {

                    case 'recuperacion':
                        $input["updated_at"] =  $documento->updated_at;

                        $input["created_at"] =  $documento->created_at;

                        $id_google = explode("/", $input["plantilla"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();

                        $ruta_documento = $google->saveFileGoogleDrive($id_google, $input["formato_publicacion"] == "PDF" || empty($input["formato_publicacion"]) ? "pdf" : "", $input["consecutivo"], "container/de_documentos_" . date('Y'));
                        // Si la variable 'ruta_documento' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
                        if(!empty($ruta_documento["type_message"]) && $ruta_documento["type_message"] == "warning") {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de advertencia al usuario
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $ruta_documento["message"], 'warning');
                        }
                        $input["document_pdf"] = $ruta_documento;
                        // Valida el 'TIPO_ALMACENAMIENTO_GENERAL', si es AWS
                        if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS") {
                            // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
                            $requestObjectAWS = new Request();
                            // Ruta del documento
                            $requestObjectAWS["path"] = $input["document_pdf"];
                            // Tipo de url de descarga 'obtener_hash_documento_aws', quiere decir que calculará el hash del documento desde S3
                            $requestObjectAWS["tipoURL"] = "obtener_hash_documento_aws";
                            // Se hace la solicitud a la función 'readObjectAWS' para obtener la URL prefirmada del archivo
                            $archivo_aws = $this->readObjectAWS($requestObjectAWS);
                            // Se decodifica la URL
                            $hash_document_pdf = JwtController::decodeToken($archivo_aws['data']);
                            // Se asigna el hash calculado del archivo desde S3 de AWS
                            $input["hash_document_pdf"] = $hash_document_pdf;
                        } else {
                            // Genera una cadena hash usando el archivo local del campo document_pdf
                            $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                        }
                        break;
                    case 'publicacion':
                        if ($input["origen_documento"] == 'Producir documento en línea a través de Intraweb') {
                            // Valida si el usuario posee una firma para la publicación del documento
                            if (!$userLogin->url_digital_signature) {
                                // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            } else {
                                if (!file_exists(storage_path("app/public/" . $userLogin->url_digital_signature))) {
                                    return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                                }
                            }
                            // Si el estado del documento es diferente de Público, calcula el consecutivo del documento
                            if ($input["estado"] != "Público") {
                                // Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order (prefijo)
                                $datosConsecutivo = UtilController::getNextConsecutive($de_tipos_documentos["formato_consecutivo"], $de_tipos_documentos["prefijo_incrementan_consecutivo"], $formatoConsecutivoValores);

                                $input["consecutivo"] = $datosConsecutivo['consecutivo'];
                                $input["consecutivo_prefijo"] = $datosConsecutivo['consecutivo_prefijo'];
                                // Actualiza el registro
                                $documento = $this->documentoRepository->update(['consecutivo'=>$input["consecutivo"], 'consecutivo_prefijo'=>$input["consecutivo_prefijo"]], $id);
                            }
                            // Estado del documento
                            $input["estado"] = "Público";
                            $variables = $de_tipos_documentos["variables_plantilla_requeridas"] && $de_tipos_documentos["variables_plantilla"] ? explode(", ", $de_tipos_documentos["variables_plantilla"]) : [];

                            $information["#consecutivo"] = $input["consecutivo"];
                            $information["#titulo"] = $input["titulo_asunto"];
                            $information["#dependencia_remitente"] = $users["dependencies"]["nombre"];
                            $information["#compartidos"] = str_replace("<br>", " - ", $input["compartidos"]);
                            $information["#tipo_documento"] = $de_tipos_documentos["nombre"];
                            $information["#elaborado"] = $input["elaboro_nombres"];
                            $information["#revisado"] = $input["reviso_nombres"] ?? '';
                            $information["#proyecto"] = $input["elaboro_nombres"];
                            $information["#codigo_formato"] = $de_tipos_documentos["codigo_formato"];
                            $information["#documento_asociado"] = $input["documentos_asociados"] ?? '';
                            $information["#codigo_dependencia"] = $users["dependencies"]["codigo"];


                            $signUnique = new \stdClass();
                            $signUnique->name = $userLogin->fullname;
                            $signUnique->url_digital_signature = $userLogin->url_digital_signature;
                            $signUnique->escala_firma = $userLogin->escala_firma;

                            if (!file_exists(storage_path("app/public/" . $signUnique->url_digital_signature))) {
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            }

                            // Se crea una cadena hash para identificar la firma del usuario en el documento
                            $fullHash = hash('sha256', date("Y-m-d H:i:s") . Auth::user()->name . $input["consecutivo"]);
                            $hash = "ID firma: " . base64_encode(hex2bin($fullHash));

                            $signUnique2 = new \stdClass();
                            $signUnique2->users = $signUnique;
                            $signUnique2->hash = $hash;

                            $information["#firmas"] = [$signUnique2];

                            setlocale(LC_ALL, "es_CO.UTF-8");
                            $information["#fecha"] = strftime("%d de %B del %Y");

                            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            // Genera un código de verificación único para cada documento
                            $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
                            $information["#codigo_validacion"] = $input["validation_code"];

                            // Obtiene la información de los metadatos con sus valores si el documento los tiene
                            $metadatos = DocumentoHasMetadato::with("deMetadatos")->where("de_documentos_id", $id)->get()->toArray();
                            // Arreglo temporal para almacenar la variable del metadato como clave y el valor
                            $valores_metadatos = [];
                            // Recorre los valores de metadatos si el documento los tiene
                            foreach ($metadatos as $metadato) {
                                $variable = $metadato["de_metadatos"]["variable_documento"];
                                $variable ? $valores_metadatos[$variable] = $metadato["valor"] : null;
                            }
                            // Une los arreglos de las variables del documento predeterminada con los metadatos
                            $information = array_merge($information, $valores_metadatos);

                            $id_google = explode("/", $input["plantilla"]);
                            $id_google = end($id_google);

                            $google = new GoogleController();
                            $returnGoogle = $google->editFileDoc(null, $id, $id_google, $variables, $information, 0);
                            if ($returnGoogle['type_message'] == 'info') {
                                // Devuelve los cambios realizados
                                DB::rollback();
                                // Retorna mensaje de error de base de datos
                                return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
                            }

                            $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, $input["formato_publicacion"] == "PDF" || empty($input["formato_publicacion"]) ? "pdf" : "", $input["consecutivo"], "container/de_documentos_" . date('Y'));


                            $numberVersion = DocumentoVersion::where('de_documentos_id', $id)->max("numero_version") + 1;

                            $inputVersion["document_pdf_temp"] = $documento_almacenado;
                            $inputVersion["numero_version"] = $numberVersion;
                            $inputVersion["nombre_usuario"] = $userLogin->fullname;
                            $inputVersion["estado"] = "Pendiente";
                            $inputVersion["observacion"] = !empty($input["observacion"]) ? $input["observacion"] : null;
                            $inputVersion["de_documentos_id"] = $id;
                            $inputVersion["users_id"] = $userLogin->id;
                            $idVersion = DocumentoVersion::create($inputVersion);

                            // Obtiene la IP del usuario en sesión
                            $publicIp = $this->detectIP();

                            $inputSign["tipo_usuario"] = "Interno";
                            $inputSign["nombre_usuario"] = $userLogin->fullname;
                            $inputSign["estado"] = "Firma aprobada";
                            $inputSign["observacion"] = "";
                            $inputSign["hash"] = $hash;
                            $inputSign["ip"] = $publicIp;
                            $inputSign["fecha_firma"] = date("Y-m-d H:i:s");
                            $inputSign["de_documentos_id"] = $id;
                            $inputSign["users_id"] = $userLogin->id;
                            $inputSign["de_documento_version_id"] = $idVersion->id;

                            DocumentoFirmar::create($inputSign);

                            $input["document_pdf"] = $documento_almacenado;
                            // Valida el 'TIPO_ALMACENAMIENTO_GENERAL', si es AWS
                            if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS") {
                                // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
                                $requestObjectAWS = new Request();
                                // Ruta del documento
                                $requestObjectAWS["path"] = $input["document_pdf"];
                                // Tipo de url de descarga 'obtener_hash_documento_aws', quiere decir que calculará el hash del documento desde S3
                                $requestObjectAWS["tipoURL"] = "obtener_hash_documento_aws";
                                // Se hace la solicitud a la función 'readObjectAWS' para obtener la URL prefirmada del archivo
                                $archivo_aws = $this->readObjectAWS($requestObjectAWS);
                                // Se decodifica la URL
                                $hash_document_pdf = JwtController::decodeToken($archivo_aws['data']);
                                // Se asigna el hash calculado del archivo desde S3 de AWS
                                $input["hash_document_pdf"] = $hash_document_pdf;
                            } else {
                                // Genera una cadena hash usando el archivo local del campo document_pdf
                                $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                            }
                        } else {
                            // Si el estado del documento es diferente de Público, calcula el consecutivo del documento
                            if ($input["estado"] != "Público") {
                                // Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order (prefijo)
                                $datosConsecutivo = UtilController::getNextConsecutive($de_tipos_documentos["formato_consecutivo"], $de_tipos_documentos["prefijo_incrementan_consecutivo"], $formatoConsecutivoValores);
                                $input["consecutivo"] = $datosConsecutivo['consecutivo'];
                                $input["consecutivo_prefijo"] = $datosConsecutivo['consecutivo_prefijo'];
                                //Actualiza registro
                                $documento = $this->documentoRepository->update(['consecutivo'=>$input["consecutivo"], 'consecutivo_prefijo'=>$input["consecutivo_prefijo"]], $id);
                            }
                            $input["document_pdf"] = isset($input["document_pdf"]) && $input["document_pdf"] ? implode(",", $input["document_pdf"]) : null;
                            // Estado del documento
                            $input["estado"] = "Público";
                        }

                        break;

                    case 'elaboracion':
                        $input["estado"] = "Elaboración";
                        if($input["tipo_usuario"] == "Interno") {
                            $input["elaboro_id"] = isset($input["elaboro_id"]) && !empty($input["funcionario_elaboracion_revision"]) ? $input["elaboro_id"] . "," . $input["funcionario_elaboracion_revision"] : ($input["funcionario_elaboracion_revision"] ?? null);
                            $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                            $nombre_usuario = $datos_usuario["name"];
                            $correo_usuario = $datos_usuario["email"];
                        } else {
                            $input["elaboro_nombres"] = isset($input["elaboro_nombres"]) ? $input["elaboro_nombres"] . ", " . $input["funcionario_elaboracion_revision"] : $input["funcionario_elaboracion_revision"];
                            $nombre_usuario = $input["funcionario_elaboracion_revision"];
                            $correo_usuario = $input["correo_usuario_externo"];
                            $input["codigo_acceso_documento"] = Str::random(10);

                        }

                        $input["users_id_actual"] = $input["funcionario_elaboracion_revision"] ?? null;
                        $input["users_name_actual"] = $nombre_usuario;
                        $input["correo_usuario"] = $correo_usuario;
                        $input["reviso_id"] = "";

                        $input["reviso_id"] = "";
                        if($input["tipo_usuario"] =='Externo'){

                            $input["id_encriptado"] = JwtController::generateToken($input["id"]);

                            $envioNotificacion = $this->enviarNotificacion($input, [$input["correo_usuario"]],$input["tipo"]);
                        }
                        break;

                    case 'revision':
                        $input["estado"] = "Revisión";
                        if($input["tipo_usuario"] == "Interno") {
                            $input["reviso_id"] = isset($input["reviso_id"]) && !empty($input["funcionario_elaboracion_revision"]) ? $input["reviso_id"] . "," . $input["funcionario_elaboracion_revision"] : ($input["funcionario_elaboracion_revision"] ?? null);
                            $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                            $input["reviso_nombres"] = isset($input["reviso_nombres"]) ? $input["reviso_nombres"] . ", " . $datos_usuario["fullname"] : $datos_usuario["fullname"];
                            $nombre_usuario = $datos_usuario["name"];
                            $correo_usuario = $datos_usuario["email"];
                        } else {
                            $input["reviso_nombres"] = isset($input["reviso_nombres"]) ? $input["reviso_nombres"] . ", " . $input["funcionario_elaboracion_revision"] : $input["funcionario_elaboracion_revision"];
                            $nombre_usuario = $input["funcionario_elaboracion_revision"];
                            $correo_usuario = $input["correo_usuario_externo"];
                            $input["codigo_acceso_documento"] = Str::random(10);

                        }

                        $input["users_id_actual"] = $input["funcionario_elaboracion_revision"] ?? null;
                        $input["users_name_actual"] = $nombre_usuario;
                        $input["correo_usuario"] = $correo_usuario;
                        $input["elaboro_id"] = "";

                        if($input["tipo_usuario"] =='Externo'){
                            $input["id_encriptado"] = JwtController::generateToken($input["id"]);

                            $envioNotificacion = $this->enviarNotificacion($input, [$input["correo_usuario"]],$input["tipo"]);
                        }

                        break;

                    case 'firmar_varios':
                        $estadoActual = $input['estado'];
                        $input["estado"] = "Pendiente de firma";
                        $input["elaboro_id"] = "";
                        $input["reviso_id"] = "";

                        $numberVersion = DocumentoVersion::where('de_documentos_id', $id)->max("numero_version") + 1;
                        $variables = $de_tipos_documentos["variables_plantilla_requeridas"] && $de_tipos_documentos["variables_plantilla"] ? explode(", ", $de_tipos_documentos["variables_plantilla"]) : [];

                        $information["#consecutivo"] = "consecutivo";
                        $information["#titulo"] = $input["titulo_asunto"];
                        $information["#dependencia_remitente"] = $users["dependencies"]["nombre"];
                        $information["#compartidos"] = str_replace("<br>", " - ", $input["compartidos"]);
                        $information["#tipo_documento"] = $de_tipos_documentos["nombre"];
                        $information["#elaborado"] = $input["elaboro_nombres"];
                        $information["#revisado"] = $input["reviso_nombres"] ?? '';
                        $information["#proyecto"] = $input["elaboro_nombres"];
                        $information["#codigo_formato"] = $de_tipos_documentos["codigo_formato"];
                        $information["#documento_asociado"] = $input["documentos_asociados"] ?? '';
                        $information["#codigo_dependencia"] = $users["dependencies"]["codigo"];
                        $information["#firmas"] = "Espacio para firmas";

                        setlocale(LC_ALL, "es_CO.UTF-8");
                        $information["#fecha"] = strftime("%d de %B del %Y");

                        $information["#codigo_validacion"] = $input["validation_code"] ?? "No aplica";

                        // Obtiene la información de los metadatos con sus valores si el documento los tiene
                        $metadatos = DocumentoHasMetadato::with("deMetadatos")->where("de_documentos_id", $id)->get()->toArray();
                        // Arreglo temporal para almacenar la variable del metadato como clave y el valor
                        $valores_metadatos = [];
                        // Recorre los valores de metadatos si el documento los tiene
                        foreach ($metadatos as $metadato) {
                            $variable = $metadato["de_metadatos"]["variable_documento"];
                            $variable ? $valores_metadatos[$variable] = $metadato["valor"] : null;
                        }
                        // Une los arreglos de las variables del documento predeterminada con los metadatos
                        $information = array_merge($information, $valores_metadatos);

                        $id_google = explode("/", $input["plantilla"]);
                        $id_google = end($id_google);
                        $google = new GoogleController();
                        $returnGoogle = $google->editFileDoc(null, $id, $id_google, $variables, $information, 0, true);

                        if ($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, $input["formato_publicacion"] == "PDF" || empty($input["formato_publicacion"]) ? "pdf" : "", $input["consecutivo"], "container/documentos_electronicos_" . date('Y'));
                        // Si la variable 'documento_almacenado' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
                        if(!empty($documento_almacenado["type_message"]) && $documento_almacenado["type_message"] == "warning") {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de advertencia al usuario
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $documento_almacenado["message"], 'warning');
                        }

                        $inputVersion["document_pdf_temp"] = $documento_almacenado;
                        $inputVersion["numero_version"] = $numberVersion;
                        $inputVersion["nombre_usuario"] = $userLogin->fullname;
                        $inputVersion["estado"] = "Pendiente";
                        $inputVersion["observacion"] = $input["observacion"];
                        $inputVersion["de_documentos_id"] = $id;
                        $inputVersion["users_id"] = $userLogin->id;
                        $idVersion = DocumentoVersion::create($inputVersion);

                        // Valida si viene usuarios para asignar
                        if (!empty($input['de_documento_firmars'])) {
                            // Elimina los registros, esto para que no queden duplicados, ya que mas adelante los vuelve a crear
                            DocumentoFirmar::where('de_documentos_id', $id)->delete();

                            // Recorre los usuarios que firman el documento
                            foreach ($input['de_documento_firmars'] as $recipent) {
                                // Array de usuarios firmantes
                                $recipentArray = json_decode($recipent, true);

                                // Si no existe un id es porque es un registro nuevo
                                if(empty($recipentArray["id"]) || str_contains($estadoActual, 'pendiente de enviar')){
                                    $inputSign["correo"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["usuarios"]["email"] : $recipentArray["correo"];
                                    $inputSign["tipo_usuario"] = $recipentArray["tipo_usuario"];
                                    $inputSign["nombre_usuario"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["usuarios"]["fullname"] : $recipentArray["nombre_usuario"];
                                    $inputSign["estado"] = "Pendiente de firma";
                                    $inputSign["de_documentos_id"] = $id;
                                    $inputSign["users_id"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["nombre_usuario"] : null;
                                    $inputSign["de_documento_version_id"] = $idVersion->id;
                                    // Contraseña para los usuarios de tipo externo, esto para poder editar el documento desde la vista pública
                                    $inputSign["codigo_acceso_documento"] = Str::random(10);

                                    $datosfirmaUsuario = DocumentoFirmar::create($inputSign);

                                    //otros datos para la notificacion
                                    $datosfirmaUsuario["nombre_usuario_version"] = $inputVersion["nombre_usuario"];
                                    $datosfirmaUsuario["document_pdf_temp"] = $inputVersion["document_pdf_temp"];
                                    $datosfirmaUsuario["id_encriptado"] = JwtController::generateToken($datosfirmaUsuario["id"]);
                                    $datosfirmaUsuario["titulo_asunto"] = $input["titulo_asunto"];
                                    $datosfirmaUsuario["consecutivo"] = $input["consecutivo"];

                                    if($inputSign["tipo_usuario"] =='Externo'){
                                        $envioNotificacion = $this->enviarNotificacion($datosfirmaUsuario, [$inputSign["correo"]],$input["tipo"]);
                                    }
                                }
                            }
                        }

                        break;

                    default:
                        # code...
                        break;
                }
            }
             // crea el estado pendiente de enviar cuando es guardar_avance
             if(empty($input["type_storage"]) || (!empty($input["type_storage"]) && $input["type_storage"] == 'guardar_avance')){

                // se crea la subcadena del estado
                $newState = $input['tipo'] == 'elaboracion' ? 'Elaboración' : ($input['tipo'] == 'revision' ? 'Revisión' :
                ($input['tipo'] == 'firmar_varios' ? 'Pendiente de firma' : 'publicación'));
                // se unen las cadenas del nuevo estado
                $newState =  $newState . " (pendiente de enviar)";
                // se asigna el nuevo estado
                $input["estado"] = $newState;

                if (!empty($input['funcionario_elaboracion_revision_object'])) {
                    $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                    $input["users_name_actual"] = $datos_usuario["fullname"];
                    $input["correo_usuario"] = $datos_usuario["email"];
                    $input["users_id_actual"] = $datos_usuario["id"] ?? null;
                }

                 // Valida si viene usuarios para asignar
                 if (!empty($input['de_documento_firmars'])) {
                    DocumentoFirmar::where('de_documentos_id', $id)->delete();

                    // Recorre los usuarios que firman el documento
                    foreach ($input['de_documento_firmars'] as $recipent) {
                        // Array de usuarios firmantes
                        $recipentArray = json_decode($recipent, true);

                        // Si no existe un id es porque es un registro nuevo
                        // if(empty($recipentArray["id"])){
                            $inputSign["correo"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["usuarios"]["email"] : $recipentArray["correo"];
                            $inputSign["tipo_usuario"] = $recipentArray["tipo_usuario"];
                            $inputSign["nombre_usuario"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["usuarios"]["fullname"] : $recipentArray["nombre_usuario"];
                            $inputSign["estado"] = "Pendiente de firma";
                            $inputSign["de_documentos_id"] = $id;
                            $inputSign["users_id"] = $recipentArray["tipo_usuario"] == "Interno" ? $recipentArray["nombre_usuario"] : null;
                            // Contraseña para los usuarios de tipo externo, esto para poder editar el documento desde la vista pública
                            $inputSign["codigo_acceso_documento"] = Str::random(10);

                            $datosfirmaUsuario = DocumentoFirmar::create($inputSign);

                        // }
                    }
                }


            }

            // Actualiza el registro
            $documento = $this->documentoRepository->update($input, $id);
            $input["users_id"] = $userLogin->id;
            $input["users_name"] = $userLogin->fullname;
            $input['de_documento_id'] = $documento->id;
            $input['observacion_historial'] = "Actualización de documento";
            $input['document_pdf'] = $input['document_pdf'] ?? null;


            // Crea un nuevo registro de historial
            $this->documentoHistorialRepository->create($input);
            //Obtiene el historial
            $documento->deDocumentoHistorials;
            $documento->deDocumentoVersions;
            $documento->deTiposDocumentos;
            $documento->serieClasificacionDocumental;
            $documento->subserieClasificacionDocumental;
            $documento->oficinaProductoraClasificacionDocumental;
            $documento->deCompartidos;
            $documento->documentoLeido;
            $documento->deDocumentoHasDeMetadatos;
            $documento->deDocumentoFirmars;

            // Condición para validar si se va a finalizar un PQRS
            if (array_key_exists('require_answer',$input) && $input['require_answer'] == 'Si' && $documento->estado == 'Público') {
                // Actualiza el estado, fecha de finalización, adjunto y demás datos del PQRS, relacionando el documento electrónico
                PQR::where('id', $input["pqr_id"])->update([
                    'fecha_fin' =>  date('Y-m-d H:i:s'),
                    'estado' => "Finalizado",
                    'adjunto' => $input["document_pdf"] ?? '',
                    'de_documento_id' => $documento->id,
                    'respuesta' => "El PQR fue finalizado desde el documento electrónico con el consecutivo : " . $input["consecutivo"],
                    'tipo_finalizacion' => $input["tipo_finalizacion"] ?? 'Respuesta al ciudadano',
                    'no_oficio_respuesta' =>  $input["consecutivo"]
                ]);
                $informacionPqr = PQR::where('id', $input["pqr_id"])->first()->toArray();
                $informacionPqr["pqr_pqr_id"] = $input["pqr_id"];
                $informacionPqr["action"] = "Finalización de registro";

                // Se crea el historial del PQR
                PQRHistorial::create($informacionPqr);
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_update'), 'success');
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
            if(strpos($error->getMessage(), "Duplicate entry") !== false && strpos($error->getMessage(), "consecutive") !== false) {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess('Lo sentimos, no pudimos procesar su solicitud debido a un alto flujo de peticiones. Por favor, inténtelo de nuevo al hacer clic en "Guardar". Si la situación persiste, comuníquese con ' . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co', 'info');
            } else {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'), 'info');
            }
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . ' Consecutivo: '. ($input['consecutivo'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }


    public function enviarNotificacion($datos, $emails, $estado) {
        try {
            $asunto = '';

            switch ($estado) {
                case 'firmar_varios':
                    $asunto = json_decode('{"subject": "Documento Pendiente por firmar: '.$datos["titulo_asunto"].'"}');

                    // $asunto = "Documento Pendiente por firmar: {$datos['titulo_asunto']}";
                    SendNotificationController::SendNotification('documentoselectronicos::documentos.emails.notificacion_firma',$asunto,$datos,$emails,'Documentos electronicos');

                    break;

                case 'elaboracion':
                    $asunto = json_decode('{"subject": "Documento Pendiente de elaboración: '.$datos["titulo_asunto"].'"}');
                    SendNotificationController::SendNotification('documentoselectronicos::documentos.emails.notificacion_estados',$asunto,$datos,$emails,'Documentos electronicos');
                    break;

                case 'revision':
                    $asunto = json_decode('{"subject": "Documento Pendiente de revisión: '.$datos["titulo_asunto"].'"}');
                    SendNotificationController::SendNotification('documentoselectronicos::documentos.emails.notificacion_estados',$asunto,$datos,$emails,'Documentos electronicos');
                    break;

                case 'devolver_documento':
                    $document_id_encrypted = base64_encode($datos["id"]);
                    $datos["link"] = "/documentos-electronicos/documentos?qder=".$document_id_encrypted;
                    $asunto = json_decode('{"subject": "Documento Devuelto por un firmante: '.$datos["titulo_asunto"].'"}');
                    SendNotificationController::SendNotification('documentoselectronicos::documentos.emails.notificacion_devolver_documento',$asunto,$datos,$emails,'Documentos electronicos');
                    break;

                case 'documento_firmado':
                    $asunto = json_decode('{"subject": "Documento firmado y publicado: '.$datos["titulo_asunto"].'"}');
                    SendNotificationController::SendNotification('documentoselectronicos::documentos.emails.notificacion_firmado',$asunto,$datos,$emails,'Documentos electronicos');
                    break;


                default:
                    // Estado no reconocido
                    throw new \Exception("Estado '{$estado}' no válido.");
            }

            // Registro exitoso
            return $this->sendSuccess('Notificación enviada correctamente.', 'info');

        } catch (\Illuminate\Database\QueryException $error) {
            // Error de base de datos
            $this->generateSevenLog('DocumentosElectronicos', 'Error en la consulta: ' . $error->getMessage());
            return $this->sendErrorData('Error en la consulta: ' . $error->getMessage(), 500);

        } catch (\Exception $e) {
            // Error lógico
            $this->generateSevenLog('DocumentosElectronicos', 'Error lógico: ' . $e->getMessage(). ' Linea: '. $e->getLine() . ' Consecutivo: '.$$data["consecutivo"]);
            return $this->sendErrorData('Error lógico: ' . $e->getMessage(), 500);
        }

    }

    /**
     * Elimina un Documento del almacenamiento
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        /** @var Documento $documento */
        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro del historial
            DocumentoHistorial::where("de_documento_id",$id)->delete();

            // Elimina el registro
            $documento->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . ' Linea: '. $e->getLine() . ' Consecutivo: '.($documento['consecutivo'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {
        $input = $request->all();

        if(array_key_exists("filtros", $input)) {

            if($input["filtros"] != "") {
                $input["data"] = Documento::with('deTiposDocumentos')->whereRaw($input["filtros"])->latest()->get()->toArray();
            } else {
                $input["data"] = Documento::with('deTiposDocumentos')->latest()->get()->toArray();
            }

        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('documentos') . '.' . $fileType;

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
            return Excel::download(new RequestExport('documentoselectronicos::documentos.reportes.reporte_excel_documentos', JwtController::generateToken($input['data']), 'R'), $fileName);
        }
    }

    public function crearDocumento(Request $request)
    {
        $input = $request->all();
        $userLogin = Auth::user();

        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;

        $input["estado"] = "Elaboración";
        $input["vigencia"] = date("Y");

        $input["elaboro_id"] = $userLogin->id;
        $input["elaboro_nombres"] = $userLogin->fullname;
        $input["users_id_actual"] = $userLogin->id;

        // Valida si se adjunto una plantilla para el tipo de documento
        if ($request->hasFile('documento_adjunto')) {
            $input['documento_adjunto'] = substr($input['documento_adjunto']->store('public/container/de_documentos_' . date("Y")), 7);
        } else if (!empty($input["plantilla"]) && !file_exists(storage_path("app/public/" . $input["plantilla"]))) { // Chequeamos si la plantilla existe
            // Valida si el usuario es administrador de documentos electrónicos
            if (Auth::user()->hasRole('Admin Documentos Electrónicos')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de documentos
                return $this->sendSuccess('<strong>¡Atención! Configuración de Tipos de Documentos Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="tipo-documentos" target="_blank">Tipos de documentos</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lo sentimos, actualmente no hay una plantilla configurada para este tipo de documento o la plantilla ha dejado de estar disponible. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }

        // Valida si se adjunto un documento principal
        if ($request->hasFile('document_pdf')) {
            $input['document_pdf'] = substr($input['document_pdf']->store('public/container/de_documentos_' . date("Y")), 7);
        }
        $crear_documento_google_drive = false;
        // Valida si tiene un documento adjuntado o como plantilla desde el tipo de documento
        if (!empty($input["documento_adjunto"]) || !empty($input['plantilla'])) {
            $documento_plantilla = $input["documento_adjunto"] ?? $input['plantilla'];
            $documento = explode(".", $documento_plantilla);
            $extension_plantilla = end($documento);
        } else {
            // Si no tiene plantilla, se asume que va a crear un documento vacio de Google Docs
            $documento_plantilla = "";
            $extension_plantilla = "docx";
        }

        switch ($extension_plantilla) {
            case 'docx':
            case 'doc':
                $prefijo_url_plantilla = "https://docs.google.com/document/d/";
                $crear_documento_google_drive = true;
                break;

            case 'xlsx':
            case 'xls':
                $prefijo_url_plantilla = "https://docs.google.com/spreadsheets/d/";
                $crear_documento_google_drive = true;
                break;

            default:
                $crear_documento_google_drive = false;
                break;
        }

        // Si no tiene plantilla, se asume que va a crear un documento vacio de Google Docs
        if (!$documento_plantilla) {
            $google = new GoogleController();
            $id_google = $google->crearDocumentoEnBlancoGoogleDrive($input["titulo_asunto"], "word", "Documentos Electrónicos");
        } else {
            if ($crear_documento_google_drive) {
                $google = new GoogleController();
                $id_google = $google->crearDocumentoGoogleDrive($input["titulo_asunto"], storage_path("app/public/" . $documento_plantilla), "Documentos Electrónicos");
            }
        }

        if (isset($id_google)) {
            // Decodifica el JSON
            $decodedJson = json_decode($id_google);

            // Primero, verifica si la decodificación fue exitosa y no es nula
            if ($decodedJson !== null) {
                // Luego, verifica si la propiedad TYPE_ERROR existe
                if (isset($decodedJson->TYPE_ERROR) && $decodedJson->TYPE_ERROR) {
                    return $this->sendSuccess($decodedJson->MESSAGE, 'info');
                }
            }
        }

        if ($crear_documento_google_drive) {
            $input["plantilla"] = $prefijo_url_plantilla . $id_google;
        }

        $input["consecutivo"] = "DE" . date("YmdHis");

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $documento = $this->documentoRepository->create($input);

            $input['de_documento_id'] = $documento->id;
            $input['observacion_historial'] = "Creación del documento";

            // Crea un nuevo registro de historial
            $this->documentoHistorialRepository->create($input);
            //Obtiene el historial
            $documento->deTiposDocumentos;
            $documento->metadatos;
            $documento->documentoHistorial;
            $documento->users;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() .  ' Linea: '. $e->getLine() . ' Consecutivo: '.$documento['consecutivo']);
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
        }
    }

    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 4. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getCompartidos(Request $request)
    {
        // Query de compartidos
        $query = $request->input('query');

        // Usuarios
        $users = User::select(DB::raw('id, id_dependencia, id_cargo, "Usuario" as categoria, name'))->where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
            ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
            ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
            ->get() // Realiza la consulta y obtiene una colección de usuarios
            ->map(function ($user) {
                // Agrega el atributo 'fullname' a cada instancia de usuario
                $user->nombre = $user->fullname; // Accede al mutador para calcular el fullname
                return $user; // Retorna el usuario con el nuevo atributo 'fullname'
            });

        // Dependencias
        $query = $request->input('query');
        $dependencias = DB::table('dependencias')
            ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS nombre, dependencias.id as users_id, "Dependencia" AS categoria'))
            ->where('dependencias.nombre', 'like', '%' . $query . '%')
            ->get();

        $compartidos = array_merge($users->toArray(), $dependencias->toArray());

        return $this->sendResponse($compartidos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los documentos electrónicos según el criterio de búsqueda
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 4. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDocumentos(Request $request)
    {
        // query de documentos
        $query = $request->input('query');

        $documento = Documento::where('consecutivo', 'like', '%' . $query . '%') // Filtra por consecutivo que contenga el valor de $query
            ->orWhere('titulo_asunto', 'like', '%' . $query . '%') // Filtra por título que contenga el valor de $query
            ->get(); // Realiza la consulta y obtiene una colección de documentos coincidentes

        return $this->sendResponse($documento->toArray(), trans('data_obtained_successfully'));
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
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '.($documento['consecutivo'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
        }
    }

    public function leidoAnotacion($documentoId)
    {
        // Obtener el ID del usuario actual
        $userId = Auth::id();

        // Actualizar los registros directamente en la base de datos
        DocumentoAnotacion::where('de_documento_id', $documentoId)
            ->where(function ($q) use ($userId) {
                $q->where('leido_por', 'not like', '%,' . $userId . ',%'); // Si ya contiene el ID del usuario actual, no lo actualiza
                $q->where('leido_por', 'not like', $userId . ',%');
                $q->where('leido_por', 'not like', '%,' . $userId);
                $q->where('leido_por', 'not like', $userId);
                $q->orWhereNull('leido_por');
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        // Buscar y obtener la instancia del Documento
        $documento = $this->documentoRepository->find($documentoId);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Documento
        $documento->anotacionesPendientes;

        // Devolver una respuesta con los datos de la instancia del Documento actualizados
        return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 22 - 2024
     * @version 1.0.0
     *
     * @param int $id del registro procediente de las entradas recientes del dashboard o de alguna vista por medio de un link
     */
    public function showFromDashboard($id)
    {
        $documento = $this->documentoRepository->find($id);
        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }
        // Relaciones
        $documento->deDocumentoHistorials;
        $documento->deDocumentoVersions;
        $documento->deTiposDocumentos;
        $documento->serieClasificacionDocumental;
        $documento->subserieClasificacionDocumental;
        $documento->oficinaProductoraClasificacionDocumental;
        $documento->deCompartidos;
        $documento->documentoLeido;
        $documento->deDocumentoHasDeMetadatos;
        $documento->deDocumentoFirmars;
        $documento->users;

        return $this->sendResponse($documento->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Asigna el leído del documento electrónico según el usuario
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 3 - 2024
     * @version 1.0.0
     *
     * @param int $id del documento
     */
    public function leido($documentoId)
    {
        // Información del usuario logueado
        $userLogin = Auth::user();

        $leidoDocumento = DocumentoLeido::select("id", "accesos", 'nombre_usuario')->where("de_documento_id", $documentoId)->where("users_id", $userLogin->id)->first();
        if ($leidoDocumento) {
            // Valida si ya tiene accesos
            if ($leidoDocumento["accesos"]) {
                $accesos = $leidoDocumento["accesos"] . "<br/>" . date("Y-m-d H:i:s");
            } else {
                $accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido
            $resultleidoDocumento = DocumentoLeido::where("id", $leidoDocumento["id"])->update(["accesos" => $accesos], $leidoDocumento["id"]);
        } else {
            $leidoDocumento = date("Y-m-d H:i:s");

            // Valida si es el usuario que esta leyendo el registro, tiene el rol de administrador
            if (Auth::user()->hasRole('Admin Documentos Electrónicos')) {
                $rol = "Administrador";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del registro
            $resultleidoDocumento = DocumentoLeido::create([
                'tipo_usuario' => $rol,
                'nombre_usuario' => $userLogin->fullname,
                'accesos' => $leidoDocumento,
                'vigencia' => date("Y"),
                'de_documento_id' => $documentoId,
                'users_id' => $userLogin->id
            ]);
        }

        // Buscar y obtener la instancia de Documento correspondiente
        $documento = $this->documentoRepository->find($documentoId);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Documento
        $documento->deDocumentoHistorials;
        $documento->deDocumentoVersions;
        $documento->deTiposDocumentos;
        $documento->serieClasificacionDocumental;
        $documento->subserieClasificacionDocumental;
        $documento->oficinaProductoraClasificacionDocumental;
        $documento->deCompartidos;
        $documento->documentoLeido;
        $documento->deDocumentoHasDeMetadatos;
        $documento->deDocumentoFirmars;

        // Devolver una respuesta con los datos de la instancia del documento actualizado
        return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
    }

     /**
     * Obtiene los datos del registro, para asignarlo de nuevo al dataform
     *
     * @version 1.0.0
     *
     */
    public function getHiddenData($id)
    {
         $documento = Documento::where('id', $id)->get();
         return $this->sendResponse($documento->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Obtiene los usuarios del sistema
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 04. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsuarios(Request $request)
    {
        // Usuarios
        $query = $request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
            ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
            ->where('id_cargo', '!=', 0)
            ->where('id_dependencia', '!=', 0)
            ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
            ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los usuarios del sistema que tengan permiso de firmar documentos
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 04. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsuariosFirmar(Request $request)
    {
        // Usuarios
        $query = $request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
            ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
            ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
            ->where('id_cargo', '!=', 0)
            ->where('id_dependencia', '!=', 0)
            ->where('autorizado_firmar', 1) // Filtra los registros donde 'autorizado_firmar' sea igual a 1
            ->where('url_digital_signature', '!=', '') // Asegura que el campo 'url_digital_signature' no esté vacío
            ->whereNotNull('url_digital_signature')
            ->get() // Realiza la consulta y obtiene una colección de usuarios
            ->map(function ($user) {
                $user->users_id = $user->id;
                return $user; // Retorna el usuario con el nuevo atributo 'fullname'
            });

        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Procesa el envío de un documento.
     *
     * Esta función procesa la solicitud de envío de un documento, actualizando su estado
     * y realizando las operaciones necesarias según la acción especificada.
     *
     * @param Illuminate\Http\Request $request La solicitud HTTP recibida.
     * @return Illuminate\Http\Response La respuesta HTTP con el resultado del procesamiento.
     */
    public function enviarDocumento(Request $request) {
        try {
            // Obtener todos los datos de la solicitud
            $input = $request->all();

            // Obtener el ID del documento desde los datos de entrada
            $id = $input["id_documento"] ?? null;

            // Buscar el documento en el repositorio por su ID
            $documento = $this->documentoRepository->find($id);

            // Verificar si el documento no fue encontrado
            if (empty($documento)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            // Comenzar una transacción de base de datos
            DB::beginTransaction();
            try {
                // Verificar la acción especificada en los datos de entrada
                if ($input["accion_documento"] == "Revisión (Editado por externo)") {
                    // Actualizar el estado del documento y agregar observaciones
                    $estado = $input["accion_documento"];
                    $this->actualizarEstadoDocumento($id, $estado, $input["observaciones"]);

                    // Registrar un historial de la acción realizada en el documento
                    $history = [];
                    $history["users_id"] = 1;
                    $history["users_name"] = $documento["users_name_actual"];
                    $history["consecutivo"] = $documento["consecutivo"];
                    $history['de_documento_id'] = $documento->id;
                    $history['observacion_historial'] = "Actualización de documento";
                    $history['observacion'] = $input["observaciones"];
                    $history['titulo_asunto'] = $documento["titulo_asunto"];
                    $history['document_pdf'] = $documento["document_pdf"];
                    $history['estado'] = "Revisión (Editado por externo)";
                    $history['de_tipos_documentos_id'] = $documento["de_tipos_documentos_id"];

                    // Crea un nuevo registro de historial
                    $this->documentoHistorialRepository->create($history);
                    // $this->crearHistorial($documento, "Devolución en la firma del documento: ".$input["observaciones"] ?? "",$user_name,$user_id);
                }

                // Confirmar la transacción si no se produjeron errores
                DB::commit();

                // Enviar una respuesta de éxito con los datos del documento actualizados
                return $this->sendResponse($documento->toArray(), "Documento firmado con éxito", "success");
            } catch (\Illuminate\Database\QueryException $error) {
                // Revertir la transacción en caso de error de la base de datos
                DB::rollback();

                // Generar un registro de error en el sistema de logs
                $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());

                // Enviar una respuesta informativa con un mensaje de soporte genérico
                return $this->sendSuccess(config('constants.support_message'), 'info');
            } catch (\Exception $e) {
                // Revertir la transacción en caso de error general
                DB::rollback();

                // Generar un registro de error en el sistema de logs
                $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' -  -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine().' Consecutivo: '.($documento["consecutivo"] ?? 'Desconocido'));

                // Enviar una respuesta informativa con un mensaje de soporte genérico
                return $this->sendSuccess(config('constants.support_message'), 'info');
            }
        } catch (\Exception $e) {
            // Capturar cualquier error no esperado y enviar una respuesta de error con el mensaje
            return $this->sendError($e->getMessage(), 500);
        }
    }


    /**
     * Registra la firma de un usuario sobre el documento
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 04. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function firmarDocumento(Request $request) {

        try {
            $input = $request->all();

            
            if(isset($input["tipo_firmado"]) && $input["tipo_firmado"] == "externo") {

                // Devuelto para modificaciones
                $id = $input["id_documento"] ?? null;
                if($id !== null) {
                    // $documento = $this->documentoRepository->find($id);
                    // $input['accion_documento'] = $input['accion_documento'];
                    // $input['observaciones'] = $input['observaciones'];
                    $idTablaFirmar = JwtController::decodeToken($input['idTablaFirmar']);

                }
            } else {

                $id = $input["id"] ?? null;

                $idTablaFirmar = DocumentoFirmar::where('de_documentos_id', $id)
                ->where('users_id', Auth::user()->id)
                ->orderBy('de_documento_version_id', 'desc') // Order by ID in descending order
                ->first()
                ->id;

            }

            $documento = $this->documentoRepository->find($id);
            $documento->users;

            if (empty($documento)) {
                return $this->sendErrorData(trans('not_found_element'), 200);
            }

            // Se valida que si tenga una versión relacionada, si no la tiene, posiblemente sea un documento en estado de revisión que se está devolviendo
            $datosVersionActual = $documento->deDocumentoVersions->first() ? $documento->deDocumentoVersions[0] : null;

            if(isset($input["tipo_firmado"]) && $input["tipo_firmado"] == "externo") {

                $user_name = DocumentoFirmar::where('id', $idTablaFirmar)
                ->pluck('nombre_usuario')
                ->first();

                if($input["accion_documento"] != "Devolver para modificaciones"){
                    $url_firma = $input["rutafirma"];
                }else{
                    $url_firma = "";
                }

                $user_id = 0;


            } else {

                $datosUsuario = Auth::user();
                $user_name = $datosUsuario->name;
                $user_id = $datosUsuario->id;

                if($input["accion_documento"] != "Devolver para modificaciones"){
                    $url_firma = $this->validarFirmaUsuario();
                }else{
                    $url_firma = "";
                }
            }
            DB::beginTransaction();
            try {
                // Valida si el documento fue devuelto por el usuario, sea interno o externo
                if ($input["accion_documento"] == "Devolver para modificaciones") {
                    $estado = "Devuelto para modificaciones";
                    $documento["tipo_usuario"] = !empty($input["tipo_firmado"]) ?  $input["tipo_firmado"] : "Interno";
                    $documento["observacion_devuelto"] = $input["observaciones"];
                    $documento["nombre_usuario_accion_devolver"] = $user_name;
                    // Se valida que si tenga una versión relacionada para actualizar su estado a devuelto
                    if(!empty($datosVersionActual))
                        $this->actualizarEstadoVersion($datosVersionActual,$estado);
                    $this->actualizarEstadoFirma($idTablaFirmar,$estado,$input["observaciones"]);
                    $this->actualizarEstadoDocumento($id,$estado,$input["observaciones"]);
                    // Envia notificación al usuario creador del documento, indicándole de la acción devuelta del documento
                    $document_id_encrypted = base64_encode($documento["id"]);
                    $documento["link"] = "/documentos-electronicos/documentos?qder=".$document_id_encrypted;
                    $envioNotificacion = $this->enviarNotificacion($documento, [$documento["users"]["email"]], "devolver_documento");

                    // $this->crearHistorial($documento, "Devolución en la firma del documento: ".$input["observaciones"] ?? "",$user_name,$user_id);
                } else {


                    // // Valida si el usuario posee una firma para la publicación del documento
                    // if(!$datosUsuario->url_digital_signature){
                    //     // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                    //     return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                    // }else{
                    //     if (!file_exists(storage_path("app/public/".$datosUsuario->url_digital_signature))) {
                    //         return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                    //     }
                    // }

                    $aprobarFirma = $this->aprobarFirma($documento, $datosVersionActual,$input,$user_name,$user_id,$idTablaFirmar,$url_firma);
                    if($aprobarFirma){
                        return $aprobarFirma;
                    }
                    // $this->crearHistorial($documento, "Firma Aprobada: ".$input["observaciones"] ?? "",$user_name,$user_id);
                }

                DB::commit();


                if($documento["estado"]=='Público'){
                        $correos = $this->obtenerCorreosFirmantes($datosVersionActual->id);
                        $documento["id_encriptado"] = JwtController::generateToken($id);

                        $envioNotificacion = $this->enviarNotificacion($documento, $correos,'documento_firmado');
                    }

                return $this->sendResponse($documento->toArray(), "Documento firmado con éxito", "success");
            } catch (\Illuminate\Database\QueryException $error) {
                DB::rollback();
                $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\DocumentoController - ' . (Auth::user()->name ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage());
                return $this->sendSuccess(config('constants.support_message'), 'info');
            } catch (\Exception $e) {
                DB::rollback();
                $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' -  -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                return $this->sendSuccess(config('constants.support_message'), 'info');
            }
        } catch (\Exception $e) {
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile() . ' -  -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($documento['consecutivo'] ?? 'Desconocido'));
            return $this->sendErrorData($e->getMessage(), 500);
        }
    }


    private function actualizarEstadoDocumento($id,$estado,$observaciones) {
            Documento::where('id', $id)
            ->update(['estado' => $estado,'observacion'=>$observaciones]);

    }
    private function actualizarEstadoVersion($datosVersionActual,$estado) {
        DocumentoVersion::where('id', $datosVersionActual->id)
            ->update(['estado' => $estado]);
    }

    private function actualizarEstadoFirma($idTablaFirmar,$estado,$observaciones) {
        DocumentoFirmar::where('id', $idTablaFirmar)
            ->update(['estado' => $estado, 'observacion' => $observaciones]);
    }

    private function crearHistorial($documento, $observacion,$user_name,$user_id) {
        $documento['users_name'] = $user_name ?? '';
        $documento['users_id'] = $user_id;
        $documento['de_documento_id'] = $documento->id;
        $documento['observacion_historial'] = $observacion ?? '';
        // $this->documentoHistorialRepository->create($documento);
    }

    private function aprobarFirma($documento, $datosVersionActual, $input,$user_name,$user_id,$idTablaFirmar,$url_firma) {

        $contadorFirmas = DocumentoFirmar::where('de_documento_version_id', $datosVersionActual->id)
            ->where('estado', 'Pendiente de firma')
            ->count();

        $fullHash = hash('sha256', date("Y-m-d H:i:s") . $user_name . $documento["consecutivo"]);
        $hash = "ID firma: " . base64_encode(hex2bin($fullHash));
        $publicIp = $this->detectIP();

        DocumentoFirmar::where('id', $idTablaFirmar)
            ->update(['fecha_firma' => date("Y-m-d H:i:s"), 'estado' => "Firma aprobada", 'observacion' => $input["observaciones"], 'hash' => $hash, 'ip' => $publicIp,'url_firma'=>$url_firma]);

        if ($contadorFirmas == 1) {
            $actualizarConsecutivoVariables = $this->actualizarConsecutivoYVariables($documento,$datosVersionActual);
            if($actualizarConsecutivoVariables){
                return $actualizarConsecutivoVariables;
            }
        }

    }

    private function validarFirmaUsuario() {
        $userLogin = Auth::user();
        if (!$userLogin->url_digital_signature || !file_exists(storage_path("app/public/" . $userLogin->url_digital_signature))) {
            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
        }

        return $userLogin->url_digital_signature;
    }

    private function actualizarConsecutivoYVariables($documento, $datosVersionActual) {
        $de_tipos_documentos = $documento->deTiposDocumentos;

        $formatoConsecutivoValores = [];
        $formatoConsecutivoValores["prefijo_dependencia"] = $documento->users->dependencies->codigo;
        $formatoConsecutivoValores["serie_documental"] = $documento["classification_serie"] ?? '';
        $formatoConsecutivoValores["subserie_documental"] = $documento["classification_subserie"] ?? '';
        $formatoConsecutivoValores["prefijo_documento"] = $de_tipos_documentos["prefijo"];
        $formatoConsecutivoValores["separador_consecutivo"] = $de_tipos_documentos["separador_consecutivo"];
        $formatoConsecutivoValores["vigencia_actual"] = date("Y");

        // Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order (prefijo)
        $datosConsecutivo = UtilController::getNextConsecutive($de_tipos_documentos["formato_consecutivo"], $de_tipos_documentos["prefijo_incrementan_consecutivo"], $formatoConsecutivoValores);

        $documento["consecutivo"] = $datosConsecutivo['consecutivo'];
        $documento["consecutivo_prefijo"] = $datosConsecutivo['consecutivo_prefijo'];
        // Estado del documento
        $documento["estado"] = "Público";

        $variables = $de_tipos_documentos["variables_plantilla_requeridas"] && $de_tipos_documentos["variables_plantilla"] ? explode(", ", $de_tipos_documentos["variables_plantilla"]) : [];

        $information["#consecutivo"] = $documento["consecutivo"];
        $information["#titulo"] = $documento["titulo_asunto"];
        $information["#dependencia_remitente"] = $documento->users->dependencies->nombre;
        $information["#compartidos"] = str_replace("<br>", " - ", $documento["compartidos"]);
        $information["#tipo_documento"] = $de_tipos_documentos["nombre"];
        $information["#elaborado"] = $documento["elaboro_nombres"];
        $information["#revisado"] = $documento["reviso_nombres"] ?? '';
        $information["#proyecto"] = $documento["elaboro_nombres"];
        $information["#codigo_formato"] = $de_tipos_documentos["codigo_formato"];
        $information["#documento_asociado"] = $documento["documentos_asociados"] ?? '';
        $information["#codigo_dependencia"] = $documento->users->dependencies->codigo;

        $firmasFinales=[];

        $contadorFirmas = DocumentoFirmar::where('de_documento_version_id', $datosVersionActual->id)->with('users')->get();
        $firmasFinales = [];

        foreach ($contadorFirmas as $datosFirma) {
            $usuario = new \stdClass();
            $usuario->users = new \stdClass();
            $usuario->users->name = $datosFirma->nombre_usuario;
            $usuario->users->url_digital_signature = $datosFirma->url_firma;
            // Valida el tipo de usuario, ya que la relación 'users' solo está disponible para los usuarios internos
            if($datosFirma->tipo_usuario == 'Interno') {
                $usuario->users->escala_firma = $datosFirma->users->escala_firma;
            }
            $usuario->users->hash = $datosFirma->hash;
            $firmasFinales[] = $usuario;
        }

        $information["#firmas"] = $firmasFinales;


        setlocale(LC_ALL, "es_CO.UTF-8");
        $information["#fecha"] = strftime("%d de %B del %Y");

        // Obtiene la información de los metadatos con sus valores si el documento los tiene
        $metadatos = DocumentoHasMetadato::with("deMetadatos")->where("de_documentos_id", $documento->id)->get()->toArray();
        // Arreglo temporal para almacenar la variable del metadato como clave y el valor
        $valores_metadatos = [];
        // Recorre los valores de metadatos si el documento los tiene
        foreach ($metadatos as $metadato) {
            $variable = $metadato["de_metadatos"]["variable_documento"];
            $variable ? $valores_metadatos[$variable] = $metadato["valor"] : null;
        }
        // Une los arreglos de las variables del documento predeterminada con los metadatos
        $information = array_merge($information, $valores_metadatos);

        $documento["validation_code"] = $this->generarCodigoValidacion();
        $information["#codigo_validacion"] = $documento["validation_code"];


        $id_google = explode("/", $documento["plantilla"]);
        $id_google = end($id_google);
        $google = new GoogleController();
        $returnGoogle = $google->editFileDoc(null, $documento->id, $id_google, $variables, $information, 0);
        if ($returnGoogle['type_message'] == 'info') {
            DB::rollback();
            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
        }
        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, $documento["formato_publicacion"] == "PDF" || empty($documento["formato_publicacion"]) ? "pdf" : "", $documento["consecutivo"], "container/de_documentos_" . date('Y'));
        // Si la variable 'documento_almacenado' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
        if(!empty($documento_almacenado["type_message"]) && $documento_almacenado["type_message"] == "warning") {
            // Devuelve los cambios realizados
            DB::rollback();
            // Retorna mensaje de advertencia al usuario
            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $documento_almacenado["message"], 'warning');
        }
        $documento["document_pdf"] = $documento_almacenado;
        // Valida el 'TIPO_ALMACENAMIENTO_GENERAL', si es AWS
        if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS") {
            // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
            $requestObjectAWS = new Request();
            // Ruta del documento
            $requestObjectAWS["path"] = $documento["document_pdf"];
            // Tipo de url de descarga 'obtener_hash_documento_aws', quiere decir que calculará el hash del documento desde S3
            $requestObjectAWS["tipoURL"] = "obtener_hash_documento_aws";
            // Se hace la solicitud a la función 'readObjectAWS' para obtener la URL prefirmada del archivo
            $archivo_aws = $this->readObjectAWS($requestObjectAWS);
            // Se decodifica la URL
            $hash_document_pdf = JwtController::decodeToken($archivo_aws['data']);
            // Se asigna el hash calculado del archivo desde S3 de AWS
            $documento["hash_document_pdf"] = $hash_document_pdf;
        } else {
            // Genera una cadena hash usando el archivo local del campo document_pdf
            $documento["hash_document_pdf"] = hash_file('sha256', 'storage/' . $documento["document_pdf"]);
        }

        $documento["codigo_acceso_documento"] = Str::random(10);

        $this->documentoRepository->update($documento->toArray(), $documento->id);

        $documento->deDocumentoHistorials;
        $documento->deDocumentoVersions;
        $documento->deTiposDocumentos;
        $documento->serieClasificacionDocumental;
        $documento->subserieClasificacionDocumental;
        $documento->oficinaProductoraClasificacionDocumental;
        $documento->deCompartidos;
        $documento->documentoLeido;
        $documento->deDocumentoHasDeMetadatos;
        $documento->deDocumentoFirmars;

    }

    private function obtenerCorreosFirmantes($idDatosVersionActual)
    {
        // Obtener los registros de DocumentoFirmar con los datos de usuarios relacionados
        $firmas = DocumentoFirmar::with('users') // Cargar relación "users"
            ->where('de_documento_version_id', $idDatosVersionActual)
            ->get();

        // Inicializar un array para almacenar los correos electrónicos
        $firmasFinales = [];

        // Iterar sobre cada registro de DocumentoFirmar
        foreach ($firmas as $documentoFirmar) {

            if($documentoFirmar->tipo_usuario == 'Externo'){

                $firmasFinales[] = $documentoFirmar->correo;


            }else{
                    // Verificar si el registro tiene asociado un usuario
                if ($documentoFirmar->users) {
                    // Obtener el correo electrónico del usuario asociado
                    $correoUsuario = $documentoFirmar->users->email;

                    // Agregar el correo electrónico al array final si es válido
                    if ($correoUsuario) {
                        $firmasFinales[] = $correoUsuario;
                    }
                }

            }

        }

        return $firmasFinales;
    }

    private function generarCodigoValidacion() {
        $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($caracteres_permitidos), 0, 8);

    }

    /**
     * Exporta el historial de un documento electrónico
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abril. 022. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportarHistorial($id)
    {
        $historial = DocumentoHistorial::with("deTiposDocumentos")->where('de_documento_id', $id)->get();

        return Excel::download(new RequestExport('documentoselectronicos::documentos.reportes.reporte_historial', JwtController::generateToken($historial->toArray()), 'I'), 'Prueba.xlsx');
    }

    public function compartirDocumento(UpdateDocumentoRequest $request)
    {
        $input = $request->all();
        $id = $input["id"];

        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }

        $userLogin = Auth::user();

        // Valida si viene usuarios para asignar
        if (!empty($input['de_compartidos'])) {

            //borra todo para volver a insertarlo
            DocumentoCompartir::where('de_documentos_id', $id)->delete();

            // Arreglo para almacenar el nombre de los usuarios y/o dependencias a los que se le compartió el documento
            $usuariosCompartidos = array();
            // Recorre los compartidos
            foreach ($input['de_compartidos'] as $recipent) {
                // Array de compartidos
                $destinatarioArray = json_decode($recipent, true);
                $destinatarioArray["de_documentos_id"] = $id;
                $usuariosCompartidos[] = $destinatarioArray["nombre"];

                DocumentoCompartir::create($destinatarioArray);
            }
            $actualizarDocumento["compartidos"] = implode("<br>", $usuariosCompartidos);
        } else {
            // Elimina todos lo usuarios/dependencias que estaban como compartidos
            DocumentoCompartir::where('de_documentos_id', $id)->delete();
            $actualizarDocumento["compartidos"] = "";
        }

        $documento = $this->documentoRepository->update($actualizarDocumento,$id);

        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;
        $input['de_documento_id'] = $documento->id;
        $input['observacion_historial'] = "Actualización de documento";

        // Crea un nuevo registro de historial
        $this->documentoHistorialRepository->create($input);

        $documento->deCompartidos;

        return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
    }

    /**
     * Función para reenviar el correo a un usuario externo con la información necesaria para el firmado de un documento
     */
    public function reenviarCorreoUsuarioExterno(Request $request) {

        $data = $request->all();
        // Se consulta la información del registro de la firma y la relación con su versión y documento
        $datosUsuarioFirmar = DocumentoFirmar::with(["deDocumentoVersion", "deDocumentos"])->where("id", $data["id"])->get()->first()->toArray();
        // Datos adicionales para el envío de la notificación
        $datosUsuarioFirmar["nombre_usuario_version"] = $datosUsuarioFirmar["de_documento_version"]["nombre_usuario"];
        $datosUsuarioFirmar["document_pdf_temp"] = $datosUsuarioFirmar["de_documento_version"]["document_pdf_temp"];
        $datosUsuarioFirmar["id_encriptado"] = JwtController::generateToken($datosUsuarioFirmar["id"]);
        $datosUsuarioFirmar["titulo_asunto"] = $datosUsuarioFirmar["de_documentos"]["titulo_asunto"];
        $datosUsuarioFirmar["consecutivo"] = $datosUsuarioFirmar["de_documentos"]["consecutivo"];
        // Se reenvía la notificación al usuario externo informándole del proceso de firma del documento
        $envioNotificacion = $this->enviarNotificacion($datosUsuarioFirmar, [$datosUsuarioFirmar["correo"]],'firmar_varios');

        return $this->sendResponse($envioNotificacion, "Reenvio del correo con éxito", "success");
    }
}
