<div class="row">
   <!-- Sampling Date Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Sampling Date'):</dt>
   <dd class="col-9 ">@{{ formatDate(dataShow.sampling_date) }}</dd>
</div>


<div class="row">
   <!-- Lc Sample Points Id Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Lc Sample Points Id'):</dt>
   <dd class="col-9 ">@{{ dataShow.lc_sample_points ? dataShow.lc_sample_points.point_location: '' }}</dd>
</div>

<div class="row" v-if="dataShow.direction">
   <!-- Direction Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Direction'):</dt>
   <dd class="col-9 ">@{{ dataShow.direction }}</dd>
</div>


<div class="row">
   <!-- Lc Officials Id Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Lc Officials Id'):</dt>
   <dd class="col-9 ">@{{ dataShow.users_name   }}</dd>
</div>

<div class="row">
   <!-- Lc Officials Id Field -->
   <dt class="text-inverse text-left col-3 ">Usuario creador:</dt>
   <dd class="col-9 ">@{{ dataShow.user_creador   }}</dd>
</div>

<div class="row">
   <!-- Lc Officials Id Field -->
   <dt class="text-inverse text-left col-3 ">¿ Aplica para duplicado ?:</dt>
   <dd class="col-9 ">@{{ dataShow.duplicado   }}</dd>
</div>

<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 ">Ensayos:</dt>
   <dd class="col-9 ">@{{ dataShow.mensaje }}</dd>
</div>


<div class="row">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Observation'):</dt>
   <dd class="col-9 ">@{{ dataShow.observation }}</dd>
</div>


