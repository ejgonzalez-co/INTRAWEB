<!-- Panel -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
       <h3 class="panel-title"><strong>Tipo de documento, correspondencia recibida</strong></h3>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">

      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Name'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.name }}.</dd>
     </div>

      
      <div class="row">
         <!-- State Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('State'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.state == 1 ? 'Activo' : 'Inactivo' }}.</dd>

      </div>
      
   </div>

</div>

