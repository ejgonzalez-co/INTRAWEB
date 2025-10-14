<div class="table-responsive">
    <table-component
        id="monthlyRoutinesOfficials-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="monthlyRoutinesOfficials"
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
        {{-- <table-column show="lc_monthly_routines_id" label="@lang('Lc Monthly Routines Id')"></table-column> --}}
            {{-- <table-column show="users_id" label="@lang('Users Id')"></table-column> --}}
            <table-column show="user_name" label="@lang('Lc Officials Id')"></table-column>
            <table-column show="lc_list_trials" label="Ensayos">
                <template slot-scope="row">
                    <ul>
                        <li v-for="(lt, key) in row.lc_list_trials" :key="key">
                            @{{ lt.name }}
                        </li>
                    </ul>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-monthlyRoutinesHasUsers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-monthlyRoutinesHasUsers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                <button @click="show(row)" data-backdrop="static" data-target="#modal-history-request" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Ver historial"><i class="fas fa-calendar-check"></i></button>
                
            </template>
        </table-column>
    </table-component>
</div>