<div class="table-responsive">
    <table-component
        id="startSamplings-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="startSamplings"
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
            <table-column show="1" label="@lang('Sampling Date')">
                <template slot-scope="row">
                    @{{ formatDate(row.created_at) }}
                </template></table-column>
                <table-column show="type_customer" label="Tipo cliente"></table-column>
            <table-column show="user_name" label="@lang('responsible for taking')"></table-column>
            <table-column show="vehicle_arrival_time" label="@lang('Vehicle Arrival Time')"></table-column>
            <table-column show="service_agreement" label="@lang('Service Agreement')"></table-column>
            <table-column show="sample_request" label="@lang('Sample Request')"></table-column>
            
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-startSamplings" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <a :href="'sample-takings?lc_start_sampling_id='+row.id" class="btn btn-white btn-icon btn-md"
                data-placement="top" title="Muestra">
                <i class="fas fa-tint"></i>
                </a>

                <button   @click="edit(row)" data-target="#modal-information-finish" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Informacion final">
                <i class="fas fa-info"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-startSamplings" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <a  v-if="row.has_sample_taking && row.date_last_ph_adjustment!=null && row.type_customer == 'Distribucion'" :href="'get-format-pdf/' + row.id"
                class="btn btn-white btn-icon btn-md" target="_blank" data-toggle="tooltip" data-placement="top"
                title="Ver documento"> <i class="fas fa-file-pdf"></i></a>

                <a  v-if="row.has_sample_taking && row.type_customer == 'Captacion'" :href="'get-format-pdf/' + row.id"
                    class="btn btn-white btn-icon btn-md" target="_blank" data-toggle="tooltip" data-placement="top"
                    title="Ver documento"> <i class="fas fa-file-pdf"></i></a>

                <button @click="show(row)" data-target="#modal-history-start-sampling" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}
                
            </template>
        </table-column>
    </table-component>
</div>