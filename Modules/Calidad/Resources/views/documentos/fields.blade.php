<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title">Datos generales del documento</h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Titulo Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('titulo', trans('Titulo').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('titulo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.titulo }", 'v-model' => 'dataForm.titulo', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Titulo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.titulo">
                    <p class="m-b-0" v-for="error in dataErrors.titulo">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Formato Consecutivo Field -->
            {!! Form::label('codigo_formato', trans('Formato del consecutivo') . ':', [
                'class' => 'col-form-label col-md-3 required',
            ]) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="codigo_formato_value" reduce-label="nombre"
                    :value="dataForm" :is-required="true"
                    :enable-search="true" :is-multiple="true"
                    :options-list-manual="[{ id: 'prefijo_dependencia', nombre: 'Prefijo de la dependencia' },
                        { id: 'prefijo_tipo_proceso', nombre: 'Prefijo del Tipo de Proceso' },
                        { id: 'prefijo_proceso', nombre: 'Prefijo del Proceso' },
                        { id: 'prefijo_subproceso', nombre: 'Prefijo del Sub-Proceso' },
                        { id: 'orden_proceso', nombre: 'Número de Orden del Proceso' },
                        { id: 'prefijo_tipo_documento', nombre: 'Prefijo del Tipo de Documento)' },
                        { id: 'serie_documental', nombre: 'Serie documental' },
                        { id: 'subserie_documental', nombre: 'Subserie documental' },
                        { id: 'vigencia_actual', nombre: 'Año actual' },
                        { id: 'consecutivo_documento', nombre: 'Número de Orden de Documento' }
                    ]">
                </select-check>
                <small>Construya el formato del consecutivo. Ejemplo: Prefijo de la dependencia-Serie documental-Orden del consecutivo (SIS-090-1).<br>
                        Tenga que cuenta sí el módulo tiene habilitada la clasificación documental para el uso de las variables Serie documental y Subserie documental.</small>
                <div class="invalid-feedback" v-if="dataErrors.codigo_formato">
                    <p class="m-b-0" v-for="error in dataErrors.codigo_formato">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Separador Consecutivo Field -->
            {!! Form::label('separador_consecutivo', trans('Separador del consecutivo') . ':', [
                'class' => 'col-form-label col-md-3 required',
            ]) !!}
            <div class="col-md-9">
                <select class="form-control" name="separador_consecutivo" id="separador_consecutivo"
                    v-model="dataForm.separador_consecutivo" required>
                    <option value="-">-</option>
                    <option value="">Sin separador</option>
                </select>
                <small>@lang('Select the') el separador del formato del consecutivo</small>
                <div class="invalid-feedback" v-if="dataErrors.separador_consecutivo">
                    <p class="m-b-0" v-for="error in dataErrors.separador_consecutivo">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Version Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('version', trans('Versión').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::number('version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.version }", 'v-model' => 'dataForm.version', 'required' => true]) !!}
                <small>@lang('Enter the') la versión del documento</small>
                <div class="invalid-feedback" v-if="dataErrors.version">
                    <p class="m-b-0" v-for="error in dataErrors.version">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Formato Publicación Field -->
        <div class="form-group row m-b-15" v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' && (dataForm.tipo == 'publicacion' || dataForm.tipo == 'aprobacion')">
            {!! Form::label('formato_publicacion', trans('Formato de publicación').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::select('formato_publicacion', ["PDF" => "PDF", "Formato original" => "Formato original"], 'PDF', [':class' => "{'form-control':true, 'is-invalid':dataErrors.formato_publicacion }", 'v-model' => 'dataForm.formato_publicacion', 'required' => true]) !!}
                <small>@lang('Select the') el formato de publicación</small>
                <div class="invalid-feedback" v-if="dataErrors.formato_publicacion">
                    <p class="m-b-0" v-for="error in dataErrors.formato_publicacion">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('estado', 'Estado actual del documento:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9 col-form-label">
                <strong>@{{ dataForm.estado ? dataForm.estado : 'Elaboración' }}</strong>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('observacion_previa', 'Observación del documento:', ['class' => 'col-form-label col-md-3']) !!}
            <div v-if="dataForm.observacion_previa" class="col-form-label alert" style="background-color: #00bcd429 !important; margin-left: 10px; margin-right: 10px; margin-bottom: 0px; width: 72%; padding: 10px;">
                @{{ dataForm.observacion_previa }}
            </div>
            <div v-else class="col-form-label">
                Este documento aún no tiene una observación
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'">
            {!! Form::label('tipo', 'Elija qué desea hacer con este documento:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <div class="btn-group d-flex flex-wrap" role="group" aria-label="Tipo de documento" style="box-shadow: none;">
                    <button type="button" class="btn btn-sm btn-blue mr-2 mb-2" @click="selectTipo('elaboracion', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'elaboracion' }">Elaborar<div :class="dataForm.tipo+' arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                    <button type="button" class="btn btn-sm btn-yellow mr-2 mb-2 text-black" @click="selectTipo('revision', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'revision' }">Revisar<div :class="dataForm.tipo+' arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                    <button type="button" class="btn btn-sm btn-cyan mr-2 mb-2" @click="selectTipo('aprobacion', '.contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'aprobacion' }">Aprobación<div :class="dataForm.tipo+' arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                    <button type="button" class="btn btn-sm btn-green text-white mb-2" @click="selectTipo('publicacion', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'publicacion' }">Publicar<div :class="dataForm.tipo+' arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                </div>
                <div class="invalid-feedback" v-if="dataErrors.tipo">
                    <p class="m-b-0" v-for="error in dataErrors.tipo">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- publicacion -->
        <div class="form-group row m-b-15 contenedorFormPublicacion" v-if="dataForm.tipo=='publicacion'">
            <div v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" class="alert alert-success w-100" role="alert">
                Al aceptar, usted firma este documento y acepta su publicación automática.
            </div>
            <div v-else class="alert alert-success w-100" role="alert">
                Al guardar, el documento se publicará y se le asignará un consecutivo.
            </div>
        </div>

        <div :class="'contenedorFormFuncionarioObs contenedorForm'+dataForm.tipo" style="display: none;">

            <div class="form-group row m-b-15" v-if="dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision' || dataForm.tipo == 'aprobacion'">
                <!-- Tipo Usuario Elaboracion Field -->
                {!! Form::label('tipo_usuario', trans('Tipo de usuario') . ':', ['class' => 'col-form-label col-md-3 required',]) !!}
                <div class="col-md-9">
                    {!! Form::select('tipo_usuario', ["Interno" => "Interno"], 'Interno', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_usuario }", 'v-model' => 'dataForm.tipo_usuario', 'required' => true, '@change' => '$set(dataForm, "funcionario_elaboracion_revision", ""); $set(dataForm, "funcionario_elaboracion_revision_object", "");']) !!}
                    <small>Selecione el tipo de usuario</small>
                    <div class="invalid-feedback" v-if="dataErrors.tipo_usuario">
                        <p class="m-b-0" v-for="error in dataErrors.tipo_usuario">@{{ error }}</p>
                    </div>
                </div>
            </div>
            <!-- funcionario interno Field -->
            <div class="form-group row m-b-15" v-if="(dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision' || dataForm.tipo == 'aprobacion') && dataForm.tipo_usuario == 'Interno'">
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que elaborará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "elaboracion"']) !!}
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que revisará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "revision"']) !!}
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que aprobará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "aprobacion"']) !!}
                <div class="col-md-9">
                    <autocomplete
                        name-prop="name"
                        name-field="funcionario_elaboracion_revision"
                        :value='dataForm'
                        name-resource="/calidad/get-usuarios"
                        css-class="form-control"
                        :is-required="true"
                        :name-labels-display="['fullname']"
                        reduce-key="id"
                        :key="keyRefresh"
                        name-field-edit="funcionario_elaboracion_revision"
                        name-field-object="funcionario_elaboracion_revision_object">
                    </autocomplete>

                    <div class="invalid-feedback" v-if="dataErrors.funcionario_elaboracion_revision">
                        <p class="m-b-0" v-for="error in dataErrors.funcionario_elaboracion_revision">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Observacion Field -->
            <div class="form-group row m-b-15" v-if="dataForm.tipo != 'publicacion'">
                {!! Form::label('observacion', 'Escriba un comentario para el funcionario sobre lo que deberá hacer con el documento:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('observacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observacion }", 'v-model' => 'dataForm.observacion', ':required' => "dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision' || dataForm.tipo == 'aprobacion' ? true : false", 'rows' => '3']) !!}
                    <small>Ingrese en este campo una corta descripción de lo que debe hacer el funcionario que recibirá este documento
                        (Elaboración, Revisión o Aprobación), esta descripción no alterará el contenido del documento y será enviado al correo electrónico como una notificación.</small>
                    <div class="invalid-feedback" v-if="dataErrors.observacion">
                        <p class="m-b-0" v-for="error in dataErrors.observacion">@{{ error }}</p>
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
        <h4 class="panel-title"><strong>Datos adicionales</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Calidad Tipo Sistema Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_tipo_sistema_id', trans('Tipo de sistema').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_tipo_sistema_id" reduce-label="nombre_sistema" name-resource="get-tipo-sistemas-activos" :value="dataForm" :is-required="true"></select-check>
                <small>@lang('Select the') el @{{ `@lang('Tipo de Sistema')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_tipo_sistema_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_tipo_sistema_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Calidad Documento Tipo Documento Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_documento_tipo_documento_id', trans('Tipo de documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_documento_tipo_documento_id" reduce-label="nombre" :name-resource="'get-tipo-documentos-activos-documentos/'+dataForm.calidad_tipo_sistema_id" :value="dataForm" :is-required="true" name-field-object="calidad_documento_tipo_documento" :key="dataForm.calidad_tipo_sistema_id"></select-check>
                <small>@lang('Select the') el @{{ `@lang('tipo de documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_documento_tipo_documento_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_documento_tipo_documento_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Calidad Proceso Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_proceso_id', trans('Proceso').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_proceso_id" reduce-label="nombre" :name-resource="'get-procesos-activos-documentos/'+dataForm.calidad_tipo_sistema_id" :value="dataForm" :is-required="true" name-field-object="calidad_documento_proceso" :key="dataForm.calidad_tipo_sistema_id"></select-check>
                <small>@lang('Select the') el @{{ `@lang('proceso')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_proceso_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_proceso_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Calidad Documento Solicitud Documental Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('calidad_documento_solicitud_documental_id', trans('Solicitud de elaboración').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="calidad_documento_solicitud_documental_id" :reduce-label="['codigo','nombre_documento']" :name-resource="'get-documento-solicitud-documentals'" :value="dataForm" :enable-search="true"></select-check>
                <small>Selecciona una solicitud de elaboración en caso de que la tenga</small>
                <div class="invalid-feedback" v-if="dataErrors.calidad_documento_solicitud_documental_id">
                    <p class="m-b-0" v-for="error in dataErrors.calidad_documento_solicitud_documental_id">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Documento principal</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- documento Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('documento', trans('Documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="documento_adjunto" :max-files="1"
                    :max-filesize="11" file-path="public/container/calidad_documentos_{{ date('Y') }}"
                    message="Arrastre o seleccione el archivo" help-text="Tenga en cuenta que al seleccionar otro documento, se reemplazará el actual. El tamaño máximo permitido es de 10 MB."
                    :is-update="isUpdate" :key="keyRefresh" :required="true" :mostrar-eliminar-adjunto="false">
                </input-file>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Normativa de clasificación y distribución del documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Clase Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('clase', trans('Clase').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select class="form-control" v-model="dataForm.clase" required>
                    <option value="Documento interno">Documento interno</option>
                    <option value="Documento externo">Documento externo</option>
                </select>
                <small>@lang('Select the') la @{{ `@lang('clase del documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.clase">
                    <p class="m-b-0" v-for="error in dataErrors.clase">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Visibilidad Documento Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('visibilidad_documento', trans('Visibilidad del documento').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select class="form-control" v-model="dataForm.visibilidad_documento" required>
                    <option value="Público">Público</option>
                    <option value="Privado">Privado</option>
                </select>
                <small>@lang('Select the') la @{{ `@lang('visibilidad del documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.visibilidad_documento">
                    <p class="m-b-0" v-for="error in dataErrors.visibilidad_documento">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Distribucion Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('distribucion', trans('Distribución').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="distribucion" reduce-label="nombre" :name-resource="'get-procesos-activos-distribucion'" :value="dataForm" :is-required="true" :is-multiple="true" :function-change="seleccionarDistribucion"></select-check>
                <small>@lang('Select the') la @{{ `@lang('distribución del documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.distribucion">
                    <p class="m-b-0" v-for="error in dataErrors.distribucion">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" id="clasificacion">
    @include('calidad::documentos.field_clasificacion_documental')
</div>
