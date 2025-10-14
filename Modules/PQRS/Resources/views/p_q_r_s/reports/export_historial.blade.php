<table border="1" width="1000px">
  <thead>
     <tr class="text-center">
        <td align="center" colspan="3">Reporte del historial del PQRS</td>
     </tr>
     <tr class="font-weight-bold">
        <th>Numero de radicado</th>
        <th>Nombre del ciudadano</th>
        <th>Documento del ciudadano</th>
        <th>Correo del ciudadano</th>
        <th>Contenido</th>
        <th>Folios</th>
        <th>Estado</th>
        <th>Linea de tiempo</th>
        <th>Nombre eje tematico</th>
        <th>Fecha del cambio</th>
     </tr>
  </thead>
  <tbody>
     @foreach ($data as $key => $item)
        <tr>
           <td width="20%">{!! $item['pqr_id'] !!}</td>
           <td width="20%">{!! $item['nombre_ciudadano'] !!}</td>
           <td width="20%">{!! $item['documento_ciudadano'] !!}</td>
           <td width="20%">{!! $item['email_ciudadano'] !!}</td>
           <td width="20%">{!! $item['contenido'] !!}</td>
           <td width="20%">{!! $item['folios'] !!}</td>
           <td width="20%">{!! $item['estado'] !!}</td>
           <td width="20%">{!! $item['linea_tiempo'] !!}</td>
           <td width="20%">{!! $item['nombre_ejetematico'] !!}</td>
           <td width="20%">{!! $item['created_at'] !!}</td>
        </tr> 
     @endforeach
     @if(!$data)
     <tr>
        <td align="center" colspan="3">Sin registros para mostrar</td>
     </tr>
     @endif
  </tbody>
</table>