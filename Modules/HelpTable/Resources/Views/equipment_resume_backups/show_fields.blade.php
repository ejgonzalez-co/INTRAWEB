<div class="panel">
   <div class="panel-heading">
       <div class="panel-title">
           <strong>Datos del equipo</strong>
       </div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Asset Type Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Equip Type'):</strong>
           <p class="col-3 text-break">@{{ dataShow.asset_type }}.</p>
       </div>
   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Datos del cliente</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Domain User Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Usuario del dominio (EPA. LOCAL)'):</strong>
           <p class="col-3 text-break">@{{ dataShow.domain_user }}.</p>

           <!-- Officer Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Officer'):</strong>
           <p class="col-3 text-break">@{{ dataShow.officer }}.</p>
       </div>

       <div class="row">
           <!-- Contract Type Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Type'):</strong>
           <p class="col-3 text-break">@{{ dataShow.contract_type }}.</p>

           <!-- Charge Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Charge'):</strong>
           <p class="col-3 text-break">@{{ dataShow.charge }}.</p>
       </div>

       <div class="row">
           <!-- Dependence Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Dependence'):</strong>
           <p class="col-3 text-break">@{{ dataShow.dependence }}.</p>

           <!-- Area Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Area'):</strong>
           <p class="col-3 text-break">@{{ dataShow.area }}.</p>
       </div>

       <div class="row">
           <!-- Site Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Site'):</strong>
           <p class="col-3 text-break">@{{ dataShow.site }}.</p>

           <!-- Service Manager Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Service Manager'):</strong>
           <p class="col-3 text-break">@{{ dataShow.service_manager }}.</p>
       </div>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Mantenimiento</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Maintenance Type Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Maintenance Type'):</strong>
           <p class="col-3 text-break">@{{ dataShow.maintenance_type }}.</p>

           <!-- Cycle Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Cycle'):</strong>
           <p class="col-3 text-break">@{{ dataShow.cycle }}.</p>
       </div>

       <div class="row">
           <!-- Contract Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.contract_number }}.</p>

           <!-- Contract Date Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Date'):</strong>
           <p class="col-3 text-break">@{{ dataShow.contract_date ? formatDate(dataShow.contract_date) : "N/A" }}.</p>
       </div>

       <div class="row">
           <!-- Maintenance Date Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Maintenance Date'):</strong>
           <p class="col-3 text-break">@{{ dataShow.maintenance_date ? formatDate(dataShow.maintenance_date) : "N/A" }}.</p>

           <!-- Provider Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Provider'):</strong>
           <p class="col-3 text-break">@{{ dataShow.provider }}.</p>
       </div>

       <div class="row">
           <!-- Contract Value Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Value'):</strong>
           <p class="col-3 text-break">@{{ dataShow.contract_value }}.</p>
       </div>
   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Lista de chequeo mantenimiento</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Has Internal And External Hardware Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Internal And External Hardware Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_internal_and_external_hardware_cleaning }}.</p>

           <!-- Observation Internal And External Hardware Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_internal_and_external_hardware_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Ram Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Ram Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_ram_cleaning }}.</p>

           <!-- Observation Ram Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_ram_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Board Memory Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Board Memory Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_board_memory_cleaning }}.</p>

           <!-- Observation Board Memory Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_board_memory_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Power Supply Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Power Supply Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_power_supply_cleaning }}.</p>

           <!-- Observation Power Supply Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_power_supply_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Dvd Drive Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Dvd Drive Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_dvd_drive_cleaning }}.</p>

           <!-- Observation Dvd Drive Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_dvd_drive_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Monitor Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Monitor Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_monitor_cleaning }}.</p>

           <!-- Observation Monitor Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_monitor_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Keyboard Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Keyboard Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_keyboard_cleaning }}.</p>

           <!-- Observation Keyboard Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_keyboard_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Mouse Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Mouse Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_mouse_cleaning }}.</p>

           <!-- Observation Mouse Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_mouse_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Has Thermal Paste Change Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Thermal Paste Change'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_thermal_paste_change }}.</p>

           <!-- Observation Thermal Paste Change Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_thermal_paste_change }}.</p>
       </div>

       <div class="row">
           <!-- Has Heatsink Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Has Heatsink Cleaning'):</strong>
           <p class="col-3 text-break">@{{ dataShow.has_heatsink_cleaning }}.</p>

           <!-- Observation Heatsink Cleaning Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation_heatsink_cleaning }}.</p>
       </div>

       <div class="row">
           <!-- Technical Report Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Technical Report'):</strong>
           <p class="col-3 text-break">@{{ dataShow.technical_report }}.</p>

           <!-- Observation Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.observation }}.</p>
       </div>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Configuración del actual hardware - Torre</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Tower Inventory Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Inventory Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_inventory_number }}.</p>

           <!-- Tower Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower }}.</p>

       </div>

       <div class="row">
           <!-- Tower Model Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Model'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_model }}.</p>

           <!-- Tower Series Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Serie'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_series }}.</p>

       </div>

       <div class="row">
           <!-- Tower Processor Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Processor'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_processor }}.</p>

           <!-- Tower Host Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Host'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_host }}.</p>
       </div>

       <div class="row">
           <!-- Tower Ram Gb Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('RAM GB'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ram_gb }}.</p>

           <!-- Tower Ram Gb Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ram_gb_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Number Ram Modules Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Number Ram Modules'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_number_ram_modules }}.</p>

           <!-- Tower Mac address Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mac Adress'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_mac_address }}.</p>
       </div>

       <div class="row">
           <!-- Tower Mainboard Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mainboard'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_mainboard }}.</p>

           <!-- Tower Mainboard Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_mainboard_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Ipv4 address Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Ipv4 Adress'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ipv4_address }}.</p>

           <!-- Tower Ipv6 address Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Ipv6 Adress'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ipv6_address }}.</p>
       </div>

       <div class="row">
           <!-- Tower ddh Capacity Gb Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('DDH Capacity Gb'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ddh_capacity_gb }}.</p>

           <!-- Tower ddh Capacity Gb Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ddh_capacity_gb_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Ssd Capacity Gb Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('SSD Capacity Gb'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ssd_capacity_gb }}.</p>

           <!-- Tower Ssd Capacity Gb Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_ssd_capacity_gb_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Video Card Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Video Card'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_video_card }}.</p>

           <!-- Tower Video Card Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_video_card_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Sound Card Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Sound Card'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_sound_card }}.</p>

           <!-- Tower Sound Card Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_sound_card_mark }}.</p>
       </div>

       <div class="row">
           <!-- Tower Network Card Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Network Card'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_network_card }}.</p>

           <!-- Tower Network Card Mark Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mark'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_network_card_mark }}.</p>
       </div>

       <div class="row">
           <!-- Faceplate Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Faceplate'):</strong>
           <p class="col-3 text-break">@{{ dataShow.faceplate }}.</p>

           <!-- Faceplate Patch Panel Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Faceplate patch panel'):</strong>
           <p class="col-3 text-break">@{{ dataShow.faceplate_patch_panel }}.</p>
       </div>

       <div class="row">
           <!-- Tower Value Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Tower Value'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_value }}.</p>

           <!-- Tower Contract Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.tower_contract_number }}.</p>
       </div>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Configuración actual de hardware – Monitor</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Monitor Number Inventory Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Inventory Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor_number_inventory }}.</p>

           <!-- Monitor Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Monitor'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor }}.</p>
       </div>
       
       <div class="row">
           <!-- Monitor Model Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Model'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor_model }}.</p>
           
           <!-- Monitor Serial Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Serial'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor_serial }}.</p>
       </div>
       
       <div class="row">
           <!-- Monitor Value Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Monitor Value'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor_value }}.</p>
           
           <!-- Monitor Contract Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.monitor_contract_number }}.</p>
       </div>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Configuración actual de hardware – Teclado</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Keyboard Number Inventory Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Inventory Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard_number_inventory }}.</p>

           <!-- Keyboard Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Keyboard'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard }}.</p>
       </div>
       
       <div class="row">
           <!-- Keyboard Model Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Model'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard_model }}.</p>

           <!-- Keyboard Serial Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Serial'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard_serial }}.</p>
       </div>

       <div class="row">
           <!-- Keyboard Value Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Keyboard Value'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard_value }}.</p>

           <!-- Keyboard Contract Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.keyboard_contract_number }}.</p>
       </div>

   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Configuración actual de hardware – Mouse</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Mouse Number Inventory Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Inventory Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse_number_inventory }}.</p>
           <!-- Mouse Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mouse'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse }}.</p>
       </div>

       <div class="row">
           <!-- Mouse Model Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Model'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse_model }}.</p>

           <!-- Mouse Serial Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Serial'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse_serial }}.</p>
       </div>

       <div class="row">
           <!-- Mouse Value Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Mouse Value'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse_value }}.</p>

           <!-- Mouse Contract Number Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
           <p class="col-3 text-break">@{{ dataShow.mouse_contract_number }}.</p>
       </div>

   </div>
