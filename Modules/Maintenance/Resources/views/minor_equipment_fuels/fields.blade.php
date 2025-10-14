<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Información general.</b>
        </h6>
    </label>
<!-- Dependencias Id Field -->
    <div class="row">
        <!-- Responsible Process Field -->
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('responsible_process', trans('Responsible Process').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                    css-class="form-control" 
                    name-field="responsible_process" 
                    reduce-label="nombre"
                    reduce-key="id"
                    name-resource="get-dependency" 
                    :value="dataForm" 
                    :is-required="true"
                    :key="keyRefresh"
                    >
                    </select-check>
                    <small>Seleccione el proceso responsable</small>
                    <div class="invalid-feedback" v-if="dataErrors.responsible_process">
                        <p class="m-b-0" v-for="error in dataErrors.responsible_process">@{{ error }}</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="col">        
            <!-- Supply Date Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('supply_date', trans('Supply Date').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::date('supply_date', null, ['class' => 'form-control', 'id' => 'supply_date', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.supply_date', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Supply Date')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.supply_date">
                        <p class="m-b-0" v-for="error in dataErrors.supply_date">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- Supply Hour Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('supply_hour', trans('Supply Hour').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <hour-military
                    name-field="supply_hour"
                    :value="dataForm">
                    </hour-military>
                    <small>@lang('Enter the') @{{ `@lang('Supply Hour')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.supply_hour">
                        <p class="m-b-0" v-for="error in dataErrors.supply_hour">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Start Date Fortnight Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('start_date_fortnight', trans('Fecha inicial del período').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">        
                    {!! Form::date('start_date_fortnight', null, ['class' => 'form-control', 'id' => 'start_date_fortnight', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.start_date_fortnight', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Start Date Fortnight')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.start_date_fortnight">
                        <p class="m-b-0" v-for="error in dataErrors.start_date_fortnight">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{-- <!-- End Date Fortnight Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('end_date_fortnight', trans('Fecha final del período').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::date('end_date_fortnight', null, ['class' => 'form-control', 'id' => 'end_date_fortnight', 'placeholder' => 'Select Date', 'v-model' => 'dataForm.end_date_fortnight', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('End Date Fortnight')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.end_date_fortnight">
                        <p class="m-b-0" v-for="error in dataErrors.end_date_fortnight">@{{ error }}</p>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="col">

        </div>
    </div>
</div>









<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Evaluación del saldo de combustible.</b>
        </h6>
    </label>




    <div class="row">
        <div class="col">
            <!-- Initial Fuel Balance Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('fuel_type', 'Tipo combustible'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <div >
                        <select
                        v-model="dataForm.fuel_type"
                        name="fuel_type"
                        class="form-control"
                        >
                        <option value="Gasolina">Gasolina</option>
                        <option value="ACPM">ACPM </option>
                      </select>                    
                    </div>
                    <small>Seleccione el tipo de combustible</small>
                    <div class="invalid-feedback" v-if="dataErrors.fuel_type">
                        <p class="m-b-0" v-for="error in dataErrors.fuel_type">@{{ error }}</p>
                    </div>             
                </div>
            </div>
        </div>
        <div class="col">
        </div>
    </div>

   

    <div class="row">
        <div class="col">
            <!-- Initial Fuel Balance Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('initial_fuel_balance', trans('Initial Fuel Balance').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <div v-if="dataForm.vueTableComponentInternalRowId != null">
                        <currency-input
                        v-model="dataForm.initial_fuel_balance"
                        required="true"
                        :currency="{'suffix': ' gal'}"
                        locale="es"
                        :precision="4"
                        class="form-control"
                        readonly="true"
                        :key="keyRefresh"
                        >
                        </currency-input>                        
                    </div>
                    <div v-else>
                        <input-data 
                        name-field="initial_fuel_balance"
                        class-input="form-control" 
                        :name-resource="'get-final-fuel/'+dataForm.responsible_process+'/'+dataForm.fuel_type"
                        suffix=" gal"
                        :value="dataForm"
                        :decimals="4"
                        :key="dataForm.fuel_type"
                        ></input-data>
                    </div>                  
                    <small>@lang('Enter the') @{{ `@lang('Initial Fuel Balance')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.initial_fuel_balance">
                        <p class="m-b-0" v-for="error in dataErrors.initial_fuel_balance">@{{ error }}</p>
                    </div>             
                </div>
            </div>
        </div>
        <div class="col">
            <!-- More Buy Fortnight Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('more_buy_fortnight', trans('Más compras en el período').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input
                    v-model="dataForm.more_buy_fortnight"
                    required="true"
                    :currency="{'suffix': ' gal'}"
                    locale="es"
                    :precision="4"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('More Buy Fortnight')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.more_buy_fortnight">
                        <p class="m-b-0" v-for="error in dataErrors.more_buy_fortnight">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- Less Fuel Deliveries Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('less_fuel_deliveries', trans('Less Fuel Deliveries').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <div v-if="dataForm.vueTableComponentInternalRowId != null">
                        <currency-input
                        v-model="dataForm.less_fuel_deliveries"
                        required="true"
                        :currency="{'suffix': ' gal'}"
                        locale="es"
                        :precision="4"
                        class="form-control"
                        readonly="true"
                        :key="keyRefresh"
                        >
                        </currency-input>                        
                    </div>
                    <div v-else>
                        <input-data 
                        name-field="less_fuel_deliveries"
                        class-input="form-control" 
                        :name-resource="'get-total-consumption/'+dataForm.responsible_process+'/'+dataForm.fuel_type"
                        suffix=" gal"
                        :value="dataForm"
                        :decimals="4"
                        :key="dataForm.fuel_type"
                        ></input-data>
                    </div>
                    <small>@lang('Enter the') @{{ `@lang('Less Fuel Deliveries')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.less_fuel_deliveries">
                        <p class="m-b-0" v-for="error in dataErrors.less_fuel_deliveries">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Final Fuel Balance Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('final_fuel_balance', trans('Final Fuel Balance').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <input-operation 
                    ref="operacionResta"
                    name-field="final_fuel_balance"
                    :number-one="dataForm.initial_fuel_balance"
                    :number-two="dataForm.more_buy_fortnight"
                    :number-three="dataForm.less_fuel_deliveries"
                    :key="dataForm.executed_value  + dataForm.value_available"
                    :value="dataForm"
                    :cantidad-decimales="4"
                    operation="especial1"
                    suffix=' gal'
                    ></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Final Fuel Balance')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.final_fuel_balance">
                        <p class="m-b-0" v-for="error in dataErrors.final_fuel_balance">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Relación compras de combustibles en la quincena.</b>
        </h6>
    </label>
    <div class="row">
        <div class="col">
            <!-- Bill Number Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('bill_number', trans('Bill Number').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('bill_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.bill_number }", 'v-model' => 'dataForm.bill_number', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Bill Number')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.bill_number">
                        <p class="m-b-0" v-for="error in dataErrors.bill_number">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Gallon Value Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('gallon_value', trans('Gallon Value').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input
                    v-model="dataForm.gallon_value"
                    required="true"
                    :currency="{'prefix': '$ '}"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Gallon Value')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.gallon_value">
                        <p class="m-b-0" v-for="error in dataErrors.gallon_value">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- Checked Fuel Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('checked_fuel', trans('Checked Fuel').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <input-operation 
                    name-field="checked_fuel"
                    :number-one="dataForm.more_buy_fortnight"
                    :number-two="0"
                    :key="dataForm.more_buy_fortnight"
                    :value="dataForm"                    
                    operation="resta"
                    :cantidad-decimales="4"
                    suffix=' gal'
                    ></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Checked Fuel')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.checked_fuel">
                        <p class="m-b-0" v-for="error in dataErrors.checked_fuel">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Cost In Pesos Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('cost_in_pesos', trans('Cost In Pesos').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <input-operation 
                    name-field="cost_in_pesos"
                    :number-one="dataForm.gallon_value"
                    :number-two="dataForm.checked_fuel"
                    :key="dataForm.checked_fuel + dataForm.gallon_value"
                    :value="dataForm"                    
                    operation="multiplica"
                    prefix='$ '
                    ></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Cost In Pesos')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.cost_in_pesos">
                        <p class="m-b-0" v-for="error in dataErrors.cost_in_pesos">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Responsable.</b>
        </h6>
    </label>
    <div class="row">
        <div class="col">
            <!-- Name Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('name', trans('Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name }", 'v-model' => 'dataForm.name', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Name')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.name">
                        <p class="m-b-0" v-for="error in dataErrors.name">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Position Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('position', trans('Cargo').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('position', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.position }", 'v-model' => 'dataForm.position', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `el cargo` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.position">
                        <p class="m-b-0" v-for="error in dataErrors.position">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- Approved Process Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('approved_process', trans('Approved Process').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                    css-class="form-control" 
                    name-field="approved_process" 
                    reduce-label="nombre"
                    reduce-key="id"
                    name-resource="get-dependency" 
                    :value="dataForm" 
                    :is-required="true"
                    :key="keyRefresh"
                    >
                    </select-check>
                    <small>Seleccione el proceso que aprobó</small>
                    <div class="invalid-feedback" v-if="dataErrors.approved_process">
                        <p class="m-b-0" v-for="error in dataErrors.approved_process">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <!-- Process Leader Name Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('process_leader_name', trans('Process Leader Name').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">        
                    <select-check 
                    css-class="form-control" 
                    name-field="process_leader_name" 
                    reduce-label="name"
                    reduce-key="id"
                    :name-resource="'get-users-dependency/'+dataForm.approved_process"
                    :value="dataForm" 
                    :is-required="true"
                    :key="dataForm.approved_process"
                    >
                    </select-check>
                    <small>Seleccione el nombre del líder del proceso</small>
                    <div class="invalid-feedback" v-if="dataErrors.process_leader_name">
                        <p class="m-b-0" v-for="error in dataErrors.process_leader_name">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row m-b-15" v-if="dataForm.id > 0">

        <!-- Kilometraje anterior -->
        <label class="col-form-label col-md-2 required" for="descriptionDelete" >Observación porque va editar este registro:</label>
        <div class="col-md-10">
            <textarea  type="textarea" id="descriptionDelete" :class="{'form-control':true, 'is-invalid':dataErrors.descriptionDelete }"  v-model="dataForm.descriptionDelete" required></textarea>
            <small style="font-size:9px">Ingrese porque va editar este registro</small>
        </div>
    </div>
</div>








