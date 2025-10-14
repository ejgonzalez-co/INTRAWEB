<template>
   <div>
      <!-- begin #modal-view-paa-process-attachments -->
      <div class="modal fade" id="modal-form-paa-process-attachments" data-backdrop="static" data-keyboard="false">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-paa-process-attachments" enctype="multipart/form-data">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Formulario de Documentos</h4>
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
                                 <!-- Name Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required" for="observation">{{ 'trans.Name' | trans }}:</label>
                                    <div class="col-md-9">
                                       <input type="name" id="name" :class="{'form-control':true, 'is-invalid':dataErrors.name}" v-model="dataForm.name" required>
                                       <small>Ingrese el nombre del documento.</small>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-12">
                                 <!-- Description Field -->
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-2 required" for="description">{{ 'trans.Description' | trans }}:</label>
                                    <div class="col-md-9">
                                       <textarea name="description" cols="50" rows="5" id="description" class="form-control" v-model="dataForm.description" required></textarea>
                                       <small>Ingrese una descripción.</small>
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
                                          :max-files="20"
                                          file-path="public/contractual_process/annual_action_plan"
                                          :is-update="isUpdate"
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
      <!-- end #modal-view-paa-process-attachments -->
   </div>
</template>
<script lang="ts">

   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';
   import { Locale } from "v-calendar";

   const locale = new Locale();
   
   /**
    * Componente para evaluar las necesidades de plan anual de accion
    *
    * @author Carlos Moises Garcia T. - May. 11 - 2021
    * @version 1.0.0
    */
   @Component
   export default class PaaProcessAttachment extends Vue {

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Key autoincrementable y unico para
       * ayudar a refrescar un componente
       */
      public keyRefresh: number;

      /**
       * Valida si los valores del formulario
       * son para actualizar o crear
       */
      public isUpdate: boolean;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();

         // Inicializa valores del dataform
         this.dataForm = {};
         this.dataErrors = {};
         this.keyRefresh = 0;
         this.isUpdate = false;
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del formulario
         this.initValues();
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
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
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {
         // console.log(dataElement);
         // Valida que exista datos
         if (dataElement) {
            // Habilita actualizacion de datos
            this.isUpdate = false;

            let data = utility.clone(dataElement);
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm.pc_needs_id = data.id;      

            $(`#modal-form-paa-process-attachments`).modal('show');
         }
      }

      /**
       * Crea el formulario de datos para guardar
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
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
       * Visualiza notificacion por accion
       *
       * @author Carlos Moises Garcia T. - May. 11 - 2021
       * @version 1.0.0
       *
       * @param message mesaje de notificacion
       * @param isPositive valida si la notificacion debe ser posiva o negativa
       * @param title titulo de notificacion
       */
      public pushNotification(title: string = 'OK', message: string, isPositive: boolean = true): void {
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
         // Valida que el metodo http sea PUT
         if (this.isUpdate) {
            formData.append('_method', 'put');
         }
         // Envia peticion de guardado de datos
         axios.post('paa-process-attachments', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {
            // Valida que se retorne los datos desde el controlador
            if (res.data.data) {
               // Emite evento de guardado para quien lo solicite
               // this.$emit('saved', { 'data': res.data.data, 'isUpdate': this.isUpdate });

               // Actualiza elemento modificado en la lista
               Object.assign((this.$parent as any)._findElementById(this.dataForm['pc_needs_id'], false), res.data.data);
            }
            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            // Cierra fomrulario modal
            $('#modal-form-paa-process-attachments').modal('hide');
            // Limpia datos ingresados
            this.clearDataForm();
            // Emite notificacion de almacenamiento de datos
            this.pushNotification('Ok', res.data.message, true);
         })
         .catch((err) => {
            (this.$swal as any).close();
            // Emite notificacion de almacenamiento de datos
            this.pushNotification('Error', 'Error al guardar los datos',  false);
            // Valida si hay errores asociados al formulario
            if (err.response.data.errors) {
                  // this.dataErrors = err.response.data.errors;
            }
         });
      }
   }
</script>