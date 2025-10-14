<div class="table-responsive">
    <table-component
        id="correo-integrado-spams-table"
        :data="dataList"
        sort-by="correo-integrado-spams"
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
        <table-column show="correo_remitente" label="@lang('Correo Remitente')"></table-column>

                    <!-- <table-column show="uid" label="@lang('uid')"></table-column> -->

                    <table-column show="fecha" label="@lang('Fecha')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <button @click="show(row)" data-target="#modal-view-correo-integrado-spams" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>                
            </template>
        </table-column>
    </table-component>
</div>