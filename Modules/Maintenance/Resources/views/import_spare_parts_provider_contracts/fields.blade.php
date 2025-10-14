<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Descripción').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>Ingrese la descripción del repuesto.</small>
    </div>
</div>

<!-- Unit_measure Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_measure', trans('Unidad de medida').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_measure', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_measure', 'required' => true]) !!}
        <small>Ingrese la unidad de medida.</small>
    </div>
</div>

<!-- Unit_value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_value', trans('Valor unitario').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_value', 'required' => true]) !!}
        <small>Ingrese el valor unitario.</small>
    </div>
</div>

<!-- Iva Field -->
<div class="form-group row m-b-15">
    {!! Form::label('iva', trans('IVA').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('iva', null, ['class' => 'form-control', 'v-model' => 'dataForm.iva', 'required' => true]) !!}
        <small>Ingrese el IVA.</small>
    </div>
</div>

<!-- Total_value Field -->
<div class="form-group row m-b-15">
    {!! Form::label('total_value', trans('Valor total').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('total_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_value', 'required' => true]) !!}
        <small>Ingrese el valor total.</small>
    </div>
</div>