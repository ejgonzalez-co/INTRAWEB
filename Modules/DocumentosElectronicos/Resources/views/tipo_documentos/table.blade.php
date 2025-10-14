<div class="table-responsive">
    <table-component
        id="tipo-documentos-table"
        :data="dataList"
        sort-by="tipo-documentos"
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
        <table-column show="nombre" label="@lang('Nombre')"></table-column>

        <table-column show="prefijo" label="@lang('Prefijo')"></table-column>

        <table-column show="version" label="@lang('Version')"></table-column>

        <table-column show="codigo_formato" label="@lang('Código del formato')"></table-column>

        <table-column show="formato_consecutivo" label="@lang('Formato del consecutivo')"></table-column>

        <table-column show="permiso_crear_documentos_todas" label="@lang('¿Qué dependencias pueden usar este tipo de documento?')">
            <template slot-scope="row">
                <span v-if="row.permiso_crear_documentos_todas">Todas</span>
                <ul v-else style="padding-left: 20px;">
                    <li v-for="(dependencia, index) in row.de_permiso_crear_documentos" :key="index">
                        @{{ dependencia.nombre }}
                        <br />
                    </li>
                </ul>
            </template>
        </table-column>

        <table-column show="estado" label="@lang('Estado')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-tipo-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-tipo-documentos" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}

            </template>
        </table-column>
    </table-component>
</div>
