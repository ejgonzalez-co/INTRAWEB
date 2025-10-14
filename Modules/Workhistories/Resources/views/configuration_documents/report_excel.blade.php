<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE PENSIONADOS CUOTAS PARTES</td>
       </tr> -->
       <tr>
         <td>@lang('Created_at')</td>
         <td>@lang('Name')</td>
         <td>@lang('Description')</td>
         <td>@lang('State')</td>
       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>
            <td>{!! $item ['created_at'] !!}</td>
             <td>{!! $item ['name'] !!}</td>
             <td>{!! $item['description'] !!}</td>
             <td>{!! $item['state_name']  !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>