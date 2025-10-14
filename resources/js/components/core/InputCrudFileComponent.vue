<template>
  <div>
<div class="dropzone-container">
  <v-dropzone
    id="evidence"
    ref="vueDropzone"
    :options="dropzoneOptions"
    :destroy-dropzone="false"
    @vdropzone-removed-file="removeThisFile"
    :key="this.key2"
    @vdropzone-sending="onFileSending"
    @vdropzone-success="onUploadSuccess"
    @vdropzone-error="onUploadError"
  ></v-dropzone>
</div>
    <input v-if="required && !filesUploaded && listAttachments.length < 1" @keydown.prevent @paste.prevent class="form-control input-dropzone" type="text" required style="position: absolute; pointer-events: none; bottom: 20px; width: -webkit-fill-available; height: -webkit-fill-available; background: transparent; margin-right: 10px; caret-color: transparent; border: none;" />
    <small v-if="helpText">{{ helpText }}</small>
    <br />
    <div v-if="isUpdate">
      <ul>
        <li v-for="(attachment, key) in listAttachments" :key="key">
          <a
            href="javascript:void(0)"
            @click="mostrarAdjunto(attachment.path, 'office')"
          >
            <i :class="iconClass(attachment.path)"></i>
            {{ attachment.name }}
          </a>
          <!-- <a :href="'/storage/'+attachment.path" target="_blank">{{ attachment.name }}</a> -->
          <button
            v-if="mostrarEliminarAdjunto"
            @click="removeThisFile(attachment.path)"
            type="button"
            class="btn btn-white btn-icon btn-md"
            data-toggle="tooltip"
            data-placement="top"
            title="Eliminar"
          >
            <i class="fa fa-trash"></i>
          </button>
        </li>
      </ul>
      <!-- Muestra el iframe debajo de los enlaces si hay una ruta definida -->
        <iframe v-if="openIframe" :key="route" id="pdfViewer" class="abrir_animacion" width="100%" height="420" frameborder="0" :src="route"></iframe>
    </div>
  </div>
</template>

<style>
.dropzone-container {
  max-height: 400px; /* Ajusta la altura según necesites */
  overflow-y: auto;
  overflow-x: hidden;
  border: 1px solid #ddd; /* Opcional: para visualizar mejor el contenedor */
  border-radius: 4px;
}

/* Opcional: personalizar el scrollbar */
.dropzone-container::-webkit-scrollbar {
  width: 8px;
}

.dropzone-container::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.dropzone-container::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.dropzone-container::-webkit-scrollbar-thumb:hover {
  background: #555;
}
</style>

<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

/**
 * Estilos de dropzone, se importan en cada componente,
 * enuncia error cuando se agrega el app.ts
 */
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { constants } from "zlib";
import { jwtDecode } from "jwt-decode";
import { data } from "jquery";

/**
 * Componente de input para subir archivos
 *
 * @author Carlos Moises Garcia T. - Marz. 12 - 2021
 * @version 1.0.0
 */
@Component({
  components: {
    /**
     * Se importa de esta manera por error de referencia circular
     * @see https://vuejs.org/v2/guide/components-edge-cases.html#Circular-References-Between-Components
     */
    vDropzone: () => import("vue2-dropzone"),
  },
})
export default class InputCrudFileComponent extends Vue {
  /**
   * Nombre del campo
   */
  @Prop({ type: String, required: true })
  public nameField: string;

  /**
   * listado de adjuntos que estan relacionados a otra tabla
   */
  @Prop({ type: Object, default: () => ({ data: null, field: null }) })
  public attachmentList: any;

  /**
   * Valor del campo
   */
  @Prop()
  public value: any;

  /**
   * Valor del campo
   */
  @Prop({ default: 0 })
  public key2: any;
  /**
   * Valida si los valores del formulario
   * son para actualizar o crear
   * esta variable viene del crud component
   */
  @Prop({ type: Boolean, default: false })
  public isUpdate: boolean;

  /**
   * Numero de archivos permitidos para subir
   */
  @Prop({ type: Number, default: 1 })
  public maxFiles: number;

  /**
   * Peso permitido por archivo
   */
  @Prop({ type: Number, default: 1 })
  public maxFilesize: number;

  /**
   * Mensaje del campo
   */
  @Prop({ type: String, default: "Arrastra aquí el archivo" })
  public message: string;

