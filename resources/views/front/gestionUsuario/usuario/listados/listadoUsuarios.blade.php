@tablaRegistros(["tituloTabla"=>"usuarios","totalRegistros"=>"paginationUsuarios.total"])
   <tr>
      <th>Fecha de registro</th>
      <th>Nombre</th>
      <th>Usuario</th>
      <th>Email</th>
      <th>Operaciones</th>
   </tr>

   @slot('body')
   <tr v-for="usuario in usuarios">
      <td>@{{ usuario.created_at }}</td>
      <td>@{{ usuario.nombre }}</td>
      <td>@{{ usuario.username }}</td>
      <td>@{{ usuario.email }}</td>
      <td>

         <!-- Botón que permite abrir la ventana para la edición del registro -->
         @btnAccion([
            'click'=>'abrirModal("actualizar", usuario)',
            'class'=>'btn btn-light btnAcciones btn-sm',
            'titulo'=>'Editar',
            'fontawesome'=>'fas fa-edit'
         ])
         
         <!-- Botón que permite eliminar el registro -->
         @btnAccion([
            'click'=>'eliminarRol("/usuarios/", usuario.id)',
            'class'=>'btn btn-light btnAcciones btn-sm',
            'titulo'=>'Eliminar',
            'fontawesome'=>'fas fa-trash-alt'
         ])
      </td>
   </tr>
   @endslot
@endtablaRegistros
@paginador(["listarPaginador"=>"listarUsuarios()"])
<v-paginador :pagination="paginationUsuarios" @paginate="listarUsuarios()"></v-paginador>
@endpaginador