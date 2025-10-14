
<!-- Event Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('event_date', trans('Fecha de toma de muestra').':', ['class' => 'col-form-label col-md-3 required']) !!}
      <div class="col-md-4">
          <date-picker
              :value="dataForm"
              name-field="event_date"
              :input-props="{required: true}"
          >
          </date-picker>
          <small>Seleccione la fecha de la toma de  muestra </small>
          <div class="invalid-feedback" v-if="dataErrors.event_date">
              <p class="m-b-0" v-for="error in dataErrors.event_date">@{{ error }}</p>
          </div>
      </div>
  </div>

<!-- tipos de eventos Id Field -->
<div class="form-group row m-b-15">
    {!! Form::label('lc_customers_id', trans('Cliente').':', ['class' => 'col-form-label col-md-3 required']) !!}
    <div class="col-md-4">
            <select-check css-class="form-control" name-field="lc_customers_id" reduce-label="name"
                reduce-key="id" name-resource="get-customer" :value="dataForm" :is-required="true">
            </select-check>
        <small>@lang('Seleccione el') @{{ `@lang('cliente')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.lc_customers_id">
            <p class="m-b-0" v-for="error in dataErrors.lc_customers_id">@{{ error }}</p>
        </div>
    </div>
</div>