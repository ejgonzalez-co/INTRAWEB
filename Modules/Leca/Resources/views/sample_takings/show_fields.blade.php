<div class="row mt-4">
   <!-- Lc Start Sampling Id Field -->
   <dt class="text-inverse text-left col-3 ">@lang('Lc Start Sampling Id'):</dt>
   <dd class="col-9">@{{ formatDate(dataShow.created_at) }}.</dd>
</div>


{{-- <div class="row">
   <!-- Lc Sample Points Id Field -->
   <dt class="text-inverse text-left col-3">@lang('Lc Sample Points Id'):</dt>
   <dd class="col-9">@{{ dataShow.lc_sample_points_id ? dataShow.lc_sample_points_id.point_location: ''}}.</dd>
</div> --}}


{{-- <div class="row">
   <!-- Users Id Field -->
   <dt class="text-inverse text-left col-3">@lang('Users Id'):</dt>
   <dd class="col-9">@{{ dataShow.users_id }}.</dd>
</div> --}}


<div class="row mt-4">
   <!-- User Name Field -->
   <dt class="text-inverse text-left col-3">@lang('Lc Officials Id'):</dt>
   <dd class="col-9">@{{ dataShow.user_name }}.</dd>
</div>


<div class="row mt-4">
   <!-- Sample Reception Code Field -->
   <dt class="text-inverse text-left col-3">@lang('Sample Reception Code'):</dt>
   <dd class="col-9">@{{ dataShow.sample_reception_code }}.</dd>
</div>


<div class="row mt-4">
   <!-- Type Water Field -->
   <dt class="text-inverse text-left col-3">@lang('Type Water'):</dt>
   <dd class="col-9">@{{ dataShow.type_water }}.</dd>
</div>


<div class="row mt-4">
   <!-- Humidity Field -->
   <dt class="text-inverse text-left col-3">@lang('Humidity'):</dt>
   <dd class="col-9">@{{ dataShow.humidity }}.</dd>
</div>


<div class="row mt-4">
   <!-- Temperature Field -->
   <dt class="text-inverse text-left col-3">@lang('Temperature'):</dt>
   <dd class="col-9">@{{ dataShow.temperature }}.</dd>
</div>


<div class="row mt-4">
   <!-- Hour From To Field -->
   <dt class="text-inverse text-left col-3">@lang('Hour From To'):</dt>
   <dd class="col-9">@{{ dataShow.hour_from_to }}.</dd>
</div>


<div class="row mt-4">
   <!-- Prevailing Climatic Characteristics Field -->
   <dt class="text-inverse text-left col-3">@lang('Prevailing Climatic Characteristics'):</dt>
   <dd class="col-9">@{{ dataShow.prevailing_climatic_characteristics }}.</dd>
</div>

<div class="row mt-4">
   <!-- Prevailing Climatic Characteristics Field -->
   <dt class="text-inverse text-left col-3">@lang('pH promedio'):</dt>
   <dd class="col-9">@{{ dataShow.ph_promedio }}.</dd>
</div>

<div class="row mt-4">
   <!-- Prevailing Climatic Characteristics Field -->
   <dt class="text-inverse text-left col-3">@lang('Rango de temperatura en *c(pH)'):</dt>
   <dd class="col-9">@{{ dataShow.temperatura_promedio }}.</dd>
</div>


<div class="row mt-4">
   <!-- Test Perform Field -->
   <dt class="text-inverse text-left col-3">@lang('Test Perform'):</dt><br>
</div>

<div class="row mt-12">
   <!-- Test Perform Field -->
   <ul style="list-style-type: circle;">
      <li v-for="test in dataShow.lc_list_trials_two"  class="col-12">@{{ test.name }}.</li>
  </ul>
  
   
</div>


<div class="row mt-4">
   <!-- Container Number Field -->
   <dt class="text-inverse text-left col-3">@lang('Container Number'):</dt>
   <dd class="col-9">@{{ dataShow.container_number }}.</dd>
</div>


<div class="row mt-4">
   <!-- Hour Field -->
   <dt class="text-inverse text-left col-3">@lang('Hour'):</dt>
   <dd class="col-9">@{{ dataShow.hour }}.</dd>
</div>


<div class="row mt-4">
   <!-- According Field -->
   <dt class="text-inverse text-left col-3">@lang('According'):</dt>
   <dd class="col-9">@{{ dataShow.according }}.</dd>
</div>


<div class="row mt-4">
   <!-- Sample Characteristics Field -->
   <dt class="text-inverse text-left col-3">@lang('Sample Characteristics'):</dt>
   <dd class="col-9">@{{ dataShow.sample_characteristics }}.</dd>
</div>


<div class="row mt-4">
   <!-- Observations Field -->
   <dt class="text-inverse text-left col-3">@lang('Observations'):</dt>
   <dd class="col-9">@{{ dataShow.observations }}.</dd>
</div>

{{-- 
<div class="row">
   <!-- Refrigeration Field -->
   <dt class="text-inverse text-left col-3">@lang('Refrigeration'):</dt>
   <dd class="col-9">@{{ dataShow.refrigeration }}.</dd>
</div>


<div class="row">
   <!-- Filtered Sample Field -->
   <dt class="text-inverse text-left col-3">@lang('Filtered Sample'):</dt>
   <dd class="col-9">@{{ dataShow.filtered_sample }}.</dd>
</div>


<div class="row">
   <!-- Hno3 Field -->
   <dt class="text-inverse text-left col-3">@lang('Hno3'):</dt>
   <dd class="col-9">@{{ dataShow.hno3 }}.</dd>
</div>


<div class="row">
   <!-- H2So4 Field -->
   <dt class="text-inverse text-left col-3">@lang('H2So4'):</dt>
   <dd class="col-9">@{{ dataShow.h2so4 }}.</dd>
</div>


<div class="row">
   <!-- Hci Field -->
   <dt class="text-inverse text-left col-3">@lang('Hci'):</dt>
   <dd class="col-9">@{{ dataShow.hci }}.</dd>
</div>


<div class="row">
   <!-- Naoh Field -->
   <dt class="text-inverse text-left col-3">@lang('Naoh'):</dt>
   <dd class="col-9">@{{ dataShow.naoh }}.</dd>
</div> --}}

{{-- 
<div class="row">
   <!-- Acetate Field -->
   <dt class="text-inverse text-left col-3">@lang('Acetate'):</dt>
   <dd class="col-9">@{{ dataShow.acetate }}.</dd>
</div> --}}


{{-- <div class="row">
   <!-- Ascorbic Acid Field -->
   <dt class="text-inverse text-left col-3">@lang('Ascorbic Acid'):</dt>
   <dd class="col-9">@{{ dataShow.ascorbic_acid }}.</dd>
</div> --}}


<div class="row mt-4">
   <!-- Charge Field -->
   <dt class="text-inverse text-left col-3">@lang('Charge'):</dt>
   <dd class="col-9">@{{ dataShow.charge }}.</dd>
</div>


<div class="row mt-4">
   <!-- Process Field -->
   <dt class="text-inverse text-left col-3">@lang('Process'):</dt>
   <dd class="col-9">@{{ dataShow.process }}.</dd>
</div>


