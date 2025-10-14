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

            <!-- Link Ext Field -->
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('link_ext', trans('Enlace a sitio externo').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('link_ext', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.link_ext }", 'v-model' => 'dataForm.link_ext']) !!}
                        <small>@lang('Enter the') @{{ `@lang('enlace al sitio externo')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.link_ext">
                            <p class="m-b-0" v-for="error in dataErrors.link_ext">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Intranet Downloads Categories Id Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('intranet_downloads_categories_id', trans('Enlace a la categoría').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="intranet_downloads_categories_id" reduce-label="title" name-resource="get-download-categories" :value="dataForm" is-required="true"></select-check>
                        <small>@lang('Select the') @{{ `@lang('enlace a la categoría')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.intranet_downloads_categories_id">
                            <p class="m-b-0" v-for="error in dataErrors.intranet_downloads_categories_id">@{{ error }}</p>
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
                        <small>Ingrese la @{{ `@lang('Description')` | lowercase }}</small>
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
                    <div class="switcher col-md-9">
                        {!! Form::select('published', ["Publicado" => "Publicado", "Despublicado" => "Despublicado"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.published }", 'v-model' => 'dataForm.published', 'required' => true]) !!}
                        <small>@lang('Select the') @{{ `@lang('estado')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.published">
                            <p class="m-b-0" v-for="error in dataErrors.published">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>