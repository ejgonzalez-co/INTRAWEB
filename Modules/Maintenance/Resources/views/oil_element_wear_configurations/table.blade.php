<div class="table-responsive">
    <table-component
        id="oilElementWearConfigurations-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="oilElementWearConfigurations"
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
        <table-column show="1" label="@lang('register_date')">
            <template slot-scope="row">
                @{{ formatDate(row.created_at)}}
            </template>

        </table-column>
            <table-column show="element_name" label="@lang('element_name')"></table-column>
            <table-column show="1" label="Grupo">
                <template slot-scope="row">
                    @{{ row.group ? row.group : 'N/A' }} 
                </template>
            </table-column>
            <table-column show="1" label="Componente">
                <template slot-scope="row">
                    @{{ row.component ? row.component : 'N/A' }} 
                </template>
            </table-column>
            <table-column show="rank_higher" label="@lang('rank_higher')"></table-column>
            <table-column show="rank_lower" label="@lang('rank_lower')"></table-column>

        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-oil-element-wear-configurations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                @endif
                <button @click="show(row)" data-target="#modal-view-oilElementWearConfigurations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                @endif
            </template>
        </table-column>
    </table-component>
</div>