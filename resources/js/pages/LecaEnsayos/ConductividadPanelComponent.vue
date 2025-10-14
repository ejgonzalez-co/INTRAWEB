<template>
    <div class="m-1">
        <div>
            <!-- begin breadcrumb -->
            <ol class="breadcrumb m-b-10">
                <li class="breadcrumb-item">
                    <a href="/">Inicio</a>
                </li>
            </ol>
            <!-- end breadcrumb -->
            <h1 class="page-header text-center m-b-35">
                Vista principal para la ejecución del ensayo de {{ titulo }}
            </h1>
            <div class="row m-5">
                <div class="col">
                    <!--  letrero de blanco patron ensayo  -->
                    <div
                        style="background-color: red"
                        class="m-5"
                        v-if="ensayos['blanco'] == false"
                    >
                        <h3 class="m-5 text-center text-white">Blanco</h3>
                    </div>
                    <h4
                        class="m-5 text-center"
                        v-if="ensayos['blanco'] == false"
                    >
                        Pendiente
                    </h4>
                    <div
                        style="background-color: #6fb154"
                        class="m-5"
                        v-if="ensayos['blanco'] == true"
                    >
                        <h3 class="m-5 text-center text-white">Blanco</h3>
                    </div>
                    <h4
                        class="m-5 text-center"
                        v-if="ensayos['blanco'] == true"
                    >
                        Ejecutado
                    </h4>
                </div>
                <div class="col">
                    <div
                        style="background-color: red"
                        class="m-5"
                        v-if="ensayos['patron'] == false"
                    >
                        <h3 class="m-5 text-center text-white">STD</h3>
                    </div>
                    <h4
                        class="m-5 text-center"
                        v-if="ensayos['patron'] == false"
                    >
                        Pendiente
                    </h4>

                    <div
                        style="background-color: #6fb154"
                        class="m-5"
                        v-if="ensayos['patron'] == true"
                    >
                        <h3 class="m-5 text-center text-white">STD</h3>
                    </div>
                    <h4
                        class="m-5 text-center"
                        v-if="ensayos['patron'] == true"
                    >
                        Ejecutado
                    </h4>
                </div>
                <div class="col">
                    <div style="background-color: #6fb154" class="m-5">
                        <h3 class="m-5 text-center text-white">Ensayos</h3>
                    </div>
                    <h4 class="m-5 text-center">{{ ensayos["ensayo"] }}</h4>
                </div>
            </div>
            <div class="m-t-20 mt-5">
                <!-- Aqui van los botones -->
                <a
                    href="javascript:location.reload()"
                    class="btn btn-primary m-b-10"
                >
                    <i class="fas fa-sync mr-2"></i> Actualizar página</a
                >
                <button
                    v-if="
                        ensayos['blanco'] == false &&
                            ensayos['pendienteb'] == false
                    "
                    @click="datosBlancoFunction()"
                    type="button"
                    class="btn btn-primary m-b-10"
                    data-backdrop="static"
                >
                    <i class="fa fa-plus mr-2"></i>Ejecutar blanco
                </button>
                <button
                    v-if="
                        ensayos['patron'] == false &&
                            ensayos['blanco'] == true &&
                            ensayos['pendienteb'] == false &&
                            ensayos['pendientep'] == false
                    "
                    @click="datosPatronFunction()"
                    type="button"
                    class="btn btn-primary m-b-10"
                    data-backdrop="static"
                >
                    <i class="fa fa-plus mr-2"></i>Ejecutar STD
                </button>

                <a
                    v-if="
                        ensayos['patron'] == true && ensayos['blanco'] == true
                    "
                    type="button"
                    class="btn btn-primary m-b-10"
                    :href="'get-ejecutar-' + nameFormulario"
                >
                    <i class="fa fa-plus mr-2"></i>Ejecutar ensayo</a
                >
            </div>

            <!-- end widget -->
            <!-- begin panel -->
            <div class="panel panel-default">
                <div class="panel-heading border-bottom">
                    <div class="panel-title">
                        <h5 class="text-center">
                            Total registros ensayos: {{ totalRegistro }}
                        </h5>
                    </div>
                    <div class="panel-heading-btn">
                        <a
                            href="javascript:;"
                            class="btn btn-xs btn-icon btn-circle btn-default"
                            data-click="panel-expand"
                            ><i class="fa fa-expand"></i
                        ></a>
                        <a
                            href="javascript:;"
                            class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse"
                            ><i class="fa fa-minus"></i
                        ></a>
                    </div>
                </div>
                <!-- begin #accordion search-->
                <div id="accordion" class="accordion">
                    <!-- begin card search -->
                    <div
                        class="
              cursor-pointer
              card-header
              bg-white
              pointer-cursor
              d-flex
              align-items-center
            "
                        data-toggle="collapse"
                        data-target="#collapseOne"
                    >
                        <i class="fa fa-search fa-fw mr-2 f-s-12"></i>
                        <b>Mostrar opciones de búsqueda</b>
                    </div>
                    <div
                        id="collapseOne"
                        class="collapse border-bottom p-l-40 p-r-40"
                        data-parent="#accordion"
                    >
                        <div class="card-body">
                            <label class="col-form-label"
                                ><b>Búsqueda rápida</b></label
                            >
                            <!-- Campos de busqueda -->
                            <div class="row form-group mt-4">
                                <!-- Aqui van los filtros -->
                                <div class="col-md-4 mb-3">
                                    <input
                                        class="form-control"
                                        type="text"
                                        v-model="searchFields.codigo"
                                        placeholder="Filtro por Código de la muestra"
                                    />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input
                                        class="form-control"
                                        type="date"
                                        v-model="searchFields.inicio"
                                        placeholder="Filtro por Código de la muestra"
                                    />
                                    <small>Seleccione la fecha inicio.</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <input
                                        class="form-control"
                                        type="date"
                                        v-model="searchFields.final"
                                        placeholder="Filtro por Código de la muestra"
                                    />
                                    <small>Seleccione la fecha final.</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select
                                        v-model="searchFields.tipo"
                                        class="m-b-3 form-control "
                                    >
                                        <option value="Blanco">Blanco</option>
                                        <option value="Patrón">Patrón</option>
                                        <option value="Ensayo">Ensayo</option>
                                        <option value="Duplicado"
                                            >Duplicado</option
                                        >
                                        <option value="Ensayo - Repetición"
                                            >Ensayo - Repetición</option
                                        >
                                        <option
                                            value="Ensayo - Regla de decisión"
                                            >Ensayo - Regla de decisión</option
                                        >
                                    </select>
                                    <small>Seleccione el tipo.</small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select
                                        v-model="searchFields.estado"
                                        class="m-b-3 form-control "
                                    >
                                        <option value="Si">Si</option>
                                        <option value="No">No</option>
                                    </select>
                                    <small>Seleccione si esta aprobado.</small>
                                </div>
                                <div class="col-md-4">
                                    <button
                                        @click="clearDataSearch()"
                                        class="btn btn-primary m-b-10"
                                    >
                                        Limpiar campos de búsqueda
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button
                                        @click="filtrar()"
                                        class="btn btn-primary m-b-10"
                                    >
                                        Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card search -->
                </div>
                <!-- end #accordion search -->
                <div class="panel-body">
                    <!-- begin buttons action table -->
                    <div class="float-xl-right m-b-15">
                        <!-- Acciones para exportar datos de tabla-->
                        <div class="btn-group">
                            <a
                                href="javascript:;"
                                data-toggle="dropdown"
                                class="btn btn-primary"
                                ><i class="fa fa-download mr-2"></i>Exportar
                                tabla</a
                            >
                            <a
                                href="#"
                                data-toggle="dropdown"
                                class="btn btn-primary dropdown-toggle"
                                aria-expanded="false"
                                ><b class="caret"></b
                            ></a>
                            <div
                                class="dropdown-menu dropdown-menu-right bg-blue"
                            >
                                <a
                                    href="javascript:;"
                                    @click="exportDataTable('xlsx')"
                                    class="dropdown-item text-white no-hover"
                                    ><i class="fa fa-file-excel mr-2"></i> EXCEL
                                    : LECA - R - 041
                                </a>

                                <a
                                    v-if="totalRegistro"
                                    href="javascript:;"
                                    @click="exportDataTableGoogle('xlsx')"
                                    class="dropdown-item text-white no-hover"
                                    ><i class="fa fa-file-excel mr-2"></i> EXCEL
                                    : LECA - R - 034
                                </a>
                            </div>
                        </div>
                    </div>

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
                                show="consecutivo"
                                label="Identificación de la muestra"
                            ></table-column>
                            <table-column
                                show="created_at"
                                label="Fecha ejecución"
                            ></table-column>
                            <table-column
                                show="temperatura_con"
                                label="Temperatura(cº)"
                            ></table-column>
                            <table-column
                                show="conductividad"
                                label="Conductividad"
                            ></table-column>
                            <table-column
                                show="user_name"
                                label="Analista"
                            ></table-column>
                            <table-column
                                show="tipo"
                                label="Tipo"
                            ></table-column>
                            <table-column
                                show="estado"
                                label="Aprobado"
                            ></table-column>

                            <table-column
                                label="Acciones"
                                :sortable="false"
                                :filterable="false"
                            >
                                <template slot-scope="row">
                                    <button
                                        v-if="
                                            row.tipo == 'Blanco' ||
                                                row.tipo == 'Patrón'
                                        "
                                        @click="showReg(row)"
                                        data-target="#modal-show-ensayo"
                                        data-toggle="modal"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="Ver detalles"
                                    >
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button
                                        v-if="
                                            row.tipo == 'Ensayo' ||
                                                row.tipo ==
                                                    'Ensayo - Repetición'
                                        "
                                        @click="showRegTotal(row)"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="Ver detalles"
                                    >
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button
                                        v-if="
                                            row.tipo == 'Ensayo' ||
                                                row.tipo ==
                                                    'Ensayo - Repetición'
                                        "
                                        @click="datosEnsayoFunction(row)"
                                        data-target="#modal-ejecutar-duplicado"
                                        data-toggle="modal"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="Metodo de ejecución"
                                    >
                                        <i class="fas fa-clone"></i>
                                    </button>
                                    <button
                                        v-if="row.tipo == 'Duplicado'"
                                        @click="datosPorcentualRelativa(row)"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="(DPR) Diferencia porcentual relativa"
                                    >
                                        <i class="fas fa-balance-scale"></i>
                                    </button>

                                    <button
                                        v-if="row.tipo == 'Duplicado'"
                                        @click="promedio(row)"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="Promedio"
                                    >
                                        <i class="far fa-hourglass"></i>
                                    </button>

                                    <a
                                        v-if="row.tipo == 'Duplicado'"
                                        type="button"
                                        class="btn btn-white btn-icon btn-md"
                                        :href="
                                            'observacion-duplicados-conduc' +
                                                '?lc_ensayo_conductividad_id=' +
                                                row.id
                                        "
                                        title="Observaciones"
                                    >
                                        <i class="fa fa-plus mr-2"></i
                                    ></a>
                                </template>
                            </table-column>
                        </table-component>
                    </div>
                </div>
                <div class="p-b-15 text-center">
                    <!-- Cantidad de elementos a mostrar -->
                    <div
                        class="form-group row m-5 col-md-3 text-center d-sm-inline-flex"
                    >
                        <div class="row mt-3 paginatorclass">
                            <div class="col"></div>
                            <div class="col">
                                <paginate
                                    v-model="dataPaginator.currentPage"
                                    :page-count="dataPaginator.numPages"
                                    :click-handler="pageEvent"
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
            <!-- end panel -->
        </div>

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-ejecutar-blanco">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Ejecutar blanco</h4>
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
                        <!-- formulario de blanco -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            
                            <div class="panel-body">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Temperatura(cº):</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.temperatura_con
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.temperatura_con"
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
                                                >Conductividad: </label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                         dataForm.conductividad
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.conductividad"
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="storeBlanco()" class="btn btn-primary">
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

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-promedio">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Promedio
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
                        <!-- Ver detalles del promedio -->
                        <h4>Ver detalles Promedio</h4>
                        <div
                            class="row"
                            v-for="(item, key) in datosPromedio"
                            :key="key"
                        >
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <!-- Proceso -->
                                    <div class="form-group row m-b-15">
                                        <label
                                            class="text-inverse text-left col-6"
                                        >
                                            <b>Fecha de ejecución:</b>
                                        </label>
                                        <div class="col-md-3">
                                            <label>
                                                {{ item.created_at }}</label
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Proceso -->
                                    <div class="form-group row m-b-15">
                                        <label
                                            class="text-inverse text-left col-6"
                                        >
                                            <b> Promedio:</b>
                                        </label>
                                        <div class="col-md-3">
                                            <label>
                                                {{
                                                    currencyFormat(
                                                        item.resultado
                                                    )
                                                }}</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            class="btn btn-warning"
                            type="button"
                            @click="printContent('DestallesPorcentualRelativa')"
                        >
                            <i class="fa fa-print mr-2"></i>Imprimir
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

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-porcentual-relativa">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Diferencia porcentual relativa
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
                        <!-- Type Field -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Dpr</strong>
                                    </h4>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Dato(1):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    readonly
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.add1"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Dato(2):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    readonly
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.add2"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6"
                                                >Diferencia porcentual
                                                relativa:</label
                                            >

                                            <input-operation
                                                name-field="resultado"
                                                :number-one="
                                                    parseFloat(dataForm.add1)
                                                "
                                                :number-two="
                                                    parseFloat(dataForm.add2)
                                                "
                                                :key="
                                                    dataForm.add1 +
                                                        dataForm.add2
                                                "
                                                :cantidad-decimales="2"
                                                :value="dataForm"
                                                operation="porcentualRelativa"
                                                suffix="%"
                                            ></input-operation>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-5" id="DestallesPorcentualRelativa">
                            <h4>Ver detalles Diferencia Porcentual Relativa</h4>
                            <div
                                class="row"
                                v-for="(item, key) in datosRelativa"
                                :key="key"
                            >
                                <div
                                    class="panel"
                                    data-sortable-id="ui-general-1"
                                >
                                    <div class="panel-body">
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b
                                                            >Fecha de
                                                            ejecución:</b
                                                        >
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.created_at
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b>
                                                            Código de la
                                                            muestra:</b
                                                        >
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.consecutivo
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b> Dato 1:</b></label
                                                    >
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.add1
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b>Dato 2:</b>
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.add2
                                                            }}</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b>Resultado:</b>
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.resultado
                                                            }}%.</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- Proceso -->
                                                <div
                                                    class="form-group row m-b-15"
                                                >
                                                    <label
                                                        class="text-inverse text-left col-6"
                                                    >
                                                        <b
                                                            >Resultado
                                                            completo:</b
                                                        >
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.resultado_completo
                                                            }}%.</label
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- State Routine Field -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            class="btn btn-warning"
                            type="button"
                            @click="printContent('DestallesPorcentualRelativa')"
                        >
                            <i class="fa fa-print mr-2"></i>Imprimir
                        </button>
                        <button
                            @click="storePorcentualRelativa()"
                            class="btn btn-primary"
                        >
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

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-ejecutar-patron">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Ejecutar STD</h4>
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
                    <!-- Type Field -->
                    <div class="panel" data-sortable-id="ui-general-1">
                        <!-- formulario de patron -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            
                            <div class="panel-body">
                                <div class="row mt-3">

                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Temperatura(cº):</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                     class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.temperatura_con
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.temperatura_con"
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
                                                >Conductividad: </label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                     class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                         dataForm.conductividad
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.conductividad"
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="storePatron()" class="btn btn-primary">
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

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-ejecutar-duplicado">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Metodo de ejecución
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
                        <!-- Formulario de duplicado o metodo de ejecucion -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="modal-body">
                                <!-- Type Field -->

                                                        <div class="panel" data-sortable-id="ui-general-1">
                            
                            <div class="panel-body">
                                <h5>Ingrese la Información para la ejecución del blanco</h5>
                                <div class="row mt-3">

                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Temperatura(cº):</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                     class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.temperatura_con
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.temperatura_con"
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
                                                ><i class="icon-ostatus-colored"></i>Conductividad: </label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                     class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.conductividad
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.conductividad"
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
                                </div>
                            </div>
                        </div>
                                <div
                                    class="col-md-6"
                                    v-if="idMuestra.duplicado == 'Si'"
                                >
                                    <!-- Proceso -->
                                    <div class="form-group row m-b-15">
                                        <label
                                            class="text-inverse text-left col-6"
                                            >Método de ensayo:</label
                                        >
                                        <div class="col-md-5">
                                            <select
                                                v-model="metodoTotal"
                                                 class="form-group form-control"
                                            >
                                                <option value="Seleccione"
                                                    >Seleccione</option
                                                >
                                                <option value="Duplicado"
                                                    >Duplicado</option
                                                >
                                                <option value="Repetición"
                                                    >Repetición</option
                                                >
                                                <option
                                                    value="Regla de decisión"
                                                    >Regla de decisión</option
                                                >
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="col-md-6"
                                    v-if="idMuestra.duplicado == 'No'"
                                >
                                    <!-- Proceso -->
                                    <div class="form-group row m-b-15">
                                        <label
                                            class="text-inverse text-left col-6"
                                            >Método de ensayo:</label
                                        >
                                        <div class="col-md-5">
                                            <select
                                                v-model="metodoTotal"
                                                 class="form-group form-control"
                                            >
                                                <option value="Seleccione"
                                                    >Seleccione</option
                                                >
                                                <option value="Repetición"
                                                    >Repetición</option
                                                >
                                                <option
                                                    value="Regla de decisión"
                                                    >Regla de decisión</option
                                                >
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            @click="storeDuplicado()"
                            class="btn btn-primary"
                        >
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

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-show-ensayo">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Ver detalles</h4>
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
                        <!-- ver detalles blanco y patron -->
                        <div class="row" id="VerDetalles">
                            <!-- State Routine Field -->
                            <dt class="text-inverse text-left col-3 mt-5">
                                Fecha de ejecución:
                            </dt>
                            <dd class="col-9 mt-5">
                                {{ dataShow.created_at }}
                            </dd>

                            <dt class="text-inverse text-left col-3 mt-5">
                                Tipo:
                            </dt>
                            <dd class="col-9 mt-5">{{ dataShow.tipo }}</dd>

                            <dt class="text-inverse text-left col-3 mt-5">
                                Consecutivo:
                            </dt>
                            <dd class="col-9 mt-5">
                                {{ dataShow.consecutivo }}
                            </dd>

                            <dt class="text-inverse text-left col-3 mt-5">
                                Temperatura(cº)
                            </dt>
                            <dd class="col-9 mt-5">
                                {{ dataShow.temperatura_con }}
                            </dd>

                            <dt class="text-inverse text-left col-3 mt-5">
                                Conductividad
                            </dt>
                            <dd class="col-9 mt-5">
                                {{ dataShow.conductividad }}
                            </dd>

                            <dt class="text-inverse text-left col-3 mt-5">
                                Aprobado:
                            </dt>
                            <dd class="col-9 mt-5">{{ dataShow.estado }}</dd>
                            <dt class="text-inverse text-left col-3 mt-5">
                                Tipo:
                            </dt>
                            <dd class="col-9 mt-5">{{ dataShow.tipo }}</dd>
                            <dt
                                class="text-inverse text-left col-3"
                                v-if="
                                    dataShow.aprobacion_usuario != 'Pendiente'
                                "
                            >
                                Aprobación del analista:
                            </dt>
                            <dd
                                class="col-9"
                                v-if="
                                    dataShow.aprobacion_usuario != 'Pendiente'
                                "
                            >
                                {{ dataShow.aprobacion_usuario }}
                            </dd>

                            <dt
                                class="text-inverse text-left col-3 mt-3"
                                v-if="
                                    dataShow.aprobacion_usuario != 'Pendiente'
                                "
                            >
                                Observación del analista:
                            </dt>
                            <dd
                                class="col-9 mt-3"
                                v-if="
                                    dataShow.aprobacion_usuario != 'Pendiente'
                                "
                            >
                                <textarea
                                    class="m-5"
                                    name="observacion_analista"
                                    v-model="dataShow.observacion_analista"
                                    rows="10"
                                    cols="40"
                                    readonly
                                ></textarea>
                            </dd>
                            <div
                                class="row m-5"
                                v-if="dataShow.aprobacion_usuario == 'Pendiente'"
                            >
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                ><b>
                                                    Aprobación del analista:</b
                                                ></label
                                            >
                                            <div class="col-md-8">
                                                <select
                                                    v-model="dataShow.esta"
                                                    class="form-control"
                                                >
                                                    <option value="Si"
                                                        >Si</option
                                                    >
                                                    <option value="No"
                                                        >No</option
                                                    >
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                ><b>Observación:</b></label
                                            >
                                            <div class="col-md-8">
                                                <textarea
                                                    class="m-5 form-control"
                                                    name="observacion_analista"
                                                    v-model="
                                                        dataShow.observacion_analista
                                                    "
                                                    rows="10"
                                                    cols="40"
                                                ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            v-if="dataShow.aprobacion_usuario == 'Pendiente'"
                            @click="storeEstado()"
                            class="btn btn-primary"
                        >
                            <i class="fa fa-save mr-2"></i>Guardar
                        </button>
                        <button
                            class="btn btn-warning"
                            type="button"
                            @click="printContent('VerDetalles')"
                        >
                            <i class="fa fa-print mr-2"></i>Imprimir
                        </button>
                        <button class="btn btn-white" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end #modal-ejecutar-blanco -->

        <!-- begin #modal-ejecutar-blanco -->
        <div class="modal fade" id="modal-show-ensayo-cloruro">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">Ver detalles</h4>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-hidden="true"
                        >
                            <i class="fa fa-times text-white"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="VerDetallesEnsayo">
                        <!-- ver detalles general  -->
                        <div
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Proceso</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Fecha de creación de la toma de muestra:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.created_at }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Hora de la toma de la muestra:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.hour_from_to }}
                                    </dd>
                                </div>
                                <br />
                                <div
                                    v-if="dataShow.reception_date"
                                    class="row mt-3"
                                >
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Fecha y hora de la recepción de la
                                        muestra:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.reception_date }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>
                                Información general del responsable de la toma
                            </h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Identificación asignada por Leca:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.sample_reception_code }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Tipo de agua:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.type_water }}
                                    </dd>
                                </div>
                                <br />
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Responsable de la entrega de la muestra:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.user_name }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="muestraShow.chlorine_reception"
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Parámetros determinados en campo</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Cloro:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.chlorine_reception }}
                                    </dd>

                                </div>
                                <br />
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        NTU:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.ntu_reception }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        μS/cm:
                                    </dt>
                                    <dd class="col-4">
                                        {{
                                            muestraShow.conductivity_reception
                                        }}
                                    </dd>
                                </div>
                                <br />
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Otro:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.other_reception }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="muestraShow.type_receipt"
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Recipientes con muestras</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Tipo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.type_receipt }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Volumen litros:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.volume_liters }}
                                    </dd>
                                </div>
                                <br />
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Parametros solicitados:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.requested_parameters }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Adición de preservante:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.persevering_addiction }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="muestraShow.according_receipt"
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Estado de muestra recepcionada</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        T°c(inicial):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.t_initial_receipt }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        T°c(final):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.t_final_receipt }}
                                    </dd>
                                </div>
                                <br />
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Conforme:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.according_receipt }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Observaciones:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.observation_receipt }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="muestraShow.is_accepted"
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Información general de la recepción</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        ¿Se acepta?:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.is_accepted }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Responsable de recepción de muestra:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.name_receipt }}
                                    </dd>
                                </div>
                                <br />
                                <div class="row mt-3">
                                    <dt
                                        v-if="muestraShow.is_accepted == 'No'"
                                        class="text-inverse text-left col-2"
                                    >
                                        Justificación:
                                    </dt>
                                    <dd
                                        v-if="muestraShow.is_accepted == 'No'"
                                        class="col-4"
                                    >
                                        {{ muestraShow.justification_receipt }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <div
                            class="panel  mt-3"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Notas</h5>
                            <div style="padding: 15px">
                                <table
                                    class="text-left default"
                                    style="width:100%; table-layout: fixed;"
                                    border="1"
                                >
                                    <tr>
                                        <td>
                                            (1) cruda ( C), tratada (T), de
                                            proceso (P)(aguas clarificadas o
                                            filtradas).
                                        </td>
                                        <td>
                                            (6) T inicial; aplica para muestras
                                            con cadena de frío.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            (2) NTU = turbiedad, µS/cm=
                                            conductividad, otros= temperatura,
                                            OD.
                                        </td>
                                        <td>
                                            (7) T de llegada; aplica para
                                            muestras con cadena de frío y
                                            almacenadas.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(3) Vidrio ( V), Plástico (P)</td>
                                        <td>
                                            (8) Conforme. De acuerdo a criterios
                                            relacionados en el procedimiento
                                            gestión de muestras.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            (4) Parámetros relacionados en
                                            listas
                                        </td>
                                        <td>
                                            (9) No Conforme. De acuerdo a
                                            criterios relacionados en el
                                            procedimiento gestión de muestras.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            (5) Ácidos (A), bases (B),
                                            tiosulfato de sodio(TS), otros ( O)
                                            Ver listas.
                                        </td>
                                        <td>
                                            (10)Observaciones: todo evento que
                                            le suceda al ítem de muestreo,
                                            solicitudes de clientes, no
                                            contemplados R-047.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Información de ejecución del blanco</h5>
                            <div style="padding: 15px">
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Conductividad μs/cm:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.conductividad }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Consecutivo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.consecutivo }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Temperatura(cº): 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.temperatura_con }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Lectura equipo: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.lectura }}
                                    </dd>

 

                                
                                </div>

                                <div class="row mt-3">


                                    <dt class="text-inverse text-left col-2">
                                        Conductividad: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.resultado }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                    Conductividad (Escala TON):  
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.temperatura_col }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.resultado_completo }}
                                    </dd>

                                      <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Expresión de resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.expresion }}
                                    </dd>
                                </div>


                            </div>
                        </div>
                        <div
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5>Información de ejecución del STD</h5>
                            <div style="padding: 15px">
                               <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Conductividad μs/cm:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.conductividad }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Consecutivo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.consecutivo }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Temperatura(cº): 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.temperatura_con }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Lectura equipo: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.lectura }}
                                    </dd>
                                </div>
                                <div class="row mt-3">
                                    <dt class="text-inverse text-left col-2">
                                        Conductividad: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.resultado }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                    Conductividad (Escala TON):  
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.temperatura_col }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.resultado_completo }}
                                    </dd>

                                      <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Expresión de resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.expresion }}
                                    </dd>
                                </div>

                            </div>
                        </div>

                        <div
                            v-for="(item, key) in showAll"
                            :key="key"
                            class="panel"
                            style="border-radius: 10px; padding: 10px"
                            data-soportable-id="ui-general-1"
                        >
                            <h5 v-if="item.tipo == 'Ensayo'">
                                Información de ejecución del ensayo
                            </h5>
                            <h5 v-if="item.tipo == 'Duplicado'">
                                Información de ejecución del duplicado
                            </h5>

                            <h5 v-if="item.tipo == 'Ensayo - Repetición'">
                                Información de repetición del ensayo
                            </h5>

                            <h5
                                v-if="item.tipo == 'Ensayo - Regla de decisión'"
                            >
                                Información de ejecución del ensayo con regla de
                                decisión
                            </h5>

                            <div style="padding: 15px">
                                <div style="padding: 15px">
                               <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Conductividad μs/cm:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.conductividad }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Consecutivo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.consecutivo }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Temperatura(cº): 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.temperatura_con }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Lectura equipo: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.lectura }}
                                    </dd>
                                </div>
                                <div class="row mt-3">
                                    <dt class="text-inverse text-left col-2">
                                        Conductividad: 
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.resultado }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                    Conductividad (Escala TON):  
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.temperatura_col }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.resultado_completo }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Expresión de resultado:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.expresion }}
                                    </dd>
                                </div>

                            </div>
                                </div>
                                <div v-if="item.tipo == 'Duplicado'">
                                    <div
                                        v-for="(itema,
                                        key) in item.lc_dpr_pr_turbidez"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Diferencia porcentual relativa'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Diferencia Porcentual Relativa
                                            </h5>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Fecha de ejecución:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.created_at }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Código de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.consecutivo }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Resultado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.resultado }}%
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Resultado completo:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.resultado_completo
                                                    }}%
                                                </dd>
                                            </div>
                                        </div>
                                        <div
                                            v-if="itema.tipo == 'Promedio'"
                                            class="mt-5"
                                        >
                                            <h5>
                                                Promedio
                                            </h5>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Fecha de ejecución:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.created_at }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Promedio:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.resultado }}
                                                </dd>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                            <div class="modal-footer">
                        <button
                            class="btn btn-warning"
                            type="button"
                            @click="printContent('VerDetallesEnsayo')"
                        >
                            <i class="fa fa-print mr-2"></i>Imprimir
                        </button>
                        <button class="btn btn-white" data-dismiss="modal">
                            <i class="fa fa-times mr-2"></i>Cerrar
                        </button>
                    </div>
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
export default class CloruroPanelComponent extends Vue {
    /**
     * Datos blanco pendiente
     */
    public blapendiente: any;

