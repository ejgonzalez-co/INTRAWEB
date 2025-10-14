<!-- Panel Parametrización del consecutivo del expediente -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Expediente: @{{ dataShow.consecutivo }}</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Nombre Field -->
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Usuario que realizo la novedad:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.user_name ? dataShow.user_name : 'N/A' }}.</dd>

            <!-- Formato Consecutivo Field -->
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Modulo donde se realizó la novedad:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.modulo ? dataShow.modulo : 'N/A' }}.</dd>

            <!-- Nombre Field -->
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Detalle de la novedad:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.detalle_modificacion ? dataShow.detalle_modificacion : 'N/A' }}.</dd>

            <!-- Formato Consecutivo Field -->
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Expediente relacionado:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.consecutivo ? dataShow.consecutivo : 'N/A' }}.</dd>

            <!-- Estado Field -->
            <dt class="text-inverse col-sm-3 col-md-3 col-lg-3 ">Fecha en que se realizó la notificación:</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.created_at }}.</dd>
        </div>
    </div>
</div>