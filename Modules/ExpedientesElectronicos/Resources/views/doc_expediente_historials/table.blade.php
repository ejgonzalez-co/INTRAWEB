<div class="table-responsive">
    <table-component
        id="doc-expediente-historials-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="doc-expediente-historials"
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
        <table-column show="accion" label="Acción"></table-column>

        <table-column show="user_name" label="Nombre usuario"></table-column>

        <table-column show="consecutivo" label="Consecutivo"></table-column>

        <table-column show="created_at" label="Fecha de creación del registro modificado"></table-column>

        <table-column show="justificacion" label="Justificacion"></table-column>

        {{-- <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-doc-expediente-historials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-doc-expediente-historials" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column> --}}
    </table-component>
</div>