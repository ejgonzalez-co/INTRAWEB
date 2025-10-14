<div class="table-responsive">
    <table-component
        id="documento-solicitud-documentals-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="documento-solicitud-documentals"
        sort-order="asc"
        table-class="table table-hover m-b-0"
        :show-filter="false"
        :pagination="dataPaginator"
        :show-caption="false"
        filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4"
        :cache-lifetime="0">
        <table-column show="tipo_solicitud" label="@lang('Tipo de solicitud')"></table-column>

        <table-column show="calidad_proceso_id" label="@lang('Proceso')">
            <template slot-scope="row">
                @{{ row.proceso?.nombre }}
            </template>
        </table-column>

        <table-column show="codigo" label="@lang('Código')">
            <template slot-scope="row">
                <span v-if="row.codigo"> @{{ row.codigo }}</span>
            </template>
        </table-column>

        <table-column show="tipo_documento" label="@lang('Tipo de documento')">
            <template slot-scope="row">
                @{{ row.documento_tipo_documento?.nombre }}
            </template>
        </table-column>

        <table-column show="nombre_documento" label="@lang('Nombre del documento')"></table-column>

        <table-column show="estado" label="@lang('Estado de la solicitud')"></table-column>

        <table-column show="created_at" label="@lang('Fecha de la solicitud')"></table-column>

        <table-column show="funcionario_responsable" label="@lang('Revisión técnica')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- Valida si el usuario en sesión es un administrador de calidad --}}
                @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documento-solicitud-documentals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif
                <button @click="show(row)" data-target="#modal-view-documento-solicitud-documentals" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                {{-- Valida si el usuario en sesión es un administrador de calidad --}}
                @if (Auth::user()->hasRole('Admin Documentos de Calidad'))
                    {{-- Abre el formulario para aprobar o rechazar una solicitud documental. Esta acción solo se habilita a las solicitudes en estad de 'Solicitud en revisión' --}}
                    <button v-if="row.estado == 'Solicitud en revisión'" @click="edit(row)" data-backdrop="static" data-target="#modal-gestion-solicitud-documental" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Gestionar solicitud">
                        <i class="fas fa-reply"></i>
                    </button>

                    <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
            </template>
        </table-column>
    </table-component>
</div>
