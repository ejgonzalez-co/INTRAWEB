<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Detalles de la cuenta de usuario</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Name Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Name'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.name }}.</dd>
        </div>

        <div class="row">
            <!-- Id Cargo Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Cargo'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.positions ? dataShow.positions.nombre : '' }}.</dd>
        </div>

        <div class="row">
            <!-- Email Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Email'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.email }}.</dd>
        </div>

        <div class="row">
            <!-- State Field -->
            <dt class="text-inverse text-left col-3 text-truncate">@lang('State'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.state ? 'Activo' : 'Inactivo' }}.</dd>
        </div>

        <div class="row">
            <dt class="text-inverse text-left col-3 text-truncate">@lang('Created_at'):</dt>
            <dd class="col-9 text-truncate">@{{ dataShow.created_at }}.</dd>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Dependencias asignadas</strong></div>
    </div>
    <div class="panel-body">
        <div class="row mb-2" v-if="dataShow.dependencies">
            <!-- Process Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Dependencias'):</strong>
            <table border="1" class="text-center col-3">
                <thead>
                    <tr>
                        <td><strong>Dependencias</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(dependence,key) in dataShow.dependencies" :key="key">
                        <td>@{{ dependence.nombre }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Roles y permisos</strong></div>
    </div>
    <div class="panel-body">
        <div class="row mb-2" v-if="dataShow.roles">
            <!-- Process Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Roles'):</strong>
            <table border="1" class="text-center col-3">
                <thead>
                    <tr>
                        <td><strong>Roles</strong></td>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(role,key) in dataShow.roles" :key="key">
                        <td>@{{ role.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
