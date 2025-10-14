<div class="table-responsive">
    <table-component id="addition-spare-part-activities-table" :data="advancedSearchFilterPaginate()"
        sort-by="addition-spare-part-activities" sort-order="asc" table-class="table table-hover m-b-0"
        :show-filter="false" :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">

        <table-column show="id" label="ID"></table-column>

        <table-column show="created_at" label="@lang('Fecha de registro')"></table-column>

        <table-column show="admin_creator_id" label="@lang('Usuario creador')">
            <template slot-scope="row">
                <p>@{{ row.admin_creator ? row.admin_creator.name : row.provider_creator.name }}</p>
            </template>
        </table-column>

        <table-column show="status" label="@lang('Estado')">
            <template slot-scope="row">

                <span v-if="row.status == 'En trámite'" style="color: rgb(255, 255, 255); background-color: #ff9901; padding:5px 10px; border: none;border-radius:5px;">En trámite</span>
                <span v-if="row.status == 'En trámite devuelto'" style="color: rgb(255, 255, 255); background-color: #ff4501; padding:5px 10px; border: none;border-radius:5px;">En trámite devuelto</span>
                <span v-else-if="row.status == 'Aprobada'" style="color: rgb(255, 255, 255); background-color: #8cc44a; padding:5px 10px; border: none;border-radius:5px;">Aprobada</span>
                <span v-else-if="row.status == 'Solicitud en asignación de costo'" style="color: rgb(255, 255, 255); background-color: #ffc107; padding:5px 10px; border: none;border-radius:5px;">Solicitud en asignación de costo</span>
                <span v-else-if="row.status == 'Cancelada'" style="color: rgb(255, 255, 255); background-color: #9c9e9d; padding:5px 10px; border: none;border-radius:5px;">Cancelada</span>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if(Auth::check() && Auth::user()->hasRole("Administrador de mantenimientos"))
                    <button v-if="row.status == 'En trámite'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-addition-spare-part-activities-admin" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @else
                    <button v-if="row.status == 'En trámite devuelto' || row.status == 'Solicitud en asignación de costo'" @click="edit(row)" data-backdrop="static"
                        data-target="#modal-form-addition-spare-part-activities" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-addition-spare-part-activities" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
