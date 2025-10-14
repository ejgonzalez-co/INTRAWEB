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
                <div><strong style="color: #2196f3">Titulo: </strong>@{{ row.nombre_documento ? row.nombre_documento : 'N/A' }}</div>
                <div><strong style="color: #2196f3">Creado por: </strong>@{{ row.user_name ? row.user_name : 'N/A' }}</div>
                <div><strong style="color: #2196f3">Origen del documento: </strong>Expedientes</div>
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
                    <a href="#" v-else-if="row.info_documento && row.info_documento[0].channel_name == 'Correo electrónico'" @click="show(row)" data-target="#modal-view-documentos-expedientes" data-toggle="modal" data-toggle="tooltip" data-placement="top">Correo electrónico</a>
                    <a href="#" v-else @click="show(row)" data-target="#modal-view-documentos-expedientes" data-toggle="modal" data-toggle="tooltip" data-placement="top">@{{ row.modulo_intraweb }}</a>
            </div>
                <div v-else>
                    <i class="fa fa-trash"></i>
                    <span>Eliminado</span>
                </div>
            </template>
        </table-column>

        <table-column show="documento_adjunto" label="Adjunte el documento para que podamos validar su contenido" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <input type="file" id="documento_adjunto" name="documento_adjunto" @change="adjuntarDocumento($event, row.nombre_documento == 'Carátula de apertura' ? row.ee_expediente.hash_caratula_apertura : row.ee_expediente.hash_caratula_cierre)" class="btn btn-info">
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
