<div class="table-responsive text-center">
    <table-component
        id="tireQuantitites-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tireQuantitites"
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
        <table-column show="id" label="#"></table-column>
            <table-column show="dependencies.nombre" label="@lang('process')"></table-column>
            <table-column show="resume_machinery_vehicles_yellow.name_vehicle_machinery" label="@lang('Name of the equipment or machinery')"></table-column>
            <table-column show="resume_machinery_vehicles_yellow.plaque" label="@lang('Plaque')"></table-column>
            <table-column show="tire_quantity" label="@lang('tire_quantity')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-tireQuantitites" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(row)" data-target="#modal-delete-provider-contract" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="@lang('drop')">
                <i class="fa fa-trash"></i>
                </button>
                @endif
                
                <a class="btn btn-white btn-icon btn-md" :href="'tire-informations?tire_id='+row.id" title="Información de la llanta"><i class="fas fa-cog"></i></a>
                
                
            </template>
        </table-column>
    </table-component>
</div>