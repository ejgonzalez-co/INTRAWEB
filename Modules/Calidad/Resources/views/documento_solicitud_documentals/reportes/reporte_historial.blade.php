<table border="1" width="1000px">
    <thead>
        <tr class="text-center">
            <td align="center" colspan="13">Reporte del historial de la solicitud documental</td>
        </tr>
        <tr class="font-weight-bold">
            <td>Fecha de la solicitud</td>
            <td>Macroproceso/Sistema</td>
            <td>Nombre del solicitante</td>
            <td>Cargo</td>
            <td>Tipo de solicitud</td>
            <td>Tipo de documento</td>
            <td>Nombre del documento</td>
            <td>Versión</td>
            <td>Estado</td>
            <td>Proceso al que pertenece</td>
            <td>Justificación o necesidad de la solicitud</td>
            <td>Funcionario responsable de atender la solicitud</td>
            <td>Cargo</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td width="20%">{!! $item['created_at'] !!}</td>
                <td width="20%">{!! $item['macro_proceso'] ? $item['macro_proceso']->nombre : "N/A" !!}</td>
                <td width="20%">{!! $item['nombre_solicitante'] !!}</td>
                <td width="20%">{!! $item['cargo'] !!}</td>
                <td width="20%">{!! $item['tipo_solicitud'] !!}</td>
                <td width="20%">{!! $item['documento_tipo_documento'] ? $item['documento_tipo_documento']->nombre : "N/A" !!}</td>
                <td width="20%">{!! $item['nombre_documento'] !!}</td>
                <td width="20%">{!! $item['version'] !!}</td>
                <td width="20%">{!! $item['estado'] !!}</td>
                <td width="20%">{!! $item['proceso'] ? $item['proceso']->nombre : "N/A" !!}</td>
                <td width="20%">{!! $item['justificacion_solicitud'] !!}</td>
                <td width="20%">{!! $item['funcionario_responsable'] !!}</td>
                <td width="20%">{!! $item['cargo'] !!}</td>
            </tr>
        @endforeach
        @if(!$data)
        <tr>
            <td align="center" colspan="13">Sin registros para mostrar</td>
        </tr>
        @endif
    </tbody>
 </table>
