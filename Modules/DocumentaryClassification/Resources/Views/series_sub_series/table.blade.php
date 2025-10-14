<div class="table-responsive">
    <table-component
        id="seriesSubSeries-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="seriesSubSeries"
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
        <table-column show="no_serie" label="No. Serie"></table-column>
        <table-column show="no_subserie" label="No. Subserie"></table-column>
        <table-column label="Nombre serie">
            <template slot-scope="row">
                <div class="text-left font-weight-bold"
                    v-html="row.name_serie"></div>
            </template>
        </table-column>

        <table-column label="Nombre subserie">
            <template slot-scope="row">
                <div class="text-left font-weight-bold"
                    v-html="row.name_subserie"></div>
            </template>
        </table-column>

        <table-column label="Tiempo en archivo de gestión">
            <template slot-scope="row">
                <div v-html="row.time_gestion_archives"></div>
            </template>
        </table-column>

        <table-column show="time_central_file" label="Tiempo en archivo central"></table-column>
        <table-column label="Disposición final" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <ul style="list-style:none;padding-left : 0%">
                    <div v-if="row.full_conversation == 1">
                        <li>Conservación total.</li>
                    </div>
                    <div v-if="row.select == 1">
                        <li>Selección.</li>
                    </div>
                    <div v-if="row.delete == 1">
                        <li>Eliminación.</li>
                    </div>
                    <div v-if="row.medium_tecnology == 1">
                        <li>Medios Tecnológicos.</li>
                    </div>
                    <div v-if="row.not_transferable_central == 1">
                        <li>No Transferible al Archivo Central.</li>
                    </div>
                </ul>
            </template>
        </table-column>
        <table-column show="soport" label="Soporte"></table-column>
        <table-column show="confidentiality" label="Confidencialilidad"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-series-subseries" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-seriesSubSeries" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>


            </template>
        </table-column>
    </table-component>
</div>
