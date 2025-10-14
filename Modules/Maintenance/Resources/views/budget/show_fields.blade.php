<!-- Panel Descripción del equipamiento -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Type Person Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Type Person'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . type_person : '' }}.</dd>

            <!-- Document Type Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Document Type'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . document_type : '' }}.</dd>

            <!-- Identification Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Identification'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . identification : '' }}.</dd>

            <!-- Name Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Name'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . name : '' }}.</dd>

            <!-- Mail Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Mail'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . mail : '' }}.</dd>

            <!-- Regime Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Regime'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . regime : '' }}.</dd>

            <!-- Phone Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Phone'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . phone : '' }}.</dd>

            <!-- Address Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Address'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . address : '' }}.</dd>

            <!-- Municipality Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Municipality'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . municipality : '' }}.</dd>

            <!-- Department Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Department'):</dt>
            <dd class="col-3">@{{ dataShow . providers ? dataShow . providers . department : '' }}.</dd>

            <!-- Porcentaje de ejecución Field -->
            <dt class="text-inverse text-left col-3 pb-2">Supervisor del contrato:</dt>
            <dd class="col-3">@{{ dataShow . manager_dependencia }}.</dd>

            <!-- Department Field -->
            <dt class="text-inverse text-left col-3 pb-2">Dependencia del supervisor:</dt>
            <dd class="col-3">@{{ dataShow . dependencias ? dataShow . dependencias . nombre : '' }}.</dd>


            <!-- Type Contract Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Tipo de contrato'):</dt>
            <dd class="col-3">@{{ dataShow . type_contract }}.</dd>


            <!-- Contract Number Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Número de contrato'):</dt>
            <dd class="col-3">@{{ dataShow . contract_number }}.</dd>


            <!-- Fecha de acta de inicio Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Fecha de acta de inicio'):</dt>
            <dd class="col-3">@{{ dataShow . start_date }}.</dd>


            <!-- CDP aprobado Field -->
            <dt class="text-inverse text-left col-3 pb-2">Valor del CDP:</dt>
            <dd class="col-3">$ @{{ currencyFormat(dataShow . total_value_cdp) }}.</dd>


            <!-- CDP disponible Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('CDP disponible'):</dt>
            <dd class="col-3">$ @{{ currencyFormat(dataShow . total_value_avaible_cdp) }}.</dd>


            <!-- Valor de contrato Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Valor de contrato'):</dt>
            <dd class="col-3">$ @{{ currencyFormat(dataShow . total_value_contract) }}.</dd>


            <!-- Fecha de cierre Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Fecha final del contrato'):</dt>
            <dd class="col-3">@{{ dataShow . closing_date }}.</dd>


            <!-- Valor ejecutado Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Valor ejecutado'):</dt>
            <dd class="col-3">$ @{{ currencyFormat(dataShow . total_executed) }}.</dd>


            <!-- Porcentaje de ejecución Field -->
            <dt class="text-inverse text-left col-3 pb-2">@lang('Porcentaje de ejecución'):</dt>
            <dd class="col-3">@{{ currencyFormat(dataShow . total_percentage) }}%.</dd>




        </div>
        <div class="row">
            <!-- Object Field -->
            <div class="col-3">
                <dt class="text-inverse text-left pb-2">@lang('Objeto'):</dt>
            </div>
            <div class="col">
                <dd>@{{ dataShow . object }}.</dd>
            </div>
        </div>
    </div>
</div>

