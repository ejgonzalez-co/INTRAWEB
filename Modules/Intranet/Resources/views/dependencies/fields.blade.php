<!-- Sede Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_sede', 'Sede:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="id_sede" reduce-label="nombre" name-resource="get-headquarters" :value="dataForm" :is-required="true"></select-check>
        <small class="form-text text-muted">Selecciona la sede asociada.</small>
    </div>
</div>

<!-- Codigo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('codigo', trans('Codigo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('codigo', null, ['class' => 'form-control', 'v-model' => 'dataForm.codigo', 'required' => true]) !!}
        <small class="form-text text-muted">Ingresa el código relacionado con este campo.</small>
    </div>
</div>

<!-- Nombre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre', trans('Nombre').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('nombre', null, ['class' => 'form-control', 'v-model' => 'dataForm.nombre', 'required' => true]) !!}
        <small class="form-text text-muted">Ingresa el nombre correspondiente.</small>
    </div>
</div>

<!-- Codigo Oficina Productora Field -->
<div class="form-group row m-b-15">
    {!! Form::label('codigo_oficina_productora', trans('Siglas del proceso').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('codigo_oficina_productora', null, ['class' => 'form-control', 'v-model' => 'dataForm.codigo_oficina_productora', 'required' => true]) !!}
        <small class="form-text text-muted">Ingresa las siglas relacionadas con el proceso.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('direccion', trans('Dirección').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('direccion', null, ['class' => 'form-control', 'v-model' => 'dataForm.direccion', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa la dirección relacionado con este campo.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('piso', trans('Piso').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('piso', null, ['class' => 'form-control', 'v-model' => 'dataForm.piso', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa el piso relacionado con este campo.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('codigo_postal', trans('Código postal').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('codigo_postal', null, ['class' => 'form-control', 'v-model' => 'dataForm.codigo_postal', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa el código postal relacionado con este campo.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('telefono', trans('Teléfono').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('telefono', null, ['class' => 'form-control', 'v-model' => 'dataForm.telefono', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa el telefono relacionado con este campo.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('extension', trans('Extensión').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('extension', null, ['class' => 'form-control', 'v-model' => 'dataForm.extension', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa el código relacionado con este campo.</small>
    </div>
</div>

<div class="form-group row m-b-15">
    {!! Form::label('correo', trans('Correo').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('correo', null, ['class' => 'form-control', 'v-model' => 'dataForm.correo', 'required' => false]) !!}
        <small class="form-text text-muted">Ingresa el correo relacionado con este campo.</small>
    </div>
</div>

<!-- Logo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('logo', trans('Logo').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::file('logo', ['accept' => 'image/*', '@change' => 'inputFile($event, "logo")', 'required' => false]) !!}
            <small>Adjunte el logo de la dependencia. El logo debe tener un tamaño de 90x60 píxeles.</small>
    </div>
</div>

<!-- Dependencia Padre Field -->
<div class="form-group row m-b-15">
    {!! Form::label('id_dependencia_padre', 'Dependencia Padre:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check css-class="form-control" name-field="id_dependencia_padre" reduce-label="nombre" name-resource="get-dependencies" :value="dataForm"></select-check>
        <small class="form-text text-muted">Selecciona la dependencia padre asociada.</small>
    </div>
</div>

<!-- Dependencias Relacionadas Field -->
<div class="form-group row m-b-15">
    {!! Form::label('dependencias_relacionadas', 'Dependencias relacionadas:', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <add-list-autocomplete :value="dataForm" name-prop="nameFalse"
            name-field-autocomplete="dependencias_autocomplete" name-field="dependencias_relacionadas"
            name-resource="get-dependencies"
            name-options-list="dependencias_list" :name-labels-display="['nombre','codigo']" name-key="id" 
            help="Ingrese el nombre de la dependencia en la caja y seleccione para agregar a la lista"
            :key="keyRefresh">
        </add-list-autocomplete>
        <small class="form-text text-muted">Ingresa el nombre de la dependencia en la caja y selecciónala para agregar a la lista.</small>
    </div>
</div>
