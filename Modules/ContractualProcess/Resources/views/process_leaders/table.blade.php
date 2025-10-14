<table class="table table-hover m-b-0" id="process-leaders-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name Process')</th>
            <th>@lang('Leader Name')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(processLeaders, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ processLeaders.name_process }}</td>
            <td>@{{ processLeaders.leader_name }}</td>
            <td>
                <button @click="edit(processLeaders)" data-backdrop="static" data-target="#modal-form-process-leaders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(processLeaders)" data-target="#modal-view-process-leaders" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(processLeaders[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
