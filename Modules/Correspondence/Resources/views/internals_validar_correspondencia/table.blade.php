<div class="table-responsive">
    <table-component
        id="internals-table"
        :data="dataList"
        sort-by="internals"
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

        <table-column show="consecutive" label="@lang('Consecutive')"></table-column>

        <table-column show="internal_type.name" label="@lang('Type')"></table-column>

        <table-column show="title" label="@lang('Title')"></table-column>

        <table-column show="state" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="text-white text-center p-4 bg-blue states_style" v-html="row.state" v-if="row.state=='Elaboración'"></div>
                <div class="text-black text-center p-4 bg-yellow states_style" v-html="row.state" v-if="row.state=='Revisión'"></div>
                <div class="text-white text-center p-4 bg-cyan states_style" v-html="row.state" v-if="row.state=='Aprobación'"></div>
                <div class="text-white text-center p-4 bg-orange states_style" v-html="row.state" v-if="row.state=='Pendiente de firma'"></div>
                <div class="text-white text-center p-4 bg-red states_style" v-html="row.state" v-if="row.state=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 bg-green states_style" v-html="row.state" v-if="row.state=='Público'"></div>

                {{-- <div class="button__status-in_review states_style" v-html="row.state" v-if="row.state=='Elaboración'"></div>
                <div class="button__status-assigned states_style" v-html="row.state" v-if="row.state=='Revisión'"></div>
                <div class="button__status-pending_approval states_style" v-html="row.state" v-if="row.state=='Aprobación'"></div>
                <div class="button__status-pending states_style" v-html="row.state" v-if="row.state=='Pendiente de firma'"></div>
                <div class="button__status-cancelled states_style" v-html="row.state" v-if="row.state=='Devuelto para modificaciones'"></div>
                <div class="button__status-approved states_style" v-html="row.state" v-if="row.state=='Público'"></div> --}}
            </template>
        </table-column>


        <table-column show="dependency_from" label="Dependencia"></table-column>

        <table-column show="from" label="Remitente"></table-column>

        <table-column show="recipients" label="Destinatarios">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.recipients">
                    <span v-html="row.recipients"></span>
                </div>
                <div v-else>
                    <span>Aún no tiene destinatarios</span>
                </div>
            </template>

        </table-column>

        <table-column show="document_pdf" label="Adjuntos" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div v-if="row.document_pdf">
                    <viewer-attachement
                    :ref="row.consecutive"
                    :component-reference="row.consecutive"
                    type="only-link"
                    :list="row.document_pdf"
                    :key="row.document_pdf"
                    :name="row.consecutive"></viewer-attachement>
                </div>
                <div v-else>
                    <span>No tiene adjuntos</span>
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
                <button  @click="validar_documento_correspondencia_interna()" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="validar documento">
                    <i class="fas fa-check"></i>
                </button>
            </template>
        </table-column>

    </table-component>
</div>
