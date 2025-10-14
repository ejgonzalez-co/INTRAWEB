<div class="form-group row m-b-15">
      <!-- Register Date Field -->
      <dt class="text-inverse text-left col-3 text-truncate">@lang('register_date'):</dt>
      <dd class="col-3 text-truncate">@{{ formatDate(dataShow.created_at) }}.</dd>
      <!-- Element Name Field -->
      <dt class="text-inverse text-left col-3 text-truncate">@lang('element_name'):</dt>
      <dd class="col-3 text-truncate">@{{ dataShow.element_name }}.</dd>
</div>

<div class="form-group row m-b-15">
   <!-- Register Date Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Grupo: </dt>
   <dd class="col-3 text-truncate">@{{ dataShow.group }}.</dd>
   <!-- Element Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">Componente: </dt>
   <dd class="col-3 text-truncate">@{{ dataShow.component }}.</dd>
</div>

<div class="form-group row m-b-15">
   <!-- Rank Higher Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('rank_higher'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.rank_higher }}.</dd>

   <!-- Rank Lower Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('rank_lower'):</dt>
   <dd class="col-3 text-truncate">@{{ dataShow.rank_lower }}.</dd>
</div>

<div class="form-group row m-b-15">
   <!-- Observation Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.observation }}.</dd>
</div>


