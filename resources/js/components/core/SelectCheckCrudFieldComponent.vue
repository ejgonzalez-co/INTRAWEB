<template>
    <div style="width: -webkit-fill-available;">
        <div v-if="isCheck">
            <template v-if="isOptionsGroup">
                <!-- Multiple checkbox -->
                <div v-for="(option, index) in optionsList" :key="option[reduceKey]" class="custom-control custom-checkbox">
                    <br v-if="option['agrupador'] && index" />
                    <h6 style="margin-left: -1.5rem;" v-if="option['agrupador']">{{ option['agrupador'] }}</h6>

                    <span v-for="option2 in JSON.parse(option['opciones'])">
                        <input
                            :ref="refSelectCheck"
                            type="checkbox"
                            :id="option2[reduceLabel]+option2[reduceKey]" :class="cssClass"
                            :value="option2[reduceKey]" v-model="value[nameField]"
                            :name="nameField" :required="isRequired">
                        <label class="custom-control-label" :for="option2[reduceLabel]+option2[reduceKey]">{{ _getLabelText(option2) }}</label>
                        <br />
                    </span>
                </div>
            </template>

            <template v-else>
                <!-- Multiple checkbox -->
                <div v-for="option in optionsList" :key="option[reduceKey]" class="custom-control custom-checkbox">
                    <input
                        :ref="refSelectCheck"
                        type="checkbox"
                        :id="option[reduceLabel]+option[reduceKey]" :class="cssClass"
                        :value="option[reduceKey]" v-model="value[nameField]"
                        :name="nameField" :required="isRequired">
                    <label class="custom-control-label" :for="option[reduceLabel]+option[reduceKey]">{{ _getLabelText(option) }}</label>
                </div>
            </template>
        </div>
        <!-- Selector de opciones -->
        <select
        @change="selectChange" v-else-if="!enableSearch" :class="cssClass" :ref="refSelectCheck" v-model="value[nameField]" :multiple="isMultiple" :name="nameField" :required="isRequired" :disabled="elementDisabled">
            <option v-for="option in (optionsListManual ?? optionsList)" :key="option[reduceKey]" :value="valueVModel(option)" selected>{{  _getLabelText(option)  }}</option>
        </select>
        <!-- Selector con buscador integrado -->
        <v-select v-else :taggable="taggable" :disabled="elementDisabled" @input="selectChange" :loading="(optionsListManual == null && optionsList.length <= 0) && loadingSpinner && !taggable" :ref="refSelectCheck" v-model="value[nameField]" :options="optionsListManual ?? optionsList" :multiple="isMultiple" :get-option-label="(label) => _getLabelText(label)" :reduce="(option) => valueVModel(option)">
            <span slot="no-options" v-if="taggable">Ingrese una palabra y seleccionela o presione la tecla Enter para agregarla.</span>
            <span slot="no-options" v-else>No hay opciones de coincidencia.</span>
            <template #search="{attributes, events}">
                <input
                    :class="'vs__search'"
                    :required="isRequired ? !value[nameField] : false"
                    v-bind="attributes"
                    v-on="events"
                />
            </template>
            <template #spinner="{ loading }">
                <div v-if="loading" class="vs__spinner" />
            </template>
        </v-select>
    </div>
</template>

