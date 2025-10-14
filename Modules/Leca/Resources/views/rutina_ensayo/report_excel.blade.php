<table border="1">
    <tr>
        <td>
            @section('section_img', '/assets/img/components/LOGO_EPA.png')
            {{-- <img src="/var/www/clients/client1/web5/web/intranet/public/assets/img/components/LOGO_EPA.png" width="240" height="133"> --}}
        </td>
        <td colspan="3" align="center">
            <strong>
                Reporte de recepción de muestras
            </strong>
        </td>
        <td>
            @section('section_img', '/assets/img/components/logo_leca.png')
            {{-- <img src="/var/www/clients/client1/web5/web/intranet/public/assets/img/components/logo_leca.png" width="230" height="133"> --}}
        </td>
    </tr>
    <thead>
        <tr>
            
            <td><strong>Identificación asignada por Leca</strong></td>
            <td><strong>Fecha de la toma</strong></td>
            <td><strong>Hora de la toma</strong></td>
            <td><strong>Fecha y hora de la recepción de muestra</strong></td>
            <td><strong>Responsable de entrega</strong></td>
            <td><strong>Responsable de la recepción</strong></td>
            <td><strong>Estado de la muestra</strong></td>
            <td><strong>¿Se acepta?</strong></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['sample_reception_code'] !!}</td>
                <td>{!! $item['created_at'] !!}</td>
                <td>{!! $item['hour_from_to'] !!}</td>
                <td>{!! $item['reception_date'] !!}</td>
                <td>{!! $item['user_name'] !!}</td>
                <td>{!! $item['name_receipt'] !!}</td>
                @if ($item['state_receipt'] == 1)
                <td>Muestra recepcionada</td>
                @else
                    <td>Pendiente de recepción</td>
                @endif
                <td>{!! $item['is_accepted'] !!}</td>
            </tr> 
        @endforeach
    </tbody>
</table>