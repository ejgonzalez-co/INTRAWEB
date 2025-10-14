<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intraweb | Certificado de conceptos técnicos</title>
</head>

<style type="text/css">
    * {
        margin: 0;
        padding: 0;

    }

    body {
        padding: 15px 0px 0px 15px;
    }

    tbody {
        border: 0px 1px 1px 1px solid black;
    }

    td {
        font-size: 20px;
    }

    .tg {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .tg td {
        font-family: Arial, sans-serif;
        font-size: 8.5px;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 7px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg .tg-9wq8 {
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-91w8 {
        border-color: inherit;
        font-size: 10px;
        text-align: center;
        vertical-align: top
    }

    .tg .tg-77x5 {
        font-size: 9px;
        font-weight: bold;
        text-align: left;
        vertical-align: top
    }

    .tg .tg-0pky {
        font-size: 10.5px;
        text-align: left;
        vertical-align: top
    }

    .tg .tg-73a0 {
        border-color: inherit;
        font-size: 12px;
        text-align: left;
        vertical-align: top
    }

    .tg .tg-0p48 {
        border-color: inherit;
        font-size: 11px;
        font-weight: bold;
        text-align: left;
        vertical-align: top
    }

    .table__input-option {
        text-align: left;
        vertical-align: center;
    }

    .table__input-code {
        border-bottom: 1px solid #000;
        padding: 0 0 0 2px;
    }
</style>

<body>
    <table class="tg" style="table-layout: fixed;height:150px; width: 750px">
        <thead>
            <tr>
                <th rowspan="1"><img src="{{ asset('storage')}}/{{ $data['entity_logo'] }}"
                        alt="Escudo de Armenia" height="50px" width="50px"></th>
                <th colspan="9" rowspan="1" style="font-size:10px;border-bottom:none">
                    <h3>CONCEPTO TÉCNICO <br><br>Secretaría de Tecnologías de la Información y las
                        Comunicaciones<br>P18. Proceso de Infraestructura tecnológica<br></h3><br>
                </th>
                <th class="table__input-option" style="font-size: 8.5px;" colspan="2" rowspan="1">
                    <div class="" style="margin:-3px -5px;">
                        <p class="table__input-code">Código R-TI-PIT-037</p><br>
                        <p class="table__input-code"> Fecha: 11/10/2012</p><br>
                        <p class="table__input-code">Versión: 001</p><br>
                        <p style="padding: 0 0 0 2px;">Página 1 de 1</p>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:justify;padding-left: 5px;border:1px solid #000;font-size: 8px;" colspan="12">
                    <strong>Generalidades: </strong>para el diligenciamiento del formato Concepto tecnico , Tenga
                    encuenta lo siguiente:Funcionario TIC:Es el funcionario de la Secretaria TIC que brinda el concepto
                    tecnico, En el inventario De: va la persona que tiene el elemento en su inventario, En tipo de
                    equipo va si es ( computador, impresora, escaner,Servidor, Monitor, Perifericos) , en modelo o serie
                    va la referencia del equipo, en placa de inventario va el numero asignado por bienes y suministros
                    en el inventario.En Firma Tecnico es la firma del funcionario que emite el concepto, El lider IT es
                    la persona de las TIC que tiene a cargo el proceso 18 de la secretaria TIC y Firma Usuario es la
                    persona a la cual se le entrega el concepto tecnico.</td>
            </tr>
            <tr>
                <td style="border:1px solid #000000; text-align:center;font-size:10px" colspan="12">
                    <strong>INFORMACIÓN DE LA DEPENDENCIA</strong>
                </td>
            </tr>
            <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px">
                <td style="font-size:10px;" colspan="6"> <b> 
                    Concepto Técnico No:</b> {!! $data['consecutive'] ?? 'N/E' !!} 
                </td>
                <td colspan="6" style="font-size:10px;">
                    <b>Fecha de Emisión Concepto:</b> {!! $data['date_issue_concept'] ? date('Y-m-d', strtotime($data['date_issue_concept'])) : 'N/E' !!}
                </td>
            </tr>
            <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">
                <td style="font-size:10px;" colspan="12">
                    <b>En el inventario De:</b> {!! $data['inventory_manager'] ?? 'N/E' !!}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000000; text-align:center;font-size:10px" colspan="12">
                    <strong>INFORMACIÓN DEL EQUIPO</strong>
                </td>
            </tr>
            <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">
                <td style="font-size:10px;" colspan="6"><b>Tipo de Equipo:</b> {!! $data['equipment_type'] ?? 'N/E' !!}</td>
                <td style="font-size:10px;" colspan="6"><b>Fecha Solicitud:</b> {!! $data['created_at'] ?? 'N/E' !!}</td>
            </tr>
            <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">
                <td style="font-size:10px;" colspan="6">
                    <b>Marca Equipo:</b> {!! $data['equipment_mark'] ?? 'N/E' !!}
                </td>
                <td style="font-size:10px;" colspan="6">
                    <b>Modelo o Serie:</b> {!! $data['equipment_model'] ?? 'N/E' !!}
                </td>
            </tr>
            <tr style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">
                <td style="font-size:10px;" colspan="6">
                    <b>Serial:</b> {!! $data['equipment_serial'] ?? 'N/E' !!}
                </td>
                <td style="font-size:10px;" colspan="6">
                    <b>Placa Inventario:</b> {!! $data['inventory_plate'] ?? 'N/E' !!}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000000; text-align:center;font-size:10px" colspan="12">
                    <strong>CONCEPTO TÉCNICO</strong>
                </td>
            </tr>
            <tr>
                <td style="text-align:justify; border-left:1px solid #000;border-right:1px solid #000;font-size:10px;vertical-align: top;" height="120" colspan="12">
                    {!! $data['technical_concept'] ?? 'N/E' !!}
                </td>
            </tr>
            <tr>
                <td style="border:1px solid #000000; text-align:center;font-size:10px" colspan="12">
                    <strong>OBSERVACIONES</strong>
                </td>
            </tr>
            <tr>
                <td style="font-size:10px;text-align:justify; border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;vertical-align: top;"
                    colspan="12" height="80">
                    {!! $data['observations'] ?? 'N/E' !!}

                </td>
            </tr>
            <tr style="text-align:center; border-left:1px solid #000;border-right:1px solid #000;">
                @if($data["status"] === 'Aprobado')
                    @php
                        $firma = $data["Approvers"]["url_digital_signature"] ?? null;
                        $firmaPath = $firma ? public_path('storage/' . $firma) : null;
                    @endphp
                    <td class=""></td>
                    <td colspan="10">

                    @if ($firma && file_exists($firmaPath) && is_file($firmaPath))
                        <img src="{{ $firmaPath }}" width="100px" height="100px">
                    @else
                        <p>Usuario sin firma</p>
                    @endif

                        <br><b>{!! $data["Approvers"]["name"] !!}<br>{!! $data["Approvers"]["dependencies"]["nombre"] !!}</b></td>
                    <td class=""></td>                    
                @else                    
                    <td class=""></td>
                    <td colspan="10" height="100">Sin aprobación</td>
                    <td class=""></td>                    
                @endif
            </tr>
            <tr>
                <td style="border-left:1px solid #000;border-right:1px solid #000;font-size:6.5px;" colspan="12">
                    <p style="margin: 0 0 2px 0"><b>Elaboró:</b> {!! $data["Reviewers"] ? $data["Technicians"]["name"] : 'N/E' !!}<p>
                    <b>Revisó:</b> {!! $data["status"] == "Aprobación pendiente" || $data["status"] == "Devolver al revisor" || $data["status"] == "Aprobado" ? $data["Reviewers"]["name"] : 'N/E' !!}
                </td>
            </tr>
            <tr>
                <td class="tg-91w8"
                    style="font-size: 6.5px;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"
                    colspan="12">
                    ----------------------------------------------------------------------------------------------------------------<br>Carrera
                    16 No 15-28, Armenia Quindío - CAM Piso 4 - Código Postal.620004<br>Correo
                    Electrónico:tic@armenia.gov.co</td>
            </tr>
        </tbody>
    </table>
</body>
