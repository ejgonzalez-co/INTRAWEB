<!-- Panel Información general -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información y recursos de la ayuda</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Nombre Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Nombre'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 ">@{{ dataShow.nombre }}.</dd>
        </div>

        <div class="row">
            <!-- Descripcion Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Descripción'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.descripcion }}.</dd>
        </div>

        <div class="row">
            <!-- Documento Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 ">@lang('Documento'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9 "><a :href="'/storage/'+dataShow.documento" target="_blank">Ver documento</a></dd>
        </div>
    </div>
</div>

<!-- Panel Información Videos de apoyo -->
<div class="panel col-md-12" data-sortable-id="ui-general-1" v-if="dataShow.videos_url">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Videos de apoyo</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Insertar el código de inserción del video de YouTube aquí -->
            <iframe class="m-b-20" width="560" height="315" v-for="video in dataShow.videos_url?.split(',')" :src="video.indexOf('embed') !== -1 ? video.replace('watch?v=','') : video.replace('watch?v=','').replace(/\/([^\/]*)$/, '/embed/$1')" :title="dataShow.nombre" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
    </div>
</div>
