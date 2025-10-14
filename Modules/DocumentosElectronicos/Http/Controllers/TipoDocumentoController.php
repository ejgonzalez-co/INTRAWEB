<?php

namespace Modules\DocumentosElectronicos\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentosElectronicos\Http\Requests\CreateTipoDocumentoRequest;
use Modules\DocumentosElectronicos\Http\Requests\UpdateTipoDocumentoRequest;
use Modules\DocumentosElectronicos\Repositories\TipoDocumentoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use App\User;
use Barryvdh\Snappy\Facades\SnappyImage;
use DB;
use Modules\DocumentosElectronicos\Models\Metadato;
use Modules\DocumentosElectronicos\Models\PermisoCrearDocumento;
use Modules\DocumentosElectronicos\Models\PermisoConsultarDocumento;
use Modules\DocumentosElectronicos\Models\TipoDocumento;
use Modules\Intranet\Models\Dependency;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

// use PHPWord;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class TipoDocumentoController extends AppBaseController {

    /** @var  TipoDocumentoRepository */
    private $tipoDocumentoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TipoDocumentoRepository $tipoDocumentoRepo) {
        $this->tipoDocumentoRepository = $tipoDocumentoRepo;
    }

    /**
     * Muestra la vista para el CRUD de TipoDocumento.
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
        if(Auth::user()->hasRole('Admin Documentos Electrónicos')) {
            return view('documentoselectronicos::tipo_documentos.index');
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
    public function all(Request $request) {
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])){
            $tipo_documentos = TipoDocumento::with(["dePermisoCrearDocumentos","dePermisoConsultarDocumentos", "deMetadatos"])->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();

            $cantidad_tipo_documentos = count($tipo_documentos);
        }
        else{
            $tipo_documentos = TipoDocumento::with(["dePermisoCrearDocumentos","dePermisoConsultarDocumentos", "deMetadatos"])
            ->latest()->get()->toArray();

            $cantidad_tipo_documentos = count($tipo_documentos);
        }
        return $this->sendResponseAvanzado($tipo_documentos, trans('data_obtained_successfully'),null, ["total_registros" => $cantidad_tipo_documentos]);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDocumentTypes(Request $request) {
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])){
            $tipo_documentos = TipoDocumento::with(["dePermisoCrearDocumentos", "dePermisoConsultarDocumentos","deMetadatos"])->where(function($e) {
                $e->whereHas("dePermisoConsultarDocumentos", function ($q) {
                    $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                    $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                    $q->where("dependencia_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                    $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                    $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                    $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                });
                $e->orWhereHas("dePermisoCrearDocumentos", function ($q) {
                    $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                    $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                    $q->where("dependencias_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                    $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                    $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                    $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                });
                $e->orWhere("permiso_crear_documentos_todas", 1);
                $e->orWhere("permiso_consultar_documentos_todas", 1);
            })->whereRaw(base64_decode($request["f"]))->orderBy("nombre")->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();

            $cantidad_tipo_documentos = count($tipo_documentos);
        }
        else{
            $tipo_documentos = TipoDocumento::with(["dePermisoCrearDocumentos","dePermisoConsultarDocumentos", "deMetadatos"])                ->where(function($e) {
                $e->whereHas("dePermisoConsultarDocumentos", function ($q) {
                    $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                    $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                    $q->where("dependencia_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                    $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                    $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                    $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                });
                $e->orWhereHas("dePermisoCrearDocumentos", function ($q) {
                    $user = User::where("id",Auth::user()->id)->with("workGroups")->first()->toArray();
                    $work_groups = !empty($user["work_groups"]) ? array_column($user["work_groups"],"id") : null;
                    $q->where("dependencias_id", Auth::user()->id_dependencia); // Access dependencias_id from related model
                    $q->orWhere("cargo_id", Auth::user()->id_cargo); // Access dependencias_id from related model
                    $work_groups ? $q->orWhereIn("grupo_id", $work_groups) : null; // Access dependencias_id from related model
                    $q->orWhere("usuario_id", Auth::user()->id); // Access dependencias_id from related model
                });
                $e->orWhere("permiso_crear_documentos_todas", 1);
                $e->orWhere("permiso_consultar_documentos_todas", 1);
            })
            ->orderBy("nombre")->get()->toArray();

            $cantidad_tipo_documentos = count($tipo_documentos);
        }
        return $this->sendResponseAvanzado($tipo_documentos, trans('data_obtained_successfully'),null, ["total_registros" => $cantidad_tipo_documentos]);
    }

        /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getRecipients(Request $request)
    {
        // Usuarios
        $query =$request->input('query');

        $users = User::where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->get() // Realiza la consulta y obtiene una colección de usuarios
        ->map(function ($user) {
            $user->users_id = $user->id;
            $user->type = 'Usuario';
            $user->users_email = $user->email;
            // Agrega el atributo 'fullname' a cada instancia de usuario
            $user->nombre = $user->fullname; // Accede al mutador para calcular el fullname
            return $user; // Retorna el usuario con el nuevo atributo 'fullname'
        });

        // Grupos
        $query =$request->input('query');
        $grupos = DB::table('work_groups')
        ->select(DB::raw('CONCAT("Grupo ", work_groups.name) AS nombre, work_groups.id as work_groups_id, concat("grupo",work_groups.id) as id, "Grupo" AS type, "" AS email'))
        ->where('work_groups.name', 'like', '%'.$query.'%')
        ->get();

        // Dependencias
        $query =$request->input('query');
        $dependencias = DB::table('dependencias')
        ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS nombre, dependencias.id as dependencias_id, concat("dependencia",dependencias.id) as id, "Dependencia" AS type, "" AS email'))
        ->where('dependencias.nombre', 'like', '%'.$query.'%')
        ->get();

        // Cargos
        $query =$request->input('query');
        $position = DB::table('cargos')
        ->select(DB::raw('CONCAT("Cargo ", cargos.nombre) AS nombre, cargos.id as cargos_id, concat("cargo",cargos.id) as id, "Cargo" AS type,"" AS email'))
        ->where('cargos.nombre', 'like', '%'.$query.'%')
        ->get();

        $recipients = array_merge($users->toArray(), $grupos->toArray(), $dependencias->toArray(), $position->toArray());

        return $this->sendResponse($recipients, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateTipoDocumentoRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoDocumentoRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Vigencia del registro = Año actual
            $input["vigencia"] = date("Y");
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;

            // Castea las variables de requeridos de boolean a valores numéricos (true=1, false/null=0)
            $input['variables_plantilla_requeridas'] = isset($input['variables_plantilla_requeridas']) && ($this->toBoolean($input['variables_plantilla_requeridas']) || $input['variables_plantilla_requeridas'] == 1) ? 1 : 0;
            $input['es_borrable'] = isset($input['es_borrable']) && ($this->toBoolean($input['es_borrable']) || $input['es_borrable'] == 1) ? 1 : 0;
            $input['es_editable'] = isset($input['es_editable']) && ($this->toBoolean($input['es_editable']) || $input['es_editable'] == 1) ? 1 : 0;
            $input['sub_estados_requerido'] = isset($input['sub_estados_requerido']) && ($this->toBoolean($input['sub_estados_requerido']) || $input['sub_estados_requerido'] == 1) ? 1 : 0;

            // Valida la existencia del valor de la variable consecutivo_documento en el formato del consecutivo
            if(!in_array("consecutivo_documento", $input["formato_consecutivo_value"])) {
                // Si el usuario no seleccionó el número de orden del documento en el formato del consecutivo, el sistema le arroja un mensaje de advertencia y no puede continuar
                return $this->sendSuccess('<strong>¡Atención!</strong><br /><br />Es necesario agregar el número de orden del documento en el formato del consecutivo, ya que esta opción es esencial para calcular el consecutivo del documento.', 'info');
            }

            // Condición para validar si existe el formato del consecutivo y separarlo por coma
            if (!empty($input["formato_consecutivo_value"])) {
                $input["formato_consecutivo"] = implode(", ", $input["formato_consecutivo_value"]);
            }

            // Condición para validar si existe el prefijo que incrementa el consecutivo y separarlo por coma
            if (!empty($input["prefijo_incrementan_consecutivo_value"])) {
                $input["prefijo_incrementan_consecutivo"] = implode(", ", $input["prefijo_incrementan_consecutivo_value"]);
            }

            // Condición para validar si existe algún registro de permiso para las dependencias
            if (!empty($input["variables_plantilla_value"])) {
                $variables_plantilla = [];

                foreach($input["variables_plantilla_value"] as $variable) {
                    // Decodificar el JSON en un array asociativo
                    $variable = json_decode($variable, true);
                    $variables_plantilla[] = $variable["variable"];
                }
                $input["variables_plantilla"] = implode(", ", $variables_plantilla);
            } else {
                // Se asigna el valor de 0 = No requerido, para cuando no tiene variables seleccionadas al tipo de documento
                $input['variables_plantilla_requeridas'] = 0;
            }

            // Condición para validar si existe algún registro de subestado
            if (!empty($input["sub_estados_value"])) {
                $sub_estados = [];

                foreach($input["sub_estados_value"] as $subestado) {
                    // Decodificar el JSON en un array asociativo
                    $subestado = json_decode($subestado, true);
                    $sub_estados[] = $subestado["subestado"];
                }
                $input["sub_estados"] = implode(", ", $sub_estados);
            } else {
                // Se asigna el valor de 0 = No requerido, para cuando no tiene subestados (actividades) habilitados
                $input['sub_estados_requerido'] = 0;
            }

            // Valida si se adjunto una plantilla para el tipo de documento
            if ($request->hasFile('plantilla')) {
                // Obtiene la extensión de la plantilla para validar el tipo posteriormente
                $extension_plantilla = $input['plantilla']->extension();
                if($extension_plantilla == "docx" || $extension_plantilla == "doc" || $extension_plantilla == "xlsx" || $extension_plantilla == "xls" || $extension_plantilla == "pptx") {
                    $input['plantilla'] = substr($input['plantilla']->store('public/container/de_tipos_documentos_'.date("Y")), 7);
                } else {
                    return $this->sendResponse($extension_plantilla, "La plantilla que ha seleccionado no es válida. Por favor, seleccione una plantilla con un formato compatible (Word, Excel, PowerPoint).", "info");
                }
            }

            if($request->hasFile('preview_document')) {
                $input['preview_document'] = substr($input['preview_document']->store('public/container/de_tipos_documentos_'.date("Y")), 7);
            }

            $de_permiso_crear_documentos = $input['de_permiso_crear_documentos'] ?? null;
            $de_permiso_consultar_documentos = $input['de_permiso_consultar_documentos'] ?? null;
            $metadatos = $input['de_metadatos'] ?? null;
            // Si el separador del consecutivo es null, se le asigna el valor de vacio
            $input["separador_consecutivo"] ?? $input["separador_consecutivo"] = "";
            // Inserta el registro en la base de datos
            $tipoDocumento = $this->tipoDocumentoRepository->create($input);
            // Si el usuario no adjunto imagen previa del documento, se genera una a partir del documento
            if(empty($input['preview_document']) && !empty($input['plantilla'])) {
                // Ruta del archivo de Word
                $preview_document = $this->wordToImage2('storage/'.$input['plantilla'], $tipoDocumento->id);
                // Actualiza la imagen previa tomada de la plantilla
                $tipoDocumento->update(["preview_document" => $preview_document]);
            }
            // Condición para validar si existe algún registro de permiso para las dependencias
            if (!empty($de_permiso_crear_documentos)) {
                // Ciclo para recorrer todos los registros de dependencias
                foreach($de_permiso_crear_documentos as $option){
                    // Decodifica la cadena JSON de dependencias
                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PermisoCrearDocumento::create([
                        'nombre' => $dependencia->recipient_datos->nombre ?? null,
                        'type_user' => $dependencia->type_user ?? null,
                        'cargo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Cargo" ? $dependencia->recipient_datos->id_cargo : null,
                        'dependencias_id' => isset($dependencia->type_user) && $dependencia->type_user === "Dependencia" ? $dependencia->recipient_datos->dependencias_id : null,
                        'grupo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Grupo" ? $dependencia->recipient_datos->work_groups_id : null,
                        'usuario_id' => isset($dependencia->type_user) && $dependencia->type_user === "Usuario" ? $dependencia->recipient_datos->id : null,
                        'de_tipos_documentos_id' => $tipoDocumento->id
                    ]);
                }
            }

            // Condición para validar si existe algún registro de permiso para consultar
            if (!empty($de_permiso_consultar_documentos)) {
                // Elimina los registros previos de permisos de dependencias
                PermisoConsultarDocumento::where("de_tipo_documento_id", $tipoDocumento->id)->delete();
                // Ciclo para recorrer todos los registros de dependencias
                foreach($de_permiso_consultar_documentos as $option){
                    // Decodifica la cadena JSON de dependencias
                    $dependencia = json_decode($option);

                    // Se crean la cantidad de registros ingresados por el usuario
                    PermisoConsultarDocumento::create([
                        'nombre' => $dependencia->recipient_datos->nombre ?? null,
                        'type_user' => $dependencia->type_user ?? null,
                        'cargo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Cargo" ? $dependencia->recipient_datos->id_cargo : null,
                        'dependencia_id' => isset($dependencia->type_user) && $dependencia->type_user === "Dependencia" ? $dependencia->recipient_datos->dependencias_id : null,
                        'grupo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Grupo" ? $dependencia->recipient_datos->work_groups_id : null,
                        'usuario_id' => isset($dependencia->type_user) && $dependencia->type_user === "Usuario" ? $dependencia->recipient_datos->id : null,
                        'de_tipo_documento_id' => $tipoDocumento->id
                    ]);
                }
            }

            // Condición para validar si existe algún registro de metadato
            if (!empty($metadatos)) {
                // Ciclo para recorrer todos los registros de metadato
                foreach($metadatos as $index => $option){
                    // Decodifica la cadena JSON de dependencias
                    $metadato = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    Metadato::create([
                        'nombre_metadato' => $metadato->nombre_metadato,
                        'variable_documento' => $metadato->variable_documento,
                        'tipo' => $metadato->tipo,
                        'texto_ayuda' => $metadato->texto_ayuda ?? '',
                        'opciones_listado' => isset($metadato->opciones_listado) ? implode(", ", $metadato->opciones_listado) : null,
                        'requerido' => $metadato->requerido,
                        'de_tipos_documentos_id' => $tipoDocumento->id,
                        'users_id' => Auth::user()->id,
                        'orden' => $index
                    ]);
                }
            }

            $tipoDocumento->dePermisoCrearDocumentos;
            $tipoDocumento->deMetadatos;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tipoDocumento->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\TipoDocumentoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine());

            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function wordToImage($plantilla, $id)
    {
        // $phpWord = new PhpWord();
        $adapter = IOFactory::createReader('Word2007');

        $phpWord = $adapter->load($plantilla);

        // Convertir la primera página a imagen
        $image = $phpWord->getSection(0);

        // Guardar la imagen
        $image->save('ruta/a/la/imagen.png');

    }

    public function wordToImage2($plantilla, $id) {
        // Ruta al archivo DOCX
        $docxFilePath = $plantilla;

        // Convertir el archivo DOCX a HTML
        $phpWord = IOFactory::load($docxFilePath);
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

        // Generar la salida HTML en un buffer
        ob_start();
        $htmlWriter->save("php://output");
        $htmlContent = ob_get_clean();

        $image = SnappyImage::loadHTML($htmlContent)->output();

        // Guardar la imagen o hacer algo con ella
        // Por ejemplo, puedes guardarla en el sistema de archivos
        if(!file_exists(storage_path('app/public/container/de_tipos_documentos_'.date('Y')))) {
            mkdir(storage_path('app/public/container/de_tipos_documentos_'.date('Y')), 755, true);
        }

        $ruta_documento = 'container/de_tipos_documentos_'.date('Y').'/preview_document_'.$id.'.jpg';
        file_put_contents(storage_path('app/public/'.$ruta_documento), $image);

        return $ruta_documento;
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTipoDocumentoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoDocumentoRequest $request) {

        $input = $request->all();

        /** @var TipoDocumento $tipoDocumento */
        $tipoDocumento = $this->tipoDocumentoRepository->find($id);

        if (empty($tipoDocumento)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Castea las variables de requeridos de boolean a valores numéricos (true=1, false/null=0)
            $input['variables_plantilla_requeridas'] = isset($input['variables_plantilla_requeridas']) && ($this->toBoolean($input['variables_plantilla_requeridas']) || $input['variables_plantilla_requeridas'] == 1) ? 1 : 0;
            $input['es_borrable'] = isset($input['es_borrable']) && ($this->toBoolean($input['es_borrable']) || $input['es_borrable'] == 1) ? 1 : 0;
            $input['es_editable'] = isset($input['es_editable']) && ($this->toBoolean($input['es_editable']) || $input['es_editable'] == 1) ? 1 : 0;
            $input['sub_estados_requerido'] = isset($input['sub_estados_requerido']) && ($this->toBoolean($input['sub_estados_requerido']) || $input['sub_estados_requerido'] == 1) ? 1 : 0;

            // Valida la existencia del valor de la variable consecutivo_documento en el formato del consecutivo
            if(!in_array("consecutivo_documento", $input["formato_consecutivo_value"])) {
                // Si el usuario no seleccionó el número de orden del documento en el formato del consecutivo, el sistema le arroja un mensaje de advertencia y no puede continuar
                return $this->sendSuccess('<strong>¡Atención!</strong><br /><br />Es necesario agregar el número de orden del documento en el formato del consecutivo, ya que esta opción es esencial para calcular el consecutivo del documento.', 'info');
            }

            // Condición para validar si existe el formato del consecutivo y separarlo por coma
            if (!empty($input["formato_consecutivo_value"])) {
                $input["formato_consecutivo"] = implode(", ", $input["formato_consecutivo_value"]);
            }

            // Condición para validar si existe el prefijo que incrementa el consecutivo y separarlo por coma
            if (!empty($input["prefijo_incrementan_consecutivo_value"])) {
                $input["prefijo_incrementan_consecutivo"] = implode(", ", $input["prefijo_incrementan_consecutivo_value"]);
            }

            // Condición para validar si existe algún registro de permiso para las dependencias
            if (!empty($input["variables_plantilla_value"])) {
                $variables_plantilla = [];

                foreach($input["variables_plantilla_value"] as $variable) {
                    // Decodificar el JSON en un array asociativo
                    $variable = json_decode($variable, true);
                    $variables_plantilla[] = $variable["variable"];
                }
                $input["variables_plantilla"] = implode(", ", $variables_plantilla);
            } else {
                // Se asigna el valor de 0 = No requerido, para cuando no tiene variables seleccionadas al tipo de documento
                $input['variables_plantilla_requeridas'] = 0;
            }

            // Condición para validar si existe algún registro de subestado
            if (!empty($input["sub_estados_value"])) {
                $sub_estados = [];

                foreach($input["sub_estados_value"] as $subestado) {
                    // Decodificar el JSON en un array asociativo
                    $subestado = json_decode($subestado, true);
                    $sub_estados[] = $subestado["subestado"];
                }
                $input["sub_estados"] = implode(", ", $sub_estados);
            } else {
                // Se asigna el valor de 0 = No requerido, para cuando no tiene subestados (actividades) habilitados
                $input['sub_estados_requerido'] = 0;
            }

            // Valida si se adjunto una plantilla para el tipo de documento
            if ($request->hasFile('plantilla')) {
                // Obtiene la extensión de la plantilla para validar el tipo posteriormente
                $extension_plantilla = $input['plantilla']->extension();
                if($extension_plantilla == "docx" || $extension_plantilla == "doc" || $extension_plantilla == "xlsx" || $extension_plantilla == "xls" || $extension_plantilla == "pptx") {
                    $input['plantilla'] = substr($input['plantilla']->store('public/container/de_tipos_documentos_'.date("Y")), 7);
                } else {
                    return $this->sendResponse($extension_plantilla, "La plantilla que ha seleccionado no es válida. Por favor, seleccione una plantilla con un formato compatible (Word, Excel, PowerPoint).", "info");
                }
            }
            // Valida si adjunto un imagen previa del documento para actualizarla
            if($request->hasFile('preview_document')) {
                $input['preview_document'] = substr($input['preview_document']->store('public/container/de_tipos_documentos_'.date("Y")), 7);
            }

            // Si el usuario no adjunto imagen previa del documento, se genera una a partir del documento
            if(empty($input['preview_document']) && !empty($input['plantilla'])) {
                // Ruta del archivo de Word
                $input['preview_document'] = $this->wordToImage2('storage/'.$input['plantilla'], $id);
            }
            $de_permiso_crear_documentos = $input['de_permiso_crear_documentos'] ?? null;
            $de_permiso_consultar_documentos = $input['de_permiso_consultar_documentos'] ?? null;
            $metadatos = $input['de_metadatos'] ?? null;
            // Si el separador del consecutivo es null, se le asigna el valor de vacio
            $input["separador_consecutivo"] ?? $input["separador_consecutivo"] = "";
            // Actualiza el registro
            $tipoDocumento = $this->tipoDocumentoRepository->update($input, $id);

               // Condición para validar si existe algún registro de permiso para las dependencias
               if (!empty($de_permiso_crear_documentos)) {
                // Elimina los registros previos de permisos de dependencias
                PermisoCrearDocumento::where("de_tipos_documentos_id", $tipoDocumento->id)->delete();
                // Ciclo para recorrer todos los registros de dependencias
                foreach($de_permiso_crear_documentos as $option){
                    // Decodifica la cadena JSON de dependencias
                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PermisoCrearDocumento::create([
                        'nombre' => $dependencia->recipient_datos->nombre ?? null,
                        'type_user' => $dependencia->type_user ?? null,
                        'cargo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Cargo" ? $dependencia->recipient_datos->id_cargo : null,
                        'dependencias_id' => isset($dependencia->type_user) && $dependencia->type_user === "Dependencia" ? $dependencia->recipient_datos->dependencias_id : null,
                        'grupo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Grupo" ? $dependencia->recipient_datos->work_groups_id : null,
                        'usuario_id' => isset($dependencia->type_user) && $dependencia->type_user === "Usuario" ? $dependencia->recipient_datos->id : null,
                        'de_tipos_documentos_id' => $tipoDocumento->id
                    ]);
                }
            } else {
                // Elimina los registros de permisos de dependencias
                PermisoCrearDocumento::where("de_tipos_documentos_id", $tipoDocumento->id)->delete();
            }

                // Condición para validar si existe algún registro de permiso para consultar
                if (!empty($de_permiso_consultar_documentos)) {
                    // Elimina los registros previos de permisos de dependencias
                    PermisoConsultarDocumento::where("de_tipo_documento_id", $tipoDocumento->id)->delete();
                    // Ciclo para recorrer todos los registros de dependencias
                    foreach($de_permiso_consultar_documentos as $option){
                        // Decodifica la cadena JSON de dependencias
                        $dependencia = json_decode($option);

                    // Se crean la cantidad de registros ingresados por el usuario
                        PermisoConsultarDocumento::create([
                            'nombre' => $dependencia->recipient_datos->nombre ?? null,
                            'type_user' => $dependencia->type_user ?? null,
                            'cargo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Cargo" ? $dependencia->recipient_datos->id_cargo : null,
                            'dependencia_id' => isset($dependencia->type_user) && $dependencia->type_user === "Dependencia" ? $dependencia->recipient_datos->dependencias_id : null,
                            'grupo_id' => isset($dependencia->type_user) && $dependencia->type_user === "Grupo" ? $dependencia->recipient_datos->work_groups_id : null,
                            'usuario_id' => isset($dependencia->type_user) && $dependencia->type_user === "Usuario" ? $dependencia->recipient_datos->id : null,
                            'de_tipo_documento_id' => $tipoDocumento->id
                        ]);
                    }
                } else {
                // Elimina los registros de permisos de dependencias
                PermisoConsultarDocumento::where("de_tipo_documento_id", $tipoDocumento->id)->delete();
            }

            // Obtiene los ids de los metadatos a elimminar
            $metadatos_eliminados = $input['de_metadatos_eliminados']  ?? null;
            // Condición para validar si existe algún registro de metadato a eliminar
            if (!empty($metadatos_eliminados)) {
                // Elimina los registros de metadatos
                Metadato::whereIn("id", $metadatos_eliminados)->delete();
            }

            // Condición para validar si existe algún registro nuevo de metadato para registrar
            if (!empty($metadatos)) {
                // Ciclo para recorrer todos los registros de metadatos
                foreach($metadatos as $index => $option){
                    // Decodifica la cadena JSON de metadatos
                    $metadato = json_decode($option);
                    // Valida si el registro tiene la propiedad id, si la tiene no es un registro nuevo y por lo tanto no se debe guardar
                    if(!empty($metadato->id)) {
                        Metadato::where("id" , $metadato->id)->update(["orden" => $index]);
                        continue;
                    }

                    // Se crea el nuevo registro de metadato
                    Metadato::create([
                        'nombre_metadato' => $metadato->nombre_metadato,
                        'variable_documento' => $metadato->variable_documento,
                        'tipo' => $metadato->tipo,
                        'texto_ayuda' => $metadato->texto_ayuda ?? '',
                        'opciones_listado' => isset($metadato->opciones_listado) ? (is_array($metadato->opciones_listado) ? implode(", ", $metadato->opciones_listado) : $metadato->opciones_listado) : null,
                        'requerido' => $metadato->requerido,
                        'de_tipos_documentos_id' => $tipoDocumento->id,
                        'users_id' => Auth::user()->id,
                        'orden' => $index
                    ]);
                }
            }

            $tipoDocumento->dePermisoCrearDocumentos;
            $tipoDocumento->dePermisoConsultarDocumentos;
            $tipoDocumento->deMetadatos;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tipoDocumento->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\TipoDocumentoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine(). ' Id: '.($tipoDocumento['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un TipoDocumento del almacenamiento
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

        /** @var TipoDocumento $tipoDocumento */
        $tipoDocumento = $this->tipoDocumentoRepository->find($id);

        if (empty($tipoDocumento)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $tipoDocumento->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', 'Modules\DocumentosElectronicos\Http\Controllers\TipoDocumentoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('DocumentosElectronicos', $e->getFile().' - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: ' . $e->getMessage(). '. Linea: ' . $e->getLine().' Id: '.($tipoDocumento['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('tipo_documentos').'.'.$fileType;

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
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtener_dependencias() {
        // Obtiene las dependencias con sus sedes
        $dependencies = Dependency::with(['headquarters'])->latest()->get();
        return $this->sendResponse($dependencies->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Valida si el metadato obtenido por parámetro se puede eliminar o no, consultando si ya se encuentra en uso por medio de la relación deDocumentoHasDeMetadatos
     *
     * @author Seven Soluciones Informáticas S.A.S - Abr. 24 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function validarEliminarMetadato(Request $request) {
        // Obtiene el metadato a eliminar
        $data = $request->toArray();
        // Consulta si el metadato ya esta en uso por medio de la relación deDocumentoHasDeMetadatos
        $metadato_en_uso = Metadato::where("id", $data["id"])->whereHas("deDocumentoHasDeMetadatos")->first();
        // Si el metadato ya esta en uso, se retorna un mensaje indicando que no es posible eliminarlo
        if($metadato_en_uso) {
            $metadato = $metadato_en_uso->toArray();
            $mensaje = "No se puede eliminar el metadato <strong>".$metadato["nombre_metadato"]."</strong> ya que actualmente esta en uso";
        } else {
            // No retorna ningún mensaje
            $mensaje = null;
        }
        return $this->sendResponse($mensaje, "Consulta del uso del metadato");
    }
}
