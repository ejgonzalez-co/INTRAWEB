<template>
  <div></div>
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";

import utility from "../../utility";

import { Locale } from "v-calendar";
import { jwtDecode } from "jwt-decode";

const locale = new Locale();

/**
 * Componente para agregar activos tic a la mesa de ayuda
 *
 * @author Seven Soluciones Informáticas S.A.S - Jun. 4 - 2023
 * @version 1.0.0
 */
@Component
export default class DocumentoComponent extends Vue {
  /**
   * Nombre del componente
   */
  @Prop({ type: String, required: false, default: "DocumentoComponent" })
  public nameComponent: string;

  /**
   * Nombre de la entidad a afectar
   */
  @Prop({ type: String, required: false, default: "documentos" })
  public name: string;

  /**
   * Campos de busqueda
   */
  public searchFields: any;

  /**
   * Valor del campo
   */
  // @Prop({type: Object})
  public dataForm: any;

  public dataList: any;

  /**
   * Nombre de la entidad a afectar
   */
  public btnResizeEditor: string = "Maximizar visor";

  /**
   * Valida si radito
   * son para actualizar o crear
   */
  public selectTipoValidate: boolean = false;

  // Variable para controlar el formulario que se va a ejecutar
  public formOpen: string;

  /**
   * Errores del formulario
   */
  public dataErrors: any;

  /**
   * Key autoincrementable y unico para
   * ayudar a refrescar un componente
   */
  public keyRefresh: number;

  @Prop({ type: [] })
  public listadoTiposDocumentos: any;

  public loadedImages: number;

  public loadedImagesComplete: boolean;

  public slideIndex: number = 0;

  public tiposDocumentosExpanded: boolean = false;

  /**
   * Valida si los valores del formulario
   * son para actualizar o crear
   */
  public isUpdate: boolean;

  /**
   * Funcionalidades de traduccion de texto
   */
  public lang: any;

  public crudComponent: Vue;

  /**
   * Valida para validar si se esta enviando correo,
   * para mostrar el spiner indicando el evío en la vista de detalles
   */
   public enviandoCorreo: boolean;

  /**
   * Detecta el estado de carga de los archivos, si se ha terminado o no de cargar por completo los adjuntos del componente InputCrudFile
   */
   public uploadingFileInputCrudFile: boolean;

  /**
   * Constructor de la clase
   *
   * @author Seven Soluciones Informáticas S.A.S. - Ene. 14 - 2024
   * @version 1.0.0
   */
  constructor() {
    super();
    this.searchFields = {};
    this.dataErrors = {};
    this.dataList = [];
    this.formOpen = "";
    this.dataForm = {};
    this.loadedImagesComplete = false;
    this.loadedImages = 0;
    this.keyRefresh = 0;
    this.isUpdate = false;
    // Obtiene la instancia del crudComponent
    this.crudComponent = this.$parent as Vue;
    this.lang = this.crudComponent["lang"];
    this.enviandoCorreo = false;
    this.uploadingFileInputCrudFile = false;
  }

