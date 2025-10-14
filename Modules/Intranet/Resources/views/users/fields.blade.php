<div v-show="!dataForm.change_user" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Estructura organizacional</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('sedes_id', trans('headquarter').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        <select-check css-class="form-control" name-field="sedes_id" reduce-label="nombre" name-resource="get-headquarters" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                        <small>Seleccione la sede a la que pertenece el funcionario</small>
                    </div>
                </div>
            </div>


            @if (Auth::user()->hasRole('Admin seven'))
                <!-- Id Dependencia Field -->
                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        {!! Form::label('id_dependencia', trans('dependencies').':', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8">
                            <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="get-dependencies" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                            <small>Seleccione la dependencia a la cual pertenece el funcionario.</small>
                        </div>
                    </div>
                </div>
                <!-- Id Cargo Field -->
                <div class="col-md-6">
                    <div class="form-group row m-b-15">
                        {!! Form::label('id_cargo', trans('positions').':', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8">
                            <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="get-positions" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                            <small>Seleccione el cargo al cual pertenece el funcionario</small>
                        </div>
                    </div>
                </div>
            @else
                <!-- Id Dependencia Field -->
                <div class="col-md-6" v-if="!isUpdate">
                <div class="form-group row m-b-15">
                    {!! Form::label('id_dependencia', trans('dependencies').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="get-dependencies" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                        <small>Seleccione la dependencia a la cual pertenece el funcionario.</small>
                    </div>
                </div>
                </div>
                <!-- Id Cargo Field -->
                <div class="col-md-6" v-if="!isUpdate">
                    <div class="form-group row m-b-15">
                        {!! Form::label('id_cargo', trans('positions').':', ['class' => 'col-form-label col-md-4 required']) !!}
                        <div class="col-md-8">
                            <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="get-positions" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                            <small>Seleccione el cargo al cual pertenece el funcionario</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Detalles de la cuenta de usuario</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Name Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-2 required']) !!}
                    <div class="col-md-10">
                        {!! Form::text('name', null, ['autocomplete' => 'off', ':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
                        <small>Ingrese el nombre completo del funcionario (Nombres y apellidos).</small>
                        <div class="invalid-feedback" v-if="dataErrors.name">
                            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Username Field -->
            {{-- Se documenta ya que el campo username va a gestionarse para los usuarios antiguos creados desde Joomla --}}
            {{-- <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('username', trans('Username').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::text('username', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.username }", 'v-model' => 'dataForm.username', 'required' => true]) !!}
                        <small>Ingrese el nombre de usuario que va a utilizar en la cuenta.</small>
                        <div class="invalid-feedback" v-if="dataErrors.username">
                            <p class="m-b-0" v-for="error in dataErrors.username">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password', trans('Password').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::password('password', ['autocomplete' => 'new-password', ':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password', ':required' => 'dataForm.change_user']) !!}
                        <small>Ingrese una contraseña que tenga mínimo 8 caracteres, debe contener al menos una letra mayúscula, una minúscula, un número y un símbolo - /; 0) & @.?! %*.</small><br />
                        <div class="invalid-feedback" v-if="dataErrors.password">
                            {{-- <p class="m-b-0" v-for="error in dataErrors.password">@{{ error }}</p> --}}
                            <p>@{{ dataErrors.password[dataErrors.password.length - 1] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Confirm Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password_confirmation', trans('Confirm Password').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::password('password_confirmation', ['autocomplete' => 'off',':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password_confirmation', ':required' => 'dataForm.change_user']) !!}
                        <small>Por favor confirme la contraseña que ingresó.</small>
                    </div>
                </div>
            </div>
            <!-- Email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::email('email', null, ['autocomplete' => 'new-email', ':class' => "{'form-control':true, 'is-invalid':dataErrors.email }", 'v-model' => 'dataForm.email', 'required' => true]) !!}
                        <small>Ingrese un correo electrónico válido, ej: xxxxx@gmail.com</small>
                        <div class="invalid-feedback" v-if="dataErrors.email">
                            <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Block Field -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('block', trans('Block').':', ['class' => 'col-form-label col-md-4']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="block" id="block" v-model="dataForm.block">
                        <label for="block"></label>
                        <small>Si bloquea la cuenta el usuario no podrá ingresar a ninguno de los sistemas.</small>
                    </div>
                </div>
            </div>

            <!-- autorizado_firmar Field -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('autorizado_firmar', '¿Este usuario está autorizado para firmar?', ['class' => 'col-form-label col-md-4 font-weight-semibold']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="autorizado_firmar" id="autorizado_firmar" v-model="dataForm.autorizado_firmar">
                        <label for="autorizado_firmar"></label>
                        <small>Habilitar esta opción permite al usuario firmar documentos. Si la cuenta se bloquea, el usuario no podrá acceder a ninguno de los sistemas.</small>
                    </div>
                </div>
            </div>

            <!-- Sendemail Field -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('sendEmail', trans('Sendemail').':', ['class' => 'col-form-label col-md-4']) !!}
                    <!-- Sendemail switcher -->
                    <div class="switcher col-md-6 m-t-5">
                        <input type="checkbox" name="sendEmail" id="sendEmail" v-model="dataForm.sendEmail">
                        <label for="sendEmail"></label>
                    </div>
                </div>
            </div>

            <!-- Inicio del contrato-->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('contract_start', 'Fecha inicial del contrato:', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::date('contract_start', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_start }", 'v-model' => 'dataForm.contract_start', 'required' => false]) !!}
                        <small>Seleccione la fecha inicial del contrato del funcionario</small>
                    </div>

                </div>
            </div>

            <!-- fin del contrato-->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('contract_end', 'Fecha final del contrato:', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::date('contract_end', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_end }", 'v-model' => 'dataForm.contract_end', 'required' => false]) !!}
                        <small>Seleccione la fecha final del contrato del funcionario</small>
                    </div>

                </div>
            </div>

            <!-- Url Img Profile Field -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('url_img_profile', trans('Url Img Profile').':', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::file('url_img_profile', ['accept' => 'image/*', '@change' => 'inputFile($event, "url_img_profile")', 'required' => false]) !!}
                        <small>Relacione una imagen al perfil de la cuenta.</small>
                    </div>
                </div>
            </div>
            <!-- Url Digital Signature Field -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('url_digital_signature', trans('Digital Signature').':', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::file('url_digital_signature', ['accept' => 'image/*', '@change' => 'inputFile($event, "url_digital_signature")', 'required' => false]) !!}
                        <small>Relacione una imagen de la firma escaneada del funcionario, sólo se utilizará para algunos sistemas.</small>
                    </div>
                </div>
            </div>

            <!-- birthday -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                <div class="form-group row m-b-15">
                    <label for="birthday" class="col-form-label col-md-4">Fecha de cumpleaños: <i class="fas fa-birthday-cake"></i></label>
                    <div class="col-md-8">
                        {!! Form::date('birthday', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_end }", 'v-model' => 'dataForm.birthday', 'required' => false]) !!}
                        <small>Seleccione la fecha de cumpleaños del funcionario</small>
                    </div>
                </div>
            </div>

            <!-- birthday -->
            <div v-show="!dataForm.change_user" class="col-md-6">
                {!! Form::label('is_assignable_pqr_correspondence', trans('No asignar PQRS ni correspondencias').':', ['class' => 'col-form-label col-md-4']) !!}
                <!-- is_assignable_pqr_correspondence switcher -->
                <div class="switcher col-md-6 m-t-5">
                    <input type="checkbox" name="is_assignable_pqr_correspondence" id="is_assignable_pqr_correspondence" v-model="dataForm.is_assignable_pqr_correspondence">
                    <label for="is_assignable_pqr_correspondence"></label>
                </div>
            </div>


        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel de usuario como encargo -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Encargo (E)</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- funcionario_encargado Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('funcionario_encargado', '¿Este funcionario está en encargo?', ['class' => 'col-form-label col-md-4 font-weight-semibold']) !!}
                    <!-- switcher -->
                    <div class="switcher col-md-8 m-t-5">
                        <input type="checkbox" name="funcionario_encargado" id="funcionario_encargado" v-model="dataForm.funcionario_encargado">
                        <label for="funcionario_encargado"></label>
                        <small>Al habilitar esta opción, se indicará que el usuario está en encargo. Además, su firma incluirá una <b>(E)</b> junto al cargo.</small>
                    </div>
                </div>
            </div>

            <!-- Inicio del encargo-->
            <div v-if="dataForm.funcionario_encargado" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('fecha_inicio_encargo', 'Fecha inicial del encargo:', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::date('fecha_inicio_encargo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_inicio_encargo }", 'v-model' => 'dataForm.fecha_inicio_encargo', 'required' => true]) !!}
                        <small>Seleccione la fecha inicial del encargo del funcionario</small>
                    </div>

                </div>
            </div>

            <!-- fin del encargo-->
            <div v-if="dataForm.funcionario_encargado" class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('fecha_fin_encargo', 'Fecha final del encargo:', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::date('fecha_fin_encargo', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fecha_fin_encargo }", 'v-model' => 'dataForm.fecha_fin_encargo', 'required' => true]) !!}
                        <small>Seleccione la fecha final del encargo del funcionario</small>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div v-show="!dataForm.change_user" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Asignaciones en el sistema</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Roles y permisos</strong></h4><br>
                    </div>
                    <div class="alert hljs-wrapper fade show">
                        <!--<span class="close" data-dismiss="alert">×</span>-->
                        <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
                        <p class="m-b-0">Son permisos otorgados a los usuarios a partir de roles agrupados por módulos que le permiten llevar a acabo ciertas tareas en los sistemas.</p>
                    </div>
                    <div class="panel-body">
                        <!-- Checks Roles -->
                        <select-check
                            css-class="custom-control-input"
                            name-field="roles" reduce-key="id" reduce-label="name"
                            name-resource="get-roles" :value="dataForm"
                            :is-check="true"
                            :is-options-group="true">
                        </select-check>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Grupos de trabajo</strong></h4>
                    </div>
                    <div class="alert hljs-wrapper fade show">
                        <!--<span class="close" data-dismiss="alert">×</span>-->
                        <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
                        <p class="m-b-0">Son grupos de funcionarios que dan uso a las herramientas colaborativas del intranet según sus intereses organizacionales.</p>
                    </div>
                    <div class="panel-body">
                        <!-- Checks Roles -->
                        <select-check
                            css-class="custom-control-input"
                            name-field="work_groups" reduce-label="name"
                            name-resource="get-work-groups" :value="dataForm"
                            :is-check="true" >
                        </select-check>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1" v-if="isUpdate">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Notas adicionales</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    {!! Form::label('observation', trans('Observations').':', ['class' => 'col-form-label col-md-2']) !!}
                    <div class="col-md-10">
                        {!! Form::text('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => false]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
