<div class="table-responsive">
    <table-component
        id="notices-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="notices"
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
        <table-column show="title" label="@lang('Title')"></table-column>
            <table-column show="users_name" label="Creador"></table-column>

            <table-column show="state_name" label="@lang('State')" cell-class="col-sm-1">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div :class="row.state_color" v-html="row.state_name"></div>
               </template>
            </table-column>

            <table-column show="date_start" label="@lang('Start Date')"></table-column>
            <table-column show="date_end" label="@lang('End Date')"></table-column>
            <table-column show="views" label="Vistas"></table-column>
            <table-column show="category.name" label="@lang('Category')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-notices" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-notices" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>