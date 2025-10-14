


<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Detalles de la cuenta de usuario</strong></div>
    </div>
    <div class="panel-body">
        <!-- Name Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Name') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                    <small>Ingrese el nombre del usuario.</small>
                </div>
            </div>
        </div>

        <!-- Id Cargo Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('id_cargo', 'Cargo' . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <select-check css-class="form-control" name-field="id_cargo" name-resource="get-charges"
                        reduce-label="nombre" reduce-key="id" :value="dataForm" :is-required="true" :enable-search="true">
                    </select-check>
                    <small>Seleccione el cargo del usuario.</small>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('id_others_dependencies', ' Dependencias' . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    <add-list-autocomplete :value="dataForm" name-prop="name"
                        name-field-autocomplete="search_field" name-field="others_dependencies"
                        name-resource="get-search-dependences" name-options-list="dependencies"
                        :name-labels-display="['nombre']" reduce-key="id"
                        help="Ingrese el nombre de la dependencia y seleccione una opción del listado." :key="keyRefresh">
                    </add-list-autocomplete>
                </div>
            </div>
        </div>

        <!-- Email Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('email', trans('Email') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::email('email', null, ['class' => 'form-control', 'v-model' => 'dataForm.email', 'required' => true]) !!}
                    <small>Ingrese el correo del usuario.</small>
                </div>
            </div>
        </div>

        <!-- State Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('state', trans('State') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::select('status', ['Activo' => 'Activo', 'Inactivo' => 'Inactivo'], null, [
                        'class' => 'form-control',
                        'v-model' => 'dataForm.status',
                        'required' => true,
                    ]) !!}
                    <small>Seleccione el estado del usuario.</small>
                </div>
            </div>
        </div>
    </div>
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
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading ui-sortable-handle">
                        <h4 class="panel-title"><strong>Roles y permisos</strong></h4><br>
                    </div>
                    <div class="alert hljs-wrapper fade show">
                        <!--<span class="close" data-dismiss="alert">×</span>-->
                        <i class="fa fa-info fa-2x pull-left m-r-10 m-t-5"></i>
                        <p class="m-b-0">Son permisos otorgados a los usuarios a partir de roles que le permiten llevar a acabo ciertas tareas en los sistemas.</p>
                    </div>
                    <div class="panel-body">
                        <!-- Checks Roles -->
                        <select-check
                            css-class="custom-control-input"
                            name-field="roles" reduce-key="name" reduce-label="name"
                            name-resource="get-roles" :value="dataForm"
                            :is-check="true" >
                        </select-check>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>