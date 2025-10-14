<template>
    <div>      
                <!-- Code Cost Field -->
                <div class="form-group row m-b-15">
                   <label for="code_cost" class="col-form-label col-md-5
                    required">{{ this.labelText }}</label>
                    <div class="col-md-6">
                       <input 
                       :v-model="nameField"
                       required
                       :id="nameField" 
                       :name="nameField" 
                       :value="result"                       
                        type="text" 
                        class="form-control"  
                        readonly
                        :key="result" >
                        <small>{{this.labelText}}</small>
                    </div>
                </div>
           
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from "jwt-decode";

/**
 * Componente para hacer que lso campos se llenen cuando el select seleccione algo
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
 * @version 1.0.0
 */
@Component
export default class InputDynamic extends Vue {
   
  /**
         * Nombre del campo
         */
        @Prop({ type: String, required: true })
        public nameField: string;

  /**
         * Nombre del campo
         */
        @Prop({ type: String, default : "Código del rubro presupuestal" })
        public labelText: string;

           /**
         * Valor del campo
         */
         @Prop({type: Object})
         public value: any;

                /**
         * Nombre de la ruta a obtener
         */
        @Prop({ type: String, required: true })
        public nameResource: string;

               /**
         * Nombre de la ruta a obtener
         */
        @Prop({ type: Number})
        public idResource: number;

         public result: any;
    
    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        
        /**LLama a la operacion de los dos numeros ingresados por props */
        if(this.value[this.nameField]){
           this.result=this.value[this.nameField];
        }else{
             this.value[this.nameField] = '';
             this.result='';
            
        }
          this.getChange();
    }

    /**
     * En este metodo se hacen las operaciones de dos numeros
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
     * @version 1.0.0
     */
    public getChange(): void {
        
        if(this.nameResource && this.idResource && isNaN(this.idResource)==false ){
            
             axios.get(this.nameResource+ this.idResource)
                    .then(res=>{
                        res.data.data = res.data.data ? jwtDecode(res.data.data) : null;
                        const request = res.data.data.data;                        
                        this.result=request;                        
                        this.value[this.nameField]=this.result;    
                        this.$forceUpdate();
                    }).catch(error=>{
                       
                    })

        }
        
        
    }
}
</script>
