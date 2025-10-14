<div class="table-responsive">
    <table-component
        id="investment-needs-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="investment-needs"
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
        <table-column show="name_process" label="@lang('Name Process')"></table-column>
        <table-column show="description" label="@lang('Description')"></table-column>
        <table-column show="unit" label="@lang('Unit')"></table-column>
        <table-column show="quantity" label="@lang('Quantity')"></table-column>
        <table-column show="unit_value" label="@lang('Unit Value')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ '$ '+currencyFormat(row.unit_value) }}
            </template>
        </table-column>
        <table-column show="total_value" label="@lang('Total Value')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ '$ '+currencyFormat(row.total_value) }}
            </template>
        </table-column>
        <table-column show="state_name" label="@lang('State')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <span v-if="row.state_name == 'Aprobada'">
                    Validado
                </span>
                <span v-else>
                    @{{ row.state_name }}
                </span>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.user_leader_id == '{!! Auth::user()->id !!}'" @click="edit(row)" data-backdrop="static" data-target="#modal-form-investment-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button v-if="row.user_leader_id == '{!! Auth::user()->id !!}'" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                @if(Auth::user()->hasRole('PC Gestor presupuesto'))
                <button  v-if="row.state == 1" @click="callFunctionComponent('evaluate-budget', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Evaluar presupuesto')"><i class="fas fa-cogs"></i></button>
                @endif

                <button @click="show(row)" data-target="#modal-view-investment-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                
                
            </template>
        </table-column>
    </table-component>
</div>