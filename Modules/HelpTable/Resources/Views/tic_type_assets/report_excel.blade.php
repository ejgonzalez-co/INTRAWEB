<table border="1">
   <thead>
      <!-- <tr>
         <td></td>
         <td colspan="8">REPORTE DE BASE DE CONOCIMIENTOS</td>
      </tr> -->
      <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Name')</td>
         <td>@lang('Categoría')</td>
         <td>@lang('Description')</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['name'] !!}</td>
            <td>{!! $item['tic_type_tic_categories']->name !!}</td>
            <td>{!! $item['description'] !!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>