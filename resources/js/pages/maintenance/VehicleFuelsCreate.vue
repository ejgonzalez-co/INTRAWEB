<template>
  <div class="modal fade" id="modal-form-vehicle-fuels">
    <div class="modal-dialog modal-lg">
      <form @submit.prevent="save()" id="form-vehicleFuels">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">
              {{ 'trans.form_of' | trans }} registro de combustibles para vehículos
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
            <!-- Placa -->
            <label class="col-form-label col-md-2 required" for="mant_resume_machinery_vehicles_yellow_id" >{{ 'trans.Plaque' | trans }}:</label>
            <div class="col-md-4" v-if="isUpdate">
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
                    :key="keyRefresh"
                    :is-required="true"
                    :disabled="true"
                    >
                </auto>
                <small>{{ 'trans.Enter the' | trans }} la placa del vehículo</small>
            </div>

            <div class="col-md-4" v-else>
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
                    :match-selected="selectVehicle"
                    :key="keyRefresh"
                    :min-text-input="2"
                    asignar-al-data=""
                    :is-required="true"
                    >
                </auto>
                <small>{{ 'trans.Enter the' | trans }} la placa del vehículo</small>
            </div>

            <!-- Nombre del activo -->
            <label class="col-form-label col-md-2 required" for="asset_name" >Nombre del activo:</label>
            <div class="col-md-4">
                <input disabled type="text" id="asset_name" :class="{'form-control':true, 'is-invalid':dataErrors.asset_name }" v-model="dataForm.asset_name">  
                <small>Nombre del activo</small>
            </div>
        </div>

        <!-- Tipo de activo -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-2 required" for="mant_asset_type_name" >{{ 'trans.Mant_asset_type' | trans }}:</label>
            <div class="col-md-4">
                <input disabled type="text" id="mant_asset_type_id" :class="{'form-control':true, 'is-invalid':dataErrors.mant_asset_type_name }" v-model="dataForm.mant_asset_type_name">  
                <small>Tipo de activo</small>
            </div>

            <!-- Nombre del activo -->  
            <label class="col-form-label col-md-2 required" for="dependencies_name" >Proceso:</label>
            <div class="col-md-4">
                <input disabled type="text" id="dependencies_name" :class="{'form-control':true, 'is-invalid':dataErrors.dependencies_name }" v-model="dataForm.dependencies_name">
                <small>Proceso</small>
            </div>
        </div>

        <!-- Numero de la factura -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-2 required" for="invoice_number" >{{ 'trans.Invoice number' | trans }}:</label>
            <div class="col-md-4">
                <input type="text" id="invoice_number" :class="{'form-control':true, 'is-invalid':dataErrors.invoice_number }" v-model="dataForm.invoice_number" required>
                <small>Ingrese el número del recibo</small>
            </div>

            <!-- Fecha de la factura  -->
            <label class="col-form-label col-md-2 required" for="invoice_date" >Fecha de recibo:</label>
            <div class="col-md-4">
                <date-picker
                    :value="dataForm"
                    name-field="invoice_date"
                    :input-props="{required: true}"
                    :key="keyRefresh"
                    :max-date="this.currentDate"
                >
                </date-picker>
                <small>Seleccione la fecha del recibo</small>
            </div>
        </div>

        <!-- Hora de tanqueo -->
        <div class="form-group row m-b-15">
            <label class="col-form-label col-md-2 required" for="tanking_hour" >Hora de tanqueo:</label>
            <div class="col-md-2">
                <!-- <select @blur="fillClock()"  class="form-control"  v-model="dataForm.hour" required>
                  <option  v-for="hour in hours"  >{{ hour }} </option>
                </select> -->
                <input type="text" id="hour" :class="{'form-control':true, 'is-invalid':dataErrors.hour }" v-model="dataForm.hour" @keyup="noLettersHour();" minlength="2" maxlength="2" name="hour" required>
                <!-- <small>La selección es tipo hora militar.</small>    -->
                <small>Ingrese la hora de tanqueo(Formato hora militar).</small>   
            </div>
            <p style="font-size: 20px">:</p> 
            <div class="col-md-2">
                <!-- <select @blur="fillClock()"  class="form-control"  v-model="dataForm.minute" required>
                  <option v-for="minute in minutes"  >{{ minute }} </option>
                </select> -->
                <input type="text" id="minute" :class="{'form-control':true, 'is-invalid':dataErrors.minute }" v-model="dataForm.minute" @keyup="noLettersMinute();" minlength="2" maxlength="2" name="minute" required>
                <!-- <small>La selección es tipo hora militar.</small>    -->
                <small>Ingrese el minuto de la hora de tanqueo.</small>   
            </div>
        </div>

        <div class="form-group row m-b-15">
            <!-- Nombre del conductor -->
            <label class="col-form-label col-md-2 required" for="driver_name" >Nombre del conductor:</label>
            <div class="col-md-4">
                <input type="text" id="driver_name" :class="{'form-control':true, 'is-invalid':dataErrors.driver_name }" v-model="dataForm.driver_name" required>
                <small>{{ 'trans.Enter the' | trans }}  el nombre del conductor</small>
            </div>
      
            <!-- Cantidad de combustible -->
            <label class="col-form-label col-md-2 required" for="fuel_quantity" >Cantidad de combustible:</label>
            <div class="col-md-4">
                <currency-input
                v-model.number="dataForm.fuel_quantity"
                :required="true"
                :currency="{'prefix': ''}"
                locale="es"
                :precision="3"
                class="form-control"
                :key="keyRefresh"
                v-on:keyup="cantFuel()"
                >
                </currency-input>
                <!-- <input type="text" id="fuel_quantity" :class="{'form-control':true, 'is-invalid':dataErrors.fuel_quantity }" @keyup="cantFuel($event)" v-model="dataForm.fuel_quantity" required> -->
                <small style="font-size:9px"> {{ 'trans.Enter the' | trans }} la cantidad de combustible(Ejemplo: 15.2)</small>
            </div>
        </div>

        <div class="form-group row m-b-15" v-if="!dataForm.current_hourmeter && !dataForm.previous_hourmeter">
            <!-- Kilometraje actual -->
            <label class="col-form-label col-md-2 required" for="current_mileage" >Kilometraje actual:</label>
            <div class="col-md-4">
                <input type="text" id="current_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.current_mileage }" @keyup="variationRouteTanking($event)" v-model="dataForm.current_mileage" required>
                <small style="font-size:9px">{{ 'trans.Enter the' | trans }} el kilometraje actual(Ejemplo: 2200)</small>
            </div> 

            <!-- Kilometraje anterior -->
            <label class="col-form-label col-md-2 required" for="previous_mileage" >Kilometraje anterior:</label>
            <div class="col-md-4">
                <input type="text" id="previous_mileage" :class="{'form-control':true, 'is-invalid':dataErrors.previous_mileage }" disabled="true" v-model="dataForm.previous_mileage">
                <small style="font-size:9px">Kilometraje anterior</small>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Precio de galon -->
            <label class="col-form-label col-md-2 required" for="gallon_price" >Precio de galón:</label>
            <div class="col-md-4">
              <currency-input
                v-model="dataForm.gallon_price"
                :required="true"
                :currency="{'prefix': '$ '}"
                locale="es"
                :precision="0"
                class="form-control"
                :key="keyRefresh"
                v-on:keyup="gallonPrice()"
                >
                </currency-input>
                <small style="font-size:9px"> {{ 'trans.Enter the' | trans }} precio de galón(Ejemplo: 8200)</small>
            </div>

            <!-- Precio total -->
            <label class="col-form-label col-md-2 required" for="total_price" >Precio total:</label>
            <div class="col-md-4">
                <currency-input
                v-model = "dataForm.total_price"
                required = "true"
                :currency = "{'prefix': '$ '}"
                locale= "es"
                :precision ="0"
                readonly="true"
                class="form-control"
                :key="keyRefresh"
                >
                </currency-input>
                <!-- <input disabled type="text" v-model="dataForm.total_price" :class="{'form-control':true, 'is-invalid':dataErrors.current_hourmeter }"> -->
                <small style="font-size:9px">Precio total</small>
            </div>
        </div>
        
        <div class="form-group row m-b-15" v-if="!dataForm.current_mileage && !dataForm.previous_mileage">
            <!-- Horometro actual -->
            <label class="col-form-label col-md-2 required" for="current_hourmeter" >Horómetro actual:</label>
            <div class="col-md-4">
              <currency-input
                v-model = "dataForm.current_hourmeter"
                required = "true"
                :currency = "{'prefix': ''}"
                locale= "es"
                :precision ="2"
                class="form-control"
                :key="keyRefresh"
                v-on:keyup="variationTankingHour()"
                >
              </currency-input>
              <!-- <input type="text" id="current_hourmeter" :class="{'form-control':true, 'is-invalid':dataErrors.current_hourmeter }" @keyup="variationTankingHour($event);" v-model="dataForm.current_hourmeter" required> -->
              <small style="font-size:9px"> {{ 'trans.Enter the' | trans }} el horometro actual(Ejemplo: 1.2)</small>
            </div>

            <!-- Horometro anterior -->
            <label class="col-form-label col-md-2 required" for="previous_hourmeter" >Horómetro anterior:</label>
            <div class="col-md-4">
              <currency-input
              v-model = "dataForm.previous_hourmeter"
              required = "true"
              :currency = "{'prefix': ''}"
              locale= "es"
              :precision ="2"
              readonly="true"
              class="form-control"
              :key="keyRefresh"
              >
              </currency-input>
                <!-- <input type="text" id="previous_hourmeter" :class="{'form-control':true, 'is-invalid':dataErrors.previous_hourmeter }" disabled="true" v-model="dataForm.previous_hourmeter"> -->
                <small style="font-size:9px"> Horometro anterior</small>
            </div>
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Variaciones de horas en los tanqueos -->
            <label v-if="!dataForm.previous_mileage && !dataForm.current_mileage" class="col-form-label col-md-2 required" for="variation_tanking_hour" >Variaciones de horas en los tanqueos:</label>
            <div class="col-md-4" v-if="!dataForm.previous_mileage && !dataForm.current_mileage">
              <currency-input
                v-model = "dataForm.variation_tanking_hour"
                required = "true"
                :currency = "{'prefix': ''}"
                locale= "es"
                :precision ="2"
                readonly="true"
                class="form-control"
                :key="keyRefresh"
                >
                </currency-input> 
                <!-- <input disabled type="text" id="variation_tanking_hour" :class="{'form-control':true, 'is-invalid':dataErrors.variation_tanking_hour }" v-model="dataForm.variation_tanking_hour"> -->
                <small style="font-size:9px"> Variacion de horas en los tanqueos</small>
            </div>

            <!-- Tipo de combustible -->
            <label class="col-form-label col-md-2 required" for="fuel_type" >{{ 'trans.Fuel Type' | trans }}:</label>

            <div class="col-md-4">
              <input disabled type="text" id="fuel_type_name" :class="{'form-control':true, 'is-invalid':dataErrors.fuel_type_name }" v-model="dataForm.fuel_type_name">  
              <small style="font-size:9px">Tipo de combustible</small>
            </div>      
        </div>
        
        <div class="form-group row m-b-15">
            <!-- Variación en km recorridos por tanqueo -->
            <label v-if="!dataForm.previous_hourmeter && !dataForm.current_hourmeter" class="col-form-label col-md-2 required" for="variation_route_hour" >Variación en km recorridos por tanqueo:</label>
            <div class="col-md-4" v-if="!dataForm.previous_hourmeter && !dataForm.current_hourmeter">
                <input disabled type="text" id="name" :class="{'form-control':true, 'is-invalid':dataErrors.variation_route_hour }"  v-model="dataForm.variation_route_hour">
                <small style="font-size:9px">Variación en km recorridos por tanqueo</small>
            </div>

            <!-- Rendimiento km por galón -->
            <label class="col-form-label col-md-2 required" for="name" >Rendimiento por galón:</label>
            <div class="col-md-4">
                <currency-input
                v-model = "dataForm.performance_by_gallon"
                required = "true"
                :currency = "{'prefix': ''}"
                locale= "es"
                :precision ="2"
                readonly="true"
                class="form-control"
                :key="keyRefresh"
                >
                </currency-input>
                <small style="font-size:9px"> Rendimiento km por galón</small>
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
import * as bootstrap from "bootstrap";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { Console } from "console";
import VueCurrencyInput from 'vue-currency-input';
import { jwtDecode } from 'jwt-decode';

