<template>
    <div>
        <!-- begin #modal-form-form-responsable-category -->
        <div class="modal fade" id="modal-form-external-provider">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Formulario Finalizar solicitud {{ this.consecutive ?? "" }}
                        </h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true"
                        >
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <strong>Información general</strong>
                                </div>
                            </div>
                            <div class="panel-body">
                                <form @submit.prevent="save()">
                                    <div class="form-group row m-b-15">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="mant_asset_type_id"
                                            >Finalizar solicitud:
                                        </label>
                                        <div class="col-md-8">
                                            <select
                                                class="form-control"
                                                v-model="dataForm.estado_proveedor"
                                                required
                                            >
                                                <option value="Finalizado"
                                                    >Sí- Finalizar</option
                                                >
                                                <option value="Pendiente por finalizar"
                                                    >No- Pendiente por
                                                    finalizar</option
                                                >
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado'">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="mant_asset_type_id"
                                            >Kilometraje/Horómetro actual:
                                        </label>
                                        <div class="col-md-8" v-if="loading.current_mileage_or_hourmeter">
                                            <div class="skeleton skeleton-input"></div>
                                        </div>                                            
                                        <div class="col-md-8" v-else>
                                            <input
                                                v-model="dataForm.current_mileage_or_hourmeter"
                                                type="text"
                                                class="form-control"
                                                readonly
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado'">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="date_entry"
                                            >Fecha de ingreso:
                                        </label>
                                        <div class="col-md-8">
                                            <input type="date" class="form-control"
                                                v-model="dataForm.date_entry" required />
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado'">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="mant_asset_type_id"
                                            >Kilometraje/Horómetro Recibido:
                                        </label>
                                        <div class="col-md-8" v-if="loading.mileage_or_hourmeter_received">
                                            <div class="skeleton skeleton-input"></div>
                                        </div>
                                            
                                        <div class="col-md-8" v-else>
                                            <input
                                                v-model="
                                                    dataForm.mileage_or_hourmeter_received
                                                "
                                                type="text"
                                                class="form-control"
                                                :disabled="true"
                                            />
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado'">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="date_work_completion"
                                            >Fecha finalización del trabajo:
                                        </label>                                    
                                        <div class="col-md-8">
                                            <input
                                                v-model="
                                                    dataForm.date_work_completion
                                                "
                                                type="date"
                                                class="form-control"
                                                required
                                                />
                                        </div>
                                    </div>                                    

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado' || dataForm.estado_proveedor == 'Pendiente por finalizar'">
                                        <label
                                            class="col-form-label col-md-4 required"
                                            for="mant_asset_type_id"
                                            >Observación:
                                        </label>
                                        <div class="col-md-8">
                                            <textarea
                                                v-model="
                                                    dataForm.provider_observation
                                                "
                                                class="form-control"
                                                cols="30"
                                                rows="10"
                                                required
                                            ></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.estado_proveedor == 'Finalizado'">
                                        <label
                                            class="col-form-label col-md-4"
                                            for="mant_asset_type_id"
                                            >Evidencias:
                                        </label>
                                        <div class="col-md-8">
                                            <input-file :value="dataForm" name-field="url_evidences" :max-files="10"
                                                :max-filesize="5" file-path="public/maintenance/identification_needs/providers_externals/evidences"
                                                message="Arrastre o seleccione los archivos" help-text="Utilice este campo para cargar un documento de la solicitud. El tamaño máximo permitido es de 5 MB."
                                                :is-update="isUpdate"  ruta-delete-update="correspondence/internals-delete-file" :id-file-delete="dataForm.id">
                                            </input-file>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-white" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i>Cerrar
                        </button>
                        <button type="button" @click="save()" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-form-responsable-category -->
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from "jwt-decode";

import utility from "../../utility";

import { Locale } from "v-calendar";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Kleverman Salazar Florez. - Ene. 23 - 2024
 * @version 1.0.0
 */
@Component
export default class ExternalProviderRequestForm extends Vue {
    /**
     * Nombre de la entidad a afectar
     */
    @Prop({ type: String, required: true, default: "modal-form-assets-tics" })
    public name: string;

    /**
     * Lista de elementos
     */
    public dataListCategory: any;

    public dataListTypesAssets: any;

