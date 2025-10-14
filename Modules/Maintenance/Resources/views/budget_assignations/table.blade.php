<div class="table-responsive">
    <table-component id="budgetAssignations-table" :data="advancedSearchFilterPaginate()" sort-by="budgetAssignations"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="id" label="#"></table-column>
        <table-column show="created_at" label="Fecha de registro"></table-column>
        <table-column show="updated_at" label="Fecha de modificación"></table-column>
        {{-- <table-column show="value_cdp" label="@lang('Value Cdp')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row.value_cdp)}}
            </template>
        </table-column> --}}
        {{-- <table-column show="consecutive_cdp" label="@lang('Consecutive Cdp')"></table-column> --}}
        <table-column show="value_contract" label="@lang('Value Contract')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row.value_contract)}}
            </template>
        </table-column>
        {{-- <table-column show="cdp_available" label="@lang('Cdp Available')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row.cdp_available)}}
            </template>
        </table-column> --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-budgetAssignations"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-budgetAssignations" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="edit(row)" data-target="#modal-delete-budgetassignations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            

                {{-- <button @click="edit(row)" data-target="#modal-new-contract" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Novedad contrato">
                <i class="fa fa-plus-circle"></i>
                </button> --}}
                
                <a :href="'{!! url('maintenance/contract-news') !!}?mpc=' +row.id" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Novedad contrato"> <i class="fa fa-plus-circle"></i></a>


                <a :href="'{!! url('maintenance/administration-cost-items') !!}?mpc=' +row.id" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Asignación de rubros"><i class="fas fa-comment-dollar"></i></a>

            </template>
        </table-column>
    </table-component>
</div>
