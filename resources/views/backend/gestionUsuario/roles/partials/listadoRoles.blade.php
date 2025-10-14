<table class="table table-bordered table-hover">
   <thead class="thead-light">
      <tr>
         <th>ID</th>
         <th>Nombre</th>
         <th colspan="3">Operaciones</th>
      </tr>
   </thead>
   <tbody>
      @foreach($roles as $role)
      <tr>
         <td>{{ $role->id }}</td>
         <td>{{ $role->name }}</td>
         @can('roles.show')
         <td width="10px">
            <a href="{{ route('roles.show', $role->id) }}" 
            class="btn btn-sm btn-default">ver</a>
         </td>
         @endcan
         <td width="10px">
            <a href="{{ route('roles.edit', $role->id) }}" 
            class="btn btn-sm btn-default">editar</a>
         </td>
         @can('roles.destroy')
         <td width="10px">
            @if ($role->name != "Super administrador")
                  {!! Form::open(['route' => ['roles.destroy', $role->id], 
                  'method' => 'DELETE']) !!}
                     <button class="btn btn-sm btn-danger">
                        Eliminar
                     </button>
                  {!! Form::close() !!}
            @endif
         </td>
         @endcan
      </tr>
      @endforeach
   </tbody>
</table>
