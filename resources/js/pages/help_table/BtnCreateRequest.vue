<template>
   <div style="display: contents;">
      <button @click="callFunction()" type="button" class="btn btn-primary m-b-10">
         <i class="fa fa-plus mr-2"></i>{{ btnTitle }}
      </button>
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Vue } from "vue-property-decorator";

   import axios from "axios";

   /**
   * Componente para agregar activos tic a la mesa de ayuda
   *
   * @author Carlos Moises Garcia T. - Ago. 26 - 2021
   * @version 1.0.0
   */
   @Component
   export default class BtnCreateRequest extends Vue {

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, default: 'Registrar' })
      public btnTitle: string;

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String })
      public name: string;

      /**
       * Ruta del recurso
       */
      @Prop({ type: String })
      public resourcePath: string;

      /**
      * Errores
       */
      public dataErrors: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Ago. 26 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataErrors = {};
         this.lang = (this.$parent as any).lang;
      }

      /**
       * Funcion para validar la accion del boton registrar
       *
       * @author Carlos Moises Garcia T. - Ago. 26 - 2021
       * @version 1.0.0
       */
      public callFunction(): void {
         // Envia peticion de obtener todos los datos del recurso
         axios.get(this.resourcePath)
         .then((res) => {
            // Valida que el tipo de respuesta sea exitoso
            if (res.data.type_message == "success") {
               (this.$parent as any).add();
               $(`#modal-form-${this.name}`).modal('show');
            } else {
               // Abre el swal de la respusta de la peticion
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });
            }
         })
         .catch((err) => {
            let errors = err.response.data.errors;

            // Abre el swal para mostrar los errores
            this.$swal({
               icon: 'error',
               html: this.lang.get('trans.Failed to save data')+errors,
               confirmButtonText: this.lang.get('trans.Accept'),
               allowOutsideClick: false,
            });
         });
      }
   }
</script>