<table border="1">
    <thead>
       <!-- <tr>
          <td></td>
          <td colspan="8">REPORTE DE HISTORIAS LABORALES</td>
       </tr> -->
       <tr>
          <td>Tipo de documento</td>
          <td>Número de documento</td>
          <td>Nombre</td>
          <td>Apellidos</td>
          <td>Dirección</td>
          <td>Teléfono</td>
          <td>Correo</td>
          <td>Estado</td>
          <td>Cantidad de documentos</td>
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
             @if ($item['state'] == 1)
                <td>Historia laboral - Activo</td>
             @else
                <td>Historia laboral - Retirado</td>
             @endif
             
             <td>{!! $item['total_documents'] !!}</td>
      
          </tr> 
       @endforeach
    </tbody>
 </table>