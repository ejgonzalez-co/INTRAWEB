
   <div class="row">
      <!-- Name Field -->
      <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
      <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
   </div>
   <br>
   <div class="row">
      <!-- Name Field -->
      <dt class="text-inverse text-left col-3 text-truncate">@lang('Observation'):</dt>
      <dd class="col-9">@{{ dataShow.observation }}.</dd>
   </div>
   
   <br>
   <div class="row">
      <!-- Url Field -->
      <dt class="text-inverse text-left col-3 text-truncate">Documentos:</dt>
      <dd class="col-9 text-truncate">
      <span v-for="urlA in dataShow.url?.split(',')"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+urlA" target="_blank">Ver adjunto</a><br/></span>
      </dd>
   </div>





