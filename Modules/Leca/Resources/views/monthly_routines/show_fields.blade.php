<div class="row">
   <!-- Routine Start Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Routine Start Date'):</dt>
   <dd class="col-9 text-truncate">@{{ formatDate(dataShow.routine_start_date) }}.</dd>
</div>


<div class="row">
   <!-- Routine End Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Routine End Date'):</dt>
   <dd class="col-9 text-truncate">@{{ formatDate(dataShow.routine_end_date) }}.</dd>
</div>


<div class="row">
   <!-- State Routine Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('State Routine'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.state_routine }}.</dd>
</div>