<table class="table table-hover m-b-0" id="provider-contracts-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Proveedor')</th>
            <th>@lang('Objeto')</th>
            <th>@lang('Tipo de contrato')</th>
            <th>@lang('Número de contrato')</th>
            <th>@lang('Fecha de acta de inicio')</th>
            <th>Estado</th>
            <th>Valor del CDP</th>
            <th>Valor contrato</th>
            <th>Valor disponible</th>
            <th>Porcentaje de ejecución</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(providerContract, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ providerContract . providers . name }}</td>
            <td>@{{ providerContract . object }}</td>
            <td>@{{ providerContract . type_contract }}</td>
            <td>@{{ providerContract . contract_number }}</td>
            <td>@{{ providerContract . start_date }}</td>
            <td>@{{ providerContract . condition }}</td>
            <td>$ @{{ currencyFormat(providerContract . total_value_cdp) }}</td>
            <td>$ @{{ currencyFormat(providerContract . total_value_contract) }}</td>
            <td>$ @{{ currencyFormat(providerContract . value_avaible) }}</td>
            <td v-if="providerContract.total_percentage<=70">
                <div style="background-color: green; color: white;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %</div>
            </td>
            <td v-if="providerContract.total_percentage>70 && providerContract.total_percentage<86">
                <div style="background-color: yellow; color: black;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %</div>
            </td>
            <td v-if="providerContract.total_percentage>=86">
                <div style="background-color: red; color: white;">
                    @{{ currencyFormat(providerContract . total_percentage) }} %
                </div>
            </td>
            <td>
                <div class="row">                  
                        <button @click="show(providerContract)" data-target="#modal-view-provider-contracts"
                            data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                            data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                            <a :href="'{!! url('maintenance/budget-execution-index') !!}?mpc=' + providerContract[customId]"
                            class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                            title="Ejecución presupuestal"><i class="fas fa-hand-holding-usd"></i></a>
                </div>
            </td>
        </tr>
    </tbody>
</table>
