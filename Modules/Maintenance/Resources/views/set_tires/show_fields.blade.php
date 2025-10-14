<div class="row">
   <!-- Mant Tire Brand Id Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Mant Tire Brand Id'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.tire_brand ? dataShow.tire_brand.brand_name : ''}}.</dd>
</div>


<div class="row">
   <!-- Registration Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Registration Date'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.registration_date }}.</dd>
</div>


<div class="row">
   <!-- Tire Reference Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Tire Reference'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.tire_all  ? dataShow.tire_all.name : ''  }}.</dd>
</div>


<div class="row">
   <!-- Maximum Wear Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Máx desgaste para <br> reencauche'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.maximum_wear }}.</dd>
</div>


<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.observation }}.</dd>
</div>


