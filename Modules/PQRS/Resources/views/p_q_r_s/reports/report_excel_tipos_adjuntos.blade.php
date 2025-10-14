<table border="1">
    <thead>
        <tr>
            @php
                // Define la ruta de la imagen a la izquierda del reporte.
                $imagePath = 'storage/images/img_reporte_tipo_adjunto_izq.png';

                // Si existe, usa la ruta original; si no, usa una imagen por defecto.
                $imageSrcIzq = file_exists($imagePath) ? $imagePath : 'assets/img/components/sin_imagen.jpg';

                // Define la ruta de la imagen a la derecha del reporte.
                $imagePath = 'storage/images/img_reporte_tipo_adjunto_der.png';

                // Si existe, usa la ruta original; si no, usa una imagen por defecto.
                $imageSrcDer = file_exists($imagePath) ? $imagePath : 'assets/img/components/sin_imagen.jpg';
            @endphp
            <td align="center" valign="middle">
                <img src="{{ $imageSrcIzq }}" width="80" height="100">
            </td>
            <td colspan="3" style="border: none; text-align: center; margin: auto; vertical-align: middle;">
                CONSOLIDADO DE RESPUESTAS A LOS CIUDADANOS<br style='mso-data-placement:same-cell;' />
                (Peticiones, Quejas, Reclamos, Sugerencias, Denuncias)
            </td>
            <td align="center" valign="middle">
                <img src="{{ $imageSrcDer }}" width="80" height="100">
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;" rowspan="2">DEPENDENCIA</td>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;" rowspan="2">TOTAL DE RESPUESTAS</td>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;" colspan="3">TIPO DE ADJUNTO</td>
        </tr>
        <tr>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;">RESPUESTA SIN ADJUNTO</td>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;">RESPUESTA CON ADJUNTO</td>
            <td style="font-weight: bold; text-align: center; vertical-align: middle;">RESPUESTA CON ADJUNTO PENDIENTE</td>
        </tr>
    </thead>
    <tbody>
        @php
            // Inicializa los contadores para los diferentes tipos de respuestas
            $total_respuesta_sin_adjunto = 0;
            $total_respuesta_con_adjunto = 0;
            $total_sin_adjunto = 0;
            $total_por_fila = 0;
        @endphp

        <!-- Itera sobre los datos de cada dependencia -->
        @foreach ($data as $dependencia)
            @php
                // Calcula el total por fila sumando los diferentes tipos de respuestas
                $fila_total = $dependencia["respuesta_sin_adjunto"] + $dependencia["respuesta_con_adjunto"] + $dependencia["sin_adjunto"];

                // Acumula los totales en las variables correspondientes
                $total_respuesta_sin_adjunto += $dependencia["respuesta_sin_adjunto"];
                $total_respuesta_con_adjunto += $dependencia["respuesta_con_adjunto"];
                $total_sin_adjunto += $dependencia["sin_adjunto"];

                // Acumula el total de respuestas por fila
                $total_por_fila += $fila_total;
            @endphp

            <tr>
                <!-- Muestra los datos de la dependencia y los totales calculados -->
                <td style="text-align: left;">{{ $dependencia["dependencia"] }}</td>
                <td style="text-align: center;">{{ $fila_total }}</td>
                <td style="text-align: center;">{{ $dependencia["respuesta_sin_adjunto"] }}</td>
                <td style="text-align: center;">{{ $dependencia["respuesta_con_adjunto"] }}</td>
                <td style="text-align: center;">{{ $dependencia["sin_adjunto"] }}</td>
            </tr>
        @endforeach

        <!-- Muestra la fila de totales al final de la tabla -->
        <tr>
            <td style="text-align: center; background-color: #d3d3d3;"><strong>TOTAL</strong></td>
            <td style="text-align: center; background-color: #d3d3d3;">{{ $total_por_fila }}</td>
            <td style="text-align: center; background-color: #d3d3d3;">{{ $total_respuesta_sin_adjunto }}</td>
            <td style="text-align: center; background-color: #d3d3d3;">{{ $total_respuesta_con_adjunto }}</td>
            <td style="text-align: center; background-color: #d3d3d3;">{{ $total_sin_adjunto }}</td>
        </tr>
    </tbody>
</table>
