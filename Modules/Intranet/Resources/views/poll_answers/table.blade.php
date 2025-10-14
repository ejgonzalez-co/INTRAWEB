<div class="table-responsive">
    <table-component
        id="pollAnswers-table"
        :data="advancedSearchFilterPaginate()"
        sort-by="pollAnswers"
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
            <table-column show="intranet_polls_questions.question" label="Pregunta"></table-column>
            <table-column show="users_name" label="Funcionario"></table-column>
            <table-column show="answer" label="@lang('Answer')"></table-column>

    
    </table-component>
</div>