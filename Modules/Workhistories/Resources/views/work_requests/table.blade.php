<div class="table-responsive">
    <table-component id="workRequests-table" :data="advancedSearchFilterPaginate()" sort-by="workRequests"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4"
        :cache-lifetime="0">

        <table-column show="user_name" label="Nombre del usuario consultado"></table-column>
        <table-column show="consultation_time" label="Tiempo solicitado de consulta"></table-column>
        <table-column show="users.name" label="Nombre del solicitante"></table-column>
        <table-column show="condition" label="Estado de la solicitud">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <!--El v-if de cada div comprueba la condicion para saber que color mostrar -->
                <div v-if="row.condition=='Pendiente'" class="text-black text-center p-2 font-weight-bold"
                    :style="'background-color: yellow'" v-html="row.condition"></div>
                <div v-if="row.condition=='En ejecución'" class="text-black text-center p-2 font-weight-bold"
                    :style="'background-color: orange'" v-html="row.condition"></div>
                <div v-if="row.condition=='Aprobado'" class="text-white text-center p-2 font-weight-bold"
                    :style="'background-color: green'" v-html="row.condition"></div>
                <div v-if="row.condition=='Cancelado'" class="text-black text-center p-2 font-weight-bold"
                    :style="'background-color: red'" v-html="row.condition"></div>
                <div v-if="row.condition=='Finalizado'" class="text-black text-center p-2 font-weight-bold"
                    :style="'background-color: #00A6FF'" v-html="row.condition"></div>

            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                {{-- si se desea habilitar la opcion de editar o eliminar borrar la palabra 'quitar' de los roles --}}
                @if (Auth::user()->hasRole('Gestor hojas de vida quitar'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-workRequests"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                        data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif
                <button @click="show(row)" data-target="#modal-view-workRequests" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                @if (Auth::user()->hasRole('Gestor hojas de vida quitar'))
                    <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                        data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif

                @if (Auth::user()->hasRole('Administrador historias laborales'))
                    <div v-if="row.condition == 'Pendiente'">
                        <button @click="callFunctionComponent('approve-request','approve',row)"
                            class="btn btn-white btn-icon btn-md" title="Pagar">
                            <i class="fas fa-check-square"></i>
                        </button>
                    </div>
                @endif
            </template>
        </table-column>
    </table-component>
</div>
