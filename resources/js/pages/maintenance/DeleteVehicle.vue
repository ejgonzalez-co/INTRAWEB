<template>
    <div>
        <button @click="deleteVehicule()" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>

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
   export default class DeleteVehicle extends Vue {

        /** Se guarda el numero */
         @Prop({type: Number})
       public id: number;

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
       * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
       * @version 1.0.0
       */
      public deleteVehicule(idVehicle): void {
               
                
                idVehicle=this.id;
                 
                this.$swal({
                title: 'Deseas eliminar este registro.',
                text: "¿Estás seguro de aceptar ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText:'No'
            }).then((result) => {
            if (result.value) {
                    const params={
                        id: idVehicle
                    }
                    //enviar peticion al servidor
                    axios.delete(`vehicle-fuels/${idVehicle}`)
                    .then(res=>{
                    const request = res.data.data;                    
                    
                    if(res.data.data[1]){
                        // Actualiza elemento modificado en la lista
                       Object.assign((this.$parent as any)._findElementById(res.data.data[1].id, false), res.data.data[1]);
                    }
                      this.$el.parentNode.parentNode.parentNode.parentNode.removeChild(this.$el.parentNode.parentNode.parentNode);
                      
                    this.$swal({
                        title: 'Registro eliminado',
                        text:'Se elimino el registro',
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