const locale = new Locale();

/**
 * Componente para registro de gestion de combustible de vehiculos
 *
 * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
 * @version 1.0.0
 */
@Component
export default class VehicleFuelsCreate extends Vue {

  /**
   * Datos del formulario
   */
  public dataForm: any;

  /**
   * Errores del formulario
   */
  public dataErrors: any;

  public time:Array<any> = [];

  public minutes:Array<any> = [];
  public hours:Array<any> = [];

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
   * Fecha actual
   */
  public currentDate: any;

  public formType: String;

  /**
   * Constructor de la clase
   *
   * @author Carlos Moises Garcia T. - Oct. 13 - 2020
   * @version 1.0.0
   */
  constructor() {
    super();
    this.keyRefresh = 0;
    this.dataForm = {};
    this.currentDate = new Date();
    this.dataErrors = {};
    this.isUpdate = false;
    this.lang = (this.$parent as any).lang;
  }

  /**
   * Limpia los filtros de busqueda
   *
   * @author Andres Stiven Pinzon G. - Ago. 21 - 2021
   * @version 1.0.0
   */
  public cleanAuto ():void{
    (this.$parent as any)["clearDataSearch"]();
    (this.$parent.$refs['placa'] as Vue)["_clean"]();
  }

   /**
   * Asigna valor al campo de precio total
   *
   * @author Andres Stiven Pinzon G. - Ago. 17 - 2021
   * @version 1.0.0
   */
  public cantFuel(): void{

    let fuel = this.dataForm.fuel_quantity;
    //variable que almacena el precio total
    let totalP=0;
    let variationKmTanking = 0;
   
    if(this.dataForm.gallon_price){
      totalP = fuel * this.dataForm.gallon_price;
    }
    
    this.dataForm.total_price= totalP;

    //valida si el campo variation_route_hour contiene un valor,si lo contiene,cuando se le de al boton de editar este valor se almacenara en la variable
    //variationKmTanking,sino el valor que se almacenara en esta sera el del campo variation_tanking_hour
    if(this.dataForm.variation_route_hour){
      variationKmTanking = this.dataForm.variation_route_hour;

    }else{
      variationKmTanking = this.dataForm.variation_tanking_hour;
    }
    //Se refresca el componente
    this.$forceUpdate();
    //Se llama a la funcion para calcular el rendimiento por galon
    this.performanceGallon(variationKmTanking);
  }