  /**
   * Extensiones de archivos permitidas
   * Ejemplo: .jpeg,.jpg,.png,.pdf
   */
  @Prop({ type: String, default: null })
  public acceptedFiles: string;

  /**
   * Ruta donde quedan los adjuntos
   */
  @Prop({ type: String, default: null })
  public filePath: string;

  /**
   * Texto de ayuda del componente
   */
  @Prop({ type: String, default: null })
  public helpText: string;

  /**
   * Opciones del componente de adjuntar archivos
   */
  public dropzoneOptions: any;

  /**
   * Lista de adjuntos
   */
  public listAttachments: any;

  @Prop({ type: Boolean, default: false })
  public fileNameReal: boolean;

  /**
   * Permite mostrar o no la acción de elminar adjunto
   */
  @Prop({ type: Boolean, default: true })
  public mostrarEliminarAdjunto: boolean;

  public route: string = "";
  public openIframe: boolean = false;

  // Valida si el adjunto se debe guardar en S3 de AWS, de lo contrario se guarda de manera convencional (local en el servidor)
  @Prop({ type: Boolean, default: true })
  public almacenarAWS: boolean;

  // Variable que rastrea la cantidad de archivos que están siendo cargados o procesados.
  public uploadingFilesCount: number = 0;

  // Variable que almacena un identificador numérico (key), indicando que se está obteniendo la ruta del archivo, sea desde AWS o local.
  public gettingFilePath: number = null;
  // Variable que almacena un identificador numérico (key) del archivo que se está mostrando actualmente.
  public currentFileDisplay: number = null;
  // Variable que almacena la ruta del archivo previamente seleccionado, como parámetro de la función mostrarAdjunto.
  public previousFilePath: string = "";

  public filesUploaded: boolean = false;

    /**
     * Permite indicar si el input-file es requerido o no
     */
    @Prop({ type: Boolean, default: false })
    public required: boolean;

  /**
   * Constructor de la clase
   *
   * @author Carlos Moises Garcia T. - Marz. 12 - 2021
   * @version 1.0.0
   */
  constructor() {
    super();

    this.listAttachments = [];

    const token: HTMLMetaElement | null = document.head.querySelector(
      'meta[name="csrf-token"]'
    );
    const that = this;
    this.dropzoneOptions = {
      url: this.almacenarAWS ? "/upload-input-files-aws" : "/upload-input-files",
      params: {
        file_path: this.filePath,
        file_name_real: this.fileNameReal,
      },
      success: (file, response) => {
        let dataPayload = jwtDecode(response["data"]);

        const dataDecrypted = Object.assign({}, dataPayload);

        if (
          dataDecrypted["data"] ==
          "No hay espacio disponible en el almacenamiento"
        ) {
          // Visualiza toast informativo
          toastr.info(
            "La capacidad de almacenamiento ha llegado a su límite, por favor comuniquese con soporte@intraweb.com.co",
            "Error"
          );
          this.key2 = this.key2 + 1;
        } else {
          // Valida si se va adjuntar varios documentos
          if (that.maxFiles > 1) {
            // Asigna el valor de respuesta del controlador
            that.value[that.nameField].push(dataDecrypted["data"]);
          } else {
            // Asigna el valor de respuesta del controlador
            that.value[that.nameField] = dataDecrypted["data"];
          }
          // Asigna una variable identificadora al archivo
          file.filePath = dataDecrypted["data"];
        }
      },
      thumbnailWidth: 150,
      addRemoveLinks: true,
      maxFiles: this.maxFiles,
      maxFilesize: this.maxFilesize, // Tamano maximo de archivo
      headers: { "X-CSRF-TOKEN": token.content },
      acceptedFiles: this.acceptedFiles,

      /**
       * El texto utilizado antes de que se suelten los archivos.
       */
      dictDefaultMessage: this.message,

      /**
       * El texto que reemplaza el texto del mensaje predeterminado si el navegador no es compatible.
       */
      dictFallbackMessage:
        "Su navegador no admite la carga de archivos mediante la función de arrastrar y soltar.",

      /**
       * Si el es demasiado grande.
       * `{{filesize}}` y `{{maxFilesize}}` sera reemplazado con los valores de configuracion respectivos.
       */
      dictFileTooBig:
        "El archivo es demasiado grande ({{filesize}}MB). Tamaño máximo de archivo: {{maxFilesize}}MB.",

      /**
       * Si el archivo no coincide con el tipo de archivo.
       */
      dictInvalidFileType: "No puedes subir archivos de este tipo",

      /**
       * Si la respuesta del servidor no es valida
       * `{{statusCode}}` sera reemplazado por el codigo de estado del servidor.
       */
      dictResponseError: "El servidor respondió con el código {{statusCode}}.",

      /**
       * Si `addRemoveLinks` es verdadero, El texto que se utilizara para cancelar el enlace de carga.
       */
      dictCancelUpload: "Cancelar carga",

      /**
       * El texto que se muestra si una carga se cancelo manualmente
       */
      dictUploadCanceled: "Subida cancelada.",

      /**
       * Si `addRemoveLinks` es verdadero, el texto que se utilizara para la confirmacion al cancelar la carga.
       */
      dictCancelUploadConfirmation:
        "¿Estás seguro de que deseas cancelar esta carga?",

      /**
       * Si `addRemoveLinks` es verdadero, el texto que se utilizara para eliminar un archivo.
       */
      dictRemoveFile: "Remover archivo",

      /**
       * Se muestra si `maxFiles` es st y excedido.
       * La cadena `{{maxFiles}}` sera reemplazada por el valor de configuracion.
       */
      dictMaxFilesExceeded: "No puedes subir más archivos",
    };

    // Valida si el valor del campo no esta definido
    if (
      this.value[this.nameField] == undefined ||
      this.value[this.nameField] == null
    ) {
      this.value[this.nameField] = [];
    }
  }

