<table class="table table-bordered">
    <thead>
        <tr>
            <td colspan="18" style="font-size: 20px; background: #12E54F;">
                <h1>Información de llantas activas</h1>
            </td>
        </tr>
        <tr>
            <th style="background: #BDBDBD; font-size:14px;">Proceso</th>
            <th style="background: #BDBDBD; font-size:14px;">Codigo de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Nombre del equipo o maquinaria</th>
            <th style="background: #BDBDBD; font-size:14px;">Placa</th>
            <th style="background: #BDBDBD; font-size:14px;">Fecha de ingreso de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Referencia de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Fecha de asignación de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Posición de llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Tipo de llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Marca de llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Costo de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Kilometraje al crear</th>
            <th style="background: #BDBDBD; font-size:14px;">Profundidad de la llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Profundidad disponible</th>
            <th style="background: #BDBDBD; font-size:14px;">Costo por mm general</th>
            <th style="background: #BDBDBD; font-size:14px;">Presión de llanta</th>
            <th style="background: #BDBDBD; font-size:14px;">Observación</th>
            <th style="background: #BDBDBD; font-size:14px;">Estado</th>
            <th style="background: #BDBDBD; font-size:14px;">Kilometraje de rodamiento</th>
            <th style="background:#409394">Fecha de revisión</th>
            <th style="background:#409394">Kilometraje en la revisión</th>
            <th style="background:#409394">Profundidad del registro</th>
            <th style="background:#409394">Total desgaste</th>
            <th style="background:#409394">Valor actual llanta</th>
            <th style="background:#409394">Costo por Km</th>
            <th style="background:#409394">Total recorrido</th>
            <th style="background:#409394">Presión en la revisión</th>
            <th style="background:#409394">Observación</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $item)
                @foreach ($item['excel'] as $data)
                    <tr>
                        <td>{!! $item['name_dependencias'] !!}</td>
                        <td>{!! $item['code_tire'] !!}</td>
                        <td>{!! $item['name_machinery'] !!}</td>
                        <td>{!! $item['plaque'] !!}</td>
                        <td>{!! $item['date_register'] !!}</td>
                        <td>{!! $item['reference_name'] !!}</td>
                        <td>{!! $item['date_assignment'] !!}</td>
                        <td>{!! $item['position_tire'] !!}</td>
                        <td>{!! $item['type_tire'] !!}</td>
                        <td>{!! $item['tire_brand_name'] !!}</td>
                        <td>{!! $item['cost_tire'] !!}</td>
                        <td>{!! $item['mileage_initial'] !!}</td>
                        <td>{!! $item['depth_tire'] !!}</td>
                        <td>{!! $item['available_depth'] !!}</td>
                        <td>{!! $item['general_cost_mm'] !!}</td>
                        <td>{!! $item['tire_pressure'] !!}</td>
                        <td>{!! $item['observation_information'] !!}</td>
                        <td>{!! $item['state'] !!}</td>
                        <td>{!! $item['kilometraje_rodamiento'] !!}</td>
                        <td>{!! $data->revision_date !!}</td>
                        <td>{!! $data->revision_mileage !!}</td>
                        <td>{!! $data->registration_depth !!}</td>
                        <td>{!! $data->wear_total !!}</td>
                        <td>{!! $data->wear_cost_mm !!}</td>
                        <td>{!! $data->cost_km !!}</td>
                        <td>{!! $data->route_total !!}</td>
                        <td>{!! $data->revision_pressure !!}</td>
                        <td>{!! $data->observation !!}</td>
                    </tr>
                @endforeach
        @endforeach
    </tbody>
</table>