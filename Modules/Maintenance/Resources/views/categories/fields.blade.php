<!-- Tipo activo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('mant_asset_type_id', trans('Tipo de activo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="mant_asset_type_id" reduce-label="name" reduce-key="id" name-resource="/maintenance/get-type-assets" :value="dataForm" :is-required="true">
        </select-check>
        <small>Seleccione el tipo de activo de la categoría</small>
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control', 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese aquí el nombre de la categoría</small>
    </div>
</div>
