<div class="table-responsive">
    <table-component
        id="tic-knowledge-bases-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tic-knowledge-bases"
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
        <table-column show="created_at" label="@lang('Created_at')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ formatDate(row.created_at) }}
            </template>
        </table-column>
        <table-column show="tic_type_request.name" label="@lang('Type Knowledge')"></table-column>
        <table-column show="users.name" label="@lang('Registrado por')"></table-column>
        <table-column show="affair" label="@lang('Affair')"></table-column>
        <table-column show="knowledge_state_name" label="@lang('State')"></table-column>
        {{-- <table-column label="@lang('attached')">
            <template slot-scope="row" :sortable="false" :filterable="false"><span v-for="Document in row.attached.split(',')">
            <a v-if="row.attached" :href="'{{ asset('storage') }}/'+Document" target="_blank">Ver adjunto</a><br/>
            </span>
            </template>
        </table-column> --}}
            
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if (Auth::user()->hasRole('Administrador TIC'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-knowledge-bases" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @else
                    <button v-if="row.users_id == {!! Auth::user()->id !!}" @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-knowledge-bases" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-tic-knowledge-bases" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>