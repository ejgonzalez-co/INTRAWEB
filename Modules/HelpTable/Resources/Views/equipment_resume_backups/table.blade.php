<div class="table-responsive">
    <table-component id="equipmentResumeBackups-table" :data="advancedSearchFilterPaginate()"
        sort-by="equipmentResumeBackups" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="consecutive" label="@lang('#')"></table-column>
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="asset_type" label="@lang('Equip Type')"></table-column>
        <table-column show="provider" label="@lang('Proveedor')"></table-column>
        <table-column show="maintenance_date" label="@lang('Maintenance Date')">
            <template slot-scope="row">
                <p>@{{ row.maintenance_date ? formatDate(row.maintenance_date) : "N/E" }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-equipmentResumeBackups" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <a :href="'export-equipment-resume-history/' + row.id" target="_blank">
                    <button title="@lang('Descargar hoja de vida')" class="btn btn-white btn-icon btn-md">
                        <i class="fas fa-file-excel"></i>
                    </button>
                </a>

            </template>
        </table-column>
    </table-component>
</div>
