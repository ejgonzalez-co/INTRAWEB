<template>
    <div>
        <div class="modal fade" id="modal-form-tireWears">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Formulario para agregar desgaste de la llanta
                        </h4>
                        <button type="button" class="close" @click="clearDataForm()" data-dismiss="modal"
                            aria-hidden="true">
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="checkDateTireWears()">
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2">Máx desgaste para reencauche (mm)</label>
                                <div class="col-md-2 d-flex align-items-center">
                                    <p>{{ this.initValues.max_wear }}</p>
                                </div>
                                <label class="col-form-label col-md-2">Profundidad de la llanta en (mm)</label>
                                <div class="col-md-3 d-flex align-items-center">
                                    <p>{{ this.initValues.depth_tire }}</p>

                                </div>
                            </div>
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2">Profundidad del registro</label>
                                <dynamic-list-change label-button-add="Agregar profundidad"
                                    :data-list.sync="formData.record_depth"
                                    class-table="table-hover text-inverse table-bordered" :key="keyRefresh"
                                    :data-list-options="[
                                {label:'Profundidad', name:'name', isShow: true},
                            ]">
                                <template #fields="scope">
                                    <currency-input
                                                v-model="scope.dataForm.name"
                                                required="true"
                                                :currency="{'suffix': ''}"
                                                locale="es"
                                                :precision="2"
                                                class="form-control"
                                                :key="keyRefresh"                    
                                                >
                                    </currency-input>
                                </template>
                                </dynamic-list-change>
                                <div class="row" style="margin-top: 10px; margin-left:10px">
                                    <input-operation-change
                                        :array="formData.record_depth"
                                        operation="especial2"
                                        name-field="registration_depth"
                                        :value="formData"
                                        :key="formData.record_depth.length"
                                        suffix=" "
                                    ></input-operation-change>
                                </div>
                            </div>


                            <!-- Revision Date Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2">Fecha de revisión</label>

                                <div class="col-md-4" v-if="this.count == 1">
                                    <input 
                                        type="date" 
                                        v-model="formData.revision_date"
                                        class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow"
                                        :max="this.initValues.date"
                                        />

                                    <small>Fecha de revisión</small>
                                </div>


                                <!-- Wear Total Field -->
                                <label class="col-form-label col-md-2">Total del desgaste</label>
                                <div class="col-md-4" :key="keyRefresh">
                                    <input-operation-change :array="formData.record_depth"
                                        :number-two="parseFloat(formData.depth_tire)" operation="especial3"
                                        name-field="wear_total" :value="formData" :key="formData.registration_depth"
                                        suffix=" "></input-operation-change>
                                    <small>Total del desgaste</small>
                                </div>
                            </div>

                            <!-- Revision Mileage Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2">Kilometraje en revisión</label>
                                <div class="col-md-4">
                                    <currency-input v-model="formData.revision_mileage" required="true"
                                        :currency="{'suffix':  !this.suffixKmHr ? ' km' : ' HR'}" locale="es" :precision="2" class="form-control"
                                        :key="keyRefresh" disabled>
                                    </currency-input>
                                    <small>Kilometraje en revisión (El kilometraje en revisión debe ser mayor al kilometraje inicial), kilometraje inicial: {{ this.initValues.mileage_initial }}.</small>


                                </div>

                                <label class="col-form-label col-md-2">Presión en la revisión</label>
                                <div class="col-md-4">
                                    <currency-input v-model="formData.revision_pressure" required="true"
                                        :currency="{'suffix': ' PSI'}" locale="es" :precision="2" class="form-control"
                                        :key="keyRefresh">
                                    </currency-input>
                                    <small>Ingrese la preseión en la revisión</small>
                                </div>
                            </div>

                            <!-- Wear Cost Mm Field -->
                            <div class="form-group row m-b-15">
                                <label class="col-form-label col-md-2">Valor actual en la llanta</label>
                                <div class="col-md-4">
                                    <input-operation-change :array="formData.record_depth"
                                        :number-one="parseFloat(formData.general_cost_mm)"
                                        :number-two="parseFloat(formData.max_wear)" operation="especial4"
                                        name-field="wear_cost_mm" :value="formData" :key="formData.registration_depth"
                                        prefix="$ "></input-operation-change>
                                    <small>Costo por mm en desgaste</small>
                                </div>

                                <!-- Cost Km Field -->
                                <label class="col-form-label col-md-2">Costo por km</label>
                                <div class="col-md-4">
                                    <input-operation-change
                                        :number-one="parseFloat(formData.wear_total)"
                                        :number-two="parseFloat(formData.general_cost_mm)"
                                        :number-three="parseFloat(formData.route_total)"
                                        operation="especial5"
                                        name-field="cost_km"
                                        :value="formData"
                                        prefix="$ " 
                                        :key="formData.revision_mileage"
                                    ></input-operation-change>
                                    <small>Costo por km</small>
                                </div>
                            </div>

                            <!-- Revision Pressure Field -->
                            <div class="form-group row m-b-15">
                                <!-- Route Total Field -->
                                <label class="col-form-label col-md-2">Total recorrido</label>
                                <div class="col-md-4">
                                    <input-operation-change :number-one="parseFloat(formData.revision_mileage)"
                                        :number-two="parseFloat(formData.mileage_initial)" operation="restaMantTireWear"
                                        name-field="route_total" :value="formData" :key="formData.revision_mileage"
                                        :suffix="!this.suffixKmHr ? ' km' : ' HR'" :refresh-parent-component="true" required></input-operation-change>
                                    <small>Total del recorrido</small>
                                </div>


                                <!-- Observation Field -->
                                <label class="col-form-label col-md-2">Observación</label>
                                <div class="col-md-4">
                                    <textarea
                                        name="observation"
                                        id="observation"
                                        rows="3"
                                        v-model="formData.observation"
                                        class="form-control"
                                        required
                                    ></textarea>
                                    <small>Ingrese observación</small>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button @click="clearDataForm()" class="btn btn-white" data-dismiss="modal"><i
                                        class="fa fa-times mr-2"></i>Cerrar</button>
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-save mr-2"></i>Guardar</button>
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
import { error } from "jquery";
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para hacer una resta entre dos numeros y se le da el formato de dinero
 *
 * @author Johan David Velasco. - Octubre. 19 - 2021
 * @version 1.0.0
 */
