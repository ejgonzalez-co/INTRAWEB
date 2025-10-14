<table border="1" width="1000px">
   <thead>
      <tr class="text-center">
         <td align="center" colspan="11">Reporte del historial del expediente {{ $data[0]["consecutivo"] }}</td>
      </tr>
      <tr class="font-weight-bold">
            <th>Fecha y hora</th>
            <th>Usuario que realizó la acción</th>
            <th>Rol</th>
            <th>Responsable</th>
            <th>Estado</th>
            <th>Descripción del cambio</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $key => $item)
            <tr>
                <td>{!! $item['created_at'] !!}</td>
                <td>{!! $item['user_name'] !!}</td>
                <td>{!! $item['users_id'] == $item['id_usuario_enviador'] ? 'Operador' : ($item['users_id'] == $item['id_responsable'] ? 'Responsable' : 'Funcionario') !!}</td>
                <td>{!! $item['nombre_responsable'] !!}</td>
                <td>{!! $item['estado'] !!}</td>
                <td>{!! $item['detalle_modificacion'] !!}</td>
            </tr>
        @endforeach
        @if(!$data)
            <tr>
                <td align="center" colspan="6">Sin registros para mostrar</td>
            </tr>
        @endif
    </tbody>
 </table>
