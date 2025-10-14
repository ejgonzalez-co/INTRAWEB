<div class="table-responsive">
    <table-component
        id="ticSatisfactionPolls-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="ticSatisfactionPolls"
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
        <table-column show="ht_tic_requests_id" label="@lang('Ht Tic Requests Id')"></table-column>
            <table-column show="users_id" label="@lang('Users Id')"></table-column>
            <table-column show="functionary_id" label="@lang('Functionary Id')"></table-column>
            <table-column show="question" label="@lang('Question')"></table-column>
            <table-column show="reply" label="@lang('Reply')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-ticSatisfactionPolls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-ticSatisfactionPolls" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>