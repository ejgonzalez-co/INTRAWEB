<div class="panel col-md-12 mt-2" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle" style="padding-bottom: 0px;">
      <h3 class="panel-title" style="font-size: 13px;"><strong>Información del cliente.</strong></h3>
   </div>
   <div class="panel-body">
      <div class="row">
         <!-- Name Field -->
         <dt class="text-inverse text-left col-3 ">@lang('Name'):</dt>
         <dd class="col-9 ">@{{ dataShow.name }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Password Field -->
         <dt class="text-inverse text-left col-8 ">@lang('report_query'):</dt>
         <dd class="col-4 ">@{{ dataShow.query_report }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Identification Number Field -->
         <dt class="text-inverse text-left col-3 ">@lang('Identification Number'):</dt>
         <dd class="col-9 ">@{{ dataShow.identification_number }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Email Field -->
         <dt class="text-inverse text-left col-3 ">@lang('Email'):</dt>
         <dd class="col-9 ">@{{ dataShow.email }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Telephone Field -->
         <dt class="text-inverse text-left col-3 ">Telefono:</dt>
         <dd class="col-9 ">@{{ dataShow.telephone }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Extension Field -->
         <dt class="text-inverse text-left col-3 ">@lang('Extension'):</dt>
         <dd class="col-9 ">@{{ dataShow.extension }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- Cell Number Field -->
         <dt class="text-inverse text-left col-3 ">@lang('Cell Number'):</dt>
         <dd class="col-9 ">@{{ dataShow.cell_number }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- State Field -->
         <dt class="text-inverse text-left col-3 ">Direccion:</dt>
         <dd class="col-9 ">@{{ dataShow.direction }}.</dd>
      </div>
      
      
      <div class="row">
         <!-- State Field -->
         <dt class="text-inverse text-left col-3 ">@lang('State'):</dt>
         <dd class="col-9 ">@{{ dataShow.publication_status }}.</dd>
      </div>
      
      <div class="row mt-2">
         <!-- Name User Field -->
         <dt class="text-inverse text-left col-3 ">Descripción:</dt>
         <dd class="col-9 ">@{{ dataShow.description }}.</dd>
      </div>
      


   </div>
</div>




<!-- Panel -->
<div class="panel col-md-12 mt-2" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle" style="padding-bottom: 0px;">
      <h3 class="panel-title" style="font-size: 13px;"><strong>Credencial única de acceso a clientes para consulta de informes.</strong></h3>
   </div>
   <div class="panel-body">
      <div class="row">
         <!-- Name User Field -->
         <dt class="text-inverse text-left col-2 ">Credencial:</dt>
         <dd class="col-9 ">@{{ dataShow.pin }}</dd>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<!-- Panel -->
<div class="panel col-md-12 mt-2" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle" style="padding-bottom: 0px;">
      <h3 class="panel-title" style="text-align: center; font-size: 13px;"><strong>Lista de los puntos de muestra</strong></h3>
   </div>
   <div class="panel-body">
      <div class="row">
            <div class="row" style="margin: auto;">
               <div class="table-responsive">
                  <table class="table table-responsive table-bordered">
                        <thead>
                           <tr>
                              <th>Puntos de muestra</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr v-for="(category, key) in dataShow.point_location">
                              <td>@{{ category.point_location }}</td>
                           </tr>
                        </tbody>
                  </table>
               </div>
            </div>
      </div>
   </div>
   <!-- end panel-body -->
</div>