<div class="form-group row m-b-15">
    {!! Form::label('registration_depth', 'Máx desgaste para reencauche (mm)' . ':', ['class' => 'col-form-label col-md-2']) !!}
    <div class="col-md-2 d-flex align-items-center">
        <p>@{{ initValues.max_wear }}</p>
    </div>
    {!! Form::label('registration_depth', 'Profundidad de la llanta en (mm)' . ':', ['class' => 'col-form-label col-md-2']) !!}
    <div class="col-md-3 d-flex align-items-center">
        <p>@{{ initValues.depth_tire }}</p>

    </div>
</div>
<div class="form-group row m-b-15">
    {!! Form::label('registration_depth', 'Profundidad del registro' . ':', ['class' => 'col-form-label col-md-2 required']) !!}
<dynamic-list-change
    label-button-add="Agregar profundidad" 
    :data-list.sync="dataForm.record_depth"
    class-table="table-hover text-inverse table-bordered" 
    :key="keyRefresh"
    :data-list-options="[
                                {label:'Profundidad', name:'name', isShow: true},
                            ]">
    <template #fields="scope">
        {{-- <input class="form-control col-md-20" required type="text" v-model="scope.dataForm.name" placeholder="Profundidad"> --}}
        <currency-input
                    v-model="scope.dataForm.name"
                    required="true"
                    :currency="{'suffix': ''}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"                    
                    >
        </currency-input>
    </template>
</dynamic-list-change>
<div class="row" style="margin-top: 10px; margin-left:10px">
    <input-operation-change
        :array="dataForm.record_depth"
        operation="especial2"
        name-field="registration_depth"
        :value="dataForm"
        :key="dataForm.record_depth?.length"
        suffix=" "
    ></input-operation-change>
</div>
</div>


<!-- Revision Date Field -->
<div class="form-group row m-b-15">
    {!! Form::label('revision_date', trans('Revision Date') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        <date-picker :value="dataForm" name-field="revision_date" :input-props="{required: true}" :max-date="dataForm.date">
        </date-picker>
        <small>@lang('Enter the') @{{ `@lang('Revision Date')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.revision_date">
            <p class="m-b-0" v-for="error in dataErrors.revision_date">@{{ error }}</p>
        </div>
    </div>


<!-- Wear Total Field -->
    {!! Form::label('wear_total', trans('Wear Total') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4" :key="keyRefresh"> 
        <input-operation-change
            :array="dataForm.record_depth"
            :number-two="parseFloat(dataForm.depth_tire)"
            operation="especial3"
            name-field="wear_total"
            :value="dataForm"
            :key="dataForm.registration_depth"
            suffix=" "
        ></input-operation-change>
        <small>Total del desgaste</small>
        <div class="invalid-feedback" v-if="dataErrors.wear_total">
            <p class="m-b-0" v-for="error in dataErrors.wear_total">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Revision Mileage Field -->
<div class="form-group row m-b-15">
    {!! Form::label('revision_mileage', trans('Revision Mileage') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">        
        <currency-input
            v-model="dataForm.revision_mileage"
            required="true"
            :currency="{'suffix': ' km'}"
            locale="es"
            :precision="2"
            class="form-control"
            :key="keyRefresh"
            required="true"                    
            >
        </currency-input>

        <small>@lang('Enter the') @{{ `@lang('Revision Mileage')` | lowercase }} (El kilometraje en revisión debe ser mayor al kilometraje inicial), kilometraje inicial: @{{ initValues.mileage_initial }}.</small>
        <div class="invalid-feedback" v-if="dataErrors.revision_mileage">
            <p class="m-b-0" v-for="error in dataErrors.revision_mileage">@{{ error }}</p>
        </div>
    </div>

    {!! Form::label('revision_pressure', trans('Revision Pressure') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        <currency-input
                    v-model="dataForm.revision_pressure"
                    required="true"
                    :currency="{'suffix': ' PSI'}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    required="true"                    
                    >
        </currency-input>
        <small>@lang('Enter the') @{{ `@lang('Revision Pressure')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.revision_pressure">
            <p class="m-b-0" v-for="error in dataErrors.revision_pressure">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Wear Cost Mm Field -->
<div class="form-group row m-b-15">
    {!! Form::label('wear_cost_mm','Valor actual de llanta'. ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        <input-operation-change
            :array="dataForm.record_depth"
            :number-one="parseFloat(dataForm.general_cost_mm)"
            :number-two="parseFloat(dataForm.max_wear)"
            operation="especial4"
            name-field="wear_cost_mm"
            :value="dataForm"
            :key="dataForm.registration_depth"
            prefix="$ "
        ></input-operation-change>
        <small>Costo por mm en desgaste</small>
        <div class="invalid-feedback" v-if="dataErrors.wear_cost_mm">
            <p class="m-b-0" v-for="error in dataErrors.wear_cost_mm">@{{ error }}</p>
        </div>
    </div>

<!-- Cost Km Field -->
    {!! Form::label('cost_km', trans('Cost Km') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">  
        <input-operation-change
            :number-one="parseFloat(dataForm.wear_total)"
            :number-two="parseFloat(dataForm.general_cost_mm)"
            :number-three="parseFloat(dataForm.route_total)"
            operation="especial5"
            name-field="cost_km"
            :value="dataForm"
            prefix="$ " 
            :key="dataForm.route_total"
        ></input-operation-change>
        <small>@{{ `@lang('Cost Km')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.cost_km">
            <p class="m-b-0" v-for="error in dataErrors.cost_km">@{{ error }}</p>
        </div>
    </div>
</div>

<!-- Revision Pressure Field -->
<div class="form-group row m-b-15">
<!-- Route Total Field -->
{!! Form::label('route_total', trans('Route Total') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
<div class="col-md-4">
    <input-operation-change
        :number-one="parseFloat(dataForm.revision_mileage)"
        :number-two="parseFloat(dataForm.mileage_initial)"
        operation="resta"
        name-field="route_total"
        :value="dataForm"
        :key="dataForm.revision_mileage"
        suffix=" km"
        :refresh-parent-component="true"
        required
    ></input-operation-change>
    <small>Total del recorrido</small>
    <div class="invalid-feedback" v-if="dataErrors.route_total">
        <p class="m-b-0" v-for="error in dataErrors.route_total">@{{ error }}</p>
    </div>
</div>
    

<!-- Observation Field -->
    {!! Form::label('observation', trans('Observation') . ':', ['class' => 'col-form-label col-md-2 required']) !!}
    <div class="col-md-4">
        {!! Form::textarea('observation', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.observation }",'rows'=>3, 'v-model' => 'dataForm.observation', 'required' => true]) !!}
        <small>@lang('Enter the') @{{ `@lang('Observation')` | lowercase }}</small>
        <div class="invalid-feedback" v-if="dataErrors.observation">
            <p class="m-b-0" v-for="error in dataErrors.observation">@{{ error }}</p>
        </div>
    </div>
</div>
