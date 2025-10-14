

<div class="row">
   <!-- Nombre Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Nombre'):</dt>
   <dd class="col-9 ">@{{ dataShow.nombre }}.</dd>
</div>

<div class="row">
   <!-- Attachment Field -->
      <dt class="text-inverse text-left col-3">Adjunto:</dt>
      <dd class="col-9 text-truncate">
      <span  style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+dataShow.adjunto" target="_blank">Ver adjunto</a><br/></span></dd>
   </div>