</div>

<div class="panel" v-if="dataShow.other_equipment_backups?.length > 0">
   <div class="panel-heading">
       <div class="panel-title"><strong>Configuración actual de hardware – Otros dispositivos</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <table class="table table-hover m-b-0">
               <thead>
                   <tr>
                       <td class="text-center border"><strong>No. inventario</strong></td>
                       <td class="text-center border"><strong>Marca</strong></td>
                       <td class="text-center border"><strong>Modelo</strong></td>
                       <td class="text-center border"><strong>Serial</strong></td>
                       <td class="text-center border"><strong>Valor dispositivo</strong></td>
                       <td class="text-center border"><strong>No. Contrato</strong></td>
                   </tr>
               </thead>
               <tbody>
                   <tr v-for="(otherEquipment,key) in dataShow.other_equipment_backups" :key="key">
                       <td class="text-center border">@{{ otherEquipment.inventory_number }}</td>
                       <td class="text-center border">@{{ otherEquipment.mark }}</td>
                       <td class="text-center border">@{{ otherEquipment.model }}</td>
                       <td class="text-center border">@{{ otherEquipment.serial }}</td>
                       <td class="text-center border">@{{ otherEquipment.monitor_value }}</td>
                       <td class="text-center border">@{{ otherEquipment.contract_number }}</td>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>
