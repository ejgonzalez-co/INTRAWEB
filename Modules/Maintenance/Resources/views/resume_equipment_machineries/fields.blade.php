<!-- Name Equipment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_equipment', trans('Name Equipment').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_equipment', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_equipment', 'required' => true]) !!}
    </div>
</div>

<!-- No Identification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_identification', trans('No Identification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_identification', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_identification', 'required' => true]) !!}
    </div>
</div>

<!-- No Inventory Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_inventory', trans('No Inventory').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_inventory', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_inventory', 'required' => true]) !!}
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

<!-- No Motor Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_motor', trans('No Motor').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_motor', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_motor', 'required' => true]) !!}
    </div>
</div>

<!-- Serie Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serie', trans('Serie').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serie', null, ['class' => 'form-control', 'v-model' => 'dataForm.serie', 'required' => true]) !!}
    </div>
</div>

<!-- Ubication Field -->
<div class="form-group row m-b-15">
    {!! Form::label('ubication', trans('Ubication').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('ubication', null, ['class' => 'form-control', 'v-model' => 'dataForm.ubication', 'required' => true]) !!}
    </div>
</div>

<!-- Acquisition Contract Field -->
<div class="form-group row m-b-15">
    {!! Form::label('acquisition_contract', trans('Acquisition Contract').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('acquisition_contract', null, ['class' => 'form-control', 'v-model' => 'dataForm.acquisition_contract', 'required' => true]) !!}
    </div>
</div>

<!-- No Invoice Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_invoice', trans('No Invoice').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_invoice', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_invoice', 'required' => true]) !!}
    </div>
</div>

<!-- Purchase Price Field -->
<div class="form-group row m-b-15">
    {!! Form::label('purchase_price', trans('Purchase Price').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('purchase_price', null, ['class' => 'form-control', 'v-model' => 'dataForm.purchase_price', 'required' => true]) !!}
    </div>
</div>

<!-- Equipment Warranty Field -->
<div class="form-group row m-b-15">
    {!! Form::label('equipment_warranty', trans('Equipment Warranty').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('equipment_warranty', null, ['class' => 'form-control', 'v-model' => 'dataForm.equipment_warranty', 'required' => true]) !!}
    </div>
</div>

<!-- Equipment Operation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('equipment_operation', trans('Equipment Operation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('equipment_operation', null, ['class' => 'form-control', 'v-model' => 'dataForm.equipment_operation', 'required' => true]) !!}
    </div>
</div>

<!-- No Batteries Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_batteries', trans('No Batteries').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('no_batteries', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_batteries', 'required' => true]) !!}
    </div>
</div>

<!-- Batteries Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('batteries_reference', trans('Batteries Reference').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('batteries_reference', null, ['class' => 'form-control', 'v-model' => 'dataForm.batteries_reference', 'required' => true]) !!}
    </div>
</div>

<!-- No Tires Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_tires', trans('No Tires').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('no_tires', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_tires', 'required' => true]) !!}
    </div>
</div>

<!-- Tire Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tire_reference', trans('Tire Reference').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('tire_reference', null, ['class' => 'form-control', 'v-model' => 'dataForm.tire_reference', 'required' => true]) !!}
    </div>
</div>

<!-- Fuel Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fuel_type', trans('Fuel Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('fuel_type', null, ['class' => 'form-control', 'v-model' => 'dataForm.fuel_type', 'required' => true]) !!}
    </div>
</div>

<!-- Fuel Capacity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fuel_capacity', trans('Fuel Capacity').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('fuel_capacity', null, ['class' => 'form-control', 'v-model' => 'dataForm.fuel_capacity', 'required' => true]) !!}
    </div>
</div>

