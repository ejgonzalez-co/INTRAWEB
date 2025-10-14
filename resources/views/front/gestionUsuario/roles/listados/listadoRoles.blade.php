@tablaRegistros(["tituloTabla"=>"roles","totalRegistros"=>"paginationRol.total"])
   <tr>
      <th>Fecha de registro</th>
      <th>ID</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Operaciones</th>
   </tr>

   @slot('body')
   <tr v-for="rol in listadoRoles">
      <td>@{{ rol.created_at }}</td>
      <td>@{{ rol.id }}</td>
      <td>@{{ rol.name }}</td>
      <td>@{{ rol.description }}</td>
      <td>
         <!-- Botón que permite abrir la ventana para la edición del registro -->
         @btnAccion([
            'click'=>'abrirModal("actualizar", rol)',
            'class'=>'btn btn-light btnAcciones btn-sm',
            'titulo'=>'Editar',
            'fontawesome'=>'fas fa-edit'
         ])


         <!-- Botón que permite eliminar el registro -->
         @btnAccion([
            'click'=>'eliminarRol("/roles/", rol.id)',
            'class'=>'btn btn-light btnAcciones btn-sm',
            'titulo'=>'Eliminar',
            'fontawesome'=>'fas fa-trash-alt'
         ])
      </td>
   </tr>
   @endslot
@endtablaRegistros
@paginador(["listarPaginador"=>"listarRoles()"])
<v-paginador :pagination="paginationRol" @paginate="listarRoles()"></v-paginador>
@endpaginador