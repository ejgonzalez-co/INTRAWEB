<div class="table-responsive">
    <table-component
        id="fuelConsumptionHistoryMinors-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="fuelConsumptionHistoryMinors"
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
            <table-column show="created_at" label="Fecha de registro">
                <template slot-scope="row">
                    @{{ formatDate(row.created_at) }}
                </template>
            </table-column>
            <table-column show="action" label="Acción"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column show="name_user" label="Nombre del usuario"></table-column>
            <table-column show="dependencia" label="Proceso"></table-column>
            <table-column show="fuel_equipment_consumption" label="Descripción del equipo"></table-column>

    </table-component>
</div>