<template>
    <!-- begin #modal-form -->
    <div class="modal fade" :id="modalId">
        <div :class="`modal-dialog modal-${sizeModal}`">
            <form @submit.prevent="store()" enctype="multipart/form-data">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            {{ title | capitalize }}
                        </h4>
                        <button
                            @click="clearDataForm()"
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true"
                        >
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Campos de formulario -->
                        <slot name="fields" :data-form="dataForm"> </slot>
                    </div>
                    <div class="modal-footer">
                        <button
                            @click="clearDataForm()"
                            class="btn btn-white"
                            data-dismiss="modal"
                        >
                            <i class="fa fa-times mr-2"></i>Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Descargar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- end #modal-form -->
</template>

<script lang="ts">
import { Prop, Component, Vue } from "vue-property-decorator";
import axios from "axios";
import * as bootstrap from "bootstrap";
import { Locale } from "v-calendar";

const locale = new Locale();

/**
 * Componente que permite crear ub formulario con campos dinamicos
 *
 * [saved] emisor de evento cuando se efectua el envio de los datos del formulario,
 *  se usa para ejecutar una funcion externa: @saved como directiva de este componente
 *  se puede acceder a:
 *  - $event: {data: any = 'valor devuelto en la peticion', isUpdate: boolean = 'Valida si es una actualizacion o nuevo registro'}
 *
 * @author Jhoan Sebastian Chilito S. - Ene. 18 - 2021
 * @version 1.0.0
 *
 * @property { any }        dataForm campos del formulario
 * @property { String }     endpoint Url de guardado de datos
 * @property { Boolean }    isUpdate Habilita el formulario para que la peticion sea un PUT
 * @property { String }     confirmationMessageSaved Mensaje de confirmacion de almacenamiento de datos,
 *                          Habilita la funcion de confirmacion de almacenamiento de datos
 * @property { String }     modalId Id del modal, para ejecutar la accion de abrir el modal desde fuera del componente
 * @property { String }     title Titulo del formulario modal
 * @property { String }     sizeModal Tamamo del formulario modal
 *
 * @emits saved emisor de evento cuando se efectua el envio de los datos del formulario
 *
 */
@Component
export default class CreateReportComponent extends Vue {
    /**
     * Campos del formulario
     */
    @Prop({ type: Object, default: () => ({}) })
    public dataForm: any;

    /**
     * Url de guardado de datos
     */
    @Prop({ type: String, required: true })
    public endpoint: string;

    /**
     * Habilita el formulario para que la peticion sea un PUT
     * por defecto es POST
     */
    @Prop({ type: Boolean, default: false })
    public isUpdate: boolean;

        /**
     * Habilita el formulario para que se guarde y despues elimine el objeto
     */
    @Prop({ type: Boolean, default: false })
    public isDelete: boolean;

    /**
     * Mensaje de confirmacion de almacenamiento de datos
     */
    @Prop({ type: String })
    public confirmationMessageSaved: string;

    /**
     * Id del modal, para ejecutar la accion de abrir el modal desde fuera del componente
     */
    @Prop({ type: String, required: true })
    public modalId: string;

    /**
     * Titulo del formulario modal
     */
    @Prop({ type: String, default: "Modal Form" })
    public title: string;

    /**
     * Tamano de la ventana del formulario modal,
     *  basado el lo estilos css del template
     *
     * [opciones]: 'xl', 'lg', 'md', 'sm'
     */
    @Prop({ type: String, default: "xl" })
    public sizeModal: string;


    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;
    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 18 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.lang = (this.$parent as any).lang;
    }

    /**
     * Inicializa valores del dataform
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 18 - 2021
     * @version 1.0.0
     */
    public initValues() {
        // Inicializa el objeto dataform para recibir cualquier campo
        this.$emit("update:dataForm", {});
    }

    /**
     * Limpia los datos del fomulario
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del formulario
        this.initValues();
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
     * Guarda la informacion del formulario dinamico
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 23 - 2021
     * @version 1.0.0
     */
    public store() {
        // Construye los datos del formulario
        const formData: FormData = this.makeFormData();
        let date
        date = this.dataForm.event_date
        let day = `${(date.getDate())}`.padStart(2,'0');     
        let month = date.getMonth() + 1
        let year = date.getFullYear()

        if(month < 10){
            date = `${year}-0${month}-${day}`
        }else{
            date = `${year}-${month}-${day}`
        }


        // Envia peticion de guardado de datos

        this.$swal({
            title: "Cargando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .get(this.endpoint+'/'+ date +','+ this.dataForm.report)
            .then(res => {

                window.open(this.endpoint+'/'+ date +','+ this.dataForm.report,'_blank');
                // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {
                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {
                        
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.modalId}`).modal("toggle");
                        // Limpia datos ingresados
                        this.clearDataForm();
                        
                        // Valida que se retorne los datos desde el controlador
                        if (res.data.data) {
                            // Emite evento de guardado para quien lo solicite
                            this.$emit("saved", {
                                data: res.data.data,
                                isUpdate: this.isUpdate,
                                isDelete: this.isDelete
                            });
                        }

                    }
                    // Abre el swal de la respusta de la peticion
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    // Valida que se retorne los datos desde el controlador
                    if (res.data.data) {
                        // Emite evento de guardado para quien lo solicite
                        this.$emit("saved", {
                            data: res.data.data,
                            isUpdate: this.isUpdate,
                            isDelete: this.isDelete
                        });
                    }
                    // Cierra el swal de guardando datos
                    (this.$swal as any).close();
                    // Cierra fomrulario modal
                    $(`#${this.modalId}`).modal("hide");
                    // Limpia datos ingresados
                    this.clearDataForm();
                    // Emite notificacion de almacenamiento de datos
                    this.pushNotification("Ok", res.data.message, true);
                }
            })
            .catch(err => {
                console.log("Error al enviar el formualario dinamico", err);
                (this.$swal as any).close();
                // Emite notificacion de almacenamiento de datos
                this.pushNotification(
                    "Error",
                    "Error descargando reporte",
                    false
                );
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    // this.dataErrors = err.response.data.errors;
                }
            });
    }

    /**
     * Visualiza notificacion por accion
     *
     * @author Jhoan Sebastian Chilito S. - May. 09 - 2020
     * @version 1.0.0
     *
     * @param message mesaje de notificacion
     * @param isPositive valida si la notificacion debe ser posiva o negativa
     * @param title titulo de notificacion
     */
    public pushNotification(
        title: string = "OK",
        message: string,
        isPositive: boolean = true
    ): void {
        // Datos de notificacion (Por defecto guardar)
        const toastOptions = {
            closeButton: true,
            closeMethod: "fadeOut",
            timeOut: 3000,
            tapToDismiss: false
        };
        // Valida el tipo de toast que se debe visualiza
        if (isPositive) {
            // Visualiza toast positivo
            toastr.success(message, title, toastOptions);
        } else {
            toastOptions.timeOut = 0;
            // Visualiza toast negativo
            toastr.error(message, title, toastOptions);
        }
    }
}
</script>
