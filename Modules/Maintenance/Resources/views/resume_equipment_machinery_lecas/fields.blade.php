<!-- Name Equipment Machinery Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_equipment_machinery', trans('Name Equipment Machinery').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_equipment_machinery', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_equipment_machinery', 'required' => true]) !!}
    </div>
</div>

<!-- No Identification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_identification', trans('No Identification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_identification', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_identification', 'required' => true]) !!}
    </div>
</div>

<!-- No Inventory Epa Esp Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_inventory_epa_esp', trans('No Inventory Epa Esp').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_inventory_epa_esp', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_inventory_epa_esp', 'required' => true]) !!}
    </div>
</div>

<!-- Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mark', trans('Mark').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('mark', null, ['class' => 'form-control', 'v-model' => 'dataForm.mark', 'required' => true]) !!}
    </div>
</div>

<!-- Model Field -->
<div class="form-group row m-b-15">
    {!! Form::label('model', trans('Model').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('model', null, ['class' => 'form-control', 'v-model' => 'dataForm.model', 'required' => true]) !!}
    </div>
</div>

<!-- Serie Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serie', trans('Serie').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serie', null, ['class' => 'form-control', 'v-model' => 'dataForm.serie', 'required' => true]) !!}
    </div>
</div>

<!-- Location Field -->
<div class="form-group row m-b-15">
    {!! Form::label('location', trans('Location').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('location', null, ['class' => 'form-control', 'v-model' => 'dataForm.location', 'required' => true]) !!}
    </div>
</div>

<!-- Path Information Field -->
<div class="form-group row m-b-15">
    {!! Form::label('path_information', trans('Path Information').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('path_information', null, ['class' => 'form-control', 'v-model' => 'dataForm.path_information', 'required' => true]) !!}
    </div>
</div>

<!-- Acquisition Contract Field -->
<div class="form-group row m-b-15">
    {!! Form::label('acquisition_contract', trans('Acquisition Contract').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('acquisition_contract', null, ['class' => 'form-control', 'v-model' => 'dataForm.acquisition_contract', 'required' => true]) !!}
    </div>
</div>

<!-- Provider Data Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider_data', trans('Provider Data').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('provider_data', null, ['class' => 'form-control', 'v-model' => 'dataForm.provider_data', 'required' => true]) !!}
    </div>
</div>

<!-- Apply Field -->
<div class="form-group row m-b-15">
    {!! Form::label('apply', trans('Apply').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('apply', null, ['class' => 'form-control', 'v-model' => 'dataForm.apply', 'required' => true]) !!}
    </div>
</div>

<!-- Location Specification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('location_specification', trans('Location Specification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('location_specification', null, ['class' => 'form-control', 'v-model' => 'dataForm.location_specification', 'required' => true]) !!}
    </div>
</div>

<!-- Language Field -->
<div class="form-group row m-b-15">
    {!! Form::label('language', trans('Language').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('language', null, ['class' => 'form-control', 'v-model' => 'dataForm.language', 'required' => true]) !!}
    </div>
</div>

<!-- Version Field -->
<div class="form-group row m-b-15">
    {!! Form::label('version', trans('Version').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('version', null, ['class' => 'form-control', 'v-model' => 'dataForm.version', 'required' => true]) !!}
    </div>
</div>

<!-- Purchase Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('purchase_date', trans('Purchase Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('purchase_date', null, ['class' => 'form-control', 'v-model' => 'dataForm.purchase_date', 'required' => true]) !!}
    </div>
</div>

<!-- Commissioning Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('commissioning_date', trans('Commissioning Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('commissioning_date', null, ['class' => 'form-control', 'v-model' => 'dataForm.commissioning_date', 'required' => true]) !!}
    </div>
</div>

<!-- Date Withdrawal Service Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_withdrawal_service', trans('Date Withdrawal Service').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_withdrawal_service', null, ['class' => 'form-control', 'v-model' => 'dataForm.date_withdrawal_service', 'required' => true]) !!}
    </div>
</div>

<!-- Observations Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observations', trans('Observations').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('observations', null, ['class' => 'form-control', 'v-model' => 'dataForm.observations', 'required' => true]) !!}
    </div>
</div>

<!-- Vo Bo Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('vo_bo_name', trans('Vo Bo Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('vo_bo_name', null, ['class' => 'form-control', 'v-model' => 'dataForm.vo_bo_name', 'required' => true]) !!}
    </div>
</div>

<!-- Vo Bo Cargo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('vo_bo_cargo', trans('Vo Bo Cargo').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('vo_bo_cargo', null, ['class' => 'form-control', 'v-model' => 'dataForm.vo_bo_cargo', 'required' => true]) !!}
    </div>
</div>

<!-- Magnitude Field -->
<div class="form-group row m-b-15">
    {!! Form::label('magnitude', trans('Magnitude').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('magnitude', null, ['class' => 'form-control', 'v-model' => 'dataForm.magnitude', 'required' => true]) !!}
    </div>
</div>

<!-- Unit Measurement Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_measurement', trans('Unit Measurement').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_measurement', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_measurement', 'required' => true]) !!}
    </div>
</div>

<!-- Scale Division Field -->
<div class="form-group row m-b-15">
    {!! Form::label('scale_division', trans('Scale Division').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('scale_division', null, ['class' => 'form-control', 'v-model' => 'dataForm.scale_division', 'required' => true]) !!}
    </div>
</div>

<!-- Manufacturer Specification Max Permissible Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('manufacturer_specification_max_permissible_error', trans('Manufacturer Specification Max Permissible Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('manufacturer_specification_max_permissible_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.manufacturer_specification_max_permissible_error', 'required' => true]) !!}
    </div>
</div>

<!-- Max Permissible Error Technical Standard Process Field -->
<div class="form-group row m-b-15">
    {!! Form::label('max_permissible_error_technical_standard_process', trans('Max Permissible Error Technical Standard Process').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('max_permissible_error_technical_standard_process', null, ['class' => 'form-control', 'v-model' => 'dataForm.max_permissible_error_technical_standard_process', 'required' => true]) !!}
    </div>
</div>

<!-- Measurement Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('measurement_range', trans('Measurement Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('measurement_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.measurement_range', 'required' => true]) !!}
    </div>
</div>

<!-- Operation Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operation_range', trans('Operation Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operation_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.operation_range', 'required' => true]) !!}
    </div>
</div>

<!-- Use Parameter Field -->
<div class="form-group row m-b-15">
    {!! Form::label('use_parameter', trans('Use Parameter').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('use_parameter', null, ['class' => 'form-control', 'v-model' => 'dataForm.use_parameter', 'required' => true]) !!}
    </div>
</div>

<!-- Use Recommendations Field -->
<div class="form-group row m-b-15">
    {!! Form::label('use_recommendations', trans('Use Recommendations').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('use_recommendations', null, ['class' => 'form-control', 'v-model' => 'dataForm.use_recommendations', 'required' => true]) !!}
    </div>
</div>

<!-- Resolution Field -->
<div class="form-group row m-b-15">
    {!! Form::label('resolution', trans('Resolution').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('resolution', null, ['class' => 'form-control', 'v-model' => 'dataForm.resolution', 'required' => true]) !!}
    </div>
</div>

<!-- Analog Indication Field -->
<div class="form-group row m-b-15">
    {!! Form::label('analog_indication', trans('Analog Indication').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('analog_indication', null, ['class' => 'form-control', 'v-model' => 'dataForm.analog_indication', 'required' => true]) !!}
    </div>
</div>

<!-- Digital Indication Field -->
<div class="form-group row m-b-15">
    {!! Form::label('digital_indication', trans('Digital Indication').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('digital_indication', null, ['class' => 'form-control', 'v-model' => 'dataForm.digital_indication', 'required' => true]) !!}
    </div>
</div>

<!-- Wavelength Indication Field -->
<div class="form-group row m-b-15">
    {!! Form::label('wavelength_indication', trans('Wavelength Indication').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('wavelength_indication', null, ['class' => 'form-control', 'v-model' => 'dataForm.wavelength_indication', 'required' => true]) !!}
    </div>
</div>

<!-- Adsorption Indication Field -->
<div class="form-group row m-b-15">
    {!! Form::label('adsorption_indication', trans('Adsorption Indication').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('adsorption_indication', null, ['class' => 'form-control', 'v-model' => 'dataForm.adsorption_indication', 'required' => true]) !!}
    </div>
</div>

<!-- Feeding Field -->
<div class="form-group row m-b-15">
    {!! Form::label('feeding', trans('Feeding').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('feeding', null, ['class' => 'form-control', 'v-model' => 'dataForm.feeding', 'required' => true]) !!}
    </div>
</div>

<!-- Voltage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('voltage', trans('Voltage').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('voltage', null, ['class' => 'form-control', 'v-model' => 'dataForm.voltage', 'required' => true]) !!}
    </div>
</div>

<!-- Rh Field -->
<div class="form-group row m-b-15">
    {!! Form::label('RH', trans('Rh').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('RH', null, ['class' => 'form-control', 'v-model' => 'dataForm.RH', 'required' => true]) !!}
    </div>
</div>

<!-- Power Field -->
<div class="form-group row m-b-15">
    {!! Form::label('power', trans('Power').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('power', null, ['class' => 'form-control', 'v-model' => 'dataForm.power', 'required' => true]) !!}
    </div>
</div>

<!-- Temperature Field -->
<div class="form-group row m-b-15">
    {!! Form::label('temperature', trans('Temperature').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('temperature', null, ['class' => 'form-control', 'v-model' => 'dataForm.temperature', 'required' => true]) !!}
    </div>
</div>

<!-- Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('frequency', trans('Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Revolutions Per Minute Field -->
<div class="form-group row m-b-15">
    {!! Form::label('revolutions_per_minute', trans('Revolutions Per Minute').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('revolutions_per_minute', null, ['class' => 'form-control', 'v-model' => 'dataForm.revolutions_per_minute', 'required' => true]) !!}
    </div>
</div>

<!-- Type Protection Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type_protection', trans('Type Protection').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('type_protection', null, ['class' => 'form-control', 'v-model' => 'dataForm.type_protection', 'required' => true]) !!}
    </div>
</div>

<!-- Rated Current Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rated_current', trans('Rated Current').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('rated_current', null, ['class' => 'form-control', 'v-model' => 'dataForm.rated_current', 'required' => true]) !!}
    </div>
</div>

<!-- Rated Power Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rated_power', trans('Rated Power').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('rated_power', null, ['class' => 'form-control', 'v-model' => 'dataForm.rated_power', 'required' => true]) !!}
    </div>
</div>

<!-- Operating Conditions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operating_conditions', trans('Operating Conditions').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operating_conditions', null, ['class' => 'form-control', 'v-model' => 'dataForm.operating_conditions', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Validation External Verification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_validation_external_verification', trans('Calibration Validation External Verification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_validation_external_verification', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_validation_external_verification', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_frequency', trans('Calibration Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Preventive Maintenance Field -->
<div class="form-group row m-b-15">
    {!! Form::label('preventive_maintenance', trans('Preventive Maintenance').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('preventive_maintenance', null, ['class' => 'form-control', 'v-model' => 'dataForm.preventive_maintenance', 'required' => true]) !!}
    </div>
</div>

<!-- Maintenance Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maintenance_frequency', trans('Maintenance Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maintenance_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.maintenance_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Verification Internal Verification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('verification_internal_verification', trans('Verification Internal Verification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('verification_internal_verification', null, ['class' => 'form-control', 'v-model' => 'dataForm.verification_internal_verification', 'required' => true]) !!}
    </div>
</div>

<!-- Verification Frequency Field -->
<div class="form-group row m-b-15">
    {!! Form::label('verification_frequency', trans('Verification Frequency').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('verification_frequency', null, ['class' => 'form-control', 'v-model' => 'dataForm.verification_frequency', 'required' => true]) !!}
    </div>
</div>

<!-- Procedure Code Field -->
<div class="form-group row m-b-15">
    {!! Form::label('procedure_code', trans('Procedure Code').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('procedure_code', null, ['class' => 'form-control', 'v-model' => 'dataForm.procedure_code', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Points Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_points', trans('Calibration Points').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_points', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_points', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Under Accreditation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_under_accreditation', trans('Calibration Under Accreditation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_under_accreditation', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_under_accreditation', 'required' => true]) !!}
    </div>
</div>

<!-- Reference Norm Field -->
<div class="form-group row m-b-15">
    {!! Form::label('reference_norm', trans('Reference Norm').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('reference_norm', null, ['class' => 'form-control', 'v-model' => 'dataForm.reference_norm', 'required' => true]) !!}
    </div>
</div>

<!-- Measure Pattern Field -->
<div class="form-group row m-b-15">
    {!! Form::label('measure_pattern', trans('Measure Pattern').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('measure_pattern', null, ['class' => 'form-control', 'v-model' => 'dataForm.measure_pattern', 'required' => true]) !!}
    </div>
</div>

<!-- Criteria Acceptance Field -->
<div class="form-group row m-b-15">
    {!! Form::label('criteria_acceptance', trans('Criteria Acceptance').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('criteria_acceptance', null, ['class' => 'form-control', 'v-model' => 'dataForm.criteria_acceptance', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Test Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_test', trans('Calibration Test').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_test', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_test', 'required' => true]) !!}
    </div>
</div>

<!-- Dependencias Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_id', trans('Dependencias Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('dependencias_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.dependencias_id', 'required' => true]) !!}
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
