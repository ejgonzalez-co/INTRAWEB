<div class="table-responsive">
    <table-component
        id="fuelEquipmentHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="fuelEquipmentHistories"
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
            <table-column show="date_register" label="@lang('Date Register')"></table-column>
            <table-column show="action" label="Acción"></table-column>
            <table-column show="name_user" label="Nombre de usuario"></table-column>
            <table-column show="description" label="Descripción"></table-column>
            <table-column show="dependencia" label="Dependencia"></table-column>
        
    </table-component>
</div>