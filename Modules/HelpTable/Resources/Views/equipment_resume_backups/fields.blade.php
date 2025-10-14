<!-- Ht Tic Equipment Resume Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_equipment_resume_id', trans('Ht Tic Equipment Resume Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_equipment_resume_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_equipment_resume_id }", 'v-model' => 'dataForm.ht_tic_equipment_resume_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Equipment Resume Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_equipment_resume_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_equipment_resume_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse', trans('Mouse').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse }", 'v-model' => 'dataForm.mouse', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse">
            <p class="m-b-0" v-for="error in dataErrors.mouse">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower', trans('Tower').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower }", 'v-model' => 'dataForm.tower', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower">
            <p class="m-b-0" v-for="error in dataErrors.tower">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Keyboard Field -->
<div class="form-group row m-b-15">
    {!! Form::label('keyboard', trans('Keyboard').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('keyboard', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard }", 'v-model' => 'dataForm.keyboard', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Keyboard')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.keyboard">
            <p class="m-b-0" v-for="error in dataErrors.keyboard">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor', trans('Monitor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor }", 'v-model' => 'dataForm.monitor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor">
            <p class="m-b-0" v-for="error in dataErrors.monitor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Asset Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('asset_type', trans('Asset Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('asset_type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.asset_type }", 'v-model' => 'dataForm.asset_type', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Asset Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.asset_type">
            <p class="m-b-0" v-for="error in dataErrors.asset_type">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Domain User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('domain_user', trans('Domain User').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('domain_user', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.domain_user }", 'v-model' => 'dataForm.domain_user', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Domain User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.domain_user">
            <p class="m-b-0" v-for="error in dataErrors.domain_user">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Officer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('officer', trans('Officer').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('officer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.officer }", 'v-model' => 'dataForm.officer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Officer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.officer">
            <p class="m-b-0" v-for="error in dataErrors.officer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_type', trans('Contract Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('contract_type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_type }", 'v-model' => 'dataForm.contract_type', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Contract Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_type">
            <p class="m-b-0" v-for="error in dataErrors.contract_type">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Charge Field -->
<div class="form-group row m-b-15">
    {!! Form::label('charge', trans('Charge').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('charge', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.charge }", 'v-model' => 'dataForm.charge', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Charge')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.charge">
            <p class="m-b-0" v-for="error in dataErrors.charge">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Dependence Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependence', trans('Dependence').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('dependence', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.dependence }", 'v-model' => 'dataForm.dependence', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Dependence')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.dependence">
            <p class="m-b-0" v-for="error in dataErrors.dependence">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Area Field -->
<div class="form-group row m-b-15">
    {!! Form::label('area', trans('Area').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('area', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.area }", 'v-model' => 'dataForm.area', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Area')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.area">
            <p class="m-b-0" v-for="error in dataErrors.area">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Site Field -->
<div class="form-group row m-b-15">
    {!! Form::label('site', trans('Site').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('site', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.site }", 'v-model' => 'dataForm.site', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Site')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.site">
            <p class="m-b-0" v-for="error in dataErrors.site">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Service Manager Field -->
<div class="form-group row m-b-15">
    {!! Form::label('service_manager', trans('Service Manager').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('service_manager', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.service_manager }", 'v-model' => 'dataForm.service_manager', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Service Manager')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.service_manager">
            <p class="m-b-0" v-for="error in dataErrors.service_manager">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Maintenance Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maintenance_type', trans('Maintenance Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('maintenance_type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.maintenance_type }", 'v-model' => 'dataForm.maintenance_type', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Maintenance Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.maintenance_type">
            <p class="m-b-0" v-for="error in dataErrors.maintenance_type">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Cycle Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cycle', trans('Cycle').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('cycle', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cycle }", 'v-model' => 'dataForm.cycle', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Cycle')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cycle">
            <p class="m-b-0" v-for="error in dataErrors.cycle">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_number', trans('Contract Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }", 'v-model' => 'dataForm.contract_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Contract Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_number">
            <p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_date', trans('Contract Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="contract_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Contract Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_date">
            <p class="m-b-0" v-for="error in dataErrors.contract_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Maintenance Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maintenance_date', trans('Maintenance Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="maintenance_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Maintenance Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.maintenance_date">
            <p class="m-b-0" v-for="error in dataErrors.maintenance_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Provider Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider', trans('Provider').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('provider', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.provider }", 'v-model' => 'dataForm.provider', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Provider')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.provider">
            <p class="m-b-0" v-for="error in dataErrors.provider">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_value', trans('Contract Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('contract_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_value }", 'v-model' => 'dataForm.contract_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Contract Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.contract_value">
            <p class="m-b-0" v-for="error in dataErrors.contract_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Internal And External Hardware Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_internal_and_external_hardware_cleaning', trans('Has Internal And External Hardware Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_internal_and_external_hardware_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_internal_and_external_hardware_cleaning }", 'v-model' => 'dataForm.has_internal_and_external_hardware_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Internal And External Hardware Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_internal_and_external_hardware_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_internal_and_external_hardware_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Internal And External Hardware Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_internal_and_external_hardware_cleaning', trans('Observation Internal And External Hardware Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('observation_internal_and_external_hardware_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_internal_and_external_hardware_cleaning }", 'v-model' => 'dataForm.observation_internal_and_external_hardware_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Internal And External Hardware Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_internal_and_external_hardware_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_internal_and_external_hardware_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Ram Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_ram_cleaning', trans('Has Ram Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_ram_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_ram_cleaning }", 'v-model' => 'dataForm.has_ram_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Ram Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_ram_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_ram_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Ram Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_ram_cleaning', trans('Observation Ram Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_ram_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_ram_cleaning }", 'v-model' => 'dataForm.observation_ram_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Ram Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_ram_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_ram_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Board Memory Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_board_memory_cleaning', trans('Has Board Memory Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_board_memory_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_board_memory_cleaning }", 'v-model' => 'dataForm.has_board_memory_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Board Memory Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_board_memory_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_board_memory_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Board Memory Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_board_memory_cleaning', trans('Observation Board Memory Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_board_memory_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_board_memory_cleaning }", 'v-model' => 'dataForm.observation_board_memory_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Board Memory Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_board_memory_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_board_memory_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Power Supply Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_power_supply_cleaning', trans('Has Power Supply Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_power_supply_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_power_supply_cleaning }", 'v-model' => 'dataForm.has_power_supply_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Power Supply Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_power_supply_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_power_supply_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Power Supply Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_power_supply_cleaning', trans('Observation Power Supply Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_power_supply_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_power_supply_cleaning }", 'v-model' => 'dataForm.observation_power_supply_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Power Supply Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_power_supply_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_power_supply_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Dvd Drive Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_dvd_drive_cleaning', trans('Has Dvd Drive Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_dvd_drive_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_dvd_drive_cleaning }", 'v-model' => 'dataForm.has_dvd_drive_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Dvd Drive Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_dvd_drive_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_dvd_drive_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Dvd Drive Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_dvd_drive_cleaning', trans('Observation Dvd Drive Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_dvd_drive_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_dvd_drive_cleaning }", 'v-model' => 'dataForm.observation_dvd_drive_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Dvd Drive Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_dvd_drive_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_dvd_drive_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Monitor Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_monitor_cleaning', trans('Has Monitor Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_monitor_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_monitor_cleaning }", 'v-model' => 'dataForm.has_monitor_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Monitor Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_monitor_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_monitor_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Monitor Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_monitor_cleaning', trans('Observation Monitor Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_monitor_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_monitor_cleaning }", 'v-model' => 'dataForm.observation_monitor_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Monitor Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_monitor_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_monitor_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Keyboard Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_keyboard_cleaning', trans('Has Keyboard Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_keyboard_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_keyboard_cleaning }", 'v-model' => 'dataForm.has_keyboard_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Keyboard Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_keyboard_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_keyboard_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Keyboard Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_keyboard_cleaning', trans('Observation Keyboard Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_keyboard_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_keyboard_cleaning }", 'v-model' => 'dataForm.observation_keyboard_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Keyboard Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_keyboard_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_keyboard_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Mouse Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_mouse_cleaning', trans('Has Mouse Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_mouse_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_mouse_cleaning }", 'v-model' => 'dataForm.has_mouse_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Mouse Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_mouse_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_mouse_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Mouse Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_mouse_cleaning', trans('Observation Mouse Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_mouse_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_mouse_cleaning }", 'v-model' => 'dataForm.observation_mouse_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Mouse Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_mouse_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_mouse_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Thermal Paste Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_thermal_paste_change', trans('Has Thermal Paste Change').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_thermal_paste_change', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_thermal_paste_change }", 'v-model' => 'dataForm.has_thermal_paste_change', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Thermal Paste Change')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_thermal_paste_change">
            <p class="m-b-0" v-for="error in dataErrors.has_thermal_paste_change">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Thermal Paste Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_thermal_paste_change', trans('Observation Thermal Paste Change').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_thermal_paste_change', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_thermal_paste_change }", 'v-model' => 'dataForm.observation_thermal_paste_change', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Thermal Paste Change')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_thermal_paste_change">
            <p class="m-b-0" v-for="error in dataErrors.observation_thermal_paste_change">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Has Heatsink Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('has_heatsink_cleaning', trans('Has Heatsink Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('has_heatsink_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.has_heatsink_cleaning }", 'v-model' => 'dataForm.has_heatsink_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Has Heatsink Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.has_heatsink_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.has_heatsink_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Heatsink Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation_heatsink_cleaning', trans('Observation Heatsink Cleaning').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation_heatsink_cleaning', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_heatsink_cleaning }", 'v-model' => 'dataForm.observation_heatsink_cleaning', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation Heatsink Cleaning')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation_heatsink_cleaning">
            <p class="m-b-0" v-for="error in dataErrors.observation_heatsink_cleaning">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Technical Report Field -->
<div class="form-group row m-b-15">
    {!! Form::label('technical_report', trans('Technical Report').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('technical_report', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.technical_report }", 'v-model' => 'dataForm.technical_report', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Technical Report')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.technical_report">
            <p class="m-b-0" v-for="error in dataErrors.technical_report">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Inventory Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_inventory_number', trans('Tower Inventory Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_inventory_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_inventory_number }", 'v-model' => 'dataForm.tower_inventory_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Inventory Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_inventory_number">
            <p class="m-b-0" v-for="error in dataErrors.tower_inventory_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_model', trans('Tower Model').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_model', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_model }", 'v-model' => 'dataForm.tower_model', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Model')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_model">
            <p class="m-b-0" v-for="error in dataErrors.tower_model">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Series Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_series', trans('Tower Series').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_series', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_series }", 'v-model' => 'dataForm.tower_series', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Series')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_series">
            <p class="m-b-0" v-for="error in dataErrors.tower_series">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Processor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_processor', trans('Tower Processor').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_processor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_processor }", 'v-model' => 'dataForm.tower_processor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Processor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_processor">
            <p class="m-b-0" v-for="error in dataErrors.tower_processor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Host Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_host', trans('Tower Host').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_host', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_host }", 'v-model' => 'dataForm.tower_host', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Host')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_host">
            <p class="m-b-0" v-for="error in dataErrors.tower_host">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ram Gb Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ram_gb', trans('Tower Ram Gb').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ram_gb', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ram_gb }", 'v-model' => 'dataForm.tower_ram_gb', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ram Gb')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ram_gb">
            <p class="m-b-0" v-for="error in dataErrors.tower_ram_gb">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ram Gb Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ram_gb_mark', trans('Tower Ram Gb Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ram_gb_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ram_gb_mark }", 'v-model' => 'dataForm.tower_ram_gb_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ram Gb Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ram_gb_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_ram_gb_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Number Ram Modules Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_number_ram_modules', trans('Tower Number Ram Modules').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_number_ram_modules', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_number_ram_modules }", 'v-model' => 'dataForm.tower_number_ram_modules', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Number Ram Modules')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_number_ram_modules">
            <p class="m-b-0" v-for="error in dataErrors.tower_number_ram_modules">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Mac Address Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_mac_address', trans('Tower Mac Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_mac_address', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_mac_address }", 'v-model' => 'dataForm.tower_mac_address', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Mac Address')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_mac_address">
            <p class="m-b-0" v-for="error in dataErrors.tower_mac_address">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Mainboard Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_mainboard', trans('Tower Mainboard').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_mainboard', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_mainboard }", 'v-model' => 'dataForm.tower_mainboard', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Mainboard')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_mainboard">
            <p class="m-b-0" v-for="error in dataErrors.tower_mainboard">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Mainboard Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_mainboard_mark', trans('Tower Mainboard Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_mainboard_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_mainboard_mark }", 'v-model' => 'dataForm.tower_mainboard_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Mainboard Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_mainboard_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_mainboard_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ipv4 Address Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ipv4_address', trans('Tower Ipv4 Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ipv4_address', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ipv4_address }", 'v-model' => 'dataForm.tower_ipv4_address', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ipv4 Address')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ipv4_address">
            <p class="m-b-0" v-for="error in dataErrors.tower_ipv4_address">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ipv6 Address Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ipv6_address', trans('Tower Ipv6 Address').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ipv6_address', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ipv6_address }", 'v-model' => 'dataForm.tower_ipv6_address', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ipv6 Address')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ipv6_address">
            <p class="m-b-0" v-for="error in dataErrors.tower_ipv6_address">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ddh Capacity Gb Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ddh_capacity_gb', trans('Tower Ddh Capacity Gb').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ddh_capacity_gb', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ddh_capacity_gb }", 'v-model' => 'dataForm.tower_ddh_capacity_gb', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ddh Capacity Gb')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ddh_capacity_gb">
            <p class="m-b-0" v-for="error in dataErrors.tower_ddh_capacity_gb">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ddh Capacity Gb Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ddh_capacity_gb_mark', trans('Tower Ddh Capacity Gb Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ddh_capacity_gb_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ddh_capacity_gb_mark }", 'v-model' => 'dataForm.tower_ddh_capacity_gb_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ddh Capacity Gb Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ddh_capacity_gb_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_ddh_capacity_gb_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ssd Capacity Gb Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ssd_capacity_gb', trans('Tower Ssd Capacity Gb').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ssd_capacity_gb', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ssd_capacity_gb }", 'v-model' => 'dataForm.tower_ssd_capacity_gb', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ssd Capacity Gb')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ssd_capacity_gb">
            <p class="m-b-0" v-for="error in dataErrors.tower_ssd_capacity_gb">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Ssd Capacity Gb Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_ssd_capacity_gb_mark', trans('Tower Ssd Capacity Gb Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_ssd_capacity_gb_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_ssd_capacity_gb_mark }", 'v-model' => 'dataForm.tower_ssd_capacity_gb_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Ssd Capacity Gb Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_ssd_capacity_gb_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_ssd_capacity_gb_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Video Card Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_video_card', trans('Tower Video Card').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_video_card', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_video_card }", 'v-model' => 'dataForm.tower_video_card', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Video Card')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_video_card">
            <p class="m-b-0" v-for="error in dataErrors.tower_video_card">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Video Card Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_video_card_mark', trans('Tower Video Card Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_video_card_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_video_card_mark }", 'v-model' => 'dataForm.tower_video_card_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Video Card Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_video_card_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_video_card_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Sound Card Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_sound_card', trans('Tower Sound Card').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_sound_card', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_sound_card }", 'v-model' => 'dataForm.tower_sound_card', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Sound Card')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_sound_card">
            <p class="m-b-0" v-for="error in dataErrors.tower_sound_card">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Sound Card Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_sound_card_mark', trans('Tower Sound Card Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_sound_card_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_sound_card_mark }", 'v-model' => 'dataForm.tower_sound_card_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Sound Card Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_sound_card_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_sound_card_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Network Card Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_network_card', trans('Tower Network Card').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_network_card', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_network_card }", 'v-model' => 'dataForm.tower_network_card', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Network Card')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_network_card">
            <p class="m-b-0" v-for="error in dataErrors.tower_network_card">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Network Card Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_network_card_mark', trans('Tower Network Card Mark').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_network_card_mark', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_network_card_mark }", 'v-model' => 'dataForm.tower_network_card_mark', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Network Card Mark')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_network_card_mark">
            <p class="m-b-0" v-for="error in dataErrors.tower_network_card_mark">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Faceplate Field -->
