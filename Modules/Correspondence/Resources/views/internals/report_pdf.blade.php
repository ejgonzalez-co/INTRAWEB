<table>
   <thead>
      <tr>
         <th>CONSECUTIVO</th>
         <th>ESTADO</th>
         <th>ENVIADO A</th>
         <th width="30">RADICADO POR</th>
         <th>PLANTILLA</th>
         <th width="18">FECHA DE CREACIÓN</th>
         <th>ORIGEN</th>
         <th>ULTIMA MODIFICACIÓN</th>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $request)
         <tr>
            <td>{!! isset($request['consecutive']) ? $request['consecutive'] : null !!}</td>
            <td>{!! isset($request['state']) ? $request['state'] : null !!}</td>
            <td>{!! isset($request['recipients']) ? $request['recipients'] : null !!}</td>
            <td>{!! isset($request['from']) ? $request['from'] : null !!}</td>
            <td>{!! isset($request['plantilla']) ? $request['plantilla'] : null !!}</td>
            <td>{!! isset($request['created_at']) ? $request['created_at'] : null !!}</td>
            <td>{!! isset($request['origen']) ? $request['origen'] : null !!}</td>
            <td>{!! isset($request['modification']) ? $request['modification'] : null !!}</td>

         </tr>
      @endforeach
   </tbody>
</table>
