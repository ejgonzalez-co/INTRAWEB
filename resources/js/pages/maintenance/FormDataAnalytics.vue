<template>
    <div>
        <div class="container">
            <form @submit.prevent="getPetition()">

                <h3>Búsqueda rápida</h3>
                <div class="row mt-4">
                    <div class="col-4 ">
                        <select class="form-control" name="indicator_type" id="indicator_type" 
                            v-model="dataForm.indicator_type" :key="keyRefresh">
                            <option value="Rendimiento de combustible">Rendimiento de combustible</option>
                            <option value="Consumo combustible">Consumo de combustible</option>
                            <option value="Recorrido por tanqueo">Recorrido por tanqueo</option>
                            <option value="Activos">Activos</option>
                            <option value="Ejecución de los contratos">Ejecución de los contratos</option>

                        </select>
                        <small>Seleccione el tipo de indicador.</small>
                    </div>
                </div>

                <!-- Formulario cuando el tipo de indicador sea Rendimiento de combustible -->
                <div class="mt-5 row" v-if="dataForm.indicator_type == 'Rendimiento de combustible'">
                    <div class="col-4 mb-3">
                          <select-check
                            css-class="form-control"
                            name-field="asset_process_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                    </div>
                    <div class="col-4 mb-3">
                        <select class="form-control required" name="type_combustible" id="type_combustible" required
                            v-model="dataForm.type_combustible" :key="dataForm.indicator_type">
                            <option value="">Seleccione una opción</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diesel">Diesel</option>
                        </select>
                        <small>Seleccione el tipo de combustible.</small>
                    </div>

                    <div class="col-4" :key=" dataForm.type_combustible + keyRefresh">
                        <select class="form-control required" name="type_promedio" id="type_promedio" required
                            v-model="dataForm.type_promedio">
                            <option value="rendimiento en Km-gln">Rendimiento en Km/gln </option>
                            <option value="rendimiento en hr-gln" v-if="dataForm.type_combustible === 'Diesel'">Rendimiento en hr/gln</option>
                        </select>
                        <small>Seleccionar tipo de promedio</small>
                    </div>

                    <dynamic-list label-button-add="Agregar ítem a la lista" :data-list.sync="dataForm.list_indicator"
                        :data-list-options="[
                                { label: 'Nombre del activo', name: 'name_vehicle_machinery', isShow: true },
                                { label: 'Placa', name: 'placa', isShow: true, refList:'selectRefPlaca' },
                                    ]" class-container="col-md-12" class-table="table table-bordered">
                        <template #fields="scope">
                            <div class="form-group row">

                                <!-- Nombre activo -->
                                <div class="col-4">
                                    <autocomplete
                                        :min-text-input="1"
                                        asignar-al-data = "1"
                                        name-prop="name_vehicle_machinery"
                                        name-field="name_vehicle_machinery"
                                        :value='scope.dataForm'
                                        :name-resource="'assetsA/' + dataForm.type_combustible + ',' + dataForm.type_promedio + '/' + dataForm.asset_process_id"
                                        css-class="form-control"
                                        :name-labels-display="['name_vehicle_machinery']"
                                        reduce-key="name_vehicle_machinery"
                                        :is-required="true" 
                                        :key="dataForm.type_combustible + dataForm.type_promedio + keyRefresh"
                                    >
                                    </autocomplete>
                                    <small>Ingresar el nombre del activo</small>
                                </div>

                                <!-- Tipo de carroceria -->
                                <div class="col-4">
                                    <select-check 
                                    ref-select-check="selectRefTipo"
                                    css-class="form-control" 
                                    name-field="body_type" 
                                    reduce-label="body_type"
                                    reduce-key="body_type" 
                                    :name-resource="'body-type/' + scope.dataForm.name_vehicle_machinery"
                                    :value="dataForm"
                                    :is-required="true" 
                                    :key="scope.dataForm.name_vehicle_machinery">
                                    </select-check>

                                    <small>Seleccione el tipo de carroceria</small>
                                </div>

                                <!-- Placa -->
                                <div class="col-4">
                                    <select-check 
                                    ref-select-check="selectRefPlaca"
                                    css-class="form-control" 
                                    name-field="placa" 
                                    reduce-label='plaque'
                                    reduce-key="id"
                                    :name-resource="'fuel-effiency/plaques/' + dataForm.asset_process_id + '/' + dataForm.type_combustible + '/' + scope.dataForm.name_vehicle_machinery + '/' + dataForm.body_type"
                                    :value="scope.dataForm" 
                                    :is-required="true" 
                                    :key="dataForm.body_type">
                                    </select-check>
                                    <small>Seleccionar la placa.</small>
                                </div>
                            </div>

                            <!-- Rango de fechas -->
                            <div class="row mt-2" >
                                <div class="col-4">
                                    <input type="datetime-local" name="init_date" id="init_date" class="form-control" required
                                        v-model="dataForm.init_date" />
                                    <small>Fecha de inicio.</small>
                                </div>
                                <div class="col-4">
                                    <input type="datetime-local" name="final_date" id="final_date" class="form-control" required
                                        v-model="dataForm.final_date" />
                                    <small>Fecha de final.</small>
                                </div>
                            </div>
                        </template>
                    </dynamic-list>
                </div>

                <!-- Formulario cuando el tipo de indicador sea Recorrido por tanqueo -->
                <div class="mt-5 row" v-if="dataForm.indicator_type == 'Recorrido por tanqueo'">
                    <div class="col-4 mb-3">
                          <select-check
                            css-class="form-control"
                            name-field="asset_process_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                    </div>
                    <div class="col-4 mb-3">
                        <select class="form-control required" name="type_combustible" id="type_combustible" required
                            v-model="dataForm.type_combustible" :key="dataForm.indicator_type">
                            <option value="">Seleccione una opción</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diesel">Diesel</option>
                        </select>
                        <small>Seleccione el tipo de combustible.</small>
                    </div>

                    <div v-if="dataForm.indicator_type == 'Recorrido por tanqueo'" class="col-4" :key="dataForm.type_combustible + keyRefresh">
                        <select class="form-control required" name="type_promedio" id="type_promedio" required
                            v-model="dataForm.type_promedio">
                            <option value="Variación en Km recorridos por tanqueo">Variación en Km recorridos por tanqueo</option>
                            <option value="Variación de horas en los tanqueos"  v-if="dataForm.type_combustible === 'Diesel'">Variación de horas en los tanqueos</option>
                        
                        </select>
                        <small>Seleccionar tipo de tipo de variación</small>
                    </div>

                    <dynamic-list label-button-add="Agregar ítem a la lista" :data-list.sync="dataForm.list_indicator"
                        :data-list-options="[
                               { label: 'Nombre del activo', name: 'name_vehicle_machinery', isShow: true },
                                { label: 'Placa', name: 'placa', isShow: true, refList:'selectRefPlaca' },
                                    ]" class-container="col-md-12" class-table="table table-bordered">
                        <template #fields="scope">
                            <div class="form-group row">

                                  <!-- Nombre activo -->
                                  <div class="col-4">
                                    <autocomplete
                                        :min-text-input="1"
                                        asignar-al-data = "1"
                                        name-prop="name_vehicle_machinery"
                                        name-field="name_vehicle_machinery"
                                        :value='scope.dataForm'
                                        :name-resource="'assetsA/' + dataForm.type_combustible + ',' + dataForm.type_promedio + '/' + dataForm.asset_process_id"
                                        css-class="form-control"
                                        :name-labels-display="['name_vehicle_machinery']"
                                        reduce-key="name_vehicle_machinery"
                                        :is-required="true" 
                                        :key="dataForm.type_combustible + dataForm.type_promedio + keyRefresh"
                                    >
                                    </autocomplete>
                                    <small>Ingresar nombre del activo</small>
                                </div>

                                <!-- Tipo de carroceria -->
                                <div class="col-4">
                                    <select-check 
                                    ref-select-check="selectRefTipo"
                                    css-class="form-control" 
                                    name-field="body_type" 
                                    reduce-label="body_type"
                                    reduce-key="body_type" 
                                    :name-resource="'body-type/' + scope.dataForm.name_vehicle_machinery"
                                    :value="dataForm"
                                    :is-required="true" 
                                    :key="scope.dataForm.name_vehicle_machinery">
                                    </select-check>

                                    <small>Seleccione el tipo de carroceria</small>
                                </div>

                                <!-- placa todo -->
                                <div class="col-4">
                                    <select-check 
                                    ref-select-check="selectRefPlaca"
                                    css-class="form-control" 
                                    name-field="placa" 
                                    reduce-label='plaque'
                                    reduce-key="id"
                                    :name-resource="'fuel-effiency/plaques/' + dataForm.asset_process_id + '/' + dataForm.type_combustible + '/' + scope.dataForm.name_vehicle_machinery + '/' + dataForm.body_type"
                                    :value="scope.dataForm" 
                                    :is-required="true" 
                                    :key="dataForm.body_type">
                                    </select-check>
                                    <small>Seleccionar la placa</small>
                                </div>
                            </div>

                            <div class="row mt-2" >
                                <div class="col-4">
                                    <input type="datetime-local" name="init_date" id="init_date" class="form-control" required
                                        v-model="dataForm.init_date" />
                                    <small>Fecha de inicio.</small>
                                </div>
                                <div class="col-4">
                                    <input type="datetime-local" name="final_date" id="final_date" class="form-control" required
                                        v-model="dataForm.final_date" />
                                    <small>Fecha de final.</small>
                                </div>
                            </div>
                        </template>
                    </dynamic-list>
                </div>
                
                <!-- Formulario cuando el tipo de indicador sea Consumo combustible -->
                <div class="mt-5 row" v-if="dataForm.indicator_type == 'Consumo combustible'">

                   <!--  Select con el proceso o dependencia-->
                    <div class="col-4 mb-3">
                          <select-check
                            css-class="form-control"
                            name-field="asset_process_id"
                            reduce-label="nombre"
                            reduce-key="id"
                            name-resource="/intranet/get-dependencies"
                            :value="dataForm"
                            :is-required="true"
                          >
                          </select-check>
                          <small>Seleccione el proceso del activo.</small>
                    </div>

                    <!--  Select con el tipo de combustible-->
                    <div class="col-4 mb-3">
                        <select class="form-control required" name="type_combustible" id="type_combustible" required
                            v-model="dataForm.type_combustible" :key="dataForm.indicator_type">
                            <option value="">Seleccione una opción</option>
                            <option value="Gasolina">Gasolina</option>
                            <option value="Diesel">Diesel</option>
                        </select>
                        <small>Seleccione el tipo de combustible.</small>
                    </div>
                  
                    <dynamic-list label-button-add="Agregar ítem a la lista" :data-list.sync="dataForm.list_indicator"
                        :data-list-options="[
                                  { label: 'Nombre del activo', name: 'name_vehicle_machinery', isShow: true },
                                { label: 'Placa', name: 'placa', isShow: true, refList:'selectRefPlaca' },
                                    ]" class-container="col-md-12" class-table="table table-bordered">
                        <template #fields="scope">
                            <div class="form-group row">

                                <!-- nombre activo -->
                                <div class="col-4 ">
                                    <autocomplete
                                        :min-text-input="1"
                                        asignar-al-data = "1"
                                        name-prop="name_vehicle_machinery"
                                        name-field="name_vehicle_machinery"
                                        :value='scope.dataForm'
                                        :name-resource="'assets/' + dataForm.type_combustible + ',' + dataForm.asset_process_id"
                                        css-class="form-control"
                                        :name-labels-display="['name_vehicle_machinery']"
                                        reduce-key="name_vehicle_machinery"
                                        :is-required="true" 
                                        :key="dataForm.type_combustible + dataForm.asset_process_id + keyRefresh"
                                    >
                                    </autocomplete>
                                    <small>Ingresar el nombre del activo</small>
                                </div>
        
                                <!-- carroceria -->
                                <div class="col-4 ">
                                    <select-check 
                                    ref-select-check="selectRefTipo"
                                    css-class="form-control" 
                                    name-field="body_type" 
                                    reduce-label="body_type"
                                    reduce-key="body_type" 
                                    :name-resource="'body-type/' + scope.dataForm.name_vehicle_machinery"
                                    :value="dataForm"
                                    :is-required="true" 
                                    :key="dataForm.type_combustible  + dataForm.type_combustible + dataForm.asset_process_id + scope.dataForm.name_vehicle_machinery + keyRefresh">
                                    </select-check>

                                    <small>Seleccione el tipo de carroceria</small>
                                </div>

                                <!-- placa -->
                                <div class="col-4 " >
                                    <select-check 
                                    ref-select-check="selectRefPlaca"
                                    css-class="form-control" 
                                    name-field="placa" 
                                    reduce-label='plaque'
                                    reduce-key="id"
                                    :name-resource="'fuel-effiency/plaques/' + dataForm.asset_process_id + '/' + dataForm.type_combustible + '/' + scope.dataForm.name_vehicle_machinery + '/' + dataForm.body_type"
                                    :value="scope.dataForm" 
                                    :is-required="true" 
                                    :key="dataForm.body_type">
                                    </select-check>
                                    <small>Seleccionar la placa.</small>
                                </div>
                            </div>
                            
                            <!-- fechas -->
                            <div class="row mt-2" >
                                <!-- fechas inicio -->
                                <div class="col-4">
                                    <input type="datetime-local" name="init_date" id="init_date" class="form-control" required
                                        v-model="dataForm.init_date" />
                                    <small>Fecha de inicio.</small>
                                </div>
                                 <!-- fechas fin-->
                                <div class="col-4">
                                    <input type="datetime-local" name="final_date" id="final_date" class="form-control" required
                                        v-model="dataForm.final_date" />
                                    <small>Fecha de final.</small>
                                </div>
                            </div>
                        </template>
                    </dynamic-list>
                </div>

                <!-- Formulario cuando el tipo de indicador sea Activos -->
                <div class="mt-5 row" v-if="dataForm.indicator_type == 'Activos'">
                
                    <div class="col-4 mb-3">
                        <select-check
                        css-class="form-control" 
                        name-field="asset_process_id" 
                        reduce-label="nombre" 
                        name-resource="/intranet/get-dependencies" 
                        :value="dataForm"
                        reduce-key="id" 
                        ></select-check>
                        <small>Seleccionar la dependencia</small>
                    </div>
               
                    <div class=" col-md-4">
                        <select class="form-control" 
                        name="status" 
                        id="status" 
                        v-model="dataForm.status"
                        :key="dataForm.asset_process_id + keyRefresh"
                            required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Dado de baja">Dado de baja</option>
                            <option value="Todas">Todos</option>
                        </select>
                        <small>Seleccione el estado</small>
                    </div>

                    <dynamic-list 
                        label-button-add="Agregar ítem a la lista" 
                        :data-list.sync="dataForm.list_indicator"
                        :data-list-options="[ { label: 'Nombre de Activo', name: 'name_vehicle_machinery', isShow: true },]" 
                                            class-container="col-md-12" class-table="table table-bordered">
                        <template #fields="scope">
                            <div class="form-group row">

                                <div class="col-4 ">
                                    <autocomplete
                                        asignar-al-data = "1"
                                        name-prop="nombre_activo"
                                        name-field="name_vehicle_machinery"
                                        :value='scope.dataForm'
                                        :name-resource="'get-asset/' + dataForm.asset_process_id + ',' + dataForm.status " 
                                        css-class="form-control"
                                        :name-labels-display="['nombre_activo']"
                                        reduce-key="nombre_activo"
                                        :is-required="true" 
                                        :key="dataForm.status + keyRefresh"
                                    >
                                    </autocomplete>
                                    <small>Ingresar el nombre del activo</small>
                                </div>
                            </div>

                            <div class="row mt-2" >
                                <div class="col-4">
                                    <input type="datetime-local" name="init_date" id="init_date" class="form-control" required
                                        v-model="dataForm.init_date" />
                                    <small>Fecha de inicio.</small>
                                </div>
                                <div class="col-4">
                                    <input type="datetime-local" name="final_date" id="final_date" class="form-control" required
                                        v-model="dataForm.final_date" />
                                    <small>Fecha de final.</small>
                                </div>
                            </div>
                        </template>
                    </dynamic-list>
                </div>

                <div class="mt-5 row"  v-if="dataForm.indicator_type == 'Ejecución de los contratos'">
                   
                    <dynamic-list 
                        label-button-add="Agregar ítem a la lista" 
                        :data-list.sync="dataForm.list_indicator"
                        :data-list-options="[ { label: 'Nombre del proveedor', name: 'provider_id', isShow: true , refList: 'selectProvider'},
                                                { label: 'Número de contrato', name: 'contract_id', isShow: false , refList: 'selectContract' },
                                                { label: 'Porcentaje de ejecución', name: 'porcentage', isShow: false },]" 
                                            class-container="col-md-12" class-table="table table-bordered">
                        <template #fields="scope">
                            <div class="form-group row">

                                <div class="col-4 ">
                                    <select-check
                                    :ids-to-empty="['contract_id']" 
                                    ref-select-check="selectProvider"
                                    css-class="form-control" 
                                    name-field="provider_id" 
                                    reduce-label="name" 
                                    name-resource="get-provider" 
                                    :value="scope.dataForm"
                                    reduce-key="id" 
                                    ></select-check>
                                    <small>Seleccionar el proveedor</small>
                                </div>
                        
                                <div class="col-4 ">
                                    <select-check
                                    ref-select-check="selectContract"
                                    css-class="form-control" 
                                    name-field="contract_id" 
                                    reduce-label="contract_number"
                                    :name-resource="'get-contract/' + scope.dataForm.provider_id " 
                                    :value="scope.dataForm"
                                    reduce-key="id" 
                                    dependent-id="provider_id" 
                                    :key="scope.dataForm.provider_id  + scope.dataForm.contract_id  + keyRefresh">
                                    ></select-check>
                                    <small>Seleccionar el número contrato</small>
                                </div>

                            </div>

                                <div class="row mt-2" >
                                    <div class="col-4">
                                        <input type="datetime-local" name="init_date" id="init_date" class="form-control" required
                                            v-model="dataForm.init_date" />
                                        <small>Fecha de inicio.</small>
                                    </div>
                                    <div class="col-4">
                                        <input type="datetime-local" name="final_date" id="final_date" class="form-control" required
                                            v-model="dataForm.final_date" />
                                        <small>Fecha de final.</small>
                                    </div>
                                </div>
                        </template>
                    </dynamic-list>
                </div>

                <div class="float-xl-center m-b-15 mt-5">
                    <button type="submit" class="btn btn-primary text-white">
                        <i class="fa fa-file-excel mr-2"></i>Generar Grafico
                    </button>

                   

                                      
                     <button class="btn btn-primary text-white" @click="clear()">
                        Limpiar Grafico
                    </button> 
                </div>
            </form>
        </div>
    </div>
