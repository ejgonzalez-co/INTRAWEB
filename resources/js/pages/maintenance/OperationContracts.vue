<template>
    <div>
        <div v-if="prefix">
          <currency-input
              :v-model="nameField"
              :required="readonly"
              :currency="{'prefix': prefix}"
              locale="es"
              :value="result"
              :readonly="readonly"
              :precision="2"
              class="form-control"
              :key="refresh"                    
              >
            </currency-input>
        </div>
        <div v-if="suffix">
            <currency-input
              :v-model="nameField"
              required="true"
              :currency="{'suffix': suffix}"
              locale="es"
              :value="result"
              :readonly="readonly"
              :precision="2"
              class="form-control"
              :key="refresh"                    
              >
            </currency-input>
          </div>
              
    </div>   
</template>
<script lang="ts">
import {  Component, Prop, Watch, Vue} from "vue-property-decorator";

import axios from "axios";

/** 
* Ingresa un dato que se llama de la abse de datos
*
* @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
* @version 1.0.0
*/
@Component
export default class OperationContracts extends Vue {


 /** Se guarda el numero */
 @Prop({ type: [Number, String] })
    public numberOne: number;
    /** Se guarda el numero*/
    @Prop({ type: [Number, String] })
    public numberTwo: number;

  @Prop({type: String})
   public classInput: String;

    /**donde se guarda el prefijo */
    @Prop({ type: Boolean, default:true})
   public readonly: boolean;
/**
   * Nombre del campo
   */
  @Prop({ type: String, required: true })
  public nameField: string;

   /**donde se guarda el prefijo */
   @Prop({ type: String})
   public prefix: String;

   /**donde se guarda el sufijo */
   @Prop({ type: String})
   public suffix: String;

   /**
   * Valor del campo
   */
   @Prop({type: Object})
   public value: any;

   public result: any;

    /**
     * Datos del formulario
     */
    public dataForm: any; 


   /**
   * Valor del campo
   */
   @Prop({type: String, default:'keyRefresh'})
   public refresh: string;

   /** Se guarda la operacion a realizar suma, multiplicacion, resta, division o porcentajes*/
   @Prop({ type: String })
    public operation: string;

  


/**
 * Constructor de la clase
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
 * @version 1.0.0
 */
constructor() {
   super();
   this.dataForm = {};
   this.result=0;
   this.getPetition();
  
   
}

  /**
 * envia los datos del al servidor y confirma con un sweeralert2
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
 * @version 1.0.0
 */
public getPetition(): void {


        var numAux1 = this.numberOne;
        var numAux2 = this.numberTwo;
        var total = 0;
        
        if (this.operation == "resta") {

        /**Hace la resta */
        console.log('resta','n1='+this.numberOne, 'num2='+ this.numberTwo );
        this.result = this.numberOne - this.numberTwo;

        /**Hace se le asigna el nuevo vamor al campo requerido y al v-model */
        this.value[this.nameField] = this.result;
        }
    }
}
</script>