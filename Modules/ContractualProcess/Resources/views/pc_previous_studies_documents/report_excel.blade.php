
    <table>
    <tbody>
      <tr>
        <td style="text-align: center"  > <strong> FECHA</strong></td>
        <td style="text-align: center"  > <strong> NOMBRE</strong></td>
        <td style="text-align: center"  > <strong> DESCRIPCIÓN</strong></td>
       
      </tr>
      @foreach ($data as $key => $item)
     
      <tr> 
        <td>{!! htmlentities( $item['created_at'] ? $item['created_at'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['name'] ? $item['name'] : '' ) !!}</td>
        <td>{!! htmlentities( $item['description'] ? $item['description'] : '' ) !!}</td>
      </tr>
      @endforeach
    </tbody>
    </table>