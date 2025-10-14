<template>
   <!-- Comienza el div del consolidado del PQR -->
   <div v-if="mostrarComponente" style="display: grid;">
      <div class="btn_consolidado">
         <button type="button" class="btn btn-outline-success" @click="verTableroConsolidado();" style="font-size: 15px;">
            <i class="fa fa-table"></i>&nbsp;&nbsp;&nbsp;
            <span id="text_btn_consolidado">Ver consolidado de PQRS</span>
         </button>
      </div>
      <div class="table-responsive" id="contenedorTableConsolidado" style="display: none; position: relative;">
         <div class="spinner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: none;">
            <span class="spinner-inner"></span>
         </div>
         <table id="consolidado" class="table text-center" border="1">
            <thead>
               <tr>
                  <td colspan="21">
                     <h4>Consolidado de PQRS<br />
                     <span class="total" @click="evento_tablero(null, null)">{{ consolidado.total }}</span></h4>
                  </td>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td colspan="2" class="abiertos_bg" @click="evento_tablero(null, 'Abierto')">PQRS Abiertos</td>
                  <td colspan="5" class="a_tiempo_bg" @click="evento_tablero('A tiempo', null)">A tiempo</td>
                  <td colspan="5" class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', null)">Próximos a vencerse</td>
                  <td colspan="5" class="vencidos_bg" @click="evento_tablero('Vencido', null)">Vencidos</td>
                  <td colspan="3" class="finalizados_bg" @click="evento_tablero(null, 'Finalizado')">Finalizados</td>
                  <td class="cancelados_bg" @click="evento_tablero(null, 'Cancelado')">Cancelados</td>
               </tr>
               <tr>
                  <td id="abiertos" colspan="2" class="abiertos_txt" @click="evento_tablero(null, 'Abierto')">{{ consolidado.abiertos }}</td>
                  <td id="a_tiempo" colspan="5" class="a_tiempo_txt" @click="evento_tablero('A tiempo', null)">{{ consolidado.a_tiempo }}</td>
                  <td id="proximos_a_vencerse" colspan="5" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', null)">{{ consolidado.proximo_vencer }}</td>
                  <td id="vencidos" colspan="5" class="vencidos_txt" @click="evento_tablero('Vencido', 'solo vencido')">{{ consolidado.vencidos }}</td>
                  <td id="finalizados" colspan="3" class="finalizados_txt" @click="evento_tablero(null, 'Finalizado')">{{ consolidado.finalizados }}</td>
                  <td rowspan="3" class="cancelados_txt" @click="evento_tablero(null, 'Cancelado')">{{ consolidado.cancelados }}</td>
               </tr>
               <tr>
                  <td class="abiertos_bg" @click="evento_tablero('fisicos', 'Abierto')">Físicos</td>
                  <td class="abiertos_bg" @click="evento_tablero('Web', 'Abierto')">Web</td>

                  <td class="a_tiempo_bg" @click="evento_tablero('A tiempo', 'Asignado')">Asignados</td>
                  <td class="a_tiempo_bg" @click="evento_tablero('A tiempo', 'En trámite')">En trámite</td>
                  <td class="a_tiempo_bg" @click="evento_tablero('A tiempo', 'Esperando respuesta del ciudadano')">Pendiente de respuesta</td>
                  <td class="a_tiempo_bg" @click="evento_tablero('A tiempo', 'Devuelto')">Devueltos</td>
                  <td class="a_tiempo_bg" @click="evento_tablero('A tiempo', 'Respuesta parcial')">Respuesta parcial</td>

                  <td class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', 'Asignado')">Asignados</td>
                  <td class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', 'En trámite')">En trámite</td>
                  <td class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', 'Esperando respuesta del ciudadano')">Pendiente de respuesta</td>
                  <td class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', 'Devuelto')">Devueltos</td>
                  <td class="proximos_a_vencerse_bg" @click="evento_tablero('Próximo a vencer', 'Respuesta parcial')">Respuesta parcial</td>

                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'Asignado')">Asignados</td>
                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'En trámite')">En trámite</td>
                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'Esperando respuesta del ciudadano')">Pendiente de respuesta</td>
                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'Devuelto')">Devueltos</td>
                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'Respuesta parcial')">Respuesta parcial</td>

                  <td class="finalizados_bg" @click="evento_tablero('A tiempo', 'Finalizado')">A tiempo</td>
                  <td class="proximos_a_vencerse_bg" @click="evento_tablero(null, 'Finalizado vencido justificado')">Vencidos justificados</td>
                  <td class="vencidos_bg" @click="evento_tablero('Vencido', 'Finalizado')">Vencidos</td>
               </tr>

               <tr>
                  <td id="abiertos_fisicos" class="abiertos_txt" @click="evento_tablero('fisicos', 'Abierto')">{{ consolidado.abiertos_fisicos }}</td>
                  <td id="abiertos_web" class="abiertos_txt" @click="evento_tablero('Web', 'Abierto')">{{ consolidado.abiertos_web }}</td>

                  <td id="a_tiempo_asignados" class="a_tiempo_txt" @click="evento_tablero('A tiempo', 'Asignado')">{{ consolidado.a_tiempo_asignado }}</td>
                  <td id="a_tiempo_en_tramite" class="a_tiempo_txt" @click="evento_tablero('A tiempo', 'En trámite')">{{ consolidado.a_tiempo_en_tramite }}</td>
                  <td id="a_tiempo_pendiente_respuesta" class="a_tiempo_txt" @click="evento_tablero('A tiempo', 'Esperando respuesta del ciudadano')">{{ consolidado.a_tiempo_esperando_respuesta_ciudadano }}</td>
                  <td id="a_tiempo_devueltos" class="a_tiempo_txt" @click="evento_tablero('A tiempo', 'Devuelto')">{{ consolidado.a_tiempo_devuelto }}</td>
                  <td id="a_tiempo_respuesta_parcial" class="a_tiempo_txt" @click="evento_tablero('A tiempo', 'Respuesta parcial')">{{ consolidado.a_tiempo_respuesta_parcial }}</td>

                  <td id="proximos_vencerse_asignados" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', 'Asignado')">{{ consolidado.proximo_vencer_asignado }}</td>
                  <td id="proximos_vencerse_en_tramite" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', 'En trámite')">{{ consolidado.proximo_vencer_en_tramite }}</td>
                  <td id="proximos_vencerse_pendiente_respuesta" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', 'Esperando respuesta del ciudadano')">{{ consolidado.proximo_vencer_esperando_respuesta_ciudadano }}</td>
                  <td id="proximos_vencerse_devueltos" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', 'Devuelto')">{{ consolidado.proximo_vencer_devuelto }}</td>
                  <td id="proximos_vencerse_respuesta_parcial" class="proximos_a_vencerse_txt" @click="evento_tablero('Próximo a vencer', 'Respuesta parcial')">{{ consolidado.proximo_vencer_respuesta_parcial }}</td>

                  <td id="vencidos_asignados" class="vencidos_txt" @click="evento_tablero('Vencido', 'Asignado')">{{ consolidado.vencidos_asignado }}</td>
                  <td id="vencidos_en_tramite" class="vencidos_txt" @click="evento_tablero('Vencido', 'En trámite')">{{ consolidado.vencidos_en_tramite }}</td>
                  <td id="vencidos_pendiente_respuesta" class="vencidos_txt" @click="evento_tablero('Vencido', 'Esperando respuesta del ciudadano')">{{ consolidado.vencidos_esperando_respuesta_ciudadano }}</td>
                  <td id="vencidos_devueltos" class="vencidos_txt" @click="evento_tablero('Vencido', 'Devuelto')">{{ consolidado.vencidos_devuelto }}</td>
                  <td id="vencidos_respuesta_parcial" class="vencidos_txt" @click="evento_tablero('Vencido', 'Respuesta parcial')">{{ consolidado.vencidos_respuesta_parcial }}</td>

                  <td id="finalizados_a_tiempo" class="finalizados_txt" @click="evento_tablero('A tiempo', 'Finalizado')">{{ consolidado.a_tiempo_finalizado }}</td>
                  <td id="finalizados_vencidos_justificados" class="proximos_a_vencerse_txt" @click="evento_tablero(null, 'Finalizado vencido justificado')">{{ consolidado.finalizados_vencidos_justificados }}</td>
                  <td id="finalizados_vencidos" class="vencidos_txt" @click="evento_tablero('Vencido', 'Finalizado')">{{ consolidado.vencidos_finalizado }}</td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
