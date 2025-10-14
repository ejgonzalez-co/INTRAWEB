<div class="row">
   <!-- Name Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
</div>

<div class="row">
   <!-- Description Field -->
   <dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
   <dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>
</div>

<div class="row">
   <!-- Url Document Fuel Field -->
   <dt class="text-inverse text-left col-3 ">Documentos:</dt>

   <span v-for="documento in dataShow.url_document_fuel?.split(',')" style="margin-left: -15px;">
            <a
            class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver
            adjunto</a>
      {{-- <
      class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver
      adjunto</a><br /> --}}
   </span>

</div>


