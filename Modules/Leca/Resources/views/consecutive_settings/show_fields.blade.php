
<div class="row">
<!-- Coments Consecutive Field -->
   <dt class="text-inverse text-left col-3">@lang('Observación'):</dt>
   <dd class="col-9">@{{ dataShow.coments_consecutive }}.</dd>
</div>

<div class="row">
   <!-- User Name Field -->
      <dt class="text-inverse text-left col-3">@lang('Nombre de responsable'):</dt>
      <dd class="col-9">@{{ dataShow.user_name }}.</dd>
   </div>

<div class="row">
<!-- Date Report Field -->
   <dt class="text-inverse text-left col-3">@lang('Fecha de creación'):</dt>
   <dd class="col-9">@{{ formatDate(dataShow.date_report) }}.</dd>
</div>



