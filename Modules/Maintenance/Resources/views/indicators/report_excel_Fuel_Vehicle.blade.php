<table border="1">
    <tr style="margin: 5px">
        <td> Indicador consumo de combustible vehiculos</td>
    </tr>

    <thead>
        <tr>
            <td>Fecha</td>
            <td>Nombre del activo</td>
            <td>Placa</td>
            <td>Proceso al que pertenece</td>
            <td>Cantidad en galones</td>
            <td>Tacómetro</td>
            <td>Recorrido</td>
            <td>Tipo de combustible</td>
            <td>Promedio</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['invoice_date'] !!}</td>
                <td>{!! $item['asset_name'] !!}</td>
                <td>{!! $item['resume_machinery_vehicles_yellow']['plaque'] !!}</td>
                <td>{!! $item['dependencias']['nombre'] !!}</td>
                <td>{!! $item['fuel_quantity'] !!}</td>
                @if ($item['current_hourmeter'])
                    <td>{!! $item['current_hourmeter'] !!} Hr</td>
                @else
                    <td>{!! $item['current_mileage'] !!} Km</td>
                @endif

                @if ($item['current_hourmeter'])
                    <td>{!! $item['variation_tanking_hour'] !!}Hr</td>
                @else
                    <td>{!! $item['variation_route_hour'] !!}Km</td>
                @endif
                <td>{!! $item['resume_machinery_vehicles_yellow']['fuel_type'] !!}</td>
                @if ($item['current_hourmeter'] && $item['current_hourmeter']!=0)
                    @if ($item['variation_tanking_hour'])
                        <td>{!! $item['fuel_quantity'] / $item['variation_tanking_hour'] !!}Hr/gal</td>
                    @else
                        <td></td>
                    @endif
                @else
                    @if ($item['fuel_quantity'] && $item['fuel_quantity']!=0)
                        <td>{!! $item['variation_route_hour'] / $item['fuel_quantity'] !!}Km/gal</td>
                    @else
                        <td></td>
                    @endif
                @endif

            </tr>
        @endforeach
    </tbody>
</table>
