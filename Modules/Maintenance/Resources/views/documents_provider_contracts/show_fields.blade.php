<!-- Name Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>


<!-- Url Document Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Url Document'):</dt>
<dd class="col-9 text-truncate" v-if="dataShow.url_document"><span v-for="documento in dataShow.url_document.split(',')"><a :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver adjunto</a><br/></span></dd>

