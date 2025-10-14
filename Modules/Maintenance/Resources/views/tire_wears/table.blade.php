<div class="table-responsive text-center">
    <table-component
        id="tireWears-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tireWears"
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
        <table-column show="id" label="#"></table-column>
            <table-column show="revision_date" label="@lang('Revision Date')"></table-column>
            <table-column label="@lang('Process')"><template slot-scope="row">
                <label>@{{ row . tire_informations ? row . tire_informations . name_dependencias : null }}</label></template>
            </table-column> 
            <table-column label="@lang('Name of the equipment or machinery')"><template slot-scope="row">
                <label>@{{ row . tire_informations ? row . tire_informations . name_machinery : null }}</label></template>
            </table-column>
            <table-column label="@lang('Plaque')"><template slot-scope="row">
                <label>@{{ row . tire_informations ? row . tire_informations . plaque : null }}</label></template>
            </table-column>
            <table-column show="revision_mileage" label="@lang('Revision Mileage')">
                <template slot-scope="row">
                    <label>@{{ currencyFormat(row.revision_mileage) }} km</label>
                </template>
            </table-column>
            <table-column show="wear_total" label="@lang('Wear Total')"></table-column>
            <table-column show="revision_pressure" label="@lang('Revision Pressure')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                    <button @click="callFunctionComponent('FormTireWearsInformation','showModal',row)" data-backdrop="static" data-target="#modal-form-tireWears" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                @endif
                    <button @click="show(row)" data-target="#modal-view-tireWears" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
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