<table class="table table-hover m-b-0" id="newsHistories-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Created_at')</th>
            <th>@lang('Username')</th>
            <th>@lang('Observation')</th>
            <th class="w-25">@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(newsHistories, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ newsHistories.created_at }}</td>
            <td>@{{ newsHistories.users_name }}</td>
            <td>@{{ newsHistories.new }}</td>
            <td>
                <button @click="edit(newsHistories)" data-backdrop="static" data-target="#modal-form-newsHistories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <!--<button @click="show(newsHistories)" data-target="#modal-view-newsHistories" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>-->
                <button @click="drop(newsHistories[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
