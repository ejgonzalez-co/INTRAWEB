<div class="table-responsive">
    <table-component id="closedImprovementPlans-table" :data="advancedSearchFilterPaginate()"
        sort-by="closedImprovementPlans" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="evaluator_id" label="@lang('Fecha de creación')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.created_at) }}</p>
            </template>
        </table-column>
        <table-column show="no_improvement_plan" label="@lang('Número Plan de mejoramiento')"></table-column>
        <table-column show="name_improvement_plan" label="@lang('Plan de mejoramiento')"></table-column>

        <table-column show="evaluator" label="@lang('Funcionario Evaluado')">
            <template slot-scope="row">
                <p>@{{ row.evaluated ? row.evaluated.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="status_improvement_plan" label="@lang('Estado')">
            <template slot-scope="row">
                <p v-if="row.status_improvement_plan == 'Pendiente'" class="button__status-pending p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Revisión del plan de mejoramiento'" class="button__status-in_review  p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Aprobado'" class="button__status-approved p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Cerrado cumplido'" class="button__status-approved p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Cerrado no cumplido'" class="button__status-pending_approval p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Devuelto'" class="button__status-pending_approval p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Declinado'" class="button__status-cancelled p-5">
                    @{{ row.status_improvement_plan }}</p>
                 <p v-if="row.status_improvement_plan == 'Solicitud de modificación'" class="button__status-assigned p-5">@{{ row.status_improvement_plan }}</p>

            </template>
        </table-column>
        {{-- <table-column show="percentage_execution" label="@lang('Porcentaje Ejec')">
            <template slot-scope="row">
                <p>@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : '0%' }}</p>
            </template>
        </table-column> --}}

        <table-column show="percentage_execution" label="@lang('% de Ejecución')">
            <template slot-scope="row">
                <div class="progress" style="width: 100px;" v-cloak>
                    <div class="progress-bar" role="progressbar" :style="'width: ' + row.percentage_execution + '%;'" :aria-valuenow="row.percentage_execution" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <p v-cloak>@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : "0%" }}</p>
            </template>
        </table-column>
        

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <button v-if="row.status_improvement_plan != 'Activo' && row.status_improvement_plan != 'Cerrado cumplido' && row.status_improvement_plan != 'Cerrado cancelación'" @click="edit(row)" data-backdrop="static"
                    data-target="#modal-form-closedImprovementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('Cierre plan de mejoramiento')">
                    <i class="fas fa-check"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-closedImprovementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <a :href="'export-improvement-plan-closed/' + row.id">
                    <button class="btn btn-white btn-icon btn-md" title="@lang('Descargar reporte')">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                </a>

                {{-- <div class="dropdown">
                    <button class="btn btn-white btn-icon btn-md" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="@lang('Descargar reporte')">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" :href="'export-improvement-plan-closed/' + row.id + '?reporte=evaluacion'">Evaluación</a>
                        <a class="dropdown-item" :href="'export-improvement-plan-closed/' + row.id + '?reporte=plan_mejoramiento'">Plan de mejoramiento</a>
                        <a class="dropdown-item" :href="'export-improvement-plan-closed/' + row.id + '?reporte=cierre_final'">Cierre final del mismo</a>
                    </div>
                </div> --}}

            </template>
        </table-column>
    </table-component>
</div>
