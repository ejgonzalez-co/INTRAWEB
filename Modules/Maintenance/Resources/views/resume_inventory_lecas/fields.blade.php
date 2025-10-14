<!-- No Inventory Epa Esp Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_inventory_epa_esp', trans('No Inventory Epa Esp').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_inventory_epa_esp', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_inventory_epa_esp', 'required' => true]) !!}
    </div>
</div>

<!-- Leca Code Field -->
<div class="form-group row m-b-15">
    {!! Form::label('leca_code', trans('Leca Code').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('leca_code', null, ['class' => 'form-control', 'v-model' => 'dataForm.leca_code', 'required' => true]) !!}
    </div>
</div>

<!-- Description Equipment Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description_equipment_name', trans('Description Equipment Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('description_equipment_name', null, ['class' => 'form-control', 'v-model' => 'dataForm.description_equipment_name', 'required' => true]) !!}
    </div>
</div>

<!-- Maker Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maker', trans('Maker').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maker', null, ['class' => 'form-control', 'v-model' => 'dataForm.maker', 'required' => true]) !!}
    </div>
</div>

<!-- Serial Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serial_number', trans('Serial Number').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serial_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.serial_number', 'required' => true]) !!}
    </div>
</div>

<!-- Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('model', trans('Model').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('model', null, ['class' => 'form-control', 'v-model' => 'dataForm.model', 'required' => true]) !!}
    </div>
</div>

<!-- Location Field -->
<div class="form-group row m-b-15">
    {!! Form::label('location', trans('Location').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('location', null, ['class' => 'form-control', 'v-model' => 'dataForm.location', 'required' => true]) !!}
    </div>
</div>

<!-- Measured Used Field -->
<div class="form-group row m-b-15">
    {!! Form::label('measured_used', trans('Measured Used').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('measured_used', null, ['class' => 'form-control', 'v-model' => 'dataForm.measured_used', 'required' => true]) !!}
    </div>
</div>

<!-- Unit Measurement Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_measurement', trans('Unit Measurement').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_measurement', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_measurement', 'required' => true]) !!}
    </div>
</div>

<!-- Resolution Field -->
<div class="form-group row m-b-15">
    {!! Form::label('resolution', trans('Resolution').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('resolution', null, ['class' => 'form-control', 'v-model' => 'dataForm.resolution', 'required' => true]) !!}
    </div>
</div>

<!-- Manufacturer Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('manufacturer_error', trans('Manufacturer Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('manufacturer_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.manufacturer_error', 'required' => true]) !!}
    </div>
</div>

<!-- Operation Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operation_range', trans('Operation Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operation_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.operation_range', 'required' => true]) !!}
    </div>
</div>

<!-- Range Use Field -->
<div class="form-group row m-b-15">
    {!! Form::label('range_use', trans('Range Use').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('range_use', null, ['class' => 'form-control', 'v-model' => 'dataForm.range_use', 'required' => true]) !!}
    </div>
</div>

<!-- Operating Conditions Temperature Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_conditions_temperature', trans('Operating Conditions Temperature').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_conditions_temperature', null, ['class' => 'form-control', 'v-model' => 'dataForm.operating_conditions_temperature', 'required' => true]) !!}
    </div>
</div>

<!-- Condition Oper Elative Humidity Hr Field -->
<div class="form-group row m-b-15">
    {!! Form::label('condition_oper_elative_humidity_hr', trans('Condition Oper Elative Humidity Hr').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('condition_oper_elative_humidity_hr', null, ['class' => 'form-control', 'v-model' => 'dataForm.condition_oper_elative_humidity_hr', 'required' => true]) !!}
    </div>
</div>

<!-- Condition Oper Voltage Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('condition_oper_voltage_range', trans('Condition Oper Voltage Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('condition_oper_voltage_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.condition_oper_voltage_range', 'required' => true]) !!}
    </div>
</div>

<!-- Maintenance Metrological Operation Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maintenance_metrological_operation_frequency', trans('Maintenance Metrological Operation Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maintenance_metrological_operation_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.maintenance_metrological_operation_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Metrological Operating Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_metrological_operating_frequency', trans('Calibration Metrological Operating Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_metrological_operating_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_metrological_operating_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Qualification Metrological Operating Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('qualification_metrological_operating_frequency', trans('Qualification Metrological Operating Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('qualification_metrological_operating_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.qualification_metrological_operating_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Intermediate Verification Metrological Operating Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('intermediate_verification_metrological_operating_frequency', trans('Intermediate Verification Metrological Operating Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('intermediate_verification_metrological_operating_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.intermediate_verification_metrological_operating_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Total Interventions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_interventions', trans('Total Interventions').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('total_interventions', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_interventions', 'required' => true]) !!}
    </div>
</div>

<!-- Name Elaborated Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_elaborated', trans('Name Elaborated').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_elaborated', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_elaborated', 'required' => true]) !!}
    </div>
</div>

<!-- Cargo Role Elaborated Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cargo_role_elaborated', trans('Cargo Role Elaborated').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('cargo_role_elaborated', null, ['class' => 'form-control', 'v-model' => 'dataForm.cargo_role_elaborated', 'required' => true]) !!}
    </div>
</div>

<!-- Name Updated Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_updated', trans('Name Updated').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_updated', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_updated', 'required' => true]) !!}
    </div>
</div>

<!-- Cargo Role Updated Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cargo_role_updated', trans('Cargo Role Updated').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('cargo_role_updated', null, ['class' => 'form-control', 'v-model' => 'dataForm.cargo_role_updated', 'required' => true]) !!}
    </div>
</div>

<!-- Technical Director Field -->
<div class="form-group row m-b-15">
    {!! Form::label('technical_director', trans('Technical Director').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('technical_director', null, ['class' => 'form-control', 'v-model' => 'dataForm.technical_director', 'required' => true]) !!}
    </div>
</div>

<!-- Mant Category Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_category_id', trans('Mant Category Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_category_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.mant_category_id', 'required' => true]) !!}
    </div>
</div>

<!-- Responsable Field -->
<div class="form-group row m-b-15">
    {!! Form::label('responsable', trans('Responsable').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('responsable', null, ['class' => 'form-control', 'v-model' => 'dataForm.responsable', 'required' => true]) !!}
    </div>
</div>
