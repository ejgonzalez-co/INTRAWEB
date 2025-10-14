<!-- Type Document Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>

<dt class="text-inverse text-justify col-3 text-truncate">@lang('Attached'):</dt>
<a class="col-9 text-truncate"  v-if="dataShow.url_document"  :href="'{{ asset('storage') }}/'+dataShow.url_document" target="_blank">Ver adjunto</a>
