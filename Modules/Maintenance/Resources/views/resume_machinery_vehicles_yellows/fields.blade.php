<!-- Name Vehicle Machinery Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_vehicle_machinery', trans('Name Vehicle Machinery').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_vehicle_machinery', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_vehicle_machinery', 'required' => true]) !!}
    </div>
</div>

<!-- No Inventory Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_inventory', trans('No Inventory').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_inventory', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_inventory', 'required' => true]) !!}
    </div>
</div>

<!-- No Identification Field -->
<div class="form-group row m-b-15">
    {!! Form::label('no_identification', trans('No Identification').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('no_identification', null, ['class' => 'form-control', 'v-model' => 'dataForm.no_identification', 'required' => true]) !!}
    </div>
</div>

<!-- Purchase Price Field -->
<div class="form-group row m-b-15">
    {!! Form::label('purchase_price', trans('Purchase Price').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('purchase_price', null, ['class' => 'form-control', 'v-model' => 'dataForm.purchase_price', 'required' => true]) !!}
    </div>
</div>

<!-- Sheet Elaboration Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('sheet_elaboration_date', trans('Sheet Elaboration Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('sheet_elaboration_date', null, ['class' => 'form-control', 'id' => 'sheet_elaboration_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.sheet_elaboration_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#sheet_elaboration_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Mileage Start Activities Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mileage_start_activities', trans('Mileage Start Activities').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('mileage_start_activities', null, ['class' => 'form-control', 'v-model' => 'dataForm.mileage_start_activities', 'required' => true]) !!}
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

<!-- Acquisition Contract Field -->
<div class="form-group row m-b-15">
    {!! Form::label('acquisition_contract', trans('Acquisition Contract').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('acquisition_contract', null, ['class' => 'form-control', 'v-model' => 'dataForm.acquisition_contract', 'required' => true]) !!}
    </div>
</div>

<!-- Invoice Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('invoice_number', trans('Invoice Number').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('invoice_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.invoice_number', 'required' => true]) !!}
    </div>
</div>

<!-- Date Purchase Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_purchase', trans('Date Purchase').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_purchase', null, ['class' => 'form-control', 'id' => 'date_purchase',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_purchase', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_purchase').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Date Put Into Service Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_put_into_service', trans('Date Put Into Service').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_put_into_service', null, ['class' => 'form-control', 'id' => 'date_put_into_service',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_put_into_service', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_put_into_service').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Warranty Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('warranty_date', trans('Warranty Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('warranty_date', null, ['class' => 'form-control', 'id' => 'warranty_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.warranty_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#warranty_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Service Retirement Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('service_retirement_date', trans('Service Retirement Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('service_retirement_date', null, ['class' => 'form-control', 'id' => 'service_retirement_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.service_retirement_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#service_retirement_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Plaque Field -->
<div class="form-group row m-b-15">
    {!! Form::label('plaque', trans('Plaque').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('plaque', null, ['class' => 'form-control', 'v-model' => 'dataForm.plaque', 'required' => true]) !!}
    </div>
</div>

<!-- Color Field -->
<div class="form-group row m-b-15">
    {!! Form::label('color', trans('Color').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('color', null, ['class' => 'form-control', 'v-model' => 'dataForm.color', 'required' => true]) !!}
    </div>
</div>

<!-- Chassis Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('chassis_number', trans('Chassis Number').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('chassis_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.chassis_number', 'required' => true]) !!}
    </div>
</div>

<!-- Service Class Field -->
<div class="form-group row m-b-15">
    {!! Form::label('service_class', trans('Service Class').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('service_class', null, ['class' => 'form-control', 'v-model' => 'dataForm.service_class', 'required' => true]) !!}
    </div>
</div>

<!-- Body Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('body_type', trans('Body Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('body_type', null, ['class' => 'form-control', 'v-model' => 'dataForm.body_type', 'required' => true]) !!}
    </div>
</div>

<!-- Transit License Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('transit_license_number', trans('Transit License Number').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('transit_license_number', null, ['class' => 'form-control', 'v-model' => 'dataForm.transit_license_number', 'required' => true]) !!}
    </div>
</div>

<!-- Number Passengers Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_passengers', trans('Number Passengers').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('number_passengers', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_passengers', 'required' => true]) !!}
    </div>
</div>

<!-- Fuel Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fuel_type', trans('Fuel Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('fuel_type', null, ['class' => 'form-control', 'v-model' => 'dataForm.fuel_type', 'required' => true]) !!}
    </div>
</div>

<!-- Number Tires Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_tires', trans('Number Tires').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('number_tires', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_tires', 'required' => true]) !!}
    </div>
</div>

<!-- Tire Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tire_reference', trans('Tire Reference').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('tire_reference', null, ['class' => 'form-control', 'v-model' => 'dataForm.tire_reference', 'required' => true]) !!}
    </div>
</div>

<!-- Number Batteries Field -->
<div class="form-group row m-b-15">
    {!! Form::label('number_batteries', trans('Number Batteries').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('number_batteries', null, ['class' => 'form-control', 'v-model' => 'dataForm.number_batteries', 'required' => true]) !!}
    </div>
</div>

<!-- Battery Reference Field -->
<div class="form-group row m-b-15">
    {!! Form::label('battery_reference', trans('Battery Reference').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('battery_reference', null, ['class' => 'form-control', 'v-model' => 'dataForm.battery_reference', 'required' => true]) !!}
    </div>
</div>

<!-- Gallon Tank Capacity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('gallon_tank_capacity', trans('Gallon Tank Capacity').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('gallon_tank_capacity', null, ['class' => 'form-control', 'v-model' => 'dataForm.gallon_tank_capacity', 'required' => true]) !!}
    </div>
</div>

<!-- Tank Capacity Tons Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tank_capacity_tons', trans('Tank Capacity Tons').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('tank_capacity_tons', null, ['class' => 'form-control', 'v-model' => 'dataForm.tank_capacity_tons', 'required' => true]) !!}
    </div>
</div>

<!-- Tons Capacity Load Field -->
<div class="form-group row m-b-15">
    {!! Form::label('tons_capacity_load', trans('Tons Capacity Load').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('tons_capacity_load', null, ['class' => 'form-control', 'v-model' => 'dataForm.tons_capacity_load', 'required' => true]) !!}
    </div>
</div>

<!-- Cylinder Capacity Field -->
<div class="form-group row m-b-15">
    {!! Form::label('cylinder_capacity', trans('Cylinder Capacity').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('cylinder_capacity', null, ['class' => 'form-control', 'v-model' => 'dataForm.cylinder_capacity', 'required' => true]) !!}
    </div>
</div>

<!-- Expiration Date Soat Field -->
<div class="form-group row m-b-15">
    {!! Form::label('expiration_date_soat', trans('Expiration Date Soat').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('expiration_date_soat', null, ['class' => 'form-control', 'id' => 'expiration_date_soat',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.expiration_date_soat', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#expiration_date_soat').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Expiration Date Tecnomecanica Field -->
<div class="form-group row m-b-15">
    {!! Form::label('expiration_date_tecnomecanica', trans('Expiration Date Tecnomecanica').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('expiration_date_tecnomecanica', null, ['class' => 'form-control', 'id' => 'expiration_date_tecnomecanica',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.expiration_date_tecnomecanica', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#expiration_date_tecnomecanica').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


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
