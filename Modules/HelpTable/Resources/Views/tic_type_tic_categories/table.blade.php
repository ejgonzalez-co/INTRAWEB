<div class="table-responsive">
    <table-component
        id="ticTypeTicCategories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="ticTypeTicCategories"
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
        <table-column show="created_at" label="@lang('Created_at')"></table-column>

        <table-column show="name" label="@lang('Categoría')"></table-column>

        <table-column show="tic_type_assets" label="@lang('Tipos')">

        <!-- Mostrar los tipos si hay datos -->
        <template slot-scope="row">
            <div v-if="row.tic_type_assets.length > 0">
                <ul style="list-style-type: disc; padding-left: 20px;">
                    <div v-for="(tipo, key) in row.tic_type_assets.slice(0, 5)" :key="key">
                        <li>@{{ tipo.name }}</li>
                    </div>
                </ul>
            </div>
            <!-- Mostrar mensaje si no hay tipos -->
            <div v-else>
                <br class="">N/A</br>
            </div>
        </template>


    </table-column>


            <table-column show="estado" label="@lang('Estado')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-tic-type-tic-categories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-tic-type-tic-categories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-tic-type-tic-categories-history" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fas fa-history"></i>
                </button>
                
            </template>
    </table-component>
</div>