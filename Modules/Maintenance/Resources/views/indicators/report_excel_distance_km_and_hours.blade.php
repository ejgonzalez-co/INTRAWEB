<table border="1">
    <tr style="margin: 5px">
        <td> Indicador Recorrido en Km y horas</td>
    </tr>

    <thead>
        <tr>
            <td>Fecha inicio</td>
            <td>Proceso</td>
            <td>Tipo de activo</td>
            <td>Nombre del activo</td>
            <td>Placa</td>
            <td>Recorrido por Km</td>
            <td>Trabajo en Hr</td>
        </tr>
    </thead>
    <tbody>
        @php
            $dataRequest = array_values($data);
            unset($dataRequest[count($dataRequest)-1],$dataRequest[count($dataRequest)-1]);
            $sumaKm = 0;
            $sumaHr = 0;
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
                @if ($item['variation_route_hour'] == 0)
                <td>No aplica</td>
                @else
                <td>{!! $item['variation_route_hour'] !!}Km</td>
                @endif
                @if ($item['current_hourmeter'] == 0)
                <td>No aplica</td>
                @else
                <td>{!! $item['current_hourmeter'] !!}Hr</td>
                @endif
            </tr>
            @php
                $sumaKm += $item['variation_route_hour'];
            @endphp
        @endforeach
        @php
        if($sumaKm == 0){
            $promedioKm = 0;
        } else {
            $promedioKm = $sumaKm/count($dataRequest);
        }

        if($sumaHr == 0){
            $promedioHr = 0;
        } else {
            $promedioHr = $sumaHr/count($dataRequest);
        }
        @endphp
        <tr>
            <td colspan="5"><strong><h6>Total, recorrido</h6></strong></td>
            <td>{{ $sumaKm }}</td>
            <td>{{ $sumaHr }}</td>
        </tr>
        <tr>
            <td colspan="5"><strong>Promedio</strong></td>
            <td>{{ $promedioKm }}</td>
            <td>{{ $promedioHr }}</td>
        </tr>
    </tbody>
</table>
