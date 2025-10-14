<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

import utility from "../../utility";

import { Locale } from "v-calendar";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Erika Johana Gonzalez Cuartas - Abril. 13 - 2023
 * @version 1.0.0
 */
@Component
export default class ExternalComponent extends Vue {
    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: false, default: "externals" })
    public name: string;

    /**
     * Valida si el usuario en sesion tiene la segunda contraseña activa
     */
     @Prop({ type: Boolean, required: false, default: false })
    public secondPassword: string;

    /**
     * Datos del formulario
     */
    public dataForm: any;

    /**
     * Datos del rotulo
     */
    public dataRotule: any;

    /**
     * Errores del formulario
     */
    public dataErrors: any;

    /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;

    public componentKey: number;

    /**
     * Valida si los valores del formulario
     * son para actualizar o crear
     */
    public isUpdate: boolean;

    /**
     * Valida si radito
     * son para actualizar o crear
     */
    public radicatied: boolean;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;


    /**
     * Nombre de la entidad a afectar
     */
    //  @Prop({ type: String, required: false, default: "internals" })
    public btnResizeEditor: string = "Maximizar editor";

     /**
     * Estado de la accion de vista Previa
     */
     public btnvistaprevia:string = "Vista previa";
     
    /**
     * Valida si radito
     * son para actualizar o crear
     */
    public selectTipoValidate: boolean = false;

    // Variable para controlar el formulario que se va a ejecutar
    public formOpen: string;

    /**
     * Detecta el estado de carga de los archivos, si se ha terminado o no de cargar por completo los adjuntos del componente InputCrudFile
     */
    public uploadingFileInputCrudFile: boolean;


    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 13 - 2020
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
        this.componentKey = 0;
        this.dataForm = { citizens: [] };

        this.dataRotule = {};

        this.dataErrors = {};
        this.isUpdate = false;
        this.radicatied = false;
        this.lang = (this.$parent as any).lang;
        this.formOpen = "";
        this.uploadingFileInputCrudFile = false;
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
         // Valida si el parámetro qd (query dashboard), petición de los widgets, tiene algo quiere decir que viene a petición del dashboard
         if(params.get('qd')) {
            // Se obtiene el valor del parámtro qd (query dashboard) y se decodifíca
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qd'))));
            crudComponent["searchFields"]["origen"] = consulta_dashboard;
            crudComponent["pageEventActualizado"](1);
         } else if(params.get('qsb')) {
            // Se obtiene el valor del parámtro qsb (query sidebar), petición de la barra lateral y se decodifíca
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qsb'))));
            crudComponent["searchFields"]["state"] = consulta_dashboard;
            crudComponent["pageEventActualizado"](1);
         } else if(params.get('qder')) {
            // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
            let consulta_dashboard = decodeURIComponent(escape(atob(params.get('qder'))));
            crudComponent["searchFields"]["correspondence_external.id"] = consulta_dashboard;
            crudComponent["pageEventActualizado"](1);
            // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
            axios.get('get-externals-show-dashboard/'+consulta_dashboard)
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({data:{ state : null, origen : null, permission_edit: null}}, dataPayload);

                    // this.$set(this.dataForm, 'citizens', []);


                    // Si el estado del documento es público o pendiente de firma, se debe de ver solo en vista de detalles
                    if((dataDecrypted.data.state == "Público" || dataDecrypted.data.state == "Pendiente de firma") || dataDecrypted.data.permission_edit == false) {
                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        crudComponent["show"](dataDecrypted.data);
                        $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                    } else if(dataDecrypted.data.origen != "FISICO") {
                        // Envia elemento a mostrar a la función loadExterna
                        this.loadExterna(dataDecrypted.data);
                    } else {
                        // Envia elemento a mostrar a la función edit
                        this.edit(dataDecrypted.data);
                        $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
                    }
                })
                .catch((err) => {
                        console.log("Error obteniendo información del registro desde el listado el dashboard")
                });
         }
    }

    /**
     * Limpia los datos del fomulario
     *
     * @author Erika Johana Gonzalez C. - Abr. 15 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this.dataForm = {};

        // Inicializa valores del datashow
        (this.$parent as any)["dataShow"] = {};
        // Limpia errores
        this.dataErrors = {};
        this.formOpen = "";
        this.$set(this.dataForm, 'external_all', '0');
        this.$set(this.dataForm, 'require_answer', 'No');
        this.$set(this.dataForm, 'have_assigned_correspondence_received', 'No');
        this.$set(this.dataForm, 'annexes_digital', null);
        this.btnvistaprevia = "Vista previa";

        // Actualiza componente de refresco
        // this._updateKeyRefresh();
        // Limpia valores del campo de archivos
        $("input[type=file]").val(null);
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
      * Actualiza el componente que utilize el key
      * cada vez que se cambia de editar a actualizar
      * y borrado de campos de formulario
      *
      * @author Jhoan Sebastian Chilito S. - Jul. 06 - 2020
      * @version 1.0.0
      */
      private _updateKeyRefreshCrudComponent(): void {
         (this.$parent as Vue)["keyRefresh"] ++;
      }

    /**
     * Guarda los datos del formulario
     *
     * @author Erika Johana Gonzalez C. - Abr. 14 - 2020
     * @version 1.0.0
     */
    public save(): void {

        // alert(this.dataForm.external_recipients);
        // alert(this.dataForm.external_all);

        if(false){
            this.$swal({
                icon: "error",
                html: "Por favor ingrese al menos un destinatario de la correspondencia",
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                   $(`#citizen_id`).focus();
                }
                });

        }else{
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

    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 17 - 2020
     * @version 1.0.0
     */
    public store(): void {

        (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_save"));

        // Envia peticion de guardado de datos
        axios
            .post(this.name, this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
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
                        this.formOpen = "centralizada";

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
                    $(`#form-external-body button[id="rotule-button"]`).tab(
                        "show"
                    );
                    // Posiciona el scroll del modal inmediatamente al inicio de la ventana
                    $('#modal-form-externals').animate({scrollTop: 0}, 0);
                    // Limpia datos ingresados
                    this.clearDataForm();
                    this.formOpen = "centralizada";
                    this.$set(this.dataForm, 'citizens', []);

                    // Agrega elemento nuevo a la lista
                    (this.$parent as any).dataList.unshift(dataDecrypted.data);

                    (this.$parent as any).dataPaginator.total= (this.$parent as any).dataPaginator.total+1;


                    // Calcula el numero de paginas del paginador
                    (this.$parent as any).dataPaginator.numPages = Math.ceil((this.$parent as any).dataPaginator.total / (this.$parent as any).dataPaginator.pagesItems);
                    (this.$parent as any).dataPaginator.currentPage=1;


                    //llama a la funcion asigarData del RotuleComponent para mandarle lo que acaba de radicar
                    (this.$refs as any)["rotulo_enviada_fields"].asginarData(dataDecrypted.data);

                    // Emite notificacion de almacenamiento de datos
                    (this.$parent as any)._pushNotification(res.data.message);
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
                    allowOutsideClick: false
                });
            });
    }

    validateAndSubmit() {
        if (!this.dataForm.tipo){
            this.$swal({
                icon: "info",
                html: "Por favor, elija qué desea hacer con este documento usando los botones de estado de la correspondencia.",
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                   $(`#estados_label`).focus();
                }
                });
        return;
        }
        // Si el campo es válido, procede a enviar el formulario
        this.save();
    }
  /**
     * Actualiza la informacion en base de datos
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 17 - 2020
     * @version 1.0.0
     */
     public update(): void {
        // Valida si la acción a realizar es publicar el documento y si esta activa la opción de solicitud de segunda contraseña
        if (this.dataForm.tipo === 'Publicación' && this.secondPassword) {
            // Abre el swal indicándole al usuario que ingrese la segunda contraseña
            this.$swal({
                title: "Ingrese su segunda contraseña para la publicación del documento",
                allowOutsideClick: false,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                input: 'password',
                inputPlaceholder: 'Ingrese la segunda contraseña',
                showCancelButton: true,
                target: '#modal-form-externals-ceropapeles',
                inputValidator: (value) => {
                    // Valida el ingreso de la segunda contraseña en la caja de texto del swal
                    if (!value) {
                        return 'Por favor ingrese la segunda contraseña';
                    }
                },
                preConfirm: (res) => {
                    // Abre el swal indicando al usuario que validará la segunda contraseña con el sistema

                    (this.$parent as any).showLoadingGif("Validando contraseña");

                    let formData = new FormData();
                    // Añade al formData la clave y el valor de la segunda contraseña ingresada por el usuario
                    formData.append("second_password_publish", res);
                    // Realiza la petición al servidor para validar la segunda contaseña ingresada
                    axios.post('validar-second-password', formData, { headers: { "Content-Type": "multipart/form-data" }})
                    .then(respuesta => {
                        /* Si second_password_validate = true, las contraseñas coinciden y continua el flujo,
                         * de lo contrario indica al usuario que las contraseñas no coinciden
                         */
                        let dataPayload = jwtDecode(respuesta.data.data);

                        const dataDecrypted = Object.assign({data:{second_password_validate:null}}, dataPayload);
                        if(dataDecrypted.data.second_password_validate) {
                            // Invoca la función para actualizar la correspondencia
                            this.actualizarCorrespondencia();
                        } else {
                            // Abre el swal indicando que la segunda contraseña ingresada es incorrecta
                            this.$swal({
                                icon: "error",
                                html: "La contraseña que has ingresado no es válida. Por favor, inténtalo de nuevo.",
                                allowOutsideClick: false,
                                confirmButtonText: this.lang.get("trans.Accept")
                            });
                        }
                    })
                },
            });
        } else {
            // Invoca la función para actualizar la correspondencia
            if(this.dataForm.origen=="DIGITAL"){
                this.$swal({
                    title: 'Actualización de Correspondencia Enviada',
                    html:this.confirmationMessage(this.dataForm),
                    allowOutsideClick: false,
                    confirmButtonText: "Aceptar",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    icon: "info",
                    preConfirm: async () => {
                        // Invoca la función para actualizar la correspondencia
                        this.actualizarCorrespondencia();
                    }
                });
            }else{

                // Invoca la función para actualizar la correspondencia
                this.actualizarCorrespondencia();
            }
        }
     }

    /**
     * Genera un mensaje de confirmación utilizando los datos del formulario.
     *
     * @param {Object} dataForm - Objeto que contiene los datos del formulario.
     */
    public confirmationMessage(dataForm) : string{
        // Almacena el mensaje con la validacion de si tiene o no pqr asociada
        let message_confirmation_text = dataForm.tipo === "Publicación" && dataForm.pqr_consecutive != null ? "¿Está seguro de que desea cambiar el estado del documento a --tipo--? Este documento, identificado como --type_document--, --title--, con el consecutivo --consecutive-- y finalizará la PQR --pqr_consecutive--." : "¿Está seguro de que desea cambiar el estado del documento a --tipo--? Este documento, identificado como --type_document--, --title--, con el consecutivo --consecutive--.";

        // Expresión regular para encontrar las variables
        let regex = /--(.*?)--/g;

        // Array para almacenar las variables encontradas
        let variables = [];
        let match;

        // Buscar todas las coincidencias y almacenarlas en el array de variables
        while ((match = regex.exec(message_confirmation_text)) !== null) {
            variables.push(match[1]);
        }

        // Reemplazar las variables encontradas con sus valores correspondientes
        let mensaje = message_confirmation_text;
        variables.forEach(variable => {
            mensaje = mensaje.replace(new RegExp(`--${variable}--`, 'g'), dataForm[variable] || '');
        });

        return mensaje;
    }

    /**
     * Función para actualizar la correspondencia
     */

     public actualizarCorrespondencia(): void {

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
        // Abre el swal de guardando datos
        (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));

        const formData: FormData = this.makeFormData();
        formData.append("_method", "put");

        axios.post(`${this.name}/${this.dataForm["id"]}`, formData, {
            headers: { "Content-Type": "multipart/form-data" }
        }).then(res => {
            (this.$swal as any).close();

            if (res.data.type_message) {
                if (res.data.type_message === "success") {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({data:{editor:null}}, dataPayload);

                    $(`#modal-form-${this.name}`).modal("toggle");

                    Object.assign(
                        (this.$parent as any)._findElementById(
                            this.dataForm["id"],
                            false
                        ),
                        dataDecrypted.data
                    );


                    //llama a la funcion asigarData del RotuleComponent para mandarle lo que acaba de radicar
                    (this.$refs as any)["rotulo_enviada_fields"].asginarData(dataDecrypted.data);
                }



                this.$swal({
                    icon: res.data.type_message,
                    html: res.data.message,
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });
            } else {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({data:{editor:null}}, dataPayload);

                if (dataDecrypted.data.editor) {
                    $(`#modal-form-${this.name}-ceropapeles`).modal("toggle");
                }

                this.dataRotule = dataDecrypted.data;
                this.radicatied = true;

                $(`#form-external-body button[id="rotule-button"]`).tab("show");
                $('#modal-form-externals').animate({scrollTop: 0}, 0);

                Object.assign(
                    (this.$parent as any)._findElementById(
                        this.dataForm["id"],
                        false
                    ),
                    dataDecrypted.data
                );


                //llama a la funcion asigarData del RotuleComponent para mandarle lo que acaba de radicar
                (this.$refs as any)["rotulo_enviada_fields"].asginarData(dataDecrypted.data);
                (this.$parent as any)._pushNotification(res.data.message);
            }
        }).catch(err => {
            (this.$swal as any).close();

            let errors = "";

            if (err.response.data.errors) {
                this.dataErrors = err.response.data.errors;

                for (const key in this.dataForm) {
                    if (err.response.data.errors[key]) {
                        errors += "<br>" + err.response.data.errors[key][0];
                    }
                }
            } else if (err.response.data) {
                errors += "<br>" + err.response.data.message;
            }

            this.$swal({
                icon: "error",
                html: this.lang.get("trans.Failed to save data") + errors,
                allowOutsideClick: false
            });
        });
    }



    /**
     * Actualiza el componente que utilize el key
     * cada vez que se cambia de editar a actualizar
     * y borrado de campos de formulario
     *
     * @author Erika Johana Gonzalez C. - Jul. 06 - 2020
     * @version 1.0.0
     */
    private _updateKeyRefresh(): void {
        this.keyRefresh++;
    }

    public add(): void {
        this.clearDataForm();
        this.isUpdate = false;
        // this.dataForm.external_all = '0';
        // this.dataForm.require_answer = 'No';
        this.$set(this.dataForm, 'external_all', '0');
        this.$set(this.dataForm, 'require_answer', 'No');
        this.$set(this.dataForm, 'have_assigned_correspondence_received', 'No');
        this.$set(this.dataForm, 'annexes_digital', null);

        this.$set(this.dataForm, 'citizens', []);

        this.radicatied = false;
        $(`#form-external-body button[id="radication-button"]`).tab(
            "show"
        );
        this.$set(this.dataForm, 'channel','4');

        this.formOpen = "centralizada";

    }

    /**
     * Busca y prepara los datos a editar
     *
     * @author Erika Johana Gonzalez C. - Abr. 15 - 2020
     * @version 1.0.0
     *
     * @param dataElement elemento a editar
     */
    public edit(dataElement: object): void {
        // Busca el elemento deseado y agrega datos al fomrulario
        this.dataForm = utility.clone(dataElement);
        this.radicatied = false;
        $(`#form-external-body button[id="radication-button"]`).tab(
            "show"
        );
        // Habilita actualizacion de datos
        this.isUpdate = true;
        // Actualiza componente de refresco
        // this._updateKeyRefresh();
        this.formOpen = "centralizada";

    }


    public adjuntarDocumento(event: any): void {
    // alert(event);

        // Obtiene del id de la correspondencia del datashow, cuando el rotulo es de los detalles (se abrió desde la acción)
        let idCorrespondence = (this.$parent as any)["dataShow"]["id"];
        // Si no se abrió el rótulo desde la acción, es porque se abrió desde una creación de una correspondencia

        if(idCorrespondence == undefined) {
            idCorrespondence = this.dataRotule.id;
        }
        // Almacena el objeto de tipo file adjuntado en el rótulo
        // this.dataForm["document_pdf"] = event.target.files[0];
        this.$set(this.dataForm, 'document_pdf', event.target.files[0]);
        // console.log(event.target.files[0])
        if(event.target.files[0]){


            (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));
            
        // Envia peticion de guardado de datos
        axios
            .post("adjuntar_rotulo/"+idCorrespondence, this.makeFormData(), {
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
                    this.$set(this.dataForm, 'document_pdf', null);

                } else {
                    // Emite notificacion de almacenamiento de datos
                    (this.$parent as any)['_pushNotification'](res.data.message);
                    this.$set(this.dataForm, 'document_pdf', null);

                }

                // Valida que se retorne los datos desde el controlador
                if (dataDecrypted.data) {
                    this.$set(this.dataForm, 'document_pdf', null);

                   // Actualiza elemento modificado en la lista
                   Object.assign((this.$parent as any)._findElementById(idCorrespondence, false), dataDecrypted?.data);
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
                this.$set(this.dataForm, 'document_pdf', null);

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
        axios.post('external-read/'+id)
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
            // console.log("Corrrespondencia leida con éxito");
        })
        .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia', false, 'info');
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
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({
               data: {
                  name: 0,
                  email: 0,
                  document_number: 0,
                  id: 0,
                  states_id: 0,
                  city_id: 0,
               },
            }, dataPayload);

            // Cierra el swal de guardando datos
            (this.$swal as any).close();
            if(res.data.type_message != "error") {
               // Limpia datos ingresados
               this.clearDataFormPQRCiudadano(crudComponent);
               jQuery('#crear_ciudadano').hide(350);
               jQuery('#crear_ciudadano_cero').hide(350);

               jQuery('#citizen_id*').focus();
               jQuery('#citizen_id*').val(dataDecrypted.data.name);
               jQuery('#citizen_name*').val(dataDecrypted.data.name);
               jQuery('#citizen_email*').val(dataDecrypted.data.email);
               jQuery('#citizen_document*').val(dataDecrypted.data.document_number);
               jQuery('#department_id*').val(dataDecrypted.data.states_id);
               jQuery('#city_id*').val(dataDecrypted.data.city_id);

               crudComponent["dataForm"]["citizen_id"] = dataDecrypted?.data.id;
               crudComponent["dataForm"]["citizen_name"] = dataDecrypted?.data.name;
               crudComponent["dataForm"]["citizen_email"] = dataDecrypted.data.email;
               crudComponent["dataForm"]["citizen_document"] = dataDecrypted.data.document_number;
               crudComponent["dataForm"]["department_id"] = dataDecrypted?.data.states_id;
               crudComponent["dataForm"]["city_id"] = dataDecrypted?.data.city_id;

                (this.$refs["ciudadanoRef"] as Vue)["_selectMatch"](dataDecrypted.data);

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
            console.log(err.response.data);
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
     * Create la correspondencia externa junto con la plantilla de Google
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
     * @version 1.0.0
     *
     */

     public createExterna(): void {
        (this.$parent as any).showLoadingGif("Creando documento, Por favor espere un momento.");

        axios.post("crear-externa-ceropapeles", this.makeFormData(), {
            headers: { "Content-Type": "multipart/form-data" }
        }).then(res => {
            if (res.data.type_message === 'info') {
                this.$swal({
                    icon: res.data.type_message,
                    html: res.data.message,
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });
            } else {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({
                    data: {
                        title: 0,
                        template: 0,
                        type: 0,
                        id: 0,
                        dependencias_id: 0,
                        from_id: 0,
                        origen: 0,
                        consecutive: 0,
                        from: 0,
                        elaborated_names: 0,
                        users: 0
                    },
                }, dataPayload);

                this.formOpen = "ceropapelesEditar";
                (this.$swal as any).close();

                Object.keys(dataDecrypted.data).forEach(key => {
                    this.dataForm[key] = dataDecrypted.data[key];
                });

                // this.dataForm.tipo = 'Publicación';
                // this.dataForm.channel = 6;
                // this.$set(this.dataForm, 'tipo', 'Publicación');
                this.$set(this.dataForm, 'channel', 6);

                $('#modal-form-externa-google').modal('toggle');
                $(".modal").css("overflow-y","auto");
                $(`#modal-form-${this.name}-ceropapeles`).modal('show');

                this.isUpdate = true;

                (this.$parent as Vue)["addElementToList"](dataDecrypted.data);

                (this.$parent as any).dataPaginator.total++;
                (this.$parent as any).dataPaginator.numPages = Math.ceil((this.$parent as any).dataPaginator.total / (this.$parent as any).dataPaginator.pagesItems);
                (this.$parent as any).dataPaginator.currentPage = 1;
            }
        }).catch(err => {
            this.$swal({
                icon: "error",
                html: err.message,
                allowOutsideClick: false,
                confirmButtonText: this.lang.get("trans.Accept")
            });
        });
    }


    /**
     * Cargar los datos
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
     * @version 1.0.0
     *
     * @param dataElement elemento de grupo de trabajo
     */
    public loadExterna(dataElement: object): void {

        // Valida que exista datos
        if (dataElement) {

            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);
            // this.dataForm.tipo = 'Publicación';

            // this.$set(this.dataForm, 'tipo', 'Publicación');

            // Selecciona el tipo de acción en el documento según el estado del documento
            this.selectTipo(this.dataForm.tipo, null);
            (this.btnResizeEditor === "Minimizar editor") ? this.resizeEditor() : null;

            $(`#modal-form-${this.name}-ceropapeles`).modal('show');
            this.formOpen = "ceropapelesEditar";
            this.radicatied = false;


        } else {
            this.clearDataForm();
            this.isUpdate = false;
            // this.dataForm.external_all = '0';
            // this.dataForm.require_answer = 'No';
            // this.dataForm.editor = 'Google Docs';
            this.$set(this.dataForm, 'external_all', '0');
            this.$set(this.dataForm, 'annexes_digital', null);
            this.$set(this.dataForm, 'require_answer', 'No');
            this.$set(this.dataForm, 'have_assigned_correspondence_received', 'No');

            this.$set(this.dataForm, 'editor', 'Google Docs');
            this.$set(this.dataForm, 'citizens', []);

            $('#modal-form-externa-google').modal('show');
            this.formOpen = "ceropapelesCrear";
            this.radicatied = false;


        }
    }



    selectTipo(tipo, contenedoresInternos) {
        // this.dataForm.tipo = tipo;
        this.$set(this.dataForm, 'tipo', tipo);

        this.$forceUpdate();
        jQuery(".contenedorFormFuncionarioObs, .contenedorFormFirma").hide();
        switch (tipo) {
            case 'Elaboración':
            case 'Revisión':
            case 'Aprobación':
                    jQuery(contenedoresInternos).show();
                    jQuery(".contenedorFormFuncionarioObs").show("slow");
                break;

            case 'Firma Conjunta' :
                    jQuery(".contenedorFormFuncionario").hide();
                    jQuery(contenedoresInternos+", .contenedorFormFirma").show();
                    jQuery(".contenedorFormFuncionarioObs").show("slow");
                break;

            // case 'Publicación':
            //     jQuery(contenedoresInternos).show();
            //     jQuery(".contenedorFormPublicacion").show("slow");
            // break;

            default:
                    jQuery(contenedoresInternos).hide();
                break;
        }

        // También puedes agregar aquí cualquier lógica adicional que desees realizar cuando se selecciona una opción.
    }
    /**
     * Redimensiona la vista del editor del documento y el formulario, aumenta en un 70% la mitad del editor
     */
    public resizeEditor() {
        this.btnResizeEditor === "Maximizar editor" ? jQuery("#formularioIzq").css("max-width", "30%") : jQuery("#formularioIzq").css("max-width", "100%");
        this.btnResizeEditor = (this.btnResizeEditor === "Maximizar editor") ? "Minimizar editor" : "Maximizar editor";
    }

    private shortText(text) {
        if (typeof text !== 'undefined' && text !== null && text.trim() !== '') {
            const maxLength = 30;
            if (text.length > maxLength) {
                return text.substring(0, maxLength) + '...';
            } else {
                return text;
            }
        } else {
            return '';
        }
    }

    /**
     * Permite iniciar una sesión de google y luego abrir un documento de google docs en una nueva pestaña.
     *
     * @param {String} documento - URL del documento a editar.
     *
     */
    public abrirDocumentoGoogle(documento) {
        this.$swal({
            icon: "info",
            html: "El documento se abrirá en una nueva pestaña. Al completarlo, vuelva a Intraweb para continuar.",
            confirmButtonText: this.lang.get("trans.Accept")
        }).then((result) => {
            if (result.value) {
                window.open("https://accounts.google.com/InteractiveLogin/signinchooser?continue="+documento);
                setTimeout(() => {
                    let iframe = document.getElementById("editorDer")?.getAttribute("src");
                    iframe = iframe;  // Volver a cargar el iframe
                }, 3000);
            }
        });
    }


     /**
     * @description Maneja la vista previa y edición de un documento.
     * Cambia el estado del botón entre "Vista previa" y "Editar documento",
     * carga la vista previa desde el servidor o limpia la vista previa para editar.
     * Muestra un indicador de carga durante las operaciones.
     */

     public preview() {
        const loadingMessage = this.btnvistaprevia === 'Vista previa'
            ? "Cargando vista previa del documento"
            : "Cargando edición del documento";

        (this.$parent as any).showLoadingGif(loadingMessage); // Muestra el GIF de carga

        const waitForAttachment = (resolve: Function, attempts = 5) => {
            if (this.dataForm.template_preview || attempts <= 0) {
                resolve();
            } else {
                setTimeout(() => waitForAttachment(resolve, attempts - 1), 1000); // Reintentar cada segundo
            }
        };

        new Promise((resolve) => waitForAttachment(resolve))
            .then(() => {
                if (this.btnvistaprevia === 'Vista previa') {
                    this.btnvistaprevia = "Editar documento";

                    return axios.post('preview-document-external', this.dataForm);
                } else {
                    this.$set(this.dataForm, 'template_preview', null);
                    this.btnvistaprevia = "Vista previa";
                    return null;
                }
            })
            .then((res) => {
                if (res) {
                    const dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    this.$set(this.dataForm, 'template_preview', dataPayload?.['data'] || null);
                }
            })
            .catch((error) => {
                console.error("Error en la vista previa:", error.response?.data || error.message);
                this.$swal({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar la vista previa.'
                });
            })
            .finally(() => {
                setTimeout(() => (this.$swal as any).close(), 2000);
            });
    }

}
</script>
