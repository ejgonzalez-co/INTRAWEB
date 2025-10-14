<template>
   <div>
      <!-- begin #modal-view-approve-call-paa -->
      <div class="modal fade" id="modal-form-approve-call-paa" data-backdrop="static" data-keyboard="false">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" id="form-assess-approve-call-paa" enctype="multipart/form-data">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Aprobar convocatoría PAA</h4>
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
      <!-- end #modal-view-approve-call-paa -->
   </div>
</template>
<script lang="ts">

   import axios from "axios";
   import { Component, Vue } from "vue-property-decorator";
   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();
   
   /**
    * Componente para ver las novedades de las necesidades de plan anual de accion
    *
    * @author Carlos Moises Garcia T. - May. 20 - 2021
    * @version 1.0.0
    */
   @Component
   export default class ApproveCallPaa extends Vue {

      /**
       * Datos del formulario
       */
      public dataForm: any;

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
         // Inicializa valores del dataform
         this.dataForm = {};
         this.dataList = [];
         this.keyRefresh = 0;
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
            this.dataForm.call_id = data.id;
            this.$forceUpdate();
            $(`#modal-form-approve-call-paa`).modal('show');
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
       * @author Carlos Moises Garcia T. - May. 20 - 2021
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
         axios.post('approve-call-paa', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {

            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {
               console.log(res);
               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Abre el swal de guardando datos
                  this.$swal({
                     icon: res.data.type_message,
                     text: res.data.message,
                     allowOutsideClick: false,
                  });

                  // Cierra fomrulario modal
                  $(`#modal-form-approve-call-paa`).modal('hide');
                  // Limpia datos ingresados
                  this.clearDataForm();
                  // Actualiza elemento modificado en la lista
                  Object.assign((this.$parent as any)._findElementById(res.data.data.id, false), res.data.data);
               }
               // Abre el swal de guardando datos
               this.$swal({
                  icon: res.data.type_message,
                  text: res.data.message,
                  allowOutsideClick: false,
               });
            }
         })
         .catch((err) => {
            // console.log(err);
            (this.$swal as any).close();
            // Emite notificacion de almacenamiento de datos
            // Valida si hay errores asociados al formulario
            if (err.response.data.errors) {
               // this.dataErrors = err.response.data.errors;
            }
         });
      }
   }
</script>