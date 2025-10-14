<template>
    <div>
        <div class="input-group m-b-10">
            <input 
                tabindex="0"
                @blur="verifyData"
                type="text"
                :class="cssClass"
                :required="isRequired"
                v-model="query"
                :disabled="disabled"
                @keyup="_autoComplete">
            <div class="input-group-append" v-if="matchList.length">
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                </div>
                <button type="button" @click="_clean" class="btn btn-primary no-caret">Limpiar</button>
            </div>
        </div>
        <div class="card border-0" v-if="matchList.length">
            <ul class="list-group list-group-flush">
                <li class="list-group-item list-group-item-action"
                    v-for="match in matchList" :key="match[reduceKey]"
                    @click="_selectMatch(match)">
                    {{ _getLabelText(match) }}
                </li>
                <div class="dropdown-divider"></div>
            </ul>
        </div>
    </div>
</template>

<script lang="ts">

    import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";
import { AnyNaptrRecord } from "dns";
import { Console } from "console";
import { jwtDecode } from 'jwt-decode';

    /**
     * Componente de autobusqueda
     *
     * @author  Andres Stiven Pinzon G. - Jul. 10 - 2020
     * @version 1.0.0
     */
    @Component
    export default class AutoCompleteFieldComponent extends Vue {

        /**
         * Clase css
         */
        @Prop({ type: String})
        public cssClass: string;

        /**
         * Clase css
         */
         @Prop({ type: String, default: 'Por favor ingrese un número de placa valido'})
        public text: string;

        /**
         * Valida si el campo es requerido
         */
        @Prop({ type: Boolean, default: false})
        public isRequired: boolean;

        /**
         * Valida si los valores del formulario
         * son para actualizar o crear
         */
        @Prop({type: Boolean, default: false})
        public isUpdate: boolean;

        /**
         * Lista de coincidencias
         */
        public matchList: Array<any>;

        /**
         * Valida si el campo esta deshabilitado o no
         */
        @Prop({ type: Boolean, default: false})
        public disabled: boolean;
        
        /**
         * Datos de objeto seleccionado
         */
        @Prop({ type: Function, default: (any) => {} })
        public matchSelected: (any) => {};

        /**
         * Cantidad de letras minimas permitidas para empezar a filtrar
         */
        @Prop({type: Number, default: 3})
        public minTextInput: number;

        /**
         * Lista con los nombres de visualizacion de datos
         *
         */
        @Prop({ type: Array, required: true})
        public nameLabelsDisplay: Array<string>;

        /**
         * Nombre del campo
         */
        @Prop({ type: String, required: true })
        public nameField: string;

        /**
         * Nombre de la propiedad a visualizar
         */
        @Prop({ type: String})
        public nameProp: string;

        /**
         * Nombre de la entidad a obtener
         */
        @Prop({ type: String, required: true })
        public nameResource: string;

        /**
         * Texto ingresado a filtrar
         */
        public query: string;

        /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: 'id'})
        public reduceKey: string;

        /**
         * Valor del campo
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Valor por defecto del campo
         */
        @Prop()
        public valueDefault: any;

        /**
         * Funcion del change
         */
        @Prop({ type: Function, default: (any) => {} })
        public functionChange: (any) => {};

        /**
         * Cantidad de letras minimas permitidas para empezar a filtrar
         */
        @Prop({type: String, default: "no"})
        public asignarAlData: string;


        public message: string;

        /**
         * Constructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 10 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
            this.query = '';
            this.matchList = [];
        }

        mounted() {
            // Valida si los datos son para actualizar
            if (this.isUpdate) {
                // Se verifica si existe datos por defecto
                if (this.valueDefault) {

                    // Texto de visualizacion
                    let labelText: string = '';

                    // Recorre la lista de nombres de objeto a mostrar
                    this.nameLabelsDisplay.forEach((name: string) => {
                        // Valida si el string ya tiene texto
                        if (labelText.length > 0) {
                            labelText += ' - ' + this.valueDefault[name];
                        } else {
                            labelText += this.valueDefault[name];
                        }
                    });
                    
                    this.query = labelText;
                }
            }
        }

        /**
         * Obtiene la lista de coincidencias
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 10 - 2020
         * @version 1.0.0
         */
        private _autoComplete(): void {
            // console.log(this.query.length);
            this.value[this.nameField] = "";

            // Valida que la cantidad de letras ingresadas sean las minimas permitidas
            if (this.query.length >= this.minTextInput) {
                // Envia peticion de obtener todos los datos del recurso
                axios.get(this.nameResource, {params: {query: this.query}})
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    // console.log(res.data.data);
                    const dataDecrypted = Object.assign({data:[]}, dataPayload);
                    this.matchList = dataDecrypted?.data;
                    
                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
            }
        }

        /**
         * Obtiene la lista de coincidencias
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 10 - 2020
         * @version 1.0.0
         */
        public verifyData(){
            
            if(!this.value[this.nameField] && !this.matchList.length){
                this.$swal({
                icon: 'warning',
                title: '¡Advertencia!',
                text:this.text,
                });
                this._clean();
                this.value[this.nameField] = "";
            }

        }

        /**
         * Limpia el campo de busqueda y la lista de coincidencias
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 11 - 2020
         * @version 1.0.0
         */
        private _clean() {
            this.query = null;
            this.matchList = [];
        }

        /**
         * Obtiene el texto del label a mostar en
         * la opcion de la lista de filtrado
         *
         * @author Erika Johana Gonzalez Cuartas. - Sep. 01 - 2020
         * @version 1.0.0
         */
        private _getLabelText(dataSearch: any): string {
            // Texto de visualizacion
            let labelText: string = '';
            
            // Recorre la lista de nombres de objeto a mostrar
            this.nameLabelsDisplay.forEach((name: string) => {

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

                // Valida si el string ya tiene texto
                if (labelText.length > 0) {
                    labelText += ' - ' + dataShow;
                } else {
                    labelText += dataShow;
                }
            });
            return labelText;
        }

        /**
         * Obtiene el dato seleccionado
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 11 - 2020
         * @version 1.0.0
         */
        private _selectMatch(dataSelected: any) {

            // Valida si requiere el nombre de la seleccion
            if (this.nameProp) {
                this.query = dataSelected[this.nameProp];
            }
            // Valida si obtiene los datos de formulario
            if (this.value) {
                this.value[this.nameField] = dataSelected[this.reduceKey];   
                
                // Valida si los datos obtenidos deben ser asignados a la variable dataForm
                if (this.asignarAlData) {

                    function jsonConcat(o1: any, o2: any) {
                        for (var key in o2) {
                            o1[key] = o2[key];
                        }
                        
                        return o1;
                    }

                    this.value = jsonConcat(this.value, dataSelected);
                }
                // Actualiza todas las variables del componente 
                this.$parent.$forceUpdate();
            }
            // Limpia la lista de conicidencias
            this.matchList = [];
            // Envia coincidencia seleccionada
            this.matchSelected(dataSelected);

            // Valida si se devuelve el valor cambiado a una funcion anonima (Callback)
            if (this.functionChange) {
                this.functionChange(dataSelected);
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
    }
</script>
