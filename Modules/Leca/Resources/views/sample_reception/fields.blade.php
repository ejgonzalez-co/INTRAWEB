<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Proceso</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            {!! Form::label('created_at', 'Fecha de creación de la toma de muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <date-picker
                :value="dataForm"
                name-field="created_at"                
                :max-date="dataForm.created_at" 
                :min-date="dataForm.created_at"             
                >
                </date-picker>                                            
                {{-- <input type="text" v-model="dataForm.created_at" class="form-control" enable> --}}
                <small>Fecha de toma de muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.created_at">
                    <p class="m-b-0" v-for="error in dataErrors.created_at">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('hour_from_to', 'Hora de la toma de la muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('hour_from_to', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.hour_from_to }", 'v-model' => 'dataForm.hour_from_to', 'disabled','required' => true]) !!}
                {{-- <input type="text" v-model="dataForm.hour_from_to" class="form-control" enable> --}}
                <small>Hora de la toma de muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.hour_from_to">
                    <p class="m-b-0" v-for="error in dataErrors.hour_from_to">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('reception_date', 'Fecha de la recepción de la muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <date-picker
                :value="dataForm"
                name-field="reception_date"                
                :max-date="dataForm.reception_date" 
                :min-date="dataForm.reception_date"             
                >
                </date-picker>     
                {{-- {!! Form::text('reception_date', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reception_date }", 'v-model' =>'dataForm.reception_date' , 'disabled','required' => true]) !!} --}}
                {{-- <input type="text" v-model="dataForm.reception_date" class="form-control" enable> --}}
                <small>Fecha de la recepcion de la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.reception_date">
                    <p class="m-b-0" v-for="error in dataErrors.reception_date">@{{ error }}</p>
                </div>
            </div>


            {!! Form::label('reception_hour', 'Hora de la recepción de la muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <hour-military
                    name-field="reception_hour"
                    :value="dataForm" >
                </hour-military>
                <small>Hora de la recepcion de la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.reception_hour">
                    <p class="m-b-0" v-for="error in dataErrors.reception_hour">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Información general del responsable de la toma</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            {!! Form::label('sample_reception_code', 'Identificación asignada por LECA' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('sample_reception_code', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.sample_reception_code }", 'v-model' => 'dataForm.sample_reception_code', 'disabled','required' => true]) !!}
                <small>Identificación de la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.sample_reception_code">
                    <p class="m-b-0" v-for="error in dataErrors.sample_reception_code">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('type_water', 'Tipo de agua' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('type_water', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_water }", 'v-model' => 'dataForm.type_water', 'disabled','required' => true]) !!}
                <small>Tipo de agua.</small>
                <div class="invalid-feedback" v-if="dataErrors.type_water">
                    <p class="m-b-0" v-for="error in dataErrors.type_water">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('user_name', 'Responsable de la entrega de la muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('user_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.user_name }", 'v-model' => 'dataForm.user_name', 'disabled','required' => true]) !!}
                <small>Nombre del responsable de entrega de la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.user_name">
                    <p class="m-b-0" v-for="error in dataErrors.user_name">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('type_water', 'Firma' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <img width="100" class="img-responsive" v-if="dataForm.users.url_digital_signature" :src="'{{ asset('storage') }}/'+dataForm.users.url_digital_signature" alt="">
            </div>
        </div>
    </div>
</div>


<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Parámetros determinados en campo</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            {!! Form::label('cloro_promedio', 'Cloro' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('cloro_promedio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.cloro_promedio }", 'v-model' => 'dataForm.cloro_promedio', 'disabled','required' => true]) !!}
                <small>Promedio del cloro.</small>
                <div class="invalid-feedback" v-if="dataErrors.cloro_promedio">
                    <p class="m-b-0" v-for="error in dataErrors.cloro_promedio">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('ph_promedio', 'pH' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('ph_promedio', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.ph_promedio }", 'v-model' => 'dataForm.ph_promedio', 'disabled','required' => true]) !!}
                <small>Promedio del pH.</small>
                <div class="invalid-feedback" v-if="dataErrors.ph_promedio">
                    <p class="m-b-0" v-for="error in dataErrors.ph_promedio">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('turbidez', 'NTU' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('turbidez', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.turbidez }", 'v-model' => 'dataForm.turbidez', 'disabled','required' => true]) !!}
                
                <small>Valor de la turbidez.</small>
                <div class="invalid-feedback" v-if="dataErrors.turbidez">
                    <p class="m-b-0" v-for="error in dataErrors.turbidez">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('conductivity_reception', 'μS/cm' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {{-- {!! Form::text('conductivity_reception', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.conductivity_reception }", 'v-model' => 'dataForm.conductivity_reception','required' => true]) !!} --}}
                <currency-input
                    :required="false"
                    v-model="dataForm.conductivity_reception"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el valor del la conductividad.</small>
                <div class="invalid-feedback" v-if="dataErrors.conductivity_reception">
                    <p class="m-b-0" v-for="error in dataErrors.conductivity_reception">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('other_reception', 'Otro' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {{-- {!! Form::text('other_reception', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.other_reception }", 'v-model' => 'dataForm.other_reception','required' => true]) !!} --}}
                <currency-input
                    :required="false"
                    v-model="dataForm.other_reception"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el valor de otros parámetros.</small>
                <div class="invalid-feedback" v-if="dataErrors.other_reception">
                    <p class="m-b-0" v-for="error in dataErrors.other_reception">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Recipientes con muestras</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            {!! Form::label('type_receipt', 'Tipo' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::select('type_receipt',['Vidrio'=>'Vidrio','Plástico'=>'Plástico','Vidrio y Plástico'=>'Vidrio y Plástico'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_receipt }", 'v-model' => 'dataForm.type_receipt','required' => true]) !!}
                <small>Seleccione el tipo de envase.</small>
                <div class="invalid-feedback" v-if="dataErrors.type_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.type_receipt">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('volume_liters', 'Volumen litros' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
              {{-- {!! Form::text('volume_liters', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.volume_liters }", 'v-model' => 'dataForm.volume_liters','required' => true]) !!} --}}
            <currency-input
                required="true"
                v-model="dataForm.volume_liters"
                :currency="{'suffix': ''}"
                locale="es"
                :precision="2"
                class="form-control"
                :key="keyRefresh"
                >
            </currency-input>
                <small>Ingrese el volumen en litros de la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.volume_liters">
                    <p class="m-b-0" v-for="error in dataErrors.volume_liters">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('requested_parameters', 'Parámetros solicitados' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::textarea('requested_parameters', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.requested_parameters }", 'v-model' => 'dataForm.requested_parameters','disabled','required' => true]) !!}
                <small>Parámetros solicitados.</small>
                <div class="invalid-feedback" v-if="dataErrors.requested_parameters">
                    <p class="m-b-0" v-for="error in dataErrors.requested_parameters">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('persevering addition', 'Adición de preservante' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::textarea('persevering_addiction', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.persevering_addiction }", 'v-model' => 'dataForm.persevering_addiction','disabled','required' => true]) !!}
                <small>Ingrese el valor de la adición de preservante.</small>
                <div class="invalid-feedback" v-if="dataErrors.persevering_addiction">
                    <p class="m-b-0" v-for="error in dataErrors.persevering_addiction">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Estado de muestra recepcionada</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            <label v-if="dataForm.lc_start_sampling.type_customer == 'Captacion'" class="col-form-label col-md-2">T°C(inicial):</label>
            <label v-else class="col-form-label col-md-2">T°C(inicial): *</label>
            <div class="col-md-4" v-if="dataForm.lc_start_sampling.type_customer == 'Captacion'">
                <currency-input
                    v-model="dataForm.t_initial_receipt"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el T°C(inicial).</small>
                <div class="invalid-feedback" v-if="dataErrors.t_initial_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.t_initial_receipt">@{{ error }}</p>
                </div>
            </div>
            <div class="col-md-4" v-else>
                <currency-input
                    required="true"
                    v-model="dataForm.t_initial_receipt"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el T°C(inicial).</small>
                <div class="invalid-feedback" v-if="dataErrors.t_initial_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.t_initial_receipt">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('t_final_receipt', 'T°C(final)' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {{-- {!! Form::text('t_final_receipt', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.t_final_receipt }", 'v-model' => 'dataForm.t_final_receipt','required' => true]) !!} --}}
                <currency-input
                    required="true"
                    v-model="dataForm.t_final_receipt"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                </currency-input>
                <small>Ingrese el T°C(final).</small>
                <div class="invalid-feedback" v-if="dataErrors.t_final_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.t_final_receipt">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('according_receipt', 'Conforme' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::select('according_receipt',['Si'=>'Si','No'=>'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.according_receipt }", 'v-model' => 'dataForm.according_receipt','required' => true]) !!}
                <small>Seleccione si esta conforme.</small>
                <div class="invalid-feedback" v-if="dataErrors.according_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.according_receipt">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('observation_receipt', 'Observaciones' . ':', ['class' => 'col-form-label col-md-2 required' ]) !!}
            <div class="col-md-4">
                {!! Form::textarea('observation_receipt', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation_receipt }", 'v-model' => 'dataForm.observation_receipt','rows'=>'2','required' => true]) !!}
                <small>Ingrese alguna observación.</small>
                <div class="invalid-feedback" v-if="dataErrors.observation_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.observation_receipt">@{{ error }}</p>
                </div>
            </div>
        </div>
        <div class="form-group row m-b-15">
            {!! Form::label('sample_characteristics', trans('Sample Characteristics') . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::select('sample_characteristics',['MR'=>'MR','S'=>'S','ME'=>'ME'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.sample_characteristics }", 'v-model' => 'dataForm.sample_characteristics','required' => true]) !!}
                <small>Seleccione las características de la muestra</small>
                <div class="invalid-feedback" v-if="dataErrors.sample_characteristics">
                    <p class="m-b-0" v-for="error in dataErrors.sample_characteristics">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Información general de la recepción</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            {!! Form::label('is_accepted', '¿Se acepta?' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::select('is_accepted',['Si'=>'Si','No'=>'No'], null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.is_accepted }", 'v-model' => 'dataForm.is_accepted','required' => true]) !!}
                <small>Seleccione si se acepta la muestra.</small>
                <div class="invalid-feedback" v-if="dataErrors.is_accepted">
                    <p class="m-b-0" v-for="error in dataErrors.is_accepted">@{{ error }}</p>
                </div>
            </div>

            {!! Form::label('name_receipt', 'Responsable de recepción de muestra' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                {!! Form::text('name_receipt', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_receipt }", 'v-model' => 'dataForm.name_receipt','disabled','required' => true]) !!}
                <small>Nombre del responsable.</small>
                <div class="invalid-feedback" v-if="dataErrors.name_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.name_receipt">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            {!! Form::label('url_receipt', 'Firma' . ':', ['class' => 'col-form-label col-md-2']) !!}
            <div class="col-md-4">
                <img width="100" class="img-responsive" v-if="dataForm.users.url_digital_signature" :src="'{{ asset('storage') }}/'+dataForm.url_receipt" alt="">
            </div>

            <label  class="col-form-label col-md-2">Justificación</label>
            <div  class="col-md-4">
                {!! Form::textarea('justification_receipt', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.justification_receipt }", 'v-model' => 'dataForm.justification_receipt','rows'=>'4']) !!}
                <small>Ingrese la justificación del por que es aceptada la muestra para análisis.</small>
                <div class="invalid-feedback" v-if="dataErrors.justification_receipt">
                    <p class="m-b-0" v-for="error in dataErrors.justification_receipt">@{{ error }}</p>
                </div>
            </div>

            <input type="text" v-model="dataForm.validation" hidden>
            <input type="text" v-model="dataForm.state_receipt" hidden>
        </div>
    </div>
</div>

<div v-if="dataForm.editHistory == '1'"  class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Justificación de edición</h5>
    <div style="padding: 15px">
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-2">Justificación de edición</label>
            <div class="col-md-4">
                {!! Form::textarea('observations_edit', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observations_edit }", 'v-model' => 'dataForm.observations_edit','rows'=>'4','required' => true]) !!}
                <small>Ingrese la justificación del por que se edito el registro.</small>
                <div class="invalid-feedback" v-if="dataErrors.observations_edit">
                    <p class="m-b-0" v-for="error in dataErrors.observations_edit">@{{ error }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel" style="border-radius: 10px; padding: 10px" data-soportable-id="ui-general-1">
    <h5>Notas</h5>
    <div style="padding: 15px">
        <table class="text-left default" style="width:100%; table-layout: fixed;" border="1">
            <tr>
                <td>(1)	cruda ( C), tratada (T), de proceso (P)(aguas clarificadas o filtradas).</td>
                <td>(6)	T inicial; aplica para muestras con cadena de frío.</td>
            </tr>
            <tr>
                <td>(2)	NTU = turbiedad, µS/cm= conductividad, otros= temperatura, OD.</td>
                <td>(7)	T de llegada; aplica para muestras con cadena de frío y almacenadas.</td>
            </tr>
            <tr>
                <td>(3)	Vidrio ( V), Plástico (P)</td>
                <td>(8)	Conforme. De acuerdo a criterios relacionados en el procedimiento gestión de muestras.</td>
            </tr>
            <tr>
                <td>(4)	Parámetros relacionados en listas</td>
                <td>(9)	No Conforme.  De acuerdo a criterios relacionados en el procedimiento gestión de muestras.</td>
            </tr>
            <tr>
                <td>(5)	Ácidos (A), bases (B), tiosulfato de sodio(TS), otros ( O) Ver listas.</td>
                <td>(10)Observaciones: todo evento que le suceda al ítem de muestreo, solicitudes de clientes, no contemplados R-047.</td>
            </tr>
        </table>
    </div>
</div>