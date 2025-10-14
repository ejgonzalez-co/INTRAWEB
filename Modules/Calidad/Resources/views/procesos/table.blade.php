<div class="table-responsive">
    <table-component
        id="procesos-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="procesos"
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
        <table-column show="nombre" label="@lang('Nombre')">
            <template slot-scope="row">
                <span v-if="row.calidad_proceso_id"> -- </span>@{{ row.nombre }}
            </template>
        </table-column>

        <table-column show="prefijo" label="@lang('Prefijo')"></table-column>

        <table-column show="usuario_responsable" label="@lang('Responsable')"></table-column>

        <table-column show="calidad_proceso_id" label="@lang('Proceso padre')">
            <template slot-scope="row">
                @{{ row.proceso?.nombre ?? 'N/A' }}
            </template>
        </table-column>

        <table-column show="dependencias_id" label="@lang('Dependencia')">
            <template slot-scope="row">
                @{{ row.dependencia?.nombre ?? 'N/A' }}
            </template>
        </table-column>

        <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-procesos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-procesos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
