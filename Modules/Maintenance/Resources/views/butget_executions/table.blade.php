<div class="table-responsive">
    <table-component
        id="butgetExecutions-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="butgetExecutions"
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
        
            <table-column show="minutes" label="@lang('Minutes')"></table-column>
            <table-column show="date" label="Fecha del acta"></table-column>
            <table-column show="observation" label="Observación">                
            </table-column>
            <table-column show="executed_value" label="@lang('Executed Value')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.executed_value)}}
                </template>
            </table-column>
            <table-column show="new_value_available" label="@lang('New Value Available')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    $ @{{ currencyFormat(row.new_value_available)}}
                </template>
            </table-column>

            <table-column label="@lang('Consecutivo CDP')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <label>@{{ row.mant_administration_cost_items.mant_budget_assignation ? row.mant_administration_cost_items.mant_budget_assignation.consecutive_cdp : null }}</label> 
                </template>
            </table-column>

            <table-column show="percentage_execution_item" label="@lang('Percentage Execution Item')">
                <template slot-scope="row" :sortable="false" :filterable="false">
                     @{{ currencyFormat(row.percentage_execution_item)}} %
                </template>
            </table-column>


            
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-butgetExecutions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-butgetExecutions" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>