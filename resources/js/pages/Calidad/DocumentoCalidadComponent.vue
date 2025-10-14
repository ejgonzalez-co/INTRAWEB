<template>
  <div></div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { jwtDecode } from "jwt-decode";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Seven Soluciones Informáticas S.A.S - Jun. 4 - 2023
 * @version 1.0.0
 */
@Component
export default class DocumentoCalidadComponent extends Vue {
    /**
     * Nombre del componente
     */
    @Prop({ type: String, required: false, default: "DocumentoComponent" })
    public nameComponent: string;

    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: false, default: "documentos" })
    public name: string;

    /**
     * Campos de busqueda
     */
    public searchFields: any;

    /**
     * Valor del campo
     */
    // @Prop({type: Object})
    public dataForm: any;

    public dataList: any;

    /**
     * Nombre de la entidad a afectar
     */
    public btnResizeEditor: string = "Maximizar editor";

    /**
     * Valida si radito
     * son para actualizar o crear
     */
    public selectTipoValidate: boolean = false;

    // Variable para controlar el formulario que se va a ejecutar
    public formOpen: string;

    /**
     * Errores del formulario
     */
    public dataErrors: any;

    /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;

    @Prop({ type: [] })
    public listadoTiposDocumentos: any;

    public slideIndex: number = 0;

    public tiposDocumentosExpanded: boolean = false;

    /**
     * Valida si los valores del formulario
     * son para actualizar o crear
     */
    public isUpdate: boolean;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    public crudComponent: Vue;

    /**
     * Valida para validar si se esta enviando correo,
     * para mostrar el spiner indicando el evío en la vista de detalles
     */
    public enviandoCorreo: boolean;

    /**
     * Detecta el estado de carga de los archivos, si se ha terminado o no de cargar por completo los adjuntos del componente InputCrudFile
     */
    public uploadingFileInputCrudFile: boolean;

    /**
     * Constructor de la clase
     *
     * @author Seven Soluciones Informáticas S.A.S. - Jul. 19 - 2024
     * @version 1.0.0
     */
    constructor() {
        super();
        this.searchFields = {};
        this.dataErrors = {};
        this.dataList = [];
        this.formOpen = "";
        this.dataForm = {};
        this.keyRefresh = 0;
        this.isUpdate = false;
        // Obtiene la instancia del crudComponent
        this.crudComponent = this.$parent as Vue;
        this.lang = this.crudComponent["lang"];
        this.enviandoCorreo = false;
        this.uploadingFileInputCrudFile = false;
    }

    /**
     * Se ejecuta cuando el componente ha sido creado
     */
    created() {
        // recuperamos el querystring (parámetros enviados por URL)
        const querystring = window.location.search;
        // usando el querystring, creamos un objeto del tipo URLSearchParams
        const params = new URLSearchParams(querystring);
        // Valida si el parámetro qd (query dashboard), petición de los widgets, tiene algo quiere decir que viene a petición del dashboard
        if (params.get("qd")) {
            // Se obtiene el valor del parámtro qd (query dashboard) y se decodifíca
            let consulta_dashboard = decodeURIComponent(
                escape(atob(params.get("qd")))
            );
            this.crudComponent["searchFields"]["origen"] = consulta_dashboard;
            this.crudComponent["pageEventActualizado"](1);
        } else if (params.get("qsb")) {
            // Se obtiene el valor del parámtro qsb (query sidebar), petición de la barra lateral y se decodifíca
            let consulta_dashboard = decodeURIComponent(
                escape(atob(params.get("qsb")))
            );
            this.crudComponent["searchFields"]["state"] = consulta_dashboard;
            this.crudComponent["pageEventActualizado"](1);
        } else if (params.get("qder")) {
            // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
            let consulta_dashboard = decodeURIComponent(
                escape(atob(params.get("qder")))
            );
            // ac = accion. Variable que define la acción a realizar sobre el registro que se esta consultando
            let accion = params.get("ac")
                ? decodeURIComponent(escape(atob(params.get("ac"))))
                : "";
            this.crudComponent["searchFields"]["id"] = consulta_dashboard;
            this.crudComponent["pageEventActualizado"](1);
            // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
            axios
                .get("get-documentos-show-dashboard/" + consulta_dashboard)
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Valida si puede editar el documento o si la intención es ver los detalles
                    if (
                        !dataDecrypted["data"]["permission_edit"] ||
                        accion == "ver_detalles" || dataDecrypted["data"]["estado"] == 'Pendiente de firma'
                    ) {
                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        this.crudComponent["show"](dataDecrypted["data"]);
                        $(`#modal-view-${this.crudComponent["name"]}`).modal("toggle");
                    } else {
                        // Envia elemento a mostrar a la función edit
                        this.loadDocumento(dataDecrypted["data"]);
                        $(`#modal-form-${this.crudComponent["name"]}`).modal("toggle");
                    }
                })
                .catch((err) => {
                    console.log(
                        "Error obteniendo información del registro desde el listado el dashboard",
                        err
                    );
                });
        }
    }

    selectTipo(tipo, contenedoresInternos) {
        jQuery(".contenedorFormFuncionarioObs").hide();
        this.$set(this.dataForm, "funcionario_elaboracion_revision", "");
        this.$set(this.dataForm, "observacion", "");
        this.$set(this.dataForm, "tipo", tipo);
        switch (tipo) {
        case "elaboracion":
        case "revision":
        case "aprobacion":
            jQuery(".contenedorFormFuncionarioObs").show("slow");
            break;
        }
    }

    /**
     *
     */
    public resizeTiposDocumentos() {
        const slides = document.getElementsByClassName("templateTD");

        for (let i = 0; i < slides.length; i++) {
            slides[i]["style"].transform = `translateX(0%)`; // Aplicamos la transformación a cada slide
        }
        this.tiposDocumentosExpanded = !this.tiposDocumentosExpanded;
    }

    /**
     * Redimensiona la vista del editor del documento y el formulario, aumenta en un 70% la mitad del editor
     */
    public resizeEditor() {
        if(this.btnResizeEditor === "Maximizar editor") {
            jQuery("#formularioIzq").css("max-width", "30%");
            jQuery(".floating-icon, .info-bubble").css("left", "33%");
        } else {
            jQuery("#formularioIzq").css("max-width", "100%");
            jQuery(".floating-icon, .info-bubble").css("left", "52%");
        }
        this.btnResizeEditor =
        this.btnResizeEditor === "Maximizar editor"
            ? "Minimizar editor"
            : "Maximizar editor";
    }

    /**
     * Crea el formulario de datos para guardar
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
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
                        data.forEach((element) => {
                            if (typeof element == "object") {
                                formData.append(`${key}[]`, JSON.stringify(element));
                            } else {
                                formData.append(`${key}[]`, element);
                            }
                        });
                    } else if (data instanceof Date) {
                        formData.append(key, locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                    } else {
                        formData.append(key, data);
                    }
                } else if (typeof data == "object" && data !== null) {
                    formData.append(`${key}`, JSON.stringify(this.dataForm[key]));
                }
            }
        }
        return formData;
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Seven Soluciones Informáticas S.A.S - Jul. 19 - 2024
     * @version 1.0.0
     */
    public crearDocumentoInicial(): void {
        this.clearDataForm();
        this.isUpdate = false;
        // $('#modal-form-documentos').modal('show');
        this.$set(this.dataForm, "codigo_formato", ["prefijo_proceso", "prefijo_tipo_documento", "consecutivo_documento"]);

        this.formOpen = "tipoDocumento";
    }

    public loadDocumento(dataElement): void {
        // this.clearDataForm();
        // Habilita actualizacion de datos
        this.isUpdate = true;
        this.dataForm = utility.clone(dataElement);
        this.$set(this.dataForm, "tipo", "publicacion");
        this.$set(this.dataForm, "distribucion", this.dataForm.distribucion?.split(",") ?? []);
        // Asigna el valor de la observación a una variable temporal para mostrar en la vista
        this.$set(this.dataForm, "observacion_previa", this.dataForm.observacion);
        // Selecciona el tipo de acción en el documento según el estado del documento
        this.selectTipo(this.dataForm.tipo, null);
        // Si el editor esta maximizado, lo pone divido por defecto
        this.btnResizeEditor === "Minimizar editor" ? this.resizeEditor() : null;
        // $(`#modal-form-${this.name}`).modal("show");
        this.formOpen = "FormularioDocumento";
    }

    public seleccionarDistribucion(event) {
        if(event == "Todos")
            this.$set(this.dataForm, "distribucion", ["Todos"]);
    }

    /**
     * Muestra el formulario e información para la firma o devolución del documento
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 04 - 2024
     * @version 1.0.0
     *
     * @param dataElement datos del documento a firmar
     */
    public firmarDocumento(dataElement: object): void {
        // Busca el elemento deseado y agrega datos al fomrulario
        this.dataForm = utility.clone(dataElement);
        this.$set(this.dataForm, "accion_documento", "");
        // Habilita actualizacion de datos
        this.isUpdate = true;
        // Si el editor esta maximizado, lo pone divido por defecto
        this.btnResizeEditor === "Minimizar editor" ? this.resizeEditor() : null;
        // Habilita formulario
        this.formOpen = "FormularioFirmarDocumento";
    }

    /**
     * Crea el documento de calidad junto con la plantilla
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
     * @version 1.0.0
     *
     */
    public async crearDocumento(): Promise<void> {
        try {
            this.crudComponent["showLoadingGif"]("Creando documento, Por favor espere un momento.");
            // Si el origen del documento es diferente a producirlo en Intraweb, este se publicará en su formato original
            if (this.dataForm.origen_documento != "Producir documento en línea a través de Intraweb") {
                this.$set(this.dataForm, "formato_publicacion", "Formato original");
            }

            const res = await axios.post("crear-documento", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" },
            });

            if (res.data.type_message === "error" || res.data.type_message === "info") {
                this.$swal({
                    icon: res.data.type_message,
                    html: res.data.message,
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept"),
                });
            } else {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                (this.$swal as any).close();

                this.formOpen = "FormularioDocumento";
                this.$set(this.dataForm, "tipo", "publicacion");
                this.$set(this.dataForm, "distribucion", []);
                // dataDecrypted["data"]["document_pdf"] = dataDecrypted["data"]["document_pdf"].split(",");
                Object.assign(this.dataForm, dataDecrypted["data"]);

                $("#modal-form-documentos-documento-inicial").modal("toggle");
                $(".modal").css("overflow-y", "auto");
                $(`#modal-form-${this.name}`).modal("show");

                this.isUpdate = true;
                // Se inicializan parcialmente estos valores en null para que no se muestren undefined en el listado de registros principal
                dataDecrypted["data"]["tipo_documento"] = null;
                dataDecrypted["data"]["version"] = null;
                dataDecrypted["data"]["proceso"] = null;

                this.crudComponent["addElementToList"](dataDecrypted["data"]);

                this.crudComponent["dataPaginator"].total++;
                this.crudComponent["dataPaginator"].numPages = Math.ceil(
                    this.crudComponent["dataPaginator"].total /
                    this.crudComponent["dataPaginator"].pagesItems
                );
                this.crudComponent["dataPaginator"].currentPage = 1;
            }
        } catch (err) {
            this.$swal({
                icon: "error",
                html: err.message,
                allowOutsideClick: false,
                confirmButtonText: this.lang.get("trans.Accept"),
            });
        }
    }

    /**
     * Función para actualizar el documento
     */
    public async actualizarDocumento() {
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

        const formData: FormData = this.makeFormData();

        formData.append("_method", "put");

        this.$swal({
            html: this.crearMensaje(formData),
            allowOutsideClick: false,
            confirmButtonText: "Aceptar",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            icon: "info",
            preConfirm: async () => {
                try {
                    // Muestra el mensaje de carga
                    this.crudComponent["showLoadingGif"](
                        this.lang.get("trans.loading_update")
                    );

                    const res = await axios.post(`${this.name}/${this.dataForm["id"]}`, formData, {headers: { "Content-Type": "multipart/form-data" }});

                    // Cierra el mensaje de carga
                    (this.$swal as any).close();

                    // Valida el tipo de alerta que debe mostrarse
                    if (res.data.type_message) {
                        // Muestra la alerta
                        this.$swal({
                            icon: res.data.type_message,
                            html: res.data.message,
                            allowOutsideClick: false,
                            confirmButtonText: this.lang.get("trans.Accept"),
                        });

                        if (res.data.type_message === "success") {
                            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                            const dataDecrypted = Object.assign({}, dataPayload);

                            // Cierra el formulario modal
                            $(`#modal-form-${this.name}`).modal("toggle");


                            this.crudComponent["dataList"] = this.crudComponent["dataList"].filter(item => item.id !== parseInt(dataDecrypted["data"]["documento_id_obsoleto"]));
                            // Agrega el elemento nuevo a la lista
                            Object.assign(
                                this.crudComponent["_findElementById"](
                                    this.dataForm["id"],
                                    false
                                ),
                                dataDecrypted["data"]
                            );

                            // Limpia los datos ingresados
                            this.clearDataForm();
                        }
                    }
                } catch (err) {
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

                    // Muestra los errores
                    this.$swal({
                        icon: "error",
                        html: this.lang.get("trans.Failed to save data") + errors,
                        allowOutsideClick: false,
                    });
                }
            },
        });
    }

    /**
     * Limpia los datos del fomulario
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this._initValues();
        // Inicializa valores del datashow
        this.crudComponent["dataShow"] = {};
        // Limpia errores
        this.dataErrors = {};
        // Actualiza componente de refresco
        // this._updateKeyRefresh();
        // Limpia valores del campo de archivos
        $("input[type=file]").val(null);
    }

    /**
     * Inicializa valores del dataform
     *
     * @author Seven Soluciones Informáticas. - Mar. 20 - 2024
     * @version 1.0.0
     */
    private _initValues(): void {
        this.dataForm = utility.clone(this.crudComponent["initValues"]);
        this.searchFields = utility.clone(this.crudComponent["initValuesSearch"]);
    }

    /**
     * Actualiza el componente que utilize el key
     * cada vez que se cambia de editar a actualizar
     * y borrado de campos de formulario
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
     * @version 1.0.0
     */
    private _updateKeyRefresh_disabled(): void {
        this.keyRefresh++;
        this.crudComponent["_updateKeyRefresh"]();
    }

    /**
     * Evento de asignacion de archivo
     *
     * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
     * @version 1.0.0
     *
     * @param event datos de evento
     * @param fieldName nombre de campo
     */
    public inputFile(event, fieldName: string): void {
        this.dataForm[fieldName] = event.target.files[0];
    }

    /**
     * Asignar leido a la anotación del documento de calidad
     *
     * @author Seven Soluciones Informáticas. Mar 18 - 2024
     * @version 1.0.0
     *
     * @param id ID del documento de calidad
     */
    public leerAnotaciones(id: number): void {
        // Envia peticion de obtener todos los datos del recurs
        axios
        .post("documentos-leido-anotacion/" + id)
        .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
            const dataDecrypted = Object.assign({}, dataPayload);

            // Agrega el elemento nuevo a la lista
            Object.assign(
                this.crudComponent["_findElementById"](id, false),
                dataDecrypted["data"]
            );
        })
        .catch((err) => {
            this.crudComponent["_pushNotification"](
                "Error al leer Corrrespondencia",
                false,
                "Error"
            );
        });
    }

    /**
     * Formatea el valor ingresado en el campo nombre_metadato y variable_documento para lo asigna al campo variable_documento
     * reemplazando espacios por _, mayúsculas por minúsculas y quitando caractéres especiales, de los metadatos
     */
    public asignarVariableMetadato(event): void {
        // Formatea el valor ingresado en el campo nombre_metadato
        let variable_metadato = this.reemplazarCaracteres(event.target.value);
        if (variable_metadato != "#" && variable_metadato) {
            // Asigna el valor formateado al campo variable_documento
            this.$set(
                this.crudComponent.$refs["metadatos_tipo_documento"]["dataForm"],
                "variable_documento",
                "#" + variable_metadato
            );
        } else {
            // Asigna vacio al campo variable_documento
            this.$set(
                this.crudComponent.$refs["metadatos_tipo_documento"]["dataForm"],
                "variable_documento",
                ""
            );
        }
    }

    public reemplazarCaracteres(texto) {
        return texto
        .replace(/\s+/g, "_")
        .toLowerCase()
        .replace(/á/g, "a")
        .replace(/é/g, "e")
        .replace(/í/g, "i")
        .replace(/ó/g, "o")
        .replace(/ú/g, "u")
        .replace(/ñ/g, "n")
        .replace(/[^a-zA-Z0-9_]/g, "");
    }

    /**
     * Asignar leído al documento
     *
     * @author Seven Soluciones Informáticas S.A.S. Abr 3 - 2024
     * @version 1.0.0
     *
     * @param id ID del documento de calidad
     */
    public leido(id: number): void {
        // Envia peticion de obtener todos los datos del recurs
        axios
        .post("documentos-leido/" + id)
        .then((res) => {
            // Datos de notificacion (Por defecto guardar)
            const toastOptions = {
                closeButton: true,
                closeMethod: "fadeOut",
                timeOut: 3000,
                tapToDismiss: false,
            };

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            // Agrega el elemento nuevo a la lista
            Object.assign(
                this.crudComponent["_findElementById"](id, false),
                dataDecrypted["data"]
            );

            // Visualiza toast positivo
        })
        .catch((err) => {
            // console.log('Error al obtener la lista.');
            this.crudComponent["_pushNotification"](
                "Error al leer el documento de calidad",
                false,
                "Error"
            );
        });
    }

    /**
     * Guarda la información del formulario dinamico
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 04 - 2024
     * @version 1.0.0
     */
    public FirmaDocumento() {
        let titulo =
        this.dataForm.accion_documento == "Aprobar Firma"
            ? "¿Desea aprobar y firmar el documento " + this.dataForm.titulo_asunto + "?"
            : "¿Desea devolver para modificaciones el documento " +
            this.dataForm.titulo_asunto + "?";
        this.$swal({
            title: titulo,
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No",
        }).then((result: any) => {
        // Valida si se confirma el mensaje
        if (result.value) {
            // Almacena la informacion del formulario
            this.crudComponent["showLoadingGif"](
                this.lang.get("trans.loading_update")
            );

            // Construye los datos del formulario
            const formData: FormData = this.makeFormData();
            // Valida que el metodo http sea PUT
            if (this.isUpdate) {
                formData.append("_method", "put");
            }
            // Envia peticion de guardado de datos
            axios
            .post("firmar-documento", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then((res) => {
                // Cierra el mensaje de carga
                (this.$swal as any).close();

                // Valida el tipo de alerta que debe mostrarse
                if (res.data.type_message) {
                    // Muestra la alerta
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept"),
                    });

                    if (res.data.type_message === "success") {
                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                        const dataDecrypted = Object.assign({}, dataPayload);

                        // Cierra el formulario modal
                        $(`#modal-aprobar-cancelar-firma`).modal("toggle");

                        // Agrega el elemento nuevo a la lista
                        Object.assign(
                            this.crudComponent["_findElementById"](
                                this.dataForm["id"],
                                false
                            ),
                            dataDecrypted["data"]
                        );

                        // Limpia los datos ingresados
                        this.clearDataForm();
                    }
                }
            })
            .catch((err) => {
                console.log("Error al enviar el formualario dinamico", err);
                (this.$swal as any).close();
                // Emite notificacion de almacenamiento de datos
                this.crudComponent["pushNotification"](
                    "Error",
                    err.response.data.message,
                    false
                );
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    // this.dataErrors = err.response.data.errors;
                }
            });
        }
        });
    }

    /**
     * Mensaje de confirmación para el usuario a la hora de seleccionar un tipo de estado del flujo (Elaboración, revisión, aprobación y publicar)
     *
     * @param formData dataForm del registro
     */
    public crearMensaje(formData) {
        let mensaje = "";
        // Optimización para el tipo "publicacion"
        // Tipo de acción a realizar en el documento
        let tipo = formData.get("tipo");
        let origen_documento = formData.get("origen_documento");

        let fullname = "";
        // Valida si el estado es elaboración o revisión, quiere decir que es un solo usuario
        if(tipo == "elaboracion" || tipo == "revision" || tipo == "aprobacion") {
            // De lo contrario, se obtiene el nombre completo del objeto funcionario_elaboracion_revision_object
            fullname = JSON.parse(formData.get("funcionario_elaboracion_revision_object")).fullname;
        }

        switch (tipo) {
            case "elaboracion":
                mensaje += "El documento será enviado a elaboración a: <strong>"+fullname+"</strong>.";
                break;
            case "revision":
                mensaje += "El documento será enviado a revisión a: <strong>"+fullname+"</strong>.";
                break;
            case "aprobacion":
                mensaje += "El documento será enviado a aprobación a: <strong>"+fullname+"</strong>.";
                break;
            case "publicacion":
                mensaje += origen_documento == "Producir documento en línea a través de Intraweb" ? "El documento será públicado." : "El documento será públicado.";
                break;
            default:
                mensaje += "No se especificó el proceso de publicación.";
        }

        return mensaje;
    }

    /**
     * Inicializa campos por defecto del formulario de tipo de documento
     */
    public inicializarValoresTipoDocumento() {
        /*
        * Se inicializan los campos formato_consecutivo_value y prefijo_incrementan_consecutivo_value para ayudar al usuario a
        * escoger un formato por defecto para el consecutivo del documento
        */
        this.$set(this.crudComponent["dataForm"], "formato_consecutivo_value", ["vigencia_actual", "prefijo_documento", "consecutivo_documento"]);
        this.$set(this.crudComponent["dataForm"], "prefijo_incrementan_consecutivo_value", ["vigencia_actual", "prefijo_documento"]);
        this.$set(this.crudComponent["dataForm"], "distribucion", []);
    }

    /**
     * Función para copiar la ruta del enlace de firma del documento en el portapapeles
     * @param event Enlace de la firma
     */
    public copiarEnlace(event) {
        // Se obtiene la ruta definida del documento para firmar el usuario externo
        const enlace = event.target;
        const url = enlace.href;
        // Previene el comportamiento predeterminado del evento `click`
        event.preventDefault();
        // Copia la URL al portapapeles
        navigator.clipboard.writeText(url);
        // Se visualiza la notificación al usuario
        this.crudComponent["_pushNotification"](
            "URL copiada al portapapeles"
        );
    }

    /**
     * Función para copiar la ruta del enlace de firma del documento en el portapapeles
     * @param event Enlace de la firma
     */
    public toggleArbolDocumentos(item) {
        item.expanded = !item.expanded;
    }

    /**
     * Función para reenviar el correo electrónico del documento electrónico a firmar a un usuario externo
     * @param documento datos del registro de la firma del usuario externo2
     */
    public generarNuevaVersion(documento: any) {
        this.$swal({
            title: "Se generará un nuevo documento en estado de Elaboración en la versión " + (parseInt(documento.version) + 1) + ". \n¿Desea continuar?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No",
        }).then((result: any) => {
        // Valida si se confirma el mensaje
        if (result.value) {
            // Almacena la informacion del formulario
            this.crudComponent["showLoadingGif"]("¡Creando documento!");

            // Construye los datos del formulario
            const formData: FormData = this.makeFormDataNuevaVersion(documento);
            // Valida que el metodo http sea PUT
            // formData.append("_method", "put");
            // Envia peticion de guardado de datos
            axios
            .post("generar-nueva-version", formData, {
                headers: { "Content-Type": "multipart/form-data" },
            })
            .then((res) => {
                // Cierra el mensaje de carga
                (this.$swal as any).close();

                if (res.data.type_message === "error" || res.data.type_message === "info") {
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept"),
                    });
                } else {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);
                    (this.$swal as any).close();
                    this.crudComponent["addElementToList"](dataDecrypted["data"]);
                    // Se asigna el valor de -1 a la propierdad "documento_id_procedente", para deshabilitar la acción de "Generar un nueva versión"
                    documento.documento_id_procedente = -1;
                    // Actualiza el registro actual del que se está generando la nueva versión, para deshabilitar la acción de "Generar un nueva versión"
                    Object.assign(
                        this.crudComponent["_findElementById"](documento.id, false),
                        documento
                    );

                    this.crudComponent["dataPaginator"].total++;
                    this.crudComponent["dataPaginator"].numPages = Math.ceil(
                        this.crudComponent["dataPaginator"].total /
                        this.crudComponent["dataPaginator"].pagesItems
                    );
                    this.crudComponent["dataPaginator"].currentPage = 1;

                    // Se visualiza la notificación al usuario
                    this.crudComponent["_pushNotification"](
                        "Nueva versión del documento generada correctamente"
                    );
                }
            })
            .catch((err) => {
                console.log("Error al enviar el formualario dinamico", err);
                (this.$swal as any).close();
                // Emite notificacion de almacenamiento de datos
                this.crudComponent["pushNotification"](
                    "Error",
                    err.response.data.message,
                    false
                );
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    // this.dataErrors = err.response.data.errors;
                }
            });
        }
        });
    }

    /**
     * Crea el formulario de datos para generar una nueva versión
     *
     * @author Seven Soluciones Informáticas S.A.S. - Dic. 09 - 2024
     * @version 1.0.0
     */
    public makeFormDataNuevaVersion(documento: any): FormData {
        let formData = new FormData();
        // Recorre los datos del formulario
        for (const key in documento) {
            if (documento.hasOwnProperty(key)) {
                const data = documento[key];
                // Valida si no es un objeto y si es un archivo
                if (
                typeof data != "object" ||
                data instanceof File ||
                data instanceof Date ||
                data instanceof Array
                ) {
                // Valida si es un arreglo
                if (Array.isArray(data)) {
                    data.forEach((element) => {
                        if (typeof element == "object") {
                            formData.append(`${key}[]`, JSON.stringify(element));
                        } else {
                            formData.append(`${key}[]`, element);
                        }
                    });
                } else if (data instanceof Date) {
                    formData.append(key, locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                } else {
                    formData.append(key, data);
                }
                } else if (typeof data == "object" && data !== null) {
                    formData.append(`${key}`, JSON.stringify(this.dataForm[key]));
                }
            }
        }
        return formData;
    }

}
</script>
