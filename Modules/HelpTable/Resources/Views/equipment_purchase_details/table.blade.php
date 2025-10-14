<div class="table-responsive">
    <table-component
        id="equipment-purchase-details-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="equipment-purchase-details"
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
        {{-- <table-column show="ht_tic_equipment_resume_id" label="@lang('ht_tic_equipment_resume_id')"></table-column> --}}
                    <table-column show="date" label="@lang('date')"></table-column>

                    <table-column show="contract_number" label="@lang('Contract Number')"></table-column>


                    <table-column show="provider" label="@lang('Provider')"></table-column>

                    <table-column show="warranty_in_years" label="@lang('Waranty years')"></table-column>
                    
                    <table-column show="warranty_termination_date" label="@lang('Waranty Termination Date')"></table-column>

                    {{-- <table-column show="contract_total_value" label="@lang('Contract Total Value')"></table-column> --}}

                    <table-column show="contract_total_value" label="@lang('Contract Total Value')">
                        <template slot-scope="row">$ @{{ currencyFormat(row.contract_total_value) }}</template>
                    </table-column>

                    {{-- <table-column show="status" label="@lang('status')"></table-column> --}}


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-equipment-purchase-details" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-equipment-purchase-details" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>