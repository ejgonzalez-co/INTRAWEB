<template>
   <div>
      <multiselect
         v-model='value[nameField]'
         :options="optionsList"
         :clear-on-select="clearOnSelect"
         :close-on-select="closeOnSelect"
         :deselect-label="deselectLabel"
         :deselect-group-label="deselectGroupLabel"
         :group-label="groupLabel"
         :group-values="groupValues"
         :group-select="isGroupSelect"
         :label="reduceLabel"
         :max="maxSelected"
         :multiple="isMultiple"
         :placeholder="placeholder"
         :preselect-first="preselectFirst"
         :preserve-search="preserveSearch"
         :select-group-label="selectGroupLabel"
         :select-label="selectLabel"
         :selected-label="selectedLabel"
         :searchable="searchable"
         :required="isRequired"
         :track-by="reduceKey"
         @input="selectChange"
         >
            <template slot="tag">{{ '' }}</template>
            <template
               slot="selection"
               slot-scope="{ values, search, isOpen }">
               <span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} Opciones seleccionadas</span>
            </template>
            <template slot="noOptions">{{ 'La lista esta vacía' }}</template>
            <template slot="noResult">{{ 'No se encontraron elementos.' }}</template>
            <template slot="maxElements">Máximo de opciones  {{ maxSelected }} seleccionadas </template>
      </multiselect>
   </div>
</template>
<script lang="ts">

   import { Component, Prop, Vue } from "vue-property-decorator";

   import axios from "axios";
   import { jwtDecode } from 'jwt-decode';

   /**
    * Componente de select
    * https://vue-multiselect.js.org
    *
    * @author Carlos Moises Garcia T. - Abri. 15 - 2021
    * @version 1.0.0
    */
   @Component
   export default class MultiselectComponent extends Vue {

      /**
       * Valida si se limpia el select luego de seleccionar
       */
      @Prop({ type: Boolean, default: false})
      public clearOnSelect: boolean;

      /**
       * Valida si se cierra el select luego de seleccionar
       */
      @Prop({ type: Boolean, default: true})
      public closeOnSelect: boolean;

      /**
       * Lista de opciones con el data personalizado
       */
      @Prop()
      public customData: Array<any>;

      /**
       * Texto de ayuda para remover una opcion
       */
      @Prop({ type: String, default: 'Presione clic para eliminar' })
      public deselectLabel: string;

      /**
       * Texto de ayuda para remover las opciones de un grupo
       */
      @Prop({ type: String, default: 'Presione clic para deseleccionar las opciones' })
      public deselectGroupLabel: string;

      /**
       * Valida si el campo es un grupo de selecciones
       */
      @Prop({ type: Boolean, default: false})
      public isGroupSelect: boolean;

      /**
       * Valida si el campo es multiple
       */
      @Prop({ type: Boolean, default: false})
      public isMultiple: boolean;

      /**
       * Valida si el campo es requerido
       */
      @Prop({ type: Boolean, default: false})
      public isRequired: boolean;

      /**
       * Nombre del label que contiene el grupo de opciones
       */
      @Prop({ type: String, default: null })
      public groupLabel: string;

      /**
       * Nombre del objecto que contiene el grupo de opciones
       */
      @Prop({ type: String, default: null })
      public groupValues: string;

      /**
       * Nombre del campo
       */
      @Prop({type: String, required: true })
      public nameField: string;

      /**
       * Nombre de la entidad a obtener
       */
      @Prop({ type: String })
      public nameResource: string;

      /**
       * Numero maximo de opciones permitas para seleccionar
       */
      @Prop({ type: Number, default: null })
      public maxSelected: number;

      /**
       * Texto provisional de select
       */
      @Prop({ type: String, default: 'Seleccione' })
      public placeholder: string;

      /**
       * Selecciona la primera opcion por defecto
       */
      @Prop({ type: Boolean, default: false})
      public preselectFirst : boolean;

      /**
       * Valida si se conserva los datos al buscar en el input
       */
      @Prop({ type: Boolean, default: false})
      public preserveSearch : boolean;

      /**
       * Nombre de valor visualizacion
       */
      @Prop({ type: String, default: 'name'})
      public reduceLabel: string;

      /**
       * Valor de keyModel de reduccion
       */
      @Prop({ type: String, default: 'id'})
      public reduceKey: string;

      /**
       * Texto de ayuda para indicar que seleccione las opciones
       */
      @Prop({ type: String, default: 'Presione clic para seleccionar el grupo' })
      public selectGroupLabel: string;

      /**
       * Texto de ayuda para indicar que seleccione una opcion
       */
      @Prop({ type: String, default: null })
      public selectLabel: string;

      /**
       * Texto de al lado de la opcion cuando esta seleccionada
       */
      @Prop({ type: String, default: 'Seleccionado' })
      public selectedLabel: string;

      /**
       * Valida si se habilita el buscar en el input
       */
      @Prop({ type: Boolean, default: true})
      public searchable : boolean;

      /**
       * Valor del campo
       */
      @Prop()
      public value: Array<any>;

      /**
       * Lista de opciones
       */
      public optionsList: Array<any>;

      @Prop()
      public keyModel: string;

      /**
       * Constructor de la clase
       *
       * @author Carlos Moises Garcia T. - Abri. 15 - 2021
       * @version 1.0.0
       */
      constructor() {
         super();
         this.optionsList = [];
      }

      /**
       * Se ejecuta cuando el componente ha sido momntado
       */
      mounted() {
         // Carga la lista de elementos
         this._getDataOptions();
      }

      public selectChange(event): void {
         // Valida si el select es de tipo multiple
         if (this.isMultiple) {
            this.value[this.nameField] = this.value[this.nameField].sort((a, b) => (a.id > b.id) ? 1 : -1);
            this.$forceUpdate();
         }
      }

      /**
      * Obtiene la lista de opciones
      *
      * @author Jhoan Sebastian Chilito S. - May. 16 - 2020
      * @version 1.0.0
      */
      private _getDataOptions(): void {
         // Valida si los datos de las opciones vienen por parametro
         if (this.customData) {
            // Llena la lista de datos
            this.optionsList = this.customData;
         } else {
            // Envia peticion de obtener todos los datos del recurso
            axios.get(this.nameResource)
            .then((res) => {
               let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

               const dataDecrypted = Object.assign({data:[]}, dataPayload);

               let data = dataDecrypted?.data;

               // Valida si tiene elementos asignados
               if (this.value[this.nameField]) {
                  let dataModel = this.value[this.nameField];

                  // Se eliminan los datos que tiene el modelo para asignales los seleccionados por el usuario
                  this.value[this.nameField] = [];

                  data.forEach((item) => {
                     for (let index = 0; index < dataModel.length; index++) {
                        // Valida si el elimento es el mismo que esta asignado
                        if (item[this.reduceKey] == dataModel[index][this.keyModel]) {
                           this.value[this.nameField].push(item);
                        }
                     }
                  });
               }
               // Llena la lista de datos
               this.optionsList = data;
            })
            .catch((err) => {
               console.log('Error al obtener la lista. '+err);
            });
         }
      }
   }
</script>
