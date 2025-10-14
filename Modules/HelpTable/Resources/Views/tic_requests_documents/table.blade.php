<div class="table-responsive">
    <table-component
        id="ticRequestsDocuments-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="ticRequestsDocuments"
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
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column label="@lang('Attached')">
                <template slot-scope="row">
                    <a class="col-3 text-truncate":href="'{{ asset('storage') }}/'+row.url"target="_blank">Ver adjunto</a>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if(Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Soporte TIC'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-ticRequestsDocuments" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-ticRequestsDocuments" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if(Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Soporte TIC'))
                    <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
                
            </template>
        </table-column>
    </table-component>
</div>