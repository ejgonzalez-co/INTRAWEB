<div class="table-responsive">
    <table-component id="reportManagements-table" :data="advancedSearchFilterPaginate()" sort-by="reportManagements"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">

        
        {{-- <table-column show="lc_sample_taking_id" label="@lang('id toma muestra')"></table-column>

        <table-column  label="PROXIMO CONSECUTIVO">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div >
                    @{{ row.nex_consecutiveIC }}
                </div>
                <div >
                  
                </div>
                
            </template>
        </table-column> --}}



        <table-column show="consecutive" label="@lang('Consecutivo')"></table-column>
        <table-column show="name_customer" label="@lang('Cliente')"></table-column>
        <table-column label="@lang('Fecha del informe')">
            <template slot-scope="row">
                @{{ formatDate(row.date_report) }}
            </template>
        </table-column>
        <table-column show="lc_customers.query_report" label="@lang('Medio de entrega del resultado')"></table-column>

        <table-column show="status" label="Estado">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div v-if="row.status == 'Informe pendiente.'" style="background-color: #FFE341; color: rgb(8, 1, 1)">
                    @{{ row . status }}
                </div>
                <div v-if="row.status  == 'Informe parcial.'"
                    style="background-color: #FD9531">
                    @{{ row . status }}
                </div>
                <div v-if="row.status == 'Informe finalizado.'" style="background-color: #A3DA77; color: white">
                    @{{ row . status }}
                </div>
            </template>
        </table-column>


        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">


                
                {{-- Acción par descargar formato r-56 --}}
                <a v-if="row.lc_customers.query_report ==='Email' ||  row.lc_customers.query_report === 'Email-Físico'"
                    :href="'generate-r/56,' + row.id" target="_blank" class="btn btn-white btn-icon btn-md"
                    data-placement="top" title="R-056">
                    <i class="fa fa-solid fa-file-excel" style="color:#0C7CD5"></i>
                </a>
                {{-- Acción par descargar formato r-55 --}}
                <a v-if="row.lc_customers.query_report ==='Físico' ||  row.lc_customers.query_report ==='Email-Físico'"
                    :href="'generate-r/55,' + row.id" target="_blank" class="btn btn-white btn-icon btn-md"
                    data-placement="top" title="R-055">
                    <i class="fa fa-file-excel"> </i>
                </a>

                {{-- Acción de adjuntar --}}
                <a :href="'repor-management-attachments?report_id=' + row.id" class="btn btn-white btn-icon btn-md"
                    data-placement="top" title="Agregar anexos">
                    <i class="fas fa-folder-plus"></i>
                </a>

                {{-- Acción par cambiar estado --}}  
                <button v-if="row.status !='Informe finalizado.'"
                    @click="edit(row)" data-backdrop="static" data-target="#modal-form-report-managements" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fa fa-lock"></i>
                </button>

                <!-- icono Historial que se muestra en las filas de la tabla. -->
                <button @click="show(row)" data-target="#modal-history" data-toggle="modal"
                        class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                        title="Historial">
                        <i class="fa fa-history"></i>
                    </button>







            </template>
        </table-column>
    </table-component>
</div>
