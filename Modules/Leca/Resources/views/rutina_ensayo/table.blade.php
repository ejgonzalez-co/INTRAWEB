<div class="table-responsive">
    <table-component
        id="Toma-de-muestra-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="Toma-de-muestra"
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
        
            <table-column show="sample_reception_code" label="Identificación de la muestra"></table-column>
            <table-column show="created_at" label="Fecha toma de la muestra">
                <template slot-scope="row">
                    @{{ formatDate(row.created_at) }}
                </template></table-column>
                <table-column show="reception_date" label="Fecha recepción de muestra">
                    <template slot-scope="row">
                        @{{ formatDate(row.reception_date) }}
                    </template></table-column>
            <table-column show="type_water" label="Estado del agua"></table-column>
            <table-column show="estado_analisis" label="Estado">
                <template slot-scope="row" :sortable="false" :filterable="false">
                    <div v-if="row.estado_analisis == 'Análisis pendiente'" style="background-color: #17a2b8; color: white">
                        @{{ row . estado_analisis }}
                    </div>
                    <div v-if="row.estado_analisis  == 'Análisis en ejecución'"
                        style="background-color: #ffa500">
                        @{{ row . estado_analisis }}
                    </div>
                    <div v-if="row.estado_analisis == 'Análisis finalizado'" style="background-color: #27C44F; color: white">
                        @{{ row . estado_analisis }}
                    </div>
                </template>
            </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-Toma-de-muestra" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="show(row)" data-target="#modal-history-sample-taking" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>