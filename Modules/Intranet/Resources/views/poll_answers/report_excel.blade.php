<table border="1">
   <thead>
      <tr>
          <td colspan="4" style="background: gray">Respuesta de encuesta</td>
       </tr>
 
   
    </thead>
   @foreach ($data as $key => $item)

      <tr>
         <th style="background: gray"><b>Pregunta</b></th>
         <th style="background: gray" colspan="3"><b>{!! $item['question'] !!}</b></th>
      </tr>
      <tr>
         <th><b>Pregunta</b></th>
         <th><b>respuesta</b></th>
         <th><b>Funcionario</b></th>
         <th><b>Fecha de respuesta</b></th>
      </tr>
      @foreach ($item['intranet_polls_answers']  as $key => $item_answer)
         <tr>            
            <td>{!! $item['question'] !!}</td>
            <td>{!! $item_answer['answer'] !!}</td>
            <td>{!! $item_answer['users_name'] !!}</td>
            <td>{!! $item_answer['created_at'] !!}</td>
         </tr> 
      @endforeach

   @endforeach

</table>