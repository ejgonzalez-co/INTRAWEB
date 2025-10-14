<template>
  <div>
    <div class="row">
      <div class="col">
        <date-picker-today
          :range="range"
          v-model="value[nameField]"
          :confirm-text="confirmText"
          :type="type"
          :lang="lang"
          :format="format"
          :confirm="confirm"
          :editable="editable"
          :disabled="disabled"
          :disabled-date="deshabilitarFechas"
          :pick="seleccionaFecha()"
        ></date-picker-today>
      </div>
      <div class="col" v-if="value[nameField]">
        <div class="row">
          <div class="col">
            <label for="hora">Hora:</label>
          </div>
          <div class="col">
            <select v-model="horas">
              <option v-for="option in horasSelect" :key="option" :value="option">{{option}}</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Componente para hacer una resta entre dos numeros y se le da el formato de dinero
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
 * @version 1.0.0
 */
@Component
export default class datePickerComplete extends Vue {
  public fechasFestivas;
  public numAvaible;
  public fechasDisponibles;
  public arrayGeneral;
  public cont;
  public horas;
  public minutos;
  public horasSelect;
  public minutosDisponibles;
  public hora;
  /**
   * Nombre del campo
   */
  @Prop({ type: String, required: true })
  public nameField: string;

  /**
   * Formato puede recibir los datos que necesites guardar por defecto esta año-mes-dia horas-minutos-segundos
   */
  @Prop({ type: String, default: "YYYY-MM-DD hh:mm:ss" })
  public format: string;

  /**
   * Formato puede recibir los datos que necesites guardar por defecto esta año-mes-dia horas-minutos-segundos
   */
  @Prop({ type: String, default: "datetime" })
  public type: string;

  /**
   * Selecciona si desea seleccionar un rango o una fecha en especifico
   */
  @Prop({ type: Boolean, default: false })
  public range: boolean;

  /**
   * Selecciona si hay intervalos
   */
  @Prop({ type: Boolean, default: false })
  public booleanIntervalo: boolean;

  /**
   * Selecciona ruta a consultar horas disponibles
   */
  @Prop({ type: String, default: "" })
  public resourceIntervalo: string;

  /**
   * Selecciona si desea que carguen los festivos
   */
  @Prop({ type: Boolean, default: false })
  public booleanFestivos: boolean;

  /**
   * Selecciona ruta para consultar fesitvos
   */
  @Prop({ type: String, default: "" })
  public resourceFestivos: string;

  /**
   * Selecciona si desea que carguen los festivos
   */
  @Prop({ type: Boolean, default: false })
  public booleanHourAvaible: boolean;

  /**
   * Selecciona ruta para consultar fesitvos
   */
  @Prop({ type: String, default: "" })
  public resourceHour: string;

  /**
   * Selecciona si desea que se envien las horas disponibles
   */
  @Prop({ type: Boolean, default: false })
  public booleanDiasDisponibles: boolean;

  /**
   * Selecciona ruta para consultar las horas disponibles
   */
  @Prop({ type: String, default: "" })
  public resourceDiasDisponibles: string;

  /**
   * Selecciona si desea que se envien las horas disponibles
   */
  @Prop({ type: Boolean, default: false })
  public booleanDiasHoy: boolean;

  /**
   * Selecciona ruta para consultar las horas disponibles
   */
  @Prop({ type: String, default: "" })
  public resourceDiasHoy: string;

  /**
   * Selecciona si desea que se envien las horas disponibles
   */
  @Prop({ type: Boolean, default: false })
  public booleanHorasDisponibles: boolean;

  /**
   * Selecciona ruta para consultar las horas disponibles
   */
  @Prop({ type: String, default: "" })
  public resourceHorasDisponibles: string;

  /**
   * Selecciona si desea seleccionar un rango o una fecha en especifico
   */
  @Prop({ type: Boolean, default: false })
  public disabled: boolean;

  /**
   * Selecciona si la fecha es editable
   */
  @Prop({ type: Boolean, default: true })
  public editable: boolean;

  /**
   * Selecciona si desea agregar un boton de ok
   */
  @Prop({ type: Boolean, default: false })
  public confirm: boolean;

  /**
   * Valor del campo
   */
  @Prop({ type: Object })
  public value: any;

