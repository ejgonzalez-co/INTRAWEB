<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos generales del documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Titulo Asunto Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('titulo_asunto', trans('Title') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('titulo_asunto', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.titulo_asunto }", 'v-model' => 'dataForm.titulo_asunto', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.titulo_asunto">
                    <p class="m-b-0" v-for="error in dataErrors.titulo_asunto">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" id="botonGoogleDocs" v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb'" >
            <button class="btn" style="background: #2684FC; color: white" type="button" @click="this.window.open(dataForm.plantilla)"><i class="fa fa-file"></i>
                Agregar contenido al documento en Google Docs</button>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' && (dataForm.tipo == 'publicacion' || dataForm.tipo == 'firmar_varios')">
            <!-- Formato Publicación Field -->
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
                    <button type="button" class="btn btn-sm btn-orange mr-2 mb-2" @click="selectTipo('firmar_varios', '.contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'firmar_varios' }">Firma conjunta<div :class="dataForm.tipo+' arrow-down'" style="display: none; margin-top: 1px;"></div></button>
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

        <div :class="'contenedorFormFuncionarioObs contenedorForm'+dataForm.tipo">

            <div class="form-group row m-b-15" v-if="dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision'">
                <!-- Tipo Usuario Elaboracion Field -->
                {!! Form::label('tipo_usuario', trans('Tipo de usuario') . ':', ['class' => 'col-form-label col-md-3 required',]) !!}
                <div class="col-md-9">
                    {!! Form::select('tipo_usuario', ["Interno" => "Interno", "Externo" => "Externo"], 'Interno', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_usuario }", 'v-model' => 'dataForm.tipo_usuario', 'required' => true, '@change' => '$set(dataForm, "funcionario_elaboracion_revision", ""); $set(dataForm, "correo_usuario_externo", "");']) !!}
                    <small>Selecione el tipo de usuario</small>
                    <div class="invalid-feedback" v-if="dataErrors.tipo_usuario">
                        <p class="m-b-0" v-for="error in dataErrors.tipo_usuario">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="dataForm.users_name_actual && (dataForm.tipo == 'revision' && dataForm.estado == 'Revisión (pendiente de enviar)')">

                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que esta revisando el documento:', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <label v-if="" class="col-form-label"><b>@{{ dataForm.users_name_actual }}</b></label>
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="dataForm.users_name_actual && (dataForm.tipo == 'elaboracion' && dataForm.estado == 'Elaboración (pendiente de enviar)')">
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que esta elaborando el documento:', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <label v-if="" class="col-form-label"><b>@{{ dataForm.users_name_actual }}</b></label>
                </div>
            </div>
            <!-- funcionario interno Field -->
            <div class="form-group row m-b-15" v-if="(dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision') && dataForm.tipo_usuario == 'Interno'">
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que elaborará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "elaboracion"']) !!}
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario interno que revisará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "revision"']) !!}
                <div class="col-md-9">
                    <autocomplete
                        name-prop="name"
                        name-field="funcionario_elaboracion_revision"
                        :value='dataForm'
                        name-resource="/documentos-electronicos/get-usuarios"
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

            <!-- funcionario externo Field -->
            <div class="form-group row m-b-15" v-if="(dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision') && dataForm.tipo_usuario == 'Externo'">
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario externo que elaborará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "elaboracion"']) !!}
                {!! Form::label('funcionario_elaboracion_revision', 'Funcionario externo que revisará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "revision"']) !!}
                <div class="col-md-9">
                    {!! Form::text('funcionario_elaboracion_revision', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.funcionario_elaboracion_revision }", 'v-model' => 'dataForm.funcionario_elaboracion_revision', 'required' => true]) !!}
                    <div class="invalid-feedback" v-if="dataErrors.funcionario_elaboracion_revision">
                        <p class="m-b-0" v-for="error in dataErrors.funcionario_elaboracion_revision">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Correo Usuario Externo Field -->
            <div class="form-group row m-b-15" v-if="(dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision') && dataForm.tipo_usuario == 'Externo'">
                {!! Form::label('correo_usuario_externo', 'Correo del usuario:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::email('correo_usuario_externo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo_usuario_externo }", 'v-model' => 'dataForm.correo_usuario_externo', 'required' => true]) !!}
                    <small>Ingrese el correo del usuario externo</small>
                    <div class="invalid-feedback" v-if="dataErrors.correo_usuario_externo">
                        <p class="m-b-0" v-for="error in dataErrors.correo_usuario_externo">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Tipo Usuario Firma Conjunta Field -->
            <div class="form-group row m-b-15" v-if="dataForm.tipo == 'firmar_varios'">
                <dynamic-list label-button-add="Agregar usuario" :data-list.sync="dataForm.de_documento_firmars" :is-remove="true"
                    :data-list-options="[
                        { label: 'Tipo de usuario', name: 'tipo_usuario', isShow: true },
                        { label: 'Nombre', name: 'nombre_usuario', isShow: true, refList: 'usuarios_ref', nameObjectKey: ['usuarios', 'fullname'] },
                        { label: 'Correo electrónico', name: 'correo', isShow: true, refList: 'usuarios_ref', nameObjectKey: ['usuarios', 'email']},
                    ]"
                    class-container="col-md-12" class-table="table table-bordered" :is-remove="false" :is-required="true">
                    <template #fields="scope">
                        <div class="form-group row m-b-15">
                            <!-- Tipo Usuario Elaboracion Field -->
                            {!! Form::label('tipo_usuario', trans('Tipo de usuario') . ':', ['class' => 'col-form-label col-md-3 required',]) !!}
                            <div class="col-md-9">
                                {!! Form::select('tipo_usuario', ["Interno" => "Interno", "Externo" => "Externo"], 'Interno', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_usuario }", 'v-model' => 'scope.dataForm.tipo_usuario', 'required' => true]) !!}
                                <small>Selecione el tipo de usuario</small>
                                <div class="invalid-feedback" v-if="dataErrors.tipo_usuario">
                                    <p class="m-b-0" v-for="error in dataErrors.tipo_usuario">@{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- funcionario interno Field -->
                        <div class="form-group row m-b-15" v-if="scope.dataForm.tipo_usuario == 'Interno'">
                            {!! Form::label('nombre_usuario', 'Funcionario interno que firmará el documento:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">

                                <select-check css-class="form-control" name-field="nombre_usuario" reduce-label="fullname" name-resource="/documentos-electronicos/get-usuarios-firmar" :value="scope.dataForm" :is-required="true" ref-select-check="usuarios_ref" :enable-search="true" name-field-object="usuarios"></select-check>

                                <div class="invalid-feedback" v-if="dataErrors.nombre_usuario">
                                    <p class="m-b-0" v-for="error in dataErrors.nombre_usuario">@{{ error }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- funcionario externo Field -->
                        <div class="form-group row m-b-15" v-if="scope.dataForm.tipo_usuario == 'Externo'">
                            {!! Form::label('nombre_usuario', 'Funcionario externo que firmará el documento:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::text('nombre_usuario', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_usuario }", 'v-model' => 'scope.dataForm.nombre_usuario', 'required' => true]) !!}
                                <div class="invalid-feedback" v-if="dataErrors.nombre_usuario">
                                    <p class="m-b-0" v-for="error in dataErrors.nombre_usuario">@{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Correo Usuario Externo Field -->
                        <div class="form-group row m-b-15" v-if="scope.dataForm.tipo_usuario == 'Externo'">
                            {!! Form::label('correo', 'Correo del usuario:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::email('correo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.correo }", 'v-model' => 'scope.dataForm.correo', 'required' => true]) !!}
                                <small>Ingrese el correo del usuario externo. Ej: ejemplo@gmail.com</small>
                                <div class="invalid-feedback" v-if="dataErrors.correo">
                                    <p class="m-b-0" v-for="error in dataErrors.correo">@{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </template>
                </dynamic-list>
            </div>

            <!-- Observacion Field -->
            <div class="form-group row m-b-15" v-if="dataForm.tipo != 'publicacion'">
                {!! Form::label('observacion', 'Escriba un comentario para el funcionario sobre lo que deberá hacer con el documento:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('observacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observacion }", 'v-model' => 'dataForm.observacion', ':required' => "dataForm.tipo == 'elaboracion' || dataForm.tipo == 'revision'  || dataForm.tipo == 'firmar_varios' ? true : false", 'rows' => '3']) !!}
                    <small>Ingrese en este campo una corta descripción de lo que debe hacer el funcionario que recibirá este documento
                        (Elaboración y Revisión), esta descripción no alterará el contenido del documento y será enviado al correo electrónico como una notificación.</small>
                    <div class="invalid-feedback" v-if="dataErrors.observacion">
                        <p class="m-b-0" v-for="error in dataErrors.observacion">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.origen_documento == 'Producir documento en línea a través de Intraweb' && dataForm.de_tipos_documentos?.sub_estados_value.length > 0">
            <!-- Subestado Documento Field -->
            {!! Form::label('subestado_documento', trans('Actividad').':', ['class' => 'col-form-label col-md-3', ':class' => "{'required': dataForm.de_tipos_documentos.sub_estados_requerido == 1}"]) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="subestado_documento" reduce-key="subestado" reduce-label="subestado" :value="dataForm" :is-required="dataForm.de_tipos_documentos.sub_estados_requerido == 1 ? true : false" :options-list-manual="dataForm.de_tipos_documentos?.sub_estados_value">
                </select-check>
                <small>@lang('Select the') el @{{ `@lang('la actividad del usuario en el documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.subestado_documento">
                    <p class="m-b-0" v-for="error in dataErrors.subestado_documento">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Documento principal</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <!--  Other officials Field destination-->
                <div class="form-group row m-b-15">
                    {!! Form::label('url', 'Documento principal:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="30"
                            :max-filesize="11" file-path="public/container/documentos_electronicos_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar el o los documentos principales\. El tamaño máximo permitido es de 10 MB\."
                            :is-update="isUpdate" ruta-delete-update="documentoselectronicos/documentos-delete-file" :id-file-delete="dataForm.id">
                        </input-file>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

{{-- Verifica si la variable $habilita_finaliza_pqrs está definida y su valor sea 'si' --}}
@if (isset($habilita_finaliza_pqrs) && $habilita_finaliza_pqrs === 'si')
    <div class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Finalización de PQRS</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <!-- Require Answer Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('require_answer', 'Finaliza PQRS:', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">

                            <select class="form-control" id="require_answer" v-model="dataForm.require_answer" @change="$set(dataForm, 'pqr_consecutive', '')">
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>

                            <small>Seleccione la opción "Si" si este documento finaliza un PQRS.</small>
                            <div class="invalid-feedback" v-if="dataErrors.require_answer">
                                <p class="m-b-0" v-for="error in dataErrors.require_answer">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Consecutive PQRS Field -->
                    <div class="form-group row m-b-15" v-if="dataForm.require_answer=='Si'">
                        {!! Form::label('pqr_consecutive', 'Respuesta a PQRS:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                name-prop="pqr_id"
                                name-field="pqr_id"
                                :value='dataForm'
                                name-resource="/pqrs/get-p-q-r-s"
                                css-class="form-control"
                                :is-required="true"
                                :name-labels-display="['pqr_id', 'contenido']"
                                reduce-key="id"
                                :activar-blur="true"
                                :min-text-input="4"
                                :fields-change-values="['pqr_consecutive:pqr_id', 'nombre_ciudadano:nombre_ciudadano', 'email_ciudadano:email_ciudadano']"
                                name-field-edit="pqr_consecutive">
                            </autocomplete>

                            <small>Ingrese el consecutivo del PQR.</small>
                            <div class="invalid-feedback" v-if="dataErrors.pqr_consecutive">
                                <p class="m-b-0" v-for="error in dataErrors.pqr_consecutive">@{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15" v-if="(dataForm.require_answer=='Si' && dataForm.nombre_ciudadano)">
                        {!! Form::label('nombre_ciudadano', trans('Nombre del ciudadano').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <input type="text" :placeholder="dataForm.nombre_ciudadano" disabled class="form-control"></input>
                            <small>Nombre del ciudadano registrado en la pqr</small>
                        </div>
                    </div>

                    <div class="form-group row m-b-15" v-if="(dataForm.require_answer=='Si' && dataForm.email_ciudadano)">
                        {!! Form::label('email_ciudadano', trans('Correo del ciudadano').':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <input type="text" :placeholder="dataForm.email_ciudadano" disabled class="form-control"></input>
                            <small>Correo del ciudadano registrado en la pqr</small>
                        </div>
                    </div>

                    <!-- Tipo Finalizacion PQRS Field -->
                    <div class="form-group row m-b-15" v-if="dataForm.require_answer=='Si'">
                        <!-- Tipo Finalizacion Field -->
                        {!! Form::label('tipo_finalizacion', trans('Tipo de finalizacion').':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            {!! Form::select('tipo_finalizacion',
                                ["Respuesta al ciudadano" => "Respuesta al ciudadano",
                                "PQRS para trasladar a otra entidad" => "PQRS para trasladar a otra entidad",
                                "PQRS negado" => "PQRS negado",
                                "PQRS finalizado por falta de información" => "PQRS finalizado por falta de información"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_finalizacion }", 'v-model' => 'dataForm.tipo_finalizacion', 'required' => true]) !!}
                            <small>@lang('Select the') el tipo de finalización</small>
                            <div class="invalid-feedback" v-if="dataErrors.tipo_finalizacion">
                                <p class="m-b-0" v-for="error in dataErrors.tipo_finalizacion">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end panel-body -->
    </div>
@endif

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle" style="display: block">
        <h4 class="panel-title"><strong>Compartidos</strong></h4><br/>
        <h6>Comparta el documento con usuarios o dependencias para que puedan visualizarlo</h6>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Compartidos -->
        <div class="form-group row m-b-15">
            {!! Form::label('users_compartidos', 'Funcionarios con quien se compartirá el documento:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <add-list-autocomplete :value="dataForm" name-prop="ids"
                    name-field-autocomplete="users_compartidos_auto" name-field="users_compartidos"
                    name-resource="/documentos-electronicos/get-compartidos"
                    name-options-list="de_compartidos" :name-labels-display="['nombre']" name-key="id"
                    help="Autocomplete el nombre de uno o varios funcionarios con quien se compartirá el documento y seleccionelo o presione la tecla enter para agregarlo. Ejemplo: Fernanda"
                    :key="keyRefresh">
                </add-list-autocomplete>
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
        <!-- Documentos Asociados Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('documentos_asociados', 'Asociar documento de Intraweb:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <autocomplete
                    name-prop="consecutivo"
                    name-field="documentos_asociados"
                    :value='dataForm'
                    name-resource="get-asociar-documentos"
                    css-class="form-control"
                    :name-labels-display="['consecutivo', 'titulo_asunto']"
                    reduce-key="id"
                    :key="keyRefresh"
                    name-field-edit="documentos_asociados">
                </autocomplete>
                <small>Ingrese el consecutivo del documento a asociar</small>
                <div class="invalid-feedback" v-if="dataErrors.documentos_asociados">
                    <p class="m-b-0" v-for="error in dataErrors.documentos_asociados">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!--  Adjuntos Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('url', 'Lista de archivos adjuntos:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="adjuntos" :max-files="30"
                    :max-filesize="11" file-path="public/container/documentos_electronicos_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos. El tamaño máximo permitido es de 10 MB"
                    :is-update="isUpdate" :key="keyRefresh" ruta-delete-update="documentos-electronicos/documentos-delete-file" :id-file-delete="dataForm.id">
                </input-file>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.de_tipos_documentos?.de_metadatos.length > 0">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="metadato in dataForm.de_tipos_documentos?.de_metadatos">
            <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">@{{ metadato.nombre_metadato }}:</label>
            <div class="col-md-9">
            <input v-if="metadato.tipo != 'Listado'" :type="metadato.tipo == 'Texto' ? 'text' : (metadato.tipo == 'Número' ? 'number' : (metadato.tipo == 'Fecha' ? 'date' : (metadato.tipo == 'Hora' ? 'time' : 'text')))" v-model="dataForm.metadatos[metadato.metadato_v_model]" :name="'metadato_'+metadato.id" :id="'metadato_'+metadato.id" class="form-control" :required="metadato.requerido">
                <select v-else v-model="dataForm.metadatos[metadato.metadato_v_model]" :name="'metadato_'+metadato.id" :id="'metadato_'+metadato.id" class="form-control" :required="metadato.requerido">
                    <option v-for="opcion in metadato.opciones_listado.split(', ')" :value="opcion">@{{ opcion }}</option>
                </select>
                <small>@{{ metadato.texto_ayuda }}<span v-if="metadato.variable_documento">. Usa esta variable en el documento así: @{{ metadato.variable_documento }}</span></small>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" id="clasificacion">
    @include('documentoselectronicos::documentos.field_clasificacion_documental')
</div>