  /**
   * Se ejecuta cuando el componente ha sido creado
   */
  created() {
    this.loadData();
  }

  /**
   * Cagar los datos de los adjuntos cuando existen previamente
   *
   * @author Carlos Moises Garcia T. - Marz. 12 - 2021
   */
  public loadData() {
    // Valida si viene un array de adjuntos
    if (this.attachmentList.data) {
      // Obtiene el array con la lista de adjuntos
      let data = this.value[this.attachmentList.data];

      // Recorre los adjuntos
      data.forEach((dataElement: any) => {
        // Convierte el array la ruta del adjunto
        let name = dataElement[this.attachmentList.field].split("/");
        // Inserta el elemento al listado de adjuntos
        this.listAttachments.push({
          path: dataElement[this.attachmentList.field],
          name: name[name.length - 1],
        });

        // Agrega el adjunto al modelo
        this.value[this.nameField].push(dataElement[this.attachmentList.field]);
      });
    } else {
      // Obtiene los adjuntos
      const data = this.value[this.nameField];
      // if (typeof data != "object") {
        // Convierte el array los adjuntos
        let attachments = typeof data != "object" ? data.split(",") : data;
        // Agrega los adjuntos al modelo
        this.value[this.nameField] = attachments;
        for (let index = 0; index < attachments.length; index++) {
          const element = attachments[index];
          // Convierte el array la ruta del adjunto
          let name = element.split("/");
          // Inserta el elemento al listado de adjuntos
          this.listAttachments.push({
            path: element,
            name: name[name.length - 1],
          });
        }
      // }
    }
  }

  /**
   * Elimina el adjunto del servidor
   *
   * @author Carlos Moises Garcia T. - Marz. 12 - 2021
   *
   * @param path ruta del adjunto
   */
  public removeAttachment(path) {
    // Envia peticion para eliminar el archivo del servidor
    axios
      .post("/delete-input-files", {
        file_path: path,
      })
      .then((res) => {
        // Elimina el archivo de array que contiene todos los adjuntos
        this.listAttachments = this.listAttachments.filter(
          (attachment) => attachment.path != path
        );
        // Elimina el archivo de array que contiene las rutas
        this.value[this.nameField] = this.value[this.nameField].filter(
          (e) => e !== path
        );
        this.filesUploaded = (this.$refs.vueDropzone as Vue)["getAcceptedFiles"]().length > 0;
      })
      .catch((err) => {
        console.log(err);
      });
  }

