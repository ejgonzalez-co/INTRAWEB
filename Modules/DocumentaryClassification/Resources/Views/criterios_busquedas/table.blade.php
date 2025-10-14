<div class="table-responsive">
    <table-component
        id="criterios-busquedas-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="criterios-busquedas"
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

        <table-column show="tipo_campo" label="@lang('Tipo')"></table-column>

        <table-column label="@lang('Serie o subseries documentales relacionadas')">
            <template slot-scope="row">
                <ul class="list-unstyled">
                    <li v-if="row.series_subseries" v-for="(lt, key) in row.series_subseries" :key="key" class="mb-1">
                        <strong>@{{ lt.no_subserie ? lt.no_subserie : lt.no_serie }} - 
                        @{{ lt.no_subserie ? lt.name_subserie : lt.name_serie }}</strong>
                        <span v-if="lt.tipo_documental" class="text-muted"> ( @{{ lt.tipo_documental.name }} )</span>
                    </li>
                </ul>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="callFunctionComponent('form-criterios-busqueda','showModalEdit',row)" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <!-- <button @click="show(row)" data-target="#modal-view-criterios-busquedas" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button> -->

                <!-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> -->
                
            </template>
        </table-column>
    </table-component>
</div>