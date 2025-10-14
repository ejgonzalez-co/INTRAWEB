<table border="1">
   <tr style="margin: 5px">
       <td> Funcionarios rutina mensual</td>
   </tr>
   <thead>
       <tr>
       <td>Fecha de creación</td>
       <td>Funcionario</td>
       <td>Ensayos</td>
       <td>Fecha de inicio</td>
       <td>Fecha final</td>
       </tr>
   </thead>
   <tbody>
       @foreach ($data as $key => $item)
           <tr>
               <td>{!! $item['created_at'] !!}</td>
               <td>{!! $item['user_name'] !!}</td>
               <td>
                @foreach($item['lc_list_trials'] as  $key => $value)
                - {!! $value['name'] !!}
                <br>
                @endforeach
                </td>
                <td>{!! $item['fecha_inicio'] !!}</td>
                <td>{!! $item['fecha_fin'] !!}</td>
            </tr>
       @endforeach
   </tbody>
</table>
