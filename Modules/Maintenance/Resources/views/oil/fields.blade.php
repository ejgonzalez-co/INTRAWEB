<!-- Mant Resume Machinery Vehicles Yellow Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_resume_machinery_vehicles_yellow_id', trans('Mant Resume Machinery Vehicles Yellow Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_resume_machinery_vehicles_yellow_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_resume_machinery_vehicles_yellow_id }", 'v-model' => 'dataForm.mant_resume_machinery_vehicles_yellow_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mant Resume Machinery Vehicles Yellow Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mant_resume_machinery_vehicles_yellow_id">
            <p class="m-b-0" v-for="error in dataErrors.mant_resume_machinery_vehicles_yellow_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Mant Oil Element Wear Configurations Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_oil_element_wear_configurations_id', trans('Mant Oil Element Wear Configurations Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_oil_element_wear_configurations_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_oil_element_wear_configurations_id }", 'v-model' => 'dataForm.mant_oil_element_wear_configurations_id', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Mant Oil Element Wear Configurations Id')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.mant_oil_element_wear_configurations_id">
            <p class="m-b-0" v-for="error in dataErrors.mant_oil_element_wear_configurations_id">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Register Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('register_date', trans('Register Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <date-picker
            :value="dataForm"
            name-field="register_date"
            :input-props="{required: true}"
        >
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Register Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.register_date">
            <p class="m-b-0" v-for="error in dataErrors.register_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Show Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('show_type', trans('Show Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('show_type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.show_type }", 'v-model' => 'dataForm.show_type', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Show Type')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.show_type">
            <p class="m-b-0" v-for="error in dataErrors.show_type">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Component Field -->
<div class="form-group row m-b-15">
    {!! Form::label('component', trans('Component').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('component', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.component }", 'v-model' => 'dataForm.component', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Component')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.component">
            <p class="m-b-0" v-for="error in dataErrors.component">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Serial Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serial_number', trans('Serial Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('serial_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.serial_number }", 'v-model' => 'dataForm.serial_number', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Serial Number')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.serial_number">
            <p class="m-b-0" v-for="error in dataErrors.serial_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Brand Field -->
<div class="form-group row m-b-15">
    {!! Form::label('brand', trans('Brand').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('brand', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.brand }", 'v-model' => 'dataForm.brand', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Brand')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.brand">
            <p class="m-b-0" v-for="error in dataErrors.brand">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Number Warranty Extended Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_warranty_extended', trans('Number Warranty Extended').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::number('number_warranty_extended', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.number_warranty_extended }", 'v-model' => 'dataForm.number_warranty_extended', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Number Warranty Extended')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.number_warranty_extended">
            <p class="m-b-0" v-for="error in dataErrors.number_warranty_extended">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Work Order Field -->
<div class="form-group row m-b-15">
    {!! Form::label('work_order', trans('Work Order').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('work_order', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.work_order }", 'v-model' => 'dataForm.work_order', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Work Order')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.work_order">
            <p class="m-b-0" v-for="error in dataErrors.work_order">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Serial Component Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serial_component', trans('Serial Component').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('serial_component', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.serial_component }", 'v-model' => 'dataForm.serial_component', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Serial Component')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.serial_component">
            <p class="m-b-0" v-for="error in dataErrors.serial_component">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Model Component Field -->
<div class="form-group row m-b-15">
    {!! Form::label('model_component', trans('Model Component').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('model_component', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.model_component }", 'v-model' => 'dataForm.model_component', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Model Component')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.model_component">
            <p class="m-b-0" v-for="error in dataErrors.model_component">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Maker Component Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maker_component', trans('Maker Component').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('maker_component', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.maker_component }", 'v-model' => 'dataForm.maker_component', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Maker Component')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.maker_component">
            <p class="m-b-0" v-for="error in dataErrors.maker_component">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Number Control Lab Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_control_lab', trans('Number Control Lab').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('number_control_lab', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.number_control_lab }", 'v-model' => 'dataForm.number_control_lab', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Number Control Lab')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.number_control_lab">
            <p class="m-b-0" v-for="error in dataErrors.number_control_lab">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Grade Oil Field -->
<div class="form-group row m-b-15">
    {!! Form::label('grade_oil', trans('Grade Oil').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('grade_oil', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.grade_oil }", 'v-model' => 'dataForm.grade_oil', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Grade Oil')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.grade_oil">
            <p class="m-b-0" v-for="error in dataErrors.grade_oil">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Type Fluid Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type_fluid', trans('Type Fluid').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('type_fluid', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_fluid }", 'v-model' => 'dataForm.type_fluid', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Type Fluid')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.type_fluid">
            <p class="m-b-0" v-for="error in dataErrors.type_fluid">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Date Finished Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_finished', trans('Date Finished').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('date_finished', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.date_finished }", 'v-model' => 'dataForm.date_finished', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Date Finished')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.date_finished">
            <p class="m-b-0" v-for="error in dataErrors.date_finished">@{{ error }}</p>
        </div>
    </div>
</div>


