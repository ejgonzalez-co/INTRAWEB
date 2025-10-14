<table border="1">
    <thead>
        <tr>
            <td style="font-size: 20px; background: #12E54F;"><h1>Información de desgaste</h1></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>
            <td style="font-size: 20px; background: #12E54F;"></td>

        </tr>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;">Fecha de revisión</td>
            <td style="background: #BDBDBD; font-size:14px;">Proceso</td>
            <td style="background: #BDBDBD; font-size:14px;">Nombre del equipo o maquinaria</td>
            <td style="background: #BDBDBD; font-size:14px;">Placa</td>
            <td style="background: #BDBDBD; font-size:14px;">Profundidad del registro</td>
            <td style="background: #BDBDBD; font-size:14px;">Total, desgaste</td>
            <td style="background: #BDBDBD; font-size:14px;">Kilometraje en la revisión</td>
            <td style="background: #BDBDBD; font-size:14px;">Total, recorrido</td>
            <td style="background: #BDBDBD; font-size:14px;">Valor actual de llanta</td>
            <td style="background: #BDBDBD; font-size:14px;">Costo por km</td>
            <td style="background: #BDBDBD; font-size:14px;">Presión en la revisión</td>
            <td style="background: #BDBDBD; font-size:14px;">Observación</td>
        </tr>
    </thead>
    <tbody>

        @foreach ($data as $key => $item)
            <tr>            
                <td>{!! $item['revision_date'] !!}</td>
                <td>{!! $item['tire_informations'] ? $item['tire_informations']->name_dependencias : "" !!}</td>
                <td>{!! $item['tire_informations'] ? $item['tire_informations']->name_machinery : "" !!}</td>
                <td>{!! $item['tire_informations'] ? $item['tire_informations']->plaque : "" !!}</td>
                <td>{!! $item['registration_depth'] !!}</td>
                <td>{!! $item['wear_total'] !!}</td>
                <td>{!! $item['revision_mileage'] !!}</td>
                <td>{!! $item['route_total'] !!}</td>
                <td>{!! $item['wear_cost_mm'] !!}</td>
                <td>{!! $item['cost_km'] !!}</td>
                <td>{!! $item['revision_pressure'] !!}</td>
                <td>{!! $item['observation'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>