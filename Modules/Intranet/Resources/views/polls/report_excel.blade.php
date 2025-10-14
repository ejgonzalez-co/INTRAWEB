<table border="1">
   <thead>
     <tr>
         <td colspan="9">Encuestas</td>
      </tr>


      <tr>
        <th>@lang('Title')</th>
        <th>@lang('Description')</th>
        <th>@lang('Date Open')</th>
        <th>@lang('Date Close')</th>
        <th>@lang('Creator')</th>
        <th>@lang('State')</th>
        <th>@lang('Groups')</th>
        <th>@lang('Users')</th>
        <th>Cantidad de respuestas</th>

      </tr>
   </thead>
   <tbody>

      @foreach ($data as $key => $item)
         <tr>            
            <td>{!! $item['title'] !!}</td>
            <td>{!! $item['description'] !!}</td>
            <td>{!! $item['date_open'] !!}</td>
            <td>{!! $item['date_close'] !!}</td>
            <td>{!! $item['creator']!!}</td>
            <td>{!! $item['state_name']!!}</td>
            <td>                        
               <ul>
                  @foreach ($item['work_groups'] as $keyw => $work_group)
                     <li>
                        <p>{!! $work_group['name']!!}</p>
                     </li>
                     @endforeach
               </ul>
            </td>
            <td>                        
               <ul>
                  @foreach ($item['users'] as $keyu => $user)
                     <li>
                        <p>{!! $user['name']!!}</p>
                     </li>
                     @endforeach
               </ul>
            </td>
            <td>{!! count($item['total_answered'])!!}</td>


         </tr> 
      @endforeach
   </tbody>
</table>