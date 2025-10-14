<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Calidad\Http\Requests\CreateDocumentoRequest;
use Modules\Calidad\Http\Requests\UpdateDocumentoRequest;
use Modules\Calidad\Repositories\DocumentoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JwtController;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Exception;
use Modules\Calidad\Models\Documento;
use Modules\Calidad\Models\DocumentoHistorial;
use Modules\Calidad\Models\TipoSistema;
use Modules\DocumentosElectronicos\Models\TipoDocumento;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\Calidad\Models\DocumentoLeido;
use Modules\Calidad\Models\Proceso;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use ZipArchive;
/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class DocumentoController extends AppBaseController {

    /** @var  DocumentoRepository */
    private $documentoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocumentoRepository $documentoRepo) {
        $this->documentoRepository = $documentoRepo;
    }

    /**
     * Muestra la vista para el CRUD de Documento.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request, $nombre_proceso = null) {
        return view('calidad::documentos.index', compact('nombre_proceso'));
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
        // Variable para contar el número total de registros de la consulta realizada
        $count_documentos = 0;
        $documentos_estado = [];
        // Arreglos para formatear los estados originales a estados sin caracteres especiales ni espacios, esto para la asigación de los consolidados en el tablero
        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Rechazado"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "rechazado"];
        $totalSuma = 0;

        // Decodifica los campos filtrados
        $filtros = str_replace(["'{","}'"], ["{","}"], base64_decode($request["f"]));

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        //valida si es un administrador de documentos de calidad
        if (Auth::user()->hasRole('Admin Documentos de Calidad')) {

            $documentos_estado = Documento::select('estado', DB::raw('COUNT(estado) as total'))->groupBy("calidad_documento.estado")->get()->toArray();
            // Arreglo para almacenar la cantidad de documentos de calidad según el estado (Público, Elaboración, Revisión, Aprobación, Pendiente de firma, Devuelto para modificaciones)
            $estado_totales = [];
            // Recorre el resultado de los documentos de calidad según el estado, organizándolos por su estado como llave y el total como el valor
            foreach ($documentos_estado as $v) {
                $estado_totales[str_replace($estados_originales, $estados_reemplazar, $v["estado"])] = $v["total"];
                $totalSuma += $v["total"];
            }

            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])
                ->whereRaw($filtros)
                ->latest("updated_at")->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                // Contar el número total de registros de la consulta realizada según los filtros
                $count_documentos = Documento::whereRaw($filtros)->count();
            } else if (isset($request["cp"]) && isset($request["pi"])) {
                // Consulta los tipo de solicitudes según el paginado seleccionado
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])->latest("updated_at")->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_documentos = Documento::count();
            } else {
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])->latest("updated_at")->get()->toArray();
                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_documentos = Documento::count();
            }
        } else {
            // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
            $documentos_estado =  Documento::select('estado', DB::raw('COUNT(DISTINCT calidad_documento.id) as total'))
                ->where("calidad_documento.users_id_actual", Auth::user()->id)
                ->groupBy("calidad_documento.estado")->get()->toArray();

            // Arreglo para almacenar la cantidad de documentos de calidad según el estado (Público, Elaboración, Revisión, Aprobación, Pendiente de firma, Devuelto para modificaciones)
            $estado_totales = [];
            // Recorre el resultado de los documentos de calidad según el estado, organizándolos por su estado como llave y el total como el valor
            foreach ($documentos_estado as $v) {
                $estado_totales[str_replace($estados_originales, $estados_reemplazar, $v["estado"])] = $v["total"];
                $totalSuma += $v["total"];
            }

            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {

                //se agrega ya qee desde el dashboard al dar clic manda el filtro por id y daba error de ambiguedad.
                $filtros = str_replace("id LIKE","calidad_documento.id LIKE",$filtros);
                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])
                    ->where("calidad_documento.users_id_actual", Auth::user()->id)
                    ->whereRaw($filtros)->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
                    ->groupBy("calidad_documento.id")
                    ->distinct("calidad_documento.id")
                    // ->tosql();
                    ->latest("updated_at")->get()->toArray();

                // Contar el número total de registros de la consulta realizada según los filtros
                $count_documentos = Documento::where("calidad_documento.users_id_actual", Auth::user()->id)
                    ->whereRaw($filtros)
                    ->distinct("calidad_documento.id")->count();
            } else if (isset($request["cp"]) && isset($request["pi"])) {
                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])
                    ->where("calidad_documento.users_id_actual", Auth::user()->id)
                    ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
                    ->groupBy("calidad_documento.id")->latest("updated_at")->get()->toArray();

                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                $count_documentos = Documento::where("calidad_documento.users_id_actual", Auth::user()->id)->count();
            } else {
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $documentos = Documento::with(['documentoHistorials', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'users', 'documentoSolicitudDocumental', 'documentoTipoDocumento', 'documentoProceso', 'tipoSistema', 'documentoLeido'])->latest()->get()->toArray();
                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_documentos = Documento::count();
            }
        }
        return $this->sendResponseAvanzado($documentos, trans('data_obtained_successfully'), null, ["estados" => $estado_totales, "total_documentos" => $totalSuma, "total_registros" => $count_documentos]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentoRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoRequest $request) {

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
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
     * @param UpdateDocumentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentoRequest $request)
    {
        $input = $request->all();

        $userLogin = Auth::user();

        /** @var Documento $documento */
        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendErrorData(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $users = json_decode($input["users"], true);

            $formatoConsecutivoValores = [];
            $formatoConsecutivoValores["prefijo_dependencia"] = $users["dependencies"]["codigo"];

            $tipo_documento = json_decode($input["calidad_documento_tipo_documento"], true);
            $proceso = json_decode($input["calidad_documento_proceso"], true);

            $input["tipo_documento"] = $tipo_documento["nombre"];
            $input["proceso"] = str_replace([" -- Subproceso ", "Proceso "], "", $proceso["nombre"]);

            $formatoConsecutivoValores["prefijo_tipo_proceso"] = $users["dependencies"]["codigo"];
            $formatoConsecutivoValores["prefijo_proceso"] = $proceso["prefijo"];
            $formatoConsecutivoValores["orden_proceso"] = $proceso["orden"];
            $formatoConsecutivoValores["prefijo_tipo_documento"] = $tipo_documento["prefijo"];

            $formatoConsecutivoValores["separador_consecutivo"] = $input["separador_consecutivo"];

            $formatoConsecutivoValores["serie_documental"] = $input["classification_serie"] ?? '';
            $formatoConsecutivoValores["subserie_documental"] = $input["classification_subserie"] ?? '';
            $formatoConsecutivoValores["vigencia_actual"] = date("Y");

            // Valida el documento adjunto principal del documento es un arreglo, de ser verdadero, la ruta se convierte en una cadena
            if (is_array($input["documento_adjunto"])) {
                $input["documento_adjunto"] = implode(",", $input["documento_adjunto"]);
            }

            // tipo = Acción a realizar con el documento actual
            switch ($input["tipo"]) {

                case 'recuperacion':
                    $input["updated_at"] =  $documento->updated_at;

                    $input["created_at"] =  $documento->created_at;

                    $id_google = explode("/", $input["plantilla"]);
                    $id_google = end($id_google);

                    $google = new GoogleController();

                    $ruta_documento = $google->saveFileGoogleDrive($id_google, $input["formato_publicacion"] == "PDF" || empty($input["formato_publicacion"]) ? "pdf" : "", $input["consecutivo"], "container/de_documentos_" . date('Y'));

                    $input["document_pdf"] = $ruta_documento;
                    // Genera una cadena hash usando el archivo del campo document_pdf
                    $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                    break;

                case 'publicacion':
                    $input["distribucion"] = implode(",", $input["distribucion"]);
                    if ($input["origen_documento"] == 'Producir documento en línea a través de Intraweb') {
                        // Si el estado del documento es diferente de Público, calcula el consecutivo del documento
                        if ($input["estado"] != "Público") {
                            $prefijo_consecutivo = explode(", ", $input["codigo_formato"]);
                            // Encuentra la clave asociada a "consecutivo_documento"
                            $key = array_search('consecutivo_documento', $prefijo_consecutivo);

                            if ($key !== false) {
                                // Elimina el elemento del array
                                unset($prefijo_consecutivo[$key]);
                            }
                            $prefijo_consecutivo = implode("-", $prefijo_consecutivo);
                            // Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order (prefijo)
                            $datosConsecutivo = UtilController::getNextConsecutive($input["codigo_formato"], $prefijo_consecutivo, $formatoConsecutivoValores);
                            $input["consecutivo"] = $datosConsecutivo['consecutivo'];
                            $input["consecutivo_prefijo"] = $datosConsecutivo['consecutivo_prefijo'];

                            // Actualiza el registro
                            $documento = $this->documentoRepository->update(['consecutivo'=>$input["consecutivo"], 'consecutivo_prefijo'=>$input["consecutivo_prefijo"]], $id);
                        }
                        // Estado del documento
                        $input["estado"] = "Público";

                        $information["#consecutivo"] = $input["consecutivo"];
                        $information["#titulo"] = $input["titulo"];
                        $information["#version"] = $input["version"];

                        $information["#elaboro_cargo"] = $input["elaboro_cargos"] ?? '';
                        $information["#reviso_cargo"] = $input["reviso_cargos"] ?? '';
                        $information["#aprobo_cargo"] = $input["aprobo_cargos"] ?? '';

                        $information["#fecha_elaboro"] = $input["fechas_elaboro"] ?? '';
                        $information["#fecha_reviso"] = $input["fechas_reviso"] ?? '';
                        $information["#fecha_aprobo"] = $input["fechas_aprobo"] ?? '';


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

                        $id_google = explode("/", $input["plantilla"]);
                        $id_google = end($id_google);
                        $google = new GoogleController();
                        if(strpos($input["plantilla"], "spreadsheets") !== false) {
                            $returnGoogle = $google->editFileSheet(null, $id, $id_google, [], $information, 0);
                        } else {
                            $returnGoogle = $google->editFileDoc(null, $id, $id_google, [], $information, 0);
                        }
                        if ($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, $input["formato_publicacion"] == "PDF" || empty($input["formato_publicacion"]) ? "pdf" : "", $input["consecutivo"], "container/de_documentos_" . date('Y'));
                        $input["document_pdf"] = $documento_almacenado;
                        // Genera una cadena hash usando el archivo del campo document_pdf
                        $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                    } else {
                        // Si el estado del documento es diferente de Público, calcula el consecutivo del documento
                        if ($input["estado"] != "Público") {
                            $prefijo_consecutivo = explode(", ", $input["codigo_formato"]);
                            // Encuentra la clave asociada a "consecutivo_documento"
                            $key = array_search('consecutivo_documento', $prefijo_consecutivo);

                            if ($key !== false) {
                                // Elimina el elemento del array
                                unset($prefijo_consecutivo[$key]);
                            }
                            $prefijo_consecutivo = implode("-", $prefijo_consecutivo);
                            // Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order (prefijo)
                            $datosConsecutivo = UtilController::getNextConsecutive($input["codigo_formato"], $prefijo_consecutivo, $formatoConsecutivoValores);
                            $input["consecutivo"] = $datosConsecutivo['consecutivo'];
                            $input["consecutivo_prefijo"] = $datosConsecutivo['consecutivo_prefijo'];
                            //Actualiza registro
                            $documento = $this->documentoRepository->update(['consecutivo'=>$input["consecutivo"], 'consecutivo_prefijo'=>$input["consecutivo_prefijo"]], $id);
                        }
                        // Estado del documento
                        $input["estado"] = "Público";
                        $input["document_pdf"] = $input["documento_adjunto"];
                    }

                    $input["publico_nombres"] = $userLogin->fullname;
                    $input["fecha_publico"] = !empty($input["fecha_publico"]) ? $input["fecha_publico"] . ", " . date("Y-m-d") : date("Y-m-d") ;
                    $input["publico_cargos"] = !empty($input["publico_cargos"]) ? $input["publico_cargos"] . ", " . $userLogin->positions->nombre : $userLogin->positions->nombre;

                    break;

                case 'elaboracion':
                    $input["estado"] = "Elaboración";
                    $input["distribucion"] = implode(",", $input["distribucion"]);
                    $input["elaboro_id"] = isset($input["elaboro_id"]) && !empty($input["funcionario_elaboracion_revision"]) ? $input["elaboro_id"] . "," . $input["funcionario_elaboracion_revision"] : ($input["funcionario_elaboracion_revision"] ?? null);
                    // Intenta obtener el objeto funcionario desde el input (en formato JSON) y lo convierte a un array asociativo
                    $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                    $input["elaboro_nombres"] = $datos_usuario["name"];
                    $input["fechas_elaboro"] = !empty($input["fechas_elaboro"]) ? $input["fechas_elaboro"] . ", " . date("Y-m-d") : date("Y-m-d") ;
                    $input["elaboro_cargos"] = !empty($input["elaboro_cargos"]) ? $input["elaboro_cargos"] . ", " . $datos_usuario["positions"]["nombre"] : $datos_usuario["positions"]["nombre"];
                    $nombre_usuario = $datos_usuario["name"];
                    $correo_usuario = $datos_usuario["email"];

                    $input["users_id_actual"] = $input["funcionario_elaboracion_revision"] ?? null;
                    $input["users_name_actual"] = $nombre_usuario;
                    $input["reviso_id"] = "";
                    break;

                case 'revision':
                    $input["estado"] = "Revisión";
                    $input["distribucion"] = implode(",", $input["distribucion"]);
                    $input["reviso_id"] = isset($input["reviso_id"]) && !empty($input["funcionario_elaboracion_revision"]) ? $input["reviso_id"] . "," . $input["funcionario_elaboracion_revision"] : ($input["funcionario_elaboracion_revision"] ?? null);
                    // Intenta obtener el objeto funcionario desde el input (en formato JSON) y lo convierte a un array asociativo
                    $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                    $input["reviso_nombres"] = $datos_usuario["name"];
                    $input["fechas_reviso"] = !empty($input["fechas_reviso"]) ? $input["fechas_reviso"] . ", " . date("Y-m-d") : date("Y-m-d") ;
                    $input["reviso_cargos"] = !empty($input["reviso_cargos"]) ? $input["reviso_cargos"] . ", " . $datos_usuario["positions"]["nombre"] : $datos_usuario["positions"]["nombre"];
                    $nombre_usuario = $datos_usuario["name"];
                    $correo_usuario = $datos_usuario["email"];

                    $input["users_id_actual"] = $input["funcionario_elaboracion_revision"] ?? null;
                    $input["users_name_actual"] = $nombre_usuario;
                    $input["elaboro_id"] = "";

                    break;

                case 'aprobacion':
                    $input["estado"] = "Aprobación";
                    $input["distribucion"] = implode(",", $input["distribucion"]);
                    $input["aprobo_id"] = isset($input["aprobo_id"]) && !empty($input["funcionario_elaboracion_revision"]) ? $input["aprobo_id"] . "," . $input["funcionario_elaboracion_revision"] : ($input["funcionario_elaboracion_revision"] ?? null);
                    // Intenta obtener el objeto funcionario desde el input (en formato JSON) y lo convierte a un array asociativo
                    $datos_usuario = json_decode($input["funcionario_elaboracion_revision_object"], true);
                    $input["aprobo_nombres"] = $datos_usuario["name"];
                    $input["fechas_aprobo"] = !empty($input["fechas_aprobo"]) ? $input["fechas_aprobo"] . ", " . date("Y-m-d") : date("Y-m-d");
                    $input["aprobo_cargos"] = !empty($input["aprobo_cargos"]) ? $input["aprobo_cargos"] . ", " . $datos_usuario["positions"]["nombre"] : $datos_usuario["positions"]["nombre"];
                    $nombre_usuario = $datos_usuario["name"];
                    $correo_usuario = $datos_usuario["email"];

                    $input["users_id_actual"] = $input["funcionario_elaboracion_revision"] ?? null;
                    $input["users_name_actual"] = $nombre_usuario;
                    $input["elaboro_id"] = "";

                    break;

                default:

                    break;
            }

            // Actualiza el registro
            $documento = $this->documentoRepository->update($input, $id);

            $input["users_id"] = $userLogin->id;
            $input["users_name"] = $userLogin->fullname;
            $input['calidad_documento_id'] = $documento->id;
            $input['observacion_historial'] = "Actualización de documento";

            // Crea un nuevo registro de historial
            DocumentoHistorial::create($input);

            if(!empty($input["documento_id_procedente"]) && $input["documento_id_procedente"] != -1 && $input["estado"] == "Público") {
                DocumentoHistorial::where("calidad_documento_id", $documento->id)->update(["calidad_documento_id" => $input["documento_id_procedente"]]);
                DocumentoLeido::where("calidad_documento_id", $documento->id)->update(["calidad_documento_id" => $input["documento_id_procedente"]]);
                Documento::where("id", $documento->id)->delete();

                $datos_documento_actual = $documento->toArray();
                $datos_documento_actual["documento_id_procedente"] = null;
                $documento_id_obsoleto = $datos_documento_actual["id"];

                unset($datos_documento_actual["id"], $datos_documento_actual["id_encode"], $datos_documento_actual["permission_edit"], $datos_documento_actual["codigo_formato_value"]);

                Documento::where("id",  $input["documento_id_procedente"])->update($datos_documento_actual);
                $documento = $this->documentoRepository->find($input["documento_id_procedente"]);
                $documento->documento_id_obsoleto = $input["documento_id_procedente"];
            }

            //Obtiene el historial
            $documento->documentoHistorials;
            $documento->documentoTipoDocumento;
            $documento->serieClasificacionDocumental;
            $documento->subserieClasificacionDocumental;
            $documento->oficinaProductoraClasificacionDocumental;
            $documento->documentoLeido;
            $documento->documentoSolicitudDocumental;
            $documento->documentoProceso;
            $documento->tipoSistema;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_update'), 'success');
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Documento del almacenamiento
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

        /** @var Documento $documento */
        $documento = $this->documentoRepository->find($id);

        if (empty($documento)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina primero los registros de historial del documento
            $documentoHistorial = DocumentoHistorial::where("calidad_documento_id", $id)->delete();
            // Luego elimina los registros de historial de leídos del documento
            $documentoLeidos = DocumentoLeido::where("calidad_documento_id", $id)->delete();
            // Por último elimina el registro del documento de calidad
            $documento->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
        $fileName = time().'-'.trans('documentos').'.'.$fileType;

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

    public function crearDocumento(Request $request) {
        $input = $this->crearDocumentoInicial($request);
        // Si la posición 'type_message' existe y contiene la palabra info, hubo un error al guardar el documento
        if (!empty($input['type_message']) && $input['type_message'] == 'info') {
            // Retorna mensaje de error indicando que el sitio no tiene almacenamiento disponible
            return $this->sendSuccess("El espacio de almacenamiento se ha agotado. Le recomendamos contactar al administrador del sitio para obtener asistencia.", 'info');
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $documento = $this->documentoRepository->create($input);

            $input['calidad_documento_id'] = $documento->id;
            $input['observacion_historial'] = "Creación del documento";

            // Crea un nuevo registro de historial
            DocumentoHistorial::create($input);
            // Obtiene las relaciones del documento
            $documento->documentoTipoDocumento;
            $documento->documentoHistorials;
            $documento->users;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
        }
    }

    public function crearDocumentoInicial(Request $request) {
        $input = $request->all();

        $userLogin = Auth::user();

        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;

        $input["estado"] = "Elaboración";
        $input["vigencia"] = date("Y");
        $input["elaboro_id"] = $userLogin->id;
        $input["elaboro_nombres"] = $userLogin->fullname;
        $input["elaboro_cargos"] = $userLogin->positions->nombre;
        $input["fechas_elaboro"] = date("Y-m-d");
        $input["users_id_actual"] = $userLogin->id;

        $input["codigo_formato"] = implode(", ", $input["codigo_formato"]);
        $input["consecutivo"] = "DC" . date("YmdHis");

        // Valida si se adjunto una plantilla para el tipo de documento
        if ($request->hasFile('documento_adjunto')) {
            $file = $request->file('documento_adjunto');  // Obtiene el archivo
            $extension = $file->getClientOriginalExtension();  // Obtiene la extensión original del archivo
            // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
            $request = new Request();
            // Agrega el archivo como tipo file
            $request->files->set('file', $input['documento_adjunto']);
            // Ruta donde se va a guardar el archivo
            $request["file_path"] = 'public/container/calidad_documentos_' . date("Y");
            // Define si el archivo se almacenará con el nombre original o con un nombre generado automáticamente
            $request["file_name_real"] = true;
            // Si el adjunto es un documento zip, se le da un tratamiento especial
            if($extension == "zip") {
                $ruta_carpeta_zip = 'container/calidad_documentos_' . date("Y") . '/' . $input["consecutivo"] . '/';
                $zip = $this->descomprimirYGuardarModeloBizagi($input['documento_adjunto'], storage_path('app/public/'.$ruta_carpeta_zip));
                if (!empty($zip["type_message"]) && $zip["type_message"] == "ok") {
                    $input['document_pdf'] = $ruta_carpeta_zip;
                    $input['formato_archivo'] = "bizagi";
                } else if (!empty($zip["type_message"]) && $zip["type_message"] == "info") {
                    $input['document_pdf'] = JwtController::decodeToken($this->uploadInputFile($request)['data']);
                } else {
                    return $this->sendSuccess('Lo sentimos, hubo un error con el archivo comprimido.', 'info');
                }
                $input['documento_adjunto'] = JwtController::decodeToken($this->uploadInputFile($request)['data']);
            } else {
                $input['documento_adjunto'] = $this->uploadInputFile($request)['data'];
                $input['documento_adjunto'] = JwtController::decodeToken($input['documento_adjunto']);
            }
            // Valida si en la ubicación del documento hay mensaje indicando que el sitio tiene el almacenamiento lleno
            if((!empty($input['document_pdf']) && $input['document_pdf'] == "No hay espacio disponible en el almacenamiento") || 
            (!empty($input['documento_adjunto']) && $input['documento_adjunto'] == "No hay espacio disponible en el almacenamiento")) {
                return ["type_message" => "info", "message" => "No hay espacio disponible en el almacenamiento."];

            }
        } else if (!empty($input["plantilla"]) && !file_exists(storage_path("app/public/" . $input["plantilla"]))) { // Chequeamos si la plantilla existe
            // Valida si el usuario es administrador de documentos de calidad
            if (Auth::user()->hasRole('Admin Documentos de Calidad')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de documentos
                return $this->sendSuccess('<strong>¡Atención! Configuración de Tipos de Documentos Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="tipo-documentos" target="_blank">Tipos de documentos</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lo sentimos, actualmente no hay una plantilla configurada para este tipo de documento o la plantilla ha dejado de estar disponible. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }

        // Valida si se adjunto un documento principal
        if ($request->hasFile('document_pdf')) {
            $input['document_pdf'] = substr($input['document_pdf']->store('public/container/calidad_documentos_' . date("Y")), 7);
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

        $input['formato_archivo'] = $input['formato_archivo'] ?? $extension_plantilla;

        switch ($extension_plantilla) {
            case 'docx':
            case 'doc':
                $prefijo_url_plantilla = "https://docs.google.com/document/d/";
                $crear_documento_google_drive = true;
                $input['origen_documento'] = "Producir documento en línea a través de Intraweb";
                break;

            case 'xlsx':
            case 'xls':
                $prefijo_url_plantilla = "https://docs.google.com/spreadsheets/d/";
                $crear_documento_google_drive = true;
                $input['origen_documento'] = "Producir documento en línea a través de Intraweb";
                break;

            default:
                $crear_documento_google_drive = false;
                $input['origen_documento'] = "Adjuntar documento para ser almacenado en Intraweb";
                break;
        }

        // Si no tiene plantilla, se asume que va a crear un documento vacio de Google Docs
        if (!$documento_plantilla) {
            $google = new GoogleController();
            $id_google = $google->crearDocumentoEnBlancoGoogleDrive($input["titulo"], "word", "Documentos de Calidad");
        } else {
            if ($crear_documento_google_drive) {
                $google = new GoogleController();
                $id_google = $google->crearDocumentoGoogleDrive($input["titulo"], storage_path("app/public/" . $documento_plantilla), "Documentos de Calidad");
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

        return $input;
    }

    /**
     * Obtiene los usuarios del sistema
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 19. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function getUsuarios(Request $request) {
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
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 19. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerDocumentosPublicos(Request $request) {
        // Criterio de búsqueda para documentos
        $query = $request->input('query');
        $users = Documento::where('titulo', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
            ->where('estado', 'Público') // Filtra los registros donde 'estado' = 'Público'
            ->get(); // Realiza la consulta y obtiene una colección de documentos
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Asigna el leído del documento electrónico según el usuario
     *
     * @author Seven Soluciones Informáticas S.A.S. - Agos. 23 - 2024
     * @version 1.0.0
     *
     * @param int $id del documento
     */
    public function leido($documentoId)
    {
        // Información del usuario logueado
        $userLogin = Auth::user();

        $leidoDocumento = DocumentoLeido::select("id", "accesos", 'nombre_usuario')->where("calidad_documento_id", $documentoId)->where("users_id", $userLogin->id)->first();
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
                'calidad_documento_id' => $documentoId,
                'users_id' => $userLogin->id
            ]);
        }

        // Buscar y obtener la instancia de Documento correspondiente
        $documento = $this->documentoRepository->find($documentoId);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Documento
        $documento->documentoHistorials;
        $documento->documentoTipoDocumento;
        $documento->serieClasificacionDocumental;
        $documento->subserieClasificacionDocumental;
        $documento->oficinaProductoraClasificacionDocumental;
        $documento->documentoLeido;

        // Devolver una respuesta con los datos de la instancia del documento actualizado
        return $this->sendResponse($documento->toArray(), trans('msg_success_update'));
    }

    /**
     * Muestra la vista para el CRUD de Documento.
     *
     * @author Desarrollador Seven - Jul. 24. 2024
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function arbolDocumentos(Request $request) {
        $tiposSistemas = TipoSistema::with("procesos")->get()->toArray();
        return view('calidad::documentos.index_arbol_documentos');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerArbolDocumentos(Request $request) {
        try {
            // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
            // $documentos = Documento::with(['proceso', 'tipoSistema'])->latest()->get()->toArray();
            $documentos = TipoSistema::with("procesosArbol")->get()->toArray();
            return $this->sendResponse($documentos, trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
        }
    }

    function descomprimirYGuardarModeloBizagi($zipPath, $destinationPath) {
        try {
            $zip = new ZipArchive;

            // Crea un subdirectorio temporal único
            $tempExtractPath = storage_path('app/temp/' . uniqid('bizagi_', true));
            File::makeDirectory($tempExtractPath, 0755, true);

            // Abre el archivo .zip
            if ($zip->open($zipPath) === TRUE) {
                // Descomprime el contenido en el subdirectorio temporal
                $zip->extractTo($tempExtractPath);
                $zip->close();

                // Busca el archivo index.html dentro del subdirectorio temporal
                $indexFilePath = $this->findFile($tempExtractPath, 'index.html');
                if ($indexFilePath) {
                    // Obtiene el directorio que contiene el index.html
                    $indexFolder = dirname($indexFilePath);
                    // Copia todo el contenido del directorio al destino deseado
                    File::copyDirectory($indexFolder, $destinationPath);

                    // Elimina el subdirectorio temporal
                    File::deleteDirectory($tempExtractPath);

                    return ["type_message" => "ok", "message" => "Modelo descomprimido y guardado en: " . $destinationPath];
                } else {
                    // Elimina el subdirectorio temporal en caso de error
                    File::deleteDirectory($tempExtractPath);
                    return ["type_message" => "info", "message" => "No se encontró un archivo index.html en la carpeta descomprimida."];
                }
            } else {
                return ["type_message" => "error", "message" => "No se pudo abrir el archivo .zip"];
            }
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', $e->getFile() . ' - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'info');
        }
    }

    function findFile($dir, $filename) {
        $files = File::allFiles($dir);  // Busca todos los archivos en el directorio y subdirectorios
        foreach ($files as $file) {
            if ($file->getFilename() === $filename) {  // Compara el nombre del archivo
                return $file->getPathname();  // Devuelve la ruta completa del archivo encontrado
            }
        }
        return null;  // Retorna null si no encuentra el archivo
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocumentoRequest $request
     *
     * @return Response
     */
    public function generarNuevaVersion(Request $request) {
        $request["codigo_formato"] = explode(", ", $request["codigo_formato"]);
        $request["version"] = (int) $request["version"] + 1;
        $request["documento_id_procedente"] = $request["id"];
        $request["consecutivo_prefijo"] = null;
        $request["consecutivo"] = "DC" . date("YmdHis");
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Ruta del archivo en el sistema
            $path = storage_path("app/public/" . $request["documento_adjunto"]);

            unset($request["documento_adjunto"]);

            // Crea un objeto UploadedFile
            $file = new UploadedFile(
                $path,
                basename($path),
                mime_content_type($path)
            );

            // Crea una instancia de tipo Request para enviar a la función 'crearDocumentoInicial'
            $request2 = new Request();
            // Agrega el archivo como tipo file
            $request2->files->set('documento_adjunto', $file);
            // Asigna las propiedades y valores request a la nueva variable request
            $request2->merge($request->all());

            $input = $this->crearDocumentoInicial($request2);
            // Si la respuesta de la creación del documento no es un array, signica que probablemente ocurrió un error
            if (!is_array($input))
                // Se retorna el mensaje de "error"
                return $input;
            Documento::where("id", $request["id"])->update(["documento_id_procedente" => -1, "updated_at" => $request["updated_at"]]);

            // Inserta el registro en la base de datos
            $documento = $this->documentoRepository->create($input);

            $input['calidad_documento_id'] = $documento->id;
            $input['observacion_historial'] = "Actualización de documento a partir de una nueva versión";

            // Crea un nuevo registro de historial
            DocumentoHistorial::create($input);

            // Obtiene las relaciones del documento
            $documento->documentoHistorials;
            $documento->documentoTipoDocumento;
            $documento->serieClasificacionDocumental;
            $documento->subserieClasificacionDocumental;
            $documento->oficinaProductoraClasificacionDocumental;
            $documento->documentoLeido;
            $documento->documentoSolicitudDocumental;
            $documento->documentoProceso;
            $documento->users;
            $documento->tipoSistema;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($documento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosCalidad', 'Modules\Calidad\Http\Controllers\DocumentoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

}