<script lang="ts">

    import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';
    // import axios from "../../axios-helper";

    /**
     * Componente de campo selector
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 08 - 2020
     * @version 1.0.0
     */
    @Component
    export default class SelectCheckCrudFieldComponent extends Vue {

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
         * Valida si el select debe tener buscador integrado
         */
         @Prop({ type: Boolean, default: false})
        public enableSearch: boolean;


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
         * Nombre del campo que contiene el objeto con el nombre (texto) del campo
         */
         @Prop({ type: String})
        public nameFieldObject: string;

        /**
         * Nombre de la entidad a obtener
         */
        @Prop({ type: String })
        public nameResource: string;

        /**
         * Lista de opciones
         */
        public optionsList: Array<any>;

        /**
         * Lista de opciones manuales
         */
        @Prop({ type: Array, default: null })
        public optionsListManual: any;

        /**
         * Valor de llave de reduccion
         */
        @Prop({default: 'id'})
        public reduceKey: any;

        /**
         * Nombre de valor visualizacion
         */
        // @Prop({ type: String, default: 'name'})
        // public reduceLabel: string;
        @Prop({required: true})
        public reduceLabel: any;

        /**
         * Valor del campo
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Funcion del change
         */
        @Prop({ type: Function, default: null })
        public functionChange: (any) => {};

        /**
         * Permite obtener la referencia del select o del checkbox
         */
        @Prop({ type: String})
        public refSelectCheck: String;

        /**
         * Valor de llave de reduccion
         */
        @Prop({ type: Array, default: null })
        public idsToEmpty: any;

        /**
         * Valida si el select tiene agrupación de opciones
         */
         @Prop({ type: Boolean, default: false})
        public isOptionsGroup: boolean;

        /**
         * Valida si muestra el spinner cargando o no
         */
        public loadingSpinner: boolean = true;

        /**
         * Permite mostrar o no la acción de elminar adjunto
         */
         @Prop({type: Boolean, default: false})
        public elementDisabled: boolean;

        /**
         * Valida si muestra el spinner cargando o no
         */
         @Prop({type: Boolean, default: false})
         public taggable: boolean;

        /**
         * Valor del campo para almacenar el objeto cuando se va a usar como filtro
         */
         @Prop({type: Object})
        public valueAux: any;

        /**
         * Datos a excluir del select
         */
         @Prop({ type: Array, default: () => []})
        public excludeData: any;


        /**
         * Valida si limpia los ids a null
         */
         @Prop({type: Boolean, default: false})
         public idsToEmptyNull: boolean;


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


        }

        /**
         * Se ejecuta cuando el componente ha sido momntado
         */
        mounted() {
            // Carga la lista de elementos
            !this.optionsListManual && this.nameResource ? this._getDataOptions() : null;
        }

        /**
         * Devuelve el valor cambiado a una funcion anonima (Callback)
         * y asigna vacio a la variable recibida por parámetro
         *
         * @author German Gonzalez V. - Feb. 10 - 2021
         * @version 1.0.0
         */
        public selectChange(event): void {
            // Valida si existe un elemento dependiente para limpiar su valor
            this.idsToEmpty?.forEach(element => {
                const valor = this.value[element];

                if (Array.isArray(valor)) {
                    this.$set(this.value, element, []);
                } else if (typeof valor === 'object' && valor !== null) {
                    this.$set(this.value, element, {});
                } else {
                    this.$set(this.value, element, this.idsToEmptyNull ? null : '');
                }
            });

            // Si hay evento y una función de cambio definida, se ejecuta
            if (event && this.functionChange) {
                this.functionChange(event.target ? event.target.value : event);
            }
        }

        /**
         * Obtiene la lista de opciones
         *
         * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
         * @version 1.0.0
         */
        private _getDataOptions(): void {
            if(this.nameResource.indexOf("undefined") <= 0 && this.nameResource.indexOf("null") <= 0){
                // Envia peticion de obtener todos los datos del recurso
                axios.get(this.nameResource)
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({data:[]}, dataPayload);
                    // Llena la lista de datos
                    this.optionsList = dataDecrypted?.data;

                    // Validacion para excluir los datos que no se deben mostrar en el select
                    if(this.excludeData.length > 0 && this.optionsList.length > 0) {
                        const dataToBeExcluded = this.excludeData.map(exclude => exclude[this.nameField]);
                        this.optionsList = dataDecrypted.data.filter(data => {
                            if(!dataToBeExcluded.includes(data[this.reduceKey])){
                                return data;
                            }
                        });
                    }
                    else{
                        // Si no tiene opciones de la lista, ingresa a la condición
                        if(this.optionsList.length <= 0) {
                            // Asigna el valor de false a la variable loadingSpinner para que oculte el spinner de cargando
                            this.loadingSpinner = false;
                        }
                    }
                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
            } else {
                this.loadingSpinner = false;
            }
        }

        /**
         * Obtiene el texto del label a mostar en
         * la opcion de la lista de filtrado
         *
         * @author Erika Johana Gonzalez Cuartas. - Sep. 01 - 2020
         * @version 1.0.0
         */
        private _getLabelText(dataSearch: any): string {

            // Si se cumple esta condición, el listado no va a tener opciones predefinidas, sino, insertadas por el usuario manualmente
            if(this.taggable) {
                return dataSearch;
            } else {
                // Texto de visualizacion
                let labelText: string = '';
                if (typeof this.reduceLabel != 'string') {
                    // Recorre la lista de nombres de objeto a mostrar
                    this.reduceLabel.forEach((name: string) => {
                        // Valor  mostrar
                        let dataShow: string = '';

                        const nameSplit = name.split('.');
                        // Valida si se debe obtener el valor de un objeto
                        if (nameSplit.length > 1) {
                            // Obtiene el valor del objeto con la notacion punto(dot)
                            dataShow = this._getDataFromObjectDot(nameSplit, dataSearch, 0);
                        } else {
                            dataShow = dataSearch[name];
                        }
                        /**
                         * Valida que la varible para mostrar el texto en el select no sea indefinido.
                         * Esto se puede dar porque las opciones (optionList) esta vacio o aún no han cargado.
                         */
                        if(dataShow != undefined) {
                            // Valida si el string ya tiene texto
                            if (labelText.length > 0) {
                                labelText += ' - ' + dataShow;
                            } else {
                                labelText += dataShow;
                            }
                        }
                    });
                } else {
                    labelText = dataSearch[this.reduceLabel];
                }
                return labelText;
            }
        }

        /**
         * Obtiene el valor final de un objeto
         *
         * @author Erika Johana Gonzalez Cuartas. - Sep. 01 - 2020
         * @version 1.0.0
         *
         * @param listDataObj Lista de datos a recorrer
         * @param object objeto de donde se obtiene el valor final
         * @param indx index incremental de la lista de objetos
         */
        private _getDataFromObjectDot(listDataObj: Array<any>, object: any, indx: number): any {


           // Caso de parada, valida si se debe retonrar el ultimo elemento de la lista
            if ((listDataObj.length - 1) === indx) {
                return object[listDataObj[indx]];
            }
            // Retorna valor obtenido de la lista de datos
            return this._getDataFromObjectDot(listDataObj, object[listDataObj[indx]], ++indx);
        }

        /**
         * Asigna el valor del v-model y opcionalmente el valor del obtejo en la posición nameFieldObject si este existe
         * @param option
         */
        public valueVModel(option: any): void {
            // Si se cumple esta condición, el listado no va a tener opciones predefinidas, sino, insertadas por el usuario manualmente
            if(this.taggable) {
                return option;
            } else {
                this.nameFieldObject && this.value[this.nameField] == option[this.reduceKey] ? this.$set(this.valueAux ? this.valueAux : this.value, this.nameFieldObject, option) : null;
                return option[this.reduceKey];
            }
        }
    }

</script>
