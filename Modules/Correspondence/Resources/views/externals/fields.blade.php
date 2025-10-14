<ul class="nav nav-tabs" data-tabs="tabs">
    <li class="nav-item active" id="tab-radication">
        <button class="nav-link active" id="radication-button" data-toggle="tab" data-target="#radication" @click="radicatied=false;$refs.rotulo_enviada_fields.limpiarDatos()" type="button" role="tab">
            1. Nueva Radicación
        </button>
    </li>

    <li class="nav-item" id="tab-rotule">
        <button class="nav-link" id="rotule-button" data-toggle="tab" data-target="#rotule" type="button" role="tab" :disabled="!radicatied" :title="!radicatied ? 'Para generar el rótulo, primero debe radicar' : ''" :style="!radicatied ? 'cursor: no-drop;' : 'cursor: pointer;'">
            2. Rótulo <i class="fa fa-stamp"></i>
        </button>
    </li>
</ul>


<div class="tab-content">
    <div class="tab-pane active" id="radication" v-if="!radicatied">
        <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos generales de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- Title Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('title', trans('Title') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::text('title', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.title }", 'v-model' => 'dataForm.title', 'required' => true]) !!}
                                <small>Ingrese el título del documento.</small>
                                <div class="invalid-feedback" v-if="dataErrors.title">
                                    <p class="m-b-0" v-for="error in dataErrors.title">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Content Field -->
                        {{-- <div class="form-group row m-b-15">
                        {!! Form::label('content', 'Contenido:', ['class' => 'col-form-label col-md-3 required']) !!}
                        <div class="col-md-9">
                                {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}
                                <small>@lang('Enter the') @{{ `@lang('Content')` | lowercase }}</small>
                                <div class="invalid-feedback" v-if="dataErrors.content">
                                    <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
                                </div>
                            </div>
                        </div> --}}

                        <!-- Type Document Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('type_document', trans('Type Document') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                <div style="display: flex;">
                                    <select-check css-class="form-control" name-field="type" reduce-label="name"
                                        reduce-key="id" name-resource="get-types-external" :value="dataForm" :key="keyRefresh"
                                        :is-required="true" :enable-search="true">
                                    </select-check>
                                    <a href="#" class="fa fa-sync-alt" @click="_updateKeyRefresh();" style="margin: auto; margin-left: 10px;"></a>
                                </div>
                                <small>Seleccione el tipo de documento. Estos tipos de documentos son configurados desde la opción <a href="{{ route('external-types.index') }}" target="_blank">Tipos documentales Enviada</a></small>
                                <div class="invalid-feedback" v-if="dataErrors.type_document">
                                    <p class="m-b-0" v-for="error in dataErrors.type_document">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Folios Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('folios', trans('Folios') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-4">
                                {!! Form::text('folios', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }", 'v-model' => 'dataForm.folios', 'required' => true]) !!}
                                <small>Ingrese la cantidad de folios.</small>
                                <div class="invalid-feedback" v-if="dataErrors.folios">
                                    <p class="m-b-0" v-for="error in dataErrors.folios">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>

                            {!! Form::label('annexes', 'Anexos:', ['class' => 'col-form-label col-md-1 required']) !!}
                            <div class="col-md-4">
                                {!! Form::text('annexes', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes }", 'v-model' => 'dataForm.annexes', 'required' => true]) !!}
                                <small>Ingrese la cantidad de anexos.</small>
                                <div class="invalid-feedback" v-if="dataErrors.annexes">
                                    <p class="m-b-0" v-for="error in dataErrors.annexes">
                                        @{{ error }}
                                    </p>
                                </div>
                            </div>
                        </div>

                           <!-- Require Answer Field -->
                           <div class="form-group row m-b-15">
                                {!! Form::label('have_assigned_correspondence_received', 'Relacionar correspondencia recibida:', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">

                                    <select class="form-control" id="have_assigned_correspondence_received" v-model="dataForm.have_assigned_correspondence_received">
                                        <option value="No">No</option>
                                        <option value="Si">Si</option>
                                    </select>

                                    <small>Seleccione la opción "Si" si desea relacionar una correspondencia recibida.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.have_assigned_correspondence_received">
                                        <p class="m-b-0" v-for="error in dataErrors.have_assigned_correspondence_received">
                                            @{{ error }}</p>
                                    </div>
                                </div>
                            </div>


                            <!-- Consecutive Received Correspondence Field -->
                            <div class="form-group row m-b-15" v-if="dataForm.have_assigned_correspondence_received=='Si'">
                                {!! Form::label('pqr_consecutive', 'Correspondencia recibida a relacionar:', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    <autocomplete
                                    name-prop="consecutive"
                                    name-field="external_received_id"
                                    :value='dataForm'
                                    name-resource="/correspondence/external/correspondences-publics"
                                    css-class="form-control"
                                    :is-required="true"
                                    :name-labels-display="['consecutive', 'issue']"
                                    reduce-key="id"
                                    :min-text-input="7"
                                    name-field-edit="external_received_consecutive"
                                    :fields-change-values="['external_received_consecutive:consecutive','external_received_id']"
                                    >
                                </autocomplete>

                                    <small>Ingrese el consecutivo de la correspondencia recibida.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.pqr_consecutive">
                                        <p class="m-b-0" v-for="error in dataErrors.pqr_consecutive">@{{ error }}</p>
                                    </div>
                                </div>
                            </div>

                           <!-- Require Answer Field -->
                           <div class="form-group row m-b-15">
                                {!! Form::label('require_answer', 'Finaliza PQRS:', ['class' => 'col-form-label col-md-3']) !!}
                                <div class="col-md-9">

                                    <select class="form-control" id="require_answer" v-model="dataForm.require_answer" @change="$set(dataForm, 'pqr_consecutive', '')">
                                        <option value="No">No</option>
                                        <option value="Si">Si</option>
                                    </select>

                                    <small>Seleccione la opción "Si" si esta correspondencia finaliza un PQR.</small>
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

                            <div class="form-group row m-b-15">
                                <!-- channel Field -->
                                {!! Form::label('channel', trans('Canal') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-4">
                                    {{-- <select-check
                                        css-class="form-control"
                                        name-field="channel"
                                        reduce-label="name"
                                        reduce-key="id"
                                        name-resource="get-constants/external_received_channels"
                                        :value="dataForm"
                                        :is-required="true">
                                    </select-check> --}}
                                        <select name="channel" required="required" class="form-control" v-model="dataForm.channel">
                                            <option value="1">Correo certificado</option>
                                            <option value="2">Correo electrónico</option>
                                            <option value="3">Fax</option>
                                            <option value="4">Personal</option>
                                            <option value="5">Telefónico</option>
                                            <option value="6">Web</option>
                                            <option value="7">Notificación por aviso</option>
								            <option value="8">Buzón de sugerencias</option>
                                        </select>
                                    <small>@lang('Seleccione') @{{ `@lang('Canal')` | lowercase }}.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.channel">
                                        <p class="m-b-0" v-for="error in dataErrors.channel">
                                            @{{ error }}
                                        </p>
                                    </div>
                                </div>

                                <!-- guia Field -->
                                {!! Form::label('guia', 'Número de Guía:', ['class' => 'col-form-label col-md-1']) !!}
                                <div class="col-md-4">
                                    {!! Form::text('guia', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.guia }", 'v-model' => 'dataForm.guia']) !!}
                                    <small>@lang('Enter the') @{{ `@lang('guia')` | lowercase }}.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.guia">
                                        <p class="m-b-0" v-for="error in dataErrors.guia">
                                            @{{ error }}</p>
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
                <h4 class="panel-title"><strong>Datos de origen</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <!-- From Field -->
                        <div class="form-group row m-b-15">
                            {!! Form::label('from', 'Funcionario:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">

                                <autocomplete
                                    name-prop="fullname"
                                    name-field="from_id"
                                    :value='dataForm'
                                    name-resource="/correspondence/get-only-users"
                                    css-class="form-control"
                                    :is-required="true"
                                    :name-labels-display="['fullname']"
                                    reduce-key="id"

                                    name-field-edit="from">
                                </autocomplete>

                                {{-- <select-check
                                    css-class="form-control"
                                    name-field="from_id"
                                    reduce-label="fullname"
                                    reduce-key="id"
                                    name-resource="/intranet/get-users"
                                    :value="dataForm"
                                    :is-required="true"
                                    :enable-search="true"

                                    >
                                </select-check>  --}}

                                <small>Ingrese el nombre del funcionario y luego seleccione. Por ejemplo: Camilo.</small>

                                <div class="invalid-feedback" v-if="dataErrors.from">
                                    <p class="m-b-0" v-for="error in dataErrors.from">
                                        @{{ error }}</p>
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
                <h4 class="panel-title"><strong>Datos de destino</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                    <div class="row">
                        <dynamic-list label-button-add="Agregar ciudadano como destino" icon-button-add="fas fa-user-plus" :data-list.sync="dataForm.citizens"
                            :data-list-options="[
                                { label: 'Trato', name: 'trato', isShow: true },
                                { label: 'Entidad', name: 'entidad', isShow: true },
                                { label: 'Dirección', name: 'direccion', isShow: true },
                                { label: 'Cargo', name: 'cargo', isShow: true },
                                { label: 'Ciudadano', name: 'citizen_name', isShow: true, refList: 'ciudadanoRef' },
                                { label: 'Documento', name: 'citizen_document', isShow: true },
                                { label: 'Correo', name: 'citizen_email', isShow: true },
                                { label: 'Departamento', name: 'department_id', isShow: true, nameObjectKey: ['departamento_informacion', 'name'], refList: 'deparment_ref' },
                                { label: 'Ciudad', name: 'city_id', isShow: true, nameObjectKey: ['ciudad_informacion', 'name'], refList: 'city_ref' },

                            ]"
                            class-container="col-md-12" class-table="table table-bordered">
                            <template #fields="scope">

                                <!-- trato y demas campos-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('trato', 'Trato (Opcional):', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('trato', 'Ciudadano', [':class' => "{'form-control':true, 'is-invalid':dataErrors.trato }", 'v-model' => 'scope.dataForm.trato', 'required' => false]) !!}
                                                <small>Ingrese el trato, ejemplo (Señor, Ciudadano, Doctor, Docente, etc.).</small>
                                            </div>
                                            <label for="cargo" class="col-form-label col-md-1" >Cargo (Opcional):</label>
                                            <div class="col-md-4" style="float: right;">
                                                {!! Form::text('cargo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cargo }", 'v-model' => 'scope.dataForm.cargo', 'required' => false]) !!}
                                                <small>Ingrese el cargo del ciudadano.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row m-b-15">
                                            <label for="entidad" class="col-form-label col-md-3">Entidad (Opcional):</label>
                                            <div class="col-md-4" style="float: right;">
                                                {!! Form::text('entidad', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.entidad }", 'v-model' => 'scope.dataForm.entidad', 'required' => false]) !!}
                                                <small>Ingrese la entidad a la que pertenece el ciudadano.</small>
                                            </div>
                                            {!! Form::label('direccion', 'Dirección (Opcional):', ['class' => 'col-form-label col-md-1']) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('direccion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.direccion }", 'v-model' => 'scope.dataForm.direccion', 'required' => false]) !!}
                                                <small>Ingrese la dirección.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Fin trato y demas campos-->


                                <div class="form-group row m-b-15">
                                    {!! Form::label('citizen_name', trans('Nombre del ciudadano/empresa').':', ['class' => 'col-form-label col-md-3 required']) !!}
                                    <div class="col-md-9">
                                        <autocomplete
                                            :is-update="isUpdate"
                                            {{-- :value-default="scope.dataForm.citizen_id" --}}
                                            name-prop="name"
                                            name-field="citizen_id"
                                            :value='scope.dataForm'
                                            name-resource="/intranet/get-citizens"
                                            css-class="form-control"
                                            :name-labels-display="['document_number', 'name']"
                                            :fields-change-values="['citizen_document:document_number', 'citizen_email:email', 'citizen_name:name','department_id:states_id','city_id:city_id']"
                                            reduce-key="user_id"
                                            :is-required="true"
                                            name-field-object="ciudadano_datos"
                                            ref="ciudadanoRef"
                                            name-field-edit="citizen_name"
                                            :key="keyRefresh"
                                            >
                                        </autocomplete>
                                        {{-- {!! Form::text('citizen_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.citizen_name }", 'v-model' => 'dataForm.citizen_name', 'required' => false]) !!} --}}
                                        <small>Ingrese el nombre del ciudadano o empresa y luego seleccione. Por ejemplo: Maria. Si el ciudadano no existe utilice la opción "Nuevo Ciudadano"</small>
                                        <div class="invalid-feedback" v-if="dataErrors.citizen_name">
                                            <p class="m-b-0" v-for="error in dataErrors.citizen_name">@{{ error }}</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- Department Id Field -->
                                        <div class="form-group row m-b-15">
                                            {!! Form::label('department_id', trans('Department').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-4">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="department_id"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    name-resource="/get-states-by-country/48"
                                                    :value="scope.dataForm"
                                                    :is-required="false"
                                                    :enable-search="true"
                                                    name-field-object="departamento_informacion"
                                                    ref-select-check="deparment_ref"
                                                    >
                                                </select-check>
                                                <small>Seleccione el departamento.</small>
                                            </div>

                                            <label for="city_id" class="col-form-label col-md-1" v-if="scope.dataForm.department_id" >Ciudad:</label>
                                            <div class="col-md-4" v-if="scope.dataForm.department_id">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="city_id"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    :name-resource="'/get-cities-by-state/'+scope.dataForm.department_id"
                                                    :value="scope.dataForm"
                                                    :key="scope.dataForm.department_id"
                                                    :is-required="false"
                                                    :enable-search="true"
                                                    name-field-object="ciudad_informacion"
                                                    ref-select-check="city_ref"
                                                    >
                                                </select-check>
                                                <small>Seleccione la ciudad.</small>

                                            </div>
                                        </div>
                                    </div>

                                </div>



                                    <div id="ciudadano_personalizado">
                                        <!-- Nombre Ciudadano Field -->
                                        <div class="form-group row m-b-15">

                                            <!-- Documento Ciudadano Field -->
                                            {!! Form::label('citizen_document', trans('Documento del ciudadano').':', ['class' => 'col-form-label col-md-3']) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('citizen_document', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.citizen_document }", 'v-model' => 'scope.dataForm.citizen_document', 'required' => false]) !!}
                                                <small>@lang('Enter the') el número de identificación del ciudadano.</small>
                                                <div class="invalid-feedback" v-if="dataErrors.citizen_document">
                                                    <p class="m-b-0" v-for="error in dataErrors.citizen_document">@{{ error }}</p>
                                                </div>
                                            </div>

                                            {!! Form::label('citizen_email', trans('Correo del ciudadano').':', ['class' => 'col-form-label col-md-1']) !!}
                                            <div class="col-md-4">
                                                {!! Form::email('citizen_email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.citizen_email }", 'v-model' => 'scope.dataForm.citizen_email', 'required' => false]) !!}
                                                <small>@lang('Enter the') @{{ `@lang('Email Ciudadano')` | lowercase }}.</small>
                                                <div class="invalid-feedback" v-if="dataErrors.citizen_email">
                                                    <p class="m-b-0" v-for="error in dataErrors.citizen_email">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </template>
                        </dynamic-list>
                    </div>
                    <button class="btn btn-primary" type="button" onclick="jQuery('#crear_ciudadano').toggle(350);"><i class="fa fa-user mr-2" aria-hidden="true"></i>Nuevo ciudadano</button>

                    <div id="crear_ciudadano" style="background-color: #40b0a612!important; padding-left: 1px; display: none;">
                        <hr />
                        <h4>Formulario para crear un ciudadano</h4>
                        @include('intranet::citizens.fields_form')
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="callFunctionComponent('external_ref','addCiudadano');"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
                        </div>
                    </div>



                    <div class="col-md-12 mt-5">
                        <!--  copias Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios de copia:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="recipient_autocomplete" name-field="copies_users"
                                    name-resource="/correspondence/get-only-users"
                                    name-options-list="external_copy" :name-labels-display="['fullname']" name-key="users_id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>


            </div>
            <!-- end panel-body -->
        </div>

        <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Documento principal de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('url', 'Documento de la correspondencia radicada:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="30"
                                    :max-filesize="11" file-path="public/container/external_{{ date('Y') }}"
                                    message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar una copia electrónica de una carta u otro documento recibido\. El tamaño máximo permitido es de 10 MB."
                                    :is-update="isUpdate"  ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                                </input-file>

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
                <h4 class="panel-title"><strong>Adjuntar anexos</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('url', 'Lista de archivos anexos:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <input-file :file-name-real="true":value="dataForm" name-field="annexes_digital" :max-files="30"
                                    :max-filesize="11" file-path="public/container/external_{{ date('Y') }}"
                                    message="Arrastre o seleccione los archivos" help-text="Lista de archivos anexos. El tamaño máximo permitido es de 10 MB."
                                    :is-update="isUpdate"  ruta-delete-update="correspondence/externals-delete-file" :id-file-delete="dataForm.id">
                                </input-file>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <!-- Annexes Description Field -->
                        <div  class="form-group row m-b-15">
                            {!! Form::label('annexes_description', 'Descripción de anexos:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('annexes_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes_description }", 'v-model' => 'dataForm.annexes_description', 'required' => false, 'rows' => '3']) !!}
                                <small>Ingrese una descripción de los anexos. Por ejemplo: Evidencias, fotos.</small>
                                <div class="invalid-feedback" v-if="dataErrors.annexes_description">
                                    <p class="m-b-0" v-for="error in dataErrors.annexes_description">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end panel-body -->
        </div>

        {{-- Clasificacion documental --}}
        <div class="panel" data-sortable-id="ui-general-1" id="clasificacion" v-if="!radicatied">
            @include('correspondence::internals.field_clasificacion_documental')
        </div>

    </div>

    {{-- v-if="isUpdate" --}}
    <div class="tab-pane" id="rotule" v-show="radicatied">

        <rotule-component
            type-call="rotule_fields"
            name='{{ config('app.name') }}' 
            execute-url-axios-preview="document-preview-external/" 
            execute-url-axios-rotular="document-with-rotule-external/" 
            ref="rotulo_enviada_fields" 
            :update-props="true"
            type-correspondence="Enviada">
        </rotule-component>
        {{-- @include('correspondence::externals.rotule') --}}

    </div>
</div>