<!-- Panel asignación presupuestal -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Historial de contrato</strong>
        </h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de novedad</th>
                                <th>Fecha de la novedad</th>
                                <th>Nombre de usuario</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.history_contract">
                                <td>@{{ document . name }}</td>
                                <td>@{{ document . created_at }}</td>
                                <td>@{{ document . name_user }}</td>
                                <td>@{{ document . observation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Importar repuestos -->
<div v-if="dataShow.mant_history_budget_assignation?.length" class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Historial de asignación de
                presupuesto</strong>
        </h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <tr>
                                <th>Tipo de novedad</th>
                                <th>Fecha de la novedad</th>
                                <th>Nombre de usuario</th>
                                <th>Observación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.mant_history_budget_assignation">
                                <td>@{{ document . name }}</td>
                                <td>@{{ document . created_at }}</td>
                                <td>@{{ document . name_user }}</td>
                                <td>@{{ document . observation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Asignacion del contrato del proveedor -->
<div v-if="dataShow.mant_budget_assignation?.length" class="panel col-md-12" data-sortable-id="ui-general-1">
    <div v-for="(document, key) in dataShow.mant_budget_assignation">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Asignación de
                    presupuesto</strong></h3>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">
                <div class="row" style="margin: auto;">
                    <div class="table-responsive">
                        <table class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha de registro</th>
                                    <th>Valor del CDP</th>
                                    <th>Consecutivo del CDP</th>
                                    <th>Valor del contrato</th>
                                    <th>CDP disponible</th>
                                    <th>Nombre de usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>@{{ document . created_at }}</td>
                                    <td>$ @{{ currencyFormat(document . value_cdp) }}</td>
                                    <td>@{{ document . consecutive_cdp }}</td>
                                    <td>$ @{{ currencyFormat(document . value_contract) }}</td>
                                    <td>$ @{{ currencyFormat(document . cdp_available) }}</td>
                                    <td>@{{ document . name_user }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Importar actividades -->
        <div v-if="document.mant_administration_cost_items != null" class="panel col-md-12"
            data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">

            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body" v-for="(val, key) in document.mant_administration_cost_items">
                <div v-if="document != null">
                    <h3 class="panel-title" style="text-align: center; font-size: 15px;"><strong>Rubros de
                            presupuesto</strong>
                    </h3>
                    <div class="row">
                        <div class="row" style="margin: auto;">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Cod. del Rubro</th>
                                            <th>Nombre del rubro</th>
                                            <th>Cod. centro de costos</th>
                                            <th>Nombre del centro de costos</th>
                                            <th>Valor ejecutado</th>
                                            <th>Valor del rubro</th>
                                            <th>Porcentaje de ejecución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@{{ val . code_cost }}</td>
                                            <td>@{{ val . name }}</td>
                                            <td>@{{ val . cost_center }}</td>
                                            <td>@{{ val . cost_center_name }}</td>
                                            <td>$@{{ currencyFormat(val . total_value_executed) }}</td>
                                            <td>$@{{ currencyFormat(val . value_item) }}</td>
                                            <td>@{{ currencyFormat(val . total_percentage_executed) }}%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-for="(execution, key) in val.mant_budget_executions">
                        <div class="row" style="margin: auto;">
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered">
                                    <h3 class="panel-title" style="text-align: center; font-size: 12px;">
                                        <strong>Ejecución de presupuesto</strong>
                                    </h3>
                                    <thead>
                                        <tr>
                                            <th>Acta</th>
                                            <th>Fecha del acta</th>
                                            <th>Observación</th>
                                            <th>Valor ejecutado</th>
                                            <th>Nuevo valor disponible</th>
                                            <th>Porcentaje de ejecución</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@{{ execution . minutes }}</td>
                                            <td>@{{ execution . date }}</td>
                                            <td>@{{ execution . observation }}</td>
                                            <td>@{{ currencyFormat(execution . executed_value) }}</td>
                                            <td>$@{{ currencyFormat(execution . new_value_available) }}</td>
                                            <td>$@{{ currencyFormat(execution . percentage_execution_item) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Panel Importar repuestos -->
        <div v-if="document.mant_history_administration_cost_items != null" class="panel col-md-12"
            data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Historial de rubros de
                        presupuestos</strong>
                </h3>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="row" style="margin: auto;">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha de modificación</th>
                                        <th>Tipo de novedad</th>
                                        <th>Código del rubro</th>
                                        <th>Nombre del rubro</th>
                                        <th>Código centro de costos</th>
                                        <th>Nombre centro de costos</th>
                                        <th>Observación</th>
                                        <th>Nombre de usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(val, key) in document.mant_history_administration_cost_items">
                                        <td>@{{ val . created_at }}</td>
                                        <td>@{{ val . name }}</td>
                                        <td>@{{ val . code_cost }}</td>
                                        <td>@{{ val . name_cost }}</td>
                                        <td>@{{ val . cost_center }}</td>
                                        <td>@{{ val . cost_center_name }}</td>
                                        <td>@{{ val . observation }}</td>
                                        <td>@{{ val . name_user }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Panel Importar actividades -->
    </div>
</div>
