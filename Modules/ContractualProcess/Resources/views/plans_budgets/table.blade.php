<div class="table-responsive">
    <table-component
        id="plansBudgets-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="plansBudgets"
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
        <table-column show="pc_needs_id" label="@lang('Pc Needs Id')"></table-column>
            <table-column show="description" label="@lang('Description')"></table-column>
            <table-column show="estimated_start_date" label="@lang('Estimated Start Date')"></table-column>
            <table-column show="selection_mode" label="@lang('Selection Mode')"></table-column>
            <table-column show="estimated_total_value" label="@lang('Estimated Total Value')"></table-column>
            <table-column show="estimated_value_current_validity" label="@lang('Estimated Value Current Validity')"></table-column>
            <table-column show="additions" label="@lang('Additions')"></table-column>
            <table-column show="total_value" label="@lang('Total Value')"></table-column>
            <table-column show="future_validity_status" label="@lang('Future Validity Status')"></table-column>
            <table-column show="observation" label="@lang('Observation')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-plansBudgets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-plansBudgets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>