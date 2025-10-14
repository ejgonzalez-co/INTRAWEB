<div class="table-responsive">
    <table-component
        id="documentos-ayudas-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="documentos-ayudas"
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

        <table-column show="documento" label="@lang('Documento')">
            <template scope="row">
                <a :href="'/storage/'+row.documento" target="_blank">Ver documento</a>
            </template>
        </table-column>

        <table-column show="imagen_previa" label="@lang('Imagen previa')">
            <template scope="row">
                <img :src="'/storage/'+row.imagen_previa" alt="" width="85px">
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documentos-ayudas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-documentos-ayudas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>