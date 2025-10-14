<template>
   <div>
      <!-- begin #modal-view-novelties-paa -->
      <div class="modal fade" id="modal-form-novelties-paa">
         <div class="modal-dialog modal-xl">
            <div class="modal-content border-0">
               <div class="modal-header bg-blue">
                  <h4 class="modal-title text-white">Novedades de PAA</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
               </div>
               <div class="modal-body hljs-wrapper">
                  <table class="table table-hover m-b-0" id="needs-table">
                     <thead>
                        <tr>
                           <th>Fecha de registro</th>
                           <th>Usuario</th>
                           <th>Dependencia</th>
                           <th>Tipo de novedad</th>
                           <th>Observación</th>
                           <th>Adjunto</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(noveltie, key) in dataList" :key="key">
                           <td>{{ noveltie.created_at }}</td>
                           <td>{{ noveltie.user_name }}</td>
                           <td>{{ noveltie.users? noveltie.users.dependencies.nombre: '' }}</td>
                           <td>{{ noveltie.kind_novelty }}</td>
                           <td>{{ noveltie.observation }}</td>
                           <td>
                              <a v-if="noveltie.attached" :href="'/storage/'+ noveltie.attached" target="_blank" rel="noopener noreferrer">Ver adjunto</a>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <div class="modal-footer">
                  <button  class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
               </div>
            </div>
         </div>
      </div>
      <!-- end #modal-view-novelties-paa -->
   </div>
</template>
<script lang="ts">

   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';
   
   /**
    * Componente para ver las novedades de las necesidades de plan anual de accion
    *
    * @author Carlos Moises Garcia T. - May. 20 - 2021
    * @version 1.0.0
    */
   @Component
   export default class NoveltiesPaa extends Vue {

      /**
       * Lista de elementos
       */
      public dataList: Array<any>;

      /**
       * Key autoincrementable y unico para
       * ayudar a refrescar un componente
       */
      public keyRefresh: number;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - May. 20 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataList = [];
         this.keyRefresh = 0;
      }

      public getNoveltiesPaa(id): void {
         // Envia peticion de obtener todos los datos del recurso
         axios.get(`get-novelties-paa/${id}`)
         .then((res) => {
            // Llena la lista de datos
            this.dataList = res.data.data;
         })
         .catch((err) => {
         });
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - May. 20 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {
         // console.log(dataElement);
         // Valida que exista datos
         if (dataElement) {
            let data = utility.clone(dataElement);
            this.getNoveltiesPaa(data.id);
            this.$forceUpdate();
            $(`#modal-form-novelties-paa`).modal('show');
         }
      }
      
   }
</script>