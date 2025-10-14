<!-- Name Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>


<!-- Description Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>


<!-- State Field -->
<dt class="text-inverse text-justify col-3 text-truncate">@lang('State'):</dt>
<dd v-if="dataShow.state==1" class="col-9 text-truncate">Activo</dd>
<dd v-else class="col-9 text-truncate">Inactivo</dd>


