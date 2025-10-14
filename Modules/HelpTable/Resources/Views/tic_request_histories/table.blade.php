<div class="table-responsive">
    <table-component
        id="ticRequestHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="ticRequestHistories"
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
            <table-column show="ht_tic_type_request_id" label="@lang('Ht Tic Type Request Id')"></table-column>
            <table-column show="ht_tic_request_status_id" label="@lang('Ht Tic Request Status Id')"></table-column>
            <table-column show="assigned_by_id" label="@lang('Assigned By Id')"></table-column>
            <table-column show="assigned_by_name" label="@lang('Assigned By Name')"></table-column>
            <table-column show="users_id" label="@lang('Users Id')"></table-column>
            <table-column show="users_name" label="@lang('Users Name')"></table-column>
            <table-column show="assigned_user_name" label="@lang('Assigned User Name')"></table-column>
            <table-column show="assigned_user_id" label="@lang('Assigned User Id')"></table-column>
            <table-column show="ht_tic_type_tic_categories_id" label="@lang('Ht Tic Type Tic Categories Id')"></table-column>
            <table-column show="priority_request" label="@lang('Priority Request')"></table-column>
            <table-column show="affair" label="@lang('Affair')"></table-column>
            <table-column show="floor" label="@lang('Floor')"></table-column>
            <table-column show="assignment_date" label="@lang('Assignment Date')"></table-column>
            <table-column show="prox_date_to_expire" label="@lang('Prox Date To Expire')"></table-column>
            <table-column show="expiration_date" label="@lang('Expiration Date')"></table-column>
            <table-column show="date_attention" label="@lang('Date Attention')"></table-column>
            <table-column show="closing_date" label="@lang('Closing Date')"></table-column>
            <table-column show="reshipment_date" label="@lang('Reshipment Date')"></table-column>
            <table-column show="next_hour_to_expire" label="@lang('Next Hour To Expire')"></table-column>
            <table-column show="hours" label="@lang('Hours')"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column show="tracing" label="@lang('Tracing')"></table-column>
            <table-column show="request_status" label="@lang('Request Status')"></table-column>
            <table-column show="survey_status" label="@lang('Survey Status')"></table-column>
            <table-column show="time_line" label="@lang('Time Line')"></table-column>
            <table-column show="support_type" label="@lang('Support Type')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-ticRequestHistories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-ticRequestHistories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>