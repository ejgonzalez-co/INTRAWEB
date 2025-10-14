<template>
    <div class="container">
        <!-- Modal que se va a abrir -->
        <b-modal v-model="mostrarModal" title="Vincular documento a expediente electrónico" @hide="cerrarModal" size="lg" header-class="bg-blue text-white" hide-footer>
            <b-form ref="form_vincular_documento">
                <!-- <div class="info-box" v-if="dataForm.consecutivo_expediente">
                    <p><strong>Este registro ya está asociado a un expediente.</strong></p>
                    <p><strong>Consecutivo del expediente: <a :href="'/expedientes-electronicos/documentos-expedientes?c='+dataForm.id_expediente_info">{{ dataForm.consecutivo_expediente }}</a></strong></p>
                </div> -->
                <div style="display: flex;">
                    <div class="modal-body">
                        <div class="form-group row m-b-15" v-if="!dataForm.que_desea_hacer">
                            <label class="col-form-label col-md-3">¿Qué desea hacer?:</label>
                            <div class="col-md-6">
                                <select class="form-control" name="que_desea_hacer"
                                    id="que_desea_hacer" v-model="dataForm.que_desea_hacer" required>
                                    <option value="1">Incluir documento a un expediente</option>
                                    <option value="2" v-if="this.puedeCrearExpedientes">Crear expediente e incluir documento</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="dataForm.que_desea_hacer == '1'">
                            <div class="panel" data-sortable-id="ui-general-1">
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Detalle del documento que se vinculará al expediente: {{valorRegistro[this.campoConsecutivo]}}</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <viewer-attachement v-if="valorRegistro.document_pdf || valorRegistro.pqr_correspondence?.document_pdf || valorRegistro.correo_integrado_datos?.adjuntos_correo_cadena" :link-file-name="true" :list="valorRegistro.document_pdf ?? valorRegistro.pqr_correspondence?.document_pdf ?? valorRegistro.correo_integrado_datos?.adjuntos_correo_cadena" :key="valorRegistro.document_pdf ?? valorRegistro.pqr_correspondence?.document_pdf ?? valorRegistro.correo_integrado_datos?.adjuntos_correo_cadena"></viewer-attachement>
                                    <span v-else-if="valorRegistro.channel_name == 'Correo electrónico'">Correo electrónico</span>
                                    <span v-else>Sin documento</span>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color: rgb(172 213 175); padding: 10px;">
                                    <h4 class="panel-title">
                                        <strong>Listado de expedientes autorizados</strong>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <!-- Campo de texto y botón para la búsqueda -->
                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Listado de expedientes disponibles:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-prop="consecutivo"
                                                :name-field="moduloConsecutivo"
                                                :reduce-label="['consecutivo','nombre_expediente']"
                                                :name-resource="'/expedientes-electronicos/get-expediente-filtros/'+this.querySelect"
                                                :value="dataForm"
                                                :key="this.querySelect"
                                                :is-required="true"
                                                reduce-key="consecutivo"
                                                name-field-object="expediente_datos"
                                                :enable-search="true"
                                                :function-change="verificarCanal">
                                            </select-check>
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-15" v-if="dataForm.expediente_datos?.id">
                                        <div class="col-form-label col-md-3"><label class="required">Tipo documental:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="ee_tipos_documentales_id"
                                                name-field-object="metadatos_tipo_documental"
                                                reduce-label="name"
                                                :name-resource="getEncryptedResourceUrl()"
                                                :value="dataForm"
                                                :is-required="true"
                                                ref-select-check="tipos_documentales_ref"
                                                :enable-search="true"
                                                :key="dataForm[moduloConsecutivo]">
                                            </select-check>
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-15" v-if="dataForm.expediente_datos?.id">
                                        <div class="col-form-label col-md-3"><label class="required">Página inicio:</label></div>
                                        <div class="col-md-9">
                                            <input v-model="dataForm.pagina_inicio" type="text" class="form-control" placeholder="Ingrese la página de inicio" required />
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-15" v-if="dataForm.expediente_datos?.id">
                                        <div class="col-form-label col-md-3"><label class="required">Página fin:</label></div>
                                        <div class="col-md-9">
                                            <input v-model="dataForm.pagina_fin" type="text" class="form-control" placeholder="Ingrese la página fin" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" style="background-color: #FFFFFF; padding: 10px; border-radius: 5px;">
                                    <h4 class="panel-title">
                                    <a data-toggle="collapse" href="#collapseFilters" aria-expanded="true" style="color: black; text-decoration: none;">
                                        <strong>Use los siguientes filtros para los expedientes y presione el botón buscar</strong>
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapseFilters" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <!-- Campo de texto y botón para la búsqueda -->
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Nombre del expediente:</label>
                                            <div class="col-md-6">
                                                <input v-model="camposFiltros.nombre_expediente" type="text" class="form-control" placeholder="Escribe el nombre" />
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Usuario responsable:</label>
                                            <div class="col-md-6">
                                                <input v-model="camposFiltros.vigencia" type="text" class="form-control" placeholder="Escribe el responsable" />
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Fecha de inicio:</label>
                                            <div class="col-md-6">
                                                <date-picker
                                                    :value="camposFiltros"
                                                    name-field="fecha_inicio_expediente"
                                                    mode="range"
                                                    :input-props="{required: false}">
                                                </date-picker>
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Oficina productora:</label>
                                            <div class="col-md-6">
                                                <select-check css-class="form-control" name-field="classification_production_office" reduce-label="nombre" name-resource="/intranet/get-dependencies" :is-required="false" :value="camposFiltros" :enable-search="true" :ids-to-empty="['classification_serie','classification_subserie']"></select-check>
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Serie:</label>
                                            <div class="col-md-6">
                                                <select-check css-class="form-control" name-field="classification_serie" reduce-label="name" reduce-key="id_series_subseries" :is-required="false" :name-resource="'/documentary-classification/get-inventory-documentals-serie-dependency/'+ camposFiltros.classification_production_office"
                                                :value="camposFiltros" :enable-search="true" :key="camposFiltros.classification_production_office"></select-check>
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <label class="col-form-label col-md-3">Subserie:</label>
                                            <div class="col-md-6">
                                                <select-check css-class="form-control" name-field="classification_subserie" reduce-label="name_subserie" :name-resource="'/documentary-classification/get-subseries-clasificacion?serie='+camposFiltros.classification_serie" :is-required="false" :value="camposFiltros" :key="camposFiltros.classification_serie" :enable-search="true"></select-check>
                                            </div>
                                        </div>
                                        <div class="form-group row m-b-15">
                                            <div class="col-md-3">
                                                <button type="button" @click="buscarExpediente" class="btn btn-primary">Buscar</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" @click="limpiarFiltros" class="btn btn-primary">Limpiar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel" data-sortable-id="ui-general-1">
                                <div class="modal-body" style="background-color:#e0e0e0!important">
                                    <h5 class="panel-title" v-if="!dataForm.expediente_datos?.ee_documentos_expedientes[0]?.adjunto"><strong>Por favor consulte o seleccione primero el expediente</strong></h5>
                                    <div class="card" v-if="dataForm.expediente_datos?.ee_documentos_expedientes[0]?.adjunto">
                                        <div class="card-header">
                                            <h4 class="mb-0"><i class="fas fa-folder-open me-2"></i>Detalles del Expediente</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-file-alt"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Nombre del expediente</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.nombre_expediente ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-hashtag detail-icon text-secondary"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Consecutivo</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.consecutivo ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Fecha inicio del expediente</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.fecha_inicio_expediente ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-info-circle"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Descripción</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.descripcion ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-user-tie"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Responsable</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.nombre_responsable ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-toggle-on"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Estado</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.estado ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-building"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Oficina productora</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.oficina_productora_clasificacion_documental?.nombre ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-layer-group"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Serie</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.serie_clasificacion_documental?.name_serie ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-sitemap"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Subserie</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.subserie_clasificacion_documental?.name_subserie ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="fas fa-comment-alt"></i>
                                                        <div style="margin-left: 5px;">
                                                            <h6 class="mb-0">Observación</h6>
                                                            <p class="mb-0">{{ dataForm.expediente_datos?.observacion ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="mb-0">Carátula del expediente</h6>
                                    <viewer-attachement v-if="dataForm.expediente_datos?.ee_documentos_expedientes[0]?.adjunto" :open-default="false" :list="dataForm.expediente_datos?.ee_documentos_expedientes[0]?.adjunto" :key="dataForm.expediente_datos?.ee_documentos_expedientes[0]?.adjunto"></viewer-attachement>
                                </div>
                            </div>
                        </div>
                        <div v-if="dataForm.que_desea_hacer == '2'">
                            <div class="panel" data-sortable-id="ui-general-1">
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Detalle del documento que se vinculará al nuevo expediente: {{valorRegistro[this.campoConsecutivo]}}</strong></h4>
                                </div>
                                <div class="panel-body">
                                    <viewer-attachement :list="valorRegistro.document_pdf" :open-default="false" :key="valorRegistro.document_pdf"></viewer-attachement>
                                </div>
                            </div>
                            <div class="panel" data-sortable-id="ui-general-1">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Nuevo expediente</strong></h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <!-- classification_production_office Field -->
                                    <div class="form-group row m-b-15">
                                        <!-- Campo Oficina Productora -->
                                        <div class="col-form-label col-md-3"><label class="required">Oficina productora:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="classification_production_office"
                                                reduce-label="nombre"
                                                name-resource="/expedientes-electronicos/get-dependencies"
                                                :is-required="true"
                                                :value="dataForm"
                                                :enable-search="true"
                                                :ids-to-empty="['classification_serie','classification_subserie']">
                                            </select-check>
                                            <small>Seleccione una oficina productora para obtener las series relacionadas.</small>
                                            <div class="invalid-feedback" v-if="dataErrors.classification_production_office">
                                                <p class="m-b-0" v-for="error in dataErrors.classification_production_office">
                                                    @{{ error }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <!-- Campo Serie -->
                                        <div class="col-form-label col-md-3"><label class="required">Serie:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="classification_serie"
                                                reduce-label="name"
                                                reduce-key="id_series_subseries"
                                                :is-required="true"
                                                :name-resource="'/documentary-classification/get-inventory-documentals-serie-dependency/' + dataForm.classification_production_office"
                                                :value="dataForm"
                                                :enable-search="true"
                                                name-field-object="serie_clasificacion_documental"
                                                :key="dataForm.classification_production_office">
                                            </select-check>
                                            <small>Seleccione una serie documental, ejemplo: Contratos.</small>
                                            <div class="invalid-feedback" v-if="dataErrors.classification_serie">
                                                <p class="m-b-0" v-for="error in dataErrors.classification_serie">
                                                    @{{ error }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <!-- Campo Subserie -->
                                        <div class="col-form-label col-md-3"><label class="">Subserie:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="classification_subserie"
                                                reduce-label="name_subserie"
                                                :name-resource="'/documentary-classification/get-subseries-clasificacion?serie=' + (dataForm.classification_serie || null)"
                                                :is-required="false"
                                                :value="dataForm"
                                                :key="dataForm.classification_serie"
                                                name-field-object="subserie_clasificacion_documental"
                                                :enable-search="true">
                                            </select-check>
                                            <small>Seleccione una sub-serie documental, ejemplo: Contratos de trabajo.</small>
                                            <div class="invalid-feedback" v-if="dataErrors.classification_subserie">
                                                <p class="m-b-0" v-for="error in dataErrors.classification_subserie">
                                                    @{{ error }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Tipo documental:</label></div>
                                        <div class="col-md-9">
                                            <select-check
                                                css-class="form-control"
                                                name-field="ee_tipos_documentales_id"
                                                reduce-label="name"
                                                :name-resource="'/expedientes-electronicos/get-tipos-documentales-crear-expedientes/' + (dataForm.classification_serie || null) + '/' + dataForm.classification_subserie"
                                                :value="dataForm"
                                                :is-required="true"
                                                ref-select-check="tipos_documentales_ref"
                                                :key="dataForm.classification_serie+dataForm.classification_subserie"
                                                :enable-search="true">
                                            </select-check>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Página inicio:</label></div>
                                        <div class="col-md-9">
                                            <input v-model="dataForm.pagina_inicio" type="text" class="form-control" placeholder="Ingrese la página de inicio" required />
                                        </div>
                                    </div>
                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Página fin:</label></div>
                                        <div class="col-md-9">
                                            <input v-model="dataForm.pagina_fin" type="text" class="form-control" placeholder="Ingrese la página fin" required />
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3">
                                            <label class="required">Nombre del expediente:</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="nombre_expediente" id="nombre_expediente" class="form-control" v-model="dataForm.nombre_expediente" required>
                                            <small>Ingrese el nombre del expediente</small>
                                            <div class="invalid-feedback" v-if="dataErrors.nombre_expediente">
                                                <p class="m-b-0" v-for="error in dataErrors.nombre_expediente">@{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Fecha inicio del expediente:</label></div>
                                        <div class="col-md-9">
                                            <input type="date" name="fecha_inicio_expediente" id="fecha_inicio_expediente" class="form-control" v-model="dataForm.fecha_inicio_expediente" required>
                                            <small>Ingrese la fecha inicial</small>
                                            <div class="invalid-feedback" v-if="dataErrors.fecha_inicio_expediente">
                                                <p class="m-b-0" v-for="error in dataErrors.fecha_inicio_expediente">@{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Descripción:</label></div>
                                        <div class="col-md-9">
                                            <textarea cols="50" rows="10" class="form-control" v-model="dataForm.descripcion" required></textarea>
                                            <small>Ingrese una descripción.</small>
                                            <div class="invalid-feedback" v-if="dataErrors.descripcion">
                                                <p class="m-b-0" v-for="error in dataErrors.descripcion">
                                                    @{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Responsable:</label></div>
                                        <div class="col-md-9">
                                            <select-check css-class="form-control" name-field="id_responsable" reduce-label="fullname" name-resource="/expedientes-electronicos/obtener-responsable" :value="dataForm" :is-required="true" :enable-search="true"></select-check>
                                            <small>Ingrese el funcionario destinatario</small>
                                            <div class="invalid-feedback" v-if="dataErrors.id_responsable">
                                                <p class="m-b-0" v-for="error in dataErrors.id_responsable">@{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row m-b-15" v-if="dataForm.id_responsable == userId">
                                        <div class="col-form-label col-md-3"><label class="required">Observación del expediente:</label></div>
                                        <div class="col-md-9">
                                            <textarea cols="50" rows="10" class="form-control" v-model="dataForm.observacion_accion" required></textarea>
                                            <small>Ingrese una observación para la aprobación del expediente.</small>
                                            <div class="invalid-feedback" v-if="dataErrors.observacion_accion">
                                                <p class="m-b-0" v-for="error in dataErrors.observacion_accion">
                                                    @{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel" data-sortable-id="ui-general-1">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Información general</strong></h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">
                                    <!--  Permiso Crear Documentos Todas Field -->
                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Existe físicamente:</label></div>
                                        <div class="col-md-9">
                                            <select class="form-control" name="existe_fisicamente"
                                                id="existe_fisicamente" v-model="dataForm.existe_fisicamente" required>
                                                <option value="Si">Si</option>
                                                <option value="No">No</option>
                                            </select>
                                            <small>seleccione si el expediente existe físicamente</small>
                                            <div class="invalid-feedback" v-if="dataErrors.existe_fisicamente">
                                                <p class="m-b-0" v-for="error in dataErrors.existe_fisicamente">
                                                    @{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="dataForm.existe_fisicamente == 'Si'">

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Ubicación:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.ubicacion">
                                                <small>Ingresa la ubicación del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Sede:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.sede">
                                                <small>Ingresa la sede del expediente.</small>
                                            </div>
                                        </div>

                                        <!-- Dependencia Field -->
                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Dependencia:</label></div>
                                            <div class="col-md-9">
                                                <select-check css-class="form-control" name-field="dependencias_id" reduce-label="nombre"
                                                    :value="dataForm" :is-required="true" :enable-search="true" :is-multiple="false" name-resource="/intranet/get-dependencies">
                                                </select-check>
                                                <small>Seleccione la dependencia</small>
                                                <div class="invalid-feedback" v-if="dataErrors.dependencia">
                                                    <p class="m-b-0" v-for="error in dataErrors.dependencia">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Area de archivo:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.area_archivo">
                                                <small>Ingresa la Area de archivo del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Estante:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.estante">
                                                <small>Ingresa la Estante del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Módulo:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.modulo">
                                                <small>Ingresa la Módulo del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="required">Entrepaño:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.entrepano">
                                                <small>Ingresa la Entrepaño del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Caja:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.caja">
                                                <small>Ingresa la Caja del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Cuerpo:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.cuerpo">
                                                <small>Ingresa la Cuerpo del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Unidad de conservación:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.unidad_conservacion">
                                                <small>Ingresa la Unidad de conservación del expediente.</small>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Fecha de archivo:</label></div>
                                            <div class="col-md-9">
                                                <input type="date" name="" id="" v-model="dataForm.fecha_archivo">
                                                <small>Ingrese la fecha del archivo</small>
                                                <div class="invalid-feedback" v-if="dataErrors.fecha_archivo">
                                                    <p class="m-b-0" v-for="error in dataErrors.fecha_archivo">@{{ error }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row m-b-15">
                                            <div class="col-form-label col-md-3"><label class="required">Número de inventario:</label></div>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="dataForm.numero_inventario">
                                                <small>Ingresa la Unidad de conservación del expediente.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Funcionarios internos y externos autorizados para ver información y documentos del expediente -->
                            <div class="panel" data-sortable-id="ui-general-1" v-if="dataForm.id_responsable == userId">
                                <!-- begin panel-heading -->
                                <div class="panel-heading ui-sortable-handle">
                                    <h4 class="panel-title"><strong>Funcionarios internos y externos autorizados para ver información y documentos del expediente</strong></h4>
                                </div>
                                <!-- end panel-heading -->
                                <!-- begin panel-body -->
                                <div class="panel-body">

                                    <!--  permiso_general_expediente Field -->
                                    <div class="form-group row m-b-15">
                                        <div class="col-form-label col-md-3"><label class="">Permisos generales sobre el expediente y sus documentos: </label></div>
                                        <div class="col-md-9">
                                            <select class="form-control" name="permiso_general_expediente"
                                                id="permiso_general_expediente" v-model="dataForm.permiso_general_expediente">
                                                <option value="Todas las dependencias pueden incluir y editar documentos en el expediente">Todas las dependencias pueden incluir y editar documentos en el expediente</option>
                                                <option value="Todas las dependencias están autorizadas para ver información y documentos del expediente">Todas las dependencias están autorizadas para ver información y documentos del expediente</option>
                                            </select>
                                            <small>Seleccione el permiso general de las dependencias sobre el expediente</small>
                                            <div class="invalid-feedback" v-if="dataErrors.permiso_general_expediente">
                                                <p class="m-b-0" v-for="error in dataErrors.permiso_general_expediente">
                                                    @{{ error }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <!--  Permiso Usuarios Expedientes Field -->
                                    <div class="form-group row m-b-15">
                                        <h6 class="col-form-label">Defina los usuarios internos y/o externos con permiso de usar, ver o gestionar los documentos del expediente (Opcional)</h6>
                                        <dynamic-list label-button-add="Agregar usuario/dependencia"
                                            :data-list.sync="dataForm.ee_permiso_usuarios_expedientes"
                                            :niveles-dataform="3"
                                            :data-list-options="[
                                                { label: 'Tipo', name: 'tipo_usuario', isShow: true }, 
                                                { label: 'Nombre', name: 'nombre', isShow: true }, 
                                                { label: 'Correo', name: 'correo', isShow: true }, 
                                                { label: 'Permiso', name: 'permiso', isShow: true }, 
                                                { label: '¿Tiene permiso de descargar los documentos del expediente?', name: 'limitar_descarga_documentos', isShow: true, isEditable: true, inputType: 'checkbox' }
                                            ]"
                                            class-container="col-md-12" class-table="table table-bordered" campo-validar-existencia="correo">
                                            <template #fields="scope">

                                                <!-- tipo_usuario Field -->
                                                <div class="form-group row m-b-15">
                                                    <!-- Campo tipo_usuario -->
                                                    <div class="col-form-label col-md-3 required"><label class="">Tipo de usuario: </label></div>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="tipo_usuario" id="tipo_usuario" v-model="scope.dataForm.tipo_usuario" @change="$set(scope.dataForm, 'dependencia_usuario_id', ''); $set(scope.dataForm, 'nombre', ''); $set(scope.dataForm, 'recipient_datos', ''); $set(scope.dataForm, 'correo', ''); $set(scope.dataForm, 'permiso', '');" required>
                                                            <option value="Interno">Interno</option>
                                                            <option value="Externo">Externo</option>
                                                        </select>
                                                        <small>Seleccione el tipo de usuario.</small>
                                                    </div>
                                                </div>

                                                <div class="form-group row m-b-15" v-if="scope.dataForm.tipo_usuario == 'Interno'">
                                                    <div class="col-form-label col-md-3 required"><label class="">Nombre del funcionario o dependencia: </label></div>
                                                    <div class="col-md-9">
                                                        <autocomplete
                                                            :value-default="scope.dataForm.id"
                                                            name-prop="nombre"
                                                            name-field="dependencia_usuario_id"
                                                            :value='scope.dataForm'
                                                            name-resource="/expedientes-electronicos/get-usuarios-autorizados"
                                                            css-class="form-control"
                                                            :name-labels-display="['tipo','nombre']"
                                                            :fields-change-values="['nombre:nombre','tipo:tipo','correo:correo']"
                                                            reduce-key="id"
                                                            :is-required="true"
                                                            name-field-object="recipient_datos"
                                                            ref="dependencia_consulta_ref"
                                                            name-field-edit="nombre"
                                                            :ids-to-empty="['dependencia_informacion']"
                                                            :activar-blur="true"
                                                            >
                                                        </autocomplete>
                                                        <small>Ingrese y seleccione el nombre del usuario o dependencia para añadirlo.</small>
                                                    </div>
                                                </div>
                                                <span v-if="scope.dataForm.tipo_usuario == 'Externo'">
                                                    <div class="form-group row m-b-15">
                                                        <div class="col-form-label col-md-3 required"><label class="">Nombre del usuario: </label></div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" v-model="scope.dataForm.nombre" required>
                                                            <small>Ingresa el nombre del usuario.</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row m-b-15">
                                                        <div class="col-form-label col-md-3 required"><label class="">Correo del usuario: </label></div>
                                                        <div class="col-md-9">
                                                            <input type="text" class="form-control" v-model="scope.dataForm.correo" required>
                                                            <small>Ingresa el correo del usuario.</small>
                                                        </div>
                                                    </div>
                                                </span>

                                                <div class="form-group row m-b-15">
                                                    <div class="col-form-label col-md-3 required"><label class="">Permisos del usuario/dependencia: </label></div>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="permiso" id="permiso" v-model="scope.dataForm.permiso" required>
                                                            <option value="Incluir información y editar documentos">Incluir información y editar documentos</option>
                                                            <option value="Incluir información y editar documentos (solo del usuario)">Incluir información y editar documentos (solo del usuario)</option>
                                                            <option value="Consultar el expediente y sus documentos">Consultar el expediente y sus documentos</option>
                                                        </select>
                                                        <small>Seleccione el permiso que tendrá el usuario/dependencia.</small>
                                                    </div>
                                                </div>
                                            </template>
                                        </dynamic-list>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel" data-sortable-id="ui-general-1"
                            v-if="dataForm.metadatos_tipo_documental?.criterios_busqueda.length > 0">

                            <!-- begin panel-heading -->
                            <div class="panel-heading ui-sortable-handle">
                                <h4 class="panel-title"><strong>Metadatos</strong></h4>
                            </div>
                            <!-- end panel-heading -->

                            <!-- begin panel-body -->
                            <div class="panel-body">
                                <div class="form-group row m-b-15" v-for="metadato in dataForm.metadatos_tipo_documental?.criterios_busqueda" :key="metadato.id">
                                    <label for="nombre_metadato" class="col-form-label col-md-3" :class="{'required': metadato.requerido}">{{ metadato.nombre }}:</label>
                                    <div class="col-md-9">

                                        <input
                                            v-if="metadato && metadato.tipo_campo !== 'Lista'"
                                            :type="metadato.tipo_campo === 'Texto' ? 'text' : (metadato.tipo_campo === 'Número' ? 'number' : 'date')"
                                            v-model="dataForm.metadatos[metadato.id]"
                                            :name="metadato.id || ''"
                                            :id="metadato.id || ''"
                                            class="form-control"
                                            :required="metadato.requerido">

                                        <select
                                            v-else-if="metadato"
                                            v-model="dataForm.metadatos[metadato.id]"
                                            :name="metadato.id || ''"
                                            :id="metadato.id || ''"
                                            class="form-control"
                                            :required="metadato.requerido">
                                            <option v-for="(value, key) in $parent.parseOpciones(metadato.opciones)" :value="key" :key="key">{{ value }}</option>
                                        </select>


                                        <!-- Texto de ayuda -->
                                        <small v-if="metadato">{{ metadato.texto_ayuda }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="validarYAsociar" v-if="dataForm.que_desea_hacer" class="btn btn-primary">
                        <i class="fa fa-save mr-2"></i>
                        <span v-if="dataForm.que_desea_hacer == '1'">Asociar documento</span>
                        <span v-else-if="dataForm.que_desea_hacer == '2'">Crear expediente y asociar documento</span>
                    </button>
                    <button type="button" class="btn btn-white" @click="cerrarModal"><i class="fa fa-times mr-2"></i>Cerrar</button>
                </div>
            </b-form>
        </b-modal>
    </div>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from "vue-property-decorator";
    import axios from "axios";
    import { jwtDecode } from 'jwt-decode';
    import { Locale } from "v-calendar";

    const locale = new Locale();

    @Component
    export default class ExpedientesGeneralComponent extends Vue {

        public mostrarModal = false;
        public valorRegistro: any;
        public valorExpediente: any;
        public dataForm: any;
        public consecutivoQuery: string = ''; // Campo para el consecutivo
        public ordenQuery: string = ''; // Campo para la orden
        public resultados: any[] = [];
        public camposFiltros: any = {};
        public crudComponent: Vue;
        public isUpdate: boolean;
        public querySelect: string = '';

        /**
         * Errores del formulario
         */
        public dataErrors: any;

        @Prop({ type: String, default: 'consecutivo' }) public moduloConsecutivo: string;

        @Prop({ type: String }) public campoConsecutivo: string;

        @Prop({ type: String }) public modulo: string;

        @Prop({ type: Boolean, default: false  }) public puedeCrearExpedientes: Boolean;

        @Prop({ type: Number, required: true}) public userId: Number;

        /**
         * Constructor de la clase
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        constructor() {
            super();
            this.valorRegistro = {};
            this.dataForm = {ee_permiso_usuarios_expedientes : [], metadatos: {}};
            this.valorExpediente = {};
            this.crudComponent = this.$parent as Vue;
            this.dataErrors = {};
        }

        mounted() {

        }

        /**
         * Abre el modal al momento de accionar el boton
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public abrirModal(row: any): void {
            // Abre y muestra el modal
            this.mostrarModal = true;
            this.valorRegistro = row;
            this.dataForm = {ee_permiso_usuarios_expedientes : [], metadatos: {}};
            // this.correspondenciaRelacionada(row[this.campoConsecutivo]);
        }

        /**
         * Cierra el modal al momento de darle clic
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
         public async correspondenciaRelacionada(campo): Promise<boolean> {
            this.showLoadingGif("Verificando información");
            const queryExpeEncript = btoa(campo);
            const id_expediente = btoa(this.dataForm["expediente_datos"]["id"]);

            try {
                const res = await axios.get(`/expedientes-electronicos/get-correspondencia-relacionada/${queryExpeEncript}/${id_expediente}`);
                let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                const dataDecrypted = Object.assign({}, dataPayload);

                if (dataDecrypted["data"].length > 0) {
                    this.$swal.close();
                    this.$set(this.dataForm, "consecutivo_expediente", dataDecrypted["data"][0]["ee_expediente"]["consecutivo"]);
                    const encryptedIdExpediente = btoa(dataDecrypted["data"][0]["ee_expediente_id"]);
                    this.$set(this.dataForm, "id_expediente_info", encryptedIdExpediente);
                    return true;
                } else {
                    this.$swal.close();
                    this.$set(this.dataForm, "consecutivo_expediente", null);
                    this.$set(this.dataForm, "id_expediente_info", null);
                    return false;
                }
            } catch (err) {
                console.error('Error al realizar la consulta de la correspondencia relacionada:', err);
                return false;
            }
        }

        /**
         * Cierra el modal al momento de darle clic
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public cerrarModal(): void {
            // Cerrar el modal
            this.mostrarModal = false;
            this.$set(this, 'dataForm', {});
            this.$set(this, 'resultados', []);
        }

        /**
         * Asocia el documento al expediente electronico
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public async asociarDocumento(): Promise<void> {

            // Mostrar swal de confirmación
            this.$swal({
                title: "¿Desea asociar el documento?",
                text: "Esta acción asociará el documento al expediente seleccionado.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Asociar",
                cancelButtonText: "Cancelar",
            }).then(async (result) => {
                if(result.value) {
                    let validarDocumentoAsociado = await this.correspondenciaRelacionada(this.valorRegistro[this.campoConsecutivo]);

                    if(validarDocumentoAsociado) {
                        this.$swal({
                            html: "Este registro ya está asociado a este expediente <a href='/expedientes-electronicos/documentos-expedientes?c="+this.dataForm.id_expediente_info+"' target='_blank'>"+this.dataForm.consecutivo_expediente+"</a>",
                            text: "si desea, puede seleccionar otro expediente.",
                            icon: "info",
                            confirmButtonText: "Entendido",
                        }).then(async (result) => {
                            const contenedor = document.querySelector('div[name-prop="consecutivo"]');
                            const inputSelectExpedientes = contenedor.querySelector(".vs__search"); // Input interno de v-select, listado de expedientes
                            // Enfoca el listado de expedientes después de 300ms
                            setTimeout(() => {
                                inputSelectExpedientes["focus"]();
                            }, 300);
                        });
                    } else {
                        if (typeof this.dataForm[this.moduloConsecutivo] !== 'undefined' && this.dataForm[this.moduloConsecutivo] !== null && this.dataForm[this.moduloConsecutivo].trim() !== '') {
                            if (result.isConfirmed) {
                                try {
                                    // Mostrar el indicador de carga
                                    this.showLoadingGif("Asociando documento");
                                    // Convierte el id del expediente en una cadena
                                    const idExpediente = this.dataForm.expediente_datos.id.toString();
                                    // Lo encripta para mandarlo como parametro
                                    const idExpeEncript = btoa(idExpediente);
                                    // Crear el formulario de datos

                                    const formData: FormData = this.makeFormData();
                                    let adjunto = this.valorRegistro.document_pdf || "";
                                    if(!this.valorRegistro.document_pdf && this.modulo == "PQRSD" && this.valorRegistro.pqr_correspondence?.document_pdf) {
                                        adjunto = this.valorRegistro.pqr_correspondence.document_pdf;
                                    } else if(!this.valorRegistro.document_pdf && this.modulo == "Correspondencia recibida" && this.valorRegistro.correo_integrado_datos?.adjuntos_correo_cadena) {
                                        adjunto = this.valorRegistro.correo_integrado_datos.adjuntos_correo_cadena;
                                    }
                                    
                                    formData.append(this.moduloConsecutivo, this.dataForm[this.moduloConsecutivo]);
                                    formData.append("adjunto", adjunto);
                                    formData.append("modulo_consecutivo", this.valorRegistro[this.campoConsecutivo]);
                                    formData.append("modulo_intraweb", this.modulo);
                                    formData.append("ee_tipos_documentales_id", this.dataForm.ee_tipos_documentales_id);
                                    formData.append("pagina_inicio", this.dataForm.pagina_inicio);
                                    formData.append("pagina_fin", this.dataForm.pagina_fin);

                                    // Realizar la petición para guardar o actualizar el documento
                                    const res = await axios.post(`/expedientes-electronicos/asociar-expediente/${idExpeEncript}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
                                    if(res.data === 'existe'){
                                        this.$swal({
                                        title: "¡Documento ya existe!",
                                        text: "Este documento ya se encuentra asociado a este expediente.",
                                        icon: "info",
                                        confirmButtonText: "Entendido",
                                        });
                                    } else if(res.data.type_message === 'info') { // Si se cumple la condición, hubo un error al guardar la información
                                        // Abre el swal informando que no se guardaron los datos
                                        this.$swal({
                                            icon: res.data.type_message,
                                            html: res.data.message,
                                            allowOutsideClick: false,
                                            confirmButtonText: "Entendido"
                                        });
                                    } else {
                                        // Cerrar el indicador de carga
                                        this.$swal.close();
                                        // Cierra fomrulario modal
                                        this.cerrarModal();
                                        // Mostrar notificación de éxito
                                        this._pushNotification("Guardado con éxito");
                                    }
                                } catch (error) {
                                    // Manejar errores de la petición
                                    if (error.response && error.response.data && error.response.data.errors) {
                                        console.log(error.response.data.errors);
                                    } else {
                                        console.log(error);
                                    }
                                }
                            }
                        } else {
                            this.$swal({
                                title: "¡Ingrese consecutivo!",
                                text: "Por favor, ingrese un consecutivo que sea válido.",
                                icon: "info",
                                confirmButtonText: "Entendido",
                            }).then(async (result) => {
                                const contenedor = document.querySelector('div[name-prop="consecutivo"]');
                                const inputSelectExpedientes = contenedor.querySelector(".vs__search"); // Input interno de v-select, listado de expedientes
                                // Enfoca el listado de expedientes después de 300ms
                                setTimeout(() => {
                                    inputSelectExpedientes["focus"]();
                                }, 300);
                            });
                        }
                    }
                }
            });
        }

        /**
         * Busca el expediente dependiendo de los filtros
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public async buscarExpediente(): Promise<void> {
            this.showLoadingGif("Buscando expediente");
            let query = '';
            Object.keys(this.camposFiltros).forEach((campo, index) => {
                if (this.camposFiltros[campo]) {
                    if (index > 0) {
                        query += ' AND ';
                    }
                    query += `(${campo} like '%${this.camposFiltros[campo]}%')`;
                }
            });
            // Lo encripta para mandarlo como parametro
            const queryExpeEncript = btoa(query);
            this.querySelect = queryExpeEncript;

            setTimeout(() => {
                // Cerrar el indicador de carga
                this.$swal.close();
            }, 3000);


        }

        /**
         * Funcion del gif que se muestra de cargando
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        private showLoadingGif(message: string): void {
            // Mostrar un indicador de carga
            this.$swal({
                html: '<img src="/assets/img/loadingintraweb.gif" alt="Cargando..." style="width: 100px;"><br><span>' + message + '.</span>',
                showCancelButton: false,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }

        /**
         *
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        private _pushNotification(message: string, isPositive: boolean = true, title: string = '¡Éxito!'): void {
            // Mostrar una notificación
            const toastOptions = { closeButton: true, closeMethod: 'fadeOut', timeOut: 3000, tapToDismiss: false };
            if (isPositive) {
                toastr.success(message, title, toastOptions);
            } else {
                toastOptions.timeOut = 0;
                toastr.error(message, title, toastOptions);
            }
        }

        /**
         * Agrega datos a donde se necesita
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public limpiarFiltros(): void {
            this.$set(this, 'resultados', []);
            this.$set(this, 'camposFiltros', {});
            this.$set(this, 'querySelect', '');
        }

        /**
         * Inicializa campos por defecto del formulario de tipo de documento
         */
        public inicializarValoresTipoExpediente() {
            /*
            * Se inicializan los campos formato_consecutivo_value para ayudar al usuario a
            * escoger un formato por defecto para el consecutivo del documento
            */
            this.$set(this.crudComponent["dataForm"], "formato_consecutivo_value", ["vigencia_actual", "prefijo_documento", "consecutivo_documento"]);
        }

        // Método para encriptar el ID y generar la URL
        getEncryptedResourceUrl(): string {
            const encryptedId = btoa(this.dataForm.expediente_datos.id.toString());
            return `/expedientes-electronicos/get-tipos-documentales/${encryptedId}`;
        }

        /**
         * Crea el expediente electronico y asocia el documento al expedinete
         *
         * @author Manuel Marin.  2024
         * @version 1.0.0
         */
        public async crearExpedienteAsociar(): Promise<void> {
            // Mostrar swal de confirmación
            this.$swal({
                title: "¿Desea crear el expediente y asociar el documento?",
                text: "Esta acción creará, firmará y asociará el documento al expediente creado.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Crear y asociar",
                cancelButtonText: "Cancelar",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Mostrar el indicador de carga
                        this.showLoadingGif("Creando y asociando documento");
                        this.$set(this.dataForm, "adjunto",this.valorRegistro.document_pdf);
                        this.$set(this.dataForm, "modulo_intraweb",this.modulo);
                        this.$set(this.dataForm, "modulo_consecutivo",this.valorRegistro[this.campoConsecutivo]);

                        const formData: FormData = this.makeFormData()
                        // Realizar la petición para guardar o actualizar el documento
                        const res = await axios.post(`/expedientes-electronicos/asociar-expediente-asociar`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
                        if(res.data === 'existe'){
                            this.$swal({
                                title: "¡Documento ya existe!",
                                text: "Este documento ya se encuentra asociado a este expediente.",
                                icon: "info",
                                confirmButtonText: "Entendido",
                            });
                        } else if(res.data.type_message === 'info') { // Si se cumple la condición, hubo un error al guardar la información
                            // Abre el swal informando que no se guardaron los datos
                            this.$swal({
                                icon: res.data.type_message,
                                html: res.data.message,
                                allowOutsideClick: false,
                                confirmButtonText: "Entendido"
                            });
                        } else {
                            // Cerrar el indicador de carga
                            this.$swal.close();
                            // Cierra fomrulario modal
                            this.cerrarModal();
                            // Mostrar notificación de éxito
                            this._pushNotification("Guardado con éxito");
                        }
                    } catch (error) {
                        // Manejar errores de la petición
                        if (error.response && error.response.data && error.response.data.errors) {
                            console.log(error.response.data.errors);
                        }
                    }
                }
            });

        }

        created() {
            // Obtiene la instancia del crudComponent
            let crudComponent = (this.$parent as Vue);

            // recuperamos el querystring (parámetros enviados por URL)
            const querystring = window.location.search
            // usando el querystring, creamos un objeto del tipo URLSearchParams
            const params = new URLSearchParams(querystring)
            if(params.get('qderExpediente')) {
                axios.get('/expedientes-electronicos/get-informacion-expediente/'+params.get('qderExpediente'))
                    .then((res) => {
                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                        const dataDecrypted = Object.assign({}, dataPayload);
                            // Envia elemento a mostrar a la función show (Ver Detalles)
                            crudComponent["show"](dataDecrypted["data"]);
                            $(`#modal-view-${crudComponent["name"]}`).modal('toggle');
                    })
                    .catch((err) => {
                        console.log("Error obteniendo información del registro desde el listado el dashboard")
                    });
            }
        }

        /**
         * Asignar leído al expediente
         *
         * @author Seven Soluciones Informáticas S.A.S. Abr 3 - 2024
         * @version 1.0.0
         *
         * @param id ID del documento electrónico
         */
        public registrar_leido(id: number, rol_consulta_expedientes: string): void {
            // Envia petición para registrar el leído
            axios
            .post("expediente-leido/"+id+"/"+rol_consulta_expedientes)
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
                // console.log('Error al obtener la lista.');
                this.crudComponent["_pushNotification"](
                    "Error al registrar el leído expediente electrónico",
                    false,
                    "Error"
                );
            });
        }

        // Método que es invocado para asociar un documento a un expediente
        validarYAsociar() {
            // Verifica si el formulario referenciado por "form_vincular_documento" es válido
            if (this.$refs.form_vincular_documento["checkValidity"]()) {
                // Si el usuario seleccionó la opción '1', ejecuta la función asociarDocumento()
                if(this.dataForm.que_desea_hacer == '1') {
                    this.asociarDocumento();
                } else {
                    // Si no seleccionó '1', ejecuta la función crearExpedienteAsociar()
                    this.crearExpedienteAsociar();
                }
            } else {
                // Si el formulario no es válido, fuerza la visualización de los mensajes de error nativos del navegador
                this.$refs.form_vincular_documento["reportValidity"]();
            }
        }

        // Método para validar el nombre del canal del documento a incluir en el expediente (Esto solo para correspondencia recibida)
        verificarCanal() {
            // Si el canal es 'Correo electrónico', se cumple la condición
            if(this.valorRegistro.channel_name == 'Correo electrónico') {
                // Asigna por defecto los valores de 1 a la página de inicio y fin
                this.$set(this.dataForm, "pagina_inicio", 1);
                this.$set(this.dataForm, "pagina_fin", 1);
            }
        }

        /**
         * Abre el modal de confirmacion para el firmar y cerrar el expediente por el responsable
         *
         * @author Seven Soluciones Informáticas. - Mar. 7 - 2025
         * @version 1.0.0
         */
        public firmarCerrarExpediente(element): void {
            this.$swal({
                title: "¿Está seguro(a) de firmar y cerrar el expediente?",
                text: "Una vez firmado, el expediente se cerrará automáticamente. No podrá volver a abrirlo ni agregar más documentos.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.value) {
                    this.dataForm = element;
                    // Construye los datos del formulario
                    const formData: FormData = this.makeFormData();
                    // Se asigna al método http el valor PUT
                    formData.append('_method', 'put');
                    formData.append('type_send', 'Aprobar Firma');

                    // Envia peticion de guardado de datos
                    axios.post('aprobar-firmar-expedientes', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((res) => {

                        let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;

                        const dataDecrypted = Object.assign({data:{id:null}}, dataPayload);

                        // Actualiza elemento modificado en la lista
                        Object.assign(
                            (this.$parent as any)._findElementById(dataDecrypted?.data.id, false),
                            dataDecrypted["data"]
                        );
                        // Mostrar notificación de éxito
                        this._pushNotification("Expediente firmado y cerrado");
                    })
                    .catch((err) => {
                        console.log('Error al enviar el formualario dinamico', err);
                        (this.$swal as any).close();
                        // Emite notificacion de almacenamiento de datos
                        this._pushNotification('Error', err.response.data.message);
                        // Valida si hay errores asociados al formulario
                        if (err.response.data.errors) {
                            // this.dataErrors = err.response.data.errors;
                        }
                    });
                }
            });
        }

        /**
         * Crea el formulario de datos para guardar
         *
         * @author Jhoan Sebastian Chilito S. - Jun. 26 - 2020
         * @version 1.0.0
         */
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

        public getDataWidgets(): void {
            this.crudComponent["isTableroVisible"] = !this.crudComponent["isTableroVisible"];

            // Si se muestra, ejecutar una función para llenar los datos
            if (this.crudComponent["isTableroVisible"]) {

                this.showLoadingGif('Cargando Contadores');

                const resource = this.crudComponent["resource"].get? this.crudComponent["resource"].get: this.crudComponent["resource"].default;

                // Envia peticion de obtener todos los datos del recurso
                axios.get(`${resource}/`, {
                    params: {
                        tablero: 'valores', // Aquí estamos enviando el parámetro 'tablero'
                        rol_consulta_expedientes: this.crudComponent["searchFields"]["rol_consulta_expedientes"] // Aquí estamos enviando el rol usado en la vista principal
                    }
                })
                .then((res) => {

                    (this.$swal as any).close()

                    let dataPayload = res.data.data ? jwtDecode(res.data.data) : null;
                    const dataDecrypted = Object.assign({data:[{data:{}}]}, dataPayload);

                    // Llena la lista de datos
                    this.crudComponent["dataWidgets"] = res.data.data_extra;

                })
                .catch((err) => {
                    (this.$swal as any).close()

                });
            }
        }

        /**
         * Asignar leido a las anotaciones del expediente
         *
         * @author Seven Soluciones Informáticas. Ago 04 - 2025
         * @version 1.0.0
         *
         * @param ee_expediente_id ID del expediente
         */
        public asignarLeidoAnotacionExpediente(ee_expediente_id: number): void {
            // Envia peticion de leido a las anotaciones del expediente
            axios.post('leido-anotacion-expediente/'+ee_expediente_id)
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

                    const element = (this.$parent as any)._findElementById(ee_expediente_id, false);
                    if (element) {
                        Object.assign(element, dataDecrypted["data"]);
                    }
                    //  Visualiza toast positivo
                })
                .catch((err) => {
                    (this.$parent as any)._pushNotification('Error al asignar el leído a la anotación del expediente', false, 'Error');
                });
        }

        /**
         * Asignar leido a las anotaciones de un documento de un expediente
         *
         * @author Seven Soluciones Informáticas. Ago 04 - 2025
         * @version 1.0.0
         *
         * @param ee_documento_expediente_id ID del documento del expediente
         */
        public asignarLeidoAnotacionDocumentoExpediente(ee_documento_expediente_id: number): void {
            // Envia peticion de leido a las anotaciones del expediente
            axios.post('leido-anotacion-documento-expediente/'+ee_documento_expediente_id)
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

                    const element = (this.$parent as any)._findElementById(ee_documento_expediente_id, false);
                    if (element) {
                        Object.assign(element, dataDecrypted["data"]);
                    }
                    //  Visualiza toast positivo
                })
                .catch((err) => {
                    (this.$parent as any)._pushNotification('Error al asignar el leído a la anotación del documento del expediente', false, 'Error');
                });
        }

        /**
         * Exporta los documentos de un expediente
         *
         * @author Seven Soluciones Informáticas S.A.S. - Ago. 06 - 2025
         * @version 1.0.0
         *
         * @param int $expediente_id id del expediente
         */
        public descargarDocumentos(expediente_id: number, consecutivo: string): void {
            this.showLoadingGif("Descargando expediente");
            axios.post(`descargar-documentos-expediente/${expediente_id}`, {}, {
                responseType: 'blob', // indica el retorno de un archivo binario
            })
            .then((res) => {
                // Cierra el swal
                (this.$swal as any).close();

                // Descargar el archivo ZIP desde el blob
                this.downloadFile(res.data, "expediente_"+consecutivo, "zip");
            })
            .catch((err) => {
                // Cierra el swal
                (this.$swal as any).close();

                this._pushNotification('Error al exportar los documentos', false, 'Error');
                console.error('Error de descarga:', err);
            });
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
    }
</script>
<style>
    .info-box {
        background-color: #e6fffa; /* Verde claro para indicar éxito */
        border: 1px solid #21ba46; /* Verde oscuro para el borde */
        padding: 10px;
        border-radius: 5px;
        text-align: center;
    }
</style>
