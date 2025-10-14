@contenidoBackend
<div  id="listadoRoles">
   <h4 style="text-align: center">Gestión de roles</h4>

   <!-- Se incluye el formulario para buscar roles -->

   <!-- Botón que permite abrir una ventana para crear un nuevo registro de un rol -->
   <button type="button" class="btn btn-info"  @click="abrirModal('registrar')">
      <i class="fa fa-plus fa-2x" style="padding-right: 5px;"></i>  Nuevo rol 1
   </button>

   <!-- Botón que permite generar un reporte de excel del listado de registros -->
   <!-- <button type="button" class="btn btn-info"  @click="generarReporteExcel()">
      <i class="fas fa-file-excel fa-2x" style="padding-right: 5px;"></i>  Generar reporte Excel
   </button> -->

   <!-- Botón que permite recagar la página de nuevo -->
   <button type="button" class="btn btn-info"  onclick="window.location=''">
      <i class="fa fa-redo-alt fa-2x" style="padding-right: 5px;"></i>  Cargar página de nuevo
   </button>

   <!-- Se incluye la tabla donde se muestra los registro de los roles -->
   @include('backend.gestionUsuario.roles.listados.listadoRoles')

   <!-- Se incluye el modal donde se puede crear y editar un rol -->
   @include('backend.gestionUsuario.roles.formularios.crearEditar')
</div>

@section('script')
<script>
   new Vue({
      mixins: [funcionesGlobalVue],
      el: '#listadoRoles',
      
      created: function(){

         //Llama la función que lista los registros
         this.listarRoles();
      },
      computed: {
         retornarPermisosRoles: function() {

            if (this.tipoAccion == 2) {

               this.permissions= [];

               permisos =  this.permisosRoles;
               
               for (var index = 0; index < permisos.length; index++) {
                  this.permissions.push(permisos[index].permission_id);
               }

               return  this.permissions;
            }else{
               this.permissions= [];
            }
		   },
      },
      data:{
         
         //Objeto que va contener el los datos de los roles
         listadoRoles: [],

         //Objeto que contiene los datos del rol cuando se esta editando
         datosRol: {},

         listadoPermisos: [],
        
         //Objeto que almacena la lista de permisos de un rol
         permisosRoles: [],

         //Objeto que almacena la lista de permisos
         permissions: [],

         nombBtn : '',

         //Almacena los datos para el paginador de roles
         paginationRol: {
            'total': 0,
            'current_page': 1,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0
         },
         
      },
      methods: {
         
         abrirModal(accion, data = []){
            
            this.listarPermisos();

           
            switch(accion){
               case 'registrar':{
                  this.tituloModal     = 'Registrar rol';

                  this.datosRol    = {};
                  this.permissions = [];
                  this.permisosRoles=[];

                  this.nombBtn     = 'Guardar';
                  this.tipoAccion  = 1;
                  break;
               }
               case 'actualizar':{

                  this.tituloModal = 'Actualizar rol';

                  this.datosRol = data;

                  this.nombBtn     = 'Actualizar';
                  this.tipoAccion = 2;
                  
                  //Se llama la funcion que permite listar los permisos del rol
                  this.listarPermisosRoles();


                  break;
               }
            }

            //Abre el formulario de edición
            $('#crearEditarRolModal').modal('show');           
         },
         
         //Función que controla las acciones de los modales
         accionFormulario: function() {
            //Condición que valida si se esta intentando crear un registro de un rol
            if (this.tipoAccion == 1) {
               //Se llama la funcion que permite crear el registro
               this.registraRol('/roles');
            }
            //Condición que valida si se esta intentando actualizar el registro de un rol
            else if (this.tipoAccion == 2) {
               //Se llama la funcion que permite actualizar el registro
               this.actualizarRol('/roles/', this.datosRol.id);
               
            }
         },

         //Función que permite obtener el lisado de roles
         listarRoles: function() {
            //Se llama la función que recibe los parametros que devuelve el listado de roles
            this.listarRegistrosVue('/administrador/roles', 'listadoRoles', 'buscarRoles', 'paginationRol');
         },


         //Función que permite registrar un rol
         registraRol: function(ruta) {
            //Se llama la función que permite crear el registro
            this.registrarVue(ruta, 'listarRoles', 'crearEditarRol');
         },


         //Función que permite actualizar un rol
         actualizarRol: function(ruta, id) {
            //Se llama la función que permite actualizar el registro de un rol
            this.actualizarRegistroVue(ruta, id, 'listarRoles', 'crearEditarRol');
         },

         //Función que permite eliminar un rol
         eliminarRol: function(ruta, id) {
            //Se llama la función que permite eliminar el registro de un rol
            this.eliminarRegistroVue(ruta, id, 'listarRoles');
         },


         listarPermisos: function() {
            this.listarRegistrosVueGet('administrador/permisos', 'listadoPermisos');
         },

         //Método que permite listar las ciudades
         listarPermisosRoles: function() {
            //Condición que valida que el id del departamento no este vacio
            if (this.datosRol.id != "") {

               this.listarRegistrosVueGet('permisosRoles?&rol='+this.datosRol.id, 'permisosRoles', 'get');
            }

            
         },
      }
   });
</script>
@endsection
@endcontenidoBackend