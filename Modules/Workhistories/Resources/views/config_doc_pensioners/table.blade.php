<table class="table  table-hover m-b-0" id="configurationDocuments-table">
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
        <tr v-for="(configurationDocuments, key) in advancedSearchFilterPaginate()" :key="key">
            <td>@{{ getIndexItem(key) }}</td>
            <td>@{{ configurationDocuments.name }}</td>
            <td>@{{ configurationDocuments.description }}</td>
            <td><p v-if="configurationDocuments.state==1">Activo</p><p v-else>Inactivo</p></td>

            <td>
                <button @click="edit(configurationDocuments)" data-backdrop="static" data-target="#modal-form-config-doc-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('crud.edit')"><i class="fas fa-pencil-alt"></i></button>
                <button @click="show(configurationDocuments)" data-target="#modal-view-config-doc-pensioners" data-toggle="modal" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('see_details')"><i class="fa fa-search"></i></button>
                <button @click="drop(configurationDocuments[customId])" class="btn btn-white btn-icon btn-md" data-toggle="tooltip" data-placement="top" title="@lang('drop')"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    </tbody>
</table>
