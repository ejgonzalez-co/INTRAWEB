<div class="table-responsive">
    <table-component id="rols-table" :data="advancedSearchFilterPaginate()" sort-by="rols" sort-order="asc"
        table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator" :show-caption="false"
        filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="name" label="@lang('Nombre del rol')"></table-column>
        <table-column show="guard_name" label="@lang('Acceso a módulos')">
            <template slot-scope="row">
                <ul v-for="(permission,key) in row.rol_permissions" :key="key">
                    <li>@{{ permission.module }}</li>
                </ul>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="initValues.can_manage" @click="edit(row)" data-backdrop="static" data-target="#modal-form-rols" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-rols" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="initValues.can_manage" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
