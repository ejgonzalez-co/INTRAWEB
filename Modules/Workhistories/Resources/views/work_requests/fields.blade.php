<div class="row">
    <div class="col">
        <div class="form-group row m-b-15">
            {!! Form::label('option', trans('Seleccione una opción') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                {!! Form::select('option', ['1' => 'Hojas de vida activas', '2' => 'Hojas de vida pensionados'], null, ['class' => 'form-control', 'v-model' => 'dataForm.option']) !!}
                <small>Seleccione que hojas de vida va consultar</small>
            </div>
        </div>
    </div>
    <div class="col">
        <!-- User Id Field -->
        <div v-if="dataForm.option==1" class="form-group row m-b-15">
            {!! Form::label('user_id', trans('Documento identidad') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete :min-text-input="1" name-prop="number_document" name-field="user_id" :value='dataForm'
                    name-resource="get-users-work" css-class="form-control"
                    :name-labels-display="['name','surname','dependencias_name']" reduce-key="id" :key="keyRefresh" name-field-object="ciudadano">
                </autocomplete>
                <small>@lang('Enter the') la cédula del ciudadano</small>
                <div class="invalid-feedback" v-if="dataErrors.user_id">
                    <p class="m-b-0" v-for="error in dataErrors.user_id">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div v-if="dataForm.option==2" class="form-group row m-b-15">
            {!! Form::label('user_id', trans('Documento identidad') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
            <div class="col-md-9">
                <autocomplete :min-text-input="1" name-prop="number_document" name-field="user_id" :value='dataForm'
                    name-resource="get-pensioner-work" css-class="form-control"
                    :name-labels-display="['name','surname','dependencias_name']" reduce-key="id" :key="keyRefresh">
                </autocomplete>
                <small>@lang('Enter the') la cédula del ciudadano</small>
                <div class="invalid-feedback" v-if="dataErrors.user_id">
                    <p class="m-b-0" v-for="error in dataErrors.user_id">@{{ error }}</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Consultation Time Field -->
<div class="form-group row m-b-15">
    {!! Form::label('consultation_time', trans('Tiempo solicitado de consulta') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::text('consultation_time', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consultation_time }", 'v-model' => 'dataForm.consultation_time', 'required' => true]) !!}
        <small>Ingrese de manera textual  el tiempo solicitado, ej: 6 horas, 1 día, 1 semana, etc…</small>
        <div class="invalid-feedback" v-if="dataErrors.consultation_time">
            <p class="m-b-0" v-for="error in dataErrors.consultation_time">@{{ error }}</p>
        </div>
    </div>
</div>



<!-- Reason Consultation Field -->
<div class="form-group row m-b-15 mt-3">

    {!! Form::label('reason_consultation', trans('Motivo de consulta de la hoja de vida') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-9">
        {!! Form::textarea('reason_consultation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reason_consultation }", 'v-model' => 'dataForm.reason_consultation', 'required' => true]) !!}
        <small>@lang('Enter the') el motivo de la consulta</small>
        <div class="invalid-feedback" v-if="dataErrors.reason_consultation">
            <p class="m-b-0" v-for="error in dataErrors.reason_consultation">@{{ error }}</p>
        </div>
    </div>
</div>
