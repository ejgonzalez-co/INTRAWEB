   <table>
      <thead>
         <tr >
            <td >No. </td>
            <td >Sigla Proceso</td>
            <td >Código<br />Proceso</td>
            <td >Código<br />Serie</td>
            <td >Serie Documental</td>
            <td >Código<br />Subserie</td>
            <td style="background-color: #d8d6d6; font-size : 14px;"><b>Subserie Documental </b></td>
         </tr>
      </thead>
      <tbody>
         @foreach ($data as $key => $item)
            @foreach ($item as $key => $item2)
               <tr >
                  <td style="text-align: center" >{!! $item2['No'] !!}</td>            
                  <td style="text-align: center">{!! $item2['codigo'] !!}</td>
                  <td style="text-align: center">{!! $item2['codigo_oficina_productora'] ? $item2['codigo_oficina_productora'] : null !!}</td>
                  <td style="text-align: center">{!! $item2['no_serie'] ? $item2['no_serie'] : null !!}</td>
                  <td style="text-align: center">{!! $item2['name_serie'] ? $item2['name_serie'] : null !!}</td>
                  <td style="text-align: center">{!! $item2['no_subserie'] ? $item2['no_subserie'] : null !!}</td>
                  <td >{!! $item2['name_subserie']  ? $item2['name_subserie'] : null !!}</td>
               </tr> 
            @endforeach
         @endforeach
      </tbody>
   </table>