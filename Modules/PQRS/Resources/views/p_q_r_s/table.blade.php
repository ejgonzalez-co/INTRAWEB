<div class="table-responsive"  style="overflow-y: clip; !important">
    <table-component
        id="p-q-r-s-table"
        :data="dataList"
        sort-by="p-q-r-s"
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
            <table-column show="destacado" label="@lang('Destacado')">
                <template slot-scope="row">
                    <star-rating v-model="row.destacado" :clearable="true" @rating-selected="$refs.componentePQR.asignarDestacado(row[customId], $event);" v-bind:max-rating="1" rounded-corners :star-size=20 :show-rating=false></star-rating>
                </template>
            </table-column>

            <table-column show="pqr_id" label="@lang('Consecutive')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.pqr_id }}</div></template>
            </table-column>

            <table-column show="estado" label="@lang('Estado')">
                <template slot-scope="row">
                    <div class="text-center" :style="(!row.leido ? 'font-weight: bold;' : '')+'width: 102%'" :class="row.estado == 'Abierto' ? 'estado_abierto' : (row.estado == 'Cancelado' ? 'estado_cancelado' : (row.estado == 'Finalizado' && row.linea_tiempo == 'A tiempo' ? 'estado_finalizado_a_tiempo' : (row.estado == 'Finalizado vencido justificado' ? 'estado_finalizado_vencido_justificado' : (row.linea_tiempo == 'A tiempo' ? 'estado_a_tiempo' : (row.linea_tiempo == 'Próximo a vencer' ? 'estado_proximo_vencer' : 'estado_vencido')))))">
                        @{{ row.estado }} <br />
                        <span v-if="row.linea_tiempo" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.linea_tiempo }})</span>
                    </div>
                </template>
            </table-column>

            {{-- <table-column show="nombre_ciudadano" label="Origen">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.ciudadano_users ? row.ciudadano_users.name : row.nombre_ciudadano }}</div></template>
            </table-column> --}}

            <table-column show="nombre_ciudadano" label="Origen">
                <template slot-scope="row">
                    <div v-if="row.nombre_ciudadano" :class="{'registro-no-leido': !row.leido}">
                            <b style="color: #0C7CD5">Ciudadano:</b> <span v-html="formatTextForHtml(shortText(row.nombre_ciudadano).text)"> </span> <br>
                            <b style="color: #0C7CD5">Asunto:</b> <span v-html="formatTextForHtml(shortText(row.contenido).text)"> </span>
                      
                        <strong style="font-size:18px;">
                            <span class="tooltip-trigger">
                                ...
                                <!-- Tooltip Layer -->
                                <div class="tooltip-layer">
                                    <div class="tooltip-content">
                                            <b>Ciudadano:</b> <span v-html=" formatTextForTooltip(row.nombre_ciudadano)"></span><br>
                                            <b>Asunto:</b> <span v-html=" formatTextForTooltip(row.contenido)"></span>
                                    </div>
                                </div>
                            </span>
                        </strong>
                    </div>
                </template>
            </table-column>

            <table-column show="funcionario_destinatario" label="@lang('Destinatario')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.funcionario_destinatario }}</div></template>
            </table-column>

            <table-column show="pqr_eje_tematico" label="@lang('Eje temático')">
                <template slot-scope="row">
                    <div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.pqr_eje_tematico ? row.pqr_eje_tematico.nombre : row.nombre_ejetematico}}</div>
                    {{-- <div v-if="row.pqr_eje_tematico" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.pqr_eje_tematico.plazo }} @{{ row.pqr_eje_tematico.plazo_unidad }})</div> --}}
                    <div v-if="row.plazo" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.plazo }} @{{ row.pqr_eje_tematico ? row.pqr_eje_tematico.plazo_unidad : 'Días'}})</div>

                </template>
            </table-column>

            <table-column show="created_at" label="@lang('Fecha de creación')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.created_at }}</div></template>
            </table-column>

            <table-column show="fecha_vence" label="@lang('Fecha de vencimiento')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.fecha_vence }}</div></template>
            </table-column>


            <table-column show="adjunto" label="Adjuntos de respuesta">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div v-if="row.adjunto">

                        <viewer-attachement :link-file-name="true"
                        :ref="row.pqr_id"
                        :component-reference="row.pqr_id"
                        type="only-link" :list="row.adjunto"
                        :key="row.adjunto"
                        :name="row.pqr_id"></viewer-attachement>

                    </div>
                    <div v-else>
                        <span>No tiene adjuntos</span>
                    </div>
                </template>
            </table-column>

        <table-column show="canal" label="@lang('Canal')">
            <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.canal }}</div></template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                {{-- Valida que el usuario que esta en sesión NO sea un consultor de requerimientos --}}
                @if(!Auth::user()->hasRole('Consulta de requerimientos'))
                    <button v-if="row.permission_edit" @click="edit(row); $refs.componentePQR.checkDays(row.fecha_vence,row.tipo_plazo); show(row); $refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;" data-backdrop="static" data-target="#modal-form-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                @if(Auth::user()->hasRole('Administrador de requerimientos') || Auth::user()->hasRole('Admin Modificar PQRS Finalizados'))
                    <button @click="edit(row); $refs.componentePQR.checkDays(row.fecha_vence,row.tipo_plazo); show(row); $refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;" data-backdrop="static" data-target="#modal-form-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif

                <button @click="edit(row); openForm=false;" data-backdrop="static" data-target="#share-PQRS" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Compartir PQRS"><i class="fas fa-share-square"></i></button>

                {{-- Valida que el usuario que esta en sesión NO sea un consultor de requerimientos --}}
                @if (!Auth::user()->hasRole('Consulta de requerimientos'))

                    <!-- Botón para anotaciones pendientes -->
                    <a
                        target="_blank"
                        class="btn btn-white btn-icon btn-md position-relative"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones Pendiente"
                        v-if="row.anotaciones_pendientes?.length > 0"
                        :key="row.anotaciones_pendientes?.length"
                        @click="$refs.annotations.abrirModal(row);$refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;"> <!-- Maneja el evento click -->
                        <i class="fas fa-comment"></i> <!-- Icono -->
                        <span class="badge badge-danger position-absolute top-0 start-100 translate-middle rounded-circle zindex">
                            @{{ row.anotaciones_pendientes.length }}
                        </span> <!-- Etiqueta con la cantidad de anotaciones pendientes -->
                    </a>

                    <!-- Botón para cuando no hay anotaciones pendientes -->
                    <a v-else
                        @click="$refs.annotations.abrirModal(row);$refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;"
                        target="_blank"
                        class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Anotaciones">
                        <i class="fas fa-comment"></i> <!-- Icono -->
                    </a>
                @endif
                <button @click="show(row); $refs.componentePQR.checkDays(row.fecha_vence,row.tipo_plazo,row.estado,row.fecha_fin); $refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;" data-target="#modal-view-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md position-relative" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                    <span  v-if="row.correspondence_external_id" class="badge badge-info position-absolute top-0 start-100"
                        style="font-size: 8px !important;">
                        ENVIADA
                    </span>
                </button>
                @if (config('app.mod_expedientes'))
                <a  v-if="row.estado != 'Abierto'"
                    @click="$refs.expedientes.abrirModal(row);" 
                    target="_blank" 
                    class="btn btn-white btn-icon btn-md" 
                    data-toggle="tooltip" 
                    data-placement="top" 
                    title="Expedientes">
                    <i class="fas fa-sitemap"></i>
                </a>
                @endif

                <button @click="edit(row)" v-if="row.tipo_adjunto == 'Adjunto pendiente' && row.users_id == {!!  Auth::user()->id !!}" data-backdrop="static" data-target="#modal-form-p-q-r-s-relacionar-adjunto" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Relacionar adjuntos de respuestas al PQRS"><i class="fas fa-arrow-up"></i></button>

            </template>
        </table-column>
    </table-component>
</div>
