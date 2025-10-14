<div v-show="!dataForm.change_user" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Validar usuario</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">

            <div class="col-md-6">
                <!-- User Id Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('user_id', trans('Funcionario').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <autocomplete
                            name-prop="name"
                            name-field="user_id"
                            :value='dataForm'
                            name-resource="/intranet/get-users"
                            css-class="form-control"
                            :name-labels-display="['name', 'email']"
                            reduce-key="id"
                            :key="keyRefresh"
                        >
                        </autocomplete>
                        <small>Seleccione el funcionario del activo</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<div>


<div v-show="!dataForm.change_user" class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Estructura organizacional</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Id Cargo Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('id_cargo', trans('positions').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        <select-check css-class="form-control" name-field="id_cargo" reduce-label="nombre" name-resource="/intranet/get-positions" :value="dataForm" :is-required="true" ></select-check>
                        <small>Seleccione el cargo al cual pertenece el funcionario</small>
                    </div>
                </div>
            </div>
            <!-- Id Dependencia Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('id_dependencia', trans('dependencies').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        <select-check css-class="form-control" name-field="id_dependencia" reduce-label="nombre" name-resource="/intranet/get-dependencies" :value="dataForm" ></select-check>
                        <small>Seleccione la dependencia a la cual pertenece el funcionario.</small>
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
        <h4 class="panel-title"><strong>Detalles de la cuenta de usuario</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Name Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-4']) !!}
                    <div class="col-md-8">
                        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name']) !!}
                        <small>Ingrese el nombre completo del funcionario (Nombres y apellidos).</small>
                        <div class="invalid-feedback" v-if="dataErrors.name">
                            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Username Field -->
            <div class="col-md-6">
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
            </div>
            <!-- Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password', trans('Password').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::password('password', [':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password', ':required' => 'dataForm.change_user']) !!}
                        <small>Ingrese una contraseña que contenga mínimo 6 caracteres.</small>
                        <div class="invalid-feedback" v-if="dataErrors.password">
                            <p class="m-b-0" v-for="error in dataErrors.password">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Confirm Password Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('password_confirmation', trans('Confirm Password').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::password('password_confirmation', [':class' => "{'form-control':true, 'is-invalid':dataErrors.password }", 'v-model' => 'dataForm.password_confirmation', ':required' => 'dataForm.change_user']) !!}
                        <small>Por favor confirme la contraseña que ingresó.</small>
                    </div>
                </div>
            </div>
            <!-- Email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-4 required']) !!}
                    <div class="col-md-8">
                        {!! Form::email('email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email }", 'v-model' => 'dataForm.email', 'required' => true]) !!}
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
                            name-resource="/intranet/get-work-groups" :value="dataForm"
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


</div>
