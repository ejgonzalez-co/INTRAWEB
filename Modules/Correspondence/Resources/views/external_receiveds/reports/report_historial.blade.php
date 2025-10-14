<table border="1" width="1000px">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="10">Reporte del historial de la externa recibida</td>
      </tr>
      <tr class="font-weight-bold">
         <th>Fecha de radicado</th>
         <th>Número de radicado</th>
         <th>Nombre del funcionario</th>
         <th>Nombre de la dependencia</th>
         <th>Nombre del ciudadano</th>
         <th>Documento del ciudadano</th>
         <th>Correo del ciudadano</th>
         <th>Asunto</th>
         <th>Vigencia</th>
         <th>Observacion de la actualización</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td width="20%">{!! $item['created_at'] !!}</td>
            <td width="20%">{!! $item['consecutive'] !!}</td>
            <td width="20%">{!! $item['functionary_name'] !!}</td>
            <td width="20%">{!! $item['dependency_name'] !!}</td>
            <td width="20%">{!! $item['citizen_name'] !!}</td>
            <td width="20%">{!! $item['citizen_document'] !!}</td>
            <td width="20%">{!! $item['citizen_email'] !!}</td>
            <td width="20%">{!! $item['issue'] !!}</td>
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