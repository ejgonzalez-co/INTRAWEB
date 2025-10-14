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
 * @author Seven Soluciones Informáticas S.A.S - Jun. 4 - 2023
 * @version 1.0.0
 */
@Component
export default class CorrespondenceValidatorComponent extends Vue {

    /**
     * Errores del formulario
     */
    public dataErrors: any;

    /**
     * Datos del formulario
     */
    public dataForm: any;

    /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    /**
     * Campos de busqueda
     */
    public searchFields: any;

    /**
     * Datos de visualizacion
     */
    public dataPaginator: any;

    /**
     * Lista de elementos
     */
    public dataList: Array<any>;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 13 - 2020
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
        this.dataErrors = {};
        this.dataForm = {};
        this.lang = (this.$parent as any).lang;
        this.searchFields = {};
        this.dataPaginator = {
            currentPage: 1,
            numPages: 1,
            pagesItems: 15,
            total: 0
        }
        this.dataList = [];
    }

    /**
     * Se ejecuta cuando el componente ha sido creado
     */
    created() {}

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


    /**
     * Consulta la validez de la correspondencia según el código
     *
     * @author Seven Soluciones Informáticas S.A.S. Sep 4 - 2023
     * @version 1.0.0
     *
     * @param codigo Código de verificación de la correspondencia
     */
    public validar_correspondencia_enviada(codigo: any): void {
        // Valida que el campo del código de validación venga vacío
        if (!codigo) {
            // Si no ingresó un código de validación, muestra un mensaje indicándole al usuario que debe ingresar uno
            this.$swal({
                icon: "warning",
                title: "Advertencia",
                html: "Debe ingresar un código de validación",
                confirmButtonText: this.lang.get("trans.Accept")
            }).then((res) => {
                // Se esperan 300 milisegundos para enfocar el campo del código de validación
                setTimeout(() => {
                    jQuery("#validation_code").focus();
                }, 300);
            });
            return;
        }
        // Envia peticion de obtener la correspondencia según el código de validación
        axios.post('validar-correspondence-external-codigo/'+codigo)
        .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            // Valida si encontró una correspondencia según el código
            if(dataDecrypted["data"].length) {
                // Agrega la correspondencia encontrada a la lista
                this.dataList = dataDecrypted["data"];
                // Muestra el valor de 1 correspondencia encontrada
                this.dataPaginator.total= 1;
                // Mensaje para indicarle al usuario que la correspondencia fue verificada y validada
                this.$swal({
                    icon: "success",
                    html: "El consecutivo existe en la Intraweb, sin embargo, el contenido no está validado. Suba el documento para autenticar su integridad..",
                    confirmButtonText: this.lang.get("trans.Accept")
                });
            } else {
                // Muestra un mensaje al usuario indicándole que no encontró una correspondencia según el código ingresado
                this.$swal({
                    icon: "warning",
                    html: "Lamentablemente, el código que ingresó no concuerda con una correspondencia enviada generada por la Intraweb. Por favor, compruebe el código que ingresó para asegurarse de que es correcto o póngase en contacto con el servicio de atención al cliente para obtener más información.",
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });
                // Elimina todos los elementos de la lista
                this.dataList = [];
                // Muestra el valor de 0 en el total de registros
                this.dataPaginator.total = 0;
            }
        })
        .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia'+err, false, 'Error');
        });
    }

    public adjuntarDocumento(event: any, hash: string): void {
        // console.log(event);
        // Almacena el objeto de tipo file adjuntado en el rótulo
        this.dataForm["documento_adjunto"] = event.target.files[0];
        this.dataForm["hash"] = hash;
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Erika Johana Gonzalez Cuartas - Oct. 17 - 2020
     * @version 1.0.0
     */
     public validar_documento_correspondencia_enviada(): void {
        // Valida que el campo del código de validación venga vacío
        if (!this.dataForm["documento_adjunto"]) {
            // Si no ingresó un código de validación, muestra un mensaje indicándole al usuario que debe ingresar uno
            this.$swal({
                icon: "warning",
                title: "Advertencia",
                html: "Para que podamos validar su solicitud, necesitamos que adjunte un documento",
                confirmButtonText: this.lang.get("trans.Accept")
            }).then((res) => {
                // Se esperan 300 milisegundos para enfocar el campo del código de validación
                setTimeout(() => {
                    jQuery("#documento_adjunto").focus();
                }, 300);
            });
            return;
        }
        this.$swal({
            title: "Estamos validando el documento",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });

        // Envia peticion de guardado de datos
        axios
            .post("validar-correspondence-external-documento", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Cierra el swal de guardando datos
                (this.$swal as any).close();
                // Valida el tipo de alerta que de debe mostrar
                if (dataDecrypted["data"]["documentos_identicos"]) {
                    // Mensaje para indicarle al usuario que la correspondencia fue verificada y validada
                    this.$swal({
                        icon: "success",
                        html: "Hemos verificado el documento adjunto y hemos confirmado que es exactamente el mismo que se encuentra en el sistema de Intraweb. El contenido, la fecha y el formato del documento adjunto coinciden con los datos del sistema.",
                        confirmButtonText: this.lang.get("trans.Accept")
                    });
                } else {
                    // Muestra un mensaje al usuario indicándole que no encontró una correspondencia según el código ingresado
                    this.$swal({
                        icon: "warning",
                        html: "Lamentablemente, el documento adjunto no coincide con el documento almacenado en esta correspondencia, el documento adjunto tiene un contenido diferente al del documento almacenado. Por favor, comprueba el documento adjunto para asegurarte de que es el correcto",
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept")
                    });
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

    /**
     * Consulta la validez de la correspondencia según el código
     *
     * @author Seven Soluciones Informáticas S.A.S. Sep 4 - 2023
     * @version 1.0.0
     *
     * @param codigo Código de verificación de la correspondencia
     */
     public validar_correspondencia_interna(codigo: any): void {
        // Valida que el campo del código de validación venga vacío
        if (!codigo) {
            // Si no ingresó un código de validación, muestra un mensaje indicándole al usuario que debe ingresar uno
            this.$swal({
                icon: "warning",
                title: "Advertencia",
                html: "Debe ingresar un código de validación",
                confirmButtonText: this.lang.get("trans.Accept")
            }).then((res) => {
                // Se esperan 300 milisegundos para enfocar el campo del código de validación
                setTimeout(() => {
                    jQuery("#validation_code").focus();
                }, 300);
            });
            return;
        }
        // Envia peticion de obtener la correspondencia según el código de validación
        axios.post('validar-correspondence-internal-codigo/'+codigo)
        .then((res) => {
            let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

            const dataDecrypted = Object.assign({}, dataPayload);

            // Valida si encontró una correspondencia según el código
            if(dataDecrypted["data"].length) {
                // Agrega la correspondencia encontrada a la lista
                this.dataList = dataDecrypted["data"];
                // Muestra el valor de 1 correspondencia encontrada
                this.dataPaginator.total= 1;
                // Mensaje para indicarle al usuario que la correspondencia fue verificada y validada
                this.$swal({
                    icon: "success",
                    html: "El consecutivo existe en la Intraweb, sin embargo, el contenido no está validado. Suba el documento para autenticar su integridad..",
                    confirmButtonText: this.lang.get("trans.Accept")
                });
            } else {
                // Muestra un mensaje al usuario indicándole que no encontró una correspondencia según el código ingresado
                this.$swal({
                    icon: "warning",
                    html: "Lamentablemente, el código que ingresó no concuerda con una correspondencia interna generada por la Intraweb. Por favor, compruebe el código que ingresó para asegurarse de que es correcto o póngase en contacto con el servicio de atención al cliente para obtener más información.",
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept")
                });
                // Elimina todos los elementos de la lista
                this.dataList = [];
                // Muestra el valor de 0 en el total de registros
                this.dataPaginator.total = 0;
            }
        })
        .catch((err) => {
            // console.log('Error al obtener la lista.');
            (this.$parent as any)._pushNotification('Error al leer Corrrespondencia'+err, false, 'Error');
        });
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Seven Soluciones Informáticas S.A.S. - Sep. 6 - 2023
     * @version 1.0.0
     */
     public validar_documento_correspondencia_interna(): void {
        // Valida que el campo del código de validación venga vacío
        if (!this.dataForm["documento_adjunto"]) {
            // Si no ingresó un código de validación, muestra un mensaje indicándole al usuario que debe ingresar uno
            this.$swal({
                icon: "warning",
                title: "Advertencia",
                html: "Para que podamos validar su solicitud, necesitamos que adjunte un documento",
                confirmButtonText: this.lang.get("trans.Accept")
            }).then((res) => {
                // Se esperan 300 milisegundos para enfocar el campo del código de validación
                setTimeout(() => {
                    jQuery("#documento_adjunto").focus();
                }, 300);
            });
            return;
        }
        this.$swal({
            title: "Estamos validando el documento",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });

        // Envia peticion de guardado de datos
        axios
            .post("validar-correspondence-internal-documento", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Cierra el swal de guardando datos
                (this.$swal as any).close();
                // Valida el tipo de alerta que de debe mostrar
                if (dataDecrypted["data"]["documentos_identicos"]) {
                    // Mensaje para indicarle al usuario que la correspondencia fue verificada y validada
                    this.$swal({
                        icon: "success",
                        html: "Hemos verificado el documento adjunto y hemos confirmado que es exactamente el mismo que se encuentra en el sistema de Intraweb. El contenido, la fecha y el formato del documento adjunto coinciden con los datos del sistema.",
                        confirmButtonText: this.lang.get("trans.Accept")
                    });
                } else {
                    // Muestra un mensaje al usuario indicándole que no encontró una correspondencia según el código ingresado
                    this.$swal({
                        icon: "warning",
                        html: "Lamentablemente, el documento adjunto no coincide con el documento almacenado en esta correspondencia, el documento adjunto tiene un contenido diferente al del documento almacenado. Por favor, comprueba el documento adjunto para asegurarte de que es el correcto",
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get("trans.Accept")
                    });
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
}
</script>
