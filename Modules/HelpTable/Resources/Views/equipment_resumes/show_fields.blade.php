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
        <div class="row">
            <!-- Asset Type Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Estado del Equipo'):</strong>
            <p class="col-3 text-break">@{{ dataShow.status_equipment }}.</p>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Datos del Funcionario</strong></div>
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
            <p class="col-3 text-break">@{{ dataShow.dependencias?.nombre }}.</p>

            <!-- Area Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Área'):</strong>
            <p class="col-3 text-break">@{{ dataShow.area }}.</p>
        </div>

        <div class="row">
            <!-- Site Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Site'):</strong>
            <p class="col-3 text-break">@{{ dataShow.sedes?.name }}.</p>

            <!-- Service Manager Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Service Manager'):</strong>
            <p class="col-3 text-break">@{{ dataShow.service_manager }}.</p>
        </div>

        <div class="row">
            <!-- Site Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Nombre del usuario de dominio'):</strong>
            <p class="col-3 text-break">@{{ dataShow.name_user_domain }}.</p>
        </div>

    </div>
</div>

@if(session('is_provider') || Cookie::get('provider_name'))
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
                <strong class="text-inverse text-left col-3 text-break">@lang('Número de contrato'):</strong>
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
@endif

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
            <!-- Tower Reference Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Reference'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_references?.reference }}.</p>
        
            <!-- Tower Series Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Serial/ST'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_series }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower Warranty End Date Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Warranty End Date'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_warranty_end_date }}.</p>
        
            <!-- Tower Equipment Year Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Año del equipo'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_equipment_year ?? '' }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower Size Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Tamaño de la torre'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_size?.size }}.</p>
        
            <!-- Tower Processor Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Processor'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_processor?.processor }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower RAM Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Memoria RAM'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_ram?.memory_ram }}.</p>
        
            <!-- Tower SSD Capacity Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Capacidad SSD'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_ssd?.ssd_capacity }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower HDD Capacity Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Capacidad HDD'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_hdd?.hdd_capacity }}.</p>
        
            <!-- Tower Video Card Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Tarjeta Gráfica'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_video_card?.video_card }}.</p>
        </div>
        
        <div class="row">
            <!-- TeamViewer Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('ID TeamViewer'):</strong>
            <p class="col-3 text-break">@{{ dataShow.teamviewer }}.</p>
        
            <!-- Tower Host Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Nombre de equipo/HOST'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_host }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower Domain Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Dominio'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_domain }}.</p>
        
            <!-- Tower Directory Active Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Usuario Directorio Activo'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_directory_active }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower Shared Folder Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Carpeta Compartida'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_tower_shared_folder?.shared_folder }}.</p>
        
            <!-- Tower Network Point Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Punto de red'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_network_point }}.</p>
        </div>
        
        <div class="row">
            <!-- Faceplate Patch Panel Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Puerto en Patch Panel'):</strong>
            <p class="col-3 text-break">@{{ dataShow.faceplate_patch_panel }}.</p>
        
            <!-- Tower MAC Address Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Física MAC'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_mac_address }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower DHCP Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('DHCP Habilitado'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_dhcp }}.</p>
        
            <!-- Tower IPv4 Address Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Dirección IPV4'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_ipv4_address }}.</p>
        </div>
        
        <div class="row">
            <!-- Tower IPv6 Address Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Dirección IPV6'):</strong>
            <p class="col-3 text-break">@{{ dataShow.tower_ipv6_address }}.</p>
        
            <!-- Tower Network Card Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Tarjeta de red'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_network_card?.network_card }}.</p>
        </div>
        
        <div class="row">
            <!-- Observation Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Observation'):</strong>
            <p class="col-9 text-break">@{{ dataShow.observation }}.</p>
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
        
        {{-- <div class="row">
            <!-- Monitor Value Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Monitor Value'):</strong>
            <p class="col-3 text-break">@{{ dataShow.monitor_value }}.</p>
            
            <!-- Monitor Contract Number Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Contract Number'):</strong>
            <p class="col-3 text-break">@{{ dataShow.monitor_contract_number }}.</p>
        </div> --}}

    </div>
</div>
{{-- 
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
</div> --}}
{{-- 
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
</div> --}}
{{-- 
<div class="panel" v-if="dataShow.configuration_other_equipments?.length > 0">
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
                    <tr v-for="(otherEquipment,key) in dataShow.configuration_other_equipments" :key="key">
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
</div> --}}

<div class="panel">
    <div class="panel-heading">
        <div class="panel-title"><strong>Software</strong></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <!-- Operating System Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Sistema operativo'):</strong>
            <p class="col-3 text-break">@{{ dataShow.config_operation_system?.name }}.</p>
        
            <!-- Operating System Version Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
            <p class="col-3 text-break">@{{ dataShow.operating_system_version }}.</p>
        </div>
        
        <div class="row">
            <!-- Operating System License Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Licencia sistema operativo'):</strong>
            <p class="col-3 text-break">@{{ dataShow.operating_system_license }}.</p>
        
            <!-- Antivirus Version Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Versión ANTIVIRUS'):</strong>
            <p class="col-3 text-break">@{{ dataShow.antivirus_version }}.</p>
        </div>
        
        <div class="row">
            <!-- Antivirus Agent Version Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Versión AGENTE ANTIVIRUS'):</strong>
            <p class="col-3 text-break">@{{ dataShow.antivirus_agent_version }}.</p>
        
            <!-- Storage Status Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Estado Almacenamiento'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_storage_status?.storage_status }}.</p>
        </div>
        
        <div class="row">
            <!-- Office Version Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Versión Office'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_version_office?.office_version }}.</p>
        
            <!-- Office Automation License Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Licencia Office'):</strong>
            <p class="col-3 text-break">@{{ dataShow.office_automation_license }}.</p>
        </div>
        
        <div class="row">
            <!-- Installed Product Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Producto instalado'):</strong>
            <p class="col-3 text-break">@{{ dataShow.installed_product }}.</p>
        
            <!-- Installed Product Version Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Versión'):</strong>
            <p class="col-3 text-break">@{{ dataShow.installed_product_version }}.</p>
        </div>
        
        <div class="row">
            <!-- Unnecessary Apps Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Borrar aplicaciones no necesarios'):</strong>
            <p class="col-3 text-break">@{{ dataShow.configuration_unnecessary_apps?.unnecessary_app }}.</p>
        
            <!-- Office License Inventory Field -->
            <strong class="text-inverse text-left col-3 text-break">@lang('Placa de Inventario Licencia Office'):</strong>
            <p class="col-3 text-break">@{{ dataShow.office_license_inventory }}.</p>
        </div>

    </div>
</div>

<div class="panel" v-if="dataShow.equipment_purchase_details?.length > 0">
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
                    <tr v-for="(equipmentPurchaseDetail,key) in dataShow.equipment_purchase_details" :key="key">
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
