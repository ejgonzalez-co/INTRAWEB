<template>
  <div>
    <div v-if="prefix">
      <currency-input
        :v-model="nameField"
        required="true"
        :currency="{ prefix: prefix }"
        locale="es"
        :value="result"
        readonly="true"
        :precision="precision"
        class="form-control"
        :key="result"
      >
      </currency-input>
    </div>
    <div v-if="suffix">
      <currency-input
        :v-model="nameField"
        required="true"
        :currency="{ suffix: suffix }"
        locale="es"
        :value="result"
        readonly="true"
        :precision="2"
        class="form-control"
        :key="result"
      >
      </currency-input>
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
export default class InputOperation extends Vue {
  /** Se guarda el numero */
  @Prop({ type: Number })
  public numberOne: number;
  /** Se guarda el numero*/
  @Prop({ type: Number })
  public numberTwo: number;

  /** Se guarda el numero*/
  @Prop({ type: Number })
  public numberThree: number;

  @Prop({ type: Array, default: () => [] })
  public array: any;
  /** Se guarda el nombre del campo*/
  @Prop({ type: String, required: true })
  public nameField: string;

  /** Se guarda la operacion a realizar suma, multiplicacion, resta, division o porcentajes*/
  @Prop({ type: String })
  public operation: string;

  /**
   * Valor del campo
   */
  @Prop({ type: Object })
  public value: any;
  /**donde se guarda el resultado de la resta */
  public result: any;

  /**donde se guarda el prefijo */
  @Prop({ type: String })
  public prefix: String;

  /**donde se guarda el sufijo */
  @Prop({ type: String })
  public suffix: String;

  /**
   * Funcion del change
   */
  @Prop({ type: Function, default: (any) => {} })
  public functionChange: (any) => {};

  @Prop({ type: Boolean, default: false })
  public refreshParentComponent: boolean;

  @Prop({ type:   Number , default: 2 })
  public precision: number;

  /**
   * Constructor de la clase
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
   * @version 1.0.0
   */
  constructor() {
    super();
    /**LLama a la operacion de los dos numeros ingresados por props */
    this.operationMethod();
  }

  /**
   * En este metodo se hacen las operaciones de dos numeros
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
   * @version 1.0.0
   */
  public operationMethod(numero1: any = "", numero2: any = ""): void {
    var numAux1 = this.numberOne;
    var numAux2 = this.numberTwo;
    var numAux3 = this.numberThree;
    var total = 0;

    if (isNaN(this.numberOne)) {
      numAux1 = numero1;
    }
    if (isNaN(this.numberTwo)) {
      numAux2 = numero2;
    }
    if (isNaN(this.numberThree)) {
      numAux3 = numero2;
    }

    this.result = 0;

    if (this.operation == "resta") {
      var result = numAux1 - numAux2;

      if (result < 0) {
        this.result = 0;
      } else {
        this.result = result;
      }

      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "restaMantTireWear") {
      var result = numAux1 - numAux2;

      if (result <= 0) {
        this.result = 1;
      } else {
        this.result = result;
      }

      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "suma") {
      /**Hace la suma  */
      this.result = numAux1 + numAux2; 
      
      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "multiplica") {
      /**Hace la multiplicacion */
      this.result = numAux1 * numAux2;
      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "divide") {
      if (numAux2 == 0) {
        this.result = 0;
      } else {
        console.log(numAux1, numAux2);
        /**Hace la division */
        this.result = numAux1 / numAux2;
      }

      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "porcentaje") {
      /**Hace el porcentaje*/
      this.result = (numAux2 / numAux1) * 100;
      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    if (this.operation == "especial1") {
      /**Hace el porcentaje*/
      this.result = this.numberOne + this.numberTwo - this.numberThree;
      /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
      this.$set(this.value,this.nameField,this.result);
    }

    //Calcula la profundidad del registro
    if (this.operation == "especial2") {
      let cont = 0;
      for (let data of this.array) {
        total += parseFloat(data.name);
        cont++;
      }
      this.result = total / cont;
      this.$set(this.value,this.nameField,this.result);
    }

    //Calcula el total desgaste
    if (this.operation == "especial3") {
      let cont = 0;
      //Recorre el array enviado por props
      for (let data of this.array) {
        //suma los datos que entran
        total += parseFloat(data.name);
        cont++;
      }
      //Divide la suma total entre la cantidad de datos recorridos
      var result = total / cont;
      //Se resta el numAux2 con el result
      if(result > numAux2){
           this.$swal({
            icon: 'error',
            html: 'Error, la profundidad del registro es mayor que la profundidad de la llanta',
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            })
        this.value[this.nameField] = 0;
      }else{
        this.result = numAux2 - result;
        this.$set(this.value,this.nameField,this.result);
      }
      
    }

    if (this.operation == "especial4") {
      let cont = 0;
      for (let data of this.array) {
        total += parseFloat(data.name);
        cont++;
      }
      var depth = total / cont;
      if(depth < numAux2){
         this.$swal({
            icon: 'error',
            html: 'Error, la profundidad del registro es menor al máximo desgaste ingresado por el administrador',
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            })
        this.value[this.nameField] = 0;
      }else{
        var number = parseFloat(depth.toFixed(2)) - numAux2;
        this.result = parseFloat(number.toFixed(2)) * numAux1;
        this.$set(this.value,this.nameField,this.result);
      }
      
    }

    if (this.operation == "especial5") {
      this.value[this.nameField] = null;

      if(numAux1 && numAux2 && numAux3){
        this.result = (parseFloat(numAux1.toFixed(2)) * parseFloat(numAux2.toFixed(2)))/parseFloat(numAux3.toFixed(2));
        this.$set(this.value,this.nameField,this.result);
      }
    }

    this.$forceUpdate();

    if(this.refreshParentComponent) this.$parent.$forceUpdate();
  }
}
</script>
