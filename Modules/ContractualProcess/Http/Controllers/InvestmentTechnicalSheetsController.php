<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Http\Requests\CreateInvestmentTechnicalSheetsRequest;
use Modules\ContractualProcess\Http\Requests\UpdateInvestmentTechnicalSheetsRequest;
use Modules\ContractualProcess\Repositories\InvestmentTechnicalSheetsRepository;
use Modules\ContractualProcess\Repositories\AlternativeBudgetRepository;
use Modules\ContractualProcess\Models\InvestmentTechnicalSheets;
use Modules\ContractualProcess\Models\DirectCausesProblem;
use Modules\ContractualProcess\Models\IndirectCausesProblem;
use Modules\ContractualProcess\Models\DirectEffectsProblem;
use Modules\ContractualProcess\Models\IndirectEffectsProblem;
use Modules\ContractualProcess\Models\ProjectAreaInfluence;
use Modules\ContractualProcess\Models\SpecificObjective;
use Modules\ContractualProcess\Models\MonitoringIndicator;
use Modules\ContractualProcess\Models\InformationTariffHarmonization;
use Modules\ContractualProcess\Models\SupportingStudyData;
use Modules\ContractualProcess\Models\SelectionAlternative;
use Modules\ContractualProcess\Models\EnvironmentalImpact;
use Modules\ContractualProcess\Models\ResourceScheduleCurrentTerm;
use Modules\ContractualProcess\Models\ScheduleResourcesPreviousPeriod;
use Modules\ContractualProcess\Models\AlternativeBudget;
use Modules\ContractualProcess\Models\Budget;
use Modules\ContractualProcess\Models\BudgetTypeCost;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use Carbon\Carbon;

