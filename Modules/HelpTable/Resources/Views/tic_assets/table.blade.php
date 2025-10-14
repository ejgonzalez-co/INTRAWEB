<div class="table-responsive">
    <table-component
        id="tic-assets-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tic-assets"
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
        <table-column show="created_at" label="@lang('Created_at')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                @{{ formatDate(row.created_at) }}
            </template>
        </table-column>
        <table-column show="consecutive" label="@lang('Consecutive')"></table-column>
        <table-column show="name" label="@lang('Name')"></table-column>
        <table-column show="serial" label="@lang('Serial')"></table-column>
        <table-column show="inventory_plate" label="@lang('Inventory Plate')"></table-column>
        <table-column show="tic_type_assets.name" label="@lang('Category')"></table-column>
        <table-column show="dependencias.nombre" label="@lang('Dependency')"></table-column>
        {{-- <table-column show="provider_name" label="@lang('Provider Name')"></table-column> --}}

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button  @click="callFunctionComponent('loadAssets', 'loadAssets', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                <button @click="show(row)" data-target="#modal-view-tic-assets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                <a :href="'tic-maintenances?asset_id='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('maintenances')"><i class="fas fa-tools"></i></button>
                </a>
                <a :href="'tic-asset-documents?asset='+row.id">
                    <button class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Documentos"><i class="fas fa-folder"></i></button>
                </a>
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                
            </template>
        </table-column>
    </table-component>
</div>