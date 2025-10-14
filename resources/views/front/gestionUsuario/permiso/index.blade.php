@contenidoBackend

<div  id="listadoPermisos">
   <h4 style="text-align: center">Gestión de permisos</h4>
 
      
   <button type="button" class="btn btn-info"  @click="abrirModal('registrar')">
      <i class="fa fa-plus fa-2x" style="padding-right: 5px;"></i>  Nuevo permiso
   </button>
   <button type="button" class="btn btn-info"  onclick="window.location=''">
      <i class="fa fa-redo-alt fa-2x" style="padding-right: 5px;"></i>  Cargar página de nuevo
   </button>

   @include('backend.gestionUsuario.permiso.listados.listadoPermisos')
   @include('backend.gestionUsuario.permiso.formularios.crearEditar')
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

         datosPermisos: {},
         
         //Objeto que va contener el los datos del linea
         listadoPermisos: [],

         nombBtn : '',

         //Almacena los datos para el paginador de roles
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
                  this.tituloModal     = 'Registrar permiso';
						this.datosPermisos = {};
                  this.nombBtn      = 'Guardar';
                  this.tipoAccion   = 1;
                  break;
               }
               case 'actualizar':{

                  this.tituloModal = 'Actualizar permiso';

                  this.datosPermisos = data;

                  this.nombBtn     = 'Actualizar';
                  this.tipoAccion = 2;

                  break;
               }
            }

            //Abre el formulario de edición
            $('#crear_editar').modal('show');
         },
         
         accionFormulario: function() {
            if (this.tipoAccion == 1) {
               this.registraPermiso('permisos');
            }
            else if (this.tipoAccion == 2) {
               this.actualizarPermiso('permisos/', this.datosPermisos.id);
               
            }
         },

         //Función que permite registrar un rol
         registraPermiso: function(ruta) {
            //Se llama la función que permite crear el registro
            this.registrarVue(ruta, 'listarPermisos');
         },

         //Función que permite actualizar un rol
         actualizarPermiso: function(ruta, id) {
            //Se llama la función que permite actualizar el registro de un rol
            this.actualizarRegistroVue(ruta, id, 'listarPermisos');
         },

         //Función que permite eliminar un rol
         eliminarPermiso: function(ruta, id) {
            //Se llama la función que permite eliminar el registro de un rol
            this.eliminarRegistroVue(ruta, id, 'listarPermisos');
         },
      }
   });
</script>
@endsection
@endcontenidoBackend