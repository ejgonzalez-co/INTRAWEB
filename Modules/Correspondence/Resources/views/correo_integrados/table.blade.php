<div class="table-responsive">
    <table-component
        id="correo-integrados-table"
        :data="dataList"
        sort-by="correo-integrados"
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
        <table-column show="consecutivo" label="@lang('Consecutivo')"></table-column>

        <table-column show="asunto" label="@lang('Asunto')">
            <template slot-scope="row">
                <div v-if="row.asunto" class="text-truncate" style="max-width:250px;" :title="row.asunto">@{{ row.asunto }}</div>
                <div v-else>(sin asunto)</div>
            </template>
        </table-column>

        <table-column show="correo_remitente" label="@lang('Correo del remitente')"></table-column>

        <table-column show="fecha" label="@lang('Fecha')"></table-column>

        <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column show="clasificacion" label="@lang('Clasificación')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="row.estado == 'Sin clasificar' || row.clasificacion == 'Comunicación no oficial'" @click="callFunctionComponent('received_ref', 'clasificarComunicacion', row)" data-backdrop="static" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-correo-integrados" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>