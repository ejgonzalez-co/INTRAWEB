<template>
   <div>
      <!-- begin #modal-form-form-poll-ti -->
      <div class="modal fade" :id="`modal-form-${this.name}`">
         <div class="modal-dialog modal-lg">
            <form @submit.prevent="save()" :id="`form-${this.name}`">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">Diligenciar encuesta</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">

                     <div class="panel" data-sortable-id="ui-general-1">
                        <!-- begin panel-heading -->
                        <div class="panel-heading ui-sortable-handle">
                           <h4 class="panel-title"><strong>Diligenciar encuesta</strong></h4>
                        </div>
                        <!-- end panel-heading -->
                        <!-- begin panel-body -->
                        <div class="panel-body">
                           <div class="row">

                              <!-- Id Cargo Field -->
                              <div  v-for="(question, key) in dataList" :key="key" class="col-md-12">
                                 <div class="form-group row m-b-15">
                                    <label class="col-form-label col-md-4 required" for="question">{{ question.question }}:</label>
                                    <div class="col-md-8">
                                       <select v-if="question.answer_option" v-model="dataForm.question[key]" name="question[]" class="form-control" required>
                                          <option v-for="(answer, key) in JSON.parse(question.answer_option)" :value="answer+'_'+question.id" :key="key">{{ answer }}</option>
                                       </select>
                                       <small>Seleccione el cargo al cual pertenece el funcionario</small>
                                    </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                        <!-- end panel-body -->
                     </div>
                  </div>

                  <div class="modal-footer">
                     <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-form-poll-tic -->

   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';
   import * as bootstrap from 'bootstrap';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
   * Componente para responder la encuesta de satisaccion de solicitudes tic
   *
   * @author Carlos Moises Garcia T. - Nov. 15 - 2020
   * @version 1.0.0
   */
   @Component
   export default class HtTicSatisfactionPoll extends Vue {
      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true, default: 'modal-form-poll-tic' })
      public name: string;

      /**
       * Lista de elementos
       */
      public dataList: Array<any>;

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
       * @author Carlos Moises Garcia T. - Oct. 13 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();
         this.dataList = [];
         this.dataForm = {
            question: []
         };
         this.dataErrors = {};

         this.lang = (this.$parent as any).lang;
      }

      /**
       * Se ejecuta cuando el componente ha sido creado
       */
      created() {
         // Carga la lista de elementos
         this._getDataList();
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - Nov. 15 - 2020
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del dataform
         this.dataForm = {
            question: []
         };
         // Limpia errores
         this.dataErrors = {};
         // Actualiza componente de refresco
         (this.$parent as any)._updateKeyRefresh();
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Nov. 15 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadPollTic(dataElement: object): void {
         // this.clearDataForm();
         if (dataElement) {
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);

            this.dataForm.question = [];
         } else{
         }

         $(`#modal-form-${this.name}`).modal('show');
      }


      /**
       * Crea el formulario de datos para guardar
       *
       * @author Carlos Moises Garcia T. - Nov. 15 - 2020
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
                  } else {
                     formData.append(key, data);
                  }
               }
            }
         }
         return formData;
      }

      /**
       * Guarda los datos del formulario
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
       * @version 1.0.0
       */
      public save(): void {
         // Limpia los errores anteriores
         this.dataErrors = {};
         // Valida si los datos son para actualizar
         if (false) {
            // Actualiza un registro existente
            // this.update();
         } else {
            // Almacena un nuevo registro
            this.store();
         }
      }

      /**
       * Guarda la informacion en base de datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       */
      public store(): void {

         this.$swal({
				title: this.lang.get('trans.loading_save'),
				allowOutsideClick: false,
				onBeforeOpen: () => {
               (this.$swal as any).showLoading();
				},
			});

         // Envia peticion de guardado de datos
         axios.post(this.name, this.makeFormData(), { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:{id:null}}, dataPayload);

            const request = dataDecrypted?.data;

            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            // Valida el tipo de alerta que de debe mostrar
            if (res.data.type_message) {

               // Valida que el tipo de respuesta sea exitoso
               if (res.data.type_message == "success") {
                  // Cierra fomrulario modal
                  $(`#modal-form-${this.name}`).modal('toggle');
                  // Limpia datos ingresados
                  this.clearDataForm();
                  // Actualiza elemento modificado en la lista
                  Object.assign((this.$parent as any)._findElementById(request.id, false), request);
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
               $(`#modal-form-${this.name}`).modal('toggle');
               // Limpia datos ingresados
               this.clearDataForm();

               // Actualiza elemento modificado en la lista
               Object.assign((this.$parent as any)._findElementById(request.id, false), request);
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
               allowOutsideClick: false,
            });
         });
      }

      /**
       * Obtiene la lista de datos
       *
       * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
       * @version 1.0.0
       */
      private _getDataList(): void {
         // Envia peticion de obtener todos los datos del recurso de categorias
         axios.get('get-tic-poll-questions')
         .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({data:[]}, dataPayload);

            this.dataList = dataDecrypted?.data;
         })
         .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
         });
      }
   }

</script>
