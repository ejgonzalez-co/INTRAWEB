<!-- Name Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate" style="white-space: break-spaces;">@{{ dataShow.description }}.</dd>


<!-- Url Document Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Url Document'):</dt>
<dd v-if="dataShow.url_document" class="col-9 text-truncate">
    <span v-for="documento in dataShow.url_document.split(',')" style="margin-left: -15px;"><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+documento" target="_blank">Ver adjunto</a><br/></span>
</dd>


<!-- Mant Providers Id Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Proveedor'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.mant_providers ? dataShow.mant_providers.name : '' }}.</dd>
