<template>
    <div>
        <div class="input-group">

                <input
                :id="nameField"
                type="text"
                :class="cssClass"
                :required="isRequired"
                v-model="query"
                :disabled="elementDisabled"
                autocomplete="off"
                @keydown.up.prevent="preventCursorMove"
                @keydown.down.prevent
                @keydown="handleKeyEvents"
                @blur="handleBlur"
                >

                <!-- :autocomplete="nameField + '1'" Esta línea permite la personalización del autocomplete para que no acepte las sugerencias del navegador -->
                <div class="vs__spinner" v-if="loading" style="
                    opacity: 1;
                    z-index: 9;
                    margin-left: -20px;
                "></div>

            <div class="input-group-append" v-if="matchList.length">
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-divider"></div>
                </div>
                <button type="button" @click="_clean" class="btn btn-primary no-caret">Limpiar</button>
            </div>
        </div>

        <div v-if="this.loadMoreOnScroll">
            <div class="card border-0" v-if="matchList.length" style="position: relative; margin-top: 1px;">
                <div style="z-index: 999; width: 100%; max-height: 200px; overflow-y: auto;" ref="listContainerDiv" @scroll="_handleScroll">
                    <ul class="list-group list-group-flush" ref="listContainerUl" style="">
                        <li ref="items" class="list-group-item list-group-item-action" :class="selectedIndex === matchList.indexOf(match) ? 'vs__dropdown-option--highlight' : ''"
                            v-for="(match, index) in matchList" :key="index"
                            @click="_selectMatch(match)" :aria-selected="selectedIndex === matchList.indexOf(match)" @mouseover="selectItemOnHover(index)">
                            {{ _getLabelText(match) }}
                        </li>
                        <li v-if="this.isLoadingOnScroll" class="list-group-item list-group-item-action"><div class="skeleton-text"></div></li>
                        <li v-if="this.isLoadingOnScroll" class="list-group-item list-group-item-action"><div class="skeleton-text"></div></li>
                        <li v-if="this.isLoadingOnScroll" class="list-group-item list-group-item-action"><div class="skeleton-text"></div></li>
                        <li v-if="this.isLoadingOnScroll" class="list-group-item list-group-item-action"><div class="skeleton-text"></div></li>
                        <li v-if="this.isLoadingOnScroll" class="list-group-item list-group-item-action"><div class="skeleton-text"></div></li>
                        <div class="dropdown-divider"></div>
                    </ul>
                </div>
            </div>
        </div>

        <div v-else>
            <div class="card border-0" v-if="matchList.length" style="position: relative; margin-top: 1px;">
                <div style="z-index: 999; width: 100%; max-height: 200px; overflow-y: auto;" ref="listContainerDiv">
                    <ul class="list-group list-group-flush" ref="listContainerUl" style="">
                        <li ref="items" class="list-group-item list-group-item-action" :class="selectedIndex === matchList.indexOf(match) ? 'vs__dropdown-option--highlight' : ''"
                            v-for="(match, index) in matchList" :key="index"
                            @click="_selectMatch(match)" :aria-selected="selectedIndex === matchList.indexOf(match)" @mouseover="selectItemOnHover(index)">
                            {{ _getLabelText(match) }}
                        </li>
                        <div class="dropdown-divider"></div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>


