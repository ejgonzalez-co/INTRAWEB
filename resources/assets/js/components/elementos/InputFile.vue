<template>
   <div :class="`form-group row col-md-${col_md}`">
      <label :for="nombre" :class="`col-sm-${label_col_sm}`">{{ titulo }}</label>
      <div :class="`col-sm-${input_col_sm}`">
         <input 
            :type="tipo" 
            :class="`form-control ${clase_input}`"
            :aria-describedby="`${nombre}Help`"
            :placeholder="placeholder"
            :name="nombre"
            :id="nombre"
            :value="value"
         />
         <span class="invalid-feedback" role="alert">
            <strong></strong>
         </span> 
         <small :id="`${nombre}Help`" class="form-text text-muted">{{ ayuda }}</small>
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
         value: {
            type: String,
            default: ''
         },

         clase_input: {
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
		},
      computed: {

      },
      methods: {
      }
   }
</script>