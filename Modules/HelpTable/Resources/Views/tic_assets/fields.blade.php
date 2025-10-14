<!-- Consecutive Field -->
<div class="form-group row m-b-15">
    {!! Form::label('consecutive', trans('Consecutive').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('consecutive', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutive }", 'v-model' => 'dataForm.consecutive', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Consecutive')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.consecutive">
            <p class="m-b-0" v-for="error in dataErrors.consecutive">@{{ error }}</p>
        </div> 
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Brand Field -->
<div class="form-group row m-b-15">
    {!! Form::label('brand', trans('Brand').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('brand', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.brand }", 'v-model' => 'dataForm.brand', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Brand')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.brand">
            <p class="m-b-0" v-for="error in dataErrors.brand">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Serial Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serial', trans('Serial').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.serial }", 'v-model' => 'dataForm.serial', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Serial')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.serial">
            <p class="m-b-0" v-for="error in dataErrors.serial">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('model', trans('Model').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('model', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.model }", 'v-model' => 'dataForm.model', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Model')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.model">
            <p class="m-b-0" v-for="error in dataErrors.model">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Inventory Plate Field -->
<div class="form-group row m-b-15">
    {!! Form::label('inventory_plate', trans('Inventory Plate').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('inventory_plate', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.inventory_plate }", 'v-model' => 'dataForm.inventory_plate', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Inventory Plate')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.inventory_plate">
            <p class="m-b-0" v-for="error in dataErrors.inventory_plate">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- General Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_description', trans('General Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('general_description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.general_description }", 'v-model' => 'dataForm.general_description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('General Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.general_description">
            <p class="m-b-0" v-for="error in dataErrors.general_description">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Purchase Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('purchase_date', trans('Purchase Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('purchase_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.purchase_date }", 'id' => 'purchase_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.purchase_date', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Purchase Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.purchase_date">
            <p class="m-b-0" v-for="error in dataErrors.purchase_date">@{{ error }}</p>
        </div>
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#purchase_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Location Address Field -->
<div class="form-group row m-b-15">
    {!! Form::label('location_address', trans('Location Address').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('location_address', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.location_address }", 'v-model' => 'dataForm.location_address', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Location Address')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.location_address">
            <p class="m-b-0" v-for="error in dataErrors.location_address">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  State Field -->
<div class="form-group row m-b-15">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- state switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="state" id="state"  v-model="dataForm.state">
        <label for="state"></label>
        <small>@lang('Select the') @{{ `@lang('State')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.state">
            <p class="m-b-0" v-for="error in dataErrors.state">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Monitor Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('monitor_id', trans('Monitor Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('monitor_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.monitor_id }", 'v-model' => 'dataForm.monitor_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Monitor Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.monitor_id">
            <p class="m-b-0" v-for="error in dataErrors.monitor_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Keyboard Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('keyboard_id', trans('Keyboard Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('keyboard_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.keyboard_id }", 'v-model' => 'dataForm.keyboard_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Keyboard Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.keyboard_id">
            <p class="m-b-0" v-for="error in dataErrors.keyboard_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mouse Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mouse_id', trans('Mouse Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('mouse_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mouse_id }", 'v-model' => 'dataForm.mouse_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mouse Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mouse_id">
            <p class="m-b-0" v-for="error in dataErrors.mouse_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Operating System Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system', trans('Operating System').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- operating_system switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="operating_system" id="operating_system"  v-model="dataForm.operating_system">
        <label for="operating_system"></label>
        <small>@lang('Select the') @{{ `@lang('Operating System')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system">
            <p class="m-b-0" v-for="error in dataErrors.operating_system">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Operating System Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system_version', trans('Operating System Version').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- operating_system_version switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="operating_system_version" id="operating_system_version"  v-model="dataForm.operating_system_version">
        <label for="operating_system_version"></label>
        <small>@lang('Select the') @{{ `@lang('Operating System Version')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system_version">
            <p class="m-b-0" v-for="error in dataErrors.operating_system_version">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Operating System Serial Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_system_serial', trans('Operating System Serial').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_system_serial', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.operating_system_serial }", 'v-model' => 'dataForm.operating_system_serial', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Operating System Serial')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.operating_system_serial">
            <p class="m-b-0" v-for="error in dataErrors.operating_system_serial">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  License Microsoft Office Field -->
<div class="form-group row m-b-15">
    {!! Form::label('license_microsoft_office', trans('License Microsoft Office').':', ['class' => 'col-form-label col-md-3']) !!}
    <!-- license_microsoft_office switcher -->
    <div class="switcher col-md-9">
        <input type="checkbox" name="license_microsoft_office" id="license_microsoft_office"  v-model="dataForm.license_microsoft_office">
        <label for="license_microsoft_office"></label>
        <small>@lang('Select the') @{{ `@lang('License Microsoft Office')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.license_microsoft_office">
            <p class="m-b-0" v-for="error in dataErrors.license_microsoft_office">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Serial Licencia Microsoft Office Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serial_licencia_microsoft_office', trans('Serial Licencia Microsoft Office').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serial_licencia_microsoft_office', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.serial_licencia_microsoft_office }", 'v-model' => 'dataForm.serial_licencia_microsoft_office', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Serial Licencia Microsoft Office')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.serial_licencia_microsoft_office">
            <p class="m-b-0" v-for="error in dataErrors.serial_licencia_microsoft_office">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Processor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('processor', trans('Processor').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('processor', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.processor }", 'v-model' => 'dataForm.processor', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Processor')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.processor">
            <p class="m-b-0" v-for="error in dataErrors.processor">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ram Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ram', trans('Ram').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('ram', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ram }", 'v-model' => 'dataForm.ram', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ram')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ram">
            <p class="m-b-0" v-for="error in dataErrors.ram">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Hdd Field -->
<div class="form-group row m-b-15">
    {!! Form::label('hdd', trans('Hdd').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('hdd', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.hdd }", 'v-model' => 'dataForm.hdd', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Hdd')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.hdd">
            <p class="m-b-0" v-for="error in dataErrors.hdd">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name User Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_user', trans('Name User').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_user', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_user }", 'v-model' => 'dataForm.name_user', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name User')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name_user">
            <p class="m-b-0" v-for="error in dataErrors.name_user">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Provider Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider_name', trans('Provider Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('provider_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.provider_name }", 'v-model' => 'dataForm.provider_name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Provider Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.provider_name">
            <p class="m-b-0" v-for="error in dataErrors.provider_name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ht Tic Period Validity Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_period_validity_id', trans('Ht Tic Period Validity Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_period_validity_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_period_validity_id }", 'v-model' => 'dataForm.ht_tic_period_validity_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Period Validity Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_period_validity_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_period_validity_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ht Tic Type Assets Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_type_assets_id', trans('Ht Tic Type Assets Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_type_assets_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_type_assets_id }", 'v-model' => 'dataForm.ht_tic_type_assets_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Type Assets Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_type_assets_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_type_assets_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Ht Tic Provider Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ht_tic_provider_id', trans('Ht Tic Provider Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('ht_tic_provider_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ht_tic_provider_id }", 'v-model' => 'dataForm.ht_tic_provider_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Ht Tic Provider Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.ht_tic_provider_id">
            <p class="m-b-0" v-for="error in dataErrors.ht_tic_provider_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Users Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', trans('Users Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('users_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.users_id }", 'v-model' => 'dataForm.users_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Users Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.users_id">
            <p class="m-b-0" v-for="error in dataErrors.users_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Dependencias Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_id', trans('Dependencias Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('dependencias_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.dependencias_id }", 'v-model' => 'dataForm.dependencias_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Dependencias Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
            <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
        </div>
    </div>
</div>
