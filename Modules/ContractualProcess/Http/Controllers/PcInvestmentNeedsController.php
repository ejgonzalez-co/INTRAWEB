<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreatePcInvestmentNeedsRequest;
use Modules\ContractualProcess\Http\Requests\UpdatePcInvestmentNeedsRequest;
use Modules\ContractualProcess\Repositories\PcInvestmentNeedsRepository;
use Modules\ContractualProcess\Models\Needs;
use Modules\ContractualProcess\Models\ProcessLeaders;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class PcInvestmentNeedsController extends AppBaseController {

    /** @var  PcInvestmentNeedsRepository */
    private $pcInvestmentNeedsRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(PcInvestmentNeedsRepository $pcInvestmentNeedsRepo) {
        $this->pcInvestmentNeedsRepository = $pcInvestmentNeedsRepo;
    }

    /**
     * Muestra la vista para el CRUD de PcInvestmentNeeds.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::pc_investment_needs.index')->with("need", $request['need'] ?? null);
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
        $pc_investment_needs = $this->pcInvestmentNeedsRepository->all();
        return $this->sendResponse($pc_investment_needs->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreatePcInvestmentNeedsRequest $request
     *
     * @return Response
     */
    public function store(CreatePcInvestmentNeedsRequest $request) {

        $input = $request->all();

        // Obtiene los datos de la necesidad
        $needs = Needs::find($input['pc_needs_id']);

        // Validad si el estado de la necesidad es sin iniciar
        if ($needs->state == 1) {
            $needs->update([
                'state' => $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA en elaboración')->id,
            ]);
        }

        // Actualiza el registro de la necesidad
        $needs = $needs->update([
            'total_investment_value' => $needs->total_investment_value + $input['estimated_value'],
            'total_value_paa' => $needs->total_value_paa + $input['estimated_value'],
        ]);

        $pcInvestmentNeeds = $this->pcInvestmentNeedsRepository->create($input);

        return $this->sendResponse($pcInvestmentNeeds->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePcInvestmentNeedsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePcInvestmentNeedsRequest $request) {

        $input = $request->all();

        /** @var PcInvestmentNeeds $pcInvestmentNeeds */
        $pcInvestmentNeeds = $this->pcInvestmentNeedsRepository->find($id);

        if (empty($pcInvestmentNeeds)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Obtiene los datos de la necesidad
        $needs = Needs::find($input['pc_needs_id']);

        // Actualiza el registro de la necesidad
        $needs = $needs->update([
            'total_investment_value' => ($needs->total_investment_value - $pcInvestmentNeeds->estimated_value) + $input['estimated_value'],
            'total_value_paa' => ($needs->total_value_paa - $pcInvestmentNeeds->estimated_value) + $input['estimated_value'],
        ]);

        $pcInvestmentNeeds = $this->pcInvestmentNeedsRepository->update($input, $id);

        return $this->sendResponse($pcInvestmentNeeds->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un PcInvestmentNeeds del almacenamiento
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

        /** @var PcInvestmentNeeds $pcInvestmentNeeds */
        $pcInvestmentNeeds = $this->pcInvestmentNeedsRepository->find($id);

        if (empty($pcInvestmentNeeds)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Obtiene los datos de la necesidad
        $needs = Needs::find($pcInvestmentNeeds->pc_needs_id);

        // Actualiza el registro de la necesidad
        $needs = $needs->update([
            'total_investment_value' => $needs->total_investment_value - $pcInvestmentNeeds->estimated_value,
            'total_value_paa' => $needs->total_value_paa - $pcInvestmentNeeds->estimated_value,
        ]);

        $pcInvestmentNeeds->delete();

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
        $fileName = time().'-'.trans('pc_investment_needs').'.'.$fileType;

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
