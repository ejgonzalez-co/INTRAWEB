<div class="panel" data-sortable-id="ui-general-1">

   <div class="panel-body">

      <div class="form-group row m-b-15">
         <!-- Register Date Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Fecha de registro:</dt>
         <dd class="col-3 text-truncate">@{{ formatDate(dataShow.register_date) }}.</dd>
      <!-- Mant Resume Machinery Vehicles Yellow Id Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Placa:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.resume_machinery_vehicles_yellow ? dataShow.resume_machinery_vehicles_yellow.plaque: '' }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <!-- Mant Oil Element Wear Configurations Id Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Nombre del activo:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.asset_name }}.</dd>

         <!-- Mant Asset Type Id Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Tipo de activo:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.asset_type ? dataShow.asset_type.name:'' }}.</dd>
      </div>

      <div class="form-group row m-b-15">

      <!-- Dependencies Id Field -->
      <dt class="text-inverse text-left col-3 text-truncate">Proceso:</dt>
      <dd class="col-3 text-truncate">@{{ dataShow.dependencias ? dataShow.dependencias.nombre: ''}}.</dd>

      <!-- Show Type Field -->
      <dt class="text-inverse text-left col-3 text-truncate">Tipo de muestra:</dt>
      <dd class="col-3 text-truncate">@{{ dataShow.show_type }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <dt class="text-inverse text-left col-3 text-truncate">@lang('Component'):</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.component_name }}.</dd>

         <dt class="text-inverse text-left col-3 text-truncate">Número de equipo:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.equipment_number }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <!-- Brand Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Marca:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.brand }}.</dd>

         <!-- Serial Number Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Número de serie:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.serial_number }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <!-- Work Order Field -->
         <dt class="text-inverse text-left col-3 text-truncate">@lang('work_order'):</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.work_order }}.</dd>
         <!-- Model Component Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Modelo del componente:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.model_component }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <!-- Date Finished Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Fecha de termino:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.date_finished_warranty_extended }}.</dd>
         
         <!-- Serial Component Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Serie componente:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.serial_component }}.</dd>
      </div>

      <div class="form-group row m-b-15">
         <!-- Number Control Lab Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Número control lab:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.number_control_lab }}.</dd>

         <!-- Maker Component Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Fabricante del componente:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.maker_component }}.</dd>
      </div>

      <div class="form-group row m-b-15">

         <!-- Grade Oil Field -->
         <dt class="text-inverse text-left col-3 text-truncate">Marca/grado aceite:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.grade_oil }}.</dd>


      </div>

   </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">

   <div class="panel-heading ui-sortable-handle">
      <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Control laboratorio</strong></h3>
   </div>

   <div class="panel-body">

      <div class="row">
         <div class="row" style="margin: auto;">
             <div class="table-responsive">
                 <table class="table table-responsive table-bordered">
                     <thead>
                         <tr>
                             <th>Fecha de muestreo</th>
                             <th>Fecha de proceso</th>
                             <th>Hórometro</th>
                             <th>Horas aceite</th>
                             <th>Kilometraje</th>
                             <th>Relleno</th>
                             <th>¿Cambio aceite?</th>
                             <th>¿Cambio de filtro?</th>
                             <th>Unidades del relleno</th>
                             <th>Observación</th>


                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="(laboratories, key) in dataShow.oil_control_laboratories">
                             <td>@{{ laboratories.date_sampling }}</td>
                             <td>@{{ laboratories.date_process }}</td>
                             <td>@{{ laboratories.hourmeter }} Hr</td>
                             <td>@{{ laboratories.oil_hours }}</td>
                             <td>@{{ laboratories.kilometer }} Km</td>
                             <td>@{{ laboratories.filling }}</td>
                             <td>@{{ laboratories.change_oil }}</td>
                             <td>@{{ laboratories.change_filter }}</td>
                             <td>@{{ laboratories.filling_units }}</td>
                             <td>@{{ laboratories.observation }}</td>

                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>

   
   </div>
</div>

<div class="panel" data-sortable-id="ui-general-1">

   <div class="panel-heading ui-sortable-handle">
      <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Elementos de desgaste</strong></h3>
  </div>

   <div class="panel-body">

      <div class="row">
         <div class="row" style="margin: auto;">
             <div class="table-responsive">
                 <table class="table table-responsive table-bordered">
                     <thead>
                         <tr>
                             <th>Número de control de laboratorio</th>
                             <th>Nombre del elemento de desgaste</th>
                             <th>Grupo</th>
                             <th>Valor detectado</th>
                             <th>Rango</th>
                         </tr>
                     </thead>
                     <tbody>
                         <tr v-for="(element, key) in dataShow.oil_element_wears">
                             <td>@{{ element.number_control_laboratory }}</td>
                             <td>@{{ element.oil_element_wear_configurations?.element_name }}</td>
                             <td>@{{ element.oil_element_wear_configurations?.group ? element.oil_element_wear_configurations?.group :'N/A' }}</td>
                             <td>@{{ element.detected_value }}</td>
                             <td v-if="element.range=='Alto'" style="background: red;" class="text-light">@{{ element.range }}</td>
                             <td v-else-if="element.range=='Bajo'" style="background: blue;" class="text-light">@{{ element.range }}</td>
                             <td v-else style="background: #12E54F;" class="text-dark">@{{ element.range }}</td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>

      {{-- <div class="form-group row m-b-15">
         <dt class="text-inverse text-left col-3 text-truncate">Número de control de laboratorio:</dt>
         <dd class="col-3 text-truncate">@{{ dataShow.oil_element_wears.oil_element_wear_configurations ? dataShow.number_control_laboratory.oil_element_wear_configurations.element_name: ''}}.</dd>

      </div> --}}

   </div>
</div>





