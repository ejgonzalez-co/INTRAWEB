<!-- Title Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Título'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.title }}.</dd>


<!-- Alias Field -->
{{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Alias'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.alias }}.</dd> --}}


<!-- Description Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Contenido de la licencia'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.description }}.</dd>


<!-- Checked Out Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Usuario'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.usuario?.name }}.</dd>


<!-- Checked Out Time Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Fecha de modificación'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.checked_out_time }}.</dd>


<!-- Published Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Publicado'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.published }}.</dd>


<!-- Ordering Field -->
<dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Orden'):</dt>
<dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.ordering }}.</dd>


