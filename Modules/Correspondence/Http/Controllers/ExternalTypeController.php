<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateExternalTypeRequest;
use Modules\Correspondence\Http\Requests\UpdateExternalTypeRequest;
use Modules\Correspondence\Repositories\ExternalTypesRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Correspondence\Models\ExternalTypes;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\External;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ExternalTypeController extends AppBaseController
{

    /** @var  ExternalTypesRepository */
    private $externalTypesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ExternalTypesRepository $externalTypeRepo)
    {
        $this->externalTypesRepository = $externalTypeRepo;
    }

    /**
     * Muestra la vista para el CRUD de ExternalTypes.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->hasRole(["Administrador Correspondencia", "Correspondencia Enviada Admin"])) {
            return view('correspondence::external_types.index');
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
    public function all(Request $request)
    {
        
        $external_types = $this->externalTypesRepository->all();
        $responseData = DB::table('rotule_props')->where('modulo','Enviada')->get();
        $count_external = ExternalTypes::all()->count();
        return $this->sendResponseAvanzado($external_types->toArray(), trans('data_obtained_successfully'),null,['rotule_props'=>$responseData,'total_registros'=>$count_external]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateExternalTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateExternalTypeRequest $request)
    {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona la plantilla
            if ($request->hasFile('template')) {
                $input['template'] = substr($input['template']->store('public/container/external_type_' . date("Y")), 7);
            }
            // Valida si se selecciona la plantilla web
            if ($request->hasFile('template_web')) {
                $input['template_web'] = substr($input['template_web']->store('public/container/external_type_' . date("Y")), 7);
            }
            // Valida si seleccionó variables de la plantilla
            if (isset($input['variables_documento'])) {
                $input['variables'] = str_replace(['{"variable":"', '"}'], '', implode(",", array_unique($input['variables_documento'])));
            }
            // Inserta el registro en la base de datos
            $externalType = $this->externalTypesRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($externalType->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . ' Linea: ' . $e->getLine());
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
     * @param UpdateExternalTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExternalTypeRequest $request)
    {

        $input = $request->all();

        /** @var ExternalType $externalType */
        $externalType = $this->externalTypesRepository->find($id);

        if (empty($externalType)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona la plantilla
            if ($request->hasFile('template')) {
                $input['template'] = substr($input['template']->store('public/container/external_type_' . date("Y")), 7);
            }
            // Valida si se selecciona la plantilla web
            if ($request->hasFile('template_web')) {
                $input['template_web'] = substr($input['template_web']->store('public/container/external_type_' . date("Y")), 7);
            }
            // Valida si seleccionó variables de la plantilla
            if (isset($input['variables_documento'])) {
                $input['variables'] = str_replace(['{"variable":"', '"}'], '', implode(",", array_unique($input['variables_documento'])));
            } else {
                // Si no seleccion variables de la plantilla, le asigna NULL al campo variables
                $input['variables'] = NULL;
            }
            // Actualiza el registro
            $externalType = $this->externalTypesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($externalType->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine().' Id: '. ($externalType['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un ExternalTypes del almacenamiento
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
    public function destroy($id)
    {


        /** @var ExternalTypes $externalType */
        $externalType = $this->externalTypesRepository->find($id);
        $exists = External::where('type', $id)->exists();
        if ($exists ) {
             return $this->sendSuccess("La eliminación no está permitida porque existen documentos asociados a este tipo de documento." , 'info');
        }
        if (empty($externalType)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $externalType->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().$e->getMessage().' Linea: '.$e->getLine().' Id: '. ($externalType['id'] ?? 'Desconocido'));
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
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('external_types') . '.' . $fileType;

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

    public function getPropsRotule($module)
    {
        $rotuleProps = DB::table('rotule_props')
        ->where('modulo', $module)
        ->select('variables_rotule', 'nombre_label','rotuleWidth')
        ->get(); // Ordena por la columna de tiempo más reciente
        return $this->sendResponse($rotuleProps->toArray(), trans('data_obtained_successfully'));
    }
    public function createProps(Request $request, $modulo)
    { 
        // Obtener todos los datos de la petición
        $input = $request->all();

        // Inicializar el array que se usará para construir cada registro
        $props = [];

        // Validar que exista información en 'rotule_props'
        if (empty($input['rotule_props'])) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Agregar el nombre del módulo al array base
        $props['modulo'] = $modulo;

        // Definir los campos disponibles según el tipo de módulo
        if ($modulo == 'Enviada') {
            $campos = [
                ['Fecha' => 'created_at'],
                ['Funcionario Radicador' => 'users_name'],
                ['Ciudadano' => 'citizen_name'],
                ['Asunto' => 'title'],
                ['Anexo' => 'annexes_description'],
                ['Destinatario' => 'citizen_name'],
                ['PQRS Asociado' => 'pqr_consecutive'],
                ['Codigo Validacion' => 'validation_code'],
                ['Funcionario remitente' => 'from'],
                ['Canal' => 'channel_name'],
                ['Folios' => 'folios']
            ];
        } else {
            $campos = [
                ['Fecha' => 'created_at'],
                ['Funcionario Radicador' => 'user_name'],
                ['Ciudadano' => 'citizen_name'],
                ['Asunto' => 'issue'],
                ['Anexo' => 'annexed'],
                ['Destinatario' => 'functionary_name'],
                ['PQRS Asociado' => 'pqr'],
                ['Novedad' => 'novelty'],
                ['Codigo Validacion' => 'validation_code'],
                ['Canal' => 'channel_name'],
                ['Folios' => 'folio']
            ];
        }

        // Inicializar array para almacenar los nombres de las etiquetas seleccionadas
        $nombreLabels = [];

        // Recorrer los objetos recibidos en 'rotule_props' desde el frontend
        foreach ($input['rotule_props'] as $objeto) {

            // Si el objeto viene como string (JSON), lo decodificamos
            if (is_string($objeto)) {
                $elemento_limpio = trim($objeto, '"'); // Limpiar comillas adicionales
                $objeto = json_decode($elemento_limpio, true); // Convertir JSON a array asociativo
            }

            // Si el objeto tiene 'nombre_label', lo guardamos en el array
            if (isset($objeto['nombre_label'])) {
                $nombreLabels[] = $objeto['nombre_label'];
            }
        }

        // Eliminar registros existentes del mismo módulo para evitar duplicados
        DB::table('rotule_props')->where('modulo', $modulo)->delete();

        // Recorrer los labels seleccionados para construir e insertar los registros
        foreach ($nombreLabels as $label) {
            foreach ($campos as $campo) {
                $key = key($campo);       // Obtener el nombre del campo
                $value = current($campo); // Obtener el nombre de la variable asociada

                // Si el nombre del campo coincide con el label enviado desde el frontend
                if ($key === $label) {
                    $registro = [
                        'modulo' => $modulo,                // Nombre del módulo (Enviada o Recibida)
                        'variables_rotule' => $value,       // Nombre de la variable asociada
                        'nombre_label' => $label,           // Nombre del campo mostrado
                        'created_at' => now()               // Fecha actual (timestamp)
                    ];

                    // Insertar el registro en la tabla rotule_props
                    DB::table('rotule_props')->insert($registro);

                    // Romper el bucle interno una vez encontrado
                    break;
                }
            }
        }

        // Consultar los registros recién insertados para devolverlos como respuesta
        $responseData = DB::table('rotule_props')->where('modulo', $modulo)->get();

        // Devolver respuesta exitosa al cliente
        return $this->sendResponseAvanzado(
            $responseData,
            trans('data_obtained_successfully'),
            null,
            ["rotule_props" => $responseData]
        );
    }

}

    