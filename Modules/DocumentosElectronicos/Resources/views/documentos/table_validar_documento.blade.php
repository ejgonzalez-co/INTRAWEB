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
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="users_name" label="@lang('Creado por')"></table-column>

        <table-column show="consecutivo" label="@lang('Consecutive')">
        <template slot-scope="row">
            <span v-if="row.estado != 'Público'">(Temporal) </span>@{{ row.consecutivo }}
        </template>
        </table-column>

        <table-column show="titulo_asunto" label="@lang('Título')"></table-column>

        <table-column show="de_tipos_documentos_id" label="@lang('Tipo de documento')">
            <template slot-scope="row">
                <span>@{{ row.de_tipos_documentos ? row.de_tipos_documentos.nombre : "Documento en blanco"}}</span>
            </template>
        </table-column>

        <table-column show="estado" label="@lang('Estado')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.estado" v-if="row.estado=='Elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.estado" v-if="row.estado=='Revisión' || row.estado == 'Revisión (Editado por externo)'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.estado" v-if="row.estado=='Pendiente de firma'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.estado" v-if="row.estado=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.estado" v-if="row.estado=='Público'"></div>
            </template>
        </table-column>

        <table-column show="document_pdf" label="@lang('Documento')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.document_pdf">
                    <viewer-attachement :link-file-name="true" type="only-link" :ref="row.consecutivo" :component-reference="row.consecutivo" :list="row.document_pdf" :key="row.document_pdf" :name="row.consecutivo"></viewer-attachement>
                </div>

                <div v-else>
                    <span>No tiene documento principal</span>
                </div>
            </template>
        </table-column>

        <table-column show="documento_adjunto" label="Adjunte el documento para que podamos validar su contenido" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <input type="file" id="documento_adjunto" name="documento_adjunto" @change="adjuntarDocumento($event, row.hash_document_pdf)" class="btn btn-info">
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <button  @click="validar_adjunto_documento()" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="validar documento">
                    <i class="fas fa-check"></i>
                </button>
            </template>
        </table-column>
    </table-component>
</div>
