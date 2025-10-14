<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE AUTORIZACIONES DE CREACIÓN DE ACTIVOS</td>
       </tr> -->
       <tr>
         <td>@lang('Dependencia')</td>
          <td>@lang('Responsable')</td>
          <td>@lang('Created_at')</td>

       </tr>
    </thead>
    <tbody>

       @foreach ($data as $key => $item)
          <tr>            
             <td>{!! $item['dependencias'] ? $item['dependencias']->nombre: '' !!}</td>
             <td>{!! $item['usuarios'] ? $item['usuarios']->name: '' !!}</td>
             <td>{!! $item['created_at'] !!}</td>

          </tr> 
       @endforeach
    </tbody>
 </table>