</div>

<div class="panel">
   <div class="panel-heading">
       <div class="panel-title"><strong>Software</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <!-- Operating System Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Operating System'):</strong>
           <p class="col-3 text-break">@{{ dataShow.operating_system }}.</p>

           <!-- Operating System Version Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
           <p class="col-3 text-break">@{{ dataShow.operating_system_version }}.</p>
       </div>

       <div class="row">
           <!-- Operating System License Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Operating System License'):</strong>
           <p class="col-3 text-break">@{{ dataShow.operating_system_license }}.</p>

       </div>

       <div class="row">
           <!-- Office Automation Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Office Automation'):</strong>
           <p class="col-3 text-break">@{{ dataShow.office_automation }}.</p>
           <!-- Office Automation Version Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
           <p class="col-3 text-break">@{{ dataShow.office_automation_version }}.</p>

       </div>

       <div class="row">
           <!-- Office Automation License Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Office Automation License'):</strong>
           <p class="col-3 text-break">@{{ dataShow.office_automation_license }}.</p>
           <!-- Antivirus Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Antivirus'):</strong>
           <p class="col-3 text-break">@{{ dataShow.antivirus }}.</p>
       </div>

       <div class="row">
           <!-- Installed Product Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Installed Product'):</strong>
           <p class="col-3 text-break">@{{ dataShow.installed_product }}.</p>

           <!-- Installed Product Version Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
           <p class="col-3 text-break">@{{ dataShow.installed_product_version }}.</p>
       </div>

       <div class="row">
           <!-- Browser Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Browser'):</strong>
           <p class="col-3 text-break">@{{ dataShow.browser }}.</p>

           <!-- Browser Version Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
           <p class="col-3 text-break">@{{ dataShow.browser_version }}.</p>
       </div>

       <div class="row">
           <!-- Teamviewer Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Teamviewer'):</strong>
           <p class="col-3 text-break">@{{ dataShow.teamviewer }}.</p>

           <!-- Other Field -->
           <strong class="text-inverse text-left col-3 text-break">@lang('Other'):</strong>
           <p class="col-3 text-break">@{{ dataShow.other }}.</p>
       </div>

   </div>
</div>

<div class="panel" v-if="dataShow.equipment_purchase_details_backups?.length > 0">
   <div class="panel-heading">
       <div class="panel-title"><strong>Información compra equipo</strong></div>
   </div>
   <div class="panel-body">
       <div class="row">
           <table class="table table-hover m-b-0">
               <thead>
                   <tr>
                       <td class="text-center border"><strong>No. contrato</strong></td>
                       <td class="text-center border"><strong>Fecha</strong></td>
                       <td class="text-center border"><strong>Proveedor</strong></td>
                       <td class="text-center border"><strong>Garantía en años</strong></td>
                       <td class="text-center border"><strong>Valor total del contrato</strong></td>
                       <td class="text-center border"><strong>Estado</strong></td>
                       <td class="text-center border"><strong>Fecha de terminación de la garantía</strong></td>
                   </tr>
               </thead>
               <tbody>
                   <tr v-for="(equipmentPurchaseDetail,key) in dataShow.equipment_purchase_details_backups" :key="key">
                       <td class="text-center border">@{{ equipmentPurchaseDetail.contract_number }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.date }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.provider }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.warranty_in_years }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.contract_total_value }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.status }}</td>
                       <td class="text-center border">@{{ equipmentPurchaseDetail.warranty_termination_date }}</td>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>
</div>
