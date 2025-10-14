<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE PENSIONADOS CUOTAS PARTES</td>
       </tr> -->
       <tr>
          <td>@lang('Created_at')</td>
         <td>@lang('Type Document')</td>
          <td>@lang('Description')</td>
          <td>@lang('Sheet')</td>
       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item ['created_at'] !!}</td>
             <td>{!! $item['config_documents']['name']  !!}</td>
             <td>{!! $item['description'] !!}</td>
             <td>{!! $item['sheet'] !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>