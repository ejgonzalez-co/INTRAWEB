<div class="table-striped">
    <table-component id="goals-table" :data="advancedSearchFilterPaginate()" sort-by="goals" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="goal_name" label="@lang('Meta')"></table-column>
        <table-column show="indicator_description" label="@lang('Descripción del indicador')"></table-column>
        <table-column show="commitment_date" label="@lang('Fecha del compromiso')">
            <template slot-scope="row">
                <p v-if="row.commitment_date < new Date().toISOString()" style="color: #F00; font-weight: bold;">
                @{{ formatDate(row.commitment_date) }}
                </p>
                <p v-else style="color: rgb(51, 204, 61); font-weight: bold;">
                @{{ formatDate(row.commitment_date) }}</p>
            </template>
        </table-column>
        <table-column show="goal_weight" label="Porcentaje de contribución de esta Meta al cumplimiento de la oportunidad de mejora">
            <template slot-scope="row">
                <p>@{{ row.goal_weight ? row.goal_weight + "%" : "" }}</p>
            </template>
        </table-column>
        <table-column show="quantity_progress_pending" label="@lang('Cantidad de avances por revisar')">
            <template slot-scope="row">
                <p>@{{ row.quantity_progress_pending ? row.quantity_progress_pending + "" : '0' }}</p>
            </template>
        </table-column>
        {{-- <table-column show="percentage_execution" label="@lang('% de ejecución')">
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
                <button v-if="row.can_edit_goal != 'Aprobado' || row.status_modification == 'Aprobado'" @click="edit(row)" data-backdrop="static" data-target="#modal-form-goals" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="@lang('crud.edit')">
                <i class="fas fa-pencil-alt"></i>
                </button>
                <button v-if="row.can_edit_goal != 'Aprobado' || row.status_modification == 'Aprobado'" @click="edit(row)" data-backdrop="static" data-target="#modal-form-assigning-responsible-activity" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="@lang('Gestionar responsables')">
                <i class="fas fa-users"></i>
                </button>
                @if(!Auth::user()->hasRole('Registered'))     
                    <a :href="'goal-progresses?goal=' + row.encrypted_id" v-if="row.can_edit_goal == 'Aprobado'">
                        <button @click="edit(row)" class="btn btn-white btn-icon btn-md" title="@lang('Avances')">
                            <i class="fas fa-newspaper"></i>
                        </button>
                    </a>
            


                    @else
                    <a :href="'goal-progresses?goal=' + row.encrypted_id">
                        <button @click="edit(row)" class="btn btn-white btn-icon btn-md" title="@lang('Avances')">
                            <i class="fas fa-newspaper"></i>
                        </button>
                    </a>
                @endif

                <button @click="show(row)" data-target="#modal-view-goals" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
