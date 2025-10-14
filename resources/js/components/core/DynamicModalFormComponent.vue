<template>
    <!-- begin #modal-form -->
    <div class="modal fade" :id="modalId">
        <div :style="stylesModal" :class="`modal-dialog modal-${sizeModal}`">
            <form @submit.prevent="save()" enctype="multipart/form-data">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">{{ title }}</h4>
                        <button @click="clearDataForm()" type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div :style="routeFile ? 'display: flex;min-height: 72vh;' : ''">
                        <div class="modal-body" :class="routeFile ? 'col-md-6' : ''">

                            <slot name="fields" :data-form="dataForm">
                            </slot>
                        </div>

                        <iframe v-if="routeFile && routeFile != 'cargando_documento'" class="column" :src=" routeFile" id="editorDer" style="width: 100%; margin-right: 1px; border-right-color: rgb(0 0 0 / 56%);"></iframe>
                        <div class="col-md-6" v-else-if="routeFile">
                            <div class="spinner"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                        <button type="submit" class="btn btn-add"><i :class="classButton"></i> {{nameButton}}</button>
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
import * as bootstrap from 'bootstrap';
import { Locale } from "v-calendar";
import { jwtDecode } from 'jwt-decode';

const locale = new Locale();
interface SignToImageComponent {
  guardarImagen(): Promise<string | null>;
}
/**
 * Componente que permite crear ub formulario con campos dinamicos
//  *
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
export default class DynamicModalFormComponent extends Vue {

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
     * Ruta de documento, si es pantalla dividida
     */
     @Prop({ type: String, required: false })
    public routeFile: string;

       /**
     * Ruta de documento, si es pantalla dividida
     */
     @Prop({ type: String, required: false })
    public stylesModal: string;

    /**
     * Habilita el formulario para que la peticion sea un PUT
     * por defecto es POST
     */
    @Prop({ type: Boolean, default: false })
    public isUpdate: boolean;

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
    @Prop({ type: String, default: 'Modal Form' })
    public title: string;

        /**
     * Nombre del boton
     */
    @Prop({ type: String, default: 'Guardar' })
    public nameButton: string;


            /**
     * Nombre del boton
     */
    @Prop({ type: String, default: 'fa fa-save mr-2' })
    public classButton: string;

    /**
     * Tamano de la ventana del formulario modal,
     *  basado el lo estilos css del template
     *
     * [opciones]: 'xl', 'lg', 'md', 'sm'
     */
    @Prop({ type: String, default: 'xl' })
    public sizeModal: string;

    /**
     * Condiciona si se vacia el objeto dataForm del formulario o no
     */
    @Prop({type: Boolean, default: true})
    public inicializarDataForm: boolean;

    /**
     * Funcionalidades de traduccion de texto
     */
     public lang: any;

    // Variable para controlar firma del usuario
    public firmaUsuario: string;

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
        this.$emit('update:dataForm', {});
    }

    /**
     * Limpia los datos del fomulario
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Valida si se debe inicializar en vacio el dataForm del formulario
        if(this.inicializarDataForm)
            // Inicializa valores del formulario
            this.initValues();
        // Limpia valores del campo de archivos
        $('input[type=file]').val(null);
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
     * @author Jhoan Sebastian Chilito S. - Ene. 18 - 2021
     * @version 1.0.0
     */
    public save(): void {
        // Valida si se debe validar el amacenamiento de los datos del formulario
        if (this.confirmationMessageSaved) {
            this.$swal({
                title: 'Confirmación',
                html: this.confirmationMessageSaved,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result: any) => {
                // Valida si se confirma el mensaje
                if (result.value) {
                    // Almacena la informacion del formulario
                    this.store();
                }
            });
        } else {
            // Almacena la informacion del formulario
            this.store();
        }
    }

    public obtenerFirma(): Promise<string> {
        const signToImageComponent = this.$parent.$refs['signToImage'] as Vue & {
            retornarRutaImagen: () => Promise<any>;
        };

        return signToImageComponent.retornarRutaImagen().then((rutaImagen) => {

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
    /**
     * Guarda la informacion del formulario dinamico
     *
     * @author Jhoan Sebastian Chilito S. - Ene. 23 - 2021
     * @version 1.0.0
     */
    public async store() {


        (this.$parent as any).showLoadingGif(this.lang.get("trans.loading_update"));

        if (this.endpoint.includes('sign')) {

            if (this.dataForm.usar_firma_cargada=='No') {

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
        }



        // Construye los datos del formulario
        const formData: FormData = this.makeFormData();
        // Valida que el metodo http sea PUT
        if (this.isUpdate) {
            formData.append('_method', 'put');
        }
        formData.append("firma_desde_componente", this.firmaUsuario);
        // Envia peticion de guardado de datos
        axios.post(this.endpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
        .then((res) => {

            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            if (res.data.type_message != "info") {

                // Valida que se retorne los datos desde el controlador
                if (dataDecrypted["data"]) {
                    // Emite evento de guardado para quien lo solicite
                    this.$emit('saved', { 'data': dataDecrypted["data"], 'isUpdate': this.isUpdate });
                }
                // Cierra el swal de guardando datos
                (this.$swal as any).close();
                // Cierra fomrulario modal
                $(`#${this.modalId}`).modal('hide');
                // Limpia datos ingresados
                this.clearDataForm();
                // Valida el tipo de mensaje
                if(res.data.type_message=='error'){
                    // Emite notificacion de almacenamiento de datos
                    this.pushNotification('Error', res.data.message, false);
                } else {
                    // Emite notificacion de almacenamiento de datos
                    this.pushNotification('¡Éxito!', res.data.message, true);
                }

            }else{
                this.$swal({
                    icon: res.data.type_message,
                    html: res.data.message,
                    allowOutsideClick: false,
                    confirmButtonText: 'Aceptar'
                });
            }

        })
        .catch((err) => {
            console.log('Error al enviar el formualario dinamico', err);
            (this.$swal as any).close();
            // Emite notificacion de almacenamiento de datos
            this.pushNotification('Error', err.response.data.message,  false);
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
    public pushNotification(title: string = 'OK', message: string, isPositive: boolean = true): void {
        // Datos de notificacion (Por defecto guardar)
        const toastOptions = {
            closeButton: true,
            closeMethod: 'fadeOut',
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
