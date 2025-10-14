<template>
  <div class="modal fade" id="modal-form-oil">
    <div class="modal-dialog modal-xl">
      <form @submit.prevent="save()" id="form-oil">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">
              {{ 'trans.form_of' | trans }} registrar el aceite
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
          <div class="modal-body" >

            <div class="panel" data-sortable-id="ui-general-1">
            <!-- begin panel-heading -->
            <div class="panel-heading">
                <h4 class="panel-title" >Información general</h4> 
            </div>

    <div class="panel-body">

        <div class="form-group row m-b-15">
            <!-- Fecha de la factura  -->
            <label class="col-form-label col-md-2" for="invoice_date" >Fecha de registro:</label>
            <div class="col-md-4">
                <date-picker
                    :value="dataForm"
                    name-field="register_date"
                    :key="keyRefresh"
                >
                </date-picker>
            </div>
            
            <!-- Placa -->
            <label class="col-form-label col-md-2 required" for="mant_resume_machinery_vehicles_yellow_id" >{{ 'trans.Plaque' | trans }}:</label>
            <div class="col-md-4">
                <auto
                    :is-update="isUpdate"
                    name-prop="plaque"
                    :value-default="dataForm.resume_machinery_vehicles_yellow"
                    name-field="mant_resume_machinery_vehicles_yellow_id"
                    :name-labels-display="['plaque']"
                    :value='dataForm'
                    name-resource='get-vehicle'
                    css-class="form-control"
                    reduce-key="id"
                    asignar-al-data=""
                    :match-selected="selectVehicle"
                    :key="keyRefresh"
                    :min-text-input="2"
                    :is-required="true"
                    >
                </auto>
            </div> 
        </div>

        <!-- Tipo de activo -->
        <div class="form-group row m-b-15">

            <!-- Nombre del activo -->
            <label class="col-form-label col-md-2 required" for="asset_name" >Nombre del activo:</label>
            <div class="col-md-4">
                <input disabled type="text" id="asset_name" :class="{'form-control':true, 'is-invalid':dataErrors.asset_name }" v-model="dataForm.asset_name">  
            </div>

            <label class="col-form-label col-md-2 required" for="mant_asset_type_name" >{{ 'trans.Mant_asset_type' | trans }}:</label>
            <div class="col-md-4">
                <input disabled type="text" id="mant_asset_type_id" :class="{'form-control':true, 'is-invalid':dataErrors.mant_asset_type_name }" v-model="dataForm.mant_asset_type_name">  
            </div>
        </div>

        <!-- Hora de tanqueo -->
        <div class="form-group row m-b-15">
            <!-- Nombre del activo -->  
            <label class="col-form-label col-md-2 required" for="dependencies_name" >Proceso:</label>
            <div class="col-md-4">
                <input disabled type="text" id="dependencies_name" :class="{'form-control':true, 'is-invalid':dataErrors.dependencies_name }" v-model="dataForm.dependencies_name">
            </div>
           
           <!-- Nombre del conductor -->
            <label class="col-form-label col-md-2 required" for="show_type" >Tipo de muestra:</label>
            <div class="col-md-4 ">
              <input type="text" id="show_type" :class="{'form-control':true, 'is-invalid':dataErrors.show_type }" v-model="dataForm.show_type">
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Cantidad de combustible -->
            <label class="col-form-label col-md-2 required" for="fuel_quantity" >Componente:</label>
            <div class="col-md-4">
                <select name="component" id="component" v-model="dataForm.component" @change="nameElement(dataForm.component)" class="form-control" required>
                  <option value="Motor" >Motor</option>
                  <option value="Caja" >Caja</option>
                  <option value="Transmisión (Dif. 1)" >Transmisión (Dif. 1)</option>
                  <option value="Transmisión (Dif. 2)" >Transmisión (Dif. 2)</option>
                  <option value="Refrigerante" >Refrigerante</option>
                  <option value="Otro" >Otro</option>
              </select>            
            </div>

            <!-- Cantidad de combustible -->
            <label class="col-form-label col-md-2" for="equipment_number" >Número de equipo:</label>
            <div class="col-md-4">
                <input type="text" id="equipment_number" :class="{'form-control':true, 'is-invalid':dataErrors.equipment_number }" v-model="dataForm.equipment_number" >
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Kilometraje actual -->
            <label class="col-form-label col-md-2 required" for="brand" >Marca:</label>
            <div class="col-md-4">
                <input type="text" id="brand" :class="{'form-control':true, 'is-invalid':dataErrors.brand }"  v-model="dataForm.brand" required>
   
            </div> 

            <!-- Kilometraje anterior -->
            <label class="col-form-label col-md-2" for="serial_number" >Número de serie:</label>
            <div class="col-md-4">
                <input type="text" id="serial_number" :class="{'form-control':true, 'is-invalid':dataErrors.serial_number }" v-model="dataForm.serial_number">
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Horometro actual -->
            <label class="col-form-label col-md-2 required" for="work_order" >Orden de trabajo:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.work_order }" v-model="dataForm.work_order" required>
            </div>
            <!-- Horometro anterior -->
            <label class="col-form-label col-md-2 required" for="number_warranty_extended" >Número de garantía extendida:</label>
            <div class="col-md-4">
                <input type="number" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.number_warranty_extended }" v-model="dataForm.number_warranty_extended" required>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Horometro actual -->
            <label class="col-form-label col-md-2 " for="model_component" >Modelo del componente:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.model_component }" v-model="dataForm.model_component">
            </div>

            <!-- Horometro actual -->
            <label class="col-form-label col-md-2 " for="serial_component" >Serie componente:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.serial_component }" v-model="dataForm.serial_component">
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Horometro actual -->
            <label class="col-form-label col-md-2 required" for="number_control_lab" >Número control lab:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.number_control_lab }" v-model="dataForm.number_control_lab" required>
            </div>

            <!-- Horometro actual -->
            <label class="col-form-label col-md-2" for="maker_component" >Fabricante del producto:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.maker_component }" v-model="dataForm.maker_component">
            </div>
        </div>

        <div class="form-group row m-b-15">

            <!-- Horometro actual -->
            <label class="col-form-label col-md-2" for="grade_oil" >Marca/grado de aceite:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.grade_oil }" v-model="dataForm.grade_oil">
            </div>
        </div>
            </div>
        </div>  


        <div class="panel" data-sortable-id="ui-general-1">
            <div class="panel-heading">
                <h4 class="panel-title text-left">Control laboratorio</h4> 
            </div>

                <div class="panel-body">
                    <dynamic-list
            ref="referenceDynamic"
            label-button-add="Agregar ítem a la lista"
            :data-list.sync="dataForm.oil_control_laboratories"
            :data-list-options="[
               {label:'Fecha de muestreo', name:'date_sampling', isShow: true},
               {label:'Fecha proceso', name:'date_process', isShow: true},
               {label:'Hórometro', name:'hourmeter', isShow: true},
               {label:'Antigüedad aceite', name:'oil_hours', isShow: true},
               {label:'Kilometraje', name:'kilometer', isShow: true},
               {label:'Relleno', name:'filling', isShow: true},
               {label:'¿Cambio aceite?', name:'change_oil', isShow: true},
               {label:'¿Cambio de filtro?', name:'change_filter', isShow: true},
               {label:'Unidades del relleno', name:'filling_units', isShow: true},
               {label:'Observación', name:'observation', isShow: true},
            ]"
            class-container="col-md-12"
            class-table="table table-bordered"
            :key="keyRefresh"
            >
            <template #fields="scope">
                                        
                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="date_sampling" >Fecha de muestreo:</label>
                        <div class="col-md-3">
                          <input 
                            type="date" 
                            v-model="scope.dataForm.date_sampling"
                            class="vc-appearance-none vc-text-base vc-text-gray-800 vc-bg-white vc-border vc-border-gray-400 vc-rounded vc-w-full vc-py-2 vc-px-3 vc-leading-tight focus:vc-outline-none focus:vc-shadow"
                            @change="checkMileage(dataForm.mant_resume_machinery_vehicles_yellow_id,scope.dataForm.date_sampling)"
                            />
                        </div>

                        <label class="col-form-label col-md-3 required" for="date_process" >Fecha de proceso:</label>
                        <div class="col-md-3">
                          <date-picker
                              :value="scope.dataForm"
                              name-field="date_process"
                              :input-props="{required: true}"
                              :key="keyRefresh"
                          >
                          </date-picker>
                        </div>
                    </div>
                    
                    
                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="hourmeter" > {{ scope.dataForm.suffixKmHr == false ? 'Kilometraje' : 'Hórometro'}}:</label>
                       
                        <div v-if="scope.dataForm.suffixKmHr == false"  class="col-md-3">
                          <currency-input 
                              v-model="scope.dataForm.kilometer"
                              :currency="{ suffix: ' km' }"
                              locale="es"
                              :precision="0"
                              class="form-control"
                              disabled
                          >
                          </currency-input>
                        </div>

                        
                        <div v-else class="col-md-3">
                          <currency-input
                              v-model="scope.dataForm.hourmeter"
                              :currency="{ suffix: ' hm' }"
                              locale="es"
                              :precision="0"
                              class="form-control"
                              disabled
                          >
                          </currency-input>
                        </div>

                        <label class="col-form-label col-md-3 required" for="oil_hours" >Antigüedad del aceite:</label>
                        <div class="col-md-3">
                            <input type="number" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.oil_hours }" v-model="scope.dataForm.oil_hours">
                        </div>
                     </div>

                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="filling" >Aceite adicionado:</label>
                        <div class="col-md-3">
                            <input type="text" id="filling" :class="{'form-control':true, 'is-invalid':dataErrors.filling }" v-model="scope.dataForm.filling">
                        </div>

                        
                        <label class="col-form-label col-md-3 required" for="change_oil" >¿Cambio aceite?:</label>
                        <div class="col-md-3">
                          <select name="change_oil" id="change_oil" v-model="scope.dataForm.change_oil" class="form-control" required>
                            <option value="No" >No</option>
                            <option value="Si" >Si</option>
                          </select>  
                        </div>
                     </div>

                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="change_filter" >¿Cambio de filtro?:</label>
                        <div class="col-md-3">
                          <select name="change_filter" id="change_filter" v-model="scope.dataForm.change_filter" class="form-control" required>
                            <option value="No" >No</option>
                            <option value="Si" >Si</option>
                          </select>  
                        </div>

                        
                        <label class="col-form-label col-md-3 " for="filling_units" >Unidades de relleno:</label>
                        <div class="col-md-3">
                            <input type="text" id="filling_units" :class="{'form-control':true, 'is-invalid':dataErrors.filling_units }" v-model="scope.dataForm.filling_units">
                        </div>
                     </div>



                     <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 " for="filling_units" >Observación:
                      </label>
                      <div class="col-md-9">
                        <textarea
                          name="observation"
                          id="observation"
                          rows="8"
                          v-model="
                              scope.dataForm.observation
                          "
                          class="form-control"
                          required
                      ></textarea>
                        </div>
                     </div>
                  
               </template>
         </dynamic-list>

                </div>
            </div>      