    /**
     * Datos blanco pendiente
     */
    public blacurva: any;

    /**
     * Datos blanco intercepto
     */
    public blaintercepto: any;

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
     *  Paginador
     */
    public dataPaginator: any;

    /**
     * Suma la cantidad de registros
     */
    public totalRegistro: any;

    /**
     * Inicia valores de busqueda
     */
    public initValuesSearch: Object;

    /**
     *  Carga el codigo de la muestra cuando se selecciona
     */
    public codigoMuestra: any;

    /**
     * Funcionalidades de traduccion de texto
     */
    public lang: any;

    /**
     *  Campos para la busqueda
     */
    public searchFields: any;

    /**
     *  Datos de los porcentajes a ejecutar
     */
    public datosPorcentajes: any;

    public divName: any;
    /**
     *  Datos de relativa cuando se van a ejecutar
     */
    public datosRelativa: any;

    /**
     *  Datos de relativa cuando se van a ejecutar
     */
    public datosPromedio: any;
    /**
     *  Decimales ejecucion blanco
     */
    public decimalBlanco: any;
    /**
     *  Decimales ejecucion patron
     */
    public decimalPatron: any;

    /**
     *  Guarda que metodo va ejecutar si tiene duplicado
     */
    public metodoTotal: any;

    /**
     *  Si hay un filtro aplicado sera mayor que 0
     */
    public contadorFiltro: any;

