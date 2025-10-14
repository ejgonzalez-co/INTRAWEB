<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Name Process'):</dt>
   <dd class="col-9">@{{ dataShow.name_process }}.</dd>
</div>

<div class="row">
   <!-- Description Field -->
   <dt class="text-inverse text-left col-3">@lang('Description'):</dt>
   <dd class="col-9">@{{ dataShow.description }}.</dd>
</div>


<div class="row">
   <!-- Estimated Total Value Field -->
   <dt class="text-inverse text-left col-3">@lang('Estimated Total Value'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.estimated_total_value) }}.</dd>
</div>


<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3">@lang('Observation'):</dt>
   <dd class="col-9">@{{ dataShow.observation }}</dd>
</div>

<div class="row">
   <!-- State Field -->
   <dt class="text-inverse text-left col-3">@lang('State'):</dt>
   <dd class="col-9">@{{ dataShow.state_name }}</dd>
</div>