<div class="form-group row m-b-15">
    {!! Form::label('faceplate', trans('Faceplate').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('faceplate', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.faceplate }", 'v-model' => 'dataForm.faceplate', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Faceplate')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.faceplate">
            <p class="m-b-0" v-for="error in dataErrors.faceplate">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Faceplate Patch Panel Field -->
<div class="form-group row m-b-15">
    {!! Form::label('faceplate_patch_panel', trans('Faceplate Patch Panel').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('faceplate_patch_panel', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.faceplate_patch_panel }", 'v-model' => 'dataForm.faceplate_patch_panel', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Faceplate Patch Panel')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.faceplate_patch_panel">
            <p class="m-b-0" v-for="error in dataErrors.faceplate_patch_panel">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_value', trans('Tower Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_value }", 'v-model' => 'dataForm.tower_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_value">
            <p class="m-b-0" v-for="error in dataErrors.tower_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Tower Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tower_contract_number', trans('Tower Contract Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('tower_contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tower_contract_number }", 'v-model' => 'dataForm.tower_contract_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Tower Contract Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.tower_contract_number">
            <p class="m-b-0" v-for="error in dataErrors.tower_contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Number Inventory Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_number_inventory', trans('Monitor Number Inventory').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor_number_inventory', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_number_inventory }", 'v-model' => 'dataForm.monitor_number_inventory', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Number Inventory')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_number_inventory">
            <p class="m-b-0" v-for="error in dataErrors.monitor_number_inventory">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_model', trans('Monitor Model').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor_model', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_model }", 'v-model' => 'dataForm.monitor_model', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Model')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_model">
            <p class="m-b-0" v-for="error in dataErrors.monitor_model">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Serial Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_serial', trans('Monitor Serial').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor_serial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_serial }", 'v-model' => 'dataForm.monitor_serial', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Serial')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_serial">
            <p class="m-b-0" v-for="error in dataErrors.monitor_serial">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_value', trans('Monitor Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_value }", 'v-model' => 'dataForm.monitor_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_value">
            <p class="m-b-0" v-for="error in dataErrors.monitor_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Monitor Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_contract_number', trans('Monitor Contract Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('monitor_contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_contract_number }", 'v-model' => 'dataForm.monitor_contract_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Contract Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_contract_number">
            <p class="m-b-0" v-for="error in dataErrors.monitor_contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Number Inventory Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_number_inventory', trans('Mouse Number Inventory').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse_number_inventory', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_number_inventory }", 'v-model' => 'dataForm.mouse_number_inventory', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Number Inventory')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_number_inventory">
            <p class="m-b-0" v-for="error in dataErrors.mouse_number_inventory">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_model', trans('Mouse Model').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse_model', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_model }", 'v-model' => 'dataForm.mouse_model', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Model')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_model">
            <p class="m-b-0" v-for="error in dataErrors.mouse_model">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Serial Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_serial', trans('Mouse Serial').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse_serial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_serial }", 'v-model' => 'dataForm.mouse_serial', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Serial')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_serial">
            <p class="m-b-0" v-for="error in dataErrors.mouse_serial">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_value', trans('Mouse Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse_value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_value }", 'v-model' => 'dataForm.mouse_value', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Value')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_value">
            <p class="m-b-0" v-for="error in dataErrors.mouse_value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_contract_number', trans('Mouse Contract Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('mouse_contract_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_contract_number }", 'v-model' => 'dataForm.mouse_contract_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Contract Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_contract_number">
            <p class="m-b-0" v-for="error in dataErrors.mouse_contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Operating System Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system', trans('Operating System').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_system', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system }", 'v-model' => 'dataForm.operating_system', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Operating System')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system">
            <p class="m-b-0" v-for="error in dataErrors.operating_system">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Operating System Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system_version', trans('Operating System Version').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_system_version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system_version }", 'v-model' => 'dataForm.operating_system_version', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Operating System Version')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system_version">
            <p class="m-b-0" v-for="error in dataErrors.operating_system_version">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Operating System License Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system_license', trans('Operating System License').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_system_license', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system_license }", 'v-model' => 'dataForm.operating_system_license', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Operating System License')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system_license">
            <p class="m-b-0" v-for="error in dataErrors.operating_system_license">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Office Automation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('office_automation', trans('Office Automation').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('office_automation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.office_automation }", 'v-model' => 'dataForm.office_automation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Office Automation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.office_automation">
            <p class="m-b-0" v-for="error in dataErrors.office_automation">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Office Automation Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('office_automation_version', trans('Office Automation Version').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('office_automation_version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.office_automation_version }", 'v-model' => 'dataForm.office_automation_version', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Office Automation Version')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.office_automation_version">
            <p class="m-b-0" v-for="error in dataErrors.office_automation_version">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Office Automation License Field -->
<div class="form-group row m-b-15">
    {!! Form::label('office_automation_license', trans('Office Automation License').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('office_automation_license', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.office_automation_license }", 'v-model' => 'dataForm.office_automation_license', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Office Automation License')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.office_automation_license">
            <p class="m-b-0" v-for="error in dataErrors.office_automation_license">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Antivirus Field -->
<div class="form-group row m-b-15">
    {!! Form::label('antivirus', trans('Antivirus').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('antivirus', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.antivirus }", 'v-model' => 'dataForm.antivirus', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Antivirus')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.antivirus">
            <p class="m-b-0" v-for="error in dataErrors.antivirus">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Installed Product Field -->
<div class="form-group row m-b-15">
    {!! Form::label('installed_product', trans('Installed Product').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('installed_product', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.installed_product }", 'v-model' => 'dataForm.installed_product', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Installed Product')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.installed_product">
            <p class="m-b-0" v-for="error in dataErrors.installed_product">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Installed Product Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('installed_product_version', trans('Installed Product Version').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('installed_product_version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.installed_product_version }", 'v-model' => 'dataForm.installed_product_version', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Installed Product Version')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.installed_product_version">
            <p class="m-b-0" v-for="error in dataErrors.installed_product_version">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Browser Field -->
<div class="form-group row m-b-15">
    {!! Form::label('browser', trans('Browser').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('browser', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.browser }", 'v-model' => 'dataForm.browser', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Browser')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.browser">
            <p class="m-b-0" v-for="error in dataErrors.browser">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Browser Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('browser_version', trans('Browser Version').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('browser_version', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.browser_version }", 'v-model' => 'dataForm.browser_version', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Browser Version')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.browser_version">
            <p class="m-b-0" v-for="error in dataErrors.browser_version">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Teamviewer Field -->
<div class="form-group row m-b-15">
    {!! Form::label('teamviewer', trans('Teamviewer').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('teamviewer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.teamviewer }", 'v-model' => 'dataForm.teamviewer', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Teamviewer')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.teamviewer">
            <p class="m-b-0" v-for="error in dataErrors.teamviewer">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Other Field -->
<div class="form-group row m-b-15">
    {!! Form::label('other', trans('Other').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('other', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.other }", 'v-model' => 'dataForm.other', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Other')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.other">
            <p class="m-b-0" v-for="error in dataErrors.other">@{{ error }}</p>
        </div>
    </div>
</div>
