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
            <div class="col-md-12">
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

            <!-- Parent Id Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('parent_id', trans('Categoría principal').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="parent_id" reduce-label="title" name-resource="get-download-categories" :value="dataForm" ></select-check>
                        <small>@lang('Seleccione la') @{{ `@lang('categoría principal')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.parent_id">
                            <p class="m-b-0" v-for="error in dataErrors.parent_id">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accessgroupid Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('accessgroupid', trans('Grupos con acceso').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <multiselect-component
                            key-model="group_id"
                            :close-on-select="false"
                            name-field="download_access_groups"
                            name-resource="/intranet/get-work-groups"
                            :is-multiple="true"
                            :value="dataForm">
                        </multiselect-component>
                        <small>@lang('Seleccione los') @{{ `@lang('grupos con acceso')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.accessgroupid">
                            <p class="m-b-0" v-for="error in dataErrors.accessgroupid">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accessuserid Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('accessuserid', trans('Usuarios con derechos de acceso').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <multiselect-component
                            key-model="user_id"
                            :close-on-select="false"
                            name-field="download_access_users"
                            name-resource="/intranet/get-users"
                            :is-multiple="true"
                            :value="dataForm">
                        </multiselect-component>
                        <small>@lang('Seleccione los') @{{ `@lang('derechos de acceso')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.accessuserid">
                            <p class="m-b-0" v-for="error in dataErrors.accessuserid">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Field -->
            <div class="col-md-12">
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
        </div>
    </div>
    <!-- end panel-body -->
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
            <!--  Published Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('published', trans('Publicado').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <!-- published switcher -->
                    <div class="col-md-9">
                        {!! Form::select('published', ["Publicado" => "Publicado", "Despublicado" => "Despublicado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.published }", 'v-model' => 'dataForm.published', 'required' => true]) !!}
                        <small>@lang('Select the') @{{ `@lang('estado')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.published">
                            <p class="m-b-0" v-for="error in dataErrors.published">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('date', trans('Date').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::datetimelocal('date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date }", 'id' => 'date',
                        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date']) !!}
                        <small>@lang('Seleccione la') @{{ `@lang('Date')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.date">
                            <p class="m-b-0" v-for="error in dataErrors.date">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- end panel-body -->
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
    <!-- end panel-body -->
</div>