<table border="1">
    <tr>
        <td>
            <img :src="'{{ asset('img/logo_epa.png') }}'" width="240" height="133">
        </td>
        <td colspan="3" align="center">
            <strong>
                Reporte programación toma de muestra
            </strong>
        </td>
        <td>
            <img :src="'{{ asset('img/components/logo_leca.png') }}'" width="230" height="133">
        </td>
    </tr>
    <thead>
        <tr>
            
            <td><strong>Fecha de la toma</strong></td>
            <td><strong>Punto de la toma</strong></td>
            <td><strong>Dirección</strong></td>
            <td><strong>Funcionario</strong></td>
            <td><strong>Usuario creador</strong></td>
            <td><strong>¿ Aplica para duplicado ?</strong></td>
            <td><strong>Observación</strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['sampling_date'] !!}</td>
                <td>{!! $item['lc_sample_points'] ? $item['lc_sample_points']->point_location : '' !!}</td>
                <td>{!! $item['direction'] !!}</td>
                <td>{!! $item['users_name'] !!}</td>
                <td>{!! $item['user_creador'] !!}</td>
                <td>{!! $item['duplicado'] !!}</td>
                <td>{!! $item['observation'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>