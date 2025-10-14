<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ExportViewExcel;
use Modules\ContractualProcess\Http\Requests\CreateFunctioningNeedRequest;
use Modules\ContractualProcess\Http\Requests\UpdateFunctioningNeedRequest;
use Modules\ContractualProcess\Repositories\FunctioningNeedRepository;
use Modules\ContractualProcess\Models\FunctioningNeed;
use Modules\ContractualProcess\Models\Need;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class FunctioningNeedController extends AppBaseController {

    /** @var  FunctioningNeedRepository */
    private $functioningNeedRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(FunctioningNeedRepository $functioningNeedRepo) {
        $this->functioningNeedRepository = $functioningNeedRepo;
    }

    /**
     * Muestra la vista para el CRUD de FunctioningNeed.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // Valida si existe un id de necesidad
        if (!empty($request['need'])) {
            // Obtiene los datos de la necesidad
            $need = Need::find($request['need']);
            
        }
        // dd($need);
        return view('contractual_process::functioning_needs.index')->with("need", $need ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        
        $functioning_needs = FunctioningNeed::with(['needs'])
            ->when($request, function ($query) use($request) {
                // Valida si existe un id de una necesidad
                if (!empty($request['pc_needs_id'])) {
                    return $query->where('pc_needs_id', $request['pc_needs_id']);
                }

                // Valida cuando el rol es el gestor de presupuesto
                if (Auth::user()->hasRole('PC Gestor presupuesto')) {
                    $query->whereNotNull('state');
                }
            })
            ->get()
            ->map(function($item, $key){
                if ($item->needs) {
                    $item->user_leader_id = $item->needs->processLeaders->users_id;
                    $item->name_process = $item->needs->processLeaders->name_process;
                }
                return $item;
            });
        return $this->sendResponse($functioning_needs->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateFunctioningNeedRequest $request
     *
     * @return Response
     */
    public function store(CreateFunctioningNeedRequest $request) {

        $input = $request->all();

        try {
            // Obtiene los datos de la necesidad
            $need = Need::find($input['pc_needs_id']);

            // Validad si el estado de la necesidad es sin iniciar
            if ($need->state == 1) {
                $need->update([
                    'state' => $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA en elaboración')->id,
                ]);
            }

            // Actualiza el registro de la necesidad
            $need = $need->update([
                'total_operating_value' => $need->total_operating_value + $input['estimated_total_value'],
                'total_value_paa' => $need->total_value_paa + $input['estimated_total_value'],
            ]);

            // Inserta el registro en la base de datos
            $functioningNeed = $this->functioningNeedRepository->create($input);

            // Valida que exista una necesidad relacionada
            if (!empty($functioningNeed->needs)) {
                $functioningNeed->user_leader_id = $functioningNeed->needs->processLeaders->users_id;
                $functioningNeed->name_process = $functioningNeed->needs->processLeaders->name_process;
            }

            return $this->sendResponse($functioningNeed->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\FunctioningNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\FunctioningNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateFunctioningNeedRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFunctioningNeedRequest $request) {

        $input = $request->all();

        /** @var FunctioningNeed $functioningNeed */
        $functioningNeed = $this->functioningNeedRepository->find($id);

        if (empty($functioningNeed)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Obtiene los datos de la necesidad
            $need = Need::find($input['pc_needs_id']);

            // Actualiza el registro de la necesidad
            $need = $need->update([
                'total_operating_value' => ($need->total_operating_value - $functioningNeed->estimated_total_value) + $input['estimated_total_value'],
                'total_value_paa' => ($need->total_value_paa - $functioningNeed->estimated_total_value) + $input['estimated_total_value'],
            ]);

            // Actualiza el registro
            $functioningNeed = $this->functioningNeedRepository->update($input, $id);
        
            return $this->sendResponse($functioningNeed->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\FunctioningNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\FunctioningNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un FunctioningNeed del almacenamiento
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

        /** @var FunctioningNeed $functioningNeed */
        $functioningNeed = $this->functioningNeedRepository->find($id);

        if (empty($functioningNeed)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

         // Obtiene los datos de la necesidad
        $need = Need::find($functioningNeed->pc_needs_id);

        // Actualiza el registro de la necesidad
        $need = $need->update([
            'total_operating_value' => $need->total_operating_value - $functioningNeed->estimated_total_value,
            'total_value_paa' => $need->total_operating_value - $functioningNeed->estimated_total_value,
        ]);

        $functioningNeed->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
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
        $fileName = time().'-'.trans('functioning_needs').'.'.$fileType;

        return Excel::download(new ExportViewExcel('contractual_process::functioning_needs.report_excel', $input['data'], 'D'), $fileName);
    }
}
