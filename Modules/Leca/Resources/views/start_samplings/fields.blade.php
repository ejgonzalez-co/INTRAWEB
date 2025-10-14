<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Tipo de nuestra</strong></h4>
        </div>
        <br>
        <div class="col-md-6">
            <!-- Service Agreement Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('type_customer', 'Seleccione el tipo de cliente para este muestreo'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select name="select" class="form-control" v-model="dataForm.type_customer" required>
                        <option value="Distribucion">Distribución</option>
                        <option value="Captacion">Captación</option>
                    </select>
                    {{-- {!! Form::text('type_customer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.type_customer }", 'v-model' => 'dataForm.type_customer', 'required' => true]) !!} --}}
                    <small>Seleccione el tipo de cliente para este muestreo</small>
                    <div class="invalid-feedback" v-if="dataErrors.type_customer">
                        <p class="m-b-0" v-for="error in dataErrors.type_customer">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
       

     
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Proceso: Laboratorio de ensayo de calidad de agua</strong></h4>
        </div>
        <br>
        <div  class="row">
            <div class="col-md-6" v-if="isUpdate">
                <!-- Vehicle Arrival Time Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('vehicle_arrival_time', trans('Vehicle Arrival Time').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {{-- <input class="form-control" type="time" name="name" v-model="dataForm.vehicle_arrival_time" disabled> --}}


                        <hour-military
                        style="margin-top: 10px"
                        name-field="vehicle_arrival_time"
                        :value="dataForm"
                        :ceros="true"
                        :disabled="true"
                        >
                        </hour-military>


                        <small>@lang('Enter the') @{{ `@lang('Vehicle Arrival Time')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.vehicle_arrival_time">
                            <p class="m-b-0" v-for="error in dataErrors.vehicle_arrival_time">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" v-else>
                <!-- Vehicle Arrival Time Field -->
                <div v-if="dataForm.type_customer == 'Distribucion'" class="form-group row m-b-15">
                    {!! Form::label('vehicle_arrival_time', trans('Vehicle Arrival Time').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <hour-military
                        style="margin-top: 10px"
                        name-field="vehicle_arrival_time"
                        :value="dataForm"
                        :ceros="true"
                        >
                        </hour-military>
                        <small>@lang('Enter the') @{{ `@lang('Vehicle Arrival Time')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.vehicle_arrival_time">
                            <p class="m-b-0" v-for="error in dataErrors.vehicle_arrival_time">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Leca Outlet Field -->
                <div v-if="dataForm.type_customer == 'Distribucion'"  class="form-group row m-b-15">
                    {!! Form::label('leca_outlet', trans('Leca Outlet').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <hour-military
                        style="margin-top: 10px"
                        name-field="leca_outlet"
                        :value="dataForm"
                        :ceros="true"
                        >
                        </hour-military>
                        <small>@lang('Enter the') @{{ `@lang('Leca Outlet')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.leca_outlet">
                            <p class="m-b-0" v-for="error in dataErrors.leca_outlet">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="dataForm.type_customer == 'Distribucion'" class="row">
            <div  class="col-md-6">
                <!-- Time Sample Completion Field -->
                <div  class="form-group row m-b-15">
                    {!! Form::label('time_sample_completion', trans('Time Sample Completion').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <hour-military
                        style="margin-top: 20px"
                        name-field="time_sample_completion"
                        :value="dataForm">
                        </hour-military>
                        <small>@lang('Enter the') @{{ `@lang('Time Sample Completion')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.time_sample_completion">
                            <p class="m-b-0" v-for="error in dataErrors.time_sample_completion">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div  class="col-md-6">
                <!-- Service Agreement Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('service_agreement', trans('Service Agreement').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.service_agreement">
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        {{-- {!! Form::text('service_agreement', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.service_agreement }", 'v-model' => 'dataForm.service_agreement', 'required' => true]) !!} --}}
                        <small>Ingrese acuerdo de servicio</small>
                        <div class="invalid-feedback" v-if="dataErrors.service_agreement">
                            <p class="m-b-0" v-for="error in dataErrors.service_agreement">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       
        
        <div v-if="dataForm.type_customer == 'Distribucion'" class="row">
            <div class="col-md-6">
                <!-- Sample Request Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('sample_request', trans('Sample Request').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.sample_request">
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        {{-- {!! Form::text('sample_request', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.sample_request }", 'v-model' => 'dataForm.sample_request', 'required' => false]) !!} --}}
                        <small>@lang('Enter the') @{{ `@lang('Sample Request')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.sample_request">
                            <p class="m-b-0" v-for="error in dataErrors.sample_request">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Time Field -->
                <div  class="form-group row m-b-15">
                    <label class="col-md-3">Hora</label>
                    <div class="col-md-9">
                        <hour-military
                        name-field="time"
                        :value="dataForm">
                        </hour-military>
                        <small>Ingrese la hora en la que se realizara la toma</small>
                        <div class="invalid-feedback" v-if="dataErrors.time">
                            <p class="m-b-0" v-for="error in dataErrors.time">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Name Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('name', trans('Name_sample_taking').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <input class="form-control" type="text" disabled  placeholder="{{ Auth::user()->name }}">
                        <input type="hidden" name="name" v-model="dataForm.name={{ Auth::user()->id }}" value="{{ Auth::user()->id }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Equipos</strong></h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Environmental Conditions Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('environmental_conditions', trans('Environmental Conditions').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="switcher">
                            <input type="checkbox" name="environmental_conditions" id="environmental_conditions" v-model="dataForm.environmental_conditions" >
                            <label for="environmental_conditions"></label>
                        </div>
                        <div class="invalid-feedback" v-if="dataErrors.environmental_conditions">
                            <p class="m-b-0" v-for="error in dataErrors.environmental_conditions">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Referencia  -->
                <div class="form-group row m-b-15" v-if="dataForm.environmental_conditions">
                    {!! Form::label('reference_thermohygrometer', trans('reference_thermohygrometer').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('reference_thermohygrometer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reference_thermohygrometer }", 'v-model' => 'dataForm.reference_thermohygrometer', 'required' => false]) !!}
                        <small>ingrese el código de referencia del equipo ejemplo: 145 </small>
                        <div class="invalid-feedback" v-if="dataErrors.reference_thermohygrometer">
                            <p class="m-b-0" v-for="error in dataErrors.reference_thermohygrometer">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Potentiometer Multiparameter Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('potentiometer_multiparameter', trans('Potentiometer Multiparameter').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="switcher">
                            <input type="checkbox" name="potentiometer_multiparameter" id="potentiometer_multiparameter" v-model="dataForm.potentiometer_multiparameter" >
                            <label for="potentiometer_multiparameter"></label>
                        </div>
                        <div class="invalid-feedback" v-if="dataErrors.potentiometer_multiparameter">
                            <p class="m-b-0" v-for="error in dataErrors.potentiometer_multiparameter">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Referencia -->
                <div class="form-group row m-b-15" v-if="dataForm.potentiometer_multiparameter">
                    {!! Form::label('reference_multiparameter', trans('reference_multiparameter').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('reference_multiparameter', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reference_multiparameter }", 'v-model' => 'dataForm.reference_multiparameter', 'required' => false]) !!}
                        <small>ingrese el código de referencia del equipo ejemplo: 145 </small>
                        <div class="invalid-feedback" v-if="dataErrors.reference_multiparameter">
                            <p class="m-b-0" v-for="error in dataErrors.reference_multiparameter">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Chlorine Residual Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('chlorine_residual', trans('Chlorine Residual').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="switcher">
                            <input type="checkbox" name="chlorine_residual" id="chlorine_residual" v-model="dataForm.chlorine_residual" >
                            <label for="chlorine_residual"></label>
                        </div>
                        <div class="invalid-feedback" v-if="dataErrors.chlorine_residual">
                            <p class="m-b-0" v-for="error in dataErrors.chlorine_residual">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Referencia -->
                <div class="form-group row m-b-15" v-if="dataForm.chlorine_residual">
                    {!! Form::label('reference_photometer', trans('reference_photometer').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('reference_photometer', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reference_photometer }", 'v-model' => 'dataForm.reference_photometer', 'required' => false]) !!}
                        <small>ingrese el código de referencia del equipo ejemplo: 145 </small>
                        <div class="invalid-feedback" v-if="dataErrors.reference_photometer">
                            <p class="m-b-0" v-for="error in dataErrors.reference_photometer">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Turbidimeter Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('turbidimeter', trans('Turbidimeter').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <div class="switcher">
                            <input type="checkbox" name="turbidimeter" id="turbidimeter" v-model="dataForm.turbidimeter" >
                            <label for="turbidimeter"></label>
                        </div>
                        <div class="invalid-feedback" v-if="dataErrors.turbidimeter">
                            <p class="m-b-0" v-for="error in dataErrors.turbidimeter">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Referencia -->
                <div class="form-group row m-b-15" v-if="dataForm.turbidimeter">
                    {!! Form::label('reference_turbidimeter', trans('reference_turbidimeter').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('reference_turbidimeter', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.reference_turbidimeter }", 'v-model' => 'dataForm.reference_turbidimeter', 'required' => false]) !!}
                        <small>ingrese el código de referencia del equipo ejemplo: 145 </small>
                        <div class="invalid-feedback" v-if="dataErrors.reference_turbidimeter">
                            <p class="m-b-0" v-for="error in dataErrors.reference_turbidimeter">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Another Test Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('another_test', trans('Another Test').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('another_test', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.another_test }", 'v-model' => 'dataForm.another_test', 'required' => false]) !!}
                        <small>Ingrese el nombre del nuevo ensayo.</small>
                        <div class="invalid-feedback" v-if="dataErrors.another_test">
                            <p class="m-b-0" v-for="error in dataErrors.another_test">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Other Equipment Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('other_equipment', trans('Other Equipment').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('other_equipment', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.other_equipment }", 'v-model' => 'dataForm.other_equipment', 'required' => false]) !!}
                        <small>Ingrese el nombre del nuevo equipo.</small>
                        <div class="invalid-feedback" v-if="dataErrors.other_equipment">
                            <p class="m-b-0" v-for="error in dataErrors.other_equipment">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Fórmula</strong></h4>
        </div>

        <div style="padding: 30px">
            <img style="margin-left: 250px" src="{{ asset('assets/img/default/formula_leca.png')}}" width="500" height="200" class="w-55 p-10"/>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Chlorine Test Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('chlorine_test', trans('Chlorine Test').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <currency-input
                        v-model="dataForm.chlorine_test"
                        :currency="{'prefix': ''}"
                        locale="es"
                        :precision="6"
                        class="form-control"
                        :key="keyRefresh"
                        >
                        </currency-input>
                        <small>Ingrese el valor del ensayo de cloro concentración FAS n(eq-g/l).</small>
                        <div class="invalid-feedback" v-if="dataErrors.chlorine_test">
                            <p class="m-b-0" v-for="error in dataErrors.chlorine_test">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Factor Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('factor', trans('Factor_calculate').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <currency-input
                            v-model="dataForm.factor"
                            :currency="{'prefix': ''}"
                            locale="es"
                            :precision="6"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        <small>Ingrese el valor del factor o cálculo.</small>
                        <div class="invalid-feedback" v-if="dataErrors.factor">
                            <p class="m-b-0" v-for="error in dataErrors.factor">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Residual Color Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('residual_color', trans('Residual_Color_digital').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {{-- {!! Form::text('residual_color', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.residual_color }", 'v-model' => 'dataForm.residual_color', 'required' => false]) !!} --}}
                        <currency-input
                            v-model="dataForm.residual_color"
                            :currency="{'prefix': ''}"
                            locale="es"
                            :precision="6"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        <small>Ingrese el valor del cloro residual/ Colorímetro.</small>
                        <div class="invalid-feedback" v-if="dataErrors.residual_color">
                            <p class="m-b-0" v-for="error in dataErrors.residual_color">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel-heading ui-sortable-handle">
                    <h4 class="panel-title"><strong>Documento de referencia en la determinación de: Valor de pH 4500. Método electrométrico <br>Métodos estándar la examinación de agua <br>y aguas residuales. Edición
                        <label for="" v-if="dataForm.edition == null">23</label>
                        <label for="">@{{ dataForm.edition }}</label></strong></h4>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Residual Color Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('edition', trans('number_edition').':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::text('edition', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.edition }", 'v-model' => 'dataForm.edition', 'required' => false]) !!}
                        <small>Ingrese el número de edición del documento.</small>
                        <div class="invalid-feedback" v-if="dataErrors.edition">
                            <p class="m-b-0" v-for="error in dataErrors.edition">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Protocolos determinación de cloro residual método (4500.CL. F) <br> Métodos estándar para la examinación de agua y aguas residuales. Edición
                <label for="" v-if="dataForm.edition == null">23</label>
                <label for="">@{{ dataForm.edition }}</label></strong></h4>
            </strong></h4>
        </div>

        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Métodos nefelométrico 21 30 B <br> Métodos estándar para la examinación de agua y aguas residuales. Edición 
                <label for="" v-if="dataForm.edition == null">23</label>
                <label for="">@{{ dataForm.edition }}</label></strong></h4>
            </strong></h4>
        </div>
    </div>
</div>