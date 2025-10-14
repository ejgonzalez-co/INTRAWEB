<div class="table-responsive">
    <table-component
        id="tic-maintenances-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tic-maintenances"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
        <table-column show="created_at" label="@lang('Created_at')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ formatDate(row.created_at) }}
            </template>
        </table-column>
        <table-column show="type_maintenance_name" label="@lang('Type Maintenance')"></table-column>
        <table-column show="fault_description" label="@lang('Descripción Falla o Daño')"></table-column>
        <table-column show="service_start_date" label="@lang('Fecha de inicio')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ formatDate(row.service_start_date) }}
            </template>
        </table-column>
        <table-column show="end_date_service" label="@lang('Fecha de terminado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ formatDate(row.end_date_service) }}
            </template>
        </table-column>
        <table-column show="maintenance_status_name" label="@lang('Maintenance Status')"></table-column>
        
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if (Auth::user()->hasRole('Administrador TIC'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-maintenances" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @else
                    <button v-if="row.user_id == {!! Auth::user()->id !!}" @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-maintenances" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-tic-maintenances" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>