<table border="1">
  <thead>
     <!-- <tr>
        <td></td>
        <td colspan="8">REPORTE DE ELEMENTOS DE DESGASTE DEL ACEITE</td>
     </tr> -->
     <tr>
        <td>Fecha de registro</td>
        <td>Nombre del elemento</td>
        <td>Grupo</td>
        <td>Componente</td>
        <td>Rango superior</td>
        <td>Rango inferior</td>
        
     </tr>
  </thead>
  <tbody>

     @foreach ($data as $key => $item)
        <tr>       
           <td>{!! $item['created_at'] !!}</td>
           <td>{!! $item['element_name'] !!}</td>
           <td>{!! $item['group'] ? $item['group'] : 'N/A'  !!}</td>
           <td>{!! $item['component'] ? $item['component'] : 'N/A' !!}</td>
           <td>{!! $item['rank_higher'] !!}</td>
           <td>{!! $item['rank_lower'] !!}</td>
        </tr> 
     @endforeach
  </tbody>
</table>