  /**
   * Elimina el archivo del formulario
   *
   * @author Carlos Moises Garcia T. - Marz. 15 - 2021
   * @param removedFile archivo que se va a eliminar
   */
  public removeThisFile(removedFile) {
    // Valida si se esta insertando por primera vez el archivo o si falló la carga de este
    if (removedFile.filePath || removedFile.status == 'error') {
      // Invoca la funciona que elimina el adjunto del servidor
      this.removeAttachment(removedFile.filePath);
    } else {
      // Visualiza alerta de eliminacion de elemento
      this.$swal({
        icon: "error",
        title: "¿Seguro que desea eliminar éste adjunto?",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
        customClass: {
          confirmButton: "btn m-r-5 btn-danger",
          cancelButton: "btn btn-default",
        },
      }).then((res) => {
        // Valida si la opcion selecionada es positiva
        if (res.value) {
          // Invoca la funciona que elimina el adjunto del servidor
          this.removeAttachment(removedFile);
        }
      });
    }
  }

    /**
     * Muestra el archivo adjunto en el iframe predeterminado.
     * Si el archivo es de tipo .doc, .docx, .xls, .xlsx, .ppt o .pptx,
     * se generará una URL de vista previa utilizando servicios en línea.
     * Si no, se utilizará la ruta directa del archivo.
     *
     * @param documento - El nombre del archivo adjunto.
     * @param editor - El editor para mostrar el archivo, puede ser "google" o cualquier otro valor para el visor predeterminado.
     */
    public mostrarAdjunto(documento: string, editor: string): void {

        // Obtiene la URL base del sitio
        const baseUrl = window.location.origin;
        // Combina la URL base con la ruta del archivo
        const fullUrl = baseUrl + "/storage/" + documento;
        let urlTemporal = "";

        //Función para verificar si el archivo existe en el servidor
        this.verificarExistenciaArchivo(fullUrl)
        .then(existe => {
            //Si el archivo existe en el servidor envia la ruta a la funcion RouteLink y esta procede a abrir el iframe
            if (existe) {
                // Verifica si el archivo es de tipo .doc, .docx, .xls, .xlsx, .ppt o .pptx
                if (documento.endsWith('.doc') || documento.endsWith('.docx') || documento.endsWith('.xls') || documento.endsWith('.xlsx') || documento.endsWith('.ppt') || documento.endsWith('.pptx')) {
                    // Construye la URL del visor adecuado
                    if (editor == "google") {
                    urlTemporal = `https://docs.google.com/gview?url=${fullUrl}&embedded=true`;
                    } else {
                    urlTemporal = `https://view.officeapps.live.com/op/embed.aspx?src=${fullUrl}`;
                    }
                } else {
                    urlTemporal = fullUrl;
                    // Establece la ruta normal si no es un archivo de Microsoft Office
                }

                this.routeLink(urlTemporal);

            //De lo contrario, se modifica la ruta para que los archivos puedan ser llamados desde AWS y se envia la ruta final a la funcion routeLink
            } else {
                // Envia peticion para descargar y/o obtener la ruta del documento a mostrar
                axios.post("/read-object-aws", {
                    tipoURL: "temporal_local", // Tipo de URL, si es temporal_local, se descarga el documento en una carpeta temporal y posteriormente se muestra
                    path: documento, // key, ruta del archivo en AWS
                })
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    let documentoRecuperado = dataPayload["data"];
                    // Verifica si el archivo es de tipo .doc, .docx, .xls, .xlsx, .ppt o .pptx
                    if (documento.endsWith('.doc') || documento.endsWith('.docx') || documento.endsWith('.xls') || documento.endsWith('.xlsx') || documento.endsWith('.ppt') || documento.endsWith('.pptx')) {
                    // Construye la URL del visor adecuado
                        if (editor == "google") {
                            urlTemporal = `https://docs.google.com/gview?url=${documentoRecuperado}&embedded=true`;
                        } else {
                            urlTemporal = `https://view.officeapps.live.com/op/embed.aspx?src=${documentoRecuperado}`;
                        }
                    } else {
                        // Obtiene la ruta del documento "pdf" y lo asigna a la ruta temporal
                        urlTemporal = documentoRecuperado;
                    }
                    // Se hace el reemplazo en la ruta del documento de la siguiente cadena, si aplica
                    // Se envia la ruta del documento final al iframe
                    this.routeLink(urlTemporal);
                })
                .catch((err) => {
                    console.log("Error al obtener el objeto de AWS", err);
                });
            }
        });
    }

    /**
     * actualiza la ruta y abre el iframe.
     *
     * @param Route - Ruta del archivo que abrira.
     */
    public routeLink(route): void {
        // Si la ruta temporal es igual a la actual y el iframe está abierto, lo cierra; de lo contrario, actualiza la ruta y abre el iframe.
        if (this.route == route && this.openIframe) {
            this.openIframe = false;
        } else {
            this.route = route;
            this.openIframe = true;
        }
    }

  /**
   * Retorna la clase de icono correspondiente según la extensión del archivo.
   *
   * @param documento - El nombre del archivo incluyendo su extensión.
   * @returns La clase de icono de Font Awesome correspondiente.
   */
  public iconClass(documento: string): string {
    if (documento.endsWith(".doc") || documento.endsWith(".docx")) {
      return "fas fa-file-word fa-lg text-blue"; // Icono de archivo de Word
    } else if (documento.endsWith(".xls") || documento.endsWith(".xlsx")) {
      return "fas fa-file-excel fa-lg text-success"; // Icono de archivo de Excel
    } else if (documento.endsWith(".ppt") || documento.endsWith(".pptx")) {
      return "fas fa-file-powerpoint fa-lg text-info"; // Icono de archivo de PowerPoint
    } else if (documento.endsWith(".pdf")) {
      return "fas fa-file-pdf fa-lg text-danger"; // Icono de archivo PDF
    } else if (
      documento.endsWith(".jpg") ||
      documento.endsWith(".jpeg") ||
      documento.endsWith(".png") ||
      documento.endsWith(".gif") ||
      documento.endsWith(".bmp")
    ) {
      return "fas fa-file-image fa-lg text-secondary"; // Icono de archivo de imagen
    } else if (documento.endsWith(".zip")) {
      return "fas fa-file-archive fa-lg text-secondary"; // Icono de archivo ZIP
    } else {
      return "fas fa-file fa-lg text-muted"; // Icono de archivo genérico
    }
  }

  /**
   * Verifica si un archivo existe en la URL proporcionada.
   * @param url - La URL del archivo a verificar.
   * @returns True si el archivo existe, de lo contrario False.
   */
  async verificarExistenciaArchivo(url: string): Promise<boolean> {
    try {
      // Intenta cargar el archivo utilizando la API Fetch
      const response = await fetch(url, { method: "HEAD" });

      // Si la respuesta es exitosa (código de estado 200 o 204), devuelve true
      return response.ok;
    } catch (error) {
      // Si se produce un error (por ejemplo, CORS bloqueado), devuelve false
      return false;
    }
  }

    // Esta función se ejecuta cuando un archivo comienza a enviarse.
    onFileSending(file, xhr, formData) {
        // Establece la propiedad 'uploadingFileInputCrudFile' en el componente padre a true, indicando que hay una carga en proceso.
        (this.$parent as Vue)["uploadingFileInputCrudFile"] = true;
        // Incrementa el contador 'uploadingFilesCount', que rastrea cuántos archivos se están subiendo actualmente.
        this.uploadingFilesCount++;
    }

    // Esta función se ejecuta cuando un archivo se ha subido con éxito.
    onUploadSuccess(file, response) {
        this.filesUploaded = true;
        // Decrementa el contador 'uploadingFilesCount', indicando que una subida ha terminado.
        this.uploadingFilesCount--;
        // Llama a 'checkAllUploadsFinished()' para verificar si todas las subidas han finalizado.
        this.checkAllUploadsFinished();
    }

    // Esta función se ejecuta cuando ocurre un error durante la subida de un archivo.
    onUploadError(file, errorMessage) {
        // Decrementa el contador 'uploadingFilesCount', indicando que una subida ha terminado (a pesar del error).
        this.uploadingFilesCount == 0 ? this.uploadingFilesCount = 0 :  this.uploadingFilesCount--;
        // Llama a 'checkAllUploadsFinished()' para verificar si todas las subidas han finalizado.
        this.checkAllUploadsFinished();
    }

    // Esta función verifica si todas las subidas de archivos han terminado.
    checkAllUploadsFinished() {
        // Si 'uploadingFilesCount' es igual a 0 es decir, no hay más archivos en proceso de subida
        if (this.uploadingFilesCount === 0) {
            /**
             * Establece 'uploadingFileInputCrudFile' en el componente padre a false,
             * indicando que no hay cargas en proceso, todos los adjuntos han finalizado la subida.
             */
            (this.$parent as Vue)["uploadingFileInputCrudFile"] = false;
        }
    }
}
</script>
