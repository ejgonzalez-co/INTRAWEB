@if (Auth::user()->hasRole('Administrador Leca'))
    <!-- Campo de fecha de la toma -->
    <div class="form-group row m-b-15">
        {!! Form::label('sampling_date', trans('Sampling Date') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            <date-picker :value="dataForm" name-field="sampling_date" :input-props="{ required: true }">
            </date-picker>
            <small>Seleccione la fecha de inicio de la rutina mensual</small>
            <div class="invalid-feedback" v-if="dataErrors.sampling_date">
                <p class="m-b-0" v-for="error in dataErrors.sampling_date">@{{ error }}</p>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->hasRole('Analista microbiológico') ||
    Auth::user()->hasRole('Analista fisicoquímico') ||
    Auth::user()->hasRole('Personal de Apoyo') ||
    Auth::user()->hasRole('Recepcionista') ||
    Auth::user()->hasRole('Toma de Muestra'))
    <!-- Campo de fecha de la toma -->
    <div class="form-group row m-b-15">
        {!! Form::label('sampling_date', trans('Sampling Date') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-9">
            <date-picker :value="dataForm" name-field="sampling_date" :input-props="{ required: true }"
                :min-date="new Date()">
            </date-picker>
            <small>Seleccione la fecha de inicio de la rutina mensual</small>
            <div class="invalid-feedback" v-if="dataErrors.sampling_date">
                <p class="m-b-0" v-for="error in dataErrors.sampling_date">@{{ error }}</p>
            </div>
        </div>
    </div>
@endif
<div class="form-group row m-b-15" v-if="dataForm.id >= 0">
    {!! Form::label('lc_sample_points_id', trans('Punto toma de muestra') . ':', [
        'class' => 'col-form-label col-md-3',
    ]) !!}
    <div class="col-md-9">
        {!! Form::text('lc_sample_points.point_location', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.lc_sample_points_id }",
            'v-model' => 'dataForm.lc_sample_points.point_location',
            'readonly' => false,
            'required' => false,
        ]) !!}
        <small>@lang('Enter the') @{{ `@lang('Direction')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_sample_points_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_sample_points_id">@{{ error }}</p>
        </div>
    </div>
</div>
<div v-else class="mt-5">
    <select-list-check name-field="lc_sample_points_id" name-resource="get-points" :value="dataForm"
        label="Seleccione los puntos de muestra"></select-list-check>
</div>

{{-- <!-- Campo punto de la toma -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_sample_points_id', trans('Lc Sample Points Id').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        <select-check
        css-class="form-control"
        name-field="lc_sample_points_id"
        reduce-label="point_location"
        reduce-key="id"
        name-resource="get-points-sampling"
        :value="dataForm"
        :is-required="true">
        </select-check>
        <small>Seleccione el punto de muestra.</small>
    </div>
</div> --}}

<!-- Campo de direccion -->
<div class="form-group row m-b-15" v-if="dataForm.lc_sample_points_id==64">
    {!! Form::label('direction', trans('Direction') . ':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        {!! Form::text('direction', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.direction }",
            'v-model' => 'dataForm.direction',
            'required' => false,
        ]) !!}
        <small>@lang('Enter the') @{{ `@lang('Direction')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.direction">
            <p class="m-b-0" v-for="error in dataErrors.direction">@{{ error }}</p>
        </div>
    </div>
</div>

<div class="form-group row m-b-15 m-5">
    <div class="col">
        {!! Form::label('ensayo', trans('Ensayo a realizar') . ':', ['class' => 'col-form-label']) !!}
    </div>
    <div class="col">
        <div class="col-md-9">
            <input type="checkbox"  name="fisico" id="fisico" v-model="dataForm.fisico">
            {!! Form::label('ensayo', trans('Físico'), ['class' => 'col-form-label col-md-3']) !!}

        </div>
        <div class="col-md-9">
            <input type="checkbox"  name="quimico" id="quimico" v-model="dataForm.quimico">
            {!! Form::label('ensayo', trans('Quimico'), ['class' => 'col-form-label col-md-3']) !!}
        </div>
        <div class="col-md-9">
            <input type="checkbox"  name="microbiologico" id="microbiologico" v-model="dataForm.microbiologico">
            {!! Form::label('ensayo', trans('Microbiológico'), ['class' => 'col-form-label col-md-3']) !!}
        </div>
        <div class="col-md-9">
            <input type="checkbox" name="todos" id="todos" v-model="dataForm.todos">
            {!! Form::label('ensayo', trans('Todos'), ['class' => 'col-form-label col-md-3']) !!}
        </div>
    </div>
    <div class="col"></div>
    <div class="col"></div>
    <div class="invalid-feedback" v-if="dataErrors.fisico">
        <p class="m-b-0" v-for="error in dataErrors.fisico">@{{ error }}</p>
    </div>
</div>

<!-- Campo de funcionario -->
<div class="form-group row m-b-15">
    {!! Form::label('users_id', 'Recurso humano' . ':', ['class' => 'col-form-label col-md-3']) !!}
    <div class="col-md-9">
        @if (Auth::user()->hasRole('Administrador Leca'))
            <autocomplete :is-update="isUpdate" name-prop="name" :value-default="dataForm.users" name-field="users_id"
                :value="dataForm" name-resource="get-officials-sampling" css-class="form-control"
                :name-labels-display="['name']" reduce-key="id" :is-required="false" name-field-object="objet_oficial_sampling">
            </autocomplete>
            <small>Ingrese el nombre del funcionario</small>
        @elseif(Auth::user()->hasRole('Analista fisicoquímico') ||
            Auth::user()->hasRole('Analista microbiológico') ||
            Auth::user()->hasRole('Personal de Apoyo') ||
            Auth::user()->hasRole('Recepcionista') ||
            Auth::user()->hasRole('Toma de Muestra'))
            <input class="form-control" type="text" disabled placeholder="{{ Auth::user()->name }}">
            <input type="hidden" name="users_id" v-model="dataForm.users_id={{ Auth::user()->id }}"
                value="{{ Auth::user()->id }}">
        @endif
    </div>
</div>
<!-- select: Duplicado -->
<div class="form-group row m-b-15">
    {!! Form::label('duplicado', trans('¿ Aplica para duplicado ?') . ':', [
        'class' => 'col-form-label col-md-3 required',
    ]) !!}
    <div class="col-md-9">
        {!! Form::select('duplicado', ['Si' => 'Si', 'No' => 'No'], null, [
            'class' => 'form-control',
            'v-model' => 'dataForm.duplicado',
            'required' => true,
        ]) !!}
        <small>Seleccione si a esta muestra se le hará duplicado.</small>
    </div>
</div>

<!-- Campo de observacion -->
<div class="form-group row m-b-15">
    {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 false']) !!}
    <div class="col-md-9">
        {!! Form::textarea('observation', null, [
            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }",
            'v-model' => 'dataForm.observation',
            'required' => false,
        ]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>
