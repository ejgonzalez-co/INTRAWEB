<div class="table-responsive">
    <table-component
        id="reporManagementAttachments-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="reporManagementAttachments"
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
            <table-column show="name" label="@lang('Name')"></table-column>
            <table-column label="@lang('Adjunto')">
                <template slot-scope="row">
                    <a :href="'{{ asset('storage') }}/'+row.attachment" class="col-9" target="_blank">Ver Adjunto</a>
                </template>
             </table-column>


            <table-column show="status" label="@lang('Status')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-reporManagementAttachments" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <!-- icono Historial que se muestra en las filas de la tabla. -->
                <button @click="show(row)" data-target="#modal-history" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="Historial">
                        <i class="fa fa-history"></i>
                    </button>
                
            </template>
        </table-column>
    </table-component>
</div>