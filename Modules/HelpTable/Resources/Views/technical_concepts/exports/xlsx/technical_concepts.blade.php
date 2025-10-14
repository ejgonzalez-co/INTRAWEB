<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conceptos técnicos</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>Concepto técnico No</td>
                <td>Fecha emisión concepto</td>
                <td>En el inventario de</td>
                <td>Tipo de equipo</td>
                <td>Fecha solicitud</td>
                <td>Marca equipo</td>
                <td>Modelo o serie</td>
                <td>Serial</td>
                <td>Placa inventario</td>
                <td>Concepto técnico</td>
                <td>Observaciones</td>
                <td>Elaboró</td>
                <td>Revisó</td>
                <td>Aprobó</td>
                <td>Estado</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $technical_concept)
            <tr>
                <td style="text-align: left;">{!! $technical_concept['consecutive'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! date('Y-m-d',strtotime($technical_concept['date_issue_concept'])) ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['inventory_manager'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['equipment_type'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['created_at'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['equipment_mark'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['equipment_model'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['equipment_serial'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['inventory_plate'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['technical_concept'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['observations'] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['StaffMember']["name"] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['Reviewers']["name"] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['Approvers']["name"] ?? 'N/A' !!}</td>
                <td style="text-align: left;">{!! $technical_concept['status'] ?? 'N/A' !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
