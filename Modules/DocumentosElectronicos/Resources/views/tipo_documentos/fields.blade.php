<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información inicial</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            <!-- Nombre Field -->
            {!! Form::label('nombre', trans('Nombre') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('nombre', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre }",
                    'v-model' => 'dataForm.nombre',
                    'required' => true,
                ]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Nombre')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.nombre">
                    <p class="m-b-0" v-for="error in dataErrors.nombre">@{{ error }}</p>
                </div>
            </div>

            <!-- Prefijo Field -->
            {!! Form::label('prefijo', trans('Prefijo') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('prefijo', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.prefijo }",
                    'v-model' => 'dataForm.prefijo',
                    'required' => true,
                ]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Prefijo')` | lowercase }} del tipo de documento</small>
                <div class="invalid-feedback" v-if="dataErrors.prefijo">
                    <p class="m-b-0" v-for="error in dataErrors.prefijo">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Version Field -->
            {!! Form::label('version', trans('Versión') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('version', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.version }",
                    'v-model' => 'dataForm.version',
                    'required' => true,
                ]) !!}
                <small>Ingrese la versión del tipo de documento</small>
                <div class="invalid-feedback" v-if="dataErrors.version">
                    <p class="m-b-0" v-for="error in dataErrors.version">@{{ error }}</p>
                </div>
            </div>

            <!-- Codigo Formato Field -->
            {!! Form::label('codigo_formato', trans('Código del formato') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
                {!! Form::text('codigo_formato', null, [
                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.codigo_formato }",
                    'v-model' => 'dataForm.codigo_formato',
                    'required' => true,
                ]) !!}
                <small>@lang('Enter the') el código o versión del documento en su formato. <br>Ejemplo: SC-FO-001</small>
                <div class="invalid-feedback" v-if="dataErrors.codigo_formato">
                    <p class="m-b-0" v-for="error in dataErrors.codigo_formato">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Formato Consecutivo Field -->
            {!! Form::label('formato_consecutivo', trans('Formato del consecutivo') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-10">
                <select-check css-class="form-control" name-field="formato_consecutivo_value" reduce-label="nombre"
                    :value="dataForm" :is-required="true"
                    :enable-search="true" :is-multiple="true"
                    :options-list-manual="[{ id: 'prefijo_dependencia', nombre: 'Prefijo de la dependencia' },
                        { id: 'serie_documental', nombre: 'Serie documental' },
                        { id: 'subserie_documental', nombre: 'Subserie documental' },
                        { id: 'prefijo_documento', nombre: 'Prefijo del documento' },
                        { id: 'vigencia_actual', nombre: 'Año actual' },
                        { id: 'consecutivo_documento', nombre: 'Número de Orden de Documento' }
                    ]">
                </select-check>
                <small>Construya el formato del consecutivo. Ejemplo: Prefijo de la dependencia-Serie documental-Orden del consecutivo (SIS-090-1).<br>
                        Tenga que cuenta sí el módulo tiene habilitada la clasificación documental para el uso de las variables Serie documental y Subserie documental.</small>
                <div class="invalid-feedback" v-if="dataErrors.formato_consecutivo">
                    <p class="m-b-0" v-for="error in dataErrors.formato_consecutivo">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Prefijo Incrementan Consecutivo Field -->
            {!! Form::label('prefijo_incrementan_consecutivo', trans('Prefijo para incrementar el consecutivo') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-10">
                <select-check css-class="form-control" name-field="prefijo_incrementan_consecutivo_value"
                    name-field-object="prefijo_incrementan_consecutivo" reduce-label="nombre" :value="dataForm"
                    :is-required="true" :enable-search="true"
                    :is-multiple="true"
                    :options-list-manual="[{ id: 'prefijo_dependencia', nombre: 'Prefijo de la dependencia' },
                        { id: 'serie_documental', nombre: 'Serie documental' },
                        { id: 'subserie_documental', nombre: 'Subserie documental' },
                        { id: 'prefijo_documento', nombre: 'Prefijo del documento' },
                        { id: 'vigencia_actual', nombre: 'Año actual' }
                    ]">
                </select-check>
                <small>Indique el @{{ `@lang('Prefijo que Incrementa el Consecutivo')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.prefijo_incrementan_consecutivo">
                    <p class="m-b-0" v-for="error in dataErrors.prefijo_incrementan_consecutivo">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Separador Consecutivo Field -->
            {!! Form::label('separador_consecutivo', trans('Separador del consecutivo') . ':', [
                'class' => 'col-form-label col-md-2 required',
            ]) !!}
            <div class="col-md-4">
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

            <!-- Estado Field -->
            {!! Form::label('estado', trans('Estado') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <select class="form-control" name="estado" id="estado" v-model="dataForm.estado" required>
                    <option value="Público">Público</option>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Obsoleto">Obsoleto</option>
                </select>
                <small>@lang('Select the') el @{{ `@lang('Estado del tipo de documento')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.estado">
                    <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!--  Variables Plantilla Requeridas Field -->
            {!! Form::label(
                'es_borrable',
                trans('¿Permite eliminar documentos?') . ':',
                ['class' => 'col-form-label col-md-2'],
            ) !!}
            <!-- es_borrable switcher -->
            <div class="switcher col-md-4">
                <input type="checkbox" name="es_borrable" id="es_borrable"
                    v-model="dataForm.es_borrable">
                <label for="es_borrable"></label>
                <small>@lang('Seleccione si se permite eliminar documentos')</small>
                <div class="invalid-feedback" v-if="dataErrors.es_borrable">
                    <p class="m-b-0" v-for="error in dataErrors.es_borrable">
                        @{{ error }}</p>
                </div>
            </div>
            <!--  Variables Plantilla Requeridas Field -->
            {!! Form::label(
                'es_editable',
                trans('¿Permite editar documentos?') . ':',
                ['class' => 'col-form-label col-md-2'],
            ) !!}
            <!-- es_editable switcher -->
            <div class="switcher col-md-4">
                <input type="checkbox" name="es_editable" id="es_editable"
                    v-model="dataForm.es_editable">
                <label for="es_editable"></label>
                <small>@lang('Seleccione si se permite editar documentos')</small>
                <div class="invalid-feedback" v-if="dataErrors.es_editable">
                    <p class="m-b-0" v-for="error in dataErrors.es_editable">
                        @{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos de la plantilla</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Variables Field -->
        <div class="form-group row m-b-15">
            <h6 class="col-form-label">Variables de la plantilla</h6>
            <dynamic-list label-button-add="Agregar variable" :data-list.sync="dataForm.variables_plantilla_value"
                :is-remove="true"
                :data-list-options="[
                    { label: 'Variable - Descripción', name: 'variable', isShow: true, refList: 'variables' }
                ]"
                class-container="col-md-12" class-table="table table-bordered">
                <template #fields="scope">
                    <!-- Variables Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('variable', trans('Variable') . ':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <select-check css-class="form-control" name-field="variable" name-field-object="variables"
                                reduce-label="nombre" :value="scope.dataForm" :is-required="true"
                                ref-select-check="variables"
                                :options-list-manual="[{ id: '#consecutivo', nombre: '#consecutivo - Consecutivo del documento' },
                                    { id: '#titulo', nombre: '#titulo - Título del documento' },
                                    { id: '#dependencia_remitente',
                                        nombre: '#dependencia_remitente - Dependencia remitente del documento' },
                                    { id: '#compartidos', nombre: '#compartidos - Usuarios con permiso de ver el documento' },
                                    { id: '#tipo_documento', nombre: '#tipo_documento - Tipo de documento' },
                                    { id: '#elaborado', nombre: '#elaborado - Pérsona que elaboró el documento' },
                                    { id: '#revisado', nombre: '#revisado - Persona que revisó el document' },
                                    { id: '#proyecto', nombre: '#proyecto - Persona que proyectó el documento' },
                                    { id: '#codigo_formato', nombre: '#codigo_formato - Código o versión del documento en su formato' },
                                    { id: '#documento_asociado',
                                        nombre: '#documento_asociado - Documento asociado' },
                                    { id: '#codigo_dependencia',
                                        nombre: '#codigo_dependencia - Código de la dependencia' },
                                    { id: '#fecha', nombre: '#fecha - Fecha de publicación del documento' },
                                    { id: '#codigo_validacion',
                                        nombre: '#codigo_validacion - Código de validación del documento' },
                                    { id: '#firmas',
                                        nombre: '#firmas - Firmas del documento' }
                                ]">
                            </select-check>
                            <div class="invalid-feedback" v-if="dataErrors.variable">
                                <p class="m-b-0" v-for="error in dataErrors.variable">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>

        <div class="form-group row m-b-15">
            <!--  Variables Plantilla Requeridas Field -->
            {!! Form::label(
                'variables_plantilla_requeridas',
                trans('¿Las variables en la plantilla son requeridas?') . ':',
                ['class' => 'col-form-label col-md-2'],
            ) !!}
            <!-- variables_plantilla_requeridas switcher -->
            <div class="switcher col-md-4">
                <input type="checkbox" name="variables_plantilla_requeridas" id="variables_plantilla_requeridas"
                    v-model="dataForm.variables_plantilla_requeridas" :disabled="dataForm.variables_plantilla_value.length <= 0">
                <label for="variables_plantilla_requeridas" :style="dataForm.variables_plantilla_value.length <= 0 ? 'opacity: .5' : ''"></label>
                <small>@lang('Seleccione si las variables del documento son requeridas')</small>
                <div class="invalid-feedback" v-if="dataErrors.variables_plantilla_requeridas">
                    <p class="m-b-0" v-for="error in dataErrors.variables_plantilla_requeridas">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Plantilla Field -->
            {!! Form::label('plantilla', trans('Plantilla') . ':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::file('plantilla', [
                    'accept' => '.xlsx,.xls,.doc,.docx',
                    '@change' => 'inputFile($event, "plantilla")',
                    'required' => false,
                ]) !!}
                <small>@lang('Select the') la @{{ `@lang('Plantilla')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.plantilla">
                    <p class="m-b-0" v-for="error in dataErrors.plantilla">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Preview document Field -->
            {!! Form::label('preview_document', trans('Imagen previa del documento') . ':', [
                'class' => 'col-form-label col-md-3',
            ]) !!}
            <div class="col-md-9">
                {!! Form::file('preview_document', [
                    'accept' => '.jpg,.jpeg,.png',
                    '@change' => 'inputFile($event, "preview_document")',
                    'required' => false,
                ]) !!}
                <small>@lang('Select the') una @{{ `@lang('Imagen Previa')` | lowercase }} del documento de 140X180 píxeles</small>
                <div class="invalid-feedback" v-if="dataErrors.preview_document">
                    <p class="m-b-0" v-for="error in dataErrors.preview_document">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Permisos para usar este tipo de documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Permiso Crear Documentos Todas Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'permiso_crear_documentos_todas',
                trans('¿Todas las dependencias pueden crear documentos de este tipo?') . ':',
                ['class' => 'col-form-label col-md-3 required'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="permiso_crear_documentos_todas"
                    id="permiso_crear_documentos_todas" v-model="dataForm.permiso_crear_documentos_todas" required>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
                <small>@lang('Select the') si todas las dependencias pueden usar este tipo de documento</small>
                <div class="invalid-feedback" v-if="dataErrors.permiso_crear_documentos_todas">
                    <p class="m-b-0" v-for="error in dataErrors.permiso_crear_documentos_todas">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.permiso_crear_documentos_todas == 0">
            <h6 class="col-form-label">Defina las dependencias con permiso de usar este tipo de documento</h6>
            <dynamic-list label-button-add="Agregar dependencia"
                :data-list.sync="dataForm.de_permiso_crear_documentos" :is-remove="true"
                :data-list-options="[
                    { label: 'Funcionario, cargo, dependencia o grupo', name: 'nombre',nameObjectKey: ['recipient_datos', 'nombre'], isShow: true, refList: 'dependencia_ref' },
                    { label: 'Tipo', name: 'type_user', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <!-- Type Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('type_user', trans('Tipo') . ':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <select class="form-control" v-model="scope.dataForm.type_user">
                                <option value="Cargo">Cargo</option>
                                <option value="Dependencia">Dependencia</option>
                                <option value="Grupo">Grupo</option>
                                <option value="Usuario">Usuario</option>
                            </select>
                            <small>Seleccione el tipo</small>
                            <div class="invalid-feedback" v-if="dataErrors.dependencia">
                                <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15">
                        {!! Form::label('name', 'Nombre del funcionario, cargo, dependencia o grupo:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                :is-update="isUpdate"
                                {{-- :value-default="scope.dataForm.id" --}}
                                name-prop="nombre"
                                name-field="dependencias_id"
                                :value='scope.dataForm'
                                name-resource="/documentos-electronicos/get-recipients"
                                css-class="form-control"
                                :name-labels-display="['nombre']"
                                reduce-key="id"
                                :is-required="true"
                                name-field-object="recipient_datos"
                                ref="dependencia_ref"
                                name-field-edit="nombre"
                                :key="keyRefresh"
                                :ids-to-empty="['dependencia_informacion']"
                                >
                            </autocomplete>

                            {{-- {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => false]) !!} --}}
                            <small>
                                Ingrese y seleccione el nombre, cargo, dependencia o grupo para añadirlo.
                                <br>También puede agregar funcionarios no registrados en Intraweb.
                              </small>

                            <div class="invalid-feedback" v-if="dataErrors.name">
                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                            </div>
                        </div>

                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Permisos para consultar este tipo de documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Permiso Crear Documentos Todas Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'permiso_consultar_documentos_todas',
                trans('¿Todos pueden consultar documentos de este tipo?') . ':',
                ['class' => 'col-form-label col-md-3 required'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="permiso_consultar_documentos_todas"
                    id="permiso_consultar_documentos_todas" v-model="dataForm.permiso_consultar_documentos_todas" required>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
                <small>@lang('Select the') si todos pueden consultar este tipo de documento</small>
                <div class="invalid-feedback" v-if="dataErrors.permiso_consultar_documentos_todas">
                    <p class="m-b-0" v-for="error in dataErrors.permiso_consultar_documentos_todas">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.permiso_consultar_documentos_todas == 0">
            <h6 class="col-form-label">Defina las dependencias con permiso de consultar este tipo de documento</h6>
            <dynamic-list label-button-add="Agregar dependencia"
                :data-list.sync="dataForm.de_permiso_consultar_documentos" :is-remove="true"
                :data-list-options="[
                    { label: 'Funcionario, cargo, dependencia o grupo', name: 'nombre',nameObjectKey: ['recipient_datos', 'nombre'], isShow: true, refList: 'dependencia_ref' },
                    { label: 'Tipo', name: 'type_user', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <!-- Type Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('type_user', trans('Tipo') . ':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            <select class="form-control" v-model="scope.dataForm.type_user">
                                <option value="Cargo">Cargo</option>
                                <option value="Dependencia">Dependencia</option>
                                <option value="Grupo">Grupo</option>
                                <option value="Usuario">Usuario</option>
                            </select>
                            <small>Seleccione el tipo</small>
                            <div class="invalid-feedback" v-if="dataErrors.dependencia">
                                <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15">
                        {!! Form::label('name', 'Nombre del funcionario, cargo, dependencia o grupo:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                :is-update="isUpdate"
                                {{-- :value-default="scope.dataForm.id" --}}
                                name-prop="nombre"
                                name-field="dependencias_id"
                                :value='scope.dataForm'
                                name-resource="/documentos-electronicos/get-recipients"
                                css-class="form-control"
                                :name-labels-display="['nombre']"
                                reduce-key="id"
                                :is-required="true"
                                name-field-object="recipient_datos"
                                ref="dependencia_ref"
                                name-field-edit="nombre"
                                :key="keyRefresh"
                                :ids-to-empty="['dependencia_informacion']"
                                >
                            </autocomplete>

                            {{-- {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => false]) !!} --}}
                            <small>
                                Ingrese y seleccione el nombre, cargo, dependencia o grupo para añadirlo.
                                <br>También puede agregar funcionarios no registrados en Intraweb.
                              </small>

                            <div class="invalid-feedback" v-if="dataErrors.name">
                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                            </div>
                        </div>

                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <documentos-electronicos ref="documentos_ref"></documentos-electronicos>
        <!-- Sub Estados Field -->
        <div class="form-group row m-b-15">
            <dynamic-list url-eliminar-registro="/documentos-electronicos/validar-metadato-eliminar" obj-eliminados="de_metadatos_eliminados" label-button-add="Agregar metadato" :data-list.sync="dataForm.de_metadatos"
                :is-remove="true" ref="metadatos_tipo_documento"
                :data-list-options="[
                    { label: 'Nombre del campo', name: 'nombre_metadato', isShow: true },
                    { label: 'Tipo', name: 'tipo', isShow: true, refList: 'tipo' },
                    { label: 'Texto de ayuda', name: 'texto_ayuda', isShow: true },
                    { label: 'Opciones del listado', name: 'opciones_listado', isShow: true, refList: 'valores', nameObjectKey: [
                            'opciones_listado'
                        ] },
                    { label: '¿Es requerido?', name: 'requerido', isShow: true, refList: 'requerido' },
                    { label: 'Variable en el documento', name: 'variable_documento', isShow: true },
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <div class="form-group row m-b-15">
                        <!-- Nombre Campo Field -->
                        {!! Form::label('nombre_metadato', trans('Nombre del campo') . ':', [
                            'class' => 'col-form-label col-md-2 required',
                        ]) !!}
                        <div class="col-md-4">
                            {!! Form::text('nombre_metadato', null, [
                                ':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_metadato }",
                                'v-model' => 'scope.dataForm.nombre_metadato',
                                'required' => true,
                                '@keyUp' => 'callFunctionComponent("documentos_ref", "asignarVariableMetadato", $event)',
                            ]) !!}
                            <small>Ingrese el nombre del campo</small>
                            <div class="invalid-feedback" v-if="dataErrors.nombre_metadato">
                                <p class="m-b-0" v-for="error in dataErrors.nombre_metadato">@{{ error }}
                                </p>
                            </div>
                        </div>

                        <!-- Variable Documento Field -->
                        {!! Form::label('variable_documento_aux', trans('Variable en el documento') . ':', [
                            'class' => 'col-form-label col-md-2',
                        ]) !!}
                        <div class="col-md-4">
                            <div style="display: flex;">
                                <div style="position: absolute; padding-left: 12px; padding-top: 7px; color: gray;">#metadato_</div>
                                {!! Form::text('variable_documento_aux', null, [
                                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.variable_documento_aux }",
                                    'v-model' => 'scope.dataForm.variable_documento_aux',
                                    '@keyUp' => 'callFunctionComponent("documentos_ref", "asignarVariableMetadato", $event)',
                                    'style' => 'padding-left: 81px;'
                                ]) !!}
                            </div>
                            <small>Esta variable será reemplazada en el documento por el valor del campo</small>
                            <div class="invalid-feedback" v-if="dataErrors.variable_documento">
                                <p class="m-b-0" v-for="error in dataErrors.variable_documento">
                                    @{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15">
                        <!-- Texto Ayuda Campo Field -->
                        {!! Form::label('texto_ayuda', trans('Texto de ayuda') . ':', ['class' => 'col-form-label col-md-2']) !!}
                        <div class="col-md-10">
                            {!! Form::text('texto_ayuda', null, [
                                ':class' => "{'form-control':true, 'is-invalid':dataErrors.texto_ayuda }",
                                'v-model' => 'scope.dataForm.texto_ayuda',
                            ]) !!}
                            <small>Ingrese el texto de ayuda del campo</small>
                            <div class="invalid-feedback" v-if="dataErrors.texto_ayuda">
                                <p class="m-b-0" v-for="error in dataErrors.texto_ayuda">@{{ error }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row m-b-15">
                        <!-- Tipo Field -->
                        {!! Form::label('tipo', trans('Tipo') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                        <div class="col-md-4">
                            {!! Form::select(
                                'tipo',
                                ['Texto' => 'Texto', 'Número' => 'Número', 'Fecha' => 'Fecha', 'Listado' => 'Listado', 'Hora' => 'Hora'],
                                'text',
                                [
                                    ':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo }",
                                    'v-model' => 'scope.dataForm.tipo',
                                    'required' => true,
                                    'ref' => 'tipo',
                                ],
                            ) !!}
                            <small>Selecione el tipo del campo</small>
                            <div class="invalid-feedback" v-if="dataErrors.tipo">
                                <p class="m-b-0" v-for="error in dataErrors.tipo">@{{ error }}</p>
                            </div>
                        </div>

                        <!--  Metadato Requerido Field -->
                        {!! Form::label('requerido', trans('¿Es requerido?') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
                        <div class="col-md-4">
                            {!! Form::select('requerido', [true => 'Si', false => 'No'], false, [
                                ':class' => "{'form-control':true, 'is-invalid':dataErrors.requerido }",
                                'v-model' => 'scope.dataForm.requerido',
                                'required' => true,
                                'ref' => 'requerido',
                            ]) !!}
                            <small>Seleccione si es requerido o no</small>
                            <div class="invalid-feedback" v-if="dataErrors.requerido">
                                <p class="m-b-0" v-for="error in dataErrors.requerido">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row m-b-15" v-if="scope.dataForm.tipo == 'Listado'">
                        <!-- Opciones listado  Field -->
                        {!! Form::label('opciones_listado', trans('Opciones del listado') . ':', [
                            'class' => 'col-form-label col-md-2 required',
                        ]) !!}
                        <div class="col-md-10">
                            <select-check css-class="form-control" name-field="opciones_listado"
                                reduce-label="nombre" :value="scope.dataForm" :is-required="true"
                                ref-select-check="valores" :enable-search="true" :is-multiple="true"
                                :taggable="true">
                            </select-check>
                            <small>Ingrese el nombre de las opciones y seleccionela o presione la tecla Enter para
                                agregarlas</small>
                            <div class="invalid-feedback" v-if="dataErrors.tipo">
                                <p class="m-b-0" v-for="error in dataErrors.tipo">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
            <div class="invalid-feedback" v-if="dataErrors.sub_estados">
                <p class="m-b-0" v-for="error in dataErrors.sub_estados">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Actividades del documento</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Sub Estados Field -->
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar actividad" :data-list.sync="dataForm.sub_estados_value"
                :is-remove="true"
                :data-list-options="[
                    { label: 'Estado', name: 'subestado', isShow: true }
                ]"
                class-container="col-md-12" class-table="table table-bordered" :is-remove="false">
                <template #fields="scope">
                    <!-- Variables Field -->
                    <div class="form-group row m-b-15">
                        {!! Form::label('subestado', trans('Actividad') . ':', ['class' => 'col-form-label col-md-3']) !!}
                        <div class="col-md-9">
                            {!! Form::text('subestado', null, [
                                ':class' => "{'form-control':true, 'is-invalid':dataErrors.subestado }",
                                'v-model' => 'scope.dataForm.subestado',
                                'required' => true,
                            ]) !!}
                            <div class="invalid-feedback" v-if="dataErrors.subestado">
                                <p class="m-b-0" v-for="error in dataErrors.subestado">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
            <div class="invalid-feedback" v-if="dataErrors.sub_estados">
                <p class="m-b-0" v-for="error in dataErrors.sub_estados">@{{ error }}</p>
            </div>
        </div>

        <!--  Sub Estados Requerido Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('sub_estados_requerido', trans('¿La actividad es requerida?') . ':', [
                'class' => 'col-form-label col-md-3 required',
            ]) !!}
            <!-- sub_estados_requerido switcher -->
            <div class="switcher col-md-9">
                <input type="checkbox" name="sub_estados_requerido" id="sub_estados_requerido"
                    v-model="dataForm.sub_estados_requerido" :disabled="dataForm.sub_estados_value.length <= 0">
                <label for="sub_estados_requerido" :style="dataForm.sub_estados_value.length <= 0 ? 'opacity: .5' : ''"></label>
                {{-- <small>@lang('Select the') {{ `@lang('{{ $fieldTitle }}')` | lowercase }}</small> --}}
                <div class="invalid-feedback" v-if="dataErrors.sub_estados_requerido">
                    <p class="m-b-0" v-for="error in dataErrors.sub_estados_requerido">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
