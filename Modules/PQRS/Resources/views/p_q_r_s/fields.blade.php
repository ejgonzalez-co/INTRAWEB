<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.canal != 'Web'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Remitente</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Ciudadano Users Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('ciudadano_users_id', trans('Nombre del ciudadano').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete
                    :is-update="isUpdate"
                    {{-- :value-default="dataForm.ciudadano_users_id" --}}
                    name-prop="name"
                    name-field="ciudadano_users_id"
                    :value='dataForm'
                    name-resource="/intranet/get-citizens"
                    css-class="form-control"
                    :name-labels-display="['document_number', 'name']"
                    :fields-change-values="['documento_ciudadano:document_number', 'email_ciudadano:email', 'nombre_ciudadano:name']"
                    reduce-key="user_id"
                    :is-required="false"
                    name-field-edit="nombre_ciudadano"
                    :element-disabled="isUpdate && dataForm.users_id != {{ Auth::user()->id }}">
                </autocomplete>
                <small>Ingrese el nombre del ciudadano y luego seleccionelo. Si el ciudadano no existe utilice la opción "Nuevo Ciudadano"</small>
                <div class="invalid-feedback" v-if="dataErrors.ciudadano_users_id">
                    <p class="m-b-0" v-for="error in dataErrors.ciudadano_users_id">@{{ error }}</p>
                </div>
            </div>

        </div>

        <div class="form-group row m-b-15">
            <!-- Documento Ciudadano Field -->
            {!! Form::label('documento_ciudadano', trans('Documento del ciudadano').':', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-4">
                {!! Form::text('documento_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.documento_ciudadano }", 'v-model' => 'dataForm.documento_ciudadano', 'required' => false, ':disabled' => 'isUpdate && dataForm.users_id != '.Auth::user()->id]) !!}
                <small>@lang('Enter the') el número de identificación del ciudadano</small>
                <div class="invalid-feedback" v-if="dataErrors.documento_ciudadano">
                    <p class="m-b-0" v-for="error in dataErrors.documento_ciudadano">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('email_ciudadano', trans('Correo del ciudadano').':', ['class' => 'col-form-label col-md-1']) !!}
            <div class="col-md-4">
                {!! Form::email('email_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email_ciudadano }", 'v-model' => 'dataForm.email_ciudadano', 'required' => false, ':disabled' => 'isUpdate && dataForm.users_id != '.Auth::user()->id]) !!}
                <small>@lang('Enter the') @{{ `@lang('Email Ciudadano')` | lowercase }}.</small>
                <div class="invalid-feedback" v-if="dataErrors.email_ciudadano">
                    <p class="m-b-0" v-for="error in dataErrors.email_ciudadano">@{{ error }}</p>
                </div>
            </div>
        </div>
        @if(Auth::user()->hasRole('Administrador de requerimientos'))
            <div class="row">
                <button class="btn ml-3 bg-success-lighter" type="button" onclick="jQuery('#crear_ciudadano').toggle(350);"><i class="fa fa-user mr-2" aria-hidden="true"></i>Nuevo ciudadano</button>
            </div>
        @endif
        <div id="crear_ciudadano" style="background-color: #40b0a612!important; padding-left: 1px; display: none;">
            <hr />
            <h4>Formulario para crear un ciudadano</h4>
            @include('intranet::citizens.fields_form')
            <div class="modal-footer">
                <button type="button" class="btn bg-success-lighter" @click="callFunctionComponent('componentePQR','addCiudadano');"><i class="fa fa-save mr-2"></i>@lang('crud.save')</button>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Detalles del PQR</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Contenido Field -->
        <div class="form-group row m-b-15" v-if="!isUpdate">
            {!! Form::label('contenido', trans('Contenido').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::textarea('contenido', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contenido }", 'v-model' => 'dataForm.contenido', 'required' => true, ':disabled' => 'isUpdate && dataForm.users_id != '.Auth::user()->id]) !!}
                <small>@lang('Enter the') @{{ `@lang('Contenido')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.contenido">
                    <p class="m-b-0" v-for="error in dataErrors.contenido">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="!isUpdate">
            {!! Form::label('respuesta_correo', 'Autorizo recibir la respuesta por correo electrónico:', ['class' => 'col-form-label col-md-3']) !!}
            <!-- switcher -->
            <div class=" switcher col-md-9 m-t-5">
                <input type="checkbox" name="respuesta_correo" id="respuesta_correo" v-model="dataForm.respuesta_correo">
                <label for="respuesta_correo"></label>
                <small>El campo "Autorizo recibir la respuesta por correo electrónico" se utiliza únicamente con fines informativo.</small>
            </div>
        </div>

        <!-- Estado Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('estado', trans('Estado').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <select class="form-control" v-model="dataForm.estado" name="estado" @change="delete dataForm.tipo_finalizacion; delete dataForm.tipo_adjunto;">
                    @if(Auth::user()->hasRole('Administrador de requerimientos'))
                    <option value="Abierto">Abierto</option>
                    @endif
                    <option value="Asignado">Asignado</option>
                    <option value="En trámite">En trámite</option>
                    <option value="Esperando respuesta del ciudadano">Esperando respuesta del ciudadano</option>

                    @if(config('app.pqrs_finalizar_todos'))
                        <option value="Finalizado">Finalizado</option>
                    @elseif(Auth::user()->hasRole('Administrador de requerimientos'))
                        <option value="Finalizado">Finalizado</option>
                    @endif
                    <option value="Finalizado vencido justificado" v-if="(dataForm.estado == 'Finalizado' && dataForm.linea_tiempo == 'Vencido') || dataForm.estado == 'Finalizado vencido justificado'">Finalizado vencido justificado</option>
                    <option value="Respuesta parcial" v-if="(dataForm.estado != 'Abierto' && dataForm.estado != 'Finalizado' && dataForm.estado != 'Cancelado' && dataForm.linea_tiempo != 'Vencido' && !dataForm.fecha_fin_parcial)">Respuesta parcial</option>
                    <option value="Devuelto">Devuelto</option>
                    @if(Auth::user()->hasRole('Administrador de requerimientos'))
                    <option value="Cancelado">Cancelado</option>
                    @endif
                </select>
                <small>@lang('Select the') el estado que tomará el PQR</small>
                <div class="invalid-feedback" v-if="dataErrors.estado">
                    <p class="m-b-0" v-for="error in dataErrors.estado">@{{ error }}</p>
                </div>
            </div>
        </div>



        <div style="margin-bottom: 10px" :class="{'estado_vencido_select': dataForm.estado == 'Cancelado' || dataForm.estado == 'Devuelto',
            'estado_entramite_select': dataForm.estado == 'En trámite',
            'estado_esperandor_select': dataForm.estado == 'Esperando respuesta del ciudadano',
            'estado_finalizado_select': dataForm.estado == 'Finalizado',
            'estado_finalizado_justifi_select': dataForm.estado == 'Finalizado vencido justificado'
            }">

            <!-- Funcionario Destinatario Field -->
            @if(Auth::user()->hasRole('Administrador de requerimientos'))
            <div class="form-group row m-b-15" v-if="dataForm.estado != 'Abierto'">
                {!! Form::label('funcionario_users_id', trans('Funcionario Destinatario').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">

                    <select-check id="szs" css-class="form-control"  name-field="funcionario_users_id" reduce-label="fullname" name-resource="/correspondence/get-only-users" ref-select-check="funcionario_users_id" :value="dataForm" :is-required="true" :enable-search="true"></select-check>

                    {{-- <autocomplete
                        name-prop="name"
                        name-field="funcionario_users_id"
                        :value='dataForm'
                        name-resource="/intranet/get-users"
                        css-class="form-control"
                        :is-required="true"
                        :name-labels-display="['name', 'email']"
                        reduce-key="id"

                        name-field-edit="funcionario_destinatario">
                    </autocomplete> --}}
                    <small>@lang('Enter the') el funcionario destinatario</small>
                    <div class="invalid-feedback" v-if="dataErrors.funcionario_users_id">
                        <p class="m-b-0" v-for="error in dataErrors.funcionario_users_id">@{{ error }}</p>
                    </div>
                </div>
            </div>
            @endif
            <!-- Funcionario Destinatario Field -->
            @if(Auth::user()->hasRole('Operadores'))
            <div class="form-group row m-b-15" v-if="dataForm.estado != 'Devuelto'">
                {!! Form::label('funcionario_users_id', trans('Funcionario Destinatario').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">

                    <select-check css-class="form-control"  name-field="funcionario_users_id" reduce-label="fullname" name-resource="get-only-users-pqrs" :value="dataForm" :is-required="true" :enable-search="true" :key="dataForm.estado"></select-check>
                    <small>@lang('Enter the') el funcionario destinatario</small>
                    <div class="invalid-feedback" v-if="dataErrors.funcionario_users_id">
                        <p class="m-b-0" v-for="error in dataErrors.funcionario_users_id">@{{ error }}</p>
                    </div>
                </div>
            </div>
            <div class="form-group row m-b-15" v-else>
                {!! Form::label('funcionario_users_id', 'Funcionarios administradores'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">

                    <select-check css-class="form-control"  name-field="funcionario_users_id" reduce-label="fullname" name-resource="get-admin-users-pqrs" :value="dataForm" :is-required="true" :enable-search="true" :key="dataForm.estado"></select-check>
                    <small>@lang('Enter the') el funcionario administrador</small>
                    <div class="invalid-feedback" v-if="dataErrors.funcionario_users_id">
                        <p class="m-b-0" v-for="error in dataErrors.funcionario_users_id">@{{ error }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Respuesta Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Cancelado'">
                {!! Form::label('respuesta', trans('Razón de la cancelación').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('respuesta', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.respuesta }", 'v-model' => 'dataForm.respuesta', 'required' => true]) !!}
                    <small>@lang('Enter the') la razón de la cancelación del PQR</small>
                    <div class="invalid-feedback" v-if="dataErrors.respuesta">
                        <p class="m-b-0" v-for="error in dataErrors.respuesta">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Devolucion Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Devuelto'">
                {!! Form::label('devolucion', trans('Razón de la devolucion').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('devolucion', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.devolucion }", 'v-model' => 'dataForm.devolucion', 'required' => true, 'id' => 'devolucionTextarea']) !!}
                    <small>@lang('Enter the') la razón de la devolución del PQR</small>
                    <div class="invalid-feedback" v-if="dataErrors.devolucion">
                        <p class="m-b-0" v-for="error in dataErrors.devolucion">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Respuesta Parcial Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Respuesta parcial'">
                {!! Form::label('respuesta_parcial', trans('Respuesta Parcial').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('respuesta_parcial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.respuesta_parcial }", 'v-model' => 'dataForm.respuesta_parcial', 'required' => true]) !!}
                    <small>@lang('Enter the') las observaciones de la respuesta parcial</small>
                    <div class="invalid-feedback" v-if="dataErrors.respuesta_parcial">
                        <p class="m-b-0" v-for="error in dataErrors.respuesta_parcial">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Adjunto respuesta parcial Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Respuesta parcial'">
                {!! Form::label('adjunto_r_parcial', 'Adjunto respuesta parcial:', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <input-file :file-name-real="true":value="dataForm" name-field="adjunto_r_parcial" :max-files="1"
                        :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                        message="Arrastre o seleccione los archivos" help-text="Lista de archivo de respuesta parcial\. El tamaño máximo permitido es de 10 MB\."
                        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
                    </input-file>
                </div>
            </div>

            <!-- Pregunta Ciudadano Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Esperando respuesta del ciudadano'">
                {!! Form::label('pregunta_ciudadano', trans('Pregunta para el ciudadano').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('pregunta_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.pregunta_ciudadano }", 'v-model' => 'dataForm.pregunta_ciudadano', 'required' => true]) !!}
                    <small>@lang('Enter the') la pregunta para el ciudadano</small>
                    <div class="invalid-feedback" v-if="dataErrors.pregunta_ciudadano">
                        <p class="m-b-0" v-for="error in dataErrors.pregunta_ciudadano">@{{ error }}</p>
                    </div>
                </div>
            </div>

             <!-- Adjunto espera ciudadano  Field -->
             <div   v-if="dataForm.estado == 'Esperando respuesta del ciudadano'" class="form-group row m-b-15" >
                {!! Form::label('adjunto_espera_ciudadano',"Adjunto  de espera ciudadano:", ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <input-file :file-name-real="true":value="dataForm" name-field="adjunto_espera_ciudadano" :max-files="10"
                        :max-filesize="10" file-path="public/container/pqr_{{ date('Y') }}"
                        message="Arrastre o seleccione los archivos" help-text="Lista de archivos anexos. El tamaño máximo permitido es de 10 MB."
                        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
                    </input-file>
                    <div class="invalid-feedback" v-if="dataErrors.adjunto_espera_ciudadano">
                        <p class="m-b-0" v-for="error in dataErrors.adjunto_espera_ciudadano">@{{ error }}</p>
                    </div>
                </div>
            </div>


            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Esperando respuesta del ciudadano' && dataForm.respuesta_ciudadano != null">
                {!! Form::label('respuesta_ciudadano', trans('Respuesta del ciudadano').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('respuesta_ciudadano', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.respuesta_ciudadano }", 'v-model' => 'dataForm.respuesta_ciudadano', 'required' => true, 'disabled' => true]) !!}
                    <small>@lang('Enter the') la respuesta</small>
                    <div class="invalid-feedback" v-if="dataErrors.respuesta_ciudadano">
                        <p class="m-b-0" v-for="error in dataErrors.respuesta_ciudadano">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="(dataForm.estado == 'Finalizado' || dataForm.estado == 'Finalizado vencido justificado')">
                <!-- Tipo Finalizacion Field -->
                {!! Form::label('tipo_finalizacion', 'Tipo de finalización:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-3">
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
                <!-- Tipo Adjunto Field -->
                {!! Form::label('tipo_adjunto', trans('Tipo de adjunto').':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div  class="col-md-4">
                    {!! Form::select('tipo_adjunto',
                        ["Respuesta sin adjunto" => "Respuesta sin adjunto",
                        "Respuesta con adjunto" => "Respuesta con adjunto"
                        ], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_adjunto }", 'v-model' => 'dataForm.tipo_adjunto', 'required' => true]) !!}
                    <small>@lang('Select the') el tipo de adjunto</small>
                    <div class="invalid-feedback" v-if="dataErrors.tipo_adjunto">
                        <p class="m-b-0" v-for="error in dataErrors.tipo_adjunto">@{{ error }}</p>
                    </div>
                </div>
            </div>

             <!-- Nuve notificación -->
             <div v-if="dataForm.tipo_finalizacion == 'PQRS para trasladar a otra entidad'" style="text-align: justify;">
                <div role="alert" class="alert alert-success w-100">
                    Seleccione esta opción si el PQRS  no es competencia de nuestra entidad. Al hacer esto, se deberá realizar un traslado físico del PQRS a la entidad correspondiente. Asegúrese de imprimir los documentos y enviarlos a la entidad indicada siguiendo los procedimientos establecidos.
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="dataForm.tipo_finalizacion == 'PQRS para trasladar a otra entidad'">
                <!-- Empresa traslado Field -->
                {!! Form::label('empresa_traslado', trans('Empresa a trasladar').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('empresa_traslado', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.empresa_traslado }", 'v-model' => 'dataForm.empresa_traslado', 'required' => true]) !!}
                    <small>@lang('Enter the') la empresa a donde se va a trasladar el PQR</small>
                    <div class="invalid-feedback" v-if="dataErrors.empresa_traslado">
                        <p class="m-b-0" v-for="error in dataErrors.empresa_traslado">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Descripcion Tramite Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'En trámite'">
                {!! Form::label('descripcion_tramite', trans('Descripción del trámite').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('descripcion_tramite', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.descripcion_tramite }", 'v-model' => 'dataForm.descripcion_tramite', 'required' => true]) !!}
                    <small>@lang('Enter the') los movimientos del requerimiento </small>
                    <div class="invalid-feedback" v-if="dataErrors.descripcion_tramite">
                        <p class="m-b-0" v-for="error in dataErrors.descripcion_tramite">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Respuesta Field -->
            <div class="form-group row m-b-15" v-if="dataForm.estado == 'Finalizado' || dataForm.estado == 'Finalizado vencido justificado'">
                {!! Form::label('respuesta', trans('Respuesta').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('respuesta', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.respuesta }", 'v-model' => 'dataForm.respuesta', 'required' => true]) !!}
                    <small>@lang('Enter the') la @{{ `@lang('Respuesta')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.respuesta">
                        <p class="m-b-0" v-for="error in dataErrors.respuesta">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Folios Field -->
            <div class="form-group row m-b-15" v-if="!isUpdate">
                {!! Form::label('folios', trans('Folios').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-3">
                    {!! Form::number('folios', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.folios }", 'v-model' => 'dataForm.folios', 'required' => true, 'id' => 'folios']) !!}
                    <small>@lang('Enter the') el número de folios</small>
                    <div class="invalid-feedback" v-if="dataErrors.folios">
                        <p class="m-b-0" v-for="error in dataErrors.folios">@{{ error }}</p>
                    </div>
                </div>
                <!-- Anexos Field -->
                {!! Form::label('anexos', trans('Anexos').':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-4">
                    {!! Form::text('anexos', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.anexos }", 'v-model' => 'dataForm.anexos', 'required' => true]) !!}
                    <small>@lang('Enter the') la cantidad de anexos</small>
                    <div class="invalid-feedback" v-if="dataErrors.anexos">
                        <p class="m-b-0" v-for="error in dataErrors.anexos">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Pqr Tipo Solicitud Id Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('pqr_tipo_solicitud_id', trans('Tipo de PQR').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-3">
                    <select-check
                        css-class="form-control"
                        name-field="pqr_tipo_solicitud_id"
                        reduce-label="nombre"
                        reduce-key="id"
                        name-resource="get-p-q-r-tipo-solicituds-radicacion"
                        :value="dataForm"
                        :is-required="true">
                    </select-check>
                    <small>@lang('Select the') el tipo de PQR</small>
                    <div class="invalid-feedback" v-if="dataErrors.pqr_tipo_solicitud_id">
                        <p class="m-b-0" v-for="error in dataErrors.pqr_tipo_solicitud_id">@{{ error }}</p>
                    </div>
                </div>
                <!-- Canal Field -->
                {!! Form::label('canal', trans('Canal').':', ['class' => 'col-form-label col-md-2 required']) !!}
                <div class="col-md-4">
                    {!! Form::select('canal',
                        ["Buzon de sugerencia" => "Buzon de sugerencia",
                        "Correo certificado" => "Correo certificado",
                        "Correo electrónico" => "Correo electrónico",
                        "FAX" => "FAX",
                        "Personal" => "Personal",
                        "Telefónico" => "Telefónico",
                        "Verbal" => "Verbal",
                        "Web" => "Web"], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.canal }", 'v-model' => 'dataForm.canal', 'required' => true]) !!}

                    <small>@lang('Select the') el canal de recepción</small>
                    <div class="invalid-feedback" v-if="dataErrors.canal">
                        <p class="m-b-0" v-for="error in dataErrors.canal">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group row m-b-15" v-if="!isUpdate">
                <!-- Número matricula Field -->
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

            <!-- Motivos o hechos Field -->
            <div class="form-group row m-b-15" v-if="!isUpdate">
                {!! Form::label('motivos_hechos', trans('Motivos o hechos').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('motivos_hechos', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.motivos_hechos }", 'v-model' => 'dataForm.motivos_hechos', 'required' => true, ':disabled' => 'isUpdate && dataForm.users_id != '.Auth::user()->id]) !!}
                    <small>@lang('Enter the') los motivos o hechos</small>
                    <div class="invalid-feedback" v-if="dataErrors.motivos_hechos">
                        <p class="m-b-0" v-for="error in dataErrors.motivos_hechos">@{{ error }}</p>
                    </div>
                </div>
            </div>

            <!-- Adjunto Field Auth::user()->hasRole('Administrador de requerimientos')-->
            @if (Auth::user()->hasRole('Admin Modificar PQRS Finalizados') )
                <div v-if="dataForm.estado == 'Finalizado' && dataForm.tipo_adjunto == 'Respuesta con adjunto'" class="form-group row m-b-15">
                    {!! Form::label('adjunto_finalizado', 'Adjunto finalizado', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <input-file :file-name-real="true":value="dataForm" name-field="adjunto_finalizado" :max-files="1"
                            :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                            message="Arrastre o seleccione un archivo" help-text="Adjunto de modificado finalizado. El tamaño máximo permitido es de 10 MB."
                            :is-update="isUpdate" :required="true" :key="keyRefresh" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
                        </input-file>
                        <div class="invalid-feedback" v-if="dataErrors.adjunto_finalizado">
                            <p class="m-b-0" v-for="error in dataErrors.adjunto_finalizado">@{{ error }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="form-group row m-b-15" v-if="dataForm.tipo_adjunto == 'Respuesta con adjunto' && dataForm.estado_actual != 'Finalizado' ">
                    {!! Form::label('adjunto', trans('Adjunto').':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <input-file :file-name-real="true":value="dataForm" name-field="adjunto" :max-files="11"
                            :max-filesize="30" file-path="public/container/pqr_{{ date('Y') }}"
                            message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos."
                            :is-update="isUpdate" :required="true" ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                        </input-file>
                        <div class="invalid-feedback" v-if="dataErrors.adjunto">
                            <p class="m-b-0" v-for="error in dataErrors.adjunto">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </div>
</div>

@if(Auth::user()->hasRole('Administrador de requerimientos'))
<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.estado != 'Abierto' && dataForm.estado != 'Esperando respuesta del ciudadano' && dataForm.estado != 'Cancelado' && dataForm.estado">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Tiempos de respuesta</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- Pqr Eje Tematico Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('pqr_eje_tematico_id', trans('Eje temático').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete
                    name-prop="nombre"
                    name-field="pqr_eje_tematico_id"
                    :value='dataForm'
                    :is-required="true"
                    name-resource="get-p-q-r-eje-tematicos-radicacion"
                    css-class="form-control"
                    :name-labels-display="['nombre', 'plazo', 'plazo_unidad']"
                    :fields-change-values="['nombre_ejetematico:nombre', 'tipo_plazo:tipo_plazo', 'plazo:plazo', 'temprana:temprana']"
                    reduce-key="id"

                    name-field-edit="nombre_ejetematico">
                </autocomplete>
                <small>Ingrese el nombre del eje temático y luego seleccionelo. Si no existe complete los demás campos del eje temático</small>
                <div class="invalid-feedback" v-if="dataErrors.pqr_eje_tematico_id">
                    <p class="m-b-0" v-for="error in dataErrors.pqr_eje_tematico_id">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Tipo Plazo Field -->
            {!! Form::label('tipo_plazo', trans('Tipo de plazo').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-3">
                {!! Form::select('tipo_plazo', ["Laboral" => "Laboral", "Calendario" => "Calendario"], 'Laboral', [':class' => "{'form-control':true, 'is-invalid':dataErrors.tipo_plazo }", 'v-model' => 'dataForm.tipo_plazo', 'required' => true]) !!}
                <small>@lang('Select the') el tipo de plazo</small>
                <div class="invalid-feedback" v-if="dataErrors.tipo_plazo">
                    <p class="m-b-0" v-for="error in dataErrors.tipo_plazo">@{{ error }}</p>
                </div>
            </div>
            <!-- Plazo Field -->
            {!! Form::label('plazo', trans('Plazo').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('plazo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.plazo }", 'v-model' => 'dataForm.plazo', 'required' => true]) !!}
                <small>@lang('Enter the') el plazo</small>
                <div class="invalid-feedback" v-if="dataErrors.plazo">
                    <p class="m-b-0" v-for="error in dataErrors.plazo">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            <!-- Temprana Field -->
            {!! Form::label('temprana', trans('Alerta temprana').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::number('temprana', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.temprana }", 'v-model' => 'dataForm.temprana', 'required' => true]) !!}
                <small>@lang('Enter the') el tiempo en dias que deben transcurrir para que empiecen a generarse alertas antes que se venza el plazo, el tiempo de alerta temprana debe ser menor o igual al plazo</small>
                <div class="invalid-feedback" v-if="dataErrors.temprana">
                    <p class="m-b-0" v-for="error in dataErrors.temprana">@{{ error }}</p>
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
                    name-resource="/pqrs/get-p-q-r-tipo-solicituds-radicacion"
                    :value="dataForm"
                    :is-required="true">
                </select-check>
                <small>@lang('Select the') el tipo de PQR</small>
                <div class="invalid-feedback" v-if="dataErrors.pqr_tipo_solicitud_id">
                    <p class="m-b-0" v-for="error in dataErrors.pqr_tipo_solicitud_id">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.estado != 'Abierto' && dataForm.estado != 'Esperando respuesta del ciudadano' && dataForm.estado != 'Cancelado' && dataForm.estado">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Destinatario y copias</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!--  Other officials Field destination-->
        <div class="form-group row m-b-15">
            {!! Form::label('users', 'Funcionarios de copia:', ['class' => 'col-form-label col-md-3']) !!}
            <div class="col-md-9">
                <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
                    name-field-autocomplete="recipient_autocomplete" name-field="copies_users"
                    name-resource="/correspondence/get-only-users"
                    name-options-list="pqr_copia" :name-labels-display="['fullname']" name-key="users_id"
                    help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                    >
                </add-list-autocomplete>
            </div>
        </div>
    </div>
</div>
@endif

<div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.estado == 'Finalizado vencido justificado'">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
       <h4 class="panel-title"><strong>Finalizar PQRS vencido con justificación</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <!-- No Oficio Respuesta Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('no_oficio_respuesta', trans('No. De oficio de respuesta').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('no_oficio_respuesta', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_oficio_respuesta }", 'v-model' => 'dataForm.no_oficio_respuesta', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('No Oficio Respuesta')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.no_oficio_respuesta">
                    <p class="m-b-0" v-for="error in dataErrors.no_oficio_respuesta">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Adj Oficio Respuesta Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('adj_oficio_respuesta', trans('Adjunto oficio respuesta').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="adj_oficio_respuesta" :max-files="30"
                    :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos."
                    :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                </input-file>
                <div class="invalid-feedback" v-if="dataErrors.adj_oficio_respuesta">
                    <p class="m-b-0" v-for="error in dataErrors.adj_oficio_respuesta">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- No Oficio Solicitud Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('no_oficio_solicitud', trans('No. De oficio de solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::text('no_oficio_solicitud', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.no_oficio_solicitud }", 'v-model' => 'dataForm.no_oficio_solicitud', 'required' => true]) !!}
                <small>@lang('Enter the') @{{ `@lang('No Oficio Solicitud')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.no_oficio_solicitud">
                    <p class="m-b-0" v-for="error in dataErrors.no_oficio_solicitud">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Adj Oficio Solicitud Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('adj_oficio_solicitud', trans('Adjunto oficio solicitud').':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <input-file :file-name-real="true":value="dataForm" name-field="adj_oficio_solicitud" :max-files="30"
                    :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                    message="Arrastre o seleccione los archivos" help-text="Lista de archivos adjuntos."
                    :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id">
                </input-file>
                <div class="invalid-feedback" v-if="dataErrors.adj_oficio_solicitud">
                    <p class="m-b-0" v-for="error in dataErrors.adj_oficio_solicitud">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->hasRole('Administrador de requerimientos'))
    <div v-if="!isUpdate"  class="panel" data-sortable-id="ui-general-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Documento principal y anexos del ciudadano</strong></h4>
        </div>
        <!-- end panel-heading -->
        <!-- begin panel-body -->
        <div class="panel-body">
            <!-- Documento principal Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('url', 'Documento principal del PQRS:', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <input-file :file-name-real="true":value="dataForm" name-field="document_pdf" :max-files="1"
                        :max-filesize="11" file-path="public/container/pqr_{{ date('Y') }}"
                        message="Arrastre o seleccione los archivos" help-text="Utilice este campo para almacenar una copia electrónica de una carta u otro documento que se haya recibido\. El tamaño máximo permitido es de 10 MB\."
                        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
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
                        :is-update="isUpdate"  ruta-delete-update="pqrs/p-q-r-s-delete-file" :id-file-delete="dataForm.id" :mostrar-eliminar-adjunto="dataForm.users_id == {{ Auth::user()->id }}">
                    </input-file>
                    <div class="invalid-feedback" v-if="dataErrors.adjunto_ciudadano">
                        <p class="m-b-0" v-for="error in dataErrors.adjunto_ciudadano">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if(Auth::user()->hasRole('Administrador de requerimientos'))
{{-- Clasificacion documental --}}
<div class="panel" data-sortable-id="ui-general-1" id="clasificacion">
    @include('correspondence::internals.field_clasificacion_documental')
</div>
@endif
