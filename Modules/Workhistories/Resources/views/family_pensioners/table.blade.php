<table class="table table-hover m-b-0" id="family-pensioners-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name')</th>
            <th>@lang('Gender')</th>
            <th>@lang('Birth Date')</th>
            <th>@lang('Type')</th>
            <th>@lang('State')</th>
       
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(familyPensioner, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ familyPensioner.name }}</td>
            <td>@{{ familyPensioner.gender }}</td>
            <td>@{{ familyPensioner.birth_date }}</td>
            <td>@{{ familyPensioner.type }}</td>
            <td>@{{ familyPensioner.state }}</td>
     
            <td>
                <button @click="edit(familyPensioner)" data-backdrop="static" data-target="#modal-form-family-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(familyPensioner)" data-target="#modal-view-family-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(familyPensioner[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
