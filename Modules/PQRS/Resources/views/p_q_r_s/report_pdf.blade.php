<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte PQRS</title>
</head>
<body>
    <table border="1" style="font-size: 10px">
        <thead>
            <tr>
                <th>Radicado</th>
                <th>Estado</th>
                <th>Tipo de finalización</th>
                <th>Nombre eje tematico</th>
                <th>Plazo eje tematico</th>
                <th>Fecha de ingreso</th>
                <th>Asunto</th>
                <th>Peticionario</th>
                <th>Funcionario asignado</th>
                <th>Dependencia competente</th>
                <th>Fecha de vencimiento</th>
                <th>Correspondencia asociada</th>
                <th>Oficio (respuesta)</th>
                <th>Fecha de respuesta</th>
                <th>Recibido</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{!! htmlentities($item['pqr_id'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['estado'] ?? 'N/A') !!}
                        @if($item['estado'] != 'Abierto' && $item['estado'] != 'Finalizado vencido justificado' && $item['estado'] != 'Cancelado')
                            <br />({!! $item['linea_tiempo'] !!})
                        @endif
                    </td>
                    <td>{!! htmlentities($item['tipo_finalizacion'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['nombre_ejetematico'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['plazo'] ?? 'N/A') .' '. htmlentities($item['pqr_eje_tematico']->plazo_unidad ?? 'Días') !!}</td>
                    <td>{!! htmlentities($item['created_at'] ?? 'N/A') !!}</td>
                    <td width="10%">{!! htmlentities($item['contenido'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['nombre_ciudadano'] ?? 'N/A') !!}</td>
                    {{-- <td>{!! $item['ciudadano_users'] ? $item['ciudadano_users']->name : $item['nombre_ciudadano'] !!}</td> --}}
                    <td>{!! htmlentities($item['funcionario_destinatario'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['dependencia_funcionario'] ?? 'N/A') !!}</td>
                    {{-- <td>{!! $item['funcionario_users'] ? $item['funcionario_users']->dependencies->nombre : '' !!}</td> --}}
                    <td>{!! htmlentities($item['fecha_vence'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['pqr_correspondence']['consecutive'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['no_oficio_respuesta'] ?? 'N/A') !!}</td>
                    <td>{!! htmlentities($item['fecha_fin'] ?? 'N/A') !!}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>