<div class="table-responsive">
    <table-component
        name="documents-assets-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="documents-assets"
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

        <table-column label="#" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @{{ advancedSearchFilterPaginate().indexOf(row) + 1 }}
            </template>
        </table-column>

        <table-column show="name" label="@lang('Name')"></table-column>

        <table-column show="description" label="@lang('Description')"></table-column>

        <table-column show="url_document" label="Documento principal" cell-class="col-sm-2">
            <template slot-scope="row" :sortable="false" :filterable="false">
            <div v-if="row.url_document != ''">

                <viewer-attachement type="only-link" 
                :ref="row.name" :component-reference="row.name"
                :list="row.url_document" 
                :key="row.url_document" 
                ></viewer-attachement>
            </div>
                <div v-else>
                    <i class="fa fa-trash"></i>
                    <span>Sin documentos</span>
                </div>
            </template>
        </table-column>

        <table-column show="description" label="@lang('Activo')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <td v-if="row.form_type == 1">@{{ row.mant_resume_machinery_vehicles_yellow ? row.mant_resume_machinery_vehicles_yellow.name_vehicle_machinery : '' }}</td>
                <td v-if="row.form_type == 2">@{{ row.mant_resume_equipment_machinery ? row.mant_resume_equipment_machinery.name_equipment : '' }}</td>
                <td v-if="row.form_type == 3">@{{ row.mant_resume_equipment_machinery_leca ? row.mant_resume_equipment_machinery_leca.name_equipment_machinery : '' }}</td>
                <td v-if="row.form_type == 4">@{{ row.mant_resume_equipment_leca ? row.mant_resume_equipment_leca.name_equipment : '' }}</td>
                <td v-if="row.form_type == 5">@{{ row.mant_resume_inventory_leca ? row.mant_resume_inventory_leca.description_equipment_name : '' }}</td>
            </template>
        </table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-documents-assets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif

                <button @click="show(row)" data-target="#modal-view-documents-assets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif

            </template>
        </table-column>
    </table-component>
</div>