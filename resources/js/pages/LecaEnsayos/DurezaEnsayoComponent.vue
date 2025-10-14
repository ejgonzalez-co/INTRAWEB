<template>
    <div class="m-1">
        <!-- begin breadcrumb -->
        <ol class="breadcrumb m-b-10">
            <li class="breadcrumb-item">
                <a href="/">Inicio</a>
            </li>
        </ol>
        <!-- end breadcrumb -->
        <h1 class="page-header text-center m-b-35">
            <b>Ensayo - {{ titulo }}</b>
        </h1>
        <div class="row">
            <div class="col m-5">
                <a
                    href="javascript:location.reload()"
                    class="btn btn-primary m-b-10 mt-3"
                    ><i class="fas fa-sync mr-2"></i> Actualizar página</a
                >
            </div>
            <div class="col m-5"></div>
            <div class="col m-5"></div>
            <div class="col m-5">
                <div class="panel" data-sortable-id="ui-general-1">
                    <div class="panel-body text-center">
                        <label
                            ><b
                                >Usted se encuentra analizando la muestra con
                                código:</b
                            ></label
                        >
                        <br />
                        <h3 class="text-danger">{{ codigoMuestra }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel mt-3" data-sortable-id="ui-general-1">
            <div class="panel-heading mt-3">
                <h4 class="panel-title" style="font-size: 18px">
                    Información de las muestras a ensayar
                </h4>
            </div>
            <div class="panel-body mt-3">
                <div class="row">
                    <div class="col">
                        <button
                            @click="cargaMuestras()"
                            type="button"
                            class="btn btn-primary m-b-10"
                            data-backdrop="static"
                            data-target="#modal-tendido-muestras"
                            data-toggle="modal"
                        >
                            Seleccione el tendido de muestras para este ensayo
                        </button>
                        <br /><br /><br />
                        <label class="m-b-10" for="tendido"
                            ><b>Tendido de muestras seleccionadas:</b></label
                        >
                        <br />
                        <select
                            @change="cargaCodigo()"
                            v-model="selectMue"
                            class="m-b-10 form-control text-center mt-3"
                            id="tendido"
                        >
                            <option value="">Seleccione</option>
                            <option
                                v-for="(item, key) in listadoTendido"
                                :key="key"
                                :value="item"
                            >
                                {{ item.sample_reception_code }}
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <h4
                            class="text-center"
                            style="color: white; background-color: gray"
                        >
                            Tendido de muestras finalizadas
                        </h4>
                        <div class="m-5">
                            <div class="mt-3">
                                <!-- Aqui va la tabla -->
                                <div class="table-responsive">
                                    <table-component
                                        id="Toma-de-muestra-table"
                                        :data="advancedSearchFilterPaginate()"
                                        sort-by="Toma-de-muestra"
                                        sort-order="asc"
                                        table-class="table table-hover m-b-0"
                                        :show-filter="false"
                                        :pagination="dataPaginator"
                                        :show-caption="false"
                                        filter-placeholder="Filtro rápido"
                                        filter-no-results="No hay registros"
                                        filter-input-class="form-control col-md-4"
                                        :cache-lifetime="0"
                                    >
                                        <table-column
                                            show="codigo_muestra"
                                            label="Identificación de la muestra"
                                        ></table-column>
                                        <table-column
                                            show="created_at"
                                            label="Fecha y hora del análisis"
                                        ></table-column>
                                    </table-component>
                                </div>

                                <div class="p-b-15 text-center">
                                    <!-- Cantidad de elementos a mostrar -->
                                    <div
                                        class="form-group row m-5 text-center d-sm-inline-flex"
                                    >
                                        <div class="row mt-3 paginatorclass">
                                            <div class="col">
                                                <paginate
                                                    v-model="
                                                        dataPaginator.currentPage
                                                    "
                                                    :page-count="
                                                        dataPaginator.numPages
                                                    "
                                                    :click-handler="pageEvent"
                                                    :prev-text="'Anterior'"
                                                    :next-text="'Siguiente'"
                                                    :container-class="
                                                        'pagination m-10'
                                                    "
                                                    :page-class="'page-item'"
                                                    :page-link-class="
                                                        'page-link'
                                                    "
                                                    :prev-class="'page-item'"
                                                    :next-class="'page-item'"
                                                    :prev-link-class="
                                                        'page-link'
                                                    "
                                                    :next-link-class="
                                                        'page-link'
                                                    "
                                                    :disabled-class="
                                                        'ignore disabled'
                                                    "
                                                >
                                                </paginate>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel mt-3" data-sortable-id="ui-general-1">
            <div class="panel-heading ui-sortable-handle mt-3">
                <h4 class="panel-title" style="font-size: 18px">
                    Ingrese los datos para la ejecución del ensayo
                </h4>
            </div>
            <div class="panel-body mt-3">
                <!-- formulario de ejecucion ensayo -->
                <div class="panel" data-sortable-id="ui-general-1">
                    <div class="panel-body">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <!-- Proceso -->
                                <div class="form-group row m-b-15">
                                    <label
                                        class="text-inverse text-left col-6 required"
                                        >Volumen muestra:</label
                                    >
                                    <div class="col-md-5">
                                        <!-- <input
                                           class="form-group form-control"
                                            type="number"
                                            v-model="dataForm.volumen_muestra"
                                        /> -->
                                        <currency-input
                                            v-model="dataForm.volumen_muestra"
                                            :currency="{'prefix': ''}"
                                            locale="es"
                                            :precision="3"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Proceso -->
                                <div class="form-group row m-b-15">
                                    <label
                                        class="text-inverse text-left col-6 required"
                                        >Volumen titulación EDTA (mL):</label
                                    >
                                    <div class="col-md-5">
                                        <!-- <input
                                           class="form-group form-control"
                                            type="number"
                                            v-model="
                                                dataForm.volumen_titulacion
                                            "
                                        /> -->
                                        <currency-input
                                            v-model="dataForm.volumen_titulacion"
                                            :currency="{'prefix': ''}"
                                            locale="es"
                                            :precision="3"
                                            class="form-control"
                                            :key="keyRefresh"
                                            >
                                        </currency-input>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Proceso -->
                                <div class="form-group row m-b-15">
                                    <label
                                        class="text-inverse text-left col-6 required"
                                        >Concentración EDTA:</label
                                    >
                                    <div class="col-md-5">
                                        <input
                                            style="cursor:not-allowed; background-color: #e9ecef;"
                                            type="text"
                                            class="field left"
                                            v-model="datoConcentracion"
                                            readonly
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Proceso -->
                                <div class="form-group row m-b-15">
                                    <label class="text-inverse text-left col-6"
                                        >mg CaCo_3/L::</label
                                    >

                                    <input-operation
                                        name-field="resultadoa"
                                        :number-one="
                                            parseFloat(dataForm.volumen_muestra)
                                        "
                                        :number-two="
                                            parseFloat(
                                                dataForm.volumen_titulacion
                                            )
                                        "
                                        :number-three="
                                            parseFloat(datoConcentracion)
                                        "
                                        :key="
                                            dataForm.volumen_muestra +
                                                dataForm.volumen_titulacion +
                                                datoConcentracion
                                        "
                                        :cantidad-decimales="decimalEnsayo"
                                        :value="dataForm"
                                        operation="dureza"
                                        prefix=" "
                                    ></input-operation>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-5">
                    <button @click="storeEnsayo()" class="btn btn-primary">
                        <i class="fa fa-save mr-2"></i>Guardar
                    </button>
                </div>
            </div>
        </div>

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-tendido-muestras">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Listado de muestras para tendido.
                        </h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true"
                            @click="reinicia()"
                        >
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label class="mt-2" style="font-size: 18px"
                            >Seleccione las muestras que desea analizar para
                            esta fórmula</label
                        >
                        <div class="m-5">
                            <div class="mt-3">
                                <!-- Aqui va la tabla -->
                                <div class="table-responsive">
                                    <table-component
                                        id="Toma-de-muestra-table"
                                        :data="advancedSearchFilterPaginateT()"
                                        sort-by="Toma-de-muestra"
                                        sort-order="asc"
                                        table-class="table table-hover m-b-0"
                                        :show-filter="false"
                                        :pagination="dataPaginatorT"
                                        :show-caption="false"
                                        filter-placeholder="Filtro rápido"
                                        filter-no-results="No hay registros"
                                        filter-input-class="form-control col-md-4"
                                        :cache-lifetime="0"
                                    >
                                        <table-column
                                            label=""
                                            :sortable="false"
                                            :filterable="false"
                                        >
                                            <template slot-scope="row">
                                                <input
                                                    v-model="selectMuestras"
                                                    type="checkbox"
                                                    :value="row.id"
                                                />
                                            </template>
                                        </table-column>
                                        <table-column
                                            show="sample_reception_code"
                                            label="Identificación de la muestra"
                                        ></table-column>
                                        <table-column
                                            show="reception_date"
                                            label="Fecha de recepción"
                                        ></table-column>
                                    </table-component>
                                </div>

                                <div class="p-b-15 text-center">
                                    <!-- Cantidad de elementos a mostrar -->
                                    <div
                                        class="
                      form-group
                      row
                      m-5
                      col-md-3
                      text-center
                      d-sm-inline-flex
                    "
                                    >
                                        <div class="row mt-3 paginatorclass">
                                            <div class="col"></div>
                                            <div class="col">
                                                <paginate
                                                    v-model="
                                                        dataPaginatorT.currentPage
                                                    "
                                                    :page-count="
                                                        dataPaginatorT.numPages
                                                    "
                                                    :click-handler="pageEvent"
                                                    :prev-text="'Anterior'"
                                                    :next-text="'Siguiente'"
                                                    :container-class="
                                                        'pagination m-10'
                                                    "
                                                    :page-class="'page-item'"
                                                    :page-link-class="
                                                        'page-link'
                                                    "
                                                    :prev-class="'page-item'"
                                                    :next-class="'page-item'"
                                                    :prev-link-class="
                                                        'page-link'
                                                    "
                                                    :next-link-class="
                                                        'page-link'
                                                    "
                                                    :disabled-class="
                                                        'ignore disabled'
                                                    "
                                                >
                                                </paginate>
                                            </div>
                                            <div class="col"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="storeTendido()" class="btn btn-primary">
                            <i class="fa fa-save mr-2"></i>Guardar
                        </button>
                        <button
                            @click="reinicia()"
                            class="btn btn-white"
                            data-dismiss="modal"
                        >
                            <i class="fa fa-times mr-2"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-ejecutar-blanco -->
    </div>
</template>
<script lang="ts">
import { Component, Prop, Vue } from "vue-property-decorator";

import axios from "axios";

import { Locale } from "v-calendar";

import utility from "../../utility";

const locale = new Locale();

/**
 * Componente para index de gestion de laboratorio
 *
 * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
 * @version 1.0.0
 */
@Component({
    components: {
        /**
         * Se importa de esta manera por error de referencia circular
         * @see https://vuejs.org/v2/guide/components-edge-cases.html#Circular-References-Between-Components
         */
        paginate: () => import("vuejs-paginate")
    }
})
export default class AlcalinidadEnsayoComponent extends Vue {
    /**
     * Nombre del formulario
     */
    @Prop({ type: String, required: true })
    public nameFormulario: string;

    /**
     * Titulo componente
     */
    @Prop({ type: String, required: true })
    public titulo: string;

    /**
     * Valor dataform
     */
    public dataForm: any;

    /**
     * Valor del blanco pendiente
     */
    public blapendiente: any;

    /**
     * Valor del blanco pendiente
     */
    public blacurva: any;

    /**
     * Intercepto blanco
     */
    public blaintercepto: any;

    /**
     *  Carga cuando se va mostrar la muestra
     */
    public ultimoBlanco: any;

    /**
     * Cantidad de decimales del ensayo
     */
    public decimalEnsayo: any;

    /**
     * Codigo de la muestra
     */
    public codigoMuestra: any;

    /**
     * Lista de muestras ejecutadas o ensayos
     */
    public listadoEjecutadas: Array<any>;

    /**
     * Listado de las muestras seleccionadas para ejecutar
     */
    public listadoTendido: Array<any>;

    /**
     *  Carga cuando se va mostrar la muestra
     */
    public datoConcentracion: any;

    /**
     * Listado de muestras finalizadas
     */
    public listadoTendidoFinalizado: Array<any>;

    /**
     * Lista de muestras
     */
    public listadoMuestras: Array<any>;

    /**
     * Lista de muestras ejecutadas
     */
    public listadoEjecutados: Array<any>;

    /**
     * Datos del blanco
     */
    public datosBlanco: Array<any> = [];

    /**
     * Se cargan las muestras a seleccionar
     */
    public selectMuestras: Array<any>;
    /**
     * Este es el modelo del select de la muestra a ejecutar
     */
    public selectMue: Array<any>;

    /**
     * Paginador
     */
    public dataPaginator: any;

    /**
     * Paginador para el tendido
     */
    public dataPaginatorT: any;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    constructor() {
        super();
        this.datoConcentracion = [];
        this.dataForm = {};
        this.blapendiente = 0;
        this.ultimoBlanco = [];
        this.blacurva = 0;
        this.blaintercepto = 0;
        this.decimalEnsayo = 4;
        this.codigoMuestra = "";
        this.datosBlanco = [];
        this.listadoEjecutadas = [];
        this.listadoTendido = [];
        this.listadoMuestras = [];
        this.listadoTendidoFinalizado = [];
        this.selectMuestras = [];
        this.selectMue = [];
        this.dataPaginator = {
            currentPage: 1,
            numPages: 1,
            pagesItems: 5,
            total: 0
        };
        this.dataPaginatorT = {
            currentPage: 1,
            numPages: 1,
            pagesItems: 10,
            total: 0
        };
        this.datosEnsayo();
        this.datosTendido();
        this.datosTendidoFinalizado();
        this.decimalesEnsayo();
        this.cargaConcentracion();
        this.cargaUltimoBlanco();
    }

    mounted() {}

    /**
     * carag las muestras que se muestran en el listado del modal
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public cargaMuestras() {
        this.$swal({
            title: "Cargando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .get("get-all-muestras-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.listadoMuestras = res.data.data;

                for (var indice in this.listadoTendido) {
                    this.selectMuestras.push(this.listadoTendido[indice]["id"]);
                }
                (this.$swal as any).close();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Carga los datos del ensayo como el intercepto y la pendiente
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosEnsayo() {
        axios
            .get("get-datos-blanco-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.datosBlanco = res.data.data;
                this.blaintercepto = this.datosBlanco["b_intercepto"];
                this.blapendiente = this.datosBlanco["k_pendiente"];
                this.blacurva = this.datosBlanco["curva_numero"];
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Se reinician estas variables
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public reinicia() {
        this.selectMuestras = [];
        this.listadoMuestras = [];
    }

    /**
     * Captura evento de paginacion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
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
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     *
     * @param list lista de elementos a paginar
     */
    public paginate(list: any[]): any[] {
        // Calcula el numero de paginas del paginador
        this.dataPaginator.numPages = Math.ceil(
            list.length / this.dataPaginator.pagesItems
        );
        // Valida si la pagina actual exede al numero de paginas
        if (this.dataPaginator.currentPage > this.dataPaginator.numPages) {
            this.pageEvent(1);
        }
        this.dataPaginator.total = list.length;
        return list.slice(
            (this.dataPaginator.currentPage - 1) *
                this.dataPaginator.pagesItems,
            this.dataPaginator.currentPage * this.dataPaginator.pagesItems
        );
    }

    /**
     * Paginacion con busqueda avanzada
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public advancedSearchFilterPaginate(): Array<any> {
        return this.paginate(this.listadoTendidoFinalizado);
    }

    /**
     * Captura evento de paginacion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     *
     * @param page numero de pagina actual
     */
    public pageEventT(page: number): void {
        this.dataPaginatorT.currentPage = page;
    }

    /**
     * Organiza los elementos de paginacion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     *
     * @param list lista de elementos a paginar
     */
    public paginateT(list: any[]): any[] {
        // Calcula el numero de paginas del paginador
        this.dataPaginatorT.numPages = Math.ceil(
            list.length / this.dataPaginatorT.pagesItems
        );
        // Valida si la pagina actual exede al numero de paginas
        if (this.dataPaginatorT.currentPage > this.dataPaginatorT.numPages) {
            this.pageEventT(1);
        }
        this.dataPaginatorT.total = list.length;
        return list.slice(
            (this.dataPaginatorT.currentPage - 1) *
                this.dataPaginatorT.pagesItems,
            this.dataPaginatorT.currentPage * this.dataPaginatorT.pagesItems
        );
    }

    /**
     * Paginacion con busqueda avanzada
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public advancedSearchFilterPaginateT(): Array<any> {
        return this.paginateT(this.listadoMuestras);
    }

    /**
     * Guarda el tendido de muestras para luego ser seleccionado
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public storeTendido(): void {
        //Valida que el select no este vacio
        if (this.selectMuestras.length == 0) {
            this.$swal({
                title: "Seleccione al menos una muestra!",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                this.$swal({
                    title: "Cargando datos",
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        (this.$swal as any).showLoading();
                    }
                });
                axios
                    .post(
                        "store-tendido-" + this.nameFormulario,
                        this.selectMuestras
                    )
                    .then(res => {
                        this.listadoTendido = [];
                        this.selectMue = [];
                        this.selectMuestras = [];
                        this.datosTendido();
                        (this.$swal as any).close();
                        $(`#modal-tendido-muestras`).modal("toggle");
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Carga los datos del tendido del listado
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosTendido() {
        this.listadoTendido = [];
        axios
            .get("get-datos-tendido-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.listadoTendido = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Carga el tendido de datos finalizado
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosTendidoFinalizado() {
        this.listadoTendido = [];
        this.$swal({
            title: "Cargando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .get("get-tendido-finalizado-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.listadoTendidoFinalizado = res.data.data;
                (this.$swal as any).close();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Guarda el ensayo cuando se ejecuta
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public storeEnsayo(): void {
        //Se asignan las variables
        this.dataForm["ultimo_blanco"] = this.ultimoBlanco;
        this.dataForm["tipo"] = "Ensayo";
        this.dataForm["idMuestra"] = this.selectMue;
        this.dataForm["ultimo_blanco"] = this.ultimoBlanco;
        this.dataForm["concentracion"] = this.datoConcentracion;
        this.dataForm["resultado_completo"] = this.dataForm.resultadoa;
        this.dataForm["resultado"] = this.dataForm.resultadoa.toFixed(
            this.decimalEnsayo
        );

        if (
            this.dataForm["idMuestra"] == "" ||
            this.dataForm["idMuestra"] == null ||
            this.dataForm["volumen_muestra"] == "" ||
            this.dataForm["volumen_muestra"] == null ||
            this.dataForm["volumen_titulacion"] == "" ||
            this.dataForm["volumen_titulacion"] == null ||
            this.dataForm["concentracion"] == "" ||
            this.dataForm["concentracion"] == null
        ) {
            this.$swal({
                title: "Complete los datos !",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                this.dataForm["resultado_completo"] = this.dataForm.resultadoa;
                this.dataForm["resultado"] = this.dataForm.resultadoa.toFixed(
                    this.decimalEnsayo
                );
                axios
                    .post("ensayo-" + this.nameFormulario, this.dataForm)
                    .then(res => {
                        if (res.data.data == "Si") {
                            this.$swal({
                                title: "Análisis Guardado !",
                                icon: "success"
                            });
                            this.listadoTendido = [];
                            this.datosTendido();
                            this.datosTendidoFinalizado();
                            this.reinicia();
                            this.dataForm = {};
                            this.codigoMuestra = "";
                        } else {
                            if (res.data.data == "Error blanco") {
                                this.$swal({
                                    title: "Ejecutar el Blanco !",
                                    icon: "warning"
                                });
                            } else {
                                if (res.data.data == "Error patron") {
                                    this.$swal({
                                        title: "Ejecutar el STD !",
                                        icon: "warning"
                                    });
                                } else {
                                    if (res.data.data == "Error ensayo") {
                                        this.$swal({
                                            title: "Ejecutar !",
                                            icon: "warning"
                                        });
                                    } else {
                                    }
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Carga el valor de la muestra cuando se selecciona en el select
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public cargaCodigo() {
        this.codigoMuestra = this.selectMue["sample_reception_code"];
    }

    /**
     * Envia la cantidad de decimales por ensayo
     *
     * @author Nicolas Dario Ortiz Peña. -Junio. 15 - 2022
     * @version 1.0.0
     */
    public decimalesEnsayo() {
        axios
            .get("get-decimales-ensayo-" + this.nameFormulario)
            .then(res => {
                this.decimalEnsayo = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Carga los datos de la concentracion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public cargaConcentracion() {
        axios
            .get("get-datos-blanco-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.datoConcentracion = res.data.data["concentracion"];
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }
    /**
     * Carga los datos de la concentracion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public cargaUltimoBlanco() {
        axios
            .get("get-ultimo-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.ultimoBlanco = res.data.data["volumen_titulacion"];
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }
}
</script>