  /**
   * Se ejecuta cuando el componente ha sido creado
   */
  created() {
    // recuperamos el querystring (parámetros enviados por URL)
    const querystring = window.location.search;
    // usando el querystring, creamos un objeto del tipo URLSearchParams
    const params = new URLSearchParams(querystring);
    // Valida si el parámetro qd (query dashboard), petición de los widgets, tiene algo quiere decir que viene a petición del dashboard
    if (params.get("qd")) {
      // Se obtiene el valor del parámtro qd (query dashboard) y se decodifíca
      let consulta_dashboard = decodeURIComponent(
        escape(atob(params.get("qd")))
      );
      this.crudComponent["searchFields"]["origen"] = consulta_dashboard;
      this.crudComponent["pageEventActualizado"](1);
    } else if (params.get("qsb")) {
      // Se obtiene el valor del parámtro qsb (query sidebar), petición de la barra lateral y se decodifíca
      let consulta_dashboard = decodeURIComponent(
        escape(atob(params.get("qsb")))
      );
      this.crudComponent["searchFields"]["state"] = consulta_dashboard;
      this.crudComponent["pageEventActualizado"](1);
    } else if (params.get("qder")) {
      // Se obtiene el valor del parámtro qder (query dashboard entradas recientes), petición de las entradas recientes
      let consulta_dashboard = decodeURIComponent(
        escape(atob(params.get("qder")))
      );
      // ac = accion. Variable que define la acción a realizar sobre el registro que se esta consultando
      let accion = params.get("ac")
        ? decodeURIComponent(escape(atob(params.get("ac"))))
        : "";
      this.crudComponent["searchFields"]["de_documento.id"] = consulta_dashboard;
      this.crudComponent["pageEventActualizado"](1);
      // Envia peticion para obtener los datos del registro a consultar según el id obtenido desde el listado del dashboard
      axios
        .get("get-documentos-show-dashboard/" + consulta_dashboard)
        .then((res) => {
          let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

          const dataDecrypted = Object.assign({}, dataPayload);

          // Valida si puede editar el documento o si la intención es ver los detalles
          if (
            !dataDecrypted["data"]["permission_edit"] ||
            accion == "ver_detalles" || dataDecrypted["data"]["estado"] == 'Pendiente de firma'
          ) {
            // Envia elemento a mostrar a la función show (Ver Detalles)
            this.crudComponent["show"](dataDecrypted["data"]);
            $(`#modal-view-${this.crudComponent["name"]}`).modal("toggle");
          } else {
            // Envia elemento a mostrar a la función edit
            this.loadDocumento(dataDecrypted["data"]);
            $(`#modal-form-${this.crudComponent["name"]}`).modal("toggle");
          }
        })
        .catch((err) => {
          console.log(
            "Error obteniendo información del registro desde el listado el dashboard",
            err
          );
        });
    }
  }

  selectTipo(tipo, contenedoresInternos) {
    jQuery(".contenedorFormFuncionarioObs").hide();
    if (!this.dataForm.estado.includes('(pendiente de enviar)')) {
      this.$set(this.dataForm, "correo_usuario_externo", "");
      this.$set(this.dataForm, "tipo_usuario", "");
      this.$set(this.dataForm, "funcionario_elaboracion_revision", "");
      this.$set(this.dataForm, "observacion", "");
    }
    this.$set(this.dataForm, "tipo", tipo);
    switch (tipo) {
      case "elaboracion": 
      
      if (this.dataForm.estado.includes('Revisión (pendiente de enviar)') || this.dataForm.estado.includes('Pendiente de firma (pendiente de enviar)') ) {
        this.$set(this.dataForm, "tipo_usuario", '');
        this.$set(this.dataForm, "observacion", '');
        this.$set(this.dataForm, "correo_usuario_externo", "");

      } else {
        this.hiddenData(this.dataForm.id);
      }
      jQuery(".contenedorFormFuncionarioObs").show("slow");
      break;


    case "revision": 
      if (this.dataForm.estado.includes('Elaboración (pendiente de enviar)') || this.dataForm.estado.includes('Pendiente de firma (pendiente de enviar)')) {
        this.$set(this.dataForm, "tipo_usuario", '');
        this.$set(this.dataForm, "observacion", '');

      } else {
        this.hiddenData(this.dataForm.id);
      }
      jQuery(".contenedorFormFuncionarioObs").show("slow");
      break;


    case "aprobacion": 
      jQuery(".contenedorFormFuncionarioObs").show("slow");
    break;


    case "firmar_varios": 
      if (this.dataForm.estado.includes('Revisión (pendiente de enviar)') || this.dataForm.estado.includes('Elaboración (pendiente de enviar)') ) {
          this.$set(this.dataForm, "tipo_usuario", '');
          this.$set(this.dataForm, "observacion", '');
          this.$set(this.dataForm, "correo_usuario_externo", "");

        } else {
          this.hiddenData(this.dataForm.id);
        }
      jQuery(".contenedorFormFuncionarioObs").show("slow");
      break;

    }
  }

