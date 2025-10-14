<template>

  <div v-if="type == 'table'">
    <div class="modal fade" id="viewerModal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content border-0">
          <div class="modal-header bg-blue">
            <h4 class="modal-title text-white">Documento</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                class="fa fa-times text-white"></i></button>
          </div>
          <div class="modal-body">
            <iframe :key="route" id="pdfViewer" class="" width="100%" frameborder="0" :src="route"
              style="height: 70vh;"></iframe>
          </div>
          <div class="modal-footer">
            <button class="btn btn-white" data-dismiss="modal"><i class="fa fa-times mr-2"></i>Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-else >
    <!-- Lista numerada de enlaces de documentos con estilos de Bootstrap -->
    <ol :class="displayFlex ? 'd-flex flex-wrap p-0' : 'list-group'" v-if="list">
        <li v-for="(documento, key) in (Array.isArray(list) ? list : list.split(','))" :key="key" :class="displayFlex ? 'list-group-item m-r-3 m-b-3 adjunto-viewer border' : 'list-group-item'">
            <a href="javascript:void(0)" @click="handleClick(documento, key)">
                <i :class="iconClass(documento)"></i> {{ linkFileName ? getFileName(documento) : name + "-" + (key+1) }}
            </a>
            <!-- Muestra la acción de descarga para cada archivo según la propidad enableDownloadFile -->
            <a v-if="enableDownloadFile" class="m-l-4 p-2 p-t-0 p-b-0 text-black" href="javascript:void(0)" @click="prepararDescargaArchivo(documento, key)"><i class="fas fa-download"></i></a>
            <!-- Spinner que se muestra al momento de dar clic a un enlace de un archivo indicando que esta cargando el archivo -->
            <div v-if="key == gettingFilePath" class="spinner" :style="{position: 'relative', width: '18px', height: '18px', left: '32px', display: 'inline-block', 'vertical-align': displayFlex ? 'super' : 'bottom'}"></div>
            <!-- Ícono del ojo que indica el archivo que este visualizando en el momento -->
            <i v-if="key == currentFileDisplay && gettingFilePath == null" class="fa fa-eye" style="position: relative; left: 31px; margin-left: -18px;" title="Visualizando archivo"></i>
        </li>
    </ol>
    <ol class="list-group" v-else>
      <span>Sin documento</span>
    </ol>

    <div class="container-fluid">
      <div class="mx-auto" style="max-width: 71rem;">
        <iframe v-if="openIframe && type != 'only-link'" :key="route" id="pdfViewer" class="abrir_animacion w-100"
          height="420" frameborder="0" :src="route"></iframe>
      </div>
    </div>

    <!-- Muestra el iframe debajo de los enlaces si hay una ruta definida -->
  </div>

</template>

<script lang="ts">
import Vue from "vue";
import { Component, Prop } from "vue-property-decorator";
import axios from 'axios';
import { jwtDecode } from "jwt-decode";
import { data } from "jquery";

/**
 * Componente para ver adjuntos desde enlace
 *
 * @author Manuel Marin. 2024
 * @author Erika Johana Gonzalez C. 2024
 * @version 1.0.0
 */
@Component
export default class ViewerAtthachementComponent extends Vue {

    // Variable que almacena un identificador numérico (key), indicando que se está obteniendo la ruta del archivo, sea desde AWS o local.
    public gettingFilePath: number = null;
    // Variable que almacena un identificador numérico (key) del archivo que se está mostrando actualmente.
    public currentFileDisplay: number = null;
    // Variable que almacena una referencia local como una cadena de texto.
    public localReference: string = "";
    // Propiedad que indica si se debe mostrar el nombre del archivo en el enlace.
    @Prop({ type: Boolean, default: false }) public linkFileName: boolean;
    // Propiedad que almacena una referencia del componente "hijo" que se encuentra por debajo de el.
    @Prop({ type: String }) public componentReference: any;

    // Propiedad que indica si el registro debe ser marcado como leido
    @Prop({ type: String, default: "false" }) public readDocument: any;

    // Propiedad que almacena el modula al cual se le va a marcar el leido
    @Prop({ type: String, default: 'na' }) public refModule: any;

    // Propiedad que almacena el id del registro que se va actualizar con el leido
    @Prop({ type: Number }) public readId: any;

