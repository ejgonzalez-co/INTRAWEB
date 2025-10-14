<table class="table table-hover m-b-0" id="observation-table">
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
        <tr v-for="(observation, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ observation.created_at }}</td>
            <td>@{{ observation.users_name }}</td>
            <td>@{{ observation.new }}</td>
            <td>
                <button @click="edit(observation)" data-backdrop="static" data-target="#modal-form-news-histories-pen" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <!--<button @click="show(observation)" data-target="#modal-view-observation" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>-->
                <button @click="drop(observation[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
