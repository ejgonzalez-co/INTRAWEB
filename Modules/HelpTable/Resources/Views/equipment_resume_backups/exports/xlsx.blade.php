<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Backups</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>Tipo de equipo</td>
                <td>Usuario del dominio (EPA. LOCAL)</td>
                <td>Funcionario</td>
                <td>Tipo de contrato</td>
                <td>Cargo</td>
                <td>Dependencia</td>
                <td>Área</td>
                <td>Sede</td>
                <td>Responsable del inventario</td>
                <td>Tipo de mantenimiento</td>
                <td>Ciclo</td>
                <td>No. de contrato</td>
                <td>Fecha del contrato</td>
                <td>Fecha de mantenimiento</td>
                <td>Proveedor</td>
                <td>Valor del contrato</td>
                <td>Limpieza interna y externa del hardware</td>
                <td>Observación</td>
                <td>Limpieza memoria RAM</td>
                <td>Observación</td>
                <td>Limpieza memoria Board</td>
                <td>Observación</td>
                <td>Limpieza fuente de poder</td>
                <td>Observación</td>
                <td>Limpieza unidad DVD</td>
                <td>Observación</td>
                <td>Limpieza de monitor</td>
                <td>Observación</td>
                <td>Limpieza de teclado</td>
                <td>Observación</td>
                <td>Limpieza de mouse</td>
                <td>Observación</td>
                <td>Cambio de pasta térmica</td>
                <td>Observación</td>
                <td>Limpieza de disipadores</td>
                <td>Observación</td>
                <td>Reporte técnico</td>
                <td>Observaciones</td>
                <td>Torre No. Inventario</td>
                <td>Torre marca</td>
                <td>Torre modelo</td>
                <td>Torre serie</td>
                <td>Torre procesador</td>
                <td>Torre host</td>
                <td>Torre RAMG B</td>
                <td>Torre RAM GB marca</td>
                <td>Torre Cantidad de módulos RAM</td>
                <td>Dirección MAC de la torre</td>
                <td>Torre Mainboard</td>
                <td>Torre Mainboard Marca</td>
                <td>Torre Dirección IPV4</td>
                <td>Torre Dirección IPV6</td>
                <td>Torre DDH capacidad GB</td>
                <td>Torre DDH capacidad GB Marca</td>
                <td>Torre SSD capacidad GB</td>
                <td>Torre SSD capacidad GB Marca</td>
                <td>Torre Tarjeta de video</td>
                <td>Torre Tarjeta de video Marca</td>
                <td>Torre Tarjeta de sonido</td>
                <td>Torre Tarjeta de sonido Marca</td>
                <td>Torre Tarjeta de red</td>
                <td>Torre Tarjeta de red Marca</td>
                <td>Torre Faceplate</td>
                <td>Torre Faceplate patch panel</td>
                <td>Valor Torre</td>
                <td>Torre No. Contrato</td>
                <td>Monitor No. Inventario</td>
                <td>Monitor Marca</td>
                <td>Monitor Modelo</td>
                <td>Monitor Serial</td>
                <td>Valor Monitor</td>
                <td>Monitor No. Contrato</td>
                <td>Teclado No. Inventario</td>
                <td>Teclado Marca</td>
                <td>Teclado Modelo</td>
                <td>Teclado Serial</td>
                <td>Valor Teclado</td>
                <td>Teclado No. Contrato</td>
                <td>Mouse No. Inventario</td>
                <td>Mouse Marca</td>
                <td>Mouse Modelo</td>
                <td>Mouse Serial</td>
                <td>Valor Mouse</td>
                <td>Mouse No. Contrato</td>
                <td>Sistema operativo</td>
                <td>Sistema operativo Versión</td>
                <td>Licencia sistema operativo</td>
                <td>Ofimatica</td>
                <td>Ofimatica Versión</td>
                <td>Licencia ofimatica</td>
                <td>Antivirus</td>
                <td>Producto instalado</td>
                <td>Producto instalado Versión</td>
                <td>Navegador</td>
                <td>Navegador</td>
                <td>Teamviewer 12</td>
                <td>Otro</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $backup)
                <tr>
                    <td>{!! $backup["asset_type"] ?? "" !!}</td>
                    <td>{!! $backup["domain_user"] ?? "" !!}</td>
                    <td>{!! $backup["officer"] ?? "" !!}</td>
                    <td>{!! $backup["contract_type"] ?? "" !!}</td>
                    <td>{!! $backup["charge"] ?? "" !!}</td>
                    <td>{!! $backup["dependence"] ?? "" !!}</td>
                    <td>{!! $backup["area"] ?? "" !!}</td>
                    <td>{!! $backup["site"] ?? "" !!}</td>
                    <td>{!! $backup["service_manager"] ?? "" !!} </td>
                    <td>{!! $backup["maintenance_type"] ?? "" !!}</td>
                    <td>{!! $backup["cycle"] ?? "" !!}</td>
                    <td>{!! $backup["contract_number"] ?? "" !!}</td>
                    <td>{!! $backup["contract_date"] ?? "" !!}</td>
                    <td>{!! $backup["maintenance_date"] ?? "" !!}</td>
                    <td>{!! $backup["provider"] ?? "" !!}</td>
                    <td>{!! $backup["contract_value"] ?? "" !!}</td>
                    <td>{!! $backup["has_internal_and_external_hardware_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_internal_and_external_hardware_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_ram_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_ram_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_board_memory_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_board_memory_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_power_supply_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_power_supply_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_dvd_drive_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_dvd_drive_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_monitor_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_monitor_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_keyboard_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_keyboard_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_mouse_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_mouse_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["has_thermal_paste_change"] ?? "" !!}</td>
                    <td>{!! $backup["observation_thermal_paste_change"] ?? "" !!}</td>
                    <td>{!! $backup["has_heatsink_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["observation_heatsink_cleaning"] ?? "" !!}</td>
                    <td>{!! $backup["technical_report"] ?? "" !!}</td>
                    <td>{!! $backup["observation"] ?? "" !!}</td>
                    <td>{!! $backup["tower_inventory_number"] ?? "" !!}</td>
                    <td>{!! $backup["tower"] ?? "" !!}</td>
                    <td>{!! $backup["tower_model"] ?? "" !!}</td>
                    <td>{!! $backup["tower_series"] ?? "" !!} </td>
                    <td>{!! $backup["tower_processor"] ?? "" !!} </td>
                    <td>{!! $backup["tower_host"] ?? "" !!} </td>
                    <td>{!! $backup["tower_ram_gb"] ?? "" !!}</td>
                    <td>{!! $backup["tower_ram_gb_mark"] ?? "" !!} </td>
                    <td>{!! $backup["tower_number_ram_modules"] ?? "" !!}</td>
                    <td>{!! $backup["tower_mac_address"] ?? "" !!}</td>
                    <td>{!! $backup["tower_mainboard"] ?? "" !!} </td>
                    <td>{!! $backup["tower_mainboard_mark"] ?? "" !!}</td>
                    <td>{!! $backup["tower_ipv4_address"] ?? "" !!}</td>
                    <td>{!! $backup["tower_ipv6_address"] ?? "" !!}</td>
                    <td>{!! $backup["tower_ddh_capacity_gb"] ?? "" !!} </td>
                    <td>{!! $backup["tower_ddh_capacity_gb_mark"] ?? "" !!} </td>
                    <td>{!! $backup["tower_ssd_capacity_gb"] ?? "" !!}</td>
                    <td>{!! $backup["tower_ssd_capacity_gb_mark"] ?? "" !!} </td>
                    <td>{!! $backup["tower_video_card"] ?? "" !!} </td>
                    <td>{!! $backup["tower_video_card_mark"] ?? "" !!}</td>
                    <td>{!! $backup["tower_sound_card"] ?? "" !!} </td>
                    <td>{!! $backup["tower_sound_card_mark"] ?? "" !!}</td>
                    <td>{!! $backup["tower_network_card"] ?? "" !!}</td>
                    <td>{!! $backup["tower_network_card_mark"] ?? "" !!}</td>
                    <td>{!! $backup["faceplate"] ?? "" !!}</td>
                    <td>{!! $backup["faceplate_patch_panel"] ?? "" !!}</td>
                    <td>{!! $backup["tower_value"] ?? "" !!} Torre</td>
                    <td>{!! $backup["tower_contract_number"] ?? "" !!}</td>
                    <td>{!! $backup["monitor_number_inventory"] ?? "" !!}</td>
                    <td>{!! $backup["monitor"] ?? "" !!}</td>
                    <td>{!! $backup["monitor_model"] ?? "" !!}</td>
                    <td>{!! $backup["monitor_serial"] ?? "" !!}</td>
                    <td>{!! $backup["monitor_value"] ?? "" !!}</td>
                    <td>{!! $backup["monitor_contract_number"] ?? "" !!} </td>
                    <td>{!! $backup["keyboard_number_inventory"] ?? "" !!}</td>
                    <td>{!! $backup["keyboard"] ?? "" !!}</td>
                    <td>{!! $backup["keyboard_model"] ?? "" !!}</td>
                    <td>{!! $backup["keyboard_serial"] ?? "" !!}</td>
                    <td>{!! $backup["keyboard_value"] ?? "" !!}</td>
                    <td>{!! $backup["keyboard_contract_number"] ?? "" !!} </td>
                    <td>{!! $backup["mouse_number_inventory"] ?? "" !!} </td>
                    <td>{!! $backup["mouse"] ?? "" !!}</td>
                    <td>{!! $backup["mouse_model"] ?? "" !!}</td>
                    <td>{!! $backup["mouse_serial"] ?? "" !!}</td>
                    <td>{!! $backup["mouse_value"] ?? "" !!}</td>
                    <td>{!! $backup["mouse_contract_number"] ?? "" !!}</td>
                    <td>{!! $backup["operating_system"] ?? "" !!}</td>
                    <td>{!! $backup["operating_system_version"] ?? "" !!}</td>
                    <td>{!! $backup["operating_system_license"] ?? "" !!}</td>
                    <td>{!! $backup["office_automation"] ?? "" !!}</td>
                    <td>{!! $backup["office_automation_version"] ?? "" !!}</td>
                    <td>{!! $backup["office_automation_license"] ?? "" !!}</td>
                    <td>{!! $backup["antivirus"] ?? "" !!}</td>
                    <td>{!! $backup["installed_product"] ?? "" !!}</td>
                    <td>{!! $backup["installed_product_version"] ?? "" !!}</td>
                    <td>{!! $backup["browser"] ?? "" !!}</td>
                    <td>{!! $backup["browser_version"] ?? "" !!}</td>
                    <td>{!! $backup["teamviewer"] ?? "" !!} 12</td>
                    <td>{!! $backup["other"] ?? "" !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
