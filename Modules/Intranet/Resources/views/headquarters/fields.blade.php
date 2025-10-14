<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, ['class' => 'form-control', 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
    </div>
</div>

<!-- Descripcion Field -->
<div class="form-group row m-b-15">
    {!! Form::label('descripcion', trans('Descripcion').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('descripcion', null, ['class' => 'form-control', 'v-model' => 'dataForm.descripcion', 'required' => true]) !!}
    </div>
</div>

<!-- Codigo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('codigo', trans('Codigo').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('codigo', null, ['class' => 'form-control', 'v-model' => 'dataForm.codigo', 'required' => true]) !!}
    </div>
</div>