  public hiddenData(id) {
  return axios
    .get(`get-hidden-data/${id}`)
    .then((res) => {
      let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
      const dataDecrypted = Object.assign({data:{}}, dataPayload);
      
      if (this.dataForm.estado.includes('(pendiente de enviar)')) {
        this.$set(this.dataForm, "tipo_usuario", dataDecrypted.data[0].tipo_usuario);
        this.$set(this.dataForm, "observacion", dataDecrypted.data[0].observacion);
        this.$set(this.dataForm, "correo_usuario_externo", dataDecrypted.data[0].correo_usuario_externo);
      }
    })
    .catch((error) => {
      console.error('Error fetching hidden data:', error);
    });
}


  public showSlides() {
    const slides = document.getElementsByClassName("templateTD");
    if (this.slideIndex >= slides.length - 4) {
      this.slideIndex = 0;
    }
    if (this.slideIndex < 0) {
      this.slideIndex = slides.length - 5;
    }

    for (let i = 0; i < slides.length; i++) {
      slides[i]["style"].transform = `translateX(-${this.slideIndex * 100}%)`; // Aplicamos la transformación a cada slide
    }
  }

  public plusSlides(n: number) {
    this.slideIndex += n;
    this.showSlides();
  }

  public imageLoaded() {
    this.loadedImages++;
    if (this.loadedImages === this.listadoTiposDocumentos.length) {
      this.loadedImagesComplete = true;
    }
  }

  /**
   *
   */
  public resizeTiposDocumentos() {
    const slides = document.getElementsByClassName("templateTD");

    for (let i = 0; i < slides.length; i++) {
      slides[i]["style"].transform = `translateX(0%)`; // Aplicamos la transformación a cada slide
    }
    this.tiposDocumentosExpanded = !this.tiposDocumentosExpanded;
  }

  /**
   * Redimensiona la vista del visor del documento y el formulario, aumenta en un 70% la mitad del visor
   */
  public resizeEditor() {
    this.btnResizeEditor === "Maximizar visor"
      ? jQuery("#formularioIzq").css("max-width", "30%")
      : jQuery("#formularioIzq").css("max-width", "100%");
    this.btnResizeEditor =
      this.btnResizeEditor === "Maximizar visor"
        ? "Minimizar visor"
        : "Maximizar visor";
  }

  /**
   * Crea el formulario de datos para guardar
   *
   * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
   * @version 1.0.0
   */
  public makeFormData(): FormData {
    let formData = new FormData();
    // Recorre los datos del formulario
    for (const key in this.dataForm) {
      if (this.dataForm.hasOwnProperty(key)) {
        const data = this.dataForm[key];
        // Valida si no es un objeto y si es un archivo
        if (
          typeof data != "object" ||
          data instanceof File ||
          data instanceof Date ||
          data instanceof Array
        ) {
          // Valida si es un arreglo
          if (Array.isArray(data)) {
            data.forEach((element) => {
              if (typeof element == "object") {
                formData.append(`${key}[]`, JSON.stringify(element));
              } else {
                formData.append(`${key}[]`, element);
              }
            });
          } else if (data instanceof Date) {
            formData.append(key, locale.format(data, "YYYY-MM-DD hh:mm:ss"));
          } else {
            formData.append(key, data);
          }
        } else if (typeof data == "object" && data !== null) {
          formData.append(`${key}`, JSON.stringify(this.dataForm[key]));
        }
      }
    }
    return formData;
  }

  /**
   * Guarda la informacion en base de datos
   *
   * @author Seven Soluciones Informáticas S.A.S - Ene. 14 - 2024
   * @version 1.0.0
   */
  public tipoDocumentoPlantilla(
    plantilla: string,
    idTipoPlantilla: number,
    modo_plantilla: string
  ): void {
    this.clearDataForm();
    this.isUpdate = false;
    this.$set(this.dataForm, "destinatarios", "");
    this.$set(this.dataForm, "subestado_documento", "");
    this.$set(this.dataForm, "plantilla", plantilla);
    this.$set(this.dataForm, "de_tipos_documentos_id", idTipoPlantilla);
    // $('#modal-form-documentos').modal('show');
    this.formOpen = "tipoDocumento";
  }

