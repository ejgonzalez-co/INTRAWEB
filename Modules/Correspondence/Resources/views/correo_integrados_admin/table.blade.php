<div class="table-responsive">
    <table-component
        id="external-types-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="external-types"
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
        <table-column show="correo_comunicaciones" label="Correo"></table-column>
        <table-column show="servidor" label="Servidor"></table-column>
        <table-column show="seguridad" label="Seguridad"></table-column>
        <table-column show="puerto" label="Puerto"></table-column>
        <table-column show="correo_communicaciones_clave" label="Clave"></table-column>


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-correo-integrados-list" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
{{-- 
                <button @click="show(row)" data-target="#modal-view-correo-integrados" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button> --}}

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>