<div class="table-responsive">
    <table-component id="assetManagements-table" :data="dataList" sort-by="assetManagements"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="created_at" label="@lang('Fecha del registro')"></table-column>
        <table-column show="nombre_activo" label="@lang('Nombre Activo')"></table-column>
        <table-column show="tipo_mantenimiento" label="@lang('Tipo Mantenimiento')"></table-column>
        <table-column show="repuesto" label="@lang('Repuesto')">
            <template slot-scope="row">
                    <p v-if="row.repuestos">@{{ row.repuestos ? row.repuestos.descripcion_nombre : "No aplica"}}</p>
                    <p v-else>No aplica</p>
            </template>
        </table-column>
        <table-column show="actividad" label="@lang('Actividad')">
            <template slot-scope="row">
                <p v-if="row.actividades">@{{ row.actividades ? row.actividades.descripcion_nombre : "No aplica"}}</p>
                <p v-else>No aplica</p>
            </template>
        </table-column>
        <table-column show="actividad" label="@lang('Cantidad')">
            <template slot-scope="row">
                <p>@{{ row.repuestos ? row.repuestos.cantidad_solicitada : row.actividades.cantidad_solicitada}}</p>
            </template>
        </table-column>
        <table-column show="unidad_medida" label="Unidad de medida"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-assetManagements" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
