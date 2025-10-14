<div class="row">
    <!-- Calidad Tipo Proceso Id Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Tipo de proceso'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.tipo_proceso?.nombre ?? 'N/A' }}.</dd>
</div>

<div class="row">
    <!-- Nombre Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Nombre'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.nombre }}.</dd>
</div>

<div class="row">
    <!-- Calidad Proceso Id Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Proceso padre'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.proceso?.nombre ?? 'N/A' }}.</dd>
</div>

<div class="row">
    <!-- Prefijo Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Prefijo'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.prefijo }}.</dd>
</div>

<div class="row">
    <!-- Dependencias Id Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Dependencia'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.dependencia?.nombre ?? 'N/A' }}.</dd>
</div>

<div class="row">
    <!-- Estado Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Estado'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.estado }}.</dd>
</div>

<div class="row">
    <!-- Orden Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Orden'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.orden }}.</dd>
</div>

<div class="row">
    <!-- Id Responsable Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Responsable'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.tipo_responsable+" "+dataShow.usuario_responsable }}.</dd>
</div>

<div class="row">
    <!-- Calidad Tipo Sistema Id Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Tipo de sistema'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.tipo_sistema?.nombre_sistema ?? 'N/A' }}.</dd>
</div>

<div class="row">
    <!-- Usuario Creador Field -->
    <dt class="text-inverseC col-3 text-truncate">@lang('Creador por'):</dt>
    <dd class="col-9 text-truncate">@{{ dataShow.usuario_creador }}.</dd>
</div>
