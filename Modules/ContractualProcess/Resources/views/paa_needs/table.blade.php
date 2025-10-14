<div class="table-responsive">
    <table-component
        id="paaNeeds-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="paaNeeds"
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
        <table-column show="pc_paa_calls_id" label="@lang('Pc Paa Calls Id')"></table-column>
            <table-column show="pc_process_leaders_id" label="@lang('Pc Process Leaders Id')"></table-column>
            <table-column show="name_process" label="@lang('Name Process')"></table-column>
            <table-column show="state" label="@lang('State')"></table-column>
            <table-column show="total_value_paa" label="@lang('Total Value Paa')"></table-column>
            <table-column show="total_operating_value" label="@lang('Total Operating Value')"></table-column>
            <table-column show="future_validity_status" label="@lang('Future Validity Status')"></table-column>
            <table-column show="total_investment_value" label="@lang('Total Investment Value')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-paaNeeds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-paaNeeds" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>