<table border="1">
    <tr style="margin: 5px">
        <td> Indicador Recorrido</td>
    </tr>

    <thead>
        <tr>
            <td>Fecha inicio</td>
            <td>Proceso</td>
            <td>Tipo de activo</td>
            <td>Nombre del activo</td>
            <td>Placa</td>
            <td>Recorrido por Km</td>
        </tr>
    </thead>
    <tbody>
        @php
            $dataRequest = array_values($data);
            unset($dataRequest[count($dataRequest)-1],$dataRequest[count($dataRequest)-1]);
            $suma = 0;
            $sinKm = 0;
        @endphp
        @foreach ($dataRequest as $key => $item)
            <tr>
                <td>{!! $item['invoice_date'] !!}</td>
                <td>{!! $item['dependencias']['nombre'] !!}</td>
                <td>{!! $item['asset_type']['name'] !!}</td>
                <td>{!! $item['asset_name'] !!}</td>
                @if ($item['resume_machinery_vehicles_yellow']['plaque'] == null)
                    <td>No aplica</td>
                @else
                <td>{!! $item['resume_machinery_vehicles_yellow']['plaque'] !!}</td>
                @endif
                <td>{!! $item['variation_route_hour'] !!}Km</td>
            </tr>
            @if(!isset($item['variation_route_hour']))
            @php
                $sinKm += 1;
            @endphp
            @endif
            @php
                $suma += $item['variation_route_hour'];
            @endphp
        @endforeach
            @php
            $resProm = count($dataRequest) - $sinKm;
            $promedio = $resProm > 0 ? $suma / $resProm : 0;
            @endphp

        <tr>
            <td><strong><h6>Total, recorrido</h6></strong></td>
            <td colspan="5">{{ $suma }}</td>
        </tr>
        <tr>
            <td><strong>Promedio</strong></td>
            <td colspan="5">{{ $promedio }}</td>
        </tr>
    </tbody>
</table>
