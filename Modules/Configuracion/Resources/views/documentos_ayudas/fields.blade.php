<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Información inicial</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Nombre Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('nombre', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Nombre')` | lowercase }} del documento de ayuda</small>
                <div class="invalid-feedback" v-if="dataErrors.nombre">
                    <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Descripcion Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('descripcion', trans('Descripción').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion }", 'v-model' => 'dataForm.descripcion']) !!}
                <small>@lang('Enter the') una @{{ `@lang('Descripción')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.descripcion">
                    <p class="m-b-0" v-for="error in dataErrors.descripcion">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Documento de ayuda e imagen previa</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Documento Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('documento', trans('Documento de ayuda').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::file('documento', ['accept' => '.xlsx,.xls,.doc,.docx,.pdf,.mp4', '@change' => 'inputFile($event, "documento")', 'required' => false]) !!}
                <small>@lang('Select the') el @{{ `@lang('Documento')` | lowercase }} de ayuda</small>
                <div class="invalid-feedback" v-if="dataErrors.documento">
                    <p class="m-b-0" v-for="error in dataErrors.documento">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Imagen Previa Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('imagen_previa', trans('Imagen previa').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::file('imagen_previa', ['accept' => '.jpg,.jpeg,.png', '@change' => 'inputFile($event, "imagen_previa")', 'required' => false]) !!}
                <small>@lang('Select the') una @{{ `@lang('Imagen Previa')` | lowercase }} del documento de 320X180 píxeles</small>
                <div class="invalid-feedback" v-if="dataErrors.imagen_previa">
                    <p class="m-b-0" v-for="error in dataErrors.imagen_previa">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Videos de ayuda</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <dynamic-list label-button-add="Agregar URL" :data-list.sync="dataForm.videos_url_value" :is-remove="true"
            :data-list-options="[
                { label: 'URL', name: 'video_url', isShow: true}
            ]"
            class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
            <template #fields="scope">
                <!-- Video url Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('video_url', trans('URL') . ':', ['class' => 'col-form-label col-md-3',]) !!}
                    <div class="col-md-9">
                        {!! Form::url('video_url', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }", 'v-model' => 'scope.dataForm.video_url', 'required' => true]) !!}
                        <small>Ingrese la URL del video. Ej: https://www.youtube.com/</small>
                        <div class="invalid-feedback" v-if="dataErrors.video_url">
                            <p class="m-b-0" v-for="error in dataErrors.video_url">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </template>
        </dynamic-list>
    </div>
</div>
