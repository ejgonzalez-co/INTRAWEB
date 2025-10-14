<div class="table-responsive">
    <table class="table table-hover m-b-0" id="work-groups-table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('Name')</th>
                <th>@lang('Description')</th>
                <th>@lang('State')</th>
                <th>@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(workGroup, key) in advancedSearchFilterPaginate()" :key="key">
                <td>@{{ getIndexItem(key) }}</td>
                <td>@{{ workGroup.name }}</td>
                <td>@{{ workGroup.description }}</td>
                <td>@{{ (workGroup.state) ?  '@lang('active')' : '@lang('inactive')'  }}</td>
                <td>
                    <button @click="edit(workGroup)" data-backdrop="static" data-target="#modal-form-work-groups" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                    <button @click="show(workGroup)" data-target="#modal-view-work-groups" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                    <button @click="drop(workGroup[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
