<div class="table-responsive">
    <table-component
        id="nvestment-technical-sheets-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="nvestment-technical-sheets"
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
        <table-column show="code_bppiepa" label="@lang('Code Bppiepa')"></table-column>
        <table-column show="validities.name" label="@lang('Validity')"></table-column>
        <table-column show="date_presentation" label="@lang('Date Presentation')"></table-column>
        <table-column show="name_projects.name" label="@lang('Name Project')"></table-column>
        <table-column show="dependencies.nombre" label="@lang('Submanagement or Direction')"></table-column>
        <table-column show="management_unit.name" label="@lang('Management Unit')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                @if(!Auth::user()->hasRole('PC Gestor de recursos') )
                <execution-from-action
                    v-if="row.state == 3"
                    :value="row.needs"
                    route="send-review-needs"
                    field-update="state"
                    value-update="3"
                    css-class="fas fa-paper-plane"
                    title="@lang('Submit a review')"
                    >
                </execution-from-action>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="edit(row)" data-backdrop="static" data-target="#modal-form-investment-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="edit(row)" data-backdrop="static" data-target="#goals-indicators" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 2: @lang('Goals and indicators')"><i class="fas fa-bullseye"></i></button>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="edit(row)" data-backdrop="static" data-target="#information-tariff-harmonization" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 3: @lang('Tariff harmonization information')"><i class="fas fa-donate"></i></button>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="edit(row)" data-backdrop="static" data-target="#environmental-impacts" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 4: @lang('Environmental impacts')"><i class="fab fa-pagelines"></i></button>


                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7 || row.state == 3" @click="callFunctionComponent('alternative-investment', 'loadInvestment', row);" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"  title="Parte 5: @lang('Alternative budget')"><i class="fas fa-file-invoice-dollar"></i></button>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="edit(row)" data-backdrop="static" data-target="#chronograms" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="Parte 6: @lang('Timelines')"><i class="far fa-calendar-alt"></i></button>

                <button v-if="row.needs.state == 1 || row.needs.state == 2 || row.needs.state == 4 || row.needs.state == 7" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif

                <button @click="show(row)" data-target="#modal-view-investment-technical-sheets" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                
            </template>
        </table-column>
    </table-component>
</div>