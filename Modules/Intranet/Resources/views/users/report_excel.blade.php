
<table>
   <thead>
     <tr>
       <th style="text-align: center" colspan="13">REPORTE DE LOS USUARIOS DE ESTRUCTURA ORGANIZACIONAL</th>
     </tr>
   </thead>
   <tbody>
     <tr>
       <td style="text-align: center"  > <strong> NOMBRE</strong></td>
       <td style="text-align: center"  > <strong> CORREO</strong></td>
       <td style="text-align: center"  > <strong> TIPO DE USUARIO</strong></td>
       <td style="text-align: center"  > <strong> NOMBRE COMPLETO</strong></td>
       <td style="text-align: center"  > <strong> CARGO</strong></td>
       <td style="text-align: center"  > <strong> DEPENDENCIA</strong></td>
     </tr>
     @foreach ($data as $key => $item)
     <tr>
       <td>{!! htmlentities($item['name'] ?? 'N/A') !!}</td>
       <td>{!! htmlentities($item['email'] ?? 'N/A') !!}</td>
       <td>{!! htmlentities($item['user_type'] ?? 'N/A') !!}</td>
       <td>{!! htmlentities($item['fullname'] ?? 'N/A') !!}</td>
       <td>{!! htmlentities($item['positions']['nombre'] ?? 'N/A') !!}</td>
       <td>{!! htmlentities($item['dependencies']['nombre'] ?? 'N/A') !!}</td>
     </tr>
     @endforeach
   </tbody>
   </table>