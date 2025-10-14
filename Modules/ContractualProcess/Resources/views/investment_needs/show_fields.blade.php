<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Name Process'):</dt>
   <dd class="col-9">@{{ dataShow.name_process }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Description'):</dt>
   <dd class="col-9">@{{ dataShow.description }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Unit'):</dt>
   <dd class="col-9">@{{ dataShow.unit }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Quantity'):</dt>
   <dd class="col-9">@{{ dataShow.quantity }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Unit Value'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.unit_value) }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Total Value'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.total_value) }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Aqueduct'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.aqueduct) }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">% @lang('Aqueduct'):</dt>
   <dd class="col-9">@{{ dataShow.percentage_aqueduct }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Sewerage'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.sewerage) }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">% @lang('Sewerage'):</dt>
   <dd class="col-9">@{{ dataShow.percentage_sewerage }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">@lang('Cleanliness'):</dt>
   <dd class="col-9">@{{ '$ '+currencyFormat(dataShow.cleanliness) }}.</dd>
</div>

<div class="row">
   <dt class="text-inverse text-left col-3">% @lang('Cleanliness'):</dt>
   <dd class="col-9">@{{ dataShow.percentage_cleanliness }}.</dd>
</div>

<div class="row">
   <!-- State Field -->
   <dt class="text-inverse text-left col-3">@lang('State'):</dt>
   <dd class="col-9">@{{ dataShow.state_name }}</dd>
</div>