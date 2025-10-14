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

                        @if(config('app.iachatgpt'))
                            <chat-gpt-component></chat-gpt-component>
                            <br>
                        @endif
                        <div class="form-group row m-b-15" id="botonGoogleDocs" v-if="dataForm.editor=='Google Docs'" >
                            <button class="btn" style="background: #2684FC; color: white" type="button" @click="this.window.open(dataForm.template)"><i class="fa fa-file"></i>
                                Agregar contenido al documento en Google Docs</button>
                        </div>
                            <!-- Content Field -->
                        <div class="form-group row m-b-15" v-else>
                            {!! Form::label('content', 'Contenido:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('content', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.content }", 'v-model' => 'dataForm.content', 'required' => true]) !!}


                                    {{-- <text-area-editor
                                    :value="dataForm"
                                    name-field="content"
                                    :hide-modules="{
                                    'bold': true,
                                    'image': true,
                                    'code': true,
                                    'link': true
                                    }"
                                    placeholder="Ingrese el contenido"
                                >
                                </text-area-editor> --}}
                                <small>Ingrese el contenido, este será reemplazado en el documento.</small>
                                <div class="invalid-feedback" v-if="dataErrors.content">
                                    <p class="m-b-0" v-for="error in dataErrors.content">@{{ error }}</p>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row m-b-15">
                            {!! Form::label('state', 'Estado actual del documento:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9 col-form-label">
                                <strong>@{{ dataForm.state ? dataForm.state : 'Elaboración' }}</strong>
                            </div>
                        </div>

                        <div class="form-group row m-b-15" v-if="dataForm.observation_inicial">
                            {!! Form::label('state', 'Última Observación:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <strong>@{{ dataForm.observation_inicial ? dataForm.observation_inicial : 'Aún no tiene observación' }}</strong>
                                </p>
                            </div>
                        </div>


                        <div class="form-group row m-b-15">
                            {!! Form::label('tipo', 'Elija qué desea hacer con este documento:', ['class' => 'col-form-label col-md-3 required', 'id' => 'estados_label']) !!}
                            <input
                                v-if="!dataForm.tipo"
                                type="text"
                                v-model="dataForm.prueba"
                                required
                                style="position: absolute; left: 24px; opacity: 0; pointer-events: none; bottom: 15px;"
                            />
                            <div class="col-md-9">

                                {{-- <select class="form-control" id="tipo" v-model="dataForm.tipo" @change="_updateKeyRefresh" required>
                                    <option value="Elaboración">Enviar a otro funcionario para que continúe con la elaboración</option>
                                    <option value="Revisión">Enviar a otro funcionario para revisión</option>
                                    <option value="Aprobación">Enviar a otro funcionario para aprobación</option>
                                    <option value="Firma Conjunta">Enviar para firma de funcionarios</option>
                                    <option selected="selected" value="Publicación">Publicar documento</option>
                                </select> --}}


                                <div class="btn-group d-flex flex-wrap" role="group" aria-label="Tipo de documento" style="box-shadow: none;">
                                    <button type="button" class="btn btn-sm btn-blue mr-2 mb-2" @click="selectTipo('Elaboración', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'Elaboración' }">Elaborar<div :class="'elaboracion arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                                    <button type="button" class="btn btn-sm btn-yellow mr-2 mb-2 text-black" @click="selectTipo('Revisión', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'Revisión' }">Revisar<div :class="'revision arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                                    <button type="button" class="btn btn-sm btn-cyan mr-2 mb-2" @click="selectTipo('Aprobación', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'Aprobación' }">Aprobar<div :class="'aprobacion arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                                    <button type="button" class="btn btn-sm btn-orange mr-2 mb-2" @click="selectTipo('Firma Conjunta', '.contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'Firma Conjunta' }">Firma conjunta<div :class="'firmar_varios arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                                    <button type="button" class="btn btn-sm btn-green text-white mb-2" @click="selectTipo('Publicación', '.contenedorFormFuncionario, .contenedorFormObservacion')" :class="{ 'selected': dataForm.tipo === 'Publicación' }">Firmar y publicar<div :class="'publicacion arrow-down'" style="display: none; margin-top: 1px;"></div></button>
                                </div>

                                <div class="invalid-feedback" v-if="dataErrors.tipo">
                                    <p class="m-b-0" v-for="error in dataErrors.tipo">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Publicación -->
                        <div class="form-group row m-b-15 contenedorFormPublicacion" v-if="dataForm.tipo=='Publicación'">
                            <div class="alert alert-success w-100" role="alert" style="background-color: rgb(175 213 130);">
                                Al aceptar, usted firma este documento y acepta su publicación automática.
                            </div>
                        </div>

                        <div :class="['contenedorFormFuncionarioObs', {
                            'contenedorFormelaboracion': dataForm.tipo === 'Elaboración',
                            'contenedorFormrevision': dataForm.tipo === 'Revisión',
                            'contenedorFormaprobacion': dataForm.tipo === 'Aprobación',
                            'contenedorFormfirmar_varios': dataForm.tipo === 'Firma Conjunta',
                            'contenedorFormPublicacion': dataForm.tipo === 'Publicación'
                        }]" style="display: none;">

                        <!-- funcionario_revision Field -->
                            <div class="form-group row m-b-15 contenedorFormFuncionario" style="display: none;">

                                {!! Form::label('funcionario_revision', 'Funcionario que elaborará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "Elaboración"']) !!}
                                {!! Form::label('funcionario_revision', 'Funcionario que revisará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "Revisión"']) !!}
                                {!! Form::label('funcionario_revision', 'Funcionario que aprobará el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "Aprobación"']) !!}
                                {!! Form::label('funcionario_revision', 'Funcionarios que firmarán el documento:', ['class' => 'col-form-label col-md-3 required', 'v-if' => 'dataForm.tipo === "Firma Conjunta"']) !!}

                                <div class="col-md-9">

                                    <autocomplete
                                        name-prop="fullname"
                                        name-field="funcionario_revision"
                                        :value='dataForm'
                                        name-resource="/correspondence/get-only-users"
                                        css-class="form-control"
                                        :is-required="dataForm.tipo == 'Elaboración' || dataForm.tipo == 'Revisión' || dataForm.tipo == 'Aprobación' ? true : false"
                                        :name-labels-display="['fullname']"
                                        :fields-change-values="['user_for_last_update:name']"
                                        reduce-key="id"
                                        :activar-blur="true"
                                        name-field-edit="funcionario_revision">
                                    </autocomplete>

                                    <div class="invalid-feedback" v-if="dataErrors.funcionario_revision">
                                        <p class="m-b-0" v-for="error in dataErrors.funcionario_revision">
                                            @{{ error }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Firmantes -->
                            <div class="form-group row m-b-15 contenedorFormFirma" style="display: none;">
                                <div class="alert alert-success w-100" role="alert" style="background-color: rgb(241 188 108);">
                                    Una vez que todos los firmantes hayan completado sus firmas, el documento se publicará automáticamente.
                                  </div>
                                  {!! Form::label('users', 'Funcionarios que firman el documento:', ['class' => 'col-form-label col-md-3']) !!}

                                <div class="col-md-9">

                                    <add-list-autocomplete :value="dataForm" name-prop="ids"
                                        name-field-autocomplete="users_sign_auto" name-field="users_sign"
                                        name-resource="/correspondence/get-only-users-sign"
                                        name-options-list="users_sign_text" :name-labels-display="['fullname']" name-key="id"
                                        help="Por favor, introduzca el nombre de uno o varios funcionarios que firmarán el documento y presione la tecla Enter o Tab. Si usted también va a firmar este documento, ingrese su nombre y agréguelo. Ejemplo: Camilo..."

                                        >
                                    </add-list-autocomplete>
                                </div>
                            </div>

                            <!-- observation Field -->
                            <div class="form-group row m-b-15 contenedorFormObservacion">
                                {!! Form::label('observation', 'Escriba un comentario para el funcionario sobre lo que deberá hacer con el documento:', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', ':required' => "dataForm.tipo == 'Elaboración' || dataForm.tipo == 'Revisión' || dataForm.tipo == 'Aprobación'  || dataForm.tipo == 'Firma Conjunta' ? true : false", 'rows' => '3']) !!}
                                    <small>Ingrese en este campo una corta descripción de lo que debe hacer el funcionario que recibirá este documento
                                        (Elaboración, Revisión y Aprobación), esta descripción no alterará el contenido del documento y será enviado al correo electrónico como una notificación.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.observation">
                                        <p class="m-b-0" v-for="error in dataErrors.observation">
                                            @{{ error }}</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!-- end panel-body -->
        </div>

        {{-- <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo == 'Firma Conjunta'">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Firmantes</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <!--  Firmantes-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios que firma el documento:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="id"
                                    name-field-autocomplete="users_sign_auto" name-field="users_sign"
                                    name-resource="/correspondence/get-only-users"
                                    name-options-list="users_sign_text" :name-labels-display="['name']" name-key="users_id"
                                    help="Ingrese el nombre de uno o varios funcionarios que firmarán el documento y presione la tecla enter o tab. Ejemplo: Camilo..."
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- end panel-body -->
        </div> --}}


        <div class="panel" data-sortable-id="ui-general-1">
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos de la correspondencia</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <!-- Folios Field -->
                {{-- <div class="form-group row m-b-15">
                    {!! Form::label('folios', trans('Folios') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-4">
                        {!! Form::text('folios', '0', [':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }", 'v-model' => 'dataForm.folios', 'required' => true]) !!}
                        <small>Ingrese la cantidad de folios.</small>
                        <div class="invalid-feedback" v-if="dataErrors.folios">
                            <p class="m-b-0" v-for="error in dataErrors.folios">
                                @{{ error }}
                            </p>
                        </div>
                    </div>

                    {!! Form::label('annexes', 'Anexos:', ['class' => 'col-form-label col-md-1 required']) !!}
                    <div class="col-md-4">
                        {!! Form::text('annexes', '0', [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes }", 'v-model' => 'dataForm.annexes', 'required' => true]) !!}
                        <small>Ingrese la cantidad de anexos.</small>
                        <div class="invalid-feedback" v-if="dataErrors.annexes">
                            <p class="m-b-0" v-for="error in dataErrors.annexes">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div> --}}

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
                            :fields-change-values="['external_received_consecutive:consecutive','external_received_id']">
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
                    {!! Form::label('pqr_consecutive', 'Respuesta a PQRS:', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <autocomplete 
                            name-prop="pqr_id"
                            aditional-name-prop="contenido"
                            name-field="pqr_id" 
                            :value='dataForm'
                            name-resource="/pqrs/get-p-q-r-s"
                            css-class="form-control"
                            :is-required="true"
                            :name-labels-display="['pqr_id','contenido']" 
                            reduce-key="id" 
                            min-text-input=4
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

                <!-- channel Field -->
                <div class="form-group row m-b-15">

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
							<option value="7">Buzón de sugerencias</option>
                            <option value="1">Correo certificado</option>
                            <option value="2">Correo electrónico</option>
                            <option value="3">Fax</option>
                            <option value="4">Personal</option>
                            <option value="5">Telefónico</option>
                            <option value="6">Web</option>
                            <option value="7">Notificación por aviso</option>
                        </select>
                        <small>@lang('Seleccione') @{{ `@lang('Canal')` | lowercase }}.</small>
                        <div class="invalid-feedback" v-if="dataErrors.channel">
                            <p class="m-b-0" v-for="error in dataErrors.channel">
                                @{{ error }}
                            </p>
                        </div>
                    </div>

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
                            { label: 'Teléfono', name: 'phone', isShow: true },
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
                                        {!! Form::label('trato', 'Trato:', ['class' => 'col-form-label col-md-3 required']) !!}
                                        <div class="col-md-4">
                                            {!! Form::text('trato', 'Ciudadano', [':class' => "{'form-control':true, 'is-invalid':dataErrors.trato }", 'v-model' => 'scope.dataForm.trato', 'required' => true]) !!}
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

                                            <label for="city_id" class="col-form-label col-md-1 required" v-if="scope.dataForm.department_id" >Ciudad:</label>
                                            <div class="col-md-4" style="float: right;" v-if="scope.dataForm.department_id">
                                                <select-check
                                                    css-class="form-control"
                                                    name-field="city_id"
                                                    reduce-label="name"
                                                    reduce-key="id"
                                                    :name-resource="'/get-cities-by-state/'+scope.dataForm.department_id"
                                                    :value="scope.dataForm"
                                                    :key="scope.dataForm.department_id"
                                                    :is-required="true"
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
                                        {!! Form::label('citizen_document', trans('Documento del ciudadano/empresa').':', ['class' => 'col-form-label col-md-3']) !!}
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row m-b-15">
                                            <label for="phone" class="col-form-label col-md-3">Teléfono (Opcional):</label>
                                            <div class="col-md-4" style="float: right;">
                                                {!! Form::text('phone', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.entidad }", 'v-model' => 'scope.dataForm.phone', 'required' => false]) !!}
                                                <small>Ingrese el teléfono del ciudadano.</small>
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
                                    name-field-autocomplete="copies_autocomplete" name-field="copies_users"
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
                <h4 class="panel-title"><strong>Adjuntar Archivos</strong></h4>
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

                    <!-- Annexes Description Field -->
                    <div v-if="dataForm.annexes_digital?.length > 0" class="col-md-12">
                        <div class="form-group row m-b-15">
                            {!! Form::label('annexes_description', 'Descripción de anexos:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('annexes_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.annexes_description }", 'v-model' => 'dataForm.annexes_description', 'required' => true, 'rows' => '3']) !!}
                                <small>Ingrese una descripción de los anexos</small>
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
</div>