    /**
     * Lista de adjuntos.
     * @type {string}
     * @example 'adjunto1.pdf,adjunto2.jpg,adjunto3.docx'
     * @description Envía una cadena con adjuntos separados por comas.
     */
    @Prop({ type: String }) public list: any;
    /**
     * El tipo de adjunto.
     * - `'table'`: Utiliza cuando se desea mostrar en un modal el adjunto.
     * - `'only-link'`: Se utiliza para poner en el table.blade, recorre los adjuntos pero no pone el iframe.
     * - Sin enviar `type`: Se utiliza en los componentes de show o edición y otras partes donde se quiera desplegar un iframe abajo.
     */
    @Prop({ type: String }) public type: string;
    /**
     * Nombre del documento a mostrar.
     * @type {string}
     * @default 'Ver documento'
     * @description Con este campo puede enviar la frase o el valor del campo que quiere mostrar en la lista del visor.
     */
    @Prop({ type: String, default: 'Ver documento' }) public name: string;

    @Prop({ type: Boolean, default: false }) public displayFlex: boolean;

    /**
     *
     */
    @Prop({ type: Boolean, default: false }) public openDefault: boolean;

    public route: string = "";
    public openIframe: boolean = false;

    // Variable que almacena la ruta del archivo previamente seleccionado, como parámetro de la función mostrarAdjunto.
    public previousFilePath: string = "";

    public isIos: boolean = false;

    // Propiedad que indica si se debe mostrar la acción de descarga del archivo, por defecto se muestra
    @Prop({ type: Boolean, default: true }) public enableDownloadFile: boolean;

    /**
     * Método que se llama después de que el componente ha sido montado en el DOM.
     */
    mounted() {
        setTimeout(() => {
            // Agrega un listener para el evento hidden.bs.modal al modal donde se visualiza el adjunto
            jQuery('#viewerModal').on('hidden.bs.modal', this.closeModalViewerFile);
        }, 1000);
        this.openIframe = this.openDefault;
        if (this.openDefault) {
            this.handleClick(this.list, 0);
            // this.route = this.list;
        }
    }
    created() {
        this.detectIos();
    }