    /**
     * Datos del formulario
     */
    public dataForm: any;

    /**
     * Datos del formulario
     */
    public loading: any;

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

    public formType: String;

    public fuel_type: String;

    public idForm: number;

    public frontReferences: any;

    public rearReferences: any;
    public consecutive: string;

    /**
     * Constructor de la clase
     *
     * @author Carlos Moises Garcia T. - Oct. 13 - 2020
     * @version 1.0.0
     */
    constructor() {
        super();
        this.dataListCategory = {};

        this.dataListTypesAssets = {};
        this.keyRefresh = 0;

        this.dataForm = {
            id:0,
            estado_proveedor: "",
            mileage_or_hourmeter_received: "",
            provider_observation: "",
            current_mileage_or_hourmeter: "",
            date_entry: null,
            date_work_completion: null,
            url_evidences: null,
            created_at: ""
        };
        this.consecutive = (this.$parent as any)["dataForm"]["consecutivo"];
        this.loading = {
            current_mileage_or_hourmeter:true,
            mileage_or_hourmeter_received: true,
        };
        this.frontReferences = [];
        this.rearReferences = [];
        this.dataErrors = {};
        this.isUpdate = false;

        this.lang = (this.$parent as any).lang;
    }

    /**
     * Se ejecuta cuando el componente ha sido creado
     */
    created() {
        // Carga la lista de elementos de categorias
        this._getDataListCategory();
    }

    public openForm(requestId : number): void {
        $(`#modal-form-external-provider`).modal("show");

        // Formate los datos del formulario
        this.dataForm = {
            id:requestId,
            estado_proveedor: "",
            mileage_or_hourmeter_received: "",
            provider_observation: "",
            current_mileage_or_hourmeter: "",
            created_at: ""
        };

        this.getRequestInformationExternalProvider(requestId);
        this.getCurrentMileageOrhourmeter(requestId);
    }

    public getRequestInformationExternalProvider(requestId: number) : void{
        axios
            .get(`request-information-external-provider/${requestId}`)
            .then(res => {
                const request = res.data.data ? jwtDecode(res.data.data) : null;

                this.dataForm.estado_proveedor = request["data"]["estado_proveedor"];
                this.dataForm.created_at = request["data"]["created_at"];

                this.dataForm.mileage_or_hourmeter_received = this.dataForm.current_mileage_or_hourmeter == "No aplica" ? "No aplica" : "";

                this.$forceUpdate();
            })
            .catch(error => {
                console.log(error);
            });
    }

    public getCurrentMileageOrhourmeter(requestId: number): void {
        axios
            .get(`current-mileage-hourmeter/${requestId}`)
            .then(res => {
                const request = res.data.data ? jwtDecode(res.data.data) : null;

                this.dataForm.current_mileage_or_hourmeter = request["data"];

                this.dataForm.mileage_or_hourmeter_received = this.dataForm.current_mileage_or_hourmeter == "No aplica" ? "No aplica" : "";

                this.$set(this.loading, "current_mileage_or_hourmeter", false);

                this.$forceUpdate();
            })
            .catch(error => {
                console.log(error);
            });
    }

    @Watch("dataForm.date_entry")
    public getMileageOrHourmeterReceived() : void{
        this.$set(this.loading, "mileage_or_hourmeter_received", true);

        axios
            .get(`fual-management-by-date/${this.dataForm.id}/${this.dataForm.date_entry}`)
            .then(res => {
                const request = res.data.data ? jwtDecode(res.data.data) : null;

                this.dataForm.mileage_or_hourmeter_received = request["data"]["current_hourmeter_or_mileage"];

                this.$forceUpdate();
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                this.$set(this.loading, "mileage_or_hourmeter_received", false);
            });
    }


