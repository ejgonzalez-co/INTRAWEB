<div class="table-responsive">
    <table-component
        id="fuelHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="fuelHistories"
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
            <table-column show="action" label="Acción"></table-column>
            <table-column show="user_name" label="Nombre usuario"></table-column>
            <table-column show="plaque" label="@lang('Plaque')"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column show="date_register" label="Fecha de creación del registro modificado"></table-column>
    </table-component>
</div>