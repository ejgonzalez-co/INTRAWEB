<table class="table table-hover m-b-0" id="families-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Type')</th>
            <th>@lang('Name')</th>
            <th>@lang('Gender')</th>
            <th>@lang('Birth Date') (YYYY-MM-DD)</th>
            <th>@lang('State')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(family, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ family.type }}</td>
            <td>@{{ family.name }}</td>
            <td>@{{ family.gender }}</td>
            <td>@{{ family.birth_date }}</td>
            <td>@{{ family.state }}</td>
            <td>
                <button @click="edit(family)" data-backdrop="static" data-target="#modal-form-family" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(family)" data-target="#modal-view-family" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(family[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
