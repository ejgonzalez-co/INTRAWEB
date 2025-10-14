@contenidoBackend
@section('migas')
<li class="breadcrumb-item active">Usuarios</li>
@endsection
<div  id="listadoUsuarios">
   <h4 style="text-align: center">Gestión de usuarios</h4>

	@include('backend.gestionUsuario.usuario.formularios.buscar')	
	
   <button type="button" class="btn btn-info"  onclick="window.location=''">
      <i class="fa fa-redo-alt fa-2x" style="padding-right: 5px;"></i>  Cargar página de nuevo
	</button>
	
	<button type="button" class="btn btn-info"  @click="generarReportePDF()">
		<i class="fas fa-file-pdf fa-2x" style="padding-right: 5px;"></i>  Generar reporte PDF
	</button>

	<button type="button" class="btn btn-info"  @click="generarReporteExcel()">
		<i class="fas fa-file-excel fa-2x" style="padding-right: 5px;"></i>  Generar reporte Excel
	</button>

   @include('backend.gestionUsuario.usuario.listados.listadoUsuarios')	
</div>




@section('script')
<script type="text/javascript">
	new Vue({
		mixins: [funcionesGlobalVue],
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

			//Función que permite eliminar un usuario
         eliminarUsuario: function(ruta, id) {
            //Se llama la función que permite eliminar el registro de un usuario
            this.eliminarRegistroVue(ruta, id, 'listarUsuarios');
         },
			
			abrirModal(accion, data = []){
				
				switch(accion){
					case 'registrar':{
						this.tituloModal     = 'Registrar categoría';
						this.usuario_id = '';
						this.name       = '';
						this.username   = data['username'];
						this.email      = data['email'];
						this.tipoAccion = 1;
						break;
					}
					case 'actualizar':{


						window.location = '/usuarios/'+data['id']+'/edit/';

						/*this.tituloModal = 'Actualizar categoría';

						this.categoriasGeoEditar = data;

						this.usuario_id = data['id'];
						this.name       = data['name'];
						this.username   = data['username'];
						this.email      = data['email'];
						this.tipoAccion = 2;*/
						break;
					}
				}

				//Abre el formulario de edición
				$('#crear_editar').modal('show');
			},
			

			accionFormulario: function() {
				if (this.tipoAccion == 1) {
					this.registrarVue('/usuarios');
				}
				else if (this.tipoAccion == 2) {
					this.actualizarRegistroVue('/usuarios/', this.usuario_id);
				}
			},
			generarReportePDF: function(){
				this.generarReportePDFVue('/reportePDFUsuarios/', 'buscarUsuarios');
			},	

			
			generarReporteExcel: function() {
				this.generarReporteExcelVue('/reporteExcelUsuarios/', 'buscarUsuarios');
			},

			limpiarCamposUsuarios: function() {
				this.buscar = '';
				jQuery("#buscarUsuarios").trigger("reset");
				this.listarUsuarios();
			},
		}
	});
</script>
@endsection
@endcontenidoBackend