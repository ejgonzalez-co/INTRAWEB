<table border="1">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="10">Reporte de PQRS</td>
      </tr>
      <tr class="font-weight-bold">
         <th>@lang('Radicado')</th>
         <th>@lang('Estado')</th>
         <th>@lang('Fecha de ingreso')</th>
         <th>@lang('Peticionario')</th>
         <th>@lang('Funcionario asignado')</th>
         <th>@lang('Dependencia competente')</th>
         <th>@lang('Fecha de vencimiento')</th>
         <th>@lang('Correspondencia asociada')</th>
         <th>@lang('Oficio (respuesta)')</th>
         <th>@lang('Fecha de respuesta')</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['pqr_id'] !!}</td>
            <td>{!! $item['estado'] !!}
               @if($item['estado'] != 'Abierto' && $item['estado'] != 'Finalizado vencido justificado' && $item['estado'] != 'Cancelado')
                  <br />({!! $item['linea_tiempo'] !!})
               @endif
            </td>
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['ciudadano_users'] ? $item['ciudadano_users']['name'] : $item['nombre_ciudadano'] !!}</td>
            <td>{!! $item['funcionario_destinatario'] !!}</td>
            <td>{!! $item['funcionario_users'] ? $item['funcionario_users']['dependencies']['nombre'] : '' !!}</td>
            <td>{!! $item['fecha_vence']!!}</td>
            <td>{!! $item['correspondence_external_received_id']!!}</td>
            <td>{!! $item['no_oficio_respuesta']!!}</td>
            <td>{!! $item['fecha_fin']!!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>