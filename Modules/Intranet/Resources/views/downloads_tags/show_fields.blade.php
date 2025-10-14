<!-- Panel Información general -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Información general</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Title Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Title'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.title }}.</dd>


            <!-- Alias Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Alias'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.alias }}.</dd> --}}


            <!-- Link Ext Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace a sitio externo'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.link_ext }}.</dd> --}}


            <!-- Intranet Downloads Categories Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace a la categoría'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.download_categories?.title }}.</dd>


            <!-- Description Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Description'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.description }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Detalles de la publicación -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles de la publicación</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Published Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Publicado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3 ">@{{ dataShow.published }}.</dd>
        </div>
    </div>
</div>