    public detectIos() {
        const userAgent = navigator.userAgent || navigator.vendor || window["opera"];
        this.isIos = /iPad|iPhone|iPod/.test(userAgent) && !window["MSStream"];
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
                // Se establece 'previousFilePath' con el valor de 'documento' recibido por parámetro, para realizar una validación al inicio de la función
                this.previousFilePath = documento;
                // Se envia la ruta del documento final al iframe
                this.routeLink(urlTemporal);

                //De lo contrario, se modifica la ruta para que los archivos puedan ser llamados desde AWS y se envia la ruta final a la funcion routeLink
            } else {
                // Envia peticion para descargar y/o obtener la ruta del documento a mostrar
                axios.post("/read-object-aws", {
                    tipoURL: "temporal_aws", // Tipo de URL, si es temporal_local, se descarga el documento en una carpeta temporal y posteriormente se muestra
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
                    // Se establece 'previousFilePath' con el valor de 'documento' recibido por parámetro, para realizar una validación al inicio de la función
                    this.previousFilePath = documento;
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
        // Si localReference tiene un valor, es decir la acción se realizó desde la tabla (listado principal)
        if (this.localReference) {
            // Establece 'gettingFilePath' en el componente padre a null, indicando que la ruta del archivo a mostrar se ha cargado en el iframe.
            (this.$parent.$refs[this.localReference] as Vue)["gettingFilePath"] = null;
        } else {
            // Establece 'gettingFilePath' null, indicando que la ruta del archivo a mostrar se ha cargado en el iframe.
            this.gettingFilePath = null;
        }

        if (this.isIos) {
            this.currentFileDisplay = null;
            if(this.localReference) {
                // Establece 'gettingFilePath' en el componente padre a null, indicando que la ruta del archivo a mostrar se ha cargado en el iframe.
                (this.$parent.$refs[this.localReference] as Vue)["currentFileDisplay"] = null;
            }
            this.openInNewTab(route);
        } else {
            this.route = route;
            this.openIframe = true;

            // Si el tipo es "table", muestra el modal de visualización.
            if (this.type == "table" || this.type == "only-link") {
                $('#viewerModal').modal('show');
            }
        }

    }

    public openInNewTab(url) {
        const a = document.createElement('a');
        a.href = url;
        a.target = '_self';
        a.rel = 'noopener noreferrer';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    /**
     * Retorna la clase de icono correspondiente según la extensión del archivo.
     *
     * @param documento - El nombre del archivo incluyendo su extensión.
     * @returns La clase de icono de Font Awesome correspondiente.
     */
    public iconClass(documento: string): string {
        if (documento.endsWith('.doc') || documento.endsWith('.docx')) {
            return 'fas fa-file-word fa-lg text-blue'; // Icono de archivo de Word
        } else if (documento.endsWith('.xls') || documento.endsWith('.xlsx')) {
            return 'fas fa-file-excel fa-lg text-success'; // Icono de archivo de Excel
        } else if (documento.endsWith('.ppt') || documento.endsWith('.pptx')) {
            return 'fas fa-file-powerpoint fa-lg text-info'; // Icono de archivo de PowerPoint
        } else if (documento.endsWith('.pdf')) {
            return 'fas fa-file-pdf fa-lg text-danger'; // Icono de archivo PDF
        } else if (documento.endsWith('.jpg') || documento.endsWith('.jpeg') || documento.endsWith('.png') || documento.endsWith('.gif') || documento.endsWith('.bmp')) {
            return 'fas fa-file-image fa-lg text-secondary'; // Icono de archivo de imagen
        } else if (documento.endsWith('.zip')) {
            return 'fas fa-file-archive fa-lg text-secondary'; // Icono de archivo ZIP
        } else {
            return 'fas fa-file fa-lg text-muted'; // Icono de archivo genérico
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
            const response = await fetch(url, { method: 'HEAD' });

            // Si la respuesta es exitosa (código de estado 200 o 204), devuelve true
            return response.ok;
        } catch (error) {
            // Si se produce un error (por ejemplo, CORS bloqueado), devuelve false
            return false;
        }
    }

    public callFunctionComponent(refComponentName: string, functionName: string, data: any) {
        (this.$parent.$parent.$parent.$refs[refComponentName] as Vue)[functionName](data);
        (this.$parent.$parent.$parent.$refs[refComponentName] as Vue)["localReference"] = this.componentReference;
    }

    /**
     * Se invoca al dar clic en el enlace del archivo
     * @param documento - Ruta del archivo desde donde se hizo click.
     * @param key - Posición (index) del archivo en el ciclo.
     */
    public handleClick(documento: string, key: number) {

        //Valida si se quiere marcar como leido el registro
        if (this.readDocument == 'true') {
          this.callFunctionComponent(this.refModule, 'read', this.readId);
        }

        // Se actualiza 'gettingFilePath' con la key del archivo que se desea obtener.
        this.gettingFilePath = key;
        // Se actualiza 'currentFileDisplay' con la key del archivo que se desea mostrar.
        this.currentFileDisplay = key;
        // Si type es igual a 'only-link', es decir la procedencia del link es del table
        if (this.type === 'only-link') {
            // Invoca a 'callFunctionComponent' enviándole la referencia del componente, la función y la ruta del documento como parámetro
            this.callFunctionComponent('viewerDocuments', 'mostrarAdjunto', documento);
        } else { // De lo contrario, quiere decir que la acción se realizó desde la vista de detalles
            /**
             * Si route tiene un valor y el iframe esta abierto, adicional valida si el archivo que va a mostrar es igual al archivo que esta mostrando actualmente,
             * oculta el ícono (del ojo) y el iframe.
             */
            if(this.route && this.openIframe && documento == this.previousFilePath) {
                // Establece 'gettingFilePath' a null para ocultar el spinner e indicar que no se va cargar ninguna ruta de archivo.
                this.gettingFilePath = null;
                // Se actualiza 'currentFileDisplay' a null para ocultar el ícono (ojo) que indica el archivo que se esta visualizando actualmente.
                this.currentFileDisplay = null;
                // Establece 'openIframe' a false, para ocultar el iframe que este abierto
                this.openIframe = false;
            } else {
                // Invoca la función mostrar adjunto, lo que quiere decir que abrirá el iframe con un archivo
                this.mostrarAdjunto(documento, 'office');
            }
        }
    }

    /**
     * Cierra el modal donde se esta mostrando el adjunto que posiblemente proviene del listado principal (table).
     */
    closeModalViewerFile() {
        // Se restablece la variable 'currentFileDisplay' a null, indicando que no se está visualizando ningún archivo actualmente.
        this.currentFileDisplay = null;
    }

    /**
     * Obtiene el nombre del archivo a partir de una ruta de archivo dada.
     *
     * @param documento - Ruta completa del archivo desde donde se hizo click.
     * @returns - El nombre del archivo con su extensión.
     */
    public getFileName(documento) {
        // Utiliza el método split para dividir la ruta por "/"
        const parts = documento.split('/');
        // Obtiene el último elemento del array resultante, que es el nombre del archivo
        const fileNameWithExtension = parts[parts.length - 1];
        // Usar un operador ternario para recortar el nombre si tiene más de 15 caracteres
        return fileNameWithExtension.length > 15
            ? fileNameWithExtension.substring(0, 15) + '...'
            : fileNameWithExtension;
    }

    /**
     * Prepara la descarga de un archivo desde el servidor o desde AWS S3.
     *
     *  @author Seven Soluciones Informáticas S.A.S. Jun. 11 - 2024
     *
     * @param {string} documento - Ruta del archivo que se desea descargar.
     * @param {string} key - La clave que se utiliza para actualizar la propiedad `gettingFilePath` (spinner).
     * @returns {void}
     */
    public prepararDescargaArchivo(documento: string, key: any): void {
        // Se actualiza 'gettingFilePath' con la key del archivo que se desea obtener.
        this.gettingFilePath = key;
        // Obtiene la URL base del sitio
        const baseUrl = window.location.origin;
        // Combina la URL base con la ruta del archivo
        const fullUrl = baseUrl + "/storage/" + documento;
        // Función para verificar si el archivo existe en el servidor
        this.verificarExistenciaArchivo(fullUrl)
            .then(existe => {
                // Si el archivo existe en el servidor, envía la ruta a la función downloadFile y esta procede a descargar el archivo
                if (existe) {
                    this.downloadFile(fullUrl, documento);
                } else {
                    // Envía petición para descargar y/o obtener la ruta del documento a mostrar desde AWS
                    axios.post("/read-object-aws", {
                        tipoURL: "temporal_aws", // Tipo de URL, si es temporal_local, se descarga el documento en una carpeta temporal y posteriormente se muestra
                        path: documento, // Clave o ruta del archivo en AWS
                        descargarArchivo: true, // Indica si se debe codificar la url de descarga del archivo
                    })
                    .then((res) => {
                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                        // Envía la ruta a la función downloadFile y esta procede a descargar el archivo
                        this.downloadFile(dataPayload["data"], documento);
                    })
                    .catch((err) => {
                        console.log("Error al obtener el objeto de AWS", err);
                    });
                }
            });
    }

    /**
     * Descarga un archivo desde una URL y lo guarda localmente con el nombre especificado.
     *
     *  @author Seven Soluciones Informáticas S.A.S. Jun. 11 - 2024
     *
     * @param {string} url - La URL desde la que se descargará el archivo.
     * @param {string} filename - El nombre con el que se guardará el archivo descargado.
     * @returns {Promise<void>}
     */
    public async downloadFile(url: string, filename: string): Promise<void> {
        try {
            const response = await axios({
                url: url,
                method: 'GET',
                responseType: 'blob', // Importante para manejar datos binarios
            });

            // Crear un enlace temporal
            const link = document.createElement('a');
            const blob = new Blob([response.data], { type: response.data.type });
            const urlBlob = window.URL.createObjectURL(blob);
            link.href = urlBlob;
            link.download = filename.split('/').pop(); // Nombre del archivo a descargar
            document.body.appendChild(link);
            link.click();

            // Limpiar enlace temporal del DOM para liberar memoria
            window.URL.revokeObjectURL(urlBlob);
            document.body.removeChild(link);

            // Establece 'gettingFilePath' a null, indicando que la descarga se ha completado (oculta el spinner)
            this.gettingFilePath = null;
        } catch (error) {
            console.error('Error al descargar el archivo', error);
        }
    }

    }
</script>

<style>
.abrir_animacion {
  max-width: 0;
  max-height: 0;
  animation: abrirDivCancelado 0.5s ease forwards;
}
.ancho_docs{
  width:200px ;
}
@keyframes abrirDivCancelado {
  from {
    max-width: 0;
    max-height: 0;
  }

  to {
    max-width: 1000px;
    max-height: 1000px;
  }
}
</style>
