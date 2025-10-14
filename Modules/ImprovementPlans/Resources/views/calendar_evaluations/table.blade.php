<div class="table-responsive">
    <table-component
        id="calendarEvaluations-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="calendarEvaluations"
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
        <table-column show="evaluator_id" label="@lang('Evaluator Id')"></table-column>
            <table-column show="evaluated_id" label="@lang('Evaluated Id')"></table-column>
            <table-column show="type_evaluation" label="@lang('Type Evaluation')"></table-column>
            <table-column show="evaluation_name" label="@lang('Evaluation Name')"></table-column>
            <table-column show="objective_evaluation" label="@lang('Objective Evaluation')"></table-column>
            <table-column show="evaluation_scope" label="@lang('Evaluation Scope')"></table-column>
            <table-column show="evaluation_site" label="@lang('Evaluation Site')"></table-column>
            <table-column show="evaluation_start_date" label="@lang('Evaluation Start Date')"></table-column>
            <table-column show="evaluation_start_time" label="@lang('Evaluation Start Time')"></table-column>
            <table-column show="evaluation_end_date" label="@lang('Evaluation End Date')"></table-column>
            <table-column show="evaluation_end_time" label="@lang('Evaluation End Time')"></table-column>
            <table-column show="unit_responsible_for_evaluation" label="@lang('Unit Responsible For Evaluation')"></table-column>
            <table-column show="evaluation_officer" label="@lang('Evaluation Officer')"></table-column>
            <table-column show="process" label="@lang('Process')"></table-column>
            <table-column show="attached" label="@lang('Attached')"></table-column>
            <table-column show="status" label="@lang('Status')"></table-column>
            <table-column show="evaluation_process_attachment" label="@lang('Evaluation Process Attachment')"></table-column>
            <table-column show="general_description_evaluation_results" label="@lang('General Description Evaluation Results')"></table-column>
            <table-column show="name_improvement_plan" label="@lang('Name Improvement Plan')"></table-column>
            <table-column show="is_accordance" label="@lang('Is Accordance')"></table-column>
            <table-column show="execution_percentage" label="@lang('Execution Percentage')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-calendarEvaluations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-calendarEvaluations" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>
                
            </template>
        </table-column>
    </table-component>
</div>