<table border="1">
    @php
       $count = 1;
    @endphp
 
    <thead>
       <tr>
          <td colspan="10" style="text-align: center">Roles</td>
       </tr>
       <tr>
          <td rowspan="2" style="background-color:#d8d8d8;text-align: center;">N°</td>
          <td rowspan="2" width="50" style="background-color:#d8d8d8;text-align: center;">Nombre</td>
          <td rowspan="2" width="50" style="background-color:#d8d8d8;text-align: center;">Modulo</td>
          <td colspan="3" style="background-color:#d8d8d8;text-align: center;">Permisos</td>
          <td rowspan="2" width="50" style="background-color:#d8d8d8;text-align: center;">Descripción</td>
       </tr>
       <tr>
         <td width="30" style="text-align: center;">Gestión</td>
         <td width="30" style="text-align: center;">Reportes</td>
         <td width="30" style="text-align: center;">Solo consulta</td>
       </tr>
    </thead>
    <tbody>
       @foreach ($data as $key => $rol)
          @foreach ($rol["rol_permissions"] as $key => $rolPermission )
             <tr>
                <td style="text-align: center;border:1px solid black">{!! $count !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $rol['name'] !!}</td>           
                <td style="text-align: center;border:1px solid black">{!! $rolPermission['module'] !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $rolPermission['can_manage'] ? 'X' : null !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $rolPermission['can_generate_reports'] ? 'X' : null !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $rolPermission['only_consultation'] ? 'X' : null !!}</td>
                <td style="text-align: center;border:1px solid black">{!! $rol['description'] ? $rol['description'] : null !!}</td>
             </tr>
             @php
                $count = $count+1;
             @endphp
          @endforeach
       @endforeach
    </tbody>
 </table>