  public loadDocumento(dataElement): void {
    // this.clearDataForm();
    // Habilita actualizacion de datos
    this.isUpdate = true;
    this.dataForm = utility.clone(dataElement);

    if (this.dataForm.estado.includes('Elaboración (pendiente de enviar)')) {
        this.$set(this.dataForm, "tipo", "elaboracion");
      } else if(this.dataForm.estado.includes('Revisión (pendiente de enviar)')  ){
        this.$set(this.dataForm, "tipo", "revision");
      } else if(this.dataForm.estado.includes('Pendiente de firma (pendiente de enviar)') ||  this.dataForm.estado.includes('Devuelto para modificaciones') ){
        this.$set(this.dataForm, "tipo", "firmar_varios");
      }
    else{
      this.$set(this.dataForm, "tipo", "publicacion");
    }

    // Asigna el valor de la observación a una variable temporal para mostrar en la vista
    this.$set(this.dataForm, "observacion_previa", this.dataForm.observacion);
    // Selecciona el tipo de acción en el documento según el estado del documento
    this.selectTipo(this.dataForm.tipo, null);
    // Si el editor esta maximizado, lo pone divido por defecto
    this.btnResizeEditor === "Minimizar visor" ? this.resizeEditor() : null;
    // $(`#modal-form-${this.name}`).modal("show");
    this.formOpen = "FormularioDocumento";
  }

  /**
   * Muestra el formulario e información para la firma o devolución del documento
   *
   * @author Seven Soluciones Informáticas S.A.S. - Abr. 04 - 2024
   * @version 1.0.0
   *
   * @param dataElement datos del documento a firmar
   */
  public firmarDocumento(dataElement: object): void {
    // Busca el elemento deseado y agrega datos al fomrulario
    this.dataForm = utility.clone(dataElement);
    this.$set(this.dataForm, "accion_documento", "");
    // Habilita actualizacion de datos
    this.isUpdate = true;
    // Si el editor esta maximizado, lo pone divido por defecto
    this.btnResizeEditor === "Minimizar visor" ? this.resizeEditor() : null;
    // Habilita formulario
    this.formOpen = "FormularioFirmarDocumento";
  }

  /**
   * Crea el documento electrónico junto con la plantilla
   *
   * @author Seven Soluciones Informáticas S.A.S - May. 9 - 2023
   * @version 1.0.0
   *
   */
  public async crearDocumento(): Promise<void> {
    try {
      this.crudComponent["showLoadingGif"](
        "Creando documento, Por favor espere un momento."
      );
      // Si el origen del documento es diferente a producirlo en Intraweb, este se publicará en su formato original
      if (
        this.dataForm.origen_documento !=
        "Producir documento en línea a través de Intraweb"
      ) {
        this.$set(this.dataForm, "formato_publicacion", "Formato original");
      }

      const res = await axios.post("crear-documento", this.makeFormData(), {
        headers: { "Content-Type": "multipart/form-data" },
      });

      if (
        res.data.type_message === "error" ||
        res.data.type_message === "info"
      ) {
        this.$swal({
          icon: res.data.type_message,
          html: res.data.message,
          allowOutsideClick: false,
          confirmButtonText: this.lang.get("trans.Accept"),
        });
      } else {
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const dataDecrypted = Object.assign({}, dataPayload);

        (this.$swal as any).close();

        this.formOpen = "FormularioDocumento";
        this.$set(this.dataForm, "tipo", "publicacion");
        this.$set(this.dataForm, "metadatos", {});
        // dataDecrypted["data"]["document_pdf"] = dataDecrypted["data"]["document_pdf"].split(",");
        Object.assign(this.dataForm, dataDecrypted["data"]);

        $("#modal-form-documentos-tipo-documento").modal("toggle");
        $(".modal").css("overflow-y", "auto");
        $(`#modal-form-${this.name}`).modal("show");

        this.isUpdate = true;

        this.crudComponent["addElementToList"](dataDecrypted["data"]);

        this.crudComponent["dataPaginator"].total++;
        this.crudComponent["dataPaginator"].numPages = Math.ceil(
          this.crudComponent["dataPaginator"].total /
            this.crudComponent["dataPaginator"].pagesItems
        );
        this.crudComponent["dataPaginator"].currentPage = 1;
      }
    } catch (err) {
      this.$swal({
        icon: "error",
        html: err.message,
        allowOutsideClick: false,
        confirmButtonText: this.lang.get("trans.Accept"),
      });
    }
  }

