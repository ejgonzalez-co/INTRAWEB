<template>
<div>
    <autocomplete :match-selected="addList"  :name-prop='nameProp' :name-field="nameFieldAutocomplete" :value='value' :name-resource="nameResource" css-class="form-control" :name-labels-display="nameLabelsDisplay"></autocomplete>
    <small>{{ help }}</small>
    <select multiple class="form-control m-t-30" @keyup.46="removeElement(selected)" v-model="selected">
        <option v-for="option in value[nameOptionsList]" :key="option[nameKey]" :value="option[nameKey]">{{ _getLabelText(option) }}</option>
    </select>
</div>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from "vue-property-decorator";
    /**
     * Componente para añadir a la lista desde un autocompletar
     *
     * @author Erika Johana Gonzalez C. - Jul. 20 - 2020
     * @version 1.0.0
     */
    @Component
    export default class AddListAutoCompleteComponent extends Vue {

        /**
         * Nombre del campo del componente autocomplete
         */
        @Prop({ type: String, required: true })
        public nameFieldAutocomplete: string;
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
         * Nombre de la key a visualizar, llave primaria
         */
        @Prop({ type: String})
        public nameKey: string;

        /**
         * Ruta para obtener los datos del autocomplete
         */
        @Prop({ type: String, required: true })
        public nameResource: string;

         /**
         * Lista con los nombres de los campos de visualizacion de datos
         *
         */
        @Prop({ type: Array, required: true})
        public nameLabelsDisplay: Array<string>;

        /**
         * Valor del objeto dataform
         */
        @Prop({type: Object})
        public value: any;

        /**
         * Nombre del campo que contiene el json donde se guardara los option de la lista
         */
        @Prop({ type: String, required: true })
        public nameOptionsList: string;

        /**
         * Datos seleccionados de la lista
         */
        public selected: Array<string> = [];

        /**
         * Nombre de la propiedad a visualizar
         */
        @Prop({ type: String})
        public help: string;

         /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: 'id'})
        public reduceKey: string;

        /**
         * Constructor de la clase
         *
         * @author Erika Johana Gonzalez.  Jul. 25 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
        }

        /**
         * Agrega a lista desde el autocompletar
         * Esta funcion se ejecuta cuando se selecciona un elemento del autocomplete
         * 
         * @author Erika Johana Gonzalez C. - Abr. 15 - 2020
         * @version 1.0.0
         *
         * @param elementSeleccted elemento seleccionado
         */
        public addList(elementSeleccted: any): void {

            // Valida si la lista de opciones a agregar esta indefinida
            if (this.value[this.nameOptionsList] === undefined) {
                this.value[this.nameOptionsList] = [];
            }
            // Valida si el campo del dataform esta indefinido
            if (this.value[this.nameField] === undefined) {
                this.value[this.nameField] = [];
            }

            const isDuplicate = this.value[this.nameOptionsList].some(
                (element: any) => element[this.nameKey] === elementSeleccted[this.nameKey]
            );
            
            if (isDuplicate) {
                this.$swal({
                    icon: 'warning',
                    html: "Este elemento ya fue agregado a la lista",
                    confirmButtonText: 'Aceptar',
                    allowOutsideClick: false,
                });
                return;
            } else {
                // Agrega el elemento seleccionado a la lista de elementos
                this.value[this.nameOptionsList].push(elementSeleccted);
                // Agrega el identificador del elemento seleccionado a el campo del formulario
                this.value[this.nameField].push(elementSeleccted[this.nameKey]);
                
                // Actualiza todas las variables del componente 
                this.$forceUpdate();
            }

        }

        /**
         * Elimina de la lista un elemento o letios
         *
         * @author Erika Johana Gonzalez C. - Jul. 20 - 2020
         * @version 1.0.0
         * 
         * @param optionsSelected lista de opciones seleccionadas
         */
        public removeElement(optionsSelected: any): void {

            // Recorre los elementos seleccionados, ya que pueden ser letios al utilizar Ctrl
            optionsSelected.forEach((element)=> {
                // Encuentra la posición del elemento dentro de la lista 
                // Esto se hace porque para eliminar un elemento de un lista debe ser con el index. 
                let index = this.value[this.nameOptionsList].findIndex((x) => x[this.nameKey] === element);
                
                if (index > -1) {
                    // Quita el elemento de la lista
                    this.value[this.nameOptionsList].splice(index, 1);
                    this.value[this.nameField].splice(index, 1);
                }
            });
            // Actualiza todas las variables del componente 
            this.$forceUpdate();
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
