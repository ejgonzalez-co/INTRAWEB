<template>
  <div>
    <!-- En este modal se ve el historial del registro -->
    <div class="modal fade" id="modal-aproved-request">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">Información de la solicitud</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-hidden="true"
            >
              <i class="fa fa-times text-white"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="panel" style="border: 200px; padding: 15px">
              <div class="row">
                <label><b>Nombre del solicitante: &nbsp; &nbsp;</b></label>
                <label>
                  {{ dataRequest.users ? dataRequest.users.name : "" }} &nbsp;-
                  &nbsp;
                  {{
                    dataRequest.users
                      ? dataRequest.users.dependencies.nombre
                      : ""
                  }}
                </label>
              </div>
              <div class="row">
                <label
                  ><b
                    >Nombre del usuario que va ser consultado: &nbsp; &nbsp;</b
                  ></label
                >
                <label> {{ dataRequest ? dataRequest.user_name : "" }} </label>
              </div>
              <div class="row">
                <label
                  ><b
                    >Tiempo solicitado para la consulta: &nbsp; &nbsp;</b
                  ></label
                >
                <label>
                  {{ dataRequest ? dataRequest.consultation_time : "" }}
                </label>
              </div>
              <div class="row">
                <label><b>Razón de la consulta: &nbsp; &nbsp; </b></label>
                <label>
                  {{ dataRequest ? dataRequest.reason_consultation : "" }}
                </label>
              </div>
            </div>

            <div class="row justify-content-center">
              <button class="btn btn-red m-5" @click="denyRequest()">
                <i class="fa fa-minus mr-2"></i>Negar
              </button>
              <button class="btn btn-green m-5" @click="acceptRequest()">
                <i class="fa fa-check mr-2"></i>Autorizar
              </button>
              <button class="btn btn-white m-5" data-dismiss="modal">
                <i class="fa fa-times mr-2"></i>Cerrar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- end #modal-view-titleRegistrations -->

    <!-- En este modal se cancela la solicitud -->
    <div class="modal fade" id="modal-deny-request">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">Cancelación de solicitud</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-hidden="true"
            >
              <i class="fa fa-times text-white"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row justify-content-center">
              <div class="row">
                <div class="col">
                  <label class="m-5">Motivo de negación</label>
                </div>
                <div class="col">
                  <textarea
                    class="m-5"
                    name="answer"
                    v-model="answer"
                    rows="10"
                    cols="40"
                  ></textarea>
                  <small>Ingrese el motivo de la negación</small>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-white" @click="denyRequestSave()">
              <i class="fa fa-save mr-2"></i>Guardar
            </a>
            <button class="btn btn-white" data-dismiss="modal">
              <i class="fa fa-times mr-2"></i>Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- end #modal-view-titleRegistrations -->

    <!-- En este modal se cancela la solicitud -->
    <div class="modal fade" id="modal-accept-request">
      <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">Autorización de solicitud</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-hidden="true"
            >
              <i class="fa fa-times text-white"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="row justify-content-center">
              <div class="row">
                <div class="col">
                    <label class="m-5">Fecha de inicio:</label>
                </div>
                <div class="col">
                    <input type="datetime-local" v-model="date_start">
                </div>
              </div>
              <div class="row">
                <div class="col">
                    <label class="m-5">Fecha de final:</label>
                </div>
                <div class="col">
                    <input type="datetime-local" v-model="date_final">
                </div>
              </div>
              <div class="row mt-5">
                <div class="col">
                  <label class="m-5">Observación</label>
                </div>
                <div class="col">
                  <textarea
                    class="m-5"
                    name="answer"
                    v-model="answer"
                    rows="10"
                    cols="40"
                  ></textarea>
                  <small>Ingrese una Observación</small>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-white" @click="acceptRequestSave()">
              <i class="fa fa-save mr-2"></i>Guardar
            </a>
            <button class="btn btn-white" data-dismiss="modal">
              <i class="fa fa-times mr-2"></i>Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- end #modal-view-titleRegistrations -->
  </div>
</template>

<script lang="ts">
import { Prop, Component, Vue } from "vue-property-decorator";
import axios from "axios";
import * as bootstrap from "bootstrap";
import { Locale } from "v-calendar";
import { exit } from "process";

const locale = new Locale();

/**
 * Componente que permite negar o aceptar en un modal
 *
 *
 * @author Nicolas Dario Ortiz Peña. - Oct. 21 - 2021
 * @version 1.0.0
 *
 *
 *
 */
@Component
export default class Approve extends Vue {
    //obtiene el arreglo
  public dataRequest: any;
  //Respuesta
  public answer: string;
  //fecha inicial
  public date_start: string;
  //fecha final
  public date_final: string;

  constructor() {
    super();
    //obtiene el arreglo
    this.dataRequest = {};
    //Respuesta
    this.answer = null;
    //fecha inicial
    this.date_start = null;
    //fecha final
    this.date_final = null;
  }

  /**
   * Abre la modal e inicializa el datarequest
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  public approve(idDocument): void {
      
    this.dataRequest = idDocument;
    
    $("#modal-aproved-request").modal("show");
  }

  /**
   * Niega la solicitud
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  public denyRequest(): void {
    $("#modal-aproved-request").modal("hide");

    $("#modal-deny-request").modal("show");
  }

  /**
   * acepta la solicitud
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  public acceptRequest(): void {
    $("#modal-aproved-request").modal("hide");

    $("#modal-accept-request").modal("show");
  }

  /**
   * cancela la solicitud
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  public denyRequestSave(): void {
    if (this.answer != null) {
      axios
        .post(
          `get-cancel-request`,
          {
            data: this.dataRequest,
            answer: this.answer,
          },
          { responseType: "blob" }
        )
        .then((res) => {
          //LLama un elemento del crud component
          (this.$parent as any)._getDataList();
        })
        .catch((err) => {});
      //Vuelve y inicializa las variables
      this.answer = null;
      this.dataRequest = {};
      $("#modal-deny-request").modal("hide");
    } else {
      // Abre el swal de guardando datos
      this.$swal({
        icon: "error",
        title: "Debe llenar el campo",
        text: "El campo del motivo de la negación esta vacio",
        confirmButtonText: "Aceptar",
      });
    }
  }

  /**
   * acepta la solicitud
   *
   * @author Nicolas Dario Ortiz Peña. - Agosto. 03 - 2021
   * @version 1.0.0
   */
  public acceptRequestSave(): void {
    //Verifica que los campos no esten vacios
    if (this.answer != null && this.date_final !=null && this.date_start != null) {
      axios
        .post(
          `get-approbed-request`,
          {
            data: this.dataRequest,
            answer: this.answer,
            date_start:this.date_start,
            date_final:this.date_final
          },
        )
        .then((res) => {
          //Si retorna error  es porque la fecha de inicio y la fecha final estan mal 
          if(res.data.data=='error'){
            // Abre el swal de guardando datos
            this.$swal({
              icon: "error",
              title: "Error en los datos ! ",
              text: "La fecha de cierre tiene que ser mayor que la fecha de inicio",
              confirmButtonText: "Aceptar",
            });
          }else{
          (this.$parent as any)._getDataList();
          }
        })
        .catch((err) => {});
      //Vuelve y pone los campos en null
      this.answer = null;
      this.date_start = null;
      this.date_final = null;
      this.dataRequest = {};
      $("#modal-accept-request").modal("hide");
    } else {
      // Abre el swal de guardando datos
      this.$swal({
        icon: "error",
        title: "Hay campos vacios",
        text: "Todos los campos deben estar llenos",
        confirmButtonText: "Aceptar",
      });
    }
  }
}
</script>
