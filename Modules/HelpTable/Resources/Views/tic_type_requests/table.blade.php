<div class="table-responsive">
    <table-component
        id="tic-type-requests-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tic-type-requests"
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
        <table-column show="unit_time_name" label="@lang('Unit Time')"></table-column>
        <table-column show="type_term_name" label="@lang('Type Term')"></table-column>
        <table-column show="term" label="@lang('Application deadline')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.term + ' ' + row.unit_time_name }}
            </template>
        </table-column>
        <table-column show="early" label="@lang('Early warning')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.early + ' ' + row.unit_time_name }}
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-type-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-tic-type-requests" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>