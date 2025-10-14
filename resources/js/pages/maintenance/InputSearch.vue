<template>
    <div>

    </div>
</template>
<script lang="ts">
 import { Component, Prop, Vue } from "vue-property-decorator";

   import axios from "axios";

    /**
   * Componente para enviar documentos a revision
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
   @Component
   export default class SendDocuments extends Vue {

    public route: any;

     public type: any;
      /**
       * Constructor de la clase
       *
       * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();

      }

          /**
       * envia los datos del registro de titulo
       *
       * @author José Manuel Marín Londoño. - Agosto. 17 - 2021
       * @version 1.0.0
       */
        public sendDocuments(idDocument): void {
                this.$swal({
                title: '¿Desea cambiar el estado del registro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText:'No'
            }).then((result) => {
            if (result.value) {
                    const params={
                        id: idDocument
                    }

                    this.type = idDocument.split('-');
                    //Abre el swal de cargando
                    this.$swal({
                    title: 'Cargando',
                    html: 'Actualizando estado',                        
                    onBeforeOpen: () => {
                        (this.$swal as any).showLoading();
                    }
                    })

                    if (this.type[1]=='llanta') {
                        this.route = 'get-change-status-history';
                    }else{
                        this.route = 'get-change-status-history-wear';
                    }


                    //enviar peticion al servidor
                    axios.get(`${this.route}/${this.type[0]}`)
                    .then(res=>{
                        const request = res.data.data;
                        console.log(request);

                      // Actualiza elemento modificado en la lista
                        Object.assign((this.$parent as any)._findElementById(request.id, false), request);

                    this.$swal({
                        title: 'Estado actualizado',
                        icon:'success'
                    })
                    })
                    .catch(error=>{
                        this.$swal({
                        title: 'Error al enviar',
                        text:'Error al enviar la solicitud' + error,
                        icon:'error'
                        })
                    })
                }
            })
        }
    }
</script>