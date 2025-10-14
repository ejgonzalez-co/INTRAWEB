<template>

  <div>

    <div class="row">
      <div class="alert alert-success w-100" role="alert">
        En este espacio, puedes adjuntar documentos principales de la correspondencia para ser rótulados.
        <br>
        Nota: La vista previa muestra solo la primera página del PDF. Asegúrate de elegir el formato correcto (Carta u
        Oficio) para un rotulado correcto del PDF.
      </div>


      <!-- Step 1: Attach Primary Document -->
      <div class="mb-2 mr-2 ml-2" id="attach">
        <label for="document_pdf" class="btn btn-info" style="width: 100%; cursor: pointer;">
          <i class="fa fa-upload mr-1"></i> 1. Adjuntar documento principal
        </label>
        <input type="file" name="document_pdf" id="document_pdf" @change="previewDocument($event)" ref="adjuntarRef"
          class="btn btn-info" style="display: none;" accept=".pdf" />
      </div>

      <!-- Step 2: Label and Save Document -->
      <div class="mb-2 mr-2" @click="saveDocumentWithRotule($event)" v-if="documentoTemporal">
        <label class="btn btn-warning" style="width: 100%; cursor: pointer;">
          <i class="fa fa-save mr-1"></i> 2. Ubicar, rotular y guardar documento
        </label>
      </div>

      <!-- Print Label on Paper Button -->
      <div class="mb-2">
        <button id="btn-rotule-print" class="btn btn-gray" type="button" onclick="printContent('page');">
          <i class="fa fa-print mr-2"></i> Imprimir rótulo en papel
        </button>
      </div>

      <!-- Select Paper Size -->
      <div class="mb-2 ml-2">

        <select name="paper-size" id="paper-size" class="form-control" v-model="typeSize" @change="changeSizeSheet"
          style="width: 150px;">
          <option value="Carta">Carta</option>
          <option value="Oficio">Oficio</option>
        </select>
        <small class="">
          <em>Nota: Si cambias el tamaño de papel, debes adjuntar de nuevo el documento.</em>
        </small>

      </div>

    </div>





    <!-- Document Preview with Drag & Resize -->
    <div v-if="updateProps == false" id="page" class="page-rotule-show list"
      :style="'width: ' + sheetWidth + 'mm; height: ' + sheetHeight + 'mm; position:relative; border:1px solid black; background-size:cover; background-repeat:round; background-image: url(\'' + imgPreview + '\')'">
      <vue-drag-resize ref="vueDragResize" :is-resizable="false" :is-draggable="true" :parent-w="820"
        :parent-h="sizeHeightLimit" :h="30" :w="232" :x="575" :y="10" :parent-limitation="true" :key="refreshComponent">
        <div id="content-rotule" class="ui-widget-content"
          style="width: 220px; height: auto; border: 1px solid #000; border-radius: 10px; padding: 5px; background-color: lightgray; cursor: pointer; position: absolute; right: 8px;">
          <div
            style="font-family: Arial; text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 11px;">
            {{ this.name }}
          </div>

          <div
            style="font-family: Arial; text-align: center; font-weight: bold; border-bottom: 1px solid #000; font-size: 11px;">
            {{ typeCorrespondence === 'Enviada' ? 'Correspondencia enviada' : 'Correspondencia recibida' }} <br /> {{
              dataRotuleComponent.consecutive }}
          </div>
          <div :style="{ fontSize: '11px' }" v-for="(props, index) in dataRotule.data" :key="index">
            <!-- Mostrar props.nombre_label -->
            <b>{{ props.nombre_label }}</b>:
            <!-- Mostrar el valor de data con la clave props.variables_rotule -->
            <span v-if="getValueRotule(props.variables_rotule)" v-html="getValueRotule(props.variables_rotule)"></span>

            <!-- Mensaje alternativo si no hay valor disponible -->
            <span v-else>
              Información no disponible
            </span>
          </div>


        </div>
        <!-- Spinner de carga -->
        <div v-if="loading" class="col-12">
          <div class="spinner"></div>
        </div>
      </vue-drag-resize>
    </div>




    <!-- Editar propiedades del rótulo -->
    <div v-if="updateProps == true" id="page" class="page-rotule-show list"
      :style="'width: ' + sheetWidth + 'mm; height: ' + sheetHeight + 'mm; position:relative; border:1px solid black; background-size:cover; background-repeat:round; background-image: url(\'' + imgPreview + '\')'">

      <!-- Contenedor redimensionable con vue-drag-resize -->
      <vue-drag-resize ref="vueDragResize" :is-resizable="true" :is-draggable="true" :parent-w="818"
        :parent-h="sizeHeightLimit" :x="580" :y="14" :parent-limitation="true" :key="refreshComponent" :minh="1" :h="'auto'" 
        :w="Number(rotuleWidth) || 200"
        :minw="minWidth"
        @resizing="onResizing">
 
        <!-- Contenido del rótulo -->
        <div id="content-rotule" class="ui-widget-content" :style="{
          width: rotuleWidth + 'px',
          height: 'auto',
          border: '1px solid #000',
          borderRadius: '10px',
          padding: '5px',
          backgroundColor: 'lightgray',
          cursor: 'pointer',
          position: 'relative',
          fontSize: (rotuleWidth * 0.04) + 'px',
          lineHeight: '1.3'
        }">
          <div style="font-family: Arial; text-align: center; font-weight: bold; border-bottom: 1px solid #000;">
            {{ name }}
          </div>

          <div style="font-family: Arial; text-align: center; font-weight: bold; border-bottom: 1px solid #000;">
            {{ typeCorrespondence === 'Enviada' ? 'Correspondencia enviada' : 'Correspondencia recibida' }} <br /> {{
              dataRotuleComponent.consecutive }}
          </div>

          <div v-for="(props, index) in dataRotule.data" :key="index">
            <!-- Mostrar props.nombre_label -->
            <b>{{ props.nombre_label }}</b>:
            <!-- Mostrar el valor de data con la clave props.variables_rotule -->
            <span v-if="getValueRotule(props.variables_rotule)" v-html="getValueRotule(props.variables_rotule)"></span>

            <!-- Mensaje alternativo si no hay valor disponible -->
            <span v-else>
              Información no disponible
            </span>
          </div>
        </div>
        <!-- Spinner de carga -->
        <div v-if="loading" class="col-12">
          <div class="spinner"></div>
        </div>
      </vue-drag-resize>
    </div>

  </div>
