
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Datos de destino</strong></h4>
    </div>
    <!-- end panel-heading -->

    <!-- Require internal_all Field -->
    <div class="col-md-12">
        <div class="form-group row m-b-15">
            {!! Form::label('internal_all', '¿Correspondencia para todos los funcionarios de la entidad?: ', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                @if (config('app.CI_DESTINATARIOS_TODOS'))
                    <select class="form-control" id="internal_all" v-model="dataForm.internal_all" required >
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                @else
                    <select class="form-control" id="internal_all" v-model="dataForm.internal_all" required disabled>
                        <option value="0">No</option>
                        <option value="1">Si</option>
                    </select>
                @endif

                <Small>Seleccione "Sí" o "No" para indicar si esta correspondencia esta dirigida a toda la entidad.</Small>

                <div class="invalid-feedback" v-if="dataErrors.internal_all">
                    <p class="m-b-0" v-for="error in dataErrors.internal_all">
                        @{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- begin panel-body -->
    <div class="panel-body" v-if="dataForm.internal_all!='1'">
            <div class="row">
                <dynamic-list label-button-add="Agregar destinatario" icon-button-add="fas fa-user-plus" :data-list.sync="dataForm.internal_recipients"
                    :data-list-options="[
                        { label: 'Funcionario, cargo, dependencia o grupo', name: 'name', isShow: true, refList: 'recipientRef' },
                        { label: 'Dependencia', name: 'users_dependencia_id', isShow: true, nameObjectKey: ['dependencia_informacion', 'nombre'], refList: 'dependencia_ref' },
                        { label: 'Correo', name: 'users_email', isShow: true },
                    ]"
                    class-container="col-md-12" class-table="table table-bordered">
                    <template #fields="scope">

                        <div class="form-group row m-b-15">
                            {!! Form::label('name', 'Nombre del funcionario, cargo, dependencia o grupo:', ['class' => 'col-form-label col-md-3 required']) !!}
                            <div class="col-md-9">
                                <autocomplete
                                    :is-update="isUpdate"
                                    {{-- :value-default="scope.dataForm.id" --}}
                                    name-prop="name"
                                    name-field="recipient_id"
                                    :value='scope.dataForm'
                                    name-resource="/correspondence/get-recipients-internal"
                                    css-class="form-control"
                                    :name-labels-display="['name']"
                                    :fields-change-values="['users_email:email', 'name:name','users_dependencia_id:id_dependencia','cargos_id:cargos_id','work_groups_id:work_groups_id','dependencias_id:dependencias_id','type:type']"
                                    reduce-key="id"
                                    :is-required="true"
                                    name-field-object="recipient_datos"
                                    ref="recipientRef"
                                    name-field-edit="name"
                                    :key="keyRefresh"
                                    :ids-to-empty="['dependencia_informacion']"
                                    >
                                </autocomplete>

                                {{-- {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => false]) !!} --}}
                                <small>
                                    Ingrese y seleccione el nombre, cargo, dependencia o grupo para añadirlo.
                                    <br>También puede agregar funcionarios no registrados en Intraweb.
                                  </small>

                                <div class="invalid-feedback" v-if="dataErrors.name">
                                    <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                                </div>
                            </div>

                        </div>


                        {{-- v-if="scope.dataForm.type!='Cargo' && scope.dataForm.type!='Dependencia' && scope.dataForm.type!='Grupo'" --}}
                            <div id="funcionario_personalizado" v-if="scope.dataForm.type!='Cargo' && scope.dataForm.type!='Dependencia' && scope.dataForm.type!='Grupo'">
                                <div class="form-group row m-b-15">

                                    {!! Form::label('users_dependencia_id','Dependencia (opcional):', ['class' => 'col-form-label col-md-3']) !!}
                                    <div class="col-md-4">
                                        <select-check
                                            css-class="form-control"
                                            name-field="users_dependencia_id"
                                            reduce-label="nombre"
                                            reduce-key="id"
                                            name-resource="/intranet/get-dependencies"
                                            :value="scope.dataForm"
                                            :is-required="false"
                                            :enable-search="true"
                                            name-field-object="dependencia_informacion"
                                            ref="dependencia_ref"
                                            >
                                        </select-check>


                                    </div>

                                    {!! Form::label('users_email', 'Correo (opcional):', ['class' => 'col-form-label col-md-1']) !!}
                                    <div class="col-md-4">
                                        {!! Form::email('users_email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_email }", 'v-model' => 'scope.dataForm.users_email', 'required' => false]) !!}
                                        <small>@lang('Enter the') @{{ `@lang('Email')` | lowercase }}.</small>
                                        <div class="invalid-feedback" v-if="dataErrors.users_email">
                                            <p class="m-b-0" v-for="error in dataErrors.users_email">@{{ error }}</p>
                                        </div>
                                    </div>
                                    {!! Form::hidden('work_groups_id', null, ['v-model' => 'scope.dataForm.work_groups_id']) !!}
                                    {!! Form::hidden('dependencias_id', null, ['v-model' => 'scope.dataForm.dependencias_id']) !!}
                                    {!! Form::hidden('cargos_id', null, ['v-model' => 'scope.dataForm.cargos_id']) !!}
                                    {!! Form::hidden('type', null, ['v-model' => 'scope.dataForm.type']) !!}




                                </div>
                            </div>
                    </template>
                </dynamic-list>
            </div>



            <div class="col-md-12 mt-5">
                <!--  copias Field destination-->
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
    <!-- end panel-body -->
</div>
