<div class="table-responsive">
    <table-component
        id="events-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="events"
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
        <table-column show="type_events.name" label="@lang('Type Events')"></table-column>
        <table-column show="type_category_name" label="@lang('Type Category')"></table-column>
        <table-column show="title" label="@lang('Title')"></table-column>
        <table-column show="description" label="@lang('Description')"></table-column>
        <table-column show="initial_date" label="@lang('Initial Date')"></table-column>
        <table-column show="initial_hour" label="@lang('Initial Hour')"></table-column>
        <table-column show="end_hour" label="@lang('End Hour')"></table-column>
        <table-column show="duration_name" label="@lang('Duration')"></table-column>
        <table-column show="state_name" label="@lang('State')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.state != 2" @click="edit(row)" data-backdrop="static" data-target="#modal-form-events" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-events" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.state != 2"  @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>