<!-- Name Field -->
<div class="form-group row m-b-15">
    {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
        <small>@lang('Enter the') el @{{ `@lang('Name')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.name">
            <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
        </div>
    </div>
</div>

<!--  Unit Time Field -->
<div class="form-group row m-b-15">
    {!! Form::label('unit_time', trans('Unit Time').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="unit_time"
            reduce-label="name"
            reduce-key="id"
            name-resource="get-constants/unit_time"
            :value="dataForm"
            :is-required="true">
        </select-check>
        <label for="unit_time"></label>
        <small>@lang('Select the') la @{{ `@lang('Unit Time')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.unit_time">
            <p class="m-b-0" v-for="error in dataErrors.unit_time">@{{ error }}</p>
        </div>
    </div>
</div>


<!--  Type Term Field -->
<div class="form-group row m-b-15">
    {!! Form::label('type_term', trans('Type Term').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        <select-check
            css-class="form-control"
            name-field="type_term"
            reduce-label="name"
            reduce-key="id"
            name-resource="get-constants/type_term"
            :value="dataForm"
            :is-required="true">
        </select-check>
        <small>@lang('Select the') el @{{ `@lang('Type Term')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.type_term">
            <p class="m-b-0" v-for="error in dataErrors.type_term">@{{ error }}</p>
        </div>
    </div>
</div>


<!-- Term Field -->
<div class="form-group row m-b-15">
    {!! Form::label('term', trans('Application deadline').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('term', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.term }", 'v-model' => 'dataForm.term', 'required' => true]) !!}
        <small>Ingrese en días u horas el plazo de la solicitud.</small>
        <div class="invalid-feedback" v-if="dataErrors.term">
            <p class="m-b-0" v-for="error in dataErrors.term">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Early Field -->
<div class="form-group row m-b-15">
    {!! Form::label('early', trans('Early warning').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::number('early', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.early }", 'v-model' => 'dataForm.early', 'required' => true]) !!}
        <small>Ingrese la cantidad de días u horas que deben transcurrir para que se genere la alerta, el número de días u horas de la alerta temprana debe ser menor o igual al número de días u horas del plazo.</small>
        <div class="invalid-feedback" v-if="dataErrors.early">
            <p class="m-b-0" v-for="error in dataErrors.early">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Description Field -->
<div class="form-group row m-b-15">
    {!! Form::label('description', trans('Description').':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::textarea('description', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.description }", 'v-model' => 'dataForm.description']) !!}
        <small>Ingrese la descripción del tipo de solicitud.</small>
        <div class="invalid-feedback" v-if="dataErrors.description">
            <p class="m-b-0" v-for="error in dataErrors.description">@{{ error }}</p>
        </div>
    </div>
</div>
