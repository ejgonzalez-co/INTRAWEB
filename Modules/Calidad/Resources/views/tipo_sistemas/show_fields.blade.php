<div class="row">
    <!-- Nombre Sistema Field -->
    <dt class="text-inverse col-3 text-truncate">@lang('Nombre del sistema'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.nombre_sistema }}.</dd>
</div>


<div class="row" v-if="dataShow.descripcion">
    <!-- Descripcion Field -->
    <dt class="text-inverse col-3 text-truncate">@lang('Descripción'):</dt>
    <dd class="col-9">@{{ dataShow.descripcion }}.</dd>
</div>


<div class="row">
    <!-- Estado Field -->
    <dt class="text-inverse col-3 text-truncate">@lang('Estado'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.estado }}.</dd>
</div>


<div class="row">
    <!-- Usuario Creador Field -->
    <dt class="text-inverse col-3 text-truncate">@lang('Creador por'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.usuario_creador }}.</dd>
</div>
