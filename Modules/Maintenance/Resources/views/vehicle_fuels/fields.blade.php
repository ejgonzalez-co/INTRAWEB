<div class="panel" data-sortable-id="ui-general-1">
    <!-- begin panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title" >Información general</h4> 
    </div>

    <div class="panel-body">

        <div class="form-group row m-b-15">
            <!-- Mant Resume Machinery Vehicles Yellow Id Field -->
            {!! Form::label('mant_resume_machinery_vehicles_yellow_id', trans('Plaque').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('mant_resume_machinery_vehicles_yellow_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_resume_machinery_vehicles_yellow_id }", 'v-model' => 'dataForm.mant_resume_machinery_vehicles_yellow_id', 'required' => true]) !!}
                <small>@lang('Enter the') la placa del vehículo</small>
                <div class="invalid-feedback" v-if="dataErrors.mant_resume_machinery_vehicles_yellow_id">
                    <p class="m-b-0" v-for="error in dataErrors.mant_resume_machinery_vehicles_yellow_id">@{{ error }}</p>
                </div>
            </div>

            <!-- Dependencies Id Field -->
            {!! Form::label('asset_name','Nombre del activo'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('asset_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.asset_name }", 'v-model' => 'dataForm.asset_name', 'required' => true, 'disabled' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.asset_name">
                    <p class="m-b-0" v-for="error in dataErrors.asset_name">@{{ error }}</p>
                </div>
            </div>
        
        </div>

        <!-- Mant Asset Type Id Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('mant_asset_type_id', trans('Mant_asset_type').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('mant_asset_type_id', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.mant_asset_type_id }", 'v-model' => 'dataForm.mant_asset_type_id', 'required' => true, 'disabled' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.mant_asset_type_id">
                    <p class="m-b-0" v-for="error in dataErrors.mant_asset_type_id">@{{ error }}</p>
                </div>
            </div>

            <!-- Asset Name Field -->  
            {!! Form::label('asset_name','Proceso'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('asset_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.asset_name }", 'v-model' => 'dataForm.asset_name', 'required' => true, 'disabled' => true]) !!}
                <div class="invalid-feedback" v-if="dataErrors.asset_name">
                    <p class="m-b-0" v-for="error in dataErrors.asset_name">@{{ error }}</p>
                </div>
            </div>
        </div>

        <!-- Invoice Number Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('invoice_number', trans('Invoice Number').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('invoice_number', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.invoice_number }", 'v-model' => 'dataForm.invoice_number', 'required' => true]) !!}
                <small>@lang('Enter the') el número de la factura</small>
                <div class="invalid-feedback" v-if="dataErrors.invoice_number">
                    <p class="m-b-0" v-for="error in dataErrors.invoice_number">@{{ error }}</p>
                </div>
            </div>

            <!-- Invoice Date Field -->
            {!! Form::label('invoice_date','Fecha de factura'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                <date-picker
                    :value="dataForm"
                    name-field="invoice_date"
                    :input-props="{required: true}"
                >
                </date-picker>
                <small>@lang('Enter the') la fecha de la factura</small>
                <div class="invalid-feedback" v-if="dataErrors.invoice_date">
                    <p class="m-b-0" v-for="error in dataErrors.invoice_date">@{{ error }}</p>
                </div>
            </div>
        </div>


        <!-- Tanking Hour Field -->
        <div class="form-group row m-b-15">
            {!! Form::label('tanking_hour', 'Hora de tanqueo'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('tanking_hour', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.tanking_hour }", 'v-model' => 'dataForm.tanking_hour', 'required' => true]) !!}
                <small>@lang('Enter the') la hora de tanqueo(Ejemplo: 1.5)</small>
                <div class="invalid-feedback" v-if="dataErrors.tanking_hour">
                    <p class="m-b-0" v-for="error in dataErrors.tanking_hour">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Driver Name Field -->
            {!! Form::label('driver_name', 'Nombre del conductor'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('driver_name', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.driver_name }", 'v-model' => 'dataForm.driver_name', 'required' => true]) !!}
                <small>@lang('Enter the') el nombre del conductor</small>
                <div class="invalid-feedback" v-if="dataErrors.driver_name">
                    <p class="m-b-0" v-for="error in dataErrors.driver_name">@{{ error }}</p>
                </div>
            </div>

            <!-- Fuel Type Field -->
            {!! Form::label('fuel_type', trans('Fuel Type').':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::text('fuel_type', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fuel_type }", 'v-model' => 'dataForm.fuel_type', 'required' => true]) !!}
                <small>@lang('Enter the') el @{{ `@lang('Fuel Type')` | lowercase }}</small>
                <div class="invalid-feedback" v-if="dataErrors.fuel_type">
                    <p class="m-b-0" v-for="error in dataErrors.fuel_type">@{{ error }}</p>
                </div>
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Current Mileage Field -->
            {!! Form::label('current_mileage', 'Kilometraje actual'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('current_mileage', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.current_mileage }", 'v-model' => 'dataForm.current_mileage', 'required' => true]) !!}
                <small style="font-size:9px">@lang('Enter the') el kilometraje actual(Ejemplo: 2.200)</small>
                <div class="invalid-feedback" v-if="dataErrors.current_mileage">
                    <p class="m-b-0" v-for="error in dataErrors.current_mileage">@{{ error }}</p>
                </div>
            </div>

        <!-- Fuel Quantity Field -->
            {!! Form::label('fuel_quantity', 'Cantidad de combustible'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('fuel_quantity', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.fuel_quantity }", 'v-model' => 'dataForm.fuel_quantity', 'required' => true]) !!}
                <small style="font-size:9px"> @lang('Enter the') la cantidad de combustible(Ejemplo: 15.2)</small>
                <div class="invalid-feedback" v-if="dataErrors.fuel_quantity">
                    <p class="m-b-0" v-for="error in dataErrors.fuel_quantity">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Gallon Price Field -->
            {!! Form::label('gallon_price', 'Precio de galón'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('gallon_price', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.gallon_price }", 'v-model' => 'dataForm.gallon_price', 'required' => true]) !!}
                <small style="font-size:9px"> @lang('Enter the') precio de galón(Ejemplo: 8.200)</small>
                <div class="invalid-feedback" v-if="dataErrors.gallon_price">
                    <p class="m-b-0" v-for="error in dataErrors.gallon_price">@{{ error }}</p>
                </div>
            </div>

            <!-- Total Price Field -->
            {!! Form::label('total_price', 'Precio total'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('total_price', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.total_price }", 'v-model' => 'dataForm.total_price = dataForm.fuel_quantity * dataForm.gallon_price', 'required' => true, 'disabled' => true]) !!}
                <small style="font-size:9px">  @lang('Enter the') precio total combustible</small>
                <div class="invalid-feedback" v-if="dataErrors.total_price">
                    <p class="m-b-0" v-for="error in dataErrors.total_price">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Current Hourmeter Field -->
            {!! Form::label('current_hourmeter', 'Horometro actual'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('current_hourmeter', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.current_hourmeter }", 'v-model' => 'dataForm.current_hourmeter', 'required' => true]) !!}
                <small style="font-size:9px"> @lang('Enter the') el horometro actual(Ejemplo: 1.2)</small>
                <div class="invalid-feedback" v-if="dataErrors.current_hourmeter">
                    <p class="m-b-0" v-for="error in dataErrors.current_hourmeter">@{{ error }}</p>
                </div>
            </div>

            <!-- Previous Hourmeter Field -->
            {!! Form::label('previous_hourmeter', 'Horometro anterior'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('previous_hourmeter', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.previous_hourmeter }", 'v-model' => 'dataForm.previous_hourmeter', 'required' => true]) !!}
                <small style="font-size:9px"> Horometro anterior</small>
                <div class="invalid-feedback" v-if="dataErrors.previous_hourmeter">
                    <p class="m-b-0" v-for="error in dataErrors.previous_hourmeter">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Variation Tanking Hour Field -->
            {!! Form::label('variation_tanking_hour', 'Variaciones de horas en los tanqueos'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('variation_tanking_hour', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.variation_tanking_hour }", 'v-model' => 'dataForm.variation_tanking_hour = dataForm.current_hourmeter - dataForm.previous_hourmeter', 'required' => true, 'disabled' => true]) !!}
                <small style="font-size:9px"> Variacion de horas en los tanqueos  </small>
                <div class="invalid-feedback" v-if="dataErrors.variation_tanking_hour">
                    <p class="m-b-0" v-for="error in dataErrors.variation_tanking_hour">@{{ error }}</p>
                </div>
            </div>

            <!-- Previous Mileage Field -->
            {!! Form::label('previous_mileage', 'Kilometraje anterior'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('previous_mileage', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.previous_mileage }", 'v-model' => 'dataForm.previous_mileage', 'required' => true]) !!}
                <small style="font-size:9px">@lang('Enter the') el kilometraje actual(Ejemplo: 2.200)</small>
                <div class="invalid-feedback" v-if="dataErrors.previous_mileage">
                    <p class="m-b-0" v-for="error in dataErrors.previous_mileage">@{{ error }}</p>
                </div>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Variation Route Hour Field -->
            {!! Form::label('variation_route_hour', 'Variación en km recorridos por tanqueo'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('variation_route_hour', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.variation_route_hour }", 'v-model' => 'dataForm.variation_route_hour = dataForm.current_mileage - dataForm.previous_mileage', 'required' => true, 'disabled' => true]) !!}
                <small style="font-size:9px">Variación en km recorridos por tanqueo</small>
                <div class="invalid-feedback" v-if="dataErrors.variation_route_hour">
                    <p class="m-b-0" v-for="error in dataErrors.variation_route_hour">@{{ error }}</p>
                </div>
            </div>

            <!-- Performance By Gallon Field -->
            {!! Form::label('performance_by_gallon', 'Rendimiento km por galón'.':', ['class' => 'col-form-label col-md-2 required']) !!}
            <div class="col-md-4">
                {!! Form::number('performance_by_gallon', null, [':class' => "{'form-control':true, 'is-invalid':dataErrors.performance_by_gallon }", 'v-model' => 'dataForm.performance_by_gallon =  dataForm.variation_route_hour / dataForm.fuel_quantity ', 'required' => true, 'disabled' => true]) !!}
                <small style="font-size:9px"> Rendimiento km por galón</small>
                <div class="invalid-feedback" v-if="dataErrors.performance_by_gallon">
                    <p class="m-b-0" v-for="error in dataErrors.performance_by_gallon">@{{ error }}</p>
                </div>
            </div>
        </div>

        

    </div>
</div>