  /**
   * Función para actualizar el documento
   */
  public async actualizarDocumento(typeStorage:string = "publicar") {

    // Si la variable es true, quiere decir que aún no se ha terminado la carga completa de archivos al servidor
    if(this.uploadingFileInputCrudFile) {
        // Muestra un mensaje al usuario indicando que aún faltan archivos por cargar en su totalidad en el servidor
        this.$swal({
            icon: "warning",
            html: "Por favor, espere hasta que se terminen de cargar los adjuntos completamente.",
            allowOutsideClick: false,
            confirmButtonText: this.lang.get("trans.Accept"),
        });
        // Retorna para que no continue el flujo
        return;
    }

    const formData: FormData = this.makeFormData();

    formData.append("_method", "put");
    formData.append("type_storage", typeStorage);

    this.$swal({
      html: this.crearMensaje(formData,typeStorage),
      allowOutsideClick: false,
      confirmButtonText: "Aceptar",
      cancelButtonText: "Cancelar",
      showCancelButton: true,
      icon: "info",
      preConfirm: async () => {
        try {
          // Muestra el mensaje de carga
          this.crudComponent["showLoadingGif"](
            this.lang.get("trans.loading_update")
          );

          const res = await axios.post(
            `${this.name}/${this.dataForm["id"]}`,
            formData,
            {
              headers: { "Content-Type": "multipart/form-data" },
            }
          );

          // Cierra el mensaje de carga
          (this.$swal as any).close();

          // Valida el tipo de alerta que debe mostrarse
          if (res.data.type_message) {
            // Muestra la alerta
            this.$swal({
              icon: res.data.type_message,
              html: res.data.message,
              allowOutsideClick: false,
              confirmButtonText: this.lang.get("trans.Accept"),
            });

            if (res.data.type_message === "success") {
              let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
              const dataDecrypted = Object.assign({}, dataPayload);

              // Cierra el formulario modal
              $(`#modal-form-${this.name}`).modal("toggle");

              // Agrega el elemento nuevo a la lista
              Object.assign(
                this.crudComponent["_findElementById"](
                  this.dataForm["id"],
                  false
                ),
                dataDecrypted["data"]
              );

              // Limpia los datos ingresados
              this.clearDataForm();
            }
          }
        } catch (err) {
          (this.$swal as any).close();
          let errors = "";

          if (err.response.data.errors) {
            this.dataErrors = err.response.data.errors;
            for (const key in this.dataForm) {
              if (err.response.data.errors[key]) {
                errors += "<br>" + err.response.data.errors[key][0];
              }
            }
          } else if (err.response.data) {
            errors += "<br>" + err.response.data.message;
          }

          // Muestra los errores
          this.$swal({
            icon: "error",
            html: this.lang.get("trans.Failed to save data") + errors,
            allowOutsideClick: false,
          });
        }
      },
    });
  }

  /**
   * Limpia los datos del fomulario
   *
   * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
   * @version 1.0.0
   */
  public clearDataForm(): void {
    // Inicializa valores del dataform
    this._initValues();
    // Inicializa valores del datashow
    this.crudComponent["dataShow"] = {};
    // Limpia errores
    this.dataErrors = {};
    // Actualiza componente de refresco
    // this._updateKeyRefresh();
    // Limpia valores del campo de archivos
    $("input[type=file]").val(null);
  }

  /**
   * Inicializa valores del dataform
   *
   * @author Seven Soluciones Informáticas. - Mar. 20 - 2024
   * @version 1.0.0
   */
  private _initValues(): void {
    this.dataForm = utility.clone(this.crudComponent["initValues"]);
    this.searchFields = utility.clone(this.crudComponent["initValuesSearch"]);
  }

