<table border="1">
    <tr style="margin: 5px">
        <td> Indicador Rendimiento en Hr por galón </td>
    </tr>
    <thead>
        <tr>
            <td>Fecha inicio</td>
            <td>Proceso</td>
            <td>Tipo de activo</td>
            <td>Nombre del activo</td>
            <td>Placa</td>
            <td>Rendimiento en Hr/gal</td>
        </tr>
    </thead>
    <tbody>
        @php
            $dataRequest = array_values($data);
            // unset($dataRequest[count($dataRequest)-1]);
            $suma = 0;
            $ceros = 0;
            $sinKm = 0;
        @endphp
        @foreach ($dataRequest as $key => $item)
        @if ($item['performance_by_gallon'] != 0)
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
                @if ($item['variation_tanking_hour'] == null)
                    <td>No aplica</td>
                @else
                    <td>{!! $item['performance_by_gallon'] !!}</td>
                @endif
            </tr>
            @if($item['variation_tanking_hour'] == null)
            @php
                $sinKm += 1;
            @endphp
            @endif
        @endif
            @php
            if ($item['performance_by_gallon'] != 0) {
                $ceros = 1;
            }
            if($item['variation_tanking_hour'] != null){
                $suma += $item['performance_by_gallon'];
            }
            @endphp
        @endforeach
        @php
        if($suma == 0){
            $promedio = 0;
        } else {
            $contador = count($dataRequest)-$ceros-$sinKm;
            $promedio = $suma/$contador;
        }
        @endphp
        <tr>
            <td><strong>Promedio</strong></td>
            <td colspan="5">{{ $promedio }}</td>
        </tr>
    </tbody>
</table>
