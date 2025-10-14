<div class="table-responsive">
    <table-component
        id="tireInformationHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tireInformationHistories"
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
            <table-column show="user_name" label="Nombre del usuario"></table-column>
            <table-column show="action" label="Acción"></table-column>
            <table-column show="plaque" label="@lang('Plaque')"></table-column>
            <table-column show="dependencia" label="Proceso"></table-column>
            <table-column show="descripcion" label="@lang('Description')"></table-column>
            <table-column show="assignment_type" label="Tipo de asignación"></table-column>
            <table-column show="code" label="Código de la llanta"></table-column>
            <table-column show="position" label="Posición"></table-column>
            <table-column show="observation" label="Observación"></table-column>
            <table-column show="status" label="estado"></table-column>
            <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
                <template slot-scope="row">
                    <button @click="callFunctionComponent('input-search','sendDocuments',row.id + '-llanta')" data-backdrop="static"
                         data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-circle"></i>
                    </button>
                </template>
            </table-column>
    </table-component>
</div>