  /**
   * Asigna valor al campo de precio total
   *
   * @author Andres Stiven Pinzon G. - Ago. 17 - 2021
   * @version 1.0.0
   */
  public gallonPrice():void{
    
    // this.dataForm.gallon_price = event.target.value;
    let gallon_price=this.dataForm.gallon_price;
    let totalP = 0;
    
    if(this.dataForm.fuel_quantity){
      totalP = this.dataForm.fuel_quantity * gallon_price;
    }
    
    this.dataForm.total_price= totalP;
    this.$forceUpdate();

  }

  /**
   * Asigna el valor al campo tanking_hour
   * @author Andres Stiven Pinzon G. - Sep. 03 - 2021
   * @version 1.0.0
   */
  public fillClock(): void{
    let hour = this.dataForm.hour;
    let minute = this.dataForm.minute ;
    let hourComplete = hour+":"+minute;
  
    this.dataForm.tanking_hour = hourComplete;
  }

  /**
   * Valida que en el campo de horas solo se ingresen numeros
   * Tambien valida el rango de las horas a registrar
   * @author Andres Stiven Pinzon G. - Nov. 24 - 2021
   * @version 1.0.0
   */
  public noLettersHour() {

        let hour = $("#hour").val();
        let hourTest = hour.toString();
        //expresión regular 
        const regexHours = /^[0-9]*$/;
        const onlyNumbersHours = regexHours.test(hourTest); // true
        let hourInt = parseInt(hourTest);
        if(!onlyNumbersHours){
            $("#hour").val("")
            this.dataForm.hour = null;
        }else if(hourInt>23){
            $("#hour").val("");
            this.dataForm.hour = null;
        }

    }

