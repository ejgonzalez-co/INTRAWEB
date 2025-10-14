<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HOJA DE VIDA DE LOS EQUIPOS</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>Tipo de equipo</td>
                <td>Usuario del dominio (EPA. LOCAL)</td>
                <td>Referencia</td>
                <td>Funcionario</td>
                <td>Tipo de contrato</td>
                <td>Cargo</td>
                <td>Dependencia</td>
                <td>Área</td>
                <td>Sede</td>
                <td>Responsable del inventario</td>
                <td>No. de contrato</td>
                <td>Fecha del contrato</td>
                <td>Proveedor</td>
                <td>Valor del contrato</td>
                <td>Torre placa de inventario</td>
                <td>Torre marca</td>
                <td>Torre Serial/ST</td>
                <td>Torre procesador</td>
                <td>Torre host</td>
                <td>Torre tamaño de la torre</td>
                <td>Torre RAM GB</td>
                <td>Torre fecha Finalizacíon de garantía</td>
                <td>Año del equipo</td>
                <td>Dirección MAC de la torre</td>
                <td>Dominio de la torre</td>
                <td>Usuario de Directorio Activo</td>
                <td>Carpeta compartida</td>
                <td>Punto de red</td>
                <td>Torre Dirección IPV4</td>
                <td>Torre Dirección IPV6</td>
                <td>DHCP Habilitado</td>
                <td>Observación</td>
                <td>Torre capacidad HDD</td>
                <td>Torre capacidad SSD</td>
                <td>Torre Tarjeta de video</td>
                <td>Torre Tarjeta de red</td>
                <td>Torre puerto en patch panel</td>
                <td>Monitor No. Inventario</td>
                <td>Monitor Marca</td>
                <td>Monitor Modelo</td>
                <td>Monitor Serial</td>
                <td>Sistema operativo</td>
                <td>Sistema operativo Versión</td>
                <td>Licencia sistema operativo</td>
                <td>Licencia office</td>
                <td>Versión Office</td>
                <td>Antivirus</td>
                <td>Versión Antivirus</td>
                <td>Producto instalado</td>
                <td>Producto instalado Versión</td>
                <td>Aplicaciones no necesarias</td>
                <td>Teamviewer</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $equipmentResume)
              <tr>
                <td>{!! $equipmentResume["asset_type"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["domain_user"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerReferences']['reference'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["officer"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["contract_type"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["charge"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['dependencias']['nombre'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["area"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["site"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["service_manager"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['contractData']['contract_number'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['contractData']['date'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['contractData']['provider'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['contractData']['contract_total_value'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_inventory_number"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_series"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerProcessor']['processor'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_host"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerSize']['size'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerRam']['memory_ram'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_warranty_end_date"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_equipment_year"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_mac_address"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_domain"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_directory_active"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerSharedFolder']['shared_folder'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_network_point"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_ipv4_address"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_ipv6_address"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["tower_dhcp"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["observation"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerHdd']['hdd_capacity'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerSsd']['ssd_capacity'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationTowerVideoCard']['video_card'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationNetworkCard']['network_card'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["faceplate_patch_panel"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["monitor_number_inventory"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["monitor"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["monitor_model"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["monitor_serial"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigOperationSystem']['name'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["operating_system_version"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["operating_system_license"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["office_automation_license"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationVersionOffice']['office_version'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["antivirus_agent_version"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["antivirus_version"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["installed_product"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["installed_product_version"] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume['ConfigurationUnnecessaryApps']['unnecessary_app'] ?? 'N/A' !!}</td>
                <td>{!! $equipmentResume["teamviewer"] ?? 'N/A' !!}</td>
            </tr>

            @endforeach
        </tbody>
    </table>

</body>

</html>
