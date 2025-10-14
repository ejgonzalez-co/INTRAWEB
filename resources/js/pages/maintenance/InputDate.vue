<template>
    <div>
      <div v-if="this.type = 'date'">

          <input
              :name="nameField"
              type="date"
              :v-model="nameField"
              required
              :value="result"
              :readonly="readonly"
              class="form-control"
              :key="refresh"                    
              >
            </input>
        </div>
    </div>   
</template>
<script lang="ts">
import {  Component, Prop, Watch, Vue} from "vue-property-decorator";
import { jwtDecode } from 'jwt-decode';

import axios from "axios";

/** 
* Ingresa un dato que se llama de la abse de datos
*
* @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
* @version 1.0.0
*/
@Component
export default class InputDate extends Vue {


  @Prop({type: String})
   public type: String;

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

    /**
     * Nombre de la ruta a obtener
     */
  @Prop({ type: String, required: true })
  public nameResource: string;

   /**
   * Valor del campo
   */
   @Prop({type: Object})
   public value: any;

   public result: any;

   /**
   * Valor del campo
   */
   @Prop({type: String, default:'keyRefresh'})
   public refresh: string;

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

                    const request =  Object.assign({}, dataPayload);
                    
                    this.result=request['data'];

                    if (this.type ==  'date') {
                    this.value[this.nameField]=this.result;   
                    }
                    this.$forceUpdate();
                }).catch(error=>{
                    
                })
    }
}
</script>
