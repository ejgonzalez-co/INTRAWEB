<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-6" for="name_item"
                        >Nombre del rubro:</label
                    >
                    <div class="col-md-6">
                        <input
                            class="form-control"
                            type="text"
                            readonly
                            name="name_item"
                            :value="nameItem"
                        />
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-6" for="cod_item"
                        >Código del rubro presupuestal:</label
                    >
                    <div class="col-md-6">
                        <input
                            class="form-control"
                            type="text"
                            readonly
                            name="cod_item"
                            :value="codItem"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-6" for="name_cost"
                        >Nombre del centro de costos:</label
                    >
                    <div class="col-md-6">
                        <input
                            class="form-control"
                            type="text"
                            readonly
                            name="name_cost"
                            :value="nameCenter"
                        />
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-6" for="cod_center"
                        >Código del centro de costos:</label
                    >
                    <div class="col-md-6">
                        <input
                            class="form-control"
                            type="text"
                            readonly
                            name="cod_center"
                            :value="codCenter"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group row m-b-15">
                    <label class="col-form-label col-md-6" for="name_cost"
                        >Valor total del rubro:</label
                    >
                    <div class="col-md-6">
                        <currency-input
                            :v-model="nameField"
                            required="true"
                            :currency="{ prefix: '$ ' }"
                            locale="es"
                            :value="valueItem"
                            readonly="true"
                            :precision="2"
                            class="form-control"
                            :key="nameField"
                        >
                        </currency-input>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="form-group row m-b-15"></div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para enviar peticiones al servidor con swal alert
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
 * @version 1.0.0
 */
@Component
export default class FormExecution extends Vue {
    //Clase del input
    @Prop({ type: String })
    public classInput: String;

    /**
     * Nombre de la ruta a obtener
     */
    @Prop({ type: String, required: true })
    public nameResource: string;

    //Nombre del campo
  @Prop({ type: String, required: true })
        public nameField: string;

    /**
     * Valor del campo
     */
    @Prop({ type: Object })
    public value: any;

    //Aqui se guarda el resultado
    public result: any;
    //Nombre del item
    public nameItem: any;
    //codigo del item
    public codItem: any;
    //Nombre del centro de costo
    public nameCenter: any;
    //Codigo del centro de costo
    public codCenter: any;
    //Valor del item
    public valueItem: any;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.result = 0;
         this.valueItem = 0;
        this.nameItem = "";
        this.codItem = "";
        this.nameCenter = "";
        this.codCenter = "";
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
            .then(res => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({data:[]}, dataPayload);

                const request = dataDecrypted["data"];
                this.result = request;
                this.nameItem = this.result.name;
                this.codItem = this.result.code_cost;
                this.nameCenter = this.result.cost_center_name;
                this.codCenter = this.result.cost_center;
                this.valueItem = this.result.value_item;
                this.value[this.nameField] = this.result.value_item;
                 (this.$parent.$refs["operacionPorcentaje"] as Vue)["operationMethod"](this.result.value_item);
                
               this.$forceUpdate();
            })
            .catch(error => {});
             
    }
}
</script>
