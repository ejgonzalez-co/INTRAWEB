<table class="table table-bordered table-hover">
   <thead class="thead-light">
      <tr>
         <th>ID</th>
         <th>Nombre</th>
         <th>Descripción</th>
         <th colspan="3">Operaciones</th>
      </tr>
   </thead>
   <tbody>
      @foreach($permisos as $permiso)
      <tr>
         <td>{{ $permiso->id }}</td>
         <td>{{ $permiso->name }}</td>
         <td>{{ $permiso->description }}</td>
         @can('permisos.show')
         <td width="10px">
            <a href="{{ route('permisos.show', $permiso->id) }}" 
            class="btn btn-sm btn-default">ver</a>
         </td>
         @endcan
         @can('permisos.edit')
         <td width="10px">
            <a href="{{ route('permisos.edit', $permiso->id) }}" 
            class="btn btn-sm btn-default">editar</a>
         </td>
         @endcan
         @can('permisos.destroy')
         <td width="10px">
            @if ($permiso->name != "Super administrador")
            {!! Form::open(['route' => ['permisos.destroy', $permiso->id], 
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
