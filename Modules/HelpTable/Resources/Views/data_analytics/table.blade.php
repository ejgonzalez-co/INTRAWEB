<div class="table-responsive">
    <table-component id="tic-requests-table" :data="dataList" sort-by="tic-requests" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0" :key="keyRefresh">
        <table-column show="id" label="@lang('Number')"></table-column>
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="users.dependencies.nombre" label="@lang('Dependency')"></table-column>
        <table-column show="users_name" label="@lang('User')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.users_name ? row.users_name : (row.users ? row.users.name : '') }}
            </template>
        </table-column>
        <table-column show="priority_request_name" label="@lang('Priority Request')"></table-column>
        <table-column show="affair" label="@lang('Affair')"></table-column>
        <table-column show="assigned_user_name" label="@lang('Usuario asignado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ row.assigned_user_name ? row.assigned_user_name : (row.assigned_user ? row.assigned_user.name : '') }}
            </template>
        </table-column>
        <table-column show="" label="@lang('State')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-2" :style="'background-color:' + row.status_color"
                    v-html=" row.status_name"></div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-data-analytics" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')"><i class="fa fa-search"></i></button>

            </template>
        </table-column>
    </table-component>
</div>
