<div class="table-responsive">
    <table-component
        id="oil-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="oil"
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
        <table-column show="component" label="@lang('Consecutive')"></table-column>
            <table-column show="register_date" label="@lang('register_date')">
                <template slot-scope="row">
                    <label>@{{ formatDate(row.register_date) }}</label>
                </template>
            </table-column>
            <table-column show="resume_machinery_vehicles_yellow.plaque" label="Placa"></table-column>
            <table-column show="1" label="@lang('Process')">
                <template slot-scope="row">
                    @{{ row.dependencias?.nombre}} 
                </template>
            </table-column>
            <table-column show="show_type" label="@lang('show_type')"></table-column>
            <table-column show="component_name" label="@lang('Component')"></table-column>
            <table-column show="work_order" label="@lang('work_order')"></table-column>
            {{-- <table-column show="2" label="@lang('detected_value')">
                <template slot-scope="row">
                    @{{ row.oil_element_wears[0]?.detected_value}} 
                </template>
            </table-column> --}}
            {{-- <table-column show="4" label="@lang('Range')">
                <template slot-scope="row">
                    @{{ row.oil_element_wears[0]?.range}} 
                </template>
            </table-column> --}}
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                {{-- <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-oil" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button> --}}

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="callFunctionComponent('oils', 'loadOil', row);" data-backdrop="static" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif

                <button @click="show(row)" data-target="#modal-view-oil" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(row)" data-target="#modal-delete-provider-contract" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                data-placement="top" title="@lang('drop')">
                <i class="fa fa-trash"></i>
                </button>
                @endif

                <a :href="'oil-documents?mant_oils_id='+row.id" class="btn btn-white btn-icon btn-md"
                    data-placement="top" title="Agregar documentos">
                    <i class="fas fa-folder-plus"></i>
                </a>
                
            </template>
        </table-column>
    </table-component>
</div>