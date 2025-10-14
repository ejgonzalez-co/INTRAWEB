<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Datos del activo TIC</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Ht Tic Period Validity Id Field -->
         <!-- <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Period Validities'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.tic_period_validity? dataShow.tic_period_validity.name: '' }}.</dd> -->
         
         <!-- Dependencias Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Dependency'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.dependencias? dataShow.dependencias.nombre: 'N/A' }}.</dd>

         <!-- Location Address Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Address'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.location_address ?? 'N/A' }}.</dd>

      </div>
   </div>
   <!-- end panel-body -->
</div>

<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Datos generales del activo Tic</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">

         <!-- Ht Tic Type Assets Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Tic Type Assets'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.tic_type_assets? dataShow.tic_type_assets.name: 'N/A' }}.</dd>

         <!-- Consecutive Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Consecutive'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.consecutive ?? 'N/A' }}.</dd>

         <!-- Name Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Name'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.name ?? 'N/A' }}.</dd>

         <!-- Inventory Plate Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Inventory Plate'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.inventory_plate ?? 'N/A' }}.</dd>

         <!-- Serial Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Serial'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.serial ?? 'N/A' }}.</dd>

         <!-- Brand Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Brand'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.brand ?? 'N/A' }}.</dd>
         
         <!-- Model Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Model'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.model ?? 'N/A' }}.</dd>

         <!-- General Description Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('General Description'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.general_description ?? 'N/A' }}.</dd>

      </div>

      <div class="row" v-if="dataShow.operating_system">
         <!-- Processor Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Processor'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.processor ?? 'N/A' }}.</dd>

         <!-- Ram Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Ram'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.ram ?? 'N/A' }}.</dd>

         <!-- Hdd Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Hdd'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.hdd ?? 'N/A' }}.</dd>


         <!-- Operating System Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Operating System'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.operating_systems_info.name ?? 'N/A' }}.</dd>


         <!-- Serial Microsoft License Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Serial Microsoft License'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.operating_system_serial ?? 'N/A' }}.</dd>

         <!-- License Microsoft Office Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('License Microsoft Office'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.office_automation_versions_info.name ?? 'N/A' }}.</dd>

         <!-- Serial Licencia Microsoft Office Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Serial Licencia Microsoft Office'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.serial_licencia_microsoft_office ?? 'N/A' }}.</dd>


         
         <!-- Monitor Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Monitor'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.monitor_id ?? 'N/A' }}.</dd>

         <!-- Keyboard Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Keyboard'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.keyboard_id ?? 'N/A' }}.</dd>

         <!-- Mouse Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Mouse'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.mouse_id ?? 'N/A' }}.</dd>
         
      </div>
      <div class="row">
         <!-- Provider Name Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Provider'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.provider_name ?? 'N/A' }}.</dd>

         <!-- Purchase Date Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('Purchase Date'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.purchase_date ?? 'N/A' }}.</dd>

         <!-- User Id Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('User'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.users? dataShow.users.name : 'N/A' }}.</dd>

         <!-- State Field -->
         <dt class="text-inverse text-left col-sm-3 col-md-3 col-lg-3">@lang('State'):</dt>
         <dd class="col-sm-9 col-md-9 col-lg-9">@{{ dataShow.state_name ?? 'N/A' }}.</dd>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.tic_assets_histories? dataShow.tic_assets_histories.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Historial del activo:</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <table class="table table-hover m-b-0">
            <thead>
               <tr>
                  <th>@lang('Created_at')</th>
                  <th>@lang('Consecutive')</th>
                  <th>@lang('Name')</th>
                  <th>@lang('Serial')</th>
                  <th>@lang('Inventory Plate')</th>
                  <th>@lang('Category')</th>
                  <th>@lang('dependency')</th>
                  <th>@lang('Provider Name')</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(ticAssetsHistory, key) in dataShow.tic_assets_histories" :key="key">
                  <td>@{{ ticAssetsHistory.created_at }}</td>
                  <td>@{{ ticAssetsHistory.consecutive }}</td>
                  <td>@{{ ticAssetsHistory.name }}</td>
                  <td>@{{ ticAssetsHistory.serial }}</td>
                  <td>@{{ ticAssetsHistory.inventory_plate }}</td>
                  <td>@{{ ticAssetsHistory.tic_type_assets ? ticAssetsHistory.tic_type_assets.name: 'N/A' }}</td>
                  <td>@{{ ticAssetsHistory.dependencias ? ticAssetsHistory.dependencias.nombre: 'N/A' }}</td>
                  <td>@{{ ticAssetsHistory.provider_name }}</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end panel-body -->
</div>

<div v-if="dataShow.tic_maintenances? dataShow.tic_maintenances.length > 0 : ''" class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title"><strong>Mantenimientos</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
         <table class="table table-hover m-b-0">
            <thead>
               <tr>
                  <th>@lang('Created_at')</th>
                  <th>@lang('Type Maintenance')</th>
                  <th>@lang('Descripción Falla o Daño')</th>
                  <th>@lang('Fecha de inicio')</th>
                  <th>@lang('Fecha de terminado')</th>
                  <th>@lang('Maintenance Status')</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="(maintenance, key) in dataShow.tic_maintenances" :key="key">
                  <td>@{{ maintenance.created_at }}</td>
                  <td>@{{ maintenance.type_maintenance_name }}</td>
                  <td>@{{ maintenance.fault_description }}</td>
                  <td>@{{ maintenance.service_start_date }}</td>
                  <td>@{{ maintenance.end_date_service }}</td>
                  <td>@{{ maintenance.maintenance_status_name }}</td>
            </tbody>
         </table>
      </div>
   </div>
   <!-- end panel-body -->
</div>
