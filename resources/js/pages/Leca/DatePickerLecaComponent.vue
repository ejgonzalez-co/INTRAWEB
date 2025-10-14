<template>
   <div>
      <v-date-picker
         v-model='value[nameField]'
         :popover="popover"
         :locale="locale"
         :masks="masks"
         :mode="mode"
         :is-inline="isInline"
         :min-date="minDate"
         :max-date="maxDate"
         :input-props="inputProps">
      </v-date-picker>
   </div>
</template>
<style>
.vc-text-base {
   font-size: .8125rem;
}
</style>
<script lang="ts">

   import { Component, Prop, Vue } from "vue-property-decorator";

   /**
    * Componente de campo date picker de v-calendar
    *
    * @author Carlos Moises Garcia T. - Jul. 06 - 2020
    * @version 1.0.0
    */
   @Component
   export default class DatePickerLecaComponent extends Vue {

      /**
       * Configuraciones regionales 
       */
      @Prop({default: 'es'})
      public locale: any;

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
       * Prpiedades del campo
       */
      @Prop({type: Object, default: () => ({ visibility: 'click' })})
      public popover: any;

      /**
       * Máscaras para usar cuando se muestra el calendario 
       */
      @Prop({type: Object, default: () => ({ input: 'YYYY-MM-DD', data: 'YYYY-MM-DD' })})
      public masks: any;


      /**
       * Modo de selección de fechas: "single", "multiple", "range"
       */
      @Prop({type: String, default: "multiple" })
      public mode: string;

      /**
       * Muestra el calendario en línea en lugar del campo
       */
      @Prop({ type: Boolean, default: false })
      public isInline: boolean;


      /**
       * Fecha minima del calendario
       */
      @Prop()
      public minDate: any;

      /**
       * Fecha máxima del calendario
       */
      @Prop()
      public maxDate: any;


      /**
       * Prpiedades del input de componente de v-calendar 
       */
      @Prop({type: Object, default: () => ({ required: false })})
      public inputProps: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();
         if(this.mode == 'multiple' && (this.$parent as Vue)['isUpdate']){
            this.validateUpdate();
         }
         
         // Valida si existe el elemento
         if ( this.value[this.nameField] ) {
            // Valida que no sea un array
            if (Array.isArray(this.value[this.nameField])) {
               let dates = [];
               this.value[this.nameField].forEach((date) => {
                  dates.push(new Date(date+' 00:00:00'));
               });

               this.value[this.nameField] = dates;
            } else {
               this.value[this.nameField] = new Date(this.value[this.nameField]);
            }
            
         }
      }

      public validateUpdate():void{
         let array = this.value[this.nameField].split(",");
         this.value[this.nameField] = array;
      }
   }
</script>
