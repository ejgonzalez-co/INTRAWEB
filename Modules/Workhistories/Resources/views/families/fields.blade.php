
<!-- Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
    <select v-model="dataForm.type" name="type" required="required" class="form-control">  
        <option value="">Seleccione</option>
        <option value="Conyugue">Conyugue</option>
        <option value="Hijo(a)">Hijo(a)</option>        
    </select>
    
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.name', 'required' => true]) !!}
    </div>
</div>

<!-- Gender Field -->
<div class="form-group row m-b-15">
    {!! Form::label('gender', trans('Gender').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">

    <select v-model="dataForm.gender" name="gender" required="required" class="form-control">  
        <option value="">Seleccione</option>
        <option value="Hombre">Hombre</option>
        <option value="Mujer">Mujer</option>
        <option value="Indefinido">Indefinido</option>
        
    </select>
    <small>Seleccione el genéro del familiar</small>
    </div>
</div>

<!-- Birth Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('birth_date', trans('Birth Date').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::date('birth_date', null, ['class' => 'form-control', 'id' => 'birth_date',
        'placeholder' => 'Select Date', 'v-model' => 'dataForm.birth_date', 'required' => true]) !!}
        <small>Seleccione la fecha de nacimiento del familiar</small>
    </div>
</div>


<div class="form-group row m-b-15"  v-if="isUpdate">
    {!! Form::label('state', trans('State').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">

    <select v-model="dataForm.state" name="state" class="form-control">  
        <option value="">Seleccione</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
    </select>
    </div>
</div>


<div class="form-group row m-b-15" v-if="dataForm.state=='Inactivo' && isUpdate">
    {!! Form::label('observation', trans('Observation').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
    {!! Form::text('observation', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.observation']) !!}
    </div>
</div>



