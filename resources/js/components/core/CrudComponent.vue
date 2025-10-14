<script lang="ts">

    import { Component, Prop, Vue } from "vue-property-decorator";

    import axios from "axios";
    import {jwtDecode} from 'jwt-decode';
    // import * as bootstrap from 'bootstrap';
    // import Paginate from 'vuejs-paginate'

    import utility from '../../utility';

    import { Locale } from "v-calendar";
    import { sign } from 'jsonwebtoken';
    import { data } from "jquery";


    // import * as moment from 'moment';
    // import  StarRating  from "vue-star-rating";


    const locale = new Locale();

    /**
     * Componente de crud general para todos los recursos
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 08 - 2020
     * @version 1.0.0
     */
    @Component({
        components: {
            /**
             * Se importa de esta manera por error de referencia circular
             * @see https://vuejs.org/v2/guide/components-edge-cases.html#Circular-References-Between-Components
             */
            paginate: () => import('vuejs-paginate')
        }
    })
    export default class CrudComponent extends Vue {

        /**
         * Identificador de objeto
         */
        @Prop({ type: String, default: 'id' })
        public customId: string;

        /**
         * Datos de excluidos en la exportacion de datos
         */
        @Prop({ type: Array, default: () => []})
        public excludeExportData: any;

        /**
         * Propiedades de inicializacion para dataForm
         */
        @Prop({ type: Object, default: () => ({})})
        public initValues: Object;

        /**
         * Propiedades de inicializacion para searchFields
         */
        @Prop({ type: Object, default: () => ({})})
        public initValuesSearch: Object;

        /**
         * Nombre de la entidad a afectar
         */
        @Prop({ type: String, required: true })
        public name: string;

        /**
         * Datos opcionales para usar en el crud
         */
        @Prop({ type: Object, default: () => ({})})
        public optionalData: any;

        /**
         * Datos de relaciones a exportar
         */
        @Prop({ type: Array, default: () => []})
        public relatedDataExport: any;

        /**
         * Nombre del rucurso
         */
        @Prop({ type: Object, default: () => ({default:null, get: null, post: null, put: null, delete: null})})
        public resource: any;

        /**
         * Errores del formulario
         */
        public dataErrors: any;

        /**
         * Datos del formulario
         */
        public dataForm: any;

        /**
         * Correo de soporte
         */
        public mailSupport: any;

        /**
         * Lista de elementos
         */
        public dataList: Array<any>;

        /**
         * Datos de visualizacion
         */
        public dataPaginator: any;

        /**
         * Datos de visualizacion
         */
        public dataShow: any;

        /**
         * Valida si los valores del formulario
         * son para actualizar o crear
         */
        public isUpdate: boolean;

        /**
         * Key autoincrementable y unico para
         * ayudar a refrescar un componente
         */
        public keyRefresh: number;

        /**
         * Funcionalidades de traduccion de texto
         */
        public lang: any;

        /**
         * Valida el estado del modal del formulario,
         * si habilita o cierra, se hace para recargar los elementos internos del formulario
         * cada vez que se habilita (Evita el keyrefresh para no sobrecargar la interfaz)
         */
        public openForm: boolean;

        /**
         * Campos de busqueda
         */
        public searchFields: any;

        /**
         * Campos de busqueda avanzados
         */
        public searchFieldsAdvanced: any;

        /**
         * Campos de busqueda
         */
        public searchFieldsAux: any;

        /**
         * Permite visualizar el formulario de busqueda
         * avanzada
         */
        public showSearchOptions: boolean;

        /**
         * Tipo de crud a implementar
         */
        @Prop({ type: Boolean, default: false })
        public crudAvanzado: boolean;

        /**
         * Datos de respuesta del formulario extra
         */
        public dataExtra: any;

        /**
         * Valida si de debe ejecutar la función toggle de los modales
         */
        public cerrarModal: boolean;
        public printLoading: boolean;
        public printInstance: any = null;

        /**
         * Controla si el listado principal del index se debe actualizar automáticamente dependiendo de la Prop frecuenciaDeActualización
         */
        @Prop({ type: Boolean, default: false })
        public actualizarListadoAutomatico: boolean;

        /**
         * Frecuencia de actualizado del listado en segundos, por defecto cada 10 segundos
         */
        @Prop({ type: Number, default: 120 })
        public frecuenciaDeActualización: number;

        /**
         * Titulo del swal de confirmacion
         */
        @Prop({ type: String, required: false, default: "¿Esta seguro de guardar este registro?" })
        public tituloSwal: string;

        /**
         * texto secundario del swal de confirmacion
         */
         @Prop({ type: String, required: false})
        public textoSwal: string;

        /**
         * Prop que valida si se requiere el mensaje de confirmacion en guardar y editar
         */
         @Prop({ type: Boolean, default: false })
        public swalConfirmacion: boolean;

        /**
         * Cantidad de registros que se muestran en el listado
         */
        @Prop({ type: Number, default: 15 })
        public quantityPagesItems: number;

        /**
         * Detecta el estado de carga de los archivos, si se ha terminado o no de cargar por completo los adjuntos del componente InputCrudFile
         */
        public uploadingFileInputCrudFile: boolean;

        @Prop({ type: Boolean, default: false })
        public isButtonDisabled: boolean;

        /**
         * Controla e informa si la ventana de imprimir esta abierta o no
         */
        public printOpened: boolean;

        public isTableroVisible: boolean; // Estado para controlar si el contenedor está visible

        public dataWidgets: any;


        /**
         * URL del documento que se va a mostrar en un iframe posiblemente
         */
        public routeFileUrl: string;

        /**
         * Controla si la url del documento solicitado está cargando, true = cargando, false = no esta cargando
         */
        public routeLoading: boolean;

        /**
         * Tipo de navegador en el que se está ejecutando la aplicación
         */
        public navegador: string;

        /**
         * Constructor de la clase
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
         * @version 1.0.0
         */
        constructor() {
            super();
            this.keyRefresh = 0;
            this.dataList = [];
            this.dataForm = {};
            this.dataExtra = {};
            this.dataShow = {};
            this.dataErrors = {};
            this.dataPaginator = {
                currentPage: 1,
                numPages: 1,
                pagesItems: this.quantityPagesItems,
                total: 0
            }
            this.isUpdate = false;
            this.cerrarModal = true;
            this.openForm = false;
            this.showSearchOptions = false;
            this.printOpened = false;
            this.routeFileUrl = null;
            this.routeLoading = false;
            // Return today's date and time
            let currentTime = new Date()

            // this.searchFields = {"_obj_llave_valor_": {}};
            this.searchFields = {};
            this.searchFieldsAdvanced = {
                isFocused:false
            };
            this.searchFieldsAux = {};

            // Inicializa valores del dataform
            // this._initValues();

            this.lang = (this.$parent as any).lang;
            this.uploadingFileInputCrudFile = false;
            this.isTableroVisible=false;

            this.dataWidgets = {};
            this.navegador = "";

        }

        /**
         * Se ejecuta cuando el componente ha sido creado
         */
        created() {

            //Llama el correo de comunicaciones
            this.mailSupport = window["env"].MAIL_SUPPORT;

            this.searchFields = utility.clone(this.initValuesSearch);
            // Inicializa valores del dataform
            this._initValues();
            // recuperamos el querystring (parámetros enviados por URL)
            const querystring = window.location.search
            // usando el querystring, creamos un objeto del tipo URLSearchParams
            const params = new URLSearchParams(querystring)

            if(!(params.get('qder') || params.get('qsb') || params.get('qd'))){
                if(!params.get('qus')){
                    this.crudAvanzado ? this._getDataListAvanzado() : this._getDataList();
                }
            }

            
            if(params.get('qus')){
                let parameterId = decodeURIComponent(escape(atob(params.get('qus'))));

                this.showLoadingGif(this.lang.get('trans.Loading data'))
                
                axios.get(`${this.resource.default}/${parameterId}`)
                .then((res) => {
                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                        const dataDecrypted = Object.assign({}, dataPayload);
                        // Llena la lista de datos
                        this.dataList = [dataDecrypted["data"]];

                        // Envia elemento a mostrar a la función show (Ver Detalles)
                        this.dataShow = dataDecrypted["data"];

                        this.dataPaginator.total = 1;

                        $(`#modal-view-${this.name}`).modal('toggle');
                })
                .catch((err) => {
                        console.log("Error obteniendo información del registro desde el listado el dashboard")
                })
                .finally(() => (this.$swal as any).close());
            }
            // Carga la lista de elementos dependiendo si es un crud actual o no

            //
            if(this.actualizarListadoAutomatico) {
                setInterval( function() {
                    // Carga la lista de elementos dependiendo si es un crud actual o no
                    this.crudAvanzado ? this._getDataListAvanzado(false) : this._getDataList(false);
                }.bind(this), this.frecuenciaDeActualización * 1000);
            }
        }

        /**
         * Se ejecuta cuando el componente ha sido montado
         */
        mounted() {
            this.detectarNavegador();
        }

        public checkAuth() {
            // console.log("hola");
        }

        /**
         * Habilita el formulario para agregar elementos
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 16 - 2020
         * @version 1.0.0
         */
        public add(): void {
            this.clearDataForm();
            this.isUpdate = false;
            // Habilita formulario
            this.openForm = true;
            // Actualiza componente de refresco
            this._updateKeyRefresh();
        }

        /**
         * Elemento a agregar en la lista
         *
         * @author Jhoan Sebastian Chilito S. - Ene. 23 - 2020
         * @version 1.0.0
         *
         * @param elementToAdd elemento a agregar a la lista
         */
        public addElementToList(elementToAdd: any): void {
            // Agrega elemento nuevo a la lista
            this.dataList.unshift(elementToAdd);
        }

        /**
         * Busca en la lista de elementos por cada campo disponible
         * y retorna la lista de elementos filtrados y paginados
         *
         * @author Carlos Moises Garcia T. - Ago. 09 - 2021
         * @version 2.0.0
         */
        public advancedSearchFilter(): Array<any> {


            // Se verifica si existe una lista de datos
            if (this.dataList) {

                let filters = [];

                // Valida si existe datos en los campos de busquedad
                if (Object.keys(this.searchFields).length) {
                    // Datos del filtro
                    filters = this.getFiltersFromArray(this.searchFields);
                }

                // Obtenemos las llaves de los filtros
                const filterKeys = Object.keys(filters);

                return this.dataList.filter(item => {
                    // valida todos los criterios de filtrado
                    return filterKeys.every(key => {
                        // Ignora un filtro vacio
                        if (!filters[key].length){
                            return true;
                        }

                        // Valida si el filtro es de tipo objecto
                        if (typeof filters[key] == 'object') {

                            const getValue = value => (typeof value === 'string' ? value.toUpperCase() : value);

                            //Se valida de que key no venga con . ya que esto se agrega desde el index para evitar problemas de ambiguedad
                            if(key.includes(".")){
                                //Separa el key por el punto
                                const separador = key.split(".");
                                // Iguala la variable para que tenga la key que es y no la key completa con la tabla
                                item[key] = separador[1];
                            }

                            // Valida que el dato no sea de tipo fecha
                            if (!this.isValidDate(item[key])) {
                                return filters[key].find(filter => getValue(filter) === getValue(item[key]));
                            } else {
                                // Formatea la fecha de inicio del rango
                                let startDate =  locale.format(filters[key][0], "YYYY-MM-DD");
                                 // Formatea la fecha fin del rango
                                let endDate = locale.format(filters[key][1], "YYYY-MM-DD");
                                // Formatea la fecha del listado de registros
                                let date = this.formatDate(item[key]);
                                // Compara la fecha si coinciden con el registro
                                return (date >= startDate && date <= endDate);
                            }
                        } else {

                            //Valida si el campo del filtro es typequery omite este filtro retornando true
                            if(key == "typeQuery"){
                                return true;
                            }

                            //Valida que el campo del filtro este dentro de la variable typequery
                            if(String(filters["typeQuery"]).toLowerCase().includes(key)) {
                                //Se utiliza el operador == si el filtro a evaluar esta dentro de la variable typequery
                                return String(item[key]).toUpperCase() == filters[key];
                            } else {
                                return String(item[key]).toUpperCase().includes(filters[key]);
                            }
                        }
                    });
                });
            }
            return [];
        }

        /**
         * Paginacion con busqueda avanzada
         *
         * @author Jhoan Sebastian Chilito S. - May. 26 - 2020
         * @version 1.0.0
         */
        public advancedSearchFilterPaginate(): Array<any> {
            return this.paginate(this.advancedSearchFilter());
        }

        /**
         * Actualiza un elemento de la lista
         *
         * @author Jhoan Sebastian Chilito S. - Ene. 23 - 2020
         * @version 1.0.0
         *
         * @param elementId Identificador del elemento de la lista a actualizar
         * @param elementToSet elemento actualizado
         *
         */
        public assignElementList(elementId: any, elementToSet: any): void  {
            // Actualiza elemento modificado en la lista
            Object.assign(this._findElementById(elementId, false), elementToSet);
        }

        /**
         * valida la cantidad de decimales a mostrar
         *
         * @author Carlos Moises Garcia T. - Ago. 10 - 2021
         * @version 2.0.0
         *
         * @param number dato a convertir en tipo momenda
         */
         public formatNumber(number, decimals): string {
            // Valida que no este vacio el dato a convertir
            if ( number ) {
                // Validamos que 'decimals' sea un número entero no negativo
                if (!Number.isInteger(decimals) || decimals < 0) {
                    throw new Error("El número de decimales debe ser un entero no negativo.");
                }

                return number.toFixed(decimals);
            } else {
                // Valida si el dato viene nulo
                if (number === null) {
                    return null;
                } else {
                    return number;
                }
            }

        }

        /**
         * LLama la funcion de un componente
         *
         * @author Carlos Moises Garcia T. - Sep. 30 - 2020
         * @version 1.0.0
         * @param refComponentName referencia del componente
         * @param functionName funcion a llamar del componente
         * @param data datos que se le envian a la funcion del componente
         */
        public callFunctionComponent(refComponentName: string, functionName: string, data: any) {
            (this.$refs[refComponentName] as Vue)[functionName](data);
        }

        public getValueComponentini(refComponentName: string, functionName: string, data: string) {
            // console.log((this.$refs[refComponentName] as Vue)['data'][functionName])
            return (this.$refs[refComponentName] as Vue)[data][functionName];
        }

         // Método para verificar si ciertas propiedades existen
        getValueComponent(componentName: string, propertyName: string, nestedPropertyName: string): boolean {
            // Lógica para determinar si la propiedad existe en el componente
            const component = this.$refs[componentName] as Vue | undefined;
            if (component && component[propertyName] && component[propertyName][nestedPropertyName]) {
                return true;
            } else {
                return false;
            }
        }

        public clearDataForm(): void {
            // Inicializa valores del dataform
            this._initValues();
            // Limpia errores
            this.dataErrors = {};
            // this.searchFieldsAux = {};
            // Actualiza componente de refresco
            this._updateKeyRefresh();
            // Oculta[Quita] formulario
            this.openForm = false;

            // Limpia valores del campo de archivos
            $('input[type=file]').val(null);
        }

        /**
         * Limpia los datos del fomulario de busqueda rapida
         *
         * @author Jhoan Sebastian Chilito S. - May. 27 - 2020
         * @version 1.0.0
         */
        public clearDataSearch(): void {
            this.searchFields = utility.clone(this.initValuesSearch);
        }

        /**
         * Vacia los campos del formulario
         *
         * @author Jhoan Sebastian Chilito S. - Ago. 25 - 2020
         * @version 1.0.0
         *
         * @param fields campos del dataform a vaciar
         */
        public clearFields(...fields) {
            // recorre la lista de campos a vacia
            fields.forEach((field: string) => {
                // Inicializa los campos con null
                this.dataForm[field] = null;
            });
        }

        /**
         * Convierte en numero a formato moneda
         *
         * @author Carlos Moises Garcia T. - Ago. 10 - 2021
         * @version 2.0.0
         *
         * @param number dato a convertir en tipo momenda
         */
        public currencyFormat(number) {
            // Valida que no este vacio el dato a convertir
            if ( number ) {
                // Asigna el tipo de moneda que se va a utilizar
                let currencyFormat = Intl.NumberFormat('es-CO', { maximumFractionDigits: 2 });

                // Valida si el lenguaje del sistema es ingles
                if (this.lang.locale == 'en') {
                    // Asigna el formato de moneda de dolar
                    currencyFormat = Intl.NumberFormat('en-US', { maximumFractionDigits: 5 });
                }
                // Retorna el datos formateado con el tipo de moneda
                return currencyFormat.format(number) ;
            } else {
                // Valida si el dato viene nulo
                if (number === null) {
                    return 0;
                } else {
                    return number;
                }
            }
        }

        /**
         * Descarga un archivo codificado
         *
         * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
         * @version 1.0.0
         *
         * @param file datos de archivo a construir
         * @param filename nombre de archivo
         * @param fileType tipo de archivo a exportar(extension)
         */
        public downloadFile(file: string, filename: string, fileType: string): void {
            // Crea el archivo tipo blob
            let newBlob = new Blob([file]);

            // IE no permite usar un objeto blob directamente como enlace href
            // en su lugar, es necesario usar msSaveOrOpenBlob
            if (window.navigator && window.navigator.msSaveOrOpenBlob) {
                window.navigator.msSaveOrOpenBlob(newBlob);
                return;
            }

            // Para otros navegadores:
            // Crea un enlace que apunta al ObjectURL que contiene el blob.
            const data = window.URL.createObjectURL(newBlob);
            let link = document.createElement('a');
            link.href = data;
            link.download = `${filename}.${fileType}`;
            link.click();
            setTimeout(() => {
                // Para Firefox es necesario retrasar la revocación de ObjectURL
                window.URL.revokeObjectURL(data);
            }, 100);
        }

        /**
         * Prepara el elemento a eliminar
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
         * @version 1.0.0
         *
         * @param id identificador del elemento a eliminar
         */
        public drop(id: number): void {

            // Visualiza alerta de eliminacion de elemento
            this.$swal({
                icon: 'error',
                title: this.lang.get('trans.are_you_sure_drop'),
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: this.lang.get('trans.drop'),
                cancelButtonText: this.lang.get('trans.cancel'),
                customClass: {
                    confirmButton: 'btn m-r-5 btn-danger',
                    cancelButton: 'btn btn-default'
                }
            })
            .then((res) => {
                // Valida si la opcion selecionada es positiva
                if (res.value) {
                    // Elimina el elemento
                    this._dropElement(id);
                }
            });
        }

        /**
         * Busca y prepara los datos a editar
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 15 - 2020
         * @version 1.0.0
         *
         * @param dataElement elemento a editar
         */
         public edit(dataElement: object, dates: string=''): void {
            // console.log("EJEMPLO",dataElement);
                // console.log(this.nameOptionals);
                // console.log(dataElement[this.nameOptionals.default]);
                // console.log("EJEMPLO2");
            // Valida si existe una ruta declarada para el edit
            if (this.resource.edit) {
                this.dataForm = utility.clone(dataElement);
                // Habilita formulario
                this.openForm = true;
                
                // console.log(dataElement[this.nameOptionals]);
                // Obtiene el nombre del recurso
                const resource = this.resource.edit;
                    // Envia peticion de obtener todos los datos del recurso
                axios
                    .get(`${resource}/${dataElement[this.customId]}${dates}`)
                    .then(res => {

                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                        const dataDecrypted = Object.assign({}, dataPayload);
                        // Asigna elemento a mostrar
                        this.dataForm = dataDecrypted["data"];
                        // Habilita actualizacion de datos
                        this.isUpdate = true;
                        // Actualiza componente de refresco
                        this._updateKeyRefresh();
                    })
                    .catch(err => {
                        (this.$parent as any)._pushNotification(
                            this.lang.get("Error obteniendo datos para editar."),
                            false,
                            "Error"
                        );
                    });
                

            } else {
                // Busca el elemento deseado y agrega datos al fomrulario
                this.dataForm = utility.clone(dataElement);
                // Habilita actualizacion de datos
                this.isUpdate = true;
                // Habilita formulario
                this.openForm = true;
                // Actualiza componente de refresco
                this._updateKeyRefresh();
            }
        }

        /**
         * Exporta los datos de la tabla en un archivo
         *
         * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
        public exportDataTable(fileType: string): void {
            this.showLoadingGif(this.lang.get('trans.download_report'));
            // Encripta la información del dataList
            let dataJWT = sign({data: this.getMakeDataToExport(),iat: Math.floor(Date.now() / 1000) - 60}, window["env"].JWT_SECRET_KEY);
            // Envia peticion para exportar datos de la tabla
            axios.post(`export-${this.name}`, {
                fileType,
                data: dataJWT
            }, {responseType: 'blob'})
            .then((res) => {
                // Cierra el swal
                (this.$swal as any).close()
                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        public exportFullDataTableInventorReport(fileType: string){
            this.showLoadingGif(this.lang.get('trans.download_report'));

            let dataJWT = sign({data: this.getMakeDataToExport()}, window["env"].JWT_SECRET_KEY);

            // Envia peticion para exportar datos de la tabla
            axios.post(`export-inventory-documentals-report`, {
                fileType,
                data: dataJWT
            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }



        /**
         * Exporta los datos de la tabla en un archivo de la vista de repositorio de pqr
         *
         * @author Leonardo Herrera. - Febrero. 1 - 2024
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
         public exportRepositoryPqr(fileType: string, vigency: number){
            this.showLoadingGif(this.lang.get('trans.download_report'));

            // Envia peticion para exportar datos de la tabla
            axios.post(`export-repository-pqr`, {
                fileType,
                filtros: this.advancedSearchFilterActualizado(),
                vigencia: vigency ?? null
            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        /**
         * Exporta los datos de la tabla en un archivo de la vista de repositorio de correspondencia externa
         *
         * @author Leonardo Herrera. - Febrero. 1 - 2024
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
        public exportRepositoryExternal(fileType: string, vigency: number){
            this.showLoadingGif(this.lang.get('trans.download_report'));

            // Envia peticion para exportar datos de la tabla
            axios.post(`export-repository-external`, {
                fileType,
                filtros: this.advancedSearchFilterActualizado(),
                vigencia: vigency ?? null
            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        /**
         * Exporta los datos de la tabla en un archivo de la vista de repositorio de correspondencia externa
         *
         * @author Leonardo Herrera. - Febrero. 1 - 2024
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
         public exportRepositoryExternalReceiveds(fileType: string,vigency: number){
            this.showLoadingGif(this.lang.get('trans.download_report'));
            // Envia peticion para exportar datos de la tabla
            axios.post(`export-repository-external-receiveds`, {
                fileType,
                filtros: this.advancedSearchFilterActualizado(),
                vigencia: vigency ?? null
            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

          /**
         * Exporta los datos de la tabla en un archivo de la vista de repositorio de correspondencia externa
         *
         * @author Leonardo Herrera. - Febrero. 1 - 2024
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
         public exportRepositoryCorrespondenceInternal(fileType: string,vigency: number){
            this.showLoadingGif(this.lang.get('trans.download_report'));

            // Envia peticion para exportar datos de la tabla
            axios.post(`export-repository-correspondence-internal`, {
                fileType,
                filtros: this.advancedSearchFilterActualizado(),
                vigencia: vigency ?? null

            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        /**
         * Exporta los datos de la tabla en un archivo de la vista de repositorio de correspondencia externa
         *
         * @author Leonardo Herrera. - Febrero. 1 - 2024
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         *  @param typeReport tipo de archivo a exportar
         */
         public exportDataTableSecondView(fileType: string){
            this.showLoadingGif(this.lang.get('trans.download_report'));

            let dataJWT = sign({data: this.getMakeDataToExport()}, window["env"].JWT_SECRET_KEY);

            // Envia peticion para exportar datos de la tabla
            axios.post(`export-second-view-${this.name}`, {
                fileType,
                data: dataJWT
            }, {responseType: 'blob'})
            .then((res) => {
                (this.$swal as any).close()

                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close()

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        public exportBillPDF(id: number, fileType: string): void {
            window.open('export-bill/'+id);
        }

        /**
         * Exporta los datos de la tabla en un archivo
         *
         * @author Carlos Moisés García T. - Jul. 31 - 2020
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
        public exportBilling(fileType: string): void {
            fileType = "csv";
            let data = {
                Division_Id: this.searchFields.Division_Id,
                Customer_Id: this.searchFields.Customer_Id,
                Billing_Period_Status_Id: this.searchFields.Billing_Period_Status_Id,
            };
            // Envia peticion para exportar datos de la tabla
            axios.post(`report-excel-billing`, {
                fileType,
                data: data
            }, {responseType: 'blob'})
            .then((res) => {
                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                this._pushNotification('Error exporting data', false, 'Error');
            });
        }

        /**
         * Formatea fechas
         *
         * @author Carlos Moisés García T. - Jul. 22 - 2020
         * @version 1.0.0
         *
         * @param date fecha a convertir
         */
        public formatDate(date): void {
            if( date ){
                return locale.format(date, "YYYY-MM-DD");
            }
		}

        /**
         * Formatea fechas para que muestre en este formato asi ejemplo: 26 de febrero de 2024
         *
         * @author Manuel Marin. - Feb. 21 - 2024
         * @version 1.0.0
         *
         * @param date fecha a convertir
         */
         public formatTextDate(date): string {
            // if( date ){
            //     const fechaFormateada = new Date(date).toLocaleDateString("es-ES", {
            //         day: "numeric",
            //         month: "long",
            //         year: "numeric",
            //     });
            //     return fechaFormateada;
            // }
            if (date) {
                const fechaFormateada = new Date(date).toLocaleDateString("es-ES", {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit"
                });
                return fechaFormateada;
            }

		}

        /**
         * Crea y organiza los datos de exportacion de la tabla
         *
         * @author Jhoan Sebastian Chilito S. - May. 28 - 2020
         * @version 1.0.0
         */
        public getMakeDataToExport(): any[] {
            const dataExport = utility.clone(this.advancedSearchFilter());
            // Recorre datos originales de exportacion
            dataExport.forEach((dataElement: any) => {
                // Recorre datos de exportacion
                this.excludeExportData.forEach((excludeData: string) => {
                    delete dataElement[excludeData];
                });
                // Recorre datos relacionados
                this.relatedDataExport.forEach((relatedData: any) => {
                    const relatedElement = dataElement[relatedData.name];
                    // Valida si existe el elemento relacionado
                    if (relatedElement) {
                        // Asigna el elemento valor a mostrar del objeto relacionado
                        dataElement[relatedData.name] = relatedElement[relatedData.value];
                    }
                });
            });
            return dataExport;
        }

        /**
         * Obtiene los filtros extrayendo
         * el key del arreglo por parametro
         *
         * @author Carlos Moises Garcia T. - Ago. 09 - 2021
         * @version 2.0.0
         *
         * @param list lista de datos a filtrar
         */
        public getFiltersFromArray(list): any {
            // // Filtros de busqueda

            const filters = {};
            const values: Array<string> = Object.values(list);
            Object.keys(list).forEach((key, index) => {
                // Valida que no este vacio
                if (key && values[index] != null) {
                    // Valida si el tipo de filtro a buscar es de tipo objecto
                    if (typeof values[index] == 'object') {
                        // Obtiene los valores del objecto
                        let valueArray = Object.values(values[index]);
                        let data = [];
                        // Valida que el filtro a buscar tenga mas de un dato
                        if (valueArray.length > 0) {
                            // Recorre los datos del filtro de busqueda
                            valueArray.forEach((value, index1) => {
                                // Valida si el dato que no sea de tipo fecha
                                if (isNaN(Date.parse(valueArray[index1]))) {
                                    // Asigna el dato al array
                                    data.push(valueArray[index1]);
                                } else {
                                    // Asigna el dato al array
                                    data.push(valueArray[index1]);
                                }
                            });
                            filters[key] = data;
                        } else {
                            // Valida si el valor es de tipo fecha
                            if (this.isValidDate(values[index])) {
                                // Formatea la fecha
                                let date = locale.format(values[index], "YYYY-MM-DD")
                                filters[key] = (date)? String(date).toUpperCase() : date;
                            }
                        }
                    } else {
                        filters[key] = (values[index])? String(values[index]).toUpperCase() : values[index];
                    }
                }
            });
            return filters;
        }

        /**
         * Obtiene indice recalculado actual del elemento de la tabla
         *
         * @author Jhoan Sebastian Chilito S. - May. 04 - 2020
         * @version 1.0.0
         *
         * @param currentIndx incice actual de la tabla
         */
        public getIndexItem(currentIndx: number): Number {
            const totalItemsCurrentPage: number = (this.dataPaginator.currentPage * this.dataPaginator.pagesItems) - this.dataPaginator.pagesItems + 1;
            return totalItemsCurrentPage + currentIndx;
        }

        /**
         * Obtiene una propiedad de un componente
         *
         * @author Jhoan Sebastian Chilito S. - Dic. 28 - 2020
         * @version 1.0.0
         *
         * @param refComponentName referencia del componente
         * @param propName nombre de la propiedad del componente
         */
        public getPropComponent(refComponentName: string, propName: string) {
            if (this.$refs[refComponentName] as Vue) {
                return (this.$refs[refComponentName] as Vue)[propName];
            } else {
                return null;
            }
        }

        /**
         * Evento de asignacion de archivo
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         *
         * @param event datos de evento
         * @param fieldName nombre de campo
         */
        public inputFile(event, fieldName: string): void {
            this.dataForm[fieldName] = event.target.files[0];
        }

        /**
         * Valida si un dato es una fecha
         *
         * @author Seven Soluciones Informáticas. - Ago. 09 - 2021
         * @version 1.1.0
         *
         * @param date fecha a evaluar si tiene el formato correcto
         */
        public isValidDate(date) {
            // Formatea en array el dato
            date = date.toString().split('-');

            // Valida si es un fecha, si tiene mas de una posicion es fecha
            if (date.length > 1) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Crea el formulario de datos para guardar
         *
         * @author Jhoan Sebastian Chilito S. - Jun. 26 - 2020
         * @version 1.0.0
         */
        public makeFormData1(): FormData {
            let formData = new FormData();

            // Recorre los datos del formulario
            for (const key in this.dataForm) {
                if (this.dataForm.hasOwnProperty(key)) {
                    const data = this.dataForm[key];
                    // Valida si no es un objeto y si es un archivo
                    if ( typeof data != 'object' || data instanceof File || data instanceof Date || data instanceof Array) {
                        // Valida si es un arreglo
                        if (Array.isArray(data)) {
                            data.forEach((element) => {
                                if (typeof element == 'object') {
                                    formData.append(`${key}[]`,JSON.stringify(element));
                                } else {
                                    formData.append(`${key}[]`, element);
                                }
                            });
                        } else if (data instanceof Date) {
                            formData.append(key,  locale.format(data, "YYYY-MM-DD hh:mm:ss"));
                        } else {
                            formData.append(key, data);
                        }
                    }
                }
            }
            return formData;
        }

        public makeFormData(): FormData {
             let formData = new FormData();

            // Recorre los datos del formulario
            for (const key in this.dataForm) {
                if (this.dataForm.hasOwnProperty(key)) {
                    const data = this.dataForm[key];

                    if (typeof data === 'object' && !(data instanceof File || data instanceof Date || Array.isArray(data))) {
                        // Maneja objetos específicos como 'metadatos'
                        for (const subKey in data) {
                            if (data.hasOwnProperty(subKey)) {
                                const subData = data[subKey];
                                // Si es un objeto o un arreglo dentro de 'metadatos'
                                if (typeof subData === 'object') {
                                    formData.append(`${key}[${subKey}]`, JSON.stringify(subData));
                                } else {
                                    formData.append(`${key}[${subKey}]`, subData);
                                }
                            }
                        }
                    } else {
                        // Maneja archivos, fechas y arreglos
                        if (Array.isArray(data)) {
                            data.forEach((element) => {
                                if (typeof element === 'object') {
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
                    }
                }
            }

            return formData;
        }

        /**
         * Captura evento de paginacion
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 30 - 2020
         * @version 1.0.0
         *
         * @param page numero de pagina actual
         */
        public pageEvent(page: number): void {
            this.dataPaginator.currentPage = page;
        }

        /**
         * Organiza los elementos de paginacion
         *
         * @author Jhoan Sebastian Chilito S. - May. 02 - 2020
         * @version 1.0.0
         *
         * @param list lista de elementos a paginar
         */
        public paginate(list: any[]): any[] {

            // Calcula el numero de paginas del paginador
            this.dataPaginator.numPages = Math.ceil(list.length / this.dataPaginator.pagesItems);
            // Valida si la pagina actual exede al numero de paginas
            if (this.dataPaginator.currentPage > this.dataPaginator.numPages) {
                this.pageEvent(1);
            }
            this.dataPaginator.total = list.length;
            return list.slice((this.dataPaginator.currentPage - 1) * this.dataPaginator.pagesItems, (this.dataPaginator.currentPage) * this.dataPaginator.pagesItems);
        }

        /**
         * Organiza los elementos de paginacion para el listado avanzado
         *
         * @author Seven Soluciones Informáticas S.A.S - Jun. 01 - 2023
         * @version 1.0.0
         *
         * @param list lista de elementos a paginar
         */
         public paginateAvanzado() {
            this.dataPaginator.total= this.dataPaginator.total+1;
            // Calcula el numero de paginas del paginador
            this.dataPaginator.numPages = Math.ceil(this.dataPaginator.total / this.dataPaginator.pagesItems);
            this.dataPaginator.currentPage=1;
        }

        /**
         * Guarda los datos del formulario
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
         * @version 1.0.0
         */
        public save(): void {
            
            if (this.swalConfirmacion) {
                this.$swal({
                    title: this.tituloSwal,
                    text: this.textoSwal,
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (this.isUpdate) {
                            // Actualiza un registro existente
                            this.update();
                        } else {
                            // Almacena un nuevo registro
                            this.store();
                        }
                    }
                });
            } else {
                if (this.isUpdate) {
                    // Actualiza un registro existente
                    this.update();
                } else {
                    // Almacena un nuevo registro
                    this.store();
                }
            }

        }

        /**
         * Actualiza una propiedad de un objeto
         */
        public setPropObject(dataObject: string, prop: string, value: any, event?: Event) {
            this[dataObject][prop] = value;
        }

        /**
         * Prepara la informacion para mostrar en el modal
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         *
         * @param dataElement elemento a mostrar
         */
        public show(dataElement: object): void {
            // Agrega evento para cuando se cierre el modal de detalles ejecute la función emptyDataShowCloseModal
            $(`#modal-view-${this.name}`).off('hidden.bs.modal').on('hidden.bs.modal', this.emptyDataShowCloseModal);
            this.dataShow = {};

            // Valida si existe una ruta declarada para los detalles
            if (this.resource.show) {
                // Obtiene el nombre del recurso
                const resource = this.resource.show? this.resource.show: this.resource.default;
                // Envia peticion de obtener todos los datos del recurso
                axios.get(`${resource}/${dataElement[this.customId]}`)
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Asigna elemento a mostrar
                    this.dataShow =  dataDecrypted["data"];
                })
                .catch((err) => {
                    this._pushNotification(this.lang.get('trans.Failed to get data list'), false, 'Error');
                });
            } else {
                // Asigna elemento a mostrar
                this.dataShow =  dataElement;
            }
        }

        /**
         * Envia al editar o guardar
         *
         * @author Kleverman Salazar Florez. - Nov. 18 - 2024
         * @version 1.0.0
         */
        public saveProgress(){
            // Alista el objeto FormData
            let formData = this.makeFormData();
            formData.append("type_storage","Guardar avance");
            
            // Si no tiene id relacionado es para creacion de un nuevo registro
            if(this.dataForm.id == undefined){
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
                // Abre el swal de guardando datos

                this.showLoadingGif(this.lang.get('trans.loading_save'));


                // Obtiene el nombre del recurso
                const resource = this.resource.post? this.resource.post: this.resource.default;

                // Envia peticion de guardado de datos
                axios.post(resource, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then((res) => {
                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Cierra el swal de guardando datos
                    (this.$swal as any).close();
                    // Valida el tipo de alerta que de debe mostrar
                    if (res.data.type_message) {

                        // Valida que el tipo de respuesta sea exitoso
                        if (res.data.type_message == "success") {
                            // Cierra fomrulario modal
                            $(`#modal-form-${this.name}`).modal('toggle');
                            // Limpia datos ingresados
                            this.clearDataForm();
                            // Agrega elemento nuevo a la lista
                            this.addElementToList(dataDecrypted["data"]);
                        }
                        // Abre el swal de la respusta de la peticion
                        this.$swal({
                            icon: res.data.type_message,
                            html: res.data.message,
                            allowOutsideClick: false,
                            confirmButtonText: this.lang.get('trans.Accept')
                        });
                    } else {
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}`).modal('toggle');
                        // Limpia datos ingresados
                        this.clearDataForm();

                        // Agrega elemento nuevo a la lista
                        this.addElementToList(dataDecrypted["data"]);
                        // Emite notificacion de almacenamiento de datos
                        this._pushNotification(res.data.message);
                    }
                    // Valida si esta configurado el crud como listado avanzado
                    if(this.crudAvanzado) {
                        this.paginateAvanzado();
                    }
                })
                .catch((err) => {
                    (this.$swal as any).close();

                    let errors = '';

                    // Valida si hay errores asociados al formulario
                    if (err.response.data.errors) {
                        this.dataErrors = err.response.data.errors;
                        // Reocorre la lista de campos del formulario
                        for (const key in this.dataForm) {
                            // Valida si existe un error relacionado al campo
                            if (err.response.data.errors[key]) {
                                // Agrega el error a la lista de errores
                                errors += '<br>'+err.response.data.errors[key][0];
                            }
                        }
                    }
                    else if (err.response.data) {
                        errors += '<br>'+err.response.data.message;
                    }
                    // Abre el swal para mostrar los errores
                    this.$swal({
                        icon: 'error',
                        html: this.lang.get('trans.Failed to save data'),
                        confirmButtonText: this.lang.get('trans.Accept'),
                        allowOutsideClick: false,
                    });
                });
            }
            else{
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
                // Abre el swal de guardando datos

                this.showLoadingGif(this.lang.get('trans.loading_update'));

                formData.append('_method', 'put');

                // Obtiene el nombre del recurso
                const resource = this.resource.update? this.resource.update: this.resource.default;
                // Envia peticion de guardado de datos
                axios.post(`${resource}/${this.dataForm[this.customId]}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then((res) => {
                    // Cierra el swal de guardando datos
                    (this.$swal as any).close();

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                    const dataDecrypted = Object.assign({}, dataPayload);

                    // Valida el tipo de alerta que de debe mostrar
                    if (res.data.type_message) {

                        // Valida que el tipo de respuesta sea exitoso
                        if (res.data.type_message == "success") {
                            // Cierra fomrulario modal
                            $(`#modal-form-${this.name}`).modal('toggle');
                            // Agrega elemento nuevo a la lista
                            this.assignElementList(this.dataForm[this.customId], dataDecrypted["data"]);

                            // Limpia datos ingresados
                            this.clearDataForm();
                        }
                        // Abre el swal de guardando datos
                        this.$swal({
                            icon: res.data.type_message,
                            html: res.data.message,
                            allowOutsideClick: false,
                            confirmButtonText: this.lang.get('trans.Accept')
                        });
                    } else {
                        // Cierra fomrulario modal
                        this.cerrarModal ? $(`#modal-form-${this.name}`).modal('toggle') : null;

                        // Agrega elemento nuevo a la lista
                        this.assignElementList(this.dataForm[this.customId], dataDecrypted["data"]);

                        // Limpia datos ingresados
                        this.clearDataForm();
                        // Emite notificacion de almacenamiento de datos
                        this._pushNotification(res.data.message);
                    }
                })
                .catch((err) => {
                    (this.$swal as any).close();

                    let errors = '';

                    // Valida si hay errores asociados al formulario
                    if (err.response.data.errors) {
                        this.dataErrors = err.response.data.errors;
                        // Reocorre la lista de campos del formulario
                        for (const key in this.dataForm) {
                            // Valida si existe un error relacionado al campo
                            if (err.response.data.errors[key]) {
                                // Agrega el error a la lista de errores
                                errors += '<br>'+err.response.data.errors[key][0];
                            }
                        }
                    }
                    else if (err.response.data) {
                        errors += '<br>'+err.response.data.message;
                    }
                    // Abre el swal para mostrar los errores
                    this.$swal({
                        icon: 'error',
                        html: this.lang.get('trans.Failed to save data'),
                        confirmButtonText: this.lang.get('trans.Accept'),
                        allowOutsideClick: false,
                    });
                });
            }
        }

        /**
         * Guarda la informacion en base de datos
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         */
        public store(): void {
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
            // Abre el swal de guardando datos

            this.showLoadingGif(this.lang.get('trans.loading_save'));


            // Obtiene el nombre del recurso
            const resource = this.resource.post? this.resource.post: this.resource.default;

            // Envia peticion de guardado de datos
            axios.post(resource, this.makeFormData(), { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((res) => {
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Cierra el swal de guardando datos
                (this.$swal as any).close();
                // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {

                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}`).modal('toggle');
                        // Limpia datos ingresados
                        this.clearDataForm();
                        // Agrega elemento nuevo a la lista
                        this.addElementToList(dataDecrypted["data"]);
                    }
                    // Abre el swal de la respusta de la peticion
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get('trans.Accept')
                    });
                } else {
                    // Cierra fomrulario modal
                    $(`#modal-form-${this.name}`).modal('toggle');
                    // Limpia datos ingresados
                    this.clearDataForm();

                    // Agrega elemento nuevo a la lista
                    this.addElementToList(dataDecrypted["data"]);
                    // Emite notificacion de almacenamiento de datos
                    this._pushNotification(res.data.message);
                }
                // Valida si esta configurado el crud como listado avanzado
                if(this.crudAvanzado) {
                    this.paginateAvanzado();
                }
            })
            .catch((err) => {
                (this.$swal as any).close();

                let errors = '';

                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                    // Reocorre la lista de campos del formulario
                    for (const key in this.dataForm) {
                        // Valida si existe un error relacionado al campo
                        if (err.response.data.errors[key]) {
                            // Agrega el error a la lista de errores
                            errors += '<br>'+err.response.data.errors[key][0];
                        }
                    }
                }
                else if (err.response.data) {
                    errors += '<br>'+err.response.data.message;
                }
                // Abre el swal para mostrar los errores
                this.$swal({
                    icon: 'error',
                    html: this.lang.get('trans.Failed to save data'),
                    confirmButtonText: this.lang.get('trans.Accept'),
                    allowOutsideClick: false,
                });
            });
        }

        /**
         * Permite cambiar el estado del panel de busqueda avanzada
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         */
        public toggleAdvanceSearch(): void {
            this.showSearchOptions = !this.showSearchOptions;
        }

        /**
         * Actualiza la informacion en base de datos
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         */
        public update(): void {
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
            // Abre el swal de guardando datos

            this.showLoadingGif(this.lang.get('trans.loading_update'));


            const formData: FormData = this.makeFormData();
            formData.append('_method', 'put');
            
            // Obtiene el nombre del recurso
            const resource = this.resource.update? this.resource.update: this.resource.default;
            // Envia peticion de guardado de datos
            axios.post(`${resource}/${this.dataForm[this.customId]}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
            .then((res) => {
                // Cierra el swal de guardando datos
                (this.$swal as any).close();

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {

                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {
                        // Cierra fomrulario modal
                        $(`#modal-form-${this.name}`).modal('toggle');
                        // Agrega elemento nuevo a la lista
                        this.assignElementList(this.dataForm[this.customId], dataDecrypted["data"]);

                        // Limpia datos ingresados
                        this.clearDataForm();
                    }
                    // Abre el swal de guardando datos
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get('trans.Accept')
                    });
                } else {
                    // Cierra fomrulario modal
                    this.cerrarModal ? $(`#modal-form-${this.name}`).modal('toggle') : null;

                    // Agrega elemento nuevo a la lista
                    this.assignElementList(this.dataForm[this.customId], dataDecrypted["data"]);

                    // Limpia datos ingresados
                    this.clearDataForm();
                    // Emite notificacion de almacenamiento de datos
                    this._pushNotification(res.data.message);
                }
            })
            .catch((err) => {
                (this.$swal as any).close();

                let errors = '';

                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                    // Reocorre la lista de campos del formulario
                    for (const key in this.dataForm) {
                        // Valida si existe un error relacionado al campo
                        if (err.response.data.errors[key]) {
                            // Agrega el error a la lista de errores
                            errors += '<br>'+err.response.data.errors[key][0];
                        }
                    }
                }
                else if (err.response.data) {
                    errors += '<br>'+err.response.data.message;
                }
                // Abre el swal para mostrar los errores
                this.$swal({
                    icon: 'error',
                    html: this.lang.get('trans.Failed to save data'),
                    confirmButtonText: this.lang.get('trans.Accept'),
                    allowOutsideClick: false,
                });
            });
        }

        /**
         * Elimina el elemento por el id
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
         * @version 1.0.0
         *
         * @param id identificador del elemento a eliminar
         */
        private _dropElement(id: number): void {
            // Obtiene el nombre del recurso
            const resource = this.resource.delete? this.resource.delete: this.resource.default;

             // Envia eliminacion de elemento al servidor
            axios.delete(`${resource}/${id}`)
            .then((res) => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                 // Valida el tipo de alerta que de debe mostrar
                if (res.data.type_message) {

                    // Valida que el tipo de respuesta sea exitoso
                    if (res.data.type_message == "success") {
                        this.crudAvanzado ? this._getDataListAvanzado() : this._getDataList();
                    }
                    // Abre el swal de guardando datos
                    this.$swal({
                        icon: res.data.type_message,
                        html: res.data.message,
                        allowOutsideClick: false,
                        confirmButtonText: this.lang.get('trans.Accept')
                    });
                } else {
                    // Valida si la operacion en correcta
                    if (res.data.success) {
                        this.crudAvanzado ? this._getDataListAvanzado() : this._getDataList();
                    }
                    // Emite notificacion de eliminacion de datos
                    this._pushNotification(res.data.message, res.data.success);
                }
            })
            .catch((err) => {
                this._pushNotification(this.lang.get('trans.Server error while deleting item'), false, 'Error');
            });
        }

        /**
         * Busca un elemento de la lista por el id
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 17 - 2020
         * @version 1.0.0
         *
         * @param id identificador del elemento a buscar
         * @param clone valida si el objeto retornado debe ser clonado
         */
        private _findElementById(id: number, clone: boolean): any {
            for (let i = 0; i < this.dataList.length; i++) {
                const element = this.dataList[i];
                // Busca el dato a editar
                if (element[this.customId] === id) {
                    // Valida si se debe clonar el retorno
                    return (clone)? utility.clone(element) : element;
                }
            }
        }

        /**
         * Obtiene la lista de datos
         *
         * @author Jhoan Sebastian Chilito S. - Abr. 14 - 2020
         * @version 1.0.0
         */
        private _getDataList(mostrarModalCargando = true): void {
            /**
             * Muestra el modal de cargando datos, siempre y cuando la variable mostrarModalCargando sea verdadera,
             * de ser así, significa que la petición es asíncrona; petición para actualizar el listado automáticamente
             */
            mostrarModalCargando ? this.showLoadingGif(this.lang.get('trans.Loading data')) : null;


            // Obtiene el nombre del recurso
            const resource = this.resource.get? this.resource.get: this.resource.default;

            // Envia peticion de obtener todos los datos del recurso
            axios.get(resource)
            .then((res) => {
                // Convertir el string a un objeto JavaScript
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({}, dataPayload);

                // Llena la lista de datos
                this.dataList = dataDecrypted["data"];

                // Cierra el swal de cargando datos, siempre y cuando la variable mostrarModalCargando sea falsa, significa que el listado no se actualiza automáticamente
                mostrarModalCargando ? (this.$swal as any).close() : null;
            })
            .catch((err) => {
                (this.$swal as any).close();

                let errors = '';

                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                    // Reocorre la lista de campos del formulario
                    for (const key in this.dataForm) {
                        // Valida si existe un error relacionado al campo
                        if (err.response.data.errors[key]) {
                            // Agrega el error a la lista de errores
                            errors += '<br>'+err.response.data.errors[key][0];
                        }
                    }
                }
                else if (err.response.data) {
                    errors += '<br>'+err.response.data.message;
                }
                // Abre el swal para mostrar los errores
                this.$swal({
                    icon: 'info',
                    html: "No se pudo obtener el listado de registros. Por favor, verifique la conexión o comuníquese con "+this.mailSupport+" para recibir asistencia.",
                    confirmButtonText: this.lang.get('trans.Accept'),
                    allowOutsideClick: false,
                });
            });
        }

        /**
         * Obtiene la lista de datos
         *
         * @author Seven S.A.S. - May. 5 - 2022
         * @version 1.0.0
         */
        private _getDataListAvanzado(mostrarModalCargando = true): void {
            // Abre el swal de cargando datos
            // this.$swal({
			// 	title: this.lang.get('trans.Loading data'),
			// 	allowOutsideClick: false,
			// 	onBeforeOpen: () => {
            //         (this.$swal as any).showLoading();
			// 	},
			// });

            /**
             * Muestra el modal de cargando datos, siempre y cuando la variable mostrarModalCargando sea verdadera,
             * de ser así, significa que la petición es asíncrona; petición para actualizar el listado automáticamente
             */
            mostrarModalCargando ? this.showLoadingGif(this.lang.get('trans.Loading data')) : null;

            // Obtiene el nombre del recurso
            const resource = this.resource.get? this.resource.get: this.resource.default;

            /*
             * Envia peticion de obtener todos los datos del recurso
             * @params
             * cp: currentPage
             * pi: pageItems
             * f: filtros
             * fa: filtros avanzados
             */
            this.searchFieldsAdvanced.general = this.searchFieldsAdvanced.hasOwnProperty('general') ? this.searchFieldsAdvanced.general : "";
            
            axios.get(resource+"?cp="+btoa(this.dataPaginator.currentPage)+"&pi="+btoa(this.dataPaginator.pagesItems)+"&f="+btoa(unescape(encodeURIComponent(this.advancedSearchFilterActualizado())))+"&fa="+btoa(unescape(encodeURIComponent(this.searchFieldsAdvanced.general)))+"&page="+this.dataPaginator.currentPage)
            .then((res) => {
                // Llena la lista de datos
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                const dataDecrypted = Object.assign({data:[{data:{}}]}, dataPayload);

                // Llena la lista de datos
                this.dataList = dataDecrypted["data"];
                this.dataPaginator.total = res.data.data_extra.total_registros;
                this.dataExtra = res.data.data_extra;

                // Calcula el numero de paginas del paginador
                this.dataPaginator.numPages = Math.ceil(this.dataPaginator.total / this.dataPaginator.pagesItems);
                // Valida si la pagina actual exede al numero de paginas
                if (this.dataPaginator.currentPage > this.dataPaginator.numPages && this.dataPaginator.total) {
                    this.pageEventActualizado(1);
                }

                // Cierra el swal de cargando datos, siempre y cuando la variable mostrarModalCargando sea falsa, significa que el listado no se actualiza automáticamente
                mostrarModalCargando ? (this.$swal as any).close() : null;

                if(res.data.data_extra.no_consulto == "No"){
                    this.$swal({
                        icon: res.data.data_extra.icono ? res.data.data_extra.icono : 'error',
                        html: res.data.data_extra.mensaje_enviado ? res.data.data_extra.mensaje_enviado : 'Por favor ingrese un mensaje',
                        confirmButtonText: this.lang.get('trans.Accept'),
                        allowOutsideClick: false,
                    });
                }
            })
            .catch((err) => {
                (this.$swal as any).close();

                let errors = '';
                // Valida si hay errores asociados al formulario
                if (err.response.data.errors) {
                    this.dataErrors = err.response.data.errors;
                    // Reocorre la lista de campos del formulario
                    for (const key in this.dataForm) {
                        // Valida si existe un error relacionado al campo
                        if (err.response.data.errors[key]) {
                            // Agrega el error a la lista de errores
                            errors += '<br>'+err.response.data.errors[key][0];
                        }
                    }
                }
                else if (err.response.data) {
                    errors += '<br>'+err.response.data.message;
                }

                console.log("Error al obtener la lista:"+errors)
                // Abre el swal para mostrar los errores
                this.$swal({
                    icon: 'warning',
                    html: "No se pudo obtener el listado de registros. Por favor, verifique la conexión o comuníquese con "+this.mailSupport+" para recibir asistencia.",
                    confirmButtonText: this.lang.get('trans.Accept'),
                    allowOutsideClick: false,
                });
            });
        }

        /**
         * Captura evento de paginacion
         *
         * @author Seven S.A.S. - May. 5 - 2022
         * @version 1.0.0
         *
         * @param page numero de pagina actual
         */
        public pageEventActualizado(page: number): void {
            this.dataPaginator.currentPage = page;
            this._getDataListAvanzado();
        }

        /**
         * Formatea los valores de los filtros de búsqueda y los retorna
         *
         * @author Seven S.A.S. - May. 5 - 2022
         * @version 1.0.0
         */
        public advancedSearchFilterActualizado(): string {
            // Se verifica si existe una lista de datos de los filtros de búsqueda
            if (Object.keys(this.searchFields).length != 0) {

                let filters = [];

                // Valida si existe datos en los campos de busquedad
                if (Object.keys(this.searchFields).length) {
                    // Datos del filtro
                    filters = this.getFiltersFromArray(this.searchFields);
                }

                // Obtenemos las llaves de los filtros
                let filterKeys = Object.keys(filters);
                // valida todos los criterios de filtrado
                return filterKeys.map(key => {
                    // Valida si el filtro es de tipo objecto
                    if (typeof filters[key] == 'object') {
                        // Valida que el dato no sea de tipo fecha
                        if (!this.isValidDate(filters[key])) {
                            if(Array.isArray(filters[key])){
                                let whereClause = "";
                                //Valida si el campo del filtro es typequery omite este filtro retornando true
                                if(key.indexOf("_obj_llave_valor_") != -1) {
                                    whereClause += `${key} = '${JSON.stringify(this.searchFields[key])}'`;
                                } else {
                                    filters[key].forEach((element) => {
                                        if (whereClause === "") {
                                            whereClause += `${key} = '${element}'`;
                                        } else {
                                            whereClause += ` or ${key} = '${element}'`;
                                        }
                                    });
                                    return whereClause ? "("+whereClause+")" : whereClause;
                                }

                                return whereClause;
                            }
                            // return filters[key].find(filter => getValue(filter) === getValue(item[key]));
                        } else {
                            // Formatea la fecha de inicio del rango
                            let startDate =  locale.format(filters[key][0], "YYYY-MM-DD");
                                // Formatea la fecha fin del rango
                            let endDate = locale.format(filters[key][1], "YYYY-MM-DD");
                            // Compara la fecha si coinciden con el registro
                            return "(DATE("+key+") >= '"+startDate+"' AND DATE("+key+") <= '"+endDate+"')";
                        }
                    } else {
                        //Valida si el campo del filtro es typequery omite este filtro retornando true
                        if(key.indexOf("_igual_") != -1){
                            return key.replace("_igual_", "")+"="+(typeof filters[key] == 'string' ? "'"+filters[key]+"'" : filters[key]);
                        } else {
                            return key+" LIKE '%"+filters[key]+"%'";
                        }

                    }
                }).join(" AND ");
            }

            return "";
        }

        /**
         * Limpia los datos del fomulario de busqueda rapida
         *
         * @author Seven S.A.S. - May. 5 - 2022
         * @version 1.0.0
         */
        public clearDataSearchAvanzado(): void {
            this.searchFields = utility.clone(this.initValuesSearch);
            this.searchFieldsAux = {};
            this._getDataListAvanzado();
        }

        /**
         * Exporta los datos de la tabla en un archivo
         *
         * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         */
        public exportDataTableAvanzado(fileType: string, fieldNameExported:string = ""): void {

            const fieldName = fieldNameExported === "" ? this.name : fieldNameExported;

            this.showLoadingGif(this.lang.get('trans.download_report'));

            let dataJWT = sign({data: this.getMakeDataToExport()}, window["env"].JWT_SECRET_KEY);
            // Envia peticion para exportar datos de la tabla
            axios.post(`export-${this.name}`, {
                fileType,
                data:dataJWT,
                filtros: this.advancedSearchFilterActualizado()
            }, {responseType: 'blob'})
            .then((res) => {

                // Cierra el swal
                (this.$swal as any).close();
                // Descagar el archivo generado
                this.downloadFile(res.data, fieldName, fileType);
            })
            .catch((err) => {
                // Cierra el swal
                (this.$swal as any).close();
                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }

        public exportarHistorial(fileType: string, idObjeto, rutaBuscar): void {

            this.showLoadingGif(this.lang.get('trans.download_report'));

            let dataJWT = sign({data: this.getMakeDataToExport()}, window["env"].JWT_SECRET_KEY);
            // Envia peticion para exportar datos de la tabla
            axios.post('export-historial-'+rutaBuscar+'/'+idObjeto, {
                fileType,
                data:dataJWT,
                filtros: this.advancedSearchFilterActualizado()
            }, {responseType: 'blob'})
            .then((res) => {

                // Cierra el swal
                (this.$swal as any).close();
                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
        }


        /**
         * Exporta los datos de la tabla en un archivo
         *
         * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
         * @version 1.0.0
         *
         * @param fileType tipo de archivo a exportar
         * @param rutaReporte ruta del reporte a exportar
         */
         public exportReportAvanzado(fileType: string, extraParam: string = '', rutaReporte: string = null): void {

            this.showLoadingGif(this.lang.get('trans.download_report'));

            // Envia peticion para exportar datos de la tabla
            axios.post(rutaReporte ? rutaReporte : `export-report-avanzado-${this.name}`, {
                fileType,
                extraParam,
                filtros: this.advancedSearchFilterActualizado()
            }, {responseType: 'blob'})
            .then((res) => {

                // Cierra el swal
                (this.$swal as any).close();
                // Descagar el archivo generado
                this.downloadFile(res.data, this.name, fileType);
            })
            .catch((err) => {
                (this.$swal as any).close();

                this._pushNotification(this.lang.get('trans.Data export failed'), false, 'Error');
            });
            }

        /**
         * Inicializa valores del dataform
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 07 - 2020
         * @version 1.0.0
         */
        private _initValues(): void {
            this.dataForm = utility.clone(this.initValues);
            // this.searchFields = utility.clone(this.initValuesSearch);
        }

        /**
         * Visualiza notificacion por accion
         *
         * @author Jhoan Sebastian Chilito S. - May. 09 - 2020
         * @version 1.0.0
         *
         * @param message mesaje de notificacion
         * @param isPositive valida si la notificacion debe ser posiva o negativa
         * @param title titulo de notificacion
         */
        private _pushNotification(message: string, isPositive: boolean = true, title: string = '¡Éxito!'): void {
            // Datos de notificacion (Por defecto guardar)
            const toastOptions = {
                closeButton: true,
                closeMethod: 'fadeOut',
                timeOut: 3000,
                tapToDismiss: false
            };
            // Valida el tipo de toast que se debe visualiza
            if (isPositive) {
                // Visualiza toast positivo
                toastr.success(message, title, toastOptions);
            } else {
                toastOptions.timeOut = 0;
                // Visualiza toast negativo
                toastr.error(message, title, toastOptions);
            }
        }

        /**
         * Actualiza el componente que utilize el key
         * cada vez que se cambia de editar a actualizar
         * y borrado de campos de formulario
         *
         * @author Jhoan Sebastian Chilito S. - Jul. 06 - 2020
         * @version 1.0.0
         */
        private _updateKeyRefresh(): void {
            this.keyRefresh ++;
        }

        private getFileName(attachment) {
            if (typeof attachment === 'string') {
                const parts = attachment.split('/');
                return parts[parts.length - 1];
            } else {
                // Manejar el caso en el que attachment no es una cadena (puedes devolver un valor predeterminado o manejarlo de otra manera según tus necesidades)
                console.error('attachment no es una cadena válida:', attachment);
                return 'Nombre de archivo no disponible';
            }
        }

        private showLoadingGif(mensaje) {

            this.$swal({
                html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>'+mensaje+'.</span>',
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });

        }

        /**
         * Esta función toma un texto como entrada y devuelve una versión truncada si la longitud del texto supera un máximo especificado.
         * Si el texto no es válido (undefined, null o una cadena vacía), devuelve una cadena vacía.
         *
         * @param {string} text - El texto que se va a truncar.
         * @returns {string} - El texto truncado, con una longitud máxima de 30 caracteres seguido de puntos suspensivos, si es necesario.
         *                    Si el texto no es válido, devuelve una cadena vacía.
         */
         private shortText(text,maxLength = 30) {
            if (typeof text !== 'undefined' && text !== null && text.trim() !== '') {
                if (text.length > maxLength) {
                    return { text: text.substring(0, maxLength), isLong: true };
                } else {
                    return { text: text, isLong: false };
                }
            } else {
                return { text: '', isLong: false };
            }
        }

        public executeEndpoint(endpoint:string, id?:number): void{
            // Envia peticion de obtener todos los datos del recurs
            axios.post(`${endpoint}/${id}`)
            .then((res) => {
                // Datos de notificacion (Por defecto guardar)
                const toastOptions = {
                    closeButton: true,
                    closeMethod: 'fadeOut',
                    timeOut: 3000,
                    tapToDismiss: false
                };


                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                // Agrega el elemento nuevo a la lista
                Object.assign((this.$parent as any)._findElementById(id, false), dataDecrypted["data"]);
            })
            .catch((err) => {
                // console.log('Error al obtener la lista.');
                (this.$parent as any)._pushNotification('Error al leer Corrrespondencia', false, 'info');
            });
        }


        /**
         * Genera un mensaje de confirmación utilizando los datos del formulario.
         *
         * @param {Object} dataForm - Objeto que contiene los datos del formulario.
         * @returns {string} - Mensaje de confirmación generado.
         */
        public confirmationMessage(dataForm) {
            // Texto del mensaje
            let message_confirmation_text = this.initValues['message_confirmation_text'];

            // Expresión regular para encontrar las variables
            let regex = /--(.*?)--/g;

            // Array para almacenar las variables encontradas
            let variables = [];
            let match;

            // Buscar todas las coincidencias y almacenarlas en el array de variables
            while ((match = regex.exec(message_confirmation_text)) !== null) {
                variables.push(match[1]);
            }

            // Reemplazar las variables encontradas con sus valores correspondientes
            let mensaje = message_confirmation_text;
            variables.forEach(variable => {
                mensaje = mensaje.replace(new RegExp(`--${variable}--`, 'g'), dataForm[variable] || '');
            });
            return mensaje;
        }

        /**
         * Se ejecuta cuando un modal de detalles se cierra, ya sea desde el ícono de la x que está en la esquina superior derecha,
         * desde el botón de cerrar o dando clic por fuera del modal.
         *
         * @author Seven Soluciones Informáticas S.A.S. - May. 24 - 2024
         * @version 1.0.0
         */
         public emptyDataShowCloseModal() {
            // Contrae la sección de flujo de producción documental en el panel de Seguimiento del documento de la vista de detalles
            $('.collapse').collapse('hide');
            // Vacía el objeto dataShow una vez se cierra el modal de detalles
            this.dataShow = {};
        }

        public formatTextForHtml(text) {
            return text ? text.replace(/\n/g, '<br>') : '';
        }


        public formatTextForTooltip(text) {
            // Reemplaza nuevas líneas por <br> para el tooltip
            return text.replace(/\n/g, '<br>');
        }

        public parseOpciones(opciones) {
            try {
                // Asegúrate de que opciones es un objeto
                return JSON.parse(opciones);
            } catch (e) {
                console.error('Error al parsear opciones:', e);
                return {};
            }
        }

        /**
         * Callback que se ejecuta antes de abrir la ventana de impresión.
         * Marca el indicador `printOpened` como verdadero.
         */
        public beforeOpenCallback() {
            this.printOpened = true;
        }

        /**
         * Callback que se ejecuta cuando se abre la ventana de impresión.
         */
        public openCallback() {
        }

        /**
         * Callback que se ejecuta al cerrar la ventana de impresión.
         * Marca el indicador `printOpened` como falso.
         */
        public closeCallback() {
            this.printOpened = false;
        }

        /**
         * Obtiene la url de un archivo desde el servidor o desde AWS S3.
         *
         *  @author Seven Soluciones Informáticas S.A.S. Jun. 11 - 2024
         *
         * @param {string} documento - Ruta del archivo que se desea obtener.
         * @param {boolean} openTab - Indica si el documento recuperado debe de mostrarse en una pestaña aparte.
         * @returns {string}
         */
        async getPathDocument(documento, openTab = false) {
            // Si se cumple la condición quiere decir que es una petición desde un enlace
            if(openTab) {
                // Inicializa la propiedad 'routeLoading' con un valor de true, indicando que el documento está cargando
                this.routeLoading = true;
            } else {
                // Inicializa la propiedad 'routeFileUrl' con un valor temporal mientras se verifica la existencia del documento
                this.routeFileUrl = "cargando_documento";
            }
            // Obtiene la URL base del sitio web (e.g., http://localhost:8000)
            const baseUrl = window.location.origin;
            // Construye la URL completa del documento en la carpeta 'storage'
            const fullUrl = `${baseUrl}/storage/${documento}`;
            // Llama a la función asíncrona para verificar si el archivo existe en el servidor
            const existe = await this.verificarExistenciaArchivo(fullUrl);
            if (existe) {
                // Si el archivo existe, actualiza 'routeFileUrl' con la URL completa
                this.routeFileUrl = fullUrl;
                this.routeLoading = false;
                // Si se cumple la condición, se debe de abrir el documento en una nueva pestaña
                if(openTab)
                    this.openInNewTab(this.routeFileUrl);
            } else {
                try {
                    // Si el archivo no existe, realiza una petición a la API para obtener la ruta desde AWS
                    const res = await axios.post("/read-object-aws", {
                        tipoURL: "temporal_aws", // Indica que se debe obtener una URL temporal desde AWS
                        path: documento,         // Especifica la ruta o clave del archivo en AWS
                        descargarArchivo: true,  // Indica que la URL debe permitir la descarga del archivo
                    });

                    // Decodifica la respuesta JWT si está presente y actualiza 'routeFileUrl' con la URL obtenida
                    const dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    this.routeFileUrl = dataPayload["data"];
                    this.routeLoading = false;
                    // Si se cumple la condición, se debe de abrir el documento en una nueva pestaña
                    if(openTab)
                        this.openInNewTab(dataPayload["data"]);
                } catch (err) {
                    // Muestra un mensaje de error en la consola si falla la petición a AWS
                    console.log("Error al obtener el objeto de AWS desde el CrudPrincipal", err);
                }
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

        /**
         * Abre una URL en una nueva pestaña del navegador de manera segura.
         * @param {string} url - La URL que se desea abrir en una nueva pestaña.
         */
        public openInNewTab(url) {
            // Crea un elemento <a> de forma dinámica
            const a = document.createElement('a');
            // Establece el atributo href con la URL proporcionada
            a.href = url;
            // Abre el enlace en una nueva pestaña del navegador
            a.target = '_blank';
            // Añade el atributo "rel" para prevenir vulnerabilidades de seguridad
            // "noopener" evita que la nueva pestaña tenga acceso a la ventana original
            // "noreferrer" evita que se envíe la referencia de la página
            a.rel = 'noopener noreferrer';
            // Añade el elemento <a> al DOM, aunque no sea visible
            document.body.appendChild(a);
            // Simula un clic en el elemento <a> para abrir la URL
            a.click();
            // Elimina el elemento <a> del DOM después del clic
            document.body.removeChild(a);
        }

        public getDataWidgets($url): void {

            this.isTableroVisible = !this.isTableroVisible;

            // Si se muestra, ejecutar una función para llenar los datos
            if (this.isTableroVisible) {

                this.showLoadingGif('Cargando Contadores');


                const resource = this.resource.get? this.resource.get: this.resource.default;

                // Envia peticion de obtener todos los datos del recurso
                axios.get(`${resource}/`, {
                    params: {
                        tablero: 'valores' // Aquí estamos enviando el parámetro 'tablero'
                    }
                })
                .then((res) => {

                    (this.$swal as any).close()

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({data:[{data:{}}]}, dataPayload);

                    // Llena la lista de datos
                    this.dataWidgets = res.data.data_extra;

                })
                .catch((err) => {
                    (this.$swal as any).close()

                });
            }
        }

        /**
         * Detecta el tipo de navegador actual
         */
        public detectarNavegador(): void {
            const userAgent = navigator.userAgent.toLowerCase();
            // Verificar si es Chrome, no incluye Edge ni Opera
            if (userAgent.indexOf('chrome') > -1 && userAgent.indexOf('edg') === -1 && userAgent.indexOf('opr') === -1) {
                this.navegador = 'google';
            } else {
                this.navegador = 'otro';
            }
        }

    }

</script>
