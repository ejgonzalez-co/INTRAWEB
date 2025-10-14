<table class="table table-hover m-b-0" id="quotaPartsNewsUsers-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('New')</th>
        <th>@lang('Type Document')</th>
        <th>@lang('Users Name')</th>
        <th>@lang('Users Id')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(quotaPartsNewsUsers, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ quotaPartsNewsUsers.new }}</td>
            <td>@{{ quotaPartsNewsUsers.type_document }}</td>
            <td>@{{ quotaPartsNewsUsers.users_name }}</td>
            <td>@{{ quotaPartsNewsUsers.users_id }}</td>
            <td>
                <button @click="edit(quotaPartsNewsUsers)" data-backdrop="static" data-target="#modal-form-quotaPartsNewsUsers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(quotaPartsNewsUsers)" data-target="#modal-view-quotaPartsNewsUsers" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(quotaPartsNewsUsers[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
