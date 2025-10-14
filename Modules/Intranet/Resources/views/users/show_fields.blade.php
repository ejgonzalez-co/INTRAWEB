<!-- Panel estructura organizacional -->
<div class="panel col-md-12" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h3 class="panel-title"><strong>Estructura organizacional</strong></h3>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <!-- Id Cargo Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('positions'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.positions? dataShow.positions.nombre : '' }}.</label>
                </div>
            </div>
            <!-- Id Dependencia Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('dependencies'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.dependencies? dataShow.dependencies.nombre : '' }}.</label>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
<!-- Panel detalles de cuenta -->
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
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Name'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.name }}.</label>
                </div>
            </div>
            <!-- Username Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Username'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.username }}.</label>
                </div>
            </div>
            <!-- Email Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Email'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.email }}.</label>
                </div>
            </div>
            <!-- Sendemail Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Sendemail'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.sendEmail? '@lang('yes')': '@lang('no')' }}.</label>
                </div>
            </div>
            <!-- Block Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Block'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.block? '@lang('yes')': '@lang('no')' }}.</label>
                </div>
            </div>

            <!-- autorizado_firmar Field -->
            <div class="col-md-6">
            <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>Autorizado para firmar:</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.autorizado_firmar? '@lang('yes')': '@lang('no')' }}.</label>
                </div>
            </div>
            <!-- Url Img Profile Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Url Img Profile'):</strong></label>
                    <img width="150" class="img-responsive" v-if="dataShow.url_img_profile" :src="'{{ asset('storage') }}/'+dataShow.url_img_profile" alt="">
                </div>
            </div>
            <!-- Url Digital Signature Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Digital Signature'):</strong></label>
                    <img width="150" class="img-responsive" v-if="dataShow.url_digital_signature" :src="'{{ asset('storage') }}/'+dataShow.url_digital_signature" alt="">
                </div>
            </div>

            <!-- Funcionario encargado Field -->
            <div class="col-md-12">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('¿Este funcionario está en encargo?'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.funcionario_encargado ? '@lang('yes')': '@lang('no')' }}.</label>
                </div>
            </div>
            <!-- Fecha incio encargo Field -->
            <div class="col-md-6" v-if="dataShow.funcionario_encargado">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Fecha inicial del encargo'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.fecha_inicio_encargo }}.</label>
                </div>
            </div>
            <!-- Fecha fin encargo Field -->
            <div class="col-md-6" v-if="dataShow.funcionario_encargado">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Fecha final del encargo'):</strong></label>
                    <label class="col-form-label col-md-8">@{{ dataShow.fecha_fin_encargo }}.</label>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>

<!-- Panel asignaciones del sistema -->
<div class="panel" data-sortable-id="ui-general-1">
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
                        <p class="m-b-0">Son permisos otorgados a los usuarios a partir de roles que le permiten llevar a acabo ciertas tareas en los sistemas.</p>
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li v-for="(role, key) in dataShow.roles" :key="key">
                                @{{ role.name }}
                            </li>
                        </ul>
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
                        <ul>
                            <li v-for="(wg, key) in dataShow.work_groups" :key="key">
                                @{{ wg.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
<!-- Panel Historial de cambios -->
<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title"><strong>Historial de cambios</strong></h4>
    </div>
    <!-- end panel-heading -->
    <!-- begin panel-body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover fix-vertical-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Created_at')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Userame')</th>
                                <th>@lang('Email')</th>
                                <th>@{{ `@lang('position')` | capitalize }}</th>
                                <th>@{{ `@lang('dependency')` | capitalize }}</th>
                                <th>@lang('Block')</th>
                                <th>@lang('Account Verified')</th>
                                <th>@lang('Sendemail')</th>
                                <th>@lang('Roles')</th>
                                <th>@lang('¿Este funcionario está en encargo?')</th>
                                <th>@lang('Observations')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(history, key) in dataShow.users_history">
                                <td>@{{ getIndexItem(key) }}</td>
                                <td>@{{ history.created_at }}</td>
                                <td>@{{ history.name }}</td>
                                <td>@{{ history.username }}</td>
                                <td>@{{ history.email }}</td>
                                <td>@{{ history.position }}</td>
                                <td>@{{ history.dependency }}</td>
                                <td>@{{ (history.block)? '@lang('yes')' : '@lang('no')' }}</td>
                                <td>@{{ history.email_verified_at? '@lang('yes')' : '@lang('no')' }}</td>
                                <td>@{{ (history.sendEmail)? '@lang('yes')' : '@lang('no')' }}</td>
                                <td>
                                    <ul v-for="role in history.roles">
                                        <li>
                                            @{{ role.name }}
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    Desde: @{{ history.fecha_inicio_encargo }}<br/><br/>
                                    Hasta: @{{ history.fecha_fin_encargo }}
                                </td>
                                <td>@{{ history.observation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end panel-body -->
</div>
