<div class="table-responsive">
    <table-component
        id="historyCostItems-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="historyCostItems"
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
            <table-column show="created_at" label="Fecha de modificación"></table-column>
            <table-column show="name" label="Acción"></table-column>
            <table-column show="code_cost" label="Código del rubro"></table-column>
            <table-column show="name_cost" label="Nombre del rubro"></table-column>
            <table-column show="cost_center" label="Código centro de costos"></table-column>
            <table-column show="cost_center_name" label="Nombre centro de costos"></table-column>
            <table-column show="value_item" label="Valor del rubro">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.value_item)}}
                </template>
            </table-column>
            <table-column show="name_user" label="Nombre del usuario"></table-column>
            <table-column show="observation" label="Observación"></table-column>
        </table-component>
</div>