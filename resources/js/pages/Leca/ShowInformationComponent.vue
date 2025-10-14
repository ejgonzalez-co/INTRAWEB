<template>
  <div class="m-1">
    <h4 class="text-center m-3">{{ titulo }}</h4>
    <div class="mt-2">
      <div
        class="form-group row m-b-15"
        v-for="(ite, key) in nameLabels"
        :key="key"
      >
        <!-- label -->

        <label class="col-form-label col-md-3"
          ><b>{{ ite }}:</b></label
        >

        <div class="col-md-9" v-if="types[key] == 'label'">
          <!-- respuesta  -->
          <label class="col-form-label" v-if="matchList[items[key]]">{{
            matchList[items[key]]
          }}</label>
          <label class="col-form-label" v-else>No aplica</label>
        </div>
        <div class="col-md-9" v-if="types[key] == 'textarea'">
          <br>
          <br>
          <!-- respuesta  -->
          <p readonly class="col-form-label m-3" v-if="matchList[items[key]]">
            {{ matchList[items[key]] }}
          </p>
          <label class="col-form-label" v-else>No aplica</label>
        </div>
        <div class="col-md-9" v-if="types[key] == 'currency'">
          <div v-if="matchList[items[key]]">
            <!-- respuesta  -->
            <div v-if="prefix">
              <currency-input
                :v-model="nameField"
                required="true"
                :currency="{ prefix: prefix }"
                locale="es"
                :value="matchList[items[key]]"
                readonly="true"
                :precision="2"
                class="form-control"
                :key="result"
              >
              </currency-input>
            </div>
            <div v-if="suffix">
              <currency-input
                :v-model="nameField"
                required="true"
                :currency="{ suffix: suffix }"
                locale="es"
                :value="matchList[items[key]]"
                readonly="true"
                :precision="2"
                class="form-control"
                :key="result"
              >
              </currency-input>
            </div>
          </div>
          <label class="col-form-label" v-else>No aplica</label>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Ingresa un dato que se llama de la abse de datos
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
 * @version 1.0.0
 */
@Component
export default class ShowInformationComponent extends Vue {
  /**
   * Nombre de la ruta a obtener
   */
  @Prop({ type: String, required: true })
  public nameResource: string;

  /**
   * Lista con los nombres de los datos que se van a mostrar
   *
   */
  @Prop({ type: Array, required: true })
  public items: Array<string>;

  /**
   * Lista con los nombres de los labels
   *
   */
  @Prop({ type: Array, required: true })
  public nameLabels: Array<string>;

  /**donde se guarda el prefijo */
  @Prop({ type: String })
  public prefix: String;

  /**donde se guarda el sufijo */
  @Prop({ type: String })
  public suffix: String;

  /**donde se guarda el prefijo */
  @Prop({ type: String })
  public titulo: String;

  /**
   * Lista con los nombres de los tipos de mostrat
   *
   */
  @Prop({ type: Array, required: true })
  public types: Array<string>;

  /**
   * Lista de coincidencias
   */
  public matchList: Array<any>;

  /**
   * Lista de coincidencias
   */
  public total: Array<any> = [];

  /**
   * Constructor de la clase
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  constructor() {
    super();
    this.matchList = [];

    this.total = [];
  }

  mounted() {
    this.getPetition();
  }
  /**
   * envia los datos del al servidor y confirma con un sweeralert2
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
   * @version 1.0.0
   */
  public getPetition(): void {
    axios
      .get(this.nameResource)
      .then((res) => {
        // Llena la lista de datos
        this.matchList = res.data.data;
        this.confArray();
      })
      .catch((error) => {
        console.log("Error al obtener la lista.");
      });
  }

  /**
   * envia los datos del al servidor y confirma con un sweeralert2
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 17 - 2021
   * @version 1.0.0
   */
  public confArray() {
    //   console.log(this.matchList);

    this.total.push(this.matchList);
    this.total.push(this.items);
    this.total.push(this.nameLabels);
    this.total.push(this.types);

  }
}
</script>