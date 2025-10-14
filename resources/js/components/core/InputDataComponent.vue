
<template>
    <div>
        <div>

            <div v-if="prefix=='$'">
                <currency-input
                    v-model="value[nameField] = value[valueRecibido[0]][valueRecibido[1]]"
                    required="true"
                    :currency="{'prefix': prefix}"
                    locale="es"
                    :readonly="isReadonly"
                    :precision="0"
                    class="form-control"
                    >
                </currency-input>
            </div>
            <div v-else-if="prefix=='otros'">

                <input
                    type="Text"
                    :class="cssClass"
                    :name="nameField" 
                    :required="isRequired"
                    :disabled="disabled"
                    v-model="value[nameField] = value[valueRecibido[0]][valueRecibido[1]]"
                    >
            </div>
            <!-- Multiple checkbox -->
            <div v-else class="col-form-label col-md-15 required">
                <input
                    type="Text"
                    :class="cssClass"
                    :name="nameField" 
                    :required="isRequired"
                    :disabled="disabled"
                    :value="optionsList"
                    >
            </div>
        </div>
    
    </div>
</template>

<script lang="ts">

    import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";
    // import axios from "../../axios-helper";
    import { jwtDecode } from 'jwt-decode';

    /**
     * Componente de campo selector
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 08 - 2020
     * @version 1.0.0
     */
    @Component
    export default class InputDataComponent extends Vue {

        /**
         * Clase css
         */
        @Prop({ type: String})
        public cssClass: string;


        /**
         * Valida si el campo es requerido
         */
        @Prop({ type: Boolean, default: false})
        public isRequired: boolean;

        /**
         * Valida si el campo es requerido
         */
        @Prop({ type: Boolean, default: true})
        public isReadonly: boolean;

        /**
         * Valida si el select esta deshabilitado o no
         */
        @Prop({ type: Boolean, default: false})
        public disabled: boolean;

        /**
         * Valida si el campo es multiple
         */
        @Prop({ type: Boolean, default: false})
        public isMultiple: boolean;

        /**
         * Nombre del campo
         */
        @Prop({ type: String, required: true })
        public nameField: string;

        /**
         * Nombre de la entidad a obtener
         */
        @Prop({ type: String, required: false })
        public nameResource: string;

        /**
         * Lista de opciones
         */
        public optionsList: Array<any> = [];

        /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: 'id'})
        public reduceKey: string;

        /**
         * Nombre de valor visualizacion
         */
        // @Prop({ type: String, default: 'name'})
        // public reduceLabel: string;
        // @Prop({required: true})
        // public reduceLabel: any;

        /**
         * Valor del campo
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Funcion del change
         */
        @Prop({ type: Function, default: (any) => {} })
        public functionChange: (any) => {};

      

        /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: ''})
        public idToEmpty: string;

        /**
         * Valor de llave de reduccion
         */
         @Prop({ type: String, default: ''})
        public typeInput: string;

    
        /**donde se guarda el prefijo */
        @Prop({ type: String})
        public prefix: String;

            
        /**donde se guarda el prefijo */
        @Prop({})
        public valueRecibido: Array<any>;

        /**
         * Constructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();

            this.optionsList = [];
            
            if (this.valueRecibido && this.valueRecibido.length > 1) {
                let propiedad = this.valueRecibido[1];
                // this.value[this.valueRecibido[0]] = { propiedad: null };
                this.$set(this.value,this.valueRecibido[0],{propiedad: null});
            }


        }

        /**
         * Se ejecuta cuando el componente ha sido momntado
         */
        mounted() {
            // Carga la lista de elementos
            this._getDataOptions();
        }


        /**
         * Obtiene la lista de opciones
         *
         * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
         * @version 1.0.0
         */
        private _getDataOptions(): void {
            if(!this.typeInput){
                // Envia peticion de obtener todos los datos del recurso
                axios.get(this.nameResource)
                .then((res) => {

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({data:[]}, dataPayload);

                    // Llena la lista de datos
                    this.optionsList = dataDecrypted['data'];

                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
            }else{
                this.$forceUpdate();
            }
            
        }

      
    }

</script>