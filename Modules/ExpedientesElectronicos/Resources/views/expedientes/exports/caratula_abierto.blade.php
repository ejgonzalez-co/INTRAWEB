<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carátula del Expediente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .container {
            text-align: center;
        }
        .header img {
            width: 100px; /* Ajusta el tamaño de la imagen según sea necesario */
        }
        .header, .footer {
            text-align: center;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
        }
        .titleApertura {
            font-size: 17px;
            font-weight: bold;
            margin-top: 22px;
        }
        .info {
            text-align: left;
            margin-top: 22px;
        }
        .info div {
            margin-bottom: 10px;
        }
        .signature {
            text-align: left;
            margin-top: 35px;
        }
        .signature img {
            width: 120px; /* Ajusta el tamaño de la imagen según sea necesario */
        }
        .footer div {
            margin-top: 5px;
        }
        hr {
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="storage/{{ $data["logo"] }}" alt="Logo de la entidad">
            <h5 style="margin-top: 8px;">{{ $data["nombre_entidad"] }}</h5>
        </div>
        <div class="title">Carátula del Expediente</div>
        <div class="info">
            <div><strong>Consecutivo:</strong> {{ $data["consecutivo_doc"] }}</div>
            <div style="text-align: right;">{{ $data["fecha_documento_extensa"] }}</div>
        </div>
        <hr>
        <div class="info">
            <div><strong>Expediente:</strong> {{ $data["consecutivo"] }}</div>
            <div><strong>Nombre del expediente:</strong> {{ $data["nombre_expediente"] }}</div>
            <div><strong>Fecha de caratulación:</strong> {{ $data["fecha_documento_extensa"] }}</div>
            <div><strong>Fecha incio del expediente:</strong> {{ $data["fecha_inicio_expediente"] }}</div>
            <div><strong>Usuario Caratulación:</strong> {{ $data["user_name"] }}</div>
            <div><strong>Usuario Responsable:</strong>{{ $data["nombre_responsable"] }}</div>
            <div><strong>Dependencia:</strong> {{ $data["oficina_productora_clasificacion_documental"] ? $data["oficina_productora_clasificacion_documental"]["nombre"] : "No asignada" }}</div>
            <div><strong>Serie:</strong> {{ $data["serie_clasificacion_documental"] ? $data["serie_clasificacion_documental"]["name_serie"] : "No asignada" }}</div>
            <div><strong>Subserie:</strong> {{ $data["subserie_clasificacion_documental"] ? $data["subserie_clasificacion_documental"]["name_subserie"] : "No asignada" }}</div>
            <div><strong>Descripción:</strong> {{ $data["descripcion"] }}</div>
        </div>
        @if( $data["estado"] == "Abierto")
            <div class="titleApertura">Apertura del Expediente {{ $data["consecutivo"] }}</div>
            <div class="signature">
                <img src="storage/{{ $data['firma_responsable'] }}" alt="Firma">
                <div>{{ $data["nombre_responsable"] }}</div>
                <div>ID firma: {{ $data["id_firma_caratula_apertura"] }}</div>
                <small>Fecha y hora: {{ $data["updated_at"] }}</small>
                <br>
                <small>Ip: {{ $data["ip_apertura"] ?? 'No disponible' }}</small>
                <br><br>
                <div>Accede a la página <a href="{{ config('app.url') }}/expedientes-electronicos/validar-documento-expediente">Validar carátula de apertura</a>. Utiliza el código de validación: <strong>{{ $data["codigo_acceso_caratula_apertura"] }}</strong> para verificar la autenticidad del
                documento y asegurarte de que no haya sufrido modificaciones.</div>
            </div>
        @endif
    </div>
</body>
</html>
