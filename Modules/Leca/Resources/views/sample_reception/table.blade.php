<div class="table-responsive">
    <table-component
        id="Recepcion-de-muestras-table"
        :data="dataList"
        sort-by="sampleTakings"
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
            <table-column show="sample_reception_code" label="Identificación asignada por Leca"></table-column>
            <table-column label="Fecha y hora de la toma de muestra">
                <template slot-scope="row">
                    <label>@{{ formatDate(row.created_at) }} / @{{ row.hour_from_to }}</label>
                </template>
            </table-column>
            <table-column  label="Fecha y hora de la recepción de muestra">

                <template slot-scope="row">
                    <label>@{{ formatDate(row.reception_date) }} / @{{ row.reception_hour}}</label>
                </template>

            </table-column>
            <table-column show="user_name" label="Responsable de entrega"></table-column>
            <table-column show="name_receipt" label="Responsable de la recepción"></table-column>
            <table-column label="Estado de la muestra">
                <template slot-scope="row">
                    <div v-if="row.state_receipt == '1'" class="bg-green"
                    style="text-align: center; border-radius: 5px; width: 100px;">
                    <strong>Muestra recepcionada</strong>
                    </div>

                    <div v-if="row.state_receipt == null" class="bg-blue"
                    style="text-align: center; border-radius: 5px; width: 100px;">
                    <strong>Pendiente de recepción</strong>
                    </div>
                </template>
            </table-column>
            <table-column show="is_accepted" label="¿Se acepta?"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-Recepcion-de-muestras" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-Recepcion-de-muestras" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                {{-- <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button> --}}

                <button @click="show(row)" data-target="#modal-history-sample-taking" data-toggle="modal"
                class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                title="Historial">
                <i class="fa fa-history"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>