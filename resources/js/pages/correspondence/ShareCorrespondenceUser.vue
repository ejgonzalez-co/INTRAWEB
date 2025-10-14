<template>
   <div>
      <!-- begin #modal-form -->
      <div class="modal fade" :id="`modal-form-${this.name}`">
         <div class="modal-dialog modal-xl">
            <form @submit.prevent="save()" :id="`form-${this.name}`">
               <div class="modal-content border-0">
                  <div class="modal-header bg-blue">
                     <h4 class="modal-title text-white">{{ titleModal }}</h4>
                     <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times text-white"></i></button>
                  </div>
                  <div class="modal-body">

                     <div class="row">

                        <div class="form-group row m-b-15">
                           <label for="share_users" class="col-form-label col-md-3">Compartir con:</label>
                           <div class="col-md-9">
                              <add-list-autocomplete
                                 :value="dataForm"
                                 name-prop="name"
                                 name-field-autocomplete="recipient_autocomplete"
                                 name-field="share_users"
                                 name-resource="/intranet/get-users"
                                 name-options-list="recipientsListShares"
                                 :name-labels-display="['name']"
                                 name-key="id"
                                 help="Ingrese el nombre del funcionario en la caja y seleccione para agregar a la lista"
                                 :key="keyRefresh">
                              </add-list-autocomplete>
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
      <!-- end #modal-form -->
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
   * Componente para compartir correspondencia con usuarios
   *
   * @author Carlos Moises Garcia T. - Abr. 23 - 2022
   * @version 1.0.0
   */
   @Component
   export default class ShareCorrespondenceUser extends Vue {

      /**
       * Nombre de la entidad a afectar
       */
      @Prop({ type: String, required: true })
      public name: string;

      /**
       * Titulo del modal
       */
      @Prop({ type: String, default: "Compartir correspondencia" })
      public titleModal: string;

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


      public edit(dataElement: object): void {
         this.dataForm = {};

         $(`#modal-form-${this.name}`).modal('show');

         // Valida si existe una ruta declarada para los detalles
         if ((this.$parent as any).resource.edit) {
            // Asigna elemento a mostrar
            this.dataForm = utility.clone(dataElement);
            // Obtiene el nombre del recurso
            const resource = (this.$parent as any).resource.edit
               ? (this.$parent as any).resource.edit
               : (this.$parent as any).resource.default;
            // Envia peticion de obtener todos los datos del recurso
            axios
               .get(
                  `${resource}/${dataElement[(this.$parent as any).customId]}`
               )
               .then(res => {
                  let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                  const dataDecrypted = Object.assign({data:{}}, dataPayload);

                  // Asigna elemento a mostrar
                  this.dataForm = dataDecrypted?.data;
                  // Habilita actualizacion de datos
                  this.isUpdate = true;
                  // Actualiza componente de refresco
                  this._updateKeyRefresh();
               })
               .catch(err => {
                  (this.$parent as any)._pushNotification(
                     this.lang.get("trans.Failed to get data list"),
                     false,
                     "Error"
                  );
               });
         } else {
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);
            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Habilita formulario
            // Actualiza componente de refresco
            this._updateKeyRefresh();
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
         // // Valida si los datos son para actualizar
         // if (this.isUpdate) {
         //    // Actualiza un registro existente
         //    // this.update();
         // } else {
            // Almacena un nuevo registro
            this.store();
         // }
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

            const dataDecrypted = Object.assign({data:{}}, dataPayload);

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
