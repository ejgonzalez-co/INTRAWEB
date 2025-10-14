<div class="table-responsive">
    <table-component
        id="monthlyRoutines-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="monthlyRoutines"
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
            <table-column show="1" label="@lang('Routine Start Date')">
                <template slot-scope="row">
                    @{{ formatDate(row.routine_start_date) }}
                </template>
            </table-column>
            <table-column show="2" label="@lang('Routine End Date')">
                <template slot-scope="row">
                    @{{ formatDate(row.routine_end_date) }}
                </template>
            </table-column>
            <table-column show="state_routine" label="@lang('State Routine')"></table-column>
            <table-column show="lc_officials" label="Funcionarios">
                <template slot-scope="row">
                    <ul>
                        <li v-for="(lt, key) in row.lc_officials" :key="key">
                            @{{ lt.name }}
                        </li>
                    </ul>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-monthlyRoutines" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <a :href="'monthly-routines-officials?lc_monthly_routines_id='+row.id" class="btn btn-white btn-icon btn-md"
                data-placement="top" title="Agregar Funcionarios">
                <i class="fas fa-address-book"></i>
                </a>

                <a :href="'weekly-routines?lc_monthly_routines_id='+row.id" class="btn btn-white btn-icon btn-md"
                data-placement="top" title="Rutinas fines de semana">
                <i class="fas fa-calendar-alt"></i>
                </a>

                {{-- <button @click="show(row)" data-target="#modal-view-monthlyRoutines" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button> --}}

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>