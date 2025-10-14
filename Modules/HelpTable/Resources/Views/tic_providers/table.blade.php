<div class="table-responsive">
    <table-component
        id="tic-providers-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tic-providers"
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
        <table-column show="type_person_name" label="@lang('Type Person')"></table-column>
        <table-column show="document_type_name" label="@lang('Document Type')"></table-column>
        <table-column show="identification" label="@lang('Identification')"></table-column>
        <table-column show="users.name" label="@lang('Name')"></table-column>
        <table-column show="users.email" label="@lang('Email')"></table-column>
        <table-column show="state_name" label="@lang('State')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                
                <button @click="edit(row)" data-target="#modal-form-tic-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fas fa-pencil-alt"></i></button>

                <button @click="show(row)" data-target="#modal-view-tic-providers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                

            </template>
        </table-column>
    </table-component>
</div>