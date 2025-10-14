<template>
    <div class="col-md-6 offset-md-3">
          <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#recordarCorreoWrap" aria-expanded="true" aria-controls="recordarCorreoWrap" style="margin-bottom: 15px;">
          <i class="fas fa-hand-point-right"></i> Consultar correo registrado
          </button>

        <div id="recordarCorreoWrap" class="collapse in">
          <div class="panel panel-default">
              <div class="panel-heading bg-white">
              <h5 class="panel-title text-center" style="margin:0; padding:0 10px;">
                  Recordar correo electrónico del usuario.
              </h5>
              </div>
              <div class="panel-body">
              <p>Para recuperar el correo electrónico asociado a su cuenta, ingrese el número de identificación con el que creó el usuario en Intraweb. Si presenta algún inconveniente, comuníquese con el área de soporte técnico: {{ this.mailSupport }}</p>

                  <div class="form-group">
                  <label for="numero_identificacion" class="control-label">Número de identificación:</label>
                  <br>
                  <input type="number" id="numero_identificacion" v-model="dataForm.numero_identificacion" name="numero_identificacion" class="form-control" placeholder="Ingrese su numero de identificación con el cual se registró en Intraweb" required>
                  </div>

                  <div class="form-group">
                  <label for="numero_identificacion" class="control-label">Correo asociado:</label>
                  <br>
                  <input type="text" id="correo_ciudadano" v-model="dataForm.correo_ciudadano" name="correo_ciudadano" class="form-control" disabled>
                  </div>                                    

                  <div class="form-group">
                  </div>
                  <button type="button" class="btn btn-primary pull-right" @click="consultInformation()">
                      Buscar correo por número de identificación
                  </button>
              </div>
          </div>

      </div>


  </div>
  </template>
  <script lang="ts">
  import { Component, Prop, Vue } from "vue-property-decorator";
  
  import { jwtDecode } from "jwt-decode";
  import axios from "axios";
  
  /**
   * Componente para consultar un dato y darle el valor al input
   *
   * @author Johan David Velasco Rios. - Sep. 09 - 2025
   * @version 1.0.0
   */
  @Component
  export default class RememberMailCitizen extends Vue {

    /**
     * Nombre del recurso
     */
    @Prop({ type: String, required: true })
    public nameResource: string;

    /**
     * correo electronico del soporte tecnico
     */
    @Prop({ type: String, default:'soporte@seven.com.co' })
    public mailSupport: string;
  
    /**
     * Mensaje de error a mostrar
     */
    @Prop({ type: String, default: '' })
    public messageError: string;

    public isLoading: boolean;

    public dataForm: any; 
  
      /**
       * Constructor de la clase
       *
       * @author Johan David Velasco Rios. - Sep. 09 - 2025
       * @version 1.0.0
       */
      constructor() {
        super();
        this.isLoading = true;
        this.dataForm = {};
      }

    /**
     * Consulta los datos del ciudadano
     *
     * @author Johan David Velasco Rios. - Sep. 09 - 2025
     * @version 1.0.0
     */
      public consultInformation() {

        if (!this.dataForm.numero_identificacion) {
          this.$swal({
            icon: "warning",
            title: "Número de identificación requerido",
            text: "Por favor ingrese su número de identificación antes de continuar."
          });
          return;
        }

        this.isLoading = true;

        this.$swal({
            html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>Buscando ciudadano...</span>',
            showCancelButton: false,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });


        const encodedDocument = btoa(this.dataForm.numero_identificacion.toString());

        // 2) Hace la petición con la URL resultante
        axios
          .get(`${this.nameResource}/${encodeURIComponent(encodedDocument)}`)
          
          .then((res) => {

          (this.$swal as any).close();

            res.data.data = res.data.data ? jwtDecode(res.data.data) : null;

            this.$set(this.dataForm, 'correo_ciudadano', res.data.data.data.email);
            // this.$forceUpdate(); // mantiene tu comportamiento actual
          })
          .catch((err) => {
            
            (this.$swal as any).close();
            this.$set(this.dataForm, 'correo_ciudadano', this.messageError == '' ? "" : this.messageError);
          })
          .finally(() => (this.isLoading = false));
      }
      
  }
  </script>
  