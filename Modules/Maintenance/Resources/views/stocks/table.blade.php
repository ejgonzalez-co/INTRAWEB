<div class="table-responsive">
    <table-component id="stocks-table" :data="dataList" sort-by="stocks" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="codigo" label="@lang('Código ')"></table-column>
        <table-column show="articulo" label="@lang('Artículo')"></table-column>
        <table-column show="grupo" label="@lang('Grupo')"></table-column>
        <table-column show="cantidad" label="@lang('Cantidad')"></table-column>
        <table-column show="unidad_medida" label="@lang('Unidad medida')"></table-column>
        <table-column show="costo_unitario" label="@lang('Costo Unitario (IVA incluido)')">
            <template slot-scope="row">
                <p>@{{ "$" + currencyFormat(row.unit_cost) }}</p>
            </template>
        </table-column>
        <table-column show="total" label="@lang('Total')">
            <template slot-scope="row">
                <p>@{{ "$" + currencyFormat(row.total_value) }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-stocks" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-history-stock" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                    <i class="fa fa-history"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
