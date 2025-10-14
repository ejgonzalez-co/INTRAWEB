<!-- Identification Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('identification_number', trans('Número de NIT/Cédula') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {!! Form::number('identification_number', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.identification_number }",
            'v-model' => 'dataForm.identification_number',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.identification_number">
            <p class="m-b-0" v-for="error in dataErrors.identification_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Number Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_number', trans('Número de contrato') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {!! Form::text('contract_number', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.contract_number }",
            'v-model' => 'dataForm.contract_number',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.contract_number">
            <p class="m-b-0" v-for="error in dataErrors.contract_number">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Fullname Field -->
<div class="form-group row m-b-15">
    {!! Form::label('fullname', trans('Nombre') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('fullname', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.fullname }",
            'v-model' => 'dataForm.fullname',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.fullname">
            <p class="m-b-0" v-for="error in dataErrors.fullname">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Email Field -->
<div class="form-group row m-b-15">
    {!! Form::label('email', trans('Correo') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::email('email', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.email }",
            'maxlength' => 40,
            'maxlength' => 40,
            'v-model' => 'dataForm.email',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.email">
            <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Phone Field -->
<div class="form-group row m-b-15">
    {!! Form::label('phone', trans('Celular') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('phone', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.phone }",
            'v-model' => 'dataForm.phone',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.phone">
            <p class="m-b-0" v-for="error in dataErrors.phone">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Address Field -->
<div class="form-group row m-b-15">
    {!! Form::label('address', trans('Address') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('address', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.address }",
            'v-model' => 'dataForm.address',
            'required' => true,
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.address">
            <p class="m-b-0" v-for="error in dataErrors.address">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Contract Start Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_start_date', trans('Fecha de inicio de contrato') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <date-picker :value="dataForm" name-field="contract_start_date" :input-props="{ required: true }">
        </date-picker>
        <div class="invalid-feedback" v-if="dataErrors.contract_start_date">
            <p class="m-b-0" v-for="error in dataErrors.contract_start_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Contract End Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('contract_end_date', trans('Fecha de terminación de contrato: ') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        <date-picker :value="dataForm" name-field="contract_end_date" :input-props="{ required: true }">
        </date-picker>
        <div class="invalid-feedback" v-if="dataErrors.contract_end_date">
            <p class="m-b-0" v-for="error in dataErrors.contract_end_date">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Status Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Estado del contrato') . ':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <input v-if="dataForm.contract_end_date >= dataForm.contract_start_date" :v-model="dataForm.status = 'Activo'" type="text" class="form-control" value="Activo" disabled>
        <input v-else-if="dataForm.contract_end_date < dataForm.contract_start_date" :v-model="dataForm.status = 'Inactivo'" type="text" class="form-control" value="Inactivo" disabled>
        <input v-else type="text" class="form-control" disabled>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Status System Field -->
<div class="form-group row m-b-15">
    {!! Form::label('status', trans('Estado ingreso al sistema') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select class="form-control" v-model="dataForm.status_system" required>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
        <div class="invalid-feedback" v-if="dataErrors.status">
            <p class="m-b-0" v-for="error in dataErrors.status">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Observations Field -->
<div class="form-group row m-b-15">
    {!! Form::label('observations', trans('Observations') . ':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observations', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observations }",
            'v-model' => 'dataForm.observations'
        ]) !!}
        <div class="invalid-feedback" v-if="dataErrors.observations">
            <p class="m-b-0" v-for="error in dataErrors.observations">@{{ error }}</p>
        </div>
    </div>
</div>
