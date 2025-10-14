<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Type Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type', trans('Type').':', ['class' => 'col-form-label col-md-3 required']) !!}
    
    <div class="col-md-9">
        <select v-model="dataForm.type" class="form-control" required>
            <option value="Normal">Normal</option>
            <option value="Roles">Roles</option>
        </select>
        <small>Seleccione el tipo de variable</small>
    </div>
</div>

<!-- Value Field -->
<div class="form-group row m-b-15" v-if="dataForm.type=='Roles'">
    {!! Form::label('value', 'Valor:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="value"
            reduce-label="name"
            reduce-key="name"
            name-resource="/intranet/get-roles"
            :value="dataForm"
            :is-required="true">
        </select-check>
    </div>
</div>

<!-- Value Field -->
<div class="form-group row m-b-15" v-if="dataForm.type=='Normal'">
    {!! Form::label('value', 'Valor:', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('value', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.value }", 'v-model' => 'dataForm.value', 'required' => true]) !!}
        <div class="invalid-feedback" v-if="dataErrors.value">
            <p class="m-b-0" v-for="error in dataErrors.value">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Description')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>
