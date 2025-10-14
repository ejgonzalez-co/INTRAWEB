
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.toastr = require('toastr');

window.Swal = require('sweetalert2');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('v-paginador', require('./components/PaginadorComponent.vue').default);

//Vue.component('departamento', require('./components/Departamento.vue'));

funcionesGlobalVue = {
	created: function () {

		//this.nombrelistado   = '';
		this.almacenaListado = '';
	},

   data:{
		n_a: '',
		
		//Almacena la ruta de la consulta de registros
		ruta: '',
		
		//Almacena el nombre del objecto que guarda el listado de registros
		almacenaListado: '',

		registrosMostrarDefault: 50,
		
		registrosMostrar: 50,

		//Almacena los datos para el paginador
		pagination: {
			'total': 0,
			'current_page': 1,
			'per_page': 0,
			'last_page': 0,
			'from': 0,
			'to': 0
		},
		
		tituloModal : '',
		
		tipoAccion : 0,

		buscar: '',

		nombBtn: '',

		//Variable que almacena un error  
		errors: '',
		  
      //Por defecto organiza el número de items del paginador
		//offset: 3,
   },

    computed: {

		/*isActived: function() {
			return this.pagination.current_page;
		},
		pagesNumber: function() {
			if(!this.pagination.to){
				return [];
			}

			var from = this.pagination.current_page - this.offset; 
			if(from < 1){
				from = 1;
			}

			var to = from + (this.offset * 2); 
			if(to >= this.pagination.last_page){
				to = this.pagination.last_page;
			}

			var pagesArray = [];
			while(from <= to){
				pagesArray.push(from);
				from++;
			}
			return pagesArray;
		},*/
   },

   methods:{
      //Función que permite obtener el listado de registros
		listarRegistrosVue: function(ruta, almacenaListado, buscar, paginador, datos = null) {

			//this.ruta = ruta;
			//this.nombrelistado = nombrelistado;
			//this.almacenaListado = almacenaListado;

			// Swal.fire({
			// 	title: '¡Cargando datos!',
			// 	allowOutsideClick: false,
			// 	onBeforeOpen: () => {
			// 	  Swal.showLoading()
			// 	},
			// });

			let pagina = 1;
			if (this.registrosMostrar != this.registrosMostrarDefault) {
				this.registrosMostrarDefault = this.registrosMostrar;
				pagina = 1;
			}else{
				pagina = this[paginador].current_page;
			}

			//Variable que contiene los campos para buscar
			this.buscar = jQuery("#"+buscar).serialize();

			//url del controlador que permite obtener el listado
			var url = ruta+'?page='+pagina;
			
			var data = this.buscar;

			if (datos != "") {
				data += "&"+datos;
			}
			
			data += "&cantidadRegistros="+this.registrosMostrar;

			axios.post(url, data).then(response => {

					listado = response['data']['datosConsulta']['data'];

					//Valida que la consulta obtenga registros
				if (listado != "") {
							
					//Se asigna los datos para el listado de registros                   
					this[almacenaListado] = listado;

					//Se asigna los datos para la paginación
					this[paginador] = response.data.pagination;

					// Swal.close();
					
			
				}else{
					//Se asigna los datos para el listado de registros
					this[almacenaListado] = listado,

					//Se asigna los datos para la paginación
					this[paginador] = response.data.pagination
					// Swal.close();
					toastr.warning('No se encontraron resultados');
					
				}
			}).catch(error => {
				Swal.close();
				this.errors = 'Corrija para poder crear con éxito'
			});
		},

		//Método que permite listar los departamentos
		listarRegistrosVueGet: function(ruta, almacenaListado, metodo, data=null) {
			//url del controlador que permite obtener el listado de departamentos

			if (metodo == "get") {
				//Código que lista los departamentos
				axios.get(ruta).then(response => {
					//console.log(response['data'][nombrelistado]['data']);
					//Se asigna los datos para el listado de registros                   
					this[almacenaListado] = response['data'];
				});
			}else{
			
				//Código que lista los departamentos
				axios.post(ruta, data).then(response => {
					//console.log(response['data'][nombrelistado]['data']);
					//Se asigna los datos para el listado de registros                   
					this[almacenaListado] = response['data'];

				});
			}
		},

   	//Método que permite registrar
		registrarVue: function(ruta, listadoRecargar, idForm = null) {
			
			let formularioId;
			if (idForm != "") {
				formularioId =  idForm;
				var data = jQuery("#"+idForm).serialize();
			}else{
				var data = jQuery("#crearEditar").serialize();
				formularioId = '#crear_ditar';
			}

         
				
			Swal.fire({
				title: '¡Guardando datos!',
				allowOutsideClick: false,
				onBeforeOpen: () => {
				  Swal.showLoading()
				},
			});

			if (data != "") {
				axios.post(ruta, data).then(response => {
					//Código que permite refrescar el listado de registros
				
					this[listadoRecargar]();    
					this.errors = [];
					
					Swal.close(); 

					//Se oculta el modal
					$("#"+formularioId+"Modal").modal('hide');
					//Mensaje que sale en pantalla
					toastr.clear();
					toastr.success('Registro creado con éxito');
					
				}).catch(error => {
					this.errors = 'Corrija para poder crear con éxito'
				});
			}else{
				this.errors = 'Complete los campos *'
			}
		},		

      //Método para actualizar el registro
		actualizarRegistroVue: function(url, registro, listadoRecargar, idForm, datos = null) {

		
			var url =  url +registro;
			var data;
			let formularioId;
			if (datos == null) {
				
				if (idForm != "") {
					formularioId =  idForm;
					var data = jQuery("#"+idForm).serialize();
				}else{
					var data = jQuery("#crearEditar").serialize();
					formularioId = '#crear_ditar';
				}
			}else{
				var data = datos;
				formularioId = '';
			}

			Swal.fire({
				title: '¡Guardando datos!',
				allowOutsideClick: false,
				onBeforeOpen: () => {
				  Swal.showLoading()
				},
			});

			axios.put(url,
				data
				).then(response => {
				//Código que permite refrescar el listado de registros
				//this.listarRegistrosVue(1);
				this[listadoRecargar]();
				this.errors	  = [];
				Swal.close(); 
				$("#"+formularioId+"Modal").modal('hide');
                
				toastr.success('Registro actualizado con éxito');
			}).catch(error => {
				this.errors = 'Corrija para poder editar con éxito'
			});
		},

        //Función que permite eliminar un registro
		eliminarRegistroVue: function(ruta, registro, listadoRecargar){
			Swal.fire({
				title: '¿Esta seguro de eliminar el registro ?',
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Aceptar',
				cancelButtonText: 'Cancelar',
				reverseButtons: true
			}).then((result) => {
				if (result.value) {
					//url del controlador que permite eliminar una categoría
					var url = ruta+registro;
					//Código que elimina el categoría
					axios.delete(url).then(response =>{
						//Código que permite refrescar el listado de registros
						this[listadoRecargar]();
						//Mensaje que aparece en pantalla
						toastr.clear();
						toastr.success("Registro eliminado correctamente");
					});
				}
			});
		},

		autoCompletarVue: function(ruta, almacenaListado, campo, datos = null) {
			//this.results = [];
			
			axios.get(ruta+"/?"+campo).then(response => {
				this[almacenaListado] = response.data;
			});
			
		},


		//Función que permite generar un reporte pdf
		generarReportePDFVue: function(ruta, buscar, datos= null) {
			
			//Variable que contiene los campos para buscar
			var formulario = jQuery("#"+buscar).serialize();

			//url del controlador que permite obtener el listado
			var url = ruta;

			var campos = formulario.split("&");
			
			var consulta = new Array();

			for (let index = 0; index < campos.length; index++) {
			  
				const element = campos[index].split("=");
        
				if (element[1] != "" && element[1] != null && element[1] != 'null') {
					
					consulta.push(element[0]+"="+ element[1]);
				}
			}
			
			var data = consulta.join("&");

			//Condición que valida si se estan enviando datos para condicionar la consulta
			if (datos != "" && datos != null) {
				data += "&"+datos;
			}

			window.location = ruta+"?"+data;
		},


		//Función que permite generar un reporte pdf
		generarReporteExcelVue: function(ruta, buscar, datos= null) {
			
			//Variable que contiene los campos para buscar
			var formulario = jQuery("#"+buscar).serialize();

			//url del controlador que permite obtener el listado
			var url = ruta;

			var campos = formulario.split("&");
			
			var consulta = new Array();

			for (let index = 0; index < campos.length; index++) {
			  
				const element = campos[index].split("=");
        
				if (element[1] != "" && element[1] != null && element[1] != 'null') {
					
					consulta.push(element[0]+"="+ element[1]);
				}
			}
			
			var data = consulta.join("&");

			//Condición que valida si se estan enviando datos para condicionar la consulta
			if (datos != "" && datos != null) {
				data += "&"+datos;
			}

			window.location = ruta+"?"+data;
		},

		

		limpiarCampos: function() {
			this.buscar = '';
			jQuery("#buscar").trigger("reset");
			//this.listarRegistrosVue(1);
		},
		
		verFiltrosBusqueda: function() {

			var opcion = jQuery("#mostrarFiltros").text();

			if(opcion == "Ver filtros de búsqueda"){
				jQuery("#mostrarFiltros").text(" Ocultar filtros de búsqueda");
			}else{
				jQuery("#mostrarFiltros").text("Ver filtros de búsqueda");
			}
		},
		
		validarFormulario: function(formulario) {
			$(document).ready(function() {
		
				
		
				$(formulario).bootstrapValidator({
					// Only disabled elements are excluded
					// The invisible elements belonging to inactive tabs must be validated
					excluded: [':disabled'],
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
				})
				// Called when a field is invalid
				.on('error.field.bv', function(e, data) {
								
					var $tabPane = data.element.parents('.tab-pane'),
					tabId    = $tabPane.attr('id');
		
					$('a[href="#' + tabId + '"][data-toggle="tab"]')
						.parent()
						.find('i')
						.removeClass('fa-check')
						.addClass('fa-times');
		
					
				})
				// Called when a field is valid
				.on('success.field.bv', function(e, data) {
		
		
					var $tabPane = data.element.parents('.tab-pane'),
					tabId = $tabPane.attr('id'),
					
					$icon = $('a[href="#' + tabId + '"][data-toggle="tab"]')
								.parent()
								.find('i')
								.removeClass('fa-check fa-times');
		
		
					// Check if the submit button is clicked
					if (data.bv.getSubmitButton()) {
						//panelValido = true;
						// Check if all fields in tab are valid
						isValidTab = data.bv.isValidContainer($tabPane);
						$icon.addClass(isValidTab ? 'fa-check' : 'fa-times');	
						//accionFormulario();			
					}else{
						panelValido = false;
					}
				});
		
				//Limpiar validador del formulario
				$(".fa.fa-times").removeClass('fa-times');
				$(".fa.fa-check").removeClass('fa-check');
				$(formulario).bootstrapValidator('disableSubmitButtons', false) // Enable the submit buttons
				.bootstrapValidator('resetForm', true);
				panelValido = false;
			});
		},

		//Método que permite eliminar un adjunto del servidor
		eliminarAdjunto: function(ruta, campo) {

			//Condición que valida que la ruta no este vacia
			if (ruta != "") {
				Swal.fire({
					title: '¿Esta seguro de eliminar el adjunto ?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Aceptar',
					cancelButtonText: 'Cancelar',
					reverseButtons: true
				}).then((result) => {
					if (result.value) {
						//url del controlador que permite eliminar una categoría
						var url = "eliminarAdjunto/"+ruta;
						//Código que elimina el categoría
						axios.get(url).then(response => {

							console.log(response);

							if (response.data == "exito") {
								Swal.fire(
									'¡Éxito!',
									'Se ha eliminado con éxito el archivo.',
									'success'
								);
								$("#"+campo).val('');
								$('#file_'+campo).val('');
								$("#datosAdjunto_"+campo).css("display", "none");	
							}else{
								Swal.fire(
									'¡Error!',
									'Ha ocurrido un error al intentar eliminar el archivo, intentelo más tarde.',
									'error'
								);
							}
						});
					}
				});
			}
		},
		//Función que permite validar si un campo se encuentra vacio
		validarFecha: function(fecha1, fecha2) {

			let fecha_1 = moment(fecha1, "YYYY-MM-DD HH:mm:ss A");
			let fecha_2 = moment(fecha2, "YYYY-MM-DD HH:mm:ss A");

			// console.log("fecha1: "+fecha_1);
			// console.log("fecha2: "+fecha_2);

			if (fecha_1 > fecha_2) {
				return "fecha 1 mayor";
			}
			else if (fecha_2 > fecha_1){
				return "fecha 2 mayor";
			}
			else if (fecha_1 == fecha_2){
				return "fechas iguales";
			}
		},
   }
};

$("body").tooltip({ selector: '[data-toggle=tooltip]' });

/*const app = new Vue({
    el: '#aplicacion'
});*/

/*const app = new Vue({
 	el: '#app',

 	created: function(){
 		//Recargar el listado de usuarios
 		this.listarUsuarios();
 	},
 	data:{
 		//Objeto que va contener el los datos del usuario
 		usuarios: [],
 	},
 	methods: {
 		//Método que permite listar los usuarios
 		listarUsuarios: function(){
 			//url del controlador que permite obtener el listado de usuarios
 			var urlKeeps = 'usuarios';

 			//Código que lista los usuario
 			axios.get(urlKeeps).then(response => {
 				//Se asigna los datos
 				this.usuarios = response.data
 			});
 		},
 		//Método que permite eliminar un usuario
 		eliminarUsuario: function(usuario){
 			//url del controlador que permite eliminar un usuario
 			var url = 'usuarios/'+usuario.id;

 			//Código que elimina el usuario
 			axios.delete(url).then(response =>{
 				//Recargar el listado de usuarios
 				this.listarUsuarios();
 				//Mensaje que aparece en pantalla
 				toastr.success("Registro eliminado correctamente");
 			});
 		}
 	
 });*/