<table border="1">
    <tr>
        <td>
            {{-- <img src="/var/www/clients/client1/web5/web/intranet/public/assets/img/components/LOGO_EPA.png" width="240" height="133"> --}}
        </td>
        <td colspan="3" align="center">
            <strong>
                Reporte de Toma de muestras
            </strong>
        </td>
        <td>
            {{-- <img src="/var/www/clients/client1/web5/web/intranet/public/assets/img/components/logo_leca.png" width="230" height="133"> --}}
        </td>
    </tr>
    <thead>
        <tr>
            
            <td><strong>Fecha de la muestra</strong></td>
            <td><strong>Punto de la toma</strong></td>
            <td><strong>Dirección</strong></td>
            <td><strong>Funcionario</strong></td>
            <td><strong>Observaciones</strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['created_at'] !!}</td>
                <td>{!! $item['lc_sample_points'] ? $item['lc_sample_points']['id'] : '' !!}</td>
                <td>{!! $item['lc_sample_points'] ? $item['lc_sample_points']['point_location'] : '' !!}</td>
                <td>{!! $item['user_name'] !!}</td>
                <td>{!! $item['observations'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>