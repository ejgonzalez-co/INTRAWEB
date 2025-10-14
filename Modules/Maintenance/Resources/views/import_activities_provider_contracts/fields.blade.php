<!-- Descripcion -->
<div class="form-group row m-b-15">
    {!! Form::label('item', 'Item'.':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('item', null, ['class' => 'form-control', 'v-model' => 'dataForm.item', 'required' => true]) !!}
        <small>Ingrese la descripción de la actividad.</small>
    </div>
</div>

<!-- Descripcion -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('description', null, ['class' => 'form-control', 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>Ingrese la descripción de la actividad.</small>
    </div>
</div>

<!-- Tipo -->
<div class="form-group row m-b-15">
    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('type', null, ['class' => 'form-control', 'v-model' => 'dataForm.type', 'required' => true]) !!}
        <small>Ingrese el tipo.</small>
    </div>
</div>

<!-- Sistema -->
<div class="form-group row m-b-15">
    {!! Form::label('system', trans('System').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('system', null, ['class' => 'form-control', 'v-model' => 'dataForm.system', 'required' => true]) !!}
        <small>Ingrese el nombre del sistema.</small>
    </div>
</div>

<!-- Unidad de medida -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_measurement', trans('Unit Measurement').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_measurement', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_measurement', 'required' => true]) !!}
        <small>Ingrese la unidad de medida.</small>
    </div>
</div>

<!-- Cantidad-->
<div class="form-group row m-b-15">
    {!! Form::label('quantity', trans('Quantity').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('quantity', null, ['class' => 'form-control', 'v-model' => 'dataForm.quantity', 'required' => true]) !!}
        <small>Ingrese la cantidad.</small>
    </div>
</div>

<!-- Cantidad-->
<div class="form-group row m-b-15">
    {!! Form::label('iva', trans('Iva').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('iva', null, ['class' => 'form-control', 'v-model' => 'dataForm.iva', 'required' => true]) !!}
        <small>Ingrese el IVA.</small>
    </div>
</div>

<!-- valor unitario -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_value', trans('Unit value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('unit_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.unit_value', 'required' => true]) !!}
        <small>Ingrese el valor unitario.</small>
    </div>
</div>

<!-- Valor total-->
<div class="form-group row m-b-15">
    {!! Form::label('total_value', trans('Total Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('total_value', null, ['class' => 'form-control', 'v-model' => 'dataForm.total_value', 'required' => true]) !!}
        <small>Ingrese el valor total.</small>
    </div>
</div>



