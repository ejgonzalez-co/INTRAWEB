{{-- Nuevo expediente --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="!isUpdate || dataForm.estado == 'Devuelto para modificaciones'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Nuevo expediente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- classification_production_office Field -->
        <div class="form-group row m-b-15">
            <!-- Campo Oficina Productora -->
            {!! Form::label('classification_production_office', 'Oficina productora: ', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check
                    css-class="form-control"
                    name-field="classification_production_office"
                    reduce-label="nombre"
                    name-resource="get-dependencies"
                    :is-required="true"
                    :value="dataForm"
                    :enable-search="true"
                    :ids-to-empty="['classification_serie','classification_subserie']">
                </select-check>
                <small>Seleccione una oficina productora para obtener las series relacionadas.</small>
                <div class="invalid-feedback" v-if="dataErrors.classification_production_office">
                    <p class="m-b-0" v-for="error in dataErrors.classification_production_office">
                        @{{ error }}
                    </p>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <!-- Campo Serie -->
            {!! Form::label('classification_serie', 'Serie:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-4">
                <select-check
                    css-class="form-control"
                    name-field="classification_serie"
                    reduce-label="name"
                    reduce-key="id_series_subseries"
                    :is-required="true"
                    :name-resource="'/documentary-classification/get-inventory-documentals-serie-dependency-expediente/' + dataForm.classification_production_office"
                    :value="dataForm"
                    :enable-search="true"
                    name-field-object="serie_clasificacion_documental"
                    :key="dataForm.classification_production_office">
                </select-check>
                <small>Seleccione una serie documental, ejemplo: Contratos.</small>
                <div class="invalid-feedback" v-if="dataErrors.classification_serie">
                    <p class="m-b-0" v-for="error in dataErrors.classification_serie">
                        @{{ error }}
                    </p>
                </div>
            </div>

            <!-- Campo Subserie -->
            {!! Form::label('classification_subserie', 'Subserie:', ['class' => 'col-form-label col-md-1']) !!}
            <div class="col-md-4">
                <select-check
                    css-class="form-control"
                    name-field="classification_subserie"
                    reduce-label="name_subserie"
                    :name-resource="'/documentary-classification/get-subseries-clasificacion-expediente?serie=' + dataForm.classification_serie"
                    :is-required="false"
                    :value="dataForm"
                    :key="dataForm.classification_serie"
                    name-field-object="subserie_clasificacion_documental"
                    :enable-search="true">
                </select-check>
                <small>Seleccione una sub-serie documental, ejemplo: Contratos de trabajo.</small>
                <div class="invalid-feedback" v-if="dataErrors.classification_subserie">
                    <p class="m-b-0" v-for="error in dataErrors.classification_subserie">
                        @{{ error }}
                    </p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('nombre_expediente', 'Nombre del expediente'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('nombre_expediente', null, ['class' => 'form-control', 'v-model' => 'dataForm.nombre_expediente', 'required' => true]) !!}
                <small>Ingresa el nombre del expediente.</small>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('fecha_inicio_expediente', 'Fecha inicio del expediente'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::date('fecha_inicio_expediente', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_inicio_expediente }", 'v-model' => 'dataForm.fecha_inicio_expediente', 'required' => true]) !!}
                <small>@lang('Enter the') la @{{ `@lang('Initial Date')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.fecha_inicio_expediente">
                    <p class="m-b-0" v-for="error in dataErrors.fecha_inicio_expediente">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('descripcion', 'Descripción:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('descripcion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion}", 'v-model' => 'dataForm.descripcion', 'required' => true, 'rows' => '3']) !!}
                <small>Ingrese una descripción.</small>
                <div class="invalid-feedback" v-if="dataErrors.descripcion">
                    <p class="m-b-0" v-for="error in dataErrors.descripcion">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('id_responsable', 'Responsable'.':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check css-class="form-control" name-field="id_responsable" reduce-label="fullname" name-resource="obtener-responsable" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                <small>@lang('Enter the') el funcionario responsable del expediente</small>
                <div class="invalid-feedback" v-if="dataErrors.id_responsable">
                    <p class="m-b-0" v-for="error in dataErrors.id_responsable">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Metadatos de la Subserie --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="(!isUpdate || dataForm.estado == 'Devuelto para modificaciones') && dataForm.subserie_clasificacion_documental?.criterios_busqueda_expedientes?.length > 0 && dataForm.subserie_clasificacion_documental && dataForm.classification_subserie">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos de la Subserie</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="metadato in dataForm.subserie_clasificacion_documental?.criterios_busqueda_expedientes">
            <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">@{{ metadato.nombre }}:</label>
            <div class="col-md-9">

                <input
                    v-if="metadato && metadato.tipo_campo !== 'Lista'"
                    :type="metadato.tipo_campo === 'Texto' ? 'text' : (metadato.tipo_campo === 'Número' ? 'number' : 'date')"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">

                    <select
                        v-else-if="metadato"
                        v-model="dataForm.metadatos[metadato.id]"
                        :name="metadato.id || ''"
                        :id="metadato.id || ''"
                        class="form-control"
                        :required="metadato.requerido">
                        <option v-for="(value, key) in parseOpciones(metadato.opciones)" :value="key">@{{ value }}</option>
                    </select>

                <small v-if="metadato">@{{ metadato.texto_ayuda }}</small>
            </div>
        </div>
    </div>
</div>

{{-- Metadatos --}}
<div class="panel" data-sortable-id="ui-general-1" v-else-if="(!isUpdate || dataForm.estado == 'Devuelto para modificaciones') && dataForm.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda_expedientes?.length > 0 && dataForm.serie_clasificacion_documental && dataForm.classification_serie">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Metadatos de la serie</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15" v-for="metadato in dataForm.serie_clasificacion_documental?.series_osubseries?.criterios_busqueda_expedientes">
            <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">@{{ metadato.nombre }}:</label>
            <div class="col-md-9">

                <input
                    v-if="metadato && metadato.tipo_campo !== 'Lista'"
                    :type="metadato.tipo_campo === 'Texto' ? 'text' : (metadato.tipo_campo === 'Número' ? 'number' : 'date')"
                    v-model="dataForm.metadatos[metadato.id]"
                    :name="metadato.id || ''"
                    :id="metadato.id || ''"
                    class="form-control"
                    :required="metadato.requerido">

                    <select
                        v-else-if="metadato"
                        v-model="dataForm.metadatos[metadato.id]"
                        :name="metadato.id || ''"
                        :id="metadato.id || ''"
                        class="form-control"
                        :required="metadato.requerido">
                        <option v-for="(value, key) in parseOpciones(metadato.opciones)" :value="key">@{{ value }}</option>
                    </select>

                <small v-if="metadato">@{{ metadato.texto_ayuda }}</small>
            </div>
        </div>
    </div>
</div>

{{-- Información general --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="!isUpdate || dataForm.estado == 'Devuelto para modificaciones'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Información general</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Permiso Crear Documentos Todas Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'existe_fisicamente',
                'Existe físicamente' . ':',
                ['class' => 'col-form-label col-md-3 required'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="existe_fisicamente"
                    id="existe_fisicamente" v-model="dataForm.existe_fisicamente" required>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                </select>
                <small>@lang('Select the') si el expediente existe físicamente</small>
                <div class="invalid-feedback" v-if="dataErrors.existe_fisicamente">
                    <p class="m-b-0" v-for="error in dataErrors.existe_fisicamente">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div v-if="dataForm.existe_fisicamente == 'Si'">

            <div class="form-group row m-b-15">
                {!! Form::label('ubicacion', 'Ubicación'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('ubicacion', null, ['class' => 'form-control', 'v-model' => 'dataForm.ubicacion', 'required' => true]) !!}
                    <small>Ingresa la ubicación del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('sede', 'Sede'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('sede', null, ['class' => 'form-control', 'v-model' => 'dataForm.sede', 'required' => false]) !!}
                    <small>Ingresa la sede del expediente.</small>
                </div>
            </div>

            <!-- Dependencia Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('dependencia', 'Dependencia' . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre"
                        :value="dataForm" :is-required="true" :enable-search="true" :is-multiple="false" name-resource="/intranet/get-dependencies">
                    </select-check>
                    <small>Seleccione la dependencia</small>
                    <div class="invalid-feedback" v-if="dataErrors.dependencia">
                        <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('area_archivo', 'Area de archivo'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('area_archivo', null, ['class' => 'form-control', 'v-model' => 'dataForm.area_archivo', 'required' => false]) !!}
                    <small>Ingresa la Area de archivo del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('estante', 'Estante'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('estante', null, ['class' => 'form-control', 'v-model' => 'dataForm.estante', 'required' => false]) !!}
                    <small>Ingresa la Estante del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('modulo', 'Módulo'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('modulo', null, ['class' => 'form-control', 'v-model' => 'dataForm.modulo', 'required' => false]) !!}
                    <small>Ingresa la Módulo del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('entrepano', 'Entrepaño'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('entrepano', null, ['class' => 'form-control', 'v-model' => 'dataForm.entrepano', 'required' => false]) !!}
                    <small>Ingresa la Entrepaño del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('caja', 'Caja'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('caja', null, ['class' => 'form-control', 'v-model' => 'dataForm.caja', 'required' => false]) !!}
                    <small>Ingresa la Caja del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('cuerpo', 'Cuerpo'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('cuerpo', null, ['class' => 'form-control', 'v-model' => 'dataForm.cuerpo', 'required' => false]) !!}
                    <small>Ingresa la Cuerpo del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('unidad_conservacion', 'Unidad de conservación'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('unidad_conservacion', null, ['class' => 'form-control', 'v-model' => 'dataForm.unidad_conservacion', 'required' => false]) !!}
                    <small>Ingresa la Unidad de conservación del expediente.</small>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('fecha_archivo', 'Fecha de archivo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::date('fecha_archivo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_archivo }", 'v-model' => 'dataForm.fecha_archivo', 'required' => true]) !!}
                    <small>Ingrese la fecha del archivo</small>
                    <div class="invalid-feedback" v-if="dataErrors.fecha_archivo">
                        <p class="m-b-0" v-for="error in dataErrors.fecha_archivo">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15">
                {!! Form::label('numero_inventario', 'Número de inventario'.':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::text('numero_inventario', null, ['class' => 'form-control', 'v-model' => 'dataForm.numero_inventario', 'required' => false]) !!}
                    <small>Ingresa la Unidad de conservación del expediente.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Detalles del expediente --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="!isUpdate || dataForm.estado == 'Devuelto para modificaciones'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Detalles del expediente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        {{-- <div class="form-group row m-b-15" v-if="dataForm.estado">
            {!! Form::label(
                'estado',
                'Estado' . ':',
                ['class' => 'col-form-label col-md-3 required'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="estado"
                    id="estado" v-model="dataForm.estado" required>
                    <option value="Abierto">Abierto</option>
                    <option value="Cerrado">Cerrado</option>
                </select>
                <small>@lang('Select the') si el expediente Estado</small>
                <div class="invalid-feedback" v-if="dataErrors.estado">
                    <p class="m-b-0" v-for="error in dataErrors.estado">
                        @{{ error }}</p>
                </div>
            </div>
        </div> --}}
        <div class="form-group row m-b-15">
            {!! Form::label('observacion', 'Observación:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::textarea('observacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observacion}", 'v-model' => 'dataForm.observacion', 'required' => false, 'rows' => '3']) !!}
                <small>Ingrese una Observación.</small>
                <div class="invalid-feedback" v-if="dataErrors.observacion">
                    <p class="m-b-0" v-for="error in dataErrors.observacion">
                        @{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Dependencias o funcionarios autorizados para incluir y editar documentos en el expediente --}}
{{-- <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.id_responsable == @json(Auth::user()->id) && dataForm.estado != 'Cerrado'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Dependencias o funcionarios autorizados para incluir y editar documentos en el expediente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Permiso Crear Documentos Todas Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'permiso_usar_expedientes_todas',
                trans('¿Todas las dependencias pueden incluir y editar documentos en el expediente?') . ':',
                ['class' => 'col-form-label col-md-3'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="permiso_usar_expedientes_todas"
                    id="permiso_usar_expedientes_todas" v-model="dataForm.permiso_usar_expedientes_todas">
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
                <small>@lang('Select the') si todas las dependencias pueden incluir y editar documentos en el expediente</small>
                <div class="invalid-feedback" v-if="dataErrors.permiso_usar_expedientes_todas">
                    <p class="m-b-0" v-for="error in dataErrors.permiso_usar_expedientes_todas">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.permiso_usar_expedientes_todas == 0">
            <h6 class="col-form-label">Defina las dependencias o usuarios con permiso de incluir y editar documentos en el expediente</h6>
            <dynamic-list label-button-add="Agregar dependencia/usuario"
                :data-list.sync="dataForm.ee_permiso_usar_expedientes"
                :data-list-options="[
                    { label: 'Dependencia/Funcionario', name: 'nombre', nameObjectKey: ['recipient_datos', 'nombre'], isShow: true, refList: 'dependencia_ref' },
                    { label: 'Limitar la visibilidad de los documentos al usuario o dependencia', name: 'limitar_visibilidad_documentos', isShow: true, isEditable: true, inputType: 'checkbox' }
                ]"
                class-container="col-md-12" class-table="table table-bordered" campo-validar-existencia="dependencia_usuario_id">
                <template #fields="scope">
                    <div class="form-group row m-b-15">
                        {!! Form::label('name', 'Nombre del funcionario o dependencia:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                :is-update="isUpdate"
                                {{-- :value-default="scope.dataForm.id" --}}
                                name-prop="nombre"
                                name-field="dependencia_usuario_id"
                                :value='scope.dataForm'
                                name-resource="/expedientes-electronicos/get-usuarios-autorizados"
                                css-class="form-control"
                                :name-labels-display="['tipo','nombre']"
                                :fields-change-values="['nombre:nombre','tipo:tipo']"
                                reduce-key="id"
                                :is-required="true"
                                name-field-object="recipient_datos"
                                ref="dependencia_ref"
                                name-field-edit="nombre"
                                :key="keyRefresh"
                                :ids-to-empty="['dependencia_informacion']"
                                :activar-blur="true"
                                >
                            </autocomplete>
                            <small>Ingrese y seleccione el nombre de la dependencia o usuario para añadirlo.</small>
                            <div class="invalid-feedback" v-if="dataErrors.name">
                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
</div> --}}

{{-- Dependencias o funcionarios autorizados para ver información y documentos del expediente --}}
{{-- <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.id_responsable == @json(Auth::user()->id)">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Dependencias o funcionarios autorizados para ver información y documentos del expediente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Permiso Crear Documentos Todas Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'permiso_consultar_expedientes_todas',
                trans('¿Todas las dependencias están autorizadas para ver información y documentos del expediente?') . ':',
                ['class' => 'col-form-label col-md-3'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="permiso_consultar_expedientes_todas"
                    id="permiso_consultar_expedientes_todas" v-model="dataForm.permiso_consultar_expedientes_todas">
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
                <small>@lang('Select the') si todas las dependencias están autorizadas para ver información y documentos del expediente</small>
                <div class="invalid-feedback" v-if="dataErrors.permiso_consultar_expedientes_todas">
                    <p class="m-b-0" v-for="error in dataErrors.permiso_consultar_expedientes_todas">
                        @{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="dataForm.permiso_consultar_expedientes_todas == 0">
            <h6 class="col-form-label">Defina las dependencias o usuarios con permiso de ver información y documentos del expediente</h6>
            <dynamic-list label-button-add="Agregar dependencia/usuario"
                :data-list.sync="dataForm.ee_permiso_consultar_expedientes"
                :data-list-options="[
                    { label: 'Dependencia/Funcionario', name: 'nombre', nameObjectKey: ['recipient_datos', 'nombre'], isShow: true, refList: 'dependencia_consulta_ref' }
                ]"
                class-container="col-md-12" class-table="table table-bordered" campo-validar-existencia="dependencia_usuario_id">
                <template #fields="scope">
                    <div class="form-group row m-b-15">
                        {!! Form::label('name', 'Nombre del funcionario o dependencia:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                :is-update="isUpdate"
                                {{-- :value-default="scope.dataForm.id" --}}
                                name-prop="nombre"
                                name-field="dependencia_usuario_id"
                                :value='scope.dataForm'
                                name-resource="/expedientes-electronicos/get-usuarios-autorizados"
                                css-class="form-control"
                                :name-labels-display="['tipo','nombre']"
                                :fields-change-values="['nombre:nombre','tipo:tipo']"
                                reduce-key="id"
                                :is-required="true"
                                name-field-object="recipient_datos"
                                ref="dependencia_consulta_ref"
                                name-field-edit="nombre"
                                :key="keyRefresh"
                                :ids-to-empty="['dependencia_informacion']"
                                :activar-blur="true"
                                >
                            </autocomplete>
                            <small>Ingrese y seleccione el nombre de la dependencia o usuario para añadirlo.</small>
                            <div class="invalid-feedback" v-if="dataErrors.name">
                                <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
</div> --}}

{{-- Funcionarios externos autorizados para ver información y documentos del expediente --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.id_responsable == @json(Auth::user()->id)">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Funcionarios internos y externos autorizados para ver información y documentos del expediente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">

         <!--  permiso_general_expediente Field -->
        <div class="form-group row m-b-15">
            {!! Form::label(
                'permiso_general_expediente',
                trans('Permisos generales sobre el expediente y sus documentos') . ':',
                ['class' => 'col-form-label col-md-3'],
            ) !!}
            <div class="col-md-9">
                <select class="form-control" name="permiso_general_expediente"
                    id="permiso_general_expediente" v-model="dataForm.permiso_general_expediente">
                    <option value="Todas las dependencias pueden incluir y editar documentos en el expediente">Todas las dependencias pueden incluir y editar documentos en el expediente</option>
                    <option value="Todas las dependencias están autorizadas para ver información y documentos del expediente">Todas las dependencias están autorizadas para ver información y documentos del expediente</option>
                </select>
                <small>@lang('Select the') el permiso general de las dependencias sobre el expediente</small>
                <div class="invalid-feedback" v-if="dataErrors.permiso_general_expediente">
                    <p class="m-b-0" v-for="error in dataErrors.permiso_general_expediente">
                        @{{ error }}</p>
                </div>
            </div>
        </div>
        <hr />
        <!--  Permiso Usuarios Expedientes Field -->
        <div class="form-group row m-b-15">
            <h6 class="col-form-label">Defina los usuarios internos y/o externos con permiso de usar, ver o gestionar los documentos del expediente (Opcional)</h6>
            <dynamic-list label-button-add="Agregar usuario/dependencia"
                :data-list.sync="dataForm.ee_permiso_usuarios_expedientes"
                :data-list-options="[
                    { label: 'Tipo', name: 'tipo_usuario', isShow: true }, 
                    { label: 'Nombre', name: 'nombre', isShow: true }, 
                    { label: 'Correo', name: 'correo', isShow: true }, 
                    { label: 'Permiso', name: 'permiso', isShow: true }, 
                    { label: '¿Tiene permiso de descargar los documentos del expediente?', name: 'limitar_descarga_documentos', isShow: true, isEditable: true, inputType: 'checkbox' }
                ]"
                class-container="col-md-12" class-table="table table-bordered" campo-validar-existencia="correo">
                <template #fields="scope">

                    <!-- tipo_usuario Field -->
                    <div class="form-group row m-b-15">
                        <!-- Campo tipo_usuario -->
                        {!! Form::label('tipo_usuario', 'Tipo de usuario: ', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <select class="form-control" name="tipo_usuario" id="tipo_usuario" v-model="scope.dataForm.tipo_usuario" @change="$set(scope.dataForm, 'dependencia_usuario_id', ''); $set(scope.dataForm, 'nombre', ''); $set(scope.dataForm, 'recipient_datos', ''); $set(scope.dataForm, 'correo', ''); $set(scope.dataForm, 'permiso', '');" required>
                                <option value="Interno">Interno</option>
                                <option value="Externo">Externo</option>
                            </select>
                            <small>Seleccione el tipo de usuario.</small>
                        </div>
                    </div>

                     <div class="form-group row m-b-15" v-if="scope.dataForm.tipo_usuario == 'Interno'">
                        {!! Form::label('name', 'Nombre del funcionario o dependencia:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <autocomplete
                                :is-update="isUpdate"
                                :value-default="scope.dataForm.id"
                                name-prop="nombre"
                                name-field="dependencia_usuario_id"
                                :value='scope.dataForm'
                                name-resource="/expedientes-electronicos/get-usuarios-autorizados"
                                css-class="form-control"
                                :name-labels-display="['tipo','nombre']"
                                :fields-change-values="['nombre:nombre','tipo:tipo','correo:correo']"
                                reduce-key="id"
                                :is-required="true"
                                name-field-object="recipient_datos"
                                ref="dependencia_consulta_ref"
                                name-field-edit="nombre"
                                :key="keyRefresh"
                                :ids-to-empty="['dependencia_informacion']"
                                :activar-blur="true"
                                >
                            </autocomplete>
                            <small>Ingrese y seleccione el nombre del usuario o dependencia para añadirlo.</small>
                        </div>
                    </div>
                    <span v-if="scope.dataForm.tipo_usuario == 'Externo'">
                        <div class="form-group row m-b-15">
                            {!! Form::label('nombre', 'Nombre del usuario'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::text('nombre', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.nombre', 'required' => true]) !!}
                                <small>Ingresa el nombre del usuario.</small>
                            </div>
                        </div>

                        <div class="form-group row m-b-15">
                            {!! Form::label('correo', 'Correo del usuario'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::email('correo', null, ['class' => 'form-control', 'v-model' => 'scope.dataForm.correo', 'required' => true]) !!}
                                <small>Ingresa el correo del usuario.</small>
                            </div>
                        </div>
                    </span>

                    <div class="form-group row m-b-15">
                        {!! Form::label('permiso', 'Permisos del usuario/dependencia'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                            <select class="form-control" name="permiso" id="permiso" v-model="scope.dataForm.permiso" required>
                                <option value="Incluir información y editar documentos">Incluir información y editar documentos</option>
                                <option value="Incluir información y editar documentos (solo del usuario)">Incluir información y editar documentos (solo del usuario)</option>
                                <option value="Consultar el expediente y sus documentos">Consultar el expediente y sus documentos</option>
                            </select>
                            <small>Seleccione el permiso que tendrá el usuario/dependencia.</small>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
</div>

{{-- Detalles de la modificación aplicada al formulario --}}
<div class="panel" data-sortable-id="ui-general-1" v-if="isUpdate">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Detalles de la modificación aplicada al formulario</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="form-group row m-b-15">
            {!! Form::label('detalle_modificacion', 'Descripción del cambio:', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('detalle_modificacion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.detalle_modificacion}", 'v-model' => 'dataForm.detalle_modificacion', 'required' => true, 'rows' => '3']) !!}
                <small>Describa la modificación realizada en el formulario.</small>
                <div class="invalid-feedback" v-if="dataErrors.detalle_modificacion">
                    <p class="m-b-0" v-for="error in dataErrors.detalle_modificacion">
                        @{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
