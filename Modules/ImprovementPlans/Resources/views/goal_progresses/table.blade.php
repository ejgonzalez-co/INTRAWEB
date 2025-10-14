<div class="table-responsive">
    <table-component id="goalProgresses-table" :data="advancedSearchFilterPaginate()" sort-by="goalProgresses"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="created_at" label="Fecha de creación">
            <template slot-scope="row">
                <p>@{{ row.created_at ? row.created_at : "" }}</p>
            </template>created_at
        </table-column>
        <table-column show="user_id" label="Responsable">
            <template slot-scope="row">
                <p>@{{ row.creator_user ? row.creator_user.name : "" }}</p>
            </template>created_at
        </table-column>
        <table-column show="pm_goal_activities_id" label="@lang('Nombre de la actividad')">
            <template slot-scope="row">
                <p>@{{ row.goal_activities ? row.goal_activities.activity_name : "" }}</p>
            </template>
        </table-column>z
        <table-column show="activity_weigth" label="@lang('Peso de la actividad')">
            <template slot-scope="row">
                <p>@{{ row.activity_weigth ? row.activity_weigth + "%" : "" }}</p>
            </template>
        </table-column>
        <table-column show="goal_activities" label="@lang('Brecha para cumplimiento de la meta')">
            <template slot-scope="row">
                <p>@{{ row?.goal_activities.goal_type == 'Cuantitativa' ? row.goal_activities.gap_meet_goal : "No aplica" }}</p>
            </template>
        </table-column>
        <table-column show="progress_weigth" label="@lang('Cantidad del avance')">
            <template slot-scope="row">
                <p v-if="row.goal_activities.goal_type != 'Cualitativa'">@{{ row.progress_weigth ? currencyFormat(row.progress_weigth) + "" : "" }}</p>
                <p v-else>No Aplica</p>
            </template>
        </table-column>

        <table-column show="percentage_execution" label="@lang('Peso del avance')">
            <template slot-scope="row">
                <p v-if="row.goal_activities.goal_type != 'Cualitativa'">@{{ row.percentage_execution ? currencyFormat(row.percentage_execution) + "%" : "" }}</p>
                <p v-else>No Aplica</p>
            </template>
        </table-column>

        {{-- <table-column show="progress_weigth" label="@lang('Peso del avance')">
            <template slot-scope="row">
                <!-- Calcular el porcentaje de progreso en relación con el peso de la actividad -->
                <div class="progress" style="width: 200px; margin-top: 10px;">
                    <div class="progress-bar" role="progressbar" :style="'width: ' + ((row.progress_weigth * 100) / row.activity_weigth) + '%;'" :aria-valuenow="((row.progress_weigth * 100) / row.activity_weigth)" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
        
                <!-- Mostrar el valor calculado del progreso en formato de porcentaje -->
                <p v-cloak>
                    @{{ row.progress_weigth ? currencyFormat((row.progress_weigth * 100) / row.activity_weigth) + "%" : "" }}
                </p>
            </template>
        </table-column>
         --}}


        <table-column show="status" label="@lang('Estado')">
            <template slot-scope="row">
                <p v-if="row.status == 'Pendiente'" class="button__status-pending">@{{ row.status ? row.status : "" }}</p>
                <p v-if="row.status == 'Revisión'" class="button__status-pending_approval">@{{ row.status ? row.status : "" }}</p>
                <p v-if="row.status == 'Aprobado'" class="button__status-approved">@{{ row.status ? row.status : "" }}</p>
                <p v-if="row.status == 'Declinado'" class="button__status-cancelled">@{{ row.status }}</p>
                <p v-if="row.status == 'Devuelto'" class="button__status-pending_approval p-5">@{{ row.status }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if (Auth::user()->id != $evaluatorId)
                <button v-if="row.status == 'Pendiente' || row.status == 'Devuelto'" @click="edit(row)" data-backdrop="static"
                    data-target="#modal-form-goalProgresses" data-toggle="modal" class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-goalProgresses" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                
                @if (Auth::user()->id != $evaluatorId)
                <button v-if="row.status === 'Pendiente' || row.status === 'Devuelto'"
                    @click="callFunctionComponent('alert-confirmation','openConfirmationModal',row.id)"
                    class="btn btn-white btn-icon btn-md" title="@lang('Enviar a revisión')">
                    <i class="fas fa-paper-plane"></i>
                </button>
                @endif

                @if (Auth::user()->id == $evaluatorId)
                    <button v-if="row.status == 'Revisión'" @click="edit(row)" data-target="#modal-form-approved-progress" data-toggle="modal"
                        data-toggle="tooltip" data-placement="top" class="btn btn-white btn-icon btn-md"
                        title="@lang('Aprobar/Devolver Avance')">
                        <i class="fas fa-check"></i>
                    </button>
                @endif

                <button v-if="row.status == 'Pendiente'" @click="drop(row[customId])"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
