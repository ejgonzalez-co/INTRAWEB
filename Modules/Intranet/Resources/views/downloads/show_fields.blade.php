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

            <!-- Intranet Downloads Categories Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Categoría'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.categorie?.title }}.</dd>

            <!-- Filename Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Archivo'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.filename" style="word-break: break-all;" target="_blank">@{{ dataShow.filename?.split("/").pop() }}</a>.</dd>

            <!-- Filename Play Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Archivo para reproducir'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.filename_play" style="word-break: break-all;" target="_blank">@{{ dataShow.filename_play }}</a>.</dd> --}}

            <!-- Filename Preview Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Archivo para la vista previa'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.filename_preview" style="word-break: break-all;" target="_blank">@{{ dataShow.filename_preview }}</a>.</dd> --}}

            <!-- Image Filename Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Ícono'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">
                <a v-if="dataShow.image_filename" :href="'/storage/'+dataShow.image_filename" target="_blank"><img :src="'/storage/'+dataShow.image_filename" alt="Ícono" style="width: 180px;"></a>
                <img v-else src="{{ asset('assets/img/components/sin_imagen.jpg')}}" alt="Ícono" style="width: 200px;">
            </dd>

            <!-- Image Filename Spec1 Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Ícono específico 1'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.image_filename_spec1" style="word-break: break-all;" target="_blank">@{{ dataShow.image_filename_spec1 }}</a>.</dd> --}}

            <!-- Image Filename Spec2 Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Ícono específico 2'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.image_filename_spec2" style="word-break: break-all;" target="_blank">@{{ dataShow.image_filename_spec2 }}</a>.</dd> --}}

            <!-- Image Download Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Imagen'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3"><a :href="'/storage/'+dataShow.image_download" style="word-break: break-all;" target="_blank">@{{ dataShow.image_download }}</a>.</dd> --}}

            <!-- Version Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Versión'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.version }}.</dd>

            <!-- Author Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Author'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.author }}.</dd>

            <!-- Author Url Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Sitio web del autor'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.author_url }}.</dd>

            <!-- Author Email Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Email del autor'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.author_email }}.</dd>

            <!-- License Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Licencia'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.download_license?.title }}.</dd> --}}

            <!-- License Url Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace a la licencia'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.license_url }}.</dd> --}}

            <!-- Intranet Downloads Licenses Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Confirmar licencia'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.download_license?.title }}.</dd>

            <!-- Directlink Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace directo'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.directlink }}.</dd> --}}

            <!-- Link External Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace externo'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.link_external }}.</dd> --}}

            <!-- Unaccessible File Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Mostrar archivo protejido'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.unaccessible_file }}.</dd> --}}

            <!-- Users Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Subido por'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.users?.name }}.</dd>

            <!-- Owner Id Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Creado por'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.owner?.name }}.</dd>

            <!-- Description Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Description'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3" style="white-space: break-spaces;">@{{ dataShow.description }}.</dd>

            <!-- Features Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Características'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3" style="white-space: break-spaces;">@{{ dataShow.features }}.</dd>

            <!-- Changelog Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Lista de cambios'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3" style="white-space: break-spaces;">@{{ dataShow.changelog }}.</dd>

            <!-- Notes Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Notas'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3" style="white-space: break-spaces;">@{{ dataShow.notes }}.</dd>
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

            <!-- Approved Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Autorizado'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.approved }}.</dd> --}}

            <!-- Date Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Date'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.created_at }}.</dd>

            <!-- Publish Up Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Comenzar publicación'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.publish_up }}.</dd>

            <!-- Publish Down Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Finalizar publicación'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.publish_down }}.</dd>

            <!-- Hits Field -->
            {{-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Descargas'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.hits }}.</dd> --}}
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
            <dd class="col-sm-3 col-md-3 col-lg-3" style="white-space: break-spaces;">@{{ dataShow.metadesc }}.</dd> --}}

            <!-- Metakey Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Metapalabras clave'):</dt>
            <dd class="col-sm-9 col-md-9 col-lg-9" style="white-space: break-spaces;">@{{ dataShow.metakey }}.</dd>
        </div>
    </div>
</div>

<!-- Panel Detalles del espejo -->
{{-- <div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Detalles del espejo</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Mirror1Link Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror1link }}.</dd>

            <!-- Mirror1Title Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Título (Enlace 1)'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror1title }}.</dd>

            <!-- Mirror1Target Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Etiqueta'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror1target }}.</dd>

            <!-- Mirror2Link Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Enlace'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror2link }}.</dd>

            <!-- Mirror2Title Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Título (Enlace 2)'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror2title }}.</dd>

            <!-- Mirror2Target Field -->
            <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3 pb-2">@lang('Etiqueta'):</dt>
            <dd class="col-sm-3 col-md-3 col-lg-3">@{{ dataShow.mirror2target }}.</dd>
        </div>
    </div>
</div> --}}

<!-- Panel Youtube -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Youtube</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Video Filename Field -->
            <dt class="text-inverse text-left col-6 pb-2">@lang('Enlace a videos en YouTube'):</dt>
            <dd class="col-6"><a :href="dataShow.video_filename" target="_blank">@{{ dataShow.video_filename }}.</a></dd>
            {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                @{{ dataShow.video_filename }}
            </button> --}}

        </div>
    </div>
</div>
