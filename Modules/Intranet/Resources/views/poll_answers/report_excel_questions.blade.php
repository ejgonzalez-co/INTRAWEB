
   @foreach ($data as $key => $item)
   <table border="1">
 
      <tr>
         <th style="background: gray"><b>{!! $item['question'] !!}</b></th>
         <th style="background: gray"><b>Funcionario</b></th>

      </tr>
    
      @foreach ($item['intranet_polls_answers']  as $key => $item_answer)
         <tr>            
            <td>{!! $item_answer['answer'] !!}</td>
            <td>{!! $item_answer['users_name'] !!}</td>
         </tr> 
      @endforeach

   </table>
   @endforeach

