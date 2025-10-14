<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

import utility from "../../utility";

import { Locale } from "v-calendar";
import { data } from "jquery";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Erika Johana Gonzalez Cuartas - Oct. 13 - 2020
 * @version 1.0.0
 */
@Component
export default class MetadatosComponent extends Vue {
    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: false, default: "metadatos" })
    public name: string;

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
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 13 - 2020
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
        this.componentKey = 0;
        this.dataForm = {};

        this.dataRotule = {};

        this.dataErrors = {};
        this.isUpdate = false;
        this.radicatied = false;
        this.lang = (this.$parent as any).lang;
    }

    /**
     * Se ejecuta cuando el componente ha sido creado
     */
    created() {}

    /**
     * Limpia los datos del fomulario
     *
     * @author Erika Johana Gonzalez C.. - Abr. 15 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this.dataForm = {};

        // Inicializa valores del datashow
        (this.$parent as any)["dataShow"] = {};
        // Limpia errores
        this.dataErrors = {};
        // Actualiza componente de refresco
        this._updateKeyRefresh();
        // Limpia valores del campo de archivos
        $("input[type=file]").val(null);
        const iframe = document.getElementById('attachmentFrame') as HTMLIFrameElement;
        iframe.src = "";
    }

    /**
     * Crea el formulario de datos para guardar
     *
     * @author Erika Johana Gonzalez C.. - 2023
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
                else{

                        if (key == 'metadatos') {
                            formData.append(`${key}[]`,JSON.stringify(data));
                        }

                    }
            }
        }
        return formData;
    }

    /**
     * Guarda los datos del formulario
     *
     * @author Erika Johana Gonzalez C.. - Abr. 14 - 2020
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
               // this.store();
         }
    }



    /**
     * Actualiza la informacion en base de datos
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 17 - 2020
     * @version 1.0.0
     */
    public update(): void {
        // Abre el swal de guardando datos
        this.$swal({
            title: this.lang.get("trans.loading_update"),
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });

        const formData: FormData = this.makeFormData();

        formData.append("_method", "put");

        // Envia peticion de guardado de datos
        axios
            .post(`inventory-documentals/${this.dataForm["id"]}`, formData, {
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


                  const parsedData = JSON.parse(dataDecrypted["data"]["metadatos"]);

                  dataDecrypted["data"]["metadatos"] = {};

                  let data = parsedData;

                  for (const selectedAttachment in data) {
                     dataDecrypted["data"]["metadatos"][selectedAttachment] = data[selectedAttachment];
                  }

                   $(`#modal-form-${this.name}`).modal('toggle');
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
     * @author Erika Johana Gonzalez C.. - Jul. 06 - 2020
     * @version 1.0.0
     */
    private _updateKeyRefresh(): void {
        this.keyRefresh++;
        (this.$parent as any)['_updateKeyRefresh']();
    }

    public add(): void {
        this.clearDataForm();
        this.isUpdate = false;

        // Actualiza componente de refresco
        this._updateKeyRefresh();

    }

    /**
     * Busca y prepara los datos a editar
     *
     * @author Erika Johana Gonzalez C.. - Abr. 15 - 2020
     * @version 1.0.0
     *
     * @param dataElement elemento a editar
     */
    public edit(dataElement: object): void {
        // Busca el elemento deseado y agrega datos al fomrulario
        this.dataForm = utility.clone(dataElement);

        let adjuntos = this.dataForm.attachment.split(',');
        this.dataForm.attachment = adjuntos;

        if (typeof this.dataForm.metadatos === 'string') {
         try {

            const parsedData = JSON.parse(this.dataForm.metadatos);
            this.dataForm.metadatos = {};

            let data = JSON.parse(parsedData);
            for (const selectedAttachment in data) {
               this.dataForm.metadatos[selectedAttachment] = data[selectedAttachment];
            }
         } catch (e) {
            console.error('Error parsing JSON:', e);
            // Manejar el error, por ejemplo, asignar un objeto vacío en caso de un error
            this.dataForm.metadatos = {};
         }
      }



        // Habilita actualizacion de datos
        this.isUpdate = true;
        // Actualiza componente de refresco
        this._updateKeyRefresh();
    }


    private getFileName(attachment) {
         if (typeof attachment === 'string') {
            const parts = attachment.split('/');
            return parts[parts.length - 1];
         } else {
            // Manejar el caso en el que attachment no es una cadena (puedes devolver un valor predeterminado o manejarlo de otra manera según tus necesidades)
            console.error('attachment no es una cadena válida:', attachment);
            return 'Nombre de archivo no disponible';
         }
      }


        private displayAttachment() {
            const selectedAttachment = this.dataForm.selectedAttachment;
            if (selectedAttachment) {
                // Obtiene la URL base del sitio
                const baseUrl = window.location.origin;
                // Quita la palabra "documentary-classification" de la ruta del archivo
                const updatedPath = selectedAttachment.replace("documentary-classification/", "");
                // Combina la URL base con la ruta del archivo
                const fullUrl = baseUrl + "/storage/" + updatedPath;

                // Usa una afirmación de tipo para decirle a TypeScript que iframe es un HTMLIFrameElement
                const iframe = document.getElementById('attachmentFrame') as HTMLIFrameElement;
                iframe.src = fullUrl;
            }
        }

        private addMetadata() {
            const item = this.dataForm.information;
            const pagina = this.dataForm.page;
            const selectedAttachment = this.dataForm.selectedAttachment;

            if (item !== undefined && item !== '' && pagina !== undefined && pagina !== '' && selectedAttachment) {
                // Asegúrate de que this.dataForm.metadatos sea un objeto inicializado
                if (!this.dataForm.metadatos) {
                    this.dataForm.metadatos = {};
                }

                // Asegúrate de que this.dataForm.metadatos[selectedAttachment] sea un array inicializado
                if (!this.dataForm.metadatos[selectedAttachment]) {
                    this.dataForm.metadatos[selectedAttachment] = [];
                }

                // Crea un nuevo objeto de metadatos
                const newMetadata = { item: item, pagina: pagina };

                // Agrega el nuevo objeto al array de metadatos para el archivo seleccionado
                this.dataForm.metadatos[selectedAttachment].push(newMetadata);

                // Limpiar los campos después de agregar el metadato
                this.dataForm.information = '';
                this.dataForm.page = '';
                this.$forceUpdate();
                this._updateKeyRefresh();
            } else {
                // Mostrar un mensaje de error si falta información
                this.$swal({
                    html: "Ingrese el Item de información y el número de página.",
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get('trans.Accept')
                });
            }
        }


        private removeMetadata(attachment, metaIndex) {
            // Elimina el metadato del archivo y el índice seleccionado
            this.dataForm.metadatos[attachment].splice(metaIndex, 1);
            // this._updateKeyRefresh();

        }



}
</script>
