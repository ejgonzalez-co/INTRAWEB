<div class="table-responsive">
    <table-component id="evaluationProcesses-table" :data="advancedSearchFilterPaginate()" sort-by="evaluationProcesses"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column show="status" label="@lang('Status')">
            <template slot-scope="row">
                <p v-if="row.status == 'Activo'" class="button__status-approved">@{{ row.status }}</p>
                <p v-if="row.status == 'Inactivo'" class="button__status-cancelled">@{{ row.status }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="initValues.can_manage" @click="edit(row)" data-backdrop="static" data-target="#modal-form-evaluationProcesses"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-evaluationProcesses" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
