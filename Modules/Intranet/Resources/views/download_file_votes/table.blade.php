<div class="table-responsive">
    <table-component
        id="download-file-votes-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="download-file-votes"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="true"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >
            <table-column show="users.name" label="@lang('Usuario')"></table-column>
            <table-column show="rating" label="@lang('Clasificación')"></table-column>
            <table-column show="download.title" label="@lang('Archivo')"></table-column>
            <table-column show="download.categorie.title" label="@lang('Categoría')"></table-column>
        {{-- <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-download-file-votes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-download-file-votes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column> --}}
    </table-component>
</div>