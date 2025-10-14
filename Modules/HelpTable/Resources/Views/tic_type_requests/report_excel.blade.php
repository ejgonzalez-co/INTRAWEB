<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE TIPOS DE SOLICITUDES</td>
      </tr> -->
      <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Name')</td>
         <td>@lang('Unit Time')</td>
         <td>@lang('Type Term')</td>
         <td>@lang('Description')</td>
         <td>@lang('Application deadline')</td>
         <td>@lang('Early warning')</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['name'] !!}</td>
            <td>{!! $item['unit_time_name'] !!}</td>
            <td>{!! $item['type_term_name'] !!}</td>
            <td>{!! $item['description'] !!}</td>
            <td>{!! $item['term'] !!} {!! $item['unit_time_name'] !!}</td>
            <td>{!! $item['early'] !!} {!! $item['unit_time_name'] !!}</td> 
         </tr> 
      @endforeach
   </tbody>
</table>