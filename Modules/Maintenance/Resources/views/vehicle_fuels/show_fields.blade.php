<div class="panel" data-sortable-id="ui-general-1">

   <div class="panel-body">


      <div class="row">
         <!-- Previous Mileage Field -->
         <dt class="text-inverse text-left col-5 text-truncate">Fecha de modificación:</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.updated_at }}.</dd>

         
         <!-- Dependencies Id Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Process'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.dependencias ? dataShow.dependencias.nombre: ''}}.</dd>


         <!-- Mant Resume Machinery Vehicles Yellow Id Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Plaque'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.resume_machinery_vehicles_yellow ? dataShow.resume_machinery_vehicles_yellow.plaque: '' }}.</dd>


         <!-- Mant Asset Type Id Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Mant_asset_type'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.asset_type ? dataShow.asset_type.name:'' }}.</dd>


         <!-- Asset Name Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Asset Name'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.asset_name }}.</dd>


         <!-- Invoice Number Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Invoice number'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.invoice_number }}.</dd>


         <!-- Invoice Date Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Invoice Date'):</dt>
         <dd class="col-5 text-truncate">@{{ formatDate(dataShow.invoice_date) }}.</dd>


         <!-- Tanking Hour Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Tanking Hour'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.tanking_hour }} Hrs.</dd>


         <!-- Driver Name Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Driver Name'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.driver_name }}.</dd>


         <!-- Fuel Type Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Fuel Type'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.resume_machinery_vehicles_yellow ? dataShow.resume_machinery_vehicles_yellow.fuel_type: '' }}.</dd>


         <!-- Current Mileage Field -->
         <dt v-if="dataShow.current_mileage" class="text-inverse text-left col-5 text-truncate">@lang('Current Mileage'):</dt>
         <dd v-if="dataShow.current_mileage" class="col-5 text-truncate">@{{ dataShow.current_mileage }} KM.</dd>


         <!-- Fuel Quantity Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Quantity Fuel'):</dt>
         <dd class="col-5 text-truncate">@{{ dataShow.fuel_quantity }} G.</dd>


         <!-- Gallon Price Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Gallon Price'):</dt>
         <dd class="col-5 text-truncate"> $@{{ currencyFormat(dataShow.gallon_price) }}.</dd>


         <!-- Total Price Field -->
         <dt class="text-inverse text-left col-5 text-truncate">@lang('Total Price'):</dt>
         <dd class="col-5 text-truncate">  $@{{ currencyFormat(dataShow.total_price) }} </dd>
                  
         <!-- Current Hourmeter Field -->
         <dt v-if="dataShow.current_hourmeter" class="text-inverse text-left col-5 text-truncate">@lang('Current Hourmeter'):</dt>
         <dd v-if="dataShow.current_hourmeter" class="col-5 text-truncate">@{{ dataShow.current_hourmeter }} HR.</dd>


         <!-- Previous Hourmeter Field -->
         <dt v-if="dataShow.previous_hourmeter" class="text-inverse text-left col-5 text-truncate">@lang('Previous Hourmeter'):</dt>
         <dd v-if="dataShow.previous_hourmeter" class="col-5 text-truncate">@{{ dataShow.previous_hourmeter }} HR.</dd>


         <!-- Variation Tanking Hour Field -->
         <dt v-if="dataShow.variation_tanking_hour" class="text-inverse text-left col-5 text-truncate">@lang('Variation Tanking Hour'):</dt>
         <dd v-if="dataShow.variation_tanking_hour" class="col-5 text-truncate">@{{ dataShow.variation_tanking_hour }} HR.</dd>


         <!-- Previous Mileage Field -->
         <dt v-if="dataShow.previous_mileage" class="text-inverse text-left col-5 text-truncate">@lang('Previous Mileage'):</dt>
         <dd v-if="dataShow.previous_mileage" class="col-5 text-truncate">@{{ dataShow.previous_mileage }} KM.</dd>


         <!-- Variation Route Hour Field -->
         <dt v-if="dataShow.variation_route_hour" class="text-inverse text-left col-5 text-truncate">@lang('Variation Route Hour'):</dt>
         <dd v-if="dataShow.variation_route_hour" class="col-5 text-truncate">@{{ dataShow.variation_route_hour }} KM.</dd>


         <!-- Performance By Gallon Field -->
         <dt class="text-inverse text-left col-5 text-truncate">Rendimiento por galón:</dt>
         <dd v-if="dataShow.variation_route_hour" class="col-5 text-truncate">@{{ currencyFormat(dataShow.performance_by_gallon) }} KM/G</dd>
         <dd v-if="dataShow.variation_tanking_hour" class="col-5 text-truncate">@{{ currencyFormat(dataShow.performance_by_gallon) }} HR/G</dd>

      </div>
   </div>

</div>


<div class="panel" v-if="dataShow.attached_invoce && dataShow.photo_tachometer_hourmeter">
   <div class="panel-heading">
      <div class="panel-title">
         <strong>Archivos adjuntos al registro de combustible</strong>
      </div>
   </div>
   <div class="panel-body" v-if="dataShow.attached_invoce != null && dataShow.photo_tachometer_hourmeter != null">
      <div class="row">
         <p class="ml-2 mr-4">Adjunto de la factura de combustible:</p>
         <a :href="'{{ asset('storage') }}/' + dataShow.attached_invoce" target="_blank">Ver adjunto</a>
      </div>
      <div class="row">
         <p class="ml-2 mr-4">Adjunto del tacómetro u horómetro:</p>
         <a :href="'{{ asset('storage') }}/' + dataShow.photo_tachometer_hourmeter" target="_blank">Ver adjunto</a>
      </div>
   </div>
</div>

<div class="panel" v-if="dataShow.fuel_documents?.length">
   <div class="panel-heading">
      <div class="panel-title">
         <strong>Otros documentos</strong>
      </div>
   </div>
   <div class="panel-body" v-for="(fuelDocument,key) in dataShow.fuel_documents" :key="key">
      <div class="row" v-for="(document,key) in fuelDocument.url_document_fuel.split(',')" :key="key">
         <p class="ml-2 mr-4">Documento:</p>
         <a :href="'{{ asset('storage') }}/' + document" target="_blank">Ver adjunto</a>
      </div>
   </div>
</div>



