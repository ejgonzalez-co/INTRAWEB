<template>
   <div>
      <!-- begin #modal-form-report-missing-processes -->
      <div class="modal fade" id="modal-form-report-missing-processes">
         <div class="modal-dialog modal-xl">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Lista de procesos</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">
                     <h3>Listado procesos con necesidades sin evaluar</h3>
                     <table class="table table-bordered table-hover m-b-0">
                        <tr>
                           <th>Nombre del proceso</th>
                           <th>Valor total PAA</th>
                           <th>Estado de la necesidad</th>
                        </tr>
                        <tr v-for="(processes, key) in dataList" :key="key">
                           <td>{{ processes.name_process }}</td>
                           <td>{{ '$ '+currencyFormat(processes.total_value_paa) }}</td>
                           <td>
                              <span :style="{ 'background-color': processes.state_colour, 'color': '#FFFFFF' }" class="p-5">
                                 {{ processes.state_name }}
                              </span>
                           </td>
                        </tr>
                     </table>

                  </div>
                  <div class="modal-footer">
                        <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                        <button v-if="dataList.length > 0" @click="sendNotificationLeaders(dataForm.call_id)" type="button" class="btn btn-primary"><i class="fas fa-paper-plane mr-2"></i>Enviar notificación</button>
                  </div>
               </div>
         </div>
      </div>
      <!-- end #modal-form-report-missing-processes -->
   </div>
</template>
<script lang="ts">
   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';
   
   /**
    * Componente listar las necesidades en elaboracion o devueltas
    *
    * @author Carlos Moises Garcia T. - Jul. 06 - 2021
    * @version 1.0.0
    */
   @Component
   export default class ReportMissingProcesses extends Vue {

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Lista de elementos
       */
      public dataList: Array<any>;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      constructor() {
         super();
         // Inicializa valores del dataform
         this.dataForm = {};
         this.dataList = [];

         this.lang = (this.$parent as any).lang;
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2021
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del formulario
         this.dataForm = {};
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Convierte en numero a formato moneda
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2021
       * @version 1.0.0
       *
       * @param numero dato a convertir en tipo momenda
       */
      public currencyFormat(numero) {
         return (this.$parent as any).currencyFormat(numero);
      }

      /**
       * Cargar el listados de procesos con necesidades sin evaluar
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public getListNeed(id): void {
         // Envia peticion de obtener todos los datos del recurso
         axios.get(`get-unassessed-needs-paa/${id}`)
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
       * @author Carlos Moises Garcia T. - Jul. 06 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {
         // Valida que exista datos
         if (dataElement) {
            let data = utility.clone(dataElement);
            this.dataForm.call_id = data.id;
            this.getListNeed(data.id);
            this.$forceUpdate();
            $(`#modal-form-report-missing-processes`).modal('show');
         }
      }

      /**
       * Envia la notificacion a los procesos pendientes
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public sendNotificationLeaders(id): void {
         // Abre el swal de guardando datos
         this.$swal({
				title: this.lang.get('trans.loading_save'),
				allowOutsideClick: false,
				onBeforeOpen: () => {
               (this.$swal as any).showLoading();
				},
			});
         // Envia peticion de obtener todos los datos del recurso
         axios.get(`notify-unassessed-needs-paa/${id}`)
         .then((res) => {
            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            $('#modal-form-report-missing-processes').modal('hide');
         })
         .catch((err) => {
         });
      }

   }
</script>