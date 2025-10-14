<div class="row">
   <!-- Users Id Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('analyst'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.users ? dataShow.users.name: ''}}.</dd>
</div>


<div class="row">
   <!-- Lc List Trials Id Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('types_of_routines'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.lc_list_trials ? dataShow.lc_list_trials.name: ''}}.</dd>
</div>


