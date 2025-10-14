<div class="row">
    <!-- Codigo Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Codigo'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.codigo }}.</dd>
</div>


<div class="row">
    <!-- Articulo Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Articulo'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.articulo }}.</dd>
</div>


<div class="row">
    <!-- Grupo Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Grupo'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.grupo }}.</dd>
</div>


<div class="row">
    <!-- Cantidad Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Cantidad'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.cantidad }}.</dd>
</div>


<div class="row">
    <!-- Costo Unitario Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Costo Unitario'):</dt>
    <dd class="col-9 text-truncate">@{{ "$"  +  currencyFormat(dataShow.costo_unitario) }}.</dd>
</div>

<div class="row">
    <!-- Costo Unitario Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Iva'):</dt>
    <dd class="col-9 text-truncate">@{{ "$"  +  currencyFormat(dataShow.iva_bd) }}.</dd>
</div>


<div class="row">
    <!-- Total Field -->
    <dt class="text-inverse text-left col-3 text-truncate">@lang('Total'):</dt>
    <dd class="col-9 text-truncate">@{{ "$" + currencyFormat(dataShow.total_value) }}.</dd>
</div>
