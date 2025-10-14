<div class="row">
   <!-- Name Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Nombre del documento'):</strong>
   <p class="col-9 text-break">@{{ dataShow.name }}.</p>
</div>


<div class="row">
   <!-- Description Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Description'):</strong>
   <p class="col-9 text-break">@{{ dataShow.description }}.</p>
</div>


<div class="row">
   <!-- Url Field -->
   <strong class="text-inverse text-left col-3 text-break">@lang('Adjunto'):</strong>
   <a class="ml-2" :href="'{{ asset('storage') }}/' + dataShow.url" target="_blank">Ver adjunto</a>
</div>


