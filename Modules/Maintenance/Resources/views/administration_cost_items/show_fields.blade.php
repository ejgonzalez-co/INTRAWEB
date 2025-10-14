<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Información general</h4>
    <div class="row mt-5">
        <!-- Name Field -->
        <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . name }}.</dd>
    </div>


    <div class="row text-left">
        <!-- Code Cost Field -->
        <dt class=" text-inverse text-left col-3 text-truncate">Cód. centro del rubro:</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . code_cost }}.</dd>
    </div>


    <div class="row">
        <!-- Cost Center Name Field -->
        <dt class="text-inverse text-left col-3 ">Nombre del centro de costos:</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . cost_center_name }}.</dd>
    </div>


    <div class="row">
        <!-- Cost Center Field -->
        <dt class="text-inverse text-left col-3 text-truncate">Código del centro costos:</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . cost_center }}.</dd>
    </div>


    <div class="row">
        <!-- Value Item Field -->
        <dt class="text-inverse text-left col-3 text-truncate">Valor del rubro:</dt>
        <dd class="col-9 text-truncate">$@{{ currencyFormat(dataShow . value_item) }}.</dd>
    </div>


    <div class="row">
        <!-- Observation Field -->
        <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
        <dd class="col-9 text-truncate">@{{ dataShow . observation }}.</dd>
    </div>
</div>

<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Historial rubro presupuestal</h4>
    <div class="container">
        <div class="row justify-content-center">
            <table class="text-center default" border="1">
                <tr>
                    <th>Fecha modificación</th>
                    <th>Acción</th>
                    <th>Observación</th>
                    <th>Nombre del usuario</th>
                    <th>Código del rubro</th>
                    <th>Nombre del rubro</th>
                    <th>Valor del rubro</th>
               </tr>
                <tr v-for="attachment in dataShow.history_item">
                    <td style="padding: 15px">@{{ attachment . created_at }}</td>
                    <td style="padding: 15px">@{{ attachment . name }}</td>
                    <td style="padding: 15px">@{{ attachment . observation }}</td>
                    <td style="padding: 15px">@{{ attachment . name_user }}</td>
                    <td style="padding: 15px">@{{ attachment . code_cost }}</td>
                    <td style="padding: 15px">@{{ attachment . name_cost }}</td>
                    <td style="padding: 15px">$@{{ currencyFormat(attachment . value_item) }}</td>

                </tr>
            </table>
        </div>
    </div>
</div>

<div v-if="dataShow.mant_budget_executions?.length" class="panel" style="border: 200px; padding: 15px;">
   <h4 class="text-center">Ejecución presupuestal</h4>
   <div class="container">
       <div class="row justify-content-center">
           <table class="text-center default" border="1">
               <tr>
                   <th>Acta</th>
                   <th>Fecha del acta</th>
                   <th>Observación</th>
                   <th>Valor ejecutado</th>
                   <th>Nuevo valor disponible</th>
                   <th>Porcentaje de ejecución</th>
              </tr>
               <tr v-for="attachment in dataShow.mant_budget_executions">
                   <td style="padding: 15px">@{{ attachment . minutes }}</td>
                   <td style="padding: 15px">@{{ attachment . date }}</td>
                   <td style="padding: 15px">@{{ attachment . observation }}</td>
                   <td style="padding: 15px">$@{{  currencyFormat(attachment . executed_value )}}</td>
                   <td style="padding: 15px">$@{{  currencyFormat(attachment . new_value_available) }}</td>
                   <td style="padding: 15px">@{{  currencyFormat(attachment . percentage_execution_item) }}%</td>
                  
               </tr>
           </table>
       </div>
   </div>
</div>
