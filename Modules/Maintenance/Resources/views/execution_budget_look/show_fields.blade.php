<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Información general de rubros</h4>
    <div class="row mt-5">
        <div class="col">
            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3">Nombre del rubro:</dt>
                <dd class="col-9">
                    @{{ dataShow . mant_administration_cost_items ? dataShow . mant_administration_cost_items . name : '' }}.
                </dd>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3 ">Código del rubro presupuestal:</dt>
                <dd class="col-9">
                    @{{ dataShow . mant_administration_cost_items ? dataShow . mant_administration_cost_items . code_cost : '' }}.
                </dd>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3 ">Nombre del centro de costos:</dt>
                <dd class="col-9">
                    @{{ dataShow . mant_administration_cost_items ? dataShow . mant_administration_cost_items . cost_center_name : '' }}.
                </dd>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3 ">Código del centro de costos:</dt>
                <dd class="col-9">
                    @{{ dataShow . mant_administration_cost_items ? dataShow . mant_administration_cost_items . cost_center : '' }}.
                </dd>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3 ">Valor del rubro:</dt>
                <dd class="col-9">
                    $@{{ currencyFormat(dataShow . mant_administration_cost_items ? dataShow . mant_administration_cost_items . value_item : '') }}.
                </dd>
            </div>
        </div>

        <div class="col">
            <div class="row">

            </div>
        </div>
    </div>
</div>
<div class="panel" style="border: 200px; padding: 15px;">
    <h4 class="text-center">Información ejecución presupuestal</h4>
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <!-- Date Field -->
                <dt class="text-inverse text-left col-3">@lang('Date'):</dt>
                <dd class="col-9">@{{ dataShow . date }}.</dd>
            </div>
        </div>
        <div class="col">

            <div class="row">
                <!-- Minutes Field -->
                <dt class="text-inverse text-left col-3">@lang('Minutes'):</dt>
                <dd class="col-9">@{{ dataShow . minutes }}.</dd>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">

            <div class="row">
                <!-- Executed Value Field -->
                <dt class="text-inverse text-left col-3">@lang('Executed Value'):</dt>
                <dd class="col-9">$@{{ currencyFormat(dataShow . executed_value) }}.</dd>
            </div>
        </div>
        <div class="col">

            <div class="row">
                <!-- New Value Available Field -->
                <dt class="text-inverse text-left col-3">@lang('New Value Available'):</dt>
                <dd class="col-9">$@{{ currencyFormat(dataShow . new_value_available) }}.</dd>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="row">
                <!-- Percentage Execution Item Field -->
                <dt class="text-inverse text-left col-3 ">@lang('Percentage Execution Item'):</dt>
                <dd class="col-9">@{{ currencyFormat(dataShow . percentage_execution_item) }}%.</dd>
            </div>
        </div>
        <div class="col">

        </div>
    </div>

    <div class="row mt-5">
        <!-- Observation Field -->
        <dt class="text-inverse text-left col-3">@lang('Observation'):</dt>
        <dd>@{{ dataShow . observation }}</dd>
    </div>
</div>
