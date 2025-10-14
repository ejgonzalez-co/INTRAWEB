<table border="1">
   <thead>
      <tr>
         <td>No. de orden</td>
         <td>Usuario solicitante</td>
         <td>Dependencia</td>
         <td>Fecha de creación</td>
         <td>Fecha de vencimiento</td>
         <td>Fecha de atención</td>
         <td>Usuario de soporte</td>
         <td>Asignado por</td>
         <td>Estado</td>
         <td>Asunto</td>
         <td>Descripción</td>
         <td>Seguimiento</td>
         <td>Categoría</td>
         <td>Tipo</td>
         <td>Tipo de solicitud</td>
         <td>¿Fue atendido oportunamente el servicio?</td>
         <td>¿El técnico fue cordial y respetuoso?</td>
         <td>¿La atención brindada por el técnico solucionó completamente la falla?</td>
         <td>¿Cuál de los aspectos anteriores considera que podríamos mejorar?</td>
      </tr>
   </thead>
   <tbody>
      @foreach ($data as $key => $item)
         <tr>
            <td>{!! $item['id'] !!}</td>
            <td>{!! isset($item['users_name']) ? $item['users_name'] : (isset($item['users']) ? $item['users']['name'] : '') !!}</td>
            <td>{!! isset($item['users']->dependencies) ? $item['users']->dependencies->nombre: '' !!}</td>
            <td>{!! $item['created_at'] !!}</td>
            <td>{!! $item['expiration_date'] !!}</td>
            <td>{!! $item['date_attention'] !!}</td>
            <td>{!! isset($item['assigned_user'])? $item['assigned_user']->name: '' !!}</td>
            <td>{!! isset($item['assigned_by']) ? $item['assigned_by']->name: ''  !!}</td>
            <td>{!! str_replace("<br>", " ", $item['status_name']); !!}</td>
            <td>{!! $item['affair'] !!}</td>
            <td>{!! htmlentities($item['description']) !!}</td>
            <td>{!! htmlentities(strip_tags($item['tracing'])) !!}</td>
            <td>{!! $item['tic_type_tic_categories'] ? $item['tic_type_tic_categories']->name: '' !!}</td>
            <td>{!! $item['tic_type_assets'] ? $item['tic_type_assets']->name: '' !!}</td>
            <td>{!! $item['tic_type_request'] ? $item['tic_type_request']->name: '' !!}</td>
            @php
            $encuesta = DB::table("ht_tic_satisfaction_poll")->where('ht_tic_requests_id',$item['id'])->get()->toArray();
            foreach ($encuesta as $respuesta) {
               echo '<td>'.$respuesta->reply.'</td>';
            }
            @endphp
         </tr> 
      @endforeach
   </tbody>
</table>