<div class="table-responsive">
    <table-component
        id="documentos-expedientes-table"
        :data="dataList"
        sort-by="documentos-expedientes"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0"
        >

                    <table-column show="consecutivo" label="Consecutivo"></table-column>

                    <table-column show="fecha_documento" label="Fecha de creación"></table-column>


                    <table-column show="ee_tipos_documentales.name" label="Tipo documental">
                        <template slot-scope="row">
                            <span v-if="row.ee_tipos_documentales?.name">@{{row.ee_tipos_documentales.name}}</span>
                            <span v-else>N/A</span>
                        </template>
                    </table-column>

                    <table-column label="Información del documento">
                        <template slot-scope="row">
                            <div v-if="row.modulo_intraweb == 'Correspondencia interna'">
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.info_documento[0].title ? row.info_documento[0].title : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Destinatario: </strong><span v-html="row.info_documento[0].recipients ? row.info_documento[0].recipients : 'N/A'"></span></div>
                                <div><strong style="color: #2196f3">Consecutivo: </strong>@{{ row.info_documento[0].consecutive ? row.info_documento[0].consecutive : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>@{{ row.modulo_intraweb ? row.modulo_intraweb : 'N/A' }}</div>
                            </div>
                            <div v-else-if="row.modulo_intraweb == 'PQRSD'">
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.info_documento[0].contenido ? row.info_documento[0].contenido : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Ciudadano: </strong>@{{ row.info_documento[0].nombre_ciudadano ? row.info_documento[0].nombre_ciudadano : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Destinatario: </strong>@{{ row.info_documento[0].funcionario_destinatario ? row.info_documento[0].funcionario_destinatario : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Consecutivo: </strong>@{{ row.info_documento[0].pqr_id ? row.info_documento[0].pqr_id : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>@{{ row.modulo_intraweb ? row.modulo_intraweb : 'N/A' }}</div>
                            </div>
                            <div v-else-if="row.modulo_intraweb == 'Correspondencia recibida'">
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.info_documento[0].issue ? row.info_documento[0].issue : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Ciudadano: </strong><span v-html="row.info_documento[0].citizen_name ? row.info_documento[0].citizen_name : 'N/A'"></span></div>
                                <div><strong style="color: #2196f3">Destinatario: </strong>@{{ row.info_documento[0].functionary_name ? row.info_documento[0].functionary_name : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Consecutivo: </strong>@{{ row.info_documento[0].consecutive ? row.info_documento[0].consecutive : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>@{{ row.modulo_intraweb ? row.modulo_intraweb : 'N/A' }}</div>
                            </div>
                            <div v-else-if="row.modulo_intraweb == 'Correspondencia enviada'">
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.info_documento[0].title ? row.info_documento[0].title : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Ciudadano: </strong><span v-html="row.info_documento[0].citizen_name ? row.info_documento[0].citizen_name : 'N/A'"></span></div>
                                <div><strong style="color: #2196f3">Funcionario: </strong>@{{ row.info_documento[0].from ? row.info_documento[0].from : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Consecutivo: </strong>@{{ row.info_documento[0].consecutive ? row.info_documento[0].consecutive : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>@{{ row.modulo_intraweb ? row.modulo_intraweb : 'N/A' }}</div>
                            </div>
                            <div v-else-if="row.modulo_intraweb == 'Documentos electrónicos'">
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.info_documento[0].titulo_asunto ? row.info_documento[0].titulo_asunto : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Compartido con: </strong><span v-html="row.info_documento[0].compartidos ? row.info_documento[0].compartidos : 'N/A'"></span></div>
                                <div><strong style="color: #2196f3">Consecutivo: </strong>@{{ row.info_documento[0].consecutivo ? row.info_documento[0].consecutivo : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>@{{ row.modulo_intraweb ? row.modulo_intraweb : 'N/A' }}</div>
                            </div>
                            <div v-else>
                                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.nombre_documento ? row.nombre_documento : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Creado por: </strong>@{{ row.user_name ? row.user_name : 'N/A' }}</div>
                                <div><strong style="color: #2196f3">Origen del documento: </strong>Expedientes</div>
                            </div>
                        </template>
                    </table-column>

                    <table-column show="orden_documento" label="Orden del documento"></table-column>


                    <table-column show="adjunto" label="Documentos del expediente" cell-class="col-sm-2">
                        <template slot-scope="row" :sortable="false" :filterable="false">
                        <div v-if="row.adjunto != 'Eliminado'">

                            <viewer-attachement v-if="row.adjunto" :link-file-name="true" type="only-link"
                            :ref="row.consecutivo" :component-reference="row.consecutivo"
                            :list="row.adjunto"
                            :key="row.adjunto"
                            :name="row.consecutivo"></viewer-attachement>
                                <a href="#" v-else-if="row.info_documento && row.info_documento[0].channel_name == 'Correo electrónico'" @click="show(row)" data-target="#modal-view-documentos-expedientes" data-toggle="modal" data-toggle="tooltip" data-placement="top">Email</a>
                                <a href="#" v-else @click="show(row)" data-target="#modal-view-documentos-expedientes" data-toggle="modal" data-toggle="tooltip" data-placement="top">@{{ row.modulo_intraweb }}</a>
                        </div>
                            <div v-else>
                                <i class="fa fa-trash"></i>
                                <span>Eliminado</span>
                            </div>
                        </template>
                    </table-column>


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documentos-expedientes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                <button @click="show(row)" data-target="#modal-view-documentos-expedientes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                {{-- @if (Auth::user()->hasRole('Admin Expedientes Electrónicos')) --}}
                    {{-- <button v-if="row.ee_tipos_documentales_id != 1 && row.ee_expediente.estado != 'Cerrado'"
                        @click="callFunctionComponent('alert-confirmation','openConfirmationModal',row.id,row)"
                        class="btn btn-white btn-icon btn-md" title="Eliminar documento">
                        <i class="fa fa-trash"></i>
                    </button> --}}
                {{-- @endif --}}
                
                {{-- Se valida si el usuario tiene sesión para mostrarle la acción de anotaciones --}}
                <span v-if="{{ Auth::check() ? 'true' : 'false' }}">
                    <!-- Botón para anotaciones pendientes -->
                    <a
                        target="_blank"
                        class="btn btn-white btn-icon btn-md position-relative"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones Pendiente"
                        v-if="row.anotaciones_pendientes?.length > 0"
                        :key="row.anotaciones_pendientes?.length"
                        @click="$refs.annotations.abrirModal(row); $refs.expedientes.asignarLeidoAnotacionDocumentoExpediente(row[customId]);"> <!-- Maneja el evento click -->
                        <i class="fas fa-comment"></i> <!-- Icono -->
                        <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                            @{{ row.anotaciones_pendientes.length }}
                        </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                    </a>

                    <!-- Botón para cuando no hay anotaciones pendientes -->
                    <a v-else
                        @click="$refs.annotations.abrirModal(row); $refs.expedientes.asignarLeidoAnotacionDocumentoExpediente(row[customId]);"
                        target="_blank"
                        class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones">
                        <i class="fas fa-comment"></i> <!-- Icono -->
                    </a>
                </span>
            </template>
        </table-column>
    </table-component>
</div>
