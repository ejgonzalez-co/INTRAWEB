<template>
  <div>
      <div v-if="prefix">
        <currency-input
            :v-model="nameField"
            required="true"
            :currency="{'prefix': prefix}"
            locale="es"
            :value="result"
            readonly="true"
            :precision="decimals"
            class="form-control"
            :key="result"                    
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
            readonly="true"
            :precision="decimals"
            class="form-control"
            :key="result"                    
            >
          </currency-input>
        </div>
            
  </div>   
</template>
<script lang="ts">
import {  Component, Prop, Watch, Vue} from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

/** 
* Ingresa un dato que se llama de la abse de datos
*
* @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
* @version 1.0.0
*/
@Component
export default class InputData extends Vue {




@Prop({type: String})
 public classInput: String;


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
 * Nombre de la ruta a obtener
 */
@Prop({ type: String, required: true })
public nameResource: string;

  /**
 * Cantidad de decimales a mostrar
 */
@Prop({ type: Number, default: 2})
public decimals: number;

 /**
 * Valor del campo
 */
 @Prop({type: Object})
 public value: any;

 public result: any;


/**
* Constructor de la clase
*
* @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
* @version 1.0.0
*/
constructor() {
 super();
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
      
      axios.get(this.nameResource)
            .then(res=>{

              let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({data:[]}, dataPayload);

                this.result=dataDecrypted["data"];


                 this.result=parseFloat( this.result);
                
                this.$set(this.value, this.nameField, this.result);

                
                (this.$parent.$refs["operacionResta"] as Vue)["operationMethod"](this.result);
            }).catch(error=>{
               
            })
}
}
</script>