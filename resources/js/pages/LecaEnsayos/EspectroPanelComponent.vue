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

            <!-- Aqui van los botones -->
            <div class="m-t-20 mt-5">
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
                                    : LECA - R - 035
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
                                show="volumen"
                                label="Volumen muestra (mL)"
                            ></table-column>
                            <table-column
                                show="factor_disolucion"
                                label="FD"
                            ></table-column>
                            <table-column
                                show="absorbancia"
                                label="Absorbancia"
                            ></table-column>
                            <table-column
                                show="concentracion"
                                label="Concentración (mg/L)"
                            ></table-column>
                            <table-column
                                show="aprobacion_usuario"
                                label="Aprobado analista"
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
                                                row.tipo == 'Patrón' ||
                                                row.tipo == 'LCM'
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
                                        @click="datosMuestraFunction(row)"
                                        class="btn btn-white btn-icon btn-md"
                                        data-placement="top"
                                        title="Porcentaje de recuperación"
                                    >
                                        <i class="fas fa-percent"></i>
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

                                    <a
                                        v-if="row.tipo == 'Duplicado'"
                                        type="button"
                                        class="btn btn-white btn-icon btn-md"
                                        :href="
                                            'observacion-' +
                                                nameFormulario +
                                                '?lc_ensayo_aluminio_id=' +
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
        <!-- Aqui se ejecuta el blanco -->
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
                        <!-- Type Field -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Volumen muestra</strong>
                                    </h4>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Volumen de la muestra
                                                (mL):</label
                                            >
                                            <div class="col-md-5">
                                              <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.volumen"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.volumen"
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
                                                >Curva No:</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    readonly
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blacurva"
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
                                                class="text-inverse text-left col-6 required"
                                                >K(pendiente):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blapendiente"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >B(intercepto):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blaintercepto"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >FD:</label
                                            >
                                            <div class="col-md-5">
                                                 <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.fd"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.fd"
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
                                                >Absorbancia(s):</label
                                            >
                                            <div class="col-md-5">
                                                 <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.absorbancia
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.absorbancia"
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
                                                class="text-inverse text-left col-6"
                                                >Concentración(mg/L):</label
                                            >
                                            <div class="col-md-5">
                                                <input-operation
                                                    name-field="concentracionB"
                                                    :number-one="blapendiente"
                                                    :number-two="blaintercepto"
                                                    :number-three="dataForm.fd"
                                                    :number-four="
                                                        dataForm.absorbancia
                                                    "
                                                    :key="
                                                        dataForm.absorbancia +
                                                            blapendiente +
                                                            blaintercepto +
                                                            dataForm.fd
                                                    "
                                                    :cantidad-decimales="
                                                        decimalBlanco
                                                    "
                                                    :value="dataForm"
                                                    operation="general"
                                                    prefix=" "
                                                ></input-operation>
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

        <div class="modal fade" id="modal-ejecutar-pr">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header bg-blue">
                        <h4 class="modal-title text-white">
                            Porcentaje de recuperación
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
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >mg/L (código de la
                                                muestra):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                   class="form-group form-control"
                                                    type="text"
                                                    readonly
                                                    v-model="codigoMuestra"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Real:</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.real"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.real"
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
                                                >Dato 1:</label
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
                                                >Dato 2:</label
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
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Volumen adicionado:</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.volumenAdicionado
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.volumenAdicionado"
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
                                                >Volumen muestra:</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.volumenMuestra
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.volumenMuestra"
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
                                                >Concentración solución
                                                madre:</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.concentracionSolucion
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.concentracionSolucion"
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
                                                class="text-inverse text-left col-6"
                                                >Porcentaje de
                                                recuperación:</label
                                            >

                                            <input-operation
                                                name-field="resultado"
                                                :number-one="
                                                    parseFloat(
                                                        dataForm.concentracionSolucion
                                                    )
                                                "
                                                :number-two="
                                                    parseFloat(
                                                        dataForm.volumenMuestra
                                                    )
                                                "
                                                :number-three="
                                                    parseFloat(
                                                        dataForm.volumenAdicionado
                                                    )
                                                "
                                                :number-four="
                                                    parseFloat(dataForm.add2)
                                                "
                                                :number-five="
                                                    parseFloat(dataForm.add1)
                                                "
                                                :number-six="
                                                    parseFloat(dataForm.real)
                                                "
                                                :key="
                                                    dataForm.concentracionSolucion +
                                                        dataForm.volumenMuestra +
                                                        dataForm.volumenAdicionado +
                                                        dataForm.add2 +
                                                        dataForm.add1 +
                                                        dataForm.real
                                                "
                                                :cantidad-decimales="1"
                                                :value="dataForm"
                                                operation="porcentajeRecuperacion"
                                                suffix="%"
                                            ></input-operation>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- v-for="(item,
                                        key) in datosPorcentajes"
                                        :key="key" -->
                        <div class="m-5" id="DestallesPr">
                            <h4>Ver detalles Porcentaje de recuperación</h4>
                            <div
                                class="row"
                                v-for="(item, key) in datosPorcentajes"
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
                                                        <b>Real:</b>
                                                    </label>
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.real
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
                                                        <b>
                                                            Volumen
                                                            adicionado:</b
                                                        ></label
                                                    >
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.volumen_adicionado
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
                                                            Volumen de la
                                                            muestra:</b
                                                        ></label
                                                    >
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.volumen_muestra
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
                                                            Concentración
                                                            adición madre:</b
                                                        ></label
                                                    >
                                                    <div class="col-md-5">
                                                        <label>
                                                            {{
                                                                item.concentracion
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
                            @click="printContent('DestallesPr')"
                        >
                            <i class="fa fa-print mr-2"></i>Imprimir
                        </button>
                        <button @click="storePr()" class="btn btn-primary">
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
        <!-- Aqui se ejecuta el patron -->
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
                    <div class="modal-body">
                        <!-- formulario de ejecutar patron -->
                        <!-- Type Field -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Tipo de STD a ejecutar</strong>
                                    </h4>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >STD:</label
                                            >
                                            <div class="col-md-5">
                                                <select
                                                    v-model="
                                                        dataForm.tipo_patron
                                                    "
                                                    class="form-group form-control"
                                                >
                                                    <option value="Seleccione"
                                                        >Seleccione</option
                                                    >
                                                    <option value="Patrón"
                                                        >Patrón</option
                                                    >
                                                    <option value="LCM"
                                                        >LCM</option
                                                    >
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                        </div>
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Volumen muestra</strong>
                                    </h4>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Volumen de la muestra
                                                (mL):</label
                                            >
                                            <div class="col-md-5">
                                              <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.volumen"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.volumen"
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
                                                >Curva No:</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    readonly
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blacurva"
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
                                                class="text-inverse text-left col-6 required"
                                                >K(pendiente):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blapendiente"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >B(intercepto):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="blaintercepto"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >FD:</label
                                            >
                                            <div class="col-md-5">
                                                 <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.fd"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.fd"
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
                                                >Absorbancia(s):</label
                                            >
                                            <div class="col-md-5">
                                                 <!-- <input
                                                    class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.absorbancia
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.absorbancia"
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
                                                class="text-inverse text-left col-6"
                                                >Concentración(mg/L):</label
                                            >
                                            <div class="col-md-5">
                                                <input-operation
                                                    name-field="concentracionP"
                                                    :number-one="
                                                        parseFloat(blapendiente)
                                                    "
                                                    :number-two="
                                                        parseFloat(
                                                            blaintercepto
                                                        )
                                                    "
                                                    :number-three="
                                                        parseFloat(dataForm.fd)
                                                    "
                                                    :number-four="
                                                        parseFloat(
                                                            dataForm.absorbancia
                                                        )
                                                    "
                                                    :key="
                                                        dataForm.absorbancia +
                                                            blapendiente +
                                                            blaintercepto +
                                                            dataForm.fd +
                                                            1
                                                    "
                                                    :cantidad-decimales="
                                                        decimalPatron
                                                    "
                                                    :value="dataForm"
                                                    operation="general"
                                                    prefix=" "
                                                ></input-operation>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6"
                                                >Control concentrado del
                                                estandar:</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    type="text"
                                                    class="field left form-control"
                                                    v-model="
                                                        dataForm.addconsecutivo
                                                    "
                                                />
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
                        <!-- Type Field -->
                        <div class="panel" data-sortable-id="ui-general-1">
                            <div class="panel-body">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title">
                                        <strong>Volumen muestra</strong>
                                    </h4>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >Volumen de la muestra
                                                (mL):</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.volumen"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.volumen"
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
                                                >Curva No:</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    readonly
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left"
                                                    v-model="blacurva"
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
                                                class="text-inverse text-left col-6 required"
                                                >K(pendiente):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left"
                                                    v-model="blapendiente"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >B(intercepto):</label
                                            >
                                            <div class="col-md-5">
                                                <input
                                                    style="cursor:not-allowed; background-color: #e9ecef;"
                                                    type="text"
                                                    class="field left"
                                                    v-model="blaintercepto"
                                                    readonly
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Proceso -->
                                        <div class="form-group row m-b-15">
                                            <label
                                                class="text-inverse text-left col-6 required"
                                                >FD:</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="dataForm.fd"
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.fd"
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
                                                >Absorbancia(s):</label
                                            >
                                            <div class="col-md-5">
                                                <!-- <input
                                                   class="form-group form-control"
                                                    type="number"
                                                    v-model="
                                                        dataForm.absorbancia
                                                    "
                                                /> -->
                                                <currency-input
                                                    v-model="dataForm.absorbancia"
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
                                                class="text-inverse text-left col-6"
                                                >Concentración(mg/L):</label
                                            >
                                            <div class="col-md-6">
                                                <input-operation
                                                   class="form-group form-control"
                                                    name-field="concentracion"
                                                    :number-one="blapendiente"
                                                    :number-two="blaintercepto"
                                                    :number-three="dataForm.fd"
                                                    :number-four="
                                                        dataForm.absorbancia
                                                    "
                                                    :key="
                                                        dataForm.absorbancia +
                                                            blapendiente +
                                                            blaintercepto +
                                                            dataForm.fd
                                                    "
                                                    :cantidad-decimales="
                                                        decimalEnsayo
                                                    "
                                                    :value="dataForm"
                                                    operation="general"
                                                    prefix=" "
                                                ></input-operation>
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
                                                        >Regla de
                                                        decisión</option
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
                                                        >Regla de
                                                        decisión</option
                                                    >
                                                </select>
                                            </div>
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

        <!-- Aqui se ven detalles del blanco y patron -->
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
                        <div class="row" id="VerDetalles">
                            <!-- State Routine Field -->
                            <dt class="text-inverse text-left col-3">
                                Fecha de ejecución:
                            </dt>
                            <dd class="col-9">{{ dataShow.created_at }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Absorbancia:
                            </dt>
                            <dd class="col-9">{{ dataShow.absorbancia }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Concentración:
                            </dt>
                            <dd class="col-9">{{ dataShow.concentracion }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Consecutivo:
                            </dt>
                            <dd class="col-9">{{ dataShow.consecutivo }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Resultado completo:
                            </dt>
                            <dd class="col-9">{{ dataShow.resultado }}</dd>

                            <dt class="text-inverse text-left col-3">Curva:</dt>
                            <dd class="col-9">{{ dataShow.curva }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Estado:
                            </dt>
                            <dd class="col-9">{{ dataShow.created_at }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Pendiente:
                            </dt>
                            <dd class="col-9">{{ dataShow.pendiente }}</dd>

                            <dt class="text-inverse text-left col-3">Tipo:</dt>
                            <dd class="col-9">{{ dataShow.tipo }}</dd>

                            <dt class="text-inverse text-left col-3">
                                Aprobado:
                            </dt>
                            <dd class="col-9">{{ dataShow.estado }}</dd>

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
                                v-if="
                                    dataShow.aprobacion_usuario == 'Pendiente'
                                "
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

        <div class="modal fade" id="modal-show-ensayo-aluminio">
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

                                    <dt class="text-inverse text-left col-2">
                                        PH:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraShow.reception_ph }}
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
                                        {{ muestraShow.conductivity_reception }}
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
                                        Volumen de la muestra(mL):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.volumen }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Curva No:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.curva }}
                                    </dd>
                                </div>
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        K (pendiente):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.pendiente }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        b (intercepto):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.intercepto }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        FD:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.factor_disolucion }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Absorbancia(s):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.absorbancia }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Concentración:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.concentracion }}
                                    </dd>

                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Resultado completo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraBlanco.resultado }}
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
                                        Volumen de la muestra(mL):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.volumen }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Curva No:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.curva }}
                                    </dd>
                                </div>
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        K (pendiente):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.pendiente }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        b (intercepto):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.intercepto }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        FD:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.factor_disolucion }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Absorbancia(s):
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.absorbancia }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Concentración:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.concentracion }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Resultado completo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ muestraPatron.resultado }}
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
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Consecutivo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.consecutivo }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Curva No:
                                    </dt>
                                    <dd class="col-4">{{ item.curva }}</dd>
                                </div>
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Volumen de la muestra(mL):
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.volumen }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Curva No:
                                    </dt>
                                    <dd class="col-4">{{ item.curva }}</dd>
                                </div>
                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        K (pendiente):
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.pendiente }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        b (intercepto):
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.intercepto }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        FD:
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.factor_disolucion }}
                                    </dd>

                                    <dt class="text-inverse text-left col-2">
                                        Absorbancia(s):
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.absorbancia }}
                                    </dd>
                                </div>

                                <div class="row mt-3">
                                    <!-- User Name Field -->
                                    <dt class="text-inverse text-left col-2">
                                        Concentración:
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.concentracion }}
                                    </dd>
                                    <dt class="text-inverse text-left col-2">
                                        Resultado completo:
                                    </dt>
                                    <dd class="col-4">
                                        {{ item.resultado }}
                                    </dd>
                                </div>
                                <div v-if="item.tipo == 'Duplicado'">
                                    <div
                                        v-for="(itema,
                                        key) in item.dprpr_nitritos"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
                                    </div>
                                    <div
                                        v-for="(itema,
                                        key) in item.dprpr_fosfato"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
                                    </div>
                                    <div
                                        v-for="(itema,
                                        key) in item.dpr_pr_hierro"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
                                    </div>

                                    <div
                                        v-for="(itema, key) in item.dpr_pr"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
                                    </div>

                                    <div
                                        v-for="(itema,
                                        key) in item.dprpr_nitratos"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
                                    </div>

                                    <div
                                        v-for="(itema,
                                        key) in item.dpr_pr_carbono_organico"
                                        :key="key"
                                    >
                                        <div
                                            v-if="
                                                itema.tipo ==
                                                    'Porcentaje de recuperacion'
                                            "
                                            class="mt-5"
                                        >
                                            <h5>
                                                Porcentaje de recuperación
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
                                                    Real:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.real }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 1:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add1 }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Dato 2:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.add2 }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen adicionado:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
                                                </dd>
                                            </div>
                                            <div class="row mt-3">
                                                <!-- User Name Field -->
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Volumen de la muestra:
                                                </dt>
                                                <dd class="col-4">
                                                    {{ itema.volumen_muestra }}
                                                </dd>
                                                <dt
                                                    class="text-inverse text-left col-2"
                                                >
                                                    Concentración adición madre:
                                                </dt>
                                                <dd class="col-4">
                                                    {{
                                                        itema.volumen_adicionado
                                                    }}
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
export default class EspectroPanelComponent extends Vue {
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
     *Muestra 1 original
     */
    public add1: any;

