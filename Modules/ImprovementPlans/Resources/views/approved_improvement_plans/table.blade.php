<div class="table-responsive">
    <table-component id="approvedImprovementPlans-table"
        :data="dataList"
        sort-by="approvedImprovementPlans"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        :key="keyRefresh">
        <table-column show="evaluator_id" label="@lang('Fecha de creación')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.created_at) }}</p>
            </template>
        </table-column>
        <table-column show="no_improvement_plan" label="@lang('Consecutivo')"></table-column>
        <table-column show="type_name_improvement_plan" label="@lang('Nombre del plan de mejoramiento')"></table-column>
        

        <table-column show="evaluator" label="@lang('Funcionario Evaluado')">
            <template slot-scope="row">
                <p>@{{ row.evaluated ? row.evaluated.name : '' }}</p>
            </template>
        </table-column>

        <table-column show="evaluated" label="@lang('Dependencia')">
            <template slot-scope="row">
                <p>@{{ row.evaluated.dependencies ? row.evaluated.dependencies[0]?.nombre : '' }}</p>
            </template>
        </table-column>
        
        <table-column show="status_improvement_plan" label="@lang('Estado del plan')">
            <template slot-scope="row">
                <p v-if="row.status_improvement_plan == 'Cerrada'" class="button__status-approved p-5">
                    @{{ row.status}}</p> 
                <p v-if="row.status_improvement_plan == 'Pendiente'" class="button__status-pending p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Revisión del plan de mejoramiento'" class="button__status-in_review p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Cerrado cumplido'" class="button__status-approved p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Aprobado'" class="button__status-approved p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Cerrado no cumplido'" class="button__status-pending_approval p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Devuelto'" class="button__status-pending_approval p-5">
                    @{{ row.status_improvement_plan }}</p>
                <p v-if="row.status_improvement_plan == 'Declinado'" class="button__status-cancelled p-5">
                    @{{ row.status_improvement_plan }}</p>

                <p v-if="row.status_improvement_plan == 'Solicitud de modificación'" class="button__status-assigned">@{{ row.status_improvement_plan }}</p>

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

        <table-column show="quantity_progress_pending" label="@lang('Cantidad de avances por revisar')">
            <template slot-scope="row">
                <p>@{{ row.quantity_progress_pending ? row.quantity_progress_pending + "" : '0' }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <a v-if="row.status_improvement_plan === 'Revisión del plan de mejoramiento' || row.status_improvement_plan === 'Aprobado'" :href="'non-conforming-criterias?evaluation=' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" title="Ver oportunidades de mejora"><i
                    class="fas fa-list"></i></button>
                </a>

                <a :href="'annotation-evaluations?evaluation=' + row.encrypted_id">
                    <button class="btn btn-white btn-icon btn-md" title="Ver Anotaciones de la evaluacion"><i
                    class="far fa-comment-dots"></i></button>
                </a>
                
                <button v-if="row.status_improvement_plan == 'Revisión del plan de mejoramiento'" @click="edit(row)" data-backdrop="static"
                    data-target="#modal-form-approvedImprovementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-check"></i>
                </button>


                <button v-if="row.status_improvement_plan == 'Solicitud de modificación'" @click="edit(row)" data-backdrop="static"
                data-target="#modal-form-modification" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="@lang('Procesar modificación del plan de mejoramiento')">
                <i class="fas fa-thumbs-up"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-approvedImprovementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-history-approvedImprovementPlans" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="Historial">
                    <i class="fa fa-history"></i>
                </button>

                <a :href="'export-improvement-plan/' + row.id">
                    <button class="btn btn-white btn-icon btn-md" title="Descargar reporte">
                        <i class="fas fa-chart-bar"></i>
                    </button>
                </a>

            </template>
        </table-column>
    </table-component>
</div>
