<!-- Item  -->
<dt class="text-inverse text-left col-3 text-truncate">Item:</dt>
<dd class="col-9 text-truncate">@{{ dataShow.item }}.</dd>


<!-- Descripcion  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>


<!-- Tipo  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Type'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.type }}.</dd>


<!-- Sistema  -->
<dt class="text-inverse text-left col-3 text-truncate">Sistema:</dt>
<dd class="col-9 text-truncate">@{{ dataShow.system }}.</dd>


<!-- Unidad de medida  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Unit Measurement'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.unit_measurement }}.</dd>


<!-- Cantidad  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Quantity'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.quantity }}.</dd>

<!-- Iva Field -->
<dt class="text-inverse text-left col-3">IVA:</dt>
<dd class="col-9">$@{{ currencyFormat(dataShow.iva) }}.</dd>

<!-- Valor unitario  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Unit value'):</dt>
<dd class="col-9 text-truncate">$@{{ currencyFormat(dataShow.unit_value) }}.</dd>


<!-- Valor total  -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Total Value'):</dt>
<dd class="col-9 text-truncate">$@{{ currencyFormat(dataShow.total_value) }}.</dd>
