<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Información de la muestra</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Lc Sample Points Id Field -->
                <div class="form-group row m-b-15" v-if="dataForm.id>0">
                    {!! Form::label('lc_sampling_schedule_id', trans('code_tap_point') . ':', [
                        'class' => 'col-form-label col-md-3 required',
                    ]) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="lc_sampling_schedule_id"
                            reduce-label="nombre_punto" reduce-key="id" :name-resource="'get-points-location/'+ {!! $lc_start_sampling_id !!}"
                            :value="dataForm" :is-required="true" :disabled="true" :key="keyRefresh">
                        </select-check>

                        <small>Selección el punto de toma de la muestra.</small>
                        <div class="invalid-feedback" v-if="dataErrors.lc_sampling_schedule_id">
                            <p class="m-b-0" v-for="error in dataErrors.lc_sampling_schedule_id">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
                <!-- Lc Sample Points Id Field -->
                <div class="form-group row m-b-15" v-else>
                    {!! Form::label('lc_sampling_schedule_id', trans('code_tap_point') . ':', [
                        'class' => 'col-form-label col-md-3 required',
                    ]) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="lc_sampling_schedule_id"
                            reduce-label="nombre_punto" reduce-key="id" :name-resource="'get-points-location/'+ {!! $lc_start_sampling_id !!}"
                            :value="dataForm" :is-required="true" :key="keyRefresh">
                        </select-check>

                        <small>Selección el punto de toma de la muestra.</small>
                        <div class="invalid-feedback" v-if="dataErrors.lc_sampling_schedule_id">
                            <p class="m-b-0" v-for="error in dataErrors.lc_sampling_schedule_id">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Type Water Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('type_water', trans('Type Water') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.type_water">
                            <option value="Cruda">Cruda</option>
                            <option value="Tratada">Tratada</option>
                            <option value="De proceso">De proceso</option>
                        </select>
                        <small>Seleccione el tipo de agua.</small>
                        <div class="invalid-feedback" v-if="dataErrors.type_water">
                            <p class="m-b-0" v-for="error in dataErrors.type_water">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="dataForm.lc_sampling_schedule_id==0">
            <div class="col-md-6">
                <!-- Type Water Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('emergencia', trans('Alternativa') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.emergencia">
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        <small>Seleccione el tipo de alternativa.</small>
                        <div class="invalid-feedback" v-if="dataErrors.emergencia">
                            <p class="m-b-0" v-for="error in dataErrors.emergencia">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="dataForm.emergencia=='No'">
            <div class="col-md-6">
                <div class="form-group row m-b-15">
                    {!! Form::label('puntoReemplazar', trans('Punto a reemplazar') . ':', [
                        'class' => 'ol-focrm-label col-md-3 required',
                    ]) !!}
                    <div class="col-md-9">
                        <select-check css-class="form-control" name-field="puntoReemplazar"
                            reduce-label="nombre_punto" reduce-key="id" name-resource="get-points-reem"
                            :value="dataForm" :is-required="true" :key="keyRefresh">
                        </select-check>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div>

        <div class="row mt-5 text-center" v-if="dataForm.emergencia=='No'">
            {!! Form::label('puntoReemplazar', trans('Punto de reemplazo') . ':', [
                'class' => 'ol-focrm-label col-md-3 required',
            ]) !!}
            <div class="col-md-7">
                <select-list-check name-field="lc_sample_points_id" name-resource="get-points" :value="dataForm"
                    label="Seleccione el punto de reemplazo"></select-list-check>
            </div>
        </div>

        <div class="row">
            <!-- Campo de direccion -->
            <div class="col-md-6" v-if="dataForm.lc_sampling_schedule_id==0 && dataForm.emergencia=='Si'">
                <div class="form-group row m-b-15">
                    {!! Form::label('direction', trans('Direction') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
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
            </div>
            <div class="col-md-6"
                v-if="dataForm.lc_sampling_schedule_id==0 && dataForm.emergencia=='No' || dataForm.emergencia=='Si'">
                <!-- observationsPunto Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('observationsPunto', trans('Observaciones sobre el nuevo punto de toma') . ':', [
                        'class' => 'col-form-label col-md-3 required',
                    ]) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('observationsPunto', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observationsPunto }",
                            'v-model' => 'dataForm.observationsPunto',
                            'required' => true,
                        ]) !!}
                        <small>Ingrese las observaciones correspondientes al nuevo sitio de la toma.</small>
                        <div class="invalid-feedback" v-if="dataErrors.observationsPunto">
                            <p class="m-b-0" v-for="error in dataErrors.observationsPunto">
                                @{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="dataForm.lc_sampling_schedule_id==0 && dataForm.emergencia=='No'">

        </div>
        <div class="row">
            <!-- select: Duplicado -->
            <div class="col-md-6"
                v-if="dataForm.lc_sampling_schedule_id==0  && (dataForm.emergencia=='Si' || dataForm.emergencia=='No')">
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
            </div>

            <div class="col"
                v-if="dataForm.lc_sampling_schedule_id==0 && (dataForm.emergencia=='Si' || dataForm.emergencia=='No') ">
                {!! Form::label('ensayo', trans('Ensayo a realizar') . ':', ['class' => 'col-form-label required']) !!}
            </div>
            <div class="col"
                v-if="dataForm.lc_sampling_schedule_id==0 && (dataForm.emergencia=='Si' || dataForm.emergencia=='No') ">
                <div class="col-md-9">
                    <input type="checkbox" name="fisico" id="fisico" v-model="dataForm.fisico">
                    {!! Form::label('ensayo', trans('Físico'), ['class' => 'col-form-label col-md-3']) !!}

                </div>
                <div class="col-md-9">
                    <input type="checkbox" name="quimico" id="quimico" v-model="dataForm.quimico">
                    {!! Form::label('ensayo', trans('Quimico'), ['class' => 'col-form-label col-md-3']) !!}
                </div>
                <div class="col-md-9">
                    <input type="checkbox" name="microbiologico" id="microbiologico" v-model="dataForm.microbiologico">
                    {!! Form::label('ensayo', trans('Microbiológico'), ['class' => 'col-form-label col-md-3']) !!}
                </div>
                <div class="col-md-9">
                    <input type="checkbox" name="todos" id="todos" v-model="dataForm.todos">
                    {!! Form::label('ensayo', trans('Todos'), ['class' => 'col-form-label col-md-3']) !!}
                </div>
            </div>

            <div class="col-md-6 " v-if="dataForm.lc_sampling_schedule_id && dataForm.lc_sampling_schedule_id!=0">
                <div class="form-group row m-b-15 text-center align-items-center justify-content-center">
                    <div class="col-md-7">
                        <information-component titulo="Información"
                            :name-resource="'get-information-programming/' + dataForm.lc_sampling_schedule_id"
                            :items="['duplicado', 'nombre_punto', 'direction', 'sampling_date', 'user_creador', 'mensaje',
                                'observation'
                            ]"
                            :types="['label', 'label', 'label', 'label', 'label', 'textarea', 'textarea']"
                            :name-labels="['Duplicado', 'Punto de muestra', 'Dirección', 'Fecha de la toma', 'Usuario creador',
                                'Ensayo', 'Observaciones'
                            ]"
                            :key="dataForm.lc_sampling_schedule_id"></information-component>
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
            <h4 class="panel-title"><strong>pH</strong></h4>
        </div>
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar ítem a la lista" :data-list.sync="dataForm.lc_dynamic_ph_lists"
                class-table="table-responsive table-bordered" class-container="w-100 p-10"
                :data-list-options="[
                    { label: 'Unidad de pH', name: 'ph_unit', isShow: true },
                    { label: 'temperatura', name: 'temperature_range', isShow: true }
                ]">
                <template #fields="scope">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Unidad de ph -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('ph_unit', trans('unit_ph') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    <currency-input
                                        placeholder="Unidad de pH"
                                        v-model="scope.dataForm.ph_unit"
                                        :currency="{'prefix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                
                                    {{-- <input class="form-control" required type="text"
                                        v-model="scope.dataForm.ph_unit" placeholder="Unidad de pH"> --}}
                                    <small>Ingrese la unidad de pH</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- temperature Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('temperature_range', trans('Range_temperature') . ':', [
                                    'class' => 'col-form-label col-md-3 required',
                                ]) !!}
                                <div class="col-md-9">
                                    <currency-input
                                        placeholder="Rango de temperatura (°C)"
                                        v-model="scope.dataForm.temperature_range"
                                        :currency="{'prefix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input
                                    {{-- <input class="form-control" required type="text"
                                        v-model="scope.dataForm.temperature_range"
                                        placeholder="Rango de temperatura (°C)"> --}}
                                    <small>Ingrese el rango de temperatura</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <!-- Value Contract Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('ph_promedio', trans('Promedio de pH') . ':', ['class' => 'col-form-label col-md-6 required']) !!}
                <div class="col-md-6">
                    <input-operation name-field="ph_promedio" :array-recorrer="dataForm.lc_dynamic_ph_lists"
                        :key="dataForm.lc_dynamic_ph_lists.length" :value="dataForm" :cantidad-decimales="3"
                        operation="promedio" name-campo="ph_unit" prefix=' '></input-operation>
                    <div class="invalid-feedback" v-if="dataErrors.ph_promedio">
                        <p class="m-b-0" v-for="error in dataErrors.ph_promedio">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Cdp Available Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('temperatura_promedio', trans('Rango de temperatura en °C(pH)') . ':', [
                    'class' => 'col-form-label col-md-6 required',
                ]) !!}
                <div class="col-md-6">
                    <input-operation name-field="temperatura_promedio" name-campo="temperature_range"
                        :array-recorrer="dataForm.lc_dynamic_ph_lists" :key="dataForm.lc_dynamic_ph_lists.length"
                        :value="dataForm" tipo-operacion="concatena" operation="concatena" prefix=' '>
                    </input-operation>
                    <div class="invalid-feedback" v-if="dataErrors.temperatura_promedio">
                        <p class="m-b-0" v-for="error in dataErrors.temperatura_promedio">@{{ error }}</p>
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
            <h4 class="panel-title"><strong>Cloro residual</strong></h4>
        </div>
        <div class="form-group row m-b-15">
            <dynamic-list label-button-add="Agregar ítem a la lista"
                :data-list.sync="dataForm.lc_residual_chlorine_lists" class-table="table-responsive table-bordered"
                class-container="w-100 p-10"
                :data-list-options="[
                    { label: 'V muestra (mL)', name: 'v_sample', isShow: true },
                    { label: 'V FAS gastado Ensayo cloro Residual', name: 'chlorine_residual_test', isShow: true },
                    { label: 'mg Cl2/l', name: 'mg_cl2', isShow: true },
                ]">
                <template #fields="scope">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Unidad de ph -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('v_sample', trans('v_sample_ml') . ':', ['class' => 'col-form-label col-md-3 required']) !!}
                                <div class="col-md-9">
                                    {{-- <input class="form-control" required type="text"
                                        v-model="scope.dataForm.v_sample" placeholder="V muestra (mL)"> --}}
                                        <currency-input
                                            placeholder="V muestra (mL)"
                                            v-model="scope.dataForm.v_sample"
                                            :currency="{'prefix': ''}"
                                            locale="es"
                                            :precision="2"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                        
                                    <small>Ingrese V muestra</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- temperature Field -->
                            <div class="form-group row m-b-15">
                                {!! Form::label('chlorine_residual_test', trans('chlorine_residual') . ':', [
                                    'class' => 'col-form-label col-md-3 required',
                                ]) !!}
                                <div class="col-md-9">
                                    <currency-input
                                        placeholder="V FAS gastado Ensayo cloro Residual"
                                        v-model="scope.dataForm.chlorine_residual_test"
                                        :currency="{'prefix': ''}"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        :key="keyRefresh"
                                        >
                                    </currency-input>
                                    {{-- <input class="form-control" required type="text"
                                        v-model="scope.dataForm.chlorine_residual_test"
                                        placeholder="V FAS gastado Ensayo cloro Residual"> --}}
                                    <small>Ingrese el V FAS gastado de ensayo cloro residual</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- temperature Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-3">mg Cl<sub>2</sub>/l:</label>
                                <div class="col-md-9">
                                    <input-operation name-field="mg_cl2"
                                        :number-one="scope.dataForm.chlorine_residual_test"
                                        :number-two="scope.dataForm.v_sample"
                                        :number-three="dataForm.lc_start_sampling_id"
                                        :key="scope.dataForm.v_sample + scope.dataForm.chlorine_residual_test"
                                        :value="scope.dataForm" operation="especial toma de muestra" prefix=' '
                                        :cantidad-decimales="4"></input-operation>

                                    <small>Ingrese el mg Cl<sub>2</sub>/l</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </dynamic-list>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- Value Contract Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('cloro_promedio', trans('Promedio mg Cl/L') . ':', [
                    'class' => 'col-form-label col-md-6 required',
                ]) !!}
                <div class="col-md-6">
                    <input-operation name-field="cloro_promedio" name-campo="mg_cl2"
                        :array-recorrer="dataForm.lc_residual_chlorine_lists"
                        :key="dataForm.lc_residual_chlorine_lists.length" :value="dataForm"
                        :cantidad-decimales="3" operation="promedio" prefix=' '></input-operation>
                    <div class="invalid-feedback" v-if="dataErrors.cloro_promedio">
                        <p class="m-b-0" v-for="error in dataErrors.cloro_promedio">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Turbidez</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Humidity Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('turbidez', trans('Turbidez(NTU)') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {{-- {!! Form::text('turbidez', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.turbidez }",
                            'v-model' => 'dataForm.turbidez',
                            'required' => false,
                        ]) !!} --}}
                        <currency-input
                            required="false"
                            v-model="dataForm.turbidez"
                            :currency="{'prefix': ''}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        <small>@lang('Enter the') @{{ `@lang('turbidez')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.turbidez">
                            <p class="m-b-0" v-for="error in dataErrors.turbidez">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
