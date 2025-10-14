<div class="row">
   <!-- Registration Depth Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Registration Depth'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.registration_depth }}.</dd>

   <!-- Revision Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Date'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.revision_date }}.</dd>
</div>

<br>

<div class="row">
   <!-- Wear Total Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Wear Total'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.wear_total }}.</dd>

   <!-- Revision Mileage Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Mileage'):</dt>
   <dd class="col-3 text-truncate">@{{ currencyFormat(dataShow.revision_mileage) }} km.</dd>
</div>

<br>

<div class="row">
   <!-- Route Total Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Route Total'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.route_total }} km.</dd>

   <!-- Wear Cost Mm Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Valor actual de llanta:</dt>
   <dd class="col-3 text-truncate">$ @{{ currencyFormat(dataShow.wear_cost_mm) }}.</dd>
</div>

<br>

<div class="row">
   <!-- Cost Km Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Cost Km'):</dt>
   <dd class="col-3 text-truncate">$ @{{ dataShow.cost_km }}.</dd>

   <!-- Revision Pressure Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Revision Pressure'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.revision_pressure }}.</dd>
</div>

<br>

<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.observation }}.</dd>
</div>