    /**
     * Limpia los datos del fomulario
     *
     * @author German Gonzalez V. - May. 7 - 2021
     * @version 1.0.0
     */
    public clearDataForm(): void {
        // Inicializa valores del dataform
        this.dataForm = {
            mileage_or_hourmeter_received: "",
            composition_equipment_leca: [],
            maintenance_equipment_leca: [],
            specifications_equipment_leca: [],
            schedule_inventory_leca: [],
            rubros_asignados: []
        };
        // Limpia errores
        this.dataErrors = {};
        // Actualiza componente de refresco
        this._updateKeyRefresh();
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
        // Limpia los errores anteriores
        this.dataErrors = {};
        this.$swal({
                title: "Estás a punto de enviar una notificación informativa sobre el estado de la orden de servicio a la Jefatura de Mantenimiento de la EPA.<br> <br> ¿Está seguro de aceptar?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si",
                cancelButtonText: "No"
            }).then((result: any) => {
                // Valida si se confirma el mensaje
                if (result.value) {
                    // Valida si los datos son para actualizar
                    if (this.isUpdate) {
                        // Actualiza un registro existente
                        this.update();
                    } else {
                        // Almacena un nuevo registro
                        this.store();
                    }
                }
        });
    }

    /**
     * Guarda la informacion en base de datos
     *
     * @author Carlos Moises Garcia T. - Oct. 17 - 2020
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
            .post("external-provider-request", this.makeFormData(), {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({data:{}}, dataPayload);

                // Valida que se retorne los datos desde el controlador
                if (dataDecrypted.data) {
                    // Actualiza elemento modificado en la lista
                    Object.assign(
                        this._findElementById(
                            this.dataForm["id"],
                            this.dataForm["created_at"],
                            false
                        ),
                        dataDecrypted.data
                    );
                }

                (this.$swal as any).close();

                if (dataDecrypted["type_message"] == "error") {
                    this.$swal({
                        icon: dataDecrypted["type_message"],
                        html: dataDecrypted["message"],
                        confirmButtonText: this.lang.get("trans.Accept"),
                        allowOutsideClick: false
                    });
                } else {
                    // Cierra fomrulario modal
                    $(`#modal-form-external-provider`).modal("toggle");

                    // Limpia datos ingresados
                    this.clearDataForm();
                    // Emite notificacion de almacenamiento de datos
                    (this.$parent as any)._pushNotification(dataDecrypted["message"]);
                }
            })
            .catch(err => {
                (this.$swal as any).close();
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
     * Actualiza la informacion en base de datos
     *
     * @author Carlos Moises Garcia T. - Oct. 17 - 2020
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
            .post(`${this.name}/${this.dataForm["id"]}`, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            })
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({data:{}}, dataPayload);

                // Valida que se retorne los datos desde el controlador
                if (dataDecrypted.data) {
                    // Actualiza elemento modificado en la lista
                    Object.assign(
                        this._findElementById(
                            this.dataForm["id"],
                            this.dataForm["created_at"],
                            false
                        ),
                        dataDecrypted.data
                    );
                }

                // Cierra el swal de guardando datos
                (this.$swal as any).close();

                if (dataDecrypted["type_message"] == "error") {
                    this.$swal({
                        icon: dataDecrypted["type_message"],
                        html: dataDecrypted["message"],
                        confirmButtonText: this.lang.get("trans.Accept"),
                        allowOutsideClick: false
                    });
                } else {
                    // Cierra fomrulario modal
                    $(`#modal-form-${this.name}`).modal("toggle");
                    // Limpia datos ingresados
                    this.clearDataForm();
                    // Emite notificacion de actualizacion de datos
                    (this.$parent as any)._pushNotification(dataDecrypted["message"]);
                }
            })
            .catch(err => {
                (this.$swal as any).close();

                // Emite notificacion de almacenamiento de datos
                (this.$parent as any)._pushNotification(
                    "Error al guardar los datos",
                    false,
                    "Error"
                );
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                }
            });
    }

    /**
     * Busca un elemento de la lista por el id
     *
     * @author German Gonzalez V. - Ago. - 2021
     * @version 1.0.1
     *
     * @param id identificador del elemento a buscar
     * @param created_at fecha de creación del elemento a buscar
     * @param clone valida si el objeto retornado debe ser clonado
     */
    private _findElementById(
        id: number,
        created_at: string,
        clone: boolean
    ): any {
        for (let i = 0; i < (this.$parent as any).dataList.length; i++) {
            const element = (this.$parent as any).dataList[i];
            // Busca el dato a editar
            if (
                element[(this.$parent as any).customId] === id &&
                element["created_at"] === created_at
            ) {
                // Valida si se debe clonar el retorno
                return clone ? utility.clone(element) : element;
            }
        }
    }

