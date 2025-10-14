<div class="table-responsive">
    <table-component
        id="minorEquipmentFuels-table"
        :data="dataList"
        sort-by="minorEquipmentFuels"
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
        
                 <table-column show="fuel_type" label="Tipo de combustible"></table-column>

                <table-column show="created_at" label="Fecha de registro"></table-column>
            
                <table-column show="supply_date" label="@lang('Supply Date')"></table-column>

                <table-column show="updated_at" label="Fecha de modificación"></table-column>

                <table-column show="dependencias.nombre" label="Proceso">
                </table-column>

                <table-column show="checked_fuel" label="@lang('Checked Fuel')">
                    <template slot-scope="row" :sortable="false" :filterable="false">
                        @{{ formatNumber(row.checked_fuel,4)}} gal
                    </template>
                </table-column>
                
                <table-column show="start_date_fortnight" label="Fecha inicial del período"></table-column>
                <table-column show="end_date_fortnight" label="Fecha final del período"></table-column>
                
                <table-column show="more_buy_fortnight" label="Más compras en el período">
                    <template slot-scope="row" :sortable="false" :filterable="false">
                        @{{ formatNumber(row.more_buy_fortnight, 4)}} gal
                    </template>
                </table-column>

                <table-column show="total_fuel_avaible" label="Combustible disponible">
                    <template slot-scope="row" :sortable="false" :filterable="false">
                        <div v-if="row.exists_after==false" class="bg-green"> 
                            @{{ formatNumber(row.total_fuel_avaible, 4)}} gal
                        </div>
                        <div v-if="row.exists_after==true" class="bg-red text-white"> 
                            @{{ formatNumber(row.total_fuel_avaible, 4)}} gal
                        </div>
                    </template>
                </table-column>
                
                <table-column show="total_consume_fuel" label="Total consumo en la quincena">
                    <template slot-scope="row" :sortable="false" :filterable="false">
                        @{{ formatNumber(row.total_consume_fuel, 4)}} gal
                    </template>
                </table-column>
                            
                <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
                    <template slot-scope="row">
                        @if (Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                        <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-minorEquipmentFuels" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        @endif
                        <button @click="show(row)" data-target="#modal-view-minorEquipmentFuels" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                            <i class="fa fa-search"></i>
                        </button>
                        
                        <a :href="'documents-minor-equipments?equipment='+row.id" class="btn btn-white btn-icon btn-md"
                            data-placement="top" title="Agregar documentos">
                            <i class="fas fa-folder-plus"></i>
                        </a>

                        <a :href="'equipment-minor-fuel-consumptions?equipment='+row.id" class="btn btn-white btn-icon btn-md"
                        data-placement="top" title="Consumo de combustible por equipo">
                        <i class="fas fa-gas-pump"></i>
                    </a>

                    @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button @click="edit(row)" data-target="#modal-delete-provider-contract" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                    </button>
                    @endif
                    </template>
            </table-column>
        
    </table-component>
</div>