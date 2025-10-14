<template>
   <div>
      <!-- begin #modal-form-external-receiveds -->
      <div class="modal fade" :id="`modal-form-${this.name}`">
         <div class="modal-dialog modal-xl">
               <form @submit.prevent="save()" :id="`form-${this.name}`">
                  <div class="modal-content border-0">
                     <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Correspondencia externa recibida</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                     </div>
                     <div class="modal-body">

                        <div class="card mb-4">
                           <div class="card-header">
                              <h6>Procedencia</h6>
                           </div>
                           <div class="card-body gray">
                              <div class="row">

                                 <div class="form-group col-sm-12">
                                    <label for="citizen_id">Ciudadano:</label>
                                    <autocomplete
                                       :is-update="isUpdate"
                                       :value-default="dataForm.citizens"
                                       name-prop="name"
                                       name-field="citizen_id"
                                       :value='dataForm'
                                       name-resource="/intranet/get-citizens"
                                       css-class="form-control"
                                       :name-labels-display="['document_number', 'name']"
                                       reduce-key="id"
                                       :function-change="validateCitizen"
                                       :key="keyRefresh"
                                    >
                                    </autocomplete>
                                    <small class="form-text text-muted">Ingrese el nombre o número de documento del ciudadano que desea relacionar.</small>
                                    <div class="invalid-feedback" v-if="dataErrors.citizen_id">
                                       <p class="m-b-0" v-for="error in dataErrors.citizen_id">@{{ error }}</p>
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-6">
                                    <label class="required" for="document_number">No. de documento:</label>
                                    <input type="text" id="document_number" :class="{'form-control':true}" v-model="dataForm.document_number" required>
                                    <small></small>
                                    <div class="invalid-feedback" v-if="dataErrors.first_name">
                                       <p class="m-b-0" v-for="error in dataErrors.first_name">@{{ error }}</p>
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-6">
                                    <label class="required" for="email">Correo electrónico:</label>
                                    <input type="email" id="email" :class="{'form-control':true}" v-model="dataForm.email" required>
                                    <small></small>
                                    <div class="invalid-feedback" v-if="dataErrors.email">
                                       <p class="m-b-0" v-for="error in dataErrors.email">@{{ error }}</p>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>

                        <div class="card mb-4">
                           <div class="card-header">
                              <h6>Destino</h6>
                           </div>
                           <div class="card-body gray">
                              <div class="row">

                                 <div class="form-group col-sm-12">
                                    <label class="required" for="functionary_id">Destinatario:</label>
                                    <autocomplete
                                       :is-update="isUpdate"
                                       :value-default="dataForm.functionaries"
                                       name-prop="name"
                                       name-field="functionary_id"
                                       :value='dataForm'
                                       name-resource="/intranet/get-functionaries"
                                       css-class="form-control"
                                       :name-labels-display="['name', 'dependency_name']"
                                       reduce-key="id"
                                       :key="keyRefresh"
                                    >
                                    </autocomplete>
                                    <small></small>
                                    <div class="invalid-feedback" v-if="dataErrors.functionary_id">
                                       <p class="m-b-0" v-for="error in dataErrors.functionary_id">@{{ error }}</p>
                                    </div>
                                 </div>

                                 <div class="form-group col-sm-12">
                                    <label class="required" for="functionary_id">Enviar copia a:</label>
                                    <add-list-autocomplete
                                       :value="dataForm"
                                       name-prop="name"
                                       name-field-autocomplete="search_field"
                                       name-field="external_received_copy_shares"
                                       name-resource="/intranet/get-functionaries"
                                       name-options-list="users"
                                       :name-labels-display="['name', 'dependency_name']"
                                       name-key="id"
                                       help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                       :key="keyRefresh">
                                    </add-list-autocomplete>
                                    <small></small>
                                    <div class="invalid-feedback" v-if="dataErrors.functionary_id">
                                       <p class="m-b-0" v-for="error in dataErrors.functionary_id">@{{ error }}</p>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>

                        <div class="card mb-4">
                           <div class="card-header">
                              <h6>Información general</h6>
                           </div>
                           <div class="card-body gray">
                              <div class="row">

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="type_documentary_id">Tipo documental:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="type_documentary_id"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-types-documentaries"
                                          :value="dataForm"
                                          :is-required="true">
                                       </select-check>
                                       <div class="invalid-feedback" v-if="dataErrors.type_documentary_id">
                                          <p class="m-b-0" v-for="error in dataErrors.type_documentary_id">{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-12">
                                    <label class="col-form-label col-md-3 required" for="issue">Asunto:</label>
                                    <div class="col-md-9">
                                       <textarea cols="50" rows="10" class="form-control" v-model="dataForm.issue"></textarea>
                                       <small></small>
                                       <div class="invalid-feedback" v-if="dataErrors.issue">
                                          <p class="m-b-0" v-for="error in dataErrors.issue">@{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="folios">Folios:</label>
                                    <div class="col-md-9">
                                       <input type="text" :class="{'form-control': true, 'is-invalid':dataErrors.folios }" v-model="dataForm.folios" required>
                                       <small></small>
                                       <div class="invalid-feedback" v-if="dataErrors.folios">
                                          <p class="m-b-0" v-for="error in dataErrors.folios">@{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="annexes">Anexos:</label>
                                    <div class="col-md-9">
                                       <input type="text" :class="{'form-control': true, 'is-invalid':dataErrors.annexes }" v-model="dataForm.annexes" required>
                                       <small></small>
                                       <div class="invalid-feedback" v-if="dataErrors.annexes">
                                          <p class="m-b-0" v-for="error in dataErrors.annexes">@{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="channel">Canal:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="channel"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-constants/external_received_channels"
                                          :value="dataForm"
                                          :is-required="true">
                                       </select-check>
                                       <div class="invalid-feedback" v-if="dataErrors.channel">
                                          <p class="m-b-0" v-for="error in dataErrors.channel">{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="attached_document">Documento adjunto:</label>
                                    <div class="col-md-9">
                                       <input type="file" accept="application/pdf" :class="{'form-control':true, 'is-invalid':dataErrors.attached_document }" @change="inputFile($event, 'attached_document')">
                                       <small></small>
                                       <div class="invalid-feedback" v-if="dataErrors.attached_document">
                                          <p class="m-b-0" v-for="error in dataErrors.attached_document">@{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-12">
                                    <label class="col-form-label col-md-3 required" for="novelty">Novedad:</label>
                                    <div class="col-md-9">
                                       <textarea cols="50" rows="10" class="form-control" v-model="dataForm.novelty"></textarea>
                                       <small></small>
                                       <div class="invalid-feedback" v-if="dataErrors.novelty">
                                          <p class="m-b-0" v-for="error in dataErrors.novelty">@{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <label class="col-form-label col-md-3 required" for="state">{{ 'trans.State' | trans }}:</label>
                                    <div class="col-md-9">
                                       <select-check
                                          css-class="form-control"
                                          name-field="state"
                                          reduce-label="name"
                                          reduce-key="id"
                                          name-resource="get-constants/external_received_states"
                                          :value="dataForm"
                                          :is-required="true">
                                       </select-check>
                                       <div class="invalid-feedback" v-if="dataErrors.state">
                                          <p class="m-b-0" v-for="error in dataErrors.state">{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group row m-b-15 col-sm-6">
                                    <!-- <label class="col-form-label col-md-3 required" for="npqr">¿Es PQRSD?:</label> -->
                                    <div class="col-md-9">
                                       <div class="custom-control custom-checkbox">
                                          <input
                                             type="checkbox"
                                             id="npqr"
                                             class="custom-control-input"
                                             v-model="dataForm.npqr">
                                          <label for="npqr" class="custom-control-label">Nuevo PQR</label>
                                       </div>
                                       <div class="invalid-feedback" v-if="dataErrors.npqr">
                                          <p class="m-b-0" v-for="error in dataErrors.npqr">{{ error }}</p>
                                       </div>
                                    </div>
                                 </div>

                              </div>
                           </div>
                        </div>

                     </div>
                     <div class="modal-footer">
                        <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                     </div>
                  </div>
               </form>
         </div>
      </div>
      <!-- end #modal-form-external-receiveds -->
   </div>
</template>
<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';

   import utility from '../../utility';

   import { Locale } from "v-calendar";

   const locale = new Locale();

   /**
   * Componente para agregrar las correspondencias externas recibidas
   *
   * @author Carlos Moises Garcia T. - Oct. 13 - 2020
   * @version 1.0.0
   */
   @Component
   export default class ExternalReceived extends Vue {
      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true })
      public name: string;

      /**
       * Errores del formulario
       */
      public dataErrors: any;

      /**
       * Datos del formulario
       */
      public dataForm: any;

      /**
       * Funcionalidades de traduccion de texto
       */
      public lang: any;

      /**
       * Valida si los valores del formulario
       * son para actualizar o crear
       */
      public isUpdate: boolean;

      /**
       * Key autoincrementable y unico para
       * ayudar a refrescar un componente
       */
      public keyRefresh: number;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Nov. 04 - 2020
       * @version 1.0.0
       */
      constructor() {
         super();

         this.dataErrors = {};
         this.dataForm   = {};
         this.isUpdate   = false;
         this.keyRefresh = 0;
         this.lang = (this.$parent as any).lang;
      }

      /**
       * Limpia los datos del fomulario
       *
       * @author Carlos Moises Garcia T. - Ene. 03 - 2022
       * @version 1.0.0
       */
      public clearDataForm(): void {
         // Inicializa valores del formulario
         this.dataForm = {
            citizen: {},
         };
         // Limpia errores
         this.dataErrors = {};
         // Actualiza componente de refresco
         this._updateKeyRefresh();
         // Limpia valores del campo de archivos
         $('input[type=file]').val(null);
      }

      /**
       * Evento de asignacion de archivo
       *
       * @author Carlos Moises Garcia T. - Abr. 17 - 2020
       * @version 1.0.0
       *
       * @param event datos de evento
       * @param fieldName nombre de campo
       */
      public inputFile(event, fieldName: string): void {
         this.dataForm[fieldName] = event.target.files[0];
      }

      /**
       * Cargar los datos
       *
       * @author Carlos Moises Garcia T. - Oct. 17 - 2020
       * @version 1.0.0
       *
       * @param dataElement elemento de grupo de trabajo
       */
      public loadData(dataElement: object): void {

         // Valida que exista datos
         if (dataElement) {
            // Asigna los valores
            let data = utility.clone(dataElement);

            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = data;

            // Actualiza componente de refresco
            this._updateKeyRefresh();
            $(`#modal-form-${this.name}`).modal('show');
         } else{
            this.isUpdate = false;
            $(`#modal-form-${this.name}`).modal('show');
         }
      }

      /**
       * Crea el formulario de datos para guardar
       *
       * @author Carlos Moises Garcia T. - Ene. 03 - 2022
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
       * @author Carlos Moises Garcia T. - Jul. 30 - 2021
       * @version 1.0.0
       */
      public save(): void {
         // Limpia los errores anteriores
         this.dataErrors = {};
         // Valida si los datos son para actualizar
         if (this.isUpdate) {
            // Actualiza un registro existente
            // this.update();
         } else {
            // Almacena un nuevo registro
            this.store();
         }
      }

      /**
       * Guarda la informacion del formulario dinamico
       *
       * @author Carlos Moises Garcia T. - Jul. 30 - 2021
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
         axios.post(this.name, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
         .then((res) => {

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

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
                  (this.$parent as any).addElementToList(dataDecrypted.data);
               }
               // Abre el swal de la respusta de la peticion
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get('trans.Accept')
               });
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

      /**
       * Valida si existe o no el usuario previamente registrador
       *
       * @author Carlos Moises Garcia T. - Ene. 03 - 2022
       * @version 1.0.0
       */
      public validateCitizen(data): void {
         // Valida que exista datos en el autocompletar usuarios
         if (data) {
            this.dataForm.document_number = data.document_number;
            this.dataForm.email = data.email;
            this._updateKeyRefresh();
            // this.dataForm.citizen_id = utility.clone(data.id);
         } else{
            // this.clearDataForm();
         }
      }

      /**
       * Actualiza el componente que utilize el key
       * cada vez que se cambia de editar a actualizar
       * y borrado de campos de formulario
       *
       * @author Carlos Moises Garcia T. - Jul. 06 - 2020
       * @version 1.0.0
       */
      private _updateKeyRefresh(): void {
         this.keyRefresh ++;
      }

   }
</script>
