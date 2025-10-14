<template>
    <div>
        <div class="modal fade" id="modal-form-tireInformations">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Formulario de información general de la llanta
                        </h4>
                        <button
                            type="button"
                            class="close"
                            @click="clearDataForm()"
                            data-dismiss="modal"
                            aria-hidden="true"
                        >
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="crud()">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2 required"
                                    >Tipo de asignación de la llanta</label
                                >
                                <div class="col-md-4">
                                    <select
                                        class="form-control"
                                        v-model="formData.assignment_type"
                                        required
                                    >
                                        <option value="Activo">Activo</option>
                                        <option value="Almacén">Almacén</option>
                                    </select>
                                    <small
                                        >Seleccione el tipo de asignación que
                                        desea realizarle a la llanta</small
                                    >
                                </div>

                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="
                                        formData.assignment_type == 'Activo' ||
                                            formData.assignment_type ==
                                                'Almacén'
                                    "
                                    >Fecha de ingreso de la llanta:</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="
                                        formData.assignment_type == 'Activo' ||
                                            formData.assignment_type ==
                                                'Almacén'
                                    "
                                >
                                    <date-picker
                                        :value="formData"
                                        name-field="date_register"
                                        :input-props="{ required: true }"
                                        :max-date="this.currentDate"
                                    >
                                    </date-picker>
                                    <small
                                        >Seleccione la fecha de ingreso de la
                                        llanta</small
                                    >
                                </div>
                            </div>

                            <div
                                class="form-group row m-b-15"
                                v-if="formData.assignment_type == 'Activo'"
                            >
                                <label class="col-form-label col-md-2"
                                    >Placa</label
                                >
                                <div class="col-md-4">
                                    <autocomplete
                                        :is-update="isUpdate"
                                        name-prop="plaque"
                                        name-field="plaque"
                                        :value="formData"
                                        :value-default="formData"
                                        name-resource="get-plaque-vehicle"
                                        css-class="form-control"
                                        :name-labels-display="['plaque']"
                                        reduce-key="id"
                                        name-field-object="vehiculo"
                                        :key="keyRefresh"
                                    >
                                    </autocomplete>
                                    <small>Ingrese la placa</small>
                                </div>

                                <label class="col-form-label col-md-2"
                                    >Nombre del equipo o maquinaria</label
                                >
                                <div class="col-md-4" v-if="formData.vehiculo">
                                    <select-check
                                        css-class="form-control"
                                        name-field="mant_resume_machinery_vehicles_yellow_id"
                                        reduce-label="name_vehicle_machinery"
                                        reduce-key="id"
                                        name-resource="get-mant-vehicles"
                                        :key="formData.plaque"
                                        :element-disabled="true"
                                        :value="formData.vehiculo"
                                    >
                                    </select-check>
                                    <small
                                        >Nombre del equipo o maquinaria</small
                                    >
                                </div>
                                <div class="col-md-4" v-else>
                                    <select-check
                                        css-class="form-control"
                                        name-field="mant_resume_machinery_vehicles_yellow_id"
                                        reduce-label="name_vehicle_machinery"
                                        reduce-key="id"
                                        name-resource="get-mant-vehicles"
                                        :key="formData.plaque"
                                        :element-disabled="true"
                                        :value="formData"
                                    >
                                    </select-check>
                                    <small
                                        >Nombre del equipo o maquinaria</small
                                    >
                                </div>
                            </div>

                            <div
                                class="form-group row m-b-15"
                                v-if="formData.assignment_type == 'Activo'"
                            >
                                <label class="col-form-label col-md-2"
                                    >Proceso</label
                                >
                                <div class="col-md-4" v-if="formData.vehiculo">
                                    <select-check
                                        css-class="form-control"
                                        name-field="dependencias_id"
                                        reduce-label="nombre"
                                        reduce-key="id"
                                        name-resource="get-mant-dependencias_id"
                                        :value="formData.vehiculo"
                                        :element-disabled="true"
                                        :key="formData.plaque"
                                    >
                                    </select-check>
                                    <small
                                        >Proceso relacionado al vehículo o
                                        maquinaria amarilla</small
                                    >
                                </div>
                                <div class="col-md-4" v-else>
                                    <select-check
                                        css-class="form-control"
                                        name-field="dependencias_id"
                                        reduce-label="nombre"
                                        reduce-key="id"
                                        name-resource="get-mant-dependencias_id"
                                        :value="formData"
                                        :element-disabled="true"
                                        :key="formData.plaque"
                                    >
                                    </select-check>
                                    <small
                                        >Proceso relacionado al vehículo o
                                        maquinaria amarilla</small
                                    >
                                </div>

                                <label class="col-form-label col-md-2"
                                    >Fecha de asignación de la llanta:</label
                                >
                                <div class="col-md-4">
                                    <input 
                                        type="date" 
                                        v-model="formData.date_assignment"
                                        class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow"
                                        />
                                    <small
                                        >Seleccione la fecha de asignación de la
                                        llanta</small
                                    >
                                </div>
                            </div>

                            <!-- Position Tire Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="
                                    formData.assignment_type == 'Activo' ||
                                        formData.assignment_type == 'Almacén'
                                "
                            >
                                <label class="col-form-label col-md-2 required"
                                    >Dimensión de llanta:</label
                                >
                                <div class="col-md-4">
                                    <select-check
                                        css-class="form-control"
                                        name-field="mant_set_tires_id"
                                        reduce-label="name"
                                        reduce-key="id"
                                        name-resource="get-tire-reference"
                                        :value="formData"
                                        :is-required="true"
                                    >
                                    </select-check>
                                    <small
                                        >Seleccione la dimensión de la
                                        llanta</small
                                    >
                                </div>

                                <!-- Type Brand Field -->
                                <label class="col-form-label col-md-2 required"
                                    >Marca de llanta</label
                                >
                                <div class="col-md-4">
                                    <select-check
                                        css-class="form-control"
                                        name-field="tire_brand"
                                        reduce-label="brand_name"
                                        reduce-key="id"
                                        name-resource="get-tire-brands-order-name"
                                        :value="formData"
                                        :is-required="true"
                                    >
                                    </select-check>
                                    <small
                                        >Seleccione la marca de la llanta</small
                                    >
                                </div>
                            </div>
                            <div
                                class="form-group row m-b-15"
                                v-if="
                                    formData.assignment_type == 'Activo' ||
                                        formData.assignment_type == 'Almacén'
                                "
                            >
                                <!-- Tire References Field -->
                                <label class="col-form-label col-md-2 required"
                                    >Referencia de llanta</label
                                >
                                <div class="col-md-4">
                                    <select-check
                                        css-class="form-control"
                                        name-field="reference_name"
                                        reduce-label="reference_name"
                                        reduce-key="reference_name"
                                        :name-resource="
                                            'tire-references/' +
                                                formData.tire_brand
                                        "
                                        :value="formData"
                                        :is-required="true"
                                        :key="formData.tire_brand"
                                    >
                                    </select-check>
                                    <small
                                        >Seleccione la referencia de la
                                        llanta</small
                                    >
                                </div>

                                <!-- Depth Tire Field -->
                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="formData.assignment_type == 'Almacén'"
                                    >Profundidad de la llanta en (mm):</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Almacén'"
                                >
                                    <currency-input
                                        v-model="formData.depth_tire"
                                        required="true"
                                        :currency="{ suffix: ' mm' }"
                                        locale="es"
                                        :precision="0"
                                        class="form-control"
                                    >
                                    </currency-input>
                                    <small
                                        >Ingrese la profundidad de la
                                        llanta</small
                                    >
                                </div>

                                <!-- Inflation Pressure Field -->
                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Presión de inflado</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model="formData.inflation_pressure"
                                        required
                                    />
                                    <small
                                        >Ingrese la presión de inflado de la
                                        llanta</small
                                    >
                                </div>
                            </div>
                            <!-- Cost Tire Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="
                                    formData.assignment_type == 'Activo' ||
                                        formData.assignment_type == 'Almacén'
                                "
                            >
                                <!-- Depth Tire Field -->
                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Profundidad de la llanta en (mm):</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <currency-input
                                        v-model="formData.depth_tire"
                                        required="true"
                                        :currency="{ suffix: ' mm' }"
                                        locale="es"
                                        :precision="0"
                                        class="form-control"
                                    >
                                    </currency-input>
                                    <small
                                        >Ingrese la profundidad de la
                                        llanta</small
                                    >
                                </div>

                                <!-- Max Wear For Retorquing Field -->
                                <label
                                    class="col-form-label col-md-2"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Máx desgaste para reencauche (mm)</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model="
                                            formData.max_wear_for_retorquing
                                        "
                                    />
                                    <small
                                        >Ingrese el máx desgaste para
                                        reencauche</small
                                    >
                                </div>
                            </div>

                            <!-- Mileage Initial Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="formData.assignment_type == 'Activo' || formData.assignment_type == 'Almacén'"
                            >
                                <label v-if="formData.assignment_type == 'Activo'" class="col-form-label col-md-2 required"
                                    >Kilometraje Inicial:</label
                                >
                                <div class="col-md-4" v-if="formData.assignment_type == 'Activo'">
                                    <currency-input 
                                        v-model="formData.mileage_initial"
                                        :currency="{ suffix: !this.suffixKmHr ? ' km' : ' HR' }"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        disabled
                                    >
                                    </currency-input>
                                    <small
                                        >Ingrese el Kilometraje Inicial</small
                                    >
                                </div>

                                <label class="col-form-label col-md-2"
                                    >Costo de llanta:</label
                                >
                                <div class="col-md-4">
                                    <currency-input
                                        v-model="formData.cost_tire"
                                        :currency="{ prefix: '$ ' }"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        @keyup="division"
                                    >
                                    </currency-input>
                                    <small>Ingrese el costo de la llanta</small>
                                </div>
                            </div>

                            <!-- General Cost Mm Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="
                                    formData.assignment_type == 'Activo' ||
                                        formData.assignment_type == 'Almacén'
                                "
                            >
                                <label class="col-form-label col-md-2"
                                    >Posición de la llanta (1,2,3,4...):</label
                                >
                                <div class="col-md-4">
                                    <input
                                        type="number"
                                        class="form-control"
                                        v-model="formData.position_tire"
                                    />
                                    <small
                                        >Ingrese la posición de la llanta</small
                                    >
                                </div>

                                <label
                                    class="col-form-label col-md-2"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Costo por mm general:</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <!-- cost_tire dividido available_depth -->
                                    <!-- <currency-input
                    v-model="formData.general_cost_mm"
                    :currency="{ prefix: '$ ' }"
                    locale="es"
                    :precision="2"
                    class="form-control"
                    disabled
                    :key="keyRefresh"
                >
                </currency-input> -->
                                    <input-operation
                                        name-field="general_cost_mm"
                                        :number-one="
                                            parseFloat(formData.cost_tire)
                                        "
                                        :number-two="
                                            parseFloat(formData.available_depth)
                                        "
                                        :key="
                                            formData.cost_tire +
                                                formData.available_depth
                                        "
                                        :cantidad-decimales="2"
                                        :value="formData"
                                        operation="divide"
                                        prefix="$ "
                                    ></input-operation>
                                    <small
                                        >Ingrese el Costo por mm general</small
                                    >
                                </div>
                            </div>

                            <!-- Observation Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="formData.assignment_type == 'Activo'"
                            >
                                <!-- Available Depth Field -->
                                <label class="col-form-label col-md-2"
                                    >Profundidad disponible:</label
                                >
                                <div class="col-md-4">
                                    <!-- tire_reference restado depth_tire -->
                                    <!-- <input
                    type="number"
                    v-model="formData.available_depth"
                    class="form-control"
                    disabled
                /> -->
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="formData.available_depth"
                                        disabled
                                        required
                                    />
                                    <small>Profundidad disponible</small>
                                </div>

                                <!-- Location Tire Field -->
                                <label class="col-form-label col-md-2 required"
                                    >Ubicacion de la llanta:</label
                                >
                                <div class="col-md-4">
                                    <select
                                        class="form-control"
                                        v-model="formData.location_tire"
                                        required
                                    >
                                        <option value="Delantera"
                                            >Delantera</option
                                        >
                                        <option value="Trasera">Trasera</option>
                                    </select>
                                    <small
                                        >Ingrese la ubicacion de la
                                        llanta</small
                                    >
                                </div>
                            </div>
                            <!-- Observation Field -->
                            <div
                                class="form-group row m-b-15"
                                v-if="formData.assignment_type == 'Activo'"
                            >
                            <label class="col-form-label col-md-2 required"
                                    >Codigo de la llanta:</label
                                >
                                <div class="col-md-4">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="formData.code_tire"
                                        required
                                    />
                                    <small
                                        >Ingrese el codigo de la llanta</small
                                    >
                                </div>
                                <label class="col-form-label col-md-2 required"
                                    >Kilometraje de rodamiento:</label
                                >
                                <div class="col-md-4">
                                    <currency-input
                                        v-model="formData.kilometraje_rodamiento"
                                        :currency="{ suffix: ' km' }"
                                        locale="es"
                                        :precision="2"
                                        class="form-control"
                                        disabled
                                    >
                                    </currency-input>
                                    <small
                                        >Kilometraje de rodamiento</small
                                    >
                                </div>

                            </div>

                            <div v-if="formData.assignment_type == 'Activo'" class="form-group row m-b-15">
                                <label class="col-form-label col-md-2 required"
                                    >Tipo de llanta:</label
                                >
                                <div class="col-md-4">
                                    <select
                                        class="form-control"
                                        v-model="formData.type_tire"
                                        required
                                    >
                                        <option value="Nueva">Nueva</option>
                                        <option value="Usada">Usada</option>
                                        <option value="Repuesto">Repuesto</option>
                                        <!-- <option value="Prestada">Prestada</option> -->
                                        <!-- <option value="En préstamo">En préstamo</option> -->
                                        <option value="Reencauche">Reencauche</option>
                                        <option value="Reencauche 2">Reencauche 2</option>
                                        <option value="Reencauche 3">Reencauche 3</option>
                                    </select>
                                    <small>Ingrese el tipo de llanta</small>
                                </div>

                                <label
                                    class="col-form-label col-md-2"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Estado</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <select
                                        class="form-control"
                                        v-model="formData.state"
                                    >
                                        <option value="Seleccione"
                                            >Seleccione</option
                                        >
                                        <option value="Instalada"
                                            >Instalada</option
                                        >
                                        <option value="En proceso de reencauche"
                                            >En proceso de reencauche</option
                                        >
                                        <option value="Dada de baja"
                                            >Dada de baja</option
                                        >
                                    </select>
                                    <small
                                        >Ingrese el estado de la llanta</small
                                    >
                                </div>
                            </div>

                            <div class="form-group row m-b-15">
                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="formData.assignment_type == 'Activo'"
                                    >Observación</label
                                >
                                <div
                                    class="col-md-4"
                                    v-if="formData.assignment_type == 'Activo'"
                                >
                                    <textarea
                                        name="observation_information"
                                        id="observation_information"
                                        rows="3"
                                        v-model="
                                            formData.observation_information
                                        "
                                        class="form-control"
                                        required
                                    ></textarea>
                                    <small>Ingrese observación</small>
                                </div>

                                <label
                                    class="col-form-label col-md-2 required"
                                    v-if="formData.id"
                                    requiered
                                    >Observación porque va editar este
                                    registro:</label
                                >
                                <div class="col-md-4" v-if="formData.id">
                                    <textarea
                                        name="descriptionDelete"
                                        id="descriptionDelete"
                                        rows="3"
                                        v-model="formData.descriptionDelete"
                                        class="form-control"
                                        required
                                    ></textarea>
                                    <small
                                        >Ingrese porque va editar este
                                        registro</small
                                    >
                                </div>
                            </div>



                            <input type="hidden" v-model="formData.max_wear" />
                            <input
                                type="hidden"
                                v-model="formData.mant_vehicle_fuels_id"
                            />
                        <div class="modal-footer">
                            <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save mr-2"></i>Guardar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";
