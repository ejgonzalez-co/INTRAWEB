<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateInternalTypeRequest;
use Modules\Correspondence\Http\Requests\UpdateInternalTypeRequest;
use Modules\Correspondence\Repositories\InternalTypesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\InternalTypes;
use Modules\Correspondence\Models\Internal;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class InternalTypeController extends AppBaseController {

    /** @var  InternalTypesRepository */
    private $internalTypesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(InternalTypesRepository $internalTypeRepo) {
        $this->internalTypesRepository = $internalTypeRepo;
    }

    /**
     * Muestra la vista para el CRUD de InternalType.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador Correspondencia","Correspondencia Interna Admin"])){
            return view('correspondence::internal_types.index');
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
    public function all() {
        $internal_types = $this->internalTypesRepository->all();
        // $internal_types = InternalTypes::select(DB:raw("id, name, template, template_web, prefix, CONCAT('[[\"template => \"')"))->get();
        return $this->sendResponse($internal_types->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateInternalTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateInternalTypeRequest $request) {

        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona la plantilla
            if ($request->hasFile('template')) {
                $input['template'] = substr($input['template']->store('public/container/internal_type_'.date("Y")), 7);
            }
            // Valida si se selecciona la plantilla
            if ($request->hasFile('template_web')) {
                $input['template_web'] = substr($input['template_web']->store('public/container/internal_type_'.date("Y")), 7);
            }
            // Valida si seleccionó variables de la plantilla
            if(isset($input['variables_documento'])) {
                $input['variables'] = str_replace(['{"variable":"', '"}'], '', implode(",", array_unique($input['variables_documento'])));
            }
            // Inserta el registro en la base de datos
            $internalType = $this->internalTypesRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internalType->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdateInternalTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInternalTypeRequest $request) {

        $input = $request->all();

        /** @var InternalType $internalType */
        $internalType = $this->internalTypesRepository->find($id);

        if (empty($internalType)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se selecciona la plantilla
            if ($request->hasFile('template')) {
                $input['template'] = substr($input['template']->store('public/container/internal_type_'.date("Y")), 7);
            }
            // Valida si se selecciona la plantilla web
            if ($request->hasFile('template_web')) {
                $input['template_web'] = substr($input['template_web']->store('public/container/internal_type_'.date("Y")), 7);
            }
            // Valida si seleccionó variables de la plantilla
            if(isset($input['variables_documento'])) {
                $input['variables'] = str_replace(['{"variable":"', '"}'], '', implode(",", array_unique($input['variables_documento'])));
            } else {
                // Si no seleccion variables de la plantilla, le asigna NULL al campo variables
                $input['variables'] = NULL;
            }
            // Actualiza el registro
            $internalType = $this->internalTypesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internalType->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($internalType['id'] ?? 'Desconocido' ));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un InternalType del almacenamiento
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

        /** @var InternalType $internalType */
        $internalType = $this->internalTypesRepository->find($id);

        $exists = Internal::where('type', $id)->exists();
        if ($exists ) {
             return $this->sendSuccess("La eliminación no está permitida porque existen documentos asociados a este tipo de documento." , 'info');
        }
        if (empty($internalType)) {
            return $this->sendSuccess(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            
            // Elimina el registro
            $internalType->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalTypeController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($internalType['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('internal_types').'.'.$fileType;

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
}
