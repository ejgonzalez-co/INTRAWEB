<!-- Name Equipment Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name_equipment', trans('Name Equipment').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name_equipment', null, ['class' => 'form-control', 'v-model' => 'dataForm.name_equipment', 'required' => true]) !!}
    </div>
</div>

<!-- Internal Code Leca Field -->
<div class="form-group row m-b-15">
    {!! Form::label('internal_code_leca', trans('Internal Code Leca').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('internal_code_leca', null, ['class' => 'form-control', 'v-model' => 'dataForm.internal_code_leca', 'required' => true]) !!}
    </div>
</div>

<!-- Inventory No Field -->
<div class="form-group row m-b-15">
    {!! Form::label('inventory_no', trans('Inventory No').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('inventory_no', null, ['class' => 'form-control', 'v-model' => 'dataForm.inventory_no', 'required' => true]) !!}
    </div>
</div>

<!-- Mark Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mark', trans('Mark').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('mark', null, ['class' => 'form-control', 'v-model' => 'dataForm.mark', 'required' => true]) !!}
    </div>
</div>

<!-- Serie Field -->
<div class="form-group row m-b-15">
    {!! Form::label('serie', trans('Serie').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('serie', null, ['class' => 'form-control', 'v-model' => 'dataForm.serie', 'required' => true]) !!}
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

<!-- Software Field -->
<div class="form-group row m-b-15">
    {!! Form::label('software', trans('Software').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('software', null, ['class' => 'form-control', 'v-model' => 'dataForm.software', 'required' => true]) !!}
    </div>
</div>

<!-- Purchase Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('purchase_date', trans('Purchase Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('purchase_date', null, ['class' => 'form-control', 'id' => 'purchase_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.purchase_date', 'required' => true]) !!}
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


<!-- Commissioning Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('commissioning_date', trans('Commissioning Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('commissioning_date', null, ['class' => 'form-control', 'id' => 'commissioning_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.commissioning_date', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#commissioning_date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Date Withdrawal Service Field -->
<div class="form-group row m-b-15">
    {!! Form::label('date_withdrawal_service', trans('Date Withdrawal Service').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('date_withdrawal_service', null, ['class' => 'form-control', 'id' => 'date_withdrawal_service',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.date_withdrawal_service', 'required' => true]) !!}
    </div>
</div>

@push('css')
	{!!Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css')!!}
@endpush

@push('scripts')
    {!!Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')!!}
    <script>
        $('#date_withdrawal_service').datepicker({
            todayHighlight: true
        });
    </script>
@endpush


<!-- Maker Field -->
<div class="form-group row m-b-15">
    {!! Form::label('maker', trans('Maker').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('maker', null, ['class' => 'form-control', 'v-model' => 'dataForm.maker', 'required' => true]) !!}
    </div>
</div>

<!-- Provider Field -->
<div class="form-group row m-b-15">
    {!! Form::label('provider', trans('Provider').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('provider', null, ['class' => 'form-control', 'v-model' => 'dataForm.provider', 'required' => true]) !!}
    </div>
</div>

<!-- Catalogue Field -->
<div class="form-group row m-b-15">
    {!! Form::label('catalogue', trans('Catalogue').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('catalogue', null, ['class' => 'form-control', 'v-model' => 'dataForm.catalogue', 'required' => true]) !!}
    </div>
</div>

<!-- Idiom Field -->
<div class="form-group row m-b-15">
    {!! Form::label('idiom', trans('Idiom').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('idiom', null, ['class' => 'form-control', 'v-model' => 'dataForm.idiom', 'required' => true]) !!}
    </div>
</div>

<!-- Instructive Field -->
<div class="form-group row m-b-15">
    {!! Form::label('instructive', trans('Instructive').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('instructive', null, ['class' => 'form-control', 'v-model' => 'dataForm.instructive', 'required' => true]) !!}
    </div>
</div>

<!-- Instructional Location Field -->
<div class="form-group row m-b-15">
    {!! Form::label('instructional_location', trans('Instructional Location').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('instructional_location', null, ['class' => 'form-control', 'v-model' => 'dataForm.instructional_location', 'required' => true]) !!}
    </div>
</div>

<!-- Magnitude Control Field -->
<div class="form-group row m-b-15">
    {!! Form::label('magnitude_control', trans('Magnitude Control').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('magnitude_control', null, ['class' => 'form-control', 'v-model' => 'dataForm.magnitude_control', 'required' => true]) !!}
    </div>
</div>

<!-- Consumables Field -->
<div class="form-group row m-b-15">
    {!! Form::label('consumables', trans('Consumables').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('consumables', null, ['class' => 'form-control', 'v-model' => 'dataForm.consumables', 'required' => true]) !!}
    </div>
</div>

<!-- Resolution Field -->
<div class="form-group row m-b-15">
    {!! Form::label('resolution', trans('Resolution').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('resolution', null, ['class' => 'form-control', 'v-model' => 'dataForm.resolution', 'required' => true]) !!}
    </div>
</div>

<!-- Accessories Field -->
<div class="form-group row m-b-15">
    {!! Form::label('accessories', trans('Accessories').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('accessories', null, ['class' => 'form-control', 'v-model' => 'dataForm.accessories', 'required' => true]) !!}
    </div>
</div>

<!-- Operation Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('operation_range', trans('Operation Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('operation_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.operation_range', 'required' => true]) !!}
    </div>
</div>

<!-- Voltage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('voltage', trans('Voltage').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('voltage', null, ['class' => 'form-control', 'v-model' => 'dataForm.voltage', 'required' => true]) !!}
    </div>
</div>

<!-- Use Field -->
<div class="form-group row m-b-15">
    {!! Form::label('use', trans('Use').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('use', null, ['class' => 'form-control', 'v-model' => 'dataForm.use', 'required' => true]) !!}
    </div>
</div>

<!-- Use Range Field -->
<div class="form-group row m-b-15">
    {!! Form::label('use_range', trans('Use Range').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('use_range', null, ['class' => 'form-control', 'v-model' => 'dataForm.use_range', 'required' => true]) !!}
    </div>
</div>

<!-- Allowable Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('allowable_error', trans('Allowable Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('allowable_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.allowable_error', 'required' => true]) !!}
    </div>
</div>

<!-- Minimum Permissible Error Field -->
<div class="form-group row m-b-15">
    {!! Form::label('minimum_permissible_error', trans('Minimum Permissible Error').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('minimum_permissible_error', null, ['class' => 'form-control', 'v-model' => 'dataForm.minimum_permissible_error', 'required' => true]) !!}
    </div>
</div>

<!-- Environmental Operating Conditions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('environmental_operating_conditions', trans('Environmental Operating Conditions').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('environmental_operating_conditions', null, ['class' => 'form-control', 'v-model' => 'dataForm.environmental_operating_conditions', 'required' => true]) !!}
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
