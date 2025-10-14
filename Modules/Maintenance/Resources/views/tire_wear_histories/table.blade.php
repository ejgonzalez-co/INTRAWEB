<div class="table-responsive">
    <table-component
        id="tireWearHistories-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="tireWearHistories"
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
            <table-column show="user_name" label="@lang('User Name')"></table-column>
            <table-column show="action" label="@lang('Action')"></table-column>
            <table-column show="plaque" label="@lang('Plaque')"></table-column>
            <table-column show="position" label="@lang('Position')"></table-column>
            <table-column show="revision_pressure" label="@lang('Revision Pressure')"></table-column>
            <table-column label="@lang('Revision Mileage')">
                <template slot-scope="row">
                    <label>@{{ currencyFormat(row.revision_mileage) }} km</label>
                </template>
            </table-column>
            <table-column show="wear_total" label="@lang('Wear Total')"></table-column>
            
            <table-column show="observation" label="@lang('Observation')"></table-column>
            <table-column show="descripcion" label="@lang('Descripcion')"></table-column>
            <table-column show="status" label="@lang('Status')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="callFunctionComponent('input-search','sendDocuments',row.id + '-desgaste')" data-backdrop="static"
                         data-toggle="modal" class="btn btn-white btn-icon btn-md"
                        data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                        <i class="fas fa-circle"></i>
                    </button>
                
            </template>
        </table-column>
    </table-component>
</div>