  /**
   * Valida que en el campo de minutos solo se ingresen numeros
   * Tambien valida el rango de los miutos a registrar
   * @author Andres Stiven Pinzon G. - Nov. 24 - 2021
   * @version 1.0.0
   */
    public noLettersMinute() {
    
        let minute = $("#minute").val();
        let minuteTest = minute.toString();
        //expresión regular 
        const regexMinutes = /^[0-9]*$/;
        const onlyNumbersMinutes = regexMinutes.test(minuteTest); // true
        let minutesInt = parseInt(minuteTest);
        if(!onlyNumbersMinutes){
            $("#minute").val("");
            this.dataForm.minute = null;
        }else if(minutesInt>59){
            $("#minute").val("");
            this.dataForm.minute = null;
        }

    }

  /**
   * Rellena el arreglo que se lista en el select de los miuntos
   * @author Andres Stiven Pinzon G. - Sep. 03 - 2021
   * @version 1.0.0
   */
  public fillMinutes(): void{
    let minute;
    for (let min = 0; min < 60; min++) {
      if(min<10){
        minute = "0"+min;
      }else{
        minute = min;
      }
      this.minutes.push(minute);
    }
  }

  /**
   * Rellena el arreglo que se lista en el select de las horas
   * @author Andres Stiven Pinzon G. - Sep. 03 - 2021
   * @version 1.0.0
   */
  public fillHours(): void{
    let hour;
    for (let hrs = 0; hrs < 24; hrs++) {
      if(hrs<10){
        hour = "0"+hrs;
      }else{
        hour = hrs;
      }
      this.hours.push(hour);
    }
  }


