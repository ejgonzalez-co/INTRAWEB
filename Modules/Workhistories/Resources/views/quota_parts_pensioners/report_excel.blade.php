<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE PENSIONADOS CUOTAS PARTES</td>
       </tr> -->
       <tr>
         <td>@lang('Type Document')</td>
          <td>@lang('Número de documento')</td>
          <td>@lang('Name')</td>
          <td>@lang('Surname')</td>
          <td>@lang('Dirección')</td>
          <td>@lang('Teléfono')</td>
          <td>@lang('Email')</td>
          <td>@lang('Level study')</td>
          <td>@lang('Group Ethnic')</td>
          <td>@lang('Deceased')</td>
          <td>@lang('Observación')</td>

       </tr>
    </thead>
    <tbody>
 
       @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item['type_document'] !!}</td>
             <td>{!! $item['number_document'] !!}</td>
             <td>{!! $item['name'] !!}</td>
             <td>{!! $item['surname'] !!}</td>
             <td>{!! $item['address'] !!}</td>
             <td>{!! $item['phone'] !!}</td>
             <td>{!! $item['email'] !!}</td>
             <td>{!! $item['level_study'] !!}</td>
             <td>{!! $item['group_ethnic'] !!}</td>
             <td>{!! $item['deceased'] !!}</td>
             <td>{!! $item['observation_deceased'] !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>