@Component
export default class FormTireWears extends Vue {
    /**
     * Propiedades de inicializacion para formData
     */
    @Prop({})
    public formData: any;

    @Prop({})
    public initValues: any;

    public dataValidator: any;

    public dateOne: any;

    public dateTwo: any;

    public currentDate: any;

    public count: any;

    public lang: any;

    public keyRefresh: number;

    public checkDate: boolean;

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
        this.checkDate = false;
        this.lang = (this.$parent as any).lang;
        /**LLama a la operacion de los dos numeros ingresados por props */
    }

    //Valida si las propiedades mant_resume_machinery_vehicles_yellow_id y date_assignment del formData sufren algun cambio
    @Watch("formData", { deep: true })
    private validateDataform() {
        if (
            this.formData.revision_date != this.dataValidator.revision_date
        ) {
            // Aquí va la función que deseas ejecutar cuando ambas propiedades tengan un valor
            this.checkMileage(
                this.initValues.mant_resume_machinery_vehicles_yellow_id,
                this.formData.revision_date
            );
        }

        this.calculateCostKm();
    }

        //Consulta el kilometraje inicial del combustible por medio de la placa yu la fecha de asignacion
        private checkMileage(id_machinery, fecha) {
        // Lógica de tu función (por ejemplo, llamar a `resta` u otras funciones)
                            axios
            .get(`get-check-fuel-mileage-wears/${id_machinery}/${fecha}`)
            .then(res => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const request = Object.assign({}, dataPayload);

                //Valida si el valor de request es diferente de null
                if (request != null) {
                    this.$set(
                        this.formData,
                        "revision_mileage",
                        request["data"]["current_mileage"] ?? request["data"]["current_hourmeter"]
                    );

                    if (request["data"]["current_hourmeter"]) {
                        this.suffixKmHr = true;
                    } 
                    //De lo contrario muestra un swal
                } else {
                    if (this.count == 0) {
                        this.count++;
                        this.$swal({
                            icon: "info",
                            html:
                                "No se encontró un combustible la fecha de revisión ingresada. Por favor, verifique los datos.",
                            confirmButtonText: "Aceptar",
                            allowOutsideClick: false
                        });
                    }
                }

            });




    }



    //Funcion para abrir la modal
    public showModal(update) {

        if (!update) {
            this.isUpdate = false;
        } else {
            this.isUpdate = true;
            this.dataValidator = utility.clone(update);
            this.formData = utility.clone(update);

            if (update.tire_informations.vehicles_fuels.current_hourmeter) {
                    this.suffixKmHr = true;
                }
        }
        
        this.count = 1;
        $("#modal-form-tireWears").modal("show");
    }

    public calculateCostKm(){
        this.formData.cost_km = (parseFloat(this.formData.wear_total.toFixed(2)) * parseFloat(this.formData.general_cost_mm.toFixed(2)))/parseFloat(this.formData.route_total.toFixed(2));
    }

    //Funcion para guardar la informacion
    public crud() {
       
            if(this.isUpdate == true){
                
                if (this.formData.route_total == 0) {
                    this.$swal({
                            icon: 'info',
                            title: 'El recorrido total es 0',
                            html: 'El vehículo no ha registrado un aumento en el kilometraje; para considerar un desgaste, es necesario que dicho kilometraje incremente.',
                        }).then((result) => {
        
                        })
                }else{
                    axios.put(`tire-wears/${this.formData.id}`, this.formData)
                        .then((res) => {
        
                                this.clearDataForm();
        
                                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                                const request = Object.assign({}, dataPayload);
        
                                (this.$parent as any)["assignElementList"](this.formData.id,request["data"]);
        
                                (this.$swal as any).close();
                                this.$swal({
                                    icon: 'success',
                                    title: '¡Los datos han sido actulizadas!',
                                }).then((result) => {
        
                                }).catch((error)=>{
                                    this.$swal({
                                    icon: 'error',
                                    html: error,
                                    confirmButtonText: this.lang.get('trans.Accept'),
                                    allowOutsideClick: false,
                                });
                                })
                        })
                        .catch((error) => {
                        //   console.log(Error: ${error});
                        });
    
                }
    
    
            }else{
    
                    if (this.formData.route_total == 0) {
                        this.$swal({
                            icon: 'info',
                            title: 'El recorrido total es 0',
                            html: 'El vehículo no ha registrado un aumento en el kilometraje; para considerar un desgaste, es necesario que dicho kilometraje incremente.',
                        }).then((result) => {
        
                        })
                    }else{
                        axios.post('tire-wears', this.formData)
                            .then((res) => {
        
                                    this.clearDataForm();
                            
                                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                                    const request = Object.assign({}, dataPayload);
        
                            (this.$parent as any)["addElementToList"](request["data"]);
        
                            (this.$swal as any).close();
                                this.$swal({
                                    icon: 'success',
                                    title: '¡Los datos han sido almanecados!',
                                }).then((result) => {
        
                                }).catch((error)=>{
                                    this.$swal({
                                    icon: 'error',
                                    html: error,
                                    confirmButtonText: this.lang.get('trans.Accept'),
                                    allowOutsideClick: false,
                                });
                                })
        
                        })
                        .catch((error) => {
                        //   console.log(Error: ${error});
                        });
    
                    }
                }
        


    }

    public checkDateTireWears(){

        this.$swal({
                title: 'Cargando',
                html: this.isUpdate ? 'Se estan actualizando los datos' : 'Se estan guardando los datos',
                onBeforeOpen: () => {
                    (this.$swal as any).showLoading();
                }
            });

        axios.post(`check-date-tire-wears/${this.formData.revision_date}/${this.initValues.tire_id_plaque}/${this.initValues.mant_resume_machinery_vehicles_yellow_id}`)
                .then((res) => {

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const request = Object.assign({}, dataPayload);

                    if (request['data'] != 0) {
                        (this.$swal as any).close();

                        this.$swal({
                            icon: "info",
                            html:
                                "La fecha de revisión debe ser posterior a la última fecha de revisión registrada.",
                            confirmButtonText: "Aceptar",
                            allowOutsideClick: false
                        });
                    }else{
                        this.crud();
                    }

            })
            .catch((error) => {
            //   console.log(Error: ${error});
            });
    }

    
    //Limpia el formData 
    public clearDataForm(){
            this.dataValidator = {};
            this.count = 0;
            (this.$parent as any)["clearDataForm"]();
            $("#modal-form-tireWears").modal("hide");
    }




    created() {
        //Obtiene la fehca actual para validar la fecha de registro
        var today = new Date();
        this.currentDate = today;
    }

}
</script>
