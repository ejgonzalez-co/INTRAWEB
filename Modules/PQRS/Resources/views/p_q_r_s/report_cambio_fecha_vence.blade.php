<table border="1" width="1000px">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="3">Reporte de actualización de fecha de vencimiento de PQRS</td>
      </tr>
      <tr class="font-weight-bold">
         <th>@lang('Radicado')</th>
         <th>@lang('Fecha de vencimiento anterior')</th>
         <th>@lang('Fecha de vencimiento actualizada')</th>
         
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td width="20%">{!! $item['pqr_id'] !!}</td>
            <td width="20%">{!! $item['fecha_vence_anterior'] !!}</td>
            <td width="20%">{!! $item['fecha_vence_actualizada'] !!}</td>
         </tr> 
      @endforeach
      @if(!$data)
      <tr>
         <td align="center" colspan="3">Sin registros para mostrar</td>
      </tr>
      @endif
   </tbody>
</table>