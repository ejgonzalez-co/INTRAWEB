@pdf
   <table border="1">
      <thead>
         <tr>
            <th>Fecha de registro</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Estado</th>
         </tr>
      </thead>
      <tbody>
         @foreach($usuarios as $usuario)
         <tr>
            <td>{{ $usuario->created_at }}</td>
            <td>{{ $usuario->nombre }}</td>
            <td>{{ $usuario->username }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->estado }}</td>
         </tr>
         @endforeach
      </tbody>
   </table>
@endpdf