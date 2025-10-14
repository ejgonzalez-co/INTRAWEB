<template>
   <div>
      <!-- begin #modal-view-change-version-paa -->
      <div class="modal fade" id="modal-form-change-version-paa" data-backdrop="static" data-keyboard="false">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-change-version-paa" enctype="multipart/form-data">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Cambiar versión PAA</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body hljs-wrapper">

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title">
                              <!-- <strong>:</strong> -->
                           </h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <div class="col-md-12">
                                 <!-- Observation Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2" for="observation">{{ 'trans.Observation' | trans }}:</label>
                                    <div class="col-md-9">
                                       <textarea name="observation" cols="50" rows="5" id="observation" class="form-control" v-model="dataForm.observation"></textarea>
                                       <small>Ingrese una observación.</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-12">
                                 <!-- Attached Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2" for="attached">{{ 'trans.Attached' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input-file
                                          :value="dataForm"
                                          name-field="attached"
                                          :max-files="2"
                                          file-path="public/contractual_process/annual_action_plan"
                                          help-text="Adjunto el archivo."
                                       >
                                       </input-file>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button  class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-view-change-version-paa -->
   </div>
</template>
<script lang="ts">

   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();
   
   /**
    * Componente para cambiar la version del plan anual de accion
    *
    * @author Carlos Moises Garcia T. - Jul. 26 - 2021
    * @version 1.0.0
    */
   @Component
   export default class ChangeVersionPaa extends Vue {

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Jul. 26 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         // Inicializa valores del dataform
         this.dataForm = {};

         this.dataErrors = {};
         this.lang = (this.$parent as any).lang;
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del formulario
         this.dataForm = {};
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Jul. 26 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {
         // Valida que exista datos
         if (dataElement) {
            let data = utility.clone(dataElement);   
            this.dataForm.call_id = data.id;
            this.$forceUpdate();
            $(`#modal-form-change-version-paa`).modal('show');
         }
      }

      /**
       * Inicializa valores del dataform
       *
       * @author Carlos Moises Garcia T. - Feb. 23 - 2021
       * @version 1.0.0
       */
      public initValues(): void {
         this.dataForm = utility.clone((this.$parent as any).initValues);
      }

      /**
       * Crea el formulario de datos para guardar
       *
       * @author Carlos Moises Garcia T. - Jul. 26 - 2021
       * @version 1.0.0
       */
      public makeFormData(): FormData {
         let formData = new FormData();
         // Recorre los datos del formulario
         for (const key in this.dataForm) {
               if (this.dataForm.hasOwnProperty(key)) {
                  const data = this.dataForm[key];
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
                        formData.append(key, locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                     } else {
                        formData.append(key, data);
                     }
                  }
               }
         }
         return formData;
      }

      /**
       * Almacena informacion del formulario
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       */
      public save(): void {
         // Almacena la informacion del formulario
         this.store();
      }

      /**
       * Guarda la informacion del formulario dinamico
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       */
      public store() {
         // Abre el swal de guardando datos
         this.$swal({
            title: 'Guardando',
            allowOutsideClick: false,
            onBeforeOpen: () => {
               (this.$swal as any).showLoading();
            },
         });
         // Construye los datos del formulario
         const formData: FormData = this.makeFormData();

         // Envia peticion de guardado de datos
         axios.post('change-version-paa', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {


            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {

               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Cierra fomrulario modal
                  $(`#modal-form-change-version-paa`).modal('toggle');
                  // Limpia datos ingresados
                  this.clearDataForm();
                  // Actualiza elemento modificado en la lista
                  Object.assign((this.$parent as any)._findElementById(res.data.data.id, false), res.data.data);
               }
               // Abre el swal de la respusta de la peticion
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });
            } else {
               // Cierra fomrulario modal
               $(`#modal-form-change-version-paa`).modal('toggle');
               // Limpia datos ingresados
               this.clearDataForm();

              // Actualiza elemento modificado en la lista
               Object.assign((this.$parent as any)._findElementById(res.data.data.id, false), res.data.data);
               // Emite notificacion de almacenamiento de datos
               (this.$parent as any)._pushNotification(res.data.message);
            }
         })
         .catch((err) => {
            (this.$swal as any).close();

            let errors = '';

            // Valida si hay errores asociados al formulario
            if (err.response.data.errors) {
               this.dataErrors = err.response.data.errors;
               // Reocorre la lista de campos del formulario
               for (const key in this.dataForm) {
                  // Valida si existe un error relacionado al campo
                  if (err.response.data.errors[key]) {
                        // Agrega el error a la lista de errores
                        errors += '<br>'+err.response.data.errors[key][0];
                  }
               }
            }
            else if (err.response.data) {
               errors += '<br>'+err.response.data.message;
            }
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