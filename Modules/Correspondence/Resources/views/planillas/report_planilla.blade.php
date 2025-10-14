<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro y radicación de correspondencia recibida</title>
    <style type="text/css">
        @page { 
            margin: 20px 15px; 
            size: A4 landscape;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        
        .header {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            border: 1px solid #666;
        }
        
        .header-row {
            display: table-row;
        }
        
        .header-cell {
            display: table-cell;
            padding: 5px;
            border: 1px solid #666;
        }
        
        .logo-section {
            width: 100px;
            text-align: center;
        }
        
        .logo-section img {
            width: 80px;
            height: auto;
        }
        
        .title-section {
            text-align: center;
            width: 60%;
            vertical-align: middle;
        }
        
        .title-section h1 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        
        .info-section {
            width: 200px;
            text-align: right;
        }
        
        .info-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }
        
        .info-table td {
            border: 1px solid #666;
            padding: 2px 4px;
            background-color: #f0f0f0;
        }
        
        .info-table .label {
            font-weight: bold;
            background-color: #d0d0d0;
        }
        
        .main-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
            font-size: 9px;
            margin-top: 10px;
        }
        
        .main-table th {
            background-color: #d0d0d0;
            border: 1px solid #666;
            padding: 4px 2px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        
        .main-table td {
            border: 1px solid #666;
            padding: 2px 3px;
            text-align: center;
            font-size: 10px;
        }
        
        .main-table tbody tr {
            margin-bottom: 3px;
        }
        
        .main-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .main-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        .col-hora { width: 50px; }
        .col-fecha { width: 70px; }
        .col-radicacion { width: 80px; }
        .col-procedencia { width: 120px; }
        .col-destinatario { width: 100px; }
        .col-asunto { width: 80px; }
        .col-anexos { width: 50px; }
        .col-oficina { width: 100px; }
        .col-recibidos { width: 80px; }
        .col-novedad { width: 80px; }
        .col-consecutivo { width: 90px; }
        .col-estado { width: 70px; }
        
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        .no-data {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr class="header-row">
            <td class="header-cell logo-section">
                <img src="https://epa.gov.co/images/imagenes/LOGO_EPA.png" alt="Logo Universidad del Quindío">
            </td>
            <td class="header-cell title-section">
                <h1>Registro y radicación de correspondencia recibida</h1>
            </td>
            <td class="header-cell info-section">
                <table class="info-table">
                    <tr>
                        <td class="label" colspan="2">Documento Controlado</td>
                    </tr>
                    <tr>
                        <td class="label">Código:</td>
                        <td>GTI-P-024</td>
                    </tr>
                    <tr>
                        <td class="label">Versión:</td>
                        <td>01</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha de Emisión:</td>
                        <td>14-02-12</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-hora">Hora</th>
                <th class="col-fecha">Fecha</th>
                <th class="col-radicacion">Radicación</th>
                <th class="col-procedencia">Procedencia</th>
                <th class="col-destinatario">Destinatario</th>
                <th class="col-asunto">Asunto</th>
                <th class="col-anexos">Anexos</th>
                <th class="col-oficina">Oficina</th>
                <th class="col-recibidos">Recibidos</th>
                <th class="col-novedad">Novedad</th>
                <th class="col-consecutivo">Consecutivo PQR</th>
                <th class="col-estado">Estado</th>
            </tr>
        </thead>
        <tbody>
            @if (!$data["correspondencias"])
                <tr>
                    <td colspan="12" class="no-data">No se encontraron correspondencias para esta ruta</td>
                </tr>
            @else
                @foreach ($data["correspondencias"] as $item)

                    <tr>
                        <td>{!! \Carbon\Carbon::parse($item['created_at'])->format('H:i') !!}</td>
                        <td>{!! \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d') !!}</td>
                        <td>{!! $item['consecutive'] !!}</td>
                        <td class="text-left">{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['citizen_name'] : ($data["tipo"] == 'EXTERNA ENVIADA' ? $item['from'] : $item['from']) !!}</td>
                        <td class="text-left">{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['functionary_name'] : ($data["tipo"] == 'EXTERNA ENVIADA' ? $item['citizen_name'] : $item['recipients']) !!}</td>
                        <td class="text-left">{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['issue'] : $item['title'] !!}</td>
                        <td>{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['annexed'] : $item['annexes'] !!}</td>
                        <td class="text-left">{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['dependency_name'] : ($data["tipo"] == 'INTERNA' ? $item["list_dependencias_recipients"] : $item['dependency_from']) !!}</td>
                        <td></td>
                        <td class="text-left">{!! $data["tipo"] == 'EXTERNA RECIBIDA' ? $item['novelty'] : $item['observation'] !!}</td>
                        <td class="text-left">
                            {!! $data["tipo"] == 'EXTERNA RECIBIDA' && isset($item['pqr_datos']['pqr_id']) ? $item['pqr_datos']['pqr_id'] : 'N/A' !!}
                        </td>
                        <td class="text-left">
                            {!! $data["tipo"] == 'EXTERNA RECIBIDA' && isset($item['pqr_datos']['estado']) ? $item['pqr_datos']['estado'] : 'N/A' !!}
                        </td>
                                                
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</body>
</html>