    /**
   * Asigna valor al campo de rendimiento km por galon
   *
   * @author Andres Stiven Pinzon G. - Ago. 17 - 2021
   * @version 1.0.0
   */
  public performanceGallon(variation):void{
    //Variable que almacena el resultado de la formula para calcular el rendimiento por galon
    let gallonPerformance = 0;

    //se valida que el dato que entra como parametro a la funcion no este vacio
    if(variation){
  
      gallonPerformance = variation / this.dataForm.fuel_quantity;
      
      //se valida que el valor de rendimiento por galon sea igual a infinity
      if(gallonPerformance == Infinity){
        //Cuando el campo rendimiento por galon se infinity,a este campo se le asignara el valor de 0
        this.dataForm.performance_by_gallon= 0;
      }else{
        this.dataForm.performance_by_gallon= gallonPerformance;
      }
    }else{
      this.dataForm.performance_by_gallon= 0;
    }
    //refresca el componente para que se actualicen los valores
    this.$forceUpdate();
  }

   /**
   * Asigna valor al campo de variacion en km recorridos por tanqueo
   *
   * @author Andres Stiven Pinzon G. - Ago. 17 - 2021
   * @version 1.0.0
   */
  public variationTankingHour(): void{
    // this.dataForm.current_hourmeter=event.target.value;
    let hourmeter = this.dataForm.current_hourmeter;
    //Variable que almacena el resultado de la formula de variacion de horas en los tanqueos
    let variationTanking = 0; 
    
    if(this.dataForm.previous_hourmeter || this.dataForm.current_hourmeter){
      if(this.dataForm.previous_hourmeter==null){
        this.dataForm.previous_hourmeter = 0;
      }
      // Number.isNaN(this.dataForm.previous_hourmeter) ? 0: 
      variationTanking = hourmeter -  this.dataForm.previous_hourmeter;
    }else{
      this.dataForm.variation_tanking_hour=0;
    }
    this.dataForm.variation_tanking_hour= variationTanking;
    this.$forceUpdate();
    //Se llama a la funcion de rendimiento por galon y se le manda como parametro el valor de el campo variacion de horas en los tanqueos
    this.performanceGallon(variationTanking);
  }


