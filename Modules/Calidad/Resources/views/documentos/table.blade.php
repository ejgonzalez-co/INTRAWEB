<div class="table-responsive">
    <table-component
        id="documentos-table"
        :data="dataList"
        sort-by="documentos"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="consecutivo" label="@lang('Código')"></table-column>

        <table-column show="calidad_tipo_sistema_id" label="@lang('Tipo de sistema')">
            <template slot-scope="row">
                @{{ row.tipo_sistema?.nombre_sistema }}
            </template>
        </table-column>

        <table-column show="titulo" label="@lang('Título')"></table-column>

        <table-column show="tipo_documento" label="@lang('Tipo de documento')"></table-column>

        <table-column show="version" label="@lang('Versión')"></table-column>

        <table-column show="proceso" label="@lang('Proceso')"></table-column>

        <table-column show="estado" label="@lang('Estado')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='Revisión'"></div>
                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.estado" v-if="row.estado=='Aprobación'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Público'"></div>
            </template>
        </table-column>
        <table-column show="document_pdf" label="@lang('Documento')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.document_pdf && row.estado == 'Público'">
                    <template v-if="row.formato_archivo == 'bizagi'">
                        <ol class="list-group">
                            <li class="list-group-item">
                                <a href="javascript:void(0)" @click="callFunctionComponent('viewerDocuments', 'mostrarAdjunto', row.document_pdf)">
                                    <img src="/assets/img/bizagi_icon.png" width="18" height="18" style="vertical-align: bottom;">
                                    @{{ row.consecutivo }}
                                </a>
                            </li>
                        </ol>
                    </template>
                    <template v-else>
                        <viewer-attachement type="only-link" :ref="row.consecutivo" :component-reference="row.consecutivo" :list="row.document_pdf" :key="row.document_pdf" :name="row.consecutivo"></viewer-attachement>
                    </template>
                </div>

                <div v-else>
                    <span>En elaboración</span>
                </div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.permission_edit" @click="callFunctionComponent('documento_calidad_ref', 'loadDocumento', row)" data-backdrop="static" data-target="#modal-form-documentos-calidad" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row); $refs.documento_calidad_ref.leido(row[customId]);" data-target="#modal-view-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="row.estado == 'Público' && !row.documento_id_procedente" @click="$refs.documento_calidad_ref.generarNuevaVersion(row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Generar una nueva versión')">
                    <i class="fa fa-plus-circle"></i>
                </button>
                <!-- Botón para anotaciones pendientes -->
                {{-- <a :href="'{!! url('documentos-electronicos/documento-anotacions') !!}?de=' + row.id_encode" --}}
                {{-- <a
                class="btn btn-white btn-icon btn-md position-relative"
                data-toggle="tooltip"
                data-placement="top"
                title="Anotaciones Pendiente"
                :key="row.anotaciones_pendientes?.length"
                @click="$refs.anotacionesDocumentos.abrirModal(row); row.anotaciones_pendientes?.length > 0 ? $refs.documento_calidad_ref.leerAnotaciones(row[customId]) : null"
                v-if="row.estado == 'Público'"> <!-- Maneja el evento click -->
                    <i class="fas fa-comment"></i> <!-- Icono -->
                    <span v-if="row.anotaciones_pendientes?.length > 0" class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.anotaciones_pendientes.length }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a> --}}

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