    /**
     *  Carga cuando se va mostrar todo
     */
    public showAll: any;

    /**
     *  Carga cuando se va mostrar la muestra
     */
    public muestraShow: any;

    /**
     *  Carga cuando se va mostrar la muestra
     */
    public datoConcentracion: any;

    /**
     *  Carga cuando se va mostrar la muestra
     */
    public ultimoBlanco: any;

    /**
     *  Carga ver detalles patron
     */
    public muestraPatron: any;
    /**
     *  Carga ver detalles blanco
     */
    public muestraBlanco: any;
    /**
     * Carga el id de la muestra que se selecciona
     */
    public idMuestra: any;

    /**
     *  Carga el ensayo que se seleccione
     */
    public idEnsayo: any;

    /**
     * Lista de toda la tabla
     */
    public listado: Array<any>;

    /**
     * Respaldo listado de toda la tabla
     */
    public listadoR: Array<any>;

    /**
     * Total del paginador
     */
    public total: Array<any> = [];

    /**
     * Decimales de cuando se va ejecutar el ensayo
     */
    public decimalEnsayo: any;

    /**
     * Datos del blanco
     */
    public datosBlanco: Array<any> = [];

    /**
     * Datos blanco de la carta
     */
    public datosBlancoCarta: Array<any> = [];

