<template>
    <div>

      <select class="form-control" v-model="dataForm.tipo_necesidad" @change="changeData">
            <option  value="Producto">Producto</option>
            <option value="Servicio">Servicio</option>
         </select>
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
export default class needType extends Vue {


//   @Prop({type: String})
//    public type: String;
   public dataForm: any;


   @Prop({})
   public typeNeed: any;


   @Prop({})
   public dataType: any;
   /**
   * Constructor de la clase
   *
   * @author Johan david velasco rios. - Agosto. 03 - 2021
   * @version 1.0.0
   */
   constructor() {
      super();
      this.dataForm = {};
   }

   created() {
      if(this.dataType){
            this.dataForm.tipo_necesidad = this.dataType;
            this.$forceUpdate();
      }  
   }

   //Detecta el cambio en el prop
   @Watch('typeNeed')
   onMiPropChanged(newVal: string, oldVal: string) {
      console.log(`El valor de miProp ha cambiado de ${oldVal} a ${newVal}`);
    // Llama a la función que deseas ejecutar cuando el prop cambie
    this.valueDefault(newVal);
   }

   //Detecta el cambio en el prop
   @Watch('dataType')
   onMiPropDataChanged(newVal: string, oldVal: string) {

      console.log(oldVal);
    // Llama a la función que deseas ejecutar cuando el prop cambie
    this.dataForm.tipo_necesidad = newVal;
    this.$forceUpdate();
   }


   //Asigna el valor del campo y actualiza el valor del campo en el dataForm del componente padre
   valueDefault(data){
      if(data == 'Inventario'){
         this.dataForm.tipo_necesidad = 'Producto';
         (this.$parent as any).dataForm.tipo_necesidad = this.dataForm.tipo_necesidad;
         this.$forceUpdate();

      }

   }

   //Actualiza la data en el componente padre
   changeData(){
         (this.$parent as any).dataForm.tipo_necesidad = this.dataForm.tipo_necesidad;
         this.$forceUpdate();
   }




   /**
   * envia los datos del al servidor y confirma con un sweeralert2
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
   * @version 1.0.0
   */

   }
</script>
