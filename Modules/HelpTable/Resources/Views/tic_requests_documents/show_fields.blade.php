
<div class="row">
   <!-- Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>

      <!-- Description Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('observación'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>
 
   <!-- Url Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Adjunto'):</dt>
   <a class="col-3 ":href="'{{ asset('storage') }}/'+dataShow.url"target="_blank">Ver adjunto</a>

</div>




