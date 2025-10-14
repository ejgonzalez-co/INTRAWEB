<table border="1" width="1000px">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="11">Reporte del historial de la correspondencia interna</td>
      </tr>
      <tr class="font-weight-bold">
         <th>Fecha de radicado</th>
         <th>Número de radicado</th>
         <th>Titulo</th>
         <th>Estado</th>
         <th>Tipo de documento</th>
         <th>Origen</th>
         <th>Dependencia</th>
         <th>Elaborado por</th>
         <th>Observaciones</th>
         <th>Vigencia</th>
         <th>Observación de historial</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td width="20%">{!! $item['created_at'] !!}</td>
            <td width="20%">{!! $item['consecutive'] !!}</td>
            <td width="20%">{!! $item['title'] !!}</td>
            <td width="20%">{!! $item['state'] !!}</td>
            <td width="20%">{!! $item['type_document'] !!}</td>
            <td width="20%">{!! $item['origen'] !!}</td>
            <td width="20%">{!! $item['dependency_from'] !!}</td>
            <td width="20%">{!! $item['elaborated_names'] !!}</td>
            <td width="20%">{!! $item['observation'] !!}</td>
            <td width="20%">{!! $item['year'] !!}</td>
            <td width="20%">{!! $item['observation_history'] !!}</td>
         </tr> 
      @endforeach
      @if(!$data)
      <tr>
         <td align="center" colspan="3">Sin registros para mostrar</td>
      </tr>
      @endif
   </tbody>
 </table>