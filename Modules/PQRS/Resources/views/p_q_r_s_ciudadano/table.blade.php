<div class="table-responsive">
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
            
            <table-column show="pqr_id" label="Consecutivo">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.pqr_id }}</div></template>
            </table-column>

            <table-column show="estado" label="@lang('Estado')">
                <template slot-scope="row">
                    <div class="text-center" :style="!row.leido ? 'font-weight: bold;' : ''" :class="row.estado == 'Abierto' ? 'estado_abierto' : (row.estado == 'Finalizado' && row.linea_tiempo == 'A tiempo' ? 'estado_finalizado_a_tiempo' : (row.estado == 'Finalizado vencido justificado' ? 'estado_finalizado_vencido_justificado' : (row.linea_tiempo == 'A tiempo' ? 'estado_a_tiempo' : (row.linea_tiempo == 'Próximo a vencer' ? 'estado_proximo_vencer' : 'estado_vencido'))))">
                        @{{ row.estado }} <br />
                        <span v-if="row.linea_tiempo" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.linea_tiempo }})</span>
                    </div>
                </template>
            </table-column>

            <table-column show="nombre_ciudadano" label="@lang('Ciudadano')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.ciudadano_users ? row.ciudadano_users.name : row.nombre_ciudadano }}</div></template>
            </table-column>

            <table-column show="funcionario_destinatario" label="@lang('Destinatario')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.funcionario_destinatario }}</div></template>
            </table-column>

            <table-column show="pqr_eje_tematico" label="@lang('Eje temático')">
                <template slot-scope="row">
                    <div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.pqr_eje_tematico ? row.pqr_eje_tematico.nombre : row.nombre_ejetematico}}</div>
                    <div v-if="row.pqr_eje_tematico" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.pqr_eje_tematico.plazo }} @{{ row.pqr_eje_tematico.plazo_unidad }})</div>
                    <div v-else-if="row.plazo" :style="!row.leido ? 'font-weight: bold;' : ''">(@{{ row.plazo }} Días)</div>
                </template>
            </table-column>

            <table-column show="created_at" label="@lang('Fecha de creación')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.created_at }}</div></template>
            </table-column>

            <table-column show="fecha_vence" label="@lang('Fecha de vencimiento')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.fecha_vence }}</div></template>
            </table-column>

            <table-column show="updated_at" label="@lang('Última modificación')">
                <template slot-scope="row"><div :style="!row.leido ? 'font-weight: bold;' : ''">@{{ row.updated_at }}</div></template>
            </table-column>

            <table-column show="adjunto" label="Adjuntos de respuesta">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div v-if="row.adjunto">
                        <span v-for="adjunto in row.adjunto.split(',')" style="margin-left: -15px;">
                            <a class="col-9 text-truncate" href="javascript:void(0)"  @click="callFunctionComponent('viewerDocuments','mostrarAdjunto',adjunto)">Ver adjunto</a><br/>
                    </div>
                    <div v-else>
                        <span>No tiene adjuntos</span>
                    </div>
                </template>
            </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.estado == 'Esperando respuesta del ciudadano' && row.respuesta_ciudadano == null" @click="edit(row)" data-backdrop="static" data-target="#answer-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Dar respuesta del ciudadano"><i class="fas fa-comment-dots"></i></button>

                <button @click="show(row); $refs.componentePQR.asignarLeidoPQR(row[customId]); row.leido = true;" data-target="#modal-view-p-q-r-s" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>