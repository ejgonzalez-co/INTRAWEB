<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Información del Proveedor</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Identification Number Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Identification Number'):</strong>
            <p class="col-9 text-break">@{{ dataShow.identification_number }}.</p>
        </div>
        <div class="row">
            <!-- Contract Number Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
            <p class="col-9 text-break">@{{ dataShow.contract_number }}.</p>
        </div>


        <div class="row">
            <!-- Fullname Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Fullname'):</strong>
            <p class="col-9 text-break">@{{ dataShow.fullname }}.</p>
        </div>


        <div class="row">
            <!-- Email Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Email'):</strong>
            <p class="col-9 text-break">@{{ dataShow.email }}.</p>
        </div>


        <div class="row">
            <!-- Phone Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Phone'):</strong>
            <p class="col-9 text-break">@{{ dataShow.phone }}.</p>
        </div>


        <div class="row">
            <!-- Apress Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Address'):</strong>
            <p class="col-9 text-break">@{{ dataShow.address }}.</p>
        </div>

        <div class="row">
            <!-- Status Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Estado del contrato'):</strong>
            <p class="col-9 text-break">@{{ dataShow.status }}.</p>
        </div>

        <div class="row">
            <!-- Status System Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Estado ingreso al sistema'):</strong>
            <p class="col-9 text-break">@{{ dataShow.status_system }}.</p>
        </div>

        <div class="row">
            <!-- Observations Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Observations'):</strong>
            <p class="col-9 text-break">@{{ dataShow.observations }}.</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Credenciales de acceso</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Observations Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Pin'):</strong>
            <p class="col-9 text-break">@{{ dataShow.pin }}.</p>
        </div>
        <div class="row">
            <!-- Observations Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Password'):</strong>
            <p class="col-9 text-break">@{{ dataShow.password }}.</p>
        </div>
    </div>
</div>