    /**
     * Obtiene la lista de datos
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
     * @version 1.0.0
     */
    private _getDataListCategory(): void {
        // Envia peticion de obtener todos los datos del recurso de categorias
        // axios.get('get-tic-type-tic-categories')
        // .then((res) => {
        //    this.dataListCategory = res.data.data;
        //    let data = res.data.data;
        // })
        // .catch((err) => {
        //    // console.log('Error al obtener la lista.');
        //    (this.$parent as any)._pushNotification('Error al obtener la lista de datos', false, 'Error');
        // });
    }

    /**
     * Obtiene la lista de datos de los tipos de activos
     *
     * @author Carlos Moises Garcia T. - Oct. 26 - 2020
     * @version 1.0.0
     */
    private _getDataListTypesAssets(): void {
        // Envia peticion de obtener todos los datos del recurso de categorias
        axios
            .get(
                `get-type-assets-tics-by-category/${this.dataForm.type_tic_category}`
            )
            .then(res => {
                this.dataListTypesAssets = res.data.data;
                let data = res.data.data;
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

    /**
     * Detecta el cambio de selección de los elementos select de la hoja de vida de equipos
     *
     * @author German Gonzalez V. - Abr. 08 - 2021
     * @version 1.0.0
     * @param event Contiene los elementos dela opción seleccionada
     */
    private _maintenanceChange(event) {
        // Condición para validar si el value del select selccionado es No
        if (event.target.value == "No") {
            this.dataForm[event.target.name + "_frequency"] = "No aplica";
        } else if (event.target.value == "Si") {
            this.dataForm[event.target.name + "_frequency"] = "";
        }
    }

    /**
     * Consulta y elimina un tipo de activo si es posible
     *
     * @author German Gonzalez V. - Sep. 14 - 2021
     * @version 1.0.0
     * @param customId Contiene el id del tipo de activo
     */
    private eliminarTipoActivo(customId: number) {
        // Envia peticion de guardado de datos
        axios
            .get("get-use-type-asset?idAssetType=" + customId)
            .then(res => {
                // Valida la cantidad de veces que se usa el tipo de acivo en las categorías, autorizaciones y hojas de vida
                if (res.data.data > 0) {
                    // Abre el swal para mostrar la advertencia de que no se puede eliminar el registro, porque ya esta siendo utilizado
                    this.$swal({
                        icon: "warning",
                        html: res.data.message,
                        confirmButtonText: this.lang.get("trans.Accept"),
                        allowOutsideClick: false
                    });
                } else {
                    // Ejecuta la petición para eliminar el tipo de activo, mandándole como parámetro el id del tipo de activo
                    (this.$parent as any)["drop"](customId);
                }
            })
            .catch(err => {
                // Abre el swal para mostrar los errores de la consulta
                this.$swal({
                    icon: "error",
                    html: "Error al eliminar el tipo de activo: " + err,
                    confirmButtonText: this.lang.get("trans.Accept"),
                    allowOutsideClick: false
                });
            });
    }

    /**
     * Consulta y elimina una categoría si es posible
     *
     * @author German Gonzalez V. - Sep. 14 - 2021
     * @version 1.0.0
     * @param customId Contiene el id de la categoría
     */
    private eliminarCategory(customId: number) {
        // Envia peticion de guardado de datos
        axios
            .get("get-use-category?idCategory=" + customId)
            .then(res => {
                // Valida la cantidad de veces que se usa la categoría en las autorizaciones y hojas de vida
                if (res.data.data > 0) {
                    // Abre el swal para mostrar la advertencia de que no se puede eliminar el registro, porque ya esta siendo utilizado
                    this.$swal({
                        icon: "warning",
                        html: res.data.message,
                        confirmButtonText: this.lang.get("trans.Accept"),
                        allowOutsideClick: false
                    });
                } else {
                    // Ejecuta la petición para eliminar la categoría, mandándole como parámetro el id de la categoría
                    (this.$parent as any)["drop"](customId);
                }
            })
            .catch(err => {
                // Abre el swal para mostrar los errores de la consulta
                this.$swal({
                    icon: "error",
                    html: "Error al eliminar la categoría: " + err,
                    confirmButtonText: this.lang.get("trans.Accept"),
                    allowOutsideClick: false
                });
            });
    }
}
</script>
