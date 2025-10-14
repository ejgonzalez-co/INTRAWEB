<table border="1">
   <thead>
      <tr>
         <td>No.</td>
         <td>Sigla Proceso</td>
         <td>Código Proceso</td>
         <td>Código Serie</td>
         <td>Serie Documental</td>
         <td>Código Subserie</td>
         <td>Subserie Documental</td>
      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         @foreach ($item as $key => $item2)
         <tr>
            <td style="text-align: center">{!! $item2->No !!}</td>            
            <td style="text-align: center">{!! $item2->codigo !!}</td>
            <td style="text-align: center">{!! $item2->codigo_oficina_productora ? $item2->codigo_oficina_productora : null !!}</td>
            <td style="text-align: center">{!! $item2->no_serie ? $item2->no_serie : null !!}</td>
            <td style="text-align: center">{!! $item2->name_serie ? $item2->name_serie: null !!}</td>
            <td style="text-align: center">{!! $item2->no_subserie ? $item2->no_subserie : null !!}</td>
            <td style="text-align: center">{!! $item2->name_subserie ? $item2->name_subserie : null !!}</td>
         </tr> 
         @endforeach
      @endforeach
      
   </tbody>
</table>