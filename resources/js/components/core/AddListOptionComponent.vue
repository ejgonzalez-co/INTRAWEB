<template>
<div>

    <div class="input-group m-b-10">
        <label for="title" class="col-form-label col-md-4 required">{{textLabel}}</label> 
        <div class="col-md-8">
            <div class="input-group">
                <input v-model="textEntered" type="text" class="form-control">
                <div class="input-group-append" v-if="textEntered.length">
                    <button class="btn btn-outline-primary btn-primary" type="button"  @click="addList(textEntered)">Agregar a lista</button>
                </div>
            </div>
            <small>{{ help }}</small>
        </div>
    </div> 
 
    <select multiple class="form-control m-t-30" @keyup.46="removeElement(selected)" v-model="selected">
        <option v-for="option in value[nameOptionsList]" :key="option" :value="option">{{ option }}</option>
    </select>
    
</div>
</template>

<script lang="ts">
    import { Component, Prop,Watch, Vue } from "vue-property-decorator";
    /**
     * Componente para añadir a la lista desde un autocompletar
     *
     * @author Erika Johana Gonzalez C. - Jul. 20 - 2020
     * @version 1.0.0
     */
    @Component
    export default class AddListOptionComponent extends Vue {

        /**
         * Texto ingresado a filtrar
         */
        public textEntered: string;

        /**
         * Nombre del campo
         */
        @Prop({ type: String, required: true })
        public nameField: string;


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
         * Lista de opciones que se añanden al select multiple de manera temporal
         */
        public optionsListTemporal: Array<any> = [];

        /**
         * Lista de opciones que se añanden al select multiple de manera temporal
         * y solo almacena los ids
         */
        public idsListNameField: Array<any> = [];

        /**
         * Nombre del campo que contiene el valor donde se guardara los option de la lista
         */
        @Prop({ type: String, required: true })
        public nameOptionsList: string;

        
        public selected: Array<string> = [];

        /**
         * Texto de ayuda
         */
        @Prop({ type: String})
        public help: string;

        /**
         * Texto del label
         */
        @Prop({ type: String})
        public textLabel: string;


         /**
         * Valor de llave de reduccion
         */
        @Prop({ type: String, default: 'id'})
        public reduceKey: string;

        /**
         * Constructor de la clase
         *
         * @author Erika Johana Gonzalez.  Oct. 08 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
            this.textEntered = '';

            // Valida si tiene elementos asignados
            if (this.value[this.nameField]) {
                // Filtra los datos de seleccion para evidenciar los option seleccionados
                const valueSelected: number | string = this.value[this.nameField].map((value) => value[this.reduceKey]);
                // Asigna los datos seleccionados
                this.value[this.nameField] = valueSelected;
                this.value[this.nameOptionsList] = valueSelected;

                this.idsListNameField = this.value[this.nameField];
            } else {
                // Se inicializa
                this.value[this.nameField] = [];
            }
        }

        /**
         * Añade a lista desde el input
         * Esta funcion se ejecuta cuando se hace clic en el boton agregar a la lista
         * @author Erika - Abr. 15 - 2020
         * @version 1.0.0
         *
         * @param elementSeleccted elemento seleccionado
         */
        public addList(elementSeleccted:any): void {

           /*
            * Valida si la lista tiene valor, esto se debe hacer porque cuando edita el registro la lista debe tener 
            * en cuenta lo que trae el campo de la BD 
            */
           if (this.value[this.nameOptionsList]) {
                // Valida el tamaño
                if (this.value[this.nameOptionsList].length!=0) {
                    this.optionsListTemporal = this.value[this.nameOptionsList];
                }
           }
          
            // Verifica la propiedad específica de un objeto, ya sea que exista en su matriz o no
            let index = this.optionsListTemporal.findIndex(x => x==elementSeleccted)
            // Condicion para validar si el elemento a agregar ya existe en la lista
            if (index === -1) {
              
                this.optionsListTemporal.push(elementSeleccted);
            }
            else {
                // Datos de notificacion
                const toastOptions = {
                    closeButton: true,
                    closeMethod: 'fadeOut',
                    timeOut: 2000,
                    tapToDismiss: false
                };
                toastOptions.timeOut = 0;
                // Visualiza toast negativo
                toastr.warning("El elemento ya existe en la lista.", "Advertencia", toastOptions)

            }
            // Se agrega objeto al dataform
            this.$set(this.value, this.nameOptionsList, this.optionsListTemporal);
            this.textEntered='';
            // Limpia optionsListTemporal
            this.optionsListTemporal=[];

        }

        /**
         * Elimina de la lista un elemento
         *
         * @author Erika Johana Gonzalez C. - Jul. 20 - 2020
         * @version 1.0.0
         * 
         * @param optionsSelected lista de opciones selecciondas
         * @param keyEvent evento de la tecla que se oprime
         */
        public removeElement(optionsSelected: any): void {
        
                //Recorre los elementos seleccionados, ya que pueden ser letios al utilizar Ctrl
                optionsSelected.forEach((element)=> {
                    // Encuentra la posición del elemento dentro de la lista 
                    // Esto se hace porque para eliminar un elemento de un lista debe ser con el index.
                    
                    let index = this.value[this.nameOptionsList].findIndex(x => x==element)
                   // alert(element+" - "+index);
                    if (index > -1) {
                        // Quita el elemento de la lista
                        this.value[this.nameOptionsList].splice(index, 1);
                        // Actualiza todas las variables del componente 
                        this.$forceUpdate();

                    }
                });
            
        }


    }

</script>
