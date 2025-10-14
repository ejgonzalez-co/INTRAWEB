<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <br>
   <div class="row">
       <!-- Code Field -->
       <dt class="text-inverse text-left col-3 text-truncate">@lang('Code'):</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.code }}.</dd>
   </div>

   <div class="row">
       <!-- Name Field -->
       <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
   </div>

   <div class="row">
       <!-- State Field -->
       <dt class="text-inverse text-left col-3 text-truncate">@lang('State'):</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.status }}.</dd>
   </div>
   
   <div class="row">
       <dt class="text-inverse text-left col-3 text-truncate">@lang('User') creador:</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.name_user }}.</dd>
   </div>

   <div class="row">
       <dt class="text-inverse text-left col-3 text-truncate">@lang('Created_at'):</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.date_created }}.</dd>
   </div>

   <div class="row">
       <dt class="text-inverse text-left col-3 text-truncate">@lang('Hora de creación'):</dt>
       <dd class="col-9 text-truncate">@{{ dataShow.time_created }}.</dd>
   </div>
   <br>
</div>


