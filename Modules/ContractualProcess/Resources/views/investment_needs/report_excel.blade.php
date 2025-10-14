<table border="1">
   <thead>
      <tr>
         <td align="center"><b>Nombre del proceso</b></td>
         <td align="center"><b>Descripción</b></td>
         <td align="center"><b>Unidad</b></td>
         <td align="center"><b>Cantidad</b></td>
         <td align="center"><b>Unit Value</b></td>
         <td align="center"><b>Valor total</b></td>
         <td align="center"><b>Estado</b></td>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td>{!! $item['name_process'] !!}</td>
            <td>{!! $item['description'] !!}</td>
            <td>{!! $item['unit'] !!}</td>
            <td>{!! $item['quantity'] !!}</td>
            <td>{!! '$ '.number_format($item['unit_value'], 0, ".", ".") !!}</td>
            <td>{!! '$ '.number_format($item['total_value'], 0, ".", ".") !!}</td>
            <td>{!! $item['state_name'] !!}</td>
         </tr> 
      @endforeach
   </tbody>

</table>