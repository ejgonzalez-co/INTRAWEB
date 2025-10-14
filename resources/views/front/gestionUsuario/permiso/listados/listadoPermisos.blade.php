@tablaRegistros(["tituloTabla"=>"permisos","totalRegistros"=>"paginationPermisos.total"])
   <tr>
      <th>Fecha de registro</th>
      <th>ID</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Operaciones</th>
   </tr>

   @slot('body')
   <tr v-for="permiso in listadoPermisos">
      <td>@{{ permiso.created_at }}</td>
      <td>@{{ permiso.id }}</td>
      <td>@{{ permiso.name }}</td>
      <td>@{{ permiso.description }}</td>
      <td>
         <button type="button" @click="abrirModal('actualizar', permiso)" class="btn btn-light btnAcciones btn-sm" data-toggle="tooltip" data-placement="top" title="Editar">
            <i class="fas fa-edit"></i>
         </button>

         <button type="button" @click="eliminarPermiso('/permisos/', permiso.id)" class="btn btn-light btnAcciones btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar">
            <i class="fas fa-trash-alt"></i>
         </button>
      </td>
   </tr>
   @endslot
@endtablaRegistros
@paginador(["listarPaginador"=>"listarPermisos()"])
<v-paginador :pagination="paginationPermisos" @paginate="listarPermisos()"></v-paginador>
@endpaginador