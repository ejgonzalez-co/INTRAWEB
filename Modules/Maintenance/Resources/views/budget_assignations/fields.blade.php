<!-- Value Cdp Field -->
<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Información general</b>
        </h6>
    </label>

    <div class="row">
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('value_cdp', trans('Value Cdp') . ':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6">
                   
                    <currency-input
                    v-model="dataForm.value_cdp"
                    required="true"
                    :currency="{'prefix': '$ '}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Value Cdp')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.value_cdp">
                        <p class="m-b-0" v-for="error in dataErrors.value_cdp">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Consecutive Cdp Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('consecutive_cdp', trans('Consecutive Cdp') . ':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6">
                    {!! Form::text('consecutive_cdp', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.consecutive_cdp }", 'v-model' => 'dataForm.consecutive_cdp', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Consecutive Cdp')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.consecutive_cdp">
                        <p class="m-b-0" v-for="error in dataErrors.consecutive_cdp">@{{ error }}</p>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="row">
        <div class="col">

            <!-- Value Contract Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('value_contract', trans('Value Contract') . ':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6">
                    <currency-input
                    v-model="dataForm.value_contract"
                    required="true"
                    :currency="{'prefix': '$ '}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Value Contract')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.value_contract">
                        <p class="m-b-0" v-for="error in dataErrors.value_contract">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Cdp Available Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('cdp_available', trans('Cdp Available') . ':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6">
                    <input-operation 
                    name-field="cdp_available"
                    :number-one="dataForm.value_cdp"
                    :number-two="dataForm.value_contract"
                    :key="dataForm.value_contract + dataForm.value_cdp"
                    :value="dataForm"                    
                    operation="resta"
                    prefix='$ '
                    ></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Cdp Available')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.cdp_available">
                        <p class="m-b-0" v-for="error in dataErrors.cdp_available">@{{ error }}</p>
                    </div>
                </div>
            </div>

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

    <!-- attached Field -->
    <div class="form-group row m-b-15">
        {!! Form::label('attachment', 'Adjuntos:', ['class' => 'col-form-label col-md-3']) !!}
        <div class="col-md-7">
            <input-file 
                :value="dataForm" 
                name-field="attachment" 
                :max-files="4"
                :max-filesize="6" 
                file-path="public/maintenance/documents_provider_contract"
                message="Arrastre o seleccione los archivos" 
                help-text="Lista de archivos adjuntos."
                :is-update="isUpdate" 
                :key="keyRefresh">
            </input-file>
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
