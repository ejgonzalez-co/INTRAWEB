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
                <button v-if="(row.estado == 'Pendiente de firma' || row.estado == 'Pendiente de aprobación de cierre') && row.id_responsable == {!! Auth::user()->id !!}" @click="edit(row)" data-backdrop="static" data-target="#aprobar-firmar-expediente" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Aprobar y firmar expediente">
                    <i class="fas fa-signature"></i>
                </button>
                {{-- Valida si el usuario en sesión es operador de expedientes --}}
                <button v-if="((row.id_responsable == @json(Auth::user()->id) && (row.estado == 'Abierto' || row.estado == 'Cerrado') && searchFields.rol_consulta_expedientes != 'operador') || (@json(Auth::user()->hasRole('Operador Expedientes Electrónicos')) && row.estado == 'Devuelto para modificaciones' && searchFields.rol_consulta_expedientes == 'operador')) && searchFields.rol_consulta_expedientes != 'consulta_expedientes'" @click="edit(row)" data-backdrop="static" data-target="#modal-form-expedientes" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <a v-if="((row.id_responsable == @json(Auth::user()->id) && row.estado != 'Devuelto para modificaciones') || row.permiso_usuarios_expediente || @json(Auth::user()->hasRole('Consulta Expedientes Electrónicos'))) && searchFields.rol_consulta_expedientes != 'operador'" :href="'{!! url('expedientes-electronicos/documentos-expedientes') !!}?c=' + row.encrypted_id"
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

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}

                <button v-if="row.estado == 'Abierto' && row.id_responsable == {!!  Auth::user()->id !!} && searchFields.rol_consulta_expedientes != 'consulta_expedientes' && searchFields.rol_consulta_expedientes != 'operador'"
                    @click="$refs.expedientes.firmarCerrarExpediente(row)"
                    class="btn btn-white btn-icon btn-md" title="Firmar y cerrar expediente">
                    <i class="fas fa-exchange-alt"></i>
                </button>

                <!-- Botón para anotaciones pendientes -->
                <a
                    target="_blank"
                    class="btn btn-white btn-icon btn-md position-relative"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Anotaciones Pendiente"
                    v-if="row.anotaciones_pendientes?.length > 0"
                    :key="row.anotaciones_pendientes?.length"
                    @click="$refs.annotations.abrirModal(row); $refs.expedientes.asignarLeidoAnotacionExpediente(row[customId]);"> <!-- Maneja el evento click -->
                    <i class="fas fa-comment"></i> <!-- Icono -->
                    <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                        @{{ row.anotaciones_pendientes.length }}
                    </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                </a>

                <!-- Botón para cuando no hay anotaciones pendientes -->
                <a v-else
                    @click="$refs.annotations.abrirModal(row); $refs.expedientes.asignarLeidoAnotacionExpediente(row[customId]);"
                    target="_blank"
                    class="btn btn-white btn-icon btn-md"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Anotaciones">
                    <i class="fas fa-comment"></i> <!-- Icono -->
                </a>

            </template>
        </table-column>
    </table-component>
</div>
