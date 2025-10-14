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
            <td>{{ $usuario->registerDate }}</td>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->username }}</td>
            <td>{{ $usuario->email }}</td>
            <td>
               @if($usuario->block == 0)
                  Activo
               @else
                  Inactivo
               @endif
            </td>
         </tr>
         @endforeach
      </tbody>
   </table>
@endpdf