    /**
     * Muestra ensayos
     */
    public ensayos: Array<any> = [];

    /**
     * Datos patron carta
     */
    public datosPatronCarta: Array<any> = [];

    /**
     * Data form del formulario
     */
    public dataForm: any;

    /**
     * El campo a mostrar los datos
     */
    public dataShow: any;

    /**
     * Constructor de la clase
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    constructor() {
        super();
        this.lang = (this.$parent as any).lang;
        this.total = [];
        this.datosBlanco = [];
        this.datoConcentracion = [];
        this.ultimoBlanco = [];
        this.datosBlancoCarta = [];
        this.datosPatronCarta = [];
        this.datosPorcentajes = [];
        this.showAll = [];
        this.muestraShow = [];
        this.muestraPatron = [];
        this.muestraBlanco = [];
        this.metodoTotal = "Seleccione";
        this.datosRelativa = [];
        this.datosPromedio = [];
        this.divName = "VerDetalles";
        this.decimalEnsayo = 0;
        this.codigoMuestra = 0;
        this.ensayos = [];

        this.decimalBlanco = 0;
        this.decimalPatron = 0;
        this.idEnsayo = 0;
        this.listado = [];
        this.listadoR = [];
        this.dataForm = {};
        this.dataShow = {};
        this.searchFields = [];
        this.blapendiente = 0;
        this.blacurva = 0;
        this.blaintercepto = 0;
        this.totalRegistro = 0;
        this.idMuestra = 0;
        this.contadorFiltro = 0;
        this.decimalesBlanco();
        this.getListado();
        this.dataPaginator = {
            currentPage: 1,
            numPages: 1,
            pagesItems: 10,
            total: 0
        };
    }

    mounted() {}
    /**
     * Guarda los datos del blanco y valida los campos vacios
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public storeBlanco(): void {
        let arrayForm = this.dataForm;

        this.dataForm["tipo"] = "Blanco";

        if (
            this.dataForm["temperatura_con"] == "" ||
            this.dataForm["temperatura_con"] == null ||
            this.dataForm["conductividad"] == "" ||
            this.dataForm["conductividad"] == null 
        ) {
            this.$swal({
                title: "Complete los datos !",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                axios
                    .post("ensayo-" + this.nameFormulario, this.dataForm)
                    .then(res => {
                        if (res.data.data.estado == "No") {
                            this.$swal({
                                title: "El blanco ha sido rechazado!.",
                                icon: "warning"
                            });
                        } else {
                            this.$swal({
                                title: "El blanco ha sido aprobado con éxito.",
                                icon: "success"
                            });
                        }
                        this.getListado();
                        this.dataForm = {};
                        $(`#modal-ejecutar-blanco`).modal("hide");
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Guarda los datos del patron y valida los campos vacios
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public storePatron(): void {
        this.dataForm["tipo"] = "Patrón";

        if (

            this.dataForm["temperatura_con"] == "" ||
            this.dataForm["temperatura_con"] == null ||
            this.dataForm["conductividad"] == "" ||
            this.dataForm["conductividad"] == null 
        ) {
            this.$swal({
                title: "Complete los datos !",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                axios
                    .post("ensayo-" + this.nameFormulario, this.dataForm)
                    .then(res => {
                        if (res.data.data.estado == "No") {
                            this.$swal({
                                title: "El STD ha sido rechazado!",
                                icon: "warning"
                            });
                        } else {
                            this.$swal({
                                title: "El STD ha sido aprobado con éxito.",
                                icon: "success"
                            });
                        }
                        this.getListado();
                        this.dataForm = {};
                        $(`#modal-ejecutar-patron`).modal("hide");
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Carga los datos a mostrar en la ejecucuon del blanco
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosBlancoFunction() {
        axios
            .get("get-datos-blanco-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.datosBlanco = res.data.data;
                this.cargaConcentracion();
                this.decimalesBlanco();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });

        $(`#modal-ejecutar-blanco`).modal("toggle");
    }

    /**
     * Carga los datos de la concentracion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public cargaConcentracion() {
        axios
            .get("get-datos-primario-" + this.nameFormulario)
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
                this.ultimoBlanco = res.data.data["temperatura_con"];
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * Carga los datos a la hora de ejecutar el patron
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosPatronFunction() {
        axios
            .get("get-datos-blanco-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.datosBlanco = res.data.data;
                this.cargaConcentracion();
                this.cargaUltimoBlanco();
                this.decimalesPatron();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });

        $(`#modal-ejecutar-patron`).modal("toggle");
    }

    /**OJOOOO
     * envia los datos del al servidor y confirma con un sweeralert2
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosBlancoCartaFunction() {
        axios
            .get("get-datos-carta-blanco")
            .then(res => {
                // Llena la lista de datos
                this.datosBlancoCarta = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**OJOOOOO
     * envia los datos del al servidor y confirma con un sweeralert2
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosPatronCartaFunction() {
        axios
            .get("get-datos-carta-patron")
            .then(res => {
                // Llena la lista de datos
                this.datosPatronCarta = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * trae el listado de los ensayos de la base de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public getListado() {
        this.$swal({
            title: "Cargando datos",
            allowOutsideClick: false,
            onBeforeOpen: () => {
                (this.$swal as any).showLoading();
            }
        });
        axios
            .get("get-all-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.listado = res.data.data;
                this.listadoR = res.data.data;
                this.getEjecutarEnsayo();

                this.totalRegistro = this.listado.length;
                (this.$swal as any).close();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
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
        return this.paginate(this.listado);
    }

    /**
     * Limpia los datos de busqueda y reinicia el listado
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public clearDataSearch() {
        this.searchFields = [];
        this.listado = this.listadoR;
        this.contadorFiltro = 0;
    }

    /**
     * muestra un registro de los ensayos
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public showReg(dataElement: object) {
        this.dataShow = dataElement;

        $(`#modal-show-ensayo`).modal("toggle");
    }

    /**
     *reinicia el listado y limpia el registro de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public reinicia() {
        this.getListado();
        this.dataForm = {};
    }

    /**
     * envia y ejecuta el ensayo
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public getEjecutarEnsayo() {
        axios
            .get("get-ejecutar-ensayo-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.ensayos = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * ejecutar el ensayo de cloruro
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public alcalinidadEjecuta() {
        axios
            .get("get-ejecutar-" + this.nameFormulario)
            .then(res => {})
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * decimales del blanco
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public decimalesBlanco() {
        axios
            .get("get-decimales-blanco-" + this.nameFormulario)
            .then(res => {
                this.decimalBlanco = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * decimales del patron
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public decimalesPatron() {
        axios
            .get("get-decimales-patron-" + this.nameFormulario)
            .then(res => {
                this.decimalPatron = res.data.data;
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * guarda la ejecucion del duplicado y valida que no hayan campos vacios
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public storeDuplicado(dataElement: object) {

        this.dataForm["metodo"] = this.metodoTotal;
        this.dataForm["tipo"] = "Duplicado";
        this.dataForm["cantidadDecimales"] = this.decimalEnsayo;
        this.dataForm["idMuestra"] = this.idMuestra;

        if (
            this.dataForm["conductividad"] == "" ||
            this.dataForm["conductividad"] == null ||
            this.dataForm["temperatura_con"] == "" ||
            this.dataForm["temperatura_con"] == null
        ) {
            this.$swal({
                title: "Complete los datos !",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                axios
                    .post("ensayo-" + this.nameFormulario, this.dataForm)
                    .then(res => {
                        if (res.data.data.estado == "Si") {
                            this.$swal({
                                title: "Análisis Guardado !",
                                icon: "success"
                            });
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
                        this.getListado();
                        this.dataForm = {};
                        $(`#modal-ejecutar-duplicado`).modal("hide");
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Carga los datos del ensayo a se ejecutado
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosEnsayoFunction(dataElement: object) {
        this.idMuestra = dataElement;
        this.cargaConcentracion();
        this.decimalesEnsayo();
        this.cargaUltimoBlanco();
        axios
            .get("get-datos-blanco-" + this.nameFormulario)
            .then(res => {
                // Llena la lista de datos
                this.datosBlanco = res.data.data;
                this.blaintercepto = this.datosBlanco["b_intercepto"];
                this.blapendiente = this.datosBlanco["k_pendiente"];
                this.blacurva = this.datosBlanco["curva_numero"];
                this.metodoTotal = "Seleccione";
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });
    }

    /**
     * decimales para el ensayo
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
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
     * datos de la procentual relativa
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosPorcentualRelativa(dataElement: object) {
        this.codigoMuestra = dataElement["consecutivo_ensayo"];
        this.idMuestra = dataElement["id_muestra"];
        this.idEnsayo = dataElement["id"];
        this.dataForm = {};
        this.datosAdd(dataElement);
        axios
            .get(
                "get-datos-relativa-" +
                    this.nameFormulario +
                    "/" +
                    this.idEnsayo
            )
            .then(res => {
                if (res.data.data == "no") {
                } else {
                    // this.dataForm["concentracionSolucion"] =
                    //     res.data.data.concentracion;
                    // this.dataForm["volumenMuestra"] =
                    //     res.data.data.conductividad;
                    // this.dataForm["volumenAdicionado"] =
                    //     res.data.data.volumen_adicionado;
                    // this.dataForm["add2"] = res.data.data.add2;
                    // this.dataForm["add1"] = res.data.data.add1;
                    // this.dataForm["real"] = res.data.data.real;
                    // this.dataForm["resultado"] = res.data.data.resultado;
                    this.datosRelativa = res.data.data;
                }
            })
            .catch(error => {});

        $("#modal-porcentual-relativa").modal("show");
    }

    /**
     * datos de la procentual relativa
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public promedio(dataElement: object) {
        this.codigoMuestra = dataElement["consecutivo_ensayo"];
        this.idMuestra = dataElement["id_muestra"];
        this.idEnsayo = dataElement["id"];

        axios
            .get(
                "get-datos-promedio-" +
                    this.nameFormulario +
                    "/" +
                    this.idEnsayo
            )
            .then(res => {
                if (res.data.data == "no") {
                } else {
                    this.datosPromedio = res.data.data;
                    this.dataForm = {};
                }
            })
            .catch(error => {});

        $("#modal-promedio").modal("show");
    }

    /**
     * guarda la porcentual relativa
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public storePorcentualRelativa(dataElement: object) {
        this.dataForm["tipo"] = "Diferencia porcentual relativa";
        this.dataForm["consecutivo"] = this.codigoMuestra;
        this.dataForm["id_muestra"] = this.idMuestra;
        this.dataForm["id_ensayo"] = this.idEnsayo;
        this.dataForm["cantidadDecimales"] = 1;
        this.dataForm["resultado_completo"] = this.dataForm["resultado"];
        this.dataForm["resultado"] = this.dataForm["resultado"].toFixed(1);
        if (
            this.dataForm.add2 == "" ||
            this.dataForm.add2 == null ||
            this.dataForm.add1 == "" ||
            this.dataForm.add1 == null ||
            this.dataForm.resultado == "" ||
            this.dataForm.resultado == null
        ) {
            this.$swal({
                title: "Complete los datos !",
                icon: "warning"
            });
        } else {
            if (this.datosBlanco) {
                axios
                    .post(
                        "porcentual-relativa-store-" + this.nameFormulario,
                        this.dataForm
                    )
                    .then(res => {
                        this.getListado();
                        this.dataForm = {};
                        $(`#modal-porcentual-relativa`).modal("hide");
                        this.$swal({
                            title: "Análisis Guardado !",
                            icon: "success"
                        });
                    })
                    .catch(error => {
                        console.log("Hay un error");
                    });
            }
        }
    }

    /**
     * Muestra el registro total
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public showRegTotal(dataElement: object) {
        axios
            .post("get-show-blanco-patron-" + this.nameFormulario, dataElement)
            .then(res => {
                this.muestraShow = res.data.data["muestra"];
                this.muestraPatron = res.data.data["patron"];
                this.muestraBlanco = res.data.data["blanco"];
            })
            .catch(error => {});

        axios
            .post("get-show-ensayo-" + this.nameFormulario, dataElement)
            .then(res => {
                this.showAll = res.data.data;
            })
            .catch(error => {});

        $("#modal-show-ensayo-cloruro").modal("show");
    }

    /**
     * Metodo para cargar el imprimir
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public printContent(divName) {
        // Se obtiene el elemento del id recibido por parámetro
        var printContent = document.getElementById(divName);
        // Se guarda en una variable la nueva pestaña
        var printWindow = window.open("");
        // Se obtiene el encabezado de la página actual para no perder estilos
        var headContent = document.getElementsByTagName("head")[0].innerHTML;
        // Se escribe todo el contenido del encabezado de la página actual en la pestaña nueva que se abrirá
        printWindow.document.write(headContent);
        // Se escribe todo el contenido del id recibido por parámetro en la pestaña nueva que se abrirá
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.close();
        // Se enfoca en la pestaña nueva
        printWindow.focus();
        // Se esperan 10 milésimas de segundos para imprimir el contenido de la pestaña nueva
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 30);
    }

    /**
     * Aca se filtran los datos del listado
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public filtrar() {
        let listadoA = [];
        let contador = 0;
        this.listado = this.listadoR;

        let fechaInicio = new Date(this.searchFields["inicio"]);
        let fechaFinal = new Date(this.searchFields["final"]);

        // created_at
        if (this.searchFields["codigo"]) {
            this.contadorFiltro = 1;
            this.listado = this.listado.filter(objeto =>
                objeto.consecutivo.includes(this.searchFields["codigo"])
            );
        }
        if (this.searchFields["inicio"]) {
            this.contadorFiltro = 1;
            this.listado = this.listado.filter(
                objeto =>
                    new Date(objeto.created_at).getTime() >=
                    fechaInicio.getTime()
            );
        }
        if (this.searchFields["final"]) {
            fechaFinal.setHours(23);
            fechaFinal.setMinutes(59);
            fechaFinal.setSeconds(59);

            var dias = 1; // Número de días a agregar
            fechaFinal.setDate(fechaFinal.getDate() + dias);

            this.contadorFiltro = 1;
            this.listado = this.listado.filter(
                objeto =>
                    new Date(objeto.created_at).getTime() <=
                    fechaFinal.getTime()
            );
        }
        if (this.searchFields["tipo"]) {
            this.contadorFiltro = 1;
            this.listado = this.listado.filter(objeto =>
                objeto.tipo.includes(this.searchFields["tipo"])
            );
        }
        if (this.searchFields["estado"]) {
            this.contadorFiltro = 1;
            this.listado = this.listado.filter(objeto =>
                objeto.estado.includes(this.searchFields["estado"])
            );
        }
    }

    /**
     * Aca se exportan los datos
     *
     * @author Manuel Marin. - Junio. 15 - 2022
     * @version 1.0.0
     */
     public exportDataTable(divName) {
        if (divName == "xlsx") {
            axios
                .post(
                    `get-export-excel-${this.nameFormulario}`,
                    {
                        data: this.listado
                    },
                    { responseType: "blob" }
                )
                .then(res => {
                    console.log(res.data);
                    // Descagar el archivo generado
                    this.downloadFile(res.data, res.headers['content-disposition'].split('filename=')[1].split('"')[1]);
                })
                .catch(err => {});
        }
    }

