<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Title Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('title', trans('Title').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
                        <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.title">
                            <p class="m-b-0" v-for="error in dataErrors.title">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alias Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('alias', trans('Alias').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('alias', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.alias }", 'v-model' => 'dataForm.alias']) !!}
                        <small>@lang('Enter the') @{{ `@lang('Alias')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.alias">
                            <p class="m-b-0" v-for="error in dataErrors.alias">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Intranet Downloads Categories Id Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('intranet_downloads_categories_id', trans('Categoría').':', ['class' => 'col-form-label col-md-3 true required']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="intranet_downloads_categories_id" reduce-label="title" name-resource="get-download-categories" :value="dataForm" :is-required="true"></select-check>
                        <small>@lang('Seleccione la') @{{ `@lang('categoría')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.intranet_downloads_categories_id">
                            <p class="m-b-0" v-for="error in dataErrors.intranet_downloads_categories_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filename Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('filename', trans('Archivo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::file('filename', ['accept' => 'image/*', '@change' => 'inputFile($event, "filename")']) !!}
                        <small>@lang('Select the') @{{ `@lang('archivo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.filename">
                            <p class="m-b-0" v-for="error in dataErrors.filename">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filename Play Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('filename_play', trans('Archivo para reproducir').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('filename_play', ['accept' => 'image/*', '@change' => 'inputFile($event, "filename_play")']) !!}
                        <small>@lang('Select the') @{{ `@lang('archivo para reproducir')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.filename_play">
                            <p class="m-b-0" v-for="error in dataErrors.filename_play">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Filename Preview Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('filename_preview', trans('Archivo para la vista previa').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('filename_preview', ['accept' => 'image/*', '@change' => 'inputFile($event, "filename_preview")']) !!}
                        <small>@lang('Select the') @{{ `@lang('archivo para la vista previa')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.filename_preview">
                            <p class="m-b-0" v-for="error in dataErrors.filename_preview">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Image Filename Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('image_filename', trans('Ícono').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('image_filename', ['accept' => 'image/*', '@change' => 'inputFile($event, "image_filename")']) !!}
                        <small>@lang('Select the') @{{ `@lang('ícono')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.image_filename">
                            <p class="m-b-0" v-for="error in dataErrors.image_filename">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Filename Spec1 Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('image_filename_spec1', trans('Ícono especifico 1').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('image_filename_spec1', ['accept' => 'image/*', '@change' => 'inputFile($event, "image_filename_spec1")']) !!}
                        <small>@lang('Select the') @{{ `@lang('ícono especifico 1')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.image_filename_spec1">
                            <p class="m-b-0" v-for="error in dataErrors.image_filename_spec1">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Image Filename Spec2 Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('image_filename_spec2', trans('Ícono especifico 2').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('image_filename_spec2', ['accept' => 'image/*', '@change' => 'inputFile($event, "image_filename_spec2")']) !!}
                        <small>@lang('Select the') @{{ `@lang('ícono especifico 2')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.image_filename_spec2">
                            <p class="m-b-0" v-for="error in dataErrors.image_filename_spec2">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Image Download Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('image_download', trans('Imagen').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::file('image_download', ['accept' => 'image/*', '@change' => 'inputFile($event, "image_download")']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('imagen para el arhivo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.image_download">
                            <p class="m-b-0" v-for="error in dataErrors.image_download">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Version Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('version', trans('Version').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.version }", 'v-model' => 'dataForm.version']) !!}
                        <small>@lang('Ingrese la') @{{ `@lang('Version')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.version">
                            <p class="m-b-0" v-for="error in dataErrors.version">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Author Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('author', trans('Author').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('author', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.author }", 'v-model' => 'dataForm.author']) !!}
                        <small>@lang('Enter the') @{{ `@lang('Author')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.author">
                            <p class="m-b-0" v-for="error in dataErrors.author">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Author Url Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('author_url', trans('Sitio web del autor').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('author_url', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.author_url }", 'v-model' => 'dataForm.author_url']) !!}
                        <small>@lang('Enter the') @{{ `@lang('sitio web del autor')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.author_url">
                            <p class="m-b-0" v-for="error in dataErrors.author_url">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Author Email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('author_email', trans('Email del autor').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('author_email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.author_email }", 'v-model' => 'dataForm.author_email']) !!}
                        <small>@lang('Enter the') @{{ `@lang('Author Email')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.author_email">
                            <p class="m-b-0" v-for="error in dataErrors.author_email">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- License Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('license', trans('Licencia').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('license', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.license }", 'v-model' => 'dataForm.license']) !!}
                        <small>@lang('Enter the') @{{ `@lang('Licencia')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.license">
                            <p class="m-b-0" v-for="error in dataErrors.license">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- License Url Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('license_url', trans('Enlace a la licencia').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('license_url', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.license_url }", 'v-model' => 'dataForm.license_url']) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace a la licencia')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.license_url">
                            <p class="m-b-0" v-for="error in dataErrors.license_url">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Intranet Downloads Licenses Id Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('intranet_downloads_licenses_id', trans('Confirmar licencia').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="intranet_downloads_licenses_id" reduce-label="title" name-resource="get-licenses" :value="dataForm" :is-required="true"></select-check>
                        <small>@lang('Seleccione') @{{ `@lang('si tiene que aceptar acuredos de licencia')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.intranet_downloads_licenses_id">
                            <p class="m-b-0" v-for="error in dataErrors.intranet_downloads_licenses_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Directlink Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('directlink', trans('Enlace directo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::select('directlink', ["Si" => "Si", "No" => "No"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.directlink }", 'v-model' => 'dataForm.directlink', 'required' => true]) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace directo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.directlink">
                            <p class="m-b-0" v-for="error in dataErrors.directlink">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Link External Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('link_external', trans('Enlace externo').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('link_external', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.link_external }", 'v-model' => 'dataForm.link_external']) !!}
                        <small>@lang('Enter the') @{{ `@lang('Link External')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.link_external">
                            <p class="m-b-0" v-for="error in dataErrors.link_external">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Unaccessible File Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('unaccessible_file', trans('Mostrar archivo protegido').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::select('unaccessible_file', ["Si" => "Si", "No" => "No"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.unaccessible_file }", 'v-model' => 'dataForm.unaccessible_file', 'required' => true]) !!}
                        <small>@lang('Seleccione') @{{ `@lang('si muestra o no el archivo protegido')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.unaccessible_file">
                            <p class="m-b-0" v-for="error in dataErrors.unaccessible_file">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Users Id Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('users_id', trans('Subido por').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="users_id" reduce-label="name" name-resource="get-users" :value="dataForm" :is-required="true"></select-check>
                        <small>@lang('Select the') @{{ `@lang('usuario que subió el archivo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.users_id">
                            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Owner Id Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('owner_id', trans('Creado por').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="owner_id" reduce-label="name" name-resource="get-users" :value="dataForm" ></select-check>
                        <small>@lang('Select the') @{{ `@lang('usuario creador del archivo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.owner_id">
                            <p class="m-b-0" v-for="error in dataErrors.owner_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description']) !!}
                        <small>@lang('Ingrese la') @{{ `@lang('Description')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.description">
                            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('features', trans('Caraterísticas').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('features', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.features }", 'v-model' => 'dataForm.features']) !!}
                        <small>@lang('Ingrese las') @{{ `@lang('características')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.features">
                            <p class="m-b-0" v-for="error in dataErrors.features">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changelog Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('changelog', trans('Lista de cambios').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('changelog', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.changelog }", 'v-model' => 'dataForm.changelog']) !!}
                        <small>@lang('Ingrese la') @{{ `@lang('lista de cambios')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.changelog">
                            <p class="m-b-0" v-for="error in dataErrors.changelog">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('notes', trans('Notas').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('notes', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.notes }", 'v-model' => 'dataForm.notes']) !!}
                        <small>@lang('Ingrese las') @{{ `@lang('notas')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.notes">
                            <p class="m-b-0" v-for="error in dataErrors.notes">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Detalles de la publicación</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Published Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('published', trans('Publicado').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::select('published', ["Publicado" => "Publicado", "Despublicado" => "Despublicado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.published }", 'v-model' => 'dataForm.published', 'required' => true]) !!}
                        <small>@lang('Seleccione el') @{{ `@lang('estado')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.published">
                            <p class="m-b-0" v-for="error in dataErrors.published">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- download_tags_selected -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('accessgroupid', trans('Etiquetas').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <multiselect-component
                            key-model="tags_selected"
                            :close-on-select="false"
                            name-field="download_tags_selected"
                            name-resource="/intranet/get-downloads-tags"
                            :is-multiple="true"
                            reduce-label="title"
                            :value="dataForm">
                        </multiselect-component>
                        <small>@lang('Seleccione las') @{{ `@lang('etiquetas')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.accessgroupid">
                            <p class="m-b-0" v-for="error in dataErrors.accessgroupid">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('approved', trans('Autorizado').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        {!! Form::select('approved', ["Autorizado" => "Autorizado", "No autorizado" => "No autorizado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.approved }", 'v-model' => 'dataForm.approved', 'required' => true]) !!}
                        <small>@lang('Seleccione') @{{ `@lang('si el documento esta autorizado o no')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.approved">
                            <p class="m-b-0" v-for="error in dataErrors.approved">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Date Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('date', trans('Date').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::datetimelocal('date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date }", 'id' => 'date', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.date']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('fecha')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.date">
                            <p class="m-b-0" v-for="error in dataErrors.date">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Publish Up Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('publish_up', trans('Comenzar Publicación').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::date('publish_up', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.publish_up }", 'id' => 'publish_up', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.publish_up']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('fecha para comenzar la publicación')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.publish_up">
                            <p class="m-b-0" v-for="error in dataErrors.publish_up">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publish Down Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('publish_down', trans('Finalizar Publicación').':', ['class' => 'col-form-label col-md-3']) !!}
                    
                    <div class="col-md-9">
                        {!! Form::date('publish_down', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.publish_down }", 'id' => 'publish_down', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.publish_down']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('fecha de finalización de publicación')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.publish_down">
                            <p class="m-b-0" v-for="error in dataErrors.publish_down">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hits Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('hits', trans('Descargas').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('hits', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.hits }", 'v-model' => 'dataForm.hits']) !!}
                        <small>@lang('Ingrese el') @{{ `@lang('número de descargas del documento')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.hits">
                            <p class="m-b-0" v-for="error in dataErrors.hits">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Opciones de los metadatos</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Metadesc Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('metadesc', trans('Metadescripción').':', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::textarea('metadesc', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.metadesc }", 'v-model' => 'dataForm.metadesc']) !!}
                        <small>@lang('Ingrese la') @{{ `@lang('metadescripción')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.metadesc">
                            <p class="m-b-0" v-for="error in dataErrors.metadesc">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Metakey Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('metakey', trans('Metapalabras clave').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('metakey', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.metakey }", 'v-model' => 'dataForm.metakey']) !!}
                        <small>@lang('Ingrese las') @{{ `@lang('metapalabras clave')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.metakey">
                            <p class="m-b-0" v-for="error in dataErrors.metakey">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Detalles del espejo</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Mirror1Link Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror1link', trans('Enlace').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mirror1link', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror1link }", 'v-model' => 'dataForm.mirror1link']) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror1link">
                            <p class="m-b-0" v-for="error in dataErrors.mirror1link">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mirror1Title Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror1title', trans('Título (Enlace 1)').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mirror1title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror1title }", 'v-model' => 'dataForm.mirror1title']) !!}
                        <small>@lang('Enter the') @{{ `@lang('título del enlace 1')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror1title">
                            <p class="m-b-0" v-for="error in dataErrors.mirror1title">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mirror1Target Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror1target', trans('Etiqueta').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('mirror1target', ["_self" => "Abrir en esta ventana/marco (_self)", "_blank" => "Abrir en una nueva ventana (_blank)", "_parent" => "Abrir en una ventana/marco principal (_parent)", "_top" => "Abrir en marco superior (sustituye a todos los marcos) (_top)"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror1target }", 'v-model' => 'dataForm.mirror1target']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('etiqueta')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror1target">
                            <p class="m-b-0" v-for="error in dataErrors.mirror1target">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mirror2Link Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror2link', trans('Enlace').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mirror2link', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror2link }", 'v-model' => 'dataForm.mirror2link']) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror2link">
                            <p class="m-b-0" v-for="error in dataErrors.mirror2link">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mirror2Title Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror2title', trans('Título (Enlace 2)').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('mirror2title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror2title }", 'v-model' => 'dataForm.mirror2title']) !!}
                        <small>@lang('Enter the') @{{ `@lang('título del enlace 2')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror2title">
                            <p class="m-b-0" v-for="error in dataErrors.mirror2title">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mirror2Target Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('mirror2target', trans('Etiqueta').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::select('mirror2target', ["_self" => "Abrir en esta ventana/marco (_self)", "_blank" => "Abrir en una nueva ventana (_blank)", "_parent" => "Abrir en una ventana/marco principal (_parent)", "_top" => "Abrir en marco superior (sustituye a todos los marcos) (_top)"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mirror2target }", 'v-model' => 'dataForm.mirror2target']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('etiqueta')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.mirror2target">
                            <p class="m-b-0" v-for="error in dataErrors.mirror2target">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Youtube</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Video Filename Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('video_filename', trans('Enlace a video en YouTube').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('video_filename', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.video_filename }", 'v-model' => 'dataForm.video_filename']) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace a video en YouTube')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.video_filename">
                            <p class="m-b-0" v-for="error in dataErrors.video_filename">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>