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
                                <small>@lang('Enter the') @{{ `@lang('Title')` | lowercase }}</small>
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

                            <div class="col-md-12 mt-1">
                                <button class="btn" style="background: #2684FC; color: white" type="button" @click="_generarContenidoChatgpt(dataForm.content)">
                                    <svg style="color: white" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 671.194 680.2487">
                                        <path d="M626.9464,278.4037a169.4492,169.4492,0,0,0-14.5642-139.187A171.3828,171.3828,0,0,0,427.7883,56.9841,169.45,169.45,0,0,0,299.9746.0034,171.3985,171.3985,0,0,0,136.4751,118.6719,169.5077,169.5077,0,0,0,23.1574,200.8775,171.41,171.41,0,0,0,44.2385,401.845,169.4564,169.4564,0,0,0,58.8021,541.0325a171.4,171.4,0,0,0,184.5945,82.2318A169.4474,169.4474,0,0,0,371.21,680.2454,171.4,171.4,0,0,0,534.7642,561.51a169.504,169.504,0,0,0,113.3175-82.2063,171.4116,171.4116,0,0,0-21.1353-200.9ZM371.2647,635.7758a127.1077,127.1077,0,0,1-81.6027-29.5024c1.0323-.5629,2.8435-1.556,4.0237-2.2788L429.13,525.7575a22.0226,22.0226,0,0,0,11.1306-19.27V315.5368l57.25,33.0567a2.0332,2.0332,0,0,1,1.1122,1.568V508.2972A127.64,127.64,0,0,1,371.2647,635.7758ZM97.3705,518.7985a127.0536,127.0536,0,0,1-15.2074-85.4256c1.0057.6037,2.7624,1.6768,4.0231,2.4012L221.63,514.01a22.04,22.04,0,0,0,22.2492,0L409.243,418.5281v66.1134a2.0529,2.0529,0,0,1-.818,1.7568l-136.92,79.0534a127.6145,127.6145,0,0,1-174.134-46.6532ZM61.7391,223.1114a127.0146,127.0146,0,0,1,66.3545-55.8944c0,1.1667-.067,3.2329-.067,4.6665V328.3561a22.0038,22.0038,0,0,0,11.1173,19.2578l165.3629,95.4695-57.2481,33.055a2.0549,2.0549,0,0,1-1.9319.1752l-136.933-79.1215A127.6139,127.6139,0,0,1,61.7391,223.1114ZM532.0959,332.5668,366.7308,237.0854l57.25-33.0431a2.0455,2.0455,0,0,1,1.93-.1735l136.934,79.0535a127.5047,127.5047,0,0,1-19.7,230.055V351.8247a21.9961,21.9961,0,0,0-11.0489-19.2579Zm56.9793-85.7589c-1.0051-.6174-2.7618-1.6769-4.0219-2.4L449.6072,166.1712a22.07,22.07,0,0,0-22.2475,0L261.9963,261.6543V195.5409a2.0529,2.0529,0,0,1,.818-1.7567l136.9205-78.988a127.4923,127.4923,0,0,1,189.34,132.0117ZM230.8716,364.6456,173.6082,331.589a2.0321,2.0321,0,0,1-1.1122-1.57V171.8835A127.4926,127.4926,0,0,1,381.5636,73.9884c-1.0322.5633-2.83,1.5558-4.0236,2.28L242.0957,154.5044a22.0025,22.0025,0,0,0-11.1306,19.2566Zm31.0975-67.0521L335.62,255.0559l73.6488,42.51v85.0481L335.62,425.1266l-73.6506-42.5122Z" fill="white"></path>
                                    </svg>
                                    Agregar contenido con inteligencia artificial
                                </button>
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
                            {!! Form::label('tipo', 'Elija qué desea hacer con este documento:', ['class' => 'col-form-label col-md-3 required']) !!}
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

                                    {{-- <autocomplete 
                                        name-prop="name" 
                                        name-field="funcionario_revision" 
                                        :value='dataForm'
                                        name-resource="/intranet/get-users" 
                                        css-class="form-control"
                                        :is-required="dataForm.tipo == 'Elaboración' || dataForm.tipo == 'Revisión' || dataForm.tipo == 'Aprobación' ? true : false"
                                        :name-labels-display="['name', 'email']" 
                                        :fields-change-values="['user_for_last_update:name']"
                                        reduce-key="id" 
                                        
                                        name-field-edit="funcionario_revision">
                                    </autocomplete> --}}

                                    <autocomplete 
                                        name-prop="fullname" 
                                        name-field="funcionario_revision" 
                                        :value='dataForm'
                                        name-resource="/correspondence/get-only-users" 
                                        css-class="form-control"
                                        :is-required="dataForm.tipo == 'Elaboración' || dataForm.tipo == 'Revisión' || dataForm.tipo == 'Aprobación' ? true : false"
                                        :name-labels-display="['fullname']" 
                                        :fields-change-values="['user_for_last_update:fullname']"
                                        reduce-key="id" 
                                        name-field-edit="funcionario_revision"
                                        :activar-blur="true"
                                        >
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
                                        help="Ingrese el nombre de uno o varios funcionarios que firmarán el documento y presione la tecla enter o tab. Ejemplo: Camilo..."
                                        
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

        {{-- <div class="panel contenedorFormFirma" data-sortable-id="ui-general-1" style="display: none;">
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

        @include('correspondence::internals.fields_recipients')
        
        <div class="panel" data-sortable-id="ui-general-1" v-if="false">
            <!-- begin panel-heading -->
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title"><strong>Datos de destino</strong></h4>
            </div>
            <!-- end panel-heading -->
            <!-- begin panel-body -->
            <div class="panel-body">
                <div class="row">

                    <!-- Require internal_all Field -->
                    <div class="col-md-12">
                        <div class="form-group row m-b-15">
                            {!! Form::label('internal_all', '¿Correspondencia para todos los funcionarios de la entidad?: ', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">

                                <select class="form-control" id="internal_all" v-model="dataForm.internal_all" required>
                                    <option value="0">No</option>
                                    <option value="1">Si</option>
                                </select>
                                <Small>Seleccione "Sí" o "No" para indicar si esta correspondencia esta dirigida a toda la entidad.</Small>

                                <div class="invalid-feedback" v-if="dataErrors.internal_all">
                                    <p class="m-b-0" v-for="error in dataErrors.internal_all">
                                        @{{ error }}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" v-if="dataForm.internal_all!='1'">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios destinatarios:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9" v-if="dataForm.tipo == 'Elaboración' || dataForm.tipo == 'Revisión' || dataForm.tipo == 'Aprobación' || dataForm.tipo === 'Firma Conjunta'">
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="recipient_autocomplete" name-field="recipients_users"
                                    name-resource="/correspondence/get-recipients-internal"
                                    name-options-list="internal_recipients" :name-labels-display="['name']" name-key="id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    >
                                </add-list-autocomplete>
                            </div>
                            <div class="col-md-9" v-else>
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="recipient_autocomplete" name-field="recipients_users"
                                    name-resource="/correspondence/get-recipients-internal"
                                    name-options-list="internal_recipients" :name-labels-display="['name']" name-key="id"
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>

                  

                    <div class="col-md-12 mt-5">
                        <!--  Other officials Field destination-->
                        <div class="form-group row m-b-15">
                            {!! Form::label('users', 'Funcionarios de copia:', ['class' => 'col-form-label col-md-3']) !!}
                            <div class="col-md-9">
                                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                                    name-field-autocomplete="copy_autocomplete" name-field="copies_users"
                                    name-resource="/correspondence/get-only-users"
                                    name-options-list="internal_copy" :name-labels-display="['fullname']" name-key="users_id" 
                                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                    >
                                </add-list-autocomplete>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!-- end panel-body -->
        </div>

        @include('correspondence::internals.fields_respuestas_internas')

    </div>

    <div class="panel" data-sortable-id="ui-general-1" id="clasificacion" v-if="!radicatied">
        @include('correspondence::internals.field_clasificacion_documental')
    </div>

    
    @if (Auth::user()->autorizado_firmar == 1)
    {{-- <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo=='Publicación'">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Firma</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        @include('correspondence::internals.fields_firmas')
        
    </div> --}}

    <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.tipo=='Publicación' && !radicatied">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Firma</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        @include('correspondence::internals.fields_firmas')
        
    </div>

    
        
    @endif

    {{-- v-if="isUpdate" --}}
    {{-- <div class="tab-pane" id="rotule">
        @include('correspondence::internals.rotule')
    </div> --}}
</div>
