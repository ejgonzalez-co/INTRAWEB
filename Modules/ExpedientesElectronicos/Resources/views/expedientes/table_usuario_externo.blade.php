<div class="table-responsive">
    <table-component
        id="expedientes-table"
        :data="dataList"
        sort-by="expedientes"
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
        <table-column show="id_expediente" label="ID"></table-column>

        <table-column show="consecutivo" label="Consecutivo"></table-column>

        <table-column show="nombre_expediente" label="Expediente"></table-column>

        <table-column show="oficina_productora_clasificacion_documental.nombre" label="Oficina productora"></table-column>

        <table-column show="serie_clasificacion_documental.name_serie" label="Serie"></table-column>

        <table-column show="subserie_clasificacion_documental.name_subserie" label="Sub serie"></table-column>

        <table-column show="fecha_inicio_expediente" label="Fecha inicio expediente"></table-column>

        {{-- <table-column show="nombre_dependencia" label="Dependencia"></table-column> --}}

        {{-- <table-column show="user_name" label="Usuario creador"></table-column> --}}
        <table-column show="nombre_responsable" label="Responsable"></table-column>

        <table-column show="estado" label="@lang('State')" cell-class="col-sm-1">
            <template slot-scope="row" :sortable="false" :filterable="false">

                <div class="text-white text-center p-4 states_style" style="background-color: #2196f3;" v-html="row.estado" v-if="row.estado=='Abierto'"></div>
                {{-- <div class="text-white text-center p-4 states_style" style="background-color: #fd7e14;" v-html="row.estado" v-else-if="row.estado=='Pendiente de aprobación de cierre'"></div> --}}
                <div class="text-white text-center p-4 states_style" style="background-color: #ff9800;" v-html="row.estado" v-else-if="row.estado=='Pendiente de firma'"></div>
                <div class="text-white text-center p-4 states_style" style="background-color: #f44336;" v-html="row.estado" v-else-if="row.estado=='Devuelto para modificaciones'"></div>
                <div class="text-white text-center p-4 states_style" style="background-color: #8bc34a;" v-html="row.estado" v-else-if="row.estado=='Cerrado'"></div>
                <div v-else class="text-white text-center p-4 bg-white states_style" v-html="row.estado"></div>

            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <a v-if="'{{ session('permiso') }}' === 'Incluir información y editar documentos' || '{{ session('permiso') }}' === 'Incluir información y editar documentos (solo del usuario)' || '{{ session('permiso') }}' === 'Consultar el expediente y sus documentos'" :href="'{!! url('expedientes-electronicos/documentos-expedientes-usuario-externo') !!}?c=' + row.encrypted_id"
                    class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Administrar documentos"
                    @click="$refs.expedientes.registrar_leido(row[customId], searchFields.rol_consulta_expedientes);">
                    <i class="far fa-file-alt"></i> <!-- Icono -->
                </a>

                <button @click="show(row); $refs.expedientes.registrar_leido(row[customId], searchFields.rol_consulta_expedientes);" data-target="#modal-view-expedientes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
