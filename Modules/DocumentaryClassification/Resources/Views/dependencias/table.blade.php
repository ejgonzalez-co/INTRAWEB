<div class="table-responsive">
    <table-component
        id="dependencias-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="dependencias"
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
        {{-- <table-column show="id_sede" label="@lang('Id Sede')"></table-column> --}}
        {{-- <table-column show="codigo" label="@lang('Codigo')"></table-column> --}}
        <table-column show="codigo" label="Código de oficina productora"></table-column>
        <table-column show="nombre" label="@lang('Nombre')"></table-column>
        {{-- <table-column show="cf_user_id" label="@lang('Cf User Id')"></table-column> --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-dependencias" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                {{-- <button @click="show(row)" data-target="#modal-view-dependencias" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button> --}}

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-dependencias" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('asignDependencias')">
                    <i class="fa fa-file"></i>
                </button>

                {{-- <button @click="show(row)" data-target="#modal-processing-receipt" title="sisas" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-file"></i>
                </button> --}}
                
                <a :href="'/documentary-classification/dependencias-serie-subseries?id_dependencia='+row.id" class="style_redirect" v-if="row.del_confirm == 1">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('editDependencias')">
                        <i class="fa fa-clock"></i>
                    </button>
                </a>

                <a :href="'export-dependencias-specific-all?id_dependencia='+row.id" class="style_redirect" >
                    <button class="btn btn-white btn-icon btn-md" title="@lang('generateTrdExcel')" >
                        <i class="fa fa-file-excel"></i>
                    </button>
                </a>
                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('Borrar TRD')" v-if="row.del_confirm == 1">
                    <i class="fa fa-trash"></i>
                </button> --}}
                
            </template>
        </table-column>
    </table-component>
</div>