<script lang="ts">

    import { Component, Prop, Watch, Vue } from "vue-property-decorator";

    import axios from "axios";

    import { jwtDecode } from 'jwt-decode';
    

    import { debounce } from 'lodash';

    /**
     * Componente de autobusqueda
     *
     * @author Jhoan Sebastian Chilito S. - Jul. 10 - 2020
     * @version 1.0.0
     */
    @Component
    export default class AutoCompleteFieldComponent extends Vue {

        public loading: boolean = true;

        public isLoadingOnScroll: boolean = false;

        /**
         * Clase css
         */
        @Prop({ type: String})
        public cssClass: string;

        /**
         * Cargar mas registros mediante el scroll
         */
        @Prop({ type: Boolean, default: false})
        public loadMoreOnScroll: boolean;

        /**
         * Valida si el campo es requerido
         */
        @Prop({ type: Boolean, default: false})
        public isRequired: boolean;

        /**
         * Lista de coincidencias
         */
        public matchList: Array<any>;

        /**
         * Datos de objeto seleccionado
         */
        @Prop({ type: Function, default: (any) => {} })
        public matchSelected: (any) => {};

        /**
         * Cantidad de letras minimas permitidas para empezar a filtrar
         */
        @Prop({type: Number, default: 4})
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
         * Nombre del campo cuando esta editando el formulario
         */
        @Prop({ type: String, default: '' })
        public nameFieldEdit: string;

        /**
         * Nombre de la propiedad a visualizar
         */
        @Prop({ type: String})
        public nameProp: string;

        /**
         * Nombre de la propiedad adicional a visualizar
         */
         @Prop({ type: String})
        public AditionalNameProp: string;

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
         * Valor del campo
         */
        @Prop({type: Boolean, default: false})
        public emiting: boolean;

        /**
         * Funcion del change
         */
        @Prop({ type: Function, default: (any) => {} })
        public functionChange: (any) => {};

        /**
         * Controla la función de autocompletar, hasta que termine la acción de la consulta autocompletar
         */
        public validateQueryComplete: boolean;

        /**
         * Lista con los nombres de los campos a los que se quiere cambiar el valor
         *
         */
        @Prop({ type: Array, required: false})
        public fieldsChangeValues: Array<string>;

        /**
         * Permite mostrar o no la acción de elminar adjunto
         */
        @Prop({type: Boolean, default: false})
        public elementDisabled: boolean;

        /**
         * Nombre del objeto que almacenará el registro seleccionado del autocomplete
         */
         @Prop({ type: String})
        public nameFieldObject: string;

        public selectedIndex = 0; // track the selected index in the dropdown list

        /**
         * Valor de llave de reduccion
         */
         @Prop({ type: Array, default: null })
        public idsToEmpty: any;

        /**
         * Desactiva blur para que no limpie el valor cuando no encuentra coincidencias
         */
         @Prop({ type: Boolean, default: false })
        public activarBlur: boolean;

        /**
         * Busqueda mediante debounce para evitar hacer peticiones innecesarias
         */
        public debouncedSearch: (...args: any[]) => void;

        // Indica si se seleccionó un valor del listado de coincidencias del autocomplete
        public valorSeleccionado: boolean = false;

        /**
         * Valor por defecto del campo
         */
        @Prop()
        public valueDefault: any;

        /**
         * Valida si los valores del formulario
         * son para actualizar o crear
         */
        @Prop({type: Boolean, default: false})
        public isUpdate: boolean;


        /**
         * Constructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 10 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
            this.query = this.value[this.nameFieldEdit] ? this.value[this.nameFieldEdit] : "";
            this.matchList = [];
            this.validateQueryComplete = true;
            this.loading = false;
            this.debouncedSearch = debounce(this._autoComplete, 1000);
        }
        // Observable para la variable query, detecta cualquier cambio en el autocompletar
        @Watch('query')
        onQueryChanged(newValue: string) {
            // Si valorSeleccionado = true, entonces el usuario acabó de seleccionar un valor del autocompletar, no debe de ejecutar nada mas
            if(!this.valorSeleccionado) {
                // Asigna el valor ingresado en el autocomplete en la variable 'nameFieldEdit'
                this.$set(this.value, this.nameFieldEdit, newValue);
                this.$set(this.value, this.nameField, "");
                this.debouncedSearch();
            } else {
                this.valorSeleccionado = false;
            }
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
                            console.log(this.valueDefault);
                        }
                    });

                    this.query = labelText;
                }
            }
        }

        /**
         * Obtiene la lista de coincidencias
         *
         * @author Seven Soluciones Informáticas S.A.S. - Abr. 10 - 2024
         * @version 2.1.0
         */
        private _autoComplete(): void {

            this.selectedIndex = 0;
            this.loading = true; // Mostrar el spinner

            if (this.query.length >= this.minTextInput && this.validateQueryComplete) {
                this.validateQueryComplete = false;

                axios.get(this.nameResource, {params: {query: this.query}})
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({data:[]}, dataPayload);

                    // Si es mediante el scroll la carga de registros entonces se guarda la data en sessionStorage
                    if(this.loadMoreOnScroll){
                        // Modifica la lista de posibles destinatarios en el sessionStorage
                        this._setPotentionalRecipients(dataDecrypted?.data);
                        this.matchList = this._getPotentionalRecipients(dataDecrypted?.data);

                    }else{
                        this.matchList = dataDecrypted?.data;
                    }

                    this.validateQueryComplete = true;
                    this.loading = false; // Ocultar el spinner cuando la solicitud se complete
                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
            }else{
                // Valida que no se esté ejecutando una solicitud de consulta actualmente
                if(this.validateQueryComplete)
                    this.loading = false; // Ocultar el spinner cuando la solicitud se complete
                if (this.matchList && this.matchList.length > 0) {
                    this.matchList = [];
                }
            }
        }

        /**
         * Maneja el evento scroll para cargar la informacion cuando el usuario llegue al ultimo registro
         */
        private _handleScroll(){
            if(this.isLoadingOnScroll) return;

            setTimeout(() => {
                if(this.isTheLastItem()){
                    this._paginateList(this.matchList.length);
                }
            },1000);
        }

        /**
         * Valida si el item visualizada mediante scroll es el ultimo de la lista
         */
        private isTheLastItem() : boolean{
            const container = this.$refs.listContainerDiv as HTMLElement;

            // Calculamos si estamos cerca del final
            const {
                scrollHeight,
                scrollTop,
                clientHeight
            } = container;

            return (scrollHeight - scrollTop - clientHeight) == 0 || (scrollHeight - scrollTop - clientHeight) == 1;
        }

        /**
         * Obtiene la informacion de los posibles destinarios para añadirlos en el sessionStorage
         */
        private _paginateList(currentPage:any){
            this.isLoadingOnScroll = true;

            axios.get(`${this.nameResource}?cp=${btoa(currentPage)}`, {params: {query: this.query}})
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({data:[]}, dataPayload);

                    // Obtiene el array que ese encuentra en sessionStorage para agregar la data nueva
                    const storedArray = JSON.parse(sessionStorage.getItem('potential_recipients')) || [];

                    // Combina el array para guardar ese nuevo array en sessionStorage
                    const potentialRecipients = [...storedArray, ...dataDecrypted?.data];
                    sessionStorage.setItem('potential_recipients', JSON.stringify(potentialRecipients));

                    this.matchList = this._getPotentionalRecipients(dataDecrypted?.data);

                    this.validateQueryComplete = true;
                    this.loading = false; // Ocultar el spinner cuando la solicitud se complete
                    this.isLoadingOnScroll = false;
                })
                .catch((err) => {
                    console.log('Error al obtener la lista.');
                });
        }

        /**
         * Obtiene la informacion de los posibles destinarios si esta utilizanda la carga de registros mediante scroll
         */
        private _getPotentionalRecipients(data){
            const potentialRecipientsOnSessionStorage = sessionStorage.getItem('potential_recipients');

            if(potentialRecipientsOnSessionStorage){
                return JSON.parse(potentialRecipientsOnSessionStorage);
            }
            return data;
        }

        /**
         * Guarda la informacion de los posibles destinarios si esta utilizanda la carga de registros mediante scroll
         */
        private _setPotentionalRecipients(data) : void{
            sessionStorage.removeItem('potential_recipients');
            sessionStorage.setItem('potential_recipients', JSON.stringify(data));
        }

        /**
         * Permite desplazar el scroll hacia arriba, dependiendo de la posición del ítem y del scroll
         */
        public scrollUpItem() {
            // Desplazar el scroll hacia arriba si el nuevo ítem seleccionado está fuera de la vista
            this.$nextTick(() => {
                const listContainer = this.$refs.listContainerDiv;
                const selectedItem = this.$refs.items[this.selectedIndex];
                const containerRect = listContainer["getBoundingClientRect"]();
                const itemRect = selectedItem.getBoundingClientRect();
                // Si el ítem esta por encima del área visible del listado, se desplaza el scroll una posición hacia arriba según el alto del ítem
                if (itemRect.top < containerRect.top) {
                    listContainer["scrollTop"] -= containerRect.top - itemRect.top;
                }
            });
        }

        /**
         * Permite desplazar el scroll hacia abajo, dependiendo de la posición del ítem y del scroll
         */
        public scrollDownItem () {
            // Desplazar el scroll hacia abajo si el nuevo ítem seleccionado está fuera de la vista
            this.$nextTick(() => {
                const listContainer = this.$refs.listContainerDiv;
                const selectedItem = this.$refs.items[this.selectedIndex];
                const containerRect = listContainer["getBoundingClientRect"]();
                const itemRect = selectedItem.getBoundingClientRect();
                // Si el ítem esta por debajo del área visible del listado, se desplaza el scroll una posición hacia abajo según el alto del ítem
                if (itemRect.bottom > containerRect.bottom) {
                    listContainer["scrollTop"] += itemRect.bottom - containerRect.bottom;
                }
            });
        }

        /**
         * Obtiene el evento hover del cursor sobre los ítems de listado <li>
         *
         * @param index posición del cursor en el ítem
         */
        public selectItemOnHover(index) {
            this.selectedIndex = index;
        }

        /**
         * Obtiene el evento del teclado de flecha hacia arriba
         *
         * @param event
         */
        public preventCursorMove(event) {
            if (event.key === 'ArrowUp') {
                event.preventDefault(); // Prevenir que no posicione el cursor al inicio del input
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
            this.isLoadingOnScroll = false;
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
            // Indica si se seleccionó un valor del listado de coincidencias del autocomplete
            this.valorSeleccionado = true;
            // Valida si requiere el nombre de la seleccion
            if (this.nameProp) {
                this.query = this.AditionalNameProp ? dataSelected[this.nameProp] + " - " + dataSelected[this.AditionalNameProp].slice(0, 50) : dataSelected[this.nameProp];
            }
            // Valida si obtiene los datos de formulario
            if (this.value) {
                this.$set(this.value, this.nameField, dataSelected[this.reduceKey]);
                // Si existe la propiedad nameFieldObject, se le asigna a esa variabla todo el objeto seleccionado
                this.nameFieldObject ? this.$set(this.value, this.nameFieldObject, dataSelected) : null;
            }
                    this.$forceUpdate();

            // Actualiza todas las variables del componente 

            if (this.fieldsChangeValues) {

                this.fieldsChangeValues.forEach((name: string) => {

                    const nameSplit = name.split(':');
                    if (nameSplit.length > 1) {

                        let nameFieldPer = nameSplit[1];
                        this.$set(this.value, nameSplit[0], dataSelected[nameFieldPer]);

                    }
                });

            }
            // Limpia la lista de conicidencias
            this.matchList = [];
            // Envia coincidencia seleccionada
            this.matchSelected(dataSelected);


            // Valida si se devuelve el valor cambiado a una funcion anonima (Callback)
            if (this.functionChange) {
                this.functionChange(dataSelected);
            }

            // Valida si existe un elemento dependiente para limpiar su valor
            this.idsToEmpty?.forEach(element => {
                // Se consulta el tipo de variable a vaciar
                let tipo_variable = typeof this.value[element];
                if(tipo_variable == 'object') {
                    this.$set(this.value, element, {});
                } else {
                    this.$set(this.value, element, '');
                }
            });
            // this.$parent.$forceUpdate();


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
         * Se encarga de manejar el evento blur (desenfoque) del autocompletar.
         *
         * @author Seven Soluciones Informáticas S.A.S. - Ago. 13 - 2024
         * @version 1.0.0
         */
        public handleBlur() {
            // Verifica si el valor en el campo especificado es falsy o si la consulta es falsy
            if ((!this.value[this.nameField] || !this.query) && this.activarBlur) {
                // Si la condición es verdadera, establece la consulta en una cadena vacía
                this.query = "";
                // Establece el campo especificado (this.nameField) a null en this.value
                this.$set(this.value, this.nameField, null);
            }
        }

        /**
         * Se encarga de manejar el evento keydown del autocompletar.
         *
         * @author Seven Soluciones Informáticas S.A.S. - Abr. 04 - 2025
         * @version 1.0.0
         */
        public handleKeyEvents(event: KeyboardEvent): void {
            // Obtiene la tecla presionada por el usuario
            switch (event.key) {
                // Si la tecla es la de hacia abajo, se valida si ya existe un listado de opciones y que la posición del ítem seleccionado no sea el último
                case "ArrowDown":
                    if (!this.matchList.length || this.matchList.length-1 == this.selectedIndex) return; // Sin opciones para navegar, no se deplaza el ítem ni el cursor
                        this.selectedIndex = this.selectedIndex+1;
                    // Invoca el método para desplazar el scroll hacia abajo
                    this.scrollDownItem();
                    break;
                // Si la tecla es la de hacia arriba, se valida si ya existe un listado de opciones y que la posición del ítem seleccionado no sea el primero
                case "ArrowUp":
                    if (!this.matchList.length || this.selectedIndex <= 0) return; // Sin opciones para navegar, no se deplaza el ítem ni el cursor
                        this.selectedIndex = this.selectedIndex-1;
                    // Invoca el método para desplazar el scroll hacia arriba
                    this.scrollUpItem();
                    break;
                case "Enter":
                    if (this.selectedIndex !== -1) {
                        this._selectMatch(this.matchList[this.selectedIndex]);
                    }
                    break;
            }
        }
    }
</script>