</template>

<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import * as bootstrap from 'bootstrap';
//    import { route } from 'vue-router'; // Suponiendo que estás usando Vue Router
//    import { Router } from 'vue-router';
   import { jwtDecode } from 'jwt-decode';
   import { sign } from 'jsonwebtoken';
   import utility from '../../utility';
   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
   * Componente para responder la encuesta de satisaccion de solicitudes tic
   *
   * @author German Gonzalez V. Abril 14 - 2023
   * @version 1.0.0
   */
   @Component
   export default class PQRComponent extends Vue {

      @Prop({ type: Boolean, default: true})
      public mostrarComponente: boolean;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

          /**
         * Datos de excluidos en la exportacion de datos
         */
         @Prop({ type: Array, default: () => []})
        public excludeExportData: any;

          /**
         * Datos de relaciones a exportar
         */
         @Prop({ type: Array, default: () => []})
        public relatedDataExport: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      public dataList: Array<any>;

         public searchFields: any;

      /**
      * Valida el estado del modal del formulario,
      * si habilita o cierra, se hace para recargar los elementos internos del formulario
      * cada vez que se habilita (Evita el keyrefresh para no sobrecargar la interfaz)
      */
      public openForm: boolean;

      public consolidado: any;

      /**
      * Detecta el estado de carga de los archivos, si se ha terminado o no de cargar por completo los adjuntos del componente InputCrudFile
      */
      public uploadingFileInputCrudFile: boolean;
      /**
       * Constructor de la clase
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       */
      constructor() {
         super();
         this.openForm = false;
         this.dataList = [];
         this.dataErrors = {};

         this.searchFields = {"_obj_llave_valor_": {}};

         this.lang = (this.$parent as any).lang;
         // Variables para mostrar los valores deL conteo de PQRS
         this.consolidado = {
            'total': 0,

            'a_tiempo': 0,
            'proximo_vencer': 0,
            'vencidos': 0,
            'abiertos': 0,
            'finalizados': 0,
            'cancelados': 0,
            'abiertos_fisicos': 0,
            'abiertos_web': 0,

            'a_tiempo_asignado': 0,
            'a_tiempo_en_tramite': 0,
            'a_tiempo_esperando_respuesta_ciudadano': 0,
            'a_tiempo_devuelto': 0,
            'a_tiempo_respuesta_parcial': 0,

            'proximo_vencer_asignado': 0,
            'proximo_vencer_en_tramite': 0,
            'proximo_vencer_esperando_respuesta_ciudadano': 0,
            'proximo_vencer_devuelto': 0,
            'proximo_vencer_respuesta_parcial': 0,

            'vencidos_asignado': 0,
            'vencidos_en_tramite': 0,
            'vencidos_esperando_respuesta_ciudadano': 0,
            'vencidos_devuelto': 0,
            'vencidos_respuesta_parcial': 0,

            'a_tiempo_finalizado': 0,
            'finalizados_vencidos_justificados': 0,
            'vencidos_finalizado': 0
         };
         this.uploadingFileInputCrudFile = false;

      }

      /**
       * Se ejecuta cuando el componente ha sido creado
       */
      created() {
         // Obtiene la instancia del crudComponent
         let crudComponent = (this.$parent as Vue);
         // Se inicializa el valor por defecto del filtro del rol = rol_superior del usuario (Administrador, Consultor, Operador)
         if((!crudComponent["searchFields"]["pqrs_propios"]) && this.mostrarComponente) {
            this.$set(crudComponent["searchFields"], "pqrs_propios", "rol_superior");
            this.$set(crudComponent["searchFields"], "tipos_pqrs", "pendientes_ejecucion");
         }
         // Se inicializa el valor por defecto del filtro de vigencia a vacio = Todas
         if((!crudComponent["searchFields"]["vigencia"])) {
            this.$set(crudComponent["searchFields"], "vigencia", "");
         }

         // recuperamos el querystring (parámetros enviados por URL)
         const querystring = window.location.search
         // usando el querystring, creamos un objeto del tipo URLSearchParams
         const params = new URLSearchParams(querystring)
         // Valida si el parámetro qd (query dashboard), petición de los widgets, tiene algo quiere decir que viene a petición del dashboard
         if(params.get('qd')) {
            // Se obtiene el valor del parámtro qd (query dashboard) y se decodifíca
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qd'))));
            this.$set(crudComponent["searchFields"], "linea_tiempo", consulta_dashboard);
            this.$set(crudComponent["searchFields"], "estado", null);
            crudComponent["pageEventActualizado"](1);
         } else if(params.get('qsb')) {
            // Se obtiene el valor del parámtro qsb (query sidebar), petición de la barra lateral y se decodifíca
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qsb'))));
            this.$set(crudComponent["searchFields"], "linea_tiempo", consulta_dashboard);
            this.$set(crudComponent["searchFields"], "estado", null);
            crudComponent["pageEventActualizado"](1);
         } else if(params.get('qder')) {
            // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qder'))));
            this.$set(crudComponent["searchFields"], "id", consulta_dashboard);
            crudComponent["pageEventActualizado"](1);
            // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
            axios.get('get-p-q-r-s-show-dashboard/'+consulta_dashboard)
               .then((res) => {
                  let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                  const dataDecrypted = Object.assign({}, dataPayload);

                  // Si el estado del documento es público, se debe de ver solo en vista de detalles
                  if(!dataDecrypted["data"]["permission_edit"]) {
                     // Envia elemento a mostrar a la función show (Ver Detalless)
                     crudComponent["show"](dataDecrypted["data"]);
                     $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                  } else {
                     // Envia elemento a mostrar a la función show (Ver Detalles)
                     crudComponent["show"](dataDecrypted["data"]);
                     // Envia elemento a mostrar a la función edit del crudComponent
                     crudComponent["edit"](dataDecrypted["data"]);
                     $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
                  }
               })
               .catch((err) => {
                  console.log("Error obteniendo información del registro desde el listado el dashboard")
               });
            this.asignarLeidoPQR(crudComponent["searchFields"]["id"]);
         }

         // Se documenta ya que queda a petición la carga de información del tablero
         // Carga la lista de elementos
         // this.mostrarComponente ? this._getDataList() : null;

      }

      // Función para eliminar un parámetro y su valor de una URL
      public eliminarParametroYActualizar(parametros: Array<string>) {
            // Obtener la URL actual
            var urlActual = window.location.href;

            // Utilizar el constructor URL para parsear la URL
            var urlObj = new URL(urlActual);

           // Eliminar cada parámetro y su valor
            parametros.forEach(function(parametro) {
                urlObj.searchParams.delete(parametro);
            });

            // Obtener la nueva URL modificada
            var nuevaURL = urlObj.toString();

            // Actualizar la URL sin recargar la página
            window.history.replaceState(null, '', nuevaURL);
        }

      /**
       * Limpia los datos del fomulario
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       */
      public clearDataFormPQRCiudadano(crudComponent): void {
         // limpia los campos del formulario de creación de un ciudadano desde el módulo de PQRS
         crudComponent["dataForm"]["type_person"] = "";
         crudComponent["dataForm"]["type_document"] = "";
         crudComponent["dataForm"]["document_number"] = "";
         crudComponent["dataForm"]["first_name"] = "";
         crudComponent["dataForm"]["second_name"] = "";
         crudComponent["dataForm"]["first_surname"] = "";
         crudComponent["dataForm"]["second_surname"] = "";
         crudComponent["dataForm"]["email"] = "";
         crudComponent["dataForm"]["states_id"] = "";
         crudComponent["dataForm"]["city_id"] = "";
         crudComponent["dataForm"]["address"] = "";
         crudComponent["dataForm"]["phone"] = "";
      }


      /**
       * Asignar destacado al PQR
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       *
       * @param pqr_id - ID del PQR
       * @param event - Opción seleccionada por el usuario (destacado = 1, no destacado = 0)
       */
      public asignarDestacado(pqr_id: number, event: number): void {
         // Envia peticion para destacar el PQR
         axios.post('destacado-pqr/'+pqr_id+'/'+event)
            .then((res) => {
                // Datos de notificacion (Por defecto guardar)
                const toastOptions = {
                    closeButton: true,
                    closeMethod: 'fadeOut',
                    timeOut: 3000,
                    tapToDismiss: false
                };
                // Mensaje para indicar al usuario si destacó o no correctamente el PQR
                let mensaje = event ? "PQR destacado con éxito" : "PQR no destacado con éxito";
                // Visualiza toast positivo
                toastr.success(mensaje, "OK", toastOptions);
            })
            .catch((err) => {
               (this.$parent as any)._pushNotification('Error al asignar el destacado al PQR', false, 'Error');
            });
      }

      /**
       * Asignar leido al PQR
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       *
       * @param pqr_id ID del PQR
       */
      public asignarLeidoPQR(pqr_id: number): void {
         // Envia peticion de leido al PQR
         axios.post('leido-pqr/'+pqr_id)
            .then((res) => {
               // Datos de notificacion (Por defecto guardar)
               const toastOptions = {
                  closeButton: true,
                  closeMethod: 'fadeOut',
                  timeOut: 3000,
                  tapToDismiss: false
               };

               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
               const dataDecrypted = Object.assign({}, dataPayload);

               const element = (this.$parent as any)._findElementById(pqr_id, false);
               if (element) {
                  Object.assign(element, dataDecrypted["data"]);
               }
               //  Visualiza toast positivo
            })
            .catch((err) => {
               (this.$parent as any)._pushNotification('Error al asignar el destacado al PQR', false, 'Error');
            });
      }


      /**
       * Asignar leido a la anotación del PQR
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       *
       * @param pqr_id ID del PQR
       */
       public asignarLeidoAnotacionPQR(pqr_anotacion_id: number): void {
         // Envia peticion de leido de la anotación de un PQR
         axios.post('leido-anotacion-pqr/'+pqr_anotacion_id)
            .then((res) => {
               // Datos de notificacion (Por defecto guardar)
               const toastOptions = {
                  closeButton: true,
                  closeMethod: 'fadeOut',
                  timeOut: 3000,
                  tapToDismiss: false
               };

               // Visualiza toast positivo
               toastr.success(res.data.message, "OK", toastOptions);
            })
            .catch((err) => {
               (this.$parent as any)._pushNotification('Error al leer la anotación del PQR', false, 'Error');
            });
      }

      /**
       * Obtiene la lista de datos
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       */
      private _getDataList(): void {
         // Envia peticion de obtener todos los datos del consolidado de PQRS
         axios.get('get-tablero-consolidado?vigencia='+(this.$parent as Vue)["searchFields"]["vigencia"]+'&pqrs_propios='+(this.$parent as Vue)["searchFields"]["pqrs_propios"]+'&tipos_pqrs='+(this.$parent as Vue)["searchFields"]["tipos_pqrs"])
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({
               data: {
                  total: 0,
                  a_tiempo: 0,
                  proximo_vencer: 0,
                  vencidos: 0,
                  abiertos: 0,
                  finalizados: 0,
                  cancelados: 0,
                  abiertos_fisicos: 0,
                  abiertos_web: 0,
                  a_tiempo_asignado: 0,
                  a_tiempo_en_tramite: 0,
                  a_tiempo_esperando_respuesta_ciudadano: 0,
                  a_tiempo_devuelto: 0,
                  a_tiempo_respuesta_parcial: 0,
                  proximo_vencer_asignado: 0,
                  proximo_vencer_en_tramite: 0,
                  proximo_vencer_esperando_respuesta_ciudadano: 0,
                  proximo_vencer_devuelto: 0,
                  proximo_vencer_respuesta_parcial: 0,
                  vencidos_asignado: 0,
                  vencidos_en_tramite: 0,
                  vencidos_esperando_respuesta_ciudadano: 0,
                  vencidos_devuelto: 0,
                  vencidos_respuesta_parcial: 0,
                  a_tiempo_finalizado: 0,
                  finalizados_vencidos_justificados: 0,
                  vencidos_finalizado: 0,
               },
            }, dataPayload);

            this.consolidado = {
               'total': dataDecrypted?.data.total ?? 0,

               'a_tiempo': dataDecrypted?.data.a_tiempo ?? 0,
               'proximo_vencer': dataDecrypted?.data.proximo_vencer ?? 0,
               'vencidos': dataDecrypted?.data.vencidos ?? 0,
               'abiertos': dataDecrypted?.data.abiertos ?? 0,
               'finalizados': dataDecrypted?.data.finalizados ?? 0,
               'cancelados': dataDecrypted?.data.cancelados ?? 0,
               'abiertos_fisicos': dataDecrypted?.data.abiertos_fisicos ?? 0,
               'abiertos_web': dataDecrypted?.data.abiertos_web ?? 0,

               'a_tiempo_asignado': dataDecrypted?.data.a_tiempo_asignado ?? 0,
               'a_tiempo_en_tramite': dataDecrypted?.data.a_tiempo_en_tramite ?? 0,
               'a_tiempo_esperando_respuesta_ciudadano': dataDecrypted?.data.a_tiempo_esperando_respuesta_ciudadano ?? 0,
               'a_tiempo_devuelto': dataDecrypted?.data.a_tiempo_devuelto ?? 0,
               'a_tiempo_respuesta_parcial': dataDecrypted?.data.a_tiempo_respuesta_parcial ?? 0,

               'proximo_vencer_asignado': dataDecrypted?.data.proximo_vencer_asignado ?? 0,
               'proximo_vencer_en_tramite': dataDecrypted?.data.proximo_vencer_en_tramite ?? 0,
               'proximo_vencer_esperando_respuesta_ciudadano': dataDecrypted?.data.proximo_vencer_esperando_respuesta_ciudadano ?? 0,
               'proximo_vencer_devuelto': dataDecrypted?.data.proximo_vencer_devuelto ?? 0,
               'proximo_vencer_respuesta_parcial': dataDecrypted?.data.proximo_vencer_respuesta_parcial ?? 0,

               'vencidos_asignado': dataDecrypted?.data.vencidos_asignado ?? 0,
               'vencidos_en_tramite': dataDecrypted?.data.vencidos_en_tramite ?? 0,
               'vencidos_esperando_respuesta_ciudadano': dataDecrypted?.data.vencidos_esperando_respuesta_ciudadano ?? 0,
               'vencidos_devuelto': dataDecrypted?.data.vencidos_devuelto ?? 0,
               'vencidos_respuesta_parcial': dataDecrypted?.data.vencidos_respuesta_parcial ?? 0,

               'a_tiempo_finalizado': dataDecrypted?.data.a_tiempo_finalizado ?? 0,
               'finalizados_vencidos_justificados': dataDecrypted?.data.finalizados_vencidos_justificados ?? 0,
               'vencidos_finalizado': dataDecrypted?.data.vencidos_finalizado ?? 0
            };

            //Código que muestra el tablero
            jQuery("#consolidado").css("opacity", 1);
            jQuery(".spinner").hide();
         })
         .catch((err) => {
            (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
         });
      }

      /**
       * Obtiene los eventos del consolidado como los clics
       * @param linea_tiempo (A tiempo, Próximo a vencer y Vencidos)
       * @param estado Estados que un PQR puede tener
       */
      public evento_tablero(linea_tiempo: string, estado: string) {
         // Obtiene la instancia del crudComponent
         let crudComponent = (this.$parent as Vue);
         this.$set(crudComponent["searchFields"], "pqrs_propios", crudComponent["searchFields"]["pqrs_propios"]);
         // Eliminar los parámetros contenidos en la URL provenientes del dashboard
         this.eliminarParametroYActualizar(['qd','qsb','qder']);
         // Si la línea de tiempo es físico, obtiene solo los PQRS del canál Personal
         if(linea_tiempo == "fisicos"){
            this.$set(crudComponent["searchFields"], "canal", "Personal");
            this.$set(crudComponent["searchFields"], "estado", estado);
         } else if(linea_tiempo == "Web") { // Si la línea de tiempo es físico, obtiene solo los PQRS del canál Web
            this.$set(crudComponent["searchFields"], "canal", "Web");
            this.$set(crudComponent["searchFields"], "estado", estado);
         } else {

            //total total
            if(linea_tiempo == null && estado == null) {
               this.$set(crudComponent["searchFields"], "tipos_pqrs", "todos");
            }
            //(TOTALES) asignado, en tramite, esperando respuesta del ciudadano, devuelto, respuesta parcial
            else if((estado == null || estado == 'solo vencido') && linea_tiempo!= null) {
               this.$set(crudComponent["searchFields"], "tipos_pqrs", "pendientes_ejecucion");
               this.$set(crudComponent["searchFields"], "pqrs_propios", "rol_superior");

            }
            //(TOTALES) Cancelado , abierto, finalizado
            else if(linea_tiempo == null && estado != null) {
               this.$set(crudComponent["searchFields"], "tipos_pqrs", "todos");
               this.$set(crudComponent["searchFields"], "pqrs_propios", "rol_superior");

            }
            this.$set(crudComponent["searchFields"], "linea_tiempo", linea_tiempo);
            this.$set(crudComponent["searchFields"], "estado", estado);
            this.$set(crudComponent["searchFields"], "canal", null);

         }
         // Se actualiza la función del componente crudComponent para ejecutar la acción del filtro del consolidado de PQRS
         crudComponent["pageEventActualizado"](1);
      }

      //Valida el estado de la PQR y si es finalizado valida si esta seguro de finalizarlo
      public validationStatus(){

         let estado = (this.$parent as Vue)["dataForm"].estado;
         let idPqr = (this.$parent as Vue)["dataForm"].pqr_id;


         if (estado == 'Finalizado') {
            this.$swal({
               title: '¿Está seguro de finalizar el PQR?'+(idPqr ? ' (ID: '+idPqr+')' : ''),
               text: "Esta acción no se puede deshacer.",
               icon: 'warning',
               showCancelButton: true,
               confirmButtonText: 'Sí, finalizar',
               cancelButtonText: 'No, cancelar',
               reverseButtons: true
               }).then((result) => {
               if (result.isConfirmed) {
                  // Llama a la función para finalizar la PQR
                  this.addRequerimiento();

               } else {
                  // Cierra el swal sin hacer nada
                  // console.log(estado);
               }
            });
         }else{
            this.addRequerimiento();
         }


      }
      /**
      * Guarda la informacion en base de datos
      *
      * @author German Gonzalez V. - Abr. 20 - 2023
      * @version 1.0.0
      */
      public addRequerimiento(): void {

         // Si la variable es true, quiere decir que aún no se ha terminado la carga completa de archivos al servidor
         if(this.uploadingFileInputCrudFile) {
            // Muestra un mensaje al usuario indicando que aún faltan archivos por cargar en su totalidad en el servidor
            this.$swal({
                  icon: "warning",
                  html: "Por favor, espere hasta que se terminen de cargar los adjuntos completamente.",
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get("trans.Accept"),
            });
            // Retorna para que no continue el flujo
            return;
         }

         let crudComponent = (this.$parent as Vue);

         crudComponent["showLoadingGif"]('¡Guardando datos!');

         // Obtiene la instancia del crudComponent
         if (crudComponent["isUpdate"] == false) {

            // Obtiene el nombre del recurso
            const resource = crudComponent["resource"]["post"] ? crudComponent["resource"]["post"] : crudComponent["resource"]["default"];
            // Envia peticion de guardado de datos
            axios.post(resource, crudComponent["makeFormData"](), { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((res) => {
               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

               const dataDecrypted = Object.assign({}, dataPayload);

               // Cierra el swal de guardando datos
               (this.$swal as any).close();
               // Cierra fomrulario modal
               $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
               // Limpia datos ingresados
               crudComponent["clearDataForm"]();
               // Agrega elemento nuevo a la lista
               crudComponent["addElementToList"](dataDecrypted["data"]);
               // Se llava el paginate avanzado del padre, CrudComponent
               crudComponent["paginateAvanzado"]();

            })
            .catch((err) => {

               (this.$swal as any).close();

               let errors = '';

               // Valida si hay errores asociados al formulario
               if (err.response.data.errors) {
                  crudComponent["dataErrors"] = err.response.data.errors;
                  crudComponent["_updateKeyRefresh"]();
               }
               else if (err.response.data) {
                  errors += '<br>'+err.response.data.message;
                  // Abre el swal para mostrar los errores
                  this.$swal({
                     icon: 'error',
                     html: this.lang.get('trans.Failed to save data')+errors,
                     confirmButtonText: this.lang.get('trans.Accept'),
                     allowOutsideClick: false,
                  });
               }
            });

         }else{

            let id = (this.$parent as Vue)["dataForm"].id;

            const formData: FormData = crudComponent["makeFormData"]();

            formData.append('_method', 'put');

            // Obtiene el nombre del recurso
            const resource = crudComponent["resource"]["update"] ? crudComponent["resource"]["update"] : crudComponent["resource"]["default"];
            // Envia peticion de guardado de datos
            axios.post(`${resource}/${id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((res) => {
               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

               const dataDecrypted = Object.assign({}, dataPayload);

               // Cierra el swal de guardando datos
               (this.$swal as any).close();
               // Cierra fomrulario modal
               $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
               // Limpia datos ingresados
               crudComponent["clearDataForm"]();
               // Agrega elemento nuevo a la lista
               crudComponent["assignElementList"](id,dataDecrypted["data"]);
               // Se llava el paginate avanzado del padre, CrudComponent
               crudComponent["paginateAvanzado"]();

               this.$swal({
                  icon: 'success',
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });

            })
            .catch((err) => {

               (this.$swal as any).close();

               let errors = '';

               // Valida si hay errores asociados al formulario
               if (err.response.data.errors) {
                  crudComponent["dataErrors"] = err.response.data.errors;
                  crudComponent["_updateKeyRefresh"]();
               }
               else if (err.response.data) {
                  errors += '<br>'+err.response.data.message;
                  // Abre el swal para mostrar los errores
                  this.$swal({
                     icon: 'error',
                     html: this.lang.get('trans.Failed to save data')+errors,
                     confirmButtonText: this.lang.get('trans.Accept'),
                     allowOutsideClick: false,
                  });
               }
            });
         }



      }

      /**
      * Guarda la informacion en base de datos
      *
      * @author German Gonzalez V. - Abr. 20 - 2023
      * @version 1.0.0
      */
      public addCiudadano(): void {
         // Abre el swal de guardando datos
         this.$swal({
            title: this.lang.get('trans.loading_save'),
            allowOutsideClick: false,
            onBeforeOpen: () => {
                  (this.$swal as any).showLoading();
            },
         });
         // Obtiene la instancia del crudComponent
         let crudComponent = (this.$parent as Vue);
         // Envia peticion de guardado de datos
         axios.post("citizens", this.makeFormData(crudComponent), { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            if(res.data.type_message != "error") {
               // Limpia datos ingresados
               this.clearDataFormPQRCiudadano(crudComponent);
               jQuery('#crear_ciudadano').hide(350);
               jQuery('#ciudadano_users_id*').focus();
               jQuery('#ciudadano_users_id*').val(dataDecrypted["data"]["name"]);
               jQuery('#nombre_ciudadano*').val(dataDecrypted["data"]["name"]);
               jQuery('#email_ciudadano*').val(dataDecrypted["data"]["email"]);
               jQuery('#documento_ciudadano*').val(dataDecrypted["data"]["document_number"]);

               crudComponent["dataForm"]["ciudadano_users_id"] = dataDecrypted["data"]["id"];
               crudComponent["dataForm"]["nombre_ciudadano"] = dataDecrypted["data"]["name"];
               crudComponent["dataForm"]["email_ciudadano"] = dataDecrypted["data"]["email"];
               crudComponent["dataForm"]["documento_ciudadano"] = dataDecrypted["data"]["document_number"];
               // Datos de notificacion (Por defecto guardar)
               const toastOptions = {
                  closeButton: true,
                  closeMethod: 'fadeOut',
                  timeOut: 3000,
                  tapToDismiss: false
               };

               // Visualiza toast positivo de la creación del ciudadano
               toastr.success("Ciudadano creado correctamente", "Éxito", toastOptions);
            } else {
               let mensaje = (res.data.message).split("<br>").slice(-1);
               jQuery('#type_person').focus();
               // Abre el swal para mostrar los errores
               this.$swal({
                  icon: 'warning',
                  html: mensaje,
                  confirmButtonText: this.lang.get('trans.Accept'),
                  allowOutsideClick: false,
               });
            }

         })
         .catch((err) => {
            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            let mensaje = (err.response.data.message).split("<br>").slice(-1);
            jQuery('#type_person').focus();
            // Abre el swal para mostrar los errores
            this.$swal({
               icon: 'warning',
               html: mensaje,
               confirmButtonText: this.lang.get('trans.Accept'),
               allowOutsideClick: false,
            });
            jQuery('#type_person').focus();
         });
      }

      /**
      * Crea el formulario de datos para guardar
      *
      * @author Jhoan Sebastian Chilito S. - Jun. 26 - 2020
      * @version 1.0.0
      */
      public makeFormData(crudComponent): FormData {
         let formData = new FormData();

         // Recorre los datos del formulario
         for (const key in crudComponent["dataForm"]) {
               if (crudComponent["dataForm"]["hasOwnProperty"](key)) {
                  const data = crudComponent["dataForm"][key];
                  // Valida si no es un objeto y si es un archivo
                  if ( typeof data != 'object' || data instanceof File || data instanceof Date || data instanceof Array) {
                     // Valida si es un arreglo
                     if (Array.isArray(data)) {
                           data.forEach((element) => {
                              if (typeof element == 'object') {
                                 formData.append(`${key}[]`,JSON.stringify(element));
                              } else {
                                 formData.append(`${key}[]`, element);
                              }
                           });
                     } else if (data instanceof Date) {
                           formData.append(key,  crudComponent["locale"].format(data, "YYYY-MM-DD hh:mm:ss"));
                     } else {
                           formData.append(key, data);
                     }
                  }
               }
         }
         return formData;
      }

      /**
       * Obtiene el evento del recaptcha de Google
       * @param token token generado por Google
       */
      public onCaptchaVerified(token: any): void {
         // Se crea una instancia del componente padre (CrudComponent)
         let crudComponent = (this.$parent as Vue);
         // Se le asigna el token generado por el captcha a la variable g_recaptcha_response
         this.$set(crudComponent["dataForm"], "g_recaptcha_response", token);
      }

      /**
      * Envia la petición para actualizar la fecha de vencimiento de los PQRS según el calendario
      *
      * @author Seven Soluciones Informáticas S.A.S. - Oct. 06 - 2023
      * @version 1.0.0
      *
      */
      public updateFechaVencePQRS(): void {
         this.showLoadingGif(this.lang.get('trans.download_report'));
         // Envia peticion para actualizar la fecha de vencimiento de los PQRS según el calendario y luego exportar los datos
         let dataJWT = sign({data: this.getMakeDataToExport()}, window["env"].JWT_SECRET_KEY);

         // Envia peticion para exportar datos de la tabla
         axios.post(`update-fecha-vence`, {
            data: dataJWT
         }, {responseType: 'blob'})
         .then((res) => {
            (this.$swal as any).close()
            // Descagar el archivo generado
            this.downloadFile(res.data, "actualizacion_fecha_vence_PQRS", "pdf");
         })
         .catch((err) => {
            this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
         });
      }

      /**
      * Obtiene los filtros extrayendo
      * el key del arreglo por parametro
      *
      * @author Carlos Moises Garcia T. - Ago. 09 - 2021
      * @version 2.0.0
      *
      * @param list lista de datos a filtrar
      */
      public getFiltersFromArray(list): any {
         // Filtros de busqueda
         const filters = {};
         const values: Array<string> = Object.values(list);
         Object.keys(list).forEach((key, index) => {
            // Valida que no este vacio
            if (key && values[index] != null) {
               // Valida si el tipo de filtro a buscar es de tipo objecto
               if (typeof values[index] == 'object') {
                     // Obtiene los valores del objecto
                     let valueArray = Object.values(values[index]);
                     let data = [];
                     // Valida que el filtro a buscar tenga mas de un dato
                     if (valueArray.length > 0) {
                        // Recorre los datos del filtro de busqueda
                        valueArray.forEach((value, index1) => {
                           // Valida si el dato que no sea de tipo fecha
                           if (isNaN(Date.parse(valueArray[index1]))) {
                                 // Asigna el dato al array
                                 data.push(valueArray[index1]);
                           } else {
                                 // Asigna el dato al array
                                 data.push(valueArray[index1]);
                           }
                        });
                        filters[key] = data;
                     } else {
                        // Valida si el valor es de tipo fecha
                        if (this.isValidDate(values[index])) {
                           // Formatea la fecha
                           let date = locale.format(values[index], "YYYY-MM-DD")
                           filters[key] = (date)? String(date).toUpperCase() : date;
                        }
                     }
               } else {
                     filters[key] = (values[index])? String(values[index]).toUpperCase() : values[index];
               }
            }
         });
         return filters;
      }

      /**
      * Formatea fechas
      *
      * @author Carlos Moisés García T. - Jul. 22 - 2020
      * @version 1.0.0
      *
      * @param date fecha a convertir
      */
      public formatDate(date): void {
         if( date ){
               return locale.format(date, "YYYY-MM-DD");
         }
      }

      /**
      * Valida si un dato es una fecha
      *
      * @author Carlos Moises Garcia T. - Ago. 09 - 2021
      * @version 1.0.0
      *
      * @param date fecha a evaluar si tiene el formato correcto
      */
      public isValidDate(date) {
         // Formatea en array el dato
         date = date.toString().split('-');

         // Valida si es un fecha, si tiene mas de una posicion es fecha
         if (date.length > 1) {
               return true;
         } else {
               return false;
         }
      }

      /**
      * Busca en la lista de elementos por cada campo disponible
      * y retorna la lista de elementos filtrados y paginados
      *
      * @author Carlos Moises Garcia T. - Ago. 09 - 2021
      * @version 2.0.0
      */
      public advancedSearchFilter(): Array<any> {


         // Se verifica si existe una lista de datos
         if (this.dataList) {

            let filters = [];

            // Valida si existe datos en los campos de busquedad
            if (Object.keys(this.searchFields).length) {
               // Datos del filtro
               filters = this.getFiltersFromArray(this.searchFields);
            }

            // Obtenemos las llaves de los filtros
            const filterKeys = Object.keys(filters);

            return this.dataList.filter(item => {
               // valida todos los criterios de filtrado
               return filterKeys.every(key => {
                     // Ignora un filtro vacio
                     if (!filters[key].length){
                        return true;
                     }

                     // Valida si el filtro es de tipo objecto
                     if (typeof filters[key] == 'object') {

                        const getValue = value => (typeof value === 'string' ? value.toUpperCase() : value);

                        // Valida que el dato no sea de tipo fecha
                        if (!this.isValidDate(item[key])) {
                           return filters[key].find(filter => getValue(filter) === getValue(item[key]));
                        } else {
                           // Formatea la fecha de inicio del rango
                           let startDate =  locale.format(filters[key][0], "YYYY-MM-DD");
                              // Formatea la fecha fin del rango
                           let endDate = locale.format(filters[key][1], "YYYY-MM-DD");
                           // Formatea la fecha del listado de registros
                           let date = this.formatDate(item[key]);
                           // Compara la fecha si coinciden con el registro
                           return (date >= startDate && date <= endDate);
                        }
                     } else {

                        //Valida si el campo del filtro es typequery omite este filtro retornando true
                        if(key == "typeQuery"){
                           return true;
                        }

                        //Valida que el campo del filtro este dentro de la variable typequery
                        if(String(filters["typeQuery"]).toLowerCase().includes(key)) {
                           //Se utiliza el operador == si el filtro a evaluar esta dentro de la variable typequery
                           return String(item[key]).toUpperCase() == filters[key];
                        } else {
                           return String(item[key]).toUpperCase().includes(filters[key]);
                        }
                     }
               });
            });
         }
         return [];
      }

      /**
      * Crea y organiza los datos de exportacion de la tabla
      *
      * @author Jhoan Sebastian Chilito S. - May. 28 - 2020
      * @version 1.0.0
      */
      public getMakeDataToExport(): any[] {
         const dataExport = utility.clone(this.advancedSearchFilter());
         // Recorre datos originales de exportacion
         dataExport.forEach((dataElement: any) => {
               // Recorre datos de exportacion
               this.excludeExportData.forEach((excludeData: string) => {
                  delete dataElement[excludeData];
               });
               // Recorre datos relacionados
               this.relatedDataExport.forEach((relatedData: any) => {
                  const relatedElement = dataElement[relatedData.name];
                  // Valida si existe el elemento relacionado
                  if (relatedElement) {
                     // Asigna el elemento valor a mostrar del objeto relacionado
                     dataElement[relatedData.name] = relatedElement[relatedData.value];
                  }
               });
         });
         return dataExport;
      }

      /**
      * Visualiza notificacion por accion
      *
      * @author Jhoan Sebastian Chilito S. - May. 09 - 2020
      * @version 1.0.0
      *
      * @param message mesaje de notificacion
      * @param isPositive valida si la notificacion debe ser posiva o negativa
      * @param title titulo de notificacion
      */
      private _pushNotification(message: string, isPositive: boolean = true, title: string = '¡Éxito!'): void {
         // Datos de notificacion (Por defecto guardar)
         const toastOptions = {
            closeButton: true,
            closeMethod: 'fadeOut',
            timeOut: 3000,
            tapToDismiss: false
         };
         // Valida el tipo de toast que se debe visualiza
         if (isPositive) {
            // Visualiza toast positivo
            toastr.success(message, title, toastOptions);
         } else {
            toastOptions.timeOut = 0;
            // Visualiza toast negativo
            toastr.error(message, title, toastOptions);
         }
      }


      /**
      * Descarga un archivo codificado
      *
      * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
      * @version 1.0.0
      *
      * @param file datos de archivo a construir
      * @param filename nombre de archivo
      * @param fileType tipo de archivo a exportar(extension)
      */
      public downloadFile(file: string, filename: string, fileType: string): void {
         // Crea el archivo tipo blob
         let newBlob = new Blob([file]);

         // IE no permite usar un objeto blob directamente como enlace href
         // en su lugar, es necesario usar msSaveOrOpenBlob
         if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob);
            return;
         }

         // Para otros navegadores:
         // Crea un enlace que apunta al ObjectURL que contiene el blob.
         const data = window.URL.createObjectURL(newBlob);
         let link = document.createElement('a');
         link.href = data;
         link.download = `${filename}.${fileType}`;
         link.click();
         setTimeout(() => {
            // Para Firefox es necesario retrasar la revocación de ObjectURL
            window.URL.revokeObjectURL(data);
         }, 100);
      }

      private showLoadingGif(mensaje) {

         this.$swal({
            html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>'+mensaje+'.</span>',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
         });

      }

      //Función que permite cargar el tablero con el consolidado de PQRS
      public verTableroConsolidado(){

         //Obtiene el texto de botón que muestra el tablero
         var texto_btn_consolidado = jQuery("#text_btn_consolidado").text();

         //Condición que valida cuando el usuario desea ver el tablero
         if (texto_btn_consolidado == "Ver consolidado de PQRS") {

            var totalPQRS = jQuery(".total").text();
            //Condición que valida que la vigencia no este vacia
            if (totalPQRS == "" || totalPQRS == "0") {
               //Se hace el llamado a la función que permite cargar los totales al tablero
               this._getDataList();

               //Código que muestra el tablero
               jQuery(".spinner").show();

               jQuery("#consolidado").css("opacity", .4);
            }

            //Código que muestra el tablero
            jQuery("#contenedorTableConsolidado").show("slow");

            //Código que cambia el texto del botón que muestra u oculta el tablero
            jQuery("#text_btn_consolidado").text("Ocultar consolidado de PQRS");
         }
         //Condición que valida cuando el usuario desea ocultar el tablero
         else{
            //jQuery("#cond").val("");

            //Código que muestra el tablero
            jQuery("#contenedorTableConsolidado").hide("slow");

            //Código que cambia el texto del botón que muestra u oculta el tablero
            jQuery("#text_btn_consolidado").text("Ver consolidado de PQRS");
         }
      }

      //Consulta los dias que faltan para vencer el pqr
      public checkDays(fecha_vence,tipo_plazo,estado,fecha_fin){
            axios
            .post(`dias-restantes/${tipo_plazo}/${fecha_vence}/${estado}/${fecha_fin}`)
            .then((res) => {
               //Asigna al dataShow del padre el numero de dias que obtuvo por medio del axios
               this.$set((this.$parent as any).dataShow,'dias_restantes_pqr',res.data);


            })
            .catch((error) => {
            console.log('Error');
            });

      }

        /**
         * Guarda la informacion en base de datos
         *
         * @author Seven Soluciones Informáticas S.A.S. - Ago. 16 - 2024
         * @version 1.0.0
         */
        public actualizarAdjuntoRespuesta(): void {
            // Obtiene la instancia del crudComponent
            let crudComponent = (this.$parent as Vue);
            // Si la variable es true, quiere decir que aún no se ha terminado la carga completa de archivos al servidor
            if(crudComponent["uploadingFileInputCrudFile"]) {
                // Muestra un mensaje al usuario indicando que aún faltan archivos por cargar en su totalidad en el servidor
                this.$swal({
                        icon: "warning",
                        html: "Por favor, espere hasta que se terminen de cargar los adjuntos completamente.",
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept"),
                });
                // Retorna para que no continue el flujo
                return;
            }

            // Abre el swal de guardando datos
            this.$swal({
                title: this.lang.get('trans.loading_save'),
                allowOutsideClick: false,
                onBeforeOpen: () => {
                        (this.$swal as any).showLoading();
                },
            });
            // Envia peticion de guardado de datos
            axios.post('p-q-r-s-relacionar-adjunto-respuesta', crudComponent["makeFormData"](), { headers: { 'Content-Type': 'multipart/form-data' } })
                .then((res) => {
                    // Cierra el swal de guardando datos
                    (this.$swal as any).close();

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Cierra fomrulario modal
                    $(`#modal-form-p-q-r-s-relacionar-adjunto`).modal('toggle');
                    // Agrega elemento nuevo a la lista
                    crudComponent["assignElementList"](crudComponent["dataForm"][crudComponent["customId"]], dataDecrypted["data"]);
                    // Limpia datos ingresados
                    crudComponent["clearDataForm"]();
                    // Emite notificacion de almacenamiento de datos
                    this._pushNotification(res.data.message);
                })
                .catch((err) => {

                    (this.$swal as any).close();

                    let errors = '';

                    // Valida si hay errores asociados al formulario
                    if (err.response.data.errors) {
                        crudComponent["dataErrors"] = err.response.data.errors;
                        crudComponent["_updateKeyRefresh"]();
                    }
                    else if (err.response.data) {
                        errors += '<br>'+err.response.data.message;
                        // Abre el swal para mostrar los errores
                        this.$swal({
                            icon: 'error',
                            html: this.lang.get('trans.Failed to save data')+errors,
                            confirmButtonText: this.lang.get('trans.Accept'),
                            allowOutsideClick: false,
                        });
                    }
                });
        }

        public prueba() {
            // Verificar si la imagen está visible
            if (jQuery("#infoImage").is(":visible")) {
                jQuery("#infoImage").slideUp('slow');
            } else {
                jQuery("#infoImage").slideDown('slow');
            }
        }
   }
</script>
<style>

   /* Estilos para el tablero consolidado */
   .total {
      font-size: 30px;
      cursor: pointer;
   }

   .abiertos_bg {
      background-color: #007bff;
      color: white;
      font-size: 13px;
      cursor: pointer;
   }

   .abiertos_txt {
      color: #007bff;
      font-size: 23px;
      cursor: pointer;
   }

   .a_tiempo_bg {
      background-color: #ffc107;
      font-size: 13px;
      cursor: pointer;
   }

   .a_tiempo_txt {
      color: #ffc107;
      font-size: 23px;
      cursor: pointer;
   }

   .proximos_a_vencerse_bg {
      background-color: #fd7e14;
      color: white;
      font-size: 13px;
      cursor: pointer;
   }

   .proximos_a_vencerse_txt {
      color: #fd7e14;
      font-size: 23px;
      cursor: pointer;
   }

   .vencidos_bg {
      background-color: #dc3545;
      color: white;
      font-size: 13px;
      cursor: pointer;
   }

   .vencidos_txt {
      color: #dc3545;
      font-size: 23px;
      cursor: pointer;
   }

   .finalizados_bg {
      background-color: #28a745;
      color: white;
      font-size: 13px;
      cursor: pointer;
   }

   .finalizados_txt {
      color: #28a745;
      font-size: 23px;
      cursor: pointer;
   }

   .cancelados_bg {
      background-color: white;
      font-size: 13px;
      cursor: pointer;
   }

   .cancelados_txt {
      font-size: 23px;
      cursor: pointer;
   }


   .estado_abierto {
      background-color: #007bff;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_finalizado_a_tiempo {
      background-color: #28a745;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_cancelado {
      background-color: whitesmoke;
      /* color: white; */
      border-radius: 3px;
      padding: 5px;
   }

   .estado_a_tiempo {
      background-color: #ffc107;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_proximo_vencer, .estado_finalizado_vencido_justificado {
      background-color: #fd7e14;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_vencido {
      background-color: #dc3545;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }
   /* Scroll en el eje y para la tabla del consolidado de PQRS */
   #consolidado {
      font-weight: bold;
      display: block;
      overflow-x: auto;
   }

   .btn_consolidado {
      float: left;
      text-align: center;
      width: 100%;
   }

   .estado_vencido_select {
      border: 1px solid #2196f3;
      border-radius: 5px;
      padding: 10px;
      max-width: 0; /* Tamaño inicial del elemento */
      max-height: 0; /* Tamaño inicial del elemento */
      animation: abrirDivCancelado 0.5s ease forwards; /* Nombre de la animación, duración, función de temporización y dirección */
   }

   .estado_esperandor_select {
      border: 1px solid #2196f3;
      border-radius: 5px;
      padding: 10px;
      max-width: 0; /* Tamaño inicial del elemento */
      max-height: 0; /* Tamaño inicial del elemento */
      animation: abrirDivEsperando 0.5s ease forwards; /* Nombre de la animación, duración, función de temporización y dirección */
   }


   .estado_entramite_select {
      border: 1px solid #2196f3;
      border-radius: 5px;
      padding: 10px;
      overflow: hidden; /* Para ocultar el contenido que se desplazará */
      max-width: 0; /* Tamaño inicial del elemento */
      max-height: 0; /* Tamaño inicial del elemento */
      animation: abrirDiv 0.5s ease forwards; /* Nombre de la animación, duración, función de temporización y dirección */
   }

   .estado_finalizado_select {
      border: 1px solid #2196f3;
      border-radius: 5px;
      padding: 10px;
      overflow: hidden; /* Para ocultar el contenido que se desplazará */
      max-width: 0; /* Tamaño inicial del elemento */
      max-height: 0; /* Tamaño inicial del elemento */
      animation: abrirDivFinalizado 0.5s ease forwards; /* Nombre de la animación, duración, función de temporización y dirección */
   }

   .estado_finalizado_justifi_select {
      border: 1px solid #2196f3;
      border-radius: 5px;
      padding: 10px;
      overflow: hidden; /* Para ocultar el contenido que se desplazará */
      max-width: 0; /* Tamaño inicial del elemento */
      max-height: 0; /* Tamaño inicial del elemento */
      animation: abrirDivFinalizadoJustifi 0.5s ease forwards; /* Nombre de la animación, duración, función de temporización y dirección */
   }


   @keyframes abrirDiv {
      from {
         max-width: 0; /* Ancho inicial */
         max-height: 0; /* Altura inicial */
      }
      to {
         max-width: 1000px; /* Ancho final */
         max-height: 1000px; /* Altura final */
      }
   }

   @keyframes abrirDivFinalizado {
      from {
         max-width: 0; /* Ancho inicial */
         max-height: 0; /* Altura inicial */
      }
      to {
         max-width: 1000px; /* Ancho final */
         max-height: 1000px; /* Altura final */
      }
   }

   @keyframes abrirDivFinalizadoJustifi {
      from {
         max-width: 0; /* Ancho inicial */
         max-height: 0; /* Altura inicial */
      }
      to {
         max-width: 1000px; /* Ancho final */
         max-height: 1000px; /* Altura final */
      }
   }


   @keyframes abrirDivEsperando {
      from {
         max-width: 0; /* Ancho inicial */
         max-height: 0; /* Altura inicial */
      }
      to {
         max-width: 1000px; /* Ancho final */
         max-height: 1000px; /* Altura final */
      }
   }

   @keyframes abrirDivCancelado {
      from {
         max-width: 0; /* Ancho inicial */
         max-height: 0; /* Altura inicial */
      }
      to {
         max-width: 1000px; /* Ancho final */
         max-height: 1000px; /* Altura final */
      }
   }
</style>
