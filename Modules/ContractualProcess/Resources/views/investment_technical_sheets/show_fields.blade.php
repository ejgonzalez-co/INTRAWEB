<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Información general del proyecto:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Code Bppiepa Field -->
         <dt class="text-inverse col-3">@lang('Code Bppiepa'):</dt>
         <dd class="col-9">@{{ dataShow.code_bppiepa }}</dd>

         <!-- Pc Validities Id Field -->
         <dt class="text-inverse col-3">@lang('Validity'):</dt>
         <dd class="col-9">@{{ dataShow.validities? dataShow.validities.name: '' }}</dd>

         <!-- Date Presentation Field -->
         <dt class="text-inverse col-3">@lang('Date Presentation'):</dt>
         <dd class="col-9">@{{ dataShow.date_presentation }}</dd>

         <!-- Update Date Field -->
         <!-- <dt class="text-inverse col-3">@lang('Update Date'):</dt>
         <dd class="col-9">@{{ dataShow.update_date }}</dd> -->

         <!-- Pc Name Projects Id Field -->
         <dt class="text-inverse col-3">@lang('Name Project'):</dt>
         <dd class="col-9">@{{ dataShow.name_projects? dataShow.name_projects.name: '' }}</dd>

         <!-- Dependencias Id Field -->
         <dt class="text-inverse col-3">@lang('Submanagement or Direction'):</dt>
         <dd class="col-9">@{{ dataShow.dependencies ? dataShow.dependencies.nombre : '' }}</dd>

         <!-- Pc Management Unit Id Field -->
         <dt class="text-inverse col-3">@lang('Management Unit'):</dt>
         <dd class="col-9">@{{ dataShow.management_unit ? dataShow.management_unit.name : '' }}</dd>

         <!-- Responsible User Field -->
         <dt class="text-inverse col-3">@lang('Nombre y Apellidos de la persona responsable'):</dt>
         <dd class="col-9">@{{ dataShow.responsible_user }}</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Coherencia del proyecto con el Plan de Desarrollo Municipal y otros documentos de planificación:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Municipal Development Plan Field -->
         <dt class="text-inverse col-3">@lang('Municipal Development Plan'):</dt>
         <dd class="col-9">@{{ dataShow.municipal_development_plan_name }}</dd>

         <!-- Period Field -->
         <dt class="text-inverse col-3">@lang('Period'):</dt>
         <dd class="col-9">@{{ dataShow.period_name }}</dd>

         <!-- Strategic Line Field -->
         <dt class="text-inverse col-3">@lang('Strategic Line'):</dt>
         <dd class="col-9">@{{ dataShow.strategic_line_name }}</dd>

         <!-- Sector Field -->
         <dt class="text-inverse col-3">@lang('Sector'):</dt>
         <dd class="col-9">@{{ dataShow.sector_name }}</dd>

         <!-- Program Field -->
         <dt class="text-inverse col-3">@lang('Program'):</dt>
         <dd class="col-9">@{{ dataShow.program_name }}</dd>

         <!-- Subprogram Field -->
         <dt class="text-inverse col-3">@lang('Subprogram'):</dt>
         <dd class="col-9">@{{ dataShow.subprogram_name }}</dd>

         <!-- Pc Project Lines Id Field -->
         <dt class="text-inverse col-3">@lang('Project Line'):</dt>
         <dd class="col-9">@{{ dataShow.project_lines? dataShow.project_lines.name: '' }}</dd>

         <!-- Identification Project Field -->
         <dt class="text-inverse col-3">@lang('Identification Project'):</dt>
         <dd class="col-9">@{{ dataShow.poir? dataShow.poir.name: '' }}</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Identificación del problema o necesidad:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Description Problem Need Field -->
         <dt class="text-inverse col-3">@lang('Description Problem Need'):</dt>
         <dd class="col-9">@{{ dataShow.description_problem_need }}</dd>

         <br>

         <dt class="text-inverse col-3">@lang('Causas directas del problema'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.direct_causes_problems? dataShow.direct_causes_problems.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Name')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(directCauseProblem, key) in dataShow.direct_causes_problems" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ directCauseProblem.name }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>
      
         <dt class="text-inverse col-3">@lang('Causas indirectas del problema'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.indirect_causes_problems? dataShow.indirect_causes_problems.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Name')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(indirectCauseProblem, key) in dataShow.indirect_causes_problems" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ indirectCauseProblem.name }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dt class="text-inverse col-3">@lang('Efectos directos del problema'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.direct_effects_problems? dataShow.direct_effects_problems.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Name')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(directEffectsProblems, key) in dataShow.direct_effects_problems" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ directEffectsProblems.name }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dt class="text-inverse col-3">@lang('Causas indirectas del problema'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.indirect_effects_problems? dataShow.indirect_effects_problems.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Name')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(indirectEffectsProblems, key) in dataShow.indirect_effects_problems" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ indirectEffectsProblems.name }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

                  <!-- Description Problem Need Field -->
         <dt class="text-inverse col-3">@lang('Descripción del proyecto'):</dt>
         <dd class="col-9">@{{ dataShow.project_description }}</dd>


                  <!-- Description Problem Need Field -->
         <dt class="text-inverse col-3">@lang('Justificación'):</dt>
         <dd class="col-9">@{{ dataShow.justification }}</dd>


                  <!-- Description Problem Need Field -->
         <dt class="text-inverse col-3">@lang('Antecedentes'):</dt>
         <dd class="col-9">@{{ dataShow.background }}</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Objetivos e indicadores:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <dt class="text-inverse col-3">@lang('General objective'):</dt>
         <dd class="col-9">@{{ dataShow.general_objective }}</dd>

         <dt class="text-inverse col-3">@lang('Overall goal'):</dt>
         <dd class="col-9">@{{ dataShow.overall_goal }}</dd>

         <dt class="text-inverse col-3">@lang('Specific objectives'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.specific_objectives? dataShow.specific_objectives.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Description')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(specificObjective, key) in dataShow.specific_objectives" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ specificObjective.description }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dt class="text-inverse col-3">@lang('Indicadores de seguimiento'):</dt>
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.monitoring_indicators? dataShow.monitoring_indicators.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('Indicator type')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Formula')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(monitoringIndicator, key) in dataShow.monitoring_indicators" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ monitoringIndicator.indicator_type_name }}</td>
                        <td>@{{ monitoringIndicator.description }}</td>
                        <td>@{{ monitoringIndicator.formula }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Info. Armonización tarifaria:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.information_tariff_harmonizations? dataShow.information_tariff_harmonizations.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="2">Servicio Acueducto</th>
                     </tr>
                     <tr>
                        <th>@lang('Activity')</th>
                        <th>@lang('Unit')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(tariffHarmonization, key) in dataShow.information_tariff_harmonizations" :key="key">
                        <td>@{{ tariffHarmonization.activity_name }}</td>
                        <td>@{{ tariffHarmonization.unit }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0">
                  <thead class="text-center">
                     <tr>
                        <th colspan="2">Tipo de inversión (Aplica Acueducto y Alcantarillado)</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Reposición</td>
                        <td class="text-center"><span v-if="dataShow.replacement">X</span></td>
                     </tr>
                     <tr>
                        <td>Expansión</td>
                        <td class="text-center"><span v-if="dataShow.expansion">X</span></td>
                     </tr>
                     <tr>
                        <td>Rehabilitación</td>
                        <td class="text-center"><span v-if="dataShow.rehabilitation">X</span></td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0">
                  <thead class="text-center">
                     <tr>
                        <th colspan="2">Meta (Aplica Acueducto y Alcantarillado)</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Cobertura</td>
                        <td class="text-center"><span v-if="dataShow.coverage">X</span></td>
                     </tr>
                     <tr>
                        <td>Continuidad</td>
                        <td class="text-center"><span v-if="dataShow.continuity">X</span></td>
                     </tr>
                     <tr>
                        <td>Índice de Riesgo de Calidad de Agua IRCA</td>
                        <td class="text-center"><span v-if="dataShow.irca_water_quality_risk_index">X</span></td>
                     </tr>
                     <tr>
                        <td>Micromedición</td>
                        <td class="text-center"><span v-if="dataShow.micrometer">X</span></td>
                     </tr>
                     <tr>
                        <td>Índice de Agua No Contabilizada IANC</td>
                        <td class="text-center"><span v-if="dataShow.ianc_unaccounted_water_index">X</span></td>
                     </tr>
                     <tr>
                        <td>IPUFi - Índice de pérdidas por Usuario Facturado</td>
                        <td class="text-center"><span v-if="dataShow.ipufi_loss_index_billed_user">X</span></td>
                     </tr>
                     <tr>
                        <td>ICUFi - Índice de Agua Consumida por Usuario Facturado</td>
                        <td class="text-center"><span v-if="dataShow.icufi_index_water_consumed_user">X</span></td>
                     </tr>
                     <tr>
                        <td>ISUFi - Índice de Suministro por Usuario Facturado</td>
                        <td class="text-center"><span v-if="dataShow.isufi_supply_index_billed_user">X</span></td>
                     </tr>
                     <tr>
                        <td>CCPi - Consumo corregido por pérdidas</td>
                        <td class="text-center"><span v-if="dataShow.ccpi_consumption_corrected_losses">X</span></td>
                     </tr>
                     <tr>
                        <td>Presión</td>
                        <td class="text-center"><span v-if="dataShow.pressure">X</span></td>
                     </tr>
                     <tr>
                        <td>Índice de tratamiento de Vertimientos</td>
                        <td class="text-center"><span v-if="dataShow.discharge_treatment_index">X</span></td>
                     </tr>
                     <tr>
                        <td>Toneladas DBO Removidas</td>
                        <td class="text-center"><span v-if="dataShow.tons_bbo_removed">X</span></td>
                     </tr>
                     <tr>
                        <td>Toneladas SST Removidas</td>
                        <td class="text-center"><span v-if="dataShow.tons_sst_removed">X</span></td>
                     </tr>
                     <tr>
                        <td>Índice de Reclamación Operativa</td>
                        <td class="text-center"><span v-if="dataShow.operational_claim_index">X</span></td>
                     </tr>
                     <tr>
                        <td>Índice de Reclamación Comercial</td>
                        <td class="text-center"><span v-if="dataShow.commercial_claim_index">X</span></td>
                     </tr>
                     <tr>
                        <td>Eficiencia en el recaudo</td>
                        <td class="text-center"><span v-if="dataShow.efficiency_collection">X</span></td>
                     </tr>
                     <tr>
                        <td>Otra meta</td>
                        <td class="text-center"><span v-if="dataShow.another_goal">X</span></td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0">
                  <thead class="text-center">
                     <tr>
                        <th colspan="2">Fuentes de Inversión</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Vía Tarifas de Acueducto y Alcantarillado</td>
                        <td class="text-center"><span v-if="dataShow.via_aqueduct_sewerage_rates">X</span></td>
                     </tr>
                     <tr>
                        <td>Recursos tarifa de Aseo</td>
                        <td class="text-center"><span v-if="dataShow.cleaning_fee_resources">X</span></td>
                     </tr>
                     <tr>
                        <td>Regalías</td>
                        <td class="text-center"><span v-if="dataShow.regalias">X</span></td>
                     </tr>
                     <tr>
                        <td>Sistema General de Participación</td>
                        <td class="text-center"><span v-if="dataShow.general_participation_system">X</span></td>
                     </tr>
                     <tr>
                        <td>Entidad descentralizada</td>
                        <td class="text-center"><span v-if="dataShow.decentralized_entity">X</span></td>
                     </tr>
                     <tr>
                        <td>Capital aportado Bajo Condición</td>
                        <td class="text-center"><span v-if="dataShow.capital_contributed">X</span></td>
                     </tr>
                     <tr>
                        <td>Capital aportado Entidades Oficiales o Territoriales </td>
                        <td class="text-center"><span v-if="dataShow.contributed_capital_official">X</span></td>
                     </tr>
                     <tr>
                        <td>Aportes de Capital</td>
                        <td class="text-center"><span v-if="dataShow.capital_contributions">X</span></td>
                     </tr>
                     <tr>
                        <td>Aportes de Terceros</td>
                        <td class="text-center"><span v-if="dataShow.third_party_contributions">X</span></td>
                     </tr>
                     <tr>
                        <td>Deuda a nivel Nacional</td>
                        <td class="text-center"><span v-if="dataShow.national_debt">X</span></td>
                     </tr>
                     <tr>
                        <td>Deuda a nivel Extranjero</td>
                        <td class="text-center"><span v-if="dataShow.foreign_debt">X</span></td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.supporting_study_data? dataShow.supporting_study_data.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="7">Datos del estudio soporte</th>
                     </tr>
                     <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Study date')</th>
                        <th>@lang('Author')</th>
                        <th>@lang('State')</th>
                        <th>@lang('Storage place')</th>
                        <th>@lang('Support study type')</th>
                        <th>@lang('Product of a consultancy')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(studyData, key) in dataShow.supporting_study_data" :key="key">
                        <td>@{{ studyData.name }}</td>
                        <td>@{{ studyData.study_date }}</td>
                        <td>@{{ studyData.author }}</td>
                        <td>@{{ studyData.state_name }}</td>
                        <td>@{{ studyData.storage_place }}</td>
                        <td>@{{ studyData.support_study_type_name }}</td>
                        <td>@{{ studyData.product_consultancy }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.selection_alternatives? dataShow.selection_alternatives.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="3">Selección de alternativas</th>
                     </tr>
                     <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Selected')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(alternative, key) in dataShow.selection_alternatives" :key="key">
                        <td>@{{ alternative.alternative_name }}</td>
                        <td>@{{ alternative.description }}</td>
                        <td><span v-if="alternative.selected == 1">X</span></td>
                     </tr>
                  </tbody>
            </table>
         </dt>
         
         <dd class="col-12">
            <table class="table table-bordered m-b-0">
                  <thead class="text-center">
                     <tr>
                        <th colspan="4">Beneficios del proyecto</th>
                     </tr>
                     <tr>
                        <th>@lang('Social')</th>
                        <th>@lang('Environmental')</th>
                        <th>@lang('Economical')</th>
                        <th>@lang('Jobs to generate')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>@{{ dataShow.social }}</td>
                        <td>@{{ dataShow.environmental }}</td>
                        <td>@{{ dataShow.economical }}</td>
                        <td>@{{ dataShow.jobs_to_generate }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Impactos ambientales:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.environmental_impacts? dataShow.environmental_impacts.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>@lang('Environmental component')</th>
                        <th>@lang('Description')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(environmentalImpacts, key) in dataShow.environmental_impacts" :key="key">
                        <td>@{{ environmentalImpacts.environmental_component_name }}</td>
                        <td>@{{ environmentalImpacts.impact_description }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dt class="text-inverse col-3">@lang('Requires environmental license'):</dt>
         <dd class="col-9">@{{ dataShow.requires_environmental_license }}</dd>

         <dt class="text-inverse col-3">@lang('License number'):</dt>
         <dd class="col-9">@{{ dataShow.license_number }}</dd>

         <dt class="text-inverse col-3">@lang('Expedition date'):</dt>
         <dd class="col-9">@{{ dataShow.expedition_date }}</dd>
         

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Presupuesto alternativo:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <dd class="col-12" v-if="dataShow.alternative_budgets? dataShow.alternative_budgets.length > 0 : ''">
            <table class="table table-bordered m-b-0" v-if="dataShow.alternative_budgets? dataShow.alternative_budgets[0].budgets.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="13">Presupuesto alternativa seleccionada para la vigencia actual</th>
                     </tr>
                     <tr>
                        <th class="align-middle" rowspan="2">#</th>
                        <th class="align-middle" rowspan="2">@lang('Description')</th>
                        <th class="align-middle" rowspan="2">@lang('Unit')</th>
                        <th class="align-middle" rowspan="2">@lang('Quantity')</th>
                        <th class="align-middle" rowspan="2">@lang('Unit value')</th>
                        <th class="align-middle" rowspan="2">@lang('Total value')</th>
                        <th class="align-middle" colspan="6">@lang('Distribución del costo final por servicio')</th>
                     </tr>
                     <tr>
                        <th>@lang('Aqueduct')</th>
                        <th>% @lang('Aqueduct')</th>
                        <th>@lang('Sewerage')</th>
                        <th>% @lang('Sewerage')</th>
                        <th>@lang('Cleanliness')</th>
                        <th>% @lang('Cleanliness')</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(budget, key) in dataShow.alternative_budgets[0].budgets" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ budget.description }}</td>
                        <td>@{{ budget.unit }}</td>
                        <td>@{{ budget.quantity }}</td>
                        <td>@{{ budget.unit_value? '$ '+currencyFormat(budget.unit_value): '' }}</td>
                        <td>@{{ budget.total_value? '$ '+currencyFormat(budget.total_value): '' }}</td>
                        <td>@{{ budget.aqueduct? '$ '+currencyFormat(budget.aqueduct): '' }}</td>
                        <td>@{{ budget.percentage_aqueduct }}</td>
                        <td>@{{ budget.sewerage? '$ '+currencyFormat(budget.sewerage): '' }}</td>
                        <td>@{{ budget.percentage_sewerage }}</td>
                        <td>@{{ budget.cleanliness? '$ '+currencyFormat(budget.cleanliness): '' }}</td>
                        <td>@{{ budget.percentage_cleanliness }}</td>
                     </tr>
                     <tr class="table-secondary font-weight-bold">
                        <td colspan="5">Costo total directo</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_cost? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_direct_cost): '' }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_aqueduct? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_direct_aqueduct): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_percentage_aqueduct }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_sewerage? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_direct_sewerage): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_percentage_sewerage }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_cleanliness? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_direct_cleanliness): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_direct_percentage_cleanliness }}</td>
                     </tr>
                     <tr v-for="(typesCosts, key) in dataShow.alternative_budgets[0].budget_types_costs">
                        <td colspan="5">@{{ typesCosts.cost_type_name }}</td>
                        
                        <td>@{{ typesCosts.total_value? '$ '+currencyFormat(typesCosts.total_value): '' }}</td>
                        
                        <td>@{{ typesCosts.aqueduct? '$ '+currencyFormat(typesCosts.aqueduct): '' }}</td>
                        <td>@{{ typesCosts.percentage_aqueduct }}</td>
                        
                        <td>@{{ typesCosts.sewerage? '$ '+currencyFormat(typesCosts.sewerage): '' }}</td>
                        <td>@{{ typesCosts.percentage_sewerage }}</td>
                        
                        <td>@{{ typesCosts.cleanliness? '$ '+currencyFormat(typesCosts.cleanliness): '' }}</td>
                        <td>@{{ typesCosts.percentage_cleanliness }}</td>
                     </tr>
                     <tr class="table-secondary font-weight-bold">
                        <td colspan="5">Costo total del proyecto</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_project_cost? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_project_cost): '' }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_project_aqueduct? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_project_aqueduct): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_project_percentage_aqueduct }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_project_sewerage? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_project_sewerage): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_project_percentage_sewerage }}</td>
                        
                        <td>@{{ dataShow.alternative_budgets[0].total_project_cleanliness? '$ '+currencyFormat(dataShow.alternative_budgets[0].total_project_cleanliness): '' }}</td>
                        <td>@{{ dataShow.alternative_budgets[0].total_project_percentage_cleanliness }}</td>
                     </tr>
                  </tbody>
            </table>
         </dt>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Cronogramas:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.resource_schedule_current_terms? dataShow.resource_schedule_current_terms.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="50">Cronograma de ejecución de las actividades a ejecutarse con los recursos asignados para la vigencia en curso</th>
                     </tr>
                     <tr>
                        <th>#</th>
                        <th>@lang('Description')</th>
                        <th colspan="4">Ene</th>
                        <th colspan="4">Feb</th>
                        <th colspan="4">Mar</th>
                        <th colspan="4">Abr</th>
                        <th colspan="4">May</th>
                        <th colspan="4">Jun</th>
                        <th colspan="4">Jul</th>
                        <th colspan="4">Ago</th>
                        <th colspan="4">Sep</th>
                        <th colspan="4">Oct</th>
                        <th colspan="4">Nov</th>
                        <th colspan="4">Dic</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(scheduleCurrent, key) in dataShow.resource_schedule_current_terms" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ scheduleCurrent.description }}</td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ene - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ene - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ene - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ene - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>
                        
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Feb - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Feb - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Feb - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Feb - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Mar - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Mar - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Mar - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Mar - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Abr - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Abr - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Abr - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Abr - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("May - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("May - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("May - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("May - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jun - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jun - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jun - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jun - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jul - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jul - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jul - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Jul - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ago - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ago - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ago - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Ago - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Sep - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Sep - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Sep - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Sep - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Oct - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Oct - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Oct - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Oct - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Nov - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Nov - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Nov - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Nov - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Dic - Sem") && JSON.stringify(scheduleCurrent.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Dic - Sem") && JSON.stringify(scheduleCurrent.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Dic - Sem") && JSON.stringify(scheduleCurrent.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(scheduleCurrent.week).includes("Dic - Sem") && JSON.stringify(scheduleCurrent.week).includes("4") ? "bg-secondary" : ""'></td>
                        
                     </tr>
                  </tbody>
            </table>
         </dt>

         <dd class="col-12">
            <table class="table table-bordered m-b-0" v-if="dataShow.schedule_resources_previous_periods? dataShow.schedule_resources_previous_periods.length > 0 : ''">
                  <thead class="text-center">
                     <tr>
                        <th colspan="50">Cronograma de ejecución de las actividades de gestión o que vienen de la vigencias anteriores y que contribuyen al cumplimiento de las metas propuestas para la vigencia</th>
                     </tr>
                     <tr>
                        <th>#</th>
                        <th>@lang('Description')</th>
                        <th colspan="4">Ene</th>
                        <th colspan="4">Feb</th>
                        <th colspan="4">Mar</th>
                        <th colspan="4">Abr</th>
                        <th colspan="4">May</th>
                        <th colspan="4">Jun</th>
                        <th colspan="4">Jul</th>
                        <th colspan="4">Ago</th>
                        <th colspan="4">Sep</th>
                        <th colspan="4">Oct</th>
                        <th colspan="4">Nov</th>
                        <th colspan="4">Dic</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(previousPeriods, key) in dataShow.schedule_resources_previous_periods" :key="key">
                        <td>@{{ key + 1 }}</td>
                        <td>@{{ previousPeriods.description }}</td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Ene - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ene - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ene - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ene - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>
                        
                        <td :class='JSON.stringify(previousPeriods.week).includes("Feb - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Feb - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Feb - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Feb - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Mar - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Mar - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Mar - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Mar - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Abr - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Abr - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Abr - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Abr - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("May - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("May - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("May - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("May - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Jun - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jun - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jun - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jun - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Jul - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jul - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jul - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Jul - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Ago - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ago - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ago - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Ago - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Sep - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Sep - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Sep - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Sep - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Oct - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Oct - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Oct - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Oct - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Nov - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Nov - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Nov - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Nov - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>

                        <td :class='JSON.stringify(previousPeriods.week).includes("Dic - Sem") && JSON.stringify(previousPeriods.week).includes("1") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Dic - Sem") && JSON.stringify(previousPeriods.week).includes("2") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Dic - Sem") && JSON.stringify(previousPeriods.week).includes("3") ? "bg-secondary" : ""'></td>
                        <td :class='JSON.stringify(previousPeriods.week).includes("Dic - Sem") && JSON.stringify(previousPeriods.week).includes("4") ? "bg-secondary" : ""'></td>
                     </tr>
                  </tbody>
            </table>
         </dt>
      </div>
   </div>
   <!-- end panel-body -->
</div>


<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Área de influencia del proyecto:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <dd class="col-9">
            <table class="table table-bordered m-b-0" v-if="dataShow.project_areas_influences? dataShow.project_areas_influences.length > 0 : ''">
                  <thead>
                     <tr>
                        <th>Ciudad</th>
                        <th>Área de cobertura del servicio</th>
                        <th>Barrio</th>
                        <th>Comuna</th>
                        <th>Número de habitantes</th>


                     </tr>
                  </thead>
                  <tbody>
                     <tr v-for="(projectAreasInfluences, key) in dataShow.project_areas_influences" :key="key">
                        <td>@{{ projectAreasInfluences.municipal_name }}</td>
                        <td>@{{ projectAreasInfluences.service_name }}</td>
                        <td>@{{ projectAreasInfluences.neighborhood }}</td>
                        <td>@{{ projectAreasInfluences.commune }}</td>
                        <td>@{{ projectAreasInfluences.number_inhabitants }}</td>

                     </tr>
                  </tbody>
            </table>
         </dt>

      </div>
   </div>
   <!-- end panel-body -->
</div>
