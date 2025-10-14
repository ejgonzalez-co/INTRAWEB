<template>
   <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav mr-auto">
            <li class="nav-item" v-for="menu in itemsMenu">
               <a class="nav-link" href="https://sevendeveloper.info/'+menu.link">@{{ menu.title }}</a>
            </li>
         </ul>
      </div>
   </nav>
</template>

<script>
   export default {
      data (){
         return {
            //Objeto que va contener las opciones del menu
            itemsMenu: [],
         }
      },
      created: function() {
         //Recargar el listado del menu
	 		this.listarMenu();
      },

      methods: {
	 		//Método que permite listar los lineas
	 		listarMenu: function(page) {

	 			//url del controlador que permite obtener el listado de lineas
	 			var urlMenu = '/menus;

	 			//Código que lista las líneas
	 			axios.get(urlMenu).then(response => {

	 				if (response.data.categoriasGeo.data != "") {
						//Se asigna los datos para el listado de registros
						 
						//console.log(response.data.categoriasGeo.data);
	 					this.itemsMenu = response.data.categoriasGeo.data,

          		}else{
						//Se asigna los datos para el listado de registros
						this.itemsMenu = response.data.categoriasGeo.data,

      				toastr.warning('No se encontraron resultados');
      			}
	 			});
	 		},
		},
   }
</script>