</template>

<script lang="ts">

import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Componente para eportar excel de indicadores
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
 * @version 1.0.0
 */
@Component
export default class FormDataAnalytics extends Vue {
    /**
     * Key autoincrementable y unico para 
     * ayudar a refrescar un componente
     */
    public keyRefresh: number;

    /**
     * Valor del campo
     */
    @Prop({ type: Object })
    public listIndicator: any;

    /**
    * Valor del campo
    */
    @Prop({ type: Object })
    public dataForm: any;

    @Prop({ type: String })
    public body_type: any;
    

    public lang: any;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.keyRefresh = 0;
        this.dataForm = {
            list_indicator: [],
            body_type : ''
        };
        this.lang = (this.$parent as any).lang;

    }
    /**
     * envia los datos del al servidor y confirma con un sweeralert2
     *
     * @author Leo Herrera. - Dic. 26 - 2022
     * @version 1.0.0
     */
    public getPetition(): void {

        // this.dataForm.body_type = this.dataForm.list_indicator[0] 
        
        if (this.dataForm.indicator_type == "Rendimiento de combustible"  || this.dataForm.indicator_type == 'Consumo combustible'|| this.dataForm.indicator_type == 'Recorrido por tanqueo') {
            
            //Creamos un nuevo objeto donde vamos a almacenar por ciudades. 
            let newObject = {};

            if (this.dataForm.list_indicator.length) {
               
                //Recorremos el arreglo 
                this.dataForm.list_indicator.forEach(x => {
                    
                    //la creamos e inicializamos el arreglo de vehicles. 
                    if (!newObject.hasOwnProperty(x.name_vehicle_machinery)) {
                        newObject[x.name_vehicle_machinery] = {
                            vehicles: []
                        }
                    }

                    //Agregamos los datos de vehicles. 
                    newObject[x.name_vehicle_machinery].vehicles.push({
                        placa: x.placa,
                    })

                })

                this.dataForm.list_indicator = newObject;

            }

            (this.$parent.$refs['graphic'] as Vue)["dataForm"] = this.dataForm;
            (this.$parent.$refs['graphic'] as Vue)["_getDataOptions"]();

        } 
        
       else  if (this.dataForm.indicator_type == 'Activos') {

                        
            //Creamos un nuevo objeto donde vamos a almacenar por ciudades. 
            let newObject = {};

            if (this.dataForm.list_indicator.length) {
            
                //Recorremos el arreglo 
                this.dataForm.list_indicator.forEach(x => {
                    //la creamos e inicializamos el arreglo de vehicles. 
                    if (!newObject.hasOwnProperty(x.dependencias_id)) {
                        newObject[x.dependencias_id] = {
                            actives: []
                        }
                    }
                    //Agregamos los datos de vehicles. 
                    newObject[x.dependencias_id].actives.push({
                        asset: x.name_vehicle_machinery,
                    })
                })
                this.dataForm.list_indicator = newObject;
            }
            (this.$parent.$refs['graphic'] as Vue)["dataForm"] = this.dataForm;
            (this.$parent.$refs['graphic'] as Vue)["_getDataOptions"]();
            }
                
       else  if (this.dataForm.indicator_type == 'Ejecución de los contratos') {
            //Creamos un nuevo objeto donde vamos a almacenar por ciudades. 
            let newObject = {};

            if (this.dataForm.list_indicator.length) {

                //Recorremos el arreglo 
                this.dataForm.list_indicator.forEach(x => {
                    //la creamos e inicializamos el arreglo de vehicles. 
                    if (!newObject.hasOwnProperty(x.provider_id)) {
                        newObject[x.provider_id] = {
                            contract: []
                        }
                    }
                    //Agregamos los datos de vehicles. 
                    newObject[x.provider_id].contract.push({
                        contract_id: x.contract_id,
                    })
                })
                this.dataForm.list_indicator = newObject;
            }
            (this.$parent.$refs['graphic'] as Vue)["dataForm"] = this.dataForm;
            (this.$parent.$refs['graphic'] as Vue)["_getDataOptions"]();
        }

        this.dataForm = {
            list_indicator: []
        };
    }

    /**
     * Limpia los campos
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     */
    public clear(): void {
        this.dataForm.indicator_type = "";
        this.dataForm.type_combustible = "";
        this.dataForm.list_indicator= "";
        this.dataForm.provider_id = "";
        this.dataForm.contract_id = "";
        this.dataForm.init_date = "";
        this.dataForm.final_date = "";
        this.dataForm.dependencias_id = "";
        this.dataForm.status = "";
        this.dataForm.asset = "";
        (this.$parent.$refs['graphic'] as Vue)["dataForm"] = this.dataForm;
        // (this.$parent.$refs['graphic'] as Vue)["_getDataOptions"]();
        // Actualiza componente de refresco
        this._updateKeyRefresh();
    }
    /**
     * Actualiza el componente que utilize el key
     * cada vez que se cambia de editar a actualizar
     * y borrado de campos de formulario
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
     * @version 1.0.0
     */
    private _updateKeyRefresh(): void {
        this.keyRefresh++;
    }
}

</script>