  /**
   * Actualiza el componente que utilize el key
   * cada vez que se cambia de editar a actualizar
   * y borrado de campos de formulario
   *
   * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
   * @version 1.0.0
   */
  private _updateKeyRefresh_disabled(): void {
    this.keyRefresh++;
    this.crudComponent["_updateKeyRefresh"]();
  }

  /**
   * Evento de asignacion de archivo
   *
   * @author Seven Soluciones Informáticas S.A.S. - Mar. 04 - 2024
   * @version 1.0.0
   *
   * @param event datos de evento
   * @param fieldName nombre de campo
   */
  public inputFile(event, fieldName: string): void {
    this.dataForm[fieldName] = event.target.files[0];
  }

  /**
   * Asignar leido a la anotación del documento electrónico
   *
   * @author Seven Soluciones Informáticas. Mar 18 - 2024
   * @version 1.0.0
   *
   * @param id ID del documento electrónico
   */
  public leerAnotaciones(id: number): void {
    // Envia peticion de obtener todos los datos del recurs
    axios
      .post("documentos-leido-anotacion/" + id)
      .then((res) => {
        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
        const dataDecrypted = Object.assign({}, dataPayload);

        // Agrega el elemento nuevo a la lista
        Object.assign(
          this.crudComponent["_findElementById"](id, false),
          dataDecrypted["data"]
        );
      })
      .catch((err) => {
        this.crudComponent["_pushNotification"](
          "Error al leer Corrrespondencia",
          false,
          "Error"
        );
      });
  }

  /**
   * Formatea el valor ingresado en el campo nombre_metadato y variable_documento para lo asigna al campo variable_documento
   * reemplazando espacios por _, mayúsculas por minúsculas y quitando caractéres especiales, de los metadatos
   */
  public asignarVariableMetadato(event): void {
    // Formatea el valor ingresado en el campo nombre_metadato
    let variable_metadato = this.reemplazarCaracteres(event.target.value);
    if (variable_metadato) {
      // Asigna el valor formateado al campo variable_documento
      this.$set(this.crudComponent.$refs["metadatos_tipo_documento"]["dataForm"], "variable_documento_aux", variable_metadato);
      this.$set(this.crudComponent.$refs["metadatos_tipo_documento"]["dataForm"], "variable_documento", "#metadato_"+variable_metadato);
    } else {
      // Asigna vacio al campo variable_documento
      this.$set(this.crudComponent.$refs["metadatos_tipo_documento"]["dataForm"], "variable_documento", "");
    }
  }

  public reemplazarCaracteres(texto) {
    return texto
      .replace(/\s+/g, "_")
      .toLowerCase()
      .replace(/á/g, "a")
      .replace(/é/g, "e")
      .replace(/í/g, "i")
      .replace(/ó/g, "o")
      .replace(/ú/g, "u")
      .replace(/ñ/g, "n")
      .replace(/[^a-zA-Z0-9_]/g, "");
  }

  /**
   * Asignar leído al documento
   *
   * @author Seven Soluciones Informáticas S.A.S. Abr 3 - 2024
   * @version 1.0.0
   *
   * @param id ID del documento electrónico
   */
  public leido(id: number): void {
    // Envia peticion de obtener todos los datos del recurs
    axios
      .post("documentos-leido/" + id)
      .then((res) => {
        // Datos de notificacion (Por defecto guardar)
        const toastOptions = {
          closeButton: true,
          closeMethod: "fadeOut",
          timeOut: 3000,
          tapToDismiss: false,
        };

        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

        const dataDecrypted = Object.assign({}, dataPayload);

        // Agrega el elemento nuevo a la lista
        Object.assign(
          this.crudComponent["_findElementById"](id, false),
          dataDecrypted["data"]
        );

        // Visualiza toast positivo
      })
      .catch((err) => {
        // console.log('Error al obtener la lista.');
        this.crudComponent["_pushNotification"](
          "Error al leer el documento electrónico",
          false,
          "Error"
        );
      });
  }

