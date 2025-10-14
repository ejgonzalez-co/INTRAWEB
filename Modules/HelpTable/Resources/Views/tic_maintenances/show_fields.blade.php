<div class="panel" data-sortable-id="ui-general-1">
   <!-- begin panel-heading -->
   <div class="panel-heading ui-sortable-handle">
         <h4 class="panel-title"><strong>Información general</strong></h4>
   </div>
   <!-- end panel-heading -->
   <!-- begin panel-body -->
   <div class="panel-body">
      <div class="row">
          <!-- Fila 1 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Type Maintenance'):</dt>
              <dd>@{{ dataShow.type_maintenance_name || '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Maintenance Status'):</dt>
              <dd>@{{ dataShow.maintenance_status_name || '-' }}</dd>
          </div>
  
          <!-- Fila 2 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Fault Description'):</dt>
              <dd style="white-space: pre-line">@{{ dataShow.fault_description || '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Maintenance Description'):</dt>
              <dd style="white-space: pre-line">@{{ dataShow.maintenance_description || '-' }}</dd>
          </div>
  
          <!-- Fila 3 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Service Start Date'):</dt>
              <dd>@{{ dataShow.service_start_date ? formatDate(dataShow.service_start_date) : '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('End Date Service'):</dt>
              <dd>@{{ dataShow.end_date_service ? formatDate(dataShow.end_date_service) : '-' }}</dd>
          </div>
  
          <!-- Fila 4 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Contract Number'):</dt>
              <dd>@{{ dataShow.contract_number || '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Cost'):</dt>
              <dd>@{{ dataShow.cost ? '$' + dataShow.cost : '-' }}</dd>
          </div>
  
          <!-- Fila 5 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Warranty Start Date'):</dt>
              <dd>@{{ dataShow.warranty_start_date ? formatDate(dataShow.warranty_start_date) : '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Warranty End Date'):</dt>
              <dd>@{{ dataShow.warranty_end_date ? formatDate(dataShow.warranty_end_date) : '-' }}</dd>
          </div>
  
          <!-- Fila 6 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Active Tic'):</dt>
              <dd>@{{ dataShow.tic_assets?.name || '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Provider'):</dt>
              <dd>@{{ dataShow.tic_provider?.users?.name || '-' }}</dd>
          </div>
  
          <!-- Fila 7 -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Request'):</dt>
              <dd>@{{ dataShow.tic_requests?.affair || '-' }}</dd>
          </div>
          
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Dependency'):</dt>
              <dd>@{{ dataShow.dependencias?.nombre || '-' }}</dd>
          </div>
  
          <!-- Fila 8 (solo un item) -->
          <div class="col-md-6 mb-3">
              <dt class="text-inverse text-left">@lang('Funcionario'):</dt>
              <dd>@{{ dataShow.user_name || '-' }}</dd>
          </div>
      </div>
  </div>

   <div class="panel"
      data-sortable-id="ui-general-1">
      <div class="panel-heading">
          <div class="panel-title"><strong>Lista de chequeo mantenimiento</strong></div>
      </div>
      <div class="panel-body">
          <div class="table-responsive">
              <table class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th width="40%"><strong>Mantenimiento</strong></th>
                          <th width="20%"><strong>Realizado</strong></th>
                          <th width="40%"><strong>Observación</strong></th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Has Internal And External Hardware Cleaning -->
                      <tr>
                          <td>{{ trans('Has Internal And External Hardware Cleaning') }}</td>
                          <td>@{{ dataShow.has_internal_and_external_hardware_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_internal_and_external_hardware_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Ram Cleaning -->
                      <tr>
                          <td>{{ trans('Has Ram Cleaning') }}</td>
                          <td>@{{ dataShow.has_ram_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_ram_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Board Memory Cleaning -->
                      <tr>
                          <td>{{ trans('Has Board Memory Cleaning') }}</td>
                          <td>@{{ dataShow.has_board_memory_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_board_memory_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Power Supply Cleaning -->
                      <tr>
                          <td>{{ trans('Has Power Supply Cleaning') }}</td>
                          <td>@{{ dataShow.has_power_supply_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_power_supply_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Dvd Drive Cleaning -->
                      <tr>
                          <td>{{ trans('Has Dvd Drive Cleaning') }}</td>
                          <td>@{{ dataShow.has_dvd_drive_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_dvd_drive_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Monitor Cleaning -->
                      <tr>
                          <td>{{ trans('Has Monitor Cleaning') }}</td>
                          <td>@{{ dataShow.has_monitor_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_monitor_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Keyboard Cleaning -->
                      <tr>
                          <td>{{ trans('Has Keyboard Cleaning') }}</td>
                          <td>@{{ dataShow.has_keyboard_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_keyboard_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Mouse Cleaning -->
                      <tr>
                          <td>{{ trans('Has Mouse Cleaning') }}</td>
                          <td>@{{ dataShow.has_mouse_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_mouse_cleaning || '-' }}</td>
                      </tr>
  
                      <!-- Has Thermal Paste Change -->
                      <tr>
                          <td>{{ trans('Has Thermal Paste Change') }}</td>
                          <td>@{{ dataShow.has_thermal_paste_change || '-' }}</td>
                          <td>@{{ dataShow.observation_thermal_paste_change || '-' }}</td>
                      </tr>
  
                      <!-- Has Heatsink Cleaning -->
                      <tr>
                          <td>{{ trans('Has Heatsink Cleaning') }}</td>
                          <td>@{{ dataShow.has_heatsink_cleaning || '-' }}</td>
                          <td>@{{ dataShow.observation_heatsink_cleaning || '-' }}</td>
                      </tr>
                  </tbody>
              </table>
          </div>
  
          <!-- Technical Report -->
          <div class="form-group row m-b-15">
              <label class="col-form-label col-md-3">{{ trans('Technical Report') }}:</label>
              <div class="col-md-8">
                  <p class="form-control-static">@{{ dataShow.technical_report || '-' }}</p>
              </div>
          </div>
  
          <!-- General Observation -->
          <div class="form-group row m-b-15">
              <label class="col-form-label col-md-3">{{ trans('Observation') }}:</label>
              <div class="col-md-8">
                  <p class="form-control-static">@{{ dataShow.observation || '-' }}</p>
              </div>
          </div>
      </div>
  </div>
  
   <!-- end panel-body -->
</div>
