<table border="1">
    <thead>
        <tr>
            <td>Fecha de registro</td>
            <td>Referencia de las llanta</td>
            <td>Marca de la llanta</td>
            <td>Máx desgaste para reencauche</td>
            <td>Observacion</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>            
                <td>{!! $item['registration_date'] !!}</td>
                <td>{!! $item['tire_all'] ? $item['tire_all']['name'] : ""  !!}</td>
                <td>{!! $item['tire_brand'] ? $item['tire_brand']['brand_name'] : "" !!}</td>
                <td>{!! $item['maximum_wear'] !!}</td>
                <td>{!! $item['observation'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>