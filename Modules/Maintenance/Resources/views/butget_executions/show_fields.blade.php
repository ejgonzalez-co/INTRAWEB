<div class="row">
   <!-- Minutes Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Minutes'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.minutes }}.</dd>
</div>


<div class="row">
   <!-- Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Date'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.date }}.</dd>
</div>


<div class="row">
   <!-- Executed Value Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Executed Value'):</dt>
   <dd class="col-9 text-truncate">$@{{ currencyFormat(dataShow.executed_value) }}.</dd>
</div>


<div class="row">
   <!-- New Value Available Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('New Value Available'):</dt>
   <dd class="col-9 text-truncate">$@{{ currencyFormat(dataShow.new_value_available )}}.</dd>
</div>


<div class="row">
   <!-- Percentage Execution Item Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Percentage Execution Item'):</dt>
   <dd class="col-9 text-truncate">@{{ currencyFormat(dataShow.percentage_execution_item )}}%.</dd>
</div>

<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.observation }}</dd>
</div>

