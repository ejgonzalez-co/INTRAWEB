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
            
            <table-column show="id" label="@lang('ID')"></table-column>

            <table-column show="estado" label="@lang('Estado')">
                <template slot-scope="row">
                    <div class="text-center" :style="(!row.leido ? 'font-weight: bold;' : '')+'width: 102%'" :class="row.estado == 'Finalizado' ? 'estado_finalizado_a_tiempo' : (row.estado == 'Cancelado' ? 'estado_cancelado' : null)">
                        @{{ row.estado }} <br/>
                    </div>
                </template>
            </table-column>

            <table-column show="nombre_ciudadano" label="@lang('Ciudadano')">
            </table-column>

            <table-column show="funcionario_asignado" label="@lang('Destinatario')">
            </table-column>

            <table-column show="nombre_ejetematico" label="@lang('Eje temático')">
            </table-column>

            <table-column show="cf_created" label="@lang('Fecha de creación')">
            </table-column>

            <table-column show="fechavence" label="@lang('Fecha de vencimiento')">
            </table-column>

            <table-column show="cf_modified" label="@lang('Última modificación')">
            </table-column>

            <table-column show="adjunto" label="@lang('Adjuntos')">
                <template slot-scope="row">
                <div v-if="row.adjunto">

                    <viewer-attachement type="only-link" :list="row.adjunto" :key="row.adjunto" :name="row.consecutive"></viewer-attachement>

                    </div>

                    <div v-else>
                    <span>No tiene documento principal</span>
                </div>
                </template>
            </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row);" data-target="#modal-view-p-q-r-s-repository" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>