  /**
   * Idioma selecciona el idioma que le quieres enviar
   */
  @Prop({ type: String, default: "es" })
  public lang: string;

  /**
   * Selecciona el texto que va llevar el calendario
   */
  @Prop({ type: String, default: "Guardar" })
  public confirmText: string;

  constructor() {
    super();
    this.cont = 0;
    this.arrayGeneral = [];
    this.fechasFestivas = [];
    this.fechasDisponibles = [];
    this.horasSelect = [];

    this.consultaHorasDisponible();
    this.horas = "";
    this.minutos = "";

    
    if (this.booleanFestivos == true) {
      this.consultaFestivos();
    }
    if (this.booleanDiasDisponibles == true) {
      this.consultaDiasDisponibles();
    }
    if (this.booleanDiasHoy == true) {
      this.consultaDiasHoy();
    }

    // Valida si existe el elemento
    if (this.value[this.nameField]) {
      // Valida que no sea un array
      if (Array.isArray(this.value[this.nameField])) {
        let dates = [];

        this.value[this.nameField].forEach((date) => {
          dates.push(new Date(date + " 00:00:00"));
        });

        this.value[this.nameField] = dates;
      } else {
        this.value[this.nameField] = new Date(this.value[this.nameField]);
      }
    }
  }
  public consultaFestivos() {
    if (this.booleanFestivos == true) {
      axios
        .get(this.resourceFestivos)
        .then((res) => {
          const request = res.data.data;

          this.fechasFestivas = request;

          this.arreglaArray();
        })
        .catch((error) => {});
    }
  }
  public consultaDiasDisponibles() {
    if (this.booleanDiasDisponibles == true) {
      axios
        .get(this.resourceDiasDisponibles)
        .then((res) => {
          const request = res.data.data;

          this.fechasFestivas = request;

          this.arreglaArray();
        })
        .catch((error) => {});
    }
  }
  public consultaDiasHoy() {
    if (this.booleanDiasHoy == true) {
      axios
        .get(this.resourceDiasHoy)
        .then((res) => {
          const request = res.data.data;
          this.numAvaible = request;
          this.diasDisponibles();
        })
        .catch((error) => {});
    }
  }
  public consultaHorasDisponible() {
    axios
      .get(this.resourceHorasDisponibles)
      .then((res) => {
        const request = res.data.data;
        this.horasSelect = request;
      })
      .catch((error) => {});
  }
  public diasDisponibles() {
    if (this.booleanFestivos == true && this.booleanDiasHoy == true) {
      for (let quantity = 0; quantity < this.numAvaible; quantity++) {
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        today.setDate(today.getDate() + quantity);

        for (let index = 0; index < this.fechasFestivas.length; index++) {
          if (this.fechasFestivas[index].getTime() === today.getTime()) {
            this.numAvaible++;
            quantity++;
            today.setDate(today.getDate() + 1);
          }
        }

        this.fechasDisponibles.push(today);
      }
    }
  }
  public arreglaArray() {
    for (let index = 0; index < this.fechasFestivas.length; index++) {
      let element = this.fechasFestivas[index].date;
      var fecha = new Date(element);
      fecha.setHours(0, 0, 0, 0);
      this.fechasFestivas[index] = fecha;
    }
  }
  public seleccionaFecha() {
    if (this.value[this.nameField]) {


    

    
    }
  }
  deshabilitarFechas(date) {
    var fecha = new Date(date);

    if (this.booleanDiasHoy == true) {
      fecha.setHours(0, 0, 0, 0);
      for (let index = 0; index < this.fechasDisponibles.length; index++) {
        var fechaToday = new Date(this.fechasDisponibles[index]);
        fechaToday.setHours(0, 0, 0, 0);
        if (fechaToday.getTime() === fecha.getTime()) {
          return false;
        }
      }
      return true;
    }

    if (this.booleanFestivos == true) {
      for (let index = 0; index < this.fechasFestivas.length; index++) {
        if (this.fechasFestivas[index].getTime() === fecha.getTime()) {
          return true;
        }
      }
    }

    if (fecha.getDay() == 0 || fecha.getDay() == 6) {
      return true;
    } else {
      return false;
    }
  }
}
</script>

