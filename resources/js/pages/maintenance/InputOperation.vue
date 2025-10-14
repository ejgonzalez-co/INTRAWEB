<template>
    <div>
        <div v-if="tipoOperacion == 'concatena'">

            <input
                :v-model="nameField"
                required
                :id="nameField"
                :name="nameField"
                :value="result"
                type="text"
                class="form-control"
                readonly
                :key="result"
            />
        </div>
        <div v-else>
            <div v-if="prefix">
                <currency-input
                    :v-model="nameField"
                    required="true"
                    :currency="{ prefix: prefix }"
                    locale="es"
                    :value="result"
                    readonly="true"
                    :precision="cantidadDecimales"
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
                    :precision="cantidadDecimales"
                    class="form-control"
                    :key="result"
                >
                </currency-input>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para hacer una resta entre dos numeros y se le da el formato de dinero
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
 * @version 1.0.0
 */
@Component
export default class InputOperation extends Vue {
    /** Se guarda la ruta */
    @Prop({ type: String })
    public ruta: string;

    /** Se guarda el numero */
    @Prop({ type: [Number, String] })
    public numberOne: number;
    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberTwo: number;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberThree: number;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberSulfato: string;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberPenda: string;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberFour: number;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberFive: number;

    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberSix: number;

    @Prop({ type: Array, default: () => [] })
    public array: any;

    @Prop({ type: Array, default: () => [] })
    public arrayRecorrer: any;

    /** Se guarda el nombre del campo*/
    @Prop({ type: String, required: true })
    public nameField: string;

    /** Se guarda el nombre del campo*/
    @Prop({ type: String })
    public nameCampo: string;

    /** Se guarda el nombre del campo*/
    @Prop({ type: String })
    public endPoint: string;

    @Prop({ type: [Number, String], default: 2 })
    public cantidadDecimales: number;

    /** Se guarda la operacion a realizar suma, multiplicacion, resta, division o porcentajes*/
    @Prop({ type: String })
    public tipoOperacion: string;

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
        var total = 0;

        if (isNaN(this.numberOne)) {
            numAux1 = numero1;
        }
        if (isNaN(this.numberTwo)) {
            numAux2 = numero2;
        }

        this.result = parseFloat(this.result);

