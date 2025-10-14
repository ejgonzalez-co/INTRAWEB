@contenidoFront
<div  id="listadoUsuarios">
   <h4 style="text-align: center">Gestión de usuarios</h4>

	@include('front.gestionUsuario.usuario.formas.buscar')	
	
	<!-- Botón que permite abrir una ventana para crear un nuevo registro -->
	<button type="button" class="btn btn-info"  @click="abrirModal('registrar')">
		<i class="fa fa-plus fa-2x" style="padding-right: 5px;"></i>  Registrar usuario
	</button>
	
	<!-- Botón que permite generar un reporte en PDF del listado de registros -->
	<!-- <button type="button" class="btn btn-info"  @click="generarReportePDF()">
		<i class="fas fa-file-pdf fa-2x" style="padding-right: 5px;"></i>  Generar reporte PDF
	</button>-->
	
	<!-- Botón que permite generar un reporte de Excel del listado de registros -->
	<button type="button" class="btn btn-info"  @click="generarReporteExcel()">
		<i class="fas fa-file-excel fa-2x" style="padding-right: 5px;"></i>  Generar reporte Excel
	</button> 

	<!-- Botón que permite recagar el listado de registros -->
   <button type="button" class="btn btn-info"  onclick="window.location=''">
      <i class="fa fa-redo-alt fa-2x" style="padding-right: 5px;"></i>  Cargar página de nuevo
	</button>

	<!-- Se incluye el modal donde se puede crear y editar los tipos de solicitud -->
	@include('front.gestionUsuario.usuario.formas.crearEditarUsuario')

   @include('front.gestionUsuario.usuario.listados.listadoUsuarios')	
</div>

@section('script')
<script type="text/javascript">
	new Vue({
		mixins: [myMixin],
		el: '#listadoUsuarios',
		
		created: function(){
			
			//Se llama la función que carga los usuarios
			this.listarUsuarios();
		},
		data:{
			
			//Objeto que va contener el los datos del los usuarios
			usuarios: [],

			tipoUsuario: '',

			//Almacena los datos para el paginador de roles
         paginationUsuarios: {
            'total': 0,
            'current_page': 1,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0
         },
		},

		methods: {

			//Función que llama la función que permite listar los registros de usuario
			listarUsuarios: function() {
				//Se llama la función que recibe los parametros que devuelve el listado de usuarios
				this.listarRegistrosVue('/administrador/usuarios', 'usuarios', 'buscarUsuarios', 'paginationUsuarios');
			},

			abrirModal(accion, data = []){
				
				//Condición para recorrer las diferentes acciones que se van hacer en el modal
				switch(accion){

					//Caso cuando se va a crear un nuevo registro
					case 'registrar':{

						//Limpia los campos del formulario de registro
						jQuery("#crearEditarUsuario").trigger("reset");

						//Se asigna un titulo al modal
						this.tituloModal = 'Registrar usuario';

						//Se agrega que tipo de acción esto para cuando se de clic en el botón guardar
						this.tipoAccion = "registrar";

						//Se abre la ventana con el formulario de creación
						$('#crearEditarUsuarioModal').modal('show');

						break;
					}
					//Caso cuando se va a editar un nuevo registro
					case 'actualizar':{

						window.location = '/usuarios/'+data['id']+'/edit/';
						break;
					}
				}
			},
			
			//Función que se llama cuando se da clic en el boton guardar
			accionFormulario: function() {
				if (this.tipoAccion == 'registrar') {
               this.registrarUsuario('usuarios');
            }
			},

			//Función que permite registrar un usuario
			registrarUsuario: function(ruta) {
            this.registrarVue(ruta, 'listarUsuarios', 'crearEditarUsuario');
         },

			//Función que permite eliminar un usuario
         eliminarUsuario: function(ruta, id) {
            //Se llama la función que permite eliminar el registro de un usuario
            this.eliminarRegistroVue(ruta, id, 'listarUsuarios');
         },

			//Función que permite generar el reporte en PDF de los usuarios
			generarReportePDF: function(){
				this.generarReportePDFVue('/reportePDFUsuarios/', 'buscarUsuarios');
			},	

			//Función que permite generar el reporte en Excel de los usuarios
			generarReporteExcel: function() {
				this.generarReporteExcelVue('/reporteExcelUsuarios/', 'buscarUsuarios');
			},

			//Función que permite limpiar los campos de buscar el usuario
			limpiarCamposUsuarios: function() {
				//Limpia los campos del formulario de búsqueda
				jQuery("#buscarUsuarios").trigger("reset");

				//Se llama la funcíon para que racargue el listado de registros
				this.listarUsuarios();
			},
		}
	});
</script>
@endsection
@endcontenidoFront