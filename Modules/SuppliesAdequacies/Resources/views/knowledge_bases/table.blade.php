<div class="table-responsive">
    <table-component id="knowledgeBases-table" :data="advancedSearchFilterPaginate()" sort-by="knowledgeBases"
        sort-order="asc" table-class="table table-hover m-b-0" :show-filter="true" :pagination="dataPaginator"
        :show-caption="false" filter-placeholder="@lang('Quick filter')" filter-no-results="@lang('There are no matching rows')"
        filter-input-class="form-control col-md-4" :cache-lifetime="0">
        <table-column show="created_at" label="@lang('Created_at')"></table-column>
        <table-column show="knowledge_type" label="@lang('Knowledge Type')"></table-column>
        <table-column show="knowledge_type" label="@lang('Registered by')">
            <template slot-scope="row" :sortable="false" :filterable="false">
                <div class="p-2">@{{ row.user_creator ? row.user_creator.name : "N/E" }}</div>
            </template>
        </table-column>
        <table-column show="subject_knowledge" label="@lang('Subject')"></table-column>
        <table-column label="@lang('crud.action')" :sortable="false" :filterable="false">
            <template slot-scope="row">

                <button @click="edit(row)" data-backdrop="static" data-target="#modal-form-knowledgeBases"
                    data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('crud.edit')">
                    <i class="fas fa-pencil-alt"></i>
                </button>

                <button @click="show(row)" data-target="#modal-view-knowledgeBases" data-toggle="modal"
                    class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top"
                    title="@lang('see_details')">
                    <i class="fa fa-search"></i>
                </button>

                <button @click="drop(row[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip"
                    data-placement="top" title="@lang('drop')">
                    <i class="fa fa-trash"></i>
                </button>

            </template>
        </table-column>
    </table-component>
</div>
