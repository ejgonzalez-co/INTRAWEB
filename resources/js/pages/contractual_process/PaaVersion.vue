<template>
   <div>
      <!-- begin #modal-view-versions-paa -->
      <div class="modal fade" id="modal-form-versions-paa">
         <div class="modal-dialog modal-xl">
            <div class="modal-content border-0">
               <div class="modal-header bg-blue">
                  <h4 class="modal-title text-white">Versiones de PAA</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
               </div>
               <div class="modal-body hljs-wrapper">
                  <table class="table table-hover m-b-0" id="needs-table">
                     <thead>
                        <tr>
                           <th>Fecha de registro</th>
                           <th>Número de versión</th>
                           <th>Valor total</th>
                           <th>Acciones</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr v-for="(version, key) in dataList" :key="key">
                           <td>{{ version.created_at }}</td>
                           <td>{{ version.version_number }}</td>
                           <td>{{ '$ '+currencyFormat(version.total) }}</td>
                           <td>
                              <a :href="`export-paa-call/${version.pc_paa_calls_id}?v=${version.version_number}`">
                                 <button class="btn btn-white btn-icon btn-md" title="Exportar PAA"><i class="fas fa-file-excel"></i></button>
                              </a>
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
      <!-- end #modal-view-versions-paa -->
   </div>
</template>
<script lang="ts">

   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';
   
   /**
    * Componente para ver las versiones de las necesidades de plan anual de accion
    *
    * @author Carlos Moises Garcia T. - Oct. 01 - 2021
    * @version 1.0.0
    */
   @Component
   export default class PaaVersion extends Vue {

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
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Oct. 01 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataList = [];

         this.lang = (this.$parent as any).lang;

         this.keyRefresh = 0;
      }

      /**
       * Convierte en numero a formato moneda
       *
       * @author Carlos Moises Garcia T. - Ago. 10 - 2021
       * @version 2.0.0
       *
       * @param number dato a convertir en tipo momenda
       */
      public currencyFormat(number) {
         // Valida que no este vacio el dato a convertir
         if ( number ) {
               // Asigna el tipo de moneda que se va a utilizar
               let currencyFormat = Intl.NumberFormat('es-CO', { maximumFractionDigits: 2 });

               // Valida si el lenguaje del sistema es ingles
               if (this.lang.locale == 'en') {
                  // Asigna el formato de moneda de dolar
                  currencyFormat = Intl.NumberFormat('en-US', { maximumFractionDigits: 5 });
               }
               // Retorna el datos formateado con el tipo de moneda
               return currencyFormat.format(number) ;
         } else {
               // Valida si el dato viene nulo
               if (number === null) {
                  return 0;
               } else {
                  return number;
               }
         }
      }

      public getVersionsPaa(id): void {
         // Envia peticion de obtener todos los datos del recurso
         axios.get(`get-paa-versions/${id}`)
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
       * @author Carlos Moises Garcia T. - Oct. 01 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {
         // Valida que exista datos
         if (dataElement) {
            let data = utility.clone(dataElement);
            this.getVersionsPaa(data.id);
            this.$forceUpdate();
            $(`#modal-form-versions-paa`).modal('show');
         }
      }
      
   }
</script>