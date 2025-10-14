<div class="row">
   <!-- Registration Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Registration Date'):</dt>
   <dd class="col-9 text-truncate">@{{ formatDate(dataShow.registration_date) }}.</dd>
</div>


<div class="row">
   <!-- Tire Reference Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Tire Reference'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.tire_all  ? dataShow.tire_all.name : ''  }}.</dd>
</div>


<div class="row">
   <!-- Inflation Pressure Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('inflationPressures'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.inflation_pressure }}.</dd>
</div>


<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.observation }}.</dd>
</div>