<!-- Use Oil Field -->
<div class="form-group row m-b-15">
    {!! Form::label('use_oil', trans('Use Oil').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('use_oil', null, ['class' => 'form-control', 'v-model' => 'dataForm.use_oil', 'required' => true]) !!}
    </div>
</div>

<!-- Oil Capacity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('oil_capacity', trans('Oil Capacity').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('oil_capacity', null, ['class' => 'form-control', 'v-model' => 'dataForm.oil_capacity', 'required' => true]) !!}
    </div>
</div>

<!-- Requires Calibration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('requires_calibration', trans('Requires Calibration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('requires_calibration', null, ['class' => 'form-control', 'v-model' => 'dataForm.requires_calibration', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Calibration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_calibration', trans('Periodicity Calibration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_calibration', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_calibration', 'required' => true]) !!}
    </div>
</div>

<!-- Oil Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('oil_change', trans('Oil Change').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('oil_change', null, ['class' => 'form-control', 'v-model' => 'dataForm.oil_change', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Oil Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_oil_change', trans('Periodicity Oil Change').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_oil_change', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_oil_change', 'required' => true]) !!}
    </div>
</div>

<!-- Fluid Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fluid_change', trans('Fluid Change').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('fluid_change', null, ['class' => 'form-control', 'v-model' => 'dataForm.fluid_change', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Fluid Change Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_fluid_change', trans('Periodicity Fluid Change').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_fluid_change', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_fluid_change', 'required' => true]) !!}
    </div>
</div>

<!-- Preventive Maintenance Field -->
<div class="form-group row m-b-15">
    {!! Form::label('preventive_maintenance', trans('Preventive Maintenance').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('preventive_maintenance', null, ['class' => 'form-control', 'v-model' => 'dataForm.preventive_maintenance', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Preventive Maintenance Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_preventive_maintenance', trans('Periodicity Preventive Maintenance').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_preventive_maintenance', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_preventive_maintenance', 'required' => true]) !!}
    </div>
</div>

<!-- Electrical Checks Field -->
<div class="form-group row m-b-15">
    {!! Form::label('electrical_checks', trans('Electrical Checks').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('electrical_checks', null, ['class' => 'form-control', 'v-model' => 'dataForm.electrical_checks', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Electrical Checks Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_electrical_checks', trans('Periodicity Electrical Checks').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_electrical_checks', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_electrical_checks', 'required' => true]) !!}
    </div>
</div>

<!-- Mechanical Checks Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mechanical_checks', trans('Mechanical Checks').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('mechanical_checks', null, ['class' => 'form-control', 'v-model' => 'dataForm.mechanical_checks', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Mechanical Checks Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_mechanical_checks', trans('Periodicity Mechanical Checks').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_mechanical_checks', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_mechanical_checks', 'required' => true]) !!}
    </div>
</div>

<!-- General Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('general_cleaning', trans('General Cleaning').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('general_cleaning', null, ['class' => 'form-control', 'v-model' => 'dataForm.general_cleaning', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity General Cleaning Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_general_cleaning', trans('Periodicity General Cleaning').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_general_cleaning', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_general_cleaning', 'required' => true]) !!}
    </div>
</div>

<!-- License Expiration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('license_expiration', trans('License Expiration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('license_expiration', null, ['class' => 'form-control', 'v-model' => 'dataForm.license_expiration', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity License Expiration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_license_expiration', trans('Periodicity License Expiration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_license_expiration', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_license_expiration', 'required' => true]) !!}
    </div>
</div>

<!-- Warranty Expiration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('warranty_expiration', trans('Warranty Expiration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('warranty_expiration', null, ['class' => 'form-control', 'v-model' => 'dataForm.warranty_expiration', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Warranty Expiration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_warranty_expiration', trans('Periodicity Warranty Expiration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_warranty_expiration', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_warranty_expiration', 'required' => true]) !!}
    </div>
</div>

<!-- Insured Asset Field -->
<div class="form-group row m-b-15">
    {!! Form::label('insured_asset', trans('Insured Asset').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('insured_asset', null, ['class' => 'form-control', 'v-model' => 'dataForm.insured_asset', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Insured Asset Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_insured_asset', trans('Periodicity Insured Asset').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_insured_asset', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_insured_asset', 'required' => true]) !!}
    </div>
</div>

<!-- Rated Current Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rated_current', trans('Rated Current').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('rated_current', null, ['class' => 'form-control', 'v-model' => 'dataForm.rated_current', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Rated Current Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_rated_current', trans('Periodicity Rated Current').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_rated_current', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_rated_current', 'required' => true]) !!}
    </div>
</div>

<!-- Rated Power Field -->
<div class="form-group row m-b-15">
    {!! Form::label('rated_power', trans('Rated Power').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('rated_power', null, ['class' => 'form-control', 'v-model' => 'dataForm.rated_power', 'required' => true]) !!}
    </div>
</div>

<!-- Periodicity Reated Power Field -->
<div class="form-group row m-b-15">
    {!! Form::label('periodicity_reated_power', trans('Periodicity Reated Power').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('periodicity_reated_power', null, ['class' => 'form-control', 'v-model' => 'dataForm.periodicity_reated_power', 'required' => true]) !!}
    </div>
</div>

<!-- Maximum Voltage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maximum_voltage', trans('Maximum Voltage').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maximum_voltage', null, ['class' => 'form-control', 'v-model' => 'dataForm.maximum_voltage', 'required' => true]) !!}
    </div>
</div>

<!-- Minimun Voltage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('minimun_voltage', trans('Minimun Voltage').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('minimun_voltage', null, ['class' => 'form-control', 'v-model' => 'dataForm.minimun_voltage', 'required' => true]) !!}
    </div>
</div>

<!-- Revolutions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('revolutions', trans('Revolutions').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('revolutions', null, ['class' => 'form-control', 'v-model' => 'dataForm.revolutions', 'required' => true]) !!}
    </div>
</div>

<!-- Useful Life Field -->
<div class="form-group row m-b-15">
    {!! Form::label('useful_life', trans('Useful Life').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('useful_life', null, ['class' => 'form-control', 'v-model' => 'dataForm.useful_life', 'required' => true]) !!}
    </div>
</div>

<!-- Transportation Precaution Field -->
<div class="form-group row m-b-15">
    {!! Form::label('transportation_precaution', trans('Transportation Precaution').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('transportation_precaution', null, ['class' => 'form-control', 'v-model' => 'dataForm.transportation_precaution', 'required' => true]) !!}
    </div>
</div>

<!-- Capacity Force Hp Field -->
<div class="form-group row m-b-15">
    {!! Form::label('capacity_force_hp', trans('Capacity Force Hp').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('capacity_force_hp', null, ['class' => 'form-control', 'v-model' => 'dataForm.capacity_force_hp', 'required' => true]) !!}
    </div>
</div>

<!-- Protection Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('protection_type', trans('Protection Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('protection_type', null, ['class' => 'form-control', 'v-model' => 'dataForm.protection_type', 'required' => true]) !!}
    </div>
</div>

<!-- Minimum Permissible Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('minimum_permissible_error', trans('Minimum Permissible Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('minimum_permissible_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.minimum_permissible_error', 'required' => true]) !!}
    </div>
</div>

<!-- Maximun Permissible Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maximun_permissible_error', trans('Maximun Permissible Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maximun_permissible_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.maximun_permissible_error', 'required' => true]) !!}
    </div>
</div>

<!-- Calibration Point Field -->
<div class="form-group row m-b-15">
    {!! Form::label('calibration_point', trans('Calibration Point').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('calibration_point', null, ['class' => 'form-control', 'v-model' => 'dataForm.calibration_point', 'required' => true]) !!}
    </div>
</div>

<!-- Certified Calibration Field -->
<div class="form-group row m-b-15">
    {!! Form::label('certified_calibration', trans('Certified Calibration').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('certified_calibration', null, ['class' => 'form-control', 'v-model' => 'dataForm.certified_calibration', 'required' => true]) !!}
    </div>
</div>

<!-- No Entry Warehouse Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_entry_warehouse', trans('No Entry Warehouse').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('no_entry_warehouse', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_entry_warehouse', 'required' => true]) !!}
    </div>
</div>

<!-- No Exit Warehouse Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_exit_warehouse', trans('No Exit Warehouse').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('no_exit_warehouse', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_exit_warehouse', 'required' => true]) !!}
    </div>
</div>

<!-- Warehouse Entry Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('warehouse_entry_date', trans('Warehouse Entry Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('warehouse_entry_date', null, ['class' => 'form-control', 'id' => 'warehouse_entry_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.warehouse_entry_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#warehouse_entry_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Warehouse Exit Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('warehouse_exit_date', trans('Warehouse Exit Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('warehouse_exit_date', null, ['class' => 'form-control', 'id' => 'warehouse_exit_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.warehouse_exit_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#warehouse_exit_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Service Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('service_start_date', trans('Service Start Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('service_start_date', null, ['class' => 'form-control', 'id' => 'service_start_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.service_start_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#service_start_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Retirement Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('retirement_date', trans('Retirement Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('retirement_date', null, ['class' => 'form-control', 'id' => 'retirement_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.retirement_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#retirement_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Frecuency Use Month Field -->
<div class="form-group row m-b-15">
    {!! Form::label('frecuency_use_month', trans('Frecuency Use Month').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('frecuency_use_month', null, ['class' => 'form-control', 'v-model' => 'dataForm.frecuency_use_month', 'required' => true]) !!}
    </div>
</div>

<!-- Frecuency Use Hours Field -->
<div class="form-group row m-b-15">
    {!! Form::label('frecuency_use_hours', trans('Frecuency Use Hours').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('frecuency_use_hours', null, ['class' => 'form-control', 'v-model' => 'dataForm.frecuency_use_hours', 'required' => true]) !!}
    </div>
</div>

<!-- Operates Equipment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operates_equipment', trans('Operates Equipment').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operates_equipment', null, ['class' => 'form-control', 'v-model' => 'dataForm.operates_equipment', 'required' => true]) !!}
    </div>
</div>

<!-- Observation Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('observation', null, ['class' => 'form-control', 'v-model' => 'dataForm.observation', 'required' => true]) !!}
    </div>
</div>

<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Status').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('status', null, ['class' => 'form-control', 'v-model' => 'dataForm.status', 'required' => true]) !!}
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

<!-- Mant Providers Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_providers_id', trans('Mant Providers Id').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('mant_providers_id', null, ['class' => 'form-control', 'v-model' => 'dataForm.mant_providers_id', 'required' => true]) !!}
    </div>
</div>
