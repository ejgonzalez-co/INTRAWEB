<div class="table-responsive">
    <table-component
        id="samplePoints-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="samplePoints"
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
        <table-column show="code" label="@lang('Code')"></table-column>
            <table-column show="point_location" label="@lang('Point Location')"></table-column>
            <table-column show="no_samples_taken" label="@lang('No Samples Taken')"></table-column>
            <table-column show="sector" label="Sector"></table-column>
            <table-column show="tank_feeding" label="@lang('Tank Feeding')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row" v-if="row.point_location !='Otro'">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-samplePoints" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-samplePoints" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
            </template>
        </table-column>
    </table-component>
</div>