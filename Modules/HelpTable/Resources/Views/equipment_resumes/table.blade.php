<div class="table-responsive">
    <table-component id="equipmentResumes-table" :data="dataList" sort-by="equipmentResumes"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="id" label="@lang('#')":formatter="(value) => value || ''"></table-column>
        <table-column show="created_at" label="@lang('Created_at')":formatter="(value) => value || ''"></table-column>
        <table-column show="asset_type" label="@lang('Equip Type')":formatter="(value) => value || ''"></table-column>
        <table-column show="officer" label="@lang('Officer')" :formatter="(value) => value || ''"></table-column>
        <table-column show="dependencias.nombre" label="@lang('Dependence')":formatter="(value) => value || ''"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-equipmentResumes"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-equipmentResumes" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <a :href="'tic-maintenances-equipment?equipmentId=' + row.id">
                    <button title="@lang('Historial cambios')" class="btn btn-white btn-icon btn-md">
                        <i class="fas fa-cogs"></i>
                    </button>
                </a>

                <a :href="'equipment-resume-documents?equipment_resume_id=' + row.id">
                    <button title="@lang('Documentos')" class="btn btn-white btn-icon btn-md">
                        <i class="fas fa-folder"></i>
                    </button>
                </a>

                <button @click="show(row)" data-target="#modal-view-equipment-resume-history" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="Historial">
                    <i class="fa fa-history"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
