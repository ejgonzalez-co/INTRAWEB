<template>
           <div>
               
          </div>   
</template>
<script lang="ts">
 import {  Component, Prop, Watch, Vue} from "vue-property-decorator";
 
   import axios from "axios";

    /** 
   * Componente para enviar peticiones al servidor con swal alert
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
   * @version 1.0.0
   */
   @Component
   export default class MessageWarning extends Vue {      

   
         /**
         * Nombre de la ruta a obtener
         */
        @Prop({ type: String, required: true })
        public nameResource: string;


         public result: any;


      /**
       * Constructor de la clase
       *
       * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.result=0;
         this.getPetition();
         
      }

        /**
       * envia los datos del al servidor y confirma con un sweeralert2
       *
       * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
       * @version 1.0.0
       */
      public getPetition(): void {
                 
              axios.get(this.nameResource)
                    .then(res=>{
                        const request = res.data.data;                        
                        this.result=request;
                        if(this.result >= 86){
                           this.$swal({
                              title: 'Verificar el contrato',
                              text:'Por favor verificar el contrato, el rubro ha llegado al límite, esta en el '+this.result+'%',
                              icon:'warning'
                            })
                         }                        
                    })
                    .catch(error=>{
                       console.log('Error');
                    })

      }
}
</script>
