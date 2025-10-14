<div class="table-responsive">
    <table-component id="equipmentProviders-table" :data="advancedSearchFilterPaginate()" sort-by="equipmentProviders"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="identification_number" label="@lang('Nit')"></table-column>
        <table-column show="fullname" label="@lang('Name')"></table-column>
        <table-column show="email" label="@lang('Email')"></table-column>
        <table-column show="phone" label="@lang('Phone')"></table-column>
        <table-column show="address" label="@lang('Address')"></table-column>
        <table-column show="status" label="@lang('Estado del contrato')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-equipmentProviders"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-equipmentProviders" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
