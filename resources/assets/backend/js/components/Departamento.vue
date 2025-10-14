<template>
   <table class="table" id="listadoUsuarios">
      <thead>
         <tr>
            <th>Fecha de registro</th>
            <th>Nombre</th>
            <th>Operaciones</th>
         </tr>
      </thead>
      <tbody>
         <tr v-for="departamento in departamentos">
            <td>{{ departamento.created_at }}</td>
            <td>{{ departamento.nombreDpto }}</td>
            <td>
               <a href="#" class="btn btn-danger btn-sm" v-on:click.prevent="eliminarDepartamento(departamento)">Eliminar</a>
            </td>
         </tr>
      </tbody>
   </table>
   <nav>
      <ul class="">
         <li>
              
         </li>
      </ul>
   </nav>  
</template>
<script>
   export default{
      data () {
         return{
            departamentos: [],
            pagination: {
               'total': 0,
               'current_page': 0,
               'per_page': 0,
               'last_page': 0,
               'from': 0,
               'to': 0
            },
            newKeep: '',
            fillKeep: {'created_at': '', 'nombreDpto': ''},
            errors: '',
            offset: 3,
         }
      },
    
      created: function(){
         //Recargar el listado de departamentos
         this.getDepartamentos();
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
         }
      },
      methods: {
         getDepartamentos: function() {
            var urlDepartamentos = 'departamentos';
            //var urlDepartamentos = 'departamentos?page='+page;
            //Código que lista los departamentos
            axios.get(urlDepartamentos).then(response => {
               //Se asigna los datos
               this.departamentos = response.data.departamentos.data,
               this.pagination = response.data.pagination
            });
         },
         //Método que permite eliminar un departamento
         eliminarDepartamento: function(departamento){
            //url del controlador que permite eliminar un departamento
            var url = 'departamentos/'+departamento.id;

            //Código que elimina el departamento
            axios.delete(url).then(response =>{
               //Recargar el listado de departamentos
               this.getDepartamentos();
               //Mensaje que aparece en pantalla
               toastr.success("Registro eliminado correctamente");
            });
         },
         changePage: function(page) {
            this.pagination.current_page = page;
            this.getDepartamentos(page);
         }
      }
   }
</script>