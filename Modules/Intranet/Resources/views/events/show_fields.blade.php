<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Información general:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Type Events Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Type Event'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.type_events? dataShow.type_events.name : '' }}.</dd>

         <!-- Type Category Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Type Category'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.type_category_name }}.</dd>

         <!-- Title Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Title'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.title }}.</dd>

         <!-- Description Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Description'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.description }}.</dd>

         <!-- Initial Date Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Initial Date'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.initial_date }}.</dd>

         <!-- Initial Hour Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Initial Hour'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.initial_hour }}.</dd>

         <!-- End Hour Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('End Hour'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.end_hour }}.</dd>

         <!-- Duration Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Duration'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.duration_name }}.</dd>

         <!-- State Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('State'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.state_name }}.</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<!-- Panel Historial de cambios -->
<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Historial de cambios</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <div class="col-md-12">
               <div class="table-responsive">
                  <table class="table table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>@lang('Type Event')</th>
                           <th>@lang('Type Category')</th>
                           <th>@lang('Title')</th>
                           <th>@lang('Initial Date')</th>
                           <th>@lang('Initial Hour')</th>
                           <th>@lang('End Hour')</th>
                           <th>@lang('Duration')</th>
                           <th>@lang('State')</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(history, key) in dataShow.events_histories">
                           <td>@{{ getIndexItem(key) }}</td>
                           <td>@{{ history.type_events_name }}</td>
                           <td>@{{ history.type_category_name }}</td>
                           <td>@{{ history.title }}</td>
                           <td>@{{ history.initial_date }}</td>
                           <td>@{{ history.initial_hour }}</td>
                           <td>@{{ history.end_hour }}</td>
                           <td>@{{ history.duration_name }}</td>
                           <td>@{{ history.state_name }}</td>
                        </tr>
                     </tbody>
                  </table>
               </div>
         </div>
      </div>
   </div>
   <!-- end panel-body -->
</div>