        if (this.operation == "resta") {

            /**Hace la resta */
            this.result = this.numberOne - this.numberTwo;

            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "suma") {
            /**Hace la suma  */
            this.result = numAux1 + numAux2;
            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "multiplica") {
            /**Hace la multiplicacion */
            this.result = numAux1 * numAux2;
            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "divide") {
            /**Hace la division */
            this.result = numAux1 / numAux2;
            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "porcentaje") {
            /**Hace el porcentaje*/
            this.result = (numAux2 / numAux1) * 100;
            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "especial2") {
            let cont = 0;
            for (let data of this.array) {
                total += parseFloat(data.name);
                cont++;
            }
            this.result = total / cont;
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "especial1") {
            /**Hace el porcentaje*/
            this.result = this.numberOne + this.numberTwo - this.numberThree;
            /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "general") {
            this.result =
                ((this.numberFour - this.numberTwo) / this.numberOne) *
                this.numberThree;
            if (this.result < 0) {
                this.result = 0;
            }

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "resultSulfaA") {

            let valor = this.numberSulfato;
            valor = valor.replace(/,/g, '.');
            var resSuA = parseFloat(valor);
            let valorpendientea = this.numberPenda;
            valorpendientea = valorpendientea.replace(/,/g, '.');
            var variableA = parseFloat(valorpendientea);
            this.result = ((this.numberTwo - this.numberOne) - resSuA) / variableA;

            this.value[this.nameField] = this.result;

        }
        if (this.operation == "resultSulfaB") {
            let valor = this.numberSulfato;
            valor = valor.replace(/,/g, '.');
            var resSuB = parseFloat(valor);
            let valorpendientea = this.numberPenda;
            valorpendientea = valorpendientea.replace(/,/g, '.');
            var variableB = parseFloat(valorpendientea);
            this.result = ((this.numberTwo - this.numberOne) - resSuB) / variableB;

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "resultSolidos") {
            var mul = 1000;
            this.result = ((this.numberOne - this.numberTwo) * mul) / this.numberThree;

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "porcentajeRecuperacion") {
            var promedio = 0;

            promedio = (this.numberFive + this.numberFour) / 2;

            this.result =
                ((promedio * (this.numberThree + this.numberTwo) -
                    this.numberSix * this.numberTwo) /
                    (this.numberOne * this.numberThree)) *
                100;
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "ufc") {
            /**Hace la multiplicacion */
            function logaritmo(x,y){
                return Math.log(y)/Math.log(x);
            }
            var e=2.718281;
            var lnNpm = logaritmo(e, this.numberOne);
            var lnUfc = (lnNpm - 1.27)/0.8;
            var ufc = Math.pow(e, lnUfc);
            this.result = ufc;
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "resulPozoz") {
            let score1 = 0;
            let score2 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            this.result = score1*score2;
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "porcentualRelativa") {
            var promedio = 0;
            var resta = 0;
            // real = concentracionMuestra =number-six
            // Volumen adicionado = Volumen adicionado =number-three
            // volumen muestra = Volumen muestra =:number-two
            //add1 = promedio =number-five
            // add2 = promedio number-four
            // Concentración solución madre = Concentración solución madre =number-on

            promedio = (this.numberOne + this.numberTwo) / 2;

            resta = this.numberOne - this.numberTwo;
            if (resta < 0) {
                resta = resta * -1;
            }

            this.result = (resta / promedio) * 100;

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "promedio") {
            let valor = 0;
            let contador = this.arrayRecorrer.length;
            for (var i = 0; i < this.arrayRecorrer.length; i++) {
                valor += parseFloat(this.arrayRecorrer[i][this.nameCampo]);
            }
            this.result = valor / contador;
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "concatena") {
            let valor = "";

            for (var i = 0; i < this.arrayRecorrer.length; i++) {
                valor += this.arrayRecorrer[i][this.nameCampo] + "°" + " / ";
            }
            this.result = valor;
            this.value[this.nameField] = this.result;
            this.value[this.nameField] = this.value[this.nameField];
            this.result = this.value[this.nameField].substring(0, this.value[this.nameField].length - 2);
        }
        if (this.operation == "especial toma de muestra") {
            let cloruro = null;
            //Envia una peticion con un parametro para realizar la consulta
            axios
                .get("get-cloruro-information/" + this.numberThree)
                .then(res => {

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);


                    cloruro = dataDecrypted["data"]["chlorine_test"];

                    this.result =
                        (this.numberOne * cloruro * 35450) / this.numberTwo;
                    this.functionAsigna();
                })
                .catch(error => {
                    console.log(error);
                });
        }

        if (this.operation == "consulta") {
            //Envia una peticion con un parametro para realizar la consulta
            axios
                .get(this.ruta)
                .then(res => {

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);

                    this.result = dataDecrypted["data"];

                    this.value[this.nameField] = this.result;
                    this.$forceUpdate();
                })
                .catch(error => {
                    console.log(error);
                });
        }

        if (this.operation == "alcalinidadP") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            let score4 = 0;

            score1 = this.numberThree;
            score2 = this.numberTwo;
            score3 = this.numberOne;
            score4 = this.numberFour;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number" &&
                typeof score4 == "number"
            ) {
                this.result = ((2 * score1 - (score1 + score2)) * 50000 * this.numberFour) / this.numberOne;

                if (this.result <= 0) {
                    this.result = this.result * -1;
                }
            }
            this.value[this.nameField] = this.result;
        }
        if (this.operation == "alcalinidadS") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            let score4 = 0;

            score1 = this.numberThree;
            score2 = this.numberTwo;
            score3 = this.numberOne;
            score4 = this.numberFour;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number" &&
                typeof score4 == "number"
            ) {
                this.result = ((score1 + score2) * 50000 * this.numberFour) / this.numberOne;

                if (this.result <= 0) {
                    this.result = this.result * -1;
                }
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "acidez") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;

            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = (score2 * score3 * 50000) / score1;
            }

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "acidezpatron") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            let score4 = 0;

            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;
            score4 = this.numberFour;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number" &&
                typeof score4 == "number"
            ) {
                this.result = (score2 * score3 * 50000) / score1 - score4;
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "cloruro") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;

            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = (score2 * score3 * 35450) / score1;

                if (this.result < 0) {
                    this.result = 0;
                }

                this.value[this.nameField] = this.result;
            }
        }

        if (this.operation == "cloruropatron") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            let score4 = 0;

            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;
            score4 = this.numberFour;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number" &&
                typeof score4 == "number"
            ) {
                this.result = ((score2 - score4) * score3 * 35450) / score1;
            }
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "calcio") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = (score3 * score2 * 40080) / score1;
            }
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "cloro") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = (score3 * score2 * 35450) / score1;
            }
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "dureza") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = (score3 * score2 * 100000) / score1;
            }
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "prome") {
            let score1 = 0;
            let score2 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;

            if (typeof score1 == "number" && typeof score2 == "number") {
                this.result = (score1 + score2) / 2;
            }
            this.value[this.nameField] = this.result;
        }

        if (this.operation == "consultaBase") {
            let cloruro = null;
            //Envia una peticion con un parametro para realizar la consulta
            axios
                .get(this.endPoint)
                .then(res => {
                    this.result = res.data.data;
                    this.value[this.nameField] = this.result;
                    this.$forceUpdate();
                })
                .catch(error => {
                    console.log(error);
                });
        }

        if (this.operation == "limiteLcs") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 + 3 * score3;
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "limiteLci") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 - 3 * score3;
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "limiteLas") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 + 2 * score3;
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "limiteLai") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 - 2 * score3;
            }

            this.value[this.nameField] = this.result;
        }

        if (this.operation == "limiteX1") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 + 1 * score3;
            }

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "convert") {
            let score1 = 0;
            score1 = this.numberOne;
            if (
                typeof score1 == "number" 
            ) {
                this.result = score1 / 3.78541;
            }

            this.value[this.nameField] = this.result;
        }
        if (this.operation == "limiteX0") {
            let score1 = 0;
            let score2 = 0;
            let score3 = 0;
            score1 = this.numberOne;
            score2 = this.numberTwo;
            score3 = this.numberThree;

            if (
                typeof score1 == "number" &&
                typeof score2 == "number" &&
                typeof score3 == "number"
            ) {
                this.result = score2 - 1 * score3;
            }

            this.value[this.nameField] = this.result;
        }

        this.$forceUpdate();
    }
    public functionAsigna() {
        this.value[this.nameField] = this.result;
        this.$forceUpdate();
    }
}
</script>
