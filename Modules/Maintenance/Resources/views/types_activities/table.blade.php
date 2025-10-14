<table class="table table-hover m-b-0" id="types-activities-table">
    <thead>
        <tr>
            <th>#</th>
            <th>@lang('Name')</th>
            <th>@lang('Description')</th>
            <th>@lang('crud.action')</th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="(typesActivity, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ typesActivity.name }}</td>
            <td>@{{ typesActivity.description }}</td>
            <td>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="edit(typesActivity)" data-backdrop="static" data-target="#modal-form-types-activities" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                @endif
                <button @click="show(typesActivity)" data-target="#modal-view-types-activities" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                @if(Auth::user()->hasRole('Administrador de mantenimientos') || Auth::user()->hasRole('mant Operador apoyo administrativo'))
                <button @click="drop(typesActivity[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
                @endif
            </td>
        </tr>
    </tbody>
</table>
