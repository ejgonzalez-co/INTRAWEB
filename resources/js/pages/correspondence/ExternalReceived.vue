<script lang="ts">
   import { Component, Prop, Watch, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';

   import utility from '../../utility';

   import { Locale } from "v-calendar";
import { success } from "toastr";

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
      @Prop({ type: String, required: false, default: "external-receiveds" })
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
       * Datos del rotulo
       */
      public dataRotule: any;

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
       * Valida si radito
       * son para actualizar o crear
       */
      public radicatied: boolean;

      /**
       * Valida si el registro viene por
       * comunicaciones por correo
       */
      public communications: any;

      /**
      * Valida el estado del modal del formulario,
      * si habilita o cierra, se hace para recargar los elementos internos del formulario
      * cada vez que se habilita (Evita el keyrefresh para no sobrecargar la interfaz)
      */
      public openForm: boolean;

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
         this.dataRotule = {};
         this.isUpdate   = false;
         this.keyRefresh = 0;
         this.lang = (this.$parent as any).lang;
         this.radicatied = false;
         this.openForm = false;
         this.communications = {};
         // this.$set(this.dataForm, 'document_type', "Carta");

      }

      /**
       * Se ejecuta cuando el componente ha sido creado
       */
      created() {
         // Obtiene la instancia del crudComponent
         let crudComponent = (this.$parent as Vue);
            // recuperamos el querystring (parámetros enviados por URL)
            const querystring = window.location.search
            // usando el querystring, creamos un objeto del tipo URLSearchParams
            const params = new URLSearchParams(querystring)
            // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
            if(params.get('qder')) {
               // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
               let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qder'))));
               crudComponent["searchFields"]["external_received.id"] = consulta_dashboard;
               crudComponent["pageEventActualizado"](1);
               // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
               axios.get('get-external-receiveds-show-dashboard/'+consulta_dashboard)
                  .then((res) => {
                     let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                     const dataDecrypted = Object.assign({}, dataPayload);

                     // Si el estado del documento es público = 3, se debe de ver solo en vista de detalles
                     if(dataDecrypted["data"]["state"] == 3) {
                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        crudComponent["show"](dataDecrypted["data"]);
                        $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                     } else {
                        // Envia elemento a mostrar a la función edit
                        this.edit(dataDecrypted["data"]);
                        $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
                     }
                  })
                  .catch((err) => {
                     console.log("Error obteniendo información del registro desde el listado el dashboard")
                  });
            } else if(params.get('qderMailShra')){
               console.log("entro a qderMailShra");
               console.log(params.get('qderMailShra'));
               // Se obtiene el valor del parámtro qderMailShra (query dashboard entradas recientes), petición de las entradas recientes
               let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qderMailShra'))));
               console.log(consulta_dashboard);
               crudComponent["searchFields"]["consecutivo"] = consulta_dashboard;
               crudComponent["pageEventActualizado"](1);
               // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
               axios.get('get-correo-integrado-show-dashboard/'+consulta_dashboard)
                  .then((res) => {
                     let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                     const dataDecrypted = Object.assign({}, dataPayload);
                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        crudComponent["show"](dataDecrypted["data"]);
                        $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                  })
                  .catch((err) => {
                     console.log("Error obteniendo información del registro desde el listado el dashboard")
                  });
            }
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
            channel: 4, // 4= Personal
            state: 3, // 3 = Público, estado por defecto de la correspondencia recibida
            document_type : 'Carta'
         };
         // Limpia errores
         this.dataErrors = {};
         this.openForm = false;

         // Actualiza componente de refresco
         // this._updateKeyRefresh();
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

      public edit(dataElement: object): void {
        // Busca el elemento deseado y agrega datos al fomrulario
        this.dataForm = utility.clone(dataElement);
        this.radicatied = false;
        $(`#form-external-received-body button[id="radication-button"]`).tab(
            "show"
        );
        // Habilita actualizacion de datos
        this.isUpdate = true;
        this.openForm = true;

        // Actualiza componente de refresco
      //   this._updateKeyRefresh();
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
            // this._updateKeyRefresh();
            $(`#modal-form-${this.name}`).modal('show');
         } else{
            this.isUpdate = false;
            $(`#modal-form-${this.name}`).modal('show');
         }
      }

   /**
     * Crea el formulario de datos para guardar
     *
     * @author Erika Johana Gonzalez C. - Jun. 26 - 2020
     * @version 1.0.0
     */
     public makeFormData(): FormData {
        let formData = new FormData();
        // Recorre los datos del formulario
        for (const key in this.dataForm) {
            if (this.dataForm.hasOwnProperty(key)) {
                const data = this.dataForm[key];
                // Valida si no es un objeto y si es un archivo
                if (
                    typeof data != "object" ||
                    data instanceof File ||
                    data instanceof Date ||
                    data instanceof Array
                ) {
                    // Valida si es un arreglo
                    if (Array.isArray(data)) {
                        data.forEach(element => {
                            if (typeof element == "object") {
                                formData.append(
                                    `${key}[]`,
                                    JSON.stringify(element)
                                );
                            } else {
                                formData.append(`${key}[]`, element);
                            }
                        });
                    } else if (data instanceof Date) {
                        formData.append(
                            key,
                            locale.format(data, "YYYY-MM-DD hh:mm:ss")
                        );
                    } else {
                        formData.append(key, data);
                    }
                }
            }
        }
        return formData;
    }

   public makeFormDataCrud(crudComponent): FormData {
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
            this.update();
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

         (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_save"));

         // Construye los datos del formulario
         const formData: FormData = this.makeFormData();
         // Envia peticion de guardado de add
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
                  $(`#modal-form-${this.name}`).modal("toggle");

                  // Limpia datos ingresados
                  this.clearDataForm();
                  this.openForm = true;

                  // Agrega elemento nuevo a la lista
                  (this.$parent as any).dataList.unshift(dataDecrypted.data);
               }
               // Abre el swal de la respusta de la peticion
               this.$swal({
                  icon: res.data.type_message,
                  html: res.data.message,
                  allowOutsideClick: false,
                  confirmButtonText: this.lang.get("trans.Accept")
               });
            } else {
               // Cierra fomrulario modal
               // $(`#modal-form-${this.name}`).modal("toggle");

               this.dataRotule = dataDecrypted["data"];

               this.radicatied = true;
               $(`#form-external-received-body button[id="rotule-button"]`).tab(
                  "show"
               );
               // Posiciona el scroll del modal inmediatamente al inicio de la ventana
               $('#modal-form-external-receiveds').animate({scrollTop: 0}, 0);
               // Limpia datos ingresados
               this.clearDataForm();
               this.openForm = true;

               // Agrega elemento nuevo a la lista
               (this.$parent as any).dataList.unshift(dataDecrypted.data);

               (this.$parent as any).dataPaginator.total= (this.$parent as any).dataPaginator.total+1;


               // Calcula el numero de paginas del paginador
               (this.$parent as any).dataPaginator.numPages = Math.ceil((this.$parent as any).dataPaginator.total / (this.$parent as any).dataPaginator.pagesItems);
               (this.$parent as any).dataPaginator.currentPage=1;

               //llama a la funcion asigarData del RotuleComponent para mandarle lo que acaba de radicar
               (this.$refs as any)["rotulo_recibida_fields"].asginarData(dataDecrypted.data);

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


         /**
     * Actualiza la informacion en base de datos
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 17 - 2020
     * @version 1.0.0
     */
    public update(): void {
        // Abre el swal de guardando datos

        (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));

        const formData: FormData = this.makeFormData();
        formData.append("_method", "put");

        // Envia peticion de guardado de datos
        axios
            .post(`${this.name}/${this.dataForm["id"]}`, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

               const dataDecrypted = Object.assign({data:{external_received : null}}, dataPayload);

                // Cierra el swal de guardando datos
                (this.$swal as any).close();

                // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {
                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}`).modal("toggle");
                        // Agrega elemento nuevo a la lista
                        Object.assign(
                            (this.$parent as any)._findElementById(
                                this.dataForm["id"],
                                false
                            ),
                            dataDecrypted.data
                        );

                        // Limpia datos ingresados
                        this.clearDataForm();
                    }
                    // Abre el swal de guardando datos
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept")
                    });
                } else {
                     this.dataRotule = dataDecrypted["data"];
                     this.$set(this.dataForm, "document_type","Carta");

                     if(this.communications == "No oficial"){
                        this.radicatied = false;
                        Object.assign(
                              (this.$parent as any)._findElementById(
                                 this.dataForm["id"],
                                 false
                              ),
                              dataDecrypted.data
                        );
                           this.$swal({
                              icon: 'success',
                              html: 'Correo clasificado como no oficial con éxito',
                              allowOutsideClick: false,
                              confirmButtonText: this.lang.get("trans.Accept")
                        });
                        this.clearDataForm();
                     } else if(this.communications == "Clasificado"){
                        this.radicatied = false;
                        $(`#modal-form-${this.name}`).modal('hide');
                           Object.assign(
                              (this.$parent as any)._findElementById(
                                 this.dataForm["id"],
                                 false
                              ),
                              dataDecrypted.data
                        );
                        this.$swal({
                           icon: 'success',
                           html: 'Correo clasificado con éxito como correspondencia recibida',
                           allowOutsideClick: false,
                           confirmButtonText: this.lang.get("trans.Accept")
                     });
                     // this.openForm = false;
                     this.clearDataForm();
                     } else {
                        this.radicatied = true;
                        $(`#form-external-received-body button[id="rotule-button"]`).tab(
                           "show"
                     );
                        // Posiciona el scroll del modal inmediatamente al inicio de la ventana
                        $('#modal-form-external-receiveds').animate({scrollTop: 0}, 0);
                        // Agrega elemento nuevo a la lista
                        Object.assign(
                              (this.$parent as any)._findElementById(
                                 this.dataForm["id"],
                                 false
                              ),
                              dataDecrypted.data
                        );

                        // Limpia datos ingresados
                        // this.clearDataForm();
                        // Emite notificacion de almacenamiento de datos
                        (this.$parent as any)._pushNotification(res.data.message);

                        //llama a la funcion asigarData del RotuleComponent para mandarle lo que acaba de radicar
                        (this.$refs as any)["rotulo_recibida_fields"].asginarData(dataDecrypted.data);

                     }


                }

                // Valida que se retorne los datos desde el controlador
                // if (res.data.data) {
                //    // Actualiza elemento modificado en la lista
                //    Object.assign((this.$parent as any)._findElementById(this.dataForm['id'], false), res.data.data);
                // }
            })
            .catch(err => {
                (this.$swal as any).close();

                let errors = "";

                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                    // Reocorre la lista de campos del formulario
                    for (const key in this.dataForm) {
                        // Valida si existe un error relacionado al campo
                        if (err.response.data.errors[key]) {
                            // Agrega el error a la lista de errores
                            errors += "<br>" + err.response.data.errors[key][0];
                        }
                    }
                } else if (err.response.data) {
                    errors += "<br>" + err.response.data.message;
                }
                // Abre el swal para mostrar los errores
                this.$swal({
                    icon: "error",
                    html: this.lang.get("trans.Failed to save data") + errors,
                    allowOutsideClick: false
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
            this.$set(this.dataForm, 'document_number', data.document_number);
            this.$set(this.dataForm, 'email', data.email);

            // this.dataForm.document_number = data.document_number;
            // this.dataForm.email = data.email;
            // this._updateKeyRefresh();
            // this.dataForm.citizen_id = utility.clone(data.id);
         } else{
            // this.clearDataForm();
         }
      }

      /**
       * Oculta la pestaña de rótulo y muestra la pestaña de radicación por defecto cada vez que vaya a radicar una correspondencia
       */
      public add(): void {
         this.clearDataForm();
         this.isUpdate = false;

         this.$set(this.dataForm, 'channel','4');

         this.radicatied = false;
         $(`#form-external-received-body button[id="radication-button"]`).tab(
            "show"
         );
         this.openForm = true;

         // Actualiza componente de refresco
         // this._updateKeyRefresh();
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


      public adjuntarDocumento(event: any): void {

         // Obtiene del id de la correspondencia del datashow, cuando el rotulo es de los detalles (se abrió desde la acción)
         let correspondenceId = (this.$parent as any)["dataShow"]["id"];
         // Si no se abrió el rótulo desde la acción, es porque se abrió desde una creación de una correspondencia
         if(correspondenceId == undefined) {
            correspondenceId = this.dataRotule.id;
         }

         // Almacena el objeto de tipo file adjuntado en el rótulo
         this.dataForm["document_pdf"] = event.target.files[0];
         if(event.target.files[0]){


            (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));


         // Envia peticion de guardado de datos
         axios
            .post("attach-received-label/"+correspondenceId, this.makeFormData(), {
               headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

               const dataDecrypted = Object.assign({data:{}}, dataPayload);

               // Cierra el swal de guardando datos
               (this.$swal as any).close();
               // Valida el tipo de alerta que de debe mostrar
               if (res.data.type_message) {
                  // Abre el swal de la respuesta de la peticion
                  this.$swal({
                     icon: res.data.type_message,
                     html: res.data.message,
                     allowOutsideClick: false,
                     confirmButtonText: this.lang.get("trans.Accept")
                  });
               } else {
                  // Emite notificacion de almacenamiento de datos
                  (this.$parent as any)['_pushNotification'](res.data.message);
               }

               // Valida que se retorne los datos desde el controlador
               if (dataDecrypted.data) {
                  // Actualiza elemento modificado en la lista
                  Object.assign((this.$parent as any)._findElementById(correspondenceId, false), dataDecrypted?.data);
               }
            })
            .catch(err => {
               (this.$swal as any).close();

               let errors = "";

               // Valida si hay errores asociados al formulario
               if (err.response.data.errors) {
                  this.dataErrors = err.response.data.errors;
                  // Reocorre la lista de campos del formulario
                  for (const key in this.dataForm) {
                     // Valida si existe un error relacionado al campo
                     if (err.response.data.errors[key]) {
                           // Agrega el error a la lista de errores
                           errors += "<br>" + err.response.data.errors[key][0];
                     }
                  }
               } else if (err.response.data) {
                  errors += "<br>" + err.response.data.message;
               }
               // Abre el swal para mostrar los errores
               this.$swal({
                  icon: "error",
                  html: this.lang.get("trans.Failed to save data") + errors,
                  confirmButtonText: this.lang.get("trans.Accept"),
                  allowOutsideClick: false
               });
            });

         }
      }


    /**
     * Asignar leido correspondencia
     *
     * @author Erika Johana Gonzalez C. Abril 14 - 2023
     * @version 1.0.0
     *
     * @param id ID de la correspondencia
     */
    public read(id: number): void {
        // Envia peticion de obtener todos los datos del recurs
        axios.post('external-received-read/'+id)
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

            // Agrega el elemento nuevo a la lista
            Object.assign((this.$parent as any)._findElementById(id, false), dataDecrypted["data"]);

            // Visualiza toast positivo
        })
        .catch((err) => {
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia', false, 'Error');
        });
    }

      public callFunctionComponent(ref,data): void {
        (this.$parent as Vue)['callFunctionComponent'](ref,data);

      }

      /**
      * Guarda la informacion en base de datos
      *
      * @author German Gonzalez V. - Abr. 20 - 2023
      * @version 1.0.0
      */
      public addCiudadano(): void {
         // Abre el swal de guardando datos

         (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_save"));

         // Obtiene la instancia del crudComponent
         let crudComponent = (this as Vue);

         // Envia peticion de guardado de datos
         axios.post("citizens", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
         .then((res) => {
            // Cierra el swal de guardando datos
            (this.$swal as any).close();

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({
               data: {
                  name: 0,
                  email: 0,
                  document_number: 0,
                  user_id: 0,
                  id: 0,
               },
            }, dataPayload);

            if(res.data.type_message != "error") {
               // Limpia datos ingresados
               this.clearDataFormPQRCiudadano(crudComponent);
               jQuery('#crear_ciudadano').hide(350);
               jQuery('#citizen_id').focus();
               jQuery('#citizen_id').val(dataDecrypted.data.name);
               jQuery('#citizen_name').val(dataDecrypted.data.name);
               jQuery('#citizen_email').val(dataDecrypted.data.email);
               jQuery('#citizen_document').val(dataDecrypted.data.document_number);

               crudComponent["dataForm"]["citizen_id"] = dataDecrypted.data.user_id;
               crudComponent["dataForm"]["citizen_name"] = dataDecrypted.data.name;
               crudComponent["dataForm"]["citizen_email"] = dataDecrypted.data.email;
               crudComponent["dataForm"]["citizen_document"] = dataDecrypted.data.document_number;

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
       * Limpia los datos del fomulario
       *
       * @author German Gonzalez V. Abril 14 - 2023
       * @version 1.0.0
       */
      public clearDataFormPQRCiudadano(crudComponent): void {

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
       * Clasifica las comunicaciones obtenidas de una cuenta de correo previamente
       * @param dataElement datos de la comunicación que se van a clasificar
       */
      public clasificarComunicacion(dataElement: object): void {
         // Limpia el dataForm
         this.clearDataForm();
         // Habilita actualizacion de datos
         this.isUpdate = true;
         //Asigna valores que se necesitan
         this.$set(this.dataForm, 'citizen_email', dataElement["correo_remitente"]);
         this.$set(this.dataForm, 'citizen_name', dataElement["nombre_remitente"]);
         this.$set(this.dataForm, 'issue', dataElement["asunto"]);
         this.$set(this.dataForm, 'channel', "2");
         // Asigna el id de la comunicación para su posterior edición
         this.$set(this.dataForm, 'id', dataElement["id"]);

         this.$swal({
            input: 'select',
            icon: 'info',
            html: 'Por favor, seleccione la clasificación de esta comunicación.',
            showCancelButton: true,
            customClass: {
               input: 'form-control',
               popup: 'my-custom-popup-class' // Agrega una clase personalizada para el diálogo
            },
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#DD3333',
            confirmButtonText: 'Clasificar comunicación',
            cancelButtonText: 'Cancelar',
            inputOptions: {
               '1': 'Clasificar como correspondencia',
               '2': 'Clasificar como comunicación no oficial'
            }
         }).then((result) => {
                     // Valida que si haya seleccionado la opción de "Clasificar comunicación"
                     if (result.value) {
                        // 1 = Si la selección es clasificar la comunicación como una correspondencia
                        if (result.value === '1') {
                           this.communications = "Clasificado";
                            this.openForm = true;
                           // Muesta el formulario de radicación de correspondencias recibidas
                           $('#modal-form-'+this.name).modal('show');
                           // Asigna el valor de correspondencia a la variable de clasificación
                           // this.dataForm.clasificacion = "Correspondencia";
                           this.$set(this.dataForm, 'clasificacion', "Correspondencia");
                           // this.$set(, 'clasificacion', "Correspondencia");


                        } else if (result.value === '2') {
                           this.communications = 'No oficial';
                           // Asigna el valor de Comunicación no oficial a la variable de clasificación
                           // this.dataForm.clasificacion = "Comunicación no oficial";
                           this.$set(this.dataForm, 'clasificacion', "Comunicación no oficial");

                           // Realiza la actualización de la comunicación con una petición axios al servidor
                           this.update();
                        }
                     }
                  });
         }

      /**
      * Esta función toma un texto como entrada y devuelve una versión truncada si la longitud del texto supera un máximo especificado.
      * Si el texto no es válido (undefined, null o una cadena vacía), devuelve una cadena vacía.
      *
      * @param {string} text - El texto que se va a truncar.
      * @returns {string} - El texto truncado, con una longitud máxima de 30 caracteres seguido de puntos suspensivos, si es necesario.
      *                    Si el texto no es válido, devuelve una cadena vacía.
      */
      private shortText(text) {
            if (typeof text !== 'undefined' && text !== null && text.trim() !== '') {
                const maxLength = 30;
                if (text.length > maxLength) {
                    return { text: text.substring(0, maxLength), isLong: true };
                } else {
                    return { text: text, isLong: false };
                }
            } else {
                return { text: '', isLong: false };
            }
        }

    //Función que permite cargar el tablero con el consolidado de comunicaciones
    public verTableroConsolidado(){

        //Obtiene el texto de botón que muestra el tablero
        var texto_btn_consolidado = jQuery("#text_btn_consolidado").text();
        //Condición que valida cuando el usuario desea ver el tablero
        if (texto_btn_consolidado == "Ver consolidado de comunicaciones") {

        var totalComunicaciones = jQuery(".total").text();
        //Condición que valida que la vigencia no este vacia
        if (totalComunicaciones == "" || totalComunicaciones == "0") {
            //Se hace el llamado a la función que permite cargar los totales al tablero
            this._getWidgetsValues();

            //Código que muestra el tablero
            jQuery(".spinner").show();


        }

        //Código que muestra el tablero
        jQuery("#contenedorTableConsolidado").show("slow");

        //Código que cambia el texto del botón que muestra u oculta el tablero
        jQuery("#text_btn_consolidado").text("Ocultar consolidado de comunicaciones");
        }
        //Condición que valida cuando el usuario desea ocultar el tablero
        else{
        //jQuery("#cond").val("");

        //Código que muestra el tablero
        jQuery("#contenedorTableConsolidado").hide("slow");

        //Código que cambia el texto del botón que muestra u oculta el tablero
        jQuery("#text_btn_consolidado").text("Ver consolidado de comunicaciones");
        }
    }

    /**
     * Obtiene la información del tablero (widgets)
     *
     * @author Seven Soluciones Informáticas. Nov 21 - 2024
     * @version 1.0.0
     */
    private _getWidgetsValues(): void {
        // Envia peticion de obtener todos los datos del consolidado de comunicaciones
      if ((this.$parent as any)["dataWidgets"] && !(this.$parent as any)["dataWidgets"].hasOwnProperty("estados")) {
         jQuery("#contenedorTableConsolidado").css("opacity", .4);
      }

        axios.get('get-correo-integrados-tablero')
        .then((res) => {

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
            this.$set((this.$parent as any)["dataWidgets"], "estados", {
                'total': dataPayload["data"]?.total ?? 0,
                'sin_clasificar': dataPayload["data"]?.sin_clasificar ?? 0,
                'clasificado_correspondencia': dataPayload["data"]?.clasificado_correspondencia ?? 0,
                'clasificado_pqrsd': dataPayload["data"]?.clasificado_pqrsd ?? 0,
                'comunicacion_no_oficial': dataPayload["data"]?.comunicacion_no_oficial ?? 0
            });
            //Código que muestra el tablero
            jQuery("#contenedorTableConsolidado").css("opacity", 1);
            jQuery(".spinner").hide();
        })
        .catch((err) => {
            (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
        });
    }

}
</script>
<style>
.my-custom-popup-class {
    border-radius: 10px; /* Ajusta el valor según tus preferencias */
}

.estado_publico {
      background-color: #8BC34A;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

.estado_devuelto{
   background-color: #FF9800;
   color: white;
   border-radius: 3px;
   padding: 5px;
}
</style>