import axios from "axios";
import utility from '../../utility';
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para hacer una resta entre dos numeros y se le da el formato de dinero
 *
 * @author Johan David Velasco. - Octubre. 19 - 2021
 * @version 1.0.0
 */
@Component
export default class FormTireInformation extends Vue {
    /**
     * Propiedades de inicializacion para formData
     */
    @Prop({})
    public formData: any;

    public dataValidator: any;

    public currentDate: any;

    public count: any;

    public lang: any;

    public keyRefresh: number;

    @Prop({})
    public machinery: any;

    public isUpdate: boolean;

    @Prop({})
    public suffixKmHr: boolean;
    /**
     * Lista de elementos
     */
    public dataList: Array<any>;

    /**
     * Componente para hacer una resta entre dos numeros y se le da el formato de dinero
     *
     * @author Johan David Velasco. - octubre. 19 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.count = 0;
        this.currentDate = "";
        this.keyRefresh = 0;
        this.dataList = [];
        this.isUpdate = false;
        this.dataValidator = {};
        this.suffixKmHr = false;
        this.lang = (this.$parent as any).lang;
        /**LLama a la operacion de los dos numeros ingresados por props */
    }

    //Valida si las propiedades mant_resume_machinery_vehicles_yellow_id y date_assignment del formData sufren algun cambio
    @Watch("formData", { deep: true })
    private validateDataform() {
        if (
            (this.formData.mant_resume_machinery_vehicles_yellow_id || this.formData?.vehiculo.mant_resume_machinery_vehicles_yellow_id) &&
            this.formData.date_assignment
        ) {
            // Aquí va la función que deseas ejecutar cuando ambas propiedades tengan un valor
            this.checkMileage(
                this.formData.vehiculo ? this.formData?.vehiculo.mant_resume_machinery_vehicles_yellow_id : this.formData.mant_resume_machinery_vehicles_yellow_id,
                this.formData.date_assignment
            );
        }

        if (this.formData.max_wear_for_retorquing && this.formData.depth_tire) {
            // Aquí va la función que deseas ejecutar cuando ambas propiedades tengan un valor
            this.resta();
        }
    }

        //Consulta el kilometraje inicial del combustible por medio de la placa yu la fecha de asignacion
    private checkMileage(id_machinery, fecha) {
        // Lógica de tu función (por ejemplo, llamar a `resta` u otras funciones)
        if (this.formData.mileage_initial == 0 || (this.formData.mant_resume_machinery_vehicles_yellow_id != this.dataValidator.mant_resume_machinery_vehicles_yellow_id || this.formData.vehiculo.mant_resume_machinery_vehicles_yellow_id != this.dataValidator.mant_resume_machinery_vehicles_yellow_id) || this.formData.date_assignment != this.dataValidator.date_assignment) {
            axios
            .get(`get-check-fuel-mileage/${id_machinery}/${fecha}`)
            .then(res => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);
                //Valida si el valor de request es diferente de null
                if (dataDecrypted != null) {
                    this.$set(
                        this.formData,
                        "mileage_initial",
                        dataDecrypted["data"]["current_mileage"] ?? dataDecrypted["data"]["current_hourmeter"]
                    );
                    this.$set(
                        this.formData,
                        "mant_vehicle_fuels_id",
                        dataDecrypted["data"]["id"]
                    );

                    if (dataDecrypted["data"]["current_hourmeter"]) {
                        this.suffixKmHr = true;
                    } 

                    //De lo contrario muestra un swal
                } else {
                    if (this.count == 0) {
                        this.count++;
                        this.$swal({
                            icon: "info",
                            html:
                                "No se encontró un combustible para la placa y la fecha de asignación ingresadas. Por favor, verifique los datos o ingrese manualmente el kilometraje inicial.",
                            confirmButtonText: "Aceptar",
                            allowOutsideClick: false
                        });
                    }
                }
            });
        }

    }

    //Funcion para abrir la modal
    public showModal(update) {

        if (!update) {
            this.isUpdate = false;
        } else {
            this.isUpdate = true;
            this.formData = utility.clone(update);
            
            this.dataValidator = utility.clone(update);
            if (update.assignment_type == "Activo") {
                this.$set(this.formData,'vehiculo',this.formData?.resume_machinery_vehicles_yellow);
                this.$set(this.formData.vehiculo,'mant_resume_machinery_vehicles_yellow_id',this.formData?.resume_machinery_vehicles_yellow.id);

                if (update?.vehicles_fuels?.current_hourmeter) {
                    this.suffixKmHr = true;
                }
            }



        }

        $("#modal-form-tireInformations").modal("show");
    }

    //Funcion para guardar la informacion
    public crud() {

        if(this.isUpdate == true){

            this.$swal({
                title: 'Cargando',
                html: 'Se estan actualizando los datos',
                onBeforeOpen: () => {
                    (this.$swal as any).showLoading();
                }
            });

            axios.put(`tire-informations/${this.formData.id}`, this.formData)
                .then((res) => {

                    this.clearDataForm();

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);

                    (this.$parent as any)["assignElementList"](this.formData.id,dataDecrypted["data"]);

                    (this.$swal as any).close();
                        this.$swal({
                            icon: 'success',
                            title: '¡Los datos han sido actulizadas!',
                        }).then((result) => {
                            if (dataDecrypted["data"]["assignment_type"] == "Activo") {
                                this.$swal({
                                    title: "Estado de la llanta",
                                    text: "La llanta se almaceno en estado " + dataDecrypted["data"]["state"],
                                    icon: "info",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Cerrar"
                                }).then((result: any) => {});
                            }
                        })
                    })
                    .catch((error) => {
                    //   console.log(Error: ${error});
                    });

        }else{

                this.$swal({
                    title: 'Cargando',
                    html: 'Se estan guardando los datos',
                    onBeforeOpen: () => {
                        (this.$swal as any).showLoading();
                    }
                });


                axios.post('tire-informations', this.formData)
                    .then((res) => {

                            this.clearDataForm();
                    
                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                        const dataDecrypted = Object.assign({}, dataPayload);
                        

                    (this.$parent as any)["addElementToList"](dataDecrypted["data"]);

                    (this.$swal as any).close();
                        this.$swal({
                            icon: 'success',
                            title: '¡Los datos han sido almanecados!',
                        }).then((result) => {
                            if (dataDecrypted["data"]["assignment_type"] == "Activo") {
                                this.$swal({
                                    title: "Estado de la llanta",
                                    text: "La llanta se almaceno en estado " + dataDecrypted["data"]["state"],
                                    icon: "info",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Cerrar"
                                }).then((result: any) => {});
                            }
                        })

                })
                .catch((error) => {
                //   console.log(Error: ${error});
                });
            }
    }

    
    //Limpia el formData 
    public clearDataForm(){
            this.dataValidator = {};
            (this.$parent as any)["clearDataForm"]();
            $("#modal-form-tireInformations").modal("hide");
    }



    created() {
        //Obtiene la fehca actual para validar la fecha de registro
        var today = new Date();
        this.currentDate = today;
    }

    //Funcion encargada de restar la profundidad ingresada con el maximo desgaste
    private resta(): void {
        if (this.formData.assignment_type != "Almacén") {
            //Valida si el maximo
            if (
                this.formData.max_wear_for_retorquing < this.formData.depth_tire
            ) {
                let resta =
                    this.formData.depth_tire -
                    this.formData.max_wear_for_retorquing;
                this.$set(this.formData, "available_depth", resta);
            } else {
                this.$swal({
                    icon: "error",
                    html:
                        "La profundidad ingresada debe ser mayor al máximo desgaste ingresado por el administrador, el cual fue de " +
                        this.formData.max_wear_for_retorquing +
                        " mm",
                    confirmButtonText: "Aceptar",
                    allowOutsideClick: false
                });
                this.$set(this.formData, "available_depth", "");
            }
        }
    }
    //Calcula el total de general costo por mm
    public division(): void {
        // var division = this.formData.cost_tire / this.formData.available_depth;
        // this.formData.general_cost_mm = division;
        this.$forceUpdate();
    }

    /**
     * Exporta los datos de la tabla en un archivo
     *
     * @author Johan David Velasco
     * @version 1.0.0
     *
     * @param fileType tipo de archivo a exportar
     */
    public exportDataTable(filter: string, fileType = "xlsx"): void {
        // Envia peticion para exportar datos de la tabla
        axios
            .post(
                `export-tireInformations/${filter}`,
                {
                    fileType,
                    filter,
                    data: (this.$parent as any)
                        ["getMakeDataToExport"]()
                        .filter(word => word.assignment_type == filter)
                },
                { responseType: "blob" }
            )
            .then(res => {
                // Descagar el archivo generado
                (this.$parent as any)["downloadFile"](
                    res.data,
                    "Llantas_" + filter,
                    "xlsx"
                );
            })
            .catch(err => {
                (this.$parent as any)["_pushNotification"](
                    this.lang.get("trans.Data export failed"),
                    false,
                    "Error"
                );
            });
    }

    /**
     * Exporta los datos de la tabla en un archivo
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param fileType tipo de archivo a exportar
     */
    public exportDataTableAvanzado(filter: string, fileType = "xlsx"): void {
        // Envia peticion para exportar datos de la tabla
        axios
            .post(
                `export-tireInformations/${filter}`,
                {
                    fileType,
                    filtros: (this.$parent as any)[
                        "advancedSearchFilterActualizado"
                    ](),
                    filter: filter
                },
                { responseType: "blob" }
            )
            .then(res => {
                // Descagar el archivo generado
                (this.$parent as any)["downloadFile"](
                    res.data,
                    "Llantas_" + filter,
                    "xlsx"
                );
            })
            .catch(err => {
                (this.$parent as any)["_pushNotification"](
                    this.lang.get("trans.Data export failed"),
                    false,
                    "Error"
                );
            });
    }

    //Al destruir el componente ejecuta el siguiente codigo
    destroyed() {
        // if (this.formData.assignment_type == "Activo" && this.formData.state) {
        //     this.$swal({
        //         title: "Estado de la llanta",
        //         text: "La llanta se almaceno en estado " + this.formData.state,
        //         icon: "info",
        //         confirmButtonColor: "#3085d6",
        //         confirmButtonText: "Cerrar"
        //     }).then((result: any) => {});
        // }

        //Valida si el registro tiene un vehiculo asignado
        if (this.formData.mant_resume_machinery_vehicles_yellow_id != null) {
            //Valida si se va a listar solo las llantas de ese vehiculo
            if (this.machinery) {
                //Hace un friltro al datalist del padre base a la validacion que se le realiza con elfilter
                let data = (this.$parent as any).dataList.filter(
                    word => word.assignment_type == "Activo"
                );
                //Asigna el datalist del padre a la data
                (this.$parent as any).dataList = data;
                //Llama la funcion advancedSearchFilterPaginate del padre para actualizar el listado
                (this.$parent as any)["advancedSearchFilterPaginate"]();
            }
        }
    }
}
</script>
