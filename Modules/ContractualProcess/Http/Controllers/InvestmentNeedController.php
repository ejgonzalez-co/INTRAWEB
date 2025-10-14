<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ExportViewExcel;
use Modules\ContractualProcess\Http\Requests\CreateInvestmentNeedRequest;
use Modules\ContractualProcess\Http\Requests\UpdateInvestmentNeedRequest;
use Modules\ContractualProcess\Repositories\InvestmentNeedRepository;
use Modules\ContractualProcess\Models\Budget;
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
class InvestmentNeedController extends AppBaseController {

    /** @var  InvestmentNeedRepository */
    private $investmentNeedRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(InvestmentNeedRepository $investmentNeedRepo) {
        $this->investmentNeedRepository = $investmentNeedRepo;
    }

    /**
     * Muestra la vista para el CRUD de InvestmentNeed.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::investment_needs.index');
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
        $bugets = Budget::
            where(function($query) {
                 // Valida cuando el rol es el gestor de presupuesto
                if (Auth::user()->hasRole('PC Gestor presupuesto')) {
                    $query->whereNotNull('state');
                }
            })
            ->latest()
            ->get()
            ->map(function($item, $key){
                if ($item->alternativeBudgets) {
                    if ($item->alternativeBudgets->investmentTechnicalSheets->needs) {
                        $item->user_leader_id = $item->alternativeBudgets->investmentTechnicalSheets->needs->processLeaders->users_id;
                        $item->name_process  = $item->alternativeBudgets->investmentTechnicalSheets->needs->processLeaders->name_process;
                    }
                    
                }
                return $item;
            });
        return $this->sendResponse($bugets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateInvestmentNeedRequest $request
     *
     * @return Response
     */
    public function store(CreateInvestmentNeedRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $investmentNeed = $this->investmentNeedRepository->create($input);

            return $this->sendResponse($investmentNeed->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateInvestmentNeedRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvestmentNeedRequest $request) {

        $input = $request->all();

        /** @var InvestmentNeed $investmentNeed */
        $investmentNeed = $this->investmentNeedRepository->find($id);

        if (empty($investmentNeed)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $investmentNeed = $this->investmentNeedRepository->update($input, $id);
        
            return $this->sendResponse($investmentNeed->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un InvestmentNeed del almacenamiento
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

        /** @var InvestmentNeed $investmentNeed */
        $investmentNeed = $this->investmentNeedRepository->find($id);

        if (empty($investmentNeed)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        try {
            // Elimina el registro
            $investmentNeed->delete();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\InvestmentNeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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
        $fileName = time().'-'.trans('investment_needs').'.'.$fileType;

        return Excel::download(new ExportViewExcel('contractual_process::investment_needs.report_excel', $input['data'], 'G'), $fileName);
    }
}
