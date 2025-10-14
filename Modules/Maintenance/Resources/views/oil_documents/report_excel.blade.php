<table border="1">
  <thead>
     <!-- <tr>
        <td></td>
        <td colspan="8">REPORTE DE DOCUMENTOS DE ACEITES</td>
     </tr> -->
     <tr>
        <td>Nombre</td>
        <td>Descripción</td>        
     </tr>
  </thead>
  <tbody>

     @foreach ($data as $key => $item)
        <tr>       
           <td>{!! $item['name'] !!}</td>
           <td>{!! $item['description'] !!}</td>
        </tr> 
     @endforeach
  </tbody>
</table>