  /**
   * Guarda la información del formulario dinamico
   *
   * @author Seven Soluciones Informáticas S.A.S. - Abr. 04 - 2024
   * @version 1.0.0
   */
  public FirmaDocumento() {
    let titulo =
      this.dataForm.accion_documento == "Aprobar Firma"
        ? "¿Desea aprobar y firmar el documento " + this.dataForm.titulo_asunto + "?"
        : "¿Desea devolver para modificaciones el documento " +
          this.dataForm.titulo_asunto + "?";
    this.$swal({
      title: titulo,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si",
      cancelButtonText: "No",
    }).then((result: any) => {
      // Valida si se confirma el mensaje
      if (result.value) {
        // Almacena la informacion del formulario
        this.crudComponent["showLoadingGif"](
          this.lang.get("trans.loading_update")
        );

        // Construye los datos del formulario
        const formData: FormData = this.makeFormData();
        // Valida que el metodo http sea PUT
        if (this.isUpdate) {
          formData.append("_method", "put");
        }
        // Envia peticion de guardado de datos
        axios
          .post("firmar-documento", formData, {
            headers: { "Content-Type": "multipart/form-data" },
          })
          .then((res) => {
            // Cierra el mensaje de carga
            (this.$swal as any).close();

            // Valida el tipo de alerta que debe mostrarse
            if (res.data.type_message) {
                // Muestra la alerta
                this.$swal({
                    icon: res.data.type_message,
                    html: res.data.message,
                    allowOutsideClick: false,
                    confirmButtonText: this.lang.get("trans.Accept"),
                });
                this.crudComponent["_getDataListAvanzado"](false);
                if (res.data.type_message === "success") {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Cierra el formulario modal
                    $(`#modal-aprobar-cancelar-firma`).modal("toggle");

                    // Agrega el elemento nuevo a la lista
                    Object.assign(
                        this.crudComponent["_findElementById"](
                            this.dataForm["id"],
                            false
                        ),
                        dataDecrypted["data"]
                    );

                    // Limpia los datos ingresados
                    this.clearDataForm();
                }
            }
          })
          .catch((err) => {
            console.log("Error al enviar el formualario dinamico", err);
            (this.$swal as any).close();
            // Emite notificacion de almacenamiento de datos
            this.crudComponent["pushNotification"](
              "Error",
              err.response.data.message,
              false
            );
            // Valida si hay errores asociados al formulario
            if (err.response.data.errors) {
              // this.dataErrors = err.response.data.errors;
            }
          });
      }
    });
  }

