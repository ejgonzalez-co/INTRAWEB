<div class="table-responsive">
    <table-component id="Toma-de-muestra-table" :data="advancedSearchFilterPaginate()" sort-by="Toma-de-muestra"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="1" label="@lang('Lc Start Sampling Id')">
            <template slot-scope="row">
                @{{ formatDate(row.created_at) }}
            </template>
        </table-column>
        </table-column>
        <table-column show="sample_reception_code" label="@lang('Sample Reception Code')">
            <template slot-scope="row">
                <div v-if="row.duplicado_cloro == 'No'">
                    @{{ row.sample_reception_code }}
                </div>
                <div v-else  style="background-color:rgb(221, 214, 214) ">
                    @{{ row.sample_reception_code }}
                </div>
                
            </template>
        </table-column>
        <table-column show="lc_sample_points.point_location" label="@lang('Address')"></table-column>
        <table-column show="user_name" label="@lang('Lc Officials Id')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                <div v-if="row.duplicado_cloro == 'No'">
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-Toma-de-muestra"
                        data-toggle="modal" class="btn btn-white btn-icon btn-md m-2" data-toggle="tooltip"
                        data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>

                    <button @click="edit(row)" data-target="#modal-informatio-code-qr" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md m-2" data-toggle="tooltip" data-placement="top"
                        title="Informacion de QR">
                        <i class="fas fa-info-circle"></i>
                    </button>

                    <button @click="show(row)" data-target="#modal-view-qr" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md m-2" data-toggle="tooltip" data-placement="top"
                        title="Ver codigo QR">
                        <i class="fas fa-qrcode"></i>
                    </button>

                    <button @click="show(row)" data-target="#modal-view-Toma-de-muestra" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md m-2" data-toggle="tooltip" data-placement="top"
                        title="@lang('see_details')">
                        <i class="fa fa-search"></i>
                    </button>

                    <button @click="show(row)" data-target="#modal-history-sample-taking" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md m-2" data-toggle="tooltip" data-placement="top"
                        title="Historial">
                        <i class="fa fa-history"></i>
                    </button>

                </div>

            </template>
        </table-column>
    </table-component>
</div>
