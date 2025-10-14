<template>      
</template>

<script>
   new Vue({
      el: '#listadoCategoriasGeo',
      
      data (){
         return {
            //Objeto que va contener el los datos del linea
            categoriasGeo: [],

            //Campos del registro
            categoria_id:  '',
            tipo_categoria: '',
            nomb_categoria : '',
            descripcion_cat : '',

            //Objeto que alamcena los datos de la paginación
            pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0
            },

            buscar: '',

            tituloModal : '',

            tituloBoton: '',

            tipoAccion : 0,

            //Variable que almacena un error
            errors: '',
            offset: 3,	   

            tipos_categorias: ['Sitio','Recorrido'],
         }
      },
      created: function() {
         //Recargar el listado de lineas
	 		//this.listarCategoriasGeo(1);
      },
      
      computed: {
 			isActived: function() {
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
 			},
      },
       
      methods: {
	 		//Método que permite listar los lineas
	 		listarCategoriasGeo: function(page) {

	 			//Variable que contiene los campos para buscar
 				this.buscar = jQuery("#buscar").serialize();

	 			//url del controlador que permite obtener el listado de lineas
	 			var urlLinea = '/categoriasgeo?page='+page+'&'+this.buscar;

	 			//Código que lista las líneas
	 			axios.get(urlLinea).then(response => {
	 				if (response.data.categoriasGeo.data != "") {
						 //Se asigna los datos para el listado de registros
						 
						//console.log(response.data.categoriasGeo.data);
	 					this.categoriasGeo = response.data.categoriasGeo.data,

	 					//Se asigna los datos para la paginación
						this.pagination = response.data.pagination
				
          		}else{
						//Se asigna los datos para el listado de registros
						this.categoriasGeo = response.data.categoriasGeo.data,

						//Se asigna los datos para la paginación
						this.pagination = response.data.pagination

      				toastr.warning('No se encontraron resultados');
      			}
	 			});
	 		},

			abrirModal(accion, data = []){
				
				switch(accion){
					case 'registrar':{
					
                  this.tituloModal     = 'Registrar categoría';
                  this.tituloBoton = "Registrar";
                  this.limpiar();
                  
                  this.tipoAccion      = 1;

						break;
					}
					case 'actualizar':{
						this.tituloModal = 'Actualizar categoría';
                  this.tituloBoton = "Actualizar";
						this.categoria_id    = data['cf_id'];
						this.tipo_categoria  = data['tipo_categoria'];
						this.nomb_categoria  = data['nomb_categoria'];
						this.descripcion_cat = data['descripcion_cat'];
						this.tipoAccion = 2;
						break;
					}
            }
            
				//Abre el formulario de edición
				$('#crear_editar').modal('show');
         },
         
         accionFormulario(){

            if (this.tipoAccion == 1) {
               this.registrarPrueba();
            }
            else if (this.tipoAccion == 2) {
               
            }
         },
			

	 		//Metodo que permite visualizar el formulario de editar
	 		editarCategoria: function(linea) {
	 			//Variable que se envian al formulario
				//this.fillLinea.cf_id         = linea.cf_id;
				//this.fillLinea.nomb_categoria = linea.nomb_categoria;

				this.errors = [];

				//Abre el formulario de edición
				$('#edit').modal('show');
			},
			//Método para actualizar el registro
			actualizarCategoria: function(id) {
				var url = '/categoriasgeo/' +id;

				axios.put(url,{
						'tipo_categoria': this.tipo_categoria,
						'nomb_categoria': this.nomb_categoria,
						'descripcion_cat': this.descripcion_cat,
               }).then(response => {
					//Código que permite refrescar el listado de registros
					this.listarCategoriasGeo();
					this.errors	  = [];
					$('#crear_editar').modal('hide');
					toastr.success('Categoría actualizada con éxito');
				}).catch(error => {
					this.errors = 'Corrija para poder editar con éxito'
				});
			},

			//Método que permite registrar una línea
			registrarPrueba: function() {
            if (this.nomb_categoria != "") {
               alert("hola");
            }else{
               alert("hola");
            }
				
			},

	 		//Método que permite eliminar una categoría
	 		eliminarCategoria: function(categoria){
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
						var url = '/categoriasgeo/'+categoria.cf_id;
						//Código que elimina el categoría
						axios.delete(url).then(response =>{
							//Código que permite refrescar el listado de registros
							this.listarCategoriasGeo();
							//Mensaje que aparece en pantalla
							toastr.clear();
							toastr.success("Registro eliminado correctamente");
						});
					}
	 			});
	 		},
	 		limpiarCampos: function() {
	 			this.buscar = '';
	 			jQuery("#buscar").trigger("reset");
	 			this.listarCategoriasGeo(1);
          },
          
          limpiar: function() {
            this.tipo_categoria  = '';
            this.nomb_categoria  = '';
            this.descripcion_cat = '';
 			},
         changePage: function(page) {
            this.pagination.current_page = page;
            this.listarCategoriasGeo(page);
         }
		},
   });
</script>