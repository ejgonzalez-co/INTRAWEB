<div class="table-responsive">
    <table-component
        id="oilDocuments-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="oilDocuments"
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
            <table-column show="1" label="@lang('Documents')">
                <template slot-scope="row">
                    <div>
                        <div v-for="documento in row.url_attachment.split(',')" style="margin-left: -15px;"><a
                        class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver
                        adjunto</a></div>
                    </div>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-oilDocuments" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif
                
                <button @click="show(row)" data-target="#modal-view-oilDocuments" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                @endif
                
                
            </template>
        </table-column>
    </table-component>
</div>