    /**
     * muestra 2 duplicado
     */
    public add2: any;
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

        this.total = [];
        this.datosBlanco = [];
        this.datosBlancoCarta = [];
        this.datosPatronCarta = [];
        this.datosPorcentajes = [];
        this.showAll = [];
        this.muestraShow = [];
        this.muestraPatron = [];
        this.muestraBlanco = [];
        this.metodoTotal = "Seleccione";
        this.datosRelativa = [];
        this.divName = "VerDetalles";
        this.decimalEnsayo = 4;
        this.codigoMuestra = 0;
        this.ensayos = [];
        this.add1 = 0;
        this.add2 = 0;
        this.decimalBlanco = 4;
        this.decimalPatron = 4;
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
        this.dataForm["tipo"] = "Blanco";
        this.dataForm["cantidadDecimales"] = this.decimalBlanco;
        this.dataForm["pendiente"] = this.blapendiente;
        this.dataForm["curva"] = this.blacurva;
        this.dataForm["intercepto"] = this.blaintercepto;
        this.dataForm["concentracion"] = this.dataForm[
            "concentracionB"
        ].toFixed(this.decimalBlanco);
        this.dataForm["resultado"] = this.dataForm["concentracionB"];
        if (
            this.dataForm["volumen"] == "" ||
            this.dataForm["volumen"] == null ||
            this.dataForm.curva == "" ||
            this.dataForm.curva == null ||
            this.dataForm.fd == "" ||
            this.dataForm.fd == null ||
            this.dataForm.absorbancia == "" ||
            this.dataForm.absorbancia == null
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
        this.dataForm["pendiente"] = this.blapendiente;
        this.dataForm["curva"] = this.blacurva;
        this.dataForm["cantidadDecimales"] = this.decimalPatron;
        this.dataForm["intercepto"] = this.blaintercepto;
        this.dataForm["concentracion"] = this.dataForm[
            "concentracionP"
        ].toFixed(this.decimalPatron);
        this.dataForm["resultado"] = this.dataForm["concentracionP"];

        if (
            this.dataForm["volumen"] == "" ||
            this.dataForm["volumen"] == null ||
            this.dataForm.curva == "" ||
            this.dataForm.curva == null ||
            this.dataForm.fd == "" ||
            this.dataForm.fd == null ||
            this.dataForm.absorbancia == "" ||
            this.dataForm.absorbancia == null
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
     * Este metodo es para asignar los valores consultados a estas variables que se muestran cuando se ejecuta  el blanco
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public ejecutarBlanco() {
        this.blaintercepto = this.datosBlanco["b_intercepto"];
        this.blapendiente = this.datosBlanco["k_pendiente"];
        this.blacurva = this.datosBlanco["curva_numero"];
    }

    /**
     * Este metodo es para asignar los valores consultados a estas variables que se muestran cuando se ejecuta  el patron
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public ejecutarPatron() {
        this.blaintercepto = this.datosBlanco["b_intercepto"];
        this.blapendiente = this.datosBlanco["k_pendiente"];
        this.blacurva = this.datosBlanco["curva_numero"];
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
                this.decimalesBlanco();

                this.ejecutarBlanco();
            })
            .catch(err => {
                console.log("Error al obtener la lista.");
            });

        $(`#modal-ejecutar-blanco`).modal("toggle");
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
                this.decimalesPatron();

                this.ejecutarPatron();
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
     * ejecutar el ensayo de aluminio
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public aluminioEjecuta() {
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
        this.dataForm["pendiente"] = this.blapendiente;
        this.dataForm["curva"] = this.blacurva;
        this.dataForm["intercepto"] = this.blaintercepto;
        this.dataForm["idMuestra"] = this.idMuestra;

        if (
            this.dataForm["metodo"] == "" ||
            this.dataForm["metodo"] == null ||
            this.dataForm["metodo"] == "Seleccione" ||
            this.dataForm["volumen"] == "" ||
            this.dataForm["volumen"] == null ||
            this.dataForm.curva == "" ||
            this.dataForm.curva == null ||
            this.dataForm.fd == "" ||
            this.dataForm.fd == null ||
            this.dataForm.absorbancia == "" ||
            this.dataForm.absorbancia == null
        ) {
        } else {
            console.log(this.dataForm["concentracion"]);
            this.dataForm["resultado"] = this.dataForm["concentracion"];
            this.dataForm["concentracion"] = this.dataForm[
                "concentracion"
            ].toFixed(this.decimalEnsayo);
        }

        if (
            this.dataForm["metodo"] == "" ||
            this.dataForm["metodo"] == null ||
            this.dataForm["metodo"] == "Seleccione" ||
            this.dataForm["volumen"] == "" ||
            this.dataForm["volumen"] == null ||
            this.dataForm.curva == "" ||
            this.dataForm.curva == null ||
            this.dataForm.fd == "" ||
            this.dataForm.fd == null ||
            this.dataForm.absorbancia == "" ||
            this.dataForm.absorbancia == null
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
        console.log(this.idMuestra);

        this.decimalesEnsayo();
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
     * Muestra los datos de la porcentaje de recuperacion
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public datosMuestraFunction(dataElement: object) {
        this.codigoMuestra = dataElement["consecutivo_ensayo"];
        this.idMuestra = dataElement["id_muestra"];
        this.idEnsayo = dataElement["id"];
        this.dataForm = {};
        this.datosAdd(dataElement);
        axios
            .get("get-datos-pr-" + this.nameFormulario + "/" + this.idEnsayo)
            .then(res => {
                if (res.data.data == "no") {
                } else {
                    // this.dataForm["concentracionSolucion"] =
                    //     res.data.data.concentracion;
                    // this.dataForm["volumenMuestra"] =
                    //     res.data.data.volumen_muestra;
                    // this.dataForm["volumenAdicionado"] =
                    //     res.data.data.volumen_adicionado;
                    // this.dataForm["add2"] = res.data.data.add2;
                    // this.dataForm["add1"] = res.data.data.add1;
                    // this.dataForm["real"] = res.data.data.real;
                    // this.dataForm["resultado"] = res.data.data.resultado;
                    this.datosPorcentajes = res.data.data;
                }
            })
            .catch(error => {});

        // this.add2=
        $("#modal-ejecutar-pr").modal("show");
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
    public datosAdd(dataElement: object) {
        this.dataForm["add2"] = dataElement["resultado"];

        axios
            .get("get-ensayop-" + this.nameFormulario + "/" + this.idMuestra)
            .then(res => {
                this.dataForm["add1"] = res.data.data["resultado"];
            })
            .catch(error => {});
    }

    /**
     * ejecuta el porcentaje de recuperacion y valida los campos vacios
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
     * @version 1.0.0
     */
    public storePr() {
        this.dataForm["tipo"] = "Porcentaje de recuperacion";
        this.dataForm["consecutivo"] = this.codigoMuestra;
        this.dataForm["id_muestra"] = this.idMuestra;
        this.dataForm["id_ensayo"] = this.idEnsayo;
        this.dataForm["cantidadDecimales"] = 1;
        this.dataForm["resultado_completo"] = this.dataForm["resultado"];
        this.dataForm["resultado"] = this.dataForm["resultado"].toFixed(1);

        if (
            this.dataForm["concentracionSolucion"] == "" ||
            this.dataForm["concentracionSolucion"] == null ||
            this.dataForm.volumenMuestra == "" ||
            this.dataForm.volumenMuestra == null ||
            this.dataForm.volumenAdicionado == "" ||
            this.dataForm.volumenAdicionado == null ||
            this.dataForm.add2 == "" ||
            this.dataForm.add2 == null ||
            this.dataForm.add1 == "" ||
            this.dataForm.add1 == null ||
            this.dataForm.real == "" ||
            this.dataForm.real == null ||
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
                    .post("dpr-store-" + this.nameFormulario, this.dataForm)
                    .then(res => {
                        this.getListado();
                        this.dataForm = {};
                        $(`#modal-ejecutar-pr`).modal("hide");
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

        $("#modal-show-ensayo-aluminio").modal("show");
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
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
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
     * Descarga un archivo codificado
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
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
                title: "Ingrese la observación!",
                icon: "warning"
            });
        }
    }

    
    /**
     * Aca se exportan los datos
     *
     * @author Nicolas Dario Ortiz Peña. - Junio. 15 - 2022
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
}
</script>
