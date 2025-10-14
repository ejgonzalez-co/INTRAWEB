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
        :cache-lifetime="0"
        >
        <table-column show="created_at" label="@lang('Created_at')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.created_at }}</span>
            </template>
        </table-column>
        <table-column show="users_name" label="@lang('Creado por')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.users_name }}</span>
            </template>
        </table-column>

        <table-column show="consecutivo" label="@lang('Consecutive')">
        <template slot-scope="row">
            <span :class="{'registro-no-leido': !row.leido}">
                <span v-if="row.estado != 'Público'">(Temporal) </span>@{{ row.consecutivo }}
            </span>
        </template>
        </table-column>

        <table-column show="titulo_asunto" label="@lang('Título')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.titulo_asunto }}</span>
            </template>
        </table-column>

        <table-column show="de_tipos_documentos_id" label="@lang('Tipo de documento')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.de_tipos_documentos ? row.de_tipos_documentos.nombre : "Documento en blanco"}}</span>
            </template>
        </table-column>

        <table-column show="estado" label="@lang('Estado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Elaboración'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='Revisión' || row.estado == 'Revisión (Editado por externo)'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='Pendiente de firma'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Devuelto para modificaciones'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Público'" :class="{'registro-no-leido': !row.leido}"></div>
                <div class="text-black text-center p-4 bg-grey states_style" v-html="row.estado" v-if="row.estado.includes(' (pendiente de enviar)')" :class="{'registro-no-leido': !row.leido}"></div>
            </template>
        </table-column>

        <table-column show="subestado_documento" label="@lang('Actividad')">
            <template slot-scope="row">
                <span :class="{'registro-no-leido': !row.leido}">@{{ row.subestado_documento ? row.subestado_documento : "N/A"}}</span>
            </template>
        </table-column>

        <table-column show="document_pdf" label="@lang('Documento')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.document_pdf" :class="{'registro-no-leido': !row.leido}">
                    <viewer-attachement :link-file-name="true" type="only-link" :ref="row.consecutivo" :component-reference="row.consecutivo" :list="row.document_pdf" :key="row.document_pdf" :name="row.consecutivo"></viewer-attachement>
                </div>

                <div v-else :class="{'registro-no-leido': !row.leido}">
                    <span>No tiene documento principal</span>
                </div>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.permission_edit && ( row.estado != 'Pendiente de firma'  &&  row.estado != 'Público' ) && row.origen_documento == 'Producir documento en línea a través de Intraweb'" @click="callFunctionComponent('documentos_ref', 'loadDocumento', row)" data-backdrop="static" data-target="#modal-form-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button v-if="row.origen_documento == 'Adjuntar documento para ser almacenado en Intraweb' && 
                ((row.estado == 'Público' && row.is_editable) || 
                 (row.estado != 'Público' && row.users_id == {{ Auth::user()->id }}))"  @click="callFunctionComponent('documentos_ref', 'loadDocumento', row)" data-backdrop="static" data-target="#modal-form-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button v-if="row.permission_edit && row.estado == 'Pendiente de firma'" @click="callFunctionComponent('documentos_ref', 'firmarDocumento', row); getPathDocument(row.plantilla ? row.de_documento_versions[0]?.document_pdf_temp : row.documento_adjunto)" data-backdrop="static" data-target="#modal-aprobar-cancelar-firma" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Aprobar o devolver para modificaciones"><i class="fas fa-signature"></i></button>

                <button @click="show(row); $refs.documentos_ref.leido(row[customId]);" data-target="#modal-view-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <!-- Botón para anotaciones pendientes -->
                {{-- <a :href="'{!! url('documentos-electronicos/documento-anotacions') !!}?de=' + row.id_encode" --}}
                <a
                class="btn btn-white btn-icon btn-md position-relative"
                data-toggle="tooltip"
                data-placement="top"
                title="Anotaciones Pendiente"
                :key="row.anotaciones_pendientes?.length"
                @click="$refs.anotacionesDocumentos.abrirModal(row); row.anotaciones_pendientes?.length > 0 ? $refs.documentos_ref.leerAnotaciones(row[customId]) : null"
                v-if="row.estado == 'Público'"> <!-- Maneja el evento click -->
                    <i class="fas fa-comment"></i> <!-- Icono -->
                    <span v-if="row.anotaciones_pendientes?.length > 0" class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.anotaciones_pendientes.length }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a>

                <button @click="edit(row)" v-if="row.estado == 'Público'" data-backdrop="static" data-target="#compartir-documento" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Compartir documento"><i class="fas fa-share-square"></i></button>

                <button @click="drop(row[customId])" v-if="row.estado == 'Elaboración' || row.estado == 'Elaboración (pendiente de enviar)'" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                <button @click="drop(row[customId])" v-if="row.estado == 'Público' && row.is_eliminable" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

                @if (config('app.mod_expedientes'))
                <a  v-if="row.estado == 'Público'"
                    @click="$refs.expedientes.abrirModal(row);"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Expedientes">
                    <i class="fas fa-sitemap"></i>
                </a>
                @endif

            </template>
        </table-column>
    </table-component>
</div>
