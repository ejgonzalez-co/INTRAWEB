<div class="table-responsive">
    <table-component
        id="p-q-r-tipo-solicituds-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="p-q-r-tipo-solicituds"
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
        <table-column show="nombre" label="@lang('Nombre')"></table-column>

                    <table-column show="descripcion" label="@lang('Descripción')"></table-column>

                    <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-p-q-r-tipo-solicituds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-p-q-r-tipo-solicituds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>