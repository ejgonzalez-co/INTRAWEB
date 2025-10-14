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
            <td>Rendimiento en Km/gal</td>
            <td>Rendimiento en Hr/gal</td>
        </tr>
    </thead>
    <tbody>
        @php
            $dataRequest = array_values($data);
            unset($dataRequest[count($dataRequest)-1],$dataRequest[count($dataRequest)-1],$dataRequest[count($dataRequest)-1]);
            $sumaKm = 0;
            $sumaHr = 0;
            $ceros = 0;
        @endphp
        @foreach ($dataRequest as $key => $item)
        @if ($item['variation_route_hour'] != 0 || $item['performance_by_gallon'] != 0)
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
                @if ($item['variation_route_hour'] == null)
                    <td>No aplica</td>
                @else
                    <td>{!! $item['performance_by_gallon'] !!}</td>
                @endif
                @if ($item['variation_tanking_hour'] == null)
                <td>No aplica</td>
                @else
                    <td>{!! $item['performance_by_gallon'] !!}</td>
                @endif
            </tr>
        @endif
        @endforeach
        @php
        if($sumaKm == 0){
            $promedioKm = 0;
        }

        if($sumaHr == 0){
            $promedioHr = 0;
        } else {

        }
            //Inicializa el array
            $arraySum = [];
            //Inicializa el array
            $arraySumHr = [];
            //Recorre los vehiculos
            foreach($dataRequest as $item){
                    if ($item['variation_route_hour']) {
                        //Obtiene el rendimiento por galon
                        $total = $item['performance_by_gallon'];
                        //Agrega elemento al array
                        array_push($arraySum, $total);
                    } elseif ($item['variation_tanking_hour']) {
                        //Obtiene el rendimiento por galon
                        $totalHr = $item['performance_by_gallon'];
                        //Agrega elemento al array
                        array_push($arraySumHr, $totalHr);
                    }
                    if($item['variation_route_hour'] != 0 || $item['performance_by_gallon'] != 0){
                        $ceros = 1;
                    }
            }
            //Realiza una suma del array que esta inicializado para las horas
            $resultArrayHr = array_sum($arraySumHr);
            //realiza una suma del array que esta inicializado
            $resultArray = array_sum($arraySum);
            if(empty($arraySum)){
                $average = 0;
            } else {
                $contadorKm = count($arraySum)-$ceros;
                //Realiza el calculo para sacar el promedio
                $average = array_sum($arraySum)/$contadorKm;
            }
            if (empty($arraySumHr)) {
                $averageHr = 0;
            } else {
                $contadorHr = count($arraySumHr)-$ceros;
                //Realiza el calculo pra sacar el promedio en horas
                $averageHr = array_sum($arraySumHr)/$contadorHr;
            }
        @endphp
                <tr>
                    <td colspan="6"><strong>Total, Km</strong></td>
                    <td>{{ $resultArray }}</td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Total, Hr</strong></td>
                    <td>{{ $resultArrayHr }}</td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Promedio Km</strong></td>
                    <td>{{ $average }}</td>
                </tr>
                <tr>
                    <td colspan="6"><strong>Promedio Hr</strong></td>
                    <td>{{ $averageHr }}</td>
                </tr>
    </tbody>
</table>
