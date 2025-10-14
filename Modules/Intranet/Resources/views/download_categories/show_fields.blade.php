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
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.title }}.</dd>


            <!-- Alias Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Alias'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.alias }}.</dd> --}}


            <!-- Parent Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Categoría principal'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.parentid?.title }}.</dd>


            <!-- Ordering Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Orden'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.ordering }}.</dd>

            <hr style="width: 100%;" />

            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <th colspan="2">
                                <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Grupos con acceso</strong></h3>
                            </th>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.download_access_groups">
                                <td>@{{ document.created_at }}</td>
                                <td>@{{ document.work_group?.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="width: 100%;" />

            <div class="row" style="margin: auto;">
                <div class="table-responsive">
                    <table class="table table-responsive table-bordered">
                        <thead>
                            <th colspan="2">
                                <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Usuarios con derechos de acceso</strong></h3>
                            </th>
                            <tr>
                                <th>Fecha</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(document, key) in dataShow.download_access_users">
                                <td>@{{ document.created_at }}</td>
                                <td>@{{ document.user?.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr style="width: 100%;" />

            <!-- Description Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Description'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9" style="white-space: break-spaces;">@{{ dataShow.description }}.</dd>
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
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.published }}.</dd>


            <!-- Date Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Date'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.created_at }}.</dd>
        </div>
    </div>
</div>
<!-- Panel Opciones de los metadatos -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Opciones de los metadatos</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Metadesc Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Metadescripción'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.metadesc }}.</dd> --}}


            <!-- Metakey Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Metapalabras clave'):</dt>
            <dd class="col-6" style="white-space: break-spaces;">@{{ dataShow.metakey }}.</dd>
        </div>
    </div>
</div>