<div class="panel" data-sortable-id="ui-general-1">
            <div class="panel-heading">
                <h4 class="panel-title text-left">Elementos de desgaste</h4> 
            </div>
                <div class="panel-body">
                    <dynamic-list
                    ref="list"
            label-button-add="Agregar ítem a la lista"
            :data-list.sync="dataForm.oil_element_wears"
            :key="keyRefresh"
            :data-list-options="[
               {label:'Número de control laboratorio', name:'number_control_laboratory', isShow: true },
               {label:'Nombre del elemento de desgaste', name:'mant_oil_element_wear_configurations_id', isShow: true, nameObjectKey: ['name_oil_element_wear', 'element_name'], refList:'oil_element_wears_ref'},
               {label:'Grupo', name:'group', isShow: true}, 
               {label:'Valor detectado', name:'detected_value', isShow: true}, 
               {label:'Rango', name:'range', isShow: true}
            ]"
            class-container="col-md-12"
            class-table="table table-bordered"
            >
            <template #fields="scope">

                    <div class="form-group row m-b-15">
                        <label class="col-form-label col-md-3 required" for="number_control_laboratory" >Número de control de laboratorio:</label>
                        <div class="col-md-3">
                            <input type="text" id="number_control_laboratory" :class="{'form-control':true, 'is-invalid':dataErrors.work_order }" v-model="scope.dataForm.number_control_laboratory" required>
                        </div>

                        <label class="col-form-label col-md-3 required" for="mant_oil_element_wear_configurations_id" >Nombre del elemento de desgaste:</label>
                        <div class="col-md-3">

                          <select-check
                              css-class="form-control"
                              name-field="mant_oil_element_wear_configurations_id"
                              :reduce-label="['element_name','rank_lower','rank_higher']"
                              :key="dataForm.component"
                              reduce-key="id"
                              ref-select-check="oil_element_wears_ref"
                              :name-resource="'get-element-wear/'+ dataForm.component"
                              :value="scope.dataForm"
                              name-field-object="name_oil_element_wear"
                              :is-required="true"
                              :function-change="verifyRange"
                              >
                          </select-check>
                        </div>
                    </div>

                     <div class="form-group row m-b-15">
                      <label class="col-form-label col-md-3 required" for="group" >Grupo:</label>
                      <div class="col-md-3">
                          <input-disabled :name-resource="'group-configuration-oil/' + scope.dataForm.mant_oil_element_wear_configurations_id" :value="scope.dataForm" name-field="group" name-prop="group" :key="scope.dataForm.mant_oil_element_wear_configurations_id"></input-disabled>

                        </div>

                        <label class="col-form-label col-md-3 required" for="detected_value" >Valor detectado:</label>
                        <div class="col-md-3">
                            <input type="text" @blur="verifyData($event);" id="detected_value" :class="{'form-control':true, 'is-invalid':dataErrors.detected_value }" v-model="scope.dataForm.detected_value" required>
                        </div>

                    </div>

                    <div class="form-group row m-b-15">

                        <label class="col-form-label col-md-3 required" for="range" >Rango:</label>
                        <div class="col-md-3">
                            <input type="text" id="range" :class="{'form-control':true, 'is-invalid':dataErrors.range }" v-model="scope.dataForm.range" name="range"  disabled required>
                        </div>
                    </div>

              </template>
        </dynamic-list>

                </div>
            </div>      

        <div class="form-group row m-b-15" v-if="dataForm.id > 0">

          <!-- Kilometraje anterior -->
          <label class="col-form-label col-md-2 required" for="descriptionDelete" >Observación porque va editar este registro:</label>
            <div class="col-md-10">
                <textarea  type="textarea" id="descriptionDelete" :class="{'form-control':true, 'is-invalid':dataErrors.descriptionDelete }"  v-model="dataForm.descriptionDelete" required></textarea>
                <small style="font-size:9px">Ingrese porque va editar este registro</small>
            </div>
        </div>

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
              <i class="fa fa-save mr-2"></i>Guardar
            </button>
          </div>

        </div>

      </form>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from "jwt-decode";