</div>
<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Condiciones ambientales de la toma de muestra</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Humidity Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('humidity', trans('Humidity') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {{-- {!! Form::text('humidity', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.humidity }",
                            'v-model' => 'dataForm.humidity',
                            'required' => false,
                        ]) !!} --}}
                        @if($infoTaking->type_customer == 'Distribucion')
                        <currency-input 
                            :required="true"
                            v-model="dataForm.humidity"
                            :currency="{'prefix': ''}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        @else
                        <currency-input 
                            :required="false"
                            v-model="dataForm.humidity"
                            :currency="{'prefix': ''}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        @endif
                        <small>@lang('Enter the') @{{ `@lang('Humidity')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.humidity">
                            <p class="m-b-0" v-for="error in dataErrors.humidity">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Temperature Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('temperature', trans('Temperature') . '(°C)' . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {{-- {!! Form::text('temperature', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.temperature }",
                            'v-model' => 'dataForm.temperature',
                            'required' => false,
                        ]) !!} --}}
                        @if($infoTaking->type_customer == 'Distribucion')
                        <currency-input 
                            :required="true"
                            v-model="dataForm.temperature"
                            :currency="{'suffix': '°C'}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        @else
                        <currency-input 
                            :required="false"
                            v-model="dataForm.temperature"
                            :currency="{'suffix': '°C'}"
                            locale="es"
                            :precision="2"
                            class="form-control"
                            :key="keyRefresh"
                            >
                        </currency-input>
                        @endif
                        <small>@lang('Enter the') @{{ `@lang('Temperature')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.temperature">
                            <p class="m-b-0" v-for="error in dataErrors.temperature">@{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Hour From To Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('hour_from_to', trans('Hour From To') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <hour-military name-field="hour_from_to" :value="dataForm">
                        </hour-military>
                        <small>@lang('Enter the') @{{ `@lang('Hour From To')` | lowercase }}</small>
                        <div class="invalid-feedback" v-if="dataErrors.hour_from_to">
                            <p class="m-b-0" v-for="error in dataErrors.hour_from_to">@{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Prevailing Climatic Characteristics Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('prevailing_climatic_characteristics', trans('Prevailing Climatic Characteristics') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control"
                            v-model="dataForm.prevailing_climatic_characteristics">
                            <option value="A-Templado">A-Templado</option>
                            <option value="B-Soleado">B-Soleado</option>
                            <option value="C-Parcialmente soleado">C-Parcialmente soleado</option>
                            <option value="D-Lluvioso">D-Lluvioso</option>
                            <option value="E-Intervalos de nube y sol">E-Intervalos de nube y sol</option>
                            <option value="F-Niebla poco densa">F-Niebla poco densa</option>
                            <option value="G-Tropical húmedo y lluvioso">G-Tropical húmedo y lluvioso</option>
                            <option
                                value="I-Algunos chubascos (aguacero) y tormentas (moderado, fuerte, muy fuerte y torrencial)">
                                I-Algunos chubascos (aguacero) y tormentas (moderado, fuerte, muy fuerte y
                                torrencial)
                            </option>
                            <option value="J-Tormentas">J-Tormentas</option>
                        </select>
                        <small>Seleccione características climaticas predominantes</small>
                        <div class="invalid-feedback" v-if="dataErrors.prevailing_climatic_characteristics">
                            <p class="m-b-0" v-for="error in dataErrors.prevailing_climatic_characteristics">
                                @{{ error }}</p>
                        </div>
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
            <h4 class="panel-title"><strong>Otro</strong></h4>
        </div>
        <div class="row">

            <div class="col-md-6">
                <!-- Container Number Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('container_number', trans('Container Number') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::number('container_number', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.container_number }",
                            'v-model' => 'dataForm.container_number',
                            'required' => false,
                        ]) !!}
                        <small>Ingrese el número de envases</small>
                        <div class="invalid-feedback" v-if="dataErrors.container_number">
                            <p class="m-b-0" v-for="error in dataErrors.container_number">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
    </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">
    <div class="panel-body">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title"><strong>Recepción de muestras</strong></h4>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Hour Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('hour', trans('Hour') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <hour-military name-field="hour" :value="dataForm" :disabled="true">
                        </hour-military>
                        <small>Ingrese la hora de la recepción de la muestra</small>
                        <div class="invalid-feedback" v-if="dataErrors.hour">
                            <p class="m-b-0" v-for="error in dataErrors.hour">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- According Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('according', trans('According') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.according" disabled>
                            <option value="Si">Si</option>
                            <option value="No">No</option>
                        </select>
                        <small>Seleccione si la muestra es conforme </small>
                        <div class="invalid-feedback" v-if="dataErrors.according">
                            <p class="m-b-0" v-for="error in dataErrors.according">@{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Sample Characteristics Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('sample_characteristics', trans('Sample Characteristics') . ':', [
                        'class' => 'col-form-label col-md-3',
                    ]) !!}
                    <div class="col-md-9">
                        <select name="select" class="form-control" v-model="dataForm.sample_characteristics" disabled>
                            <option value="MR">MR</option>
                            <option value="S">S</option>
                            <option value="ME">ME</option>
                        </select>
                        <small>Seleccione las características de la muestra</small>
                        <div class="invalid-feedback" v-if="dataErrors.sample_characteristics">
                            <p class="m-b-0" v-for="error in dataErrors.sample_characteristics">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Observations Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('observations', trans('Observations') . ':', ['class' => 'col-form-label col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('observations', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observations }",
                            'v-model' => 'dataForm.observations',
                            'required' => false,
                            'readonly' => false,
                        ]) !!}
                        <small>Ingrese las observaciones correspondientes a la recepción.</small>
                        <div class="invalid-feedback" v-if="dataErrors.observations">
                            <p class="m-b-0" v-for="error in dataErrors.observations">@{{ error }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" v-if="dataForm.users_id">
                <!-- Observations Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('Razones de edicion', trans('Observations_edit') . ':', [
                        'class' => 'col-form-label col-md-3 required',
                    ]) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('observations_edit', null, [
                            ':class' => "{'form-control':true, 'is-invalid':dataErrors.observations_edit }",
                            'v-model' => 'dataForm.observations_edit',
                            'required' => true,
                            'readonly' => false,
                            
                            
                        ]) !!}
                        <small>Por favor ingrese una justificación del por que se edito el registro.</small>
                        <div class="invalid-feedback" v-if="dataErrors.observations_edit">
                            <p class="m-b-0" v-for="error in dataErrors.observations_edit">
                                @{{ error }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
