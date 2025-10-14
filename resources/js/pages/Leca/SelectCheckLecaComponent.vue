<template>
    <div>
        <div v-if="isCheck"  @change="selectChange">
             <!-- Checkbox para seleccionar todos -->
             <div>
            <!-- Checkbox para seleccionar todos -->
            <div class="custom-control custom-checkbox">
                <input
                type="checkbox"
                :id="selectAllId"
                :class="cssClass"
                v-model="selectAllChecked"
                >
                <label class="custom-control-label" :for="selectAllId">Seleccionar todos los ensayos</label>
            </div>
            </div>
            <!-- Multiple checkbox -->
            <div v-for="option in optionsList" :key="option[reduceKey]" class="custom-control custom-checkbox">
                <input
                    :ref="refSelectCheck"
                    type="checkbox"
                    :id="option[reduceLabel]+option[reduceKey]" :class="cssClass"
                    :value="option[reduceKey]" v-model="value[nameField]"
                    :name="nameField" :required="isRequired"
                    :checked="value[nameField].includes(option[reduceKey])">
                    
                <label class="custom-control-label" :for="option[reduceLabel]+option[reduceKey]">{{ option[reduceLabel] }}</label>
            </div>
        </div>
        <!-- Selector de opciones -->
        <select
        @change="selectChange" v-else :class="cssClass" :ref="refSelectCheck" v-model="value[nameField]" :multiple="isMultiple" :name="nameField" :required="isRequired" :disabled="disabled">
            <option v-for="option in optionsList" :key="option[reduceKey]" :value="option[reduceKey]" v-show="option['deleted_at'] == null">{{  _getLabelText(option)  }}</option>
        </select>
       
    </div>
</template>

<script lang="ts">

    import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';

    /**
     * Componente de campo selector
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 08 - 2020
     * @version 1.0.0
     */
    @Component
    export default class SelectCheckLecaComponent extends Vue {

         // Nueva propiedad para manejar el estado del checkbox "Seleccionar Todos"
        public selectAllChecked: boolean = false;

        // Nueva propiedad para almacenar el id del checkbox "Seleccionar Todos"
        public selectAllId: string = "select_all_checkbox";
        /**
         * Llave foránea del listado principal de opciones
         */
        @Prop({ type: String, default: 'id'})
        public foreignKey: string;

        /**
         * Id del listado (select) dependiente (padre)
         */
        @Prop({ type: String, default: 'id'})
        public dependentId: string;
        
        /**
         * Clase css
         */
        @Prop({ type: String})
        public cssClass: string;

        /**
         * Valida si el campo es un checkbox
         */
        @Prop({ type: Boolean, default: false})
        public isCheck: boolean;

        /**
         * Valida si el campo es requerido
         */
        @Prop({ type: Boolean, default: false})
        public isRequired: boolean;

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
        @Prop({ type: String, required: true })
        public nameResource: string;

        /**
         * Lista de opciones
         */
        public optionsList: Array<any>;


            @Prop({ type: String, default: ''})
        public idToEmpty: string;

        /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: 'id'})
        public reduceKey: string;

        /**
         * Nombre de valor visualizacion
         */
        @Prop({ type: String, default: 'name'})
        public reduceLabel: string;

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
         * Permite obtener la referencia del select o del checkbox
         */
        @Prop({ type: String})
        public refSelectCheck: String;


        /**
         * Constructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();

            this.optionsList = [];
            // Valida si se habilita el componente para multiples checkbox
            if (this.isCheck) {
                // Valida si tiene elementos asignados
                if (this.value[this.nameField]) {
                    // Filtra los datos de seleccion para evidenciar el checkbox seleccionado
                    const valueSelected: number | string = this.value[this.nameField].map((value) => value[this.reduceKey]);
                    // Asigna los datos seleccionados
                    this.value[this.nameField] = valueSelected;
                } else {
                    // Se inicializa para aceptar multiples checkbox
                    this.value[this.nameField] = [];
                }
            }
            
            if (this.isMultiple) {
                this.value[this.nameField] = [];
            }
            
        }

 /**
         * Devuelve el valor cambiado a una funcion anonima (Callback)
         *
         * @author Carlos Moises Garcia T. - Nov. 07 - 2020
         * @version 1.0.0
         */
         public selectChange(event): void {
            if (this.selectAllChecked) {
                console.log(this.selectAllChecked);
            // Si el checkbox "Seleccionar Todos" está marcado, seleccionamos todas las opciones
            this.value[this.nameField] = this.optionsList.map((option) => option[this.reduceKey]);
        
        } else {
            // Si el checkbox "Seleccionar Todos" no está marcado, deseleccionamos todas las opciones
            this.selectChangeSingle;
        }

        if (this.functionChange) {
                    this.functionChange(event.target.value);
                }
        }

        
        public selectChangeSingle(event): void {
            this.value[this.idToEmpty] = '';
            if (event) {
                if (this.functionChange) {
                    this.functionChange(event.target.value);
                }
            }
        }

        
        /**
         * Se ejecuta cuando el componente ha sido momntado
         */
        mounted() {
            // Carga la lista de elementos
            this._getDataOptions();
            // this.selectAllChecked = this.value[this.nameField].length === this.optionsList.length;
        }

       

        /**
         * Obtiene la lista de opciones
         *
         * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
         * @version 1.0.0
         */
         private _getDataOptions(): void {
                // Envia peticion de obtener todos los datos del recurso
                axios.get(this.nameResource)
                .then((res) => {

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);
                    // Agregar la opción "todo" al inicio de la lista con un valor numérico único
                    this.optionsList = dataDecrypted["data"];
                    ;
                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
            }

            
            }

</script>
