<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE BASE DE CONOCIMIENTOS</td>
      </tr> -->
      <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Type Knowledge')</td>
         <td>@lang('Registrador por:')</td>
         <td>@lang('Affair')</td>
         <td>@lang('State')</td>
         <td>@lang('Description')</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['ht_tic_type_request']['name'] !!}</td>
            <td>{!! $item['users']['name'] !!}</td>
            <td>{!! $item['affair'] !!}</td>
            <td>{!! $item['knowledge_state_name'] !!}</td> 
            <td>{!! $item['knowledge_description'] !!}</td> 
         </tr> 
      @endforeach
   </tbody>
</table>