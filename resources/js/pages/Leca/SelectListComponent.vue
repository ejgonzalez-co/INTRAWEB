<template>
    <div>
        <div class="row mt-1">
            <div class="col">
                <label>{{ label }}</label>
                <div class="row">
                    <div class="col">
                        <input
                            class="form-control"
                            type="text"
                            v-model="searchFields.nombre"
                            placeholder="Filtro por nombre del punto de muestra"
                        />
                    </div>

                    <form
                        @submit.prevent="filtrar()"
                        class="form-horizontal m-4"
                    >
                        <button class="btn btn-primary m-b-10">
                            Buscar
                        </button>
                    </form>
                    <form
                        @submit.prevent="clearDataSearch()"
                        class="form-horizontal m-4"
                    >
                        <button class="btn btn-primary m-b-10">
                            Limpiar
                        </button>
                    </form>
                </div>

                <div class="mt-2">
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
                            label="Seleccione"
                            :sortable="false"
                            :filterable="false"
                        >
                            <template slot-scope="row">
                                <input
                                    v-model="value[nameField]"
                                    type="checkbox"
                                    :value="row.id"
                                />
                            </template>
                        </table-column>
                        <table-column
                            show="point_location"
                            label="Nombre"
                        ></table-column>
                    </table-component>
                </div>

                <div class="row p-b-15 text-center">
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
                                    v-model="dataPaginatorT.currentPage"
                                    :page-count="dataPaginatorT.numPages"
                                    :click-handler="pageEventT"
                                    :prev-text="'Anterior'"
                                    :next-text="'Siguiente'"
                                    :container-class="'pagination m-10'"
                                    :page-class="'page-item'"
                                    :page-link-class="'page-link'"
                                    :prev-class="'page-item'"
                                    :next-class="'page-item'"
                                    :prev-link-class="'page-link'"
                                    :next-link-class="'page-link'"
                                    :disabled-class="'ignore disabled'"
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
</template>
<script lang="ts">
import { Component, Prop, Watch, Vue } from "vue-property-decorator";

import axios from "axios";
import { jwtDecode } from 'jwt-decode';

/**
 * Componente para hacer que lso campos se llenen cuando el select seleccione algo
 *
 * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
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
export default class SelectListComponent extends Vue {
    /**
     * Nombre del campo
     */
    @Prop({ type: String, required: true })
    public nameField: string;

    /**
     * Valor del campo
     */
    @Prop({ type: Object })
    public value: any;

    /**
     * Nombre de la ruta a obtener
     */
    @Prop({ type: String, required: true })
    public nameResource: string;

    /**
     * texto del label
     */
    @Prop({ type: String, required: true })
    public label: string;

    /**
     * Clase css para el color del boton de agregar a la lista
     */
    @Prop({ default: "btn-primary" })
    public classButtonAdd: String;

    public result: any;

    /**
     * Paginador
     */
    public dataPaginator: any;

    /**
     * Paginador para el tendido
     */
    public dataPaginatorT: any;

    /**
     * Lista de datos agregados
     */
    public dataList: any;

    /**
     * Lista de datos agregados
     */
    public dataListR: any;

    /**
     * Se cargan las muestras a seleccionar
     */
    public checklist: Array<any>;

    /**
     *  Campos para la busqueda
     */
    public searchFields: any;

           /**
         * Valor de llave de reduccion
         */
         @Prop({ type: String, default: 'id'})
        public reduceKey: string;


    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
     * @version 1.0.0
     */
    constructor() {
        super();
        this.searchFields = [];
        this.dataList = [];
        this.checklist = [];
        this.dataPaginatorT = {
            currentPage: 1,
            numPages: 1,
            pagesItems: 5,
            total: 0
        };
        this.getall();
    }

    /**
     * En este metodo se hacen las operaciones de dos numeros
     *
     * @author Nicolas Dario Ortiz Peña. - Agosto. 18 - 2021
     * @version 1.0.0
     */
    public getall(): void {
        axios
            .get(this.nameResource)
            .then(res => {

                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                this.dataList = dataDecrypted["data"];
                this.dataListR = dataDecrypted["data"];
                this.inicializa();
                this.$forceUpdate();
            })
            .catch(error => {});
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
        return this.paginateT(this.dataList);
    }

    /**
     * Aca se filtran los datos del dataList
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public filtrar() {
        this.dataList = this.dataListR;

        if (this.searchFields["nombre"]) {
            this.dataList = this.dataList.filter(objeto =>
                objeto.point_location
                    .toUpperCase()
                    .includes(this.searchFields["nombre"].toUpperCase())
            );
        }
    }

    /**
     * Limpia los datos de busqueda y reinicia el listado
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public clearDataSearch() {
        this.searchFields = [];
        this.dataList = this.dataListR;
    }
    /**
     * Limpia los datos de busqueda y reinicia el listado
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public seleccion() {
        this.$forceUpdate();
    }

    public inicializa() {
        if (this.value[this.nameField]) {
            // Filtra los datos de seleccion para evidenciar el checkbox seleccionado
            const valueSelected: number | string = this.value[
                this.nameField
            ].map(value => value[this.reduceKey]);
            // Asigna los datos seleccionados
            this.value[this.nameField] = valueSelected;
        } else {
            // Se inicializa para aceptar multiples checkbox
            this.value[this.nameField] = [];
        }
    }
}
</script>
