<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE PROVEEDORES</td>
       </tr> -->
       <tr>
         <td>@lang('Type Person')</td>
          <td>@lang('Type Document')</td>
          <td>@lang('Identification')</td>
          <td>@lang('Name')</td>
          <td>@lang('Email')</td>
          <td>@lang('Phone')</td>
          <td>@lang('State')</td>
          <td>@lang('Dirección')</td>
          <td>@lang('Municipality')</td>
          <td>@lang('Department')</td>
          <td>@lang('Tipo de actividad')</td>
       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item['type_person'] !!}</td>
             <td>{!! $item['document_type'] !!}</td>
             <td>{!! $item['identification'] !!}</td>
             <td>{!! htmlentities($item['name']) !!}</td>
             <td>{!! $item['mail'] !!}</td>
             <td>{!! $item['phone'] !!}</td>
             <td>{!! $item['state'] !!}</td>
             <td>{!! $item['address'] !!}</td>
             <td>{!! $item['municipality'] !!}</td>
             <td>{!! $item['department'] !!}</td>
             <td>{!! $item['mant_types_activity']?$item['mant_types_activity']->name: null!!}</td>
          </tr> 
       @endforeach
    </tbody>
 </table>