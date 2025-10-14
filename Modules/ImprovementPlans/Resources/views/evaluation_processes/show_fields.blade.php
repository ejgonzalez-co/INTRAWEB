<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <div class="panel-body">
       <!-- Name Field -->
       <div class="row">
           <strong class="text-inverse text-left col-3 text-break">@lang('Name'):</strong>
           <p class="col-9 text-break">@{{ dataShow.name }}.</p>
       </div>

       <!-- State Field -->
       <div class="row">
           <strong class="text-inverse text-left col-3 text-break">@lang('State'):</strong>
           <p class="col-9 text-break">@{{ dataShow.status }}.</p>
       </div>

       <div class="row">
           <strong class="text-inverse text-left col-3 text-break">@lang('User') creador:</strong>
           <p class="col-9 text-break">@{{ dataShow.user ? dataShow.user.name : '' }}.</p>
       </div>

   </div>
</div>