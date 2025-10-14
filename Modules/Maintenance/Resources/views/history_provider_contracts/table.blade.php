<div class="table-responsive">
    <table-component id="historyProviderContracts-table" :data="advancedSearchFilterPaginate()"
        sort-by="historyProviderContracts" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="created_at" label="Fecha de modificación"></table-column>
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column show="observation" label="@lang('Observation')"></table-column>
        <table-column show="name_user" label="Nombre de usuario"></table-column>
        <table-column show="contract_number" label="Número de contrato"></table-column>
        <table-column show="type_contract" label="Tipo de contrato"></table-column>
        <table-column show="condition" label="Estado"></table-column>
        <table-column show="provider" label="Proveedor"></table-column>
        <table-column show="object" label="Objeto"></table-column>        
        <table-column show="manager_dependencia" label="Supervisor del contrato"></table-column>
        <table-column show="dependencias.nombre" label="Dependencia del supervisor"></table-column>
        <table-column show="value_contract" label="@lang('Value Contract')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row.value_contract)}}
            </template>
        </table-column>
        <table-column show="cd_avaible" label="Cdp disponible">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row.cd_avaible)}}
            </template>
        </table-column>
    </table-component>
</div>
