<!-- Name Company Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Name Company'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name_company }}.</dd>


<!-- Time Work Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Time work'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.time_work }}.</dd>


<!-- Observation Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Observation'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.observation }}.</dd>


<dt class="text-inverse text-justify col-3 text-truncate">@lang('Attached'):</dt>
<a class="col-9 text-truncate"  v-if="dataShow.url_document"  :href="'{{ asset('storage') }}/'+dataShow.url_document" target="_blank">Ver adjunto</a>


