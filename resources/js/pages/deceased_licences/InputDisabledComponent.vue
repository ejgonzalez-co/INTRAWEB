<template>
  <div style="width: -webkit-fill-available;">
    <input
      class="form-control"
      type="text"
      v-model="value[nameField]"
      readonly
    />
  </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from "jwt-decode";

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

  /**
   * Constructor de la clase
   *
   * @author Kleverman Salzar Florez. - Oct. 25 - 2022
   * @version 1.0.0
   */
  constructor() {
    super();
    this.consultInformation();
  }

  /**
   * Abre el modal de confirmacion para el envio de los datos
   *
   * @author Kleverman Salazar Florez. - Sep. 22 - 2022
   * @version 1.0.0
   */
  public consultInformation(): void {
    // Verifica que la variable this.nameResource NO contenga las cadenas "undefined" ni "null" en su valor, para no ejecutarla
    if(this.nameResource.indexOf("undefined") <= 0 && this.nameResource.indexOf("null") <= 0){  
      axios
        .get(`${this.nameResource}`)
        .then((res) => {
          // Si existe res.data.data, se decodifica con jwtDecode, de lo contrario se asigna null.
          let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
          // Crea un nuevo objeto combinando {data: []} con dataPayload. 
          const dataDecrypted = Object.assign({data:[]}, dataPayload);
          if(this.nameProp){
            this.value[this.nameField] = dataDecrypted?.data[this.nameProp];

          }else{
            this.value[this.nameField] = dataDecrypted?.data;
          }
          // Actualiza el componente para que actualicé su value
          this.$forceUpdate();
        })
        .catch((err) => {
          console.error(`Error: ${err}`);
          this.value[this.nameField] = "";
        });
    }
  }
}
</script>
