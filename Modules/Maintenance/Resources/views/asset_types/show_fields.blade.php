<!-- Name Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>

<!-- Form type Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Tipo de formulario'):</dt>
<dd class="col-9 text-truncate" v-if="dataShow.form_type == 1">Formulario hoja de vida de los vehículos y maquinaria amarilla.</dd>
<dd class="col-9 text-truncate" v-if="dataShow.form_type == 2">Formulario hoja de vida de los equipos menores.</dd>
<dd class="col-9 text-truncate" v-if="dataShow.form_type == 3">Formulario hoja de vida del equipamiento y maquinaria (LECA).</dd>
<dd class="col-9 text-truncate" v-if="dataShow.form_type == 4">Formulario hoja de vida del equipamiento (LECA).</dd>
<dd class="col-9 text-truncate" v-if="dataShow.form_type == 5">Formulario inventario y cronograma del aseguramiento metrológico.</dd>

<!-- Description Field -->
<dt class="text-inverse text-left col-3 text-truncate">@lang('Description'):</dt>
<dd class="col-9 text-truncate">@{{ dataShow.description }}.</dd>


