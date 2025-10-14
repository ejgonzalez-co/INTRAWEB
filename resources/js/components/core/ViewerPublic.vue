<template>
  <div class="container">

    <div id="validar" v-if="accionVerContenedor === 'validarCodigo' || accionVerContenedor === ''">
      <div class="form-group row m-b-15">
        <h1>Código de acceso al documento:</h1>
        <div class="col-md-9">
          <input type="text" class="form-control" v-model="codigoAccesoDocumento" required>
          <small class="form-text text-muted">Por favor, introduzca el código de acceso recibido por correo electrónico para ver el documento.</small>

        </div>
      </div>

      <button @click="validarcodigo" class="btn btn-primary">
        <i class="fas fa-check"></i> Validar Código
      </button>

    </div>

    <div id="verContenedorAdjuntos" v-if="accionVerContenedor === 'verContenedorAdjuntos'">
      <h3>Detalle de radicación: {{ consecutivo }}</h3>
      <h3>Asunto: {{ titulo }}</h3>

      <p>Listado de documentos principales de la correspondencia:</p>
      <viewer-attachement :link-file-name="true" :list="urlDocuments" :key="urlDocuments" :name="consecutivo"></viewer-attachement>
      <h3>Listado de anexos: {{ anexosDescripcion }}</h3>
      <p>Haga clic en el anexo que desea visualizar:</p>
      <viewer-attachement :link-file-name="true" :list="anexos" :key="anexos" name="Anexo"></viewer-attachement>
    </div>

  </div>
</template>


<script lang="ts">
import axios from 'axios';
import {jwtDecode} from 'jwt-decode';

export default {
  name: 'ViewerPublic',
  props: {
    // Propiedad para la URL del documento
    executeUrlAxiosValidar: {
      type: String,
      required: true
    },
    
  },
  created(){
    const nombre = this.getParameterByName('c');

    this.codigoAccesoDocumento = jwtDecode(nombre)["data"];
    
      if(this.codigoAccesoDocumento) {

          try {
            this.showLoadingGif("Validando codigo");

            const formData = new FormData();
            formData.append('codigoAccesoDocumento', this.codigoAccesoDocumento);

            axios.post(this.executeUrlAxiosValidar, formData, {
              headers: {
                'Content-Type': 'multipart/form-data' // Opcional si no está definido por defecto
              }
            })
              .then((res) => {
                (this.$swal as any).close();

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                if(dataPayload['data']['success']) {
                        // Asignación de valores con el operador ??
                        this.urlDocuments = dataPayload['data']['rutapdf'] ?? '';
                        this.consecutivo = dataPayload['data']['consecutivo'] ?? 'No aplica';
                        this.anexosDescripcion = dataPayload['data']['anexosDescripcion'] ?? 'No aplica';
                        this.anexos = dataPayload['data']['rutaanexos'] ?? '';
                        this.titulo = dataPayload['data']['title'] ?? 'No aplica';

                        
                        this.accionVerContenedor = 'verContenedorAdjuntos';
                } else {

                    (this.$swal as any).close();

                    this.$swal({
                        icon: 'info',
                        title: 'Código ingresado incorrecto.',
                        text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    });
                }

            })
            .catch((error) => {
                (this.$swal as any).close();

                console.error('Error');
                console.error(error.response.data);

                  this.$swal({
                        icon: 'info',
                        title: 'Código ingresado incorrecto.',
                        text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    });
            });


          } catch (error) {
            (this.$swal as any).close();

            console.error("Error", error);
            // Maneja el error si es necesario
            this.$swal({
                    icon: 'info',
                    title: 'Código ingresado incorrecto.',
                    text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                    confirmButtonText: "Aceptar",
                    allowOutsideClick: false,
                });
          }
      }else{

        this.$swal({
          icon: 'info',
          title: 'Ingrese un código de acceso.',
          text: 'Por favor, ingrese un código válido.',
          confirmButtonText: "Aceptar",
          allowOutsideClick: false,
        });


      }
  },
  data() {
    return {
      // Variable para controlar la apertura del modal
      codigoAccesoDocumento: '',
      urlDocuments : '',
      accionVerContenedor:'validarCodigo',
      consecutivo:'',
      anexosDescripcion:'',
      anexos:'',
      titulo:''


    };
  },
  methods: {
    validarcodigo() {

      if(this.codigoAccesoDocumento) {

          try {
            this.showLoadingGif("Validando codigo");

            const formData = new FormData();
            formData.append('codigoAccesoDocumento', this.codigoAccesoDocumento);

            axios.post(this.executeUrlAxiosValidar, formData, {
              headers: {
                'Content-Type': 'multipart/form-data' // Opcional si no está definido por defecto
              }
            })
              .then((res) => {
                (this.$swal as any).close();

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                if(dataPayload['data']['success']) {
                        // Asignación de valores con el operador ??
                        this.urlDocuments = dataPayload['data']['rutapdf'] ?? '';
                        this.consecutivo = dataPayload['data']['consecutivo'] ?? 'No aplica';
                        this.anexosDescripcion = dataPayload['data']['anexosDescripcion'] ?? 'No aplica';
                        this.anexos = dataPayload['data']['rutaanexos'] ?? '';
                        this.titulo = dataPayload['data']['title'] ?? 'No aplica';

                        
                        this.accionVerContenedor = 'verContenedorAdjuntos';
                } else {

                    (this.$swal as any).close();

                    this.$swal({
                        icon: 'info',
                        title: 'Código ingresado incorrecto.',
                        text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    });
                }

            })
            .catch((error) => {
                (this.$swal as any).close();

                console.error('Error');
                console.error(error.response.data);

                  this.$swal({
                        icon: 'info',
                        title: 'Código ingresado incorrecto.',
                        text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                        confirmButtonText: "Aceptar",
                        allowOutsideClick: false,
                    });
            });


          } catch (error) {
            (this.$swal as any).close();

            console.error("Error", error);
            // Maneja el error si es necesario
            this.$swal({
                    icon: 'info',
                    title: 'Código ingresado incorrecto.',
                    text: 'El código ingresado es incorrecto. Por favor, revise y vuelva a intentarlo.',
                    confirmButtonText: "Aceptar",
                    allowOutsideClick: false,
                });
          }
      }else{

        this.$swal({
          icon: 'info',
          title: 'Ingrese un código de acceso.',
          text: 'Por favor, ingrese un código válido.',
          confirmButtonText: "Aceptar",
          allowOutsideClick: false,
        });


      }

    },
    getParameterByName(name){
      const url = window.location.search;
      const urlParams = new URLSearchParams(url);
      return urlParams.get(name);
    },
    showLoadingGif(mensaje) {
      this.$swal({
        html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>' + mensaje + '.</span>',
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false
      });
    },
  }
};
</script>