    /**
     * Aca se exportan los datos
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public cambiaSelect() {}

    /**
     * Aca se exportan los datos
     *
     * @author Desarrollador Seven. - Enero. 23 - 2023
     * @version 1.0.0
     */
     public exportDataTableGoogle(divName) {
        if (divName == "xlsx") {
            this.$swal({
                title: "Cargando datos",
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    (this.$swal as any).showLoading();
                }
            });
            axios
                .post(
                    `get-grafico-${this.nameFormulario}`,
                    {
                        data: this.listado
                    },
                    { responseType: "blob" }
                )
                .then(res => {
                    // Descagar el archivo generado
                    this.downloadFile(res.data, res.headers['content-disposition'].split('filename=')[1].split('"')[1]);
                    (this.$swal as any).close();
                })
                .catch(err => {});
        }
    }

    /**
     * Descarga un archivo codificado
     *
     * @author Desarrollador Seven. - Enero. 23 - 2023
     * @version 1.0.0
     *
     * @param file datos de archivo a construir
     * @param filename nombre de archivo
     */
     public downloadFile(
        file: string,
        filename: string
    ): void {
        // Crea el archivo tipo blob
        let newBlob = new Blob([file]);

        // Para otros navegadores:
        // Crea un enlace que apunta al ObjectURL que contiene el blob.
        const data = window.URL.createObjectURL(newBlob);
        let link = document.createElement("a");
        link.href = data;
        link.download = `${filename}`;
        link.click();
        setTimeout(() => {
            // Para Firefox es necesario retrasar la revocación de ObjectURL
            window.URL.revokeObjectURL(data);
        }, 100);
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
        if (number) {
            // Asigna el tipo de moneda que se va a utilizar
            let currencyFormat = Intl.NumberFormat("es-CO", {
                maximumFractionDigits: this.decimalBlanco
            });

            // Valida si el lenguaje del sistema es ingles
            if (this.lang.locale == "en") {
                // Asigna el formato de moneda de dolar
                currencyFormat = Intl.NumberFormat("en-US", {
                    maximumFractionDigits: this.decimalBlanco
                });
            }
            // Retorna el datos formateado con el tipo de moneda
            return currencyFormat.format(number);
        } else {
            // Valida si el dato viene nulo
            if (number === null) {
                return 0;
            } else {
                return number;
            }
        }
    }

    //Se guarda el estado del blanco o del patron
    public storeEstado() {
        if (
            (this.dataShow.esta == "Si" || this.dataShow.esta == "No") &&
            this.dataShow.observacion_analista
        ) {
            axios
                .post("get-estado-" + this.nameFormulario, this.dataShow)
                .then(res => {
                    this.dataShow = res.data.data;
                    this.getListado();
                    this.getEjecutarEnsayo();
                })
                .catch(error => {});
        } else {
            this.$swal({
                title: "Seleccione el estado !",
                icon: "warning"
            });
        }
    }

    /**
     * datos de la procentual relativa
     *
     * @author Manuel Marin. - Junio. 15 - 2022
     * @version 1.0.0
     */
     public datosAdd(dataElement: object) {
        this.dataForm["add2"] = dataElement["conductividad"];

        axios
            .get("get-ensayop-" + this.nameFormulario + "/" + dataElement["id"])
            .then(res => {
                this.dataForm["add1"] = res.data.data["conductividad"];
            })
            .catch(error => {});
    }
}
</script>