/**
 * DEscripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class InvestmentTechnicalSheetsController extends AppBaseController {

    /** @var  InvestmentTechnicalSheetsRepository */
    private $investmentTechnicalSheetsRepository;

    /** @var  AlternativeBudgetRepository */
    private $alternativeBudgetRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(InvestmentTechnicalSheetsRepository $investmentTechnicalSheetsRepo, AlternativeBudgetRepository $alternativeBudgetRepo) {
        $this->investmentTechnicalSheetsRepository = $investmentTechnicalSheetsRepo;
        $this->alternativeBudgetRepository = $alternativeBudgetRepo;
    }

    /**
     * Muestra la vista para el CRUD de InvestmentTechnicalSheets.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::investment_technical_sheets.index')->with("need", $request['need'] ?? null);
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
        $investment_technical_sheets = InvestmentTechnicalSheets::with([
                'validities',
                'nameProjects',
                'dependencies',
                'managementUnit',
                'projectLines',
                'poir',
                'directCausesProblems', 
                'indirectCausesProblems',
                'directEffectsProblems', 
                'indirectEffectsProblems',
                'projectAreasInfluence',
                'specificObjectives',
                'monitoringIndicators',
                'informationTariffHarmonization',
                'environmentalImpacts',
                'supportingStudyData',
                'selectionAlternatives',
                'resourceScheduleCurrentTerms',
                'scheduleResourcesPreviousPeriods'
            ])
            ->with(['alternativeBudgets' => function ($query) {
                $query->with(['budgets']);
                $query->with(['budgetTypesCosts']);
            }])
            ->when($request, function ($query) use($request) {
                // Valida si existe un id de una convocatoria
                if (!empty($request['pc_needs_id'])) {
                    return $query->where('pc_needs_id', $request['pc_needs_id']);
                }
            })
            ->latest()
            ->get();
        return $this->sendResponse($investment_technical_sheets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateInvestmentTechnicalSheetsRequest $request
     *
     * @return Response
     */
    public function store(CreateInvestmentTechnicalSheetsRequest $request) {

        $input = $request->all();

        $input['users_id']         = Auth::user()->id;
        $input['responsible_user'] = Auth::user()->name;

        $input['state'] = $this->getObjectOfList(config('contractual_process.investment_technical_state'), 'name', 'En elaboración')->id;

        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->create([
            'pc_needs_id' => $input['pc_needs_id'],
            'pc_validities_id' => $input['pc_validities_id'],
            'date_presentation' => $input['date_presentation'],
            'pc_name_projects_id' => $input['pc_name_projects_id'],
            'dependencias_id' => $input['dependencias_id'],
            'pc_management_unit_id' => $input['pc_management_unit_id'],
            'municipal_development_plan' => $input['municipal_development_plan'],
            'period' => $input['period'],
            'strategic_line' => $input['strategic_line'],
            'sector' => $input['sector'],
            'program' => $input['program'],
            'subprogram' => $input['subprogram'],
            'pc_project_lines_id' => $input['pc_project_lines_id'],
            'pc_poir_id' => $input['pc_poir_id'],
            'description_problem_need' => $input['description_problem_need'],
            'project_description' => $input['project_description'],
            'justification' => $input['justification'],
            'background' => $input['background'],
            'state' => $input['state'],
            'users_id' =>Auth::user()->id,
            'responsible_user' => Auth::user()->name,
        ]);

        // Valida si viene causas directas para asignar
        if (!empty($input['direct_causes_problems'])) {
            // Recorre las causas directas de la lista dinamica
            foreach($input['direct_causes_problems'] as $option){
                $directCause = json_decode($option);
                // Inserta relacion con causas directas
                $directCausesProblem = DirectCausesProblem::create([
                    'name' => $directCause->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene causas indirectas para asignar
        if (!empty($input['indirect_causes_problems'])) {
            // Recorre las causas indirectas de la lista dinamica
            foreach($input['indirect_causes_problems'] as $option){
                $inDirectCause = json_decode($option);
                // Inserta relacion con causas indirectas
                $indirectCausesProblem = IndirectCausesProblem::create([
                    'name' => $inDirectCause->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene efectos directos para asignar
        if (!empty($input['direct_effects_problems'])) {
            // Recorre las efectos directos de la lista dinamica
            foreach($input['direct_effects_problems'] as $option){
                $directEffect = json_decode($option);
                // Inserta relacion con efectos directos
                $directEffectsProblem = DirectEffectsProblem::create([
                    'name' => $directEffect->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene efectos indirectos para asignar
        if (!empty($input['indirect_effects_problems'])) {
            // Recorre las efectos indirectos de la lista dinamica
            foreach($input['indirect_effects_problems'] as $option){
                $directEffect = json_decode($option);
                // Inserta relacion con efectos indirectos
                $indirectEffectsProblem = IndirectEffectsProblem::create([
                    'name' => $directEffect->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene areas de influencia para asignar
        if (!empty($input['project_areas_influences'])) {
            // Recorre las areas de influencia de la lista dinamica
            foreach($input['project_areas_influences'] as $option){
                $areasInfluence = json_decode($option);
                // Inserta relacion con areas de influencia
                $projectAreaInfluence = ProjectAreaInfluence::create([
                    'service_coverage'   => $areasInfluence->service_coverage,
                    'neighborhood'       => $areasInfluence->neighborhood,
                    'commune'            => $areasInfluence->commune,
                    'number_inhabitants' => $areasInfluence->number_inhabitants,
                    'cities_id'          => $areasInfluence->cities_id,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }

        $investmentTechnicalSheets->validities;
        $investmentTechnicalSheets->nameProjects;
        $investmentTechnicalSheets->dependencies;
        $investmentTechnicalSheets->managementUnit;
        $investmentTechnicalSheets->directCausesProblems;
        $investmentTechnicalSheets->indirectCausesProblems;
        $investmentTechnicalSheets->directEffectsProblems;
        $investmentTechnicalSheets->indirectEffectsProblems;
        $investmentTechnicalSheets->projectAreasInfluence;

        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_save'));
    }


    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateInvestmentTechnicalSheetsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInvestmentTechnicalSheetsRequest $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($id);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $otherPlanningDocuments = (!empty($input['other_planning_documents'])? $input['other_planning_documents']: null);
        $whichOtherDocument = (!empty($input['which_other_document'])? $input['which_other_document']: null);

        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->update([
            'pc_needs_id'                => $input['pc_needs_id'],
            'pc_validities_id'           => $input['pc_validities_id'],
            'date_presentation'          => $input['date_presentation'],
            'pc_name_projects_id'        => $input['pc_name_projects_id'],
            'dependencias_id'            => $input['dependencias_id'],
            'pc_management_unit_id'      => $input['pc_management_unit_id'],
            'municipal_development_plan' => $input['municipal_development_plan'],
            'period'                     => $input['period'],
            'strategic_line'             => $input['strategic_line'],
            'sector'                     => $input['sector'],
            'program'                    => $input['program'],
            'subprogram'                 => $input['subprogram'],
            'pc_project_lines_id'        => $input['pc_project_lines_id'],
            'pc_poir_id'                 => $input['pc_poir_id'],
            'other_planning_documents'   => $otherPlanningDocuments,
            'other_planning_documents'   => $whichOtherDocument,
            'description_problem_need'   => $input['description_problem_need'],
            'project_description'        => $input['project_description'],
            'justification'              => $input['justification'],
            'background'                 => $input['background'],
        ], $id);

        // Valida si viene causas directas para asignar
        if (!empty($input['direct_causes_problems'])) {
            // Elimina si existe causas directas relacionadas
            DirectCausesProblem::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre las causas directas de la lista dinamica
            foreach($input['direct_causes_problems'] as $option){
                $directCause = json_decode($option);
                // Inserta relacion con causas directas
                $directCausesProblem = DirectCausesProblem::create([
                    'name' => $directCause->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene causas indirectas para asignar
        if (!empty($input['indirect_causes_problems'])) {
            // Elimina si existe causas indirectas relacionadas
            IndirectCausesProblem::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre las causas indirectas de la lista dinamica
            foreach($input['indirect_causes_problems'] as $option){
                $inDirectCause = json_decode($option);
                // Inserta relacion con causas indirectas
                $indirectCausesProblem = IndirectCausesProblem::create([
                    'name' => $inDirectCause->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene efectos directos para asignar
        if (!empty($input['direct_effects_problems'])) {
            // Elimina si existe efectos directos relacionados
            DirectEffectsProblem::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre las efectos directos de la lista dinamica
            foreach($input['direct_effects_problems'] as $option){
                $directEffect = json_decode($option);
                // Inserta relacion con efectos directos
                $directEffectsProblem = DirectEffectsProblem::create([
                    'name' => $directEffect->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene efectos indirectos para asignar
        if (!empty($input['indirect_effects_problems'])) {
            // Elimina si existe efectos indirectos relacionados
            IndirectEffectsProblem::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre las efectos indirectos de la lista dinamica
            foreach($input['indirect_effects_problems'] as $option){
                $directEffect = json_decode($option);
                // Inserta relacion con efectos indirectos
                $indirectEffectsProblem = IndirectEffectsProblem::create([
                    'name' => $directEffect->name,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene areas de influencia para asignar
        if (!empty($input['project_areas_influences'])) {
            // Elimina si existe areas de influencia relacionadas
            ProjectAreaInfluence::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre las areas de influencia de la lista dinamica
            foreach($input['project_areas_influences'] as $option){
                $areasInfluence = json_decode($option);
                // Inserta relacion con areas de influencia
                $projectAreaInfluence = ProjectAreaInfluence::create([
                    'service_coverage'   => $areasInfluence->service_coverage,
                    'neighborhood'       => $areasInfluence->neighborhood,
                    'commune'            => $areasInfluence->commune,
                    'number_inhabitants' => $areasInfluence->number_inhabitants,
                    'cities_id'          => $areasInfluence->cities_id,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }

        $investmentTechnicalSheets->validities;
        $investmentTechnicalSheets->nameProjects;
        $investmentTechnicalSheets->dependencies;
        $investmentTechnicalSheets->managementUnit;
        $investmentTechnicalSheets->directCausesProblems;
        $investmentTechnicalSheets->indirectCausesProblems;
        $investmentTechnicalSheets->directEffectsProblems;
        $investmentTechnicalSheets->indirectEffectsProblems;
        $investmentTechnicalSheets->projectAreasInfluence;

        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));

    }

    /**
     * Elimina un InvestmentTechnicalSheets del almacenamiento
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

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($id);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $investmentTechnicalSheets->delete();

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
        $fileName = time().'-'.trans('investment_technical_sheets').'.'.$fileType;

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
     * Guarda los objectivos e indicadores.
     *
     * @author Carlos Moises Garcia. - Ene. 22 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function saveGoalsIndicators(Request $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($input['id']);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Actualiza el registro de la ficha tecnica de inversion
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->update([
            'general_objective' => $input['general_objective'],
            'overall_goal' => $input['overall_goal'],
        ], $input['id']);

        // Valida si viene objectivos especificos para asignar
        if (!empty($input['specific_objectives'])) {
            // Elimina si existe objectivos especificos relacionadas
            SpecificObjective::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los objectivos especificos de la lista dinamica
            foreach($input['specific_objectives'] as $option){
                $specificObjectives = json_decode($option);
                // Inserta relacion con objectivos especificos
                $specificObjective = SpecificObjective::create([
                    'description' => $specificObjectives->description,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }

        // Valida si viene indicadores de seguimiento para asignar
        if (!empty($input['monitoring_indicators'])) {
            // Elimina si existe indicadores de seguimiento relacionadas
            MonitoringIndicator::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los indicadores de seguimiento de la lista dinamica
            foreach($input['monitoring_indicators'] as $option){
                $monitoringIndicator = json_decode($option);
                // Inserta relacion con indicadores de seguimiento
                $indicator = MonitoringIndicator::create([
                    'indicator_type' => $monitoringIndicator->indicator_type,
                    'description' => $monitoringIndicator->description,
                    'formula' => $monitoringIndicator->formula,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));
    }

    /**
     * Guarda info armonizacion tarifaria.
     *
     * @author Carlos Moises Garcia. - Ene. 26 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function saveInfoTariffHarmonization(Request $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($input['id']);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }


        // $input['replacement'] = $this->toBoolean(empty($input['replacement'])? false: $input['replacement']);
        // $input['expansion'] = $this->toBoolean(empty($input['expansion'])? false: $input['expansion']);
        // $input['rehabilitation'] = $this->toBoolean(empty($input['rehabilitation'])? false: $input['rehabilitation']);
        // $input['process_licenses_guadua'] = $this->toBoolean(empty($input['process_licenses_guadua'])? false: $input['process_licenses_guadua']);
        // $input['coverage'] = $this->toBoolean(empty($input['coverage'])? false: $input['coverage']);
        // $input['continuity'] = $this->toBoolean(empty($input['continuity'])? false: $input['continuity']);
        // $input['irca_water_quality_risk_index'] = $this->toBoolean(empty($input['irca_water_quality_risk_index'])? false: $input['irca_water_quality_risk_index']);
        // $input['micrometer'] = $this->toBoolean(empty($input['micrometer'])? false: $input['micrometer']);
        // $input['ianc_unaccounted_water_index'] = $this->toBoolean(empty($input['ianc_unaccounted_water_index'])? false: $input['ianc_unaccounted_water_index']);
        // $input['ipufi_loss_index_billed_user'] = $this->toBoolean(empty($input['ipufi_loss_index_billed_user'])? false: $input['ipufi_loss_index_billed_user']);
        // $input['icufi_index_water_consumed_user'] = $this->toBoolean(empty($input['icufi_index_water_consumed_user'])? false: $input['icufi_index_water_consumed_user']);
        // $input['isufi_supply_index_billed_user'] = $this->toBoolean(empty($input['isufi_supply_index_billed_user'])? false: $input['isufi_supply_index_billed_user']);
        // $input['ccpi_consumption_corrected_losses'] = $this->toBoolean(empty($input['ccpi_consumption_corrected_losses'])? false: $input['ccpi_consumption_corrected_losses']);
        // $input['pressure'] = $this->toBoolean(empty($input['pressure'])? false: $input['pressure']);
        // $input['discharge_treatment_index'] = $this->toBoolean(empty($input['discharge_treatment_index'])? false: $input['discharge_treatment_index']);
        // $input['tons_bbo_removed'] = $this->toBoolean(empty($input['tons_bbo_removed'])? false: $input['tons_bbo_removed']);
        // $input['tons_sst_removed'] = $this->toBoolean(empty($input['tons_sst_removed'])? false: $input['tons_sst_removed']);
        // $input['operational_claim_index'] = $this->toBoolean(empty($input['operational_claim_index'])? false: $input['operational_claim_index']);
        // $input['commercial_claim_index'] = $this->toBoolean(empty($input['commercial_claim_index'])? false: $input['commercial_claim_index']);
        // $input['efficiency_collection'] = $this->toBoolean(empty($input['efficiency_collection'])? false: $input['efficiency_collection']);
        // $input['via_aqueduct_sewerage_rates'] = $this->toBoolean(empty($input['via_aqueduct_sewerage_rates'])? false: $input['via_aqueduct_sewerage_rates']);
        // $input['cleaning_fee_resources'] = $this->toBoolean(empty($input['cleaning_fee_resources'])? false: $input['cleaning_fee_resources']);
        // $input['regalias'] = $this->toBoolean(empty($input['regalias'])? false: $input['regalias']);
        // $input['general_participation_system'] = $this->toBoolean(empty($input['general_participation_system'])? false: $input['general_participation_system']);
        // $input['decentralized_entity'] = $this->toBoolean(empty($input['decentralized_entity'])? false: $input['decentralized_entity']);
        // $input['capital_contributed'] = $this->toBoolean(empty($input['capital_contributed'])? false: $input['capital_contributed']);
        // $input['contributed_capital_official'] = $this->toBoolean(empty($input['contributed_capital_official'])? false: $input['contributed_capital_official']);
        // $input['capital_contributions'] = $this->toBoolean(empty($input['capital_contributions'])? false: $input['capital_contributions']);
        // $input['third_party_contributions'] = $this->toBoolean(empty($input['third_party_contributions'])? false: $input['third_party_contributions']);
        // $input['national_debt'] = $this->toBoolean(empty($input['national_debt'])? false: $input['national_debt']);
        // $input['foreign_debt'] = $this->toBoolean(empty($input['foreign_debt'])? false: $input['foreign_debt']);

        // Actualiza el registro de la ficha tecnica de inversion
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->update([
            'replacement' => $this->toBoolean(empty($input['replacement'])? false: $input['replacement']),
            'expansion' => $this->toBoolean(empty($input['expansion'])? false: $input['expansion']),
            'rehabilitation' => $this->toBoolean(empty($input['rehabilitation'])? false: $input['rehabilitation']),
            'process_licenses_guadua' => $this->toBoolean(empty($input['process_licenses_guadua'])? false: $input['process_licenses_guadua']),
            'coverage' => $this->toBoolean(empty($input['coverage'])? false: $input['coverage']),
            'continuity' => $this->toBoolean(empty($input['continuity'])? false: $input['continuity']),
            'irca_water_quality_risk_index' => $this->toBoolean(empty($input['irca_water_quality_risk_index'])? false: $input['irca_water_quality_risk_index']),
            'micrometer' => $this->toBoolean(empty($input['micrometer'])? false: $input['micrometer']),
            'ianc_unaccounted_water_index' => $this->toBoolean(empty($input['ianc_unaccounted_water_index'])? false: $input['ianc_unaccounted_water_index']),
            'ipufi_loss_index_billed_user' => $this->toBoolean(empty($input['ipufi_loss_index_billed_user'])? false: $input['ipufi_loss_index_billed_user']),
            'icufi_index_water_consumed_user' => $this->toBoolean(empty($input['icufi_index_water_consumed_user'])? false: $input['icufi_index_water_consumed_user']),
            'isufi_supply_index_billed_user' => $this->toBoolean(empty($input['isufi_supply_index_billed_user'])? false: $input['isufi_supply_index_billed_user']),
            'ccpi_consumption_corrected_losses' => $this->toBoolean(empty($input['ccpi_consumption_corrected_losses'])? false: $input['ccpi_consumption_corrected_losses']),
            'pressure' => $this->toBoolean(empty($input['pressure'])? false: $input['pressure']),
            'discharge_treatment_index' => $this->toBoolean(empty($input['discharge_treatment_index'])? false: $input['discharge_treatment_index']),
            'tons_bbo_removed' => $this->toBoolean(empty($input['tons_bbo_removed'])? false: $input['tons_bbo_removed']),
            'tons_sst_removed' => $this->toBoolean(empty($input['tons_sst_removed'])? false: $input['tons_sst_removed']),
            'operational_claim_index' => $this->toBoolean(empty($input['operational_claim_index'])? false: $input['operational_claim_index']),
            'commercial_claim_index' => $this->toBoolean(empty($input['commercial_claim_index'])? false: $input['commercial_claim_index']),
            'efficiency_collection' => $this->toBoolean(empty($input['efficiency_collection'])? false: $input['efficiency_collection']),
            'via_aqueduct_sewerage_rates' => $this->toBoolean(empty($input['via_aqueduct_sewerage_rates'])? false: $input['via_aqueduct_sewerage_rates']),
            'cleaning_fee_resources' => $this->toBoolean(empty($input['cleaning_fee_resources'])? false: $input['cleaning_fee_resources']),
            'regalias' => $this->toBoolean(empty($input['regalias'])? false: $input['regalias']),
            'general_participation_system' => $this->toBoolean(empty($input['general_participation_system'])? false: $input['general_participation_system']),
            'decentralized_entity' => $this->toBoolean(empty($input['decentralized_entity'])? false: $input['decentralized_entity']),
            'capital_contributed' => $this->toBoolean(empty($input['capital_contributed'])? false: $input['capital_contributed']),
            'contributed_capital_official' => $this->toBoolean(empty($input['contributed_capital_official'])? false: $input['contributed_capital_official']),
            'capital_contributions' => $this->toBoolean(empty($input['capital_contributions'])? false: $input['capital_contributions']),
            'third_party_contributions' => $this->toBoolean(empty($input['third_party_contributions'])? false: $input['third_party_contributions']),
            'national_debt' => $this->toBoolean(empty($input['national_debt'])? false: $input['national_debt']),
            'foreign_debt' => $this->toBoolean(empty($input['foreign_debt'])? false: $input['foreign_debt']),
            'social' => empty($input['social'])? null: $input['social'],
            'environmental' => empty($input['environmental'])? null: $input['environmental'],
            'economical' => empty($input['economical'])? null: $input['economical'],
            'jobs_to_generate' => empty($input['jobs_to_generate'])? null: $input['jobs_to_generate'],
        ], $input['id']);

        // Valida si viene indicadores de seguimiento para asignar
        if (!empty($input['information_tariff_harmonizations'])) {
            // Elimina si existe indicadores de seguimiento relacionadas
            InformationTariffHarmonization::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los indicadores de seguimiento de la lista dinamica
            foreach($input['information_tariff_harmonizations'] as $option){
                $informationTariffHarmonization = json_decode($option);
                // Inserta relacion con indicadores de seguimiento
                $tariffHarmonization = InformationTariffHarmonization::create([
                    'item' => $informationTariffHarmonization->item,
                    'activity' => $informationTariffHarmonization->activity,
                    'unit' => $informationTariffHarmonization->unit,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene indicadores de seguimiento para asignar
        if (!empty($input['supporting_study_data'])) {
            // Elimina si existe indicadores de seguimiento relacionadas
            SupportingStudyData::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los indicadores de seguimiento de la lista dinamica
            foreach($input['supporting_study_data'] as $option){
                $studyData = json_decode($option);
                // Inserta relacion con indicadores de seguimiento
                $supportingStudyData = SupportingStudyData::create([
                    'name' => $studyData->name,
                    'study_date' => Carbon::parse($studyData->study_date)->format('Y-m-d'),
                    'author' => $studyData->author,
                    'state' => $studyData->state,
                    'storage_place' => $studyData->storage_place,
                    'support_study_type' => $studyData->support_study_type,
                    // 'product_consultancy' => $this->toBoolean(empty($studyData->product_consultancy)? false: $studyData->product_consultancy),
                    'product_consultancy' => $studyData->product_consultancy,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        // Valida si viene indicadores de seguimiento para asignar
        if (!empty($input['selection_alternatives'])) {
            // Elimina si existe indicadores de seguimiento relacionadas
            SelectionAlternative::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los indicadores de seguimiento de la lista dinamica
            foreach($input['selection_alternatives'] as $option){
                $alternative = json_decode($option);
                // Inserta relacion con indicadores de seguimiento
                $selectionAlternative = SelectionAlternative::create([
                    'alternative_name' => $alternative->alternative_name,
                    'description' => $alternative->description,
                    // 'selected' => $this->toBoolean(empty($alternative->selected)? false: $alternative->selected),
                    'selected' => $alternative->selected,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        
        $investmentTechnicalSheets->supportingStudyData;
        $investmentTechnicalSheets->selectionAlternatives;
        
        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));
    }

    /**
     * Guarda los impactos ambientales.
     *
     * @author Carlos Moises Garcia. - Ene. 22 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function saveEnvironmentalImpacts(Request $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($input['id']);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // // $input['requires_environmental_license'] = $this->toBoolean(empty($input['requires_environmental_license'])? false: $input['requires_environmental_license']);
        // $input['requires_environmental_license'] = $input['requires_environmental_license'];
        // $input['license_number'] = empty($input['license_number'])? null: $input['license_number'];
        // $input['expedition_date'] = empty($input['expedition_date'])? null: $input['expedition_date'];

        // Actualiza el registro de la ficha tecnica de inversion
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->update([
            'requires_environmental_license' => $input['requires_environmental_license'],
            'license_number' => empty($input['license_number'])? null: $input['license_number'],
            'expedition_date' => empty($input['expedition_date'])? null: $input['expedition_date'],
        ], $input['id']);

        // Valida si viene impactos ambientales para asignar
        if (!empty($input['environmental_impacts'])) {
            // Elimina si existe impactos ambientales relacionadas
            EnvironmentalImpact::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
            // Recorre los impactos ambientales de la lista dinamica
            foreach($input['environmental_impacts'] as $option){
                $impact = json_decode($option);
                // Inserta relacion con impactos ambientales
                $environmentalImpact = EnvironmentalImpact::create([
                    'environmental_component' => $impact->environmental_component,
                    'other_environmental_component'  => empty($input['other_environmental_component'])? null: $input['other_environmental_component'],
                    'impact_description' => $impact->impact_description,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));
    }

    /**
     * Guarda los cronogramas.
     *
     * @author Carlos Moises Garcia. - Ene. 22 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function saveChronograms(Request $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($input['id']);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

       

        // Valida si viene el cronograma de la vigencia actual para asignar
        if (!empty($input['resource_schedule_current_terms'])) {
            // Elimina si existe cronograma de la vigencia actual relacionadas
            ResourceScheduleCurrentTerm::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();

            // $decode = json_decode($input['resource_schedule_current_terms'], true);

            // Recorre el cronograma de la vigencia actual de la lista dinamica
            foreach ($input['resource_schedule_current_terms'] as $key => $option) {
                
                $schedules = json_decode($option);


                $resourceScheduleCurrentTerm = ResourceScheduleCurrentTerm::create([
                    'description' => $schedules->description,
                    // 'week_id' => $schedule['id'],
                    'week' => $schedules->week,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            
               

                // foreach ($schedules as $key => $schedule) {
                //     // dd( $schedule);
                //      // Inserta relacion con cronograma de la vigencia actual
                //     $resourceScheduleCurrentTerm = ResourceScheduleCurrentTerm::create([
                //         'description' => $schedules->description,
                //         // 'week_id' => $schedule['id'],
                //         'week_name' => json_encode($schedule),
                //         'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                //     ]);
                // }
               
               
            }
        }
        // Valida si viene el cronograma de la vigencia anterior para asignar
        // if (!empty($input['schedule_resources_previous_periods'])) {
        //     // Elimina si existe cronograma de la vigencia anterior relacionadas
        //     ScheduleResourcesPreviousPeriod::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();
        //     // Recorre el cronograma de la vigencia anterior de la lista dinamica
        //     foreach($input['schedule_resources_previous_periods'] as $option){
        //         $schedule = json_decode($option);
        //         // Inserta relacion con cronograma de la vigencia anterior
        //         // $scheduleResourcesPreviousPeriod = ScheduleResourcesPreviousPeriod::create([
        //         //     'description' => $schedule->description,
        //         //     'month' => $schedule->month,
        //         //     'week' => $schedule->week,
        //         //     'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
        //         // ]);
        //     }
        // }
        // Valida si viene el cronograma de la vigencia anterior para asignar
        if (!empty($input['schedule_resources_previous_periods'])) {
            // Elimina si existe cronograma de la vigencia actual relacionadas
            ScheduleResourcesPreviousPeriod::where('pc_investment_technical_sheets_id', $investmentTechnicalSheets->id)->delete();

            // Recorre el cronograma de la vigencia actual de la lista dinamica
            foreach ($input['schedule_resources_previous_periods'] as $key => $option) {
                $schedules = json_decode($option);

                $scheduleResourcesPreviousPeriod = ScheduleResourcesPreviousPeriod::create([
                    'description' => $schedules->description,
                    'week' => $schedules->week,
                    'pc_investment_technical_sheets_id' => $investmentTechnicalSheets->id
                ]);
            }
        }
        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));
    }

    /**
     * Guarda el presupuesto alternativo.
     *
     * @author Carlos Moises Garcia. - Marz. 05 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function saveAlternativeBudget(Request $request) {

        $input = $request->all();

        /** @var InvestmentTechnicalSheets $investmentTechnicalSheets */
        $investmentTechnicalSheets = $this->investmentTechnicalSheetsRepository->find($input['pc_investment_technical_sheets_id']);

        if (empty($investmentTechnicalSheets)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

       

        
        if (empty($input['id'])) {
            // Inserta registro del presupuesto alternativo
            $alternativeBudget = AlternativeBudget::create([
                'total_direct_cost'                    => $input['total_direct_cost'],
                'total_direct_aqueduct'                => $input['total_direct_aqueduct'],
                'total_direct_percentage_aqueduct'     => $input['total_direct_percentage_aqueduct'],
                'total_direct_sewerage'                => $input['total_direct_sewerage'],
                'total_direct_percentage_sewerage'     => $input['total_direct_percentage_sewerage'],
                'total_direct_cleanliness'             => $input['total_direct_cleanliness'],
                'total_direct_percentage_cleanliness'  => $input['total_direct_percentage_cleanliness'],
                'total_project_cost'                   => $input['total_project_cost'],
                'total_project_aqueduct'               => $input['total_project_aqueduct'],
                'total_project_percentage_aqueduct'    => $input['total_project_percentage_aqueduct'],
                'total_project_sewerage'               => $input['total_project_sewerage'],
                'total_project_percentage_sewerage'    => $input['total_project_percentage_sewerage'],
                'total_project_cleanliness'            => $input['total_project_cleanliness'],
                'total_project_percentage_cleanliness' => $input['total_project_percentage_cleanliness'],
                'pc_investment_technical_sheets_id'    => $investmentTechnicalSheets->id
            ]);
        } else {

             // Inserta registro del presupuesto alternativo
            // $alternativeBudgetOld = AlternativeBudget::find($input['id']);

            // Actualiza el registro del presupuesto alternativo
            $alternativeBudget = $this->alternativeBudgetRepository->update([
                'total_direct_cost'                    => $input['total_direct_cost'],
                'total_direct_aqueduct'                => $input['total_direct_aqueduct'],
                'total_direct_percentage_aqueduct'     => $input['total_direct_percentage_aqueduct'],
                'total_direct_sewerage'                => $input['total_direct_sewerage'],
                'total_direct_percentage_sewerage'     => $input['total_direct_percentage_sewerage'],
                'total_direct_cleanliness'             => $input['total_direct_cleanliness'],
                'total_direct_percentage_cleanliness'  => $input['total_direct_percentage_cleanliness'],
                'total_project_cost'                   => $input['total_project_cost'],
                'total_project_aqueduct'               => $input['total_project_aqueduct'],
                'total_project_percentage_aqueduct'    => $input['total_project_percentage_aqueduct'],
                'total_project_sewerage'               => $input['total_project_sewerage'],
                'total_project_percentage_sewerage'    => $input['total_project_percentage_sewerage'],
                'total_project_cleanliness'            => $input['total_project_cleanliness'],
                'total_project_percentage_cleanliness' => $input['total_project_percentage_cleanliness'],
                'pc_investment_technical_sheets_id'    => $investmentTechnicalSheets->id
            ], $input['id']);
        }

        // Valida si viene presupuesto
        if (!empty($input['alternative_budgets'])) {
            // Elimina si existe presupuesto relacionadas
            Budget::where('pc_alternative_budget_id', $alternativeBudget->id)->delete();
            // Recorre el presupuesto de la lista dinamica
            foreach($input['alternative_budgets'] as $option){
                $cost = json_decode($option);
                // Inserta relacion con presupuesto
                $budget = Budget::create([
                    'description'              => empty($cost->description)? null: $cost->description,
                    'unit'                     => $cost->unit,
                    'quantity'                 => $cost->quantity,
                    'unit_value'               => $cost->unit_value,
                    'total_value'              => $cost->total_value,
                    'aqueduct'                 => $cost->aqueduct,
                    'percentage_aqueduct'      => $cost->percentage_aqueduct,
                    'sewerage'                 => $cost->sewerage,
                    'percentage_sewerage'      => $cost->percentage_sewerage,
                    'cleanliness'              => $cost->cleanliness,
                    'percentage_cleanliness'   => $cost->percentage_cleanliness,
                    'pc_alternative_budget_id' => $alternativeBudget->id
                ]);
            }
        }
        // Valida si viene tipos de costos para asignar
        if (!empty($input['types_costs'])) {
            // Elimina si existe tipos de costos relacionadas
            BudgetTypeCost::where('pc_alternative_budget_id', $alternativeBudget->id)->delete();
            // Recorre los tipos de costos de la lista dinamica
            foreach($input['types_costs'] as $option){
                $typeCost = json_decode($option);
                // Inserta relacion con tipos de costos
                $budgetTypeCost = BudgetTypeCost::create([
                    'cost_type'                => $typeCost->cost_type,
                    'total_value'              => $typeCost->total_value,
                    'aqueduct'                 => $typeCost->aqueduct,
                    'percentage_aqueduct'      => $typeCost->percentage_aqueduct,
                    'sewerage'                 => $typeCost->sewerage,
                    'percentage_sewerage'      => $typeCost->percentage_sewerage,
                    'cleanliness'              => $typeCost->cleanliness,
                    'percentage_cleanliness'   => $typeCost->percentage_cleanliness,
                    'pc_alternative_budget_id' => $alternativeBudget->id
                ]);
            }
        }
        return $this->sendResponse($investmentTechnicalSheets->toArray(), trans('msg_success_update'));
    }
}
