<table border="1">
   <thead>
      <tr>
         <td align="center" colspan="4">2. Adquisiciones Planeadas</td>
      </tr>
      <tr>
         <td align="center">Descripción</td>
         <td align="center">Modalidad de selección </td>
         <td align="center">Fuente de los recursos</td>
         <td align="center">Valor total estimado</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>
            <td>{!! $item['description'] !!}</td>
            <td></td>
            <td>{!! $item['type_need'] !!}</td>
            <td>{!! '$ '.number_format($item['total_value'], 0, ".", ".") !!}</td>
         </tr> 
      @endforeach
   </tbody>
</table>