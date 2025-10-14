<table border="1">
    <thead>
       <tr>
         <td>Fecha de creación del registro</td>
          <td>Nombre del responsable</td>
          <td>Observación</td>
          
       </tr>
    </thead>
    <tbody>
      @foreach ($data as $key => $item)
      <tr>            
         <td>{!! $item['created_at'] !!}</td>
         <td>{!! $item['user_name'] !!}</td>
         <td>{!! $item['coments_consecutive'] !!}</td>
         
         
      </tr> 
      @endforeach
      
    </tbody>
 </table>