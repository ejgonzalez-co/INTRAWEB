<!-- Description Field -->
<dt class="text-inverse text-left col-3">@lang('Descripción'):</dt>
<dd class="col-9">@{{ dataShow.description }}.</dd>


<!-- Unit_measure Field -->
<dt class="text-inverse text-left col-3">@lang('Unidad de medida'):</dt>
<dd class="col-9">@{{ dataShow.unit_measure }}.</dd>


<!-- Unit_value Field -->
<dt class="text-inverse text-left col-3">@lang('Valor unitario'):</dt>
<dd class="col-9">$@{{ currencyFormat(dataShow.unit_value) }}.</dd>


<!-- Iva Field -->
<dt class="text-inverse text-left col-3">@lang('IVA'):</dt>
<dd class="col-9">$@{{ currencyFormat(dataShow.iva) }}.</dd>


<!-- Total_value Field -->
<dt class="text-inverse text-left col-3">@lang('Valor total'):</dt>
<dd class="col-9">$@{{ currencyFormat(dataShow.total_value) }}.</dd>
