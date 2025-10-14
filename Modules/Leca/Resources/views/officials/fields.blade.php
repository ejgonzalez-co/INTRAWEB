<!-- Identification Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('identification_number', trans('Identification Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('identification_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.identification_number }", 'v-model' => 'dataForm.identification_number', 'required' => true]) !!}
        <small>Ingrese el NIT o la cédula del funcionario</small>
        <div class="invalid-feedback" v-if="dataErrors.identification_number">
            <p class="m-b-0" v-for="error in dataErrors.identification_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>Ingrese el nombre del funcionario</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Email Field -->
<div class="form-group row m-b-15">
    {!! Form::label('email', trans('Email').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::email('email', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.email }",'maxlength' => 255,'maxlength' => 255, 'v-model' => 'dataForm.email', 'required' => true]) !!}
        <small>Ingrese el correo del funcionario ej: nombre@gmail.com</small>
        <div class="invalid-feedback" v-if="dataErrors.email">
            <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Telephone Field -->
<div class="form-group row m-b-15">
    {!! Form::label('telephone', 'Telefono'.':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('telephone', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.telephone }", 'v-model' => 'dataForm.telephone', 'required' => false]) !!}
        <small>Ingrese el teléfono del funcionario</small>
        <div class="invalid-feedback" v-if="dataErrors.telephone">
            <p class="m-b-0" v-for="error in dataErrors.telephone">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Direction Field -->
<div class="form-group row m-b-15">
    {!! Form::label('direction', trans('Direction').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('direction', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.direction }", 'v-model' => 'dataForm.direction', 'required' => true]) !!}
        <small>Ingrese la dirección del funcionario</small>
        <div class="invalid-feedback" v-if="dataErrors.direction">
            <p class="m-b-0" v-for="error in dataErrors.direction">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Charge Field -->
<div class="form-group row m-b-15">
    {!! Form::label('charge', trans('Charge').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::select('charge', ['Analista'=>'Analista','Recepcionista'=>'Recepcionista',
        'Personal de Apoyo'=>'Personal de Apoyo'],null,[':class' => "{'form-control':true,'is-invalid':dataErrors.charge }", 'v-model' => 'dataForm.charge', 'required' => true]) !!}
    </div>
</div>

<!-- Functions Field -->
<div class="form-group row m-b-15">
    {!! Form::label('functions', trans('Functions').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('functions', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.functions }", 'v-model' => 'dataForm.functions', 'required' => false]) !!}
        <small>Ingrese las funciones que va a desempeñar el funcionario.</small>
        <div class="invalid-feedback" v-if="dataErrors.functions">
            <p class="m-b-0" v-for="error in dataErrors.functions">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Firm Field -->
<div class="form-group row m-b-15">
    <div class="form-group row m-b-15 mt-4">
        {!! Form::label('firm', 'Adjunte la firma'.':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            {!! Form::file('firm', ['accept' => 'application/pdf', '@change' => 'inputFile($event, "firm")', 'required' => true]) !!}
            <div v-if="dataForm.firm ? dataForm.firm.length > 0 : null"> <label><strong>Adjunto actual:</strong></label><a class="col-9 text-truncate" :href="'{{ asset('storage') }}/'+dataForm.firm" target="_blank">Ver Adjunto</a></div>
            <small>Si desea modificar los adjuntos vuela a dar clic en seleccionar archivo.</small>
        </div>
    </div>
</div>
