<table border="1">
    <thead>
        <tr>
            <td>Fecha de registro</td>
            <td>Referencia de las llanta</td>
            <td>Presión de inflado</td>
            <td>Observacion</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['registration_date'] !!}</td>
                <td>{!! $item['tire_all'] ? $item['tire_all']['name'] : ""  !!}</td>
                <td>{!! $item['inflation_pressure'] !!}</td>
                <td>{!! $item['observation'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>