<div class="container-fluid bg-light min-vh-100 py-4">
    <div class="row">
        <div class="col-12 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Información general</strong></h5>
                    <div class="row">
                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong><i class="fas fa-file-alt"></i>
                                    @lang('Nombre')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.nombre }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Prefijo')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.prefijo }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong></i> @lang('Versión')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.version }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong><i class="fas fa-barcode"></i>
                                    @lang('Código del formato')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.codigo_formato }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong><i class="fas fa-list-ol"></i>
                                    @lang('Formato del consecutivo')</strong></label><br>
                            <dd class="mb-0">@{{ dataShow.formato_consecutivo }}</dd>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Prefijo para incrementar el consecutivo')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.prefijo_incrementan_consecutivo }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong>@lang('Separador del consecutivo')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.separador_consecutivo }}</span>
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label class="mb-0"><strong><i class="fas fa-toggle-on"></i>
                                    @lang('Estado')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.estado }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Datos de la plantilla</strong></h5>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>@lang('Variables de la plantilla')</strong></label><br>
                            <div class="d-flex flex-wrap" v-if="dataShow.variables_plantilla?.length > 0">
                                <p class="mr-2 mb-0" v-for="variable in dataShow.variables_plantilla?.split(', ')">
                                    @{{ variable }}
                                </p>
                            </div>
                            <p class="mb-0" v-else>No tiene variables</p>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>@lang('¿Las variables en la plantilla son requeridas?')</strong></label><br>
                            <span class="mb-0">@{{ dataShow.variables_plantilla_requeridas ? 'Si' : 'No' }}.</span>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>@lang('Plantilla')</strong></label><br>
                            <viewer-attachement :display-flex="true" :link-file-name="true"
                                class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.plantilla" :list="dataShow.plantilla"
                                :key="dataShow.plantilla">
                            </viewer-attachement>
                            <span v-else>No tiene.</span>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="mb-0"><strong>@lang('Imagen previa')</strong></label><br>
                            <viewer-attachement :display-flex="true" :link-file-name="true"
                                class="col-sm-9 col-md-9 col-lg-9" v-if="dataShow.preview_document"
                                :list="dataShow.preview_document" :key="dataShow.preview_document">
                            </viewer-attachement>
                            <span v-else>No tiene.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Permisos para usar este tipo de documento</strong></h5>
                    <div class="mb-2">
                        <label><strong>@lang('¿Todas las dependencias pueden crear documentos de este tipo?')</strong></label><br>
                        <span class="mb-0">@{{ dataShow.permiso_crear_documentos_todas ? 'Si' : 'No' }}.</span>
                    </div>
                    <div v-if="!dataShow.permiso_crear_documentos_todas">
                        <!-- Dependencias Field -->
                        <label><strong>@lang('Dependencias con permiso de usar este tipo de documento')</strong></label><br>
                        <span v-if="dataShow.de_permiso_crear_documentos?.length > 0 ">
                            <ul>
                                <li v-for="dependencia in dataShow.de_permiso_crear_documentos">
                                    @{{ dependencia.nombre }}
                                </li>
                            </ul>
                        </span>
                        <span v-else>Ninguna</span>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3" v-if="dataShow.de_metadatos?.length > 0">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Metadatos</strong></h5>
                    <table class="table text-center mt-2" border="1">
                        <thead>
                            <tr class="font-weight-bold">
                                <td>Nombre del campo</td>
                                <td>Tipo</td>
                                <td>Texto de ayuda</td>
                                <td>Opciones del listado</td>
                                <td>¿Es requerido?</td>
                                <td>Variable en el documento</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="metadato in dataShow.de_metadatos">
                                <td>@{{ metadato.nombre_metadato }}</td>
                                <td>@{{ metadato.tipo }}</td>
                                <td>@{{ metadato.texto_ayuda }}</td>
                                <td>@{{ metadato.opciones_listado ?? 'N/A' }}</td>
                                <td>@{{ metadato.requerido ? 'Si' : 'No' }}</td>
                                <td>@{{ metadato.variable_documento }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3"><strong>Actividades del tipo de documento</strong></h5>
                    <div class="mb-3">
                        <!-- Sub Estados Field -->
                        <dt>@lang('Actividades'):</dt>
                        <dd v-if="dataShow.sub_estados?.split(', ').length > 0 ">
                            <ul>
                                <li v-for="subestado in dataShow.sub_estados?.split(', ')">
                                    @{{ subestado }}
                                </li>
                            </ul>
                        </dd>
                        <span v-else>No tiene actividades asignadas.</span>
                    </div>
            
                    <div>
                        <!-- Sub Estados Requerido Field -->
                        <dt>@lang('¿La elección de la actividad es requerida?'):</dt>
                        <dd>@{{ dataShow.sub_estados_requerido ? 'Si' : 'No' }}.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
