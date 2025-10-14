<div class="table-responsive">
    <table-component id="improvementPlans-table" :data="advancedSearchFilterPaginate()" sort-by="improvementPlans"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="created_at" label="@lang('Fecha de creación')"></table-column>
        <table-column show="no_improvement_plan" label="@lang('Consecutivo')"></table-column>
        <table-column show="type_name_improvement_plan" label="@lang('Nombre del plan de mejoramiento')"></table-column>
        <table-column show="quantity_progress_pending" label="@lang('¿Metas vencidas ?')">
            <template slot-scope="row">
                <p v-if="row.quantity_goal == 0" class="button__status-approved p-5">En ejecucion a tiempo</p>
                <p v-else class="button__status-cancelled p-5">En ejecucion vencido</p>
            </template>
        </table-column>
        {{-- <table-column show="evaluation_end_date" label="@lang('Fecha y hora fin evaluación')">
            <template slot-scope="row">
                <p v-if="row.evaluation_end_date < new Date().toISOString()" style="color: #F00; font-weight: bold;">
                @{{ formatDate(row.evaluation_end_date) + " " + row.evaluation_end_time }}
                </p>
                <p v-else style="color: rgb(51, 204, 61); font-weight: bold;">
                @{{ formatDate(row.evaluation_end_date) + " " + row.evaluation_end_time }}</p>
            </template>
        </table-column> --}}
        <table-column show="evaluator" label="@lang('Funcionario Evaluador')">
            <template slot-scope="row">
                <p>@{{ row.evaluator ? row.evaluator.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="status_improvement_plan" label="@lang('Estado del plan')">
            <template slot-scope="row">
                <p v-if="row.status_improvement_plan == 'Cerrado cumplido'" class="button__status-approved p-5">@{{ row.status_improvement_plan}}</p> 
                <p v-if="row.status_improvement_plan == 'Pendiente'" class="button__status-pending p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Revisión del plan de mejoramiento'" class="button__status-in_review p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Aprobado'" class="button__status-approved p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Devuelto'" class="button__status-pending_approval p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Cerrado no cumplido'" class="button__status-pending_approval p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Declinado'" class="button__status-cancelled p-5">@{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Solicitud de modificación'" class="button__status-assigned p-5">@{{ row.status_improvement_plan }}</p>

            </template>
        </table-column>
        {{-- <table-column show="percentage_execution" label="@lang('% de Ejecución')">
            <template slot-scope="row">
                <p>@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : "0%" }}</p>
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

                <a v-if="row.status_improvement_plan == 'Pendiente' || row.status_improvement_plan == 'Aprobado' || row.status_improvement_plan == 'Devuelto'" :href="'non-conforming-criterias?evaluation=' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" title="Ver oportunidades de mejora"><i
                            class="fas fa-list"></i></button>
                </a>


                <button v-if="row.status_improvement_plan === 'Pendiente' || row.status_improvement_plan == 'Devuelto'"
                    @click="callFunctionComponent('alert-confirmation','openConfirmationModal',row.id)"
                    class="btn btn-white btn-icon btn-md" title="@lang('Enviar el plan de mejoramiento a revisión del evaluador')">
                    <i class="fas fa-paper-plane"></i>
                </button>

                <button v-if="row.status_improvement_plan != 'Devuelto'  && row.status_improvement_plan != 'Declinado' && row.status_improvement_plan != 'Pendiente' && row.status_improvement_plan != 'Solicitud de modificación' && row.status_improvement_plan != 'Revisión del plan de mejoramiento' && row.evaluated_id == {{ Auth::user()->id }}" @click="edit(row)" data-backdrop="static"
                data-target="#modal-form-processes-modification" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="@lang('Solicitar modificación del plan de mejoramiento')">
                <i class="fas fa-hand-point-up"></i>
                </button>

                <a v-if="row.evaluated_id == {{ Auth::user()->id }}" :href="'annotation-evaluations?evaluation=' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" title="Ver Anotaciones de la evaluacion"><i
                    class="far fa-comment-dots"></i></button>
                </a>

                <button @click="show(row)" data-target="#modal-view-improvementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-history-improvementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="Historial">
                    <i class="fa fa-history"></i>
                </button>

                <a :href="'export-improvement-plan/' + row.id" v-if="row.evaluated_id == {{ Auth::user()->id }} || row.evaluator_id == {{ Auth::user()->id }}">
                    <button class="btn btn-white btn-icon btn-md" title="@lang('Descargar información')">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </a>

            </template>
        </table-column>
    </table-component>
</div>
