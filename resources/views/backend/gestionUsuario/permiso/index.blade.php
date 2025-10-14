@contenidoBackend
<div  id="listadoPermisos">
   <h4 style="text-align: center">Gestión de permisos</h4>

   <!-- Se incluye el formulario para buscar permisos -->

   <!-- Botón que permite abrir una ventana para crear un nuevo registro de un permiso -->
   <button type="button" class="btn btn-info"  @click="abrirModal('registrar')">
      <i class="fa fa-plus fa-2x" style="padding-right: 5px;"></i>  Nuevo permiso
   </button>

   <!-- Botón que permite generar un reporte de excel del listado de registros -->
   <!-- <button type="button" class="btn btn-info"  @click="generarReporteExcel()">
      <i class="fas fa-file-excel fa-2x" style="padding-right: 5px;"></i>  Generar reporte Excel
   </button> -->

   <!-- Botón que permite recagar la página de nuevo -->
   <button type="button" class="btn btn-info"  onclick="window.location=''">
      <i class="fa fa-redo-alt fa-2x" style="padding-right: 5px;"></i>  Cargar página de nuevo
   </button>

   <!-- Se incluye el modal donde se puede crear y editar un permiso -->
   @include('backend.gestionUsuario.permiso.formularios.crearEditar')

   <!-- Se incluye la tabla donde se muestra los registro de los permisos -->
   @include('backend.gestionUsuario.permiso.listados.listadoPermisos')

</div>
@section('script')
<script>
   new Vue({
      mixins: [funcionesGlobalVue],
      el: '#listadoPermisos',
      
      created: function(){
         //Ejecuta la función que lista los registros
         this.listarPermisos();         
      },

      data:{
         //Objeto que va contener los datos de un permisos
         datosPermisos: {},
         
         //Objeto que va contener el listado de registro de permisos
         listadoPermisos: [],

         //Almacena los datos para el paginador del listado de permisos
         paginationPermisos: {
            'total': 0,
            'current_page': 1,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0
         },
         
      },
      methods: {

         //Función que permite obtener el lisado de permisos
         listarPermisos: function() {
            //Se llama la función que recibe los parametros que devuelve el listado de permisos
            this.listarRegistrosVue('/administrador/permisos', 'listadoPermisos', 'buscarPermisos', 'paginationPermisos');
         },
         
         abrirModal(accion, data = []){
            
            switch(accion){
               case 'registrar':{
                  //Se asigna un titulo al modal
                  this.tituloModal     = 'Registrar permiso';

						this.datosPermisos = {};
                  this.nombBtn      = 'Guardar';

                   //Se agrega que tipo de acción esto para cuando se de clic en el botón guardar
                   this.tipoAccion = "registrar";
                  break;
               }
               case 'actualizar':{

                  //Se asigna un titulo al modal
                  this.tituloModal = 'Actualizar permiso';

                  //Se Asigna los datos para llenar el formulario de edición
                  this.datosPermisos = data;

                  this.nombBtn     = 'Actualizar';

                  //Se agrega que tipo de acción esto para cuando se de clic en el botón guardar
                  this.tipoAccion = 'actualizar';

                  break;
               }
            }

            //Se abre la ventana con el formulario de edición
            $('#crearEditarPermisoModal').modal('show');
         },
         
         //Función que se llama cuando se da clic en el boton guardar
         accionFormulario: function() {
            if (this.tipoAccion == "registrar") {
               this.registraPermiso('permisos');
            }
            else if (this.tipoAccion == "actualizar") {
               this.actualizarPermiso('permisos/', this.datosPermisos.id);
               
            }
         },

         //Función que permite registrar un permiso
         registraPermiso: function(ruta) {
            //Se llama la función que permite crear el registro
            this.registrarVue(ruta, 'listarPermisos', 'crearEditarPermiso');
         },

         //Función que permite actualizar un permiso
         actualizarPermiso: function(ruta, id) {
            //Se llama la función que permite actualizar el registro de un permiso
            this.actualizarRegistroVue(ruta, id, 'listarPermisos', 'crearEditarPermiso');
         },

         //Función que permite eliminar un permiso
         eliminarPermiso: function(ruta, id) {
            //Se llama la función que permite eliminar el registro de un permiso
            this.eliminarRegistroVue(ruta, id, 'listarPermisos');
         },
      }
   });
</script>
@endsection
@endcontenidoBackend