import * as bootstrap from "bootstrap";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { Console } from "console";
import VueCurrencyInput from 'vue-currency-input';
import DynamicListComponent from "../../components/core/DynamicListComponent.vue";

const locale = new Locale();

/**
 * Componente para registro de gestion de combustible de vehiculos
 *
 * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
 * @version 1.0.0
 */
@Component
export default class OilsCreate extends Vue {

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


  public formType: String;

  /**
   * Rango inferior
   */
  public lowRank: any;

  /**
   * Rango superior
   */
  public highRank: any;

  /**
   * Valida si es horometro o kilometro
   */
  public suffixKmHr: boolean;

  /**
   * Constructor de la clase
   *
   * @author Carlos Moises Garcia T. - Oct. 13 - 2020
   * @version 1.0.0
   */
  constructor() {
    super();
    this.keyRefresh = 0;
    this.dataForm = {oil_element_wears: [],oil_control_laboratories: []};
    this.dataErrors = {};
    this.isUpdate = false;
    this.lang = (this.$parent as any).lang;
    this.suffixKmHr = false;
  }



  //Asigna la data al componente dinamyc list, todo esto para poder realizar las validaciones correctemente en el formulario
  mounted() {
    this.$set((this.$refs.referenceDynamic as DynamicListComponent).dataForm, 'suffixKmHr', this.suffixKmHr);
  }


