<table class="table table-bordered">
    <tr>
        <td style="font-size: 20px; background: #12E54F;"><h1>Información de llantas en el almacén</h1></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
    </tr>
    
    <thead>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;"><b>Tipo de asignación de la llanta</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Fecha de ingreso de la llanta</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Referencia de llanta</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Tipo de llanta</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Profundidad de la llanta en (mm)</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Kilometraje inicial</b> </td>
        </tr>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['assignment_type']!!}</td>
                <td>{!! $item['date_register']!!}</td>
                <td>{!! $item['tire_reference']!!}</td>
                <td>{!! $item['type_tire'] !!}</td>
                <td>{!! $item['depth_tire'] !!}</td>
                <td>{!! $item['mileage_initial'] !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
