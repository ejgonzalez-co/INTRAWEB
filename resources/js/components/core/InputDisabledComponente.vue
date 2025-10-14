<template>
    <div style="width: -webkit-fill-available;">
      <input
        v-if="!isLoading"
        class="form-control"
        type="text"
        v-model="value[nameField]"
        :readonly="isDisabled"
      />
      <div class="skeleton skeleton-input" v-else></div>
    </div>
  </template>
  <script lang="ts">
  import { Component, Prop, Vue } from "vue-property-decorator";
  
  import { jwtDecode } from "jwt-decode";
  import axios from "axios";
  
  /**
   * Componente para consultar un dato y darle el valor al input
   *
   * @author Kleverman Salazar Florez. - Oct. 25 - 2022
   * @version 1.0.0
   */
  @Component
  export default class InputDisabledComponent extends Vue {
    /**
     * Nombre del recurso
     */
    @Prop({ type: String, required: true })
    public nameResource: string;
  
    /**
     * Nombre del campo
     */
    @Prop({ type: String, required: true })
    public nameField: string;
  
    /**
     * Valor del campo
     */
     @Prop({ type: Object, required: true })
    public value: any;
  
    @Prop({ type: String })
    public nameProp: string;

    @Prop({ type: Boolean, default: true })
    public isDisabled: boolean;

    public isLoading: boolean;
  
    /**
     * Constructor de la clase
     *
     * @author Kleverman Salzar Florez. - Oct. 25 - 2022
     * @version 1.0.0
     */
    constructor() {
      super();
      this.isLoading = true;
      this.consultInformation();
    }
  
    /**
     * Abre el modal de confirmacion para el envio de los datos
     *
     * @author Kleverman Salazar Florez. - Sep. 22 - 2022
     * @version 1.0.0
     */
    public consultInformation(): void {
      axios
        .get(`${this.nameResource}`)
        .then((res) => {
          res.data.data = res.data.data ? jwtDecode(res.data.data) : null;

          if(this.nameProp){
            this.value[this.nameField] = res.data.data.data[this.nameProp] ? res.data.data.data[this.nameProp] : 'N/A';
  
          }else{
            this.value[this.nameField] = res.data.data.data ? res.data.data.data : 'N/A';
          }
          // Actualiza el componente para que actualicé su value
          this.$forceUpdate();
        })
        .catch((err) => {
          console.error(`Error: ${err}`);
          this.value[this.nameField] = "";
        })
        .finally(() => this.isLoading = false);
    }
  }
  </script>
  