  public nameElement(name){
    this.$set((this.$refs.list as DynamicListComponent).dataForm, 'componentParam', name);
  }

  /**
   * Limpia los filtros de busqueda
   *
   * @author Andres Stiven Pinzon G. - Dic. 19 - 2021
   * @version 1.0.0
   */
  public cleanOil ():void{
    (this.$parent as any)["clearDataSearch"]();
    (this.$parent.$refs['placa'] as Vue)["_clean"]();
  }

    /**
   * Limpia los filtros de busqueda
   *
   * @author Andres Stiven Pinzon G. - Dic. 19 - 2021
   * @version 1.0.0
   */
  public verifyRange(): void {
    this.showLoadingGif("Obteniendo datos");
    if ((this.$refs['list'] as Vue)['dataForm']['mant_oil_element_wear_configurations_id']) {
      axios
        .get(`get-element/${(this.$refs['list'] as Vue)['dataForm']['mant_oil_element_wear_configurations_id']}`)
        .then((res) => {
          let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
          const dataDecrypted = Object.assign({data:{}}, dataPayload);
          if (dataDecrypted["data"]) {
            // Se le asigna el valor a la variable que almacena el valor de rango inferior
            this.lowRank = dataDecrypted["data"]["rank_lower"];
            // Se le asigna el valor a la variable que almacena el valor de rango superior
            this.highRank = dataDecrypted["data"]["rank_higher"];
          }
          this.$swal.close();
        })
        .catch((err) => {
          console.log("error", err);
          this.$swal.close(); // Asegurarse de cerrar el swal incluso si hay un error
        });
    }
  }

