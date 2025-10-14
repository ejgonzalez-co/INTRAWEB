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
            <!-- Url Img Profile Field -->
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-4 text-black-transparent-7"><strong>@lang('Url Img Profile'):</strong></label>
                    <img width="150" class="img-responsive" v-if="dataShow.url_img_profile" :src="'{{ asset('storage') }}/'+dataShow.url_img_profile" alt="">
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
                                <th>@lang('Username')</th>
                                <th>@lang('Email')</th>
                                <th>@{{ `@lang('position')` | capitalize }}</th>
                                <th>@{{ `@lang('dependency')` | capitalize }}</th>
                                <th>@lang('Block')</th>
                                <th>@lang('Account Verified')</th>
                                <th>@lang('Sendemail')</th>
                                <th>@lang('Roles')</th>
                                <th>@lang('work_groups')</th>
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
                                <td v-if="history.roles">
                                    <ul v-for="role in history.roles">
                                        <li>
                                            @{{ role.name }}
                                        </li>
                                    </ul>
                                </td>
                                <td v-if="history.work_groups">
                                    <ul v-for="workGroup in history.work_groups">
                                        <li>
                                            @{{ workGroup.name }}
                                        </li>
                                    </ul>
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
