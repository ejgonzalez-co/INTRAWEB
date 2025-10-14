<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios, { Axios } from "axios";
import { jwtDecode } from 'jwt-decode';

import utility from "../../utility";

import { Locale } from "v-calendar";
import { exit } from "process";

const locale = new Locale();
interface SignToImageComponent {
  guardarImagen(): Promise<string | null>;
}
/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Erika Johana Gonzalez Cuartas - Oct. 13 - 2020
 * @version 1.0.0
 */
@Component
export default class InternalComponent extends Vue {
    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: false, default: "internals" })
    public name: string;

    /**
     * Firma del usuario
     */
     @Prop({ type: Boolean, required: false, default: false })
    public userHasSignature: string;

    /**
     * autorizado para Firmar del usuario
     */
     @Prop({ type: Boolean, required: false, default: false })
    public autorizadoFirmar: string;

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

    // Variable para controlar firma del usuario
    public firmaUsuario: string;

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
        this.dataForm = { internal_recipients: []};
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
            crudComponent["searchFields"]["correspondence_internal.id"] = consulta_dashboard;
            crudComponent["pageEventActualizado"](1);
            // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
            axios.get('get-internals-show-dashboard/'+consulta_dashboard)
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Valida si puede editar la correspondencia
                    if((dataDecrypted["data"]["state"] == "Público" || dataDecrypted["data"]["state"] == "Pendiente de firma") || dataDecrypted["data"]["permission_edit"] == false) {
                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        crudComponent["show"](dataDecrypted["data"]);
                        $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                    } else if(dataDecrypted["data"]["origen"] != "FISICO") {
                        // Envia elemento a mostrar a la función loadInterna
                        this.loadInterna(dataDecrypted["data"]);
                    } else {
                        // Envia elemento a mostrar a la función edit
                        this.edit(dataDecrypted["data"]);
                        $(`#modal-form-${crudComponent["name"]}`).modal('toggle');
                    }
                })
                .catch((err) => {
                    console.log("Error obteniendo información del registro desde el listado el dashboard")
                });
         }
    }

    selectTipo(tipo, contenedoresInternos) {
        // this.dataForm.tipo = tipo;
        this.$set(this.dataForm,'tipo',tipo);

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
    /**
     * Limpia los datos del fomulario
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this.dataForm = {};

        // Inicializa valores del datashow
        (this.$parent as any)["dataShow"] = {};
        // Limpia errores
        this.dataErrors = {};
        this.$set(this.dataForm,'require_answer','No');
        this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');
        this.$set(this.dataForm, 'annexes_digital', null);
        this.$set(this.dataForm, 'internal_all', '0');
        this.$set(this.dataForm,'internal_recipients',[]);
        this.formOpen = '';
        this.btnvistaprevia = "Vista previa";
        // Actualiza componente de refresco
        // this._updateKeyRefresh();
        // Limpia valores del campo de archivos
        $("input[type=file]").val(null);
    }

   
    /**
     * Crea el formulario de datos para guardar
     *
     * @author Jhoan Sebastian Chilito S. - Jun. 26 - 2020
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

    /**
     * Guarda los datos del formulario
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
     * @version 1.0.0
     */
    public save(): void {
        // alert(this.dataForm.internal_recipients);
        // alert(this.dataForm.internal_all);
        if(!this.dataForm.internal_recipients?.length && this.dataForm.internal_all==0 && (this.dataForm.tipo == 'Firma Conjunta' || this.dataForm.tipo == 'Publicación')){
            this.$swal({
                icon: "info",
                html: "Por favor ingrese al menos un destinatario de la correspondencia",
                allowOutsideClick: false
            }).then((result) => {
                if (result.value) {
                   $(`#recipient_autocomplete`).focus();
                }
                });

        }else{
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

                const dataDecrypted = Object.assign({}, dataPayload);

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
                        (this.$parent as any).dataList.unshift(dataDecrypted["data"]);
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
                    $(`#form-internal-body button[id="rotule-button"]`).tab(
                        "show"
                    );
                    // Posiciona el scroll del modal inmediatamente al inicio de la ventana
                    $('#modal-form-internals').animate({scrollTop: 0}, 0);
                    // Limpia datos ingresados
                    this.clearDataForm();
                    this.formOpen = "centralizada";

                    // Agrega elemento nuevo a la lista
                    (this.$parent as any).dataList.unshift(dataDecrypted["data"]);

                    (this.$parent as any).dataPaginator.total= (this.$parent as any).dataPaginator.total+1;


                    // Calcula el numero de paginas del paginador
                    (this.$parent as any).dataPaginator.numPages = Math.ceil((this.$parent as any).dataPaginator.total / (this.$parent as any).dataPaginator.pagesItems);
                    (this.$parent as any).dataPaginator.currentPage=1;

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

    public obtenerFirma(): Promise<string> {
        const signToImageComponent = this.$refs['signToImage'] as Vue & {
            retornarRutaImagen: () => Promise<any>;
        };

        return signToImageComponent.retornarRutaImagen().then((rutaImagen) => {
            // console.log('Imagen guardada2 en:', rutaImagen);

            if (rutaImagen === "imagen_vacia") {
                (this.$swal as any).close();
                return "imagen_vacia"; // Return a default value to fulfill the promise
            }
            return rutaImagen;
        }).catch((error) => {
            console.error('Error al obtener la firma:', error);
            throw error; // Re-throw the error to handle it in the caller
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
                   $(`#recipient_autocomplete`).focus();
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
    public async update() {


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
                target: '#modal-form-internals-ceropapeles',
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

                        const dataDecrypted = Object.assign({}, dataPayload);

                        if(dataDecrypted["data"]["second_password_validate"]) {
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


            if(this.dataForm.origen=="DIGITAL"){
                this.$swal({
                    title: 'Actualización de Correspondencia Interna',
                    html:(this.$parent as any).confirmationMessage(this.dataForm),
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


    public async  actualizarCorrespondencia() {


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

        if (this.dataForm.tipo === 'Publicación' && this.dataForm.usar_firma_cargada=='No' && this.autorizadoFirmar) {
            try {
                const firma = await this.obtenerFirma();
                this.firmaUsuario = firma;
                if(this.firmaUsuario == '' || this.firmaUsuario == 'undefined' || this.firmaUsuario == 'imagen_vacia'){
                    return;
                }

            } catch (error) {
                console.error('Error al obtener la firma:', error);
                return ;
            }
        }

        const formData: FormData = this.makeFormData();
        formData.append("_method", "put");
        formData.append("firma_desde_componente", this.firmaUsuario);

        // Envia peticion de guardado de datos
        axios
            .post(`${this.name}/${this.dataForm["id"]}`, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                // Cierra el swal de guardando datos
                (this.$swal as any).close();

                // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {

                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {

                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                        const dataDecrypted = Object.assign({}, dataPayload);

                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}`).modal("toggle");
                        // Agrega elemento nuevo a la lista
                        Object.assign(
                            (this.$parent as any)._findElementById(
                                this.dataForm["id"],
                                false
                            ),
                            dataDecrypted["data"]
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


                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);


                    // Valida si la variable editor tiene valor, de serlo, es una interna de Google
                    if(dataDecrypted["data"]["editor"]) {
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}-ceropapeles`).modal("toggle");
                    }
                    this.dataRotule = dataDecrypted["data"];

                    this.radicatied = true;
                    $(`#form-internal-body button[id="rotule-button"]`).tab(
                        "show"
                    );
                    // Posiciona el scroll del modal inmediatamente al inicio de la ventana
                    $('#modal-form-internals').animate({scrollTop: 0}, 0);
                    // Agrega elemento nuevo a la lista
                    Object.assign(
                        (this.$parent as any)._findElementById(
                            this.dataForm["id"],
                            false
                        ),
                        dataDecrypted["data"]
                    );

                    // Limpia datos ingresados
                    // this.clearDataForm();
                    // Emite notificacion de almacenamiento de datos
                    (this.$parent as any)._pushNotification(res.data.message);
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
     * Actualiza el componente que utilize el key
     * cada vez que se cambia de editar a actualizar
     * y borrado de campos de formulario
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 06 - 2020
     * @version 1.0.0
     */
    private _updateKeyRefresh(): void {
        this.keyRefresh++;
        (this.$parent as any)['_updateKeyRefresh']();
    }

    public add(): void {
        this.clearDataForm();
        this.isUpdate = false;
        this.$set(this.dataForm,'internal_recipients',[]);

        this.$set(this.dataForm,'internal_all','0');
        this.$set(this.dataForm,'require_answer','No');

        this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');


        this.$set(this.dataForm, 'annexes_digital', null);

        this.radicatied = false;
        $(`#form-internal-body button[id="radication-button"]`).tab(
            "show"
        );

        // Actualiza componente de refresco
        // this._updateKeyRefresh();
        this.formOpen = "centralizada";
    }

    /**
     * Busca y prepara los datos a editar
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
     * @version 1.0.0
     *
     * @param dataElement elemento a editar
     */
    public edit(dataElement: object): void {

        // Busca el elemento deseado y agrega datos al fomrulario
        this.dataForm = utility.clone(dataElement);
        this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');

        this.radicatied = false;
        $(`#form-internal-body button[id="radication-button"]`).tab(
            "show"
        );

        // Habilita actualizacion de datos
        this.isUpdate = true;
        // Actualiza componente de refresco
        this._updateKeyRefresh();
        this.formOpen = "centralizada";
    }

    public edit2(dataElement: object): void {
        this.dataForm = {};
        this.radicatied = false;
        $(`#form-internal-body button[id="radication-button"]`).tab(
            "show"
        );
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

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Asigna elemento a mostrar
                    this.dataForm = dataDecrypted["data"];
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

    public adjuntarDocumento(event: any): void {

        // Obtiene del id de la correspondencia del datashow, cuando el rotulo es de los detalles (se abrió desde la acción)
        let idCorrespondence = (this.$parent as any)["dataShow"]["id"];
        // Si no se abrió el rótulo desde la acción, es porque se abrió desde una creación de una correspondencia

        if(idCorrespondence == undefined) {
            idCorrespondence = this.dataRotule.id;
        }
        // Almacena el objeto de tipo file adjuntado en el rótulo
        // this.dataForm["document_pdf"] = event.target.files[0];
        this.$set(this.dataForm, 'document_pdf', event.target.files[0]);

        if(event.target.files[0]){

            (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));

            // Envia peticion de guardado de datos
            axios
                .post("adjuntar-rotulo-internal/"+idCorrespondence, this.makeFormData(), {
                    headers: { "Content-Type": "multipart/form-data" }
                })
                .then(res => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

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
                    this.$set(this.dataForm, 'document_pdf', '');

                    // Valida que se retorne los datos desde el controlador
                    if (dataDecrypted["data"]) {
                    // Actualiza elemento modificado en la lista
                    Object.assign((this.$parent as any)._findElementById(idCorrespondence, false), dataDecrypted["data"]);
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
                        errors += "<br>"
                        + err.response.data.message;
                    }
                    this.$set(this.dataForm, 'document_pdf', '');

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
     * @author German Gonzalez V. Abril 14 - 2023
     * @version 1.0.0
     *
     * @param id ID de la correspondencia
     */
    public readCheck(id: number, estado: string): void {

        // Envia peticion de obtener todos los datos del recurs
        axios.post('internal-read-check/'+id+'/'+estado)
        .then((res) => {


            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
            const dataDecrypted = Object.assign({}, dataPayload);

            
                this.$swal({
                    icon: dataDecrypted['data'].status_permission_check == 'Si' ? "success" : 'info',
                    text: dataDecrypted['data'].status_permission_check == 'Si' ? 'La correspondencia fue leída.' : ' correspondencia no ha sido leída.',
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });

            // Agrega el elemento nuevo a la lista
            Object.assign((this.$parent as any)._findElementById(id, false), dataDecrypted["data"]);

                        // Visualiza toast positivo
            // console.log("Corrrespondencia leida con éxito");
        })
        .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia', false, 'Error');
        });
    }

    /**
     * Asignar leido correspondencia
     *
     * @author German Gonzalez V. Abril 14 - 2023
     * @version 1.0.0
     *
     * @param id ID de la correspondencia
     */
    public read(id: number): void {

        console.log(id);
        // Envia peticion de obtener todos los datos del recurs
        axios.post('internal-read/'+id)
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
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia', false, 'Error');
        });
    }

    /**
     * Create la correspondencia interna junto con la plantilla de Google
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
     * @version 1.0.0
     *
     */
     public async createInterna(): Promise<void> {
        try {
            (this.$parent as any).showLoadingGif("Creando documento, Por favor espere un momento.");

            const res = await axios.post("crear-interna-ceropapeles", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            });

            if (res.data.type_message === 'error' || res.data.type_message === 'info') {
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
                        id: null,
                        title: null,
                        template: null,
                        type: null,
                        dependencias_id: null,
                        from_id: null,
                        origen: null,
                        consecutive: null,
                        from: null,
                        elaborated_names: null,
                        users: null,
                    },
                }, dataPayload);

                (this.$swal as any).close();

                Object.keys(dataDecrypted.data).forEach(key => {
                    this.dataForm[key] = dataDecrypted.data[key];
                });

                this.formOpen = "ceropapelesEditar";
                // this.dataForm.tipo = 'Publicación';
                // this.$set(this.dataForm,'tipo','Revisión');

                //valida cuando la creacion de la correspondencia no sea por respuesta, ya que esta no abre el formulario
                if(this.dataForm.require_answer != 'Responder a otra correspondencia'){
                    $('#modal-form-interna-google').modal('toggle');
                }
                $(".modal").css("overflow-y","auto");
                $(`#modal-form-${this.name}-ceropapeles`).modal('show');

                this.isUpdate = true;
                this.radicatied = false;

                (this.$parent as Vue)["addElementToList"](dataDecrypted.data);

                (this.$parent as any).dataPaginator.total++;
                (this.$parent as any).dataPaginator.numPages = Math.ceil((this.$parent as any).dataPaginator.total / (this.$parent as any).dataPaginator.pagesItems);
                (this.$parent as any).dataPaginator.currentPage = 1;
            }
        } catch (err) {
            this.$swal({
                icon: "error",
                html: err.message,
                allowOutsideClick: false,
                confirmButtonText: this.lang.get("trans.Accept")
            });
        }
    }


    /**
     * Cargar los datos
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
     * @version 1.0.0
     *
     * @param dataElement elemento de grupo de trabajo
     */
    public loadInterna(dataElement: object): void {
        this.clearDataForm();
        // Valida que exista datos
        if (dataElement) {
            // Habilita actualizacion de datos
            this.isUpdate = true;
            // Busca el elemento deseado y agrega datos al fomrulario
            this.dataForm = utility.clone(dataElement);
            this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');


            // this.dataForm.tipo = 'Publicación';
            // this.$set(this.dataForm, 'tipo', 'Publicación');

            // Selecciona el tipo de acción en el documento según el estado del documento
            this.selectTipo(this.dataForm.tipo, null);
            // Si el editor esta maximizado, lo pone divido por defecto
            (this.btnResizeEditor === "Minimizar editor") ? this.resizeEditor() : null;
            (this.btnvistaprevia === "Editar documento") ? this.preview() : null;

            $(`#modal-form-${this.name}-ceropapeles`).modal('show');
            // this._updateKeyRefresh();
            this.formOpen = "ceropapelesEditar";
            this.radicatied = false;
        } else {
            this.isUpdate = false;

            this.$set(this.dataForm, 'internal_all', '0');
            this.$set(this.dataForm, 'require_answer', 'No');
            this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');
            this.$set(this.dataForm, 'editor', 'Google Docs');
            this.$set(this.dataForm,'internal_recipients',[]);
            $('#modal-form-interna-google').modal('show');
            this.formOpen = "ceropapelesCrear";
            this.radicatied = false;

        }
    }

    public async loadInternaRecuperar(dataElement: object) {
        try {
            // Clona el objeto de datos recibido y establece un nuevo tipo
            this.dataForm = utility.clone(dataElement);
            // this.dataForm.tipo = 'recuperacion';
            this.$set(this.dataForm, 'tipo', 'recuperacion');

            // Muestra un mensaje de carga
            (this.$parent as any).showLoadingGif("Recuperando Documento");

            // Crea un objeto FormData y lo prepara para la solicitud
            const formData: FormData = this.makeFormData();
            formData.append("_method", "put");

            // Realiza una solicitud POST usando Axios
            const res = await axios.post(`${this.name}/${this.dataForm["id"]}`, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });

            // Cierra el mensaje de carga
            (this.$swal as any).close();

            // Valida si la solicitud fue exitosa
            if (res.data.success) {
                // Muestra una alerta de éxito
                this.$swal({
                    icon: res.data.type_message,
                    html: "Documento Recuperado con éxito",
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });

                // Decodifica los datos recibidos y los asigna a una variable
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                // Agrega el elemento nuevo a la lista
                Object.assign((this.$parent as any)._findElementById(this.dataForm["id"], false), dataDecrypted["data"]);

                // Limpia los datos ingresados
                this.clearDataForm();
            }
        } catch (err) {
            // En caso de error, cierra el mensaje de carga
            (this.$swal as any).close();
            let errors = "";

            // Verifica y maneja los diferentes tipos de errores
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

            // Muestra una alerta de error
            this.$swal({
                icon: "error",
                html: this.lang.get("trans.Failed to save data") + errors,
                allowOutsideClick: false
            });
        }
    }



 /**
     * Genera contenido uti
     * lizando el servicio ChatGPT basado en el contenido proporcionado.
     *
     * @param {String} contenido Texto ingresado por el usuario que se utilizará como consulta para el servicio ChatGPT.
     */
     public _generarContenidoChatgpt(contenido) {
        // Verifica si se ha proporcionado contenido
        if (contenido) {

            // Muestra un modal de carga mientras se procesa la solicitud

            (this.$parent as any).showLoadingGif(this.lang.get("trans.Loading data"));


            var contenidoCodificado = encodeURIComponent(contenido);
            var contenidoBase64 = btoa(contenidoCodificado);
            // Realiza una solicitud GET a la ruta 'chatgpt' con el contenido como parámetro
            axios.get("chatgpt", { params: { query: btoa(contenidoBase64) } })
                .then((res) => {
                    // Cierra el modal de carga
                    (this.$swal as any).close();

                    // Imprime en consola y actualiza el contenido de la forma con la respuesta recibida
                    // console.log(res.data.content);
                    // this.dataForm.content = res.data.content;
                    this.$set(this.dataForm, 'content', res.data.content);

                })
                .catch((err) => {
                    // Cierra el modal de carga en caso de error
                    (this.$swal as any).close();

                    // Imprime un mensaje de error en la consola
                    console.log('Error al obtener la lista.');
                });
        } else {
            // Muestra un mensaje informativo si el contenido no está presente
            this.$swal({
                icon: 'info',
                html: "Escriba el contenido deseado en el campo \"Contenido\".<br>Por ejemplo: 'Órdenes de Compra de Monitores' o un 'Memorando General para un Día Cívico.",
                allowOutsideClick: false,
                confirmButtonText: this.lang.get("trans.Accept")
            });
        }
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
     * Configura el formulario para una respuesta interna y cierra el modal.
     *
     * @param {Object} datos - Información para configurar el formulario.
     * @param {number} datos.id - ID del consecutivo.
     * @param {string} datos.consecutive - Número del consecutivo.
     * @param {string} datos.title - Título de la respuesta.
     * @param {string} datos.type - Tipo de respuesta.
     *
     * @returns {Promise<void>}
     */
    public async createInternaRespuesta(datos): Promise<void> {
        // Configura los datos del formulario
        this.$set(this.dataForm, 'internal_recipients', []);
        this.$set(this.dataForm, 'internal_all', '0');
        this.$set(this.dataForm, 'require_answer', 'Responder a otra correspondencia');
        this.$set(this.dataForm, 'answer_consecutive', datos.id);
        this.$set(this.dataForm, 'answer_consecutive_name', datos.consecutive);
        this.$set(this.dataForm, 'title', `Respuesta: ${datos.consecutive} (${datos.title})`);
        this.$set(this.dataForm, 'type', datos.type);
        this.$set(this.dataForm, 'usar_firma_cargada', this.userHasSignature ? 'Si' : 'No');
        this.$set(this.dataForm, 'editor', 'Google Docs');
        this.$set(this.dataForm, 'annexes_digital', null);

        // Llama a la función para crear la respuesta y cierra el modal
        this.createInterna();
        $('#modal-form-interna-google').modal('hide');
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

                    return axios.post('preview-document', this.dataForm);
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


    public previewinicial() {
        const loadingMessage = this.btnvistaprevia === 'Vista previa' ?
            "Cargando vista previa del documento" :
            "Cargando edición del documento";

        (this.$parent as any).showLoadingGif(loadingMessage); // Muestra el GIF de carga

        if (this.btnvistaprevia === 'Vista previa') {
            this.btnvistaprevia = "Editar documento";

            axios.post('preview-document', this.dataForm)
            .then((res) => {
                try {
                const dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = { ...dataPayload }; // Spread operator para crear una copia del objeto
                this.$set(this.dataForm, 'template_preview', dataDecrypted['data']); // Asigna los datos descifrados a template_preview
                } catch (error) {
                console.error("Error al decodificar JWT:", error);
                // Manejar el error, por ejemplo, mostrando un mensaje al usuario
                this.$swal({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar la vista previa.'
                });
                }
            })
            .catch((err) => {
                console.error("Error al obtener la vista previa:", err.response?.data || err.message);
                // Manejar el error, por ejemplo, mostrando un mensaje al usuario
                this.$swal({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar la vista previa.'
                });
            })
            .finally(() => {
                (this.$swal as any).close(); // Cierra el indicador de carga independientemente del resultado
            });
        } else {
            this.$set(this.dataForm, 'template_preview', null); // Limpia la vista previa
            this.btnvistaprevia = "Vista previa";

            // Se recomienda usar una promesa o un evento para manejar el cierre del loading en lugar de un setTimeout
            // Esto asegura que el loading se cierre después de que se complete la acción necesaria.
            // Ejemplo usando una promesa:
            // Promise.resolve().then(() => {
            //     (this.$swal as any).close();
            // });

            setTimeout(() => {
                (this.$swal as any).close(); // Cierra el loading después de limpiar la vista previa
            }, 2000); // 500ms = 0.5 segundos
        }
    }
}
 
</script>
<style>

   /* Estilos para el tablero consolidado */
   .states_style {
        border-radius: 3px;
        min-width: 83px;
   }

   .estado_publico {
      background-color: #8BC34A;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_elaboracion {
      background-color: #2196F3;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_revision {
      background-color: #FFEB3B;
      color: black;
      border-radius: 3px;
      padding: 5px;
   }
   .estado_pendiente_firma {
      background-color: #FF9800;
      color: white;
      border-radius: 3px;
      padding: 5px;
   }

   .estado_aprobacion {
      background-color: #00BCD4;
      color: white;
      border-radius: 3px;
      padding: 5px;

   }

   .estado_devuelto_modificar {
      background-color: #F44336;
      color: white;
      border-radius: 3px;
      padding: 5px;

   }






</style>
