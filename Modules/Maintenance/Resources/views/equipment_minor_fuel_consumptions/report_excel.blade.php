<table class="table table-bordered">
    <tr>
        <td style="font-size: 20px; background: #12E54F;"><h1>Consumo de combustible por equipo</h1></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
        <td style="font-size: 20px; background: #12E54F;"></td>
    </tr>
    
    <thead>
        <tr>
            <td style="background: #BDBDBD; font-size:14px;"><b>Fecha de registro</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Fecha del suministro</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Fecha de modificación</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Descripción del equipo</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Código del equipo</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Proceso</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>Galones suministrados</b> </td>
            <td style="background: #BDBDBD; font-size:14px;"><b>@lang('Name Receives Equipment')</b> </td>
        </tr>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['created_at']!!}</td>
                <td>{!! $item['supply_date']!!}</td>
                <td>{!! $item['updated_at']!!}</td>
                <td>{!! $item['mant_resume_equipment_machinery'] ? $item['mant_resume_equipment_machinery']['name_equipment'] : 'N/A' !!}</td>
                <td>{!! $item['mant_resume_equipment_machinery'] ? $item['mant_resume_equipment_machinery']['no_inventory'] : 'N/A' !!}</td>
                <td>{!! $item['dependencias'] ? $item['dependencias']['nombre'] : 'N/A' !!}</td>
                <td>{!! $item['gallons_supplied']. ' gal' !!}</td>
                <td>{!! $item['name_receives_equipment'] !!}</td>
            </tr>
        @endforeach
    </tbody>
</table>
