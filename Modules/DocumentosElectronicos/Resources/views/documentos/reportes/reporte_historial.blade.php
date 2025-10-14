<table border="1" width="1000px">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="8">Reporte del historial de documento electrónico</td>
      </tr>
      <tr class="font-weight-bold">
         <th>Numero de radicado</th>
         <th>Titulo</th>
         <th>Estado</th>
         <th>Tipo de documento</th>
         <th>Compartidos</th>
         <th>Creado por</th>
         <th>Observaciones</th>
         <th>Vigencia</th>
         <th>Observacion de historial</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td width="20%">{!! $item['consecutivo'] !!}</td>
            <td width="20%">{!! $item['titulo_asunto'] !!}</td>
            <td width="20%">{!! $item['estado'] !!}</td>
            <td width="20%">{!! $item['de_tipos_documentos']->nombre !!}</td>
            <td width="20%">{!! $item['compartidos'] !!}</td>
            <td width="20%">{!! $item['users_name'] !!}</td>
            <td width="20%">{!! $item['observacion'] !!}</td>
            <td width="20%">{!! $item['vigencia'] !!}</td>
            <td width="20%">{!! $item['observacion_historial'] !!}</td>
         </tr>
      @endforeach
      @if(!$data)
      <tr>
         <td align="center" colspan="3">Sin registros para mostrar</td>
      </tr>
      @endif
   </tbody>
 </table>
