<div class="table-responsive">
    <table-component id="administrationCostItems-table" :data="advancedSearchFilterPaginate()"
        sort-by="administrationCostItems" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="id" label="#"></table-column>
        <table-column show="code_cost" label="Cód. del Rubro"></table-column>
        <table-column show="name" label="@lang('Name') del rubro"></table-column>
        <table-column show="cost_center" label="Cód. centro de costos"></table-column>
        <table-column show="cost_center_name" label="Nombre del centro de costos"></table-column>

        <table-column show="value_item" label="Valor disponible del rubro">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row . value_item) }}
            </template>
        </table-column>
        <table-column show="total_value_executed" label="Valor ejecutado">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row . total_value_executed) }}
            </template>
        </table-column>
        <table-column show="last_executed_value" label="Valor disponible">
            <template slot-scope="row" :sortable="false" :filterable="false">
                $ @{{ currencyFormat(row . last_executed_value) }}
            </template>
        </table-column>
        <table-column show="total_percentage_executed" label="Porcentaje de ejecución">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.total_percentage_executed <= 70" style="background-color: green; color: white">
                    @{{ currencyFormat(row . total_percentage_executed) }}%
                </div>
                <div v-if="row.total_percentage_executed > 70 && row.total_percentage_executed <= 85"
                    style="background-color: yellow">
                    @{{ currencyFormat(row . total_percentage_executed) }}%
                </div>
                <div v-if="row.total_percentage_executed > 85" style="background-color: red; color: white">
                    @{{ currencyFormat(row . total_percentage_executed) }}%
                </div>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-administrationCostItems"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-administrationCostItems" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="edit(row)"  data-target="#modal-delete-administrationCostItems" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                <a :href="'{!! url('maintenance/butget-executions') !!}?mpc=' +row.id" class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip" data-placement="top" title="Ejecución presupuestal"><i
                        class="fas fa-comment-dollar"></i></a>
            </template>
        </table-column>
    </table-component>
</div>
