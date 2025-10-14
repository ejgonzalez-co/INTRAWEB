<div class="table-responsive">
    <table-component
        id="historyBudgetAssignations-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="historyBudgetAssignations"
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
            <table-column show="observation" label="@lang('Observation')"></table-column>
            <table-column show="name_user" label="Nombre del usuario"></table-column>
            <table-column show="value_cdp" label="@lang('Value Cdp')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.value_cdp)}}
                </template>
            </table-column>
            <table-column show="value_contract" label="@lang('Value Contract')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.value_contract)}}
                </template>
            </table-column>
            <table-column show="cdp_available" label="Cdp disponible">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.cdp_available)}}
                </template>
            </table-column>
    </table-component>
</div>