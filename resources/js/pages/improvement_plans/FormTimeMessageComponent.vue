<template>
    <div>
        <!-- begin #modal-form-holiday-calendars -->
        <div class="modal fade" :id="`modal-form-${this.name}`">
            <div class="modal-dialog modal-lg">
                <form @submit.prevent="save()" id="form-holiday-calendars">
                    <div class="modal-content border-0">
                        <div class="modal-header bg-blue">
                            <h4 class="modal-title text-white">
                                Configuración de tiempos de alerta y mensajes
                                para planes de acción próximos a vencer
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
                            <div class="panel" data-sortable-id="ui-general-1">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Días no hábiles</strong>
                                    </h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- Category Id Field -->
                                            <div
                                                class="form-group row m-b-15 justify-content-center"
                                            >
                                                <date-picker
                                                    :value="dataForm"
                                                    :is-inline="true"
                                                    name-field="date"
                                                    mode="multiple"
                                                    :key="keyRefresh"
                                                    endpoint="holiday-calendar"
                                                >
                                                </date-picker>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <!-- Star Time AM Field -->
                                            <div class="form-group row m-b-15">
                                                <label
                                                    class="col-form-label col-md-12 required"
                                                    for="star_time_am"
                                                    >Dias anticipados:</label
                                                >
                                                <div class="col-md-12">
                                                    <input
                                                        type="number"
                                                        class="form-control"
                                                        v-model="
                                                            dataForm.anticipated_days
                                                        "
                                                        required
                                                    />
                                                    <small
                                                        >Para configurar los
                                                        días anticipados de la
                                                        fecha máxima en un plan
                                                        de mejoramiento, utiliza
                                                        este campo. Por ejemplo,
                                                        si configura 5 días y la
                                                        fecha máxima es el 20 de
                                                        junio, la notificación
                                                        se enviará
                                                        automáticamente el 15 de
                                                        junio. Esto brindará un
                                                        recordatorio previo para
                                                        que los evaluados puedan
                                                        tomar las acciones
                                                        necesarias con
                                                        anticipación.</small
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <!-- Time End AM Field -->
                                            <div class="form-group row m-b-15">
                                                <label
                                                    class="col-form-label col-md-12 required"
                                                    for="time_end_am"
                                                    >Mensaje:</label
                                                >
                                                <div class="col-md-12">
                                                    <textarea class="form-control" v-model="dataForm.message"></textarea>
                                                    <small
                                                        >Por favor, ingresa el mensaje que desea que reciban los evaluados.</small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end panel-body -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                @click="clearDataForm()"
                                class="btn btn-white"
                                data-dismiss="modal"
                            >
                                <i class="fa fa-times mr-2"></i> Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-2"></i> Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- end #modal-form-holiday-calendars -->
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import * as bootstrap from "bootstrap";

import { Locale } from "v-calendar";
import { jwtDecode } from "jwt-decode";

const locale = new Locale();

/**
 * Componente para agregar los dias festivos y el horario laboral
 *
 * @author Carlos Moises Garcia T. - Oct. 29 - 2020
 * @version 1.0.0
 */
@Component
export default class FormTimeMessageComponent extends Vue {
    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: true, default: "holiday-calendars" })
    public name: string;

    /**
     * Datos del formulario
     */
    public dataForm: any;

    /**
     * Errores del formulario
     */
    public dataErrors: any;

    /**
     * Key autoincrementable y unico para
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;

    /**
     * Valida si los valores del formulario
     * son para actualizar o crear
     */
    public isUpdate: boolean;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    /**
     * Constructor de la clase
     *
     * @author Carlos Moises Garcia T. - Oct. 27 - 2020
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
        this.dataForm = {};
        this.dataErrors = {};
        this.isUpdate = false;

        this.lang = (this.$parent as any).lang;
    }

    /**
     * Limpia los datos del fomulario
     *
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this.dataForm = {};
        // Limpia errores
        this.dataErrors = {};
        // Actualiza componente de refresco
        this._updateKeyRefresh();
        // Limpia valores del campo de archivos
        $("input[type=file]").val(null);
    }

    public inputEvents(): void {
        // console.log("hola");
    }

    /**
     * Cargar los datos
     *
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
     * @version 1.0.0
     *
     * @param dataElement elemento de grupo de trabajo
     */
    public loadCalendar(dataElement: object) {
        // Abre el formulario modal del calendario laboral
        $(`#modal-form-${this.name}`).modal("show");
        // Carga la lista de elementos de horario laboral
        this._getDataList();
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
                        // console.log(data);
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
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
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
     * Guarda la informacion en base de datos
     *
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
     * @version 1.0.0
     */
    public store(): void {
        this.$swal({
            title: this.lang.get("trans.loading_save"),
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });

        // Envia peticion de guardado de datos
        axios
            .post(this.name, this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                // Agrega elemento nuevo a la lista
                // (this.$parent as any).dataList.unshift(res.data.data);

                (this.$swal as any).close();

                // Cierra fomrulario modal
                $(`#modal-form-${this.name}`).modal("toggle");
                // Limpia datos ingresados
                this.clearDataForm();
                // Emite notificacion de almacenamiento de datos
                (this.$parent as any)._pushNotification(res.data.message);
            })
            .catch(err => {
                // Issues data storage notification
                (this.$parent as any)._pushNotification(
                    "Error al guardar los datos",
                    false,
                    "Error"
                );
                // Validate if there are errors associated with the form
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                }
            });
    }

    /**
     * Obtiene la lista de datos
     *
     * @author Carlos Moises Garcia T. - Oct. 29 - 2020
     * @version 1.0.0
     */
    private _getDataList(): void {
        // Envia peticion de obtener todos los datos del recurso
        axios
            .get("get-configuration-time-messages")
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({data:{}}, dataPayload);
 
                let hours = dataDecrypted["data"];
                this.$set(this.dataForm , 'anticipated_days', hours["anticipated_days"]);
                this.$set(this.dataForm , 'message', hours["message"]);

                // // Refresh refresh component
                this._updateKeyRefresh();
            })
            .catch(err => {
                // console.log('Error al obtener la lista.');
                (this.$parent as any)._pushNotification(
                    "Error al obtener la lista de datos",
                    false,
                    "Error"
                );
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
    }
}
</script>
