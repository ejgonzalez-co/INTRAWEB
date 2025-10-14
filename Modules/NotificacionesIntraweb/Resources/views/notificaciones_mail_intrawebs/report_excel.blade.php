<table border="1">
   <thead>
      <tr class="text-center">
         <td style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; " align="center" colspan="16">Reporte de notificaciones</td>
      </tr>
      <tr class="font-weight-bold">
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Fecha</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Módulo de origen</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Consecutivo</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Correo del destinatario</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Estado de la notificación</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Usuario que realizó la notifiación</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Asunto de la notificaión</th>
         <th style="background-color: #f0f0f0;font-weight: bold; padding: 10px; border: 1px solid #ddd; ">Respuesta del envio</th>
      
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td>{!! htmlentities($item['created_at'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['modulo'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['consecutivo'] ?? 'N/A')  !!}</td>
            <td>{!! htmlentities($item['correo_destinatario'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['estado_notificacion'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['user_name'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['asunto_notificacion'] ?? 'N/A') !!}</td>
            <td>{!! htmlentities($item['respuesta_servidor_notificacion']  ?? 'N/A') !!}</td>
         </tr>
      @endforeach
   </tbody>
</table>
