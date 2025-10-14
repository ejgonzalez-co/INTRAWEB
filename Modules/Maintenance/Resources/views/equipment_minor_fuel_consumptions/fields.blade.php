
<div class="panel" style="border: 200px; padding: 15px;">
    <label>
        <h6>
            <b>Información general.</b>
        </h6>
    </label>



       <div class="row">
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
        <div class="col">
            <!-- dependencias_id Field -->
            <div class="form-group row m-b-15">
                {!! Form::label('fuel_type', 'Tipo de combustible:', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <input-check 
                    :disabled="false"
                    readonly="true"
                    name-field="fuel_type"
                    css-class="form-control" 
                    :name-resource="'get-requirement-for-operation/'+ initValues.mant_minor_equipment_fuel_id" 
                    :value="dataForm"
                    ></input-check>
                    <small>Tipo de combustible</small>
                    <div class="invalid-feedback" v-if="dataErrors.fuel_type">
                        <p class="m-b-0" v-for="error in dataErrors.fuel_type">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
             <!-- dependencias_id Field -->
             <div class="form-group row m-b-15">
                {!! Form::label('dependencias_id', trans('Proceso al que pertenece').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                        css-class="form-control" 
                        name-field="dependencias_id" 
                        reduce-label="nombre"
                        reduce-key="id"
                        name-resource="get-dependency" 
                        :value="dataForm" 
                        :is-required="true"
                        :key="keyRefresh"
                        >
                    </select-check>
                    <small>Seleccione el proceso al que pertenece</small>
                    <div class="invalid-feedback" v-if="dataErrors.dependencias_id">
                        <p class="m-b-0" v-for="error in dataErrors.dependencias_id">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('mant_resume_equipment_machinery_id', trans('Equipment Description').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <select-check 
                        css-class="form-control" 
                        name-field="mant_resume_equipment_machinery_id" 
                        reduce-label="name_equipment_inventory"
                        reduce-key="id"
                        :name-resource="'get-machinary/'+dataForm.dependencias_id+','+initValues.mant_minor_equipment_fuel_id"
                        :value="dataForm" 
                        :is-required="true"
                        :key="dataForm.dependencias_id"
                        >
                    </select-check>
                    <small>@lang('Enter the') @{{ `@lang('Equipment Description')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.mant_resume_equipment_machinery_id">
                        <p class="m-b-0" v-for="error in dataErrors.mant_resume_equipment_machinery_id">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">            
             <!-- Name Receives Equipment Field -->
             <div class="form-group row m-b-15">
                {!! Form::label('name_receives_equipment', trans('Name Receives Equipment').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    {!! Form::text('name_receives_equipment', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.name_receives_equipment }", 'v-model' => 'dataForm.name_receives_equipment', 'required' => true]) !!}
                    <small>@lang('Enter the') @{{ `@lang('Name Receives Equipment')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.name_receives_equipment">
                        <p class="m-b-0" v-for="error in dataErrors.name_receives_equipment">@{{ error }}</p>
                    </div>
                </div>
            </div>
        </div>
        
            <div class="col">
                <!-- Initial Fuel Balance Field -->
                <div class="form-group row m-b-15">
                    {!! Form::label('fuel_input', 'Ingrese el combustible en'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                    <div class="col-md-9">
                        <div >
                            <select
                            v-model="dataForm.fuel_input"
                            name="fuel_input"
                            class="form-control"
                            >
                            <option value="Galón">Galón</option>
                            <option value="Litros">Litros</option>
                          </select>                    
                        </div>
                        <small>Seleccione el tipo de medida</small>
                        <div class="invalid-feedback" v-if="dataErrors.fuel_input">
                            <p class="m-b-0" v-for="error in dataErrors.fuel_input">@{{ error }}</p>
                        </div>             
                    </div>
                </div>
            </div>
        </div>
  
    <div class="row" v-if="dataForm.fuel_input =='Galón'">
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('gallons_supplied', trans('Gallons Supplied').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input
                    v-model="dataForm.gallons_supplied"
                    required="true"
                    :currency="{'suffix': ' gal'}"                    
                    locale="es"
                    :precision="4"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Gallons Supplied')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.gallons_supplied">
                        <p class="m-b-0" v-for="error in dataErrors.gallons_supplied">@{{ error }}</p>
                    </div>
                </div>
            </div>          
        </div>
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('value_gallon_avaible', 'Combustible disponible'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">                    
                    <input-data 
                    name-field="value_gallon_avaible"
                    class-input="form-control" 
                    :name-resource="'get-fuel-avaible/'+dataForm.mant_minor_equipment_fuel_id"
                    suffix=" gal"
                    :decimals="4"
                    :value="dataForm"
                    :key="keyRefresh"
                    ></input-data>                    
                    <div class="invalid-feedback" v-if="dataErrors.value_gallon_avaible">
                        <p class="m-b-0" v-for="error in dataErrors.value_gallon_avaible">@{{ error }}</p>
                    </div>             
            </div>
        </div>
            <!-- Gallons Supplied Field -->
             
        </div>
    </div>

    <div class="row" v-if="dataForm.fuel_input =='Litros'">
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('liter_supplied', trans('Litros suministrados').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <currency-input
                    v-model="dataForm.liter_supplied"
                    required="true"
                    :currency="{'suffix': ''}"                    
                    locale="es"
                    :precision="4"
                    class="form-control"
                    :key="keyRefresh"
                    >
                    </currency-input>
                    <small>@lang('Enter the') @{{ `@lang('Litros suministrados')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.liter_supplied">
                        <p class="m-b-0" v-for="error in dataErrors.liter_supplied">@{{ error }}</p>
                    </div>
                </div>
            </div>          
        </div>
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('gallons_supplied', trans('Gallons Supplied').':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">
                    <input-operation
                    :number-one="dataForm.liter_supplied"
                    name-field="gallons_supplied"
                    :key="dataForm.gallons_supplied+dataForm.liter_supplied"
                    :cantidad-decimales="4"
                    :value="dataForm"
                    operation="convert"
                    suffix=" "
                    ></input-operation>
                    <small>@lang('Enter the') @{{ `@lang('Gallons Supplied')` | lowercase }}</small>
                    <div class="invalid-feedback" v-if="dataErrors.gallons_supplied">
                        <p class="m-b-0" v-for="error in dataErrors.gallons_supplied">@{{ error }}</p>
                    </div>
                </div>
            </div>          
        </div>
    </div>


    <div class="row" v-if="dataForm.fuel_input =='Litros'">
        <div class="col">
            <div class="form-group row m-b-15">
                {!! Form::label('value_gallon_avaible', 'Combustible disponible'.':', ['class' => 'col-form-label col-md-3 required']) !!}
                <div class="col-md-9">                    
                    <input-data 
                    name-field="value_gallon_avaible"
                    class-input="form-control" 
                    :name-resource="'get-fuel-avaible/'+dataForm.mant_minor_equipment_fuel_id"
                    suffix=" gal"
                    :decimals="4"
                    :value="dataForm"
                    :key="keyRefresh"
                    ></input-data>                    
                    <div class="invalid-feedback" v-if="dataErrors.value_gallon_avaible">
                        <p class="m-b-0" v-for="error in dataErrors.value_gallon_avaible">@{{ error }}</p>
                        </div>             
                </div>
            </div>  
        </div>
        <div class="col">
           
            <!-- Gallons Supplied Field -->
             
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


