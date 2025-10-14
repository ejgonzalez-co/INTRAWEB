<div class="table-responsive">
    <table-component
        id="functioning-needs-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="functioning-needs"
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
        <table-column show="estimated_total_value" label="@lang('Estimated Total Value')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ '$ '+currencyFormat(row.estimated_total_value) }}
            </template>
        </table-column>
        <table-column show="state_name" label="@lang('State')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                
                @if(Auth::user()->hasRole('PC Líder de proceso') && $need ? $need->in_range_date : false)
                
                <execution-from-action
                    v-if="row.state == 3"
                    :value="row.needs"
                    route="send-review-needs"
                    field-update="state"
                    value-update="3"
                    css-class="fas fa-paper-plane"
                    title="@lang('Submit a review')"
                    >
                </execution-from-action>

                <button v-if="row.user_leader_id == '{!! Auth::user()->id !!}' && (row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7 || row.state == 3)" @click="edit(row)" data-backdrop="static" data-target="#modal-form-functioning-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                
                <button v-if="row.user_leader_id == '{!! Auth::user()->id !!}' && (row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7)" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif

                @if(Auth::user()->hasRole('PC Gestor presupuesto'))
                <button v-if="row.state == 1" @click="callFunctionComponent('evaluate-budget', 'loadData', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Evaluar presupuesto')"><i class="fas fa-cogs"></i></button>
                @endif

                <button @click="show(row)" data-target="#modal-view-functioning-needs" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                
            </template>
        </table-column>
    </table-component>
</div>