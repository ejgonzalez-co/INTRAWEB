<?php

namespace Modules\Intranet\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Intranet\Http\Requests\CreateTypeEventRequest;
use Modules\Intranet\Http\Requests\UpdateTypeEventRequest;
use Modules\Intranet\Repositories\TypeEventRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Modules\Intranet\Models\TypeEvent;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TypeEventController extends AppBaseController {

    /** @var  TypeEventRepository */
    private $typeEventRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TypeEventRepository $typeEventRepo) {
        $this->typeEventRepository = $typeEventRepo;
    }

    /**
     * Muestra la vista para el CRUD de TypeEvent.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador intranet","Administrador intranet de descargas","Administrador intranet de eventos","Administrador intranet de encuestas"])){
            return view('intranet::type_events.index');
        }
        return view("auth.forbidden");
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $type_events = $this->typeEventRepository->all();
        return $this->sendResponse($type_events->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes según el estado activo
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 31 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function allCondition() {
        $type_events = TypeEvent::where("state", 1)->get();
        return $this->sendResponse($type_events->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTypeEventRequest $request
     *
     * @return Response
     */
    public function store(CreateTypeEventRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $typeEvent = $this->typeEventRepository->create($input);

            return $this->sendResponse($typeEvent->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTypeEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTypeEventRequest $request) {

        $input = $request->all();

        /** @var TypeEvent $typeEvent */
        $typeEvent = $this->typeEventRepository->find($id);

        if (empty($typeEvent)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $typeEvent = $this->typeEventRepository->update($input, $id);
        
            return $this->sendResponse($typeEvent->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($typeEvent['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un TypeEvent del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var TypeEvent $typeEvent */
        $typeEvent = $this->typeEventRepository->find($id);

        if (empty($typeEvent)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        try {
            // Elimina el registro
            $typeEvent->delete();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('intranet', 'Modules\Intranet\Http\Controllers\TypeEventController - '. Auth::user()->name.' -  Error: '.$e->getMessage(). 'Linea: '.$e->getLine(). ' Id: '.($typeEvent['id'] ??'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('type_events').'.'.$fileType;

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