</template>



<script lang="ts">
import axios from 'axios';
import { data } from 'jquery';
import { jwtDecode } from 'jwt-decode';
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

export default {
  name: 'RotuleComponent',
  props: {
    // Propiedad para la URL del documento
    executeUrlAxiosPreview: {
      type: String,
      required: true
    },
    executeUrlAxiosRotular: {
      type: String,
      required: true
    },
    typeCorrespondence: {
      type: String,
      required: false,
      default: 'Recibida'
    },


    typeCall: {
      type: String,
      required: true
    },

    name: {
      type: String,
      required: true,
      default: 'Intraweb',
    },
    updateProps: {
      type: Boolean,
      required: false,
      default: false
    },
  },

  data() {
    return {
      loading: true,
      imgPreview: '',
      buttonRotular: false,
      idActualizar: '',
      dataRotuleComponent: {},
      documentoTemporal: '',
      pageHtml: '',
      typeSize: 'Carta',
      sheetWidth: 216,
      sheetHeight: 279,
      sizeHeightLimit: 1040,
      refreshComponent: 0,
      rotuleWidth: 220,  // Ancho inicial
      rotuleHeight: 100, // Altura inicial
      dataRotule: {},
      minWidth: 135
    };
  },

  methods: {

    getValueRotule(key) {
      if (this.dataRotuleComponent[key]) {
        return this.dataRotuleComponent[key]
      }
      // Devuelve el valor correspondiente o undefined si no existe
      return false;
    },

    onResizing(newWidth) {     
      if (newWidth.width >= this.minWidth) {
        this.rotuleWidth = newWidth.width;  // Actualiza el ancho si está dentro del límite
      }
    },
    changeSizeSheet() {

      this.sheetWidth = this.typeSize === 'Carta' ? 216 : 216; // Ancho en mm

      this.sheetHeight = this.typeSize === 'Carta' ? 279 : 360; // Alto en mm

      this.sizeHeightLimit = this.typeSize == 'Carta' ? 1040 : 1330; // Alto en mm
      this.imgPreview = "";
      this.documentoTemporal = "";
      this.refreshComponent++;

    },

    propsRotule() {

      axios.get('get-rotule-props/' + this.typeCorrespondence)
        .then(res => {

          let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

          const dataDecrypted = Object.assign({}, dataPayload)

          console.log('Datos decodificados:', dataDecrypted);
          if (dataDecrypted) {
            this.dataRotule = dataDecrypted;
            this.rotuleWidth = this.dataRotule.data[0].rotuleWidth ?? 200;
          }
          
          this.loading = false;


        })
        .catch(err => {
          // Maneja errores de la solicitud
          this.loading = false;

          (this.$swal as any).close();
          console.log("Error al obtener la previsualización del documento:", err);
          this.showAlert("warning", "Error al obtener la previsualización del documento. Por favor, inténtalo de nuevo.");

        });


    },
    previewDocument(event: Event) {

      const inputElement = event.target as HTMLInputElement;

      if (inputElement.files && inputElement.files.length > 0) {
        const selectedFile = inputElement.files[0];

        // Almacena el archivo seleccionado temporalmente
        this.documentoTemporal = selectedFile;

        // Verifica el tipo y tamaño del archivo
        if (selectedFile.type === "application/pdf" && selectedFile.size <= 10 * 1048576) {
          // El archivo es un PDF válido y está dentro del límite de tamaño (10MB)
          this.showLoadingGif("Cargando previsualización del documento principal");

          // Crea un objeto FormData para enviar el archivo al servidor
          const formData = new FormData();
          formData.append('selectedFile', selectedFile);
          formData.append('id', this.idActualizar);

          // Realiza una solicitud POST al servidor para obtener la previsualización del documento
          axios.post(this.executeUrlAxiosPreview + this.idActualizar, formData, {
            headers: { "Content-Type": "multipart/form-data" }
          })
            .then(res => {
              (this.$swal as any).close();


              // Procesa la respuesta del servidor
              // Cierra la pantalla de carga
              if (res.data.type_message == 'warning') {

                this.showAlert("warning", res.data.message);

              } else {

                const dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                // console.log('Datos decodificados:', dataPayload["data"]);
                // Almacena la URL de la previsualización del PDF en imgPreview
                this.imgPreview = dataPayload["data"];
              }


            })
            .catch(err => {
              // Maneja errores de la solicitud
              (this.$swal as any).close();
              console.log("Error al obtener la previsualización del documento:", err);
              this.showAlert("warning", "Error al obtener la previsualización del documento. Por favor, inténtalo de nuevo.");

            });
        } else {
          // El tipo o tamaño del archivo es inválido, muestra una alerta adecuada
          if (selectedFile.size > 10 * 1048576) {
            // El tamaño del archivo excede el límite de 10MB
            this.showAlert("info", "El archivo no debe superar el peso de 10MB.");
          } else {
            // El archivo seleccionado no es un PDF
            this.showAlert("warning", "El archivo adjunto debe ser un archivo con extensión PDF.");
          }
        }
      }
    },
    showAlert(icon, message) {
      // Método auxiliar para mostrar un modal SweetAlert
      this.$swal({
        icon: icon,
        html: message,
        confirmButtonText: "Aceptar",
        allowOutsideClick: false
      });
    },
    limpiarDatos() {
      this.typeSize = "Carta";
      this.imgPreview = "";
      this.dataRotuleComponent = {};
      // this.$refs.vueDragResize.right = 13;
      // this.$refs.vueDragResize.top = 10;
      // this.$refs.vueDragResize.bottom = 845;
      // this.$refs.vueDragResize.left = 575;

      this.changeSizeSheet();
    },

    async saveDocumentWithRotule(event: Event) {
      try {

        const pageElement = document.getElementById('content-rotule');

        this.showLoadingGif("Rotulando documento principal");
        // Verifica si el elemento con id 'page' existe antes de acceder a su contenido

        if (pageElement) {
          // Obtiene el contenido HTML de un elemento con el id 'page'
          const pageHtml = pageElement.innerHTML;

          // Codifica el contenido HTML en Base64 para incluirlo en la solicitud
          const encodedPageHtml = btoa(unescape(encodeURIComponent(pageHtml)));
          // console.log('Contenido HTML codificado en Base64:', encodedPageHtml);

          // Almacena el contenido HTML codificado en Base64
          this.pageHtml = encodedPageHtml;
        } else {
          // console.error('Elemento #page no encontrado');
          this.showAlert("error", "Ocurrió un error obteniendo el rotulo. Por favor, inténtalo de nuevo.");
          return;
        }

        if (!this.documentoTemporal) {
          this.showAlert("warning", "Por favor adjunte un documento principal.");
          return;
        }

        // Accede al componente vue-drag-resize mediante refs
        const dragResizeComponent = this.$refs.vueDragResize;
        const rotulePositionX = dragResizeComponent.positionStyle.left;
        const rotulePositionY = dragResizeComponent.positionStyle.top;
        const rotuleWith = dragResizeComponent.rect.width;
        const rotuleHeight = pageElement.offsetHeight;
        const fontSize = window.getComputedStyle(pageElement).fontSize;
        const fontFamily = window.getComputedStyle(pageElement).fontFamily; // Tipo de fuente


        // console.log('Posición del rótulo (X, Y):', rotulePositionX, rotulePositionY);

        // Crea un objeto FormData para enviar los datos al servidor
        const formData = new FormData();
        formData.append('selectedFile', this.documentoTemporal);
        formData.append('id', this.idActualizar);
        formData.append('pageHtml', this.pageHtml);
        formData.append('rotule_position_x', rotulePositionX);
        formData.append('rotule_position_y', rotulePositionY);
        formData.append('rotule_width', rotuleWith);
        formData.append('rotule_height', `${rotuleHeight}`);
        formData.append('font_size', `${fontSize}`);
        formData.append('font_family', `${fontFamily}`);
        formData.append('document_type', this.typeSize);

          // Realiza una solicitud POST al servidor para guardar el tamaño del rotulo
          const datarotule = await axios.post('edit-rotule/' + this.typeCorrespondence, formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });
        // Realiza una solicitud POST al servidor para guardar el documento con la rotulación
        const response = await axios.post(this.executeUrlAxiosRotular + this.idActualizar, formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });

        if (response.data.type_message == 'warning') {

          this.showAlert("warning", response.data.message);

        } else {

          // Procesa la respuesta del servidor
          const dataPayload = response.data.data ? jwtDecode(response.data.data) : null;
          const dataDecrypted = Object.assign({ data: { url_edited_document_pdf: null, pdf_previous: null, document_pdf: null } }, dataPayload);

          // console.log('Datos decodificados:', dataDecrypted);

          // Cierra el indicador de carga (SweetAlert)
          (this.$swal as any).close();

          // Actualiza los datos en el componente padre según el tipo de llamada
          if (dataDecrypted.data) {
            if (this.typeCall === 'rotule_fields') {
              // Actualiza el elemento modificado en la lista
              Object.assign(this.$parent.$parent._findElementById(this.idActualizar, false), dataDecrypted.data);

              // Actualiza el documento_pdf en el formulario si es una actualización
              if (this.$parent.isUpdate) {
                this.$set(this.$parent.dataForm, "document_pdf", dataDecrypted.data.document_pdf);
              }
            } else {

              // Actualiza el elemento modificado en la lista
              Object.assign(this.$parent._findElementById(this.idActualizar, false), dataDecrypted.data);

            }


            // Muestra una alerta de éxito si se completa la operación
            this.showAlert("success", "Documento Guardado y Rotulado con éxito");

          } else {
            this.showAlert("warning", "Ocurrió un error al guardar el documento. Por favor, inténtalo de nuevo.");

          }

        }

      } catch (error) {
        console.error('Error al guardar el documento:', error);
        // Cierra el indicador de carga (SweetAlert)
        (this.$swal as any).close();
        // Muestra una alerta de error
        this.showAlert("warning", "Ocurrió un error al guardar el documento. Por favor, inténtalo de nuevo.");
      }
    },
    asginarData(data) {

      this.propsRotule();
      this.dataRotuleComponent = data;
      this.idActualizar = data.id;
      this.documentoTemporal = '';
      this.refreshComponent++;

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
    shortText(text) {
      if (typeof text !== 'undefined' && text !== null && text.trim() !== '') {
        // Replace <br> tags with spaces
        text = text.replace(/<br\s*\/?>/gi, ' ');

        const maxLength = 30;
        if (text.length > maxLength) {
          return { text: text.substring(0, maxLength), isLong: true };
        } else {
          return { text: text, isLong: false };
        }
      } else {
        return { text: '', isLong: false };
      }
    }


  }
};
</script>
