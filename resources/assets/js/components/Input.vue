<template>
   <div :class="`form-group row col-md-${col_md}`" :id="`div_${nombre}`">
      <label :for="nombre" :class="`col-sm-${label_col_sm}`">{{ titulo }}</label>
      <div :class="`col-sm-${input_col_sm}`">
         <input 
            :type="tipo" 
            :class="`form-control ${clase_input}`"
            :aria-describedby="`${nombre}Help`"
            :placeholder="placeholder"
            :name="nombre"
            :id="nombre"
            :value="valor"
            :accept="accept"
         />
         <span class="invalid-feedback" role="alert">
            <strong></strong>
         </span> 
         <small :id="`${nombre}Help`" class="form-text text-muted">{{ ayuda }}</small>

          <em v-if="adjunto != ''" :id="`datosAdjunto_${nombre}`">
            <a  :href="'/storage/'+adjunto" v-text="nombre" target="_blank">
            </a>&nbsp;&nbsp;<a v-on:click="eliminarAdjunto(adjunto)" href="#" class="badge badge-danger"><i class="fa fa-trash"></i> Eliminar</a>
         </em>
      </div>
   </div>
</template>
<script>
	export default{

		props: {
         // Variable que almacena el tamaño de la grilla de contenedor del input
         col_md: {
            type: String,
            default: '12'
         },

         // Variable que almacena el título del label del input
         titulo: {
            type: String,
            default: ''
         },

         // Variable que almacena el tamaño de la grilla del label del input
         label_col_sm: {
            type: String,
            default: '12'
         },

         // Variable que almacena el tamaño de la grilla del input
         input_col_sm: {
            type: String,
            default: '12'
         },

         // Variable que almacena el tipo del input
         tipo: {
            type: String,
            default: 'text'
         },

         /**
          * Variable que almacena el nombre del input
          * Esta variable se utiliza en:
          * -El nombre del input
          * -El id del input
          * -El for del label del input
          * -El id del small que tiene el texto de ayuda del input
          * -El aria-describedby del input
          *
          */
         nombre: {
            type: String,
            default: ''
         },

         // Variable que almacena el valor del input
         valor: {
            type: String,
            default: ''
         },

         clase_input: {
            type: String,
            default: ''
         },

         accept: {
            type: String,
            default: ''
         },

         // Variable que almacena el placeholder del input
         placeholder: {
            type: String,
            default: ''
         },

         // Variable que almacena la ayuda del input
         ayuda: {
            type: String,
            default: ''
         },

         adjunto:{
            type: String,
            default: ''
         },
		},
      computed: {

      },
      methods: {
         eliminarAdjunto: function(adjunto) {

            //Condición que valida que la ruta no este vacia
            if (adjunto != "") {
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
                     var url = "/eliminarAdjuntos";

                     axios.post(url, {
                        adjunto: adjunto
                     }).then(response => {
                        Swal.fire(
                           '¡Éxito!',
                           'Se ha eliminado con éxito el archivo.',
                           'success'
                        );

                        $("#datosAdjunto_"+this.nombre).css("display", "none");
                     }).catch(error => {
                        Swal.fire(
                           '¡Error!',
                           'Ha ocurrido un error al intentar eliminar el archivo, intentelo más tarde.',
                           'error'
                        );
                     });


                     //Código que elimina el categoría
                     // axios.get(url).then(response => {

                     //    // console.log(response);

                     //    if (response.data == "exito") {
                     //       Swal.fire(
                     //          '¡Éxito!',
                     //          'Se ha eliminado con éxito el archivo.',
                     //          'success'
                     //       );
                     //       $("#"+campo).val('');
                     //       $('#file_'+campo).val('');
                     //       $("#datosAdjunto_"+campo).css("display", "none");	
                     //    }else{
                     //       Swal.fire(
                     //          '¡Error!',
                     //          'Ha ocurrido un error al intentar eliminar el archivo, intentelo más tarde.',
                     //          'error'
                     //       );
                     //    }
                     // });
                  }
               });
            }
         },
      }
   }
</script>