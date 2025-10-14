<!-- Type Document Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Type Document'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.config_documents? dataShow.config_documents.name : '' }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>


<!--<img width="150" class="img-responsive" v-if="dataShow.url_document" :src="'{{ asset('storage') }}/'+dataShow.url_document" alt="">-->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Attached'):</dt>
<a class="col-9 text-truncate"  v-if="dataShow.url_document"  :href="'{{ asset('storage') }}/'+dataShow.url_document" target="_blank">Ver adjunto</a>


