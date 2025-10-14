<div class="table-responsive">
    <table-component id="improvementOpportunities-table" :data="advancedSearchFilterPaginate()"
        sort-by="improvementOpportunities" sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false"
        :pagination="dataPaginator" :show-caption="false" filter-placeholder="@lang('Quick filter')"
        filter-no-results="@lang('There are no matching rows')" filter-input-class="form-control col-md-4" :cache-lifetime="0">
        
        
        <table-column show="source_information_id" label="Criterio de evaluación">
            <template slot-scope="row">
                <p>@{{ row.evaluation_criteria ? row.evaluation_criteria : '' }}</p>
            </template>
        </table-column>


        <table-column show="source_information_id" label="@lang('Source Information')">
            <template slot-scope="row">
                <p>@{{ row.source_information ? row.source_information.name : '' }}</p>
            </template>
        </table-column>
        
        <table-column show="type_oportunity_improvements_id" label="@lang('Tipo de oportunidad de mejora')">
            <template slot-scope="row">
                <p>@{{ row.type_oportunity_improvements ? row.type_oportunity_improvements.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="name_opportunity_improvement" label="@lang('Nombre oportunidad de mejora')"></table-column>
        <table-column show="unit_responsible_improvement_opportunity" label="@lang('Departamento responsable')"></table-column>
        <table-column show="official_responsible" label="@lang('Funcionario responsable')"></table-column>
        <table-column show="deadline_submission" label="@lang('Fecha límite de presentación')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.deadline_submission) }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button v-if="initValues.can_manage" @click="edit(row)" data-backdrop="static" data-target="#modal-form-improvementOpportunities"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-improvementOpportunities" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button v-if="initValues.can_manage" @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