  /**
   * Mensaje de confirmación para el usuario a la hora de seleccionar un tipo de estado del flujo (Elaboración, revisión, firmar y publicar)
   *
   * @param formData dataForm del registro
   */
  public crearMensaje(formData,typeStorage:string) {
    let mensaje = "";
    // Optimización para el tipo "publicacion"
    // Tipo de acción a realizar en el documento
    let tipo = typeStorage == 'guardar_avance' ? 'guardar_avance' : formData.get("tipo");
    let origen_documento = formData.get("origen_documento");

    let tipo_usuario = formData.get("tipo_usuario");

    let fullname = "";
    // Valida si el estado es elaboración o revisión, quiere decir que es un solo usuario
    if(tipo == "elaboracion" || tipo == "revision") {
        // Si el tipo de usuario es Externo, se obtiene el nombre del campo funcionario_elaboracion_revision
        if(tipo_usuario == 'Externo') {
            fullname = formData.get("funcionario_elaboracion_revision");
        } else {
            // De lo contrario, se obtiene el nombre completo del objeto funcionario_elaboracion_revision_object
            fullname = JSON.parse(formData.get("funcionario_elaboracion_revision_object")).fullname;
        }
    }

    switch (tipo) {
      case "guardar_avance":
        mensaje += "Se guardará el avance del documento";
        break;
      case "elaboracion":
        mensaje += "El documento será enviado a elaboración a: <strong>"+fullname+"</strong>.";
        break;
      case "revision":
        mensaje += "El documento será enviado a revisión a: <strong>"+fullname+"</strong>.";
        break;
      case "firmar_varios":
        let de_documento_firmars = formData.getAll("de_documento_firmars[]");
        let usuarios_firmar = "";
        if(de_documento_firmars.length > 2) {
            let usuario_firmar1 = JSON.parse(de_documento_firmars[0]);
            let usuario_firmar2 = JSON.parse(de_documento_firmars[1]);

            usuarios_firmar = (usuario_firmar1.tipo_usuario == "Interno" ? usuario_firmar1.usuarios.fullname : usuario_firmar1.nombre_usuario)+", "+
                              (usuario_firmar2.tipo_usuario == "Interno" ? usuario_firmar2.usuarios.fullname : usuario_firmar2.nombre_usuario)+" y otros";
        } else {
            de_documento_firmars.forEach(element => {
                let usuario_firmar = JSON.parse(element);
                usuario_firmar = usuario_firmar.tipo_usuario == "Interno" ? usuario_firmar.usuarios.fullname : usuario_firmar.nombre_usuario
                usuarios_firmar += usuarios_firmar ? " y "+usuario_firmar : usuario_firmar;
            });
        }


        mensaje += "El documento será enviado a firma conjunta a los usuarios: <strong>"+usuarios_firmar+"</strong>."
        break;
      case "publicacion":
        mensaje += origen_documento == "Producir documento en línea a través de Intraweb" ? "El documento será firmado y públicado." : "El documento será públicado.";
        break;
      default:
        mensaje += "No se especificó el proceso de publicación.";
    }

    return mensaje;
  }

  /**
   * Inicializa campos por defecto del formulario de tipo de documento
   */
  public inicializarValoresTipoDocumento() {
    /*
     * Se inicializan los campos formato_consecutivo_value y prefijo_incrementan_consecutivo_value para ayudar al usuario a
     * escoger un formato por defecto para el consecutivo del documento
     */
    this.$set(this.crudComponent["dataForm"], "formato_consecutivo_value", ["vigencia_actual", "prefijo_documento", "consecutivo_documento"]);
    this.$set(this.crudComponent["dataForm"], "prefijo_incrementan_consecutivo_value", ["vigencia_actual", "prefijo_documento"]);
  }

  /**
   * Función para copiar la ruta del enlace de firma del documento en el portapapeles
   * @param event Enlace de la firma
   */
  public copiarEnlace(event) {
    // Se obtiene la ruta definida del documento para firmar el usuario externo
    const enlace = event.target;
    const url = enlace.href;
    // Previene el comportamiento predeterminado del evento `click`
    event.preventDefault();
    // Copia la URL al portapapeles
    navigator.clipboard.writeText(url);
    // Se visualiza la notificación al usuario
    this.crudComponent["_pushNotification"](
        "URL copiada al portapapeles"
    );
  }

  /**
   * Función para reenviar el correo electrónico del documento electrónico a firmar a un usuario externo
   * @param datosFirma datos del registro de la firma del usuario externo2
   */
  public reenviarCorreoUsuarioExterno(datosFirma) {
    // Se habilita la bandera de enviando correo para mostrar el spiner en la vista
    this.enviandoCorreo = true;
    // Envia petición para el envío del correo según el registro del usuario
    axios
        .post("reenviar-correo-usuario-externo", datosFirma, {
            headers: { "Content-Type": "multipart/form-data" },
        })
        .then((res) => {
            this.enviandoCorreo = false;
            // Valida el tipo de alerta que debe mostrarse
            if (res.data.type_message) {
                // Notifica al usuario que el correo ha sido reenviado con éxito
                this.crudComponent["_pushNotification"](
                    "Correo electrónico reenviado al usuario externo",
                    true
                );
            }
        })
        .catch((err) => {
            console.log("Error al reenviar el correo electrónico al usuario externo", err);
            (this.$swal as any).close();
            // Emite notificacion de almacenamiento de datos
            this.crudComponent["_pushNotification"](
                "Error al reenviar el correo electrónico",
                false
            );
        });
  }
}
</script>
