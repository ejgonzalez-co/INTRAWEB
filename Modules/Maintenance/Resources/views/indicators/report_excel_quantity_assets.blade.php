<table border="1">
    <tr style="margin: 5px">
        <td> Cantidad de activos por estado y dependencia </td>
    </tr>

    <thead>
        <tr>
            <td>Fecha de creación</td>
            <td>Proceso</td>
            <td>Tipo de activo</td>
            <td>Estado</td>
            <td>Total</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
        @php
            $date = date("Y-m-d");
        @endphp
            <tr>
                <td>{{ $date }}</td>
                <td>{!! $item['dependencies']['nombre'] !!}</td>
                <td>{!! $item['asset_type']['name'] !!}</td>
                <td>{!! $item['status'] ? $item['status']:'Inactivo' !!}</td>
                <td>{!! $item['cont'] !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
