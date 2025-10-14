<div class="table-responsive">
    <table-component id="evaluationEvaluateds-table" :data="advancedSearchFilterPaginate()" sort-by="evaluationEvaluateds"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="false" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="type_evaluation" label="@lang('Type Evaluation')"></table-column>
        <table-column show="evaluation_name" label="@lang('Evaluation Name')"></table-column>
        <table-column show="evaluator" label="@lang('Funcionario Evaluador')">
            <template slot-scope="row">
                <p>@{{ row.evaluator ? row.evaluator.name : '' }}</p>
            </template>
        </table-column>
        <table-column show="evaluation_start_date" label="@lang('Fecha y hora inicio evaluación')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.evaluation_start_date)  +  " " + row.evaluation_start_time }}</p>
            </template>
        </table-column>
        <table-column show="evaluation_end_date" label="@lang('Fecha y hora fin evaluación')">
            <template slot-scope="row">
                <p>@{{ formatDate(row.evaluation_end_date) + " "  + row.evaluation_end_time }}</p>
            </template>
        </table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="show(row)" data-target="#modal-view-evaluationEvaluateds" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
