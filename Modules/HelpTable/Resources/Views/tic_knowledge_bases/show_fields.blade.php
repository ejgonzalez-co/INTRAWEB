<!-- Created_at Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Created_at'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.created_at }}.</dd>

<!-- Ht Tic Type Request Id Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Type Knowledge'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.tic_type_request? dataShow.tic_type_request.name : '' }}.</dd>

<!-- Users Id Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Registrado por'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.users? dataShow.users.name : '' }}.</dd>

<!-- Affair Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Affair'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.affair }}.</dd>

<!-- Knowledge Description Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Description'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.knowledge_description }}.</dd>

<!-- Knowledge Description Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Adjuntos'):</dt>
<dd v-if="dataShow.attached" class="col-sm-9 col-md-9 col-lg-9"><span v-for="Document in dataShow.attached.split(',')">
    <a :href="'{{ asset('storage') }}/'+Document" target="_blank">Ver adjunto</a><br/>
    </span>
</dd>

<!-- Knowledge State Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('State'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.knowledge_state_name }}.</dd>

<div v-if="dataShow.enlace_youtube">
    <iframe :src="dataShow.enlace_youtube" width="790" height="600" style="border:1px solid #ccc;" frameborder="0" allowfullscreen style="width: 100%; height: 100%;">
        Tu navegador no soporta iframes.
    </iframe>
</div>

