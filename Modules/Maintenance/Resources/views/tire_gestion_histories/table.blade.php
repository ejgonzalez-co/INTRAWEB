<div class="table-responsive">
    <table-component
        id="tireGestionHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tireGestionHistories"
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
            <table-column show="created_at" label="Fecha de registro"></table-column>    
            <table-column show="action" label="Acción"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column show="name_user" label="Nombre del usuario"></table-column>
            <table-column show="plaque" label="@lang('Plaque')"></table-column>
            <table-column show="dependencia" label="Proceso"></table-column>
            <table-column show="equipment" label="Nombre del equipo o maquinaria"></table-column>

    </table-component>
</div>