      //Consulta el kilometraje inicial del combustible por medio de la placa yu la fecha de asignacion
      public checkMileage(id_machinery, fecha) {
        // Lógica de tu función (por ejemplo, llamar a `resta` u otras funciones)

            axios
            .get(`get-check-fuel-mileage/${id_machinery}/${fecha}`)
            .then(res => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                //Valida si el valor de request es diferente de null
                if (dataDecrypted != null) {
                    this.$set((this.$refs.referenceDynamic as DynamicListComponent).dataForm, 'kilometer', dataDecrypted["data"]["current_mileage"]);

                    this.$set(
                        this.dataForm,
                        "mant_vehicle_fuels_id",
                        dataDecrypted["data"]["id"]
                    );

                    if (dataDecrypted["data"]["current_hourmeter"]) {
                      this.$set((this.$refs.referenceDynamic as DynamicListComponent).dataForm, 'suffixKmHr', true);
                      this.$set((this.$refs.referenceDynamic as DynamicListComponent).dataForm, 'hourmeter', dataDecrypted["data"]["current_hourmeter"]);
                    }else{
                      this.$set((this.$refs.referenceDynamic as DynamicListComponent).dataForm, 'suffixKmHr', false);

                    }

                    //De lo contrario muestra un swal
                } else {
                        this.$swal({
                            icon: "info",
                            html:
                                "No se encontró un combustible para la placa y la fecha de asignación ingresadas. Por favor, verifique los datos o ingrese manualmente el kilometraje inicial.",
                            confirmButtonText: "Aceptar",
                            allowOutsideClick: false
                        });
                    }
            });

    }

  /**
   * Verifica que en el campo valor detectado, se ingresen solo numeros o solo letras dependiendo los rangos del elemento de desgaste
   * Si los rangos son valores numericos, el campo valor detectado solo recibira valores numericos, asi mismo si los rangos son soo letras. ejem(si,no,normal)
   *
   * @author Andres Stiven Pinzon G. - Dic. 22 - 2021
   * @version 1.0.0
   */
  public verifyData(event): void {

    let detectedValue = event.target.value;
    let numbers = /^[0-9]+$/;
    // Valida que el rango superior solo tenga caracteres numericos
    if(this.highRank.match(numbers)){
      // Valida que el campo valor detectado solo tenga caracteres numericos, si tiene algun caracter diferente a un numero saltara un mensaje en pantalla
      if(!detectedValue.match(numbers)){
        $("#detected_value").val("");
        (this.$refs['list'] as Vue)['dataForm']['detected_value']=null;
        (this.$refs['list'] as Vue)['dataForm']['range']=null;
          $("#range").val("");
        this.$swal({
        icon: 'warning',
        title: '¡Advertencia!',
        text: 'Por favor ingrese un valor acorde al elemento de desgaste'
        });
      }else{
        let rankHigh = parseInt(this.highRank);
        let rankLow = parseInt(this.lowRank);
        detectedValue = parseInt(detectedValue);
        // valida si el valor ingresado es mayor al rango superior
        if(detectedValue>rankHigh){
          (this.$refs['list'] as Vue)['dataForm']['range']="Alto";
          $("#range").val("Alto");
        }
        // valida si el valor ingresado es mayor al rango inferior
        else if(detectedValue<rankLow){
          (this.$refs['list'] as Vue)['dataForm']['range']="Bajo";
          $("#range").val("Bajo");
        }
        // Si el valor detectado no es ni mayor ni menor, entonces el rango sera normal
        else{
          (this.$refs['list'] as Vue)['dataForm']['range']="Normal";
          $("#range").val("Normal");
        }
      }
    }else{
      // Valida que el campo valor detectado solo tenga valores que no sean numericos, si tiene algun caracter diferente a un numero saltara un mensaje en pantalla
      if(detectedValue.match(numbers)){
        $("#detected_value").val("");
        (this.$refs['list'] as Vue)['dataForm']['detected_value']=null;
        (this.$refs['list'] as Vue)['dataForm']['range']=null;
        $("#range").val("");
        this.$swal({
        icon: 'warning',
        title: '¡Advertencia!',
        text: 'Por favor ingrese un valor acorde al elemento de desgaste'
        });
      }else{
        // Si el rango superior es igual al valor detectado entonces el rango tendra el valor "Alto"
        if(detectedValue==this.highRank){
          (this.$refs['list'] as Vue)['dataForm']['range']="Alto";
          $("#range").val("Alto");
        }
        // Si el rango inferior es igual al valor detectado entonces el rango tendra el valor "Bajo"
        else if(detectedValue==this.lowRank){
          (this.$refs['list'] as Vue)['dataForm']['range']="Bajo";
          $("#range").val("Bajo");
        }
        // Si el valor detectado es normal entonces el rango tendra el valor "Bajo"
        else if(detectedValue=="normal"){
          (this.$refs['list'] as Vue)['dataForm']['range']="Normal";
          $("#range").val("Normal");
        }
        // Si el valor ingresado en el campo valor detectado no coincide con los, entonces aparecerane pantalla el siguiente mensaje
        else{
          this.$swal({
          icon: 'warning',
          title: '¡Advertencia!',
          text: 'Por favor ingrese un valor que este en el rango superior o inferior'
          });
        }
      }
    }
  }


  /**
   * Limpia los datos del fomulario
   *
   * @author Andres Stiven Pinzon G. - Dic. 18 - 2021
   * @version 1.0.0
   */
  public clearDataForm(): void {
    // Inicializa valores del dataform
    this.dataForm = {oil_element_wears: [],oil_control_laboratories: []};
    // Limpia errores
    this.dataErrors = {};
    // Actualiza componente de refresco
    this._updateKeyRefresh();
  }

  /**
   * Asigna los datos al dataForm del tipo de activo y de dependencias en los input de proceso,nombre y tipo de activo
   *
   * @author Andres Stiven Pinzon G. - Dic. 18 - 2021
   * @version 1.0.0
   *
   */
  public selectVehicle(data): void {

    //Se transforman a array los objetos de dependencias y tipos de activos
    let dependencias= Object.values(data.dependencies);
    let assetTypes= Object.values(data.asset_type);

    //Se valida que el array dependencias no este vacio
    if(dependencias.length > 0){
      //Se asigna valor al campo a mostrar(proceso)
      this.dataForm.dependencies_name = data.dependencies.nombre;
      //Se asigna el id de la dependencia al campo correspondiente
      this.dataForm.dependencies_id = data.dependencies.id;
    }
    //Se asigna valor al campo a mostrar(nombre del activo)
    this.dataForm.asset_name = data.name_vehicle_machinery;
    this.dataForm.fuel_type_name = data.fuel_type;
      
    //Se valida que el array de tipos de activos no este vacio
    if(assetTypes.length > 0){
      //Se asigna valor al campo a mostrar(tipo de activo)
      this.dataForm.mant_asset_type_id = data.asset_type.id;
      //Se asigna el id de la dependencia al campo correspondiente
      this.dataForm.mant_asset_type_name = data.asset_type.name;
    }

    // Actualiza todas las variables del componente
    this.$forceUpdate();
  }

  /**
   * Cargar los datos en modo edición
   *
   * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
   * @version 1.0.0
   *
   * @param dataElement elemento de grupo de trabajo
   */
  public loadOil(dataElement: object): void {
    
    // Valida que exista datos
    if (dataElement) {
      // Habilita actualizacion de datos
      this.isUpdate = true;
      // Busca el elemento deseado y agrega datos al formulario
      this.dataForm = utility.clone(dataElement);
       
      if (this.dataForm.asset_type && this.dataForm.resume_machinery_vehicles_yellow) {
        this.dataForm.asset_name = this.dataForm.resume_machinery_vehicles_yellow.name_vehicle_machinery;

        this.dataForm.mant_asset_type_id = this.dataForm.asset_type.id;
        this.dataForm.mant_asset_type_name = this.dataForm.asset_type.name;
        this.dataForm.fuel_type_name = this.dataForm.resume_machinery_vehicles_yellow.fuel_type;

        if(this.dataForm.dependencias){
          this.dataForm.dependencies_name = this.dataForm.dependencias.nombre;
          this.dataForm.dependencies_id = this.dataForm.dependencias.id;
        }
       
        // Actualiza componente de refresco
        this._updateKeyRefresh();
      }
      // detecta el enter para no cerrar el modal sin enviar el formulario
      $("#modal-form-oil").on("keypress", (e) => {
        if (e.keyCode === 13) {
          e.preventDefault();
        }
      });
      $("#modal-form-oil").modal("show");
    } else {
      this.isUpdate = false;
      $("#modal-form-oil").modal("show");
    }
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
    // Valida si los datos son para actualizar
    if (this.isUpdate) {
      // Actualiza un registro existente
      this.update();
    } else {
      // Almacena un nuevo registro
      this.store();
    }
  }

  /**
   * Guarda la informacion en base de datos
   *
   * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
   * @version 1.0.0
   */
  public store(): void {
    this.$swal({
      title: this.lang.get("trans.loading_save"),
      allowOutsideClick: false,
      onBeforeOpen: () => {
        (this.$swal as any).showLoading();
      },
    });

    // Envia peticion de guardado de datos
    axios
      .post("oil", this.makeFormData(), {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

        const dataDecrypted = Object.assign({}, dataPayload);
        
        if(dataDecrypted["data"]==1){           
              // Abre el swal
               this.$swal({
                 icon: 'warning',
                 title: '¡Advertencia!',
                 text: dataDecrypted["data"]["message"],
                });
           
        }else{
            // Agrega elemento nuevo a la lista
            (this.$parent as any).dataList.unshift(dataDecrypted["data"]);

            (this.$swal as any).close();

            // Cierra fomrulario modal
            $(`#modal-form-oil`).modal("toggle");

            // Limpia datos ingresados
            this.clearDataForm();
            // Emite notificacion de almacenamiento de datos
            (this.$parent as any)._pushNotification(dataDecrypted["data"]["message"]);
        }
        
      })
      .catch((err) => {
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
   * @author Andres Stiven Pinzon. - Dic. 17 - 2020
   * @version 1.0.0
   */
  public update(): void {
    // Abre el swal de guardando datos
    this.$swal({
      title: this.lang.get("trans.loading_update"),
      allowOutsideClick: false,
      onBeforeOpen: () => {
        (this.$swal as any).showLoading();
      },
    });

    const formData: FormData = this.makeFormData();
    formData.append("_method", "put");

    // Envia peticion de guardado de datos
    axios
      .post(`oil/${this.dataForm["id"]}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {

        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

        const dataDecrypted = Object.assign({}, dataPayload);
         
          Object.assign(
            (this.$parent as any)._findElementById(this.dataForm["id"], false),
            dataPayload["data"] 
          );
          // Cierra el swal de guardando datos
          (this.$swal as any).close();

          // Cierra fomrulario modal
          $(`#modal-form-oil`).modal("toggle");
          // Limpia datos ingresados
          this.clearDataForm();
          // Emite notificacion de actualizacion de datos
          (this.$parent as any)._pushNotification(res.data.message);

        
      })
      .catch((err) => {
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
   * Funcion del gif que se muestra de cargando
   *
   * @author Manuel Marin.  2024
   * @version 1.0.0
  */
  private showLoadingGif(message: string): void {
      // Mostrar un indicador de carga
      this.$swal({
        html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>' + message + '.</span>',
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false
      });
  }
}
</script>