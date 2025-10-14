<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>SOLICITUDES DOCUMENTALES</title>
    </head>
    <body>
        <table border="1">
            <thead>
                <tr>
                    <th align="center" colspan="13">REPORTE DE SOLICITUDES DOCUMENTALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
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
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{!! $item['created_at'] !!}</td>
                        <td>{!! $item['macro_proceso'] ? $item['macro_proceso']->nombre : "N/A" !!}</td>
                        <td>{!! $item['nombre_solicitante'] !!}</td>
                        <td>{!! $item['cargo'] !!}</td>
                        <td>{!! $item['tipo_solicitud'] !!}</td>
                        <td>{!! $item['documento_tipo_documento'] ? $item['documento_tipo_documento']->nombre : "N/A" !!}</td>
                        <td>{!! $item['nombre_documento'] !!}</td>
                        <td>{!! $item['version'] !!}</td>
                        <td>{!! $item['estado'] !!}</td>
                        <td>{!! $item['proceso'] ? $item['proceso']->nombre : "N/A" !!}</td>
                        <td>{!! $item['justificacion_solicitud'] !!}</td>
                        <td>{!! $item['funcionario_responsable'] !!}</td>
                        <td>{!! $item['cargo'] !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
