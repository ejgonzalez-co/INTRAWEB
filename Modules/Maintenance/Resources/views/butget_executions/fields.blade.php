<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Información general rubros</b>
        </h6>
    </label>

    <form-execution :name-resource="'get-administration-item/'+dataForm.mant_administration_cost_items_id"
        :value="dataForm" name-field="value_item"></form-execution>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="row">
                {!! Form::label('value_available_actuels', trans('Valor disponible actual') . ':', ['class' => 'col-form-label col-md-6']) !!}
                <div class="col-md-6">
                    <input-data prefix="$ " name-field="value_available_actuels" class-input="form-control"
                        :name-resource="'get-value-avaible/'+dataForm.mant_administration_cost_items_id"
                        :value="dataForm" :key="keyRefresh"></input-data>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row" v-if="dataForm.vueTableComponentInternalRowId != null">
                {{-- {!! Form::label('value_available', trans('Valor disponible durante su registro') . ':', ['class' => 'col-form-label col-md-6']) !!} --}}
                <div class="col-md-6">
                    <input-data prefix="$ " name-field="value_available" class-input="form-control"
                        :name-resource="'get-value-avaible-edit/'+dataForm.id" :value="dataForm" :key="keyRefresh">
                    </input-data>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="panel" style="border: 200px; padding: 15px;">

    <div class="row">
        <div class="col">
            <!-- Minutes Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('minutes', trans('Minutes') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('minutes', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.minutes }", 'v-model' => 'dataForm.minutes', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Minutes')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.minutes">
                        <p class="m-b-0" v-for="error in dataErrors.minutes">@{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="col">
            <!-- Date Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('date', trans('Fecha del acta') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::date('date', null, ['class' => 'form-control', 'id' => 'date', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.date', 'required' => true]) !!}
                    <small>Seleccione la fecha de inicio del acta.</small>
                    <div class="invalid-feedback" v-if="dataErrors.date">
                        <p class="m-b-0" v-for="error in dataErrors.date">@{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- Executed Value Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('executed_value', trans('Executed Value') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input v-model="dataForm.executed_value" required="true" :currency="{'prefix': '$ '}"
                        locale="es" :precision="2" class="form-control" :key="keyRefresh">
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Executed Value')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.executed_value">
                        <p class="m-b-0" v-for="error in dataErrors.executed_value">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- New Value Available Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('new_value_available', trans('New Value Available') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9" v-if="dataForm.vueTableComponentInternalRowId != null">
                    <input-operation ref="operacionResta" name-field="new_value_available"
                        :number-one="parseInt(dataForm.value_available)" :number-two="parseInt(dataForm.executed_value)"
                        :key="dataForm.executed_value  + dataForm.value_available" :value="dataForm" operation="resta"
                        prefix='$ '></input-operation>

                    <small>@lang('Enter the') @{{ `@lang('New Value Available')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.new_value_available">
                        <p class="m-b-0" v-for="error in dataErrors.new_value_available">@{{ error }}
                        </p>
                    </div>
                </div>
                <div class="col-md-9" v-else>
                    <input-operation ref="operacionResta" name-field="new_value_available"
                        :number-one="parseInt(dataForm.value_available_actuels)" :number-two="parseInt(dataForm.executed_value)"
                        :key="dataForm.executed_value  + dataForm.value_available_actuels" :value="dataForm" operation="resta"
                        prefix='$ '></input-operation>

                    <small>@lang('Enter the') @{{ `@lang('New Value Available')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.new_value_available">
                        <p class="m-b-0" v-for="error in dataErrors.new_value_available">@{{ error }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- Percentage Execution Item Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('percentage_execution_item', trans('Percentage Execution Item') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {{-- {!! Form::number('percentage_execution_item',null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.percentage_execution_item }", 'v-model' => 'dataForm.percentage_execution_item=dataForm.executed_value/dataForm.value_available*100', 'required' => true,'disabled' => true,'step' => '0.1']) !!} --}}
                    <input-operation ref="operacionPorcentaje" name-field="percentage_execution_item"
                        :number-one="parseInt(dataForm.value_item)" :number-two="parseInt(dataForm.executed_value)"
                        :key="dataForm.executed_value + dataForm.value_available" :value="dataForm"
                        operation="porcentaje" suffix=" %"></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Percentage Execution Item')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.percentage_execution_item">
                        <p class="m-b-0" v-for="error in dataErrors.percentage_execution_item">
                            @{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="col">

        </div>
    </div>

</div>
<div class="panel" style="border: 200px; padding: 15px;">
    <!-- Observation Field -->
    <div class="form-group row m-b-15">
        {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
        <div class="col-md-7">
            {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }", 'v-model' => 'dataForm.observation', 'required' => true]) !!}
            <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
            <div class="invalid-feedback" v-if="dataErrors.observation">
                <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
            </div>
        </div>
    </div>
</div>


@push('css')
    {!! Html::style('assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') !!}
@endpush

@push('scripts')
    {!! Html::script('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') !!}
    <script>
        $('#date').datepicker({
            todayHighlight: true
        });
    </script>
@endpush
