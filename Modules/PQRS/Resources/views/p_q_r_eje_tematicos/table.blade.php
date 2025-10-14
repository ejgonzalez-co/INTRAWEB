<div class="table-responsive">
    <table-component
        id="p-q-r-eje-tematicos-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="p-q-r-eje-tematicos"
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
        <table-column show="codigo" label="@lang('codigo')"></table-column>

        <table-column show="nombre" label="@lang('Nombre')"></table-column>

        <table-column show="tipo_plazo" label="@lang('Tipo de plazo')"></table-column>

        <table-column show="plazo" label="@lang('Plazo')"></table-column>

        <table-column show="plazo_unidad" label="@lang('Unidad de plazo')"></table-column>

        <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-p-q-r-eje-tematicos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-p-q-r-eje-tematicos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
