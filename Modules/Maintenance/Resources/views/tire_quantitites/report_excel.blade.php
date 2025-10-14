<table border="1">
    <thead>
        <tr>
            <td style="font-size: 20px; background: #12E54F;"><h1>Información de vehiculos</h1></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
        </tr>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;">Proceso</td>
            <td style="background: #BDBDBD; font-size:14px;">Nombre del equipo o maquinaria</td>
            <td style="background: #BDBDBD; font-size:14px;">Placa</td>
            <td style="background: #BDBDBD; font-size:14px;">Cantidad de llantas</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>            
                <td>{!! $item['dependencies'] ? $item['dependencies']['nombre'] : "" !!}</td>
                <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['name_vehicle_machinery'] : "" !!}</td>
                <td>{!! $item['resume_machinery_vehicles_yellow'] ? $item['resume_machinery_vehicles_yellow']['plaque'] : ""!!}</td>
                <td>{!! $item['tire_quantity'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>