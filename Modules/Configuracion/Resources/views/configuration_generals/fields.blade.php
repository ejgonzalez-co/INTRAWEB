<!-- NNombre Entidad Field -->
<div class="form-group row m-b-15">
    {!! Form::label('nombre_entidad', trans('Nombre de la entidad').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-7">
        {!! Form::text('nombre_entidad', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.nombre_entidad }", 'v-model' => 'dataForm.nombre_entidad', 'required' => true]) !!}
        <small>Ingrese el nombre de la entidad.</small>
    </div>
</div>

<!-- Logo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('logo', trans('Logo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('logo', ['accept' => 'image/*', '@change' => 'inputFile($event, "logo")', 'required' => false]) !!}
            <small>Adjunte el logo de su entidad en la esquina superior izquierda de la página. El logo debe tener un tamaño de 90x60 píxeles.</small>
    </div>
</div>

<!-- Color Barra Field -->
<div class="form-group row m-b-15">
    {!! Form::label('color_barra', trans('Color Barra').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <colour-picker
        name-field="color_barra"
        :value="dataForm"
        ></colour-picker>
    </div>
</div>


<!-- Imagen Fondo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('imagen_fondo', trans('Imagen Fondo').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('imagen_fondo', ['accept' => 'image/*', '@change' => 'inputFile($event, "imagen_fondo")', 'required' => false]) !!}

    </div>
</div>

<!-- Imagen Fondo Field -->
<div class="form-group row m-b-15">
    {!! Form::label('imagen_fondo_responsive', trans('Imagen Fondo para pantallas mas pequeñas').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::file('imagen_fondo_responsive', ['accept' => 'image/*', '@change' => 'inputFile($event, "imagen_fondo_responsive")', 'required' => false]) !!}

    </div>
</div>




<!-- NNombre Entidad Field -->
<div class="form-group row m-b-15">
    {!! Form::label('horario', 'Información de horario.', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-7">
        {!! Form::text('horario', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.horario }", 'v-model' => 'dataForm.horario', 'required' => true]) !!}
        <small>Ingrese la información de los hnformación de horarios de la entidad..</small>
    </div>
</div>


