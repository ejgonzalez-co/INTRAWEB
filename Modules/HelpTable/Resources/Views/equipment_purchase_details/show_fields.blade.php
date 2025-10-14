<div class="container-fluid">
    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Contract Number'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.contract_number }}</dd>
    </div>

    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Date'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.date }}</dd>
    </div>

    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Provider'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.provider }}</dd>
    </div>

    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Garantía en años'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.warranty_in_years }}</dd>
    </div>

    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Contract Total Value'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.contract_total_value }}</dd>
    </div>

    <div class="row mb-3">
        <dt class="col-4 text-end fw-bold">@lang('Fecha de terminación de la garantía'):</dt>
        <dd class="col-8 mb-0">@{{ dataShow.warranty_termination_date }}</dd>
    </div>
</div>
