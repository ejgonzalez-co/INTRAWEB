<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Información general</h4>
    <div class="row text-left mt-5">
        <!-- Value Cdp Field -->
        <dt class="text-inverse  text-left col-3 pb-2">@lang('Value Cdp'):</dt>
        <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow . value_cdp) }}.</dd>
    </div>


    <div class="row text-left">
        <!-- Consecutive Cdp Field -->
        <dt class="text-inverse  text-left col-3 pb-2">@lang('Consecutive Cdp'):</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . consecutive_cdp }}.</dd>
    </div>


    <div class="row">
        <!-- Value Contract Field -->
        <dt class="text-inverse  text-left col-3 pb-2">@lang('Value Contract'):</dt>
        <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow . value_contract) }}.</dd>
    </div>


    <div class="row">
        <!-- Cdp Available Field -->
        <dt class="text-inverse  text-left col-3 pb-2">@lang('Cdp Available'):</dt>
        <dd class="col-9 text-truncate">$ @{{ currencyFormat(dataShow . cdp_available) }}.</dd>
    </div>


    <div class="row">
        <!-- Observation Field -->
        <dt class="text-inverse  text-left col-3 pb-2">@lang('Observation'):</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . observation }}.</dd>
    </div>
</div>
<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Historial asignación presupuestal</h4>
    <div class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Fecha modificación</th>
                    <th>Acción</th>
                    <th>Observación</th>
                    <th>Nombre del usuario</th>
                    <th>Valor del CDP</th>
                    <th>Valor del contrato</th>
                    <th>CDP disponible</th>
                </tr>
                <tr v-for="attachment in dataShow.history_assignation">
                    <td style="padding: 15px">@{{ attachment . created_at }}</td>
                    <td style="padding: 15px">@{{ attachment . name }}</td>
                    <td style="padding: 15px">@{{ attachment . observation }}</td>
                    <td style="padding: 15px">@{{ attachment . name_user }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . value_cdp) }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . value_contract) }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . cdp_avaible) }}</td>

                </tr>
            </table>
        </div>
    </div>
</div>

<div class="panel" style="border: 200px; padding: 15px;"
    v-for="attachment in dataShow.mant_administration_cost_items">
    <h4 class="text-center">Rubros</h4>
    <div class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Cód. del rubro</th>
                    <th>Nombre del rubro</th>
                    <th>Cód. centro de costos</th>
                    <th>Nombre del centro de costos</th>
                    <th>Valor del rubro</th>
                    <th>Valor ejecutado</th>
                    <th>Porcentaje de ejecución</th>
                    <th>Valor disponible</th>
                </tr>
                <tr>
                    <td style="padding: 15px">@{{ attachment . code_cost }}</td>
                    <td style="padding: 15px">@{{ attachment . name }}</td>
                    <td style="padding: 15px">@{{ attachment . cost_center }}</td>
                    <td style="padding: 15px">@{{ attachment . cost_center_name }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . value_item) }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . total_value_executed) }}</td>
                    <td style="padding: 15px">@{{ currencyFormat(attachment . total_percentage_executed) }}%</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . value_avaible) }}</td>

                </tr>
            </table>
        </div>
    </div>

    <h4 v-if="attachment.mant_budget_executions?.length" class="text-center mt-5">Ejecución presupuestal</h4>
    <div v-if="attachment.mant_budget_executions?.length" class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Fecha del acta</th>
                    <th>Observación</th>
                    <th>Valor ejecutado</th>
                    <th>Nuevo valor disponible</th>
                    <th>Porcentaje de ejecución</th>
                </tr>
                <tr v-for="value in attachment.mant_budget_executions">
                    <td style="padding: 15px">@{{ value . date }}</td>
                    <td style="padding: 15px">@{{ value . observation }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(value . executed_value) }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(value . new_value_available) }}</td>
                    <td style="padding: 15px">@{{ currencyFormat(value . percentage_execution_item) }}%</td>

                </tr>
            </table>
        </div>
    </div>

</div>
<div v-if="dataShow.mant_history_administration_cost_items?.length" class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Historial de rubros</h4>
    <div class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Acción</th>
                    <th>Observación</th>
                    <th>Nombre del usuario</th>
                    <th>Código del rubro</th>
                    <th>Nombre del rubro</th>
                    <th>Valor del rubro</th>
                </tr>
                <tr v-for="attachment in dataShow.mant_history_administration_cost_items">
                    <td style="padding: 15px">@{{ attachment . name }}</td>
                    <td style="padding: 15px">@{{ attachment . observation }}</td>
                    <td style="padding: 15px">@{{ attachment . name_user }}</td>
                    <td style="padding: 15px">@{{ attachment . code_cost }}</td>
                    <td style="padding: 15px">$@{{ attachment . name_cost }}</td>
                    <td style="padding: 15px">$@{{  currencyFormat(attachment . value_item) }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