   /**
   * Asigna valor al campo de variacion en km recorridos por tanqueo
   *
   * @author Andres Stiven Pinzon G. - Ago. 17 - 2021
   * @version 1.0.0
   */
  public variationRouteTanking(event): void{
    //Variable global que almacena el valor que se va ingresando en el campo de kilometraje actual
    this.dataForm.current_mileage = event.target.value;
    // console.log()
    let variationKmTanking = 0;
    //Variable que almacena el resultado de la formula de variacion en km recorridos por tanqueo
    if(this.dataForm.previous_mileage || this.dataForm.current_mileage){
      if(this.dataForm.previous_mileage==null){
        this.dataForm.previous_mileage = 0;
      }
      variationKmTanking = this.dataForm.current_mileage - this.dataForm.previous_mileage;
    }else{
      this.dataForm.variation_route_hour=0;
    }
    this.dataForm.variation_route_hour= variationKmTanking;
    this.$forceUpdate();
    //Se llama a la funcion de rendimiento por galon y se le manda como parametro el valor de el campo variacion en km recorridos por tanqueo
    this.performanceGallon(variationKmTanking);
  } 


  /**
   * Asigna valor al campo de kilometraje anterior o horometro anterior
   *
   * @author Andres Stiven Pinzon G. - Ago. 19 - 2021
   * @version 1.0.0
   */
  public checkInfo():void{
    // Envia peticion para obtener el kilometraje y horometro actual del registro anterior de la placa ingresada
    axios
      .get(`get-info/${this.dataForm.mant_resume_machinery_vehicles_yellow_id}`).then((res) => {
        if(res.data.data){
          let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

          // console.log(res.data.data);
          const dataDecrypted = Object.assign({}, dataPayload);

          //Valida que en el res.data venga un atributo con el nombre de current_mileage
          if(dataDecrypted["data"]["current_hourmeter"]){
            //se le asigna el valor al horometro anterior en caso de que venga en el data
            this.dataForm.previous_hourmeter = dataDecrypted["data"]["current_hourmeter"];

            // Valida que el campo current mileage tenga un valor,este procedimiendo se realiza en caso de que el usuario ingrese primero
            // el horometro actual y la cantidad de combustible antes de seleccionar una placa de vehiculo al momento de realizar un registtro
            if(this.isUpdate==false){
              if(this.dataForm.current_hourmeter){
                this.dataForm.variation_tanking_hour = this.dataForm.current_hourmeter - this.dataForm.previous_hourmeter
                if(this.dataForm.fuel_quantity){
                  this.dataForm.performance_by_gallon = this.dataForm.variation_tanking_hour / this.dataForm.fuel_quantity
                }else{
                  this.dataForm.performance_by_gallon = 0;
                }
              }else{
                this.dataForm.variation_tanking_hour = 0;
              }
              this.$forceUpdate();

            }else if(this.isUpdate==true){
              if(this.dataForm.previous_hourmeter !=null){
                this.dataForm.previous_mileage = null;
                this.dataForm.current_mileage = null;
                this.dataForm.variation_route_hour = null;
              }
            }
            
          }else{
            //se le asigna el valor a kilometraje anterior en caso de que venga en el data
            this.dataForm.previous_mileage = dataDecrypted["data"]["current_mileage"]; 

            if(this.isUpdate==false){

              // Valida que el campo current mileage tenga un valor,este procedimiendo se realiza en caso de que el usuario ingrese primero
              // el kilometraje actual y la cantidad de combustible antes de seleccionar una placa de vehiculo al momento de realizar un registtro
              if(this.dataForm.current_mileage){
                this.dataForm.variation_route_hour = this.dataForm.current_mileage - this.dataForm.previous_mileage
                if(this.dataForm.fuel_quantity){
                  this.dataForm.performance_by_gallon = this.dataForm.variation_route_hour / this.dataForm.fuel_quantity
                }else{
                  this.dataForm.performance_by_gallon = 0;
                }
              }else{
                this.dataForm.variation_route_hour = 0;
              }

              this.$forceUpdate();

            }else if(this.isUpdate==true){
              if(this.dataForm.previous_mileage !=null){
                this.dataForm.previous_hourmeter = null;
                this.dataForm.current_hourmeter = null;
                this.dataForm.variation_tanking_hour = null;
              }
            }      
          }   
        }else{
          //Si no viene ningun dato en el data,el campo de horometro y kilometraje anterior tomaran el valor de null
          this.dataForm.previous_hourmeter = null;
          this.dataForm.previous_mileage = null;
          this.$forceUpdate();
        }
      })
      .catch((err) => {
        console.log("error tanqueo",err);
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
    this.dataForm = {};
    // Limpia errores
    this.dataErrors = {};
    // Actualiza componente de refresco
    this._updateKeyRefresh();
  }

  /**
   * Asigna los datos al dataForm del tipo de activo y de dependencias en los input de proceso,nombre y tipo de activo
   *
   * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
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
    
    //Se llama la funcion checkInfo para que asigne valores
    this.checkInfo();

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
  public loadVehicle(dataElement: object): void {
    // Valida que exista datos
    if (dataElement) {
      // Habilita actualizacion de datos
      console.log("edit datos",dataElement);
      this.isUpdate = true;
      // Busca el elemento deseado y agrega datos al fomrulario
      this.dataForm = utility.clone(dataElement);
      //Asigna las horas y los minutos a los select de estos mismos
      let hourComplete = this.dataForm.tanking_hour.split(":");
      this.dataForm.hour=hourComplete[0];
      this.dataForm.minute=hourComplete[1]
      //Valida que el array de tipo de activos y de dependencias no esten vacios 
      // if (this.dataForm.asset_type && this.dataForm.dependencias && this.dataForm.resume_machinery_vehicles_yellow) {
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
      $("#modal-form-vehicle-fuels").on("keypress", (e) => {
        if (e.keyCode === 13) {
          e.preventDefault();
        }
      });

      $("#modal-form-vehicle-fuels").modal("show");
    } else {
      this.isUpdate = false;
      $("#modal-form-vehicle-fuels").modal("show");
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
    let hour = this.dataForm.hour;
    let minute = this.dataForm.minute ;
    let hourComplete = hour+":"+minute;
  
    this.dataForm.tanking_hour = hourComplete;
    this.$swal({
      title: this.lang.get("trans.loading_save"),
      allowOutsideClick: false,
      onBeforeOpen: () => {
        (this.$swal as any).showLoading();
      },
    });

    // Envia peticion de guardado de datos
    axios
      .post("vehicle-fuels", this.makeFormData(), {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {
        
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const dataDecrypted = Object.assign({data:[]}, dataPayload);
        
        if(!dataDecrypted["data"]){           
              // Abre el swal
               this.$swal({
                 icon: 'warning',
                 title: '¡Advertencia!',
                 text: dataDecrypted["message"],
                });
           
        }else{
            // Agrega elemento nuevo a la lista
            (this.$parent as any).dataList.unshift(dataDecrypted["data"]);

            (this.$swal as any).close();

            // Cierra fomrulario modal
            $(`#modal-form-vehicle-fuels`).modal("toggle");

            // Limpia datos ingresados
            this.clearDataForm();
            // Emite notificacion de almacenamiento de datos
            (this.$parent as any)._pushNotification(dataDecrypted["message"]);
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
      },
    });

    const formData: FormData = this.makeFormData();
    formData.append("_method", "put");

    // Envia peticion de guardado de datos
    axios
      .post(`vehicle-fuels/${this.dataForm["id"]}`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then((res) => {

        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const dataDecrypted = Object.assign({data:[]}, dataPayload);


         if(!dataDecrypted["data"]){           
              // Abre el swal
               this.$swal({
                 icon: 'warning',
                 title: '¡Advertencia!',
                 text: dataDecrypted["message"],
                });
           
        }else{
          // Valida que se retorne los datos desde el controlador
          if (dataDecrypted["data"]) {
            // Actualiza elemento modificado en la lista
            if(dataDecrypted["data"][1]){
            
              Object.assign(
                (this.$parent as any)._findElementById(this.dataForm["id"], false),
                dataDecrypted["data"][1]       
              );
              
              Object.assign(
                (this.$parent as any)._findElementById(dataDecrypted["data"][0].id, false),
                dataDecrypted["data"][0]       
              );

            }else{            
              Object.assign(
                (this.$parent as any)._findElementById(this.dataForm["id"], false),
                dataDecrypted["data"][0]
              );
            
            }
          }

          // Cierra el swal de guardando datos
          (this.$swal as any).close();

          // Cierra fomrulario modal
          $(`#modal-form-vehicle-fuels`).modal("toggle");
          // Limpia datos ingresados
          this.clearDataForm();
          // Emite notificacion de actualizacion de datos
          (this.$parent as any)._pushNotification(dataDecrypted["message"]);

        }
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
}
</script>