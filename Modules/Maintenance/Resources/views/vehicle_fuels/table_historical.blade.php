<div class="table-responsive">
    <table-component
        id="vehicleFuels-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="vehicleFuels"
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
        <table-column show="created_migration" label="@lang('Created_at')"></table-column>
            
            <table-column show="resume_machinery_vehicles_yellow.plaque" label="Placa"></table-column>
            
            <table-column show="fuel_quantity" label="N° de galones">
                <template slot-scope="row">
                    @{{ row.fuel_quantity }} G
                </template>

            </table-column>
            <table-column show="" label="Precio galón ">
                <template slot-scope="row">
                    $@{{ currencyFormat(row.gallon_price) }}
                </template>

            </table-column>
            <table-column show="1" label="Precio total">
                <template slot-scope="row">
                    $@{{ currencyFormat(row.total_price) }}
                </template>
            </table-column>
            <table-column show="9" label="Kilometraje">
                <template slot-scope="row">
                    <p v-if="row.current_mileage"> @{{ currencyFormat(row.current_mileage) }} KM</p>
                </template>
            </table-column>
            <table-column show="8" label="Horómetro">
                <template slot-scope="row">
                    <p v-if="row.current_hourmeter">@{{ currencyFormat(row ? row.current_hourmeter: '') }} HR</p>
                </template>
            </table-column>
            <table-column show="variation_tanking_hour" label="Variación de HR en los tanqueos">
                <template slot-scope="row">
                    <p v-if="row.variation_tanking_hour">@{{ currencyFormat(row.variation_tanking_hour) }} HR</p>
                </template>
            </table-column>
            <table-column show="variation_route_hour" label="Variación en KM por tanqueo">
                <template slot-scope="row">
                    <p v-if="row.variation_route_hour"> @{{ currencyFormat(row.variation_route_hour) }} KM</p>
                    </template>
            </table-column>
            <table-column show="performance_by_gallon" label="Rendimiento por galón">
                <template slot-scope="row">
                    <p v-if="row.variation_route_hour"> @{{ currencyFormat(row.performance_by_gallon) }} KM/G</p>
                    <p v-if="row.variation_tanking_hour"> @{{ currencyFormat(row.performance_by_gallon) }} HR/G</p>
                </template>
            </table-column>

            <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
                <template slot-scope="row">
    
                    <button @click="show(row)" data-target="#modal-view-historical" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                        <i class="fa fa-search"></i>
                    </button>
                    
                </template>
            </table-column>
       
    </table-component>
</div>