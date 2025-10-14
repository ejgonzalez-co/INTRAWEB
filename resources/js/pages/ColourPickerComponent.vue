<template>
   <div>

      <verte :key="x" display="widget" model="hex" v-model='value[nameField]'></verte>

   </div>
</template>

<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import * as bootstrap from 'bootstrap';

   /**
   * Componente para seleccionar colores
   *
   * @author Carlos Moises Garcia T. - Oct. 29 - 2020
   * @version 1.0.0
   */
   @Component
   export default class ColourPickerComponent extends Vue {

      /**
       * Nombre del campo
       */
      @Prop({type: String, required: true })
      public nameField: string;

      /**
       * Valor del campo
       */
      @Prop({type: Object})
      public value: any;

   /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
     public keyRefresh: number;
      /**
       * Valor del campo
       */
      @Prop({type: String, default: "widget" })
      public display: string;

      /**
       * Valida si los valores del formulario
       * son para actualizar o crear
       */
      public x: boolean;


      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();
         this.x = false;

      }

      mounted() {
         setTimeout(() => {
            this.x = true;
         }, 200);
      }

   }

</script>