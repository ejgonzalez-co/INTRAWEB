<table border="1">
    <tr style="margin: 5px">
        <td> Indicador Trabajo en horas</td>
    </tr>

    <thead>
        <tr>
            <td>Fecha inicio</td>
            <td>Proceso</td>
            <td>Tipo de activo</td>
            <td>Nombre del activo</td>
            <td>Placa</td>
            <td>Recorrido por Hr</td>
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
                @if ($item['current_hourmeter'] == 0 || $item['current_hourmeter'] == '')
                <td>No aplica</td>                
                @else
                <td>{!! $item['current_hourmeter'] !!}Hr</td>
                @endif
            </tr>
            @if($item['current_hourmeter'] == '')
            @php
                $sinKm += 1;
            @endphp
            @endif
            @php
                $suma += $item['current_hourmeter'];
            @endphp
        @endforeach
        @php
        if($suma == 0){
            $promedio = 0;
        } else {
            $resProm = count($dataRequest)-$sinKm;

            
            $promedio = $suma/$resProm;
        }
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
