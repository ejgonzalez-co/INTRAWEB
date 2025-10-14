<template>
   <div>

      <!-- begin #modal-form-tic-knowledge-bases -->
      <div class="modal fade" id="modal-form-tic-knowledge-bases">
         <div class="modal-dialog modal-lg">
            <form @submit.prevent="save()" id="form-tic-knowledge-bases">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Crear conocimiento</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">

                     <!-- Ht Tic Type Request Id Field -->
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="ht_tic_type_request_id">{{ 'trans.Type Knowledge' | trans }}:</label>
                        <div class="col-md-9">
                           <select-check
                                 css-class="form-control"
                                 name-field="ht_tic_type_request_id"
                                 reduce-label="name"
                                 reduce-key="id"
                                 name-resource="get-tic-type-requests"
                                 :value="dataForm"
                                 :is-required="true">
                           </select-check>
                        </div>
                     </div>

                     <!-- Affair Field -->
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="affair">{{ 'trans.Affair' | trans }}:</label>
                        <div class="col-md-9">
                           <input type="text" id="affair" class="form-control" v-model="dataForm.affair" required>
                        </div>
                     </div>

                     <!-- Knowledge Description Field -->
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="knowledge_description">{{ 'trans.Description' | trans }}:</label>
                        <div class="col-md-9">
                           <textarea name="knowledge_description" cols="50" rows="10" id="knowledge_description" class="form-control" v-model="dataForm.knowledge_description" required></textarea>
                        </div>
                     </div>

                     <!--  Knowledge State Field -->
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="knowledge_state">{{ 'trans.State' | trans }}:</label>
                        <div class="col-md-9">
                           <select-check
                              css-class="form-control"
                              name-field="knowledge_state"
                              reduce-label="name"
                              reduce-key="id"
                              name-resource="get-constants/state_knowledge_tic"
                              :value="dataForm"
                              :is-required="true">
                           </select-check>
                        </div>
                     </div>

                     <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3 required" for="knowledge_state">Adjunto: </label>
                     <div class="col-md-9">
                        <input-file
                           :value="dataForm"
                           name-field="attached"
                           :max-files="30"
                           :max-filesize="20"
                           file-path="public/help_table/documents_help_table"
                           message="Arrastre o seleccione los archivos"
                           help-text="adjunte los archivos que acompañan su base de conocimiento, peso máximo por archivo 5m, cantidad máxima de archivos 10."
                        >
                        </input-file>
                     </div>
                  </div>

                  <!-- Affair Field -->
                  <div class="form-group row m-b-15">
                     <label class="col-form-label col-md-3 required" for="knowledge_state">Enlace: </label>
                     <div class="col-md-9">
                        <input type="text" id="affair" class="form-control" v-model="dataForm.enlace">
                        <small>Ingrese el enlace</small>
                     </div>
                  </div>

                  </div>
                  <div class="modal-footer">
                     <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i> Cerrar</button>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i> Guardar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- end #modal-form-tic-knowledge-bases -->

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
   * @author Carlos Moises Garcia T. - Nov. 29 - 2020
   * @version 1.0.0
   */
   @Component
   export default class HtTicKnowledgeBase extends Vue {
      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true, default: 'tic-knowledge-bases' })
      public name: string;

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
         this.dataForm = {};
         this.dataErrors = {};

         this.lang = (this.$parent as any).lang;
      }

      /**
       * Se ejecuta cuando el componente ha sido creado
       */
      created() {
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - Nov. 29 - 2020
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del dataform
         this.dataForm = {};
         // Limpia errores
         this.dataErrors = {};
         // Actualiza componente de refresco
         (this.$parent as any)._updateKeyRefresh();
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Nov. 29 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadKnowledgeForm(dataElement: object): void {
         // this.clearDataForm();
         if (dataElement) {
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);

            this.dataForm.ht_tic_requests_id = dataElement['id'];
            this.dataForm.component = true;
         }

         $(`#modal-form-${this.name}`).modal('show');
      }


      /**
       * Crea el formulario de datos para guardar
       *
       * @author Carlos Moises Garcia T. - Nov. 29 - 2020
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

            // Agrega elemento nuevo a la lista
            // (this.$parent as any).dataList.unshift(res.data.data);
            // Actualiza elemento modificado en la lista
            Object.assign((this.$parent as any)._findElementById(request.id, false), request);

            (this.$swal as any).close();

            // Cierra fomrulario modal
            $(`#modal-form-${this.name}`).modal('toggle');

            // Limpia datos ingresados
            this.clearDataForm();
            // Emite notificacion de almacenamiento de datos
            (this.$parent as any)._pushNotification(res.data.message);


         })
         .catch((err) => {
            (this.$swal as any).close();

            // Issues data storage notification
            (this.$parent as any)._pushNotification('Error al guardar los datos', false, 'Error');
            // Validate if there are errors associated with the form
            if (err.response.data.errors) {
               this.dataErrors = err.response.data.errors;
            }
         });
      }
   }

</script>
