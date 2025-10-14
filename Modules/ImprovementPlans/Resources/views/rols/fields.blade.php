<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información del rol</strong></div>
    </div>
    <div class="panel-body">
        <!-- Name Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Name') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
                    <small>Ingrese el nombre del rol.</small>
                </div>
            </div>
        </div>

        <!-- Descript Field -->
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                {!! Form::label('description', 'Descripción' . ':', ['class' => 'col-form-label col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::TextArea('descripction', null, ['class' => 'form-control', 'v-model' => 'dataForm.description','required' => true]) !!}
                    <small>Ingrese la descripción.</small>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Permisos del rol</strong></div>
    </div>
    <div class="panel-body">
        <div class="col-md-9">
            <div class="form-group row m-b-15">
                <dynamic-list label-button-add="Agregar permiso" :data-list.sync="dataForm.rol_permissions"
                    :data-list-options="[
                        { label: 'Módulo', name: 'module', isShow: true },
                        { label: 'Gestión', name: 'can_manage', isShow: true },
                        { label: 'Reportes', name: 'can_generate_reports', isShow: true },
                        { label: 'Solo consulta', name: 'only_consultation', isShow: true },
                    ]"
                    class-container="col-md-12" class-table="table table-bordered" :is-remove="true">
                    <template #fields="scope">

                        <div class="panel-heading ui-sortable-handle">
                            <h4 class="panel-title"><strong>Selecciona el módulo y asigna los permisos</strong></h4>
                        </div>

                        <!-- Module Field -->
                        <div class="col-md-9">
                            <div class="form-group row m-b-15">
                                {!! Form::label('module', 'Módulo' . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {!! Form::Select(
                                        'module',
                                        [
                                            'Administración' => 'Administración',
                                            'Evaluadores' => 'Evaluadores',
                                            'Evaluados' => 'Evaluados',
                                            'Evaluaciones' => 'Evaluaciones',
                                            'Consultas y reportes' => 'Consultas y reportes',
                                            'Planes de mejoramiento' => 'Planes de mejoramiento',
                                        ],
                                        null,
                                        ['class' => 'form-control', 'v-model' => 'scope.dataForm.module', 'required' => true]
                                    ) !!}
                                    <small>Seleccione el módulo.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions Field -->
                        <div class="col-md-12">
                            <div class="form-group row m-b-15">
                                {!! Form::label('', 'Permisos' . ':', ['class' => 'col-form-label col-md-2']) !!}
                                <div class="col-md-9">
                                    <br>
                                    <div class="col-md-10" v-if="!scope.dataForm.only_consultation">
                                        <input type="checkbox" id="can_manage" name="can_manage"
                                            v-model="scope.dataForm.can_manage">
                                        <label for="can_manage">Gestión (crear, editar y eliminar registros)</label>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="checkbox" id="can_generate_reports" name="can_generate_reports"
                                            v-model="scope.dataForm.can_generate_reports">
                                        <label for="can_generate_reports">Reportes</label>
                                    </div>
                                    <div class="col-md-10" v-if="!scope.dataForm.can_manage">
                                        <input type="checkbox" id="only_consultation" name="only_consultation"
                                            v-model="scope.dataForm.only_consultation">
                                        <label for="only_consultation">Solo consulta</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </template>
                </dynamic-list>
            </div>
        </div>
    </div>
</div>
