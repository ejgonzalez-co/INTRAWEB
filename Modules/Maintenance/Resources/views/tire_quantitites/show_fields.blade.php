
<div class="row">
   <!-- Dependencias Id Field -->
   <dt class="text-inverse text-left col-3">@lang('process'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.dependencies ? dataShow.dependencies.nombre : null }}.</dd>
</div>

<div class="row">
   <!-- Mant Resume Machinery Vehicles Yellow Id Field -->
   <dt class="text-inverse text-left col-3">@lang('Name of the equipment or machinery'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.resume_machinery_vehicles_yellow ? dataShow.resume_machinery_vehicles_yellow.name_vehicle_machinery : null }}.</dd>
</div><br>

<div class="row">
   <!-- Plaque Field -->
   <dt class="text-inverse text-left col-3">@lang('Plaque'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.resume_machinery_vehicles_yellow ? dataShow.resume_machinery_vehicles_yellow.plaque : null }}.</dd>
</div>


<div class="row">
   <!-- Tire Quantity Field -->
   <dt class="text-inverse text-left col-3">Cantidad:</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.tire_quantity }}.</dd>
</div>


