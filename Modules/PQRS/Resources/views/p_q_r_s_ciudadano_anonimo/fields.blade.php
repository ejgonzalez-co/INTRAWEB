<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Detalles del PQR</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <input type="hidden" name="estado" v-model="dataForm.estado = 'Abierto'">
        <input type="hidden" name="canal" v-model="dataForm.canal = 'Web'">
        <!-- Contenido Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('contenido', trans('Contenido').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('contenido', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contenido }", 'v-model' => 'dataForm.contenido', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('Contenido')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.contenido">
                    <p class="m-b-0" v-for="error in dataErrors.contenido">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Pqr Tipo Solicitud Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('pqr_tipo_solicitud_id', trans('Tipo de PQR').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select-check
                    css-class="form-control"
                    name-field="pqr_tipo_solicitud_id"
                    reduce-label="nombre"
                    reduce-key="id"
                    name-resource="get-p-q-r-anonimo-tipo-solicituds-radicacion"
                    :value="dataForm"
                    :is-required="true">
                </select-check>
                <small>@lang('Select the') el tipo de PQR</small>
                <div class="invalid-feedback" v-if="dataErrors.pqr_tipo_solicitud_id">
                    <p class="m-b-0" v-for="error in dataErrors.pqr_tipo_solicitud_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Email Ciudadano Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('email_ciudadano', trans('Correo eletrónico').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                {!! Form::email('email_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email_ciudadano }", 'v-model' => 'dataForm.email_ciudadano', 'required' => false]) !!}
                <small>@lang('Enter the') @{{ `@lang('Email Ciudadano')` | lowercase }}</small>

                <div class="invalid-feedback" v-if="dataErrors.email_ciudadano">
                    <p class="m-b-0" v-for="error in dataErrors.email_ciudadano">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            {!! Form::label('respuesta_correo', 'Autorizo recibir la respuesta por correo electrónico:', ['class' => 'col-form-label col-md-3']) !!}
            <!-- switcher -->
            <div class=" switcher col-md-9 m-t-5">
                <input type="checkbox" name="respuesta_correo" id="respuesta_correo" v-model="dataForm.respuesta_correo">
                <label for="respuesta_correo"></label>
                <div class="alert alert-info rounded-pill text-center" style="margin-top: 5px" role="alert">
                    <i class="fas fa-info-circle mr-2"></i> Autorizo a las Empresas Públicas a enviarme notificaciones sobre las respuestas a través de correo electrónico.
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="!isUpdate">
            <!-- Número matrícula Field -->
            {!! Form::label('no_matricula', trans('Número de matrícula').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-3">
                {!! Form::number('no_matricula', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_matricula }", 'v-model' => 'dataForm.no_matricula', 'required' => true, 'id' => 'folios']) !!}
                <small>@lang('Enter the') el número de matrícula</small>
                <div class="invalid-feedback" v-if="dataErrors.no_matricula">
                    <p class="m-b-0" v-for="error in dataErrors.no_matricula">@{{ error }}</p>
                </div>
            </div>
            <!-- Direccion predio Field -->
            {!! Form::label('direccion_predio', trans('Dirección del predio').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('direccion_predio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.direccion_predio }", 'v-model' => 'dataForm.direccion_predio', 'required' => true]) !!}
                <small>@lang('Enter the') la dirección del predio</small>
                <div class="invalid-feedback" v-if="dataErrors.direccion_predio">
                    <p class="m-b-0" v-for="error in dataErrors.direccion_predio">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Inicio img número de matrícula-->
        <div class="col-form-label pt-0" style="cursor: pointer;" @click="$refs.componentePQR.prueba()" id="showImage">
            <i class="fa fa-info-circle fa-lg"></i> Haga clic aquí y conozca donde encontrar el número de matrícula.
        </div>

        <div class="mb-2">
            <img src="{{ asset('assets/img/recibo.png') }}" alt="Número de matrícula" style="display: none; width: 50%; border: 1px solid #80808042;" id="infoImage">
        </div>
        <!-- Fin img número de matrícula-->

        <!-- Motivos o hechos Field -->
        <div class="form-group row m-b-15" v-if="!isUpdate">
            {!! Form::label('motivos_hechos', trans('Motivos o hechos').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('motivos_hechos', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.motivos_hechos }", 'v-model' => 'dataForm.motivos_hechos', 'required' => true]) !!}
                <small>@lang('Enter the') los motivos o hechos</small>
                <div class="invalid-feedback" v-if="dataErrors.motivos_hechos">
                    <p class="m-b-0" v-for="error in dataErrors.motivos_hechos">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- respuesta_correo Field -->
        {{-- <div class="form-group row m-b-15">
            {!! Form::label('respuesta_correo', trans('¿Autoriza recibir respuesta a tráves de notificaciones electrónicas?').':', ['class' => 'col-form-label col-md-3']) !!}
            <!-- switcher -->
            <div class="switcher col-md-9 m-t-5">
                <input type="checkbox" name="respuesta_correo" id="respuesta_correo" v-model="dataForm.respuesta_correo">
                <label for="respuesta_correo"></label>
                <small>Active esta opción si desea recibir notificaciones vía correo electrónico.</small>
            </div>
        </div> --}}

        <!-- Documento principal Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('url', 'Documento principal del PQRS:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="1"
                    :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Utilice este campo para almacenar una copia electrónica de una carta u otro documento que se haya recibido."
                    :is-update="isUpdate" :key="keyRefresh" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                </input-file>
            </div>
        </div>

        <!-- Adjunto ciudadano Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('adjunto_ciudadano', trans('Lista de archivos anexos').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="adjunto_ciudadano" :max-files="30"
                    :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Lista de archivos anexos. El tamaño máximo permitido es de 10 MB."
                    :is-update="isUpdate" :key="keyRefresh" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                </input-file>
                <div class="invalid-feedback" v-if="dataErrors.adjunto_ciudadano">
                    <p class="m-b-0" v-for="error in dataErrors.adjunto_ciudadano">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('captcha', trans('Captcha').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-6">
                <vue-recaptcha sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" @verify="callFunctionComponent('componentePQR','onCaptchaVerified', $event)"></vue-recaptcha>

                <div class="text-danger" style="margin-top: .25rem; font-size: .6875rem;" v-if="dataErrors.g_recaptcha_response">
                    <p class="m-b-0" v-for="error in dataErrors.g_recaptcha_response">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
